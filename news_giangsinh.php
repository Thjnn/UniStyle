<?php
session_start();
include('./config/db.php');
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Quà tặng xu hướng "Edutainment" cho trẻ vào dịp Giáng sinh - UniStyle</title>
  
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
              <h1 class="article-title">Quà tặng xu hướng “Edutainment” cho trẻ vào dịp Giáng sinh</h1>
              
              <div class="article-meta">
                  <span>Thứ Năm 17/12/2026</span> • <span>Bởi UniStyle Đời Sống</span>
              </div>

              <div class="article-content">
                  <p class="lead">
                      Việc lựa chọn món quà Giáng sinh ý nghĩa cho con luôn là trăn trở của nhiều bậc phụ huynh. Thay vì những món đồ chơi thông thường, xu hướng quà tặng năm nay hướng đến "Edutainment" – sự kết hợp hoàn hảo giữa học tập và giải trí. Cùng UniStyle khám phá những gợi ý quà tặng vừa giúp bé phát triển tư duy, vừa mang lại niềm vui trọn vẹn trong mùa lễ hội này.
                  </p>

                  <h2>1. Xu hướng "Edutainment" là gì?</h2>
                  <p>
                      "Edutainment" (ghép từ Education - Giáo dục và Entertainment - Giải trí) là phương pháp giáo dục lồng ghép các yếu tố giải trí vào quá trình học tập. Phương pháp này giúp trẻ em tiếp thu kiến thức một cách tự nhiên, kích thích sự tò mò và niềm đam mê khám phá mà không hề tạo cảm giác áp lực hay gò bó. 
                  </p>
                  <p>
                      Trong dịp Giáng sinh, một món quà mang tính "Edutainment" không chỉ mang lại niềm vui tức thời mà còn để lại giá trị lâu dài cho sự phát triển toàn diện của trẻ. Nó giúp trẻ rèn luyện tư duy logic, khả năng sáng tạo và các kỹ năng vận động tinh.
                  </p>

                  <img src="https://images.unsplash.com/photo-1544365558-35aa4afcf11f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" alt="Xu hướng Edutainment">
                  <p style="text-align: center; font-style: italic; font-size: 14px; color: #666; margin-top: -10px;">Edutainment kết hợp giữa giáo dục và giải trí giúp trẻ thỏa sức sáng tạo</p>

                  <h2>2. Tại sao đồ dùng học tập sáng tạo lại là món quà Giáng sinh hoàn hảo?</h2>
                  <p>
                      Đồ dùng học tập và các dụng cụ mỹ thuật không chỉ là công cụ để viết hay vẽ, mà còn là "chìa khóa" mở ra thế giới tưởng tượng phong phú của trẻ. Thông qua việc tô màu, vẽ tranh hay nặn sáp, trẻ được tự do thể hiện suy nghĩ, rèn luyện sự khéo léo của đôi tay và phát triển khả năng phối hợp giữa mắt và tay. 
                  </p>
                  <p>
                      Đặc biệt, khi nhận được món quà này trong dịp lễ, trẻ sẽ cảm thấy việc học tập và sáng tạo cũng thú vị như một trò chơi, từ đó yêu thích việc học hỏi hơn mỗi ngày.
                  </p>

                  <h2>3. Gợi ý quà tặng Giáng sinh xu hướng "Edutainment" từ UniStyle</h2>
                  <p>
                      Để giúp ba mẹ dễ dàng lựa chọn, UniStyle xin gợi ý những món quà văn phòng phẩm và dụng cụ mỹ thuật mang đậm chất Edutainment:
                  </p>
                  <p>
                      <strong>- Bút sáp màu và sáp nặn:</strong> Với chất liệu an toàn, không độc hại, sáp nặn và sáp màu giúp bé làm quen với màu sắc, hình khối. Bé có thể tự do sáng tạo nên những nhân vật Giáng sinh đáng yêu như người tuyết, ông già Noel, hay tuần lộc theo trí tưởng tượng của mình.
                  </p>
                  <p>
                      <strong>- Bút lông màu và bút dạ quang:</strong> Với màu sắc tươi sáng, ngòi bút êm ái, bé dễ dàng tô điểm cho những bức tranh lễ hội thêm phần rực rỡ, đồng thời rèn luyện sự tỉ mỉ và kỹ năng cầm bút đúng cách.
                  </p>
                  <p>
                      <strong>- Bộ dụng cụ thủ công DIY:</strong> Những tờ giấy màu, kéo an toàn, hồ dán... sẽ khuyến khích bé tự tay làm thiệp chúc mừng, trang trí cây thông. Hoạt động này không chỉ rèn luyện sự khéo léo mà còn nuôi dưỡng tình cảm gia đình khi bé tự tay làm quà tặng những người thân yêu.
                  </p>

                  <img src="./assets/file_anh/noel.png" alt="Quà tặng Giáng sinh Edutainment">
                  <p style="text-align: center; font-style: italic; font-size: 14px; color: #666; margin-top: -10px;">Dụng cụ mỹ thuật là lựa chọn tuyệt vời cho mùa Giáng sinh của bé</p>

                  <h2>Lời kết</h2>
                  <p>
                      Một món quà Giáng sinh mang xu hướng "Edutainment" không chỉ là lời chúc an lành mà còn là sự đầu tư cho tương lai và tư duy của trẻ. Hãy để UniStyle đồng hành cùng ba mẹ mang đến cho các bé một mùa Giáng sinh thật ý nghĩa, ngập tràn niềm vui và sự sáng tạo!
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
                      <a href="news_top5but_quickdry.php" class="related-news-item">
                          <img src="./assets/file_anh/cute-little-kids-studying-in-a-group-2024-10-18-03-40-30-utc_9f0d248b36de4523b673935db27492db.png" alt="Tin tức">
                          <div class="related-news-title">Top 5 dòng bút Quick Dry/Super Quick Dry dành cho bé</div>
                      </a>

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