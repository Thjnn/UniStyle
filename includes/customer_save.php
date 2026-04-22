<?php
ob_start();
ini_set('display_errors', 0);
error_reporting(0);
require_once __DIR__ . '/../config/db.php';
$__buf = ob_get_clean();
if ($__buf) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['ok' => false, 'error' => 'PHP error: ' . strip_tags(trim($__buf))]);
    exit;
}
header('Content-Type: application/json; charset=utf-8');

$makh    = isset($_POST['makh'])      ? (int)$_POST['makh']  : 0;
$tenkh   = trim($_POST['tenkh']       ?? '');
$sdt     = trim($_POST['sdt']         ?? '');
$email   = trim($_POST['email']       ?? '');
$diachi  = trim($_POST['diachi']      ?? '');
$gioitinh = trim($_POST['gioitinh']    ?? '');
$ngaysinh = trim($_POST['ngaysinh']    ?? '');
$loai    = trim($_POST['loai']        ?? 'Cá nhân');

$valid_loai = ['Cá nhân', 'Doanh nghiệp', 'VIP'];
if (!in_array($loai, $valid_loai)) $loai = 'Cá nhân';
if (!$tenkh) {
    echo json_encode(['ok' => false, 'error' => 'Tên không được để trống']);
    exit;
}

$t  = $conn->real_escape_string($tenkh);
$s  = $conn->real_escape_string($sdt);
$em = $conn->real_escape_string($email);
$dc = $conn->real_escape_string($diachi);
$gt = $conn->real_escape_string($gioitinh);
$ns = $ngaysinh ? "'$ngaysinh'" : 'NULL';
$lo = $conn->real_escape_string($loai);

if ($makh > 0) {
    $ok = $conn->query("UPDATE khachhang SET tenkh='$t',sdt='$s',email='$em',diachi='$dc',gioitinh='$gt',ngaysinh=$ns,loai='$lo' WHERE makh=$makh");
    echo json_encode($ok ? ['ok' => true, 'msg' => 'Cập nhật thành công'] : ['ok' => false, 'error' => $conn->error], JSON_UNESCAPED_UNICODE);
} else {
    $tdnhap = $conn->real_escape_string($sdt ?: $email);
    $ok = $conn->query("INSERT INTO khachhang (tenkh,sdt,email,diachi,gioitinh,ngaysinh,loai,tendangnhap,matkhau) VALUES ('$t','$s','$em','$dc','$gt',$ns,'$lo','$tdnhap','123456')");
    echo json_encode($ok ? ['ok' => true, 'msg' => 'Thêm khách hàng thành công', 'id' => $conn->insert_id] : ['ok' => false, 'error' => $conn->error], JSON_UNESCAPED_UNICODE);
}
