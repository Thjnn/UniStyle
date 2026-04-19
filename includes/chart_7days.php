<?php
require_once __DIR__ . '/../config/db.php';

// Lấy doanh thu 7 ngày gần nhất
$sql = "
    SELECT 
        DATE(ngaydat) as ngay,
        SUM(tongtien) as doanhthu
    FROM dathang
    WHERE ngaydat >= CURDATE() - INTERVAL 6 DAY
      AND trangthai != 'Hoàn trả'
    GROUP BY DATE(ngaydat)
    ORDER BY ngay ASC
";

$result = $conn->query($sql);

// Tạo mảng mặc định 7 ngày = 0
$data = [];
for ($i = 6; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $data[$date] = 0;
}

// Gán dữ liệu từ DB vào
while ($row = $result->fetch_assoc()) {
    $data[$row['ngay']] = (float)$row['doanhthu'];
}

// Tìm max để tính % chiều cao
$max = max($data);
if ($max == 0) $max = 1;

// Hàm lấy thứ
function thuVN($date)
{
    $thu = ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'];
    return $thu[date('w', strtotime($date))];
}

// Xuất HTML
foreach ($data as $date => $value):
    $percent = ($value / $max) * 100;
    $trieu = round($value / 1000000, 1);
?>
    <div class="bar-col">
        <div
            class="bar <?= ($date == date('Y-m-d')) ? 'active-bar' : '' ?>"
            style="height: <?= $percent ?>%"
            title="<?= thuVN($date) ?>: <?= $trieu ?>tr">
        </div>
        <div class="bar-label"><?= thuVN($date) ?></div>
    </div>
<?php endforeach; ?>