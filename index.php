<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']);
$username = $_SESSION['name'] ?? '';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Nhà hàng 3 Miền - Trang Chủ</title>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet" />

  <!-- Style -->
  <link rel="stylesheet" href="/nhahang/assets/css/style.css" />
</head>
<body>
  <header class="navbar">
    <div class="logo">🍽 Nhà hàng 3 Miền</div>
    <nav>
      <ul>
        <li><a href="/nhahang/index.php">Trang chủ</a></li>
       <li><a href="/nhahang/pages/menu.php">Thực đơn</a></li>
        <li><a href="<?= $isLoggedIn ? '/nhahang/views/user/reserve.php' : '/nhahang/views/auth/login_register.php' ?>">Đặt bàn</a></li>
         <li><a href="/nhahang/pages/contact.php">Liên hệ</a></li>
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

  <section class="hero">
    <div class="hero-text">
      <h1>Khám phá hương vị ba miền</h1>
      <p>Ẩm thực Việt tinh tế, đậm đà và hiện đại – đặt bàn ngay để trải nghiệm món ngon từ Bắc - Trung - Nam.</p>
      <a href="<?= $isLoggedIn ? '/nhahang/views/user/reserve.php' : '/nhahang/views/auth/login_register.php' ?>" class="btn">Đặt Bàn Ngay</a>
    </div>
    <div class="hero-images">
      <img src="/nhahang/assets/images/hero-main.jpg" alt="Món ăn chính" class="main-img" />
      <div class="small-imgs">
        <img src="/nhahang/assets/images/small1.jpg" alt="Món ăn phụ 1" />
        <img src="/nhahang/assets/images/small2.jpg" alt="Món ăn phụ 2" />
        <img src="/nhahang/assets/images/small3.jpg" alt="Món ăn phụ 3" />
      </div>
    </div>
  </section>

  <section class="blog-section">
    <h2>Latest from the blog</h2>
    <div class="blog-list">
      <div class="blog-card">
        <img src="/nhahang/assets/images/com-tam.jpg" alt="Blog 1">
        <div class="blog-content">
          <h3>Ẩm thực miền Bắc: Tinh tế trong từng món</h3>
          <p>Cùng khám phá sự thanh đạm và cân bằng trong các món ăn đặc trưng như phở, bún thang, chả cá...</p>
          <a href="#" class="read-more">Đọc thêm</a>
        </div>
      </div>

      <div class="blog-card">
        <img src="/nhahang/assets/images/bun-bo.jpg" alt="Blog 2">
        <div class="blog-content">
          <h3>Hành trình món cay nồng miền Trung</h3>
          <p>Bún bò Huế, mì Quảng và những món ăn đậm đà sắc màu làm say lòng du khách khắp nơi.</p>
          <a href="#" class="read-more">Đọc thêm</a>
        </div>
      </div>

      <div class="blog-card">
        <img src="/nhahang/assets/images/banhxeo.jpg" alt="Blog 3">
        <div class="blog-content">
          <h3>Miền Nam: Phong phú và dân dã</h3>
          <p>Cơm tấm, bánh xèo, hủ tiếu – tinh hoa ẩm thực miền Nam với hương vị mộc mạc, gần gũi.</p>
          <a href="#" class="read-more">Đọc thêm</a>
        </div>
      </div>
    </div>
  </section>

  <section class="booking-section">
    <h2>Đặt bàn trực tuyến</h2>
    <p>Hãy đặt bàn ngay để trải nghiệm hương vị ba miền tuyệt vời cùng gia đình, bạn bè.</p>
    <?php if ($isLoggedIn): ?>
      <form action="/nhahang/controllers/reservation.php" method="POST" class="booking-form">
        <input type="datetime-local" name="reservation_time" required />
        <select name="table_id">
          <option value="1">Bàn 1 - 4 chỗ</option>
          <option value="2">Bàn 2 - 6 chỗ</option>
          <option value="3">Bàn 3 - VIP</option>
        </select>
        <input type="text" name="note" placeholder="Ghi chú (tuỳ chọn)" />
        <button type="submit" name="reserve">Xác nhận</button>
      </form>
    <?php else: ?>
      <p>Vui lòng <a href="/nhahang/views/auth/login_register.php">đăng nhập</a> để đặt bàn.</p>
    <?php endif; ?>
  </section>

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
          <li><a href="#">Thực đơn</a></li>
          <li><a href="<?= $isLoggedIn ? '/nhahang/views/user/reserve.php' : '/nhahang/views/auth/login.php' ?>">Đặt bàn</a></li>
          <li><a href="#">Liên hệ</a></li>
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
