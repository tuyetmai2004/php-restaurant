<?php
session_start();
require_once '../../config/db.php';

if (!isset($_SESSION['user_id']) || strtolower($_SESSION['role']) !== 'admin') {
    header("Location: /nhahang/views/auth/login_register.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $table_number = $_POST['table_number'] ?? '';
    $capacity = $_POST['capacity'] ?? '';
    $status = $_POST['status'] ?? 'available';

    if (!$table_number || !$capacity) {
        $_SESSION['error'] = "Vui lòng nhập đầy đủ thông tin.";
    } else {
        try {
            $stmt = $conn->prepare("INSERT INTO tables (table_number, capacity, status) VALUES (?, ?, ?)");
            $stmt->execute([$table_number, $capacity, $status]);
            $_SESSION['success'] = "Thêm bàn mới thành công!";
            header("Location: /nhahang/views/admin/tables.php");
            exit();
        } catch (PDOException $e) {
            $_SESSION['error'] = "Lỗi: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Thêm bàn mới</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <div class="content-wrapper p-4">
    <h3>Thêm bàn mới</h3>

    <?php if (!empty($_SESSION['error'])): ?>
      <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="form-group">
        <label for="table_number">Số bàn</label>
        <input type="text" name="table_number" id="table_number" class="form-control" required>
      </div>
      <div class="form-group">
        <label for="capacity">Sức chứa</label>
        <input type="number" name="capacity" id="capacity" class="form-control" required>
      </div>
      <div class="form-group">
        <label for="status">Trạng thái</label>
        <select name="status" id="status" class="form-control">
          <option value="available">Trống</option>
          <option value="occupied">Đã đặt</option>
        </select>
      </div>
      <button type="submit" class="btn btn-success">Lưu bàn</button>
      <a href="/nhahang/views/admin/tables.php" class="btn btn-secondary">Quay lại</a>
    </form>
  </div>
</div>
</body>
</html>
