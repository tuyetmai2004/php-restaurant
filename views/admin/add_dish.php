<?php
session_start();
require_once '../../config/db.php';

if (!isset($_SESSION['user_id']) || strtolower($_SESSION['role']) !== 'admin') {
    header("Location: /nhahang/views/auth/login_register.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Thêm món ăn - Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a href="/nhahang/logout.php" class="nav-link text-danger"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a>
      </li>
    </ul>
  </nav>

  <!-- Sidebar -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="#" class="brand-link"><span class="brand-text font-weight-light">🍽 Admin - 3 Miền</span></a>
    <?php include 'sidebar.php'; ?>
  </aside>

  <!-- Content -->
  <div class="content-wrapper p-4">
    <div class="container">
      <h3>Thêm món ăn mới</h3>
      <form action="/nhahang/actions/admin/save_dish.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
          <label for="name">Tên món</label>
          <input type="text" name="name" class="form-control" required>
        </div>

        <div class="form-group">
          <label for="price">Giá (VNĐ)</label>
          <input type="number" name="price" class="form-control" required>
        </div>

        <div class="form-group">
          <label for="region">Miền</label>
          <select name="region" class="form-control" required>
            <option value="bac">Miền Bắc</option>
            <option value="trung">Miền Trung</option>
            <option value="nam">Miền Nam</option>
          </select>
        </div>

        <div class="form-group">
          <label for="image">Ảnh món ăn</label>
          <input type="file" name="image" class="form-control-file" required>
        </div>

        <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Lưu món ăn</button>
        <a href="dishes.php" class="btn btn-secondary">Quay lại</a>
      </form>
    </div>
  </div>

  <!-- Footer -->
  <footer class="main-footer text-center">
    <strong>&copy; 2025 <a href="#">Nhà hàng 3 Miền</a>.</strong> All rights reserved.
  </footer>

</div>
</body>
</html>
