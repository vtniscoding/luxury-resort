<?php
/* ==========================================================================
   API SCRIPT: TÀI KHOẢN TRONG HỆ THỐNG (USER ACCOUNT)
   ========================================================================== */

//    Gọi CSDL
require_once './config/database.php';

$raw_data = file_get_contents('php://input');
$data = json_decode($raw_data, true);

if (!$data || !isset($data['action']) || !isset($data['email'])) {
    echo json_encode(['status' => 'error', 'message' => 'Dữ liệu không hợp lệ']);
    exit;
}

$action = $data['action'];
$email = $data['email'];

switch ($action) {
    // 1. LẤY THÔNG TIN CÁ NHÂN
    case 'get_profile':
        try {
            $stmt = $pdo->prepare("SELECT full_name, email, phone, DATE_FORMAT(created_at, '%d/%m/%Y') as join_date FROM users WHERE email = :email LIMIT 1");
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                echo json_encode(['status' => 'success', 'data' => $user]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Không tìm thấy tài khoản']);
            }
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;

    // 2. LẤY LỊCH SỬ ĐẶT PHÒNG CỦA RIÊNG KHÁCH HÀNG NÀY
    case 'get_history':
        try {
            $sql = "SELECT b.booking_code, r.room_number, rt.name AS room_type, 
                           DATE_FORMAT(b.check_in_date, '%d/%m/%Y') AS check_in_date, 
                           DATE_FORMAT(b.check_out_date, '%d/%m/%Y') AS check_out_date, 
                           b.total_price, b.status 
                    FROM bookings b
                    JOIN users u ON b.user_id = u.id
                    JOIN rooms r ON b.room_id = r.id
                    JOIN room_types rt ON r.room_type_id = rt.id
                    WHERE u.email = :email
                    ORDER BY b.created_at DESC";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([':email' => $email]);
            $history = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode(['status' => 'success', 'data' => $history]);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;

    // 3. CẬP NHẬT THÔNG TIN CÁ NHÂN
    case 'update_profile':
        try {
            $stmt = $pdo->prepare("UPDATE users SET full_name = :name, phone = :phone WHERE email = :email");
            $stmt->execute([
                ':name' => $data['name'],
                ':phone' => $data['phone'],
                ':email' => $email
            ]);
            echo json_encode(['status' => 'success', 'message' => 'Cập nhật thông tin thành công!']);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Lỗi cập nhật: ' . $e->getMessage()]);
        }
        break;

    default:
        echo json_encode(['status' => 'error', 'message' => 'Hành động không hợp lệ']);
        break;
}
?>