<?php

class BaseController {
    
    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Renders a view with optional layout (header and footer)
     */
    protected function renderView($viewPath, $data = [], $includeLayout = true) {
        // Extract data to make variables available in the views
        extract($data);

        // Access database to get categories list for the navigation dropdown if layout is included
        $categories = [];
        if ($includeLayout) {
            require_once __DIR__ . '/../config/database.php';
            require_once __DIR__ . '/../models/Category.php';
            $categoryModel = new Category();
            $categories = $categoryModel->getActiveCategories();
        }

        // Pass variables to view scope
        $username = isset($_SESSION['Username']) ? $_SESSION['Username'] : null;
        $roleId = isset($_SESSION['RoleId']) ? $_SESSION['RoleId'] : null;
        $userId = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : null;

        // Load header if layout is enabled
        if ($includeLayout && file_exists(__DIR__ . "/../views/shared/header.php")) {
            require_once __DIR__ . "/../views/shared/header.php";
        }

        // Load main view content
        $fullViewPath = __DIR__ . "/../views/" . $viewPath . ".php";
        if (file_exists($fullViewPath)) {
            require $fullViewPath;
        } else {
            echo "View not found: " . $viewPath;
        }

        // Load footer if layout is enabled
        if ($includeLayout && file_exists(__DIR__ . "/../views/shared/footer.php")) {
            require_once __DIR__ . "/../views/shared/footer.php";
        }

        // Clear TempData after view is rendered
        $this->clearTempData();
    }

    /**
     * Redirects to a relative URL
     */
    protected function redirect($url) {
        header("Location: " . $url);
        exit;
    }

    /**
     * Redirect to action helper (mimics ASP.NET RedirectToAction)
     */
    protected function redirectToAction($action, $controller = null) {
        $url = "/";
        if ($controller) {
            $url .= strtolower(str_replace("Controller", "", $controller)) . "/";
        }
        $url .= strtolower($action);
        $this->redirect($url);
    }

    /**
     * TempData Helper
     */
    protected function setTempData($key, $value) {
        if (!isset($_SESSION['TempData'])) {
            $_SESSION['TempData'] = [];
        }
        $_SESSION['TempData'][$key] = $value;
    }

    protected function getTempData($key) {
        if (isset($_SESSION['TempData'][$key])) {
            return $_SESSION['TempData'][$key];
        }
        return null;
    }

    public function clearTempData() {
        if (isset($_SESSION['TempData'])) {
            unset($_SESSION['TempData']);
        }
    }

    /**
     * CSRF / Anti-Forgery Token helpers
     */
    public static function generateAntiForgeryToken() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['__RequestVerificationToken'])) {
            $_SESSION['__RequestVerificationToken'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['__RequestVerificationToken'];
    }

    protected function validateAntiForgeryToken() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = isset($_POST['__RequestVerificationToken']) ? $_POST['__RequestVerificationToken'] : '';
            if (empty($token) || !isset($_SESSION['__RequestVerificationToken']) || $token !== $_SESSION['__RequestVerificationToken']) {
                http_response_code(400);
                die("Anti-forgery token validation failed.");
            }
        }
    }

    /**
     * Check if user is logged in
     */
    protected function isLoggedIn() {
        return isset($_SESSION['UserId']);
    }

    /**
     * Check if user is Admin
     */
    protected function isAdmin() {
        return isset($_SESSION['RoleId']) && $_SESSION['RoleId'] == 1;
    }

    /**
     * Restrict access to Admin only
     */
    protected function authorizeAdmin() {
        if (!$this->isAdmin()) {
            $this->redirect('/account/login');
        }
    }
}
