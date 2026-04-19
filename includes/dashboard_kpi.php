<?php
// ============================================================
//  KẾT NỐI CƠ SỞ DỮ LIỆU — dùng chung file db.php
// ============================================================
require_once __DIR__ . '/../config/db.php';

// ============================================================
//  KPI 1 — DOANH THU THÁNG NÀY & THÁNG TRƯỚC
// ============================================================
$sql_dt = "
    SELECT
        SUM(CASE WHEN MONTH(ngaydat)=MONTH(NOW())  AND YEAR(ngaydat)=YEAR(NOW())
                  AND trangthai != 'Hoàn trả' THEN tongtien ELSE 0 END) AS thang_nay,
        SUM(CASE WHEN MONTH(ngaydat)=MONTH(NOW()-INTERVAL 1 MONTH)
                  AND YEAR(ngaydat)=YEAR(NOW()-INTERVAL 1 MONTH)
                  AND trangthai != 'Hoàn trả' THEN tongtien ELSE 0 END) AS thang_truoc
    FROM dathang
";
$row_dt         = $conn->query($sql_dt)->fetch_assoc();
$dt_thang_nay   = (float)($row_dt['thang_nay']  ?? 0);
$dt_thang_truoc = (float)($row_dt['thang_truoc'] ?? 0);

function format_tien($so)
{
    if ($so >= 1_000_000) {
        $ket_qua = $so / 1_000_000;
        // Nếu chia hết (VD: 2.000.000) thì hiện 0 chữ số, ngược lại hiện 2
        $so_chu_so = (fmod($ket_qua, 1) == 0) ? 0 : 2;
        return number_format($ket_qua, $so_chu_so, ',', '.') . 'tr';
    }
    if ($so >= 1_000) return number_format($so / 1_000, 0, ',', '.') . 'k';
    return number_format($so, 0, ',', '.');
}

$dt_hien_thi  = format_tien($dt_thang_nay);
$dt_phan_tram = $dt_thang_truoc > 0
    ? round(($dt_thang_nay - $dt_thang_truoc) / $dt_thang_truoc * 100, 1) : 0;
$dt_trend = $dt_phan_tram >= 0 ? 'trend-up' : 'trend-down';
$dt_icon  = $dt_phan_tram >= 0 ? '▲' : '▼';

// ============================================================
//  KPI 2 — ĐƠN HÀNG HÔM NAY & HÔM QUA
// ============================================================
$sql_dh = "
    SELECT
        SUM(ngaydat = CURDATE())                   AS hom_nay,
        SUM(ngaydat = CURDATE() - INTERVAL 1 DAY) AS hom_qua
    FROM dathang
";
$row_dh     = $conn->query($sql_dh)->fetch_assoc();
$dh_hom_nay = (int)($row_dh['hom_nay'] ?? 0);
$dh_hom_qua = (int)($row_dh['hom_qua'] ?? 0);
$dh_chenh   = $dh_hom_nay - $dh_hom_qua;
$dh_trend   = $dh_chenh >= 0 ? 'trend-up' : 'trend-down';
$dh_icon    = $dh_chenh >= 0 ? '▲' : '▼';

// ============================================================
//  KPI 3 — KHÁCH HÀNG MỚI THÁNG NÀY & THÁNG TRƯỚC
// ============================================================
$sql_kh = "
    SELECT
        SUM(MONTH(ngay_dangky)=MONTH(NOW()) AND YEAR(ngay_dangky)=YEAR(NOW()))  AS thang_nay,
        SUM(MONTH(ngay_dangky)=MONTH(NOW()-INTERVAL 1 MONTH)
            AND YEAR(ngay_dangky)=YEAR(NOW()-INTERVAL 1 MONTH))                 AS thang_truoc
    FROM khachhang
";
$row_kh = $conn->query($sql_kh)->fetch_assoc();

// Fallback nếu chưa có cột ngay_dangky
if ($row_kh['thang_nay'] === null) {
    $row_kh2        = $conn->query("SELECT COUNT(*) AS tong FROM khachhang")->fetch_assoc();
    $kh_thang_nay   = (int)$row_kh2['tong'];
    $kh_thang_truoc = 0;
} else {
    $kh_thang_nay   = (int)$row_kh['thang_nay'];
    $kh_thang_truoc = (int)$row_kh['thang_truoc'];
}
$kh_phan_tram = $kh_thang_truoc > 0
    ? round(($kh_thang_nay - $kh_thang_truoc) / $kh_thang_truoc * 100, 1) : 0;
$kh_trend = $kh_phan_tram >= 0 ? 'trend-up' : 'trend-down';
$kh_icon  = $kh_phan_tram >= 0 ? '▲' : '▼';

// ============================================================
//  KPI 4 — TỶ LỆ HOÀN TRẢ THÁNG NÀY & THÁNG TRƯỚC
// ============================================================
$sql_ht = "
    SELECT
        COUNT(CASE 
            WHEN MONTH(ngaydat)=MONTH(NOW()) 
             AND YEAR(ngaydat)=YEAR(NOW())
             AND trangthai='Hoàn trả' 
        THEN 1 END) AS ht_thang_nay,

        COUNT(CASE 
            WHEN MONTH(ngaydat)=MONTH(NOW()) 
             AND YEAR(ngaydat)=YEAR(NOW())
        THEN 1 END) AS tong_thang_nay,

        COUNT(CASE 
            WHEN MONTH(ngaydat)=MONTH(NOW()-INTERVAL 1 MONTH)
             AND YEAR(ngaydat)=YEAR(NOW()-INTERVAL 1 MONTH)
             AND trangthai='Hoàn trả' 
        THEN 1 END) AS ht_thang_truoc,

        COUNT(CASE 
            WHEN MONTH(ngaydat)=MONTH(NOW()-INTERVAL 1 MONTH)
             AND YEAR(ngaydat)=YEAR(NOW()-INTERVAL 1 MONTH)
        THEN 1 END) AS tong_thang_truoc

    FROM dathang
";
$row_ht         = $conn->query($sql_ht)->fetch_assoc();
$ht_ty_le_nay   = $row_ht['tong_thang_nay']   > 0
    ? round($row_ht['ht_thang_nay']   / $row_ht['tong_thang_nay']   * 100, 1) : 0;
$ht_ty_le_truoc = $row_ht['tong_thang_truoc'] > 0
    ? round($row_ht['ht_thang_truoc'] / $row_ht['tong_thang_truoc'] * 100, 1) : 0;
$ht_chenh = round($ht_ty_le_nay - $ht_ty_le_truoc, 1);
// Hoàn trả giảm là tốt → đảo màu trend
$ht_trend = $ht_chenh <= 0 ? 'trend-up' : 'trend-down';
$ht_icon  = $ht_chenh <= 0 ? '▼' : '▲';

// ============================================================
//  NGÀY GIỜ CHÀO DASHBOARD
// ============================================================
$thu      = ['Chủ nhật', 'Thứ Hai', 'Thứ Ba', 'Thứ Tư', 'Thứ Năm', 'Thứ Sáu', 'Thứ Bảy'];
$thang_vi = [
    '',
    'tháng 1',
    'tháng 2',
    'tháng 3',
    'tháng 4',
    'tháng 5',
    'tháng 6',
    'tháng 7',
    'tháng 8',
    'tháng 9',
    'tháng 10',
    'tháng 11',
    'tháng 12'
];
$gio  = (int)date('H');
$chao = $gio < 12 ? 'Chào buổi sáng' : ($gio < 18 ? 'Chào buổi chiều' : 'Chào buổi tối');
$ngay_hien_thi = $thu[date('w')] . ', ' . date('d') . ' '
    . $thang_vi[(int)date('m')] . ' năm ' . date('Y')
    . ' — ' . $chao . ', Admin!';
