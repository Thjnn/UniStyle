<?php
ini_set('display_errors', 0);
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/db.php';

$type = trim($_GET['type'] ?? 'voucher'); // voucher | km
$id   = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$id) {
    echo json_encode(['ok' => true, 'data' => null, 'type' => $type]);
    exit;
}

if ($type === 'voucher') {
    $row = $conn->query("SELECT * FROM voucher WHERE id_voucher=$id LIMIT 1")->fetch_assoc();
    if (!$row) {
        echo json_encode(['ok' => false, 'error' => 'Không tìm thấy voucher']);
        exit;
    }
    // Đếm lượt dùng
    $luot = (int)$conn->query("SELECT COUNT(*) AS c FROM khachhang_voucher WHERE id_voucher=$id AND trang_thai=1")->fetch_assoc()['c'];
    $row['luot_dung'] = $luot;
} else {
    $row = $conn->query("SELECT * FROM khuyenmai WHERE MaKhuyenMai=$id LIMIT 1")->fetch_assoc();
    if (!$row) {
        echo json_encode(['ok' => false, 'error' => 'Không tìm thấy chương trình']);
        exit;
    }
}

echo json_encode(['ok' => true, 'data' => $row, 'type' => $type], JSON_UNESCAPED_UNICODE);
