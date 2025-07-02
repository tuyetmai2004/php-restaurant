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
  <title>Nhà hàng 3 Miền - Thực đơn</title>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet" />

  <!-- Style -->
  <link rel="stylesheet" href="/nhahang/assets/css/style.css" />

  <style>
    /* Thêm một số style riêng cho menu */
    .menu-container {
      max-width: 1100px;
      margin: 40px auto;
      padding: 0 20px;
    }

    .menu-title {
      text-align: center;
      font-size: 2.5rem;
      color: #a52a2a;
      margin-bottom: 40px;
      font-weight: 600;
    }

    .menu-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill,minmax(280px,1fr));
      gap: 30px;
    }

    .menu-item {
      background-color: #fff;
      border-radius: 10px;
      box-shadow: 0 3px 8px rgba(0,0,0,0.1);
      overflow: hidden;
      transition: transform 0.3s ease;
    }

    .menu-item:hover {
      transform: translateY(-8px);
      box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }

    .menu-item img {
      width: 100%;
      height: 180px;
      object-fit: cover;
      display: block;
    }

    .menu-item-content {
      padding: 15px 20px;
    }

    .menu-item-content h3 {
      margin-bottom: 10px;
      color: #a52a2a;
      font-weight: 700;
      font-size: 1.3rem;
    }

    .menu-item-content p {
      color: #555;
      font-size: 0.95rem;
      line-height: 1.4;
    }

    .menu-item-price {
      margin-top: 15px;
      font-weight: 600;
      color: #333;
      font-size: 1.1rem;
    }
  </style>
</head>
<body>
  <header class="navbar">
    <div class="logo">🍽 Nhà hàng 3 Miền</div>
    <nav>
      <ul>
        <li><a href="/nhahang/index.php">Trang chủ</a></li>
        <li><a href="/nhahang/pages/menu.php" class="active">Thực đơn</a></li>
        <li><a href="<?= $isLoggedIn ? '/nhahang/views/user/reserve.php' : '/nhahang/views/auth/login.php' ?>">Đặt bàn</a></li>
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

  <main class="menu-container">
    <h1 class="menu-title">Thực đơn đặc sắc</h1>

    <div class="menu-grid">
      <!-- Món 1 -->
      <div class="menu-item">
        <img src="/nhahang/assets/images/phobo.jpg" alt="Phở bò Hà Nội" />
        <div class="menu-item-content">
          <h3>Phở bò Hà Nội</h3>
          <p>Hương vị truyền thống, nước dùng đậm đà, bánh phở mềm mại.</p>
          <div class="menu-item-price">80,000₫</div>
        </div>
      </div>

      <!-- Món 2 -->
      <div class="menu-item">
        <img src="/nhahang/assets/images/bunbohue.jpg" alt="Bún bò Huế" />
        <div class="menu-item-content">
          <h3>Bún bò Huế</h3>
          <p>Đậm đà cay nồng đặc trưng miền Trung, sợi bún to dai.</p>
          <div class="menu-item-price">75,000₫</div>
        </div>
      </div>

      <!-- Món 3 -->
      <div class="menu-item">
        <img src="/nhahang/assets/images/goicuon.jpg" alt="Gỏi cuốn" />
        <div class="menu-item-content">
          <h3>Gỏi cuốn</h3>
          <p>Tươi ngon với tôm, thịt, rau sống và nước chấm đặc biệt.</p>
          <div class="menu-item-price">50,000₫</div>
        </div>
      </div>

      <!-- Món 4 -->
      <div class="menu-item">
        <img src="/nhahang/assets/images/banhxeo.jpg" alt="Bánh xèo" />
        <div class="menu-item-content">
          <h3>Bánh xèo</h3>
          <p>Vỏ bánh giòn rụm, nhân tôm, thịt, giá đỗ thơm ngon.</p>
          <div class="menu-item-price">65,000₫</div>
        </div>
      </div>

      <!-- Món 5 -->
      <div class="menu-item">
        <img src="/nhahang/assets/images/comtam.jpg" alt="Cơm tấm sườn nướng" />
        <div class="menu-item-content">
          <h3>Cơm tấm sườn nướng</h3>
          <p>Thơm lừng sườn nướng, cơm mềm, chả trứng hấp dẫn.</p>
          <div class="menu-item-price">70,000₫</div>
        </div>
      </div>

      <!-- Món 6 -->
      <div class="menu-item">
        <img src="/nhahang/assets/images/chaocanh.jpg" alt="Cháo cá" />
        <div class="menu-item-content">
          <h3>Cháo cá</h3>
          <p>Thanh đạm, ngon miệng, hương vị đặc trưng miền Bắc.</p>
          <div class="menu-item-price">55,000₫</div>
        </div>
      </div>
    </div>
  </main>

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
