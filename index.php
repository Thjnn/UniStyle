<?php
include("./config/db.php"); // Đường dẫn phải chính xác đến file bạn vừa nêu


$sql = "SELECT * FROM danhmuc LIMIT 6";
$result = mysqli_query($conn, $sql);


$sql_sp = "SELECT * FROM sanpham WHERE NoiBat = 1 LIMIT 8";
$result_sp = mysqli_query($conn, $sql_sp);
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>UniStyle</title>
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
          <a href="index.html"><img
              src="./assets/file_anh/0c4690d7-3599-4de4-a0a4-841817ead1c0.png"
              alt="" /></a>
        </div>
        <nav>
          <ul>
            <li><a href="index.html">Trang chủ</a></li>

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
            <li><a href="contact.html">Liên hệ</a></li>
            <li><a href="FAQ.html">FAQ</a></li>
            <li><a href="aboutus.html">Về chúng tôi</a></li>
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
          <a href="package.html"><span class="material-symbols-outlined"> local_mall </span></a>
          <a href="login.html"><span class="material-symbols-outlined"> person </span></a>
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
  <!-- main-content -->
  <div class="container" style="max-width: 1400px">
    <!-- Hero Section -->
    <section class="hero">
      <div class="hero-content">
        <h1>
          Nổi bậc cùng với <br />Balo <span class="fresh">CAMELIA BRAND®</span><br />
        </h1>
        <button class="shop-now-btn">Mua Ngay →</button>
      </div>
      <div class="hero-image">
        <img
          src="./assets/file_anh/balo-U_revo-Energy-College-Leisure-h1-removebg-preview.png"
          alt="" />
      </div>
    </section>
    <!-- Featured Categories -->
    <section class="section">
      <div class="section-header">
        <h2 class="section-title">
          <span class="featured">Danh Mục</span> Nổi Bật
        </h2>
        <a href="#!" class="view-all">Xem thêm danh mục →</a>
      </div>

      <div class="categories-grid">

        <?php while ($row = mysqli_fetch_assoc($result)) { ?>

          <div class="category-card">
            <div class="category-icon">
              <img src="./assets/file_anh/San_Pham/<?= $row['hinhanh'] ?>" alt="" />
            </div>
            <div class="category-name">
              <?= $row['tendanhmuc'] ?>
            </div>
          </div>

        <?php } ?>

      </div>
    </section>
    <!-- Promo Cards Section -->
    <section class="promo-section">
      <div class="promo-grid">
        <!-- Promo Card 1 -->
        <div class="promo-card promo-card-1">
          <div class="promo-card-content">
            <h3 class="promo-card-subtitle">Ưu Đãi Đặc Biệt</h3>
            <h2 class="promo-card-title">Chuẩn Bị Cho<br />Năm Học Mới</h2>
            <p class="promo-card-description">
              Sắm sửa đầy đủ đồ dùng học tập chất lượng cao
            </p>
            <button class="promo-card-btn">Khám Phá →</button>
          </div>
          <div class="promo-card-image">
            <img
              src="./assets/file_anh/832c1d98ef4f72a3518069cb762b449f-removebg-preview.png"
              alt="" />
          </div>
        </div>

        <!-- Promo Card 2 -->
        <div class="promo-card promo-card-2">
          <div class="promo-card-content">
            <h3 class="promo-card-subtitle">Bộ Sưu Tập Mới</h3>
            <h2 class="promo-card-title">Phong Cách<br />Học Đường</h2>
            <p class="promo-card-description">
              Balo & phụ kiện thời trang cho teen
            </p>
            <button class="promo-card-btn">Mua Ngay →</button>
          </div>
          <div class="promo-card-image">
            <img
              src="./assets/file_anh/phu-kien-thoi-trang-12-removebg-preview.png"
              alt="" />
          </div>
        </div>
      </div>
    </section>
    <!-- Featured Products -->
    <section class="section">
      <div class="section-header">
        <h2 class="section-title">
          <span class="featured">Sản Phẩm</span> Nổi Bật
        </h2>
      </div>

      <div class="products-grid">
        <?php while ($row_sp = mysqli_fetch_assoc($result_sp)) { ?>
          <div class="product-card">
            <div class="product-img">
              <img src="./assets/file_anh/San_Pham/<?= $row_sp['Hinh'] ?>" alt="<?= $row_sp['TenSP'] ?>" />
            </div>

            <div class="product-info">
              <div class="product-tag">
                <span class="new">👍 New</span>
                <span class="sold">📊 Đã bán <?= $row_sp['SoLuongDaBan'] ?></span>
              </div>

              <h3 class="product-name">
                <a href="product_detail.php?id=<?= $row_sp['MaSP'] ?>" style="text-decoration:none; color:inherit;">
                  <?= $row_sp['TenSP'] ?>
                </a>
              </h3>

              <div class="rating">
                <?php
                // Kiểm tra nếu biến $sp tồn tại và có giá trị Rating, nếu không mặc định là 5
                $rating = isset($sp['Rating']) ? (int)$sp['Rating'] : 5;
                $luot_dg = isset($sp['SoLuotDanhGia']) ? $sp['SoLuotDanhGia'] : 0;

                // Hiển thị sao vàng
                for ($i = 1; $i <= $rating; $i++) {
                  echo '<span style="color: #ffc107;">★</span>';
                }
                // Hiển thị sao trống cho đủ 5 sao
                for ($i = 1; $i <= (5 - $rating); $i++) {
                  echo '<span style="color: #ccc;">★</span>';
                }
                ?>
                <span style="font-size: 0.8rem; color: #666;">(<?= $luot_dg ?>)</span>
              </div>

              <div class="price"><?= number_format($row_sp['GiaBan'], 0, ',', '.') ?>đ</div>

              <?php if (!empty($row_sp['MaKhuyenMai'])): ?>
                <div class="old-price">
                  <span class="discount">Đang có ưu đãi</span>
                </div>
              <?php endif; ?>
            </div>
          </div>
        <?php } ?>
      </div>

      <!-- Button -->
      <div class="see-more">
        <button class="see-more-btn">Xem thêm sản phẩm</button>
      </div>
    </section>
    <!-- Back To School Banner -->
    <section class="backtoschool-banner">
      <a href="#!">
        <img
          src="./assets/file_anh/1920_x_600___cta___6_.webp"
          alt="Back To School Sale" />
      </a>
    </section>
    <!-- Policy -->
    <section class="store-policy">
      <div class="policy-header">
        <h2><span>Những gì khách hàng cần,</span> khi họ cần.</h2>
      </div>

      <div class="policy-grid">
        <!-- Policy 1 -->
        <div class="policy-item">
          <div class="policy-icon">
            <span class="material-symbols-outlined"> local_shipping </span>
          </div>
          <h3>Giao hàng nhanh</h3>
          <p>
            Giao hàng trong ngày cho đơn hàng trên 200.000đ tại nội thành.
          </p>
        </div>

        <!-- Policy 2 -->
        <div class="policy-item">
          <div class="policy-icon">
            <span class="material-symbols-outlined"> assignment_return </span>
          </div>
          <h3>Đổi trả 30 ngày</h3>
          <p>Hỗ trợ đổi trả trong vòng 30 ngày nếu sản phẩm lỗi.</p>
        </div>

        <!-- Policy 3 -->
        <div class="policy-item">
          <div class="policy-icon">
            <span class="material-symbols-outlined"> security </span>
          </div>
          <h3>Thanh toán an toàn</h3>
          <p>Hỗ trợ nhiều phương thức thanh toán an toàn và tiện lợi.</p>
        </div>

        <!-- Policy 4 -->
        <div class="policy-item">
          <div class="policy-icon">
            <span class="material-symbols-outlined"> support_agent </span>
          </div>
          <h3>Hỗ trợ 24/7</h3>
          <p>Đội ngũ hỗ trợ khách hàng luôn sẵn sàng giúp đỡ bạn.</p>
        </div>
      </div>
    </section>
    <!-- Banner Charity -->
    <section class="charity-banner">
      <a href="#!">
        <img
          src="./assets/file_anh/8wthty42wz8modg-784-he-thong-tu-thien-fly-to-sky-cong-ty-tnhh-doanh-nghiep-xa-hoi-tu-thien-va-ho-tro-phat-trien-cong-dong-fly-to-sky.png"
          alt="Back To School Sale" />
      </a>
    </section>
    <!-- Featured Products 2 -->
    <section class="section">
      <div class="section-header">
        <h2 class="section-title">
          <span class="featured">Thời trang</span> với UniStyle
        </h2>
      </div>

      <div class="products-grid">
        <?php
        // 1. Truy vấn sản phẩm thuộc danh mục Giày (6) và Balo (5)
        // Lấy 8 sản phẩm mới nhất (sắp xếp theo MaSP giảm dần)
        $sql_fashion = "SELECT * FROM sanpham WHERE madanhmuc IN (5, 6) ORDER BY MaSP DESC LIMIT 8";
        $result_fashion = mysqli_query($conn, $sql_fashion);

        // 2. Kiểm tra nếu có sản phẩm dữ liệu
        if (mysqli_num_rows($result_fashion) > 0) {
          while ($sp = mysqli_fetch_assoc($result_fashion)) {
            // Chuẩn bị dữ liệu hiển thị (Rating, Lượt đánh giá, v.v.)
            $rating = isset($sp['Rating']) ? (int)$sp['Rating'] : 5;
            $luot_dg = isset($sp['SoLuotDanhGia']) ? $sp['SoLuotDanhGia'] : 0;
        ?>
            <div class="product-card">
              <div class="product-img">
                <img src="./assets/file_anh/San_Pham/<?= $sp['Hinh'] ?>" alt="<?= $sp['TenSP'] ?>" />
              </div>

              <div class="product-info">
                <div class="product-tag">
                  <span class="new">👍 New</span>
                  <span class="sold">📊 Đã bán <?= number_format($sp['SoLuongDaBan']) ?></span>
                </div>

                <h3 class="product-name">
                  <a href="detail.php?id=<?= $sp['MaSP'] ?>" style="text-decoration: none; color: inherit;">
                    <?= $sp['TenSP'] ?>
                  </a>
                </h3>

                <div class="rating">
                  <?php
                  for ($i = 1; $i <= 5; $i++) {
                    // Hiển thị sao vàng cho đến khi đạt số sao rating, sau đó hiển thị sao xám
                    echo ($i <= $rating) ? '<span style="color: #ffc107;">★</span>' : '<span style="color: #ccc;">★</span>';
                  }
                  ?>
                  <span>(<?= $luot_dg ?>)</span>
                </div>

                <div class="price"><?= number_format($sp['GiaBan'], 0, ',', '.') ?>đ</div>

                <?php if (isset($sp['GiaCu']) && $sp['GiaCu'] > $sp['GiaBan']): ?>
                  <div class="old-price">
                    <?= number_format($sp['GiaCu'], 0, ',', '.') ?>đ
                    <span class="discount">-<?= round((($sp['GiaCu'] - $sp['GiaBan']) / $sp['GiaCu']) * 100) ?>%</span>
                  </div>
                <?php endif; ?>
              </div>
            </div>
        <?php
          }
        } else {
          // Thông báo nếu không có sản phẩm nào thuộc Giày hoặc Balo
          echo "<p style='grid-column: 1/-1; text-align: center;'>Hiện chưa có sản phẩm nào trong danh mục Giày hoặc Balo.</p>";
        }
        ?>
      </div>

      <div class="see-more">
        <button class="see-more-btn">Xem thêm sản phẩm</button>
      </div>
    </section>
    <!-- News -->
    <section class="news-section">
      <div class="news-container">
        <!-- Bài viết lớn -->
        <div class="news-feature">
          <a href=""><img src="./assets/file_anh/businesswoman-planning-work_a8bb09d0b76c4c7e972d46e4c2500c4f.png" alt="" /></a>

          <div class="news-feature-content">
            <h2>Top 5 dòng bút văn phòng ngòi to viết đẹp</h2>
            <span class="date">Thứ Tư 04/03/2026</span>
            <p>
              Trong môi trường văn phòng, một cây bút có ngòi to, mực ra đều
              sẽ giúp tốc độ...
              <a href="#">Đọc tiếp</a>
            </p>
          </div>
        </div>

        <!-- Danh sách tin -->
        <div class="news-list">
          <div class="news-item">
            <a href=""><img src="./assets/file_anh/family-reunion-during-chinese-new-year-2026-01-05-05-45-10-utc_2ef835bd26794c7f9ea833af616f3784.png" alt="" /></a>
            <div class="news-text">
              <h4>Chọn màu hợp mệnh trong năm Bính Ngọ 2026</h4>
              <span>Thứ Sáu 13/02/2026</span>
              <p>
                Trong dòng chảy văn hoá Á Đông... <a href="#">Đọc tiếp</a>
              </p>
            </div>
          </div>

          <div class="news-item">
            <a href=""><img src="./assets/file_anh/loseup-of-valentine-39-s-day-calendar-reminder-2025-02-09-22-57-20-utc_5ad22c8529364b45bf6f871166be1b3a.png" alt="" /></a>
            <div class="news-text">
              <h4>Chọn quà Valentine như thế nào để duy trì mối quan hệ</h4>
              <span>Thứ Sáu 06/02/2026</span>
              <p>
                Món quà Valentine nào cũng đẹp... <a href="#">Đọc tiếp</a>
              </p>
            </div>
          </div>

          <div class="news-item">
            <a href=""><img src="./assets/file_anh/nhung_dieu_co_the_ban_chua_biet_ve_tet_binh_ngo_2026_2122e7c1466a4fe5858003daea41ae09.jpg" alt="" /></a>
            <div class="news-text">
              <h4>Những điều có thể bạn chưa biết về Tết Bính Ngọ 2026</h4>
              <span>Thứ Hai 05/01/2026</span>
              <p>
                Tết Nguyên Đán là không chỉ là khoảnh khắc...
                <a href="#">Đọc tiếp</a>
              </p>
            </div>
          </div>

          <div class="news-item">
            <a href=""><img src="./assets/file_anh/cute-little-kids-studying-in-a-group-2024-10-18-03-40-30-utc_9f0d248b36de4523b673935db27492db.png" alt="" /></a>
            <div class="news-text">
              <h4>Top 5 dòng bút Quick Dry/Super Quick Dry dành cho bé</h4>
              <span>Thứ Sáu 02/01/2026</span>
              <p>
                Viết tay là một hành trình rèn luyện...
                <a href="#">Đọc tiếp</a>
              </p>
            </div>
          </div>
          <div class="news-item">
            <a href=""><img src="./assets/file_anh/cute-little-kids-studying-in-a-group-2024-10-18-03-40-30-utc_9f0d248b36de4523b673935db27492db.png" alt="" /></a>
            <div class="news-text">
              <h4>Top 5 dòng bút Quick Dry/Super Quick Dry dành cho bé</h4>
              <span>Thứ Sáu 02/01/2026</span>
              <p>
                Viết tay là một hành trình rèn luyện...
                <a href="#">Đọc tiếp</a>
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

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
</body>

</html>