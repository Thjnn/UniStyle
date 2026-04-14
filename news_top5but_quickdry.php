<?php
session_start();
include('./config/db.php');
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Top 5 dòng bút Quick Dry/Super Quick Dry dành cho bé - UniStyle</title>
  
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
              <h1 class="article-title">Top 5 dòng bút Quick Dry/Super Quick Drying dành cho bé – Viết nhanh và mau khô</h1>
              
              <div class="article-meta">
                  <span>Thứ Sáu 02/01/2026</span> • <span>Bởi UniStyle Đời Sống</span>
              </div>

              <div class="article-content">
                  <p class="lead">
                      Viết tay là một hành trình rèn luyện sự tập trung, khéo léo và niềm yêu thích học tập của trẻ. Một cây bút tốt không chỉ giúp bé viết đẹp hơn mà còn khơi gợi cảm hứng mỗi khi mở trang vở mới. Hiểu được điều đó, Thiên Long mang đến 5 dòng bút gel Quick Dry - mực khô nhanh, nét rõ, viết êm, giúp bé tự tin viết đẹp mà không lo dây mực hay nhòe trang vở.
                  </p>

                  <h2>1. Bút Quick Dry Gel-040 - Phù hợp cho bạn thích thiết kế nổi bật</h2>
                  <p>
                      Nếu bé thích những cây bút có màu sắc bắt mắt và kiểu dáng dễ thương, Gel-040 là lựa chọn không thể bỏ qua. Thân bút có thiết kế nhân vật thời tiền sử Bububu rất đáng yêu. Đặc biệt, thân bút còn được ứng dụng công nghệ cán nhám (transfer-film) giúp hạn chế tình trạng mờ họa tiết. Dòng bút này sở hữu công nghệ mực Quick Dry tiên tiến, khô nhanh và ra mực đều, giúp bé viết rõ nét, không bị đọng giọt mực. Với thiết kế thân nhỏ gọn, dễ cầm, Gel-040 tạo cảm giác nhẹ tay khi viết, phù hợp cho học sinh tiểu học. Các sắc màu tươi sáng giúp bé thêm hứng thú khi học và viết mỗi ngày.
                  </p>
                  
                  <img src="https://images.unsplash.com/photo-1585336261022-680e295ce3fe?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" alt="Bút Quick Dry Gel-040">
                  <p style="text-align: center; font-style: italic; font-size: 14px; color: #666; margin-top: -10px;">Bút Quick Dry Gel-040</p>

                  <h2>2. Bút Quick Dry Gel-057 - Phù hợp cho bạn thích thiết kế đáng yêu</h2>
                  <p>
                      Gel-057 là dòng bút dành cho những bé viết nhanh hoặc ghi chép nhiều. Thân bút nổi bật với ba thiết kế: thành phố trong mơ (mực tím), sinh vật dưới biển (mực xanh) và khu vườn trên mây (mực đen). Bút sử dụng mực Quick Dry khô nhanh gấp 3 lần so với mực gel thông thường, giúp bé viết liên tục mà không bị nhòe. Phần thân bút phủ đệm mềm, chống trơn trượt, cho cảm giác cầm chắc và dễ điều khiển khi viết tốc độ cao. Mực đậm, nét rõ và không lem giúp bé yên tâm sử dụng trong các tiết học, đặc biệt là khi phải ghi bài nhiều. Đây là “chiến binh” đáng tin cậy cho mọi học sinh năng động.
                  </p>

                  <img src="https://images.unsplash.com/photo-1568227451475-430c8ba9067b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" alt="Bút Quick Dry Gel-057">
                  <p style="text-align: center; font-style: italic; font-size: 14px; color: #666; margin-top: -10px;">Bút Quick Dry Gel-057</p>

                  <h2>3. Bút Super Quick Drying Gel-073 - Phù hợp cho bạn thích nắp đậy</h2>
                  <p>
                      Thuộc dòng Akooland, Gel-073 gây ấn tượng với thiết kế trẻ trung và họa tiết đáng yêu. Dành cho những ai chưa biết, thế giới Akooland là bộ nhân vật độc quyền của Nhà Thiên Long tự sáng tạo, được ứng dụng rất nhiều vào trong rất nhiều sản phẩm học cụ của “brand xanh”. Không chỉ đẹp mắt, cây bút này còn sở hữu đầu ngòi 0.5mm cùng mực Super Quick Drying chất lượng cao, giúp viết êm, nét đều, khô nhanh khi vừa dứt nét đối với giấy 70gsm trở lên. Với thiết kế nắp bút kín khít, mực luôn tươi mới, không bị khô dù dùng lâu. Gel-073 không chỉ là cây bút viết, mà còn là phụ kiện học tập thể hiện phong cách riêng của bé.
                  </p>

                  <img src="https://images.unsplash.com/photo-1586075010923-2dd4570fb338?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" alt="Bút Super Quick Drying Gel-073">
                  <p style="text-align: center; font-style: italic; font-size: 14px; color: #666; margin-top: -10px;">Bút Super Quick Drying Gel-073</p>

                  <h2>4. Bút Super Quick Drying Gel-067 Joyee - Phù hợp cho bạn thích sự tươi tắn</h2>
                  <p>
                      Dòng bút Gel-067 Joyee có thể được đặt cách gọi là "con cưng" của Nhà Thiên Long vì thiết kế rất đáng yêu. Bút có ba màu mực tương ứng với ba thiết kế: mực tím là họa tiết mèo ngố, mực xanh là họa tiết chim cánh cụt, mực đen là họa tiết chó shiba. Đặc biệt, thân bút có ứng dụng công nghệ cán nhám (transfer-film) nên hạn chế tình trạng mờ họa tiết. Chất mực Super Quick Drying khô siêu nhanh, mực khô khi vừa dứt nét nếu như sử dụng giấy từ 70gsm trở lên. Ngòi 0.5mm nên nét viết khá thanh mảnh, không quá dày nên các bạn nhỏ không cần lo lắng.
                  </p>

                  <img src="https://images.unsplash.com/photo-1544816155-12df9643f363?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" alt="Bút Super Quick Drying Gel-067 Joyee">
                  <p style="text-align: center; font-style: italic; font-size: 14px; color: #666; margin-top: -10px;">Bút Super Quick Drying Gel-067</p>

                  <h2>5. Bút gel Minimalist Gel-071 - Phù hợp cho bạn thích sự tinh tế và hiện đại</h2>
                  <p>
                      Khép lại danh sách là Gel-071, dòng bút thuộc bộ sưu tập Minimalist hiện đại, tối giản nhưng vẫn đầy tinh tế. Thân bút phủ một màu trắng ngà tinh tế từ đầu đến cuối. Màu trắng này khá giống với màu trắng vân mây (Cloud Dancer) - màu Pantone của năm 2026. Ngòi 0.5mm cho nét viết đều, mực ra ổn định cùng công nghệ Super Quick Drying đảm bảo khô nhanh. Thiết kế cơ chế bấm tiện lợi, giắt bút chắc chắn, giúp bé dễ dàng mang theo khi đi học. Đây là cây bút phù hợp với mọi đối tượng, từ học sinh tiểu học đến sinh viên, thậm chí cả người lớn yêu nét viết thanh lịch.
                  </p>

                  <img src="https://images.unsplash.com/photo-1611078712613-2d2c161a0cc4?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" alt="Bút gel Minimalist Gel-071">
                  <p style="text-align: center; font-style: italic; font-size: 14px; color: #666; margin-top: -10px;">Bút Super Quick Drying Gel-071</p>

                  <h3>Lý do tại sao nên sử dụng chất mực Quick Dry/Super Quick Drying của Nhà Thiên Long:</h3>
                  <p>
                      - Chất mực Quick Dry khô nhanh chỉ sau 3 giây; riêng Super Quick Drying khô nhanh khi vừa dứt nét đối với giấy 70gsm trở lên.<br>
                      - Thiết kế đa dạng từ tối giản (Minimalist) đến rực rỡ (Bububu, Akooland,...).<br>
                      - Giá cả phù hợp, dễ tiếp cận đến đa dạng các tệp khách hàng từ học sinh, sinh viên đến nhân viên văn phòng.
                  </p>
                  <p>
                      5 dòng bút Quick Dry của Thiên Long không chỉ mang đến chất lượng mực khô nhanh, nét viết êm, màu mực rõ, mà còn giúp bé hình thành thói quen viết sạch, đẹp và gọn gàng ngay từ những năm đầu đến trường. Mỗi cây bút là một “người bạn học tập” đáng tin cậy, giúp bé viết nhanh, học hiệu quả và thêm yêu chữ viết của mình.
                  </p>
                  <p>
                      Hãy để Thiên Long đồng hành cùng bé trên hành trình viết nên những ước mơ, bắt đầu từ chính những trang vở đầu tiên, nơi từng nét chữ nhỏ chứa đựng cả niềm vui và sự tự tin mỗi ngày.
                  </p>
                  <p style="font-style: italic; color: #05a; font-weight: 500;">
                      >>> Xem thêm: Top 5 dòng bút viết dành cho học sinh cấp 1
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
                      <a href="news_top5but.php" class="related-news-item">
                          <img src="./assets/file_anh/businesswoman-planning-work_a8bb09d0b76c4c7e972d46e4c2500c4f.png" alt="Tin tức">
                          <div class="related-news-title">Top 5 dòng bút văn phòng ngòi to viết đẹp, êm tay</div>
                      </a>

                      <a href="news_mauhopmenh.php" class="related-news-item">
                          <img src="./assets/file_anh/family-reunion-during-chinese-new-year-2026-01-05-05-45-10-utc_2ef835bd26794c7f9ea833af616f3784.png" alt="Tin tức">
                          <div class="related-news-title">Chọn màu hợp mệnh trong năm Bính Ngọ 2026 cho góc học tập</div>
                      </a>

                      <a href="news_valentine.php" class="related-news-item">
                          <img src="./assets/file_anh/loseup-of-valentine-39-s-day-calendar-reminder-2025-02-09-22-57-20-utc_5ad22c8529364b45bf6f871166be1b3a.png" alt="Tin tức">
                          <div class="related-news-title">Chọn quà Valentine như thế nào để duy trì mối quan hệ</div>
                      </a>

                      <a href="news_tet2026.php" class="related-news-item">
                          <img src="./assets/file_anh/nhung_dieu_co_the_ban_chua_biet_ve_tet_binh_ngo_2026_2122e7c1466a4fe5858003daea41ae09.jpg" alt="Tin tức">
                          <div class="related-news-title">Những điều có thể bạn chưa biết về Tết Bính Ngọ 2026</div>
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
        <p>Đăng ký nhận bản tin của chúng tôi để nhận các ưu đãi và giảm giá độc quyền!</p>
      </div>
      <div class="newsletter-form">
        <input type="email" placeholder="Email của bạn..." />
        <button><span class="material-symbols-outlined">mail</span></button>
      </div>
    </div>
    <div class="footer-container">
      <div class="footer-col">
        <h2 class="logo"><img src="./assets/file_anh/0c4690d7-3599-4de4-a0a4-841817ead1c0.png" alt="" /> UniStyle</h2>
        <p>Nếu bạn có bất kỳ thắc mắc nào, vui lòng liên hệ với chúng tôi theo địa chỉ sau: <span>support@example.com</span></p>
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
          <li><a href="FAQ.php">Thông休<br>tin vận chuyển</a></li>
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
    const menuToggle = document.querySelector(".menu-toggle");
    const mobileMenu = document.querySelector(".mobile-menu-overlay");
    const menuClose = document.querySelector(".menu-close");
    menuToggle.addEventListener("click", () => { mobileMenu.classList.add("active"); document.body.style.overflow = "hidden"; });
    menuClose.addEventListener("click", () => { mobileMenu.classList.remove("active"); document.body.style.overflow = ""; });
    mobileMenu.addEventListener("click", (e) => { if (e.target === mobileMenu) { mobileMenu.classList.remove("active"); document.body.style.overflow = ""; } });
    const searchBox = document.querySelector(".search-box");
    const searchIcon = document.querySelector(".search-icon");
    searchIcon.addEventListener("click", () => { searchBox.classList.toggle("active"); });
    const btn = document.getElementById("backToTop");
    window.onscroll = function() { if (document.documentElement.scrollTop > 200) { btn.style.display = "block"; } else { btn.style.display = "none"; } };
    btn.onclick = function() { window.scrollTo({ top: 0, behavior: "smooth" }); };
  </script>
</body>
</html>