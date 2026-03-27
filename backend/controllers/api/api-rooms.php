<?php
// api/api-rooms.php (hoặc get_rooms.php)

// 1. Thiết lập Header chuẩn RESTful API
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET'); // Chỉ cho phép phương thức GET

// Kiểm tra phương thức HTTP
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405); // Method Not Allowed
    echo json_encode([
        'status' => 'error', 
        'message' => 'Phương thức không được hỗ trợ. Vui lòng sử dụng GET.'
    ]);
    exit;
}

// 2. Cấu hình Database (Chuẩn cho XAMPP mặc định)
$host = 'localhost'; // XAMPP ưu tiên dùng localhost
$dbname = 'luxury_hotel_db';
$user = 'root';      // Tài khoản mặc định của MySQL trên XAMPP
$pass = '';          // Mật khẩu mặc định trên XAMPP là để trống

try {
    // 3. Khởi tạo kết nối PDO với các tùy chọn bảo mật và tối ưu
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false, // Bảo mật hơn, ngăn chặn SQL Injection nâng cao
    ]);

    // 4. Truy vấn lấy danh sách phòng (Sắp xếp theo giá từ thấp đến cao)
    $stmt = $pdo->query("SELECT * FROM room_types ORDER BY base_price ASC");
    $rooms = $stmt->fetchAll();

    // 5. Làm sạch và xử lý dữ liệu trước khi trả về Frontend
    foreach ($rooms as &$room) {
        // Xử lý an toàn các trường JSON, nếu null/trống sẽ mặc định là mảng rỗng []
        $room['amenities'] = !empty($room['amenities']) ? json_decode($room['amenities']) : [];
        $room['images']    = !empty($room['images']) ? json_decode($room['images']) : [];

        // Ép kiểu dữ liệu (Type Casting) để Frontend (JS) xử lý chính xác hơn
        $room['id']         = (int) $room['id'];
        $room['area']       = (int) $room['area'];
        $room['capacity']   = (int) $room['capacity'];
        $room['base_price'] = (float) $room['base_price'];
    }

    // 6. Trả về kết quả thành công
    http_response_code(200); // OK
    echo json_encode([
        'status' => 'success',
        'count'  => count($rooms), // Bổ sung số lượng phòng để Frontend tiện kiểm tra
        'data'   => $rooms
    ]);

} catch (PDOException $e) {
    // 7. Xử lý lỗi Database
    http_response_code(500); // Internal Server Error
    
    // Ghi log lỗi vào file của server (error_log) để admin theo dõi ẩn
    error_log("Database Error (api-rooms.php): " . $e->getMessage());

    echo json_encode([
        'status'  => 'error',
        // Ở môi trường Production nên ẩn chi tiết lỗi CSDL, chỉ hiện thông báo chung
        'message' => 'Đã xảy ra lỗi khi tải dữ liệu phòng. Vui lòng thử lại sau.',
        // 'debug' => $e->getMessage() // Mở comment dòng này khi đang Dev để xem lỗi trực tiếp
    ]);
}