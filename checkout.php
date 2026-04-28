<?php
session_start();
include('./config/db.php');

if (!isset($_SESSION['khachhang_id'])) { header("Location: login.php"); exit(); }
if (empty($_SESSION['cart']))          { header("Location: package.php"); exit(); }

$kh_id   = (int)$_SESSION['khachhang_id'];
$error   = '';
$success = false;

// ── Lấy thông tin khách ───────────────────────────────────────────────────
$stmt = $conn->prepare("SELECT * FROM khachhang WHERE makh = ?");
$stmt->bind_param("i", $kh_id); $stmt->execute();
$user = $stmt->get_result()->fetch_assoc(); $stmt->close();

// ── Tính tổng tiền hàng (chưa giảm) ──────────────────────────────────────
$total_hang = 0;
foreach ($_SESSION['cart'] as $item)
    $total_hang += (int)$item['gia'] * (int)$item['soluong'];

// ══════════════════════════════════════════════════════════════════════════
//  HÀM: Tính giảm giá từ voucher
//  Trả về mảng: ['giam_hang'=>..., 'giam_ship'=>..., 'loi'=>'']
// ══════════════════════════════════════════════════════════════════════════
function tinh_giam($voucher, $total_hang) {
    $now = date('Y-m-d H:i:s');
    $result = ['giam_hang' => 0, 'giam_ship' => 0, 'loi' => ''];

    if (!$voucher) { $result['loi'] = 'Mã voucher không tồn tại.'; return $result; }
    if ($voucher['ngay_ket_thuc'] && $now > $voucher['ngay_ket_thuc'])
        { $result['loi'] = 'Voucher đã hết hạn.'; return $result; }
    if ($voucher['ngay_bat_dau'] && $now < $voucher['ngay_bat_dau'])
        { $result['loi'] = 'Voucher chưa đến ngày áp dụng.'; return $result; }
    if ($voucher['don_toi_thieu'] > 0 && $total_hang < $voucher['don_toi_thieu'])
        { $result['loi'] = 'Đơn hàng chưa đủ tối thiểu '.number_format($voucher['don_toi_thieu']).'đ.'; return $result; }

    // Tính số tiền được giảm
    if ($voucher['hinh_thuc_giam'] == 1) {          // Giảm theo %
        $giam = $total_hang * $voucher['gia_tri_giam'] / 100;
    } else {                                          // Giảm tiền mặt
        $giam = $voucher['gia_tri_giam'];
    }
    // Áp trần giảm tối đa
    if ($voucher['giam_toi_da'] > 0)
        $giam = min($giam, $voucher['giam_toi_da']);
    $giam = min($giam, $total_hang); // không giảm quá tổng tiền hàng

    if ($voucher['loai_voucher'] == 1)      // Giảm ship
        $result['giam_ship'] = $giam;
    else                                    // Giảm sản phẩm
        $result['giam_hang'] = $giam;

    return $result;
}

// ══════════════════════════════════════════════════════════════════════════
//  AJAX: kiểm tra mã voucher realtime
// ══════════════════════════════════════════════════════════════════════════
if (isset($_POST['ajax_check_voucher'])) {
    header('Content-Type: application/json');
    $ma = trim($_POST['ma_code'] ?? '');
    if (!$ma) { echo json_encode(['ok'=>false,'msg'=>'Nhập mã voucher.']); exit(); }

    // Lấy voucher
    $stmt = $conn->prepare("SELECT * FROM voucher WHERE ma_code = ?");
    $stmt->bind_param("s", $ma); $stmt->execute();
    $vc = $stmt->get_result()->fetch_assoc(); $stmt->close();

    // Kiểm tra khách đã lưu chưa (chỉ áp dụng nếu có trong kho)
    $in_kho = false;
    if ($vc) {
        $stmt = $conn->prepare("SELECT trang_thai FROM khachhang_voucher WHERE makh=? AND id_voucher=?");
        $stmt->bind_param("ii", $kh_id, $vc['id_voucher']); $stmt->execute();
        $kv = $stmt->get_result()->fetch_assoc(); $stmt->close();
        if ($kv) {
            if ($kv['trang_thai'] == 1) { echo json_encode(['ok'=>false,'msg'=>'Voucher này bạn đã sử dụng rồi.']); exit(); }
            $in_kho = true;
        }
    }

    $r = tinh_giam($vc, $total_hang);
    if ($r['loi']) { echo json_encode(['ok'=>false,'msg'=>$r['loi']]); exit(); }

    $tong_giam = $r['giam_hang'] + $r['giam_ship'];
    echo json_encode([
        'ok'         => true,
        'giam_hang'  => $r['giam_hang'],
        'giam_ship'  => $r['giam_ship'],
        'tong_giam'  => $tong_giam,
        'thanh_toan' => max(0, $total_hang - $tong_giam),
        'msg'        => 'Áp dụng thành công! Giảm '.number_format($tong_giam).'đ',
        'ten'        => $vc['ten_voucher'],
        'in_kho'     => $in_kho,
    ]);
    exit();
}

// ══════════════════════════════════════════════════════════════════════════
//  XỬ LÝ ĐẶT HÀNG
// ══════════════════════════════════════════════════════════════════════════
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_order'])) {
    $phuongthuctt = $conn->real_escape_string($_POST['phuongthuctt'] ?? '');
    $ghichu       = $conn->real_escape_string($_POST['ghichu'] ?? '');
    $diachi_giao  = $conn->real_escape_string($_POST['diachi_giao'] ?? '');
    $ma_voucher   = trim($_POST['ma_voucher'] ?? '');

    if (!$phuongthuctt) { $error = 'Vui lòng chọn phương thức thanh toán.'; }
    elseif (!$diachi_giao) { $error = 'Vui lòng nhập địa chỉ nhận hàng.'; }
    else {
        // ── Tính voucher ───────────────────────────────────────────────
        $vc_data   = null;
        $giam_hang = 0;
        $giam_ship = 0;

        if ($ma_voucher) {
            $stmt = $conn->prepare("SELECT * FROM voucher WHERE ma_code = ?");
            $stmt->bind_param("s", $ma_voucher); $stmt->execute();
            $vc_data = $stmt->get_result()->fetch_assoc(); $stmt->close();

            // Kiểm tra trong kho khách + chưa dùng
            if ($vc_data) {
                $stmt = $conn->prepare("SELECT id, trang_thai FROM khachhang_voucher WHERE makh=? AND id_voucher=?");
                $stmt->bind_param("ii", $kh_id, $vc_data['id_voucher']); $stmt->execute();
                $kv_row = $stmt->get_result()->fetch_assoc(); $stmt->close();
                if ($kv_row && $kv_row['trang_thai'] == 1) {
                    $error = 'Voucher này bạn đã sử dụng rồi.';
                    goto end_order;
                }
            }

            $r = tinh_giam($vc_data, $total_hang);
            if ($r['loi']) { $error = $r['loi']; goto end_order; }
            $giam_hang = $r['giam_hang'];
            $giam_ship = $r['giam_ship'];
        }

        $phi_ship   = 0;                          // hiện miễn phí ship
        $phi_ship_sau_giam = max(0, $phi_ship - $giam_ship);
        $tong_giam  = $giam_hang + $giam_ship;
        $thanh_toan = max(0, $total_hang - $giam_hang + $phi_ship_sau_giam);

        $conn->begin_transaction();
        try {
            $ngaydat      = date('Y-m-d');
            $trangthai_dh = 'Chờ xác nhận';

            // 1. dathang — lưu tongtien = giá thực trả
            $stmt = $conn->prepare("INSERT INTO dathang (makh, ngaydat, tongtien, trangthai) VALUES (?,?,?,?)");
            $stmt->bind_param("isds", $kh_id, $ngaydat, $thanh_toan, $trangthai_dh);
            $stmt->execute(); $madh = $conn->insert_id; $stmt->close();

            // 2. hoadon
            $trangthai_hd = ($phuongthuctt === 'COD') ? 'Chưa thanh toán' : 'Chờ xác nhận';
            $stmt = $conn->prepare("INSERT INTO hoadon (makh,madh,ngaylap,tongtien,phuongthuctt,trangthai,ghichu) VALUES (?,?,?,?,?,?,?)");
            $stmt->bind_param("iisdsss", $kh_id, $madh, $ngaydat, $thanh_toan, $phuongthuctt, $trangthai_hd, $ghichu);
            $stmt->execute(); $mahoadon = $conn->insert_id; $stmt->close();

            // 3. Chi tiết đơn + kho
            foreach ($_SESSION['cart'] as $item) {
                $masp      = (int)$item['masp'];
                $soluong   = (int)$item['soluong'];
                $dongia    = (float)$item['gia'];
                $thanhtien = $dongia * $soluong;
                $bienthe   = isset($item['bienthe_id']) ? (int)$item['bienthe_id'] : null;

                $stmt = $conn->prepare("INSERT INTO chitietdathang (madh,MaSP,soluong,dongia,thanhtien,bienthe_id) VALUES (?,?,?,?,?,?)");
                $stmt->bind_param("iiiddi", $madh, $masp, $soluong, $dongia, $thanhtien, $bienthe);
                $stmt->execute(); $stmt->close();

                $stmt = $conn->prepare("INSERT INTO chitiet_hoadon (mahoadon,MaSP,bienthe_id,soluong,dongia,thanhtien) VALUES (?,?,?,?,?,?)");
                $stmt->bind_param("iiiidd", $mahoadon, $masp, $bienthe, $soluong, $dongia, $thanhtien);
                $stmt->execute(); $stmt->close();

                $stmt = $conn->prepare("UPDATE sanpham SET SoLuongTon=SoLuongTon-?, SoLuongDaBan=SoLuongDaBan+? WHERE MaSP=? AND SoLuongTon>=?");
                $stmt->bind_param("iiii", $soluong, $soluong, $masp, $soluong);
                $stmt->execute(); $stmt->close();
            }

            // 4. Đánh dấu voucher đã dùng (nếu có trong kho)
            if ($vc_data && $ma_voucher) {
                $stmt = $conn->prepare("UPDATE khachhang_voucher SET trang_thai=1 WHERE makh=? AND id_voucher=?");
                $stmt->bind_param("ii", $kh_id, $vc_data['id_voucher']); $stmt->execute(); $stmt->close();
            }

            // 5. Cập nhật địa chỉ
            if ($diachi_giao && $diachi_giao !== ($user['diachi'] ?? '')) {
                $stmt = $conn->prepare("UPDATE khachhang SET diachi=? WHERE makh=?");
                $stmt->bind_param("si", $diachi_giao, $kh_id); $stmt->execute(); $stmt->close();
            }

            $conn->commit();
            $_SESSION['cart'] = [];
            $_SESSION['last_order_id']  = $madh;
            $_SESSION['last_order_save'] = $thanh_toan;
            $_SESSION['last_giam']       = $tong_giam;
            $success = true;

        } catch (Exception $e) {
            $conn->rollback();
            $error = 'Đã xảy ra lỗi: ' . $e->getMessage();
        }
    }
    end_order:;
}

// ── Header cart count ─────────────────────────────────────────────────────
$totalQty = 0;
if (!empty($_SESSION['cart'])) foreach ($_SESSION['cart'] as $i) $totalQty += $i['soluong'];

// ── Voucher từ profile "Dùng ngay" ───────────────────────────────────────
$voucher_url = trim($_GET['voucher'] ?? '');
?>
<!doctype html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0" />
    <title>Đặt hàng - UniStyle</title>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,0..1" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="shortcut icon" href="assets/file_anh/0c4690d7-3599-4de4-a0a4-841817ead1c0.png" />
    <link rel="stylesheet" href="./assets/css/style.css" />
    <link rel="stylesheet" href="./assets/css/reposive.css" />
    <style>
    * {
        box-sizing: border-box
    }

    body {
        background: #f5f5f5;
        font-family: 'Segoe UI', sans-serif
    }

    .checkout-wrapper {
        max-width: 1000px;
        margin: 30px auto;
        padding: 0 16px 60px;
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 24px
    }

    @media(max-width:768px) {
        .checkout-wrapper {
            grid-template-columns: 1fr
        }
    }

    .co-card {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 1px 4px rgba(0, 0, 0, .08);
        padding: 24px;
        margin-bottom: 16px
    }

    .co-card h3 {
        font-size: 16px;
        font-weight: 600;
        color: #333;
        margin: 0 0 18px;
        padding-bottom: 12px;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        align-items: center;
        gap: 8px
    }

    .co-card h3 .material-symbols-outlined {
        color: #ff6a00;
        font-size: 20px
    }

    .co-addr-row {
        display: flex;
        align-items: flex-start;
        gap: 12px
    }

    .co-addr-icon {
        color: #ff6a00;
        margin-top: 2px
    }

    .co-addr-info {
        flex: 1
    }

    .co-addr-info strong {
        display: block;
        font-size: 15px;
        color: #222;
        margin-bottom: 4px
    }

    .co-addr-input {
        width: 100%;
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        padding: 10px 14px;
        font-size: 14px;
        outline: none;
        transition: border .2s;
        margin-top: 8px
    }

    .co-addr-input:focus {
        border-color: #ff6a00
    }

    .co-item {
        display: flex;
        gap: 12px;
        padding: 12px 0;
        border-bottom: 1px solid #f5f5f5
    }

    .co-item:last-child {
        border-bottom: none
    }

    .co-item img {
        width: 68px;
        height: 68px;
        object-fit: cover;
        border-radius: 4px;
        border: 1px solid #eee
    }

    .co-item-info {
        flex: 1
    }

    .co-item-name {
        font-size: 14px;
        color: #333;
        line-height: 1.4;
        margin-bottom: 4px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden
    }

    .co-item-meta {
        font-size: 13px;
        color: #999
    }

    .co-item-price {
        text-align: right;
        white-space: nowrap
    }

    .co-item-price .unit {
        font-size: 13px;
        color: #999
    }

    .co-item-price .sub {
        font-size: 15px;
        font-weight: 600;
        color: #ee4d2d
    }

    /* Voucher input */
    .vc-row {
        display: flex;
        gap: 8px;
        margin-bottom: 10px
    }

    .vc-row input {
        flex: 1;
        border: 1.5px solid #e0e0e0;
        border-radius: 6px;
        padding: 10px 14px;
        font-size: 14px;
        outline: none;
        transition: border .2s
    }

    .vc-row input:focus {
        border-color: #ff6a00
    }

    .vc-row input.ok {
        border-color: #4caf50;
        background: #f1fff3
    }

    .vc-row input.err {
        border-color: #f44336;
        background: #fff5f5
    }

    .btn-apply-vc {
        background: #ff6a00;
        color: #fff;
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        white-space: nowrap;
        transition: background .2s
    }

    .btn-apply-vc:hover {
        background: #e05a00
    }

    .btn-remove-vc {
        background: #fff;
        color: #f44336;
        border: 1px solid #f44336;
        padding: 10px 14px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 13px;
        transition: all .2s
    }

    .btn-remove-vc:hover {
        background: #fdecea
    }

    #vc-msg {
        font-size: 13px;
        margin-top: 4px;
        min-height: 18px
    }

    #vc-msg.ok {
        color: #2e7d32
    }

    #vc-msg.err {
        color: #c62828
    }

    .vc-tag {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #fff0ea;
        color: #ff6a00;
        border: 1px solid #ffd0b0;
        border-radius: 20px;
        padding: 4px 12px;
        font-size: 13px;
        font-weight: 600;
        margin-top: 6px
    }

    /* Thanh toán */
    .pay-options {
        display: grid;
        gap: 10px
    }

    .pay-option {
        display: flex;
        align-items: center;
        gap: 14px;
        border: 2px solid #e8e8e8;
        border-radius: 8px;
        padding: 14px 16px;
        cursor: pointer;
        transition: border-color .2s, background .2s;
        position: relative
    }

    .pay-option input[type=radio] {
        position: absolute;
        opacity: 0;
        width: 0
    }

    .pay-option.selected {
        border-color: #ff6a00;
        background: #fff8f5
    }

    .pay-option-icon {
        width: 44px;
        height: 44px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        flex-shrink: 0
    }

    .pay-option-icon.cod {
        background: #fff3e0;
        color: #ff6a00
    }

    .pay-option-icon.bank {
        background: #e3f2fd;
        color: #1565c0
    }

    .pay-option-icon.momo {
        background: #fce4ec;
        color: #ad1457
    }

    .pay-option-icon.vnpay {
        background: #e8f5e9;
        color: #2e7d32
    }

    .pay-option-label strong {
        display: block;
        font-size: 15px;
        color: #222
    }

    .pay-option-label span {
        font-size: 13px;
        color: #888
    }

    .pay-radio-dot {
        width: 18px;
        height: 18px;
        border-radius: 50%;
        border: 2px solid #ccc;
        margin-left: auto;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: border-color .2s
    }

    .pay-option.selected .pay-radio-dot {
        border-color: #ff6a00
    }

    .pay-radio-dot::after {
        content: '';
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #ff6a00;
        display: none
    }

    .pay-option.selected .pay-radio-dot::after {
        display: block
    }

    .co-note {
        width: 100%;
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        padding: 10px 14px;
        font-size: 14px;
        resize: vertical;
        min-height: 80px;
        outline: none;
        transition: border .2s
    }

    .co-note:focus {
        border-color: #ff6a00
    }

    /* Summary */
    .summary-card {
        position: sticky;
        top: 20px
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        font-size: 15px;
        margin-bottom: 12px;
        color: #555
    }

    .summary-row.discount {
        color: #ee4d2d;
        font-weight: 500
    }

    .summary-row.total {
        font-size: 18px;
        font-weight: 700;
        color: #ee4d2d;
        margin-top: 16px;
        padding-top: 16px;
        border-top: 1px dashed #e0e0e0
    }

    .btn-order {
        width: 100%;
        padding: 14px;
        background: linear-gradient(135deg, #ff6a00, #ee4d2d);
        color: #fff;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: opacity .2s;
        margin-top: 16px;
        letter-spacing: .5px
    }

    .btn-order:hover {
        opacity: .9
    }

    .btn-back {
        display: block;
        text-align: center;
        margin-top: 12px;
        color: #888;
        font-size: 14px;
        text-decoration: none
    }

    .btn-back:hover {
        color: #ff6a00
    }

    .co-breadcrumb {
        font-size: 14px;
        color: #999;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 6px
    }

    .co-breadcrumb a {
        color: #ff6a00;
        text-decoration: none
    }

    .co-alert-error {
        background: #fdeced;
        color: #c62828;
        border: 1px solid #f5c6c6;
        padding: 12px 16px;
        border-radius: 6px;
        margin-bottom: 16px;
        font-size: 14px
    }

    /* Success */
    .success-box {
        max-width: 520px;
        margin: 60px auto;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, .1);
        padding: 48px 40px;
        text-align: center
    }

    .success-icon {
        font-size: 64px;
        color: #26aa99;
        margin-bottom: 16px
    }

    .success-box h2 {
        font-size: 22px;
        color: #222;
        margin-bottom: 10px
    }

    .success-box p {
        font-size: 15px;
        color: #666;
        margin-bottom: 24px;
        line-height: 1.6
    }

    .btn-goto {
        display: inline-block;
        padding: 12px 28px;
        border-radius: 6px;
        font-size: 15px;
        font-weight: 600;
        text-decoration: none;
        margin: 6px
    }

    .btn-goto-orders {
        background: #ff6a00;
        color: #fff
    }

    .btn-goto-shop {
        background: #fff;
        color: #ff6a00;
        border: 1.5px solid #ff6a00
    }
    </style>
</head>

<body>

    <!-- HEADER -->
    <header>
        <div class="container">
            <div class="header-content">
                <div class="menu-toggle"><span class="material-symbols-outlined">menu</span></div>
                <div class="logo"><a href="index.php"><img
                            src="./assets/file_anh/0c4690d7-3599-4de4-a0a4-841817ead1c0.png" alt="" /></a></div>
                <nav>
                    <ul>
                        <li><a href="index.php">Trang chủ</a></li>
                        <li><a href="shop.php">Cửa hàng</a></li>
                        <li><a href="contact.php">Liên hệ</a></li>
                        <li><a href="FAQ.php">FAQ</a></li>
                        <li><a href="aboutus.php">Về chúng tôi</a></li>
                    </ul>
                </nav>
                <div class="header-icons">
                    <div class="cart-icon">
                        <a href="package.php"><span class="material-symbols-outlined">local_mall</span>
                            <?php if($totalQty>0):?><span class="cart-count"><?=$totalQty?></span><?php endif;?>
                        </a>
                    </div>
                    <a href="profile.php"><span class="material-symbols-outlined">person</span></a>
                </div>
            </div>
        </div>
    </header>

    <!-- NỘI DUNG -->
    <?php if ($success): ?>
    <div class="success-box">
        <div class="success-icon"><span class="material-symbols-outlined" style="font-size:inherit">check_circle</span>
        </div>
        <h2>Đặt hàng thành công!</h2>
        <p>Cảm ơn bạn đã mua hàng tại <strong>UniStyle</strong>.<br>
            Đơn hàng <strong>#<?= $_SESSION['last_order_id'] ?? '' ?></strong> đang được xử lý.
            <?php if(($_SESSION['last_giam']??0)>0): ?>
            <br><span style="color:#ee4d2d;font-weight:600">Bạn đã tiết kiệm
                <?= number_format($_SESSION['last_giam']) ?>đ 🎉</span>
            <?php endif; ?>
        </p>
        <a class="btn-goto btn-goto-orders" href="profile.php?tab=orders">Xem đơn hàng</a>
        <a class="btn-goto btn-goto-shop" href="shop.php">Tiếp tục mua sắm</a>
    </div>
    <?php else: ?>

    <div style="max-width:1000px;margin:0 auto;padding:24px 16px 0">
        <div class="co-breadcrumb">
            <a href="index.php">Trang chủ</a>
            <span class="material-symbols-outlined" style="font-size:16px">chevron_right</span>
            <a href="package.php">Giỏ hàng</a>
            <span class="material-symbols-outlined" style="font-size:16px">chevron_right</span>
            <span style="color:#333">Đặt hàng</span>
        </div>
    </div>

    <form method="POST" action="checkout.php" id="checkoutForm">
        <!-- Field ẩn lưu mã voucher đã xác nhận -->
        <input type="hidden" name="ma_voucher" id="ma_voucher_hidden" value="<?= htmlspecialchars($voucher_url) ?>">

        <div class="checkout-wrapper">

            <!-- ── CỘT TRÁI ── -->
            <div>
                <?php if($error): ?>
                <div class="co-alert-error"><span class="material-symbols-outlined"
                        style="vertical-align:middle;font-size:18px">error</span> <?=$error?></div>
                <?php endif; ?>

                <!-- Địa chỉ -->
                <div class="co-card">
                    <h3><span class="material-symbols-outlined">location_on</span> Địa chỉ nhận hàng</h3>
                    <div class="co-addr-row">
                        <span class="material-symbols-outlined co-addr-icon">person_pin</span>
                        <div class="co-addr-info">
                            <strong><?=htmlspecialchars($user['tenkh'])?> &nbsp;|&nbsp;
                                <?=htmlspecialchars($user['sdt']??'')?></strong>
                            <input type="text" name="diachi_giao" class="co-addr-input"
                                value="<?=htmlspecialchars($user['diachi']??'')?>"
                                placeholder="Nhập địa chỉ nhận hàng..." required />
                        </div>
                    </div>
                </div>

                <!-- Sản phẩm -->
                <div class="co-card">
                    <h3><span class="material-symbols-outlined">inventory_2</span> Sản phẩm đặt mua</h3>
                    <?php foreach($_SESSION['cart'] as $item):
            $sub=(int)$item['gia']*(int)$item['soluong'];
        ?>
                    <div class="co-item">
                        <img src="assets/file_anh/San_Pham/<?=htmlspecialchars($item['hinh'])?>" alt="" />
                        <div class="co-item-info">
                            <div class="co-item-name"><?=htmlspecialchars($item['tensp'])?></div>
                            <?php if(!empty($item['size'])):?><div class="co-item-meta">Phân loại:
                                <?=htmlspecialchars($item['size'])?></div><?php endif;?>
                            <div class="co-item-meta">x<?=(int)$item['soluong']?></div>
                        </div>
                        <div class="co-item-price">
                            <div class="unit"><?=number_format($item['gia'])?>đ / cái</div>
                            <div class="sub"><?=number_format($sub)?>đ</div>
                        </div>
                    </div>
                    <?php endforeach;?>
                </div>

                <!-- VOUCHER -->
                <div class="co-card">
                    <h3><span class="material-symbols-outlined">confirmation_number</span> Mã giảm giá</h3>
                    <div class="vc-row">
                        <input type="text" id="vc_input" placeholder="Nhập mã voucher..."
                            value="<?=htmlspecialchars($voucher_url)?>" maxlength="50" autocomplete="off" />
                        <button type="button" class="btn-apply-vc" id="btn_apply_vc">Áp dụng</button>
                        <button type="button" class="btn-remove-vc" id="btn_remove_vc" style="display:none">Bỏ</button>
                    </div>
                    <div id="vc-msg"></div>
                    <div id="vc-tag-wrap"></div>
                </div>

                <!-- Ghi chú -->
                <div class="co-card">
                    <h3><span class="material-symbols-outlined">edit_note</span> Ghi chú đơn hàng</h3>
                    <textarea name="ghichu" class="co-note"
                        placeholder="Ví dụ: Giao giờ hành chính, gọi trước khi giao..."></textarea>
                </div>

                <!-- Phương thức thanh toán -->
                <div class="co-card">
                    <h3><span class="material-symbols-outlined">payments</span> Phương thức thanh toán</h3>
                    <div class="pay-options">
                        <label class="pay-option selected">
                            <input type="radio" name="phuongthuctt" value="COD" checked />
                            <div class="pay-option-icon cod"><span
                                    class="material-symbols-outlined">local_shipping</span></div>
                            <div class="pay-option-label"><strong>Thanh toán khi nhận hàng (COD)</strong><span>Trả tiền
                                    mặt khi nhận hàng tại nhà</span></div>
                            <div class="pay-radio-dot"></div>
                        </label>
                        <label class="pay-option" id="label-bank">
                            <input type="radio" name="phuongthuctt" value="Chuyển khoản" id="radio-bank" />
                            <div class="pay-option-icon bank"><span
                                    class="material-symbols-outlined">account_balance</span></div>
                            <div class="pay-option-label">
                                <strong>Chuyển khoản BIDV</strong>
                                <span>Quét mã QR — tự động điền số tiền</span>
                            </div>
                            <div class="pay-radio-dot"></div>
                        </label>

                        <!-- ── QR BIDV PANEL (hiện khi chọn chuyển khoản) ── -->
                        <div id="qr-bidv-panel"
                            style="display:none;margin-top:4px;padding:20px;background:#f9f9f9;border:1.5px solid #e0e0e0;border-radius:10px;">
                            <div style="display:flex;gap:24px;align-items:flex-start;flex-wrap:wrap;">

                                <!-- QR Image -->
                                <div style="text-align:center;flex-shrink:0;">
                                    <div
                                        style="background:#fff;border:2px solid #e8e8e8;border-radius:10px;padding:10px;display:inline-block;box-shadow:0 2px 8px rgba(0,0,0,.08);">
                                        <img id="qr-img" src="" alt="QR BIDV"
                                            style="width:180px;height:180px;display:block;border-radius:4px;" />
                                    </div>
                                    <div style="margin-top:8px;font-size:12px;color:#888;">Quét bằng App ngân hàng bất
                                        kỳ</div>
                                </div>

                                <!-- Thông tin TK -->
                                <div style="flex:1;min-width:200px;">
                                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:14px;">
                                        <!-- Logo BIDV text -->
                                        <div
                                            style="background:#1a3c8f;color:#fff;font-weight:900;font-size:16px;letter-spacing:1px;padding:5px 12px;border-radius:6px;">
                                            BIDV</div>
                                        <span style="font-size:13px;color:#555;">Ngân hàng TMCP Đầu tư và Phát triển
                                            VN</span>
                                    </div>

                                    <?php
                        // ══════════════════════════════════════════════════
                        //  ĐỔI THÔNG TIN TÀI KHOẢN BIDV CỦA BẠN Ở ĐÂY
                        // ══════════════════════════════════════════════════
                        $BIDV_STK       = '7211549501';          // ← Số tài khoản BIDV
                        $BIDV_TEN_TK    = 'NGUYEN VAN TRUC';         // ← Tên chủ tài khoản (KHÔNG DẤU)
                        $BIDV_NOI_DUNG  = 'Thanh toan don hang';  // ← Nội dung CK mặc định
                        $BIDV_BANK_CODE = 'BIDV';                 // Giữ nguyên
                        ?>

                                    <table style="width:100%;font-size:14px;border-collapse:collapse;">
                                        <tr style="border-bottom:1px solid #f0f0f0">
                                            <td style="padding:7px 0;color:#888;width:130px;">Ngân hàng</td>
                                            <td style="padding:7px 0;font-weight:600;color:#1a3c8f;">BIDV</td>
                                        </tr>
                                        <tr style="border-bottom:1px solid #f0f0f0">
                                            <td style="padding:7px 0;color:#888;">Số tài khoản</td>
                                            <td style="padding:7px 0;">
                                                <strong
                                                    style="font-size:16px;letter-spacing:.5px"><?= $BIDV_STK ?></strong>
                                                <button type="button" onclick="copyText('<?= $BIDV_STK ?>', this)"
                                                    style="margin-left:8px;background:#f0f4ff;border:1px solid #c7d2fe;color:#4f46e5;border-radius:5px;padding:3px 10px;font-size:12px;cursor:pointer;transition:all .2s">
                                                    Sao chép
                                                </button>
                                            </td>
                                        </tr>
                                        <tr style="border-bottom:1px solid #f0f0f0">
                                            <td style="padding:7px 0;color:#888;">Chủ tài khoản</td>
                                            <td style="padding:7px 0;font-weight:600"><?= $BIDV_TEN_TK ?></td>
                                        </tr>
                                        <tr style="border-bottom:1px solid #f0f0f0">
                                            <td style="padding:7px 0;color:#888;">Số tiền</td>
                                            <td style="padding:7px 0;font-weight:700;color:#ee4d2d;font-size:16px"
                                                id="qr-amount-display">—</td>
                                        </tr>
                                        <tr>
                                            <td style="padding:7px 0;color:#888;">Nội dung CK</td>
                                            <td style="padding:7px 0;">
                                                <span id="qr-note-display"
                                                    style="font-weight:600;color:#333"><?= $BIDV_NOI_DUNG ?></span>
                                                <button type="button"
                                                    onclick="copyText(document.getElementById('qr-note-display').textContent, this)"
                                                    style="margin-left:8px;background:#f0f4ff;border:1px solid #c7d2fe;color:#4f46e5;border-radius:5px;padding:3px 10px;font-size:12px;cursor:pointer">
                                                    Sao chép
                                                </button>
                                            </td>
                                        </tr>
                                    </table>

                                    <div
                                        style="margin-top:14px;background:#fffbe6;border:1px solid #ffe58f;border-radius:8px;padding:10px 14px;font-size:13px;color:#856404;line-height:1.5;">
                                        <span class="material-symbols-outlined"
                                            style="font-size:16px;vertical-align:middle">info</span>
                                        Vui lòng chuyển khoản <strong>đúng số tiền và nội dung</strong> để đơn hàng được
                                        xác nhận nhanh nhất. Đơn sẽ được xử lý sau khi chúng tôi nhận được thanh toán.
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- ── CỘT PHẢI – TÓM TẮT ── -->
            <div>
                <div class="co-card summary-card">
                    <h3><span class="material-symbols-outlined">receipt_long</span> Tóm tắt đơn hàng</h3>

                    <div class="summary-row">
                        <span>Sản phẩm (<?=array_sum(array_column($_SESSION['cart'],'soluong'))?> món)</span>
                        <span id="sum-hang"><?=number_format($total_hang)?>đ</span>
                    </div>
                    <div class="summary-row" id="row-giam-hang" style="display:none">
                        <span>Giảm giá voucher</span>
                        <span id="sum-giam-hang" style="color:#ee4d2d"></span>
                    </div>
                    <div class="summary-row">
                        <span>Phí vận chuyển</span>
                        <span id="sum-ship" style="color:#26aa99">Miễn phí</span>
                    </div>
                    <div class="summary-row" id="row-giam-ship" style="display:none">
                        <span>Giảm phí ship</span>
                        <span id="sum-giam-ship" style="color:#ee4d2d"></span>
                    </div>
                    <div class="summary-row total">
                        <span>Tổng thanh toán</span>
                        <span id="sum-total"><?=number_format($total_hang)?>đ</span>
                    </div>

                    <!-- Nút submit — JS chặn nếu đang chọn BIDV -->
                    <button type="button" id="btn-order-main" class="btn-order" onclick="handleOrderClick()">
                        <span class="material-symbols-outlined"
                            style="vertical-align:middle;font-size:20px">check_circle</span>
                        Xác nhận đặt hàng
                    </button>
                    <!-- Nút submit thật (ẩn) — trigger khi đã xác nhận CK -->
                    <button type="submit" name="btn_order" id="btn-order-real" style="display:none"></button>
                    <a href="package.php" class="btn-back">← Quay lại giỏ hàng</a>
                </div>
            </div>

        </div><!-- end checkout-wrapper -->
    </form>
    <?php endif; ?>

    <script>
    const TOTAL_HANG = <?= $total_hang ?>;
    let vcApplied = false;

    const vcInput = document.getElementById('vc_input');
    const vcHidden = document.getElementById('ma_voucher_hidden');
    const vcMsg = document.getElementById('vc-msg');
    const vcTagWrap = document.getElementById('vc-tag-wrap');
    const btnApply = document.getElementById('btn_apply_vc');
    const btnRemove = document.getElementById('btn_remove_vc');

    // Nếu có voucher từ URL (profile → Dùng ngay), tự động áp dụng
    <?php if ($voucher_url): ?>
    window.addEventListener('DOMContentLoaded', () => applyVoucher());
    <?php endif; ?>

    btnApply.addEventListener('click', applyVoucher);
    vcInput.addEventListener('keydown', e => {
        if (e.key === 'Enter') {
            e.preventDefault();
            applyVoucher();
        }
    });
    btnRemove.addEventListener('click', removeVoucher);

    function applyVoucher() {
        const code = vcInput.value.trim();
        if (!code) {
            showMsg('Vui lòng nhập mã voucher.', 'err');
            return;
        }

        btnApply.textContent = '...';
        btnApply.disabled = true;

        fetch('checkout.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'ajax_check_voucher=1&ma_code=' + encodeURIComponent(code)
            })
            .then(r => r.json())
            .then(d => {
                btnApply.textContent = 'Áp dụng';
                btnApply.disabled = false;

                if (!d.ok) {
                    showMsg(d.msg, 'err');
                    vcInput.classList.remove('ok');
                    vcInput.classList.add('err');
                    resetSummary();
                    vcApplied = false;
                    vcHidden.value = '';
                    return;
                }

                // Thành công
                showMsg(d.msg, 'ok');
                vcInput.classList.remove('err');
                vcInput.classList.add('ok');
                vcInput.readOnly = true;
                btnApply.style.display = 'none';
                btnRemove.style.display = '';
                vcApplied = true;
                vcHidden.value = code;

                // Hiển thị tag voucher
                vcTagWrap.innerHTML = `<div class="vc-tag">
            <span class="material-symbols-outlined" style="font-size:16px">sell</span>
            ${escHtml(d.ten)} &nbsp;–&nbsp; Giảm <strong>${fmtMoney(d.tong_giam)}đ</strong>
        </div>`;

                // Cập nhật tóm tắt
                updateSummary(d.giam_hang, d.giam_ship, d.thanh_toan);
            })
            .catch(() => {
                btnApply.textContent = 'Áp dụng';
                btnApply.disabled = false;
                showMsg('Lỗi kết nối, thử lại.', 'err');
            });
    }

    function removeVoucher() {
        vcInput.value = '';
        vcInput.readOnly = false;
        vcInput.classList.remove('ok', 'err');
        vcHidden.value = '';
        vcApplied = false;
        btnApply.style.display = '';
        btnRemove.style.display = 'none';
        vcMsg.className = '';
        vcMsg.textContent = '';
        vcTagWrap.innerHTML = '';
        resetSummary();
    }

    function updateSummary(giamHang, giamShip, thanhToan) {
        if (giamHang > 0) {
            document.getElementById('row-giam-hang').style.display = '';
            document.getElementById('sum-giam-hang').textContent = '-' + fmtMoney(giamHang) + 'đ';
        }
        if (giamShip > 0) {
            document.getElementById('row-giam-ship').style.display = '';
            document.getElementById('sum-giam-ship').textContent = '-' + fmtMoney(giamShip) + 'đ';
            document.getElementById('sum-ship').textContent = 'Miễn phí';
        }
        document.getElementById('sum-total').textContent = fmtMoney(thanhToan) + 'đ';
    }

    function resetSummary() {
        document.getElementById('row-giam-hang').style.display = 'none';
        document.getElementById('row-giam-ship').style.display = 'none';
        document.getElementById('sum-total').textContent = fmtMoney(TOTAL_HANG) + 'đ';
    }

    function showMsg(txt, cls) {
        vcMsg.className = cls;
        vcMsg.textContent = txt;
    }

    function fmtMoney(n) {
        return Number(n).toLocaleString('vi-VN');
    }

    function escHtml(s) {
        return s.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
    }

    // Highlight pay-option khi click (cho COD, MoMo, VNPay)
    document.querySelectorAll('.pay-option').forEach(label => {
        label.addEventListener('click', function() {
            document.querySelectorAll('.pay-option').forEach(l => l.classList.remove('selected'));
            this.classList.add('selected');
            const radio = this.querySelector('input[type=radio]');
            if (radio) radio.checked = true;
        });
    });

    // Hàm sao chép văn bản
    function copyText(text, btn) {
        navigator.clipboard.writeText(text).then(() => {
            const orig = btn.textContent;
            btn.textContent = '✓ Đã chép';
            btn.style.background = '#dcfce7';
            btn.style.borderColor = '#86efac';
            btn.style.color = '#16a34a';
            setTimeout(() => {
                btn.textContent = orig;
                btn.style.background = '';
                btn.style.borderColor = '';
                btn.style.color = '';
            }, 2000);
        }).catch(() => {
            const el = document.createElement('textarea');
            el.value = text;
            document.body.appendChild(el);
            el.select();
            document.execCommand('copy');
            document.body.removeChild(el);
            btn.textContent = '✓ Đã chép';
            setTimeout(() => btn.textContent = 'Sao chép', 2000);
        });
    }
    </script>

    <!-- ══════════════════════════════════════════════════════
     MODAL QR BIDV
══════════════════════════════════════════════════════ -->
    <div id="qr-modal-overlay"
        style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.55);z-index:99999;align-items:center;justify-content:center;">
        <div id="qr-modal-box"
            style="background:#fff;border-radius:16px;padding:32px 28px;max-width:520px;width:90%;position:relative;box-shadow:0 20px 60px rgba(0,0,0,.25);animation:modalPop .25s ease;">
            <style>
            @keyframes modalPop {
                from {
                    opacity: 0;
                    transform: scale(.92)
                }

                to {
                    opacity: 1;
                    transform: scale(1)
                }
            }

            #qr-modal-box .step-badge {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 24px;
                height: 24px;
                border-radius: 50%;
                background: #1a3c8f;
                color: #fff;
                font-size: 12px;
                font-weight: 700;
                flex-shrink: 0
            }

            #qr-modal-box .step-row {
                display: flex;
                align-items: center;
                gap: 10px;
                font-size: 14px;
                color: #444;
                margin-bottom: 10px
            }

            #qr-confirm-btn {
                width: 100%;
                padding: 14px;
                margin-top: 20px;
                background: linear-gradient(135deg, #1a3c8f, #2563eb);
                color: #fff;
                border: none;
                border-radius: 8px;
                font-size: 16px;
                font-weight: 700;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
                transition: opacity .2s
            }

            #qr-confirm-btn:hover {
                opacity: .9
            }

            #qr-cancel-btn {
                width: 100%;
                padding: 10px;
                margin-top: 10px;
                background: #fff;
                color: #888;
                border: 1px solid #e0e0e0;
                border-radius: 8px;
                font-size: 14px;
                cursor: pointer
            }

            #qr-cancel-btn:hover {
                background: #f5f5f5
            }

            #qr-modal-close {
                position: absolute;
                top: 14px;
                right: 18px;
                font-size: 24px;
                cursor: pointer;
                color: #aaa;
                line-height: 1;
                transition: color .15s;
                background: none;
                border: none
            }

            #qr-modal-close:hover {
                color: #333
            }
            </style>
            <button id="qr-modal-close" type="button" onclick="closeQrModal()">&#x2715;</button>
            <div style="text-align:center;margin-bottom:20px;">
                <div
                    style="background:#1a3c8f;color:#fff;font-weight:900;font-size:20px;letter-spacing:2px;padding:6px 18px;border-radius:8px;display:inline-block;margin-bottom:8px;">
                    BIDV</div>
                <h3 style="font-size:18px;color:#1a1a1a;margin:0">Quét mã để thanh toán</h3>
                <p style="font-size:13px;color:#888;margin-top:4px">Dùng app ngân hàng bất kỳ để quét</p>
            </div>
            <div style="display:flex;gap:20px;align-items:flex-start;flex-wrap:wrap;">
                <div style="text-align:center;flex-shrink:0;">
                    <div
                        style="background:#fff;border:2px solid #e0e8ff;border-radius:10px;padding:10px;display:inline-block;box-shadow:0 2px 10px rgba(0,0,0,.08);">
                        <img id="modal-qr-img" src="" alt="QR BIDV"
                            style="width:170px;height:170px;display:block;border-radius:4px;" />
                    </div>
                    <div style="margin-top:6px;font-size:12px;color:#aaa;">Mã hết hạn sau <span
                            id="qr-countdown-num">300</span>s</div>
                </div>
                <div style="flex:1;min-width:180px;font-size:13px;">
                    <div
                        style="background:#f8faff;border:1px solid #dbe4ff;border-radius:8px;padding:12px;margin-bottom:12px;">
                        <div style="display:flex;justify-content:space-between;margin-bottom:8px;"><span
                                style="color:#888">Số TK</span><strong id="modal-stk"
                                style="color:#1a3c8f;font-size:15px;letter-spacing:.5px"></strong></div>
                        <div style="display:flex;justify-content:space-between;margin-bottom:8px;"><span
                                style="color:#888">Chủ TK</span><strong id="modal-tentk"></strong></div>
                        <div style="display:flex;justify-content:space-between;margin-bottom:8px;"><span
                                style="color:#888">Số tiền</span><strong id="modal-amount"
                                style="color:#ee4d2d;font-size:16px"></strong></div>
                        <div style="display:flex;justify-content:space-between;align-items:center;">
                            <span style="color:#888">Nội dung</span>
                            <span style="display:flex;align-items:center;gap:6px">
                                <strong id="modal-note" style="color:#333"></strong>
                                <button type="button"
                                    onclick="copyText(document.getElementById('modal-note').textContent,this)"
                                    style="background:#eef2ff;border:1px solid #c7d2fe;color:#4f46e5;border-radius:4px;padding:2px 8px;font-size:11px;cursor:pointer">Chép</button>
                            </span>
                        </div>
                    </div>
                    <div class="step-row"><span class="step-badge">1</span> Mở app ngân hàng &rarr; Quét QR</div>
                    <div class="step-row"><span class="step-badge">2</span> Kiểm tra thông tin, xác nhận chuyển</div>
                    <div class="step-row"><span class="step-badge">3</span> Ấn nút xác nhận bên dưới</div>
                </div>
            </div>
            <div
                style="margin-top:14px;background:#fffbe6;border:1px solid #ffe58f;border-radius:8px;padding:10px 14px;font-size:12px;color:#856404;">
                &#9888;&#65039; Vui lòng chuyển khoản <strong>đúng số tiền và nội dung</strong>. Đơn hàng sẽ được xác
                nhận sau khi nhận được thanh toán.
            </div>
            <button id="qr-confirm-btn" type="button" onclick="confirmTransferred()">
                <span class="material-symbols-outlined" style="font-size:20px">check_circle</span>
                Tôi đã chuyển khoản xong
            </button>
            <button id="qr-cancel-btn" type="button" onclick="closeQrModal()">&larr; Quay lại chọn phương thức
                khác</button>
        </div>
    </div>

    <script>
    // ══ BIDV QR MODAL ══════════════════════════════════════════════
    const BIDV_STK_M = '<?= $BIDV_STK ?>';
    const BIDV_TENTK_M = '<?= $BIDV_TEN_TK ?>';
    const BIDV_NOTE_M = '<?= $BIDV_NOI_DUNG ?>';
    const BIDV_BANK_M = 'BIDV';

    let qrCountdownTimer = null;

    function handleOrderClick() {
        const radio = document.getElementById('radio-bank');
        if (radio && radio.checked) {
            // Chặn submit → mở modal QR
            openQrModal();
        } else {
            // Phương thức khác → submit thẳng
            document.getElementById('btn-order-real').click();
        }
    }

    function openQrModal() {
        const totalText = document.getElementById('sum-total').textContent.replace(/[^\d]/g, '');
        const amount = parseInt(totalText) || TOTAL_HANG;

        // Điền thông tin vào modal
        document.getElementById('modal-stk').textContent = BIDV_STK_M;
        document.getElementById('modal-tentk').textContent = BIDV_TENTK_M;
        document.getElementById('modal-amount').textContent = Number(amount).toLocaleString('vi-VN') + 'đ';
        document.getElementById('modal-note').textContent = BIDV_NOTE_M;

        // Tạo QR
        const qrUrl = 'https://img.vietqr.io/image/' +
            BIDV_BANK_M + '-' + BIDV_STK_M + '-compact2.png' +
            '?amount=' + amount +
            '&addInfo=' + encodeURIComponent(BIDV_NOTE_M) +
            '&accountName=' + encodeURIComponent(BIDV_TENTK_M);
        document.getElementById('modal-qr-img').src = qrUrl;

        // Hiện modal
        const overlay = document.getElementById('qr-modal-overlay');
        overlay.style.display = 'flex';
        document.body.style.overflow = 'hidden';

        // Đếm ngược 5 phút
        startCountdown(300);
    }

    function closeQrModal() {
        document.getElementById('qr-modal-overlay').style.display = 'none';
        document.body.style.overflow = '';
        clearInterval(qrCountdownTimer);
    }

    function confirmTransferred() {
        // Đổi nút thành loading
        const btn = document.getElementById('qr-confirm-btn');
        btn.innerHTML =
            '<span class="material-symbols-outlined" style="font-size:18px;animation:spin 1s linear infinite">sync</span> Đang xử lý...';
        btn.disabled = true;

        // Submit form thật
        document.getElementById('btn-order-real').click();
    }

    function startCountdown(seconds) {
        clearInterval(qrCountdownTimer);
        let s = seconds;
        const el = document.getElementById('qr-countdown-num');
        qrCountdownTimer = setInterval(() => {
            s--;
            if (el) el.textContent = s;
            if (s <= 0) {
                clearInterval(qrCountdownTimer);
                // QR hết hạn — làm mới
                if (el) el.textContent = 'Hết hạn - đang làm mới...';
                openQrModal();
            }
        }, 1000);
    }

    // Click ngoài modal để đóng
    document.getElementById('qr-modal-overlay').addEventListener('click', function(e) {
        if (e.target === this) closeQrModal();
    });

    // CSS spin cho icon loading
    const styleEl = document.createElement('style');
    styleEl.textContent = '@keyframes spin{to{transform:rotate(360deg)}}';
    document.head.appendChild(styleEl);
    </script>
</body>

</html>