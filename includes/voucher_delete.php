<?php
ob_start(); // bắt mọi output trước header
ini_set('display_errors', 0);
error_reporting(0);

require_once __DIR__ . '/../config/db.php';

$buffered = ob_get_clean(); // lấy và xóa buffer
if ($buffered) {
    // có output lạ (thường là lỗi PHP từ db.php) → trả lỗi JSON
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['ok' => false, 'error' => 'PHP output: ' . strip_tags(trim($buffered))]);
    exit;
}
header('Content-Type: application/json; charset=utf-8');

$type = trim($_GET['type'] ?? 'voucher');
$id   = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$id) {
    echo json_encode(['ok' => false, 'error' => 'Thiếu ID']);
    exit;
}

if ($type === 'campaign') {
    $ok = $conn->query("DELETE FROM khuyenmai WHERE MaKhuyenMai=$id");
} else {
    $conn->query("DELETE FROM khachhang_voucher WHERE id_voucher=$id");
    $ok = $conn->query("DELETE FROM voucher WHERE id_voucher=$id");
}
echo json_encode($ok ? ['ok' => true] : ['ok' => false, 'error' => $conn->error], JSON_UNESCAPED_UNICODE);
