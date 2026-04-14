<?php
session_start();
include('./config/db.php');
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Chọn màu hợp mệnh trong năm Bính Ngọ 2026 - UniStyle</title>
  
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
              <h1 class="article-title">Chọn màu hợp mệnh trong năm Bính Ngọ 2026</h1>
              
              <div class="article-meta">
                  <span>Thứ Sáu 13/02/2026</span> • <span>Bởi UniStyle Đời Sống</span>
              </div>

              <div class="article-content">
                  <p class="lead">
                      Trong dòng chảy văn hoá Á Đông, màu sắc không chỉ là yếu tố thẩm mỹ. Với người Việt, màu sắc còn gắn liền với quan niệm về ngũ hành, âm dương và cách con người ứng xử hài hoà với tự nhiên. Vì thế, việc chọn màu hợp mệnh trong năm mới không đơn thuần là niềm tin tâm linh hay mong cầu may mắn mà còn là tìm kiếm sự cân bằng, ổn định tinh thần và sống thuận theo quy luật của trời đất.
                  </p>
                  
                  <img src="https://images.unsplash.com/photo-1549465220-1a8b9238cd48?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" alt="Phong thuỷ ngũ hành">

                  <h2>1. Tổng quan năm Bính Ngọ 2026 dưới góc nhìn ngũ hành</h2>
                  <p>
                      Theo hệ thống Can Chi - Ngũ hành, năm 2026 là năm Bính Ngọ, trong đó “Bính” thuộc Hỏa, “Ngọ” cũng thuộc Hỏa, nhưng nạp âm lại là Thiên Hà Thủy - hình ảnh dòng nước từ trời cao, tượng trưng cho mưa, cho sự nuôi dưỡng âm thầm và lan tỏa. Khác với những dạng Thủy mạnh mẽ như Đại Hải Thủy hay Trường Lưu Thủy, Thiên Hà Thủy mang tính dịu nhẹ, bao dung và bền bỉ. Trong quan niệm truyền thống, đây là loại năng lượng không ồn ào, không bộc phát, mà tác động dần dần, đòi hỏi con người phải biết chờ đợi, quan sát và điều chỉnh bản thân. Thiên Hà Thủy thường được gắn với trí tuệ, sự linh hoạt và khả năng thích nghi trước biến chuyển của cuộc sống. Chính vì vậy, năm Bính Ngọ 2026 được xem là năm đề cao yếu tố cân bằng nội tâm, trí tuệ và sự tỉnh táo và phát triển bền vững thay vì vội vàng hay cực đoan.
                  </p>

                  <h2>2. Chọn màu phù hợp cho từng mệnh trong năm Bính Ngọ 2026</h2>
                  
                  <h3>2.1. Mệnh Kim: Giữ nhịp ổn định và bồi đắp nền tảng</h3>
                  <p>
                      Theo quy luật ngũ hành, Kim sinh Thủy. Khi bước vào năm Thiên Hà Thủy, người mệnh Kim có xu hướng “cho đi” nhiều năng lượng hơn bình thường. Điều này dễ thể hiện qua cảm giác mệt mỏi nếu làm việc quá sức hoặc phân tán quá nhiều mục tiêu cùng lúc. Năm Bính Ngọ 2026 phù hợp để người mệnh Kim:
                  </p>
                  <p>
                      - Củng cố nền tảng sẵn có<br>
                      - Tập trung vào chiều sâu thay vì mở rộng ồ ạt<br>
                      - Duy trì sự rõ ràng trong tư duy và quyết định
                  </p>
                  <p>
                      Những gam màu sáng nhẹ và trung tính như trắng, xám, bạc, vàng nhạt giúp người mệnh Kim duy trì sự vững vàng, tăng cảm giác an toàn và tập trung. Đây cũng là nhóm màu mang ý nghĩa thanh sạch, rõ ràng, rất phù hợp với tinh thần của người mệnh Kim trong giai đoạn cần củng cố nền móng lâu dài. Ngược lại, các màu nóng mạnh như đỏ tươi, cam đậm nên được sử dụng tiết chế trong cuộc sống hằng ngày để tránh tạo cảm giác áp lực hoặc tiêu hao năng lượng không cần thiết.
                  </p>
                  <img src="https://images.unsplash.com/photo-1516962215378-7fa2e137ae93?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" alt="Màu sắc mệnh Kim">
                  <p style="text-align: center; font-style: italic; font-size: 14px; color: #666; margin-top: -10px;">Những gam màu sáng nhẹ và trung tính giúp người mệnh Kim duy trì năng lượng vững vàng</p>

                  <h3>2.2. Mệnh Mộc: Đón nhận cơ hội và phát triển thuận tự nhiên</h3>
                  <p>
                      Trong mối quan hệ ngũ hành, Thủy sinh Mộc. Vì vậy, năm Bính Ngọ 2026 được xem là thời điểm tương đối thuận lợi cho người mệnh Mộc. Năng lượng Thiên Hà Thủy giống như mưa lành, giúp cây cối sinh trưởng một cách bền bỉ, không vội vàng nhưng chắc chắn, vì vậy đây là giai đoạn thích hợp để người mệnh Mộc:
                  </p>
                  <p>
                      - Bắt đầu những kế hoạch dài hạn<br>
                      - Học hỏi và tích lũy tri thức<br>
                      - Nuôi dưỡng cảm xúc tích cực
                  </p>
                  <p>
                      Các sắc độ màu xanh lá, xanh ngọc, xanh dương nhạt sẽ là màu sắc đại diện cho sinh khí và mang lại cảm giác thư thái phù hợp người mệnh Mộc giữ được sự tỉnh táo trong suy nghĩ và quyết định. Đây là yếu tố quan trọng khi đứng trước những cơ hội mới trong công việc hoặc các mối quan hệ xã hội. Người mệnh Mộc trong năm này nên hạn chế sử dụng màu trắng ánh kim, bởi Kim khắc Mộc, dễ tạo cảm giác bị kìm hãm hoặc phân tâm.
                  </p>
                  <img src="https://images.unsplash.com/photo-1497250681554-182375c80613?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" alt="Màu sắc mệnh Mộc">
                  <p style="text-align: center; font-style: italic; font-size: 14px; color: #666; margin-top: -10px;">Các sắc độ màu xanh lá và xanh dương giúp mang lại sinh khí cho người mệnh Mộc</p>

                  <h3>2.3. Mệnh Thủy: Đồng hành năng lượng và chú trọng điều hòa</h3>
                  <p>
                      Gặp năm cùng hành Thủy, người mệnh Thủy thường rơi vào trạng thái năng lượng dồi dào nhưng dễ dao động. Trong quan niệm xưa, khi rơi vào năm Thủy vượng thì nên tập trung vào các yếu tố như:
                  </p>
                  <p>
                      - Sự tiết chế để dẫn dắt dòng chảy trở nên ổn định có định hướng<br>
                      - Bình tĩnh và giữ tinh thần trong trái thái cân bằng thoải mái
                  </p>
                  <p>
                      Vì vậy, việc chọn màu sắc với người mệnh Thủy trong năm 2026 nên hướng đến sự cân bằng hơn là gia tăng thêm Thủy khí. Các màu như xanh dương, đen giúp cân bằng cảm xúc, tăng sự sáng suốt và giảm cảm giác bất an. Tuy nhiên, màu sắc nên được sử dụng ở mức vừa phải, tránh quá đậm hoặc quá nhiều trong cùng một không gian. Người mệnh Thủy nên hạn chế các gam vàng đậm, nâu đất, vì Thổ khắc Thủy, dễ tạo cảm giác nặng nề hoặc trì trệ.
                  </p>
                  <img src="https://images.unsplash.com/photo-1507525428034-b723cf961d3e?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" alt="Màu sắc mệnh Thủy">
                  <p style="text-align: center; font-style: italic; font-size: 14px; color: #666; margin-top: -10px;">Màu xanh dương giúp người mệnh Thủy tăng sự sáng suốt và giảm cảm giác bất an</p>

                  <h3>2.4. Mệnh Hỏa: Giảm bốc đồng và nuôi dưỡng sự hài hòa</h3>
                  <p>
                      Mặc dù năm mang tên Ngọ (thuộc Hỏa), nhưng nạp âm Thiên Hà Thủy lại tạo ra thế Thủy khắc Hỏa. Điều này khiến người mệnh Hỏa trong năm 2026 cần chú ý tiết chế cảm xúc, tránh nóng vội hoặc phản ứng quá nhanh trước các tình huống. Đây là năm nên lưu ý đến các yếu tố:
                  </p>
                  <p>
                      - Lắng nghe nhiều hơn<br>
                      - Suy xét kỹ trước khi quyết định<br>
                      - Ưu tiên sự hài hòa trong các mối quan hệ
                  </p>
                  <p>
                      Những gam màu hồng hay xanh lá (Mộc sinh Hỏa) sẽ giúp làm dịu năng lượng, tạo cảm giác cân bằng và dễ chịu hơn. Đây cũng là nhóm màu mang tính nuôi dưỡng, phù hợp với tinh thần “chậm mà chắc”. Trái lại, các màu đen, xanh nước biển đậm nên được sử dụng hạn chế để tránh làm gia tăng sự đối kháng năng lượng trong năm.
                  </p>
                  <img src="https://images.unsplash.com/photo-1520699697851-3dc68aa3a474?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" alt="Màu sắc mệnh Hỏa">
                  <p style="text-align: center; font-style: italic; font-size: 14px; color: #666; margin-top: -10px;">Màu hồng hoặc màu xanh lá sẽ giúp người mệnh Hỏa làm dịu năng lượng</p>

                  <h3>2.5. Mệnh Thổ: Củng cố sự vững vàng và ưu tiên ổn định</h3>
                  <p>
                      Cuối cùng, trong ngũ hành Thổ sẽ khắc Thủy. Vì vậy, khi bước vào năm Thiên Hà Thủy, người mệnh Thổ thường được nhìn nhận là năm:
                  </p>
                  <p>
                      - Cần sự thận trọng và tập trung vào việc<br>
                      - Ưu tiên giữ nhịp sống ổn định và chắc chắn, thay vì mở rộng hay thay đổi lớn.
                  </p>
                  <p>
                      Các gam màu mang sắc ấm như vàng nhạt, nâu, đỏ đất sẽ giúp tăng cảm giác ấm áp, vững vàng và an tâm. Ý nghĩa này cũng phù hợp với tinh thần “lấy bền làm gốc” vốn rất quen thuộc trong văn hóa Việt Nam.
                  </p>
                  <img src="https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" alt="Màu sắc mệnh Thổ">
                  <p style="text-align: center; font-style: italic; font-size: 14px; color: #666; margin-top: -10px;">Các sắc màu ấm như vàng nhạt, nâu, đỏ sẽ giúp tăng sự yên tâm cho người mệnh Thổ</p>
                  
                  <p style="margin-top: 30px;">
                      Chọn màu hợp mệnh trong năm Bính Ngọ 2026, nếu nhìn dưới góc độ văn hoá Việt, không phải là hành động mê tín hay can thiệp vào số phận. Đó là một cách tự điều chỉnh bản thân để sống hài hoà với tự nhiên, đúng với triết lý “thuận thiên” đã tồn tại từ lâu trong đời sống tinh thần người Việt.
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

                      <a href="#" class="related-news-item">
                          <img src="./assets/file_anh/family-reunion-during-chinese-new-year-2026-01-05-05-45-10-utc_2ef835bd26794c7f9ea833af616f3784.png" alt="Tin tức">
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