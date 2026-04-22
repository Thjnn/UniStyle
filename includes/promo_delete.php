<?php
ini_set('display_errors', 0);
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/db.php';

$type = trim($_GET['type'] ?? 'voucher');
$id   = (int)($_GET['id']  ?? 0);
if (!$id) {
    echo json_encode(['ok' => false, 'error' => 'Thiếu ID']);
    exit;
}

if ($type === 'voucher') {
    $used = (int)$conn->query("SELECT COUNT(*) AS c FROM khachhang_voucher WHERE id_voucher=$id AND trang_thai=1")->fetch_assoc()['c'];
    if ($used > 0) {
        echo json_encode(['ok' => false, 'error' => "Voucher đã được $used khách dùng, không thể xóa"]);
        exit;
    }
    $conn->query("DELETE FROM khachhang_voucher WHERE id_voucher=$id");
    $ok = $conn->query("DELETE FROM voucher WHERE id_voucher=$id");
} else {
    $ok = $conn->query("DELETE FROM khuyenmai WHERE MaKhuyenMai=$id");
}
echo json_encode($ok ? ['ok' => true] : ['ok' => false, 'error' => $conn->error], JSON_UNESCAPED_UNICODE);
