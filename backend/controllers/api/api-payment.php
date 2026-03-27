<?php
/* ==========================================================================
   API SCRIPT: QUY TRÌNH THANH TOÁN (CHECKOUT PROCESSING)
   ========================================================================== */

// Gọi CSDL
require_once 'config/database.php';

$raw_data = file_get_contents('php://input');
$data = json_decode($raw_data, true);

if (!$data) {
    echo json_encode(['status' => 'error', 'message' => 'Dữ liệu truyền vào không hợp lệ']);
    exit;
}

try {
    $pdo->beginTransaction();

    // 1. TÌM PHÒNG TRỐNG VÀ LẤY GIÁ GỐC (BASE_PRICE) TỪ CSDL
    $stmtRoom = $pdo->prepare("
        SELECT r.id, rt.base_price 
        FROM rooms r 
        JOIN room_types rt ON r.room_type_id = rt.id
        WHERE rt.name = :roomName 
          AND r.status != 'Maintenance'
          AND r.id NOT IN (
              SELECT room_id FROM bookings 
              WHERE status != 'Cancelled' 
              AND (check_in_date <= :out_date AND check_out_date >= :in_date)
          )
        ORDER BY r.room_number ASC 
        LIMIT 1
    ");
    $stmtRoom->execute([
        ':roomName' => $data['roomName'],
        ':in_date'  => $data['checkIn'],
        ':out_date' => $data['checkOut']
    ]);

    $room = $stmtRoom->fetch(PDO::FETCH_ASSOC);
    if (!$room) {
        echo json_encode(['status' => 'error', 'message' => 'Rất tiếc! Hạng phòng này vừa được khách hàng khác đặt.']);
        $pdo->rollBack();
        exit;
    }

    $room_id = $room['id'];
    $base_price = $room['base_price'];

    // 2. LOGIC TÍNH TOÁN CHO BACKEND
    $in_date_obj = new DateTime($data['checkIn']);
    $out_date_obj = new DateTime($data['checkOut']);

    // Tính số ngày lưu trú
    $interval = $in_date_obj->diff($out_date_obj);
    $nights = $interval->days;
    if ($nights <= 0) $nights = 1; // Tối thiểu 1 đêm

    // Tính tổng tiền phòng và Thuế (10%)
    $room_total = $base_price * $nights;
    $tax_amount = $room_total * 0.10;

    // Lưu số tiền vào CSDL
    $actual_total_price = $room_total + $tax_amount;

    // 3. KIỂM TRA/TẠO TÀI KHOẢN KHÁCH HÀNG
    $stmtUser = $pdo->prepare("SELECT id FROM users WHERE email = :email LIMIT 1");
    $stmtUser->execute([':email' => $data['email']]);
    $user = $stmtUser->fetch(PDO::FETCH_ASSOC);

    $is_new_user = false;
    if ($user) {
        $user_id = $user['id'];
    } else {
        $stmtInsertUser = $pdo->prepare("INSERT INTO users (full_name, email, phone, role) VALUES (:name, :email, :phone, 'customer')");
        $stmtInsertUser->execute([
            ':name'  => $data['fullName'],
            ':email' => $data['email'],
            ':phone' => $data['phone']
        ]);
        $user_id = $pdo->lastInsertId();
        $is_new_user = true;
    }

    // 4. TẠO ĐƠN ĐẶT PHÒNG
    $booking_code = 'LX-' . rand(10000, 99999);
    $stmtBooking = $pdo->prepare("
        INSERT INTO bookings (booking_code, user_id, room_id, check_in_date, check_out_date, total_price, status) 
        VALUES (:code, :user_id, :room_id, :check_in, :check_out, :total, 'Completed')
    ");
    $stmtBooking->execute([
        ':code'     => $booking_code,
        ':user_id'  => $user_id,
        ':room_id'  => $room_id,
        ':check_in' => $data['checkIn'],
        ':check_out' => $data['checkOut'],
        ':total'    => $actual_total_price // LƯU GIÁ BACKEND
    ]);
    $booking_id = $pdo->lastInsertId();

    // 5. GHI LẠI LỊCH SỬ THANH TOÁN
    $transaction_id = 'TXN-' . time() . '-' . rand(100, 999);
    $stmtPayment = $pdo->prepare("
        INSERT INTO payments (booking_id, payment_method, amount, transaction_id, payment_status) 
        VALUES (:bid, :method, :amt, :txn, 'Success')
    ");
    $stmtPayment->execute([
        ':bid'    => $booking_id,
        ':method' => $data['paymentMethod'],
        ':amt'    => $actual_total_price,
        ':txn'    => $transaction_id
    ]);

    // 6. KHÓA PHÒNG NẾU NHẬN TRONG NGÀY
    if ($data['checkIn'] == date('Y-m-d')) {
        $stmtUpdateRoom = $pdo->prepare("UPDATE rooms SET status = 'Occupied' WHERE id = :room_id");
        $stmtUpdateRoom->execute([':room_id' => $room_id]);
    }

    $pdo->commit();

    echo json_encode([
        'status' => 'success',
        'booking_code' => $booking_code,
        'is_new_user' => $is_new_user,
        'final_price' => $actual_total_price // Gửi ngược lại cho Frontend biết (tuỳ chọn)
    ]);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['status' => 'error', 'message' => 'Lỗi xử lý Database: ' . $e->getMessage()]);
}
