<?php
require_once __DIR__ . '/../config/db.php';

$search = trim($_GET['kh_q']    ?? '');
$loai   = trim($_GET['kh_loai'] ?? '');
$format = strtolower(trim($_GET['format'] ?? 'excel'));

$where = [];
if ($search !== '') {
    $s = $conn->real_escape_string($search);
    $where[] = "(kh.tenkh LIKE '%$s%' OR kh.sdt LIKE '%$s%' OR kh.email LIKE '%$s%')";
}
if ($loai   !== '') {
    $l = $conn->real_escape_string($loai);
    $where[] = "kh.loai='$l'";
}
$where_sql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

$result = $conn->query("
    SELECT kh.makh,kh.tenkh,kh.sdt,kh.email,kh.diachi,kh.gioitinh,kh.ngaysinh,kh.loai,kh.ngay_dangky,
           COUNT(dh.madh) AS tong_don, COALESCE(SUM(dh.tongtien),0) AS tong_tien
    FROM khachhang kh LEFT JOIN dathang dh ON dh.makh=kh.makh
    $where_sql GROUP BY kh.makh ORDER BY kh.makh DESC
");
$rows = [];
if ($result) while ($r = $result->fetch_assoc()) $rows[] = $r;

$filename = 'danh-sach-khach-hang-' . date('d-m-Y');

if ($format === 'csv') {
    header('Content-Type: text/csv; charset=utf-8');
    header("Content-Disposition: attachment; filename=\"{$filename}.csv\"");
    $out = fopen('php://output', 'w');
    fwrite($out, "\xEF\xBB\xBF");
    fputcsv($out, ['Mã KH', 'Họ tên', 'Loại', 'SĐT', 'Email', 'Địa chỉ', 'Giới tính', 'Ngày sinh', 'Ngày đăng ký', 'Tổng đơn', 'Tổng chi tiêu']);
    foreach ($rows as $r) {
        fputcsv($out, ['KH-' . str_pad($r['makh'], 4, '0', STR_PAD_LEFT), $r['tenkh'], $r['loai'], $r['sdt'], $r['email'], $r['diachi'], $r['gioitinh'], $r['ngaysinh'], $r['ngay_dangky'], $r['tong_don'], $r['tong_tien']]);
    }
    fclose($out);
    exit;
}

header('Content-Type: application/vnd.ms-excel; charset=utf-8');
header("Content-Disposition: attachment; filename=\"{$filename}.xls\"");
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

        .vip {
            color: #92400e;
            font-weight: 600
        }

        .dn {
            color: #1d4ed8;
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
            border: none;
            padding: 3px 10px
        }
    </style>
</head>

<body>
    <table>
        <tr class="hdr">
            <td colspan="9">👥 DANH SÁCH KHÁCH HÀNG — Văn Phòng UniStyle</td>
        </tr>
        <tr class="meta">
            <td colspan="9">Xuất ngày: <?= date('d/m/Y H:i') ?> | Tổng: <?= count($rows) ?> khách hàng</td>
        </tr>
        <tr>
            <td colspan="9" style="border:none;padding:3px"></td>
        </tr>
        <tr>
            <th>Mã KH</th>
            <th>Họ tên</th>
            <th>Loại</th>
            <th>SĐT</th>
            <th>Email</th>
            <th>Địa chỉ</th>
            <th>Giới tính</th>
            <th>Tổng đơn</th>
            <th>Tổng chi tiêu</th>
        </tr>
        <?php foreach ($rows as $r):
            $cls = $r['loai'] === 'VIP' ? 'vip' : ($r['loai'] === 'Doanh nghiệp' ? 'dn' : '');
        ?>
            <tr>
                <td style="text-align:center">KH-<?= str_pad($r['makh'], 4, '0', STR_PAD_LEFT) ?></td>
                <td><?= htmlspecialchars($r['tenkh']) ?></td>
                <td class="<?= $cls ?>" style="text-align:center"><?= htmlspecialchars($r['loai']) ?></td>
                <td><?= htmlspecialchars($r['sdt']) ?></td>
                <td><?= htmlspecialchars($r['email']) ?></td>
                <td><?= htmlspecialchars($r['diachi']) ?></td>
                <td style="text-align:center"><?= htmlspecialchars($r['gioitinh']) ?></td>
                <td style="text-align:center"><?= $r['tong_don'] ?></td>
                <td style="text-align:right"><?= number_format($r['tong_tien'], 0, ',', '.') ?>đ</td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>

</html>