<?php
session_start();
include("./config/db.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

$totalQty = 0;
if (!empty($_SESSION['cart'])) {
  foreach ($_SESSION['cart'] as $item) {
    $totalQty += $item['soluong'];
  }
}

// XỬ LÝ GỬI MAIL
if (isset($_POST['send'])) {

  $name = $_POST['name'];
  $email = $_POST['email'];
  $phone = $_POST['phone'];
  $message = $_POST['message'];

  $mail = new PHPMailer(true);

  try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'nguyenvantruc2607@gmail.com';
    $mail->Password   = 'ynwu rkwx ypvi aeax';
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    $mail->setFrom('nguyenvantruc2607@gmail.com', 'UniStyle');
    $mail->addAddress('nguyenvantruc2607@gmail.com');

    $mail->isHTML(true);
    $mail->Subject = 'Liên hệ từ website';

    $mail->Body = "
      <h3>Thông tin liên hệ</h3>
      <p><b>Họ tên:</b> $name</p>
      <p><b>Email:</b> $email</p>
      <p><b>SĐT:</b> $phone</p>
      <p><b>Nội dung:</b> $message</p>
    ";

    $mail->send();
    $success = true;
  } catch (Exception $e) {
    $error = "Gửi thất bại!";
  }
}
?>

<!doctype html>
<html lang="vi">

<head>
  <meta charset="UTF-8">
  <title>Liên hệ</title>

  <link rel="stylesheet" href="./assets/css/style.css">
  <link rel="stylesheet" href="./assets/css/contact.css">

  <!-- Font + icon -->
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

  <!-- Responsive -->
  <link rel="stylesheet" href="./assets/css/reposive.css" />

  <!-- 🔥 ALERT STYLE -->
  <style>
    .alert-popup {
      position: fixed;
      top: 20px;
      right: 20px;
      background: #4CAF50;
      color: white;
      padding: 20px 25px;
      border-radius: 10px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
      z-index: 9999;
      animation: slideIn 0.5s ease;
    }

    .alert-popup.error {
      background: #e74c3c;
    }

    @keyframes slideIn {
      from {
        transform: translateX(100%);
        opacity: 0;
      }

      to {
        transform: translateX(0);
        opacity: 1;
      }
    }
  </style>
</head>

<body>

  <!-- 🔥 ALERT -->
  <?php if (isset($success)): ?>
    <div class="alert-popup">
      Cảm ơn bạn đã liên hệ!<br>
      Chúng tôi sẽ phản hồi sớm nhất.
    </div>
  <?php endif; ?>

  <?php if (isset($error)): ?>
    <div class="alert-popup error">
      Gửi thất bại!
    </div>
  <?php endif; ?>

  <header>
    <div class="container">
      <div class="header-content">
        <div class="menu-toggle">
          <span class="material-symbols-outlined">menu</span>
        </div>
        <div class="logo">
          <a href="index.php"><img src="./assets/file_anh/0c4690d7-3599-4de4-a0a4-841817ead1c0.png"
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
              <input type="text" name="keyword" placeholder="Tìm sản phẩm..." />
            </form>
          </div>
          <div class="cart-icon">
            <a href="package.php">
              <span class="material-symbols-outlined">local_mall</span>

              <?php if ($totalQty > 0): ?>
                <span class="cart-count"><?= $totalQty ?></span>
              <?php endif; ?>
            </a>
          </div>
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



  <section class="contact-section">
    <div class="container contact-wrapper">


      <!-- FORM -->
      <div class="contact-left">
        <h2>UniStyle</h2>

        <div class="contact-info">
          <p>
            <i class="fa-solid fa-location-dot"></i>
            Địa chỉ: 16 Thiên Hộ Vương, P1, Mỹ Tho, Tiền Giang
          </p>

          <p>
            <i class="fa-solid fa-phone"></i>
            Số điện thoại: (+84) 0777331314
          </p>

          <p>
            <i class="fa-solid fa-envelope"></i>
            Email: support@example.
          </p>
        </div>

        <hr />

        <h3>LIÊN HỆ VỚI CHÚNG TÔI</h3>
        <form method="POST" class="contact-form" id="contactForm">
          <input type="text" name="name" placeholder="Họ tên*" required>
          <input type="email" name="email" placeholder="Email*" required>
          <input type="text" name="phone" placeholder="SĐT*" required>
          <textarea name="message" placeholder="Nội dung*" required></textarea>
          <button type="submit" name="send">Gửi liên hệ</button>
        </form>
      </div>

      <!-- MAP -->
      <div class="contact-right">
        <iframe
          src="https://www.google.com/maps?q=16 Thiên Hộ Vương, Mỹ Tho&output=embed"
          width="100%"
          height="500"
          style="border:0; border-radius:10px;"
          loading="lazy">
        </iframe>
      </div>

    </div>
  </section>

  <!-- 🔥 AUTO HIDE + RESET FORM -->
  <script>
    setTimeout(() => {
      const alert = document.querySelector('.alert-popup');
      if (alert) alert.style.display = 'none';
    }, 4000);

    // reset form sau khi gửi
    <?php if (isset($success)): ?>
      document.getElementById("contactForm").reset();
    <?php endif; ?>
  </script>
  <footer class="footer">
    <!-- Newsletter -->
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

    <!-- Footer content -->
    <div class="footer-container">
      <!-- Logo + contact -->
      <div class="footer-col">
        <h2 class="logo">
          <img src="./assets/file_anh/0c4690d7-3599-4de4-a0a4-841817ead1c0.png" alt="" />
          UniStyle
        </h2>
        <p>
          Nếu bạn có bất kỳ thắc mắc nào, vui lòng liên hệ với chúng tôi theo
          địa chỉ sau:
          <span>support@example.</span>
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

      <!-- Công ty -->
      <div class="footer-col">
        <h3>Công ty</h3>
        <ul>
          <li><a href="#">Tuyển dụng</a></li>
          <li><a href="#">Về chúng tôi</a></li>
          <li><a href="#">Quy tắc kinh doanh</a></li>
          <li><a href="#">Hợp tác sự kiện</a></li>
          <li><a href="#">Nhà cung cấp</a></li>
          <li><a href="#">Chương trình cộng tác viên</a></li>
        </ul>
      </div>

      <!-- Chăm sóc khách hàng -->
      <div class="footer-col">
        <h3>Chăm sóc khách hàng</h3>
        <ul>
          <li><a href="#">Theo dõi đơn hàng</a></li>
          <li><a href="#">Đổi / Trả hàng</a></li>
          <li><a href="#">Thông tin vận chuyển</a></li>
          <li><a href="#">Chính sách bảo hành</a></li>
          <li><a href="#">Hệ thống cửa hàng</a></li>
          <li><a href="#">Liên hệ</a></li>
        </ul>
      </div>

      <!-- Dịch vụ -->
      <div class="footer-col">
        <h3>Dịch vụ</h3>
        <ul>
          <li><a href="#">In ấn - Photo</a></li>
          <li><a href="#">Đóng gáy tài liệu</a></li>
          <li><a href="#">Laminating (Ép nhựa)</a></li>
          <li><a href="#">Cung cấp sỉ văn phòng phẩm</a></li>
          <li><a href="#">Đặt hàng theo yêu cầu</a></li>
          <li><a href="#">Trung tâm hỗ trợ</a></li>
        </ul>
      </div>
    </div>
  </footer>
</body>

</html>