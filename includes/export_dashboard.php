<?php
require_once __DIR__ . '/../config/db.php';

/* =========================
   KPI DASHBOARD
========================= */
$sql_kpi = "
SELECT 
    COALESCE(SUM(tongtien),0) as tong_doanh_thu,
    (SELECT COUNT(*) 
     FROM dathang 
     WHERE DATE(ngaydat) = CURDATE()
    ) as dh_hom_nay,
    (SELECT COUNT(*) FROM khachhang) as tong_kh
FROM dathang 
WHERE trangthai IN ('Hoàn thành','Đã giao')
";

$kpi = $conn->query($sql_kpi)->fetch_assoc();


/* =========================
   DOANH THU 7 NGÀY
========================= */
$sql_7days = "
SELECT 
    d.date as ngay,
    COALESCE(SUM(t.tongtien),0) as doanh_thu
FROM (
    SELECT CURDATE() - INTERVAL 6 DAY as date UNION
    SELECT CURDATE() - INTERVAL 5 DAY UNION
    SELECT CURDATE() - INTERVAL 4 DAY UNION
    SELECT CURDATE() - INTERVAL 3 DAY UNION
    SELECT CURDATE() - INTERVAL 2 DAY UNION
    SELECT CURDATE() - INTERVAL 1 DAY UNION
    SELECT CURDATE()
) d
LEFT JOIN dathang t 
    ON DATE(t.ngaydat) = d.date 
    AND t.trangthai IN ('Hoàn thành','Đã giao')
GROUP BY d.date
ORDER BY d.date ASC
";

$revenue_7days = $conn->query($sql_7days);


/* =========================
   TOP SẢN PHẨM
========================= */
$sql_top = "
SELECT 
    sp.TenSP, 
    SUM(ct.soluong) as da_ban,
    COALESCE(SUM(ct.soluong * ct.dongia),0) as doanh_thu
FROM chitietdathang ct
JOIN sanpham sp ON ct.MaSP = sp.MaSP
GROUP BY sp.MaSP, sp.TenSP
ORDER BY da_ban DESC
LIMIT 10
";

$top_products = $conn->query($sql_top);


/* =========================
   EXPORT CSV
========================= */
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=Dashboard_' . date('d-m-Y') . '.csv');

$output = fopen('php://output', 'w');
fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

/* =========================
   TITLE
========================= */
fputcsv($output, ['BÁO CÁO TỔNG QUAN DASHBOARD']);
fputcsv($output, ['Ngày xuất:', date('d/m/Y H:i')]);
fputcsv($output, []);

/* =========================
   KPI
========================= */
fputcsv($output, ['KPI', 'GIÁ TRỊ']);
fputcsv($output, ['Doanh thu', number_format($kpi['tong_doanh_thu']) . ' VND']);
fputcsv($output, ['Đơn hàng hôm nay', $kpi['dh_hom_nay']]);
fputcsv($output, ['Khách hàng', $kpi['tong_kh']]);
fputcsv($output, []);


/* =========================
   7 NGÀY
========================= */
fputcsv($output, ['DOANH THU 7 NGÀY GẦN NHẤT']);
fputcsv($output, ['Ngày', 'Doanh thu']);

while ($row = $revenue_7days->fetch_assoc()) {
    fputcsv($output, [
        date('d/m', strtotime($row['ngay'])),
        number_format($row['doanh_thu']) . ' VND'
    ]);
}

fputcsv($output, []);


/* =========================
   TOP SẢN PHẨM
========================= */
fputcsv($output, ['SẢN PHẨM BÁN CHẠY']);
fputcsv($output, ['Tên sản phẩm', 'Số lượng', 'Doanh thu']);

while ($row = $top_products->fetch_assoc()) {
    fputcsv($output, [
        $row['TenSP'],
        $row['da_ban'],
        number_format($row['doanh_thu']) . ' VND'
    ]);
}

fclose($output);
exit;
