<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/helpers.php';

$sql = "
    SELECT 
        dh.madh,
        dh.tongtien,
        dh.trangthai,
        kh.tenkh
    FROM dathang dh
    LEFT JOIN khachhang kh ON dh.makh = kh.makh
    ORDER BY dh.ngaydat DESC
    LIMIT 5
";

$result = $conn->query($sql);



// màu trạng thái
function getBadgeClass($status)
{
    switch ($status) {
        case 'Chờ xử lý':
            return 'badge-amber';
        case 'Đang giao':
            return 'badge-blue';
        case 'Hoàn thành':
            return 'badge-green';
        case 'Đã hủy':
            return 'badge-red';
        default:
            return 'badge-gray';
    }
}
?>

<div class="order-list">
    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="order-row">
            <span class="order-id">#DH-<?= $row['madh'] ?></span>
            <span class="order-customer"><?= $row['tenkh'] ?? 'Khách lẻ' ?></span>
            <span class="order-amount"><?= formatVND($row['tongtien']) ?></span>
            <span class="badge <?= getBadgeClass($row['trangthai']) ?>">
                <?= $row['trangthai'] ?>
            </span>
        </div>
    <?php endwhile; ?>
</div>