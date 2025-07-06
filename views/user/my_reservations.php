<?php
session_start();
require_once '../../config/db.php';

$isLoggedIn = isset($_SESSION['user_id']);
$user_id = $_SESSION['user_id'] ?? 0;
$username = $_SESSION['name'] ?? '';

// Chuyển hướng nếu chưa đăng nhập hoặc không phải user
if (!$isLoggedIn || strtolower($_SESSION['role']) !== 'user') {
    header("Location: /nhahang/views/auth/login_register.php");
    exit();
}

try {
    $stmt = $conn->prepare("SELECT r.*, t.table_number FROM reservations r
                            JOIN tables t ON r.table_id = t.id
                            WHERE r.user_id = ?
                            ORDER BY r.reservation_time DESC");
    $stmt->execute([$user_id]);
    $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Lỗi truy vấn: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <title>Lịch sử đặt bàn - Nhà hàng 3 Miền</title>
  <link rel="stylesheet" href="/nhahang/assets/css/style.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>
  <header class="navbar">
    <div class="logo">🍽 Nhà hàng 3 Miền</div>
    <nav>
      <ul>
        <li><a href="/nhahang/views/user/home.php">Trang chủ</a></li>
        <li><a href="/nhahang/pages/menu.php">Thực đơn</a></li>
        <li><a href="/nhahang/views/user/reserve.php">Đặt bàn</a></li>
        <li><a href="/nhahang/views/user/my_reservations.php" class="active">Lịch sử</a></li>
      </ul>
    </nav>
    <div class="user-info">
      Xin chào, <strong><?= htmlspecialchars($username) ?></strong> |
      <a href="/nhahang/views/user/profile.php">Trang cá nhân</a> |
      <a href="/nhahang/logout.php" class="logout">Đăng xuất</a>
    </div>
  </header>

  <main style="padding: 40px 60px;">
    <h2 style="color:#e67e22; margin-bottom:20px;">Lịch sử đặt bàn của bạn</h2>
    <?php if (empty($reservations)): ?>
      <p>Chưa có lịch sử đặt bàn.</p>
    <?php else: ?>
      <table style="width:100%; border-collapse: collapse;">
        <thead>
          <tr style="background-color:#f8f8f8; color:#333;">
            <th style="padding: 10px; border: 1px solid #ddd;">#</th>
            <th style="padding: 10px; border: 1px solid #ddd;">Thời gian</th>
            <th style="padding: 10px; border: 1px solid #ddd;">Số người</th>
            <th style="padding: 10px; border: 1px solid #ddd;">Bàn</th>
            <th style="padding: 10px; border: 1px solid #ddd;">Ghi chú</th>
            <th style="padding: 10px; border: 1px solid #ddd;">Trạng thái</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($reservations as $index => $r): ?>
            <tr>
              <td style="padding: 10px; border: 1px solid #ddd;"><?= $index + 1 ?></td>
              <td style="padding: 10px; border: 1px solid #ddd;"><?= date('d/m/Y H:i', strtotime($r['reservation_time'])) ?></td>
              <td style="padding: 10px; border: 1px solid #ddd;"><?= $r['number_of_people'] ?></td>
              <td style="padding: 10px; border: 1px solid #ddd;">Bàn <?= htmlspecialchars($r['table_number']) ?></td>
              <td style="padding: 10px; border: 1px solid #ddd;"><?= htmlspecialchars($r['note']) ?></td>
              <td style="padding: 10px; border: 1px solid #ddd;">
                <?php
                  switch ($r['status']) {
                    case 'pending': echo '<span style="color:orange;">Chờ xác nhận</span>'; break;
                    case 'confirmed': echo '<span style="color:green;">Đã xác nhận</span>'; break;
                    case 'cancelled': echo '<span style="color:red;">Đã huỷ</span>'; break;
                    default: echo htmlspecialchars($r['status']);
                  }
                ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </main>

  <footer class="main-footer" style="margin-top: 50px;">
    <div class="footer-bottom" style="text-align: center; padding: 20px; background-color: #2f2f2f; color: #fff;">
      &copy; 2025 Nhà hàng 3 Miền. Tất cả quyền được bảo lưu.
    </div>
  </footer>
</body>
</html>
