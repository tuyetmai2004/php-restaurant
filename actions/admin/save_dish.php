<?php
session_start();
require_once '../../config/db.php';

// Kiểm tra quyền admin
if (!isset($_SESSION['user_id']) || strtolower($_SESSION['role']) !== 'admin') {
    header("Location: /nhahang/views/auth/login_register.php");
    exit();
}

// Lấy dữ liệu từ form
$name = trim($_POST['name'] ?? '');
$price = trim($_POST['price'] ?? '');
$region = trim($_POST['region'] ?? '');
$imageName = '';

// Kiểm tra dữ liệu bắt buộc
if (empty($name) || empty($price) || empty($region)) {
    die("❌ Vui lòng nhập đầy đủ thông tin món ăn.");
}

// Xử lý upload ảnh nếu có
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $imageTmp = $_FILES['image']['tmp_name'];
    $imageExt = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

    // Kiểm tra định dạng ảnh hợp lệ (jpg, png, gif)
    $allowedExt = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($imageExt, $allowedExt)) {
        die("❌ Chỉ cho phép upload file ảnh định dạng JPG, PNG, GIF.");
    }

    // Tạo tên file ảnh mới tránh trùng
    $imageName = time() . '_' . uniqid() . '.' . $imageExt;

    // Đường dẫn tuyệt đối đến thư mục lưu ảnh
    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . "/nhahang/assets/images/dishes/";

    // Tạo thư mục nếu chưa tồn tại
    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0777, true)) {
            die("❌ Không thể tạo thư mục lưu ảnh.");
        }
    }

    $uploadPath = $uploadDir . $imageName;

    // Di chuyển file ảnh từ tmp lên thư mục lưu trữ
    if (!move_uploaded_file($imageTmp, $uploadPath)) {
        die("❌ Lỗi khi upload ảnh. Vui lòng kiểm tra quyền thư mục.");
    }
} else {
    die("❌ Vui lòng chọn ảnh hợp lệ.");
}

// Lưu dữ liệu vào database
try {
    $stmt = $conn->prepare("INSERT INTO dishes (name, price, region, image, created_at) VALUES (?, ?, ?, ?, NOW())");
    $stmt->execute([$name, $price, $region, $imageName]);

    // Chuyển hướng về trang danh sách món ăn
    header("Location: /nhahang/views/admin/dishes.php");
    exit();
} catch (PDOException $e) {
    die("❌ Lỗi khi thêm món ăn: " . $e->getMessage());
}
