<?php
session_start();
require_once '../config/db.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: ../views/auth/login_register.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'] ?? 'user';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy dữ liệu từ form
    $number_of_people = intval($_POST['number_of_people'] ?? 1);
    $reservation_date = $_POST['reservation_date'] ?? '';
    $start_time = $_POST['start_time'] ?? '';
    $end_time = $_POST['end_time'] ?? '';
    $has_children = isset($_POST['has_children']) ? intval($_POST['has_children']) : 0;
    $note = $_POST['note'] ?? '';
    $deposit_amount = intval($_POST['deposit_amount'] ?? 0);

    // Gộp datetime
    $reservation_time = $reservation_date . ' ' . $start_time;

    try {
        // Check xem có trùng thời gian và bàn không (ví dụ giả định chỉ có 1 bàn loại thường hoặc vip)
        $checkStmt = $conn->prepare("SELECT * FROM reservations WHERE reservation_time = ? AND start_time = ? AND end_time = ?");
        $checkStmt->execute([$reservation_time, $start_time, $end_time]);

        if ($checkStmt->rowCount() > 0) {
            $_SESSION['error'] = "Thời gian này đã có người đặt bàn!";
            header("Location: ../views/user/reserve.php");
            exit();
        }

        // Chọn bàn tự động (ví dụ: dựa trên loại bàn, số người...) — bạn có thể mở rộng sau
        $table_id = 1; // gán tạm bàn số 1

        // Thêm đặt bàn
        $stmt = $conn->prepare("INSERT INTO reservations (
            user_id, table_id, reservation_time, note, number_of_people, has_children, deposit_amount, start_time, end_time
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $success = $stmt->execute([
            $user_id,
            $table_id,
            $reservation_time,
            $note,
            $number_of_people,
            $has_children,
            $deposit_amount,
            $start_time,
            $end_time
        ]);

        if ($success) {
            $_SESSION['success'] = "Đặt bàn thành công!";
        } else {
            $_SESSION['error'] = "Đặt bàn thất bại!";
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Lỗi hệ thống: " . $e->getMessage();
    }

    header("Location: ../views/user/reserve.php");
    exit();
}
