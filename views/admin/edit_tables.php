<?php
session_start();
require_once '../../config/db.php';

// Kiểm tra phân quyền admin
if (!isset($_SESSION['user_id']) || strtolower($_SESSION['role']) !== 'admin') {
    header("Location: /nhahang/views/auth/login_register.php");
    exit();
}

$id = null;
$table_number = '';
$capacity = '';
$status = 'available';
$errors = [];

// Nếu là GET, lấy thông tin bàn để hiển thị form
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = $_GET['id'] ?? null;
    if (!$id) {
        $_SESSION['error'] = "Thiếu ID bàn.";
        header("Location: /nhahang/views/admin/tables.php");
        exit();
    }

    // Lấy dữ liệu bàn theo id
    try {
        $stmt = $conn->prepare("SELECT * FROM tables WHERE id = ?");
        $stmt->execute([$id]);
        $table = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$table) {
            $_SESSION['error'] = "Bàn không tồn tại.";
            header("Location: /nhahang/views/admin/tables.php");
            exit();
        }
        $table_number = $table['table_number'];
        $capacity = $table['capacity'];
        $status = $table['status'];
    } catch (PDOException $e) {
        die("Lỗi truy vấn: " . $e->getMessage());
    }
}

// Nếu là POST, xử lý cập nhật bàn
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    if (!$id) {
        $_SESSION['error'] = "Thiếu ID bàn.";
        header("Location: /nhahang/views/admin/tables.php");
        exit();
    }

    $table_number = trim($_POST['table_number'] ?? '');
    $capacity = intval($_POST['capacity'] ?? 0);
    $status = $_POST['status'] ?? 'available';

    // Validate dữ liệu
    if ($table_number === '') {
        $errors[] = "Số bàn không được để trống.";
    }
    if ($capacity <= 0) {
        $errors[] = "Sức chứa phải lớn hơn 0.";
    }
    if (!in_array($status, ['available', 'occupied', 'reserved'])) {
        $errors[] = "Trạng thái không hợp lệ.";
    }

    if (empty($errors)) {
        try {
            $stmt = $conn->prepare("UPDATE tables SET table_number = ?, capacity = ?, status = ? WHERE id = ?");
            $stmt->execute([$table_number, $capacity, $status, $id]);
            $_SESSION['success'] = "Cập nhật bàn thành công.";
            header("Location: /nhahang/views/admin/tables.php");
            exit();
        } catch (PDOException $e) {
            $errors[] = "Lỗi khi cập nhật bàn: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <title>Sửa Bàn - Admin Nhà hàng 3 Miền</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body class="hold-transition sidebar-mini">

<div class="wrapper">
    <!-- Navbar & Sidebar có thể include hoặc tự làm giống phần bạn có -->

    <div class="content-wrapper" style="padding: 20px;">
        <h3>Sửa Bàn</h3>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul>
                <?php foreach ($errors as $err): ?>
                    <li><?= htmlspecialchars($err) ?></li>
                <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
            <div class="form-group">
                <label for="table_number">Số bàn</label>
                <input type="text" id="table_number" name="table_number" class="form-control" required
                       value="<?= htmlspecialchars($table_number) ?>">
            </div>
            <div class="form-group">
                <label for="capacity">Sức chứa</label>
                <input type="number" id="capacity" name="capacity" class="form-control" required min="1"
                       value="<?= htmlspecialchars($capacity) ?>">
            </div>
            <div class="form-group">
                <label for="status">Trạng thái</label>
                <select id="status" name="status" class="form-control" required>
                    <option value="available" <?= $status === 'available' ? 'selected' : '' ?>>Sẵn sàng</option>
                    <option value="occupied" <?= $status === 'occupied' ? 'selected' : '' ?>>Đang sử dụng</option>
                    <option value="reserved" <?= $status === 'reserved' ? 'selected' : '' ?>>Đã đặt trước</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
            <a href="/nhahang/views/admin/tables.php" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>
</html>
