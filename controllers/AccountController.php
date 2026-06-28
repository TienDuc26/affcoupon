<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/User.php';

class AccountController extends BaseController {

    public function login() {
        if ($this->isLoggedIn()) {
            if ($this->isAdmin()) {
                $this->redirect('/admin/dashboardadmin');
            } else {
                $this->redirect('/');
            }
        }

        $error = null;
        $username = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['Username'] ?? '');
            $password = $_POST['Password'] ?? '';

            if (empty($username) || empty($password)) {
                $error = "Vui lòng nhập đầy đủ thông tin";
            } else {
                $passwordHash = $this->hashPassword($password);
                
                $userModel = new User();
                $user = $userModel->authenticate($username, $passwordHash);

                if ($user) {
                    // Start session & store info
                    $_SESSION['UserId'] = $user['id'];
                    $_SESSION['Username'] = $user['username'];
                    $_SESSION['RoleId'] = $user['role_id'];

                    if ($user['role_id'] == 1) {
                        $this->redirect('/admin/dashboardadmin');
                    } else {
                        $this->redirect('/');
                    }
                } else {
                    $error = "Sai tài khoản hoặc mật khẩu";
                }
            }
        }

        $this->renderView('account/login', [
            'error' => $error,
            'Username' => $username
        ], false);
    }

    public function register() {
        if ($this->isLoggedIn()) {
            $this->redirect('/');
        }

        $error = null;
        $username = '';
        $email = '';
        $fullName = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['Username'] ?? '');
            $email = trim($_POST['Email'] ?? '');
            $password = $_POST['Password'] ?? '';
            $fullName = trim($_POST['FullName'] ?? '');

            if (empty($username) || empty($email) || empty($password)) {
                $error = "Vui lòng nhập đầy đủ các trường bắt buộc";
            } else {
                $userModel = new User();
                
                // check username/email exists
                if ($userModel->usernameOrEmailExists($username, $email)) {
                    $error = "Username hoặc Email đã tồn tại";
                } else {
                    $passwordHash = $this->hashPassword($password);
                    
                    // role_id = 2 (regular user)
                    $success = $userModel->add($username, $email, $passwordHash, $fullName, 2, true);

                    if ($success) {
                        $this->redirect('/account/login');
                    } else {
                        $error = "Có lỗi xảy ra khi đăng ký tài khoản";
                    }
                }
            }
        }

        $this->renderView('account/register', [
            'error' => $error,
            'Username' => $username,
            'Email' => $email,
            'FullName' => $fullName
        ], false);
    }

    public function logout() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        session_unset();
        session_destroy();
        $this->redirect('/');
    }

    public function favorites() {
        if (!$this->isLoggedIn()) {
            $this->redirect('/account/login');
        }

        $userId = $_SESSION['UserId'];
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;
        $pageSize = 8;
        $offset = ($page - 1) * $pageSize;

        require_once __DIR__ . '/../config/database.php';
        $db = Database::getConnection();
        
        $countStmt = $db->prepare("SELECT COUNT(*) FROM user_favorites WHERE user_id = ?");
        $countStmt->execute([$userId]);
        $totalItems = $countStmt->fetchColumn();
        
        $totalPages = (int)ceil($totalItems / $pageSize);
        if ($totalPages < 1) $totalPages = 1;

        $stmt = $db->prepare("
            SELECT c.*, cat.name as category_name, cat.slug as category_slug 
            FROM user_favorites uf 
            JOIN coupons c ON uf.coupon_id = c.id 
            LEFT JOIN categories cat ON c.category_id = cat.id
            WHERE uf.user_id = ? AND c.is_active = 1
            LIMIT ? OFFSET ?
        ");
        $stmt->bindValue(1, $userId, PDO::PARAM_INT);
        $stmt->bindValue(2, $pageSize, PDO::PARAM_INT);
        $stmt->bindValue(3, $offset, PDO::PARAM_INT);
        $stmt->execute();
        $coupons = $stmt->fetchAll();

        $userFavorites = [];
        $userBookmarks = [];
        $favStmt = $db->prepare("SELECT coupon_id FROM user_favorites WHERE user_id = ?");
        $favStmt->execute([$userId]);
        $userFavorites = $favStmt->fetchAll(PDO::FETCH_COLUMN);

        $bookStmt = $db->prepare("SELECT coupon_id FROM user_bookmarks WHERE user_id = ?");
        $bookStmt->execute([$userId]);
        $userBookmarks = $bookStmt->fetchAll(PDO::FETCH_COLUMN);

        $this->renderView('account/favorites', [
            'Coupons' => $coupons,
            'CurrentPage' => $page,
            'TotalPages' => $totalPages,
            'UserFavorites' => $userFavorites,
            'UserBookmarks' => $userBookmarks
        ]);
    }

    public function bookmarks() {
        if (!$this->isLoggedIn()) {
            $this->redirect('/account/login');
        }

        $userId = $_SESSION['UserId'];
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;
        $pageSize = 8;
        $offset = ($page - 1) * $pageSize;

        require_once __DIR__ . '/../config/database.php';
        $db = Database::getConnection();
        
        $countStmt = $db->prepare("SELECT COUNT(*) FROM user_bookmarks WHERE user_id = ?");
        $countStmt->execute([$userId]);
        $totalItems = $countStmt->fetchColumn();
        
        $totalPages = (int)ceil($totalItems / $pageSize);
        if ($totalPages < 1) $totalPages = 1;

        $stmt = $db->prepare("
            SELECT c.*, cat.name as category_name, cat.slug as category_slug 
            FROM user_bookmarks ub 
            JOIN coupons c ON ub.coupon_id = c.id 
            LEFT JOIN categories cat ON c.category_id = cat.id
            WHERE ub.user_id = ? AND c.is_active = 1
            LIMIT ? OFFSET ?
        ");
        $stmt->bindValue(1, $userId, PDO::PARAM_INT);
        $stmt->bindValue(2, $pageSize, PDO::PARAM_INT);
        $stmt->bindValue(3, $offset, PDO::PARAM_INT);
        $stmt->execute();
        $coupons = $stmt->fetchAll();

        $userFavorites = [];
        $userBookmarks = [];
        $favStmt = $db->prepare("SELECT coupon_id FROM user_favorites WHERE user_id = ?");
        $favStmt->execute([$userId]);
        $userFavorites = $favStmt->fetchAll(PDO::FETCH_COLUMN);

        $bookStmt = $db->prepare("SELECT coupon_id FROM user_bookmarks WHERE user_id = ?");
        $bookStmt->execute([$userId]);
        $userBookmarks = $bookStmt->fetchAll(PDO::FETCH_COLUMN);

        $this->renderView('account/bookmarks', [
            'Coupons' => $coupons,
            'CurrentPage' => $page,
            'TotalPages' => $totalPages,
            'UserFavorites' => $userFavorites,
            'UserBookmarks' => $userBookmarks
        ]);
    }

    /**
     * Hashes password using SHA256 encoded in Base64 (equivalent to C# HashPassword)
     */
    private function hashPassword($password) {
        return base64_encode(hash('sha256', $password, true));
    }
}
