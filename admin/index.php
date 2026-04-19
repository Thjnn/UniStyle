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
          <span class="nav-badge badge-blue-soft">148</span>
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
          <span class="nav-badge badge-red-soft">5</span>
        </button>

        <button class="nav-item" onclick="showPage('promotions', this)">
          <svg class="nav-icon" viewBox="0 0 16 16">
            <path d="M2 8c0-3.3 2.7-6 6-6s6 2.7 6 6-2.7 6-6 6" />
            <path d="M8 5v3l2 1.5" />
          </svg>
          Khuyến mãi
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
            <!-- Nút xuất báo cáo -->
            <div class="sec-actions">
              <button class="btn">
                <svg
                  width="13"
                  height="13"
                  viewBox="0 0 16 16"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="1.6"
                  stroke-linecap="round">
                  <path d="M13 10v4H3v-4M8 2v8M5 5l3-3 3 3" />
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
              <div class="sec-sub">148 sản phẩm đang kinh doanh</div>
            </div>
            <!-- Nút xuất báo cáo -->
            <div class="sec-actions">
              <button class="btn">
                <svg
                  width="13"
                  height="13"
                  viewBox="0 0 16 16"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="1.6"
                  stroke-linecap="round">
                  <path d="M13 10v4H3v-4M8 2v8M5 5l3-3 3 3" />
                </svg>
                Xuất báo cáo
              </button>
              <button class="btn btn-primary">+ Thêm sản phẩm</button>
            </div>

          </div>

          <div class="filter-row">
            <button class="filter-chip active" onclick="filterChip(this)">
              Tất cả
            </button>
            <button class="filter-chip" onclick="filterChip(this)">
              Bút viết
            </button>
            <button class="filter-chip" onclick="filterChip(this)">
              Giấy &amp; Tập
            </button>
            <button class="filter-chip" onclick="filterChip(this)">
              Dụng cụ kẹp
            </button>
            <button class="filter-chip" onclick="filterChip(this)">
              Máy văn phòng
            </button>
            <button class="filter-chip" onclick="filterChip(this)">
              Khác
            </button>
            <input class="search-input" placeholder="Tìm sản phẩm, SKU..." />
          </div>

          <div class="card card-full">
            <div class="table-wrap">
              <table>
                <thead>
                  <tr>
                    <th>Sản phẩm</th>
                    <th>Danh mục</th>
                    <th>Giá bán</th>
                    <th>Tồn kho</th>
                    <th>Đã bán</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>
                      <div class="td-flex">
                        <div class="prod-thumb">✏️</div>
                        <div>
                          <div class="prod-name">
                            Bút bi Thiên Long TL-027
                          </div>
                          <div class="prod-sku">SKU: BTL-027-XNH</div>
                        </div>
                      </div>
                    </td>
                    <td>Bút viết</td>
                    <td>11.000đ</td>
                    <td>
                      <div class="stock-wrap">
                        <div class="stock-track">
                          <div
                            class="stock-fill stock-high"
                            style="width: 75%"></div>
                        </div>
                        <span>450</span>
                      </div>
                    </td>
                    <td>1,240</td>
                    <td><span class="badge badge-green">Kinh doanh</span></td>
                    <td>
                      <div class="actions">
                        <button class="act-btn">Sửa</button><button class="act-btn danger">Xóa</button>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div class="td-flex">
                        <div class="prod-thumb">📒</div>
                        <div>
                          <div class="prod-name">
                            Tập học sinh Hồng Hà 96tr
                          </div>
                          <div class="prod-sku">SKU: TAP-HH-96</div>
                        </div>
                      </div>
                    </td>
                    <td>Giấy &amp; Tập</td>
                    <td>9.000đ</td>
                    <td>
                      <div class="stock-wrap">
                        <div class="stock-track">
                          <div
                            class="stock-fill stock-mid"
                            style="width: 40%"></div>
                        </div>
                        <span style="color: var(--warning)">120</span>
                      </div>
                    </td>
                    <td>980</td>
                    <td><span class="badge badge-green">Kinh doanh</span></td>
                    <td>
                      <div class="actions">
                        <button class="act-btn">Sửa</button><button class="act-btn danger">Xóa</button>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div class="td-flex">
                        <div class="prod-thumb">📎</div>
                        <div>
                          <div class="prod-name">
                            Kẹp bướm Deli 51mm (hộp 12c)
                          </div>
                          <div class="prod-sku">SKU: KBD-51-12</div>
                        </div>
                      </div>
                    </td>
                    <td>Dụng cụ kẹp</td>
                    <td>28.000đ</td>
                    <td>
                      <div class="stock-wrap">
                        <div class="stock-track">
                          <div
                            class="stock-fill stock-high"
                            style="width: 60%"></div>
                        </div>
                        <span>180</span>
                      </div>
                    </td>
                    <td>762</td>
                    <td><span class="badge badge-green">Kinh doanh</span></td>
                    <td>
                      <div class="actions">
                        <button class="act-btn">Sửa</button><button class="act-btn danger">Xóa</button>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div class="td-flex">
                        <div class="prod-thumb">🖊️</div>
                        <div>
                          <div class="prod-name">
                            Bút mực Pentel EnerGel 0.5
                          </div>
                          <div class="prod-sku">SKU: BPN-EG-05</div>
                        </div>
                      </div>
                    </td>
                    <td>Bút viết</td>
                    <td>32.000đ</td>
                    <td>
                      <div class="stock-wrap">
                        <div class="stock-track">
                          <div
                            class="stock-fill stock-low"
                            style="width: 15%"></div>
                        </div>
                        <span style="color: var(--danger)">18</span>
                      </div>
                    </td>
                    <td>641</td>
                    <td><span class="badge badge-amber">Sắp hết</span></td>
                    <td>
                      <div class="actions">
                        <button class="act-btn">Sửa</button><button class="act-btn">Nhập kho</button>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div class="td-flex">
                        <div class="prod-thumb">🖨️</div>
                        <div>
                          <div class="prod-name">
                            Máy in HP LaserJet M111w
                          </div>
                          <div class="prod-sku">SKU: HP-M111W</div>
                        </div>
                      </div>
                    </td>
                    <td>Máy văn phòng</td>
                    <td>2.990.000đ</td>
                    <td>
                      <div class="stock-wrap">
                        <div class="stock-track">
                          <div
                            class="stock-fill stock-mid"
                            style="width: 30%"></div>
                        </div>
                        <span style="color: var(--warning)">9</span>
                      </div>
                    </td>
                    <td>94</td>
                    <td><span class="badge badge-green">Kinh doanh</span></td>
                    <td>
                      <div class="actions">
                        <button class="act-btn">Sửa</button><button class="act-btn danger">Xóa</button>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div class="td-flex">
                        <div class="prod-thumb">🗂️</div>
                        <div>
                          <div class="prod-name">
                            Bìa file A4 Deli EG17150
                          </div>
                          <div class="prod-sku">SKU: BF-EG17150</div>
                        </div>
                      </div>
                    </td>
                    <td>Khác</td>
                    <td>12.000đ</td>
                    <td>
                      <div class="stock-wrap">
                        <div class="stock-track">
                          <div
                            class="stock-fill stock-low"
                            style="width: 0%"></div>
                        </div>
                        <span style="color: var(--danger); font-weight: 600">Hết</span>
                      </div>
                    </td>
                    <td>320</td>
                    <td><span class="badge badge-red">Hết hàng</span></td>
                    <td>
                      <div class="actions">
                        <button class="act-btn">Sửa</button><button class="act-btn">Nhập kho</button>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <!-- /PAGE PRODUCTS -->

        <!-- ═══════════════════════════════════════
           PAGE: CUSTOMERS
      ═══════════════════════════════════════ -->
        <div class="page" id="page-customers">
          <div class="sec-header">
            <div>
              <div class="sec-title">Quản lý khách hàng</div>
              <div class="sec-sub">1.842 khách hàng đã đăng ký</div>
            </div>
            <div class="sec-actions">
              <button class="btn">Xuất danh sách</button>
              <button class="btn btn-primary">+ Thêm khách hàng</button>
            </div>
          </div>

          <div class="filter-row">
            <button class="filter-chip active" onclick="filterChip(this)">
              Tất cả (1.842)
            </button>
            <button class="filter-chip" onclick="filterChip(this)">
              Cá nhân
            </button>
            <button class="filter-chip" onclick="filterChip(this)">
              Doanh nghiệp
            </button>
            <button class="filter-chip" onclick="filterChip(this)">
              VIP
            </button>
            <input
              class="search-input"
              placeholder="Tìm tên, số điện thoại, email..." />
          </div>

          <div class="card card-full">
            <div class="table-wrap">
              <table>
                <thead>
                  <tr>
                    <th>Khách hàng</th>
                    <th>Loại</th>
                    <th>Số điện thoại</th>
                    <th>Email</th>
                    <th>Tổng đơn</th>
                    <th>Tổng chi tiêu</th>
                    <th>Lần cuối mua</th>
                    <th>Thao tác</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>
                      <div class="td-flex">
                        <div
                          class="cust-avatar"
                          style="background: #eff6ff; color: #1d4ed8">
                          NA
                        </div>
                        Nguyễn Văn An
                      </div>
                    </td>
                    <td><span class="badge badge-gray">Cá nhân</span></td>
                    <td>0901 234 567</td>
                    <td style="color: var(--text-secondary)">
                      van.an@gmail.com
                    </td>
                    <td>24</td>
                    <td><strong>8,4tr</strong></td>
                    <td>Hôm nay</td>
                    <td>
                      <div class="actions">
                        <button class="act-btn">Xem</button><button class="act-btn">Đơn hàng</button>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div class="td-flex">
                        <div
                          class="cust-avatar"
                          style="background: #f0fdf4; color: #16a34a">
                          HP
                        </div>
                        Cty TNHH Hoàng Phát
                      </div>
                    </td>
                    <td>
                      <span class="badge badge-blue">Doanh nghiệp</span>
                    </td>
                    <td>028 3849 5566</td>
                    <td style="color: var(--text-secondary)">
                      mua.hang@hoanphat.vn
                    </td>
                    <td>186</td>
                    <td><strong>124,6tr</strong></td>
                    <td>13/03/2026</td>
                    <td>
                      <div class="actions">
                        <button class="act-btn">Xem</button><button class="act-btn">Đơn hàng</button>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div class="td-flex">
                        <div
                          class="cust-avatar"
                          style="background: #eff6ff; color: #1d4ed8">
                          TM
                        </div>
                        Trần Thị Mai
                      </div>
                    </td>
                    <td><span class="badge badge-gray">Cá nhân</span></td>
                    <td>0912 345 678</td>
                    <td style="color: var(--text-secondary)">
                      tran.mai@email.com
                    </td>
                    <td>41</td>
                    <td><strong>18,2tr</strong></td>
                    <td>14/03/2026</td>
                    <td>
                      <div class="actions">
                        <button class="act-btn">Xem</button><button class="act-btn">Đơn hàng</button>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div class="td-flex">
                        <div
                          class="cust-avatar"
                          style="background: #fef9c3; color: #854d0e">
                          BV
                        </div>
                        Phòng HC — Bảo Việt Group
                      </div>
                    </td>
                    <td><span class="badge badge-vip">VIP</span></td>
                    <td>024 3826 1234</td>
                    <td style="color: var(--text-secondary)">
                      hanhchinh@baoviet.vn
                    </td>
                    <td>410</td>
                    <td><strong>380,2tr</strong></td>
                    <td>12/03/2026</td>
                    <td>
                      <div class="actions">
                        <button class="act-btn">Xem</button><button class="act-btn">Đơn hàng</button>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div class="td-flex">
                        <div
                          class="cust-avatar"
                          style="background: #fef2f2; color: #dc2626">
                          MK
                        </div>
                        Vũ Minh Khoa
                      </div>
                    </td>
                    <td><span class="badge badge-gray">Cá nhân</span></td>
                    <td>0933 456 789</td>
                    <td style="color: var(--text-secondary)">
                      minh.khoa@gmail.com
                    </td>
                    <td>8</td>
                    <td><strong>2,1tr</strong></td>
                    <td>12/03/2026</td>
                    <td>
                      <div class="actions">
                        <button class="act-btn">Xem</button><button class="act-btn">Đơn hàng</button>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <!-- /PAGE CUSTOMERS -->

        <!-- ═══════════════════════════════════════
           PAGE: INVENTORY
      ═══════════════════════════════════════ -->
        <div class="page" id="page-inventory">
          <div class="sec-header">
            <div>
              <div class="sec-title">Quản lý kho hàng</div>
              <div class="sec-sub">5 mặt hàng cần nhập kho gấp</div>
            </div>
            <div class="sec-actions">
              <button class="btn">Lịch sử nhập kho</button>
              <button class="btn btn-primary">+ Phiếu nhập kho</button>
            </div>
          </div>

          <!-- KPI 3-col -->
          <div class="kpi-grid-3">
            <div class="kpi-card">
              <div class="kpi-header">
                <span class="kpi-label">Tổng SKU quản lý</span>
                <div class="kpi-icon" style="background: var(--accent-light)">
                  <svg
                    viewBox="0 0 16 16"
                    fill="none"
                    stroke="#1d4ed8"
                    stroke-width="1.6"
                    stroke-linecap="round">
                    <rect x="2" y="2" width="5" height="5" rx="1" />
                    <rect x="9" y="2" width="5" height="5" rx="1" />
                    <rect x="2" y="9" width="5" height="5" rx="1" />
                    <rect x="9" y="9" width="5" height="5" rx="1" />
                  </svg>
                </div>
              </div>
              <div class="kpi-value">148</div>
              <div class="kpi-foot">
                <span class="kpi-period">Đang kinh doanh</span>
              </div>
            </div>

            <div class="kpi-card" style="border-color: #fca5a5">
              <div class="kpi-header">
                <span class="kpi-label">Cần nhập kho gấp</span>
                <div class="kpi-icon" style="background: var(--danger-bg)">
                  <svg
                    viewBox="0 0 16 16"
                    fill="none"
                    stroke="#dc2626"
                    stroke-width="1.6"
                    stroke-linecap="round">
                    <path d="M8 2v7M8 12v1.5" />
                    <circle cx="8" cy="8" r="6.5" />
                  </svg>
                </div>
              </div>
              <div class="kpi-value" style="color: var(--danger)">5</div>
              <div class="kpi-foot">
                <span class="kpi-change trend-down">3 hết hàng, 2 sắp hết</span>
              </div>
            </div>

            <div class="kpi-card">
              <div class="kpi-header">
                <span class="kpi-label">Giá trị tồn kho</span>
                <div class="kpi-icon" style="background: #f0fdf4">
                  <svg
                    viewBox="0 0 16 16"
                    fill="none"
                    stroke="#16a34a"
                    stroke-width="1.6"
                    stroke-linecap="round">
                    <path d="M2 14L5.5 8l3 4 3-6 2 3" />
                  </svg>
                </div>
              </div>
              <div class="kpi-value">342,1tr</div>
              <div class="kpi-foot">
                <span class="kpi-change trend-up">▲ 5,8%</span>
                <span class="kpi-period">tháng này</span>
              </div>
            </div>
          </div>

          <div class="card card-full">
            <div class="table-wrap">
              <table>
                <thead>
                  <tr>
                    <th>Sản phẩm</th>
                    <th>Tồn kho</th>
                    <th>Mức tối thiểu</th>
                    <th>Mức tối đa</th>
                    <th>Giá nhập</th>
                    <th>Nhà cung cấp</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>
                      <div class="td-flex">
                        <div class="prod-thumb">🖊️</div>
                        <div>
                          <div class="prod-name">
                            Bút mực Pentel EnerGel 0.5
                          </div>
                          <div class="prod-sku">SKU: BPN-EG-05</div>
                        </div>
                      </div>
                    </td>
                    <td style="color: var(--danger); font-weight: 700">18</td>
                    <td>50</td>
                    <td>500</td>
                    <td>18.000đ</td>
                    <td style="color: var(--text-secondary)">Pentel VN</td>
                    <td><span class="badge badge-red">Cần nhập</span></td>
                    <td><button class="act-btn">Nhập kho</button></td>
                  </tr>
                  <tr>
                    <td>
                      <div class="td-flex">
                        <div class="prod-thumb">✏️</div>
                        <div>
                          <div class="prod-name">
                            Bút chì 2B Staedtler (hộp)
                          </div>
                          <div class="prod-sku">SKU: BC-STD-2B</div>
                        </div>
                      </div>
                    </td>
                    <td style="color: var(--danger); font-weight: 700">8</td>
                    <td>30</td>
                    <td>300</td>
                    <td>42.000đ</td>
                    <td style="color: var(--text-secondary)">Staedtler VN</td>
                    <td><span class="badge badge-red">Cần nhập</span></td>
                    <td><button class="act-btn">Nhập kho</button></td>
                  </tr>
                  <tr>
                    <td>
                      <div class="td-flex">
                        <div class="prod-thumb">🗂️</div>
                        <div>
                          <div class="prod-name">
                            Bìa file A4 Deli EG17150
                          </div>
                          <div class="prod-sku">SKU: BF-EG17150</div>
                        </div>
                      </div>
                    </td>
                    <td style="color: var(--danger); font-weight: 700">0</td>
                    <td>50</td>
                    <td>400</td>
                    <td>7.800đ</td>
                    <td style="color: var(--text-secondary)">Deli VN</td>
                    <td><span class="badge badge-red">Hết hàng</span></td>
                    <td><button class="act-btn">Nhập kho</button></td>
                  </tr>
                  <tr>
                    <td>
                      <div class="td-flex">
                        <div class="prod-thumb">📒</div>
                        <div>
                          <div class="prod-name">
                            Tập học sinh Hồng Hà 96tr
                          </div>
                          <div class="prod-sku">SKU: TAP-HH-96</div>
                        </div>
                      </div>
                    </td>
                    <td style="color: var(--warning); font-weight: 600">
                      120
                    </td>
                    <td>100</td>
                    <td>1000</td>
                    <td>5.500đ</td>
                    <td style="color: var(--text-secondary)">Hồng Hà</td>
                    <td><span class="badge badge-amber">Sắp hết</span></td>
                    <td><button class="act-btn">Nhập kho</button></td>
                  </tr>
                  <tr>
                    <td>
                      <div class="td-flex">
                        <div class="prod-thumb">✏️</div>
                        <div>
                          <div class="prod-name">
                            Bút bi Thiên Long TL-027
                          </div>
                          <div class="prod-sku">SKU: BTL-027-XNH</div>
                        </div>
                      </div>
                    </td>
                    <td>450</td>
                    <td>100</td>
                    <td>2000</td>
                    <td>6.200đ</td>
                    <td style="color: var(--text-secondary)">Thiên Long</td>
                    <td><span class="badge badge-green">Đủ hàng</span></td>
                    <td><button class="act-btn">Xem</button></td>
                  </tr>
                  <tr>
                    <td>
                      <div class="td-flex">
                        <div class="prod-thumb">📎</div>
                        <div>
                          <div class="prod-name">
                            Kẹp bướm Deli 51mm (hộp)
                          </div>
                          <div class="prod-sku">SKU: KBD-51-12</div>
                        </div>
                      </div>
                    </td>
                    <td>180</td>
                    <td>50</td>
                    <td>500</td>
                    <td>15.000đ</td>
                    <td style="color: var(--text-secondary)">Deli VN</td>
                    <td><span class="badge badge-green">Đủ hàng</span></td>
                    <td><button class="act-btn">Xem</button></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <!-- /PAGE INVENTORY -->
        <!-- ═══════════════════════════════════════
           PAGE: REPORT
      ═══════════════════════════════════════ -->
        <div class="page" id="page-reports">
          <div class="sec-header">
            <div>
              <div class="sec-title">Báo cáo doanh thu</div>
              <div class="sec-sub">Phân tích số liệu kinh doanh chi tiết</div>
            </div>
            <div class="sec-actions">
              <button class="btn">Tải xuống PDF</button>
              <button class="btn btn-primary">Xuất Excel</button>
            </div>
          </div>
          <div class="card">
            <div
              class="card-body"
              style="
                  padding: 40px;
                  text-align: center;
                  color: var(--text-muted);
                ">
              <p>Biểu đồ báo cáo chi tiết sẽ hiển thị ở đây.</p>
            </div>
          </div>
        </div>
        <!-- /PAGE REPORT -->
        <!-- ═══════════════════════════════════════
           PAGE: PROMOTIONS
      ═══════════════════════════════════════ -->
        <div class="page" id="page-promotions">
          <div class="sec-header">
            <div>
              <div class="sec-title">Chương trình Khuyến mãi</div>
              <div class="sec-sub">
                Quản lý mã giảm giá và chiến dịch marketing
              </div>
            </div>
            <div class="sec-actions">
              <button class="btn btn-primary">+ Tạo khuyến mãi mới</button>
            </div>
          </div>
          <div class="card card-full">
            <div class="table-wrap">
              <table>
                <thead>
                  <tr>
                    <th>Tên chương trình</th>
                    <th>Mã</th>
                    <th>Giảm giá</th>
                    <th>Thời hạn</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td><strong>Ưu đãi Hè 2026</strong></td>
                    <td>
                      <code
                        style="background: var(--bg-page); padding: 2px 5px">SUMMER26</code>
                    </td>
                    <td>15%</td>
                    <td>01/06 - 30/08</td>
                    <td><span class="badge badge-green">Đang chạy</span></td>
                    <td>
                      <button class="act-btn">Sửa</button>
                      <button class="act-btn">Xóa</button>
                    </td>
                  </tr>

                  <tr>
                    <td><strong>Back To School</strong></td>
                    <td>
                      <code
                        style="background: var(--bg-page); padding: 2px 5px">SCHOOL10</code>
                    </td>
                    <td>10%</td>
                    <td>01/08 - 15/09</td>
                    <td><span class="badge badge-green">Đang chạy</span></td>
                    <td>
                      <button class="act-btn">Sửa</button>
                      <button class="act-btn">Xóa</button>
                    </td>
                  </tr>

                  <tr>
                    <td><strong>Giảm giá giày Sneaker</strong></td>
                    <td>
                      <code
                        style="background: var(--bg-page); padding: 2px 5px">SHOE50K</code>
                    </td>
                    <td>-50.000đ</td>
                    <td>10/03 - 10/04</td>
                    <td>
                      <span class="badge badge-yellow">Sắp diễn ra</span>
                    </td>
                    <td>
                      <button class="act-btn">Sửa</button>
                      <button class="act-btn">Xóa</button>
                    </td>
                  </tr>

                  <tr>
                    <td><strong>Freeship toàn quốc</strong></td>
                    <td>
                      <code
                        style="background: var(--bg-page); padding: 2px 5px">FREESHIP</code>
                    </td>
                    <td>Miễn phí vận chuyển</td>
                    <td>01/01 - 31/12</td>
                    <td><span class="badge badge-green">Đang chạy</span></td>
                    <td>
                      <button class="act-btn">Sửa</button>
                      <button class="act-btn">Xóa</button>
                    </td>
                  </tr>

                  <tr>
                    <td><strong>Flash Sale Cuối Tuần</strong></td>
                    <td>
                      <code
                        style="background: var(--bg-page); padding: 2px 5px">FLASH20</code>
                    </td>
                    <td>20%</td>
                    <td>Thứ 7 - Chủ nhật</td>
                    <td><span class="badge badge-red">Đã kết thúc</span></td>
                    <td>
                      <button class="act-btn">Sửa</button>
                      <button class="act-btn">Xóa</button>
                    </td>
                  </tr>

                  <tr>
                    <td><strong>Combo Balo + VPP</strong></td>
                    <td>
                      <code
                        style="background: var(--bg-page); padding: 2px 5px">COMBO30</code>
                    </td>
                    <td>30%</td>
                    <td>05/04 - 20/04</td>
                    <td>
                      <span class="badge badge-yellow">Sắp diễn ra</span>
                    </td>
                    <td>
                      <button class="act-btn">Sửa</button>
                      <button class="act-btn">Xóa</button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <!-- /PAGE PROMOTIONS -->
        <!-- ═══════════════════════════════════════
           PAGE: SETTING
═══════════════════════════════════════ -->
        <div class="page" id="page-settings">
          <div class="sec-header">
            <div>
              <div class="sec-title">Cài đặt hệ thống</div>
              <div class="sec-sub">
                Cấu hình thông tin cửa hàng và bảo mật
              </div>
            </div>
          </div>

          <div class="grid-2">
            <!-- 🏪 THÔNG TIN CHUNG -->
            <div class="card">
              <div class="card-head">
                <div class="card-title">Thông tin chung</div>
              </div>
              <div class="card-body" style="padding: 20px">
                <div style="margin-bottom: 12px">
                  <label>Tên cửa hàng</label>
                  <input
                    type="text"
                    class="search-input"
                    value="Văn Phòng UniStyle" />
                </div>

                <div style="margin-bottom: 12px">
                  <label>Email</label>
                  <input
                    type="email"
                    class="search-input"
                    placeholder="example@gmail.com" />
                </div>

                <div style="margin-bottom: 12px">
                  <label>Số điện thoại</label>
                  <input
                    type="text"
                    class="search-input"
                    placeholder="0123456789" />
                </div>

                <div style="margin-bottom: 12px">
                  <label>Địa chỉ</label>
                  <input
                    type="text"
                    class="search-input"
                    placeholder="Địa chỉ cửa hàng" />
                </div>

                <button class="btn btn-primary">Lưu thay đổi</button>
              </div>
            </div>

            <!-- 👤 TÀI KHOẢN -->
            <div class="card">
              <div class="card-head">
                <div class="card-title">Tài khoản</div>
              </div>
              <div class="card-body" style="padding: 20px">
                <p style="font-size: 13px; color: var(--text-secondary)">
                  Quản lý mật khẩu và quyền truy cập của nhân viên.
                </p>

                <button class="btn" style="margin-top: 10px">
                  Đổi mật khẩu
                </button>
                <button class="btn" style="margin-top: 10px">
                  Quản lý nhân viên
                </button>
                <button class="btn" style="margin-top: 10px">
                  Quản lý khách hàng
                </button>
              </div>
            </div>

            <!-- 💰 THANH TOÁN -->
            <div class="card">
              <div class="card-head">
                <div class="card-title">Thanh toán</div>
              </div>
              <div class="card-body" style="padding: 20px">
                <label>
                  <input type="checkbox" checked /> COD (Thanh toán khi nhận
                  hàng) </label><br />

                <label>
                  <input type="checkbox" /> Chuyển khoản ngân hàng
                </label>

                <div style="margin-top: 10px">
                  <input
                    type="text"
                    class="search-input"
                    placeholder="Số tài khoản" />
                </div>

                <button class="btn btn-primary" style="margin-top: 10px">
                  Lưu
                </button>
              </div>
            </div>

            <!-- 🚚 VẬN CHUYỂN -->
            <div class="card">
              <div class="card-head">
                <div class="card-title">Vận chuyển</div>
              </div>
              <div class="card-body" style="padding: 20px">
                <div style="margin-bottom: 10px">
                  <label>Phí ship</label>
                  <input
                    type="number"
                    class="search-input"
                    placeholder="30000" />
                </div>

                <label>
                  <input type="checkbox" /> Miễn phí ship đơn trên 500k
                </label>

                <button class="btn btn-primary" style="margin-top: 10px">
                  Lưu
                </button>
              </div>
            </div>

            <!-- 🎨 GIAO DIỆN -->
            <div class="card">
              <div class="card-head">
                <div class="card-title">Giao diện</div>
              </div>
              <div class="card-body" style="padding: 20px">
                <div style="margin-bottom: 10px">
                  <label>Upload Banner</label>
                  <input type="file" />
                </div>

                <div style="margin-bottom: 10px">
                  <label>Màu chủ đạo</label>
                  <input type="color" />
                </div>

                <button class="btn btn-primary">Lưu</button>
              </div>
            </div>

            <!-- 🔒 BẢO MẬT -->
            <div class="card">
              <div class="card-head">
                <div class="card-title">Bảo mật</div>
              </div>
              <div class="card-body" style="padding: 20px">
                <label>
                  <input type="checkbox" /> Xác thực 2 bước (2FA) </label><br />

                <label>
                  <input type="checkbox" /> Đăng xuất tất cả thiết bị
                </label>

                <button class="btn btn-danger" style="margin-top: 10px">
                  Áp dụng
                </button>
              </div>
            </div>
          </div>
        </div>
      </main>
      <!-- /CONTENT -->
    </div>
    <!-- /MAIN -->
  </div>
  <!-- /SHELL -->


</body>

</html>