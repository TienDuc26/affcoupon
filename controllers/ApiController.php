<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../config/database.php';

class ApiController extends BaseController {

    private function sendJson($data) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
        exit;
    }

    public function favoritetoggle() {
        if (!isset($_SESSION['UserId'])) {
            $this->sendJson([
                'success' => false,
                'message' => 'Bạn cần đăng nhập để sử dụng tính năng này.',
                'code' => 'UNAUTHORIZED'
            ]);
        }

        $userId = $_SESSION['UserId'];
        $couponId = isset($_POST['coupon_id']) ? (int)$_POST['coupon_id'] : null;
        if (!$couponId) {
            $input = json_decode(file_get_contents('php://input'), true);
            $couponId = isset($input['coupon_id']) ? (int)$input['coupon_id'] : null;
        }

        if (!$couponId) {
            $this->sendJson(['success' => false, 'message' => 'Mã giảm giá không hợp lệ.']);
        }

        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT id FROM user_favorites WHERE user_id = ? AND coupon_id = ?");
        $stmt->execute([$userId, $couponId]);
        $favorite = $stmt->fetch();

        if ($favorite) {
            $deleteStmt = $db->prepare("DELETE FROM user_favorites WHERE user_id = ? AND coupon_id = ?");
            $deleteStmt->execute([$userId, $couponId]);
            $this->sendJson([
                'success' => true,
                'active' => false,
                'message' => 'Đã bỏ khỏi yêu thích'
            ]);
        } else {
            $insertStmt = $db->prepare("INSERT INTO user_favorites (user_id, coupon_id) VALUES (?, ?)");
            $insertStmt->execute([$userId, $couponId]);
            $this->sendJson([
                'success' => true,
                'active' => true,
                'message' => 'Đã thêm vào yêu thích'
            ]);
        }
    }

    public function bookmarktoggle() {
        if (!isset($_SESSION['UserId'])) {
            $this->sendJson([
                'success' => false,
                'message' => 'Bạn cần đăng nhập để sử dụng tính năng này.',
                'code' => 'UNAUTHORIZED'
            ]);
        }

        $userId = $_SESSION['UserId'];
        $couponId = isset($_POST['coupon_id']) ? (int)$_POST['coupon_id'] : null;
        if (!$couponId) {
            $input = json_decode(file_get_contents('php://input'), true);
            $couponId = isset($input['coupon_id']) ? (int)$input['coupon_id'] : null;
        }

        if (!$couponId) {
            $this->sendJson(['success' => false, 'message' => 'Mã giảm giá không hợp lệ.']);
        }

        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT id FROM user_bookmarks WHERE user_id = ? AND coupon_id = ?");
        $stmt->execute([$userId, $couponId]);
        $bookmark = $stmt->fetch();

        if ($bookmark) {
            $deleteStmt = $db->prepare("DELETE FROM user_bookmarks WHERE user_id = ? AND coupon_id = ?");
            $deleteStmt->execute([$userId, $couponId]);
            $this->sendJson([
                'success' => true,
                'active' => false,
                'message' => 'Đã bỏ lưu'
            ]);
        } else {
            $insertStmt = $db->prepare("INSERT INTO user_bookmarks (user_id, coupon_id) VALUES (?, ?)");
            $insertStmt->execute([$userId, $couponId]);
            $this->sendJson([
                'success' => true,
                'active' => true,
                'message' => 'Đã lưu'
            ]);
        }
    }

    public function favoritestatus() {
        $userId = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : null;
        $couponId = isset($_GET['coupon_id']) ? (int)$_GET['coupon_id'] : null;

        if (!$userId || !$couponId) {
            $this->sendJson(['success' => true, 'active' => false]);
        }

        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT id FROM user_favorites WHERE user_id = ? AND coupon_id = ?");
        $stmt->execute([$userId, $couponId]);
        $exists = $stmt->fetch();

        $this->sendJson(['success' => true, 'active' => (bool)$exists]);
    }

    public function bookmarkstatus() {
        $userId = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : null;
        $couponId = isset($_GET['coupon_id']) ? (int)$_GET['coupon_id'] : null;

        if (!$userId || !$couponId) {
            $this->sendJson(['success' => true, 'active' => false]);
        }

        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT id FROM user_bookmarks WHERE user_id = ? AND coupon_id = ?");
        $stmt->execute([$userId, $couponId]);
        $exists = $stmt->fetch();

        $this->sendJson(['success' => true, 'active' => (bool)$exists]);
    }
}
