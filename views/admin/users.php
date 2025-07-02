<?php
session_start();
require_once '../../config/db.php';

// Kiểm tra phân quyền admin
if (!isset($_SESSION['user_id']) || strtolower($_SESSION['role']) !== 'admin') {
    header("Location: /nhahang/views/auth/login_register.php");
    exit();
}

$admin_name = $_SESSION['name'] ?? 'Admin';

// Truy vấn danh sách người dùng (trừ admin)
try {
    $stmt = $conn->prepare("SELECT * FROM users WHERE role = 'user' ORDER BY created_at DESC");
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Lỗi truy vấn: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Quản lý người dùng | Admin - Nhà Hàng 3 Miền</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a href="/nhahang/actions/logout.php" class="nav-link text-danger">
          <i class="fas fa-sign-out-alt"></i> Đăng xuất
        </a>
      </li>
    </ul>
  </nav>

  <!-- Sidebar -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="#" class="brand-link">
      <span class="brand-text font-weight-light">🍽 Admin - 3 Miền</span>
    </a>
    <div class="sidebar">
      <div class="user-panel mt-3 pb-3 d-flex">
        <div class="info">
          <a href="#" class="d-block">👤 <?= htmlspecialchars($admin_name) ?></a>
        </div>
      </div>
      <nav>
        <ul class="nav nav-pills nav-sidebar flex-column">
          <li class="nav-item">
            <a href="/nhahang/views/admin/dashboard.php" class="nav-link">
              <i class="nav-icon fas fa-home"></i>
              <p>Trang chủ</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="/nhahang/views/admin/reservations.php" class="nav-link">
              <i class="nav-icon fas fa-calendar-check"></i>
              <p>Quản lý đặt bàn</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="/nhahang/views/admin/tables.php" class="nav-link">
              <i class="nav-icon fas fa-chair"></i>
              <p>Quản lý bàn</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="/nhahang/views/admin/users.php" class="nav-link active">
              <i class="nav-icon fas fa-users"></i>
              <p>Quản lý người dùng</p>
            </a>
          </li>
        </ul>
      </nav>
    </div>
  </aside>

  <!-- Content -->
  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <h3 class="mb-2">Danh sách người dùng</h3>
        <?php if (!empty($_SESSION['success'])): ?>
          <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>
        <?php if (!empty($_SESSION['error'])): ?>
          <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">
        <table class="table table-bordered table-hover table-striped bg-white">
          <thead class="thead-dark">
            <tr>
              <th>#</th>
              <th>Họ tên</th>
              <th>Email</th>
              <th>Vai trò</th>
              <th>Ngày tạo</th>
              <th>Hành động</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($users)): ?>
              <tr><td colspan="6" class="text-center">Không có người dùng nào.</td></tr>
            <?php else: ?>
              <?php foreach ($users as $index => $user): ?>
                <tr>
                  <td><?= $index + 1 ?></td>
                  <td><?= htmlspecialchars($user['name']) ?></td>
                  <td><?= htmlspecialchars($user['email']) ?></td>
                  <td><span class="badge badge-info"><?= htmlspecialchars($user['role']) ?></span></td>
                  <td><?= date('d/m/Y H:i', strtotime($user['created_at'])) ?></td>
                  <td>
                    <!-- Thêm chức năng xoá nếu cần -->
                    <a href="#" class="btn btn-sm btn-danger" onclick="return confirm('Xoá người dùng này?')">
                      <i class="fas fa-trash"></i> Xoá
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>
  </div>

  <footer class="main-footer text-center">
    <strong>&copy; 2025 <a href="#">Nhà hàng 3 Miền</a>.</strong> All rights reserved.
  </footer>
</div>
</body>
</html>
