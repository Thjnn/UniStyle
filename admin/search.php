<?php
ini_set('display_errors', 0);
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../config/db.php';

if (!isset($conn) || $conn->connect_error) {
    echo json_encode(['products' => [], 'orders' => [], 'debug' => 'DB lỗi kết nối']);
    exit;
}

$q = trim($_GET['q'] ?? '');
if (mb_strlen($q) < 2) {
    echo json_encode(['products' => [], 'orders' => []]);
    exit;
}

$like = '%' . $conn->real_escape_string($q) . '%';

// ── Sản phẩm: tìm theo TenSP ─────────────────────────────────────────────────
$products = [];
$res_p = $conn->query("
    SELECT MaSP, TenSP, GiaBan, Hinh
    FROM sanpham
    WHERE TenSP LIKE '$like'
    LIMIT 6
");
if ($res_p) {
    while ($r = $res_p->fetch_assoc()) {
        $products[] = [
            'id'    => $r['MaSP'],
            'name'  => $r['TenSP'],
            'sku'   => 'SP-' . str_pad($r['MaSP'], 3, '0', STR_PAD_LEFT),
            'price' => number_format((float)$r['GiaBan'], 0, ',', '.') . 'đ',
            'img'   => $r['Hinh'] ?? '',
        ];
    }
}

// ── Đơn hàng: tìm theo madh ──────────────────────────────────────────────────
$orders = [];
$res_o = $conn->query("
    SELECT d.madh, d.ngaydat, d.tongtien, d.trangthai, kh.tenkh
    FROM dathang d
    LEFT JOIN khachhang kh ON kh.makh = d.makh
    WHERE CAST(d.madh AS CHAR) LIKE '$like'
    LIMIT 4
");
if ($res_o) {
    while ($r = $res_o->fetch_assoc()) {
        $orders[] = [
            'id'       => $r['madh'],
            'customer' => $r['tenkh'] ?? 'Không rõ',
            'date'     => $r['ngaydat'] ? date('d/m/Y', strtotime($r['ngaydat'])) : '?',
            'total'    => number_format((float)$r['tongtien'], 0, ',', '.') . 'đ',
            'status'   => $r['trangthai'] ?? '?',
        ];
    }
}

echo json_encode(['products' => $products, 'orders' => $orders], JSON_UNESCAPED_UNICODE);
