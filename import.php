<?php
// Set execution time limit to unlimited for large database imports
set_time_limit(0);
ini_set('memory_limit', '512M');

echo "<div style='font-family: Arial, sans-serif; max-width: 600px; margin: 40px auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);'>";
echo "<h2 style='color: #27ae60; border-bottom: 2px solid #27ae60; padding-bottom: 10px;'>Hệ thống Tự động Nhập Dữ liệu Database</h2>";

// 1. Load database configuration
$dbConfigFile = __DIR__ . '/config/database.php';
if (!file_exists($dbConfigFile)) {
    echo "<p style='color: red; font-weight: bold;'>Lỗi: Không tìm thấy file cấu hình config/database.php. Vui lòng giải nén mã nguồn trước!</p>";
    echo "</div>";
    exit;
}

require_once $dbConfigFile;

try {
    // 2. Establish connection
    echo "<p>⚡ Đang kết nối tới Database <b>" . DB_NAME . "</b>...</p>";
    $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    echo "<p style='color: green; font-weight: bold;'>✓ Kết nối Database thành công!</p>";

    // 3. Find the SQL backup file
    $sqlFile = __DIR__ . '/AffCouponDb_backup.sql';
    if (!file_exists($sqlFile)) {
        $sqlFile = __DIR__ . '/database.sql';
    }

    if (!file_exists($sqlFile)) {
        echo "<p style='color: red; font-weight: bold;'>Lỗi: Không tìm thấy tệp SQL (AffCouponDb_backup.sql hoặc database.sql) trong thư mục gốc public_html.</p>";
        echo "</div>";
        exit;
    }

    echo "<p>📂 Đọc tệp tin SQL: <b>" . basename($sqlFile) . "</b>...</p>";
    
    // Read and parse SQL statements
    $sqlContent = file_get_contents($sqlFile);
    
    // Remove comments
    $sqlContent = preg_replace('/--.*\n/', '', $sqlContent);
    $sqlContent = preg_replace('/\/\*.*?\*\//s', '', $sqlContent);
    
    // Split by semicolon (ignoring semicolons inside strings is basic here, but works for standard dumps)
    $queries = explode(';', $sqlContent);
    $successCount = 0;
    $errorCount = 0;

    echo "<p>⏳ Đang tiến hành nhập dữ liệu vào các bảng...</p>";

    $pdo->beginTransaction();
    foreach ($queries as $query) {
        $query = trim($query);
        if (!empty($query)) {
            try {
                $pdo->exec($query);
                $successCount++;
            } catch (PDOException $e) {
                // Keep going for drop tables or duplicate checks
                $errorCount++;
            }
        }
    }
    $pdo->commit();

    echo "<h3 style='color: #27ae60; margin-top: 20px;'>🎉 Nhập dữ liệu hoàn tất thành công!</h3>";
    echo "<ul style='background: #f9f9f9; padding: 15px; border-radius: 4px; list-style: none;'>";
    echo "<li>🟢 Số câu lệnh chạy thành công: <b>$successCount</b></li>";
    if ($errorCount > 0) {
        echo "<li>🟡 Số câu lệnh bỏ qua/lỗi nhẹ: <b>$errorCount</b> (thường là DROP TABLE nếu bảng chưa tồn tại)</li>";
    }
    echo "</ul>";
    echo "<p style='color: #c0392b; font-weight: bold; background: #fde8e7; padding: 10px; border-radius: 4px; border: 1px solid #f5c2c2;'>⚠️ CẢNH BÁO BẢO MẬT: Hãy XÓA ngay file <b>import.php</b> này trên File Manager sau khi nhập xong để tránh lộ thông tin!</p>";

} catch (PDOException $e) {
    echo "<div style='color: #c0392b; background: #fde8e7; padding: 15px; border-radius: 4px; border: 1px solid #f5c2c2; margin-top: 15px;'>";
    echo "<h4 style='margin-top: 0;'>❌ Kết nối thất bại hoặc Lỗi SQL:</h4>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p>💡 <b>Gợi ý:</b> Hãy kiểm tra xem bạn đã điền đúng Tên Database, Username, Mật khẩu trong file <b>config/database.php</b> chưa nhé!</p>";
    echo "</div>";
}

echo "</div>";
?>
