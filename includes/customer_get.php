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

$makh = isset($_GET['makh']) ? (int)$_GET['makh'] : 0;
if (!$makh) {
    echo json_encode(['ok' => false, 'error' => 'Thiếu mã khách hàng']);
    exit;
}

$kh = $conn->query("SELECT * FROM khachhang WHERE makh=$makh LIMIT 1")->fetch_assoc();
if (!$kh) {
    echo json_encode(['ok' => false, 'error' => 'Không tìm thấy khách hàng']);
    exit;
}

// Thống kê đơn hàng
$stat = $conn->query("
    SELECT COUNT(*) AS tong_don,
           COALESCE(SUM(tongtien),0) AS tong_tien,
           MAX(ngaydat) AS lan_cuoi
    FROM dathang WHERE makh=$makh
")->fetch_assoc();

// 5 đơn gần nhất
$orders = [];
$res = $conn->query("
    SELECT madh, ngaydat, tongtien, trangthai
    FROM dathang WHERE makh=$makh
    ORDER BY madh DESC LIMIT 5
");
if ($res) while ($r = $res->fetch_assoc()) $orders[] = $r;

echo json_encode([
    'ok'     => true,
    'kh'     => $kh,
    'stat'   => $stat,
    'orders' => $orders,
], JSON_UNESCAPED_UNICODE);
