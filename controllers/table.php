<?php
session_start();
require_once '../config/db.php'; // Kết nối PDO

class TableController {
    private $conn;

    public function __construct($pdo) {
        $this->conn = $pdo;
    }

    // Lấy tất cả bàn
    public function index() {
        $stmt = $this->conn->query("SELECT * FROM tables ORDER BY id ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Thêm bàn
    public function create($table_number, $seats) {
        $sql = "INSERT INTO tables (table_number, seats, status) VALUES (:table_number, :seats, 'available')";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':table_number' => $table_number,
            ':seats' => $seats
        ]);
    }

    // Tìm bàn theo ID
    public function find($id) {
        $sql = "SELECT * FROM tables WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Cập nhật bàn
    public function update($id, $table_number, $seats, $status) {
        $sql = "UPDATE tables SET table_number = :table_number, seats = :seats, status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':table_number' => $table_number,
            ':seats' => $seats,
            ':status' => $status
        ]);
    }

    // Xóa bàn
    public function delete($id) {
        $sql = "DELETE FROM tables WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}

// Khởi tạo controller (nếu cần sử dụng)
$controller = new TableController($conn);
// $tables = $controller->index(); // Gọi ví dụ
?>
