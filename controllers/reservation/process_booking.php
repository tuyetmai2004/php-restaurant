<?php
session_start();
require_once '../../config/db.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Bạn cần đăng nhập để đặt bàn!";
    header("Location: ../../views/auth/login_register.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Lấy dữ liệu từ form
$number_of_people = intval($_POST['number_of_people'] ?? 1);
$reservation_date = $_POST['reservation_date'] ?? '';
$start_time = $_POST['start_time'] ?? '';
$end_time = $_POST['end_time'] ?? '';
$has_children = isset($_POST['has_children']) ? 1 : 0;
$note = trim($_POST['note'] ?? '');
$deposit_amount = floatval($_POST['deposit_amount'] ?? 0);

// Kiểm tra dữ liệu đầu vào
if (!$reservation_date || !$start_time || !$end_time || $number_of_people < 1) {
    $_SESSION['error'] = "Vui lòng nhập đầy đủ thông tin!";
    header("Location: ../../views/user/reserve.php");
    exit();
}

// Gộp thời gian bắt đầu và kết thúc
$start_dt = new DateTime("$reservation_date $start_time");
$end_dt = new DateTime("$reservation_date $end_time");

// Nếu giờ kết thúc nhỏ hơn giờ bắt đầu → qua đêm
if ($end_dt <= $start_dt) {
    $end_dt->modify('+1 day');
}

// Format lại để lưu
$start_datetime = $start_dt->format('Y-m-d H:i:s');
$end_datetime = $end_dt->format('Y-m-d H:i:s');
$reservation_time = $start_datetime;

// Tính thời lượng (phút) cho kiểm tra trùng lịch
$duration_minutes = ($end_dt->getTimestamp() - $start_dt->getTimestamp()) / 60;

try {
    // Tìm bàn phù hợp chưa bị đặt trùng thời gian
    $stmt = $conn->prepare("
        SELECT * FROM tables t
        WHERE t.capacity >= :capacity
        AND t.status = 'available'
        AND NOT EXISTS (
            SELECT 1 FROM reservations r
            WHERE r.table_id = t.id
            AND r.status IN ('pending', 'confirmed')
            AND (
                (r.reservation_time < :end_datetime) 
                AND (
                    ADDTIME(r.reservation_time, SEC_TO_TIME(TIMESTAMPDIFF(MINUTE, r.start_time, r.end_time) * 60)) > :start_datetime
                )
            )
        )
        ORDER BY t.capacity ASC
        LIMIT 1
    ");

    $stmt->execute([
        ':capacity' => $number_of_people,
        ':start_datetime' => $start_datetime,
        ':end_datetime' => $end_datetime,
    ]);

    $table = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$table) {
        $_SESSION['error'] = "Không có bàn trống phù hợp trong thời gian này.";
        header("Location: ../../views/user/reserve.php");
        exit();
    }

    $table_id = $table['id'];

    // Lưu đặt bàn
    $insert = $conn->prepare("
        INSERT INTO reservations (
            user_id, table_id, reservation_time, note,
            number_of_people, has_children, deposit_amount,
            start_time, end_time, status
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')
    ");

    $result = $insert->execute([
        $user_id, $table_id, $reservation_time, $note,
        $number_of_people, $has_children, $deposit_amount,
        $start_time, $end_time
    ]);

    if ($result) {
        // Cập nhật trạng thái bàn nếu cần
        $update = $conn->prepare("UPDATE tables SET status = 'reserved' WHERE id = ?");
        $update->execute([$table_id]);

        $_SESSION['success'] = "Đặt bàn thành công!";
    } else {
        $_SESSION['error'] = "Lỗi khi đặt bàn.";
    }

} catch (PDOException $e) {
    $_SESSION['error'] = "Lỗi hệ thống: " . $e->getMessage();
}

header("Location: ../../views/user/reserve.php");
exit();
