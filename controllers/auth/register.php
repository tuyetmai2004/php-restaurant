<?php
session_start();
require_once '../../config/db.php';

$name = trim($_POST['fullname'] ?? ''); // dùng fullname từ form, gán vào biến name
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (!$name || !$email || !$password) {
    $_SESSION['error'] = "Vui lòng nhập đầy đủ thông tin.";
    $_SESSION['old'] = ['fullname' => $name, 'email' => $email];
    header("Location: /nhahang/views/auth/login_register.php");
    exit();
}

try {
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->rowCount() > 0) {
        $_SESSION['error'] = "Email đã được sử dụng.";
        $_SESSION['old'] = ['fullname' => $name, 'email' => $email];
        header("Location: /nhahang/views/auth/login_register.php");
        exit();
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'user')");
    $stmt->execute([$name, $email, $hashedPassword]);

    $_SESSION['success'] = "Đăng ký thành công. Vui lòng đăng nhập!";
    header("Location: /nhahang/views/auth/login_register.php");
    exit();
} catch (PDOException $e) {
    $_SESSION['error'] = "Lỗi hệ thống: " . $e->getMessage();
    header("Location: /nhahang/views/auth/login_register.php");
    exit();
}
