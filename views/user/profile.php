<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /nhahang/views/auth/login_register.php");
    exit();
}
require_once '../../config/db.php';
// Lấy thông tin người dùng
$stmt = $conn->prepare("SELECT name, email, created_at FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$created = date('d/m/Y', strtotime($user['created_at']));
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Trang cá nhân | Nhà hàng 3 Miền</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- AdminLTE CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav ml-auto">
      <li class="nav-item"><a href="/nhahang/actions/logout.php" class="nav-link text-danger"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a></li>
    </ul>
  </nav>
  <!-- Sidebar -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="#" class="brand-link">🍽 Nhà hàng 3 Miền</a>
    <div class="sidebar">
      <div class="user-panel mt-3 pb-3 d-flex">
        <div class="info"><a href="#" class="d-block"><?= htmlspecialchars($user['name']) ?></a></div>
      </div>
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column">
          <li class="nav-item"><a href="/nhahang/views/user/profile.php" class="nav-link active"><i class="nav-icon fas fa-user"></i><p>Hồ sơ</p></a></li>
          <li class="nav-item"><a href="/nhahang/views/user/reserve.php" class="nav-link"><i class="nav-icon fas fa-calendar-check"></i><p>Đặt bàn</p></a></li>
          <li class="nav-item"><a href="/nhahang/index.php" class="nav-link"><i class="nav-icon fas fa-home"></i><p>Trang chủ</p></a></li>
        </ul>
      </nav>
    </div>
  </aside>

  <!-- Content Wrapper -->
  <div class="content-wrapper">
    <div class="content-header"><div class="container-fluid"><h3>Thông tin cá nhân</h3></div></div>
    <section class="content"><div class="container-fluid">
      <div class="card card-primary card-outline">
        <div class="card-body box-profile">
          <div class="text-center">
            <img class="profile-user-img img-fluid img-circle" src="/nhahang/assets/images/default-avatar.png" alt="User profile picture">
          </div>
          <h3 class="profile-username text-center"><?= htmlspecialchars($user['name']) ?></h3>
          <p class="text-muted text-center">Thành viên từ: <?= $created ?></p>
          <ul class="list-group list-group-unbordered mb-3">
            <li class="list-group-item"><b>Email</b> <a class="float-right"><?= htmlspecialchars($user['email']) ?></a></li>
          </ul>
          <a href="/nhahang/views/user/edit_profile.php" class="btn btn-primary btn-block"><b>Chỉnh sửa thông tin</b></a>
        </div>
      </div>

      <!-- Tabulated content: activity/settings -->
      <div class="card">
        <div class="card-header p-2">
          <ul class="nav nav-pills">
            <li class="nav-item"><a class="nav-link active" href="#activity" data-toggle="tab">Hoạt động</a></li>
            <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Cài đặt</a></li>
          </ul>
        </div>
        <div class="card-body">
          <div class="tab-content">
            <div class="active tab-pane" id="activity">
              <p>Hiện chưa có hoạt động nào...</p>
            </div>
            <div class="tab-pane" id="settings">
              <form class="form-horizontal" action="/nhahang/controllers/user/update_profile.php" method="POST">
                <div class="form-group row"><label class="col-sm-2 col-form-label">Tên</label>
                  <div class="col-sm-10"><input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>"></div></div>
                <div class="form-group row"><label class="col-sm-2 col-form-label">Email</label>
                  <div class="col-sm-10"><input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>"></div></div>
                <div class="form-group row"><label class="col-sm-2 col-form-label"></label>
                  <div class="col-sm-10"><button type="submit" class="btn btn-danger">Lưu thay đổi</button></div></div>
              </form>
            </div>
          </div>
        </div>
      </div>

    </div></section>
  </div>

  <footer class="main-footer text-center"><strong>&copy; 2025 Nhà hàng 3 Miền.</strong></footer>
</div>

<!-- AdminLTE & Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>
</html>
