<?php
session_start();
require_once '../../config/db.php';

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

try {
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $user['role'];

        // Phân quyền chuyển trang
        if ($user['role'] === 'admin') {
            header("Location: /nhahang/views/admin/dashboard.php");
        } else {
            header("Location: /nhahang/views/user/home.php");
        }
        exit();
    } else {
        $_SESSION['error'] = "Email hoặc mật khẩu không đúng";
        $_SESSION['old'] = ['email' => $email];
        header("Location: /nhahang/views/auth/login_register.php");
        exit();
    }
} catch (PDOException $e) {
    $_SESSION['error'] = "Lỗi hệ thống: " . $e->getMessage();
    header("Location: /nhahang/views/auth/login_register.php");
    exit();
}
