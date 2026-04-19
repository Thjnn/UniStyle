<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/helpers.php';

// ============================================================
//  1. ĐẾM SỐ LƯỢNG THEO TRẠNG THÁI — cho filter chips
// ============================================================
$row_count = $conn->query("
    SELECT 
        COUNT(*) AS tong,
        SUM(trangthai = 'Chờ xử lý') AS cho_xu_ly,
        SUM(trangthai = 'Đang giao')  AS dang_giao,
        SUM(trangthai = 'Hoàn thành') AS hoan_thanh,
        SUM(trangthai = 'Đã hủy')     AS da_huy
    FROM dathang
")->fetch_assoc();

$total        = (int)$row_count['tong'];
$status_count = [
    'Chờ xử lý' => (int)$row_count['cho_xu_ly'],
    'Đang giao'  => (int)$row_count['dang_giao'],
    'Hoàn thành' => (int)$row_count['hoan_thanh'],
    'Đã hủy'     => (int)$row_count['da_huy'],
];

// ============================================================
//  2. ĐỌC FILTER & LIMIT TỪ URL
// ============================================================
$valid_tt  = ['Chờ xử lý', 'Đang giao', 'Hoàn thành', 'Đã hủy'];
$filter_tt = isset($_GET['trangthai']) && in_array($_GET['trangthai'], $valid_tt)
    ? $_GET['trangthai'] : '';
$search    = isset($_GET['q']) ? trim($_GET['q']) : '';

// Logic giới hạn: mặc định 6 đơn hàng
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 6;

// ============================================================
//  3. XÂY WHERE ĐỘNG
// ============================================================
$where_parts = [];
if ($filter_tt !== '') {
    $tt_esc        = $conn->real_escape_string($filter_tt);
    $where_parts[] = "dh.trangthai = '$tt_esc'";
}
if ($search !== '') {
    $s             = $conn->real_escape_string($search);
    $where_parts[] = "(kh.tenkh LIKE '%$s%' OR dh.madh LIKE '%$s%')";
}
$where_sql = count($where_parts) > 0 ? 'WHERE ' . implode(' AND ', $where_parts) : '';

// ============================================================
//  4. QUERY DANH SÁCH ĐƠN HÀNG VỚI LIMIT
// ============================================================
$sql = "
    SELECT dh.madh, dh.ngaydat, dh.tongtien, dh.trangthai, kh.tenkh
    FROM dathang dh
    LEFT JOIN khachhang kh ON dh.makh = kh.makh
    $where_sql
    ORDER BY dh.madh DESC
    LIMIT $limit
";
$result = $conn->query($sql);

// Kiểm tra xem còn dữ liệu để hiện nút "Xem thêm" không
$sql_total_filtered = "SELECT COUNT(*) as total FROM dathang dh LEFT JOIN khachhang kh ON dh.makh = kh.makh $where_sql";
$total_filtered = $conn->query($sql_total_filtered)->fetch_assoc()['total'];
$has_more = $total_filtered > $limit;

// ============================================================
//  FORMAT TRẠNG THÁI
// ============================================================
function renderTrangThai($tt)
{
    switch ($tt) {
        case 'Chờ xử lý':
            return '<span class="badge badge-amber">Chờ xử lý</span>';
        case 'Đang giao':
            return '<span class="badge badge-blue">Đang giao</span>';
        case 'Hoàn thành':
            return '<span class="badge badge-green">Hoàn thành</span>';
        case 'Đã hủy':
            return '<span class="badge badge-red">Đã hủy</span>';
        default:
            return '<span class="badge">---</span>';
    }
}

function getSanPham($conn, $madh)
{
    $sql = "SELECT sp.TenSP, ct.soluong FROM chitietdathang ct JOIN sanpham sp ON ct.MaSP = sp.MaSP WHERE ct.madh = $madh LIMIT 3";
    $rs  = $conn->query($sql);
    $arr = [];
    while ($r = $rs->fetch_assoc()) {
        $arr[] = $r['TenSP'] . ' × ' . $r['soluong'];
    }
    return implode(', ', $arr) ?: '---';
}
?>

<div class="filter-row">
    <button class="filter-chip <?= $filter_tt === '' ? 'active' : '' ?>" onclick="filterChip(this, '')">
        Tất cả (<?= $total ?>)
    </button>
    <button class="filter-chip <?= $filter_tt === 'Chờ xử lý' ? 'active' : '' ?>" onclick="filterChip(this, 'Chờ xử lý')">
        Chờ xử lý (<?= $status_count['Chờ xử lý'] ?>)
    </button>
    <button class="filter-chip <?= $filter_tt === 'Đang giao' ? 'active' : '' ?>" onclick="filterChip(this, 'Đang giao')">
        Đang giao (<?= $status_count['Đang giao'] ?>)
    </button>
    <button class="filter-chip <?= $filter_tt === 'Hoàn thành' ? 'active' : '' ?>" onclick="filterChip(this, 'Hoàn thành')">
        Hoàn thành (<?= $status_count['Hoàn thành'] ?>)
    </button>
    <button class="filter-chip <?= $filter_tt === 'Đã hủy' ? 'active' : '' ?>" onclick="filterChip(this, 'Đã hủy')">
        Đã hủy (<?= $status_count['Đã hủy'] ?>)
    </button>
</div>

<?php if ($result->num_rows === 0): ?>
    <tr>
        <td colspan="7" style="text-align:center; padding: 24px; color: var(--text-muted)">Không tìm thấy đơn hàng nào.</td>
    </tr>
<?php else: ?>
    <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><strong>#DH-<?= $row['madh'] ?></strong></td>
            <td><?= htmlspecialchars($row['tenkh'] ?? '---') ?></td>
            <td><?= date('d/m/Y', strtotime($row['ngaydat'])) ?></td>
            <td style="color: var(--text-secondary)"><?= getSanPham($conn, $row['madh']) ?></td>
            <td><strong><?= formatVND($row['tongtien']) ?></strong></td>
            <td><?= renderTrangThai($row['trangthai']) ?></td>
            <td>
                <div class="actions">
                    <button class="act-btn">Xem</button>
                    <?php if ($row['trangthai'] === 'Chờ xử lý'): ?>
                        <button class="act-btn">Duyệt</button>
                    <?php elseif ($row['trangthai'] === 'Đang giao'): ?>
                        <button class="act-btn">Track</button>
                    <?php endif; ?>
                </div>
            </td>
        </tr>
    <?php endwhile; ?>

    <?php if ($has_more): ?>
        <tr>
            <td colspan="7" style="text-align: center; padding: 15px;">
                <button class="btn-load-more" onclick="loadMore(<?= $limit + 10 ?>)">
                    Xem thêm (Còn <?= $total_filtered - $limit ?> đơn)
                </button>
            </td>
        </tr>
    <?php endif; ?>
<?php endif; ?>

<style>
    .btn-load-more {
        background: #fff;
        border: 1px solid #ddd;
        padding: 8px 25px;
        border-radius: 20px;
        cursor: pointer;
        font-size: 13px;
        font-weight: 500;
        transition: all 0.2s;
        color: #555;
    }

    .btn-load-more:hover {
        background: #f0f0f0;
        border-color: #bbb;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }
</style>

<script>
    // Hàm dùng chung để cập nhật URL
    function updateOrderUrl(params) {
        const url = new URL(window.location.href);
        url.searchParams.set('page', 'orders');
        for (const [key, value] of Object.entries(params)) {
            if (value === '') url.searchParams.delete(key);
            else url.searchParams.set(key, value);
        }
        window.location.href = url.toString();
    }

    function filterChip(el, trangthai) {
        updateOrderUrl({
            'trangthai': trangthai,
            'limit': 10
        }); // Reset limit về 10 khi đổi bộ lọc
    }

    function loadMore(newLimit) {
        const url = new URL(window.location.href);

        url.searchParams.set('page', 'orders'); // 🔥 QUAN TRỌNG
        url.searchParams.set('limit', newLimit);

        window.location.href = url.toString();
    }

    // Tự động xử lý active cho menu điều hướng
    (function() {
        const params = new URLSearchParams(window.location.search);
        const page = params.get('page');
        if (page) {
            document.querySelectorAll('.page').forEach(p => p.classList.remove('active'));
            document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));
            const target = document.getElementById('page-' + page);
            if (target) target.classList.add('active');
            document.querySelectorAll('.nav-item').forEach(n => {
                if (n.getAttribute('onclick')?.includes("'" + page + "'")) n.classList.add('active');
            });
        }
    })();
</script>