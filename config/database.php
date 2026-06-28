<?php
// Configuration for Database connection

define('DB_DRIVER', 'mysql'); // 'mysql' or 'sqlsrv'

// MySQL configuration
define('DB_HOST', 'localhost');
define('DB_PORT', '3306');
define('DB_NAME', 'affcoupondb');
define('DB_USER', 'root');
define('DB_PASS', '');

// SQL Server configuration (if needed)
define('SQLSRV_HOST', 'localhost');
define('SQLSRV_NAME', 'AffCouponDb');
define('SQLSRV_USER', 'root');
define('SQLSRV_PASS', '');

class Database {
    private static $connection = null;

    public static function getConnection() {
        if (self::$connection === null) {
            try {
                if (DB_DRIVER === 'mysql') {
                    $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
                    self::$connection = new PDO($dsn, DB_USER, DB_PASS, [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false,
                    ]);
                } else if (DB_DRIVER === 'sqlsrv') {
                    $dsn = "sqlsrv:Server=" . SQLSRV_HOST . ";Database=" . SQLSRV_NAME . ";TrustServerCertificate=true";
                    self::$connection = new PDO($dsn, SQLSRV_USER, SQLSRV_PASS, [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    ]);
                }
            } catch (PDOException $e) {
                die("Database connection failed: " . $e->getMessage());
            }
        }
        return self::$connection;
    }
}
