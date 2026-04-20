<?php
ini_set('display_errors', 0);
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/db.php';

$masp    = isset($_POST['MaSP'])      ? (int)$_POST['MaSP'] : 0;
$tensp   = trim($_POST['TenSP']       ?? '');
$giaban  = (float)($_POST['GiaBan']   ?? 0);
$ton     = (int)($_POST['SoLuongTon'] ?? 0);
$madm    = (int)($_POST['madanhmuc']  ?? 0);
$mota    = trim($_POST['MoTa']        ?? '');
$noibat  = isset($_POST['NoiBat'])    ? 1 : 0;

if (!$tensp || $giaban <= 0) {
    echo json_encode(['ok' => false, 'error' => 'Tên và giá không được để trống']);
    exit;
}

// Xử lý upload ảnh
$hinh = trim($_POST['hinh_cu'] ?? '');
if (!empty($_FILES['Hinh']['name'])) {
    $ext     = strtolower(pathinfo($_FILES['Hinh']['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
    if (!in_array($ext, $allowed)) {
        echo json_encode(['ok' => false, 'error' => 'Chỉ chấp nhận ảnh jpg/png/webp']);
        exit;
    }
    $newName = 'sp_' . time() . '_' . rand(100, 999) . '.' . $ext;
    $dest    = __DIR__ . '/../../assets/file_anh/' . $newName;
    if (move_uploaded_file($_FILES['Hinh']['tmp_name'], $dest)) {
        $hinh = $newName;
    }
}

$t  = $conn->real_escape_string($tensp);
$mo = $conn->real_escape_string($mota);
$h  = $conn->real_escape_string($hinh);

if ($masp > 0) {
    // CẬP NHẬT
    $ok = $conn->query("
        UPDATE sanpham
        SET TenSP='$t', GiaBan=$giaban, SoLuongTon=$ton,
            madanhmuc=$madm, MoTa='$mo', Hinh='$h', NoiBat=$noibat
        WHERE MaSP=$masp
    ");
    echo json_encode($ok
        ? ['ok' => true, 'msg' => 'Cập nhật sản phẩm thành công']
        : ['ok' => false, 'error' => $conn->error], JSON_UNESCAPED_UNICODE);
} else {
    // THÊM MỚI
    $ok = $conn->query("
        INSERT INTO sanpham (TenSP, GiaBan, SoLuongTon, madanhmuc, MoTa, Hinh, NoiBat)
        VALUES ('$t', $giaban, $ton, $madm, '$mo', '$h', $noibat)
    ");
    echo json_encode($ok
        ? ['ok' => true, 'msg' => 'Thêm sản phẩm thành công', 'id' => $conn->insert_id]
        : ['ok' => false, 'error' => $conn->error], JSON_UNESCAPED_UNICODE);
}
