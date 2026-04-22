<?php
require_once __DIR__ . '/../config/db.php';

// ── Params ───────────────────────────────────────────────────────────────────
$filter = trim($_GET['kho_filter'] ?? 'all'); // all | het | saphet | du
$search = trim($_GET['kho_q']      ?? '');
$danhmuc = trim($_GET['kho_dm']     ?? '');
$limit  = isset($_GET['kho_limit']) ? (int)$_GET['kho_limit'] : 15;

// ── KPI ──────────────────────────────────────────────────────────────────────
$kpi = $conn->query("
    SELECT
        COUNT(*)                          AS tong_sp,
        SUM(SoLuongTon <= 0)              AS het_hang,
        SUM(SoLuongTon > 0 AND SoLuongTon <= 100) AS sap_het,
        SUM(SoLuongTon > 100)             AS du_hang,
        SUM(GiaBan * SoLuongTon)          AS gia_tri_ton
    FROM sanpham
")->fetch_assoc();

$can_nhap = (int)$kpi['het_hang'] + (int)$kpi['sap_het'];

// ── WHERE ────────────────────────────────────────────────────────────────────
$where = [];
switch ($filter) {
    case 'het':
        $where[] = "sp.SoLuongTon <= 0";
        break;
    case 'saphet':
        $where[] = "sp.SoLuongTon > 0 AND sp.SoLuongTon <= 100";
        break;
    case 'du':
        $where[] = "sp.SoLuongTon > 100";
        break;
}
if ($search !== '') {
    $s = $conn->real_escape_string($search);
    $where[] = "sp.TenSP LIKE '%$s%'";
}
if ($danhmuc !== '') {
    $where[] = "sp.madanhmuc = " . (int)$danhmuc;
}
$where_sql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

// ── Đếm tổng filtered ────────────────────────────────────────────────────────
$total = (int)$conn->query("SELECT COUNT(*) AS c FROM sanpham sp $where_sql")->fetch_assoc()['c'];

// ── Danh mục ─────────────────────────────────────────────────────────────────
$dm_list = [];
$r = $conn->query("SELECT madanhmuc, tendanhmuc FROM danhmuc ORDER BY tendanhmuc");
if ($r) while ($row = $r->fetch_assoc()) $dm_list[] = $row;

// ── Query chính ───────────────────────────────────────────────────────────────
$result = $conn->query("
    SELECT sp.MaSP, sp.TenSP, sp.GiaBan, sp.SoLuongTon, sp.Hinh,
           dm.tendanhmuc,
           COALESCE(ban.da_ban,0)  AS da_ban,
           COALESCE(ban.doanhthu,0) AS doanhthu
    FROM sanpham sp
    LEFT JOIN danhmuc dm ON dm.madanhmuc = sp.madanhmuc
    LEFT JOIN (
        SELECT MaSP,
               SUM(soluong)   AS da_ban,
               SUM(thanhtien) AS doanhthu
        FROM chitietdathang GROUP BY MaSP
    ) ban ON ban.MaSP = sp.MaSP
    $where_sql
    ORDER BY sp.SoLuongTon ASC, sp.MaSP DESC
    LIMIT $limit
");
$has_more = $total > $limit;

// ── Helper ────────────────────────────────────────────────────────────────────
function inv_badge($ton)
{
    if ($ton <= 0)  return ['badge-red',   'Hết hàng', 'var(--danger)'];
    if ($ton <= 100) return ['badge-amber', 'Sắp hết',  'var(--warning)'];
    return                 ['badge-green', 'Đủ hàng',  ''];
}
function inv_fill($ton)
{
    if ($ton <= 0)   return ['stock-low',  0];
    if ($ton <= 100)  return ['stock-low',  max(4, round($ton / 100 * 100))];
    if ($ton <= 100) return ['stock-mid',  min(100, round($ton / 100 * 100))];
    return                  ['stock-high', min(100, round($ton / 500 * 100))];
}

// ── Format số ─────────────────────────────────────────────────────────────────
function inv_money($n)
{
    if ($n >= 1000000) return number_format($n / 1000000, 1, '.', '.') . 'tr';
    return number_format($n, 0, ',', '.') . 'đ';
}
?>

<!-- ── KPI Cards ── -->
<div class="kpi-grid-3" style="margin-bottom:20px">
    <div class="kpi-card">
        <div class="kpi-header">
            <span class="kpi-label">Tổng sản phẩm</span>
            <div class="kpi-icon" style="background:var(--accent-light)">
                <svg viewBox="0 0 16 16" fill="none" stroke="#1d4ed8" stroke-width="1.6" stroke-linecap="round">
                    <rect x="2" y="2" width="5" height="5" rx="1" />
                    <rect x="9" y="2" width="5" height="5" rx="1" />
                    <rect x="2" y="9" width="5" height="5" rx="1" />
                    <rect x="9" y="9" width="5" height="5" rx="1" />
                </svg>
            </div>
        </div>
        <div class="kpi-value"><?= number_format($kpi['tong_sp']) ?></div>
        <div class="kpi-foot">
            <span class="kpi-period"><?= $kpi['du_hang'] ?> đủ hàng</span>
        </div>
    </div>

    <div class="kpi-card" style="<?= $can_nhap > 0 ? 'border-color:#fca5a5' : '' ?>">
        <div class="kpi-header">
            <span class="kpi-label">Cần nhập kho</span>
            <div class="kpi-icon" style="background:#fef2f2">
                <svg viewBox="0 0 16 16" fill="none" stroke="#dc2626" stroke-width="1.6" stroke-linecap="round">
                    <path d="M8 2v7M8 12v1.5" />
                    <circle cx="8" cy="8" r="6.5" />
                </svg>
            </div>
        </div>
        <div class="kpi-value" style="<?= $can_nhap > 0 ? 'color:var(--danger)' : '' ?>"><?= $can_nhap ?></div>
        <div class="kpi-foot">
            <span class="kpi-change trend-down"><?= $kpi['het_hang'] ?> hết hàng · <?= $kpi['sap_het'] ?> sắp hết</span>
        </div>
    </div>

    <div class="kpi-card">
        <div class="kpi-header">
            <span class="kpi-label">Giá trị tồn kho</span>
            <div class="kpi-icon" style="background:#f0fdf4">
                <svg viewBox="0 0 16 16" fill="none" stroke="#16a34a" stroke-width="1.6" stroke-linecap="round">
                    <path d="M8 2v12M5 5h4.5a2 2 0 010 4H5m0-4H3m2 4H3" />
                </svg>
            </div>
        </div>
        <div class="kpi-value"><?= inv_money($kpi['gia_tri_ton']) ?></div>
        <div class="kpi-foot">
            <span class="kpi-period"><?= number_format($kpi['tong_sp']) ?> loại sản phẩm</span>
        </div>
    </div>
</div>

<!-- ── Filter row ── -->
<div class="filter-row" style="margin-bottom:16px">
    <button class="filter-chip <?= $filter === 'all' ? 'active' : '' ?>" onclick="khoFilter('all')">Tất cả (<?= $kpi['tong_sp'] ?>)</button>
    <button class="filter-chip <?= $filter === 'het' ? 'active' : '' ?>" onclick="khoFilter('het')"
        style="<?= (int)$kpi['het_hang'] > 0 ? 'color:var(--danger)' : '' ?>">
        🔴 Hết hàng (<?= $kpi['het_hang'] ?>)
    </button>
    <button class="filter-chip <?= $filter === 'saphet' ? 'active' : '' ?>" onclick="khoFilter('saphet')"
        style="<?= (int)$kpi['sap_het'] > 0 ? 'color:var(--warning)' : '' ?>">
        🟡 Sắp hết (<?= $kpi['sap_het'] ?>)
    </button>
    <button class="filter-chip <?= $filter === 'du' ? 'active' : '' ?>" onclick="khoFilter('du')">✅ Đủ hàng (<?= $kpi['du_hang'] ?>)</button>

    <select class="search-input" style="width:160px" onchange="khoDmFilter(this.value)">
        <option value="">Tất cả danh mục</option>
        <?php foreach ($dm_list as $dm): ?>
            <option value="<?= $dm['madanhmuc'] ?>" <?= $danhmuc == (string)$dm['madanhmuc'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($dm['tendanhmuc']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <input class="search-input" placeholder="Tìm sản phẩm..."
        value="<?= htmlspecialchars($search) ?>"
        oninput="khoSearchDebounce(this.value)" />
</div>

<!-- ── Bảng tồn kho ── -->
<div class="card card-full">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th>Danh mục</th>
                    <th>Tồn kho</th>
                    <th>Đã bán</th>
                    <th>Giá bán</th>
                    <th>Giá trị tồn</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0):
                    while ($row = $result->fetch_assoc()):
                        [$badgeCls, $badgeLabel, $tonColor] = inv_badge($row['SoLuongTon']);
                        [$fillCls, $fillPct]               = inv_fill($row['SoLuongTon']);
                        $giaTriTon = $row['GiaBan'] * $row['SoLuongTon'];
                        $sku       = 'SP-' . str_pad($row['MaSP'], 3, '0', STR_PAD_LEFT);
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
                            <td>
                                <div class="stock-wrap">
                                    <div class="stock-track">
                                        <div class="stock-fill <?= $fillCls ?>" style="width:<?= $fillPct ?>%"></div>
                                    </div>
                                    <span style="<?= $tonColor ? "color:$tonColor;font-weight:700" : '' ?>">
                                        <?= $row['SoLuongTon'] <= 0 ? 'Hết' : $row['SoLuongTon'] ?>
                                    </span>
                                </div>
                            </td>
                            <td><?= number_format($row['da_ban'], 0, ',', '.') ?></td>
                            <td><?= number_format($row['GiaBan'], 0, ',', '.') ?>đ</td>
                            <td><strong><?= inv_money($giaTriTon) ?></strong></td>
                            <td><span class="badge <?= $badgeCls ?>"><?= $badgeLabel ?></span></td>
                            <td>
                                <div class="actions">
                                    <button class="act-btn <?= $row['SoLuongTon'] <= 100 ? 'btn-accent' : '' ?>"
                                        onclick="openNhapKhoModal(<?= $row['MaSP'] ?>, '<?= addslashes($row['TenSP']) ?>', <?= $row['SoLuongTon'] ?>, '<?= addslashes($row['Hinh'] ?? '') ?>', '<?= $sku ?>')">
                                        Nhập kho
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile;

                    if ($has_more): ?>
                        <tr>
                            <td colspan="8" style="text-align:center;padding:14px">
                                <button class="btn-load-more" onclick="khoLoadMore(<?= $limit + 15 ?>)">
                                    Xem thêm (còn <?= $total - $limit ?> sản phẩm)
                                </button>
                            </td>
                        </tr>
                    <?php endif;
                else: ?>
                    <tr>
                        <td colspan="8" style="text-align:center;padding:28px;color:var(--text-muted)">Không có sản phẩm nào.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    function khoUpdateUrl(params) {
        const url = new URL(window.location.href);
        url.searchParams.set('page', 'inventory');
        for (const [k, v] of Object.entries(params)) {
            if (v === '') url.searchParams.delete(k);
            else url.searchParams.set(k, v);
        }
        window.location.href = url.toString();
    }

    function khoFilter(f) {
        khoUpdateUrl({
            kho_filter: f,
            kho_limit: 15,
            kho_q: '',
            kho_dm: ''
        });
    }

    function khoDmFilter(dm) {
        khoUpdateUrl({
            kho_dm: dm,
            kho_limit: 15
        });
    }

    function khoLoadMore(n) {
        const u = new URL(window.location.href);
        u.searchParams.set('page', 'inventory');
        u.searchParams.set('kho_limit', n);
        window.location.href = u.toString();
    }
    let _khoT;

    function khoSearchDebounce(v) {
        clearTimeout(_khoT);
        _khoT = setTimeout(() => khoUpdateUrl({
            kho_q: v,
            kho_limit: 15
        }), 400);
    }
    (function() {
        const p = new URLSearchParams(window.location.search).get('page');
        if (p === 'inventory') {
            document.querySelectorAll('.page').forEach(x => x.classList.remove('active'));
            document.querySelectorAll('.nav-item').forEach(x => x.classList.remove('active'));
            const pg = document.getElementById('page-inventory');
            if (pg) pg.classList.add('active');
            document.querySelectorAll('.nav-item').forEach(n => {
                if (n.getAttribute('onclick')?.includes("'inventory'")) n.classList.add('active');
            });
            const t = document.getElementById('page-title');
            if (t) t.textContent = 'Quản lý kho hàng';
        }
    })();
</script>