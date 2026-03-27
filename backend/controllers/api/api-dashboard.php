<?php
/* ==========================================================================
   API SCRIPT: ADMIN BACK-END
   ========================================================================== */

require_once 'config/database.php';

// BKHÓA BẢO MẬT API (AUTHORIZATION CHECK)
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'employee')) {
    http_response_code(403);
    echo json_encode([
        'status' => 'error', 
        'message' => 'Truy cập bị từ chối! Bạn không có quyền quản trị.'
    ]);
    exit;
}

$action = isset($_GET['action']) ? $_GET['action'] : (isset($_POST['action']) ? $_POST['action'] : '');

$raw_data = file_get_contents('php://input');
$json_data = json_decode($raw_data, true);
if($json_data && isset($json_data['action'])) {
    $action = $json_data['action'];
}

switch ($action) {

    // --- A. THỐNG KÊ DASHBOARD ---
    case 'dashboard_stats':
        try {
            $stmt = $pdo->query("SELECT COUNT(*) as total FROM bookings");
            $total_bookings = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

            $stmt = $pdo->query("SELECT COUNT(*) as available FROM rooms WHERE status = 'Available'");
            $available_rooms = $stmt->fetch(PDO::FETCH_ASSOC)['available'];

            $stmt = $pdo->query("SELECT SUM(total_price) as revenue FROM bookings WHERE status = 'Completed'");
            $total_revenue = $stmt->fetch(PDO::FETCH_ASSOC)['revenue'];

            echo json_encode([
                'total_bookings' => (int)$total_bookings,
                'available_rooms' => (int)$available_rooms,
                'total_revenue' => (int)$total_revenue
            ]);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;

    // --- B. ĐƠN ĐẶT PHÒNG GẦN ĐÂY ---
    case 'get_recent_bookings':
        try {
            $sql = "SELECT b.id, b.booking_code, u.full_name AS customer_name, r.room_number, rt.name AS room_type, 
                        DATE_FORMAT(b.check_in_date, '%d/%m/%Y') AS check_in_date, 
                        DATE_FORMAT(b.check_out_date, '%d/%m/%Y') AS check_out_date, b.status 
                    FROM bookings b
                    JOIN users u ON b.user_id = u.id
                    JOIN rooms r ON b.room_id = r.id
                    JOIN room_types rt ON r.room_type_id = rt.id
                    ORDER BY b.created_at DESC LIMIT 5";
            $stmt = $pdo->query($sql);
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;

    // --- C. QUẢN LÝ TẤT CẢ PHÒNG ---
    case 'get_rooms':
        $in_date = isset($_GET['in']) ? $_GET['in'] : '';
        $out_date = isset($_GET['out']) ? $_GET['out'] : '';
        try {
            if (!empty($in_date) && !empty($out_date)) {
                $sql = "SELECT r.room_number, 
                        CASE WHEN r.status = 'Maintenance' THEN 'Maintenance'
                             WHEN (SELECT COUNT(*) FROM bookings b WHERE b.room_id = r.id AND b.status != 'Cancelled' AND (b.check_in_date <= :out_date AND b.check_out_date >= :in_date)) > 0 THEN 'Occupied'
                             ELSE 'Available' END AS current_status, 
                        rt.name AS type_name, rt.base_price 
                        FROM rooms r JOIN room_types rt ON r.room_type_id = rt.id ORDER BY r.room_number ASC";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([':in_date' => $in_date, ':out_date' => $out_date]);
                echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            } else {
                $sql = "SELECT r.room_number, r.status AS current_status, rt.name AS type_name, rt.base_price 
                        FROM rooms r JOIN room_types rt ON r.room_type_id = rt.id ORDER BY r.room_number ASC";
                $stmt = $pdo->query($sql);
                echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            }
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;

    // --- D. LẤY DANH SÁCH PHÒNG TRỐNG (THEO NGÀY) ---
    case 'get_available_rooms':
        $in_date = isset($_GET['in']) ? $_GET['in'] : '';
        $out_date = isset($_GET['out']) ? $_GET['out'] : '';
        if (empty($in_date) || empty($out_date)) { echo json_encode([]); exit; }

        try {
            $sql = "SELECT r.id, r.room_number, rt.name, rt.base_price FROM rooms r JOIN room_types rt ON r.room_type_id = rt.id
                    WHERE r.status != 'Maintenance' AND r.id NOT IN (
                        SELECT room_id FROM bookings WHERE (check_in_date <= :out_date AND check_out_date >= :in_date) AND status != 'Cancelled'
                    ) ORDER BY r.room_number ASC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':in_date' => $in_date, ':out_date' => $out_date]);
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;

    // --- E. TẠO ĐƠN ĐẶT PHÒNG MỚI (Admin/Employee) ---
    case 'create_booking':
        $data = $json_data;
        if (!$data) { echo json_encode(['status' => 'error', 'message' => 'Dữ liệu không hợp lệ']); exit; }

        try {
            $pdo->beginTransaction();

            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email LIMIT 1");
            $stmt->execute([':email' => $data['email']]);
            $customer = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($customer) {
                $user_id = $customer['id'];
            } else {
                $stmt = $pdo->prepare("INSERT INTO users (full_name, email, phone, role) VALUES (:name, :email, :phone, 'customer')");
                $stmt->execute([':name' => $data['name'], ':email' => $data['email'], ':phone' => $data['phone']]);
                $user_id = $pdo->lastInsertId();
            }

            $booking_code = 'LX-' . rand(10000, 99999);
            $booking_status = ($data['payment_status'] === 'Completed') ? 'Completed' : 'Pending';

            $stmt = $pdo->prepare("INSERT INTO bookings (booking_code, user_id, room_id, check_in_date, check_out_date, total_price, status) 
                                   VALUES (:code, :user_id, :room_id, :check_in, :check_out, :total, :status)");
            $stmt->execute([
                ':code' => $booking_code, ':user_id' => $user_id, ':room_id' => $data['room_id'],
                ':check_in' => $data['check_in'], ':check_out' => $data['check_out'], 
                ':total' => $data['total'], ':status' => $booking_status
            ]);
            $booking_id = $pdo->lastInsertId();

            if($booking_status === 'Completed') {
                $transaction_id = 'TXN-Q' . time();
                $stmtPay = $pdo->prepare("INSERT INTO payments (booking_id, payment_method, amount, transaction_id, payment_status) VALUES (:bid, 'Cash/Transfer', :amt, :txn, 'Success')");
                $stmtPay->execute([':bid' => $booking_id, ':amt' => $data['total'], ':txn' => $transaction_id]);
            }

            if ($data['check_in'] == date('Y-m-d')) {
                $stmt = $pdo->prepare("UPDATE rooms SET status = 'Occupied' WHERE id = :room_id");
                $stmt->execute([':room_id' => $data['room_id']]);
            }

            $pdo->commit(); 
            echo json_encode(['status' => 'success', 'booking_code' => $booking_code]);
        } catch (Exception $e) {
            $pdo->rollBack(); 
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;

    // --- F. CẬP NHẬT TRẠNG THÁI ĐƠN HÀNG (ADMIN ONLY) ---
    case 'update_booking_status':
        // CHỈ CÓ ADMIN MỚI ĐƯỢC DUYỆT/HỦY ĐƠN HÀNG, EMPLOYEE KHÔNG ĐƯỢC PHÉP
        if ($_SESSION['role'] !== 'admin') {
            echo json_encode(['status' => 'error', 'message' => 'Bạn không đủ quyền (Chỉ Admin)']);
            exit;
        }

        $data = $json_data;
        try {
            $pdo->beginTransaction();
            
            $stmt = $pdo->prepare("UPDATE bookings SET status = :status WHERE id = :id");
            $stmt->execute([':status' => $data['status'], ':id' => $data['id']]);

            if ($data['status'] === 'Completed') {
                $checkPay = $pdo->prepare("SELECT id FROM payments WHERE booking_id = :id");
                $checkPay->execute([':id' => $data['id']]);
                if (!$checkPay->fetch()) {
                    $getB = $pdo->prepare("SELECT total_price FROM bookings WHERE id = :id");
                    $getB->execute([':id' => $data['id']]);
                    $bData = $getB->fetch(PDO::FETCH_ASSOC);

                    $transaction_id = 'TXN-Q' . time();
                    $stmtPay = $pdo->prepare("INSERT INTO payments (booking_id, payment_method, amount, transaction_id, payment_status) VALUES (:bid, 'Cash/Transfer', :amt, :txn, 'Success')");
                    $stmtPay->execute([':bid' => $data['id'], ':amt' => $bData['total_price'], ':txn' => $transaction_id]);
                }
            }

            $pdo->commit();
            echo json_encode(['status' => 'success']);
        } catch (Exception $e) {
            $pdo->rollBack();
            echo json_encode(['status' => 'error', 'message' => 'Lỗi CSDL: '.$e->getMessage()]);
        }
        break;

    // --- G. QUẢN LÝ KHÁCH HÀNG ---
    case 'get_customers':
        try {
            $sql = "SELECT id, full_name, email, phone, DATE_FORMAT(created_at, '%d/%m/%Y') AS created_at 
                    FROM users WHERE role = 'customer' ORDER BY id DESC";
            $stmt = $pdo->query($sql);
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;
        
    case 'update_user':
        $data = $json_data;
        try {
            $stmt = $pdo->prepare("UPDATE users SET full_name = :name, phone = :phone WHERE id = :id AND role = 'customer'");
            $stmt->execute([':name' => $data['name'], ':phone' => $data['phone'], ':id' => $data['id']]);
            echo json_encode(['status' => 'success']);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;

    case 'delete_user':
        if ($_SESSION['role'] !== 'admin') {
            echo json_encode(['status' => 'error', 'message' => 'Bạn không đủ quyền (Chỉ Admin)']);
            exit;
        }
        $data = $json_data;
        try {
            $check = $pdo->prepare("SELECT id FROM bookings WHERE user_id = :id LIMIT 1");
            $check->execute([':id' => $data['id']]);
            if($check->fetch()) {
                echo json_encode(['status' => 'error', 'message' => 'Không thể xoá! Khách đã có đơn đặt phòng trong hệ thống.']);
                exit;
            }
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id AND role = 'customer'");
            $stmt->execute([':id' => $data['id']]);
            echo json_encode(['status' => 'success']);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Lỗi CSDL: '.$e->getMessage()]);
        }
        break;

    // --- H. THÔNG BÁO MỚI TỪ CSDL ---
    case 'get_notifications':
        try {
            $notifications = [];
            $stmt1 = $pdo->query("SELECT booking_code, created_at FROM bookings ORDER BY created_at DESC LIMIT 3");
            while($row = $stmt1->fetch(PDO::FETCH_ASSOC)) {
                $notifications[] = [
                    'icon' => 'bi-journal-check', 'color' => 'success', 'title' => 'Đơn đặt phòng mới',
                    'desc' => 'Mã đơn ' . $row['booking_code'] . ' vừa được tạo.', 'time' => $row['created_at']
                ];
            }
            $stmt2 = $pdo->query("SELECT full_name, created_at FROM users WHERE role='customer' ORDER BY created_at DESC LIMIT 2");
            while($row = $stmt2->fetch(PDO::FETCH_ASSOC)) {
                $notifications[] = [
                    'icon' => 'bi-person-plus', 'color' => 'primary', 'title' => 'Tài khoản mới',
                    'desc' => 'Khách hàng ' . $row['full_name'] . ' vừa tham gia.', 'time' => $row['created_at']
                ];
            }
            usort($notifications, function($a, $b) { return strtotime($b['time']) - strtotime($a['time']); });
            echo json_encode(array_slice($notifications, 0, 5));
        } catch (Exception $e) { echo json_encode(['status' => 'error']); }
        break;

    // --- I. LỊCH SỬ ĐƠN ĐẶT PHÒNG ---
    case 'get_all_bookings':
        try {
            $sql = "SELECT b.id, b.booking_code, u.full_name AS customer_name, r.room_number, rt.name AS room_type, 
                           DATE_FORMAT(b.check_in_date, '%d/%m/%Y') AS check_in_date, 
                           DATE_FORMAT(b.check_out_date, '%d/%m/%Y') AS check_out_date, 
                           b.total_price, b.status, p.payment_method
                    FROM bookings b 
                    JOIN users u ON b.user_id = u.id 
                    JOIN rooms r ON b.room_id = r.id 
                    JOIN room_types rt ON r.room_type_id = rt.id
                    LEFT JOIN payments p ON b.id = p.booking_id
                    ORDER BY b.created_at DESC";
            $stmt = $pdo->query($sql);
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        } catch (Exception $e) { 
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]); 
        }
        break;

    case 'delete_booking':
        if ($_SESSION['role'] !== 'admin') {
            echo json_encode(['status' => 'error', 'message' => 'Bạn không đủ quyền (Chỉ Admin)']);
            exit;
        }
        $data = $json_data;
        try {
            $stmt = $pdo->prepare("DELETE FROM bookings WHERE id = :id");
            $stmt->execute([':id' => $data['id']]);
            echo json_encode(['status' => 'success']);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Lỗi CSDL: '.$e->getMessage()]);
        }
        break;

    // --- K. LẤY HẠNG PHÒNG VÀ GIÁ ĐỂ CÀI ĐẶT ---
    case 'get_room_types':
        try {
            $stmt = $pdo->query("SELECT id, name, capacity, base_price FROM room_types ORDER BY id ASC");
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        } catch (Exception $e) { echo json_encode(['status' => 'error', 'message' => $e->getMessage()]); }
        break;

    case 'update_room_price':
        if ($_SESSION['role'] !== 'admin') {
            echo json_encode(['status' => 'error', 'message' => 'Bạn không đủ quyền (Chỉ Admin)']);
            exit;
        }
        $data = $json_data;
        try {
            $stmt = $pdo->prepare("UPDATE room_types SET base_price = :price WHERE id = :id");
            $stmt->execute([':price' => $data['price'], ':id' => $data['id']]);
            echo json_encode(['status' => 'success']);
        } catch (Exception $e) { echo json_encode(['status' => 'error', 'message' => $e->getMessage()]); }
        break;

    default:
        echo json_encode(['status' => 'error', 'message' => 'Hành động không hợp lệ']);
        break;
}
?>