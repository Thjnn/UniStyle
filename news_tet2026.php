<?php
session_start();
include('./config/db.php');
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Những điều có thể bạn chưa biết về Tết Bính Ngọ 2026 - UniStyle</title>
  
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
              <h1 class="article-title">Những điều có thể bạn chưa biết về Tết Bính Ngọ 2026</h1>
              
              <div class="article-meta">
                  <span>Thứ Hai 05/01/2026</span> • <span>Bởi UniStyle Đời Sống</span>
              </div>

              <div class="article-content">
                  <p class="lead">
                      Tết Nguyên Đán là không chỉ là khoảnh khắc chuyển giao năm cũ và năm mới, mà còn là thời điểm mỗi người hướng về những giá trị tinh thần: khởi đầu, hy vọng và tái tạo năng lượng. Bước sang năm Bính Ngọ 2026, hãy cùng nhà UniStyle khám phá những bí mật văn hóa có thể bạn chưa biết và lựa chọn màu sắc phù hợp cho từng mệnh, để khởi đầu năm mới hanh thông và vững vàng.
                  </p>

                  <h2>Ý nghĩa năm Bính Ngọ 2026: Biểu tượng của sự bứt phá và kiên định</h2>
                  <p>
                      Trong hệ thống 12 con giáp, Ngọ (Ngựa) là linh vật đại diện cho sự trung thành, tự do, sức bền và tinh thần không ngừng tiến về phía trước. Hình ảnh con Ngựa trong văn hóa Á Đông gắn liền với những chuyến đi xa, sự chinh phục và ý chí vượt qua thử thách.
                  </p>
                  <p>
                      Năm 2026 là năm Bính Ngọ, trong đó:<br>
                      - Thiên can Bính thuộc hành Hỏa<br>
                      - Địa chi Ngọ cũng mang tính Hỏa
                  </p>
                  <p>
                      Sự kết hợp này tạo nên một năm Hỏa vượng, thường được gọi là hình tượng "Lộ Trung Chi Mã" - ngựa chạy giữa đường lớn. Đây là biểu trưng cho những con người không đứng yên, không chấp nhận an toàn tuyệt đối, mà sẵn sàng bước ra khỏi vùng quen thuộc để theo đuổi mục tiêu dài hạn.
                  </p>
                  <p>
                      So với năm 2025 (Ất Tỵ) mang sắc thái trầm lắng, chiến lược và thiên về quan sát, Bính Ngọ 2026 là năm của hành động rõ ràng, quyết đoán và tốc độ. Tuy nhiên, một điểm dễ bị hiểu sai là năm Ngựa chỉ khuyến khích "chạy nhanh". Trên thực tế, tinh thần của Ngọ nằm ở sự bền bỉ – khả năng duy trì nhịp độ ổn định trên chặng đường dài.
                  </p>
                  <p>
                      Trong học tập và kinh doanh, năm 2026 không đề cao sự vội vã nhất thời, mà tôn vinh bản lĩnh bền bỉ và khả năng đi đường dài. Vì vậy, cách con người chuẩn bị cho năm mới cũng dần thay đổi. Thay cho những lời cầu may đơn thuần, nhiều gia đình và doanh nghiệp hiện đại lựa chọn lập kế hoạch rõ ràng, làm mới không gian làm việc để “kích hoạt” năng lượng tích cực, đồng thời trao nhau những món quà mang giá trị truyền cảm hứng - như một lời nhắc về mục tiêu và hành trình phía trước.
                  </p>

                  <h2>Ngũ hành năm Bính Ngọ 2026: Chìa khóa cân bằng năng lượng cho một năm bứt phá bền vững</h2>
                  <p>
                      Theo học thuyết Ngũ hành, năm Bính Ngọ 2026 mang Hỏa khí vượng, tượng trưng cho nhiệt huyết, hành động và khát vọng vươn lên mạnh mẽ. Khi được dẫn dắt đúng cách, nguồn năng lượng Hỏa này sẽ trở thành động lực thúc đẩy sáng tạo, tinh thần dấn thân và khả năng tạo bước ngoặt trong học tập lẫn sự nghiệp. Ngược lại, nếu thiếu sự cân bằng, Hỏa quá thịnh dễ khiến con người rơi vào trạng thái nóng vội, căng thẳng và nhanh chóng cạn kiệt năng lượng.
                  </p>

                  <h3>Quy luật Ngũ hành tương sinh - tương khắc trong năm Bính Ngọ</h3>
                  <p>
                      - Hỏa sinh Thổ: Lửa hun đúc thành đất, biểu trưng cho việc hành động đúng hướng sẽ tạo nên nền tảng vững chắc. Hỏa không chỉ là khởi động, mà còn nuôi dưỡng sự ổn định lâu dài.<br>
                      - Mộc sinh Hỏa: Gỗ sinh lửa, giống như tri thức và ý tưởng chính là nhiên liệu cho mọi hành động. Học hỏi liên tục sẽ giúp Hỏa khí được duy trì bền bỉ, thay vì bùng cháy rồi lụi tàn.<br>
                      - Hỏa khắc Kim: Lửa làm tan kim loại, nhắc nhở con người cần tiết chế sự cứng nhắc, đối đầu hoặc áp đặt quan điểm trong các mối quan hệ và quyết định quan trọng.<br>
                      - Thủy khắc Hỏa: Nước dập lửa, tượng trưng cho những cảm xúc tiêu cực, lo âu hay hoài nghi có thể làm suy giảm nhiệt huyết nếu không được kiểm soát kịp thời.
                  </p>

                  <img src="./assets/file_anh/nguhanh.png" alt="Ngũ hành tương sinh tương khắc">
                  <p style="text-align: center; font-style: italic; font-size: 14px; color: #666; margin-top: -10px;">Hiểu về quy luật Ngũ hành tương sinh - tương khắc sẽ giúp cân bằng năng lượng</p>

                  <h3>Ai sẽ dễ thuận lợi trong năm Bính Ngọ 2026?</h3>
                  <p>
                      Năm 2026 đặc biệt ủng hộ những người:<br>
                      - Biết nuôi dưỡng tri thức và ý tưởng (Mộc) để chuyển hóa thành hành động cụ thể (Hỏa)<br>
                      - Biết xây dựng nền tảng vững chắc (Thổ) để theo đuổi mục tiêu dài hạn<br>
                      - Tránh đưa ra quyết định bốc đồng, cảm tính hoặc quá đối đầu trong những thời điểm then chốt.
                  </p>
                  <p>
                      Khi hiểu và vận dụng đúng quy luật Ngũ hành, năm Bính Ngọ 2026 sẽ không chỉ là một năm “chạy nhanh”, mà là hành trình tiến xa với nhịp độ bền bỉ và tỉnh táo.
                  </p>

                  <h2>Chọn màu hợp mệnh cho năm mới: Thu hút tài lộc và sự bền vững</h2>
                  <p>
                      Ứng dụng màu sắc trong phong thủy không mang tính mê tín, mà là cách điều hòa năng lượng và tạo tác động tích cực lên tâm lý. Với Hỏa khí chủ đạo của năm Bính Ngọ, việc chọn màu sắc cho trang phục, vật dụng cá nhân, góc làm việc hay quà Tết đầu năm cần sự cân nhắc để vừa thúc đẩy hành động, vừa giữ được sự ổn định. Theo nguyên lý Ngũ hành tương sinh (Hỏa sinh Thổ), những gam màu chủ đạo cho năm 2026 bao gồm:
                  </p>

                  <h3>Nhóm màu Hỏa - Tương hợp, kích hoạt động lực và khát vọng</h3>
                  <p>
                      Là nhóm màu bản mệnh của năm Bính Ngọ, các gam màu mệnh Hỏa như: đỏ, cam, tím sẽ đại diện cho năng lượng, cảm hứng và sự tự tin. Những sắc đỏ mận, cam đất hay tím trầm giúp:
                  </p>
                  <p>
                      - Tăng khả năng tập trung và tinh thần chủ động<br>
                      - Khơi gợi sáng tạo, dám nghĩ dám làm<br>
                      - Truyền cảm hứng hành động ngay từ những ngày đầu năm
                  </p>
                  <p>
                      Một cây bút ký lông bi Parker Sonnet X-Red GT màu đỏ, hay cuốn sổ tay tông tím không chỉ mang ý nghĩa may mắn, mà còn trở thành “điểm kích hoạt” tinh thần trong học tập và công việc.
                  </p>

                  <img src="https://images.unsplash.com/photo-1585336261022-680e295ce3fe?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" alt="Bút đỏ Parker">
                  <p style="text-align: center; font-style: italic; font-size: 14px; color: #666; margin-top: -10px;">Bút ký lông bi cao cấp tông đỏ nổi bật kích hoạt tinh thần năm mới</p>

                  <h3>Nhóm màu Thổ - Tương sinh, nuôi dưỡng sự bền bỉ</h3>
                  <p>
                      Theo quy luật Hỏa sinh Thổ, lửa chỉ thực sự phát huy giá trị khi tạo ra nền tảng vững chắc. Các gam màu Thổ như: nâu - vàng đất - be đóng vai trò “neo năng lượng”, giúp con người không bị cuốn theo nhịp độ quá nhanh của năm Ngựa. Nhóm màu này mang lại cảm giác:
                  </p>
                  <p>
                      - Vững chãi, ổn định<br>
                      - Đáng tin cậy và an tâm<br>
                      - Phù hợp với mục tiêu dài hạn và chiến lược bền vững
                  </p>
                  <p>
                      Nếu màu đỏ giúp bạn khởi đầu mạnh mẽ, thì màu nâu và vàng đất chính là yếu tố giúp bạn kiên trì đi đến đích. Vì vậy, đầu năm 2026, nhiều người sẽ có xu hướng lựa chọn các sản phẩm như sổ tay, bút kí, bút viết hay các vật dụng văn phòng khác có mang các gam màu trên để thu hút, những điều may mắn và thuận lợi cho sự nghiệp tương lai.
                  </p>

                  <h2>Tinh thần "Ngựa chiến" trong sản phẩm Tết 2026: Khi người tiêu dùng chọn biểu tượng của sự bền bỉ</h2>
                  <p>
                      Bước sang năm Bính Ngọ 2026, hình tượng Ngựa chiến không chỉ xuất hiện trong văn hóa hay phong thủy, mà còn trở thành nguồn cảm hứng rõ nét trong thiết kế sản phẩm và xu hướng tiêu dùng dịp Tết. Người tiêu dùng hiện đại không còn mua sắm đơn thuần vì hình thức, mà ngày càng quan tâm đến ý nghĩa biểu trưng, câu chuyện đằng sau sản phẩm và giá trị tinh thần mà món đồ ấy mang lại cho hành trình cá nhân.
                  </p>
                  <p>
                      Tinh thần của Ngựa trong năm Bính Ngọ không nằm ở sự hiếu thắng, mà ở bền bỉ - kiên định - không bỏ cuộc giữa đường. Chính vì vậy, các sản phẩm mang hình ảnh hoặc cảm hứng từ Ngựa được ưa chuộng như:
                  </p>
                  <p>
                      - Móc khóa hình Ngựa: nhỏ gọn nhưng giàu biểu trưng, thường được xem như vật "hộ thân" mang thông điệp tiến lên và giữ vững mục tiêu.<br>
                      - Thú bông, quà trang trí hình Ngựa: mềm mại nhưng không yếu đuối, phù hợp làm quà Tết gửi gắm lời chúc vững vàng, nhất là cho người trẻ, học sinh - sinh viên.<br>
                      - Thời trang lấy cảm hứng từ Ngựa: áo, phụ kiện mang họa tiết ngựa, gam màu Hỏa - Thổ thể hiện tinh thần tự do, dịch chuyển và bản lĩnh cá nhân.
                  </p>

                  <img src="https://images.unsplash.com/photo-1583485088034-697b5a626507?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" alt="Bút ký sang trọng">
                  <p style="text-align: center; font-style: italic; font-size: 14px; color: #666; margin-top: -10px;">Bút cũng là sản phẩm mang tinh thần "ngựa chiến" bền bỉ năm 2026</p>

                  <p>
                      Đặc biệt, trong nhóm quà tặng mang tính ứng dụng cao, bút ký và văn phòng phẩm đang trở thành lựa chọn nổi bật. Một cây bút không chỉ là công cụ viết, mà còn là biểu tượng của tri thức, sự kiên trì và hành trình đi đường dài điều này rất tương đồng với tinh thần Ngựa chiến của năm 2026.
                  </p>
                  <p>
                      Với định hướng phát triển bền vững và gắn bó cùng nhiều thế hệ người Việt, UniStyle mang đến những dòng bút ký, sổ tay và văn phòng phẩm có thiết kế tinh giản, màu sắc hài hòa. Mỗi sản phẩm không chỉ phục vụ nhu cầu sử dụng hằng ngày, mà còn trở thành món quà Tết mang thông điệp: viết tiếp hành trình của chính mình bằng sự bền bỉ và tri thức.
                  </p>
                  <p>
                      Trong bối cảnh người tiêu dùng ngày càng đề cao giá trị tinh thần, những sản phẩm mang "tinh thần Ngựa chiến" từ hình tượng, chất liệu đến câu chuyện thương hiệu sẽ tiếp tục là xu hướng được lựa chọn trong dịp Tết Bính Ngọ 2026.
                  </p>
                  
                  <div style="margin-top: 30px; display: flex; gap: 10px; flex-wrap: wrap;">
                      <span class="material-symbols-outlined" style="color: #666;">sell</span>
                      <span style="background: #eaf3fa; color: #05a; padding: 5px 12px; border-radius: 15px; font-size: 13px; font-weight: 500;">Bút ký</span>
                      <span style="background: #eaf3fa; color: #05a; padding: 5px 12px; border-radius: 15px; font-size: 13px; font-weight: 500;">Tết 2026</span>
                      <span style="background: #eaf3fa; color: #05a; padding: 5px 12px; border-radius: 15px; font-size: 13px; font-weight: 500;">Tết Bính Ngọ</span>
                      <span style="background: #eaf3fa; color: #05a; padding: 5px 12px; border-radius: 15px; font-size: 13px; font-weight: 500;">Văn phòng phẩm</span>
                  </div>

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
                      <a href="news_valentine.php" class="related-news-item">
                          <img src="./assets/file_anh/loseup-of-valentine-39-s-day-calendar-reminder-2025-02-09-22-57-20-utc_5ad22c8529364b45bf6f871166be1b3a.png" alt="Tin tức">
                          <div class="related-news-title">Chọn quà Valentine như thế nào để duy trì mối quan hệ</div>
                      </a>

                      <a href="news_top5but.php" class="related-news-item">
                          <img src="./assets/file_anh/businesswoman-planning-work_a8bb09d0b76c4c7e972d46e4c2500c4f.png" alt="Tin tức">
                          <div class="related-news-title">Top 5 dòng bút văn phòng ngòi to viết đẹp, êm tay</div>
                      </a>

                      <a href="news_mauhopmenh.php" class="related-news-item">
                          <img src="./assets/file_anh/family-reunion-during-chinese-new-year-2026-01-05-05-45-10-utc_2ef835bd26794c7f9ea833af616f3784.png" alt="Tin tức">
                          <div class="related-news-title">Chọn màu hợp mệnh trong năm Bính Ngọ 2026 cho góc học tập</div>
                      </a>
                      
                      <a href="#" class="related-news-item">
                          <img src="./assets/file_anh/cute-little-kids-studying-in-a-group-2024-10-18-03-40-30-utc_9f0d248b36de4523b673935db27492db.png" alt="Tin tức">
                          <div class="related-news-title">Top 5 dòng bút Quick Dry/Super Quick Dry dành cho bé</div>
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