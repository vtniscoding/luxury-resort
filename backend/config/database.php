<?php
/* ==========================================================================
   CẤU HÌNH CƠ SỞ DỮ LIỆU (DATABASE CONFIGURATION)
   ========================================================================== */

// 1. THIẾT LẬP HEADER CHUNG CHO TẤT CẢ CÁC API & BẢO MẬT
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Tạo mới session
if (session_status() === PHP_SESSION_NONE) {
    // Không cho Javascript đọc cookie session của PHP
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    session_start();
}

// 2. THIẾT LẬP MÚI GIỜ
date_default_timezone_set('Asia/Ho_Chi_Minh');

// 3. CẤU HÌNH KẾT NỐI DATABASE
$db_host = 'localhost';
$db_name = 'luxury_hotel_db';
$db_user = 'root';
$db_pass = '';

// 4. KHỞI TẠO KẾT NỐI PDO (PHP Data Objects)
try {
    $dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4";
    $pdo = new PDO($dsn, $db_user, $db_pass);

    // Bật chế độ báo lỗi và trả về mảng kết hợp
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // Chống SQL Injection
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

} catch (PDOException $e) {
    // Thiết lập báo lỗi
    echo json_encode([
        'status' => 'error',
        'message' => 'Lỗi kết nối CSDL: ' . $e->getMessage()
    ]);
    exit;
}

?>