<?php
require_once __DIR__ . '/../config/db.php';
require_once '../includes/dashboard_kpi.php';

// Tổng đơn
$sql_total = "SELECT COUNT(*) as total FROM dathang";
$total = $conn->query($sql_total)->fetch_assoc()['total'];

// Đếm theo trạng thái
$sql_status = "
    SELECT trangthai, COUNT(*) as count
    FROM dathang
    GROUP BY trangthai
";

$result_status = $conn->query($sql_status);

$status_count = [
  'Chờ xử lý' => 0,
  'Đang giao' => 0,
  'Hoàn thành' => 0,
  'Đã hủy' => 0,
];

while ($row = $result_status->fetch_assoc()) {
  $status_count[$row['trangthai']] = $row['count'];
}
$today = date('Y-m-d');

$sql_today = "SELECT COUNT(*) as total_today 
              FROM dathang 
              WHERE DATE(ngaydat) = '$today'";

$result_today = mysqli_query($conn, $sql_today);
$row_today = mysqli_fetch_assoc($result_today);

$orders_today = $row_today['total_today'];
?>

<!doctype html>
<html lang="vi">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Văn Phòng UniStyle — Quản trị hệ thống</title>

  <!-- logo web -->
  <link
    rel="shortcut icon"
    href="../assets/file_anh/0c4690d7-3599-4de4-a0a4-841817ead1c0.png" />
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link
    href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600&family=Sora:wght@400;500;600&display=swap"
    rel="stylesheet" />

  <!-- Stylesheet -->
  <link rel="stylesheet" href="style.css" />
  <!-- JavaScript -->
  <script src="main.js"></script>
</head>

<body>
  <div class="shell">
    <!-- ═══════════ SIDEBAR ═══════════ -->
    <aside class="sidebar">
      <div class="logo">
        <div class="logo-mark">
          <img
            src="../assets/file_anh/0c4690d7-3599-4de4-a0a4-841817ead1c0.png"
            alt="" />
        </div>
        <div class="logo-info">
          <div class="logo-name">Văn Phòng UniStyle</div>
          <div class="logo-sub">Quản trị hệ thống</div>
        </div>
      </div>

      <nav class="nav">
        <div class="nav-section">Tổng quan</div>
        <button class="nav-item active" onclick="showPage('dashboard', this)">
          <svg class="nav-icon" viewBox="0 0 16 16">
            <rect x="1.5" y="1.5" width="5" height="5" rx="1.2" />
            <rect x="9.5" y="1.5" width="5" height="5" rx="1.2" />
            <rect x="1.5" y="9.5" width="5" height="5" rx="1.2" />
            <rect x="9.5" y="9.5" width="5" height="5" rx="1.2" />
          </svg>
          Dashboard
        </button>

        <div class="nav-section">Kinh doanh</div>
        <button class="nav-item" onclick="showPage('orders', this)">
          <svg class="nav-icon" viewBox="0 0 16 16">
            <path d="M2 2h1.5l2.2 7.5h6.5l1.5-5.5H5.5" />
            <circle cx="7.5" cy="13.5" r="1" />
            <circle cx="11.5" cy="13.5" r="1" />
          </svg>
          Đơn hàng
          <span class="nav-badge badge-red-soft">
            <?= $orders_today ?>
          </span>
        </button>

        <button class="nav-item" onclick="showPage('products', this)">
          <svg class="nav-icon" viewBox="0 0 16 16">
            <path d="M8 1l6 3.5v7L8 15 2 11.5v-7L8 1z" />
            <path d="M8 1v13M2 4.5l6 3.5 6-3.5" />
          </svg>
          Sản phẩm

        </button>

        <button class="nav-item" onclick="showPage('customers', this)">
          <svg class="nav-icon" viewBox="0 0 16 16">
            <circle cx="8" cy="5" r="3" />
            <path d="M2.5 14c0-3 2.5-5 5.5-5s5.5 2 5.5 5" />
          </svg>
          Khách hàng
        </button>

        <div class="nav-section">Kho &amp; Tài chính</div>
        <button class="nav-item" onclick="showPage('inventory', this)">
          <svg class="nav-icon" viewBox="0 0 16 16">
            <rect x="1.5" y="6" width="13" height="8.5" rx="1.5" />
            <path d="M5.5 6V4.5a2.5 2.5 0 015 0V6" />
          </svg>
          Kho hàng

        </button>

        <button class="nav-item" onclick="showPage('promotions', this)">
          <svg class="nav-icon" viewBox="0 0 16 16">
            <path d="M2 8c0-3.3 2.7-6 6-6s6 2.7 6 6-2.7 6-6 6" />
            <path d="M8 5v3l2 1.5" />
          </svg>
          Khuyến mãi
        </button>

        <button class="nav-item" onclick="showPage('ads', this)">
          <svg class="nav-icon" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round">
            <rect x="1" y="4" width="14" height="8" rx="1.5" />
            <path d="M4 8h5M4 10.5h3" />
            <circle cx="12" cy="8" r="1.5" fill="currentColor" stroke="none" />
          </svg>
          Quảng cáo
        </button>
      </nav>

      <div class="sidebar-footer">
        <div class="avatar-sm">AD</div>
        <div>
          <div class="user-name">Admin</div>
          <div class="user-role">Quản trị viên</div>
        </div>
      </div>
    </aside>
    <!-- /SIDEBAR -->

    <!-- ═══════════ MAIN ═══════════ -->
    <div class="main">
      <!-- TOPBAR -->
      <header class="topbar">
        <div class="topbar-breadcrumb">
          <span class="breadcrumb-root">Văn Phòng UniStyle</span>
          <span class="breadcrumb-sep">/</span>
          <span class="breadcrumb-cur" id="page-title">Dashboard</span>
        </div>
        <div class="search-box" id="search-box">
          <svg width="13" height="13" viewBox="0 0 16 16" fill="none"
            stroke="#94a3b8" stroke-width="1.6" stroke-linecap="round">
            <circle cx="6.5" cy="6.5" r="4.5" />
            <path d="M10.5 10.5L14 14" />
          </svg>
          <input
            id="search-input"
            placeholder="Tìm sản phẩm, đơn hàng..."
            autocomplete="off" />

          <!-- Dropdown kết quả -->
          <div class="search-dropdown" id="search-dropdown">
            <div id="search-results"></div>
            <div class="search-empty" id="search-empty" style="display:none">
              Không tìm thấy kết quả
            </div>
          </div>
        </div>
        <div class="topbar-actions">
          <div class="icon-btn">
            <div class="dot"></div>
            <svg
              width="16"
              height="16"
              viewBox="0 0 16 16"
              fill="none"
              stroke="currentColor"
              stroke-width="1.6"
              stroke-linecap="round">
              <path
                d="M8 2a4.5 4.5 0 00-4.5 4.5c0 2.5-1 3-1 3h11s-1-.5-1-3A4.5 4.5 0 008 2z" />
              <path d="M6.5 13a1.5 1.5 0 003 0" />
            </svg>
          </div>
          <div class="avatar-topbar">AD</div>
        </div>
      </header>
      <!-- /TOPBAR -->

      <!-- CONTENT -->
      <main class="content">
        <!-- ═══════════════════════════════════════
           PAGE: DASHBOARD
      ═══════════════════════════════════════ -->
        <div class="page active" id="page-dashboard">
          <div class="sec-header">
            <div>
              <div class="sec-title">Dashboard</div>
              <div class="sec-sub" id="realtime-date"></div>

              <script>
                function updateDateTime() {
                  const now = new Date();

                  const days = ["Chủ Nhật", "Thứ Hai", "Thứ Ba", "Thứ Tư", "Thứ Năm", "Thứ Sáu", "Thứ Bảy"];
                  const dayName = days[now.getDay()];

                  const day = now.getDate();
                  const month = now.getMonth() + 1;
                  const year = now.getFullYear();

                  // Xác định buổi
                  const hour = now.getHours();
                  let greeting = "Chào buổi sáng";

                  if (hour >= 12 && hour < 18) {
                    greeting = "Chào buổi chiều";
                  } else if (hour >= 18) {
                    greeting = "Chào buổi tối";
                  }

                  const text = `${dayName}, ${day} tháng ${month} năm ${year} — ${greeting}, Admin!`;

                  document.getElementById("realtime-date").innerText = text;
                }

                // cập nhật ngay khi load
                updateDateTime();

                // cập nhật mỗi giây (nếu muốn realtime hơn)
                setInterval(updateDateTime, 1000);
              </script>
            </div>
            <!-- Nút xuất báo cáo -->
            <div class="sec-actions">
              <button class="btn" onclick="exportDashboardExcel()">
                <svg width="13" height="13" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round">
                  <path d="M13 10v4H3v-4M8 2v8M5 5l3-3 3 3" />
                </svg>
                Xuất báo cáo
              </button>
            </div>
          </div>

          <!-- KPI Cards -->
          <div class="kpi-grid">
            <div class="kpi-card">
              <div class="kpi-header">
                <span class="kpi-label">Doanh thu tháng này</span>
                <div class="kpi-icon" style="background: #eff6ff">
                  <svg
                    viewBox="0 0 16 16"
                    fill="none"
                    stroke="#1d4ed8"
                    stroke-width="1.6"
                    stroke-linecap="round">
                    <path
                      d="M8 2v12M5 5h4.5a2 2 0 010 4H5m0-4H3m2 4H3m2 4h4a2 2 0 000-4" />
                  </svg>
                </div>
              </div>
              <div class="kpi-value"><?= $dt_hien_thi ?></div>
              <div class="kpi-foot">
                <span class="kpi-change <?= $dt_trend ?>"><?= $dt_icon ?> <?= abs($dt_phan_tram) ?>%</span>
                <span class="kpi-period">so với tháng trước</span>
              </div>
            </div>

            <div class="kpi-card">
              <div class="kpi-header">
                <span class="kpi-label">Đơn hàng hôm nay</span>
                <div class="kpi-icon" style="background: #f0fdf4">
                  <svg
                    viewBox="0 0 16 16"
                    fill="none"
                    stroke="#16a34a"
                    stroke-width="1.6"
                    stroke-linecap="round">
                    <path d="M2 2h1.5l2 7h6l1.5-5H5" />
                    <circle
                      cx="7"
                      cy="13"
                      r="1"
                      fill="#16a34a"
                      stroke="none" />
                    <circle
                      cx="11"
                      cy="13"
                      r="1"
                      fill="#16a34a"
                      stroke="none" />
                  </svg>
                </div>
              </div>
              <div class="kpi-value"><?= $dh_hom_nay ?></div>
              <div class="kpi-foot">
                <span class="kpi-change <?= $dh_trend ?>"><?= $dh_icon ?> <?= abs($dh_chenh) ?> đơn</span>
                <span class="kpi-period">so với hôm qua</span>
              </div>
            </div>

            <div class="kpi-card">
              <div class="kpi-header">
                <span class="kpi-label">Khách hàng mới</span>
                <div class="kpi-icon" style="background: #fef9c3">
                  <svg
                    viewBox="0 0 16 16"
                    fill="none"
                    stroke="#854d0e"
                    stroke-width="1.6"
                    stroke-linecap="round">
                    <circle cx="7" cy="5.5" r="2.5" />
                    <path d="M1.5 13c0-2.5 2.5-4.5 5.5-4.5" />
                    <circle cx="12.5" cy="11.5" r="2.5" />
                    <path d="M12.5 9v1.5l1 1" />
                  </svg>
                </div>
              </div>
              <div class="kpi-value"><?= $kh_thang_nay ?></div>
              <div class="kpi-foot">
                <span class="kpi-change <?= $kh_trend ?>"><?= $kh_icon ?> <?= abs($kh_phan_tram) ?>%</span>
                <span class="kpi-period">tháng này</span>
              </div>
            </div>

            <div class="kpi-card">
              <div class="kpi-header">
                <span class="kpi-label">Tỷ lệ hoàn trả</span>
                <div class="kpi-icon" style="background: #fef2f2">
                  <svg
                    viewBox="0 0 16 16"
                    fill="none"
                    stroke="#dc2626"
                    stroke-width="1.6"
                    stroke-linecap="round">
                    <path
                      d="M3.5 8A4.5 4.5 0 0112.5 8M3.5 8A4.5 4.5 0 0112.5 8M3.5 8H1.5m11 0h2" />
                    <path d="M2 5.5l1.5 2.5M14 10.5l-1.5-2.5" />
                  </svg>
                </div>
              </div>
              <div class="kpi-value"><?= $ht_ty_le_nay ?>%</div>
              <div class="kpi-foot">
                <span class="kpi-change <?= $ht_trend ?>"><?= $ht_icon ?> <?= abs($ht_chenh) ?>%</span>
                <span class="kpi-period">so với tháng trước</span>
              </div>
            </div>
          </div>
          <!-- /KPI Cards -->

          <!-- Row: Chart + Recent Orders -->
          <div class="grid-2">
            <div class="card">
              <div class="card-head">
                <div class="card-title">Doanh thu 7 ngày gần đây</div>
                <span
                  style="
                      font-size: 11px;
                      color: var(--text-muted);
                      font-weight: 500;
                    ">Đơn vị: triệu VND</span>
              </div>
              <div class="chart-wrap" style="padding-top: 16px">
                <div class="chart-bars">
                  <?php require_once __DIR__ . '/../includes/chart_7days.php'; ?>
                </div>
              </div>
            </div>

            <div class="card">
              <div class="card-head">
                <div class="card-title">Đơn hàng mới nhất</div>
                <button
                  class="btn btn-sm"
                  onclick="
                      showPage(
                        'orders',
                        document.querySelectorAll('.nav-item')[1],
                      )
                    ">
                  Xem tất cả
                </button>
              </div>
              <div class="card-body">
                <?php require_once __DIR__ . '/../includes/latest_orders.php'; ?>
              </div>
            </div>
          </div>
          <!-- /Row -->

          <!-- Row: Top Products -->
          <div class="grid-3">
            <div class="card card-full">
              <div class="card-head" style="padding: 18px 22px 12px">
                <div class="card-title">Sản phẩm bán chạy</div>
              </div>
              <div class="table-wrap">
                <table>
                  <thead>
                    <tr>
                      <th>Sản phẩm</th>
                      <th>Đã bán</th>
                      <th>Doanh thu</th>
                      <th>Xu hướng</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php require_once __DIR__ . '/../includes/top_products.php'; ?>
                  </tbody>
                </table>
              </div>
            </div>

            <div class="card">
              <div class="card-head">
                <div class="card-title">Thông báo</div>
              </div>
              <div class="card-body" style="padding-top: 12px">
                <div class="notif-list">
                  <div class="notif-item">
                    <div class="notif-dot-wrap">
                      <div class="notif-dot dot-blue"></div>
                    </div>
                    <div>
                      <div class="notif-text">
                        Tồn kho <strong>Bút chì 2B Staedtler</strong> còn 8
                        hộp — cần nhập thêm
                      </div>
                      <div class="notif-time">5 phút trước</div>
                    </div>
                  </div>
                  <div class="notif-item">
                    <div class="notif-dot-wrap">
                      <div class="notif-dot dot-blue"></div>
                    </div>
                    <div>
                      <div class="notif-text">
                        12 đơn hàng mới đang chờ xác nhận
                      </div>
                      <div class="notif-time">23 phút trước</div>
                    </div>
                  </div>
                  <div class="notif-item">
                    <div class="notif-dot-wrap">
                      <div class="notif-dot dot-gray"></div>
                    </div>
                    <div>
                      <div class="notif-text">
                        Cty TNHH ABC thanh toán đơn #DH-1038 thành công
                      </div>
                      <div class="notif-time">1 giờ trước</div>
                    </div>
                  </div>
                  <div class="notif-item">
                    <div class="notif-dot-wrap">
                      <div class="notif-dot dot-gray"></div>
                    </div>
                    <div>
                      <div class="notif-text">
                        Báo cáo tháng 2/2026 đã sẵn sàng để xuất
                      </div>
                      <div class="notif-time">3 giờ trước</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- /Row -->
        </div>
        <!-- /PAGE DASHBOARD -->

        <!-- ═══════════════════════════════════════
           PAGE: ORDERS
      ═══════════════════════════════════════ -->
        <div class="page" id="page-orders">
          <div class="sec-header">
            <div>
              <div class="sec-title">Quản lý đơn hàng</div>
              <div class="sec-sub"><?= $total ?> đơn hàng tổng cộng</div>
            </div>
            <div style="display: flex; justify-content: flex-end; margin-bottom: 15px;">
              <button class="btn btn-primary" onclick="exportOrdersExcel()" style="display: flex; align-items: center; gap: 8px;">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M14 10v4H2v-4M8 2v10M5 9l3 3 3-3" />
                </svg>
                Xuất báo cáo
              </button>
            </div>
          </div>

          <div class="card card-full">
            <div class="table-wrap">
              <table>
                <thead>
                  <tr>
                    <th>Mã đơn</th>
                    <th>Khách hàng</th>
                    <th>Ngày đặt</th>
                    <th>Sản phẩm</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                  </tr>
                </thead>
                <tbody>
                  <?php require_once __DIR__ . '/../includes/orders_table.php'; ?>
                </tbody>
              </table>
            </div>

          </div>
        </div>
        <!-- /PAGE ORDERS -->

        <!-- ═══════════════════════════════════════
           PAGE: PRODUCTS
      ═══════════════════════════════════════ -->
        <div class="page" id="page-products">
          <div class="sec-header">
            <div>
              <div class="sec-title">Quản lý sản phẩm</div>

            </div>
            <!-- Nút xuất danh sách -->
            <div class="sec-actions">
              <div class="export-dropdown" id="export-prod-dd">
                <button class="btn" onclick="toggleExportDD()" style="display:flex;align-items:center;gap:6px">
                  <svg width="13" height="13" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round">
                    <path d="M13 10v4H3v-4M8 2v8M5 5l3 3 3-3" />
                  </svg>
                  Xuất danh sách
                  <svg width="11" height="11" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                    <path d="M4 6l4 4 4-4" />
                  </svg>
                </button>
                <div class="export-dd-menu" id="export-dd-menu">
                  <button class="export-dd-item" onclick="exportProductExcel('excel');toggleExportDD(false)">
                    <svg width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="#166534" stroke-width="1.8" stroke-linecap="round">
                      <rect x="2" y="2" width="12" height="12" rx="2" />
                      <path d="M5 6l2 4 2-4M11 6v4" />
                    </svg>
                    Xuất Excel (.xls)
                  </button>
                  <button class="export-dd-item" onclick="exportProductExcel('csv');toggleExportDD(false)">
                    <svg width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="#1d4ed8" stroke-width="1.8" stroke-linecap="round">
                      <path d="M3 4h10M3 8h7M3 12h5" />
                    </svg>
                    Xuất CSV (.csv)
                  </button>
                </div>
              </div>
              <button class="btn btn-primary" onclick="openAddProductModal()">+ Thêm sản phẩm</button>
            </div>

          </div>

          <?php require_once __DIR__ . '/../includes/products_table.php'; ?>
        </div>
        <!-- /PAGE PRODUCTS -->

        <!-- ═══════════════════════════════════════
           PAGE: CUSTOMERS
      ═══════════════════════════════════════ -->
        <div class="page" id="page-customers">
          <div class="sec-header">
            <div>
              <div class="sec-title">Quản lý khách hàng</div>
              <div class="sec-sub" id="kh-sub-count">Đang tải...</div>
            </div>
            <div class="sec-actions">
              <div class="export-dropdown" id="export-kh-dd">
                <button class="btn" onclick="toggleExportKhDD()" style="display:flex;align-items:center;gap:6px">
                  <svg width="13" height="13" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round">
                    <path d="M13 10v4H3v-4M8 2v8M5 5l3 3 3-3" />
                  </svg>
                  Xuất danh sách
                  <svg width="11" height="11" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                    <path d="M4 6l4 4 4-4" />
                  </svg>
                </button>
                <div class="export-dd-menu" id="export-kh-dd-menu">
                  <button class="export-dd-item" onclick="exportKhExcel('excel');toggleExportKhDD(false)">
                    <svg width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="#166534" stroke-width="1.8" stroke-linecap="round">
                      <rect x="2" y="2" width="12" height="12" rx="2" />
                      <path d="M5 6l2 4 2-4M11 6v4" />
                    </svg>
                    Xuất Excel (.xls)
                  </button>
                  <button class="export-dd-item" onclick="exportKhExcel('csv');toggleExportKhDD(false)">
                    <svg width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="#1d4ed8" stroke-width="1.8" stroke-linecap="round">
                      <path d="M3 4h10M3 8h7M3 12h5" />
                    </svg>
                    Xuất CSV (.csv)
                  </button>
                </div>
              </div>
              <button class="btn btn-primary" onclick="openKhForm(0)">+ Thêm khách hàng</button>
            </div>
          </div>



          <?php require_once __DIR__ . '/../includes/customers_table.php'; ?>

        </div>
        <!-- /PAGE CUSTOMERS -->

        <!-- ═══════════════════════════════════════
           PAGE: INVENTORY
      ═══════════════════════════════════════ -->
        <div class="page" id="page-inventory">
          <div class="sec-header">
            <div>
              <div class="sec-title">Quản lý kho hàng</div>

            </div>
            <div class="sec-actions">
              <div class="export-dropdown" id="export-inv-dd">
                <button class="btn" onclick="toggleExportInvDD()" style="display:flex;align-items:center;gap:6px">
                  <svg width="13" height="13" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round">
                    <path d="M13 10v4H3v-4M8 2v8M5 5l3 3 3-3" />
                  </svg>
                  Xuất báo cáo
                  <svg width="11" height="11" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                    <path d="M4 6l4 4 4-4" />
                  </svg>
                </button>
                <div class="export-dd-menu" id="export-inv-dd-menu">
                  <button class="export-dd-item" onclick="exportInv('excel');toggleExportInvDD(false)">
                    <svg width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="#166534" stroke-width="1.8" stroke-linecap="round">
                      <rect x="2" y="2" width="12" height="12" rx="2" />
                      <path d="M5 6l2 4 2-4M11 6v4" />
                    </svg>
                    Xuất Excel (.xls)
                  </button>
                  <button class="export-dd-item" onclick="exportInv('csv');toggleExportInvDD(false)">
                    <svg width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="#1d4ed8" stroke-width="1.8" stroke-linecap="round">
                      <path d="M3 4h10M3 8h7M3 12h5" />
                    </svg>
                    Xuất CSV (.csv)
                  </button>
                </div>
              </div>
              <button class="btn btn-primary" onclick="openBulkNhapKho()">+ Phiếu nhập kho</button>
            </div>
          </div>

          <?php require_once __DIR__ . '/../includes/inventory_table.php'; ?>
        </div>
        <!-- /PAGE INVENTORY -->


        <!-- ═══════════════════════════════════════
           PAGE: PROMOTIONS
      ═══════════════════════════════════════ -->
        <div class="page" id="page-promotions">
          <div class="sec-header">
            <div>
              <div class="sec-title">Chương trình Khuyến mãi</div>
              <div class="sec-sub">Quản lý mã voucher và chiến dịch marketing</div>
            </div>
            <div class="sec-actions">
              <button class="btn" onclick="openCampaignForm(0)" style="display:flex;align-items:center;gap:6px">
                <svg width="13" height="13" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                  <path d="M8 2v12M2 8h12" />
                </svg>
                Tạo chiến dịch
              </button>
              <button class="btn btn-primary" onclick="openVoucherForm(0)" style="display:flex;align-items:center;gap:6px">
                <svg width="13" height="13" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                  <path d="M8 2v12M2 8h12" />
                </svg>
                Tạo voucher mới
              </button>
            </div>
          </div>

          <?php require_once __DIR__ . '/../includes/promotions_table.php'; ?>
        </div>
        <!-- /PAGE PROMOTIONS -->

        <!-- ═══════════════════════════════════════
           PAGE: ADS
      ═══════════════════════════════════════ -->
        <div class="page" id="page-ads">
          <div class="sec-header">
            <div>
              <div class="sec-title">Quản lý Quảng cáo</div>
              <div class="sec-sub">Banner, popup và nội dung quảng bá</div>
            </div>
            <div class="sec-actions">
              <button class="btn btn-primary" onclick="openAdsForm(0)" style="display:flex;align-items:center;gap:6px">
                <svg width="13" height="13" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                  <path d="M8 2v12M2 8h12" />
                </svg>
                Thêm quảng cáo
              </button>
            </div>
          </div>

          <?php require_once __DIR__ . '/../includes/ads_table.php'; ?>
        </div>
        <!-- /PAGE ADS -->

      </main>
      <!-- /CONTENT -->
    </div>
    <!-- /MAIN -->
  </div>
  <!-- /SHELL -->


  <!-- ═══════════ MODAL CHI TIẾT ĐƠN HÀNG ═══════════ -->
  <div class="modal-overlay" id="order-modal-overlay" onclick="closeOrderModal(event)">
    <div class="modal-box" id="order-modal-box">

      <!-- Header -->
      <div class="modal-header">
        <div>
          <div class="modal-title" id="modal-order-id">Chi tiết đơn hàng</div>
          <div id="modal-order-status-wrap"></div>
        </div>
        <button class="modal-close" onclick="closeOrderModal(true)">
          <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
            <path d="M2 2l12 12M14 2L2 14" />
          </svg>
        </button>
      </div>

      <!-- Body -->
      <div class="modal-body" id="modal-body">
        <div class="modal-loading">Đang tải...</div>
      </div>

      <!-- Footer -->
      <div class="modal-footer" id="modal-footer"></div>

    </div>
  </div>
  <!-- /MODAL -->


  <!-- ═══════════ MODAL SẢN PHẨM (Thêm / Sửa) ═══════════ -->
  <div class="modal-overlay" id="prod-modal-overlay" onclick="closeProdModal(event)">
    <div class="modal-box" style="max-width:580px">
      <div class="modal-header">
        <div>
          <div class="modal-title" id="prod-modal-title">Thêm sản phẩm</div>
          <div style="font-size:12px;color:var(--text-muted);margin-top:2px" id="prod-modal-sub"></div>
        </div>
        <button class="modal-close" onclick="closeProdModal(true)">
          <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
            <path d="M2 2l12 12M14 2L2 14" />
          </svg>
        </button>
      </div>
      <div class="modal-body" id="prod-modal-body">
        <div class="modal-loading">
          <div class="modal-spinner"></div> Đang tải...
        </div>
      </div>
      <div class="modal-footer" id="prod-modal-footer"></div>
    </div>
  </div>

  <!-- ═══════════ MODAL NHẬP KHO ═══════════ -->
  <div class="modal-overlay" id="nhapkho-modal-overlay" onclick="closeNhapKhoModal(event)">
    <div class="modal-box" style="max-width:520px">
      <div class="modal-header">
        <div>
          <div class="modal-title">Nhập kho</div>
          <div class="modal-title" style="font-size:13px;font-weight:400;color:var(--text-muted)" id="nhapkho-tensp"></div>
        </div>
        <button class="modal-close" onclick="closeNhapKhoModal(true)">
          <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
            <path d="M2 2l12 12M14 2L2 14" />
          </svg>
        </button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="nhapkho-masp">
        <div style="display:flex;gap:12px;align-items:center;padding:12px;border:1px solid var(--border);border-radius:12px;background:var(--bg-page);margin-bottom:16px">
          <div id="nhapkho-sp-thumb" style="width:56px;height:56px;border-radius:10px;background:#fff;display:flex;align-items:center;justify-content:center;font-size:24px;overflow:hidden;flex-shrink:0">📦</div>
          <div style="flex:1;min-width:0">
            <div id="nhapkho-sp-sku" style="font-size:12px;color:var(--text-muted);margin-bottom:4px"></div>
            <div style="font-size:13px;color:var(--text-secondary)">Phiếu nhập cho sản phẩm đang chọn</div>
          </div>
        </div>
        <div style="margin-bottom:14px">
          <label class="form-label">Tồn kho hiện tại</label>
          <div id="nhapkho-hientai" style="font-size:20px;font-weight:600;color:var(--accent-mid);margin-top:4px">—</div>
        </div>
        <div>
          <label class="form-label">Số lượng nhập thêm</label>
          <input type="number" id="nhapkho-soluong" class="form-input" min="1" value="10" placeholder="Nhập số lượng...">
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn" onclick="closeNhapKhoModal(true)">Hủy</button>
        <button class="btn btn-primary" onclick="submitNhapKho()">
          <svg width="13" height="13" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
            <path d="M8 2v12M2 8l6 6 6-6" />
          </svg>
          Xác nhận nhập kho
        </button>
      </div>
    </div>
  </div>
  <div id="toast-container" style="position:fixed;bottom:24px;right:24px;z-index:9999;display:flex;flex-direction:column;gap:8px"></div>

  <!-- ═══════════ MODAL XEM SẢN PHẨM ═══════════ -->
  <div class="modal-overlay" id="sp-view-overlay" onclick="closeSpView(event)">
    <div class="modal-box" style="max-width:620px">
      <div class="modal-header">
        <div>
          <div class="modal-title" id="sp-view-title">Chi tiết sản phẩm</div>
          <div style="font-size:12px;color:var(--text-muted);margin-top:3px" id="sp-view-sub"></div>
        </div>
        <button class="modal-close" onclick="closeSpView(true)">
          <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
            <path d="M2 2l12 12M14 2L2 14" />
          </svg>
        </button>
      </div>
      <div class="modal-body" id="sp-view-body">
        <div class="modal-loading">
          <div class="modal-spinner"></div> Đang tải...
        </div>
      </div>
      <div class="modal-footer" id="sp-view-footer"></div>
    </div>
  </div>

  <!-- ═══════════ MODAL THÊM / SỬA SẢN PHẨM ═══════════ -->
  <div class="modal-overlay" id="sp-form-overlay" onclick="closeSpForm(event)">
    <div class="modal-box" style="max-width:600px">
      <div class="modal-header">
        <div>
          <div class="modal-title" id="sp-form-title">Thêm sản phẩm mới</div>
          <div style="font-size:12px;color:var(--text-muted);margin-top:3px" id="sp-form-sub"></div>
        </div>
        <button class="modal-close" onclick="closeSpForm(true)">
          <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
            <path d="M2 2l12 12M14 2L2 14" />
          </svg>
        </button>
      </div>
      <div class="modal-body" id="sp-form-body">
        <div class="modal-loading">
          <div class="modal-spinner"></div> Đang tải...
        </div>
      </div>
      <div class="modal-footer" id="sp-form-footer"></div>
    </div>
  </div>

  <!-- ═══════════ MODAL XEM KHÁCH HÀNG ═══════════ -->
  <div class="modal-overlay" id="kh-view-overlay" onclick="closeKhView(event)">
    <div class="modal-box" style="max-width:640px">
      <div class="modal-header">
        <div>
          <div class="modal-title" id="kh-view-title">Chi tiết khách hàng</div>
          <div style="font-size:12px;color:var(--text-muted);margin-top:3px" id="kh-view-sub"></div>
        </div>
        <button class="modal-close" onclick="closeKhView(true)">
          <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
            <path d="M2 2l12 12M14 2L2 14" />
          </svg>
        </button>
      </div>
      <div class="modal-body" id="kh-view-body">
        <div class="modal-loading">
          <div class="modal-spinner"></div> Đang tải...
        </div>
      </div>
      <div class="modal-footer" id="kh-view-footer"></div>
    </div>
  </div>

  <!-- ═══════════ MODAL FORM KHÁCH HÀNG ═══════════ -->
  <div class="modal-overlay" id="kh-form-overlay" onclick="closeKhForm(event)">
    <div class="modal-box" style="max-width:560px">
      <div class="modal-header">
        <div>
          <div class="modal-title" id="kh-form-title">Thêm khách hàng</div>
          <div style="font-size:12px;color:var(--text-muted);margin-top:3px" id="kh-form-sub"></div>
        </div>
        <button class="modal-close" onclick="closeKhForm(true)">
          <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
            <path d="M2 2l12 12M14 2L2 14" />
          </svg>
        </button>
      </div>
      <div class="modal-body" id="kh-form-body"></div>
      <div class="modal-footer" id="kh-form-footer"></div>
    </div>
  </div>

  <!-- ═══════════ MODAL XEM VOUCHER / CAMPAIGN ═══════════ -->
  <div class="modal-overlay" id="promo-view-overlay" onclick="closePromoView(event)">
    <div class="modal-box" style="max-width:560px">
      <div class="modal-header">
        <div>
          <div class="modal-title" id="promo-view-title">Chi tiết voucher</div>
          <div style="font-size:12px;color:var(--text-muted);margin-top:3px" id="promo-view-sub"></div>
        </div>
        <button class="modal-close" onclick="closePromoView(true)">
          <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
            <path d="M2 2l12 12M14 2L2 14" />
          </svg>
        </button>
      </div>
      <div class="modal-body" id="promo-view-body">
        <div class="modal-loading">
          <div class="modal-spinner"></div> Đang tải...
        </div>
      </div>
      <div class="modal-footer" id="promo-view-footer"></div>
    </div>
  </div>

  <!-- ═══════════ MODAL FORM VOUCHER / CAMPAIGN ═══════════ -->
  <div class="modal-overlay" id="promo-form-overlay" onclick="closePromoForm(event)">
    <div class="modal-box" style="max-width:580px">
      <div class="modal-header">
        <div>
          <div class="modal-title" id="promo-form-title">Tạo voucher mới</div>
          <div style="font-size:12px;color:var(--text-muted);margin-top:3px" id="promo-form-sub"></div>
        </div>
        <button class="modal-close" onclick="closePromoForm(true)">
          <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
            <path d="M2 2l12 12M14 2L2 14" />
          </svg>
        </button>
      </div>
      <div class="modal-body" id="promo-form-body"></div>
      <div class="modal-footer" id="promo-form-footer"></div>
    </div>
  </div>

  <!-- ═══════════ MODAL PHIẾU NHẬP KHO HÀNG LOẠT ═══════════ -->
  <div class="modal-overlay" id="bulk-nhapkho-overlay" onclick="closeBulkNhapKho(event)">
    <div class="modal-box" style="max-width:640px">
      <div class="modal-header">
        <div>
          <div class="modal-title">📋 Phiếu Nhập Kho</div>
          <div style="font-size:12px;color:var(--text-muted);margin-top:2px" id="bulk-phieu-so"></div>
        </div>
        <button class="modal-close" onclick="closeBulkNhapKho(true)">
          <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
            <path d="M2 2l12 12M14 2L2 14" />
          </svg>
        </button>
      </div>
      <div class="modal-body">
        <!-- Thông tin phiếu -->
        <div class="form-row-2" style="margin-bottom:16px">
          <div class="form-group">
            <label class="form-label">Nhà cung cấp</label>
            <input type="text" id="bulk-ncc" class="form-input" placeholder="Tên nhà cung cấp...">
          </div>
          <div class="form-group">
            <label class="form-label">Ngày nhập</label>
            <input type="date" id="bulk-ngay" class="form-input">
          </div>
          <div class="form-group" style="grid-column:1/-1">
            <label class="form-label">Ghi chú</label>
            <textarea id="bulk-ghichu" class="form-input" rows="2" placeholder="Ghi chú phiếu nhập..."></textarea>
          </div>
        </div>

        <!-- Tìm & thêm sản phẩm -->
        <div class="form-group" style="margin-bottom:12px">
          <label class="form-label">Thêm sản phẩm vào phiếu</label>
          <input type="text" id="bulk-sp-search" class="form-input"
            placeholder="Gõ tên sản phẩm để tìm kiếm..."
            oninput="bulkSpSearch(this.value)">
          <div id="bulk-sp-results" style="display:none;margin-top:6px;border:1px solid var(--border);border-radius:var(--radius-md);overflow:hidden;max-height:180px;overflow-y:auto;background:var(--bg-card)"></div>
        </div>

        <!-- Danh sách SP trong phiếu -->
        <div class="modal-section-title" style="margin-bottom:8px">Danh sách nhập kho</div>
        <div id="bulk-items-list">
          <div style="text-align:center;padding:20px;color:var(--text-muted);font-size:13px">
            Chưa có sản phẩm. Tìm kiếm và thêm sản phẩm vào phiếu.
          </div>
        </div>
        <div class="phieu-preview" id="bulk-total-row" style="display:none;margin-top:12px">
          <div class="phieu-preview-row">
            <span>Tổng số lượng</span>
            <strong id="bulk-total-sp">0</strong>
          </div>
          <div class="phieu-preview-row" id="preview-tong-wrap" style="display:none">
            <span>Tổng giá trị nhập</span>
            <strong id="bulk-total-tien" style="color:var(--accent-mid)">—</strong>
          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button class="btn" onclick="closeBulkNhapKho(true)">Hủy</button>
        <button class="btn btn-primary" id="bulk-submit-btn" onclick="submitBulkNhapKho()">
          <svg width="13" height="13" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
            <path d="M2 8l4 4 8-8" />
          </svg>
          Xác nhận nhập kho
        </button>
      </div>
    </div>
  </div>

  <!-- ═══════════ MODAL XEM QUẢNG CÁO ═══════════ -->
  <div class="modal-overlay" id="ads-view-overlay" onclick="closeAdsView(event)">
    <div class="modal-box" style="max-width:600px">
      <div class="modal-header">
        <div>
          <div class="modal-title" id="ads-view-title">Chi tiết quảng cáo</div>
          <div style="font-size:12px;color:var(--text-muted);margin-top:3px" id="ads-view-sub"></div>
        </div>
        <button class="modal-close" onclick="closeAdsView(true)">
          <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
            <path d="M2 2l12 12M14 2L2 14" />
          </svg>
        </button>
      </div>
      <div class="modal-body" id="ads-view-body">
        <div class="modal-loading">
          <div class="modal-spinner"></div> Đang tải...
        </div>
      </div>
      <div class="modal-footer" id="ads-view-footer"></div>
    </div>
  </div>

  <!-- ═══════════ MODAL FORM QUẢNG CÁO ═══════════ -->
  <div class="modal-overlay" id="ads-form-overlay" onclick="closeAdsForm(event)">
    <div class="modal-box" style="max-width:580px">
      <div class="modal-header">
        <div>
          <div class="modal-title" id="ads-form-title">Thêm quảng cáo</div>
          <div style="font-size:12px;color:var(--text-muted);margin-top:3px" id="ads-form-sub"></div>
        </div>
        <button class="modal-close" onclick="closeAdsForm(true)">
          <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
            <path d="M2 2l12 12M14 2L2 14" />
          </svg>
        </button>
      </div>
      <div class="modal-body" id="ads-form-body"></div>
      <div class="modal-footer" id="ads-form-footer"></div>
    </div>
  </div>
</body>

</html>
