<!doctype html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Về chúng tôi - UniStyle</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

    <!-- logo web -->
    <link rel="shortcut icon" href="assets/file_anh/0c4690d7-3599-4de4-a0a4-841817ead1c0.png" />
    <link rel="stylesheet" href="./assets/css/style.css" />
    <link rel="stylesheet" href="./assets/css/reposive.css" />
    <link rel="stylesheet" href="./assets/css/aboutus.css" />
</head>

<body>
    <header>
        <div class="container">
            <div class="header-content">
                <div class="menu-toggle">
                    <span class="material-symbols-outlined">menu</span>
                </div>
                <div class="logo">
                    <a href="index.php"><img src="./assets/file_anh/0c4690d7-3599-4de4-a0a4-841817ead1c0.png"
                            alt="" /></a>
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
                                <div class="submenu-banner">
                                    <a href="#!">
                                        <img src="./assets/file_anh/1920_x_600___cta___6_.webp"
                                            alt="Back To School Sale" />
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

    <!-- Mobile Menu Overlay -->
    <div class="mobile-menu-overlay" id="mobileMenuOverlay">
        <div class="mobile-menu-content">
            <div class="mobile-menu-header">
                <div class="logo">
                    <img src="./assets/file_anh/0c4690d7-3599-4de4-a0a4-841817ead1c0.png" alt="" />
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

    <!-- HERO -->
    <section class="hero about-hero">
        <div class="hero-overlay"></div>

        <div class="hero-content">
            <h1>Phong cách học đường hiện đại</h1>

            <p>
                UniStyle mang đến các sản phẩm balo, giày và văn phòng phẩm chất
                lượng, giúp học sinh – sinh viên thể hiện cá tính riêng, tự tin hơn
                trong học tập và sinh hoạt hằng ngày trong môi trường hiện đại.
            </p>

            <button class="shop-now-btn" onclick="location.href = 'shop.php'">
                Khám phá sản phẩm
            </button>
        </div>
    </section>

    <div class="about-container">
        <!-- GIỚI THIỆU -->
        <div class="about-row">
            <img src="./assets/file_anh/0c4690d7-3599-4de4-a0a4-841817ead1c0.png" />

            <div class="about-text">
                <h2>Giới thiệu về UniStyle</h2>

                <p>
                    UniStyle là cửa hàng trực tuyến chuyên cung cấp balo, giày và các
                    sản phẩm văn phòng phẩm dành cho học sinh – sinh viên. Chúng tôi
                    không chỉ tập trung vào việc cung cấp sản phẩm mà còn mong muốn mang
                    đến giải pháp toàn diện cho nhu cầu học tập và sinh hoạt hằng ngày.
                </p>

                <p>
                    Với định hướng phát triển bền vững, UniStyle luôn lựa chọn sản phẩm
                    dựa trên tiêu chí chất lượng, độ bền và tính thẩm mỹ. Chúng tôi liên
                    tục cập nhật xu hướng mới để giúp khách hàng dễ dàng bắt kịp phong
                    cách hiện đại và năng động.
                </p>
            </div>
        </div>

        <!-- SẢN PHẨM -->
        <div class="about-row">
            <div class="about-text">
                <h2>Sản phẩm của chúng tôi</h2>

                <p>
                    UniStyle cung cấp đa dạng các sản phẩm phục vụ học tập như balo,
                    giày thể thao, sách vở, bút viết, dụng cụ học tập và nhiều loại văn
                    phòng phẩm tiện ích khác. Tất cả sản phẩm đều được kiểm tra kỹ lưỡng
                    trước khi đến tay khách hàng.
                </p>

                <p>
                    Chúng tôi luôn đảm bảo sản phẩm có thiết kế đẹp mắt, phù hợp với xu
                    hướng học đường hiện đại, đồng thời có mức giá hợp lý để phù hợp với
                    học sinh và sinh viên trên toàn quốc.
                </p>
            </div>

            <img src="./assets/file_anh/35ad1d7d3ff101eabe99456c6e268bb1.jpg" />
        </div>

        <!-- TRẢI NGHIỆM -->
        <div class="about-row">
            <img src="./assets/file_anh/634fc0b4b0881c14e1110d479294e48e.jpg" />

            <div class="about-text">
                <h2>Trải nghiệm mua sắm</h2>

                <p>
                    Website UniStyle được thiết kế với giao diện đơn giản, dễ sử dụng,
                    giúp người dùng có thể nhanh chóng tìm kiếm sản phẩm phù hợp với nhu
                    cầu. Chỉ với vài thao tác, bạn có thể xem chi tiết sản phẩm, thêm
                    vào giỏ hàng và tiến hành đặt hàng.
                </p>

                <p>
                    Ngoài ra, hệ thống còn hỗ trợ theo dõi đơn hàng, giúp khách hàng dễ
                    dàng kiểm tra tình trạng giao hàng và quản lý quá trình mua sắm một
                    cách thuận tiện, nhanh chóng và hiệu quả.
                </p>
            </div>
        </div>

        <!-- CAM KẾT -->
        <div class="about-row">
            <div class="about-text">
                <h2>Cam kết của chúng tôi</h2>

                <p>
                    UniStyle luôn đặt chất lượng sản phẩm và sự hài lòng của khách hàng
                    lên hàng đầu. Chúng tôi cam kết cung cấp sản phẩm đúng mô tả, đảm
                    bảo nguồn gốc rõ ràng và đáp ứng nhu cầu sử dụng thực tế.
                </p>

                <p>
                    Bên cạnh đó, chúng tôi luôn hỗ trợ khách hàng một cách nhanh chóng,
                    tận tình và chuyên nghiệp. Mọi phản hồi đều được tiếp nhận và xử lý
                    nhằm mang lại trải nghiệm tốt nhất cho người dùng.
                </p>
            </div>

            <img src="./assets/file_anh/9ce911167feb3af9f0b6eed5739a70f0.jpg" />
        </div>
        <section class="s">
            <div class="section-header">
                <h2 class="section-title">
                    Nhà <span class="featured">Cung Cấp</span>
                </h2>
            </div>
            <div class="supplier-logos">
                <div class="supplier-card">
                    <img src="./assets/file_anh/images.png" />
                </div>
                <div class="supplier-card">
                    <img src="./assets/file_anh/Logo_thienlong.png" />
                </div>

                <div class="supplier-card">
                    <img src="./assets/file_anh/Hong_Ha_Shipbuilding_logo.png" />
                </div>

                <div class="supplier-card-steadtler">
                    <img src="assets/file_anh/staedtler.png" />
                </div>

                <div class="supplier-card-stabilo">
                    <img src="assets/file_anh/stabilo.jpg" />
                </div>
            </div>
        </section>
        <!-- THỐNG KÊ -->
        <div class="stats">
            <div class="stat-item">
                <h3>500+</h3>
                <p>
                    Sản phẩm đa dạng, đáp ứng đầy đủ nhu cầu học tập và làm việc của học
                    sinh – sinh viên.
                </p>
            </div>

            <div class="stat-item">
                <h3>1000+</h3>
                <p>
                    Khách hàng đã tin tưởng và lựa chọn UniStyle trong quá trình học tập
                    của mình.
                </p>
            </div>

            <div class="stat-item">
                <h3>24/7</h3>
                <p>
                    Hỗ trợ khách hàng liên tục, sẵn sàng giải đáp mọi thắc mắc nhanh
                    chóng.
                </p>
            </div>
        </div>

        <!-- map -->
        <div class="map-box">
            <h3>Bản đồ cửa hàng</h3>

            <iframe src="https://www.google.com/maps?q=16 Thiên Hộ Vương, Mỹ Tho&output=embed" width="100%" height="300"
                style="border: 0">
            </iframe>
        </div>
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
                    <img src="./assets/file_anh/0c4690d7-3599-4de4-a0a4-841817ead1c0.png" alt="" />
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