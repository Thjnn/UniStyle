<?php
ini_set('display_errors', 0);
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/db.php';

$type = trim($_POST['type'] ?? 'voucher');

if ($type === 'voucher') {
    $id          = (int)($_POST['id_voucher']     ?? 0);
    $ma          = trim($_POST['ma_code']          ?? '');
    $ten         = trim($_POST['ten_voucher']       ?? '');
    $loai        = (int)($_POST['loai_voucher']    ?? 2);
    $hinh_thuc   = (int)($_POST['hinh_thuc_giam'] ?? 1);
    $gia_tri     = (int)($_POST['gia_tri_giam']    ?? 0);
    $giam_toi_da = (int)($_POST['giam_toi_da']     ?? 0);
    $don_toi_thieu = (int)($_POST['don_toi_thieu']   ?? 0);
    $so_luong    = (int)($_POST['so_luong']         ?? 0);
    $ngay_bd     = trim($_POST['ngay_bat_dau']      ?? '');
    $ngay_kt     = trim($_POST['ngay_ket_thuc']     ?? '');

    if (!$ma || !$ten) {
        echo json_encode(['ok' => false, 'error' => 'Mã code và tên không được để trống']);
        exit;
    }

    $ma  = $conn->real_escape_string($ma);
    $ten = $conn->real_escape_string($ten);
    $bd  = $ngay_bd ? "'$ngay_bd'" : 'NULL';
    $kt  = $ngay_kt ? "'$ngay_kt'" : 'NULL';

    if ($id > 0) {
        $ok = $conn->query("UPDATE voucher SET ma_code='$ma',ten_voucher='$ten',loai_voucher=$loai,hinh_thuc_giam=$hinh_thuc,gia_tri_giam=$gia_tri,giam_toi_da=$giam_toi_da,don_toi_thieu=$don_toi_thieu,so_luong=$so_luong,ngay_bat_dau=$bd,ngay_ket_thuc=$kt WHERE id_voucher=$id");
    } else {
        // Kiểm tra mã trùng
        $exist = (int)$conn->query("SELECT COUNT(*) AS c FROM voucher WHERE ma_code='$ma'")->fetch_assoc()['c'];
        if ($exist) {
            echo json_encode(['ok' => false, 'error' => 'Mã code đã tồn tại']);
            exit;
        }
        $ok = $conn->query("INSERT INTO voucher (ma_code,ten_voucher,loai_voucher,hinh_thuc_giam,gia_tri_giam,giam_toi_da,don_toi_thieu,ap_dung_tat_ca,so_luong,ngay_bat_dau,ngay_ket_thuc) VALUES ('$ma','$ten',$loai,$hinh_thuc,$gia_tri,$giam_toi_da,$don_toi_thieu,1,$so_luong,$bd,$kt)");
    }
    echo json_encode($ok ? ['ok' => true, 'msg' => $id ? 'Cập nhật thành công' : 'Thêm voucher thành công'] : ['ok' => false, 'error' => $conn->error], JSON_UNESCAPED_UNICODE);
} else {
    // khuyenmai
    $id  = (int)($_POST['MaKhuyenMai'] ?? 0);
    $ten = trim($_POST['TenKhuyenMai'] ?? '');
    $giam = (float)($_POST['GiamGia']   ?? 0);
    $bd  = trim($_POST['NgayBatDau']   ?? '');
    $kt  = trim($_POST['NgayKetThuc']  ?? '');

    if (!$ten) {
        echo json_encode(['ok' => false, 'error' => 'Tên không được để trống']);
        exit;
    }
    $ten = $conn->real_escape_string($ten);
    $bdv = $bd ? "'$bd'" : 'NULL';
    $ktv = $kt ? "'$kt'" : 'NULL';

    if ($id > 0) {
        $ok = $conn->query("UPDATE khuyenmai SET TenKhuyenMai='$ten',GiamGia=$giam,NgayBatDau=$bdv,NgayKetThuc=$ktv WHERE MaKhuyenMai=$id");
    } else {
        $ok = $conn->query("INSERT INTO khuyenmai (TenKhuyenMai,GiamGia,NgayBatDau,NgayKetThuc) VALUES ('$ten',$giam,$bdv,$ktv)");
    }
    echo json_encode($ok ? ['ok' => true, 'msg' => $id ? 'Cập nhật thành công' : 'Thêm chương trình thành công'] : ['ok' => false, 'error' => $conn->error], JSON_UNESCAPED_UNICODE);
}
