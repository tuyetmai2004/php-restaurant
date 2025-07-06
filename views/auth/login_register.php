<?php
session_start();

// Nếu đã đăng nhập thì chuyển về trang chủ
if (isset($_SESSION['user_id'])) {
    header('Location: /nhahang/home.php');
    exit();
}

$error = $_SESSION['error'] ?? '';
$success = $_SESSION['success'] ?? '';
$old = $_SESSION['old'] ?? [];

unset($_SESSION['error'], $_SESSION['success'], $_SESSION['old']);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Đăng nhập / Đăng ký</title>
  <link rel="stylesheet" href="/nhahang/assets/css/login.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
  <script src="/nhahang/assets/js/script.js" defer></script>
  <style>
    .message { padding: 10px; margin-bottom: 15px; border-radius: 4px; }
    .error { background-color: #f8d7da; color: #721c24; }
    .success { background-color: #d4edda; color: #155724; }
  </style>
</head>
<body>
  <div class="container" id="container">

    <?php if ($error): ?>
      <div class="message error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
      <div class="message success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <!-- Đăng nhập -->
    <div class="form-container sign-in">
     <form action="/nhahang/controllers/auth/login.php" method="POST">
        <h2>Đăng Nhập</h2>
        <div class="social-icons">
          <a href="#"><i class="fab fa-google-plus-g"></i></a>
          <a href="#"><i class="fab fa-facebook-f"></i></a>
          <a href="#"><i class="fab fa-twitter"></i></a>
          <a href="#"><i class="fab fa-linkedin-in"></i></a>
        </div>
        <span>Sử dụng email và mật khẩu</span>
        <input type="email" name="email" placeholder="Email" required value="<?= htmlspecialchars($old['email'] ?? '') ?>" />
        <input type="password" name="password" placeholder="Mật khẩu" required />
        <a href="#">Quên mật khẩu?</a>
        <button type="submit">Đăng Nhập</button>
      </form>
    </div>

    <!-- Đăng ký -->
    <div class="form-container sign-up">
      <form action="/nhahang/controllers/auth/register.php" method="POST">
        <h2>Tạo Tài Khoản</h2>
        <div class="social-icons">
          <a href="#"><i class="fab fa-google-plus-g"></i></a>
          <a href="#"><i class="fab fa-facebook-f"></i></a>
          <a href="#"><i class="fab fa-twitter"></i></a>
          <a href="#"><i class="fab fa-linkedin-in"></i></a>
        </div>
        <span>Đăng ký bằng email</span>
        <input type="text" name="fullname" placeholder="Họ và tên" required value="<?= htmlspecialchars($old['fullname'] ?? '') ?>" />
        <input type="email" name="email" placeholder="Email" required value="<?= htmlspecialchars($old['email'] ?? '') ?>" />
        <input type="password" name="password" placeholder="Mật khẩu" required />
        <button type="submit">Đăng Ký</button>
      </form>
    </div>

    <!-- Panel Chuyển đổi -->
    <div class="toggle-container">
      <div class="toggle">
        <div class="toggle-panel toggle-left">
          <h1>Chào mừng trở lại!</h1>
          <p>Đăng nhập để sử dụng dịch vụ của chúng tôi</p>
          <button id="login">Đăng Nhập</button>
        </div>
        <div class="toggle-panel toggle-right">
          <h1>Xin chào bạn mới!</h1>
          <p>Đăng ký để bắt đầu hành trình cùng chúng tôi</p>
          <button id="register">Đăng Ký</button>
        </div>
      </div>
    </div>

  </div>
</body>
</html>
