<?php
include("session.php");

include("./config/db.php");

// ── Lấy từ khóa tìm kiếm ─────────────────────────────────
$keyword = trim($_GET['keyword'] ?? '');

// ── Endpoint AJAX gợi ý tìm kiếm ─────────────────────────
if (isset($_GET['ajax_suggest'])) {
  header('Content-Type: application/json');
  $q   = trim($_GET['ajax_suggest']);
  $out = [];
  if (strlen($q) >= 1) {
    $qs  = mysqli_real_escape_string($conn, $q);
    $res = mysqli_query($conn, "SELECT MaSP, TenSP, GiaBan, Hinh FROM sanpham WHERE TenSP LIKE '%$qs%' LIMIT 6");
    if ($res) while ($r = mysqli_fetch_assoc($res)) $out[] = $r;
  }
  echo json_encode($out);
  exit();
}

$sql = "SELECT * FROM danhmuc LIMIT 6";
$result = mysqli_query($conn, $sql);

$sql_sp = "SELECT * FROM sanpham WHERE NoiBat = 1 LIMIT 8";
$result_sp = mysqli_query($conn, $sql_sp);

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
  <style>
    /* ══ THANH TÌM KIẾM ══════════════════════════════════════════════ */
    .search-wrap {
      position: relative;
      display: flex;
      align-items: center;
    }

    .search-wrap form {
      display: flex;
      align-items: center;
      background: #fff;
      border: 2px solid #e0e0e0;
      border-radius: 24px;
      overflow: visible;
      transition: border-color .2s, box-shadow .2s;
      position: relative;
    }

    .search-wrap form:focus-within {
      border-color: #ff6a00;
      box-shadow: 0 0 0 3px rgba(255, 106, 0, .12);
    }

    .search-wrap form input {
      border: none;
      outline: none;
      background: transparent;
      padding: 9px 14px 9px 16px;
      font-size: 14px;
      width: 260px;
      color: #333;
    }

    .search-wrap form button[type=submit] {
      background: #ff6a00;
      border: none;
      border-radius: 0 22px 22px 0;
      padding: 9px 16px;
      cursor: pointer;
      color: #fff;
      display: flex;
      align-items: center;
      transition: background .2s;
      flex-shrink: 0;
    }

    .search-wrap form button[type=submit]:hover {
      background: #e05a00;
    }

    .search-wrap form button[type=submit] .material-symbols-outlined {
      font-size: 20px;
    }

    /* ── Nút xóa ── */
    .btn-clear-kw {
      position: absolute;
      right: 48px;
      top: 50%;
      transform: translateY(-50%);
      background: #e0e0e0;
      border: none;
      border-radius: 50%;
      width: 20px;
      height: 20px;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      font-size: 12px;
      color: #666;
      z-index: 2;
      transition: background .15s;
    }

    .btn-clear-kw:hover {
      background: #ccc;
    }

    /* ── Dropdown gợi ý ── */
    .suggest-box {
      position: absolute;
      top: calc(100% + 6px);
      left: 0;
      right: 0;
      background: #fff;
      border: 1px solid #e8e8e8;
      border-radius: 12px;
      box-shadow: 0 8px 24px rgba(0, 0, 0, .12);
      z-index: 9999;
      overflow: hidden;
      display: none;
    }

    .suggest-item {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 10px 14px;
      cursor: pointer;
      transition: background .15s;
      text-decoration: none;
      color: inherit;
    }

    .suggest-item:hover {
      background: #fff8f5;
    }

    .suggest-item img {
      width: 40px;
      height: 40px;
      object-fit: cover;
      border-radius: 6px;
      border: 1px solid #eee;
      flex-shrink: 0;
    }

    .suggest-item-name {
      font-size: 13px;
      color: #333;
      line-height: 1.4;
      flex: 1;
      display: -webkit-box;
      -webkit-line-clamp: 1;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }

    .suggest-item-price {
      font-size: 13px;
      font-weight: 600;
      color: #ee4d2d;
      white-space: nowrap;
    }

    .suggest-footer {
      padding: 10px 14px;
      border-top: 1px solid #f0f0f0;
      font-size: 13px;
      color: #ff6a00;
      font-weight: 600;
      cursor: pointer;
      text-align: center;
      transition: background .15s;
    }

    .suggest-footer:hover {
      background: #fff8f5;
    }

    /* ══ KẾT QUẢ TÌM KIẾM BANNER ════════════════════════════════════ */
    .search-result-bar {
      background: #fff;
      border: 1px solid #ffe0cc;
      border-radius: 8px;
      padding: 14px 20px;
      margin-bottom: 16px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 10px;
    }

    .search-result-bar .kw-label {
      font-size: 15px;
      color: #333;
    }

    .search-result-bar .kw-label strong {
      color: #ff6a00;
      font-size: 17px;
    }

    .search-result-bar .kw-count {
      font-size: 13px;
      color: #888;
      background: #f5f5f5;
      padding: 4px 12px;
      border-radius: 20px;
    }

    .btn-clear-search {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      padding: 6px 14px;
      background: #fff;
      border: 1px solid #ff6a00;
      color: #ff6a00;
      border-radius: 20px;
      font-size: 13px;
      font-weight: 500;
      text-decoration: none;
      transition: all .2s;
    }

    .btn-clear-search:hover {
      background: #ff6a00;
      color: #fff;
    }

    /* ── Empty state ── */
    .search-empty {
      text-align: center;
      padding: 60px 20px;
      color: #aaa;
    }

    .search-empty .material-symbols-outlined {
      font-size: 64px;
      display: block;
      margin-bottom: 12px;
      color: #ddd;
    }

    .search-empty h3 {
      font-size: 18px;
      color: #555;
      margin-bottom: 8px;
    }

    .search-empty p {
      font-size: 14px;
      margin-bottom: 20px;
    }

    .search-empty a {
      color: #ff6a00;
      font-weight: 600;
      text-decoration: none;
    }

    /* ── Highlight từ khóa trong tên sản phẩm ── */
    .kw-highlight {
      background: #fff3cd;
      color: #e65100;
      border-radius: 2px;
      padding: 0 2px;
      font-weight: 600;
    }

    /* Sort select */
    .sort-select {
      border: 1px solid #e0e0e0;
      border-radius: 6px;
      padding: 7px 12px;
      font-size: 14px;
      outline: none;
      cursor: pointer;
    }
  </style>
</head>

<body>
  <!-- Header -->
  <header>
    <div class="container">
      <div class="header-content">
        <div class="menu-toggle"><span class="material-symbols-outlined">menu</span></div>
        <div class="logo"><a href="index.php"><img
              src="./assets/file_anh/0c4690d7-3599-4de4-a0a4-841817ead1c0.png" alt="" /></a></div>
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
                    <a href="http://localhost/DoAnNganh/shop.php?category_id=8">Bút bi</a>
                    <a href="http://localhost/DoAnNganh/shop.php?category_id=17">Bút chì</a>
                    <a href="http://localhost/DoAnNganh/shop.php?category_id=10">Bút lông</a>
                  </div>

                  <div class="submenu-column">
                    <h4>Văn phòng phẩm</h4>
                    <a href="http://localhost/DoAnNganh/shop.php?category_id=18">Sổ</a>
                    <a href="http://localhost/DoAnNganh/shop.php?category_id=1">Bìa hồ sơ</a>
                    <a href="http://localhost/DoAnNganh/shop.php?category_id=15">Dập ghim</a>
                    <a href="http://localhost/DoAnNganh/shop.php?category_id=16">Băng keo</a>
                  </div>

                  <div class="submenu-column">
                    <h4>Dụng cụ học tập</h4>
                    <a href="http://localhost/DoAnNganh/shop.php?category_id=4">Thước</a>
                    <a href="http://localhost/DoAnNganh/shop.php?category_id=3">Máy tính</a>
                    <a href="http://localhost/DoAnNganh/shop.php?category_id=14">Dao rọc giấy</a>
                  </div>
                </div>

                <!-- RIGHT: banner -->
                <div class="submenu-banner">
                  <a href="#!">
                    <img src="./assets/file_anh/1920_x_600___cta___6_.webp" alt="Back To School Sale" />
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

          <!-- ══ SEARCH BOX ══ -->
          <div class="search-wrap" id="searchWrap">
            <form action="shop.php" method="GET" id="searchForm" autocomplete="off">
              <input type="text" name="keyword" id="searchInput" placeholder="Tìm sản phẩm..."
                value="<?= htmlspecialchars($keyword) ?>" aria-label="Tìm kiếm sản phẩm" />
              <?php if ($keyword): ?>
                <button type="button" class="btn-clear-kw" id="btnClearKw" title="Xóa">✕</button>
              <?php endif; ?>
              <button type="submit"><span class="material-symbols-outlined">search</span></button>
            </form>
            <!-- Dropdown gợi ý -->
            <div class="suggest-box" id="suggestBox"></div>
          </div>

          <div class="cart-icon">
            <a href="package.php">
              <span class="material-symbols-outlined">local_mall</span>
              <?php if ($totalQty > 0): ?><span class="cart-count"><?= $totalQty ?></span><?php endif; ?>
            </a>
          </div>
          <?php if (isset($_SESSION['khachhang_id'])): ?>
            <a href="profile.php"><span class="material-symbols-outlined">person</span></a>
          <?php else: ?>
            <a href="login.php"><span class="material-symbols-outlined">person</span></a>
          <?php endif; ?>
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
    <div class="moving-bg-wrapper">
      <div class="background-move"></div>
      <!-- Featured Categories -->
      <section class="section">
        <div class="section-header">
          <h2 class="section-title">
            <span class="featured">Danh Mục</span> Nổi Bật
          </h2>
          <a href="shop.php" class="view-all">Xem thêm danh mục →</a>
        </div>

        <div class="categories-grid">

          <?php while ($row = mysqli_fetch_assoc($result)) { ?>

            <a href="shop.php?category_id=<?= $row['madanhmuc'] ?>" class="category-card">

              <div class="category-icon">
                <img src="./assets/file_anh/San_Pham/<?= $row['hinhanh'] ?>" alt="" />
              </div>

              <div class="category-name">
                <?= $row['tendanhmuc'] ?>
              </div>

            </a>

          <?php } ?>

        </div>
      </section>
    </div>
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
            <a href="product-detail.php?id=107" class="promo-card-btn">
              Khám Phá
            </a>
          </div>
          <div class="promo-card-image">
            <img src="./assets/file_anh/832c1d98ef4f72a3518069cb762b449f-removebg-preview.png" alt="" />
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
            <a href="product-detail.php?id=108" class="promo-card-btn">
              Mua Ngay
            </a>
          </div>
          <div class="promo-card-image">
            <img src="./assets/file_anh/phu-kien-thoi-trang-12-removebg-preview.png" alt="" />
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
        <?php while ($row_sp = mysqli_fetch_assoc($result_sp)) {
          $rating = isset($row_sp['Rating']) ? (int)$row_sp['Rating'] : 5;
          $luot_dg = isset($row_sp['SoLuotDanhGia']) ? $row_sp['SoLuotDanhGia'] : 0;
        ?>

          <a href="product-detail.php?id=<?= $row_sp['MaSP'] ?>" class="product-link">
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
                  <?= $row_sp['TenSP'] ?>
                </h3>

                <div class="rating">
                  <?php
                  for ($i = 1; $i <= 5; $i++) {
                    echo ($i <= $rating)
                      ? '<span style="color: #ffc107;">★</span>'
                      : '<span style="color: #ccc;">★</span>';
                  }
                  ?>
                  <span>(<?= $luot_dg ?>)</span>
                </div>

                <div class="price">
                  <?= number_format($row_sp['GiaBan'], 0, ',', '.') ?>đ
                </div>

                <?php if (!empty($row_sp['MaKhuyenMai'])): ?>
                  <div class="old-price">
                    <span class="discount">Đang có ưu đãi</span>
                  </div>
                <?php endif; ?>
              </div>
            </div>
          </a>

        <?php } ?>
      </div>

      <!-- Button -->
      <div class="see-more">
        <a href="shop.php" class="see-more-btn">Xem thêm sản phẩm</a>
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
        <?php $sql_fashion = "SELECT * FROM sanpham WHERE madanhmuc IN (5, 6)
          ORDER BY MaSP DESC LIMIT 8";
        $result_fashion = mysqli_query(
          $conn,
          $sql_fashion
        );
        if (mysqli_num_rows($result_fashion) > 0) {
          while ($sp
            = mysqli_fetch_assoc($result_fashion)
          ) {
            $rating =
              isset($sp['Rating']) ? (int)$sp['Rating'] : 5;
            $luot_dg =
              isset($sp['SoLuotDanhGia']) ? $sp['SoLuotDanhGia'] : 0; ?>

            <a href="product-detail.php?id=<?= $sp['MaSP'] ?>" class="product-link">
              <div class="product-card">
                <div class="product-img">
                  <img src="./assets/file_anh/San_Pham/<?= $sp['Hinh'] ?>" alt="<?= $sp['TenSP'] ?>" />
                </div>

                <div class="product-info">
                  <div class="product-tag">
                    <span class="new">👍 New</span>
                    <span class="sold">📊 Đã bán <?= number_format($sp['SoLuongDaBan']) ?></span>
                  </div>

                  <h3 class="product-name"><?= $sp['TenSP'] ?></h3>

                  <div class="rating">
                    <?php for ($i = 1; $i <= 5; $i++) {
                      echo ($i <= $rating) ?
                        '<span style="color: #ffc107">★</span>' : '<span
                    style="color: #ccc"
                    >★</span
                  >';
                    } ?>
                    <span>(<?= $luot_dg ?>)</span>
                  </div>

                  <div class="price">
                    <?= number_format($sp['GiaBan'], 0, ',', '.') ?>đ
                  </div>

                  <?php if (isset($sp['GiaCu']) && $sp['GiaCu'] > $sp['GiaBan']):
                  ?>
                    <div class="old-price">
                      <?= number_format($sp['GiaCu'], 0, ',', '.') ?>đ
                      <span class="discount">
                        -<?= round((($sp['GiaCu'] - $sp['GiaBan']) / $sp['GiaCu']) *
                            100) ?>%
                      </span>
                    </div>
                  <?php endif; ?>
                </div>
              </div>
            </a>

        <?php }
        } else {
          echo "
<p style='grid-column: 1/-1; text-align: center'>
  Hiện chưa có sản phẩm nào trong danh mục Giày hoặc Balo.
</p>
";
        } ?>
      </div>

      <div class="see-more">
        <button class="see-more-btn">Xem thêm sản phẩm</button>
      </div>
    </section>
    <!-- News
    <section class="news-section">
      <div class="news-container">

        <div class="news-feature">
          <a href="news_top5but.php"><img src="./assets/file_anh/businesswoman-planning-work_a8bb09d0b76c4c7e972d46e4c2500c4f.png" alt="" /></a>

          <div class="news-feature-content">
            <h2><a href="news_top5but.php" style="text-decoration: none; color: inherit;">Top 5 dòng bút văn phòng ngòi to viết đẹp</a></h2>
            <span class="date">Thứ Tư 04/03/2026</span>
            <p>
              Trong môi trường văn phòng, một cây bút có ngòi to, mực ra đều
              sẽ giúp tốc độ...
              <a href="news_top5but.php">Đọc tiếp</a>
            </p>
          </div>
        </div>

        <div class="news-list">

          <div class="news-item">
            <a href="news_mauhopmenh.php"><img src="./assets/file_anh/family-reunion-during-chinese-new-year-2026-01-05-05-45-10-utc_2ef835bd26794c7f9ea833af616f3784.png" alt="" /></a>
            <div class="news-text">
              <h4><a href="news_mauhopmenh.php" style="text-decoration: none; color: inherit;">Chọn màu hợp mệnh trong năm Bính Ngọ 2026</a></h4>
              <span>Thứ Sáu 13/02/2026</span>
              <p>
                Trong dòng chảy văn hoá Á Đông... <a href="news_mauhopmenh.php">Đọc tiếp</a>
              </p>
            </div>
          </div>

          <div class="news-item">
            <a href="news_valentine.php"><img src="./assets/file_anh/loseup-of-valentine-39-s-day-calendar-reminder-2025-02-09-22-57-20-utc_5ad22c8529364b45bf6f871166be1b3a.png" alt="" /></a>
            <div class="news-text">
              <h4><a href="news_valentine.php" style="text-decoration: none; color: inherit;">Chọn quà Valentine như thế nào để duy trì mối quan hệ</a></h4>
              <span>Thứ Sáu 06/02/2026</span>
              <p>
                Món quà Valentine nào cũng đẹp... <a href="news_valentine.php">Đọc tiếp</a>
              </p>
            </div>
          </div>

          <div class="news-item">
            <a href="news_tet2026.php"><img src="./assets/file_anh/nhung_dieu_co_the_ban_chua_biet_ve_tet_binh_ngo_2026_2122e7c1466a4fe5858003daea41ae09.jpg" alt="" /></a>
            <div class="news-text">
              <h4><a href="news_tet2026.php" style="text-decoration: none; color: inherit;">Những điều có thể bạn chưa biết về Tết Bính Ngọ 2026</a></h4>
              <span>Thứ Hai 05/01/2026</span>
              <p>
                Tết Nguyên Đán là không chỉ là khoảnh khắc...
                <a href="news_tet2026.php">Đọc tiếp</a>
              </p>
            </div>
          </div>

          <div class="news-item">
            <a href="news_top5but_quickdry.php"><img src="./assets/file_anh/cute-little-kids-studying-in-a-group-2024-10-18-03-40-30-utc_9f0d248b36de4523b673935db27492db.png" alt="" /></a>
            <div class="news-text">
              <h4><a href="news_top5but_quickdry.php" style="text-decoration: none; color: inherit;">Top 5 dòng bút Quick Dry/Super Quick Dry dành cho bé</a></h4>
              <span>Thứ Sáu 02/01/2026</span>
              <p>
                Viết tay là một hành trình rèn luyện...
                <a href="news_top5but_quickdry.php">Đọc tiếp</a>
              </p>
            </div>
          </div>

          <div class="news-item">
            <a href="news_giangsinh.php"><img src="./assets/file_anh/noel.png" alt="Quà tặng Giáng sinh" /></a>
            <div class="news-text">
              <h4><a href="news_giangsinh.php" style="text-decoration: none; color: inherit;">Quà tặng xu hướng “Edutainment” cho trẻ vào dịp Giáng sinh</a></h4>
              <span>Thứ Năm 17/12/2026</span>
              <p>
                Việc lựa chọn món quà Giáng sinh ý nghĩa cho con luôn là trăn trở...
                <a href="news_giangsinh.php">Đọc tiếp</a>
              </p>
            </div>
          </div>

        </div>
      </div>
    </section> -->
    <section class="charity-banner">
      <a href="#!">
        <img
          src="./assets/file_anh/1920_x_600___cta__1_d652d361086646d3b12a89b38ce6c294.jpg"
          alt="Back To School Sale" />
      </a>
    </section>
  </div>

  <!-- Footer -->
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
  <!-- ══ SCRIPT TÌM KIẾM GỢI Ý ══════════════════════════════════════════ -->
  <script>
    (function() {
      const input = document.getElementById('searchInput');
      const suggestBox = document.getElementById('suggestBox');
      const btnClear = document.getElementById('btnClearKw');
      let timer = null;

      if (!input) return;

      // Nút xóa từ khóa
      if (btnClear) {
        btnClear.addEventListener('click', () => {
          input.value = '';
          input.focus();
          hideSuggest();
          const url = new URL(window.location.href);
          url.searchParams.delete('keyword');
          window.location.href = url.toString();
        });
      }

      // Gõ → gợi ý
      input.addEventListener('input', function() {
        clearTimeout(timer);
        const q = this.value.trim();
        if (q.length < 1) {
          hideSuggest();
          return;
        }
        timer = setTimeout(() => fetchSuggest(q), 220);
      });

      // Focus → show lại nếu có nội dung
      input.addEventListener('focus', function() {
        if (this.value.trim().length >= 1) fetchSuggest(this.value.trim());
      });

      // Click ngoài → ẩn
      document.addEventListener('click', e => {
        if (!document.getElementById('searchWrap').contains(e.target)) hideSuggest();
      });

      // Phím mũi tên + Escape điều hướng gợi ý
      input.addEventListener('keydown', function(e) {
        const items = suggestBox.querySelectorAll('.suggest-item[data-idx]');
        let cur = [...items].findIndex(el => el.classList.contains('hover'));
        if (e.key === 'ArrowDown') {
          e.preventDefault();
          cur = Math.min(cur + 1, items.length - 1);
          items.forEach(el => el.classList.remove('hover'));
          if (items[cur]) {
            items[cur].classList.add('hover');
            input.value = items[cur].dataset.name;
          }
        } else if (e.key === 'ArrowUp') {
          e.preventDefault();
          cur = Math.max(cur - 1, 0);
          items.forEach(el => el.classList.remove('hover'));
          if (items[cur]) {
            items[cur].classList.add('hover');
            input.value = items[cur].dataset.name;
          }
        } else if (e.key === 'Escape') {
          hideSuggest();
        }
      });

      function fetchSuggest(q) {
        fetch('index.php?ajax_suggest=' + encodeURIComponent(q))
          .then(r => r.json())
          .then(data => renderSuggest(data, q))
          .catch(() => hideSuggest());
      }

      function renderSuggest(data, q) {
        if (!data || data.length === 0) {
          hideSuggest();
          return;
        }
        let html = '';
        data.forEach((sp, idx) => {
          const name = escHtml(sp.TenSP);
          const highlighted = name.replace(
            new RegExp('(' + escRegex(escHtml(q)) + ')', 'gi'),
            '<strong style="color:#ff6a00">$1</strong>'
          );
          html += `<a class="suggest-item" href="product-detail.php?id=${sp.MaSP}"
                          data-idx="${idx}" data-name="${escHtml(sp.TenSP)}">
                          <img src="./assets/file_anh/San_Pham/${escHtml(sp.Hinh)}" alt=""/>
                          <span class="suggest-item-name">${highlighted}</span>
                          <span class="suggest-item-price">${fmtMoney(sp.GiaBan)}đ</span>
                       </a>`;
        });
        html += `<div class="suggest-footer" onclick="submitSearch()">
                      <span class="material-symbols-outlined" style="vertical-align:middle;font-size:15px">search</span>
                      Xem tất cả kết quả cho "<strong>${escHtml(q)}</strong>"
                   </div>`;
        suggestBox.innerHTML = html;
        suggestBox.style.display = 'block';
      }

      function hideSuggest() {
        suggestBox.style.display = 'none';
        suggestBox.innerHTML = '';
      }

      function submitSearch() {
        document.getElementById('searchForm').submit();
      }

      function fmtMoney(n) {
        return Number(n).toLocaleString('vi-VN');
      }

      function escHtml(s) {
        return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
      }

      function escRegex(s) {
        return s.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
      }

      window.submitSearch = submitSearch;
    })();
  </script>

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