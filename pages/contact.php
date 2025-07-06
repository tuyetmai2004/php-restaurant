<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']);
$username   = $_SESSION['name'] ?? '';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Liên hệ | Nhà hàng 3 Miền</title>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet" />

  <!-- Style chính -->
  <link rel="stylesheet" href="../assets/css/style.css" />
  
  <style>
    /* Override/ bổ sung cho trang liên hệ */
    .contact-container {
      max-width: 800px;
      margin: 60px auto;
      padding: 0 20px;
    }
    .contact-title {
      text-align: center;
      font-size: 2.5rem;
      color: #e67e22;
      margin-bottom: 30px;
      font-weight: 700;
    }
    .contact-form {
      background: #fffefc;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 6px 20px rgba(0,0,0,0.05);
    }
    .contact-form .form-group {
      margin-bottom: 20px;
    }
    .contact-form label {
      display: block;
      font-weight: 600;
      margin-bottom: 8px;
      color: #333;
    }
    .contact-form input,
    .contact-form textarea {
      width: 100%;
      padding: 12px 15px;
      border: 1px solid #ddd;
      border-radius: 8px;
      font-size: 1rem;
      transition: box-shadow 0.2s ease;
    }
    .contact-form input:focus,
    .contact-form textarea:focus {
      border-color: #e67e22;
      box-shadow: 0 0 8px rgba(230,126,34,0.3);
      outline: none;
    }
    .contact-form button {
      background-color: #e67e22;
      color: #fff;
      padding: 12px 30px;
      border: none;
      border-radius: 30px;
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }
    .contact-form button:hover {
      background-color: #cf711a;
    }
  </style>
</head>
<body>
  <!-- Navbar -->
  <header class="navbar">
    <div class="logo">🍽 Nhà hàng 3 Miền</div>
    <nav>
      <ul>
        <li><a href="/nhahang/index.php">Trang chủ</a></li>
        <li><a href="/nhahang/pages/menu.php">Thực đơn</a></li>
        <li><a href="<?= $isLoggedIn ? '/nhahang/views/user/reserve.php' : '/nhahang/views/auth/login.php' ?>">Đặt bàn</a></li>
        <li><a href="/nhahang/pages/contact.php" class="active">Liên hệ</a></li>
      </ul>
    </nav>
    <form class="search-bar" action="/nhahang/search.php" method="GET">
      <input type="text" name="q" placeholder="Tìm món ăn..." />
      <button type="submit"><i class="fas fa-search"></i></button>
    </form>
    <div class="user-info">
      <?php if ($isLoggedIn): ?>
        Xin chào, <strong><?= htmlspecialchars($username) ?></strong> |
        <a href="/nhahang/views/user/profile.php">Trang cá nhân</a> |
        <a href="/nhahang/actions/logout.php" class="logout">Đăng xuất</a>
      <?php else: ?>
        <a href="/nhahang/views/auth/login_register.php">Đăng nhập / Đăng ký</a>
      <?php endif; ?>
    </div>
  </header>

  <!-- Nội dung chính -->
  <div class="contact-container">
    <h1 class="contact-title">Liên hệ với chúng tôi</h1>
    <form class="contact-form" action="/nhahang/actions/send_contact.php" method="POST">
      <div class="form-group">
        <label for="name">Họ và tên</label>
        <input type="text" id="name" name="name" placeholder="Nhập họ và tên" required />
      </div>
      <div class="form-group">
        <label for="email">Địa chỉ Email</label>
        <input type="email" id="email" name="email" placeholder="Nhập email" required />
      </div>
      <div class="form-group">
        <label for="subject">Tiêu đề</label>
        <input type="text" id="subject" name="subject" placeholder="Tiêu đề liên hệ" required />
      </div>
      <div class="form-group">
        <label for="message">Nội dung</label>
        <textarea id="message" name="message" rows="6" placeholder="Nhập nội dung..." required></textarea>
      </div>
      <div class="form-group" style="text-align: center;">
        <button type="submit">Gửi liên hệ</button>
      </div>
    </form>
  </div>

  <!-- Footer -->
  <footer class="main-footer">
    <div class="footer-container">
      <div class="footer-column">
        <h4>Về Chúng Tôi</h4>
        <p>Nhà hàng 3 Miền là nơi hội tụ tinh hoa ẩm thực Bắc - Trung - Nam.</p>
      </div>
      <div class="footer-column">
        <h4>Liên Kết</h4>
        <ul>
          <li><a href="/nhahang/index.php">Trang chủ</a></li>
          <li><a href="/nhahang/pages/menu.php">Thực đơn</a></li>
          <li><a href="<?= $isLoggedIn ? '/nhahang/views/user/reserve.php' : '/nhahang/views/auth/login.php' ?>">Đặt bàn</a></li>
          <li><a href="/nhahang/pages/contact.php">Liên hệ</a></li>
        </ul>
      </div>
      <div class="footer-column">
        <h4>Kết Nối</h4>
        <div class="social-icons">
          <a href="#"><i class="fab fa-facebook-f"></i></a>
          <a href="#"><i class="fab fa-instagram"></i></a>
          <a href="#"><i class="fab fa-youtube"></i></a>
        </div>
        <p>Hotline: 0123 456 789</p>
      </div>
    </div>
    <div class="footer-bottom">
      <p>&copy; 2025 Nhà hàng 3 Miền. Tất cả quyền được bảo lưu.</p>
    </div>
  </footer>
</body>
</html>
