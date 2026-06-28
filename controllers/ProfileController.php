<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../config/database.php';

class ProfileController extends BaseController {

    public function index() {
        if (!$this->isLoggedIn()) {
            $this->redirect('/account/login');
        }

        $userId = $_SESSION['UserId'];
        $db = Database::getConnection();

        // Fetch user info
        $userStmt = $db->prepare("SELECT * FROM users WHERE id = ?");
        $userStmt->execute([$userId]);
        $user = $userStmt->fetch();

        if (!$user) {
            $this->redirect('/account/logout');
        }

        // Fetch counts
        $favCountStmt = $db->prepare("SELECT COUNT(*) FROM user_favorites WHERE user_id = ?");
        $favCountStmt->execute([$userId]);
        $favCount = $favCountStmt->fetchColumn();

        $bookCountStmt = $db->prepare("SELECT COUNT(*) FROM user_bookmarks WHERE user_id = ?");
        $bookCountStmt->execute([$userId]);
        $bookCount = $bookCountStmt->fetchColumn();

        // Determine active tab
        $activeTab = $_GET['tab'] ?? 'profile';
        $validTabs = ['profile', 'favorites', 'bookmarks', 'password'];
        if (!in_array($activeTab, $validTabs)) {
            $activeTab = 'profile';
        }

        // Pagination for favorites
        $favPage = isset($_GET['fav_page']) ? (int)$_GET['fav_page'] : 1;
        if ($favPage < 1) $favPage = 1;
        $pageSize = 4; // 4 cards per page for cleaner dashboard layout
        $favOffset = ($favPage - 1) * $pageSize;
        $favTotalPages = (int)ceil($favCount / $pageSize);
        if ($favTotalPages < 1) $favTotalPages = 1;

        $favStmt = $db->prepare("
            SELECT c.*, cat.name as category_name, cat.slug as category_slug 
            FROM user_favorites uf 
            JOIN coupons c ON uf.coupon_id = c.id 
            LEFT JOIN categories cat ON c.category_id = cat.id
            WHERE uf.user_id = ? AND c.is_active = 1
            LIMIT ? OFFSET ?
        ");
        $favStmt->bindValue(1, $userId, PDO::PARAM_INT);
        $favStmt->bindValue(2, $pageSize, PDO::PARAM_INT);
        $favStmt->bindValue(3, $favOffset, PDO::PARAM_INT);
        $favStmt->execute();
        $favoriteCoupons = $favStmt->fetchAll();

        // Pagination for bookmarks
        $bookPage = isset($_GET['book_page']) ? (int)$_GET['book_page'] : 1;
        if ($bookPage < 1) $bookPage = 1;
        $bookOffset = ($bookPage - 1) * $pageSize;
        $bookTotalPages = (int)ceil($bookCount / $pageSize);
        if ($bookTotalPages < 1) $bookTotalPages = 1;

        $bookStmt = $db->prepare("
            SELECT c.*, cat.name as category_name, cat.slug as category_slug 
            FROM user_bookmarks ub 
            JOIN coupons c ON ub.coupon_id = c.id 
            LEFT JOIN categories cat ON c.category_id = cat.id
            WHERE ub.user_id = ? AND c.is_active = 1
            LIMIT ? OFFSET ?
        ");
        $bookStmt->bindValue(1, $userId, PDO::PARAM_INT);
        $bookStmt->bindValue(2, $pageSize, PDO::PARAM_INT);
        $bookStmt->bindValue(3, $bookOffset, PDO::PARAM_INT);
        $bookStmt->execute();
        $bookmarkCoupons = $bookStmt->fetchAll();

        // Load active favorites/bookmarks for action bars on cards
        $userFavorites = [];
        $userBookmarks = [];
        $favListStmt = $db->prepare("SELECT coupon_id FROM user_favorites WHERE user_id = ?");
        $favListStmt->execute([$userId]);
        $userFavorites = $favListStmt->fetchAll(PDO::FETCH_COLUMN);

        $bookListStmt = $db->prepare("SELECT coupon_id FROM user_bookmarks WHERE user_id = ?");
        $bookListStmt->execute([$userId]);
        $userBookmarks = $bookListStmt->fetchAll(PDO::FETCH_COLUMN);

        // Flash messages
        $successMsg = $_SESSION['profile_success'] ?? null;
        $errorMsg = $_SESSION['profile_error'] ?? null;
        unset($_SESSION['profile_success'], $_SESSION['profile_error']);

        $this->renderView('account/profile', [
            'user' => $user,
            'favCount' => $favCount,
            'bookCount' => $bookCount,
            'activeTab' => $activeTab,
            'favoriteCoupons' => $favoriteCoupons,
            'bookmarkCoupons' => $bookmarkCoupons,
            'favPage' => $favPage,
            'favTotalPages' => $favTotalPages,
            'bookPage' => $bookPage,
            'bookTotalPages' => $bookTotalPages,
            'UserFavorites' => $userFavorites,
            'UserBookmarks' => $userBookmarks,
            'successMsg' => $successMsg,
            'errorMsg' => $errorMsg
        ]);
    }

    public function update() {
        if (!$this->isLoggedIn() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/profile');
        }

        $userId = $_SESSION['UserId'];
        $fullName = trim($_POST['FullName'] ?? '');
        $email = trim($_POST['Email'] ?? '');
        $phone = trim($_POST['Phone'] ?? '');
        $birthday = trim($_POST['Birthday'] ?? '');
        $gender = trim($_POST['Gender'] ?? '');

        if (empty($fullName) || empty($email)) {
            $_SESSION['profile_error'] = 'Vui lòng điền đầy đủ Họ tên và Email.';
            $this->redirect('/profile?tab=profile');
        }

        // Validate email duplicate
        $db = Database::getConnection();
        $emailStmt = $db->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $emailStmt->execute([$email, $userId]);
        if ($emailStmt->fetch()) {
            $_SESSION['profile_error'] = 'Email này đã được sử dụng bởi tài khoản khác.';
            $this->redirect('/profile?tab=profile');
        }

        // Validate phone format (basic 10-11 digit check)
        if (!empty($phone) && !preg_match('/^[0-9]{10,11}$/', $phone)) {
            $_SESSION['profile_error'] = 'Số điện thoại không đúng định dạng.';
            $this->redirect('/profile?tab=profile');
        }

        $stmt = $db->prepare("UPDATE users SET fullname = ?, email = ?, phone = ?, birthday = ?, gender = ? WHERE id = ?");
        $stmt->execute([$fullName, $email, !empty($phone) ? $phone : null, !empty($birthday) ? $birthday : null, !empty($gender) ? $gender : null, $userId]);

        $_SESSION['profile_success'] = 'Cập nhật thông tin cá nhân thành công.';
        $this->redirect('/profile?tab=profile');
    }

    public function avatar() {
        if (!$this->isLoggedIn() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/profile');
        }

        $userId = $_SESSION['UserId'];
        if (!isset($_FILES['Avatar']) || $_FILES['Avatar']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['profile_error'] = 'Không tìm thấy file upload hoặc có lỗi xảy ra.';
            $this->redirect('/profile?tab=profile');
        }

        $file = $_FILES['Avatar'];
        $maxSize = 2 * 1024 * 1024; // 2MB
        if ($file['size'] > $maxSize) {
            $_SESSION['profile_error'] = 'File ảnh không được vượt quá 2MB.';
            $this->redirect('/profile?tab=profile');
        }

        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
        if (!in_array($file['type'], $allowedTypes)) {
            $_SESSION['profile_error'] = 'Chỉ chấp nhận định dạng JPG, PNG hoặc WEBP.';
            $this->redirect('/profile?tab=profile');
        }

        // Create upload directory if not exists
        $uploadDir = ROOT_PATH . '/public/uploads/avatars';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileName = 'avatar_' . $userId . '_' . time() . '.' . $ext;
        $destPath = $uploadDir . '/' . $fileName;

        if (move_uploaded_file($file['tmp_name'], $destPath)) {
            // Update database
            $db = Database::getConnection();
            $avatarUrl = '/uploads/avatars/' . $fileName;
            $stmt = $db->prepare("UPDATE users SET avatar = ? WHERE id = ?");
            $stmt->execute([$avatarUrl, $userId]);

            $_SESSION['profile_success'] = 'Cập nhật ảnh đại diện thành công.';
        } else {
            $_SESSION['profile_error'] = 'Có lỗi xảy ra khi lưu trữ file ảnh.';
        }

        $this->redirect('/profile?tab=profile');
    }

    public function password() {
        if (!$this->isLoggedIn() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/profile');
        }

        $userId = $_SESSION['UserId'];
        $oldPassword = $_POST['OldPassword'] ?? '';
        $newPassword = $_POST['NewPassword'] ?? '';
        $confirmPassword = $_POST['ConfirmPassword'] ?? '';

        if (empty($oldPassword) || empty($newPassword) || empty($confirmPassword)) {
            $_SESSION['profile_error'] = 'Vui lòng điền đầy đủ thông tin đổi mật khẩu.';
            $this->redirect('/profile?tab=password');
        }

        if ($newPassword !== $confirmPassword) {
            $_SESSION['profile_error'] = 'Mật khẩu mới và xác nhận mật khẩu không trùng khớp.';
            $this->redirect('/profile?tab=password');
        }

        if (strlen($newPassword) < 6) {
            $_SESSION['profile_error'] = 'Mật khẩu mới phải có ít nhất 6 ký tự.';
            $this->redirect('/profile?tab=password');
        }

        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT password_hash FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch();

        $oldPasswordHash = base64_encode(hash('sha256', $oldPassword, true));
        if ($user['password_hash'] !== $oldPasswordHash) {
            $_SESSION['profile_error'] = 'Mật khẩu cũ không chính xác.';
            $this->redirect('/profile?tab=password');
        }

        $newPasswordHash = base64_encode(hash('sha256', $newPassword, true));
        $updateStmt = $db->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
        $updateStmt->execute([$newPasswordHash, $userId]);

        $_SESSION['profile_success'] = 'Đổi mật khẩu thành công.';
        $this->redirect('/profile?tab=password');
    }
}
