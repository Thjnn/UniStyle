<?php
session_start();
include('./config/db.php');

if (!isset($_SESSION['khachhang_id'])) {
    header("Location: login.php");
    exit();
}

$kh_id   = (int)$_SESSION['khachhang_id'];
$message = '';
$tab     = isset($_GET['tab']) ? $_GET['tab'] : 'profile';

$sql_get   = "SELECT * FROM khachhang WHERE makh = $kh_id";
$result    = $conn->query($sql_get);
$user_data = $result->fetch_assoc();

// ══════════════════════════════════════════════════════════
//  AJAX: lưu voucher / hủy đơn / nhận hàng
// ══════════════════════════════════════════════════════════
if (isset($_POST['ajax_action'])) {
    header('Content-Type: application/json');
    $act = $_POST['ajax_action'];

    // ── Lưu voucher ─────────────────────────────────────
    if ($act === 'save_voucher') {
        $ma = trim($conn->real_escape_string($_POST['ma_code'] ?? ''));
        if (!$ma) { echo json_encode(['success'=>false,'message'=>'Vui lòng nhập mã voucher.']); exit(); }

        $stmt = $conn->prepare("SELECT * FROM voucher WHERE ma_code = ?");
        $stmt->bind_param("s", $ma); $stmt->execute();
        $vc = $stmt->get_result()->fetch_assoc(); $stmt->close();

        if (!$vc) { echo json_encode(['success'=>false,'message'=>'Mã voucher không tồn tại.']); exit(); }

        $now = date('Y-m-d H:i:s');
        $filter_vc = $_GET['vc_filter'] ?? 'all';
        if ($vc['ngay_ket_thuc'] && $now > $vc['ngay_ket_thuc']) {
            echo json_encode(['success'=>false,'message'=>'Voucher đã hết hạn.']); exit();
        }

        $stmt = $conn->prepare("SELECT id FROM khachhang_voucher WHERE makh=? AND id_voucher=?");
        $stmt->bind_param("ii", $kh_id, $vc['id_voucher']); $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            echo json_encode(['success'=>false,'message'=>'Bạn đã lưu voucher này rồi.']); $stmt->close(); exit();
        }
        $stmt->close();

        if ($vc['so_luong'] > 0) {
            $stmt = $conn->prepare("SELECT COUNT(*) AS c FROM khachhang_voucher WHERE id_voucher=?");
            $stmt->bind_param("i", $vc['id_voucher']); $stmt->execute();
            $c = $stmt->get_result()->fetch_assoc()['c']; $stmt->close();
            if ($c >= $vc['so_luong']) { echo json_encode(['success'=>false,'message'=>'Voucher đã hết lượt.']); exit(); }
        }

        $stmt = $conn->prepare("INSERT INTO khachhang_voucher (makh,id_voucher,trang_thai) VALUES (?,?,0)");
        $stmt->bind_param("ii", $kh_id, $vc['id_voucher']); $stmt->execute(); $stmt->close();
        echo json_encode(['success'=>true,'message'=>'Đã lưu voucher vào kho!']); exit();
    }

    // ── Hủy đơn ────────────────────────────────────────
    if ($act === 'cancel_order') {
        $madh = (int)($_POST['madh'] ?? 0);
        $stmt = $conn->prepare("SELECT trangthai FROM dathang WHERE madh=? AND makh=?");
        $stmt->bind_param("ii", $madh, $kh_id); $stmt->execute();
        $ord = $stmt->get_result()->fetch_assoc(); $stmt->close();
        if (!$ord || $ord['trangthai'] !== 'Chờ xác nhận') {
            echo json_encode(['success'=>false,'message'=>'Không thể hủy đơn này.']); exit();
        }
        $conn->begin_transaction();
        try {
            $stmt = $conn->prepare("SELECT MaSP,soluong FROM chitietdathang WHERE madh=?");
            $stmt->bind_param("i", $madh); $stmt->execute();
            $items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC); $stmt->close();
            foreach ($items as $it) {
                $s2 = $conn->prepare("UPDATE sanpham SET SoLuongTon=SoLuongTon+?,SoLuongDaBan=SoLuongDaBan-? WHERE MaSP=?");
                $s2->bind_param("iii", $it['soluong'], $it['soluong'], $it['MaSP']); $s2->execute(); $s2->close();
            }
            $s = $conn->prepare("UPDATE dathang SET trangthai='Đã hủy' WHERE madh=?"); $s->bind_param("i",$madh); $s->execute(); $s->close();
            $s = $conn->prepare("UPDATE hoadon  SET trangthai='Đã hủy' WHERE madh=?"); $s->bind_param("i",$madh); $s->execute(); $s->close();
            $conn->commit(); echo json_encode(['success'=>true]);
        } catch(Exception $e){ $conn->rollback(); echo json_encode(['success'=>false,'message'=>$e->getMessage()]); }
        exit();
    }

    // ── Xác nhận nhận hàng ─────────────────────────────
    if ($act === 'received_order') {
        $madh = (int)($_POST['madh'] ?? 0);
        $stmt = $conn->prepare("SELECT trangthai FROM dathang WHERE madh=? AND makh=?");
        $stmt->bind_param("ii", $madh, $kh_id); $stmt->execute();
        $ord = $stmt->get_result()->fetch_assoc(); $stmt->close();
        if (!$ord || $ord['trangthai'] !== 'Đang giao') {
            echo json_encode(['success'=>false,'message'=>'Không thể xác nhận.']); exit();
        }
        $conn->begin_transaction();
        try {
            $s = $conn->prepare("UPDATE dathang SET trangthai='Hoàn thành'    WHERE madh=?"); $s->bind_param("i",$madh); $s->execute(); $s->close();
            $s = $conn->prepare("UPDATE hoadon  SET trangthai='Đã thanh toán' WHERE madh=?"); $s->bind_param("i",$madh); $s->execute(); $s->close();
            $conn->commit(); echo json_encode(['success'=>true]);
        } catch(Exception $e){ $conn->rollback(); echo json_encode(['success'=>false,'message'=>$e->getMessage()]); }
        exit();
    }

    echo json_encode(['success'=>false,'message'=>'Hành động không hợp lệ.']); exit();
}

// ══════════════════════════════════════════════════════════
//  XỬ LÝ FORM THÔNG THƯỜNG
// ══════════════════════════════════════════════════════════
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['btn_save_profile'])) {
        $tenkh = $conn->real_escape_string($_POST['tenkh']);
        $email = $conn->real_escape_string($_POST['email']);
        $sdt   = $conn->real_escape_string($_POST['sdt']);
        $gioitinh = isset($_POST['gender']) ? $conn->real_escape_string($_POST['gender']) : '';
        $ngay = $_POST['ngay']; $thang = $_POST['thang']; $nam = $_POST['nam'];
        $ngaysinh = NULL;
        if ($ngay != 'Ngày' && $thang != 'Tháng' && $nam != 'Năm') $ngaysinh = "$nam-$thang-$ngay";
        $avatar_query = "";
        if (isset($_FILES['avatar_upload']) && $_FILES['avatar_upload']['error'] == 0) {
            $dir = "./assets/file_anh/"; $fn = "avatar_".time()."_".basename($_FILES["avatar_upload"]["name"]);
            $tf  = $dir.$fn; $ext = strtolower(pathinfo($tf, PATHINFO_EXTENSION));
            if (in_array($ext,['jpg','jpeg','png']) && move_uploaded_file($_FILES["avatar_upload"]["tmp_name"],$tf))
                $avatar_query = ", avatar='$fn'";
        }
        $ns  = $ngaysinh ? "'$ngaysinh'" : "NULL";
        $sql = "UPDATE khachhang SET tenkh='$tenkh',email='$email',sdt='$sdt',gioitinh='$gioitinh',ngaysinh=$ns $avatar_query WHERE makh=$kh_id";
        if ($conn->query($sql)) {
            $_SESSION['khachhang_ten'] = $tenkh;
            $message = "<div class='msg-ok'>Hồ sơ đã được lưu thành công!</div>";
            $result  = $conn->query($sql_get); $user_data = $result->fetch_assoc();
        } else $message = "<div class='msg-err'>Đã xảy ra lỗi, thử lại!</div>";

    } elseif (isset($_POST['btn_save_address'])) {
        $dc = $conn->real_escape_string($_POST['diachi_chitiet'].', '.$_POST['phuongxa'].', '.$_POST['quanhuyen'].', '.$_POST['tinhthanh']);
        if ($conn->query("UPDATE khachhang SET diachi='$dc' WHERE makh=$kh_id")) {
            $message = "<div class='msg-ok'>Địa chỉ đã được cập nhật!</div>";
            $result  = $conn->query($sql_get); $user_data = $result->fetch_assoc();
        } else $message = "<div class='msg-err'>Lỗi, thử lại!</div>";

    } elseif (isset($_POST['btn_change_password'])) {
        $op = $_POST['old_pass']; $np = $_POST['new_pass']; $cp = $_POST['confirm_pass'];
        if ($op != $user_data['matkhau'])       $message = "<div class='msg-err'>Mật khẩu hiện tại không đúng!</div>";
        elseif ($np != $cp)                     $message = "<div class='msg-err'>Mật khẩu xác nhận không khớp!</div>";
        else {
            $np_safe = $conn->real_escape_string($np);
            if ($conn->query("UPDATE khachhang SET matkhau='$np_safe' WHERE makh=$kh_id")) {
                $message = "<div class='msg-ok'>Đổi mật khẩu thành công!</div>";
                $result  = $conn->query($sql_get); $user_data = $result->fetch_assoc();
            } else $message = "<div class='msg-err'>Lỗi, thử lại!</div>";
        }
    }
}

// ── Biến dùng chung ────────────────────────────────────────
$totalQty = 0;
if (!empty($_SESSION['cart'])) foreach ($_SESSION['cart'] as $i) $totalQty += $i['soluong'];

$ten_kh         = $user_data['tenkh'];
$email_kh       = $user_data['email']    ?? '';
$sdt_kh         = $user_data['sdt']      ?? '';
$tendangnhap_kh = $user_data['tendangnhap'];
$diachi_kh      = $user_data['diachi']   ?? '';
$gioitinh_kh    = $user_data['gioitinh'] ?? '';
$ngaysinh_kh    = $user_data['ngaysinh'] ?? '';
$avatar_kh      = !empty($user_data['avatar'])
    ? "./assets/file_anh/".$user_data['avatar']
    : "./assets/file_anh/default_avatar.png";

$n_ngay = 'Ngày'; $n_thang = 'Tháng'; $n_nam = 'Năm';
if ($ngaysinh_kh) {
    $p = explode('-', $ngaysinh_kh);
    if (count($p)==3) { $n_nam=$p[0]; $n_thang=$p[1]; $n_ngay=$p[2]; }
}

function status_class($s) {
    $m = ['Chờ xác nhận'=>'pending','Đã xác nhận'=>'confirmed','Chờ lấy hàng'=>'pickup',
          'Đang giao'=>'shipping','Đã giao'=>'completed','Hoàn thành'=>'completed','Đã hủy'=>'cancelled'];
    return $m[$s] ?? 'pending';
}
function days_left($d) {
    if (!$d) return null;
    return (int)ceil((strtotime($d)-time())/86400);
}
function discount_desc($v) {
    $val = $v['hinh_thuc_giam']==1 ? $v['gia_tri_giam'].'%' : number_format($v['gia_tri_giam']).'đ';
    $max = $v['giam_toi_da']>0 ? ' (tối đa '.number_format($v['giam_toi_da']).'đ)' : '';
    $min = $v['don_toi_thieu']>0 ? 'Đơn tối thiểu '.number_format($v['don_toi_thieu']).'đ. ' : 'Không giới hạn đơn. ';
    $sc  = $v['ap_dung_tat_ca'] ? 'Áp dụng toàn shop.' : 'Áp dụng SP nhất định.';
    return $min.'Giảm '.$val.$max.'. '.$sc;
}
?>
<!doctype html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0" />
    <title>Tài Khoản Của Tôi - UniStyle</title>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,0..1" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="shortcut icon" href="assets/file_anh/0c4690d7-3599-4de4-a0a4-841817ead1c0.png" />
    <link rel="stylesheet" href="./assets/css/style.css" />
    <link rel="stylesheet" href="./assets/css/reposive.css" />
    <link rel="stylesheet" href="./assets/css/profile.css" />
    <link rel="stylesheet" href="./assets/css/v.css" />
</head>

<body>

    <!-- ══ HEADER ════════════════════════════════════════════════════════ -->
    <header>
        <div class="container">
            <div class="header-content">
                <div class="menu-toggle"><span class="material-symbols-outlined">menu</span></div>
                <div class="logo"><a href="index.php"><img
                            src="./assets/file_anh/0c4690d7-3599-4de4-a0a4-841817ead1c0.png" alt="" /></a></div>
                <nav>
                    <ul>
                        <li><a href="index.php">Trang chủ</a></li>
                        <li class="has-submenu"><a href="shop.php">Cửa hàng</a>
                            <div class="submenu">
                                <div class="submenu-left">
                                    <div class="submenu-column">
                                        <h4>Bút viết</h4><a href="#">Bút bi</a><a href="#">Bút màu</a><a href="#">Bút dạ
                                            quang</a>
                                    </div>
                                    <div class="submenu-column">
                                        <h4>Văn phòng phẩm</h4><a href="#">Sổ</a><a href="#">Bìa hồ sơ</a><a
                                            href="#">Dập ghim</a>
                                    </div>
                                    <div class="submenu-column">
                                        <h4>Dụng cụ học tập</h4><a href="#">Thước</a><a href="#">Máy tính</a><a
                                            href="#">Dao rọc giấy</a>
                                    </div>
                                </div>
                                <div class="submenu-banner"><a href="#!"><img
                                            src="./assets/file_anh/1920_x_600___cta___6_.webp" alt="" /></a></div>
                            </div>
                        </li>
                        <li><a href="contact.php">Liên hệ</a></li>
                        <li><a href="FAQ.php">FAQ</a></li>
                        <li><a href="aboutus.php">Về chúng tôi</a></li>
                    </ul>
                </nav>
                <div class="header-icons">
                    <div class="search-box"><span class="material-symbols-outlined search-icon">search</span>
                        <form class="search-form" action="shop.php" method="GET"><input type="text" name="keyword"
                                placeholder="Tìm sản phẩm..." /></form>
                    </div>
                    <div class="cart-icon"><a href="package.php"><span
                                class="material-symbols-outlined">local_mall</span>
                            <?php if($totalQty>0):?><span class="cart-count"><?=$totalQty?></span><?php endif;?>
                        </a></div>
                    <?php if(isset($_SESSION['khachhang_id'])):?>
                    <a href="profile.php"><span class="material-symbols-outlined">person</span></a>
                    <?php else:?>
                    <a href="login.php"><span class="material-symbols-outlined">person</span></a>
                    <?php endif;?>
                </div>
            </div>
        </div>
    </header>

    <!-- ══ LAYOUT ════════════════════════════════════════════════════════ -->
    <div class="profile-layout-bg">
        <div class="container profile-page-container">

            <!-- SIDEBAR -->
            <aside class="profile-sidebar">
                <div class="profile-user-info">
                    <div class="profile-avatar"><img src="<?=$avatar_kh?>" alt="" /></div>
                    <div class="profile-name-group">
                        <div class="profile-name"><?=htmlspecialchars($_SESSION['khachhang_ten'])?></div>
                        <div class="profile-edit-btn"><span class="material-symbols-outlined">edit</span> Sửa hồ sơ
                        </div>
                    </div>
                </div>
                <nav class="profile-menu">
                    <div class="menu-group">
                        <div class="menu-group-title">
                            <span class="material-symbols-outlined" style="color:#05a">person</span>
                            <a href="profile.php?tab=profile" style="text-decoration:none;color:inherit">Tài Khoản Của
                                Tôi</a>
                        </div>
                        <div class="menu-sub-items">
                            <a href="profile.php?tab=profile" class="<?=$tab=='profile' ?'active':''?>">Hồ sơ</a>
                            <a href="profile.php?tab=address" class="<?=$tab=='address' ?'active':''?>">Địa chỉ</a>
                            <a href="profile.php?tab=password" class="<?=$tab=='password'?'active':''?>">Đổi mật
                                khẩu</a>
                        </div>
                    </div>
                    <a href="profile.php?tab=orders" class="menu-item <?=$tab=='orders'       ?'active':''?>"><span
                            class="material-symbols-outlined" style="color:#2563eb">receipt_long</span> Đơn Mua</a>
                    <a href="profile.php?tab=notifications" class="menu-item <?=$tab=='notifications'?'active':''?>">
                        <span class="material-symbols-outlined" style="color:#ee4d2d">notifications</span> Thông Báo
                        <?php
            // Đếm đơn mới (trong 7 ngày) để hiện badge
            $stmt_nb = $conn->prepare("SELECT COUNT(*) AS c FROM dathang WHERE makh=? AND ngaydat >= DATE_SUB(NOW(), INTERVAL 7 DAY)");
            $stmt_nb->bind_param("i",$kh_id); $stmt_nb->execute();
            $nb_count = $stmt_nb->get_result()->fetch_assoc()['c']; $stmt_nb->close();
            if ($nb_count > 0): ?>
                        <span
                            style="background:#ee4d2d;color:#fff;font-size:11px;padding:1px 6px;border-radius:10px;margin-left:auto"><?=$nb_count?></span>
                        <?php endif;?>
                    </a>
                    <a href="profile.php?tab=vouchers" class="menu-item <?=$tab=='vouchers'     ?'active':''?>"><span
                            class="material-symbols-outlined" style="color:#ffc107">confirmation_number</span> Kho
                        Voucher</a>
                    <a href="logout.php" class="menu-item" style="margin-top:20px"><span
                            class="material-symbols-outlined" style="color:#999">logout</span> Đăng Xuất</a>
                </nav>
            </aside>

            <!-- ══ MAIN ══════════════════════════════════════════════════════════ -->
            <main class="profile-main">

                <?php /* ═══ TAB: KHO VOUCHER ═══ */
if ($tab == 'vouchers'):
    $now = date('Y-m-d H:i:s');
    $filter_vc = $_GET['vc_filter'] ?? 'all';
    // ===============================
    // 1. LẤY TẤT CẢ VOUCHER HỆ THỐNG
    // ===============================
    $stmt_all = $conn->prepare(
        "SELECT * FROM voucher
         WHERE (ngay_ket_thuc IS NULL OR ngay_ket_thuc >= NOW())"
    );
    $stmt_all->execute();
    $all_vouchers = $stmt_all->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt_all->close();

    // ===============================
    // 2. LẤY VOUCHER ĐÃ LƯU
    // ===============================
    $stmt = $conn->prepare(
        "SELECT v.*, kv.trang_thai AS da_dung
         FROM khachhang_voucher kv
         JOIN voucher v ON v.id_voucher = kv.id_voucher
         WHERE kv.makh = ?"
    );
    $stmt->bind_param("i",$kh_id);
    $stmt->execute();
    $my_vouchers = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    // Lọc voucher theo tab
$display_vc = array_filter($my_vouchers, function($v) use ($filter_vc) {
    if ($filter_vc === 'all') return true;
    if ($filter_vc === 'unused') return $v['da_dung'] == 0;
    if ($filter_vc === 'used') return $v['da_dung'] == 1;
    if ($filter_vc === 'freeship') return $v['loai_voucher'] == 1;
    if ($filter_vc === 'discount') return $v['loai_voucher'] != 1;
    return true;
});?>
                <div class="profile-content-card">
                    <h2>Tất cả Voucher</h2>

                    <?php foreach ($all_vouchers as $v): 

        // check đã lưu chưa
        $saved = false;
        foreach ($my_vouchers as $mv) {
            if ($mv['id_voucher'] == $v['id_voucher']) {
                $saved = true;
                break;
            }
        }

        $expired = $v['ngay_ket_thuc'] && $v['ngay_ket_thuc'] < $now;
    ?>

                    <div class="vc-wrap <?= $expired ? 'used' : '' ?>">
                        <div class="vc-left <?= $v['loai_voucher']==1 ? 'bg-ship':'bg-disc' ?>">
                            <div class="vc-left <?= $v['loai_voucher']==1 ? 'bg-ship':'bg-disc' ?>">
                                <span class="material-symbols-outlined vc-icon">
                                    <?= $v['loai_voucher']==1 ? 'local_shipping' : 'sell' ?>
                                </span>

                                <span class="vc-type">
                                    <?= $v['loai_voucher']==1 ? 'Miễn ship' : 'Giảm giá' ?>
                                </span>
                            </div>
                        </div>

                        <div class="vc-right">
                            <div style="flex:1">
                                <h4><?= htmlspecialchars($v['ten_voucher']) ?></h4>

                                <div class="vc-desc">
                                    <?= $v['hinh_thuc_giam']==1 
                        ? "Giảm ".$v['gia_tri_giam']."%" 
                        : "Giảm ".number_format($v['gia_tri_giam'])."đ" ?>
                                </div>

                                <div style="font-size:12px;color:#888">
                                    HSD:
                                    <?= $v['ngay_ket_thuc'] 
                        ? date('d/m/Y', strtotime($v['ngay_ket_thuc'])) 
                        : 'Không giới hạn' ?>
                                </div>

                                <div style="font-size:11px;color:#aaa">
                                    Mã: <?= htmlspecialchars($v['ma_code']) ?>
                                </div>
                            </div>

                            <div>
                                <?php if ($expired): ?>
                                <button class="btn-use" disabled>Hết hạn</button>

                                <?php elseif ($saved): ?>
                                <button class="btn-use" disabled>Đã lưu</button>

                                <?php else: ?>
                                <button class="btn-use" onclick="saveVoucherCode('<?= $v['ma_code'] ?>')">
                                    Lưu
                                </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <?php endforeach; ?>
                </div>


                <div class="profile-content-card" style="min-height:500px">
                    <div
                        style="display:flex;justify-content:space-between;align-items:flex-start;padding-bottom:16px;flex-wrap:wrap;gap:14px">
                        <div>
                            <h2 style="margin-bottom:4px">Kho Voucher</h2>
                            <p style="color:#888;font-size:14px">Quản lý voucher giảm giá và miễn phí vận chuyển</p>
                        </div>
                        <div style="min-width:300px">
                            <div class="vc-input-row">

                            </div>
                            <div id="vc-msg"></div>
                        </div>
                    </div>

                    <!-- Bộ lọc -->
                    <div class="vc-tabs">
                        <?php
        $vc_tab_defs = [
            'all'      => 'Tất cả ('.count($my_vouchers).')',
            'unused'   => 'Chưa dùng',
            'freeship' => 'Miễn ship',
            'discount' => 'Giảm giá',
            'used'     => 'Đã dùng',
        ];
        foreach ($vc_tab_defs as $k=>$lbl):?>
                        <a href="profile.php?tab=vouchers&vc_filter=<?=$k?>"
                            class="vc-tab <?=$filter_vc===$k?'active':''?>"><?=$lbl?></a>
                        <?php endforeach;?>
                    </div>

                    <!-- Danh sách -->
                    <?php if (empty($display_vc)): ?>
                    <div class="vc-empty">
                        <span class="material-symbols-outlined">confirmation_number</span>
                        <p>Bạn chưa có voucher nào<?=$filter_vc!=='all'?' ở mục này':''?>.<br>Nhập mã ở trên để lưu!</p>
                    </div>
                    <?php else: foreach ($display_vc as $v):
        $is_ship    = $v['loai_voucher']==1;
        $is_used    = $v['da_dung']==1;
        $is_expired = $v['ngay_ket_thuc'] && $v['ngay_ket_thuc']<$now;
        $dl         = days_left($v['ngay_ket_thuc']);
        $val_label  = $is_ship ? 'Miễn ship' : ($v['hinh_thuc_giam']==1?'Giảm '.$v['gia_tri_giam'].'%':'Giảm '.number_format($v['gia_tri_giam']).'đ');

        if ($is_used)                          { $exp_cls='dead'; $exp_txt='Đã sử dụng'; }
        elseif ($is_expired)                   { $exp_cls='dead'; $exp_txt='Đã hết hạn'; }
        elseif ($dl!==null && $dl<=3)          { $exp_cls='soon'; $exp_txt='Sắp hết hạn: còn '.$dl.' ngày'; }
        elseif ($v['ngay_ket_thuc'])           { $exp_cls='ok';   $exp_txt='HSD: '.date('d/m/Y',strtotime($v['ngay_ket_thuc'])); }
        else                                   { $exp_cls='ok';   $exp_txt='Không giới hạn thời gian'; }

        $disabled = $is_used||$is_expired;
    ?>
                    <div class="vc-wrap <?=$disabled?'used':''?>">
                        <div class="vc-left <?=$is_ship?'bg-ship':'bg-disc'?>">
                            <span class="material-symbols-outlined vc-icon"><?=$is_ship?'local_shipping':'sell'?></span>
                            <span class="vc-type"><?=$val_label?></span>
                        </div>
                        <div class="vc-right">
                            <div style="flex:1">
                                <span
                                    class="vc-badge <?=$is_ship?'ship':'disc'?>"><?=$is_ship?'Miễn ship':'Giảm giá'?></span>
                                <h4><?=htmlspecialchars($v['ten_voucher'])?></h4>
                                <div class="vc-desc"><?=htmlspecialchars(discount_desc($v))?></div>
                                <div class="vc-exp <?=$exp_cls?>"><?=$exp_txt?></div>
                                <div style="font-size:11px;color:#ccc;margin-top:4px">Mã: <strong
                                        style="color:#bbb"><?=htmlspecialchars($v['ma_code'])?></strong></div>
                            </div>
                            <div>
                                <?php if (!$disabled): ?>
                                <button class="btn-use"
                                    onclick="window.location.href='checkout.php?voucher=<?=urlencode($v['ma_code'])?>'">Dùng
                                    ngay</button>
                                <?php else: ?>
                                <button class="btn-use" disabled><?=$is_used?'Đã dùng':'Hết hạn'?></button>
                                <?php endif;?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; endif;?>
                </div>
                <script>
                function saveVoucher() {
                    const code = document.getElementById('vcCodeInput').value.trim();
                    const msg = document.getElementById('vc-msg');
                    msg.className = '';
                    msg.textContent = '';
                    if (!code) {
                        msg.className = 'err';
                        msg.textContent = 'Vui lòng nhập mã voucher.';
                        return;
                    }
                    fetch('profile.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },
                            body: 'ajax_action=save_voucher&ma_code=' + encodeURIComponent(code)
                        })
                        .then(r => r.json()).then(d => {
                            msg.className = d.success ? 'ok' : 'err';
                            msg.textContent = d.message;
                            if (d.success) {
                                document.getElementById('vcCodeInput').value = '';
                                setTimeout(() => location.reload(), 1200);
                            }
                        }).catch(() => {
                            msg.className = 'err';
                            msg.textContent = 'Lỗi kết nối.';
                        });
                }
                document.getElementById('vcCodeInput').addEventListener('keydown', e => {
                    if (e.key === 'Enter') saveVoucher();
                });
                </script>

                <?php /* ═══ TAB: ĐƠN MUA ═══ */
elseif ($tab == 'orders'):
    $filter_status = $_GET['order_status'] ?? 'all';

    $stmt = $conn->prepare(
        "SELECT dh.*, hd.phuongthuctt, hd.trangthai AS trangthai_hd
         FROM dathang dh
         LEFT JOIN hoadon hd ON hd.madh=dh.madh
         WHERE dh.makh=?
         ORDER BY dh.ngaydat DESC, dh.madh DESC"
    );
    $stmt->bind_param("i",$kh_id); $stmt->execute();
    $all_orders = $stmt->get_result()->fetch_all(MYSQLI_ASSOC); $stmt->close();

    $order_details = [];
    foreach ($all_orders as $ord) {
        $mid = (int)$ord['madh'];
        $s2  = $conn->prepare(
            "SELECT ctdh.*,sp.TenSP,sp.Hinh,bt.GiaTri AS bien_the_giatri
             FROM chitietdathang ctdh
             JOIN sanpham sp ON sp.MaSP=ctdh.MaSP
             LEFT JOIN bienthe_sanpham bt ON bt.id=ctdh.bienthe_id
             WHERE ctdh.madh=?"
        );
        $s2->bind_param("i",$mid); $s2->execute();
        $order_details[$mid] = $s2->get_result()->fetch_all(MYSQLI_ASSOC); $s2->close();
    }

    $display_orders = array_filter($all_orders, function($o) use ($filter_status){
        return $filter_status==='all' || ($o['trangthai']??'')===$filter_status;
    });

    $icon_map=['pending'=>'pending','confirmed'=>'verified','pickup'=>'inventory',
               'shipping'=>'local_shipping','completed'=>'task_alt','cancelled'=>'cancel'];
    $tabs_def=['all'=>'Tất cả','Chờ xác nhận'=>'Chờ xác nhận','Chờ lấy hàng'=>'Chờ lấy hàng',
               'Đang giao'=>'Đang giao','Hoàn thành'=>'Hoàn thành','Đã hủy'=>'Đã hủy'];
?>
                <div
                    style="background:#fff;display:flex;box-shadow:0 1px 3px rgba(0,0,0,.07);margin-bottom:14px;border-radius:6px;overflow:hidden;">
                    <?php foreach($tabs_def as $val=>$lbl):?>
                    <a href="profile.php?tab=orders&order_status=<?=urlencode($val)?>"
                        class="o-tab <?=$filter_status===$val?'active':''?>"><?=$lbl?></a>
                    <?php endforeach;?>
                </div>

                <?php if(empty($display_orders)):?>
                <div class="empty-orders">
                    <span class="material-symbols-outlined">receipt_long</span>
                    <p>Bạn chưa có đơn hàng nào<?=$filter_status!=='all'?' ở trạng thái này':''?>.</p>
                    <a href="shop.php">Mua sắm ngay →</a>
                </div>
                <?php else: foreach($display_orders as $ord):
    $madh      = (int)$ord['madh'];
    $items     = $order_details[$madh] ?? [];
    $trangthai = $ord['trangthai'] ?? 'Chờ xác nhận';
    $sc        = status_class($trangthai);
    $pttt      = $ord['phuongthuctt'] ?? 'COD';
?>
                <div class="order-card">
                    <div class="order-card-head">
                        <div class="order-shop">
                            <span class="material-symbols-outlined" style="font-size:18px">storefront</span>
                            UniStyle Official <span class="badge-mall">Mall</span>
                            <span style="color:#ccc;margin:0 4px">|</span>
                            <span style="font-size:12px;color:#888;font-weight:400">#<?=$madh?></span>
                        </div>
                        <div class="status-badge <?=$sc?>">
                            <span class="material-symbols-outlined"
                                style="font-size:16px"><?=$icon_map[$sc]??'info'?></span>
                            <?=htmlspecialchars($trangthai)?>
                        </div>
                    </div>
                    <div class="order-body">
                        <?php if(empty($items)):?><p style="color:#aaa;font-size:14px">Không có sản phẩm.</p>
                        <?php else: foreach($items as $it):?>
                        <div class="order-item">
                            <img src="./assets/file_anh/San_Pham/<?=htmlspecialchars($it['Hinh']??'')?>" alt="" />
                            <div style="flex:1">
                                <div class="order-item-name"><?=htmlspecialchars($it['TenSP']??'')?></div>
                                <?php if(!empty($it['bien_the_giatri'])):?>
                                <div class="order-item-meta">Phân loại: <?=htmlspecialchars($it['bien_the_giatri'])?>
                                </div>
                                <?php endif;?>
                                <div class="order-item-meta">x<?=(int)$it['soluong']?></div>
                            </div>
                            <div class="order-item-price">
                                <div class="unit"><?=number_format($it['dongia'])?>đ</div>
                                <div class="sub"><?=number_format($it['thanhtien'])?>đ</div>
                            </div>
                        </div>
                        <?php endforeach; endif;?>
                    </div>
                    <div class="order-foot">
                        <div>
                            <div
                                style="font-size:12px;color:#888;display:flex;align-items:center;gap:5px;margin-bottom:5px">
                                <span class="material-symbols-outlined" style="font-size:15px">payments</span>
                                <?=htmlspecialchars($pttt)?> · <?=date('d/m/Y',strtotime($ord['ngaydat']))?>
                            </div>
                            <div style="font-size:14px;color:#555">Thành tiền:
                                <strong><?=number_format($ord['tongtien'])?>đ</strong>
                            </div>
                        </div>
                        <div class="order-actions">
                            <?php if($trangthai==='Chờ xác nhận'):?>
                            <button class="btn-ord btn-ord-danger" onclick="cancelOrder(<?=$madh?>)">Hủy đơn</button>
                            <button class="btn-ord">Liên hệ</button>
                            <?php elseif($trangthai==='Chờ lấy hàng'):?><button class="btn-ord">Liên hệ</button>
                            <?php elseif($trangthai==='Đang giao'):?>
                            <button class="btn-ord">Theo dõi đơn</button>
                            <button class="btn-ord btn-ord-primary" onclick="confirmReceived(<?=$madh?>)">Đã nhận
                                hàng</button>
                            <?php elseif(in_array($trangthai,['Hoàn thành','Đã giao'])):?>
                            <button class="btn-ord">Mua lại</button>
                            <button class="btn-ord btn-ord-primary">Đánh giá</button>
                            <?php elseif($trangthai==='Đã hủy'):?><span style="color:#aaa;font-size:13px">Đơn đã
                                hủy</span>
                            <?php endif;?>
                        </div>
                    </div>
                </div>
                <?php endforeach; endif;?>

                <script>
                function cancelOrder(madh) {
                    if (!confirm('Bạn có chắc muốn hủy đơn #' + madh + '?')) return;
                    fetch('profile.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },
                            body: 'ajax_action=cancel_order&madh=' + madh
                        })
                        .then(r => r.json()).then(d => {
                            if (d.success) {
                                alert('Đã hủy.');
                                location.reload();
                            } else alert('Lỗi: ' + (d.message || 'Không thể hủy.'));
                        });
                }

                function confirmReceived(madh) {
                    if (!confirm('Xác nhận đã nhận được hàng?')) return;
                    fetch('profile.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },
                            body: 'ajax_action=received_order&madh=' + madh
                        })
                        .then(r => r.json()).then(d => {
                            if (d.success) {
                                alert('Hoàn thành!');
                                location.reload();
                            } else alert('Lỗi: ' + (d.message || 'Không thể cập nhật.'));
                        });
                }
                </script>

                <?php /* ═══ TAB: THÔNG BÁO ═══ */
elseif ($tab == 'notifications'):

    // Lấy 20 đơn hàng gần nhất của khách, ghép hóa đơn để lấy phương thức TT
    $stmt = $conn->prepare(
        "SELECT dh.madh, dh.ngaydat, dh.tongtien, dh.trangthai,
                hd.phuongthuctt, hd.trangthai AS trangthai_hd
         FROM dathang dh
         LEFT JOIN hoadon hd ON hd.madh = dh.madh
         WHERE dh.makh = ?
         ORDER BY dh.ngaydat DESC, dh.madh DESC
         LIMIT 20"
    );
    $stmt->bind_param("i",$kh_id); $stmt->execute();
    $notif_orders = $stmt->get_result()->fetch_all(MYSQLI_ASSOC); $stmt->close();

    // Map trạng thái → icon + màu + thông điệp
    function notif_config($trangthai, $pttt='') {
        $map = [
            'Chờ xác nhận' => ['icon'=>'pending',        'cls'=>'orange', 'title'=>'Đơn hàng đang chờ xác nhận',     'msg'=>'UniStyle đã nhận đơn và đang xem xét xác nhận.'],
            'Đã xác nhận'  => ['icon'=>'verified',        'cls'=>'blue',   'title'=>'Đơn hàng đã được xác nhận',      'msg'=>'Đơn hàng của bạn đã được xác nhận và đang chuẩn bị hàng.'],
            'Chờ lấy hàng' => ['icon'=>'inventory',       'cls'=>'blue',   'title'=>'Đơn hàng đang chờ lấy hàng',     'msg'=>'Hàng đã đóng gói, đang chờ đơn vị vận chuyển đến lấy.'],
            'Đang giao'    => ['icon'=>'local_shipping',  'cls'=>'teal',   'title'=>'Đơn hàng đang được giao',         'msg'=>'Đơn vị vận chuyển đang trên đường giao đến bạn.'],
            'Đã giao'      => ['icon'=>'task_alt',        'cls'=>'green',  'title'=>'Giao hàng thành công',            'msg'=>'Đơn hàng đã được giao thành công. Cảm ơn bạn đã mua sắm!'],
            'Hoàn thành'   => ['icon'=>'task_alt',        'cls'=>'green',  'title'=>'Đơn hàng hoàn thành',             'msg'=>'Đơn hàng đã hoàn thành. Hãy đánh giá sản phẩm nhé!'],
            'Đã đặt'       => ['icon'=>'shopping_bag',   'cls'=>'orange', 'title'=>'Đặt hàng thành công',             'msg'=>'Bạn đã đặt hàng thành công. UniStyle sẽ xác nhận sớm!'],
            'Đang xử lý'   => ['icon'=>'autorenew',      'cls'=>'blue',   'title'=>'Đơn hàng đang xử lý',             'msg'=>'Đơn hàng đang được xử lý bởi hệ thống.'],
            'Đã hủy'       => ['icon'=>'cancel',         'cls'=>'red',    'title'=>'Đơn hàng đã bị hủy',              'msg'=>'Đơn hàng của bạn đã bị hủy.'],
            'Hoàn trả'     => ['icon'=>'assignment_return','cls'=>'gray', 'title'=>'Đơn hàng đã hoàn trả',            'msg'=>'Đơn hàng đã được hoàn trả thành công.'],
        ];
        return $map[$trangthai] ?? ['icon'=>'info','cls'=>'gray','title'=>'Cập nhật đơn hàng','msg'=>'Trạng thái: '.$trangthai];
    }
?>
                <div class="profile-content-card">
                    <div class="profile-header">
                        <h2>Thông Báo</h2>
                        <p>Lịch sử cập nhật trạng thái các đơn hàng của bạn</p>
                    </div>
                    <div class="profile-body" style="display:block;padding-top:8px">
                        <?php if (empty($notif_orders)): ?>
                        <div class="notif-empty">
                            <span class="material-symbols-outlined">notifications_off</span>
                            <p>Bạn chưa có thông báo nào.</p>
                        </div>
                        <?php else: foreach($notif_orders as $no):
        $cfg = notif_config($no['trangthai'], $no['phuongthuctt']??'');
    ?>
                        <div class="notif-item">
                            <div class="notif-icon <?=$cfg['cls']?>">
                                <span class="material-symbols-outlined" style="font-size:22px"><?=$cfg['icon']?></span>
                            </div>
                            <div class="notif-body">
                                <h4><?=$cfg['title']?> <span
                                        style="color:#ccc;font-weight:400;font-size:12px">#<?=$no['madh']?></span></h4>
                                <p><?=$cfg['msg']?>
                                    <?php if($no['tongtien']>0):?>
                                    Tổng tiền: <strong
                                        style="color:#ee4d2d"><?=number_format($no['tongtien'])?>đ</strong>.
                                    <?php endif;?>
                                    <?php if(!empty($no['phuongthuctt'])):?>
                                    Phương thức: <strong><?=htmlspecialchars($no['phuongthuctt'])?></strong>.
                                    <?php endif;?>
                                </p>
                                <div class="notif-time"><?=date('H:i · d/m/Y', strtotime($no['ngaydat']))?></div>
                            </div>
                            <div style="flex-shrink:0">
                                <a href="profile.php?tab=orders&order_status=<?=urlencode($no['trangthai'])?>"
                                    style="font-size:12px;color:#ff6a00;text-decoration:none;white-space:nowrap">Xem đơn
                                    →</a>
                            </div>
                        </div>
                        <?php endforeach; endif;?>
                    </div>
                </div>

                <?php /* ═══ TAB: HỒ SƠ ═══ */
elseif ($tab == 'profile'):?>

                <div class="account-card">
                    <div class="account-card-header">
                        <div class="account-card-title"><span class="material-symbols-outlined">contact_page</span> Tổng
                            quan hồ sơ</div>
                        <div class="btn-account-edit"
                            onclick="document.getElementById('editProfileForm').style.display='block';window.scrollTo(0,document.body.scrollHeight)">
                            <span class="material-symbols-outlined" style="font-size:18px">edit</span> Sửa chi tiết
                        </div>
                    </div>
                    <div class="account-summary-grid">
                        <img src="<?=$avatar_kh?>" alt="" class="account-summary-avatar">
                        <div>
                            <ul class="account-info-list">
                                <li class="account-info-item"><span class="account-info-label">Tên:</span><span
                                        class="account-info-value"><?=htmlspecialchars($ten_kh)?></span></li>
                                <li class="account-info-item"><span class="account-info-label">Tên đăng
                                        nhập:</span><span
                                        class="account-info-value"><?=htmlspecialchars($tendangnhap_kh)?></span></li>
                                <li class="account-info-item"><span class="account-info-label">Email:</span><span
                                        class="account-info-value <?=$email_kh?'':'not-set'?>"><?=$email_kh?htmlspecialchars($email_kh):'Chưa cập nhật'?></span>
                                </li>
                                <li class="account-info-item"><span class="account-info-label">Số điện
                                        thoại:</span><span
                                        class="account-info-value <?=$sdt_kh?'':'not-set'?>"><?=$sdt_kh?htmlspecialchars($sdt_kh):'Chưa cập nhật'?></span>
                                </li>
                            </ul>
                            <div style="display:flex;gap:15px;margin-top:15px">
                                <a href="profile.php?tab=profile" class="btn-account-link"
                                    style="background:#ff6a00;color:#fff">Quản lý hồ sơ</a>
                                <a href="profile.php?tab=password" class="btn-account-link">Đổi mật khẩu</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px">
                    <div class="account-card" style="margin-bottom:0">
                        <div class="account-card-header">
                            <div class="account-card-title"><span class="material-symbols-outlined">location_on</span>
                                Địa chỉ nhận hàng</div>
                        </div>
                        <div
                            style="font-size:15px;color:#333;min-height:80px;display:flex;flex-direction:column;justify-content:space-between">
                            <div style="line-height:1.5;font-weight:500;color:#ff6a00">
                                <?=$diachi_kh?htmlspecialchars($diachi_kh):'Bạn chưa cập nhật địa chỉ.'?></div>
                            <a href="profile.php?tab=address" class="btn-account-link"
                                style="align-self:flex-start;margin-top:15px">Quản lý địa chỉ</a>
                        </div>
                    </div>
                    <div class="account-card" style="margin-bottom:0">
                        <div class="account-card-header">
                            <div class="account-card-title"><span class="material-symbols-outlined">security</span> Bảo
                                mật tài khoản</div>
                        </div>
                        <div
                            style="font-size:15px;color:#333;display:flex;flex-direction:column;gap:10px;min-height:80px">
                            <div>Để bảo vệ tài khoản, UniStyle khuyên bạn nên thường xuyên thay đổi mật khẩu.</div>
                            <a href="profile.php?tab=password" class="btn-account-link"
                                style="align-self:flex-start">Cập nhật
                                mật khẩu</a>
                        </div>
                    </div>
                </div>

                <div id="editProfileForm" class="profile-content-card"
                    style="display:none;border-top:2px solid #ff6a00">
                    <div class="profile-header">
                        <h2>Hồ Sơ Của Tôi</h2>
                        <p>Quản lý thông tin hồ sơ để bảo mật tài khoản</p>
                    </div>
                    <form method="POST" action="profile.php?tab=profile" enctype="multipart/form-data">
                        <div class="profile-body">
                            <div class="profile-form">
                                <?=$message?>
                                <div class="form-row"><label>Tên đăng nhập</label>
                                    <div class="form-value" style="color:#666;font-weight:500">
                                        <?=htmlspecialchars($tendangnhap_kh)?></div>
                                </div>
                                <div class="form-row"><label>Tên</label><input type="text" name="tenkh"
                                        class="profile-input" value="<?=htmlspecialchars($ten_kh)?>" required /></div>
                                <div class="form-row"><label>Email</label><input type="email" name="email"
                                        class="profile-input" value="<?=htmlspecialchars($email_kh)?>"
                                        placeholder="Nhập email" /></div>
                                <div class="form-row"><label>Số điện thoại</label><input type="text" name="sdt"
                                        class="profile-input" value="<?=htmlspecialchars($sdt_kh)?>"
                                        placeholder="Nhập số điện thoại" /></div>
                                <div class="form-row"><label>Giới tính</label>
                                    <div class="radio-group">
                                        <label><input type="radio" name="gender" value="Nam"
                                                <?=$gioitinh_kh=='Nam' ?'checked':''?>> Nam</label>
                                        <label><input type="radio" name="gender" value="Nữ"
                                                <?=$gioitinh_kh=='Nữ'  ?'checked':''?>> Nữ</label>
                                        <label><input type="radio" name="gender" value="Khác"
                                                <?=$gioitinh_kh=='Khác'?'checked':''?>> Khác</label>
                                    </div>
                                </div>
                                <div class="form-row"><label>Ngày sinh</label>
                                    <div class="date-group">
                                        <select name="ngay" class="profile-select">
                                            <option>Ngày</option>
                                            <?php for($i=1;$i<=31;$i++){$v=str_pad($i,2,'0',STR_PAD_LEFT);echo"<option value='$v'".($n_ngay==$v?' selected':'').">$v</option>";}?>
                                        </select>
                                        <select name="thang" class="profile-select">
                                            <option>Tháng</option>
                                            <?php for($i=1;$i<=12;$i++){$v=str_pad($i,2,'0',STR_PAD_LEFT);echo"<option value='$v'".($n_thang==$v?' selected':'').">$v</option>";}?>
                                        </select>
                                        <select name="nam" class="profile-select">
                                            <option>Năm</option>
                                            <?php for($i=date('Y');$i>=1950;$i--){echo"<option value='$i'".($n_nam==$i?' selected':'').">$i</option>";}?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row"><label></label>
                                    <button type="submit" name="btn_save_profile" class="btn-save-profile">Lưu thay
                                        đổi</button>
                                    <button type="button" class="btn-save-profile"
                                        style="background:#fff;color:#555;border:1px solid #ccc;margin-left:10px"
                                        onclick="document.getElementById('editProfileForm').style.display='none';window.scrollTo(0,0)">Hủy</button>
                                </div>
                            </div>
                            <div class="profile-avatar-upload">
                                <div class="avatar-preview"><img src="<?=$avatar_kh?>" alt="" id="avatarPreview" />
                                </div>
                                <button type="button" class="btn-select-image"
                                    onclick="document.getElementById('avatarInput').click()">Chọn Ảnh</button>
                                <input type="file" name="avatar_upload" id="avatarInput" style="display:none"
                                    accept="image/png,image/jpeg,image/jpg" />
                                <div class="upload-hint">Tối đa 1 MB · Định dạng: .JPEG, .PNG</div>
                            </div>
                        </div>
                    </form>
                </div>

                <?php /* ═══ TAB: ĐỊA CHỈ ═══ */
elseif ($tab=='address'):?>
                <div class="profile-content-card">
                    <div class="profile-header">
                        <h2>Địa Chỉ Của Tôi</h2>
                        <p>Quản lý địa chỉ nhận hàng</p>
                    </div>
                    <div class="profile-body">
                        <form class="profile-form" method="POST" action="profile.php?tab=address"
                            style="max-width:600px">
                            <?=$message?>
                            <div class="form-row"><label>Địa chỉ hiện tại</label>
                                <div class="form-value" style="font-weight:500;color:#ff6a00;line-height:1.5">
                                    <?=$diachi_kh?htmlspecialchars($diachi_kh):'Chưa cập nhật'?></div>
                            </div>
                            <div class="form-row"><label>Tỉnh/Thành phố</label><input type="text" name="tinhthanh"
                                    class="profile-input" placeholder="TP. Hồ Chí Minh" required /></div>
                            <div class="form-row"><label>Quận/Huyện</label><input type="text" name="quanhuyen"
                                    class="profile-input" placeholder="Quận 1" required /></div>
                            <div class="form-row"><label>Phường/Xã</label><input type="text" name="phuongxa"
                                    class="profile-input" placeholder="Phường Bến Nghé" required /></div>
                            <div class="form-row"><label>Địa chỉ cụ thể</label><textarea name="diachi_chitiet"
                                    class="profile-input" rows="3" placeholder="Số nhà, Tên đường..."
                                    required></textarea></div>
                            <div class="form-row"><label></label><button type="submit" name="btn_save_address"
                                    class="btn-save-profile">Lưu địa chỉ mới</button></div>
                        </form>
                    </div>
                </div>

                <?php /* ═══ TAB: ĐỔI MẬT KHẨU ═══ */
elseif ($tab=='password'):?>
                <div class="profile-content-card">
                    <div class="profile-header">
                        <h2>Đổi Mật Khẩu</h2>
                        <p>Vui lòng không chia sẻ mật khẩu cho người khác</p>
                    </div>
                    <div class="profile-body">
                        <form class="profile-form" method="POST" action="profile.php?tab=password"
                            style="max-width:600px">
                            <?=$message?>
                            <div class="form-row"><label>Mật khẩu hiện tại</label><input type="password" name="old_pass"
                                    class="profile-input" required /></div>
                            <div class="form-row"><label>Mật khẩu mới</label><input type="password" name="new_pass"
                                    class="profile-input" required /></div>
                            <div class="form-row"><label>Xác nhận mật khẩu</label><input type="password"
                                    name="confirm_pass" class="profile-input" required /></div>
                            <div class="form-row"><label></label><button type="submit" name="btn_change_password"
                                    class="btn-save-profile">Xác nhận</button></div>
                        </form>
                    </div>
                </div>

                <?php endif;?>

            </main>
        </div>
    </div>

    <!-- ══ FOOTER ═══════════════════════════════════════════════════════ -->
    <footer class="footer">
        <div class="footer-newsletter">
            <div class="newsletter-text">
                <h2>Đăng ký để nhận ưu đãi</h2>
                <p>Nhận các ưu đãi và giảm giá độc quyền!</p>
            </div>
            <div class="newsletter-form"><input type="email" placeholder="Email của bạn..." /><button><span
                        class="material-symbols-outlined">mail</span></button></div>
        </div>
        <div class="footer-container">
            <div class="footer-col">
                <h2 class="logo"><img src="./assets/file_anh/0c4690d7-3599-4de4-a0a4-841817ead1c0.png" alt="" />
                    UniStyle</h2>
                <p>Liên hệ: <span>support@example.com</span></p>
                <p>📍 16 Thiên Hộ Vương, P1, Mỹ Tho, Tiền Giang</p>
                <p>📞 (+84) 0777331314</p>
                <div class="social-icons">
                    <a href="#"><i class="fa-brands fa-x-twitter"></i></a><a href="#"><i
                            class="fa-brands fa-instagram"></i></a>
                    <a href="#"><i class="fa-brands fa-facebook-f"></i></a><a href="#"><i
                            class="fa-brands fa-linkedin-in"></i></a>
                </div>
            </div>
            <div class="footer-col">
                <h3>Công ty</h3>
                <ul>
                    <li><a href="aboutus.php">Về chúng tôi</a></li>
                    <li><a href="aboutus.php">Tuyển dụng</a></li>
                    <li><a href="aboutus.php">Quy tắc kinh doanh</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h3>Chăm sóc khách hàng</h3>
                <ul>
                    <li><a href="contact.php">Theo dõi đơn hàng</a></li>
                    <li><a href="FAQ.php">Đổi / Trả hàng</a></li>
                    <li><a href="contact.php">Liên hệ</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h3>Dịch vụ</h3>
                <ul>
                    <li><a href="contact.php">In ấn - Photo</a></li>
                    <li><a href="shop.php">Văn phòng phẩm sỉ</a></li>
                    <li><a href="contact.php">Trung tâm hỗ trợ</a></li>
                </ul>
            </div>
        </div>
    </footer>

    <button id="backToTop"><span class="material-symbols-outlined">keyboard_arrow_up</span></button>
    <script>
    const btn = document.getElementById("backToTop");
    window.onscroll = () => btn.style.display = document.documentElement.scrollTop > 200 ? "block" : "none";
    btn.onclick = () => window.scrollTo({
        top: 0,
        behavior: "smooth"
    });
    const avatarInput = document.getElementById('avatarInput');
    if (avatarInput) {
        avatarInput.addEventListener('change', e => {
            if (e.target.files && e.target.files[0]) {
                const r = new FileReader();
                r.onload = e => document.getElementById('avatarPreview').src = e.target.result;
                r.readAsDataURL(e.target.files[0]);
            }
        });
    }

    function saveVoucherCode(code) {
        fetch('profile.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'ajax_action=save_voucher&ma_code=' + encodeURIComponent(code)
            })
            .then(r => r.json())
            .then(d => {
                alert(d.message);
                if (d.success) location.reload();
            });
    }
    </script>


</body>

</html>