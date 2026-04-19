<?php
require_once __DIR__ . '/../config/db.php';

// Lấy top 5 sản phẩm bán chạy
$sql = "
    SELECT 
        sp.masp,
        sp.tensp,
        sp.Hinh,   
        dm.tendanhmuc,
        SUM(ct.soluong) AS da_ban,
        SUM(ct.soluong * ct.dongia) AS doanh_thu
    FROM chitietdathang ct
    JOIN sanpham sp ON ct.masp = sp.masp
    LEFT JOIN danhmuc dm ON sp.madanhmuc = dm.madanhmuc
    JOIN dathang dh ON ct.madh = dh.madh
    WHERE dh.trangthai != 'Hoàn trả'
    GROUP BY sp.masp
    ORDER BY da_ban DESC
    LIMIT 5
";

$result = $conn->query($sql);

// format tiền
function formatTrieu($tien)
{
    return round($tien / 1000000, 1) . 'tr';
}


?>

<tbody>
    <?php while ($row = $result->fetch_assoc()):
        $trend = rand(-10, 15); // fake tạm %
        $badge = $trend >= 0 ? 'badge-green' : 'badge-amber';
        $icon  = $trend >= 0 ? '▲' : '▼';
    ?>
        <tr>
            <td>
                <div class="td-flex">
                    <div class="prod-thumb">
                        <img
                            src="../assets/file_anh/San_Pham/<?= isset($row['Hinh']) ? $row['Hinh'] : 'default.png' ?>"
                            alt="<?= $row['tensp'] ?>"
                            style="width:40px;height:40px;object-fit:cover;border-radius:6px;">
                    </div>
                    <div>
                        <div class="prod-name"><?= $row['tensp'] ?></div>
                        <div class="prod-sku"><?= $row['tendanhmuc'] ?? '---' ?></div>
                    </div>
                </div>
            </td>
            <td><strong><?= number_format($row['da_ban']) ?></strong></td>
            <td><?= formatTrieu($row['doanh_thu']) ?></td>
            <td>
                <span class="badge <?= $badge ?>">
                    <?= $icon . ' ' . abs($trend) ?>%
                </span>
            </td>
        </tr>
    <?php endwhile; ?>
</tbody>