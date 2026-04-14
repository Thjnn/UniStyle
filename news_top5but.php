<?php
session_start();
include('./config/db.php');
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Top 5 dòng bút văn phòng ngòi to viết đẹp - UniStyle</title>
  
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,0..1" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link rel="shortcut icon" href="assets/file_anh/0c4690d7-3599-4de4-a0a4-841817ead1c0.png" />
  
  <link rel="stylesheet" href="./assets/css/style.css" />
  <link rel="stylesheet" href="./assets/css/reposive.css" />
  <link rel="stylesheet" href="./assets/css/news_detail.css" />
</head>

<body>
  <header style="background: #fff;">
    <div class="container">
      <div class="header-content">
        <div class="menu-toggle">
          <span class="material-symbols-outlined">menu</span>
        </div>
        <div class="logo">
          <a href="index.php"><img src="./assets/file_anh/0c4690d7-3599-4de4-a0a4-841817ead1c0.png" alt="Logo" /></a>
        </div>
        <nav>
          <ul>
            <li><a href="index.php">Trang chủ</a></li>
            <li class="has-submenu">
              <a href="shop.php">Cửa hàng</a>
              <div class="submenu">
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
                <div class="submenu-banner">
                  <a href="#!"><img src="./assets/file_anh/1920_x_600___cta___6_.webp" alt="Sale" /></a>
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
              <input type="text" name="keyword" placeholder="Tìm sản phẩm..." />
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

  <div class="mobile-menu-overlay" id="mobileMenuOverlay">
    <div class="mobile-menu-content">
      <div class="mobile-menu-header">
        <div class="logo"><img src="./assets/file_anh/0c4690d7-3599-4de4-a0a4-841817ead1c0.png" alt="" /></div>
        <div class="menu-close" id="menuClose"><span class="material-symbols-outlined">close</span></div>
      </div>
      <nav class="mobile-nav">
        <ul>
          <li><a href="index.php" class="mobile-menu-link">Trang chủ</a></li>
          <li><a href="shop.php" class="mobile-menu-link">Cửa hàng</a></li>
          <li><a href="contact.php" class="mobile-menu-link">Liên hệ</a></li>
          <li><a href="FAQ.php" class="mobile-menu-link">FAQ</a></li>
          <li><a href="aboutus.php" class="mobile-menu-link">Về chúng tôi</a></li>
        </ul>
      </nav>
    </div>
  </div>

  <div class="article-page">
      <div class="article-container">
          
          <main class="article-main">
              <h1 class="article-title">Top 5 dòng bút văn phòng ngòi to viết đẹp</h1>
              
              <div class="article-meta">
                  <span>Thứ Tư 04/03/2026</span> • <span>Bởi UniStyle Đời Sống</span>
              </div>

              <div class="article-content">
                  <p class="lead">
                      Trong môi trường văn phòng, một cây bút có ngòi to, mực ra đều sẽ giúp tốc độ ghi chép nhanh hơn, hạn chế đứt nét và tạo cảm giác chắc tay khi ký hoặc viết nhiều. Dưới đây là Top 5 dòng bút văn phòng ngòi to viết đẹp, được đánh giá cao nhờ sự kết hợp giữa chất lượng, thẩm mỹ và độ bền.
                  </p>

                  <h3>1. Bút gel Bizner Thiên Long Gel-058 - Ngòi 0.7mm, thiết kế phù hợp dân công sở thích họa tiết văn hoá</h3>
                  <p>
                      Bizner Gel-058 là dòng bút gel thuộc phân khúc văn phòng của Thiên Long, nổi bật với ngòi 0.7mm giúp tạo độ đậm rõ ràng, chữ nét to và liền mạch. Thân bút thiết kế sang trọng, họa tiết kiến trúc độc đáo với hình ảnh địa danh Hà Nội (tháp Rùa, đền Ngọc Sơn, Khuê Văn Các, Văn miếu Quốc Tử Giám, cầu Thê Húc, lăng Bác) và địa danh miền Trung (cầu Vàng, cầu Rồng, vòng quay Sun Wheel). Công nghệ mực gel cải tiến giúp mực ra đều, nhanh khô và hạn chế lem khi viết tốc độ cao. Đây là lựa chọn lý tưởng để ký văn bản, ghi chép dày đặc hoặc dùng tại các cuộc họp cần sự chỉn chu. Ngoài ra, nhờ vào thiết kế lấy văn hoá làm cảm hứng chủ đạo, bút hoàn toàn có thể dùng làm quà tặng tri ân trong các ngày lễ hoặc dịp quan trọng.
                  </p>
                  <img src="https://images.unsplash.com/photo-1585336261022-680e295ce3fe?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" alt="Bút Bizner">

                  <h3>2. Bút đùn Buddies TL-021 - Ngòi 0.6mm, thiết kế phù hợp dân công sở thích sự dễ thương</h3>
                  <p>
                      Dù TL-021 thuộc phân khúc học sinh, nhưng lại cực kỳ phù hợp nhân viên văn phòng thích sự trẻ trung, năng động. Bút có nét viết 0.6mm, mực đậm cho nét chữ liền mạch và ít tắc. Nhờ vào thiết kế Buddies dễ thương nên đem lại cảm giác thư giãn khi làm việc. Nhờ vào những ưu điểm trên giúp TL-021 trở thành cây bút "ghi chú nhanh - ký nhẹ", giới văn phòng rất được yêu thích. Dòng bút này có hai màu gồm xanh và đen. Đây cũng là lựa chọn giá "hạt dẻ" nhưng chất lượng ổn định và phù hợp để sử dụng dài hạn.
                  </p>
                  <img src="https://images.unsplash.com/photo-1568227451475-430c8ba9067b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" alt="Bút Buddies">

                  <h3>3. Bút GelB-028 - Ngòi 0.6mm, thiết kế phù hợp dân công sở thích sắc màu dịu ngọt</h3>
                  <p>
                      GelB-028 là dòng bút GelB được cải tiến từ bút TL-031. Bút có ngòi to được thiết kế cho người viết nhiều, đặc biệt là các anh chị văn phòng. Ngòi 0.6mm cho nét chữ đậm vừa, sắc nét mà không bị quá to như những cây 1.0mm. Mực ra rất mượt, độ ổn định cao, không vón cục và ít lem trên giấy. Mực GelB ra đều nét, đây là sự lai hợp giữa độ nhanh khô của mực bi và sự đậm đà của mực gel. Dòng bút này hiện tại chỉ có mực xanh. Thân bút được phủ những tone màu pastel dễ thương như trắng ngà, be, tím, xanh ngọc và xanh lá. Đây là cây bút văn phòng tiện dụng, phù hợp với mọi loại giấy và được người dùng đánh giá cao vì độ bền ngòi.
                  </p>
                  <img src="https://images.unsplash.com/photo-1586075010923-2dd4570fb338?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" alt="Bút GelB 028">

                  <h3>4. Bút GelB-029 - Ngòi 0.6mm, thiết kế phù hợp dân công sở thích sự êm tay</h3>
                  <p>
                      Nếu GelB-028 tạo ấn tượng bởi độ bầu ở giữa thân bút thì GelB-029 thu hút người dùng ở phần đệm tay êm ái. Đây là dòng bút được cải tiến từ TL-036. Ngòi 0.6mm kết hợp cùng chất mực GelB đậm đà cho độ to rõ vừa phải, phù hợp ký giấy tờ, viết ghi chú lớn hoặc dùng trong các văn bản cần độ tương phản cao. Thân bút được thiết kế đệm tay chống trơn giúp cầm lâu vẫn thoải mái. Dòng bút này hiện tại chỉ có mực xanh. Đây là cây bút tạo sự chuyên nghiệp và cuốn hút cho nét chữ, rất hợp với những ai chuộng thiết kế xinh - mực xịn - nét chữ to vừa.
                  </p>

                  <h3>5. Bút bi TL-023 - Ngòi 0.8mm, thiết kế phù hợp dân công sở thích tinh giản</h3>
                  <p>
                      Nếu ai thích thiết kế tinh giản thì TL-023 chính là “chân ái”. TL-023 là dòng bút bi ngòi to được sử dụng nhiều trong văn phòng nhờ độ bền mực cao và khả năng viết nhanh nhưng không đứt nét. Ngòi 0.8mm cho nét chữ tròn, dày và nổi bật, rất hợp để viết biên bản, ký tờ trình hoặc ghi chú tốc độ cao. Cơ chế bấm tiện lợi giúp thao tác nhanh. Dòng này có ba màu mực gồm xanh, đỏ và đen. Đây là cây bút phù hợp mọi đối tượng, đặc biệt là nhân viên cần viết liên tục trong thời gian dài.
                  </p>
              </div>
              <div class="comment-section" style="background-color: #eff5fa; padding: 25px 30px; border-radius: 8px; margin-top: 40px;">
                  <h3 style="font-size: 18px; font-weight: 600; color: #333; margin-bottom: 20px;">Viết bình luận</h3>
                  <form action="#" method="POST">
                      <div style="margin-bottom: 15px;">
                          <input type="text" placeholder="Tên của bạn" required style="width: 100%; padding: 12px 15px; border: 1px solid #dce1e8; border-radius: 6px; outline: none; font-size: 14px; box-sizing: border-box; font-family: inherit;">
                      </div>
                      <div style="margin-bottom: 15px;">
                          <input type="email" placeholder="Email của bạn" required style="width: 100%; padding: 12px 15px; border: 1px solid #dce1e8; border-radius: 6px; outline: none; font-size: 14px; box-sizing: border-box; font-family: inherit;">
                      </div>
                      <div style="margin-bottom: 10px;">
                          <textarea placeholder="Viết bình luận ..." rows="5" required style="width: 100%; padding: 12px 15px; border: 1px solid #dce1e8; border-radius: 6px; outline: none; font-size: 14px; resize: vertical; box-sizing: border-box; font-family: inherit;"></textarea>
                      </div>
                      <p style="font-size: 13px; color: #666; margin-bottom: 15px; margin-top: 5px;">Bình luận của bạn sẽ được duyệt trước khi đăng lên</p>
                      <button type="submit" style="background-color: #007bff; color: #fff; border: none; padding: 10px 25px; border-radius: 20px; font-size: 14px; font-weight: 500; cursor: pointer; transition: background 0.3s; font-family: inherit;">Gửi bình luận</button>
                  </form>
              </div>
          </main>

          <aside class="article-sidebar">
              <div class="sidebar-widget">
                  <h3 class="widget-title">Tin liên quan</h3>
                  
                  <div class="related-news-list">
                      <a href="#" class="related-news-item">
                          <img src="./assets/file_anh/businesswoman-planning-work_a8bb09d0b76c4c7e972d46e4c2500c4f.png" alt="Tin tức">
                          <div class="related-news-title">Vì sao giấy không chỉ có một độ dày? Giải mã những con số GSM</div>
                      </a>

                      <a href="#" class="related-news-item">
                          <img src="./assets/file_anh/nhung_dieu_co_the_ban_chua_biet_ve_tet_binh_ngo_2026_2122e7c1466a4fe5858003daea41ae09.jpg" alt="Tin tức">
                          <div class="related-news-title">Những điều có thể bạn chưa biết về Tết Bính Ngọ 2026</div>
                      </a>

                      <a href="#" class="related-news-item">
                          <img src="./assets/file_anh/loseup-of-valentine-39-s-day-calendar-reminder-2025-02-09-22-57-20-utc_5ad22c8529364b45bf6f871166be1b3a.png" alt="Tin tức">
                          <div class="related-news-title">Bí quyết chọn quà tặng ý nghĩa để duy trì các mối quan hệ</div>
                      </a>
                  </div>
              </div>
          </aside>
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
          <img src="./assets/file_anh/0c4690d7-3599-4de4-a0a4-841817ead1c0.png" alt="" />
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
    // JS Điều khiển Menu Mobile & Nút BackToTop
    const menuToggle = document.querySelector(".menu-toggle");
    const mobileMenu = document.querySelector(".mobile-menu-overlay");
    const menuClose = document.querySelector(".menu-close");

    menuToggle.addEventListener("click", () => {
      mobileMenu.classList.add("active");
      document.body.style.overflow = "hidden";
    });

    menuClose.addEventListener("click", () => {
      mobileMenu.classList.remove("active");
      document.body.style.overflow = "";
    });

    mobileMenu.addEventListener("click", (e) => {
      if (e.target === mobileMenu) {
        mobileMenu.classList.remove("active");
        document.body.style.overflow = "";
      }
    });

    const searchBox = document.querySelector(".search-box");
    const searchIcon = document.querySelector(".search-icon");
    searchIcon.addEventListener("click", () => {
      searchBox.classList.toggle("active");
    });

    const btn = document.getElementById("backToTop");
    window.onscroll = function() {
      if (document.documentElement.scrollTop > 200) {
        btn.style.display = "block";
      } else {
        btn.style.display = "none";
      }
    };
    btn.onclick = function() {
      window.scrollTo({ top: 0, behavior: "smooth" });
    };
  </script>
</body>
</html>