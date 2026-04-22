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

$type = trim($_POST['type'] ?? 'voucher');

if ($type === 'campaign') {
    $id    = (int)($_POST['id'] ?? 0);
    $ten   = trim($_POST['TenKhuyenMai'] ?? '');
    $giam  = (float)($_POST['GiamGia'] ?? 0);
    $start = trim($_POST['NgayBatDau']  ?? '');
    $end   = trim($_POST['NgayKetThuc'] ?? '');

    if (!$ten) {
        echo json_encode(['ok' => false, 'error' => 'Tên không được để trống']);
        exit;
    }

    $t = $conn->real_escape_string($ten);
    if ($id > 0) {
        $ok = $conn->query("UPDATE khuyenmai SET TenKhuyenMai='$t',GiamGia=$giam,NgayBatDau='$start',NgayKetThuc='$end' WHERE MaKhuyenMai=$id");
    } else {
        $ok = $conn->query("INSERT INTO khuyenmai(TenKhuyenMai,GiamGia,NgayBatDau,NgayKetThuc) VALUES('$t',$giam,'$start','$end')");
    }
    echo json_encode($ok ? ['ok' => true, 'msg' => 'Đã lưu thành công'] : ['ok' => false, 'error' => $conn->error], JSON_UNESCAPED_UNICODE);
    exit;
}

// Voucher
$id         = (int)($_POST['id']            ?? 0);
$ma_code    = strtoupper(trim($_POST['ma_code']         ?? ''));
$ten        = trim($_POST['ten_voucher']    ?? '');
$loai       = (int)($_POST['loai_voucher']  ?? 2);
$htgiam     = (int)($_POST['hinh_thuc_giam'] ?? 1);
$gtgiam     = (int)($_POST['gia_tri_giam']  ?? 0);
$giam_max   = (int)($_POST['giam_toi_da']   ?? 0);
$don_min    = (int)($_POST['don_toi_thieu'] ?? 0);
$ap_dung    = (int)($_POST['ap_dung_tat_ca'] ?? 1);
$so_luong   = (int)($_POST['so_luong']      ?? 0);
$start      = trim($_POST['ngay_bat_dau']   ?? '');
$end        = trim($_POST['ngay_ket_thuc']  ?? '');

if (!$ten || !$ma_code) {
    echo json_encode(['ok' => false, 'error' => 'Tên và mã code là bắt buộc']);
    exit;
}

$t = $conn->real_escape_string($ten);
$mc = $conn->real_escape_string($ma_code);

if ($id > 0) {
    $ok = $conn->query("UPDATE voucher SET ma_code='$mc',ten_voucher='$t',loai_voucher=$loai,hinh_thuc_giam=$htgiam,gia_tri_giam=$gtgiam,giam_toi_da=$giam_max,don_toi_thieu=$don_min,ap_dung_tat_ca=$ap_dung,so_luong=$so_luong,ngay_bat_dau='$start',ngay_ket_thuc='$end' WHERE id_voucher=$id");
} else {
    // Kiểm tra mã code trùng
    $dup = (int)$conn->query("SELECT COUNT(*) AS c FROM voucher WHERE ma_code='$mc'")->fetch_assoc()['c'];
    if ($dup > 0) {
        echo json_encode(['ok' => false, 'error' => "Mã code '$ma_code' đã tồn tại"]);
        exit;
    }
    $ok = $conn->query("INSERT INTO voucher(ma_code,ten_voucher,loai_voucher,hinh_thuc_giam,gia_tri_giam,giam_toi_da,don_toi_thieu,ap_dung_tat_ca,so_luong,ngay_bat_dau,ngay_ket_thuc) VALUES('$mc','$t',$loai,$htgiam,$gtgiam,$giam_max,$don_min,$ap_dung,$so_luong,'$start','$end')");
}
echo json_encode($ok ? ['ok' => true, 'msg' => 'Đã lưu thành công'] : ['ok' => false, 'error' => $conn->error], JSON_UNESCAPED_UNICODE);
