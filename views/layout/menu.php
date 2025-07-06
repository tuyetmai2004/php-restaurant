<?php
if (session_status() == PHP_SESSION_NONE) session_start();
?>

<aside style="width: 200px; float: left; background-color: #f9f9f9; padding: 15px; height: 100vh; box-shadow: 2px 0 5px rgba(0,0,0,0.05);">
    <h3>Chức năng</h3>
    <ul style="list-style: none; padding: 0;">
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <li><a href="../admin/dashboard.php">📊 Thống kê</a></li>
            <li><a href="../admin/tables.php">🪑 Quản lý bàn</a></li>
            <li><a href="../admin/reservations.php">📋 Quản lý đặt bàn</a></li>
        <?php elseif (isset($_SESSION['role']) && $_SESSION['role'] === 'user'): ?>
            <li><a href="../user/home.php">🏠 Trang chủ</a></li>
            <li><a href="../user/reserve.php">📝 Đặt bàn</a></li>
            <li><a href="../user/my_reservations.php">📄 Bàn đã đặt</a></li>
        <?php endif; ?>

        <li><a href="../auth/change_password.php">🔒 Đổi mật khẩu</a></li>
        <li><a href="../../logout.php" style="color: red;">🚪 Đăng xuất</a></li>
    </ul>
</aside>
