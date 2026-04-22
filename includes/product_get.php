<?php
ob_start();
ini_set('display_errors', 0);
error_reporting(0);
require_once __DIR__ . '/../config/db.php';
$__buf = ob_get_clean();
if ($__buf) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['ok' => false, 'error' => 'PHP error: ' . strip_tags(trim($__buf))]);
    exit;
}
header('Content-Type: application/json; charset=utf-8');

if (!isset($conn) || $conn->connect_error) {
    echo json_encode(['ok' => false, 'error' => 'DB lỗi kết nối']);
    exit;
}

// Lấy danh mục — cột: madanhmuc, tendanhmuc (đã xác nhận từ DB)
$dm_list = [];
$r = $conn->query("SELECT madanhmuc, tendanhmuc FROM danhmuc ORDER BY tendanhmuc");
if ($r) while ($row = $r->fetch_assoc()) $dm_list[] = $row;

$masp = isset($_GET['masp']) ? (int)$_GET['masp'] : 0;

if (!$masp) {
    // Thêm mới — trả về danh mục để render form
    echo json_encode(['ok' => true, 'product' => null, 'danhmuc' => $dm_list], JSON_UNESCAPED_UNICODE);
    exit;
}

$res = $conn->query("SELECT * FROM sanpham WHERE MaSP=$masp LIMIT 1");
if (!$res) {
    echo json_encode(['ok' => false, 'error' => 'Lỗi query: ' . $conn->error]);
    exit;
}
$sp = $res->fetch_assoc();
if (!$sp) {
    echo json_encode(['ok' => false, 'error' => "Không tìm thấy SP #$masp"]);
    exit;
}

echo json_encode(['ok' => true, 'product' => $sp, 'danhmuc' => $dm_list], JSON_UNESCAPED_UNICODE);
