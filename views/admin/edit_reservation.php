<?php
session_start();
require_once '../../config/db.php';

// Kiểm tra quyền admin
if (!isset($_SESSION['user_id']) || strtolower($_SESSION['role']) !== 'admin') {
    header("Location: /nhahang/views/auth/login_register.php");
    exit();
}

$id = $_GET['id'] ?? null;
if (!$id) {
    $_SESSION['error'] = "Thiếu ID đặt bàn.";
    header("Location: reservations.php");
    exit();
}

// Lấy dữ liệu đặt bàn
try {
    $stmt = $conn->prepare("SELECT * FROM reservations WHERE id = ?");
    $stmt->execute([$id]);
    $reservation = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$reservation) {
        $_SESSION['error'] = "Không tìm thấy đặt bàn.";
        header("Location: reservations.php");
        exit();
    }

    // Lấy danh sách bàn khả dụng + bàn hiện tại
    $stmt = $conn->prepare("
        SELECT * FROM tables 
        WHERE status = 'available' OR id = ?
        ORDER BY capacity ASC
    ");
    $stmt->execute([$reservation['table_id']]);
    $tables = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Lỗi: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Sửa Đặt Bàn - Admin</title>
  <link rel="stylesheet" href="/nhahang/assets/css/style.css">
</head>
<body>
  <div class="booking-container">
    <h2>🛠 Sửa Thông Tin Đặt Bàn</h2>

    <?php if (isset($_SESSION['error'])): ?>
      <div class="alert alert-error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <form action="/nhahang/actions/admin/update_reservation.php" method="POST">
      <input type="hidden" name="id" value="<?= $reservation['id'] ?>">

      <div class="form-group">
        <label for="number_of_people">Số người</label>
        <input type="number" id="number_of_people" name="number_of_people" min="1" max="20" required
               value="<?= htmlspecialchars($reservation['number_of_people']) ?>">
      </div>

      <div class="form-group">
        <label for="reservation_date">Ngày đặt</label>
        <input type="date" id="reservation_date" name="reservation_date" required
               value="<?= htmlspecialchars($reservation['reservation_date']) ?>">
      </div>

      <div class="form-group">
        <label for="start_time">Giờ bắt đầu</label>
        <input type="time" id="start_time" name="start_time" required
               value="<?= htmlspecialchars($reservation['start_time']) ?>">
      </div>

      <div class="form-group">
        <label for="end_time">Giờ kết thúc</label>
        <input type="time" id="end_time" name="end_time" required
               value="<?= htmlspecialchars($reservation['end_time']) ?>">
      </div>

      <div class="form-group">
        <label for="table_id">Bàn</label>
        <select id="table_id" name="table_id" required>
          <option value="">-- Chọn bàn --</option>
          <?php foreach ($tables as $table): ?>
            <option value="<?= $table['id'] ?>"
              <?= ($table['id'] == $reservation['table_id']) ? 'selected' : '' ?>>
              Bàn <?= $table['id'] ?> (Sức chứa: <?= $table['capacity'] ?>)
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="form-group">
        <label for="has_children">Có trẻ em đi cùng?</label>
        <select id="has_children" name="has_children" required>
          <option value="0" <?= $reservation['has_children'] == 0 ? 'selected' : '' ?>>Không</option>
          <option value="1" <?= $reservation['has_children'] == 1 ? 'selected' : '' ?>>Có</option>
        </select>
      </div>

      <div class="form-group">
        <label for="note">Ghi chú</label>
        <textarea name="note" id="note" rows="3"><?= htmlspecialchars($reservation['note']) ?></textarea>
      </div>

      <div class="form-group">
        <label for="deposit_amount">Tiền đặt cọc (VNĐ)</label>
        <input type="number" name="deposit_amount" id="deposit_amount" min="0"
               value="<?= htmlspecialchars($reservation['deposit_amount']) ?>">
      </div>

      <button type="submit" class="btn-submit">💾 Cập nhật</button>
    </form>
  </div>
</body>
</html>
