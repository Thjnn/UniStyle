<?php
session_start();
require_once "./config/db.php";
require_once __DIR__ . "/includes/banner.php";

// ══════════════════════════════════════════════════════════
//  KHỞI TẠO BIẾN
// ══════════════════════════════════════════════════════════
$conditions  = [];
$keyword     = trim($_GET['keyword'] ?? '');
$category_id = isset($_GET['category_id']) && $_GET['category_id'] != '' ? (int)$_GET['category_id'] : 0;

// ── 1. Tìm kiếm theo từ khóa ─────────────────────────────
if ($keyword !== '') {
    $kw_safe     = mysqli_real_escape_string($conn, $keyword);
    $conditions[] = "(TenSP LIKE '%$kw_safe%' OR MoTa LIKE '%$kw_safe%')";
}

// ── 2. Lọc theo danh mục ─────────────────────────────────
if ($category_id > 0) {
    $conditions[] = "madanhmuc = $category_id";
}

// ── 3. Lọc theo giá ──────────────────────────────────────
if (isset($_GET['price']) && $_GET['price'] != '') {
    $price = $_GET['price'];
    if ($price == 'duoi_100')   $conditions[] = "GiaBan < 100000";
    elseif ($price == '100_300')  $conditions[] = "GiaBan BETWEEN 100000 AND 300000";
    elseif ($price == '300_500')  $conditions[] = "GiaBan BETWEEN 300000 AND 500000";
    elseif ($price == 'tren_500') $conditions[] = "GiaBan > 500000";
}

// ── 4. Sắp xếp ───────────────────────────────────────────
$sort_map = [
    'az'       => 'TenSP ASC',
    'za'       => 'TenSP DESC',
    'gia_tang' => 'GiaBan ASC',
    'gia_giam' => 'GiaBan DESC',
    'moi'      => 'MaSP DESC',
    'ban_chay' => 'SoLuongDaBan DESC',
];
$sort_key = $_GET['sort'] ?? 'moi';
$order_by = $sort_map[$sort_key] ?? 'MaSP DESC';

// ── 5. Rán SQL ────────────────────────────────────────────
$sql_product = "SELECT * FROM sanpham";
if (count($conditions) > 0) {
    $sql_product .= " WHERE " . implode(' AND ', $conditions);
}
$sql_product .= " ORDER BY $order_by";

// Nếu không có bộ lọc nào và không tìm kiếm → random 100
if (count($conditions) === 0) {
    $sql_product = "SELECT * FROM sanpham ORDER BY RAND() LIMIT 100";
}

$result_product = mysqli_query($conn, $sql_product);
$total_found    = $result_product ? mysqli_num_rows($result_product) : 0;

// ── 6. Lấy bộ lọc thuộc tính theo danh mục ───────────────
$filters = [];
if ($category_id > 0) {
    $sql_attr    = "SELECT DISTINCT LoaiThuocTinh, GiaTri FROM bienthe_sanpham WHERE madanhmuc = $category_id";
    $result_attr = mysqli_query($conn, $sql_attr);
    if ($result_attr) {
        while ($row = mysqli_fetch_assoc($result_attr))
            $filters[$row['LoaiThuocTinh']][] = $row['GiaTri'];
    }
}

// ── 7. Giỏ hàng ──────────────────────────────────────────
$totalQty = 0;
if (!empty($_SESSION['cart']))
    foreach ($_SESSION['cart'] as $item) $totalQty += $item['soluong'];

// ── 8. Gợi ý tìm kiếm (AJAX) ─────────────────────────────
if (isset($_GET['ajax_suggest'])) {
    header('Content-Type: application/json');
    $q   = trim($_GET['ajax_suggest']);
    $out = [];
    if (strlen($q) >= 1) {
        $qs   = mysqli_real_escape_string($conn, $q);
        $res  = mysqli_query($conn, "SELECT MaSP, TenSP, GiaBan, Hinh FROM sanpham WHERE TenSP LIKE '%$qs%' LIMIT 6");
        if ($res) while ($r = mysqli_fetch_assoc($res)) $out[] = $r;
    }
    echo json_encode($out);
    exit();
}
?>
<!doctype html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0" />
    <title>Shop UniStyle<?= $keyword ? ' — "' . $keyword . '"' : '' ?></title>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,0..1" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="shortcut icon" href="assets/file_anh/0c4690d7-3599-4de4-a0a4-841817ead1c0.png" />
    <link rel="stylesheet" href="./assets/css/shop.css" />
    <link rel="stylesheet" href="./assets/css/reposive.css" />
    <script src="script.js"></script>
    <style>
        /* ══ THANH TÌM KIẾM ══════════════════════════════════════════════ */
        .search-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }

        .search-wrap form {
            display: flex;
            align-items: center;
            background: #fff;
            border: 2px solid #e0e0e0;
            border-radius: 24px;
            overflow: visible;
            transition: border-color .2s, box-shadow .2s;
            position: relative;
        }

        .search-wrap form:focus-within {
            border-color: #ff6a00;
            box-shadow: 0 0 0 3px rgba(255, 106, 0, .12);
        }

        .search-wrap form input {
            border: none;
            outline: none;
            background: transparent;
            padding: 9px 14px 9px 16px;
            font-size: 14px;
            width: 260px;
            color: #333;
        }

        .search-wrap form button[type=submit] {
            background: #ff6a00;
            border: none;
            border-radius: 0 22px 22px 0;
            padding: 9px 16px;
            cursor: pointer;
            color: #fff;
            display: flex;
            align-items: center;
            transition: background .2s;
            flex-shrink: 0;
        }

        .search-wrap form button[type=submit]:hover {
            background: #e05a00;
        }

        .search-wrap form button[type=submit] .material-symbols-outlined {
            font-size: 20px;
        }

        /* ── Nút xóa ── */
        .btn-clear-kw {
            position: absolute;
            right: 48px;
            top: 50%;
            transform: translateY(-50%);
            background: #e0e0e0;
            border: none;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 12px;
            color: #666;
            z-index: 2;
            transition: background .15s;
        }

        .btn-clear-kw:hover {
            background: #ccc;
        }

        /* ── Dropdown gợi ý ── */
        .suggest-box {
            position: absolute;
            top: calc(100% + 6px);
            left: 0;
            right: 0;
            background: #fff;
            border: 1px solid #e8e8e8;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, .12);
            z-index: 9999;
            overflow: hidden;
            display: none;
        }

        .suggest-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            cursor: pointer;
            transition: background .15s;
            text-decoration: none;
            color: inherit;
        }

        .suggest-item:hover {
            background: #fff8f5;
        }

        .suggest-item img {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid #eee;
            flex-shrink: 0;
        }

        .suggest-item-name {
            font-size: 13px;
            color: #333;
            line-height: 1.4;
            flex: 1;
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .suggest-item-price {
            font-size: 13px;
            font-weight: 600;
            color: #ee4d2d;
            white-space: nowrap;
        }

        .suggest-footer {
            padding: 10px 14px;
            border-top: 1px solid #f0f0f0;
            font-size: 13px;
            color: #ff6a00;
            font-weight: 600;
            cursor: pointer;
            text-align: center;
            transition: background .15s;
        }

        .suggest-footer:hover {
            background: #fff8f5;
        }

        /* ══ KẾT QUẢ TÌM KIẾM BANNER ════════════════════════════════════ */
        .search-result-bar {
            background: #fff;
            border: 1px solid #ffe0cc;
            border-radius: 8px;
            padding: 14px 20px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
        }

        .search-result-bar .kw-label {
            font-size: 15px;
            color: #333;
        }

        .search-result-bar .kw-label strong {
            color: #ff6a00;
            font-size: 17px;
        }

        .search-result-bar .kw-count {
            font-size: 13px;
            color: #888;
            background: #f5f5f5;
            padding: 4px 12px;
            border-radius: 20px;
        }

        .btn-clear-search {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 6px 14px;
            background: #fff;
            border: 1px solid #ff6a00;
            color: #ff6a00;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            transition: all .2s;
        }

        .btn-clear-search:hover {
            background: #ff6a00;
            color: #fff;
        }

        /* ── Empty state ── */
        .search-empty {
            text-align: center;
            padding: 60px 20px;
            color: #aaa;
        }

        .search-empty .material-symbols-outlined {
            font-size: 64px;
            display: block;
            margin-bottom: 12px;
            color: #ddd;
        }

        .search-empty h3 {
            font-size: 18px;
            color: #555;
            margin-bottom: 8px;
        }

        .search-empty p {
            font-size: 14px;
            margin-bottom: 20px;
        }

        .search-empty a {
            color: #ff6a00;
            font-weight: 600;
            text-decoration: none;
        }

        /* ── Highlight từ khóa trong tên sản phẩm ── */
        .kw-highlight {
            background: #fff3cd;
            color: #e65100;
            border-radius: 2px;
            padding: 0 2px;
            font-weight: 600;
        }

        /* Sort select */
        .sort-select {
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            padding: 7px 12px;
            font-size: 14px;
            outline: none;
            cursor: pointer;
        }
    </style>
    <link rel="stylesheet" href="./assets/css/banner_slider.css">
</head>

<body>

    <!-- ══ HEADER ══════════════════════════════════════════════════════════ -->
    <header>
        <div class="container">
            <div class="header-content">
                <div class="menu-toggle"><span class="material-symbols-outlined">menu</span></div>
                <div class="logo"><a href="index.php"><img
                            src="./assets/file_anh/0c4690d7-3599-4de4-a0a4-841817ead1c0.png" alt="" /></a></div>
                <nav>
                    <ul>
                        <li><a href="index.php">Trang chủ</a></li>
                        <li class="has-submenu">
                            <a href="shop.php">Cửa hàng</a>

                            <div class="submenu">
                                <!-- LEFT: danh mục -->
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

                                <!-- RIGHT: banner -->
                                <div class="submenu-banner">
                                    <a href="#!">
                                        <img src="./assets/file_anh/1920_x_600___cta___6_.webp" alt="Back To School Sale" />
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

                    <!-- ══ SEARCH BOX ══ -->
                    <div class="search-wrap" id="searchWrap">
                        <form action="shop.php" method="GET" id="searchForm" autocomplete="off">
                            <input type="text" name="keyword" id="searchInput" placeholder="Tìm sản phẩm..."
                                value="<?= htmlspecialchars($keyword) ?>" aria-label="Tìm kiếm sản phẩm" />
                            <?php if ($keyword): ?>
                                <button type="button" class="btn-clear-kw" id="btnClearKw" title="Xóa">✕</button>
                            <?php endif; ?>
                            <button type="submit"><span class="material-symbols-outlined">search</span></button>
                        </form>
                        <!-- Dropdown gợi ý -->
                        <div class="suggest-box" id="suggestBox"></div>
                    </div>

                    <div class="cart-icon">
                        <a href="package.php">
                            <span class="material-symbols-outlined">local_mall</span>
                            <?php if ($totalQty > 0): ?><span class="cart-count"><?= $totalQty ?></span><?php endif; ?>
                        </a>
                    </div>
                    <?php if (isset($_SESSION['khachhang_id'])): ?>
                        <a href="profile.php"><span class="material-symbols-outlined">person</span></a>
                    <?php else: ?>
                        <a href="login.php"><span class="material-symbols-outlined">person</span></a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <!-- Mobile Menu -->
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
    <script>
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
        mobileMenu.addEventListener("click", e => {
            if (e.target === mobileMenu) {
                mobileMenu.classList.remove("active");
                document.body.style.overflow = "";
            }
        });
        document.querySelectorAll(".mobile-nav a").forEach(l => l.addEventListener("click", () => {
            mobileMenu.classList.remove("active");
            document.body.style.overflow = "";
        }));
    </script>

    <!-- ══ MAIN ════════════════════════════════════════════════════════════ -->
    <div class="moving-bg-wrapper">
        <div class="background-move"></div>
        <section class="shop-page">

            <!-- Danh mục -->
            <section class="section">
                <div class="categories-grid" id="categoriesGrid">
                    <?php
                    $sql_dm = "SELECT * FROM danhmuc ORDER BY madanhmuc ASC";
                    $result_dm = mysqli_query($conn, $sql_dm);
                    $count = 0;
                    if ($result_dm && mysqli_num_rows($result_dm) > 0):
                        while ($dm = mysqli_fetch_assoc($result_dm)):
                            $count++;
                            $hidden_class = ($count > 8) ? 'hidden-category' : '';
                            $active = ($category_id == $dm['madanhmuc']) ? 'active-category' : '';
                            // Giữ keyword khi chuyển danh mục
                            $cat_href = ($category_id == $dm['madanhmuc'])
                                ? 'shop.php' . ($keyword ? '?keyword=' . urlencode($keyword) : '')
                                : 'shop.php?category_id=' . $dm['madanhmuc'] . ($keyword ? '&keyword=' . urlencode($keyword) : '');
                    ?>
                            <div class="category-card <?= $hidden_class ?> <?= $active ?>">
                                <a href="<?= $cat_href ?>" class="category-link">
                                    <div class="category-icon">
                                        <img src="./assets/file_anh/San_Pham/<?= $dm['hinhanh'] ?>"
                                            alt="<?= $dm['tendanhmuc'] ?>" />
                                    </div>
                                    <div class="category-name"><?= $dm['tendanhmuc'] ?></div>
                                </a>
                            </div>
                    <?php endwhile;
                    endif; ?>
                </div>
                <?php if ($result_dm && mysqli_num_rows($result_dm) > 8): ?>
                    <div class="see-more-categories">
                        <button id="toggleCategoriesBtn" onclick="toggleCategories()">
                            Xem thêm <i class="fa-solid fa-chevron-down"></i>
                        </button>
                    </div>
                <?php endif; ?>
            </section>
    </div>

    <div class="shop-container">
        <!-- Sidebar lọc -->
        <aside class="shop-sidebar">
            <form action="shop.php" method="GET">
                <?php if ($keyword): ?><input type="hidden" name="keyword"
                        value="<?= htmlspecialchars($keyword) ?>" /><?php endif; ?>
                <?php if ($category_id > 0): ?><input type="hidden" name="category_id"
                        value="<?= $category_id ?>" /><?php endif; ?>

                <div class="filter-section">
                    <h4>MỨC GIÁ</h4>
                    <?php
                    $price_opts = ['duoi_100' => 'Dưới 100.000đ', '100_300' => '100.000đ – 300.000đ', '300_500' => '300.000đ – 500.000đ', 'tren_500' => 'Trên 500.000đ'];
                    foreach ($price_opts as $val => $lbl):
                        $chk = (isset($_GET['price']) && $_GET['price'] == $val) ? 'checked' : '';
                    ?>
                        <label><input type="radio" name="price" value="<?= $val ?>" <?= $chk ?>> <?= $lbl ?></label><br>
                    <?php endforeach; ?>
                    <label><input type="radio" name="price" value=""
                            <?= (!isset($_GET['price']) || $_GET['price'] == '') ? 'checked' : '' ?>> Tất cả mức giá</label>
                </div>

                <?php foreach ($filters as $loai => $values): ?>
                    <div class="filter-section">
                        <h4><?= strtoupper($loai) ?></h4>
                        <?php foreach ($values as $v): ?>
                            <label><input type="checkbox" name="attr[<?= $loai ?>][]" value="<?= $v ?>"> <?= $v ?></label><br>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>

                <button type="submit" class="btn-filter">Áp dụng</button>
            </form>
        </aside>

        <!-- Nội dung shop -->
        <div class="shop-content">
            <!-- Shop Banner — từ Dashboard -->
            <div class="shop-banner">
                <?php echo render_banner('banner_top', './assets/file_anh/'); ?>
            </div>

            <!-- ── Thanh kết quả tìm kiếm ── -->
            <?php if ($keyword !== ''): ?>
                <div class="search-result-bar">
                    <div class="kw-label">
                        Kết quả tìm kiếm: <strong>"<?= htmlspecialchars($keyword) ?>"</strong>
                    </div>
                    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap">
                        <span class="kw-count"><?= $total_found ?> sản phẩm</span>
                        <a href="shop.php<?= $category_id > 0 ? '?category_id=' . $category_id : '' ?>" class="btn-clear-search">
                            <span class="material-symbols-outlined" style="font-size:15px">close</span> Xóa tìm kiếm
                        </a>
                    </div>
                </div>
            <?php endif; ?>

            <!-- ── Header shop + sắp xếp ── -->
            <div class="shop-header">
                <h2><?= $keyword ? 'Kết quả tìm kiếm' : 'Cửa hàng' ?></h2>
                <div class="shop-actions">
                    <form action="shop.php" method="GET" style="display:inline">
                        <?php if ($keyword): ?><input type="hidden" name="keyword"
                                value="<?= htmlspecialchars($keyword) ?>" /><?php endif; ?>
                        <?php if ($category_id > 0): ?><input type="hidden" name="category_id"
                                value="<?= $category_id ?>" /><?php endif; ?>
                        <?php if (isset($_GET['price']) && $_GET['price'] != ''): ?><input type="hidden" name="price"
                                value="<?= htmlspecialchars($_GET['price']) ?>" /><?php endif; ?>
                        <select name="sort" class="sort-select" onchange="this.form.submit()">
                            <option value="moi" <?= $sort_key == 'moi'     ? 'selected' : '' ?>>Hàng mới</option>
                            <option value="ban_chay" <?= $sort_key == 'ban_chay' ? 'selected' : '' ?>>Bán chạy</option>
                            <option value="gia_tang" <?= $sort_key == 'gia_tang' ? 'selected' : '' ?>>Giá tăng dần</option>
                            <option value="gia_giam" <?= $sort_key == 'gia_giam' ? 'selected' : '' ?>>Giá giảm dần</option>
                            <option value="az" <?= $sort_key == 'az'      ? 'selected' : '' ?>>Tên A → Z</option>
                            <option value="za" <?= $sort_key == 'za'      ? 'selected' : '' ?>>Tên Z → A</option>
                        </select>
                    </form>
                    <button class="filter-btn" onclick="openFilter()">
                        <i class="fa-solid fa-filter"></i> Lọc
                    </button>
                </div>
            </div>

            <!-- ── Lưới sản phẩm ── -->
            <?php if ($total_found === 0 && $keyword !== ''): ?>
                <!-- Empty state khi không tìm thấy -->
                <div class="search-empty">
                    <span class="material-symbols-outlined">search_off</span>
                    <h3>Không tìm thấy "<?= htmlspecialchars($keyword) ?>"</h3>
                    <p>Thử lại với từ khóa khác hoặc xem toàn bộ sản phẩm nhé!</p>
                    <a href="shop.php">← Xem tất cả sản phẩm</a>
                </div>
            <?php elseif ($total_found === 0): ?>
                <p>Không có sản phẩm</p>
            <?php else: ?>
                <div class="product-grid" id="productGrid">
                    <?php
                    // Helper highlight từ khóa
                    function highlight($text, $kw)
                    {
                        if (!$kw) return htmlspecialchars($text);
                        return preg_replace(
                            '/(' . preg_quote(htmlspecialchars($kw), '/') . ')/iu',
                            '<mark class="kw-highlight">$1</mark>',
                            htmlspecialchars($text)
                        );
                    }

                    while ($sp = mysqli_fetch_assoc($result_product)):
                        $ma_sp      = $sp['MaSP'];
                        $ten_sp     = $sp['TenSP'];
                        $gia_ban    = $sp['GiaBan'];
                        $hinh_anh   = $sp['Hinh'];
                        $da_ban     = $sp['SoLuongDaBan'];
                        $rating     = $sp['Rating'];
                        $is_hot     = $sp['NoiBat'];
                        $so_luot_dg = $sp['SoLuotDanhGia'];
                    ?>
                        <a href="product-detail.php?id=<?= $ma_sp ?>" style="text-decoration:none;color:inherit;display:block">
                            <div class="product-card">
                                <div class="product-img">
                                    <img src="./assets/file_anh/San_Pham/<?= !empty($hinh_anh) ? $hinh_anh : 'default.png' ?>"
                                        alt="<?= htmlspecialchars($ten_sp) ?>" />
                                </div>
                                <div class="product-info">
                                    <div class="product-tag">
                                        <?php if ($is_hot == 1): ?><span class="new">👍 Hot</span><?php endif; ?>
                                        <span class="sold">📊 Đã bán <?= $da_ban ?></span>
                                    </div>
                                    <h3 class="product-name"><?= highlight($ten_sp, $keyword) ?></h3>
                                    <div class="rating">
                                        <?php for ($i = 1; $i <= 5; $i++) echo ($i <= $rating) ? '<i class="fa-solid fa-star" style="color:#ffc107"></i>' : '<i class="fa-regular fa-star" style="color:#ccc"></i>'; ?>
                                        <span>(<?= $so_luot_dg ?>)</span>
                                    </div>
                                    <div class="price"><?= number_format($gia_ban, 0, ',', '.') ?>đ</div>
                                </div>
                            </div>
                        </a>
                    <?php endwhile; ?>
                </div>

                <!-- Xem thêm -->
                <button id="loadMoreBtn">Xem thêm</button>
                <script>
                    (function() {
                        const items = document.querySelectorAll("#productGrid > a");
                        const btn = document.getElementById("loadMoreBtn");
                        let visible = 8;

                        function show() {
                            items.forEach((el, i) => el.style.display = i < visible ? "block" : "none");
                            btn.style.display = visible >= items.length ? "none" : "";
                        }
                        btn.addEventListener("click", () => {
                            visible += 8;
                            show();
                        });
                        show();
                    })();
                </script>
            <?php endif; ?>

        </div><!-- end shop-content -->
    </div><!-- end shop-container -->

    <!-- Mobile filter overlay -->
    <div class="filter-overlay" id="filterOverlay">
        <div class="filter-panel">
            <div class="filter-header">
                <h3>Bộ lọc</h3>
                <span onclick="closeFilter()">✕</span>
            </div>
            <div class="filter-group">
                <h3>LOẠI SẢN PHẨM</h3>
                <label><input type="checkbox" /> Bảng học sinh</label>
                <label><input type="checkbox" /> Kéo học sinh</label>
                <label><input type="checkbox" /> Gôm</label>
                <label><input type="checkbox" /> Bìa bao tập</label>
            </div>
        </div>
    </div>

    </section>

    <!-- Back to top -->
    <button id="backToTop"><span class="material-symbols-outlined">keyboard_arrow_up</span></button>
    <script>
        const backToTopBtn = document.getElementById("backToTop");
        window.onscroll = () => backToTopBtn.style.display = document.documentElement.scrollTop > 200 ? "block" : "none";
        backToTopBtn.onclick = () => window.scrollTo({
            top: 0,
            behavior: "smooth"
        });
    </script>

    <!-- ══ FOOTER ══════════════════════════════════════════════════════════ -->
    <footer class="footer">
        <div class="footer-newsletter">
            <div class="newsletter-text">
                <h2>Đăng ký để nhận ưu đãi</h2>
                <p>Đăng ký nhận bản tin để nhận ưu đãi và giảm giá độc quyền!</p>
            </div>
            <div class="newsletter-form"><input type="email" placeholder="Email của bạn..." /><button><span
                        class="material-symbols-outlined">mail</span></button></div>
        </div>
        <div class="footer-container">
            <div class="footer-col">
                <h2 class="logo"><img src="./assets/file_anh/0c4690d7-3599-4de4-a0a4-841817ead1c0.png" alt="" />
                    UniStyle</h2>
                <p>Liên hệ: <span>support@example.com</span></p>
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
                </ul>
            </div>
            <div class="footer-col">
                <h3>Chăm sóc khách hàng</h3>
                <ul>
                    <li><a href="#">Theo dõi đơn hàng</a></li>
                    <li><a href="#">Đổi / Trả hàng</a></li>
                    <li><a href="#">Thông tin vận chuyển</a></li>
                    <li><a href="#">Liên hệ</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h3>Dịch vụ</h3>
                <ul>
                    <li><a href="#">In ấn - Photo</a></li>
                    <li><a href="#">Đóng gáy tài liệu</a></li>
                    <li><a href="#">Cung cấp sỉ văn phòng phẩm</a></li>
                    <li><a href="#">Đặt hàng theo yêu cầu</a></li>
                </ul>
            </div>
        </div>
    </footer>

    <!-- ══ SCRIPT TÌM KIẾM GỢI Ý ══════════════════════════════════════════ -->
    <script>
        (function() {
            const input = document.getElementById('searchInput');
            const suggestBox = document.getElementById('suggestBox');
            const btnClear = document.getElementById('btnClearKw');
            let timer = null;

            if (!input) return;

            // Nút xóa từ khóa
            if (btnClear) {
                btnClear.addEventListener('click', () => {
                    input.value = '';
                    input.focus();
                    hideSuggest();
                    // Xóa param keyword khỏi URL hiện tại
                    const url = new URL(window.location.href);
                    url.searchParams.delete('keyword');
                    window.location.href = url.toString();
                });
            }

            // Gõ → gợi ý
            input.addEventListener('input', function() {
                clearTimeout(timer);
                const q = this.value.trim();
                if (q.length < 1) {
                    hideSuggest();
                    return;
                }
                timer = setTimeout(() => fetchSuggest(q), 220);
            });

            // Focus → show lại nếu có nội dung
            input.addEventListener('focus', function() {
                if (this.value.trim().length >= 1) fetchSuggest(this.value.trim());
            });

            // Click ngoài → ẩn
            document.addEventListener('click', e => {
                if (!document.getElementById('searchWrap').contains(e.target)) hideSuggest();
            });

            // Phím mũi tên + Enter điều hướng gợi ý
            input.addEventListener('keydown', function(e) {
                const items = suggestBox.querySelectorAll('.suggest-item[data-idx]');
                let cur = [...items].findIndex(el => el.classList.contains('hover'));
                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    cur = Math.min(cur + 1, items.length - 1);
                    items.forEach(el => el.classList.remove('hover'));
                    if (items[cur]) {
                        items[cur].classList.add('hover');
                        input.value = items[cur].dataset.name;
                    }
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    cur = Math.max(cur - 1, 0);
                    items.forEach(el => el.classList.remove('hover'));
                    if (items[cur]) {
                        items[cur].classList.add('hover');
                        input.value = items[cur].dataset.name;
                    }
                } else if (e.key === 'Escape') {
                    hideSuggest();
                }
            });

            function fetchSuggest(q) {
                fetch('shop.php?ajax_suggest=' + encodeURIComponent(q))
                    .then(r => r.json())
                    .then(data => renderSuggest(data, q))
                    .catch(() => hideSuggest());
            }

            function renderSuggest(data, q) {
                if (!data || data.length === 0) {
                    hideSuggest();
                    return;
                }

                let html = '';
                data.forEach((sp, idx) => {
                    const name = escHtml(sp.TenSP);
                    const highlighted = name.replace(
                        new RegExp('(' + escRegex(escHtml(q)) + ')', 'gi'),
                        '<strong style="color:#ff6a00">$1</strong>'
                    );
                    html += `<a class="suggest-item" href="product-detail.php?id=${sp.MaSP}"
                        data-idx="${idx}" data-name="${escHtml(sp.TenSP)}">
                        <img src="./assets/file_anh/San_Pham/${escHtml(sp.Hinh)}" alt=""/>
                        <span class="suggest-item-name">${highlighted}</span>
                        <span class="suggest-item-price">${fmtMoney(sp.GiaBan)}đ</span>
                     </a>`;
                });
                html += `<div class="suggest-footer" onclick="submitSearch()">
                    <span class="material-symbols-outlined" style="vertical-align:middle;font-size:15px">search</span>
                    Xem tất cả kết quả cho "<strong>${escHtml(q)}</strong>"
                 </div>`;

                suggestBox.innerHTML = html;
                suggestBox.style.display = 'block';
            }

            function hideSuggest() {
                suggestBox.style.display = 'none';
                suggestBox.innerHTML = '';
            }

            function submitSearch() {
                document.getElementById('searchForm').submit();
            }

            function fmtMoney(n) {
                return Number(n).toLocaleString('vi-VN');
            }

            function escHtml(s) {
                return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g,
                    '&quot;');
            }

            function escRegex(s) {
                return s.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
            }

            window.submitSearch = submitSearch;
        })();
    </script>

    <script src="./assets/js/banner_slider.js"></script>
</body>

</html>