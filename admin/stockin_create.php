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

$masp   = isset($_POST['masp'])   ? (int)$_POST['masp']   : 0;
$soluong = isset($_POST['soluong']) ? (int)$_POST['soluong'] : 0;

if (!$masp || $soluong <= 0) {
    echo json_encode(['ok' => false, 'error' => 'Số lượng không hợp lệ']);
    exit;
}

$ok = $conn->query("UPDATE sanpham SET SoLuongTon = SoLuongTon + $soluong WHERE MaSP=$masp");
$moi = (int)$conn->query("SELECT SoLuongTon FROM sanpham WHERE MaSP=$masp")->fetch_assoc()['SoLuongTon'];

echo json_encode($ok
    ? ['ok' => true, 'ton_moi' => $moi]
    : ['ok' => false, 'error' => $conn->error], JSON_UNESCAPED_UNICODE);
