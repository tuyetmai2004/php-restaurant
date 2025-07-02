<?php
if (session_status() == PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Nhà Hàng 3 Miền</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bạn có thể thêm Bootstrap hoặc font đẹp ở đây -->
    <style>
        body { margin: 0; font-family: Arial, sans-serif; }
        header {
            background-color: #fff;
            border-bottom: 2px solid #f0f0f0;
            padding: 10px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #e67e22;
        }
        nav ul {
            list-style: none;
            margin: 0; padding: 0;
            display: flex;
            gap: 20px;
        }
        nav ul li {
            display: inline;
        }
        nav ul li a {
            text-decoration: none;
            color: #333;
            font-weight: bold;
        }
        .user-info {
            font-size: 14px;
            color: #666;
        }
        .logout {
            margin-left: 10px;
            color: red;
            text-decoration: none;
        }
    </style>
</head>
<body>
<header>
    <div class="logo">Nhà Hàng 3 Miền</div>
    <nav>
        <ul>
            <li><a href="../user/home.php">Trang chủ</a></li>
            <li><a href="#">Giới thiệu</a></li>
            <li><a href="../user/reserve.php">Đặt bàn</a></li>
            <li><a href="#">Liên hệ</a></li>
        </ul>
    </nav>
    <div class="user-info">
        <?php if (isset($_SESSION['username'])): ?>
            Xin chào, <strong><?= htmlspecialchars($_SESSION['name']) ?></strong> |
            <a class="logout" href="../../logout.php">Đăng xuất</a>
        <?php else: ?>
            <a href="../auth/login.php">Đăng nhập</a>
        <?php endif; ?>
    </div>
</header>
