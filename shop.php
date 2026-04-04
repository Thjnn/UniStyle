<?php
require_once "./config/db.php";
?>



<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Shop UniStyle</title>
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
  <link rel="stylesheet" href="./assets/css/shop.css" />
  <!-- repo -->
  <link rel="stylesheet" href="./assets/css/reposive.css" />
  <!-- script -->
  <script src="script.js"></script>
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
            <li><a href="index.php">Trang chủ</a></li>
            <li><a href="shop.php">Cửa hàng</a></li>
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
          <li><a href="index.php" class="mobile-menu-link">Trang chủ</a></li>
          <li><a href="shop.php" class="mobile-menu-link">Cửa hàng</a></li>
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
  <!-- main content -->
  <section class="shop-page">
    <!-- Featured Categories -->
    <section class="section">
      <div class="categories-grid" id="categoriesGrid">
        <?php
        // 1. Truy vấn lấy tất cả danh mục từ bảng danhmuc
        $sql_dm = "SELECT * FROM danhmuc ORDER BY madanhmuc ASC";
        $result_dm = mysqli_query($conn, $sql_dm);
        $count = 0;

        if (mysqli_num_rows($result_dm) > 0) {
          while ($dm = mysqli_fetch_assoc($result_dm)) {
            $count++;
            // Các danh mục sau mục thứ 8 sẽ được ẩn đi bằng class 'hidden-category'
            $hidden_class = ($count > 8) ? 'hidden-category' : '';

            $id_dm = $dm['madanhmuc'];
            $ten_dm = $dm['tendanhmuc'];
            $file_anh = $dm['hinhanh'];
        ?>
            <div class="category-card <?= $hidden_class ?>">
              <a href="shop.php?danhmuc=<?= $id_dm ?>" style="text-decoration: none; color: inherit;">
                <div class="category-icon">
                  <img src="./assets/file_anh/San_Pham/<?= $file_anh ?>" alt="<?= $ten_dm ?>" />
                </div>
                <div class="category-name"><?= $ten_dm ?></div>
              </a>
            </div>
        <?php
          }
        }
        ?>
      </div>

      <?php if (mysqli_num_rows($result_dm) > 8): ?>
        <div class="see-more-categories">
          <button id="toggleCategoriesBtn" onclick="toggleCategories()">
            Xem thêm <i class="fa-solid fa-chevron-down"></i>
          </button>
        </div>
      <?php endif; ?>
    </section>
    <div class="shop-container">
      <!-- Sidebar -->
      <aside class="shop-sidebar">
        <div class="filter-group">
          <h3>LOẠI SẢN PHẨM</h3>

          <label><input type="checkbox" /> Combo giấy</label>
          <label><input type="checkbox" /> Giấy in - giấy photo</label>
        </div>

        <div class="filter-group">
          <h3>THƯƠNG HIỆU</h3>

          <label><input type="checkbox" /> IK Signature</label>
          <label><input type="checkbox" /> IK Yellow</label>
          <label><input type="checkbox" /> Plus</label>
          <label><input type="checkbox" /> IK Plus</label>

          <span class="more">Xem thêm ▾</span>
        </div>

        <div class="filter-group">
          <h3>MỨC GIÁ</h3>

          <label><input type="radio" /> Giá dưới 100.000đ</label>
          <label><input type="radio" /> 100.000đ - 300.000đ</label>
          <label><input type="radio" /> 300.000đ - 500.000đ</label>
        </div>
      </aside>

      <!-- Content -->
      <div class="shop-content">
        <!-- Banner -->
        <div class="shop-banner">
          <img
            src="./assets/file_anh/1920_x_600___cta__1_d652d361086646d3b12a89b38ce6c294.jpg" />
        </div>

        <!-- Title + Sort -->
        <div class="shop-header">
          <h2>Cửa hàng</h2>

          <div class="shop-actions">
            <select class="sort-select">
              <option>Tên A → Z</option>
              <option>Tên Z → A</option>
              <option selected>Giá tăng dần</option>
              <option>Giá giảm dần</option>
              <option>Hàng mới</option>
            </select>

            <button class="filter-btn" onclick="openFilter()">
              <i class="fa-solid fa-filter"></i> Lọc
            </button>
          </div>
        </div>

        <!-- Product grid -->
        <div class="product-grid">
          <?php
          // 1. Truy vấn lấy sản phẩm ngẫu nhiên dựa trên tên cột thực tế trong DB của bạn
          $sql_product = "SELECT * FROM sanpham ORDER BY RAND() LIMIT 100";
          $result_product = mysqli_query($conn, $sql_product);

          if ($result_product && mysqli_num_rows($result_product) > 0) {
            // Sử dụng $sp làm biến đại diện để khớp với các đoạn code bên dưới
            while ($sp = mysqli_fetch_assoc($result_product)) {

              // Lấy dữ liệu từ Database (Sửa đúng theo tên cột bạn gửi trong ảnh)
              $ten_sp   = $sp['TenSP'];
              $gia_ban  = $sp['GiaBan'];
              $hinh_anh = $sp['Hinh'];
              $da_ban   = $sp['SoLuongDaBan'];
              $rating   = $sp['Rating'];
              $is_new   = $sp['NoiBat']; // Giả định NoiBat = 1 là hàng mới/hot
          ?>
              <div class="product-card">
                <div class="product-img">
                  <img src="./assets/file_anh/San_Pham/<?= !empty($hinh_anh) ? $hinh_anh : 'default.png' ?>" alt="<?= $ten_sp ?>" />
                </div>

                <div class="product-info">
                  <div class="product-tag">
                    <?php if ($is_new == 1): ?>
                      <span class="new">👍 Hot</span>
                    <?php endif; ?>
                    <span class="sold">📊 Đã bán <?= $da_ban ?></span>
                  </div>

                  <h3 class="product-name">
                    <?= $ten_sp ?>
                  </h3>

                  <div class="rating">
                    <?php
                    // Hiển thị tối đa 5 sao
                    for ($i = 1; $i <= 5; $i++) {
                      if ($i <= $rating) {
                        echo '<i class="fa-solid fa-star" style="color: #ffc107;"></i>'; // Sao vàng
                      } else {
                        echo '<i class="fa-regular fa-star" style="color: #ccc;"></i>'; // Sao trống
                      }
                    }
                    ?>
                    <span>(<?= $rating ?>)</span>
                  </div>

                  <div class="price"><?= number_format($gia_ban, 0, ',', '.') ?>đ</div>
                </div>
              </div>
          <?php
            }
          } else {
            echo "<p>Đang cập nhật sản phẩm...</p>";
          }
          ?>

        </div>
        <button id="loadMoreBtn">Xem thêm</button>
        <script>
          const items = document.querySelectorAll(".product-card");
          const loadMoreBtn = document.getElementById("loadMoreBtn");

          let visible = 8;

          function showItems() {
            items.forEach((item, index) => {
              item.style.display = index < visible ? "block" : "none";
            });

            if (visible >= items.length) {
              btn.style.display = "none";
            }
          }

          loadMoreBtn.addEventListener("click", () => {
            visible += 8;
            showItems();
          });

          showItems();
        </script>
      </div>
    </div>
    <!-- HTML Filter Overlay (mobile) -->
    <div class="filter-overlay" id="filterOverlay">
      <div class="filter-panel">
        <div class="filter-header">
          <h3>Bộ lọc</h3>
          <span onclick="closeFilter()">✕</span>
        </div>

        <!-- copy toàn bộ filter sidebar vào đây -->

        <div class="filter-group">
          <h3>LOẠI SẢN PHẨM</h3>

          <label><input type="checkbox" /> Bảng học sinh</label>
          <label><input type="checkbox" /> Kéo học sinh</label>
          <label><input type="checkbox" /> Gôm</label>
          <label><input type="checkbox" /> Bìa bao tập</label>
        </div>

        <div class="filter-group">
          <h3>THƯƠNG HIỆU</h3>

          <label><input type="checkbox" /> Bizner</label>
          <label><input type="checkbox" /> Flexoffice</label>
          <label><input type="checkbox" /> Colokit</label>
        </div>
      </div>
    </div>
  </section>
  <!-- Back to Top -->
  <button id="backToTop">
    <span class="material-symbols-outlined"> keyboard_arrow_up </span>
  </button>
  <script>
    const backToTopBtn = document.getElementById("backToTop");

    // Hiện nút khi scroll xuống
    window.onscroll = function() {
      if (document.documentElement.scrollTop > 200) {
        backToTopBtn.style.display = "block";
      } else {
        backToTopBtn.style.display = "none";
      }
    };

    backToTopBtn.onclick = function() {
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