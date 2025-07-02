<?php
session_start();

// Kiểm tra đăng nhập và phân quyền admin
if (!isset($_SESSION['user_id']) || strtolower($_SESSION['role']) !== 'admin') {
    header("Location: /nhahang/views/auth/login_register.php");
    exit();
}

$admin_name = $_SESSION['name'] ?? 'Admin';

require_once '../../config/db.php';

try {
    // Tổng số đơn đặt bàn
    $stmt = $conn->query("SELECT COUNT(*) FROM reservations");
    $total_reservations = $stmt->fetchColumn();

    // Số bàn trống
    $stmt = $conn->prepare("SELECT COUNT(*) FROM tables WHERE status = 'available'");
    $stmt->execute();
    $available_tables = $stmt->fetchColumn();

    // Số khách hàng
    $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE role = 'user'");
    $stmt->execute();
    $total_customers = $stmt->fetchColumn();

    // Đơn đang chờ xử lý
    $stmt = $conn->query("SELECT COUNT(*) FROM reservations WHERE status = 'pending'");
    $pending_reservations = $stmt->fetchColumn();

    // Đơn đã xác nhận
    $stmt = $conn->query("SELECT COUNT(*) FROM reservations WHERE status = 'confirmed'");
    $confirmed_reservations = $stmt->fetchColumn();

    // 5 đơn mới nhất
    $stmt = $conn->query("
        SELECT r.id, u.name AS customer_name, r.reservation_time, r.status
        FROM reservations r
        JOIN users u ON r.user_id = u.id
        ORDER BY r.reservation_time DESC
        LIMIT 5
    ");
    $latest_reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Thông báo mới
    $new_notifications = 0;

} catch (PDOException $e) {
    $total_reservations = $available_tables = $total_customers = $pending_reservations = $confirmed_reservations = $new_notifications = 0;
    $latest_reservations = [];
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard | Nhà Hàng 3 Miền</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600&display=fallback">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
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
        <a href="/nhahang/logout.php" class="nav-link text-danger"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a>
      </li>
    </ul>
  </nav>

  <!-- Sidebar -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="#" class="brand-link"><span class="brand-text font-weight-light">🍽 Admin - 3 Miền</span></a>
    <div class="sidebar">
      <div class="user-panel mt-3 pb-3 d-flex">
        <div class="info"><a href="#" class="d-block">👤 <?= htmlspecialchars($admin_name) ?></a></div>
      </div>
      <nav>
        <ul class="nav nav-pills nav-sidebar flex-column">
          <li class="nav-item"><a href="/nhahang/views/admin/dashboard.php" class="nav-link active"><i class="nav-icon fas fa-home"></i><p>Trang chủ</p></a></li>
          <li class="nav-item"><a href="/nhahang/views/admin/reservations.php" class="nav-link"><i class="nav-icon fas fa-calendar-check"></i><p>Quản lý đặt bàn</p></a></li>
          <li class="nav-item"><a href="/nhahang/views/admin/tables.php" class="nav-link"><i class="nav-icon fas fa-chair"></i><p>Quản lý bàn</p></a></li>
          <li class="nav-item"><a href="/nhahang/views/admin/users.php" class="nav-link"><i class="nav-icon fas fa-users"></i><p>Quản lý người dùng</p></a></li>
        </ul>
      </nav>
    </div>
  </aside>

  <!-- Content -->
  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <h3 class="mb-2">Bảng Điều Khiển</h3>
        <p>Chào mừng bạn trở lại, <strong><?= htmlspecialchars($admin_name) ?></strong>!</p>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- Box tổng đơn -->
          <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
              <div class="inner"><h3><?= $total_reservations ?></h3><p>Đơn đặt bàn</p></div>
              <div class="icon"><i class="fas fa-calendar-alt"></i></div>
              <a href="/nhahang/views/admin/reservations.php" class="small-box-footer">Chi tiết <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>

          <!-- Box bàn trống -->
          <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
              <div class="inner"><h3><?= $available_tables ?></h3><p>Số bàn trống</p></div>
              <div class="icon"><i class="fas fa-chair"></i></div>
              <a href="/nhahang/views/admin/tables.php" class="small-box-footer">Quản lý bàn <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>

          <!-- Box khách -->
          <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
              <div class="inner"><h3><?= $total_customers ?></h3><p>Khách hàng</p></div>
              <div class="icon"><i class="fas fa-users"></i></div>
              <a href="/nhahang/views/admin/users.php" class="small-box-footer">Xem danh sách <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>

          <!-- Box thông báo -->
          <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
              <div class="inner"><h3><?= $new_notifications ?></h3><p>Thông báo mới</p></div>
              <div class="icon"><i class="fas fa-bell"></i></div>
              <a href="#" class="small-box-footer">Xem thông báo <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>

          <!-- Box chờ xử lý -->
          <div class="col-lg-3 col-6">
            <div class="small-box bg-secondary">
              <div class="inner"><h3><?= $pending_reservations ?></h3><p>Đơn đang chờ</p></div>
              <div class="icon"><i class="fas fa-hourglass-half"></i></div>
              <a href="/nhahang/views/admin/reservations.php" class="small-box-footer">Xem đơn <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>

          <!-- Box đã xác nhận -->
          <div class="col-lg-3 col-6">
            <div class="small-box bg-primary">
              <div class="inner"><h3><?= $confirmed_reservations ?></h3><p>Đã xác nhận</p></div>
              <div class="icon"><i class="fas fa-check-circle"></i></div>
              <a href="/nhahang/views/admin/reservations.php" class="small-box-footer">Xem đơn <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
        </div>

        <!-- Danh sách đơn mới nhất -->
        <h5 class="mt-4">Đơn đặt bàn mới nhất</h5>
        <table class="table table-bordered bg-white">
          <thead>
            <tr>
              <th>#</th>
              <th>Khách hàng</th>
              <th>Thời gian</th>
              <th>Trạng thái</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($latest_reservations as $res): ?>
              <tr>
                <td><?= $res['id'] ?></td>
                <td><?= htmlspecialchars($res['customer_name']) ?></td>
                <td><?= date('d/m/Y H:i', strtotime($res['reservation_time'])) ?></td>
                <td>
                  <span class="badge badge-<?= $res['status'] === 'confirmed' ? 'success' : ($res['status'] === 'pending' ? 'warning' : 'secondary') ?>">
                    <?= ucfirst($res['status']) ?>
                  </span>
                </td>
              </tr>
            <?php endforeach; ?>
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
