<?php
require_once __DIR__ . '/../config/db.php';

/* =========================
   1. TRUY VẤN DỮ LIỆU
========================= */

// KPI DASHBOARD
$sql_kpi = "
SELECT 
    COALESCE(SUM(tongtien),0) as tong_doanh_thu,
    (SELECT COUNT(*) FROM dathang WHERE DATE(ngaydat) = CURDATE()) as dh_hom_nay,
    (SELECT COUNT(*) FROM khachhang) as tong_kh
FROM dathang 
WHERE trangthai IN ('Hoàn thành','Đã giao')
";
$kpi = $conn->query($sql_kpi)->fetch_assoc();

// DOANH THU 7 NGÀY
$sql_7days = "
SELECT 
    d.date as ngay,
    COALESCE(SUM(t.tongtien),0) as doanh_thu
FROM (
    SELECT CURDATE() - INTERVAL 6 DAY as date UNION
    SELECT CURDATE() - INTERVAL 5 DAY UNION
    SELECT CURDATE() - INTERVAL 4 DAY UNION
    SELECT CURDATE() - INTERVAL 3 DAY UNION
    SELECT CURDATE() - INTERVAL 2 DAY UNION
    SELECT CURDATE() - INTERVAL 1 DAY UNION
    SELECT CURDATE()
) d
LEFT JOIN dathang t 
    ON DATE(t.ngaydat) = d.date 
    AND t.trangthai IN ('Hoàn thành','Đã giao')
GROUP BY d.date
ORDER BY d.date ASC
";
$revenue_7days = $conn->query($sql_7days)->fetch_all(MYSQLI_ASSOC);

// TOP SẢN PHẨM
$sql_top = "
SELECT 
    sp.TenSP, 
    SUM(ct.soluong) as da_ban,
    COALESCE(SUM(ct.soluong * ct.dongia),0) as doanh_thu
FROM chitietdathang ct
JOIN sanpham sp ON ct.MaSP = sp.MaSP
GROUP BY sp.MaSP, sp.TenSP
ORDER BY da_ban DESC
LIMIT 10
";
$top_products = $conn->query($sql_top)->fetch_all(MYSQLI_ASSOC);

/* =========================
   2. CẤU HÌNH HEADER EXCEL
========================= */
$filename = 'Bao-cao-Dashboard-' . date('d-m-Y');
header('Content-Type: application/vnd.ms-excel; charset=utf-8');
header("Content-Disposition: attachment; filename=\"{$filename}.xls\"");
header('Cache-Control: no-cache, must-revalidate');
header('Pragma: no-cache');
?>
<html xmlns:o="urn:schemas-microsoft-com:office:office"
    xmlns:x="urn:schemas-microsoft-com:office:excel"
    xmlns="http://www.w3.org/TR/REC-html40">

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th {
            background-color: #1d4ed8;
            color: #ffffff;
            font-weight: bold;
            text-align: center;
            padding: 10px;
            border: 1px solid #1e40af;
        }

        td {
            padding: 8px 10px;
            border: 1px solid #e2e8f0;
            vertical-align: middle;
        }

        tr:nth-child(even) td {
            background-color: #f8fafc;
        }

        .header-title {
            background: #eff6ff;
            font-weight: 700;
            font-size: 16pt;
            color: #1e40af;
            padding: 15px;
            text-align: center;
        }

        .section-title {
            background: #f1f5f9;
            font-weight: 700;
            font-size: 12pt;
            color: #334155;
            padding: 8px;
            border-left: 5px solid #1d4ed8;
        }

        .kpi-box {
            text-align: center;
            font-size: 14pt;
            font-weight: bold;
            color: #16a34a;
        }

        .ta-right {
            text-align: right;
        }

        .ta-center {
            text-align: center;
        }

        .text-muted {
            color: #64748b;
            font-size: 10pt;
        }
    </style>
</head>

<body>
    <table>
        <tr>
            <td colspan="4" class="header-title">📊 BÁO CÁO TỔNG QUAN HỆ THỐNG — UniStyle</td>
        </tr>
        <tr>
            <td colspan="4" class="ta-center text-muted">Xuất ngày: <?= date('d/m/Y H:i') ?></td>
        </tr>
        <tr>
            <td colspan="4" style="border:none; height:20px;"></td>
        </tr>

        <tr>
            <td colspan="4" class="section-title">1. Chỉ số quan trọng (KPI)</td>
        </tr>
        <tr>
            <th colspan="2">Chỉ tiêu</th>
            <th colspan="2">Giá trị hiện tại</th>
        </tr>
        <tr>
            <td colspan="2">💰 Tổng doanh thu (Hoàn thành)</td>
            <td colspan="2" class="kpi-box"><?= number_format($kpi['tong_doanh_thu'], 0, ',', '.') ?>đ</td>
        </tr>
        <tr>
            <td colspan="2">📦 Đơn hàng mới hôm nay</td>
            <td colspan="2" class="ta-center" style="font-weight:bold;"><?= $kpi['dh_hom_nay'] ?> đơn</td>
        </tr>
        <tr>
            <td colspan="2">👥 Tổng số khách hàng</td>
            <td colspan="2" class="ta-center"><?= $kpi['tong_kh'] ?> thành viên</td>
        </tr>

        <tr>
            <td colspan="4" style="border:none; height:20px;"></td>
        </tr>

        <tr>
            <td colspan="4" class="section-title">2. Doanh thu 7 ngày gần nhất</td>
        </tr>
        <tr>
            <th colspan="2">Ngày</th>
            <th colspan="2">Doanh thu</th>
        </tr>
        <?php foreach ($revenue_7days as $r): ?>
            <tr>
                <td colspan="2" class="ta-center"><?= date('d/m/Y', strtotime($r['ngay'])) ?></td>
                <td colspan="2" class="ta-right"><?= number_format($r['doanh_thu'], 0, ',', '.') ?>đ</td>
            </tr>
        <?php endforeach; ?>

        <tr>
            <td colspan="4" style="border:none; height:20px;"></td>
        </tr>

        <tr>
            <td colspan="4" class="section-title">3. Top 10 sản phẩm bán chạy nhất</td>
        </tr>
        <tr>
            <th style="width:50px">STT</th>
            <th>Tên sản phẩm</th>
            <th>Số lượng bán</th>
            <th>Doanh thu thu về</th>
        </tr>
        <?php
        $stt = 1;
        foreach ($top_products as $p): ?>
            <tr>
                <td class="ta-center"><?= $stt++ ?></td>
                <td><?= htmlspecialchars($p['TenSP']) ?></td>
                <td class="ta-center"><?= number_format($p['da_ban']) ?></td>
                <td class="ta-right"><?= number_format($p['doanh_thu'], 0, ',', '.') ?>đ</td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>

</html>