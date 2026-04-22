<?php
require_once __DIR__ . '/../config/db.php';

$filter  = trim($_GET['kho_filter'] ?? 'all');
$search  = trim($_GET['kho_q']      ?? '');
$danhmuc = trim($_GET['kho_dm']     ?? '');
$format  = strtolower(trim($_GET['format'] ?? 'excel'));

$where = [];
switch ($filter) {
    case 'het':
        $where[] = "sp.SoLuongTon <= 0";
        break;
    case 'saphet':
        $where[] = "sp.SoLuongTon > 0 AND sp.SoLuongTon <= 20";
        break;
    case 'du':
        $where[] = "sp.SoLuongTon > 20";
        break;
}
if ($search !== '') {
    $s = $conn->real_escape_string($search);
    $where[] = "sp.TenSP LIKE '%$s%'";
}
if ($danhmuc !== '') {
    $where[] = "sp.madanhmuc=" . (int)$danhmuc;
}
$ws = $where ? 'WHERE ' . implode(' AND ', $where) : '';

$result = $conn->query("
    SELECT sp.MaSP,sp.TenSP,sp.GiaBan,sp.SoLuongTon,dm.tendanhmuc,
           COALESCE(ban.da_ban,0) AS da_ban,
           (sp.GiaBan*sp.SoLuongTon) AS gia_tri_ton
    FROM sanpham sp
    LEFT JOIN danhmuc dm ON dm.madanhmuc=sp.madanhmuc
    LEFT JOIN (SELECT MaSP,SUM(soluong) AS da_ban FROM chitietdathang GROUP BY MaSP) ban ON ban.MaSP=sp.MaSP
    $ws ORDER BY sp.SoLuongTon ASC
");
$rows = [];
if ($result) while ($r = $result->fetch_assoc()) $rows[] = $r;

$fn = 'bao-cao-kho-hang-' . date('d-m-Y');

function tt($ton)
{
    if ($ton <= 0) return 'Hết hàng';
    if ($ton <= 20) return 'Sắp hết';
    return 'Đủ hàng';
}

if ($format === 'csv') {
    header('Content-Type: text/csv; charset=utf-8');
    header("Content-Disposition: attachment; filename=\"{$fn}.csv\"");
    $out = fopen('php://output', 'w');
    fwrite($out, "\xEF\xBB\xBF");
    fputcsv($out, ['Mã SP', 'Tên sản phẩm', 'Danh mục', 'Tồn kho', 'Đã bán', 'Giá bán', 'Giá trị tồn', 'Trạng thái']);
    foreach ($rows as $r) fputcsv($out, ['SP-' . str_pad($r['MaSP'], 3, '0', STR_PAD_LEFT), $r['TenSP'], $r['tendanhmuc'], $r['SoLuongTon'], $r['da_ban'], $r['GiaBan'], $r['gia_tri_ton'], tt($r['SoLuongTon'])]);
    fclose($out);
    exit;
}

header('Content-Type: application/vnd.ms-excel; charset=utf-8');
header("Content-Disposition: attachment; filename=\"{$fn}.xls\"");
?>
<html>

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial;
            font-size: 11pt
        }

        table {
            border-collapse: collapse;
            width: 100%
        }

        th {
            background: #1d4ed8;
            color: #fff;
            padding: 7px 10px;
            border: 1px solid #1e40af;
            text-align: center
        }

        td {
            padding: 6px 10px;
            border: 1px solid #e2e8f0
        }

        tr:nth-child(even) td {
            background: #f8fafc
        }

        .red {
            color: #991b1b;
            font-weight: 700
        }

        .amber {
            color: #92400e;
            font-weight: 600
        }

        .green {
            color: #166534;
            font-weight: 600
        }

        .hdr td {
            background: #eff6ff;
            font-weight: 700;
            font-size: 13pt;
            border: none;
            padding: 10px
        }

        .meta td {
            color: #64748b;
            font-size: 10pt;
            border: none
        }
    </style>
</head>

<body>
    <table>
        <tr class="hdr">
            <td colspan="8">📦 BÁO CÁO TỒN KHO — Văn Phòng UniStyle</td>
        </tr>
        <tr class="meta">
            <td colspan="8">Xuất ngày: <?= date('d/m/Y H:i') ?> | Tổng: <?= count($rows) ?> sản phẩm | Giá trị tồn: <?= number_format(array_sum(array_column($rows, 'gia_tri_ton')), 0, ',', '.') ?>đ</td>
        </tr>
        <tr>
            <td colspan="8" style="border:none;padding:3px"></td>
        </tr>
        <tr>
            <th>Mã SP</th>
            <th>Tên sản phẩm</th>
            <th>Danh mục</th>
            <th>Tồn kho</th>
            <th>Đã bán</th>
            <th>Giá bán</th>
            <th>Giá trị tồn</th>
            <th>Trạng thái</th>
        </tr>
        <?php foreach ($rows as $r):
            $ton = $r['SoLuongTon'];
            $cls = $ton <= 0 ? 'red' : ($ton <= 20 ? 'amber' : 'green');
            $tt = tt($ton);
        ?>
            <tr>
                <td style="text-align:center">SP-<?= str_pad($r['MaSP'], 3, '0', STR_PAD_LEFT) ?></td>
                <td><?= htmlspecialchars($r['TenSP']) ?></td>
                <td><?= htmlspecialchars($r['tendanhmuc'] ?? '—') ?></td>
                <td class="<?= $cls ?>" style="text-align:center"><?= $ton <= 0 ? 'Hết' : $ton ?></td>
                <td style="text-align:center"><?= $r['da_ban'] ?></td>
                <td style="text-align:right"><?= number_format($r['GiaBan'], 0, ',', '.') ?>đ</td>
                <td style="text-align:right"><strong><?= number_format($r['gia_tri_ton'], 0, ',', '.') ?>đ</strong></td>
                <td class="<?= $cls ?>" style="text-align:center"><?= $tt ?></td>
            </tr>
        <?php endforeach; ?>
        <tr>
            <td colspan="3" style="font-weight:700;background:#f1f5f9;border:1px solid #e2e8f0">Tổng cộng</td>
            <td style="text-align:center;font-weight:700;background:#f1f5f9;border:1px solid #e2e8f0"><?= array_sum(array_column($rows, 'SoLuongTon')) ?></td>
            <td style="text-align:center;font-weight:700;background:#f1f5f9;border:1px solid #e2e8f0"><?= array_sum(array_column($rows, 'da_ban')) ?></td>
            <td style="background:#f1f5f9;border:1px solid #e2e8f0"></td>
            <td style="text-align:right;font-weight:700;background:#f1f5f9;border:1px solid #e2e8f0;color:#166534"><?= number_format(array_sum(array_column($rows, 'gia_tri_ton')), 0, ',', '.') ?>đ</td>
            <td style="background:#f1f5f9;border:1px solid #e2e8f0"></td>
        </tr>
    </table>
</body>

</html>