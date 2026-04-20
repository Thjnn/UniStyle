<?php
ini_set('display_errors', 0);
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/db.php';

$madh      = isset($_GET['madh']) ? (int)$_GET['madh'] : 0;
$trangthai = trim($_GET['trangthai'] ?? '');

// Chấp nhận tất cả trạng thái hợp lệ trong DB
$valid = ['Chờ xử lý', 'Chờ xác nhận', 'Đang giao', 'Hoàn thành', 'Đã hủy', 'Đang xử lý'];

if (!$madh || !in_array($trangthai, $valid)) {
    echo json_encode(['ok' => false, 'error' => "Trạng thái không hợp lệ: '$trangthai'"]);
    exit;
}

$tt = $conn->real_escape_string($trangthai);
$ok = $conn->query("UPDATE dathang SET trangthai='$tt' WHERE madh=$madh");

echo json_encode(
    $ok
        ? ['ok' => true, 'madh' => $madh, 'trangthai' => $trangthai]
        : ['ok' => false, 'error' => $conn->error],
    JSON_UNESCAPED_UNICODE
);
