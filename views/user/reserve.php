<?php
session_start();
require_once '../../config/db.php';

$isLoggedIn = isset($_SESSION['user_id']);
$username = $_SESSION['name'] ?? '';

if (!$isLoggedIn || strtolower($_SESSION['role'] ?? '') !== 'user') {
    header('Location: /nhahang/views/auth/login_register.php');
    exit();
}

// Thông báo lỗi/thành công
$error = $_SESSION['error'] ?? '';
$success = $_SESSION['success'] ?? '';
unset($_SESSION['error'], $_SESSION['success']);

// Lấy danh sách bàn khả dụng
try {
    $stmt = $conn->prepare("SELECT * FROM tables WHERE status = 'available' ORDER BY capacity ASC");
    $stmt->execute();
    $available_tables = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $available_tables = [];
    $error = "Lỗi khi tải danh sách bàn: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Đặt Bàn - Nhà hàng 3 Miền</title>

  <!-- Font Awesome & Google Fonts -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet" />

  <!-- Custom CSS -->
  <link rel="stylesheet" href="/nhahang/assets/css/style.css" />

  <style>
    .booking-container {
      max-width: 600px;
      margin: 30px auto;
      background: #fff;
      padding: 30px 40px;
      border-radius: 8px;
      box-shadow: 0 0 15px rgba(0,0,0,0.1);
      font-family: 'Montserrat', sans-serif;
    }
    .booking-container h2 {
      text-align: center;
      margin-bottom: 25px;
      font-weight: 600;
      color: #333;
    }
    .form-group {
      margin-bottom: 18px;
    }
    label {
      display: block;
      font-weight: 600;
      margin-bottom: 6px;
      color: #555;
    }
    input, select, textarea {
      width: 100%;
      padding: 10px 12px;
      font-size: 15px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-family: 'Montserrat', sans-serif;
    }
    input:focus, select:focus, textarea:focus {
      border-color: #28a745;
      outline: none;
    }
    button.btn-submit {
      background-color: #28a745;
      border: none;
      padding: 12px 0;
      color: white;
      font-weight: 600;
      font-size: 17px;
      width: 100%;
      border-radius: 5px;
      cursor: pointer;
    }
    button.btn-submit:hover {
      background-color: #218838;
    }
    .alert {
      padding: 15px;
      border-radius: 5px;
      margin-bottom: 20px;
      font-weight: 600;
      text-align: center;
    }
    .alert-error { background: #f8d7da; color: #842029; border: 1px solid #f5c2c7; }
    .alert-success { background: #d1e7dd; color: #0f5132; border: 1px solid #badbcc; }
  </style>
</head>
<body>

<header class="navbar">
  <div class="logo">🍽 Nhà hàng 3 Miền</div>
  <nav>
    <ul>
      <li><a href="/nhahang/index.php">Trang chủ</a></li>
      <li><a href="/nhahang/views/user/menu.php">Thực đơn</a></li>
      <li><a href="/nhahang/views/user/reserve.php" class="active">Đặt bàn</a></li>
      <li><a href="/nhahang/views/contact.php">Liên hệ</a></li>
    </ul>
  </nav>
  <div class="user-info">
    Xin chào, <strong><?= htmlspecialchars($username) ?></strong> |
    <a href="/nhahang/views/user/profile.php">Trang cá nhân</a> |
    <a href="/nhahang/actions/logout.php" class="logout">Đăng xuất</a>
  </div>
</header>

<div class="booking-container">
  <h2>Đặt Bàn Nhà Hàng</h2>

  <?php if ($error): ?>
    <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>
  <?php if ($success): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
  <?php endif; ?>

  <form action="/nhahang/controllers/reservation/process_booking.php" method="POST">
    <div class="form-group">
      <label for="number_of_people">Số người</label>
      <input type="number" id="number_of_people" name="number_of_people" min="1" max="20" required />
    </div>

    <div class="form-group">
      <label for="reservation_date">Ngày đặt</label>
      <input type="date" id="reservation_date" name="reservation_date" required min="<?= date('Y-m-d') ?>" />
    </div>

    <div class="form-group">
      <label for="start_time">Giờ bắt đầu</label>
      <input type="time" id="start_time" name="start_time" required />
    </div>

    <div class="form-group">
      <label for="end_time">Giờ kết thúc</label>
      <input type="time" id="end_time" name="end_time" required />
    </div>

    <div class="form-group">
      <label for="table_id">Chọn bàn</label>
      <select id="table_id" name="table_id" required>
        <option value="">-- Chọn bàn --</option>
        <?php foreach ($available_tables as $table): ?>
          <option value="<?= $table['id'] ?>">
            Bàn số <?= $table['id'] ?> (Sức chứa: <?= $table['capacity'] ?> người)
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="form-group">
      <label for="has_children">Có trẻ em đi cùng?</label>
      <select id="has_children" name="has_children" required>
        <option value="0">Không</option>
        <option value="1">Có</option>
      </select>
    </div>

    <div class="form-group">
      <label for="note">Ghi chú (tuỳ chọn)</label>
      <textarea id="note" name="note" rows="3" placeholder="Ví dụ: Yêu cầu đặt gần cửa sổ..."></textarea>
    </div>

    <div class="form-group">
      <label for="deposit_amount">Tiền đặt cọc (VNĐ)</label>
      <input type="number" id="deposit_amount" name="deposit_amount" min="0" value="0" />
    </div>

    <button type="submit" class="btn-submit">Đặt Bàn</button>
  </form>
</div>

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
        <li><a href="/nhahang/views/user/menu.php">Thực đơn</a></li>
        <li><a href="/nhahang/views/user/reserve.php">Đặt bàn</a></li>
        <li><a href="/nhahang/views/contact.php">Liên hệ</a></li>
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
