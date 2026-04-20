<?php
require_once __DIR__ . '/../config/db.php';

// ── Đọc filter từ URL (giống products_table.php) ────────────────────────────
$search  = trim($_GET['sp_q']    ?? '');
$danhmuc = trim($_GET['danhmuc'] ?? '');
$format  = strtolower(trim($_GET['format'] ?? 'excel')); // excel | csv

$where = [];
if ($search !== '') {
    $s       = $conn->real_escape_string($search);
    $where[] = "sp.TenSP LIKE '%$s%'";
}
if ($danhmuc !== '') {
    $where[] = "sp.madanhmuc = " . (int)$danhmuc;
}
$where_sql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

// ── Query toàn bộ (không LIMIT) ──────────────────────────────────────────────
$result = $conn->query("
    SELECT
        sp.MaSP,
        sp.TenSP,
        sp.GiaBan,
        sp.SoLuongTon,
        sp.SoLuotDanhGia,
        sp.NoiBat,
        dm.tendanhmuc,
        COALESCE(ban.da_ban, 0) AS da_ban
    FROM sanpham sp
    LEFT JOIN danhmuc dm ON dm.madanhmuc = sp.madanhmuc
    LEFT JOIN (
        SELECT MaSP, SUM(soluong) AS da_ban
        FROM chitietdathang GROUP BY MaSP
    ) ban ON ban.MaSP = sp.MaSP
    $where_sql
    ORDER BY sp.MaSP DESC
");

$rows = [];
if ($result) {
    while ($r = $result->fetch_assoc()) $rows[] = $r;
}

$filename = 'danh-sach-san-pham-' . date('d-m-Y');

// ════════════════════════════════════════════════════════
//  CSV
// ════════════════════════════════════════════════════════
if ($format === 'csv') {
    header('Content-Type: text/csv; charset=utf-8');
    header("Content-Disposition: attachment; filename=\"{$filename}.csv\"");
    header('Cache-Control: no-cache');

    $out = fopen('php://output', 'w');
    // BOM để Excel đọc được UTF-8
    fwrite($out, "\xEF\xBB\xBF");

    fputcsv($out, ['Mã SP', 'Tên sản phẩm', 'Danh mục', 'Giá bán (đ)', 'Tồn kho', 'Đã bán', 'Trạng thái', 'Nổi bật']);

    foreach ($rows as $r) {
        $ton = (int)$r['SoLuongTon'];
        $tt  = $ton <= 0 ? 'Hết hàng' : ($ton <= 20 ? 'Sắp hết' : 'Kinh doanh');
        fputcsv($out, [
            'SP-' . str_pad($r['MaSP'], 3, '0', STR_PAD_LEFT),
            $r['TenSP'],
            $r['tendanhmuc'] ?? '—',
            $r['GiaBan'],
            $ton,
            $r['da_ban'],
            $tt,
            $r['NoiBat'] ? 'Có' : 'Không',
        ]);
    }

    fclose($out);
    exit;
}

// ════════════════════════════════════════════════════════
//  EXCEL (HTML table với Content-Type xls — mở được trong Excel)
// ════════════════════════════════════════════════════════
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
    <!--[if gte mso 9]>
<xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet>
  <x:Name>Sản phẩm</x:Name>
  <x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions>
</x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml>
<![endif]-->
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12pt;
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
            padding: 8px 12px;
            border: 1px solid #1e40af;
        }

        td {
            padding: 7px 10px;
            border: 1px solid #e2e8f0;
            vertical-align: middle;
        }

        tr:nth-child(even) td {
            background-color: #f8fafc;
        }

        .badge-green {
            color: #166534;
            font-weight: 600;
        }

        .badge-amber {
            color: #92400e;
            font-weight: 600;
        }

        .badge-red {
            color: #991b1b;
            font-weight: 600;
        }

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
            border: none;
            padding: 10px;
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
        <!-- Tiêu đề báo cáo -->
        <tr class="header-row">
            <td colspan="8">📋 DANH SÁCH SẢN PHẨM — Văn Phòng UniStyle</td>
        </tr>
        <tr class="meta-row">
            <td colspan="8">
                Xuất ngày: <?= date('d/m/Y H:i') ?>
                <?= $search   ? " | Từ khóa: $search"                       : '' ?>
                <?= $danhmuc  ? " | Danh mục ID: $danhmuc"                  : '' ?>
                &nbsp;&nbsp;|&nbsp;&nbsp; Tổng: <?= count($rows) ?> sản phẩm
            </td>
        </tr>
        <tr>
            <td colspan="8" style="border:none;padding:4px"></td>
        </tr>

        <!-- Header bảng -->
        <tr>
            <th style="width:80px">Mã SP</th>
            <th style="width:300px">Tên sản phẩm</th>
            <th style="width:120px">Danh mục</th>
            <th style="width:110px">Giá bán</th>
            <th style="width:80px">Tồn kho</th>
            <th style="width:80px">Đã bán</th>
            <th style="width:100px">Trạng thái</th>
            <th style="width:70px">Nổi bật</th>
        </tr>

        <!-- Dữ liệu -->
        <?php foreach ($rows as $r):
            $ton  = (int)$r['SoLuongTon'];
            $cls  = $ton <= 0 ? 'badge-red' : ($ton <= 20 ? 'badge-amber' : 'badge-green');
            $tt   = $ton <= 0 ? 'Hết hàng'  : ($ton <= 20 ? 'Sắp hết'    : 'Kinh doanh');
            $maSP = 'SP-' . str_pad($r['MaSP'], 3, '0', STR_PAD_LEFT);
        ?>
            <tr>
                <td class="ta-center"><?= $maSP ?></td>
                <td><?= htmlspecialchars($r['TenSP']) ?></td>
                <td class="ta-center"><?= htmlspecialchars($r['tendanhmuc'] ?? '—') ?></td>
                <td class="ta-right"><?= number_format($r['GiaBan'], 0, ',', '.') ?>đ</td>
                <td class="ta-center <?= $ton <= 20 ? ($ton <= 0 ? 'badge-red' : 'badge-amber') : '' ?>"><?= $ton <= 0 ? 'Hết' : $ton ?></td>
                <td class="ta-center"><?= number_format($r['da_ban'], 0, ',', '.') ?></td>
                <td class="ta-center <?= $cls ?>"><?= $tt ?></td>
                <td class="ta-center"><?= $r['NoiBat'] ? '✔' : '' ?></td>
            </tr>
        <?php endforeach; ?>

        <!-- Tổng kết -->
        <tr>
            <td colspan="8" style="border:none;padding:4px"></td>
        </tr>
        <tr>
            <td colspan="3" style="font-weight:700;background:#f1f5f9;border:1px solid #e2e8f0">Tổng cộng</td>
            <td class="ta-right" style="font-weight:700;background:#f1f5f9;border:1px solid #e2e8f0">
                <?= number_format(array_sum(array_column($rows, 'GiaBan')), 0, ',', '.') ?>đ
            </td>
            <td class="ta-center" style="font-weight:700;background:#f1f5f9;border:1px solid #e2e8f0">
                <?= array_sum(array_column($rows, 'SoLuongTon')) ?>
            </td>
            <td class="ta-center" style="font-weight:700;background:#f1f5f9;border:1px solid #e2e8f0">
                <?= array_sum(array_column($rows, 'da_ban')) ?>
            </td>
            <td colspan="2" style="background:#f1f5f9;border:1px solid #e2e8f0"></td>
        </tr>
    </table>
</body>

</html>