<?php
// Cấu hình kết nối
$host = 'localhost';
$dbname = 'nhahang';
$username = 'root';
$password = '';

try {
    // Kết nối bằng PDO
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);

    // Thiết lập chế độ lỗi
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Kết nối CSDL thất bại: " . $e->getMessage());
}
?>
