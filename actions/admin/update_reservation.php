<?php
session_start();
require_once '../../config/db.php';

if (!isset($_SESSION['user_id']) || strtolower($_SESSION['role']) !== 'admin') {
    $_SESSION['error'] = "Bạn không có quyền truy cập!";
    header("Location: /nhahang/views/auth/login_register.php");
    exit();
}

$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? '';

if (!$id || !in_array($action, ['confirm', 'cancel'])) {
    $_SESSION['error'] = "Thiếu ID đặt bàn hoặc hành động không hợp lệ!";
    header("Location: /nhahang/views/admin/reservations.php");
    exit();
}

try {
    if ($action === 'confirm') {
        $stmt = $conn->prepare("UPDATE reservations SET status = 'confirmed' WHERE id = ?");
        $stmt->execute([$id]);
        $_SESSION['success'] = "✅ Đã xác nhận đơn đặt bàn.";
    } elseif ($action === 'cancel') {
        $stmt = $conn->prepare("UPDATE reservations SET status = 'cancelled' WHERE id = ?");
        $stmt->execute([$id]);
        $_SESSION['success'] = "❌ Đơn đặt bàn đã bị huỷ.";
    }

    header("Location: /nhahang/views/admin/reservations.php");
    exit();
} catch (PDOException $e) {
    $_SESSION['error'] = "Lỗi xử lý: " . $e->getMessage();
    header("Location: /nhahang/views/admin/reservations.php");
    exit();
}
