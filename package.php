<?php
session_start();

// XÓA SẢN PHẨM
if (isset($_GET['del'])) {
    $index = $_GET['del'];
    unset($_SESSION['cart'][$index]);
    $_SESSION['cart'] = array_values($_SESSION['cart']);
}

// TĂNG GIẢM SỐ LƯỢNG
if (isset($_GET['action']) && isset($_GET['index'])) {
    $i = $_GET['index'];

    if ($_GET['action'] == "plus") {
        $_SESSION['cart'][$i]['soluong']++;
    }

    if ($_GET['action'] == "minus") {
        $_SESSION['cart'][$i]['soluong']--;
        if ($_SESSION['cart'][$i]['soluong'] <= 0) {
            unset($_SESSION['cart'][$i]);
            $_SESSION['cart'] = array_values($_SESSION['cart']);
        }
    }
}

include("./config/db.php");

$madanhmuc = 0;

// lấy danh mục từ sản phẩm đầu tiên trong giỏ
if (!empty($_SESSION['cart'])) {
    $masp = $_SESSION['cart'][0]['masp'];

    $sql_dm = "SELECT madanhmuc FROM sanpham WHERE MaSP = $masp";
    $res_dm = mysqli_query($conn, $sql_dm);
    $row_dm = mysqli_fetch_assoc($res_dm);

    $madanhmuc = $row_dm['madanhmuc'];
}

// lấy sản phẩm cùng loại
$sql_lq = "SELECT * FROM sanpham 
           WHERE madanhmuc = $madanhmuc 
           LIMIT 5";

$result_lq = mysqli_query($conn, $sql_lq);
?>


<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Package</title>
    <!-- embed link icon  -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,0..1" />
    <!--embed Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <!-- logo web -->
    <link rel="shortcut icon" href="assets/file_anh/0c4690d7-3599-4de4-a0a4-841817ead1c0.png" />
    <!-- css -->
    <link rel="stylesheet" href="./assets/css/package.css" />
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
                    <a href="index.php"><img
                            src="./assets/file_anh/0c4690d7-3599-4de4-a0a4-841817ead1c0.png"
                            alt="" /></a>
                </div>
                <nav>
                    <ul>
                        <li><a href="index.php">Trang chủ</a></li>
                        <li><a href="shop.php">Cửa hàng</a></li>
                        <li><a href="contact.php">Liên hệ</a></li>
                        <li><a href="FAQ.php">FAQ</a></li>
                        <li><a href="aboutus.php">Về chúng tôi</a></li>
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
                    <li><a href="contact.php" class="mobile-menu-link">Liên hệ</a></li>
                    <li><a href="FAQ.php" class="mobile-menu-link">FAQ</a></li>
                    <li>
                        <a href="aboutus.php" class="mobile-menu-link">Về chúng tôi</a>
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

    <!-- khi có hàng -->
    <section class="cart-page">
        <div class="cart-left">

            <?php if (empty($_SESSION['cart'])) { ?>
                <div class="cart-box">
                    <h1>Giỏ hàng</h1>
                    <p>Chưa có sản phẩm - <a href="shop.php">Mua ngay</a></p>
                </div>
            <?php } else { ?>

                <?php
                $total = 0;
                foreach ($_SESSION['cart'] as $index => $item):
                    $thanhtien = (int)$item['gia'] * (int)$item['soluong'];
                    $total += $thanhtien;
                ?>

                    <div class="cart-item">
                        <img src="assets/file_anh/San_Pham/<?php echo $item['hinh']; ?>" class="cart-img" />

                        <div class="cart-info">
                            <h3><?php echo $item['tensp']; ?></h3>
                            <p class="variant"><?php echo $item['size']; ?></p>
                        </div>

                        <div class="cart-price">
                            <span class="price"><?php echo number_format($item['gia']); ?>đ</span>
                        </div>

                        <div class="cart-qty">
                            <a href="?action=minus&index=<?php echo $index; ?>">-</a>
                            <input type="text" value="<?php echo $item['soluong']; ?>" />
                            <a href="?action=plus&index=<?php echo $index; ?>">+</a>
                        </div>

                        <a href="?del=<?php echo $index; ?>" style="color:red">Xóa</a>
                    </div>

                <?php endforeach; ?>

        </div>

        <!-- RIGHT CHECKOUT -->
        <div class="cart-right">
            <h3>Tổng tiền <span><?php echo number_format($total); ?>đ</span></h3>

            <textarea placeholder="Ghi chú đơn hàng"></textarea>

            <button class="checkout-btn">Tiến hành đặt hàng</button>
        </div>
    <?php } ?>
    </section>
    <!-- RELATED PRODUCT -->
    <section class="related-product">
        <h2>Sản phẩm cùng loại</h2>

        <div class="product-grid">

            <?php while ($sp = mysqli_fetch_assoc($result_lq)) { ?>

                <div class="product-card">
                    <div class="product-img">
                        <img src="assets/file_anh/San_Pham/<?php echo $sp['Hinh']; ?>" />
                    </div>

                    <div class="product-info">
                        <div class="product-tag">
                            <span class="new">👍 New</span>
                        </div>

                        <h3 class="product-name">
                            <?php echo $sp['TenSP']; ?>
                        </h3>

                        <div class="price">
                            <?php echo number_format($sp['GiaBan']); ?>đ
                        </div>

                        <div class="old-price">
                            <?php echo number_format($sp['GiaBan'] * 1.3); ?>đ
                            <span class="discount">-30%</span>
                        </div>
                    </div>
                </div>

            <?php } ?>

        </div>
    </section>
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