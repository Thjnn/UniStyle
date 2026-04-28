<?php
require_once __DIR__ . '/../config/db.php';

// ── Đọc params ────────────────────────────────────────────────────────────────
$search  = trim($_GET['sp_q']      ?? '');
$danhmuc = trim($_GET['danhmuc']   ?? '');
$limit   = isset($_GET['sp_limit']) ? (int)$_GET['sp_limit'] : 10;

// ── Danh sách danh mục từ DB ──────────────────────────────────────────────────
$dm_list = [];
$dm_res  = $conn->query("SELECT madanhmuc, tendanhmuc FROM danhmuc ORDER BY tendanhmuc");
if ($dm_res) while ($r = $dm_res->fetch_assoc()) $dm_list[] = $r;

// ── WHERE động ───────────────────────────────────────────────────────────────
$where = [];
if ($search !== '') {
    $s       = $conn->real_escape_string($search);
    $where[] = "sp.TenSP LIKE '%$s%'";
}
if ($danhmuc !== '') {
    $where[] = "sp.madanhmuc = " . (int)$danhmuc;
}
$where_sql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

// ── Đếm tổng ─────────────────────────────────────────────────────────────────
$total_sp = (int)$conn->query("SELECT COUNT(*) as c FROM sanpham sp $where_sql")->fetch_assoc()['c'];

// ── Query chính ───────────────────────────────────────────────────────────────
$result = $conn->query("
    SELECT sp.MaSP, sp.TenSP, sp.GiaBan, sp.SoLuongTon, sp.Hinh,
           sp.NoiBat, dm.tendanhmuc,
           COALESCE(ban.da_ban, 0) AS da_ban
    FROM sanpham sp
    LEFT JOIN danhmuc dm ON dm.madanhmuc = sp.madanhmuc
    LEFT JOIN (
        SELECT MaSP, SUM(soluong) AS da_ban
        FROM chitietdathang GROUP BY MaSP
    ) ban ON ban.MaSP = sp.MaSP
    $where_sql
    ORDER BY sp.MaSP DESC
    LIMIT $limit
");
$has_more = $total_sp > $limit;

// ── Helpers ───────────────────────────────────────────────────────────────────
function spBadge($ton)
{
    if ($ton <= 0)  return ['badge-red',   'Hết hàng'];
    if ($ton <= 20) return ['badge-amber', 'Sắp hết'];
    return                 ['badge-green', 'Kinh doanh'];
}
function spFill($ton)
{
    if ($ton <= 0)   return ['stock-low',  0];
    if ($ton <= 20)  return ['stock-low',  max(4, round($ton / 20 * 100))];
    if ($ton <= 100) return ['stock-mid',  min(100, round($ton / 100 * 100))];
    return                  ['stock-high', min(100, round($ton / 500 * 100))];
}
?>

<!-- ── Filter chips ── -->
<div class="filter-row" id="prod-filter-row">
    <button class="filter-chip <?= $danhmuc === '' ? 'active' : '' ?>"
        onclick="prodFilter(this,'')">
        Tất cả (<?= $total_sp ?>)
    </button>
    <?php foreach ($dm_list as $dm):
        $cnt = (int)$conn->query("SELECT COUNT(*) as c FROM sanpham WHERE madanhmuc={$dm['madanhmuc']}")->fetch_assoc()['c'];
    ?>
        <button class="filter-chip <?= $danhmuc == (string)$dm['madanhmuc'] ? 'active' : '' ?>"
            onclick="prodFilter(this,'<?= $dm['madanhmuc'] ?>')">
            <?= htmlspecialchars($dm['tendanhmuc']) ?> (<?= $cnt ?>)
        </button>
    <?php endforeach; ?>

</div>

<!-- ── Table card ── -->
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
                <?php if ($result && $result->num_rows > 0):
                    while ($row = $result->fetch_assoc()):
                        [$badgeCls, $badgeLabel] = spBadge($row['SoLuongTon']);
                        [$fillCls,  $fillPct]   = spFill($row['SoLuongTon']);
                        $sku = 'SP-' . str_pad($row['MaSP'], 3, '0', STR_PAD_LEFT);
                        $tonColor = $row['SoLuongTon'] <= 0  ? 'var(--danger)'  : ($row['SoLuongTon'] <= 20 ? 'var(--warning)' : '');
                        $tonLabel = $row['SoLuongTon'] <= 0  ? 'Hết' : $row['SoLuongTon'];
                ?>
                        <tr data-pid="<?= $row['MaSP'] ?>">
                            <td>
                                <div class="td-flex">
                                    <div class="prod-thumb">
                                        <?php if ($row['Hinh']): ?>
                                            <img src="../assets/file_anh/<?= htmlspecialchars($row['Hinh']) ?>"
                                                onerror="this.parentNode.textContent='📦'"
                                                style="width:100%;height:100%;object-fit:cover;border-radius:6px">
                                            <?php else: ?>📦<?php endif; ?>
                                    </div>
                                    <div>
                                        <div class="prod-name"><?= htmlspecialchars($row['TenSP']) ?></div>
                                        <div class="prod-sku"><?= $sku ?></div>
                                    </div>
                                </div>
                            </td>
                            <td><?= htmlspecialchars($row['tendanhmuc'] ?? '—') ?></td>
                            <td><?= number_format($row['GiaBan'], 0, ',', '.') ?>đ</td>
                            <td>
                                <div class="stock-wrap">
                                    <div class="stock-track">
                                        <div class="stock-fill <?= $fillCls ?>" style="width:<?= $fillPct ?>%"></div>
                                    </div>
                                    <span <?= $tonColor ? "style=\"color:$tonColor\"" : '' ?>>
                                        <?= $tonLabel ?>
                                    </span>
                                </div>
                            </td>
                            <td><?= number_format($row['da_ban'], 0, ',', '.') ?></td>
                            <td><span class="badge <?= $badgeCls ?>"><?= $badgeLabel ?></span></td>
                            <td>
                                <div class="actions">

                                    <button class="act-btn" onclick="openSpView(<?= $row['MaSP'] ?>)">Xem</button>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile;

                    if ($has_more): ?>
                        <tr>
                            <td colspan="7" style="text-align:center;padding:14px">
                                <button class="btn-load-more" onclick="prodLoadMore(<?= $limit + 10 ?>)">
                                    Xem thêm (còn <?= $total_sp - $limit ?> sản phẩm)
                                </button>
                            </td>
                        </tr>
                    <?php endif;

                else: ?>
                    <tr>
                        <td colspan="7" style="text-align:center;padding:28px;color:var(--text-muted)">
                            Không tìm thấy sản phẩm nào.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    function prodUpdateUrl(params) {
        const url = new URL(window.location.href);
        url.searchParams.set('page', 'products');
        for (const [k, v] of Object.entries(params)) {
            if (v === '') url.searchParams.delete(k);
            else url.searchParams.set(k, v);
        }
        window.location.href = url.toString();
    }

    function prodFilter(el, madanhmuc) {
        prodUpdateUrl({
            danhmuc: madanhmuc,
            sp_limit: 10,
            sp_q: ''
        });
    }
    let _prodTimer;

    function prodSearchDebounce(val) {
        clearTimeout(_prodTimer);
        _prodTimer = setTimeout(() => prodUpdateUrl({
            sp_q: val,
            sp_limit: 10,
            danhmuc: ''
        }), 400);
    }

    function prodLoadMore(newLimit) {
        const url = new URL(window.location.href);
        url.searchParams.set('page', 'products');
        url.searchParams.set('sp_limit', newLimit);
        window.location.href = url.toString();
    }
    // Kích hoạt trang products nếu URL có page=products
    (function() {
        const p = new URLSearchParams(window.location.search).get('page');
        if (p === 'products') {
            document.querySelectorAll('.page').forEach(x => x.classList.remove('active'));
            document.querySelectorAll('.nav-item').forEach(x => x.classList.remove('active'));
            const pg = document.getElementById('page-products');
            if (pg) pg.classList.add('active');
            document.querySelectorAll('.nav-item').forEach(n => {
                if (n.getAttribute('onclick')?.includes("'products'")) n.classList.add('active');
            });
            const t = document.getElementById('page-title');
            if (t) t.textContent = 'Quản lý sản phẩm';
        }
    })();
</script>