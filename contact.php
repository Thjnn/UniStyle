<?php
include("session.php");

include("./config/db.php");
$totalQty = 0;
if (!empty($_SESSION['cart'])) {
  foreach ($_SESSION['cart'] as $item) {
    $totalQty += $item['soluong'];
  }
}
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Contact</title>
  <!-- embed link icon  -->
  <link
    rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,0..1" />
  <!--embed Font Awesome -->
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <!-- logo web -->
  <link
    rel="shortcut icon"
    href="assets/file_anh/0c4690d7-3599-4de4-a0a4-841817ead1c0.png" />
  <!-- css -->
  <link rel="stylesheet" href="./assets/css/contact.css" />
  <link rel="stylesheet" href="./assets/css/style.css" />

  <!-- repo -->
  <link rel="stylesheet" href="./assets/css/reposive.css" />
  <!-- script -->
  <!-- <script src="script.js"></script> -->
</head>

<body>
  <!-- Header -->
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

            <li class="has-submenu">
              <a href="shop.php">Cửa hàng</a>

              <div class="submenu">
                <!-- LEFT: danh mục -->
                <div class="submenu-left">
                  <div class="submenu-column">
                    <h4>Bút viết</h4>
                    <a href="#">Bút bi</a>
                    <a href="#">Bút màu</a>
                    <a href="#">Bút dạ quang</a>
                  </div>

                  <div class="submenu-column">
                    <h4>Văn phòng phẩm</h4>
                    <a href="#">Sổ</a>
                    <a href="#">Bìa hồ sơ</a>
                    <a href="#">Dập ghim</a>
                    <a href="#">Băng keo</a>
                  </div>

                  <div class="submenu-column">
                    <h4>Dụng cụ học tập</h4>
                    <a href="#">Thước</a>
                    <a href="#">Máy tính</a>
                    <a href="#">Dao rọc giấy</a>
                  </div>
                </div>

                <!-- RIGHT: banner -->
                <div class="submenu-banner">
                  <a href="#!">
                    <img
                      src="./assets/file_anh/1920_x_600___cta___6_.webp"
                      alt="Back To School Sale" />
                  </a>
                </div>
              </div>
            </li>
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

  <!-- Mobile Menu Overlay -->
  <div class="mobile-menu-overlay" id="mobileMenuOverlay">
    <div class="mobile-menu-content">
      <div class="mobile-menu-header">
        <div class="logo">
          <img
            src="./assets/file_anh/0c4690d7-3599-4de4-a0a4-841817ead1c0.png"
            alt="" />
        </div>
        <div class="menu-close" id="menuClose">
          <span class="material-symbols-outlined">close</span>
        </div>
      </div>

      <!-- MENU CHỮ (ĐỂ Ở ĐÂY) -->
      <nav class="mobile-nav">
        <ul>
          <li><a href="index.html" class="mobile-menu-link">Trang chủ</a></li>
          <li><a href="shop.html" class="mobile-menu-link">Cửa hàng</a></li>
          <li><a href="contact.html" class="mobile-menu-link">Liên hệ</a></li>
          <li><a href="FAQ.html" class="mobile-menu-link">FAQ</a></li>
          <li>
            <a href="aboutus.html" class="mobile-menu-link">Về chúng tôi</a>
          </li>
        </ul>
      </nav>
    </div>
  </div>
  <!-- script menu mobile -->
  <script>
    const menuToggle = document.querySelector(".menu-toggle");
    const mobileMenu = document.querySelector(".mobile-menu-overlay");
    const menuClose = document.querySelector(".menu-close");

    // Mở menu
    menuToggle.addEventListener("click", () => {
      mobileMenu.classList.add("active");
      document.body.style.overflow = "hidden"; // khóa scroll
    });

    // Đóng menu bằng nút X
    menuClose.addEventListener("click", () => {
      mobileMenu.classList.remove("active");
      document.body.style.overflow = "";
    });

    // Click ra ngoài overlay để đóng
    mobileMenu.addEventListener("click", (e) => {
      if (e.target === mobileMenu) {
        mobileMenu.classList.remove("active");
        document.body.style.overflow = "";
      }
    });

    // Click link thì tự đóng menu
    document.querySelectorAll(".mobile-nav a").forEach((link) => {
      link.addEventListener("click", () => {
        mobileMenu.classList.remove("active");
        document.body.style.overflow = "";
      });
    });
    // Thanh tìm kiếm
    const searchBox = document.querySelector(".search-box");
    const searchIcon = document.querySelector(".search-icon");
    searchIcon.addEventListener("click", () => {
      searchBox.classList.toggle("active");
    });
  </script>
  <!-- main -->
  <section class="contact-section">
    <div class="container contact-wrapper">
      <!-- LEFT -->
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

        <form class="contact-form">
          <input type="text" placeholder="Họ tên*" required />

          <input type="email" placeholder="Email*" required />

          <input type="text" placeholder="Số điện thoại*" required />

          <textarea placeholder="Nhập nội dung*" rows="6"></textarea>

          <button type="submit">Gửi liên hệ của bạn</button>
        </form>
      </div>

      <!-- RIGHT -->
      <div class="contact-right">
        <iframe
          src="https://www.google.com/maps?q=16 Thiên Hộ Vương, Mỹ Tho&output=embed"
          width="100%"
          height="100%"
          style="border: 0"
          loading="lazy">
        </iframe>
      </div>
    </div>
  </section>
  <!-- Back to Top -->
  <button id="backToTop">
    <span class="material-symbols-outlined"> keyboard_arrow_up </span>
  </button>
  <script>
    const btn = document.getElementById("backToTop");

    // Hiện nút khi scroll xuống
    window.onscroll = function() {
      if (document.documentElement.scrollTop > 200) {
        btn.style.display = "block";
      } else {
        btn.style.display = "none";
      }
    };

    // Click để lên đầu trang
    btn.onclick = function() {
      window.scrollTo({
        top: 0,
        behavior: "smooth",
      });
    };
  </script>
  <!-- Footer -->
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
          <img
            src="./assets/file_anh/0c4690d7-3599-4de4-a0a4-841817ead1c0.png"
            alt="" />
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