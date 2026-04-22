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
    echo json_encode(['ok' => false, 'error' => 'Thiếu mã KH']);
    exit;
}

$don = (int)$conn->query("SELECT COUNT(*) AS c FROM dathang WHERE makh=$makh")->fetch_assoc()['c'];
if ($don > 0) {
    echo json_encode(['ok' => false, 'error' => "Khách hàng có $don đơn hàng, không thể xóa"]);
    exit;
}

$ok = $conn->query("DELETE FROM khachhang WHERE makh=$makh");
echo json_encode($ok ? ['ok' => true] : ['ok' => false, 'error' => $conn->error], JSON_UNESCAPED_UNICODE);
