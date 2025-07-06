<?php
session_start();
require_once '../../config/db.php';

// Kiểm tra quyền admin
if (!isset($_SESSION['user_id']) || strtolower($_SESSION['role']) !== 'admin') {
    header("Location: /nhahang/views/auth/login_register.php");
    exit();
}

try {
    $stmt = $conn->prepare("SELECT * FROM dishes ORDER BY created_at DESC");
    $stmt->execute();
    $dishes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Lỗi truy vấn: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Quản lý món ăn - Admin</title>
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
        <a href="/nhahang/logout.php" class="nav-link text-danger">
          <i class="fas fa-sign-out-alt"></i> Đăng xuất
        </a>
      </li>
    </ul>
  </nav>

  <!-- Sidebar -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="#" class="brand-link"><span class="brand-text font-weight-light">🍽 Admin - 3 Miền</span></a>
    <?php include 'sidebar.php'; ?>
  </aside>

  <!-- Content Wrapper -->
  <div class="content-wrapper p-4">
    <div class="content-header">
      <div class="container-fluid">
        <h3 class="mb-3">Danh sách món ăn</h3>
        <a href="add_dish.php" class="btn btn-success mb-3"><i class="fas fa-plus"></i> Thêm món mới</a>

        <table class="table table-bordered table-hover bg-white">
          <thead class="thead-dark">
            <tr>
              <th>#</th>
              <th>Ảnh</th>
              <th>Tên món</th>
              <th>Giá</th>
              <th>Miền</th>
              <th>Hành động</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($dishes as $index => $dish): ?>
              <tr>
                <td><?= $index + 1 ?></td>
                <td>
                  <?php if (!empty($dish['image'])): ?>
                    <img src="/nhahang/assets/images/dishes/<?= htmlspecialchars($dish['image']) ?>" width="80">
                  <?php else: ?>
                    <span class="text-muted">Không có ảnh</span>
                  <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($dish['NAME'] ?? '[Chưa đặt tên]') ?></td>
                <td><?= number_format($dish['price']) ?>đ</td>
                <td><?= ucfirst($dish['region']) ?></td>
                <td>
                  <a href="edit_dish.php?id=<?= $dish['id'] ?>" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
                  <a href="/nhahang/actions/admin/delete_dish.php?id=<?= $dish['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Xoá món này?')"><i class="fas fa-trash"></i></a>
                </td>
              </tr>
            <?php endforeach ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer class="main-footer text-center">
    <strong>&copy; 2025 <a href="#">Nhà hàng 3 Miền</a>.</strong> All rights reserved.
  </footer>

</div>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>
</html>
