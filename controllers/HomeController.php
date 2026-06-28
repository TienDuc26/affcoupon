<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/Coupon.php';

class HomeController extends BaseController {

    public function index() {
        $categoryId = isset($_GET['categoryId']) && $_GET['categoryId'] !== '' ? (int)$_GET['categoryId'] : null;
        $keyword = isset($_GET['keyword']) && $_GET['keyword'] !== '' ? trim($_GET['keyword']) : null;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;

        $pageSize = 8;

        $couponModel = new Coupon();
        $categoryModel = new Category();

        $totalCoupons = $couponModel->countActiveCoupons($categoryId, $keyword);
        $coupons = $couponModel->getActiveCoupons($categoryId, $page, $pageSize, $keyword);
        $categories = $categoryModel->getActiveCategories();

        $totalPages = (int)ceil($totalCoupons / $pageSize);
        if ($totalPages < 1) $totalPages = 1;

        $userFavorites = [];
        $userBookmarks = [];
        if (isset($_SESSION['UserId'])) {
            $db = Database::getConnection();
            $favStmt = $db->prepare("SELECT coupon_id FROM user_favorites WHERE user_id = ?");
            $favStmt->execute([$_SESSION['UserId']]);
            $userFavorites = $favStmt->fetchAll(PDO::FETCH_COLUMN);

            $bookStmt = $db->prepare("SELECT coupon_id FROM user_bookmarks WHERE user_id = ?");
            $bookStmt->execute([$_SESSION['UserId']]);
            $userBookmarks = $bookStmt->fetchAll(PDO::FETCH_COLUMN);
        }

        $this->renderView('home/index', [
            'Coupons' => $coupons,
            'Categories' => $categories,
            'CurrentPage' => $page,
            'TotalPages' => $totalPages,
            'SelectedCategoryId' => $categoryId,
            'UserFavorites' => $userFavorites,
            'UserBookmarks' => $userBookmarks
        ]);
    }

    public function about() {
        $this->renderView('home/about');
    }

    public function contact() {
        $this->renderView('home/contact');
    }
}
