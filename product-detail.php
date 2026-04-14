<?php
session_start();

// ================== THÊM GIỎ HÀNG ==================
if (isset($_POST['addcart'])) {

    $item = [
        "masp" => (int)$_POST['masp'],
        "tensp" => $_POST['tensp'],
        "gia" => (int)$_POST['gia'],
        "hinh" => $_POST['hinh'],
        "size" => $_POST['size'],
        "soluong" => (int)$_POST['soluong']
    ];

    $_SESSION['cart'][] = $item;
}

// ================== KẾT NỐI DB ==================
include("./config/db.php");
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// ================== LẤY SẢN PHẨM ==================
$sql = "SELECT sp.*, dm.tendanhmuc, km.GiamGia
        FROM sanpham sp
        LEFT JOIN danhmuc dm ON sp.madanhmuc = dm.madanhmuc
        LEFT JOIN khuyenmai km ON sp.MaKhuyenMai = km.MaKhuyenMai
        WHERE sp.MaSP = $id";

$result = mysqli_query($conn, $sql);
$sp = mysqli_fetch_assoc($result);

if (!$sp) {
    echo "Không tìm thấy sản phẩm";
    exit;
}

// ================== GIÁ ==================
$gia = $sp['GiaBan'];
$gia_giam = ($sp['GiamGia']) ? $gia - ($gia * $sp['GiamGia'] / 100) : $gia;

// ================== BIẾN THỂ ==================
$sql_bt = "SELECT * FROM bienthe_sanpham WHERE madanhmuc = " . $sp['madanhmuc'];
$result_bt = mysqli_query($conn, $sql_bt);

// ================== LIÊN QUAN ==================
$sql_lq = "SELECT * FROM sanpham 
          WHERE madanhmuc = " . $sp['madanhmuc'] . " 
          AND MaSP != $id LIMIT 4";
$result_lq = mysqli_query($conn, $sql_lq);

if (isset($_POST['buyNow'])) {

    $item = [
        "masp" => (int)$_POST['masp'],
        "tensp" => $_POST['tensp'],
        "gia" => (int)$_POST['gia'],
        "hinh" => $_POST['hinh'],
        "size" => $_POST['size'],
        "soluong" => (int)$_POST['soluong']
    ];

    $_SESSION['cart'][] = $item;

    // 👉 chuyển trang
    header("Location: package.php");
    exit();
}
$totalQty = 0;
if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $totalQty += $item['soluong'];
    }
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title><?php echo $sp['TenSP']; ?></title>
    <!-- logo web -->
    <link
        rel="shortcut icon"
        href="assets/file_anh/0c4690d7-3599-4de4-a0a4-841817ead1c0.png" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
    <link rel="stylesheet" href="./assets/css/product-detail.css">
    <link rel="stylesheet" href="./assets/css/responsive.css" />

    <style>
        .cart-icon {
            position: relative;
        }

        .cart-count {
            position: absolute;
            top: -8px;
            right: -10px;
            background: red;
            color: #fff;
            font-size: 12px;
            padding: 3px 7px;
            border-radius: 50%;
        }

        .variant-btn.active {
            background: black;
            color: white;
        }
    </style>
</head>

<body>
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
                    <div class="cart-icon">
                        <a href="package.php">
                            <span class="material-symbols-outlined">local_mall</span>

                            <?php if ($totalQty > 0): ?>
                                <span class="cart-count"><?= $totalQty ?></span>
                            <?php endif; ?>
                        </a>
                    </div>
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

    <!-- MAIN -->
    <div class="product-page">
        <div class="product-container">

            <!-- LEFT -->
            <div class="product-left">
                <img class="main-img" src="assets/file_anh/San_Pham/<?php echo $sp['Hinh']; ?>" />
            </div>

            <!-- RIGHT -->
            <div class="product-right">

                <h1 class="product-title"><?php echo $sp['TenSP']; ?></h1>

                <div class="product-meta">
                    Danh mục: <b><?php echo $sp['tendanhmuc']; ?></b> |
                    <span class="stock">
                        <?php echo ($sp['SoLuongTon'] > 0) ? "Còn hàng" : "Hết hàng"; ?>
                    </span>
                </div>

                <div class="product-code">
                    Mã sản phẩm: <span><?php echo $sp['MaSP']; ?></span>
                </div>

                <div class="price-box">
                    <div class="price">
                        <?php echo number_format($gia_giam); ?>đ
                    </div>
                </div>

                <!-- VARIANT -->
                <div class="variant">
                    <label>Phân loại:</label>
                    <div class="variant-list">
                        <?php while ($bt = mysqli_fetch_assoc($result_bt)) { ?>
                            <button class="variant-btn">
                                <?php echo $bt['LoaiThuocTinh'] . " - " . $bt['GiaTri']; ?>
                            </button>
                        <?php } ?>
                    </div>
                </div>

                <!-- QUANTITY -->
                <div class="quantity">
                    <div class="qty-box">
                        <button onclick="changeQty(-1)">-</button>
                        <input type="text" id="qty" value="1">
                        <button onclick="changeQty(1)">+</button>
                    </div>
                </div>

                <!-- BUTTON -->
                <div class="product-buttons">
                    <form method="POST">
                        <input type="hidden" name="masp" value="<?php echo $sp['MaSP']; ?>">
                        <input type="hidden" name="tensp" value="<?php echo $sp['TenSP']; ?>">
                        <input type="hidden" name="gia" value="<?php echo $gia_giam; ?>">
                        <input type="hidden" name="hinh" value="<?php echo $sp['Hinh']; ?>">
                        <input type="hidden" name="size" id="selectedSize">
                        <input type="hidden" name="soluong" id="selectedQty">

                        <button type="submit" name="addcart" class="add-cart"
                            onclick="document.getElementById('selectedQty').value=document.getElementById('qty').value">
                            🛒 Thêm vào giỏ
                        </button>
                        <button type="submit" name="buyNow" class="buy-now"
                            onclick="document.getElementById('selectedQty').value=document.getElementById('qty').value">
                            Mua ngay
                        </button>
                    </form>


                </div>

            </div>
        </div>
    </div>
    <section class="related-product">
        <h2>Sản phẩm liên quan</h2>

        <div class="product-grid">

            <?php while ($lq = mysqli_fetch_assoc($result_lq)) { ?>

                <div class="product-card">
                    <a href="product-detail.php?id=<?php echo $lq['MaSP']; ?>">

                        <div class="product-img">
                            <img src="assets/file_anh/San_Pham/<?php echo $lq['Hinh']; ?>" />
                        </div>

                        <div class="product-info">
                            <h3 class="product-name">
                                <?php echo $lq['TenSP']; ?>
                            </h3>

                            <div class="price">
                                <?php echo number_format($lq['GiaBan']); ?>đ
                            </div>
                        </div>

                    </a>
                </div>

            <?php } ?>

        </div>
        <div class="product-description">
            <h3>Mô tả sản phẩm</h3>
            <p>
                <?php echo $sp['MoTa']; ?>
            </p>
        </div>
    </section>
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
    <!-- SCRIPT -->
    <script>
        function changeQty(num) {
            let qty = document.getElementById("qty");
            let value = parseInt(qty.value) || 1;
            value += num;
            if (value < 1) value = 1;
            qty.value = value;
        }

        let selectedSize = "";

        document.querySelectorAll(".variant-btn").forEach(btn => {
            btn.onclick = function() {
                document.querySelectorAll(".variant-btn").forEach(b => b.classList.remove("active"));
                this.classList.add("active");

                selectedSize = this.innerText;
                document.getElementById("selectedSize").value = selectedSize;
            }
        });
    </script>

</body>

</html>