<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Product Detail</title>
    <!-- embed link icon  -->
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,0..1"
    />
    <!--embed Font Awesome -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
    />
    <!-- logo web -->
    <link
      rel="shortcut icon"
      href="assets/file_anh/0c4690d7-3599-4de4-a0a4-841817ead1c0.png"
    />
    <!-- css -->
    <link rel="stylesheet" href="./assets/css/product-detail.css" />
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
            <a href="index.html"
              ><img
                src="./assets/file_anh/0c4690d7-3599-4de4-a0a4-841817ead1c0.png"
                alt=""
            /></a>
          </div>
          <nav>
            <ul>
              <li><a href="index.html">Trang chủ</a></li>
              <li><a href="shop.html">Cửa hàng</a></li>
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
                  placeholder="Tìm sản phẩm..."
                />
              </form>
            </div>
            <a href="package.html"
              ><span class="material-symbols-outlined"> local_mall </span></a
            >
            <a href="login.html"
              ><span class="material-symbols-outlined"> person </span></a
            >
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
              alt=""
            />
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
    <div class="product-page">
      <div class="product-container">
        <!-- LEFT IMAGE -->
        <div class="product-left">
          <img class="main-img" src="img/product.jpg" alt="product" />

          <div class="thumb-list">
            <img src="img/thumb1.jpg" />
            <img src="img/thumb2.jpg" />
            <img src="img/thumb3.jpg" />
            <img src="img/thumb4.jpg" />
          </div>
        </div>

        <!-- RIGHT INFO -->
        <div class="product-right">
          <div class="rating">⭐⭐⭐⭐⭐ <span>(8 đánh giá)</span></div>

          <h1 class="product-title">
            Acrylic Markers 1 đầu ngòi/ Bút sơn/ Bút lông 12/24/36 màu ThiênLong
            Colokit
          </h1>

          <div class="product-meta">
            Thương hiệu: <b>Colokit</b> | Tình trạng:
            <span class="stock">Còn hàng</span> | Đơn vị chịu trách nhiệm:
            <b>Thiên Long</b>
          </div>

          <div class="product-code">Mã sản phẩm: <span>60017437</span></div>

          <!-- PRICE -->
          <div class="price-box">
            <div class="price">
              57,300đ
              <span class="old-price">112,000đ</span>
            </div>

            <div class="sale-badge">
              Tiết kiệm <br />
              49%
            </div>
          </div>

          <!-- VARIANT -->
          <div class="variant">
            <label>Phân loại:</label>

            <div class="variant-list">
              <button class="variant-btn active">ACM-C003 12 màu</button>
              <button class="variant-btn">ACM-C005 36 màu</button>
              <button class="variant-btn">ACM-C004 24 màu</button>
            </div>
          </div>

          <!-- QUANTITY -->
          <div class="quantity">
            <label>Số lượng:</label>

            <div class="qty-box">
              <button>-</button>
              <input type="text" value="1" />
              <button>+</button>
            </div>
          </div>

          <!-- BUTTON -->
          <div class="product-buttons">
            <button class="add-cart">🛒 THÊM VÀO GIỎ</button>

            <button class="buy-now">MUA NGAY</button>
          </div>
        </div>
      </div>
    </div>

    <!-- RELATED PRODUCT -->
    <section class="related-product">
      <h2>Sản phẩm cùng loại</h2>

      <div class="product-grid">
        <!-- CARD -->
        <div class="product-card">
          <div class="product-img">
            <img src="img/sp1.png" />
          </div>

          <div class="product-info">
            <div class="product-tag">
              <span class="new">👍 New</span>
              <span class="sold">📊 Đã bán 54</span>
            </div>

            <h3 class="product-name">
              Bút lông 5 màu Cherry Blossom Morandi đầu Brush Thiên Long Colokit
            </h3>

            <div class="rating">★★★★★ <span>(0)</span></div>

            <div class="price">14,300đ</div>

            <div class="old-price">
              21,500đ <span class="discount">-33%</span>
            </div>
          </div>
        </div>

        <!-- COPY CARD -->
        <div class="product-card">
          <div class="product-img">
            <img src="img/sp2.png" />
          </div>

          <div class="product-info">
            <div class="product-tag">
              <span class="new">👍 New</span>
              <span class="sold">📊 Đã bán 32</span>
            </div>

            <h3 class="product-name">
              Bút lông 5 màu Blooming Morandi đầu Brush Thiên Long
            </h3>

            <div class="rating">★★★★★ <span>(0)</span></div>

            <div class="price">14,300đ</div>

            <div class="old-price">
              21,500đ <span class="discount">-33%</span>
            </div>
          </div>
        </div>

        <!-- CARD -->
        <div class="product-card">
          <div class="product-img">
            <img src="img/sp3.png" />
          </div>

          <div class="product-info">
            <div class="product-tag">
              <span class="new">👍 New</span>
              <span class="sold">📊 Đã bán 118</span>
            </div>

            <h3 class="product-name">
              Bút lông 24 màu Hygge Morandi đầu Brush Thiên Long
            </h3>

            <div class="rating">★★★★★ <span>(0)</span></div>

            <div class="price">60,200đ</div>

            <div class="old-price">
              90,800đ <span class="discount">-34%</span>
            </div>
          </div>
        </div>
        <div class="product-card">
          <div class="product-img">
            <img src="img/sp3.png" />
          </div>

          <div class="product-info">
            <div class="product-tag">
              <span class="new">👍 New</span>
              <span class="sold">📊 Đã bán 118</span>
            </div>

            <h3 class="product-name">
              Bút lông 24 màu Hygge Morandi đầu Brush Thiên Long
            </h3>

            <div class="rating">★★★★★ <span>(0)</span></div>

            <div class="price">60,200đ</div>

            <div class="old-price">
              90,800đ <span class="discount">-34%</span>
            </div>
          </div>
        </div>
        <div class="product-card">
          <div class="product-img">
            <img src="img/sp3.png" />
          </div>

          <div class="product-info">
            <div class="product-tag">
              <span class="new">👍 New</span>
              <span class="sold">📊 Đã bán 118</span>
            </div>

            <h3 class="product-name">
              Bút lông 24 màu Hygge Morandi đầu Brush Thiên Long
            </h3>

            <div class="rating">★★★★★ <span>(0)</span></div>

            <div class="price">60,200đ</div>

            <div class="old-price">
              90,800đ <span class="discount">-34%</span>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- desc -->
    <div class="product-description">
      <h2>Thông số kĩ thuật</h2>

      <table class="spec-table">
        <tr>
          <td>Thương hiệu</td>
          <td>Thiên Long</td>
        </tr>

        <tr>
          <td>Đường kính viên bi</td>
          <td>0.5 mm</td>
        </tr>

        <tr>
          <td>Tiêu chuẩn</td>
          <td>TCCS 009:2011/TL-BGEL</td>
        </tr>

        <tr>
          <td>Xuất xứ</td>
          <td>Việt Nam</td>
        </tr>

        <tr>
          <td>Sản xuất</td>
          <td>Việt Nam</td>
        </tr>

        <tr>
          <td>Khuyến cáo</td>
          <td>
            Tránh nguồn nhiệt, hóa chất. Không thích hợp cho trẻ dưới 3 tuổi
          </td>
        </tr>
      </table>

      <div class="description-text" id="desc">
        <p>
          Bút có thiết kế hiện đại, kết hợp hài hòa giữa phần thân dưới bằng
          nhựa nhiều màu sắc và thon dài với phần giắt bút được phối màu theo
          từng thức uống khoái khẩu. Cảm giác cầm bút rất thoải mái, viết rất
          êm. Nét viết thanh mảnh và sắc nét.
        </p>

        <h3>Đặc tính sản phẩm:</h3>

        <ul>
          <li>
            Cây bút đẹp, nhỏ nhọn khá nhẹ nhàng, thú vị, nét viết trơn êm, sắc
            sảo, chữ viết đẹp.
          </li>
          <li>
            Sản phẩm có đầu bấm và lò xo nhạy và bền, không bung, không kẹt,
            vướng.
          </li>
        </ul>
      </div>

      <!-- <button class="toggle-desc" onclick="toggleDesc()">Thu gọn</button> -->
    </div>
    <!-- Back to Top -->
    <button id="backToTop">
      <span class="material-symbols-outlined"> keyboard_arrow_up </span>
    </button>
    <script>
      const btn = document.getElementById("backToTop");

      // Hiện nút khi scroll xuống
      window.onscroll = function () {
        if (document.documentElement.scrollTop > 200) {
          btn.style.display = "block";
        } else {
          btn.style.display = "none";
        }
      };

      // Click để lên đầu trang
      btn.onclick = function () {
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
              alt=""
            />
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
