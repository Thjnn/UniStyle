<?php
ini_set('display_errors', 0);
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/db.php';

$madh = isset($_GET['madh']) ? (int)$_GET['madh'] : 0;
if (!$madh) {
    echo json_encode(['error' => 'Thiếu mã đơn']);
    exit;
}

// ── Thông tin đơn hàng + khách hàng ─────────────────────────────────────────
$r = $conn->query("
    SELECT dh.madh, dh.ngaydat, dh.tongtien, dh.trangthai,
           kh.tenkh, kh.sdt, kh.diachi
    FROM dathang dh
    LEFT JOIN khachhang kh ON kh.makh = dh.makh
    WHERE dh.madh = $madh
    LIMIT 1
")->fetch_assoc();

if (!$r) {
    echo json_encode(['error' => 'Không tìm thấy đơn']);
    exit;
}

// ── Chi tiết sản phẩm trong đơn ─────────────────────────────────────────────
$items = [];
$res = $conn->query("
    SELECT sp.TenSP, sp.GiaBan, sp.Hinh, ct.soluong,
           (sp.GiaBan * ct.soluong) AS thanhtien
    FROM chitietdathang ct
    JOIN sanpham sp ON ct.MaSP = sp.MaSP
    WHERE ct.madh = $madh
");
while ($row = $res->fetch_assoc()) {
    $items[] = [
        'ten'      => $row['TenSP'],
        'hinh'     => $row['Hinh'] ?? '',
        'giaban'   => (float)$row['GiaBan'],
        'soluong'  => (int)$row['soluong'],
        'thanhtien' => (float)$row['thanhtien'],
    ];
}

echo json_encode([
    'madh'     => $r['madh'],
    'ngaydat'  => date('d/m/Y', strtotime($r['ngaydat'])),
    'tongtien' => (float)$r['tongtien'],
    'trangthai' => $r['trangthai'],
    'tenkh'    => $r['tenkh'] ?? 'Không rõ',
    'sdt'      => $r['sdt'] ?? '',
    'diachi'   => $r['diachi'] ?? '',
    'items'    => $items,
], JSON_UNESCAPED_UNICODE);
