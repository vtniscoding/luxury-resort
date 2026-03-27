<?php
/* ==========================================================================
   API SCRIPT: XÁC THỰC & PHÂN QUYỀN (AUTHENTICATION & AUTHORIZATION)
   ========================================================================== */

// Gọi CSDL
require_once 'config/database.php';

// Nhận dữ liệu JSON
$input = json_decode(file_get_contents('php://input'), true);
$action = isset($input['action']) ? $input['action'] : '';

// 1. XỬ LÝ ĐĂNG NHẬP
if ($action === 'login') {
    $email = $input['email'] ?? '';
    $pass = $input['password'] ?? '';

    if (empty($email) || empty($pass)) {
        echo json_encode(['success' => false, 'message' => 'Vui lòng nhập đầy đủ thông tin!']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("SELECT id, full_name, email, role, password FROM users WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Xác minh password_hash
        if ($user && password_verify($pass, $user['password'])) {

            // BƯỚC 3 QUAN TRỌNG: LƯU THÔNG TIN VÀO SESSION Ở MÁY CHỦ
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['name'] = $user['full_name'];

            echo json_encode([
                'success' => true,
                'user' => [
                    'id' => $user['id'],
                    'name' => $user['full_name'],
                    'email' => $user['email'],
                    'role' => $user['role']
                ]
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Email hoặc mật khẩu không chính xác!']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Lỗi truy vấn: ' . $e->getMessage()]);
    }

    // 2. XỬ LÝ ĐĂNG XUẤT (HỦY SESSION)
} elseif ($action === 'logout') {
    session_unset();    // Xóa hết biến session
    session_destroy();  // Phá hủy session
    echo json_encode(['success' => true, 'message' => 'Đã đăng xuất thành công khỏi máy chủ']);
    exit;

    // 3. XỬ LÝ ĐĂNG KÝ
} elseif ($action === 'register') {
    $name = $input['name'] ?? '';
    $email = $input['email'] ?? '';
    $pass = $input['password'] ?? '';

    if (empty($name) || empty($email) || empty($pass)) {
        echo json_encode(['success' => false, 'message' => 'Vui lòng điền đủ thông tin!']);
        exit;
    }

    try {
        $checkStmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
        $checkStmt->execute([':email' => $email]);
        if ($checkStmt->fetch()) {
            echo json_encode(['success' => false, 'message' => 'Email này đã được đăng ký!']);
            exit;
        }

        $hashedPassword = password_hash($pass, PASSWORD_BCRYPT);
        $phone = 'Chưa cập nhật';
        $role = 'customer';

        $insertStmt = $pdo->prepare("INSERT INTO users (full_name, email, phone, password, role) VALUES (:name, :email, :phone, :pass, :role)");
        $result = $insertStmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':phone' => $phone,
            ':pass' => $hashedPassword,
            ':role' => $role
        ]);

        if ($result)
            echo json_encode(['success' => true, 'message' => 'Đăng ký tài khoản thành công!']);
        else
            echo json_encode(['success' => false, 'message' => 'Không thể tạo tài khoản lúc này.']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Lỗi lưu CSDL: ' . $e->getMessage()]);
    }

    // 4. QUÊN MẬT KHẨU
} elseif ($action === 'forgot_password') {
    $email = $input['email'] ?? '';
    if (empty($email)) {
        echo json_encode(['success' => false, 'message' => 'Vui lòng nhập email!']);
        exit;
    }
    try {
        $stmt = $pdo->prepare("SELECT id, full_name FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();

        if ($user)
            echo json_encode(['success' => true, 'message' => 'Đã gửi liên kết khôi phục. Vui lòng kiểm tra hộp thư email!']);
        else
            echo json_encode(['success' => false, 'message' => 'Nếu email tồn tại trong hệ thống, chúng tôi đã gửi thư khôi phục!']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Lỗi truy vấn: ' . $e->getMessage()]);
    }

} else {
    echo json_encode(['success' => false, 'message' => 'Hành động không hợp lệ!']);
}
?>