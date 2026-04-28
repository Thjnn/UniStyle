<?php
ob_start();
ini_set('display_errors', 0);
error_reporting(0);
require_once __DIR__ . '/../config/db.php';
$b = ob_get_clean();
if ($b) {
    header('Content-Type: application/json');
    echo json_encode(['ok' => false, 'error' => strip_tags(trim($b))]);
    exit;
}
header('Content-Type: application/json; charset=utf-8');

$id      = (int)($_POST['id'] ?? 0);
$ten     = trim($_POST['ten_qc']   ?? '');
$vitri   = trim($_POST['vi_tri']   ?? 'banner_home');
$link    = trim($_POST['link']     ?? '');
$mota    = trim($_POST['mo_ta']    ?? '');
$tt      = isset($_POST['trang_thai']) ? 1 : 0;
$thutu   = (int)($_POST['thu_tu']  ?? 0);
$start   = trim($_POST['ngay_bat_dau']  ?? '') ?: 'NULL';
$end     = trim($_POST['ngay_ket_thuc'] ?? '') ?: 'NULL';
$hinhanh = trim($_POST['hinh_cu']  ?? '');

$valid_vt = ['banner_top', 'banner_home', 'banner_home_2', 'banner_home_3', 'popup', 'sidebar'];
if (!$ten) {
    echo json_encode(['ok' => false, 'error' => 'Tên quảng cáo không được để trống']);
    exit;
}
if (!in_array($vitri, $valid_vt)) $vitri = 'banner_home';

// Upload ảnh
if (!empty($_FILES['hinh_anh']['name'])) {
    $ext     = strtolower(pathinfo($_FILES['hinh_anh']['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
    if (in_array($ext, $allowed)) {
        $fn   = 'ads_' . time() . '_' . rand(100, 999) . '.' . $ext;
        $dest = __DIR__ . '/../../assets/file_anh/' . $fn;
        if (move_uploaded_file($_FILES['hinh_anh']['tmp_name'], $dest)) $hinhanh = $fn;
    }
}

$t  = $conn->real_escape_string($ten);
$vt = $conn->real_escape_string($vitri);
$lk = $conn->real_escape_string($link);
$mt = $conn->real_escape_string($mota);
$h  = $conn->real_escape_string($hinhanh);
$s  = $start !== 'NULL' ? "'" . $conn->real_escape_string($start) . "'" : 'NULL';
$e  = $end   !== 'NULL' ? "'" . $conn->real_escape_string($end) . "'"   : 'NULL';

if ($id > 0) {
    $ok = $conn->query("UPDATE quangcao SET ten_qc='$t',vi_tri='$vt',link='$lk',mo_ta='$mt',trang_thai=$tt,thu_tu=$thutu,hinh_anh='$h',ngay_bat_dau=$s,ngay_ket_thuc=$e WHERE id=$id");
} else {
    $ok = $conn->query("INSERT INTO quangcao(ten_qc,vi_tri,link,mo_ta,trang_thai,thu_tu,hinh_anh,ngay_bat_dau,ngay_ket_thuc) VALUES('$t','$vt','$lk','$mt',$tt,$thutu,'$h',$s,$e)");
}
echo json_encode($ok ? ['ok' => true, 'msg' => 'Đã lưu thành công'] : ['ok' => false, 'error' => $conn->error], JSON_UNESCAPED_UNICODE);
