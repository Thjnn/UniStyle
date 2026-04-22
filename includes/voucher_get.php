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

$type = trim($_GET['type'] ?? 'voucher'); // voucher | campaign
$id   = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$id) {
    echo json_encode(['ok' => false, 'error' => 'Thiếu ID']);
    exit;
}

if ($type === 'campaign') {
    $r = $conn->query("SELECT * FROM khuyenmai WHERE MaKhuyenMai=$id LIMIT 1")->fetch_assoc();
    echo json_encode($r ? ['ok' => true, 'data' => $r] : ['ok' => false, 'error' => 'Không tìm thấy'], JSON_UNESCAPED_UNICODE);
    exit;
}

$r = $conn->query("SELECT * FROM voucher WHERE id_voucher=$id LIMIT 1")->fetch_assoc();
if (!$r) {
    echo json_encode(['ok' => false, 'error' => 'Không tìm thấy voucher']);
    exit;
}

// Số khách đã lưu
$used = (int)$conn->query("SELECT COUNT(*) AS c FROM khachhang_voucher WHERE id_voucher=$id")->fetch_assoc()['c'];

echo json_encode(['ok' => true, 'data' => $r, 'used' => $used], JSON_UNESCAPED_UNICODE);
