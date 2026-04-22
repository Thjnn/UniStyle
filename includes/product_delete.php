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

$masp = isset($_GET['masp']) ? (int)$_GET['masp'] : 0;
if (!$masp) {
    echo json_encode(['ok' => false, 'error' => 'Thiếu mã SP']);
    exit;
}

// Kiểm tra còn trong đơn hàng không
$used = (int)$conn->query("SELECT COUNT(*) as c FROM chitietdathang WHERE MaSP=$masp")->fetch_assoc()['c'];
if ($used > 0) {
    echo json_encode(['ok' => false, 'error' => "Sản phẩm đang có trong $used đơn hàng, không thể xóa"]);
    exit;
}

$ok = $conn->query("DELETE FROM sanpham WHERE MaSP=$masp");
echo json_encode($ok
    ? ['ok' => true]
    : ['ok' => false, 'error' => $conn->error], JSON_UNESCAPED_UNICODE);
