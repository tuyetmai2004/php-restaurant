<?php
// layout.php - Layout chung AdminLTE cho admin panel

// Đảm bảo biến $pageTitle và $contentFile đã được gán trước khi include layout này
// Ví dụ:
// $pageTitle = "Dashboard";
// $contentFile = __DIR__ . "/dashboard.php";
// include 'layout.php';

if (!isset($pageTitle)) $pageTitle = 'Admin Panel';
if (!isset($contentFile) || !file_exists($contentFile)) {
    $contentFile = __DIR__ . '/dashboard.php'; // mặc định trang dashboard
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?= htmlspecialchars($pageTitle) ?> | Nhà hàng 3 Miền - Admin</title>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="/nhahang/assets/adminlte/plugins/fontawesome-free/css/all.min.css" />
  <!-- AdminLTE CSS -->
  <link rel="stylesheet" href="/nhahang/assets/adminlte/dist/css/adminlte.min.css" />
  <!-- Google Fonts (optional) -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet" />
  
  <style>
    /* Style tuỳ chỉnh nếu cần */
  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button">
          <i class="fas fa-bars"></i>
        </a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="/nhahang/views/admin/dashboard.php" class="nav-link">Dashboard</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="/nhahang/index.php" target="_blank" class="nav-link">Trang chính</a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- User Account Menu -->
      <li class="nav-item dropdown user-menu">
        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
          <img src="/nhahang/assets/adminlte/dist/img/user2-160x160.jpg" class="user-image img-circle elevation-2" alt="User Image" />
          <span class="d-none d-md-inline"><?= htmlspecialchars($_SESSION['name'] ?? 'Admin') ?></span>
        </a>
        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <!-- User image -->
          <li class="user-header bg-primary">
            <img src="/nhahang/assets/adminlte/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image" />
            <p>
              <?= htmlspecialchars($_SESSION['name'] ?? 'Admin') ?>
              <small>Quản trị viên</small>
            </p>
          </li>
          <!-- Menu Footer-->
          <li class="user-footer">
            <a href="/nhahang/views/admin/profile.php" class="btn btn-default btn-flat">Hồ sơ</a>
            <a href="/nhahang/actions/logout.php" class="btn btn-default btn-flat float-right">Đăng xuất</a>
          </li>
        </ul>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/nhahang/views/admin/dashboard.php" class="brand-link">
      <img src="/nhahang/assets/adminlte/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8" />
      <span class="brand-text font-weight-light">Nhà hàng 3 Miền</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="/nhahang/assets/adminlte/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image" />
        </div>
        <div class="info">
          <a href="/nhahang/views/admin/profile.php" class="d-block"><?= htmlspecialchars($_SESSION['name'] ?? 'Admin') ?></a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul
          class="nav nav-pills nav-sidebar flex-column"
          data-widget="treeview"
          role="menu"
          data-accordion="false"
        >
          <li class="nav-item">
            <a href="/nhahang/views/admin/dashboard.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : '' ?>">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="/nhahang/views/admin/reservations.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'reservations.php' ? 'active' : '' ?>">
              <i class="nav-icon fas fa-calendar-check"></i>
              <p>Đặt Bàn</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="/nhahang/views/admin/tables.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'tables.php' ? 'active' : '' ?>">
              <i class="nav-icon fas fa-chair"></i>
              <p>Bàn Ăn</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="/nhahang/views/admin/users.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'users.php' ? 'active' : '' ?>">
              <i class="nav-icon fas fa-users"></i>
              <p>Người Dùng</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="/nhahang/actions/logout.php" class="nav-link">
              <i class="nav-icon fas fa-sign-out-alt"></i>
              <p>Đăng Xuất</p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->

    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" style="min-height: 600px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <h1><?= htmlspecialchars($pageTitle) ?></h1>
      </div>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">
      <?php include $contentFile; ?>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Footer -->
  <footer class="main-footer">
    <strong>&copy; 2025 <a href="/nhahang/index.php">Nhà hàng 3 Miền</a>.</strong> Tất cả quyền được bảo lưu.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 1.0.0
    </div>
  </footer>
  <!-- /.footer -->

</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="/nhahang/assets/adminlte/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="/nhahang/assets/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="/nhahang/assets/adminlte/dist/js/adminlte.min.js"></script>

</body>
</html>
