<?php
session_start();
require_once '../../config/db.php'; // đường dẫn tùy theo cấu trúc dự án

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['user_id'])) {
    // Chưa đăng nhập => chuyển về trang login
    header('Location: /nhahang/views/auth/login.php');
    exit();
}

// Lấy user id từ session
$userId = $_SESSION['user_id'];

// Xử lý form đổi mật khẩu khi method POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    // Kiểm tra nhập liệu
    if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
        $_SESSION['error'] = 'Vui lòng nhập đầy đủ thông tin.';
        header('Location: /nhahang/views/user/change_password.php');
        exit();
    }

    if ($newPassword !== $confirmPassword) {
        $_SESSION['error'] = 'Mật khẩu mới và xác nhận mật khẩu không khớp.';
        header('Location: /nhahang/views/user/change_password.php');
        exit();
    }

    // Lấy mật khẩu hiện tại trong database
    $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        $_SESSION['error'] = 'Người dùng không tồn tại.';
        header('Location: /nhahang/views/auth/login.php');
        exit();
    }

    // Kiểm tra mật khẩu hiện tại có đúng không
    if (!password_verify($currentPassword, $user['password'])) {
        $_SESSION['error'] = 'Mật khẩu hiện tại không đúng.';
        header('Location: /nhahang/views/user/change_password.php');
        exit();
    }

    // Mã hóa mật khẩu mới
    $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);

    // Cập nhật mật khẩu mới
    $updateStmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    $updated = $updateStmt->execute([$newPasswordHash, $userId]);

    if ($updated) {
        $_SESSION['success'] = 'Đổi mật khẩu thành công.';
        header('Location: /nhahang/views/user/change_password.php');
        exit();
    } else {
        $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại.';
        header('Location: /nhahang/views/user/change_password.php');
        exit();
    }
} else {
    // Nếu không phải POST => chuyển hướng về trang đổi mật khẩu hoặc trang khác
    header('Location: /nhahang/views/user/change_password.php');
    exit();
}
