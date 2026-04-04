<!doctype html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>FAQ - Câu hỏi thường gặp | UniStyle</title>
    <link
      href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;800&display=swap"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,0..1"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
    />

    <link
      rel="shortcut icon"
      href="assets/file_anh/0c4690d7-3599-4de4-a0a4-841817ead1c0.png"
    />

    <link rel="stylesheet" href="./assets/css/style.css" />
    <link rel="stylesheet" href="./assets/css/FAQ.css" />
    <link rel="stylesheet" href="./assets/css/reposive.css" />
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

    <section class="faq-section">
      <div class="faq-container">
        <div class="faq-header-title">
          <div class="icon-question">?</div>
          <h1>Câu hỏi thường gặp.</h1>
        </div>

        <div class="faq-grid">
          <div class="faq-column-left">
            <div class="faq-category">
              <h2 class="faq-category-title">01. Đặt hàng & Thanh toán</h2>
              <div class="faq-item">
                <p class="faq-question">Khi nào tôi nhận được đơn hàng?</p>
                <p class="faq-answer">
                  Khi đặt hàng, ngày giao hàng dự kiến sẽ được ghi rõ. Sau khi
                  đơn hàng được đặt, thời gian giao hàng cũng sẽ được thông báo
                  trong email xác nhận đơn hàng. Bạn có thể theo dõi mã vận đơn
                  trực tiếp trong mục "Đơn hàng của tôi".
                </p>
              </div>
              <div class="faq-item">
                <p class="faq-question">
                  UniStyle hỗ trợ những hình thức thanh toán nào?
                </p>
                <p class="faq-answer">
                  Chúng tôi hiện đang hỗ trợ thanh toán khi nhận hàng (COD),
                  thanh toán qua thẻ ngân hàng (Visa/Mastercard/JCB) và các loại
                  ví điện tử phổ biến như ShopeePay, MoMo, ZaloPay để mang lại
                  sự tiện lợi nhất cho bạn.
                </p>
              </div>
            </div>

            <div class="faq-category">
              <h2 class="faq-category-title">03. Quy tắc kinh doanh</h2>
              <div class="faq-item">
                <p class="faq-question">
                  Cam kết về chất lượng sản phẩm của UniStyle là gì?
                </p>
                <p class="faq-answer">
                  UniStyle cam kết 100% sản phẩm bán ra đều là hàng chính hãng,
                  có nguồn gốc xuất xứ minh bạch. Chúng tôi nói KHÔNG với hàng
                  giả, hàng nhái. Nếu phát hiện sản phẩm kém chất lượng, chúng
                  tôi sẽ hoàn tiền 200% giá trị đơn hàng.
                </p>
              </div>
              <div class="faq-item">
                <p class="faq-question">
                  Chính sách bán sỉ (bán buôn) như thế nào?
                </p>
                <p class="faq-answer">
                  Chúng tôi có chính sách chiết khấu cực kỳ ưu đãi cho khách
                  hàng mua số lượng lớn, các trường học, câu lạc bộ hoặc doanh
                  nghiệp. Vui lòng liên hệ trực tiếp qua Hotline để nhận bảng
                  báo giá sỉ tốt nhất.
                </p>
              </div>
            </div>

            <div class="faq-category">
              <h2 class="faq-category-title">05. Nhà cung cấp & Đối tác</h2>
              <div class="faq-item">
                <p class="faq-question">
                  Nguồn gốc văn phòng phẩm của UniStyle từ đâu?
                </p>
                <p class="faq-answer">
                  Chúng tôi tự hào là đối tác phân phối chính thức của các
                  thương hiệu văn phòng phẩm và balo uy tín hàng đầu trong và
                  ngoài nước như:
                  <strong
                    >Thiên Long, Hồng Hà, Bến Nghé, Campus, Deli, Pentel, Casio,
                    Camelia Brand...</strong
                  >
                  đảm bảo tiêu chuẩn an toàn và chất lượng cho học sinh, sinh
                  viên.
                </p>
              </div>
              <div class="faq-item">
                <p class="faq-question">
                  Tôi muốn trở thành nhà cung cấp cho UniStyle thì làm sao?
                </p>
                <p class="faq-answer">
                  UniStyle luôn hoan nghênh các đối tác có sản phẩm chất lượng
                  tốt. Quý công ty vui lòng gửi Hồ sơ năng lực (Profile) và Báo
                  giá sản phẩm vào email: <strong>partner@unistyle.vn</strong>.
                  Bộ phận Thu mua của chúng tôi sẽ xem xét và liên hệ lại trong
                  vòng 3 ngày làm việc.
                </p>
              </div>
            </div>
          </div>

          <div class="faq-column-right">
            <div class="faq-category">
              <h2 class="faq-category-title">02. Giao hàng & Vận chuyển</h2>
              <div class="faq-item">
                <p class="faq-question">
                  Chính sách giao hàng và Phí vận chuyển
                </p>
                <p class="faq-answer">
                  Chúng tôi cam kết giao hàng nhanh và an toàn. Thời gian xử lý
                  đơn: 1-2 ngày làm việc. Thời gian giao dự kiến: Nội thành 1-3
                  ngày; Tỉnh thành khác 3-7 ngày.<br />Miễn phí giao hàng cho
                  đơn từ 500.000đ trở lên. Phí cụ thể đối với đơn hàng nhỏ hơn
                  sẽ hiển thị ở bước Thanh toán.
                </p>
              </div>
              <div class="faq-item">
                <p class="faq-question">
                  Tôi có thể hủy đơn hàng bằng cách nào?
                </p>
                <p class="faq-answer">
                  Nếu đơn hàng chưa được chuyển cho đơn vị vận chuyển, bạn có
                  thể tự hủy ngay trong mục "Đơn hàng của tôi". Nếu đơn đã được
                  gửi đi, vui lòng liên hệ Tổng đài chăm sóc khách hàng để được
                  hỗ trợ kiểm tra và hủy đơn.
                </p>
              </div>
            </div>

            <div class="faq-category">
              <h2 class="faq-category-title">04. Đổi trả - Hoàn tiền</h2>
              <div class="faq-item">
                <p class="faq-question">
                  Chính sách đổi trả của UniStyle ra sao?
                </p>
                <p class="faq-answer">
                  Chúng tôi hỗ trợ đổi trả trong vòng 7 ngày kể từ khi nhận hàng
                  đối với sản phẩm bị lỗi kỹ thuật, hư hỏng do vận chuyển hoặc
                  giao sai mẫu. Điều kiện: Sản phẩm mới 100%, chưa qua sử dụng,
                  chưa giặt rửa và còn nguyên tem mác.
                </p>
              </div>
              <div class="faq-item">
                <p class="faq-question">
                  Tôi sẽ nhận lại tiền hoàn trong bao lâu?
                </p>
                <p class="faq-answer">
                  Sau khi kho nhận lại hàng và kiểm tra tình trạng thành công,
                  tiền sẽ được hoàn lại vào tài khoản ngân hàng hoặc ví điện tử
                  của bạn trong vòng từ 3-5 ngày làm việc tùy thuộc vào ngân
                  hàng thụ hưởng.
                </p>
              </div>
            </div>

            <div class="faq-category">
              <h2 class="faq-category-title">06. Hợp tác sự kiện</h2>
              <div class="faq-item">
                <p class="faq-question">
                  UniStyle có tài trợ cho các sự kiện của học sinh, sinh viên
                  không?
                </p>
                <p class="faq-answer">
                  Rất sẵn lòng! UniStyle luôn mong muốn đồng hành cùng các hoạt
                  động phát triển của giới trẻ. Chúng tôi thường xuyên có các
                  gói tài trợ (tiền mặt hoặc hiện vật như balo, voucher, đồ dùng
                  học tập) cho các chương trình chào tân sinh viên, sự kiện câu
                  lạc bộ, chiến dịch Mùa hè xanh...
                </p>
              </div>
              <div class="faq-item">
                <p class="faq-question">
                  Làm thế nào để mời UniStyle hợp tác sự kiện?
                </p>
                <p class="faq-answer">
                  Các Câu lạc bộ / Ban tổ chức vui lòng gửi Hồ sơ mời tài trợ
                  (Sponsorship Proposal) chi tiết về email
                  <strong>event@unistyle.vn</strong> trước khi sự kiện diễn ra
                  ít nhất 20 ngày. Đội ngũ Truyền thông & Marketing của chúng
                  tôi sẽ trực tiếp xem xét và phản hồi đến bạn sớm nhất.
                </p>
              </div>
            </div>
          </div>
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
    <!-- footer -->
    <footer class="footer">
      <div class="footer-newsletter">
        <div class="newsletter-text">
          <h2>Sign up for offers</h2>
          <p>
            Sign up for our newsletter to receive exclusive offers & discounts!
          </p>
        </div>
        <div class="newsletter-form">
          <input type="email" placeholder="Your email address..." />
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
              alt=""
              style="height: 50px; width: 50px; margin-bottom: 20px"
            />
            <br />UniStyle
          </h2>
          <p>
            Nếu bạn có bất kỳ thắc mắc nào, vui lòng liên hệ với chúng tôi theo
            địa chỉ sau: <span>support@example.</span>
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
            <li><a href="#">Tuyển dụng</a></li>
            <li><a href="#">Về chúng tôi</a></li>
            <li><a href="#">Quy tắc kinh doanh</a></li>
            <li><a href="#">Hợp tác sự kiện</a></li>
            <li><a href="#">Nhà cung cấp</a></li>
            <li><a href="#">Chương trình cộng tác viên</a></li>
          </ul>
        </div>

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
