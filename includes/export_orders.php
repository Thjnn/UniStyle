<?php
require_once __DIR__ . '/../config/db.php';

// ── TRUY VẤN DỮ LIỆU ────────────────────────────────────────────────────────
$sql = "SELECT 
            d.madh AS madon,
            k.tenkh AS hoten,
            d.ngaydat,
            d.tongtien,
            d.trangthai
        FROM dathang d
        LEFT JOIN khachhang k ON d.makh = k.makh
        ORDER BY d.ngaydat DESC";

$result = $conn->query($sql);
$rows = [];
if ($result) {
    while ($r = $result->fetch_assoc()) $rows[] = $r;
}

$filename = 'Danh-sach-don-hang-' . date('d-m-Y');

// ── CẤU HÌNH HEADER EXCEL ───────────────────────────────────────────────────
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

        /* Màu sắc cho trạng thái đơn hàng */
        .status-success {
            color: #166534;
            font-weight: bold;
        }

        /* Hoàn thành / Đã giao */
        .status-warning {
            color: #92400e;
            font-weight: bold;
        }

        /* Đang xử lý / Chờ duyệt */
        .status-danger {
            color: #991b1b;
            font-weight: bold;
        }

        /* Hủy đơn */

        .ta-right {
            text-align: right;
        }

        .ta-center {
            text-align: center;
        }

        .header-row td {
            background: #eff6ff;
            font-weight: 700;
            font-size: 14pt;
            padding: 10px;
            border: none;
        }

        .meta-row td {
            color: #64748b;
            font-size: 10pt;
            border: none;
            padding: 4px 10px;
        }
    </style>
</head>

<body>
    <table>
        <tr class="header-row">
            <td colspan="5">📦 DANH SÁCH ĐƠN HÀNG — UniStyle</td>
        </tr>
        <tr class="meta-row">
            <td colspan="5">
                Ngày xuất: <?= date('d/m/Y H:i') ?> | Tổng cộng: <?= count($rows) ?> đơn hàng
            </td>
        </tr>
        <tr>
            <td colspan="5" style="border:none; height:10px;"></td>
        </tr>

        <tr>
            <th style="width:100px">Mã Đơn</th>
            <th style="width:250px">Khách Hàng</th>
            <th style="width:150px">Ngày Đặt</th>
            <th style="width:150px">Tổng Tiền</th>
            <th style="width:150px">Trạng Thái</th>
        </tr>

        <?php if (!empty($rows)): ?>
            <?php foreach ($rows as $r):
                // Xử lý logic màu sắc trạng thái
                $tt = $r['trangthai'];
                $cls = '';
                if ($tt == 'Hoàn thành' || $tt == 'Đã giao') $cls = 'status-success';
                elseif ($tt == 'Hủy đơn') $cls = 'status-danger';
                else $cls = 'status-warning';
            ?>
                <tr>
                    <td class="ta-center">#<?= $r['madon'] ?></td>
                    <td><?= htmlspecialchars($r['hoten'] ?? 'Khách vãng lai') ?></td>
                    <td class="ta-center"><?= $r['ngaydat'] ? date('d/m/Y', strtotime($r['ngaydat'])) : '—' ?></td>
                    <td class="ta-right" style="font-weight:bold;"><?= number_format($r['tongtien'], 0, ',', '.') ?>đ</td>
                    <td class="ta-center <?= $cls ?>"><?= $tt ?></td>
                </tr>
            <?php endforeach; ?>

            <tr>
                <td colspan="3" style="font-weight:700; background:#f1f5f9; text-align:right;">TỔNG DOANH THU:</td>
                <td class="ta-right" style="font-weight:700; background:#f1f5f9; color:#1d4ed8;">
                    <?= number_format(array_sum(array_column($rows, 'tongtien')), 0, ',', '.') ?>đ
                </td>
                <td style="background:#f1f5f9;"></td>
            </tr>
        <?php else: ?>
            <tr>
                <td colspan="5" class="ta-center">Không tìm thấy đơn hàng nào trong hệ thống.</td>
            </tr>
        <?php endif; ?>
    </table>
</body>

</html>