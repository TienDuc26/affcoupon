<?php
// router.php for PHP Built-in Web Server
$filePath = __DIR__ . '/public' . $_SERVER['REQUEST_URI'];

// Strip query string if present
if (($pos = strpos($filePath, '?')) !== false) {
    $filePath = substr($filePath, 0, $pos);
}

// Serve the static file directly if it exists
if (is_file($filePath)) {
    return false;
}

// Otherwise, route the request to public/index.php
require_once __DIR__ . '/public/index.php';
