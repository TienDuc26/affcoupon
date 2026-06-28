<?php
// Enable error reporting for debugging on hosting (temporary)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start session for state and authentication
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Define root path
define('ROOT_PATH', dirname(__DIR__));

// Autoload/require controllers as needed
require_once ROOT_PATH . '/controllers/HomeController.php';
require_once ROOT_PATH . '/controllers/CouponController.php';
require_once ROOT_PATH . '/controllers/AccountController.php';
require_once ROOT_PATH . '/controllers/AdminController.php';
require_once ROOT_PATH . '/controllers/BlogController.php';
require_once ROOT_PATH . '/controllers/ApiController.php';
require_once ROOT_PATH . '/controllers/ProfileController.php';

// Parse Request URL
$requestUri = $_SERVER['REQUEST_URI'] ?? '/';

// Strip query string from URI
if (($pos = strpos($requestUri, '?')) !== false) {
    $requestUri = substr($requestUri, 0, $pos);
}

// Clean and split URL segments
$requestUri = trim($requestUri, '/');
$segments = $requestUri !== '' ? explode('/', $requestUri) : [];

// Determine Controller
$controllerName = 'Home'; // Default
if (isset($segments[0]) && !empty($segments[0])) {
    $controllerName = ucfirst(strtolower($segments[0]));
}

// Determine Action
$actionName = 'index'; // Default
if (isset($segments[1]) && !empty($segments[1])) {
    $actionName = strtolower($segments[1]);
}
if (strtolower($controllerName) === 'api' && isset($segments[1]) && isset($segments[2])) {
    $actionName = strtolower($segments[1]) . strtolower($segments[2]);
}

// Core Controller Mappings to avoid file loading issues and support strict naming
$controllerMap = [
    'Home' => 'HomeController',
    'Coupon' => 'CouponController',
    'Account' => 'AccountController',
    'Admin' => 'AdminController',
    'Blog' => 'BlogController',
    'Api' => 'ApiController',
    'Profile' => 'ProfileController'
];

if (array_key_exists($controllerName, $controllerMap)) {
    $controllerClass = $controllerMap[$controllerName];
    
    if (class_exists($controllerClass)) {
        $controllerInstance = new $controllerClass();
        
        // C#-style mapping to lowercase and exact matching
        $methods = get_class_methods($controllerClass);
        $methodToCall = null;
        
        foreach ($methods as $method) {
            if (strtolower($method) === $actionName) {
                $methodToCall = $method;
                break;
            }
        }
        
        // Specialized fallback for detail views
        if (!$methodToCall) {
            if ($controllerName === 'Coupon' && ($actionName === 'coupondetail' || $actionName === 'detail')) {
                $methodToCall = 'coupondetail';
            } else if ($controllerName === 'Coupon') {
                $methodToCall = 'coupondetail';
                $_GET['slug'] = $actionName;
            } else if ($controllerName === 'Blog' && ($actionName === 'detail' || $actionName === 'blogdetail')) {
                $methodToCall = 'detail';
            } else if ($controllerName === 'Blog') {
                $builtInActions = ['index', 'search', 'rss', 'sitemap'];
                if (in_array(strtolower($actionName), $builtInActions)) {
                    $methodToCall = $actionName;
                } else if (empty($actionName)) {
                    $methodToCall = 'index';
                } else {
                    $methodToCall = 'detail';
                    $_GET['slug'] = $actionName;
                }
            }
        }

        
        if ($methodToCall && method_exists($controllerInstance, $methodToCall)) {
            // Call the matched action
            $controllerInstance->$methodToCall();
            exit;
        }
    }
}

// Fallback: 404 Not Found
http_response_code(404);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Không tìm thấy trang</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
            color: #333;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            text-align: center;
        }
        .container {
            max-width: 500px;
            padding: 30px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        h1 {
            font-size: 6rem;
            margin: 0;
            color: #e74c3c;
        }
        p {
            font-size: 1.2rem;
            color: #666;
            margin: 20px 0;
        }
        a {
            display: inline-block;
            padding: 10px 20px;
            background-color: #27ae60;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 600;
        }
        a:hover {
            background-color: #219653;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>404</h1>
        <p>Đường dẫn bạn yêu cầu không tồn tại hoặc đã bị gỡ bỏ.</p>
        <a href="/">Quay lại trang chủ</a>
    </div>
</body>
</html>
