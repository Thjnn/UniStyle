<?php
session_start();
include('./config/db.php');

if (!isset($_SESSION['khachhang_id'])) {
  header("Location: login.php");
  exit();
}

$kh_id = $_SESSION['khachhang_id'];
$message = '';
$tab = isset($_GET['tab']) ? $_GET['tab'] : 'profile';

$sql_get = "SELECT * FROM khachhang WHERE makh = '$kh_id'";
$result = $conn->query($sql_get);
$user_data = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['btn_save_profile'])) {
    $tenkh = $_POST['tenkh'];
    $email = $_POST['email'];
    $sdt = $_POST['sdt'];
    $gioitinh = isset($_POST['gender']) ? $_POST['gender'] : '';

    $ngay = $_POST['ngay'];
    $thang = $_POST['thang'];
    $nam = $_POST['nam'];
    $ngaysinh = NULL;
    if ($ngay != 'Ngày' && $thang != 'Tháng' && $nam != 'Năm') {
      $ngaysinh = "$nam-$thang-$ngay";
    }

    $avatar_query = "";
    if (isset($_FILES['avatar_upload']) && $_FILES['avatar_upload']['error'] == 0) {
      $target_dir = "./assets/file_anh/";
      $file_name = "avatar_" . time() . "_" . basename($_FILES["avatar_upload"]["name"]);
      $target_file = $target_dir . $file_name;
      $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

      if (in_array($imageFileType, ['jpg', 'jpeg', 'png'])) {
        if (move_uploaded_file($_FILES["avatar_upload"]["tmp_name"], $target_file)) {
          $avatar_query = ", avatar='$file_name'";
        }
      }
    }

    $ngaysinh_sql = $ngaysinh ? "'$ngaysinh'" : "NULL";
    $sql_update = "UPDATE khachhang SET tenkh='$tenkh', email='$email', sdt='$sdt', gioitinh='$gioitinh', ngaysinh=$ngaysinh_sql $avatar_query WHERE makh='$kh_id'";

    if ($conn->query($sql_update) === TRUE) {
      $_SESSION['khachhang_ten'] = $tenkh;
      $message = "<div style='color: #28a745; background: #e8f5e9; padding: 10px; border-radius: 4px; margin-bottom: 20px; font-size: 14px;'>Hồ sơ của bạn đã được lưu thành công!</div>";
      $result = $conn->query($sql_get);
      $user_data = $result->fetch_assoc();
    } else {
      $message = "<div style='color: #dc3545; background: #fdeced; padding: 10px; border-radius: 4px; margin-bottom: 20px; font-size: 14px;'>Đã xảy ra lỗi, vui lòng thử lại!</div>";
    }
  } elseif (isset($_POST['btn_save_address'])) {
    $diachi_moi = $_POST['diachi_chitiet'] . ", " . $_POST['phuongxa'] . ", " . $_POST['quanhuyen'] . ", " . $_POST['tinhthanh'];

    $sql_update_addr = "UPDATE khachhang SET diachi='$diachi_moi' WHERE makh='$kh_id'";
    if ($conn->query($sql_update_addr) === TRUE) {
      $message = "<div style='color: #28a745; background: #e8f5e9; padding: 10px; border-radius: 4px; margin-bottom: 20px; font-size: 14px;'>Địa chỉ đã được cập nhật thành công!</div>";
      $result = $conn->query($sql_get);
      $user_data = $result->fetch_assoc();
    } else {
      $message = "<div style='color: #dc3545; background: #fdeced; padding: 10px; border-radius: 4px; margin-bottom: 20px; font-size: 14px;'>Đã xảy ra lỗi, vui lòng thử lại!</div>";
    }
  } elseif (isset($_POST['btn_change_password'])) {
    $old_pass = $_POST['old_pass'];
    $new_pass = $_POST['new_pass'];
    $confirm_pass = $_POST['confirm_pass'];

    if ($old_pass != $user_data['matkhau']) {
      $message = "<div style='color: #dc3545; background: #fdeced; padding: 10px; border-radius: 4px; margin-bottom: 20px; font-size: 14px;'>Mật khẩu hiện tại không chính xác!</div>";
    } elseif ($new_pass != $confirm_pass) {
      $message = "<div style='color: #dc3545; background: #fdeced; padding: 10px; border-radius: 4px; margin-bottom: 20px; font-size: 14px;'>Mật khẩu xác nhận không khớp!</div>";
    } else {
      $sql_update_pass = "UPDATE khachhang SET matkhau='$new_pass' WHERE makh='$kh_id'";
      if ($conn->query($sql_update_pass) === TRUE) {
        $message = "<div style='color: #28a745; background: #e8f5e9; padding: 10px; border-radius: 4px; margin-bottom: 20px; font-size: 14px;'>Đổi mật khẩu thành công! Vui lòng sử dụng mật khẩu mới cho lần đăng nhập sau.</div>";
        $result = $conn->query($sql_get);
        $user_data = $result->fetch_assoc();
      } else {
        $message = "<div style='color: #dc3545; background: #fdeced; padding: 10px; border-radius: 4px; margin-bottom: 20px; font-size: 14px;'>Đã xảy ra lỗi, vui lòng thử lại!</div>";
      }
    }
  }
}

$ten_kh = $user_data['tenkh'];
$email_kh = isset($user_data['email']) ? $user_data['email'] : '';
$sdt_kh = $user_data['sdt'];
$tendangnhap_kh = $user_data['tendangnhap'];
$diachi_kh = isset($user_data['diachi']) ? $user_data['diachi'] : '';
$gioitinh_kh = isset($user_data['gioitinh']) ? $user_data['gioitinh'] : '';
$ngaysinh_kh = isset($user_data['ngaysinh']) ? $user_data['ngaysinh'] : '';
$avatar_kh = (!empty($user_data['avatar'])) ? "./assets/file_anh/" . $user_data['avatar'] : "./assets/file_anh/default_avatar.png";

$n_ngay = 'Ngày';
$n_thang = 'Tháng';
$n_nam = 'Năm';
if ($ngaysinh_kh) {
  $parts = explode('-', $ngaysinh_kh);
  if (count($parts) == 3) {
    $n_nam = $parts[0];
    $n_thang = $parts[1];
    $n_ngay = $parts[2];
  }
}
?>
<!doctype html>
<html lang="vi">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tài Khoản Của Tôi - UniStyle</title>
  <link
    rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,0..1" />
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link
    rel="shortcut icon"
    href="assets/file_anh/0c4690d7-3599-4de4-a0a4-841817ead1c0.png" />
  <link rel="stylesheet" href="./assets/css/style.css" />
  <link rel="stylesheet" href="./assets/css/reposive.css" />
  <link rel="stylesheet" href="./assets/css/profile.css" />
</head>

<body>
  <header>
    <div class="container">
      <div class="header-content">
        <div class="menu-toggle">
          <span class="material-symbols-outlined">menu</span>
        </div>
        <div class="logo">
          <a href="index.php"><img
              src="./assets/file_anh/0c4690d7-3599-4de4-a0a4-841817ead1c0.png"
              alt="" /></a>
        </div>
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
          <div class="search-box">
            <span class="material-symbols-outlined search-icon">search</span>

            <form class="search-form" action="shop.php" method="GET">
              <input
                type="text"
                name="keyword"
                placeholder="Tìm sản phẩm..." />
            </form>
          </div>
          <a href="package.php"><span class="material-symbols-outlined"> local_mall </span></a>
          <?php
          if (isset($_SESSION['khachhang_id'])) {
            echo '<a href="profile.php"><span class="material-symbols-outlined"> person </span></a>';
          } else {
            echo '<a href="login.php"><span class="material-symbols-outlined"> person </span></a>';
          }
          ?>
        </div>
      </div>
    </div>
  </header>

  <div class="profile-layout-bg">
    <div class="container profile-page-container">
      <aside class="profile-sidebar">
        <div class="profile-user-info">
          <div class="profile-avatar">
            <img src="<?php echo $avatar_kh; ?>" alt="" />
          </div>
          <div class="profile-name-group">
            <div class="profile-name"><?php echo $_SESSION['khachhang_ten']; ?></div>
            <div class="profile-edit-btn">
              <span class="material-symbols-outlined">edit</span> Sửa hồ sơ
            </div>
          </div>
        </div>

        <nav class="profile-menu">
          <div class="menu-group">
            <div class="menu-group-title">
              <span class="material-symbols-outlined" style="color: #05a;">person</span>
              <a href="profile.php?tab=profile" style="text-decoration: none; color: inherit;">Tài Khoản Của Tôi</a>
            </div>
            <div class="menu-sub-items">
              <a href="profile.php?tab=profile" class="<?php echo ($tab == 'profile') ? 'active' : ''; ?>">Hồ sơ</a>
              <a href="profile.php?tab=address" class="<?php echo ($tab == 'address') ? 'active' : ''; ?>">Địa chỉ</a>
              <a href="profile.php?tab=password" class="<?php echo ($tab == 'password') ? 'active' : ''; ?>">Đổi mật khẩu</a>
            </div>
          </div>

          <a href="profile.php?tab=orders" class="menu-item <?php echo ($tab == 'orders') ? 'active' : ''; ?>">
            <span class="material-symbols-outlined" style="color: #2563eb;">receipt_long</span>
            Đơn Mua
          </a>

          <a href="profile.php?tab=notifications" class="menu-item <?php echo ($tab == 'notifications') ? 'active' : ''; ?>">
            <span class="material-symbols-outlined" style="color: #ee4d2d;">notifications</span>
            Thông Báo
          </a>

          <a href="profile.php?tab=vouchers" class="menu-item <?php echo ($tab == 'vouchers') ? 'active' : ''; ?>">
            <span class="material-symbols-outlined" style="color: #ffc107;">confirmation_number</span>
            Kho Voucher
          </a>

          <a href="logout.php" class="menu-item" style="margin-top: 20px;">
            <span class="material-symbols-outlined" style="color: #999;">logout</span>
            Đăng Xuất
          </a>
        </nav>
      </aside>

      <main class="profile-main">

        <?php if ($tab == 'vouchers'): ?>
          <style>
            .voucher-card {
              display: flex;
              background: #fff;
              border: 1px solid #e8e8e8;
              border-radius: 4px;
              overflow: hidden;
              margin-bottom: 15px;
              box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
              transition: transform 0.2s, box-shadow 0.2s;
            }

            .voucher-card:hover {
              transform: translateY(-2px);
              box-shadow: 0 4px 8px rgba(0, 0, 0, 0.08);
            }

            .voucher-left {
              width: 120px;
              display: flex;
              flex-direction: column;
              justify-content: center;
              align-items: center;
              color: #fff;
              border-right: 2px dashed #e8e8e8;
              position: relative;
              padding: 15px 10px;
              text-align: center;
            }

            .voucher-left::before,
            .voucher-left::after {
              content: '';
              position: absolute;
              width: 16px;
              height: 16px;
              background: #fff;
              border-radius: 50%;
              right: -9px;
              border-left: 1px solid #e8e8e8;
            }

            .voucher-left::before {
              top: -8px;
              border-bottom: 1px solid #e8e8e8;
              transform: rotate(45deg);
            }

            .voucher-left::after {
              bottom: -8px;
              border-top: 1px solid #e8e8e8;
              transform: rotate(-45deg);
            }

            .voucher-right {
              flex: 1;
              padding: 20px;
              display: flex;
              justify-content: space-between;
              align-items: center;
            }

            .voucher-info h4 {
              margin-bottom: 8px;
              font-size: 16px;
              color: #333;
              font-weight: 600;
            }

            .voucher-info p {
              font-size: 13px;
              color: #666;
              margin-bottom: 8px;
              line-height: 1.4;
            }

            .voucher-info .expiry {
              font-size: 12px;
              color: #ff6a00;
              font-weight: 500;
            }

            .voucher-action button {
              background: #ff6a00;
              color: #fff;
              border: none;
              padding: 8px 16px;
              border-radius: 3px;
              cursor: pointer;
              font-size: 13px;
              transition: background 0.2s;
            }

            .voucher-action button:hover {
              background: #ff8533;
            }

            .bg-freeship {
              background: linear-gradient(135deg, #00bfa5, #009688);
            }

            .bg-discount {
              background: linear-gradient(135deg, #ff6a00, #ee4d2d);
            }
          </style>
          <div class="profile-content-card" style="min-height: 500px;">
            <div class="profile-header" style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 15px;">
              <div>
                <h2>Kho Voucher</h2>
                <p>Quản lý các mã giảm giá và miễn phí vận chuyển</p>
              </div>
              <div style="display: flex; gap: 10px; align-items: center;">
                <span style="font-size: 14px; font-weight: 500;">Thêm mã: </span>
                <input type="text" placeholder="Nhập mã voucher..." style="padding: 10px 12px; border: 1px solid #e0e0e0; border-radius: 3px; outline: none; font-size: 14px; width: 220px;">
                <button style="background: #ff6a00; color: #fff; border: none; padding: 11px 20px; border-radius: 3px; cursor: pointer; font-weight: 500;">Lưu</button>
              </div>
            </div>

            <div class="profile-body" style="display: block; padding-top: 20px;">
              <div style="display: flex; gap: 25px; border-bottom: 1px solid #efefef; margin-bottom: 25px; padding-bottom: 12px;" id="voucherTabs">
                <a href="javascript:void(0);" onclick="filterVoucher('all', this)" class="v-tab active" style="color: #ff6a00; font-weight: 500; border-bottom: 2px solid #ff6a00; padding-bottom: 12px; text-decoration: none; font-size: 15px; transition: all 0.3s;">Tất cả</a>
                <a href="javascript:void(0);" onclick="filterVoucher('freeship', this)" class="v-tab" style="color: #666; text-decoration: none; font-size: 15px; padding-bottom: 12px; border-bottom: 2px solid transparent; transition: all 0.3s;">Miễn phí vận chuyển</a>
                <a href="javascript:void(0);" onclick="filterVoucher('discount', this)" class="v-tab" style="color: #666; text-decoration: none; font-size: 15px; padding-bottom: 12px; border-bottom: 2px solid transparent; transition: all 0.3s;">Giảm giá UniStyle</a>
              </div>

              <div id="voucherList">
                <div class="voucher-card type-freeship">
                  <div class="voucher-left bg-freeship">
                    <span class="material-symbols-outlined" style="font-size: 36px; margin-bottom: 5px;">local_shipping</span>
                    <span style="font-size: 13px; font-weight: 600; text-transform: uppercase;">Free Ship</span>
                  </div>
                  <div class="voucher-right">
                    <div class="voucher-info">
                      <h4>Miễn phí vận chuyển</h4>
                      <p>Đơn tối thiểu đ150k. Giảm tối đa đ30k</p>
                      <div class="expiry">Sắp hết hạn: Còn 2 ngày</div>
                    </div>
                    <div class="voucher-action">
                      <button>Dùng ngay</button>
                    </div>
                  </div>
                </div>

                <div class="voucher-card type-freeship">
                  <div class="voucher-left bg-freeship">
                    <span class="material-symbols-outlined" style="font-size: 36px; margin-bottom: 5px;">local_shipping</span>
                    <span style="font-size: 13px; font-weight: 600; text-transform: uppercase;">Free Ship</span>
                  </div>
                  <div class="voucher-right">
                    <div class="voucher-info">
                      <h4>Freeship Xtra</h4>
                      <p>Đơn tối thiểu đ300k. Giảm tối đa đ70k</p>
                      <div class="expiry" style="color: #999;">HSD: 30/06/2026</div>
                    </div>
                    <div class="voucher-action">
                      <button>Dùng ngay</button>
                    </div>
                  </div>
                </div>

                <div class="voucher-card type-discount">
                  <div class="voucher-left bg-discount">
                    <span class="material-symbols-outlined" style="font-size: 36px; margin-bottom: 5px;">sell</span>
                    <span style="font-size: 13px; font-weight: 600; text-transform: uppercase;">Giảm 10%</span>
                  </div>
                  <div class="voucher-right">
                    <div class="voucher-info">
                      <h4>Giảm 10%</h4>
                      <p>Áp dụng cho danh mục Bút Viết. Giảm tối đa đ50k</p>
                      <div class="expiry" style="color: #999;">HSD: 30/05/2026</div>
                    </div>
                    <div class="voucher-action">
                      <button>Dùng ngay</button>
                    </div>
                  </div>
                </div>

                <div class="voucher-card type-discount">
                  <div class="voucher-left bg-discount">
                    <span class="material-symbols-outlined" style="font-size: 36px; margin-bottom: 5px;">payments</span>
                    <span style="font-size: 13px; font-weight: 600; text-transform: uppercase;">Giảm 20k</span>
                  </div>
                  <div class="voucher-right">
                    <div class="voucher-info">
                      <h4>Giảm đ20k</h4>
                      <p>Đơn tối thiểu đ200k. Áp dụng toàn shop</p>
                      <div class="expiry" style="color: #999;">HSD: 15/06/2026</div>
                    </div>
                    <div class="voucher-action">
                      <button>Dùng ngay</button>
                    </div>
                  </div>
                </div>

                <div class="voucher-card type-discount">
                  <div class="voucher-left bg-discount">
                    <span class="material-symbols-outlined" style="font-size: 36px; margin-bottom: 5px;">stars</span>
                    <span style="font-size: 13px; font-weight: 600; text-transform: uppercase;">Giảm 50k</span>
                  </div>
                  <div class="voucher-right">
                    <div class="voucher-info">
                      <h4>Khách hàng mới</h4>
                      <p>Đơn tối thiểu đ0. Áp dụng cho lần mua đầu tiên</p>
                      <div class="expiry" style="color: #999;">HSD: 31/12/2026</div>
                    </div>
                    <div class="voucher-action">
                      <button>Dùng ngay</button>
                    </div>
                  </div>
                </div>
              </div>

              <script>
                function filterVoucher(type, element) {
                  let tabs = document.querySelectorAll('.v-tab');
                  tabs.forEach(tab => {
                    tab.style.color = '#666';
                    tab.style.fontWeight = 'normal';
                    tab.style.borderBottom = '2px solid transparent';
                  });
                  element.style.color = '#ff6a00';
                  element.style.fontWeight = '500';
                  element.style.borderBottom = '2px solid #ff6a00';

                  let cards = document.querySelectorAll('.voucher-card');
                  cards.forEach(card => {
                    if (type === 'all') {
                      card.style.display = 'flex';
                    } else if (card.classList.contains('type-' + type)) {
                      card.style.display = 'flex';
                    } else {
                      card.style.display = 'none';
                    }
                  });
                }
              </script>
            </div>
          </div>

        <?php elseif ($tab == 'orders'): ?>

          <style>
            .o-tab {
              flex: 1;
              text-align: center;
              padding: 15px 0;
              color: #333;
              border-bottom: 2px solid transparent;
              text-decoration: none;
              font-size: 15px;
              transition: all 0.2s;
            }

            .o-tab.active {
              color: #ff6a00;
              border-bottom: 2px solid #ff6a00;
              font-weight: 500;
            }

            .o-tab:hover {
              color: #ff6a00;
            }

            .order-card-mall {
              background-color: #ee4d2d;
              color: #fff;
              font-size: 10px;
              padding: 2px 4px;
              border-radius: 2px;
              margin-left: 5px;
              font-weight: 600;
              text-transform: uppercase;
            }

            .order-status-completed {
              color: #26aa99;
              text-transform: uppercase;
              font-size: 14px;
              font-weight: 500;
              display: flex;
              align-items: center;
              gap: 5px;
            }

            .order-status-completed .material-symbols-outlined {
              font-size: 18px;
              color: #26aa99;
            }

            .order-status-shipping {
              color: #26aa99;
              text-transform: uppercase;
              font-size: 14px;
              font-weight: 500;
              display: flex;
              align-items: center;
              gap: 5px;
            }

            .order-status-shipping .material-symbols-outlined {
              font-size: 18px;
              color: #26aa99;
            }

            .btn-order {
              padding: 8px 20px;
              background: #fff;
              border: 1px solid #ccc;
              color: #555;
              border-radius: 3px;
              cursor: pointer;
              font-size: 14px;
              transition: background 0.2s;
            }

            .btn-order-primary {
              background: #ff6a00;
              border: 1px solid #ff6a00;
              color: #fff;
            }

            .btn-order-primary:hover {
              background: #ff8533;
              border-color: #ff8533;
            }

            .btn-order:hover {
              background: #f8f8f8;
            }
          </style>
          <div style="background: #fff; display: flex; box-shadow: 0 1px 2px rgba(0,0,0,0.05); margin-bottom: 15px; border-radius: 4px;">
            <a href="javascript:void(0)" onclick="filterOrder('all', this)" class="o-tab active">Tất cả</a>
            <a href="javascript:void(0)" onclick="filterOrder('pending', this)" class="o-tab">Chờ xác nhận</a>
            <a href="javascript:void(0)" onclick="filterOrder('pending_pickup', this)" class="o-tab">Chờ lấy hàng</a>
            <a href="javascript:void(0)" onclick="filterOrder('shipping', this)" class="o-tab">Đang giao</a>
            <a href="javascript:void(0)" onclick="filterOrder('completed', this)" class="o-tab">Đánh giá</a>
          </div>

          <div id="orderList">

            <div class="order-card type-pending" style="background: #fff; padding: 20px; border-radius: 4px; box-shadow: 0 1px 2px rgba(0,0,0,0.05); margin-bottom: 15px;">
              <div style="display: flex; justify-content: space-between; border-bottom: 1px solid #efefef; padding-bottom: 12px; margin-bottom: 15px; align-items: center;">
                <div style="font-weight: 600; display: flex; align-items: center; gap: 5px;">
                  <span class="material-symbols-outlined" style="font-size: 20px;">storefront</span> UniStyle Official
                  <span class="order-card-mall">Mall</span>
                </div>
                <div style="color: #ff6a00; text-transform: uppercase; font-size: 14px; font-weight: 500;">Chờ xác nhận</div>
              </div>
              <div style="display: flex; gap: 15px;">
                <img src="./assets/file_anh/San_Pham/vn-11134207-7ras8-mcte935uer8c51_tn.jpg" style="width: 80px; height: 80px; object-fit: cover; border: 1px solid #e0e0e0;">
                <div style="flex: 1;">
                  <div style="font-size: 16px; color: #333; margin-bottom: 5px;">Ba lô - cặp Thiên Long dành cho học sinh</div>
                  <div style="font-size: 14px; color: #999;">Phân loại hàng: Đen</div>
                  <div style="font-size: 14px; margin-top: 5px;">x1</div>
                </div>
                <div style="text-align: right; color: #ee4d2d; font-weight: 500; font-size: 15px;">180.000đ</div>
              </div>
              <div style="border-top: 1px solid #efefef; margin-top: 15px; padding-top: 15px; text-align: right;">
                <div style="font-size: 15px; margin-bottom: 15px;">Thành tiền: <span style="font-size: 22px; color: #ee4d2d; font-weight: 600; margin-left: 10px;">180.000đ</span></div>
                <button class="btn-order">Hủy Đơn Hàng</button>
                <button class="btn-order btn-order-primary" style="margin-left: 10px;">Liên Hệ Người Bán</button>
              </div>
            </div>

            <div class="order-card type-pending_pickup" style="background: #fff; padding: 20px; border-radius: 4px; box-shadow: 0 1px 2px rgba(0,0,0,0.05); margin-bottom: 15px;">
              <div style="display: flex; justify-content: space-between; border-bottom: 1px solid #efefef; padding-bottom: 12px; margin-bottom: 15px; align-items: center;">
                <div style="font-weight: 600; display: flex; align-items: center; gap: 5px;">
                  <span class="material-symbols-outlined" style="font-size: 20px;">storefront</span> UniStyle Official
                  <span class="order-card-mall">Mall</span>
                </div>
                <div style="color: #ff6a00; text-transform: uppercase; font-size: 14px; font-weight: 500;">Chờ lấy hàng</div>
              </div>
              <div style="display: flex; gap: 15px;">
                <img src="./assets/file_anh/San_Pham/vo_10021_38734cfceb7242cf82ef2fb2b96fc0ea.jpg" style="width: 80px; height: 80px; object-fit: cover; border: 1px solid #e0e0e0;">
                <div style="flex: 1;">
                  <div style="font-size: 16px; color: #333; margin-bottom: 5px;">Vở Hồng Hà Pupil Bốn mùa 200 trang</div>
                  <div style="font-size: 14px; color: #999;">Phân loại hàng: 10 quyển</div>
                  <div style="font-size: 14px; margin-top: 5px;">x1</div>
                </div>
                <div style="text-align: right; color: #ee4d2d; font-weight: 500; font-size: 15px;">120.000đ</div>
              </div>
              <div style="border-top: 1px solid #efefef; margin-top: 15px; padding-top: 15px; text-align: right;">
                <div style="font-size: 15px; margin-bottom: 15px;">Thành tiền: <span style="font-size: 22px; color: #ee4d2d; font-weight: 600; margin-left: 10px;">120.000đ</span></div>
                <button class="btn-order">Hủy Đơn Hàng</button>
                <button class="btn-order btn-order-primary" style="margin-left: 10px;">Liên Hệ Người Bán</button>
              </div>
            </div>

            <div class="order-card type-shipping" style="background: #fff; padding: 20px; border-radius: 4px; box-shadow: 0 1px 2px rgba(0,0,0,0.05); margin-bottom: 15px;">
              <div style="display: flex; justify-content: space-between; border-bottom: 1px solid #efefef; padding-bottom: 12px; margin-bottom: 15px; align-items: center;">
                <div style="font-weight: 600; display: flex; align-items: center; gap: 5px;">
                  <span class="material-symbols-outlined" style="font-size: 20px;">storefront</span> UniStyle Official
                  <span class="order-card-mall">Mall</span>
                </div>
                <div class="order-status-shipping">
                  <span class="material-symbols-outlined">local_shipping</span> Đang giao hàng
                </div>
              </div>
              <div style="display: flex; gap: 15px;">
                <img src="./assets/file_anh/San_Pham/vn-11134201-7ras8-m0l92bqq2jn140.webp" style="width: 80px; height: 80px; object-fit: cover; border: 1px solid #e0e0e0;">
                <div style="flex: 1;">
                  <div style="font-size: 16px; color: #333; margin-bottom: 5px;">Máy tính CASIO Fx 580VN X thế hệ mới ( Màu Hồng )</div>
                  <div style="font-size: 14px; color: #999;">Phân loại hàng: Hồng</div>
                  <div style="font-size: 14px; margin-top: 5px;">x1</div>
                </div>
                <div style="text-align: right; color: #ee4d2d; font-weight: 500; font-size: 15px;">432.000đ</div>
              </div>
              <div style="border-top: 1px solid #efefef; margin-top: 15px; padding-top: 15px; text-align: right;">
                <div style="font-size: 15px; margin-bottom: 15px;">Thành tiền: <span style="font-size: 22px; color: #ee4d2d; font-weight: 600; margin-left: 10px;">432.000đ</span></div>
                <button class="btn-order">Theo Dõi Đơn Hàng</button>
                <button class="btn-order-primary btn-order" style="margin-left: 10px; background: #ee4d2d; border-color: #ee4d2d;">Đã Nhận Được Hàng</button>
              </div>
            </div>

            <div class="order-card type-completed" style="background: #fff; padding: 20px; border-radius: 4px; box-shadow: 0 1px 2px rgba(0,0,0,0.05); margin-bottom: 15px;">
              <div style="display: flex; justify-content: space-between; border-bottom: 1px solid #efefef; padding-bottom: 12px; margin-bottom: 15px; align-items: center;">
                <div style="font-weight: 600; display: flex; align-items: center; gap: 5px;">
                  <span class="material-symbols-outlined" style="font-size: 20px;">storefront</span> UniStyle Official
                  <span class="order-card-mall">Mall</span>
                </div>
                <div class="order-status-completed">
                  <span class="material-symbols-outlined">task_alt</span> Hoàn thành
                </div>
              </div>
              <div style="display: flex; gap: 15px;">
                <img src="./assets/file_anh/San_Pham/artboard_6_993a955c7bc34ed983cf23d627053848.jpg" style="width: 80px; height: 80px; object-fit: cover; border: 1px solid #e0e0e0;">
                <div style="flex: 1;">
                  <div style="font-size: 16px; color: #333; margin-bottom: 5px;">Bút bi Thiên Long TL027</div>
                  <div style="font-size: 14px; color: #999;">Phân loại hàng: Xanh</div>
                  <div style="font-size: 14px; margin-top: 5px;">x5</div>
                </div>
                <div style="text-align: right; color: #ee4d2d; font-weight: 500; font-size: 15px;">25.000đ</div>
              </div>
              <div style="border-top: 1px solid #efefef; margin-top: 15px; padding-top: 15px; text-align: right;">
                <div style="font-size: 15px; margin-bottom: 15px;">Thành tiền: <span style="font-size: 22px; color: #ee4d2d; font-weight: 600; margin-left: 10px;">25.000đ</span></div>
                <button class="btn-order">Mua Lại</button>
                <button class="btn-order btn-order-primary" style="margin-left: 10px;">Đánh Giá Sản Phẩm</button>
              </div>
            </div>

          </div>

          <script>
            function filterOrder(type, element) {
              let tabs = document.querySelectorAll('.o-tab');
              tabs.forEach(tab => tab.classList.remove('active'));
              element.classList.add('active');

              let cards = document.querySelectorAll('.order-card');
              cards.forEach(card => {
                if (type === 'all' || card.classList.contains('type-' + type)) {
                  card.style.display = 'block';
                } else {
                  card.style.display = 'none';
                }
              });
            }
          </script>
        <?php elseif ($tab == 'notifications'): ?>
          <div class="profile-content-card">
            <div class="profile-header">
              <h2>Thông Báo</h2>
              <p>Cập nhật những thông tin mới nhất về đơn hàng của bạn</p>
            </div>
            <div class="profile-body" style="display: block; padding-top: 10px;">

              <div style="display: flex; gap: 15px; padding: 20px 0; border-bottom: 1px solid #efefef;">
                <div style="width: 45px; height: 45px; border-radius: 50%; background: #e8f5e9; display: flex; align-items: center; justify-content: center; color: #28a745; flex-shrink: 0;">
                  <span class="material-symbols-outlined">check_circle</span>
                </div>
                <div>
                  <h4 style="margin-bottom: 5px; color: #333; font-weight: 500;">Đơn hàng đã được xác nhận</h4>
                  <p style="font-size: 14px; color: #666; margin-bottom: 5px; line-height: 1.4;">Đơn hàng #DH-1042 của bạn đã được hệ thống xác nhận và đang trong quá trình đóng gói chuẩn bị giao cho đơn vị vận chuyển.</p>
                  <span style="font-size: 12px; color: #999;">14:30 - Hôm nay</span>
                </div>
              </div>

              <div style="display: flex; gap: 15px; padding: 20px 0; border-bottom: 1px solid #efefef;">
                <div style="width: 45px; height: 45px; border-radius: 50%; background: #fff3e0; display: flex; align-items: center; justify-content: center; color: #ff9800; flex-shrink: 0;">
                  <span class="material-symbols-outlined">local_mall</span>
                </div>
                <div>
                  <h4 style="margin-bottom: 5px; color: #333; font-weight: 500;">Đã đặt hàng thành công</h4>
                  <p style="font-size: 14px; color: #666; margin-bottom: 5px; line-height: 1.4;">Bạn đã đặt thành công đơn hàng #DH-1042 trị giá 842.000đ. Vui lòng chờ UniStyle xác nhận đơn hàng nhé!</p>
                  <span style="font-size: 12px; color: #999;">10:15 - Hôm nay</span>
                </div>
              </div>

            </div>
          </div>

        <?php elseif ($tab == 'profile'): ?>

          <style>
            .account-card {
              background: #fff;
              border-radius: 4px;
              box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
              padding: 25px;
              margin-bottom: 20px;
            }

            .account-card-header {
              display: flex;
              justify-content: space-between;
              align-items: center;
              border-bottom: 1px solid #efefef;
              padding-bottom: 15px;
              margin-bottom: 20px;
            }

            .account-card-title {
              font-size: 18px;
              font-weight: 600;
              color: #333;
              display: flex;
              align-items: center;
              gap: 10px;
            }

            .account-card-title .material-symbols-outlined {
              color: #ff6a00;
              font-size: 24px;
            }

            .btn-account-edit {
              color: #ff6a00;
              font-size: 14px;
              text-decoration: none;
              font-weight: 500;
              display: flex;
              align-items: center;
              gap: 5px;
              cursor: pointer;
            }

            .account-summary-grid {
              display: grid;
              grid-template-columns: 100px 1fr;
              gap: 20px;
              align-items: center;
            }

            .account-summary-avatar {
              width: 100px;
              height: 100px;
              border-radius: 50%;
              border: 3px solid #f0f0f0;
              object-fit: cover;
            }

            .account-info-list {
              list-style: none;
              padding: 0;
              margin: 0;
            }

            .account-info-item {
              display: grid;
              grid-template-columns: 120px 1fr;
              gap: 10px;
              padding: 8px 0;
              border-bottom: 1px solid #fafafa;
              font-size: 15px;
            }

            .account-info-item:last-child {
              border-bottom: none;
            }

            .account-info-label {
              color: #888;
            }

            .account-info-value {
              color: #333;
              font-weight: 500;
            }

            .account-info-value.not-set {
              color: #aaa;
              font-style: italic;
              font-weight: normal;
            }

            .account-links {
              display: flex;
              gap: 15px;
              margin-top: 15px;
            }

            .btn-account-link {
              padding: 10px 20px;
              border: 1px solid #ff6a00;
              color: #ff6a00;
              text-decoration: none;
              border-radius: 4px;
              font-weight: 500;
              font-size: 14px;
              transition: background 0.2s;
            }

            .btn-account-link:hover {
              background: #fffcf8;
            }
          </style>

          <div class="account-card">
            <div class="account-card-header">
              <div class="account-card-title">
                <span class="material-symbols-outlined">contact_page</span> Tổng quan hồ sơ
              </div>
              <div class="btn-account-edit" onclick="document.getElementById('editProfileForm').style.display='block'; window.scrollTo(0, document.body.scrollHeight);">
                <span class="material-symbols-outlined" style="font-size: 18px;">edit</span> Sửa chi tiết
              </div>
            </div>
            <div class="account-summary-grid">
              <img src="<?php echo $avatar_kh; ?>" alt="Avatar" class="account-summary-avatar">
              <div>
                <ul class="account-info-list">
                  <li class="account-info-item">
                    <span class="account-info-label">Tên:</span>
                    <span class="account-info-value"><?php echo $ten_kh; ?></span>
                  </li>
                  <li class="account-info-item">
                    <span class="account-info-label">Tên đăng nhập:</span>
                    <span class="account-info-value"><?php echo $tendangnhap_kh; ?></span>
                  </li>
                  <li class="account-info-item">
                    <span class="account-info-label">Email:</span>
                    <span class="account-info-value <?php echo $email_kh ? '' : 'not-set'; ?>">
                      <?php echo $email_kh ? $email_kh : 'Chưa cập nhật'; ?>
                    </span>
                  </li>
                  <li class="account-info-item">
                    <span class="account-info-label">Số điện thoại:</span>
                    <span class="account-info-value <?php echo $sdt_kh ? '' : 'not-set'; ?>">
                      <?php echo $sdt_kh ? $sdt_kh : 'Chưa cập nhật'; ?>
                    </span>
                  </li>
                </ul>
                <div class="account-links">
                  <a href="profile.php?tab=profile" class="btn-account-link" style="background: #ff6a00; color: #fff;">Quản lý hồ sơ</a>
                  <a href="profile.php?tab=password" class="btn-account-link">Đổi mật khẩu</a>
                </div>
              </div>
            </div>
          </div>

          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div class="account-card" style="margin-bottom: 0;">
              <div class="account-card-header">
                <div class="account-card-title">
                  <span class="material-symbols-outlined">location_on</span> Địa chỉ nhận hàng
                </div>
              </div>
              <div style="font-size: 15px; color: #333; min-height: 80px; display: flex; flex-direction: column; justify-content: space-between;">
                <div style="line-height: 1.5; font-weight: 500; color: #ff6a00;">
                  <?php echo $diachi_kh ? $diachi_kh : 'Bạn chưa cập nhật địa chỉ mặc định.'; ?>
                </div>
                <a href="profile.php?tab=address" class="btn-account-link" style="align-self: flex-start; margin-top: 15px;">Quản lý địa chỉ</a>
              </div>
            </div>
            <div class="account-card" style="margin-bottom: 0;">
              <div class="account-card-header">
                <div class="account-card-title">
                  <span class="material-symbols-outlined">security</span> Bảo mật tài khoản
                </div>
              </div>
              <div style="font-size: 15px; color: #333; display: flex; flex-direction: column; gap: 10px; justify-content: space-between; min-height: 80px;">
                <div>Để bảo vệ tài khoản, UniStyle khuyên bạn nên thường xuyên thay đổi mật khẩu định kỳ.</div>
                <a href="profile.php?tab=password" class="btn-account-link" style="align-self: flex-start; margin-top: 5px;">Cập nhật mật khẩu</a>
              </div>
            </div>
          </div>

          <div id="editProfileForm" class="profile-content-card" style="display: none; border-top: 2px solid #ff6a00; margin-top: 0;">
            <div class="profile-header">
              <h2>Hồ Sơ Của Tôi</h2>
              <p>Quản lý thông tin hồ sơ để bảo mật tài khoản</p>
            </div>
            <form method="POST" action="profile.php?tab=profile" enctype="multipart/form-data">
              <div class="profile-body">
                <div class="profile-form">
                  <?php echo $message; ?>
                  <div class="form-row">
                    <label>Tên đăng nhập</label>
                    <div class="form-value" style="color: #666; font-weight: 500;"><?php echo $tendangnhap_kh; ?></div>
                  </div>
                  <div class="form-row">
                    <label>Tên</label>
                    <input type="text" name="tenkh" class="profile-input" value="<?php echo $ten_kh; ?>" required />
                  </div>
                  <div class="form-row">
                    <label>Email</label>
                    <input type="email" name="email" class="profile-input" value="<?php echo $email_kh; ?>" placeholder="Nhập địa chỉ email của bạn" />
                  </div>
                  <div class="form-row">
                    <label>Số điện thoại</label>
                    <input type="text" name="sdt" class="profile-input" value="<?php echo $sdt_kh; ?>" placeholder="Nhập số điện thoại" />
                  </div>
                  <div class="form-row">
                    <label>Giới tính</label>
                    <div class="radio-group">
                      <label><input type="radio" name="gender" value="Nam" <?php if ($gioitinh_kh == 'Nam') echo 'checked'; ?>> Nam</label>
                      <label><input type="radio" name="gender" value="Nữ" <?php if ($gioitinh_kh == 'Nữ') echo 'checked'; ?>> Nữ</label>
                      <label><input type="radio" name="gender" value="Khác" <?php if ($gioitinh_kh == 'Khác') echo 'checked'; ?>> Khác</label>
                    </div>
                  </div>
                  <div class="form-row">
                    <label>Ngày sinh</label>
                    <div class="date-group">
                      <select name="ngay" class="profile-select">
                        <option>Ngày</option>
                        <?php
                        for ($i = 1; $i <= 31; $i++) {
                          $v = str_pad($i, 2, '0', STR_PAD_LEFT);
                          $sel = ($n_ngay == $v) ? 'selected' : '';
                          echo "<option value='$v' $sel>$v</option>";
                        }
                        ?>
                      </select>
                      <select name="thang" class="profile-select">
                        <option>Tháng</option>
                        <?php
                        for ($i = 1; $i <= 12; $i++) {
                          $v = str_pad($i, 2, '0', STR_PAD_LEFT);
                          $sel = ($n_thang == $v) ? 'selected' : '';
                          echo "<option value='$v' $sel>$v</option>";
                        }
                        ?>
                      </select>
                      <select name="nam" class="profile-select">
                        <option>Năm</option>
                        <?php
                        for ($i = date('Y'); $i >= 1950; $i--) {
                          $sel = ($n_nam == $i) ? 'selected' : '';
                          echo "<option value='$i' $sel>$i</option>";
                        }
                        ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-row">
                    <label></label>
                    <button type="submit" name="btn_save_profile" class="btn-save-profile">Lưu thay đổi</button>
                    <button type="button" class="btn-save-profile" style="background: #fff; color: #555; border: 1px solid #ccc; margin-left: 10px;" onclick="document.getElementById('editProfileForm').style.display='none'; window.scrollTo(0,0);">Hủy</button>
                  </div>
                </div>

                <div class="profile-avatar-upload">
                  <div class="avatar-preview">
                    <img src="<?php echo $avatar_kh; ?>" alt="" id="avatarPreview" />
                  </div>
                  <button type="button" class="btn-select-image" onclick="document.getElementById('avatarInput').click();">Chọn Ảnh</button>
                  <input type="file" name="avatar_upload" id="avatarInput" style="display:none;" accept="image/png, image/jpeg, image/jpg" />
                  <div class="upload-hint">
                    Dụng lượng file tối đa 1 MB<br />
                    Định dạng: .JPEG, .PNG
                  </div>
                </div>
              </div>
            </form>
          </div>
        <?php elseif ($tab == 'address'): ?>
          <div class="profile-content-card">
            <div class="profile-header">
              <h2>Địa Chỉ Của Tôi</h2>
              <p>Quản lý địa chỉ nhận hàng của bạn</p>
            </div>
            <div class="profile-body">
              <form class="profile-form" method="POST" action="profile.php?tab=address" style="max-width: 600px;">
                <?php echo $message; ?>
                <div class="form-row">
                  <label>Địa chỉ hiện tại</label>
                  <div class="form-value" style="font-weight: 500; color: #ff6a00; line-height: 1.5;">
                    <?php echo $diachi_kh ? $diachi_kh : 'Chưa cập nhật địa chỉ'; ?>
                  </div>
                </div>
                <div class="form-row">
                  <label>Tỉnh/Thành phố</label>
                  <input type="text" name="tinhthanh" class="profile-input" placeholder="Ví dụ: TP. Hồ Chí Minh" required />
                </div>
                <div class="form-row">
                  <label>Quận/Huyện</label>
                  <input type="text" name="quanhuyen" class="profile-input" placeholder="Ví dụ: Quận 1" required />
                </div>
                <div class="form-row">
                  <label>Phường/Xã</label>
                  <input type="text" name="phuongxa" class="profile-input" placeholder="Ví dụ: Phường Bến Nghé" required />
                </div>
                <div class="form-row">
                  <label>Địa chỉ cụ thể</label>
                  <textarea name="diachi_chitiet" class="profile-input" rows="3" placeholder="Số nhà, Tên đường, Ấp/Khu phố..." required></textarea>
                </div>
                <div class="form-row">
                  <label></label>
                  <button type="submit" name="btn_save_address" class="btn-save-profile">Lưu địa chỉ mới</button>
                </div>
              </form>
            </div>
          </div>

        <?php elseif ($tab == 'password'): ?>
          <div class="profile-content-card">
            <div class="profile-header">
              <h2>Đổi Mật Khẩu</h2>
              <p>Để bảo mật tài khoản, vui lòng không chia sẻ mật khẩu cho người khác</p>
            </div>
            <div class="profile-body">
              <form class="profile-form" method="POST" action="profile.php?tab=password" style="max-width: 600px;">
                <?php echo $message; ?>
                <div class="form-row">
                  <label>Mật khẩu hiện tại</label>
                  <input type="password" name="old_pass" class="profile-input" required />
                </div>
                <div class="form-row">
                  <label>Mật khẩu mới</label>
                  <input type="password" name="new_pass" class="profile-input" required />
                </div>
                <div class="form-row">
                  <label>Xác nhận mật khẩu</label>
                  <input type="password" name="confirm_pass" class="profile-input" required />
                </div>
                <div class="form-row">
                  <label></label>
                  <button type="submit" name="btn_change_password" class="btn-save-profile">Xác nhận</button>
                </div>
              </form>
            </div>
          </div>
        <?php endif; ?>

      </main>
    </div>
  </div>

  <footer class="footer">
    <div class="footer-newsletter">
      <div class="newsletter-text">
        <h2>Đăng ký để nhận ưu đãi</h2>
        <p>
          Đăng ký nhận bản tin của chúng tôi để nhận các ưu đãi và giảm giá
          độc quyền!
        </p>
      </div>

      <div class="newsletter-form">
        <input type="email" placeholder="Email của bạn..." />
        <button>
          <span class="material-symbols-outlined">mail</span>
        </button>
      </div>
    </div>

    <div class="footer-container">
      <div class="footer-col">
        <h2 class="logo">
          <img
            src="./assets/file_anh/0c4690d7-3599-4de4-a0a4-841817ead1c0.png"
            alt="" />
          UniStyle
        </h2>
        <p>
          Nếu bạn có bất kỳ thắc mắc nào, vui lòng liên hệ với chúng tôi theo
          địa chỉ sau:
          <span>support@example.com</span>
        </p>

        <p>📍 16 Thiên Hộ Vương, P1, Mỹ Tho, Tiền Giang</p>
        <p>📞 (+84) 0777331314</p>

        <div class="social-icons">
          <a href="#"><i class="fa-brands fa-x-twitter"></i></a>
          <a href="#"><i class="fa-brands fa-instagram"></i></a>
          <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
          <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
          <a href="#"><i class="fa-brands fa-pinterest-p"></i></a>
        </div>
      </div>

      <div class="footer-col">
        <h3>Công ty</h3>
        <ul>
          <li><a href="aboutus.php">Tuyển dụng</a></li>
          <li><a href="aboutus.php">Về chúng tôi</a></li>
          <li><a href="aboutus.php">Quy tắc kinh doanh</a></li>
          <li><a href="aboutus.php">Hợp tác sự kiện</a></li>
          <li><a href="aboutus.php">Nhà cung cấp</a></li>
          <li><a href="aboutus.php">Chương trình cộng tác viên</a></li>
        </ul>
      </div>

      <div class="footer-col">
        <h3>Chăm sóc khách hàng</h3>
        <ul>
          <li><a href="contact.php">Theo dõi đơn hàng</a></li>
          <li><a href="FAQ.php">Đổi / Trả hàng</a></li>
          <li><a href="FAQ.php">Thông tin vận chuyển</a></li>
          <li><a href="FAQ.php">Chính sách bảo hành</a></li>
          <li><a href="FAQ.php">Hệ thống cửa hàng</a></li>
          <li><a href="contact.php">Liên hệ</a></li>
        </ul>
      </div>

      <div class="footer-col">
        <h3>Dịch vụ</h3>
        <ul>
          <li><a href="contact.php">In ấn - Photo</a></li>
          <li><a href="shop.php">Đóng gáy tài liệu</a></li>
          <li><a href="shop.php">Laminating (Ép nhựa)</a></li>
          <li><a href="shop.php">Cung cấp sỉ văn phòng phẩm</a></li>
          <li><a href="shop.php">Đặt hàng theo yêu cầu</a></li>
          <li><a href="contact.php">Trung tâm hỗ trợ</a></li>
        </ul>
      </div>
    </div>
  </footer>

  <button id="backToTop">
    <span class="material-symbols-outlined"> keyboard_arrow_up </span>
  </button>
  <script>
    const btn = document.getElementById("backToTop");
    window.onscroll = function() {
      if (document.documentElement.scrollTop > 200) {
        btn.style.display = "block";
      } else {
        btn.style.display = "none";
      }
    };
    btn.onclick = function() {
      window.scrollTo({
        top: 0,
        behavior: "smooth",
      });
    };

    const avatarInput = document.getElementById('avatarInput');
    if (avatarInput) {
      avatarInput.addEventListener('change', function(e) {
        if (e.target.files && e.target.files[0]) {
          var reader = new FileReader();
          reader.onload = function(e) {
            document.getElementById('avatarPreview').src = e.target.result;
          }
          reader.readAsDataURL(e.target.files[0]);
        }
      });
    }
  </script>
</body>

</html>