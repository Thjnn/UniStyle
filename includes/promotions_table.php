<?php
require_once __DIR__ . '/../config/db.php';

$tab    = trim($_GET['promo_tab'] ?? 'voucher'); // voucher | campaign
$search = trim($_GET['promo_q']   ?? '');
$filter = trim($_GET['promo_f']   ?? 'all');     // all | active | upcoming | expired
$limit  = isset($_GET['promo_limit']) ? (int)$_GET['promo_limit'] : 10;

$today = date('Y-m-d H:i:s');

// ── KPI Voucher ───────────────────────────────────────────────────────────────
$kpi = $conn->query("
    SELECT
        COUNT(*)                                                        AS tong,
        SUM(ngay_bat_dau <= '$today' AND ngay_ket_thuc >= '$today')     AS dang_chay,
        SUM(ngay_bat_dau > '$today')                                    AS sap_dien_ra,
        SUM(ngay_ket_thuc < '$today')                                   AS da_ket_thuc,
        SUM(so_luong)                                                   AS tong_sl,
        SUM(CASE WHEN so_luong > 0 THEN so_luong ELSE 0 END)            AS con_lai
    FROM voucher
")->fetch_assoc();

// ── Helpers ───────────────────────────────────────────────────────────────────
function promo_status($start, $end)
{
    $now = date('Y-m-d H:i:s');
    if ($start > $now) return ['badge-blue',  'Sắp diễn ra', 'upcoming'];
    if ($end   < $now) return ['badge-red',   'Đã kết thúc', 'expired'];
    return                    ['badge-green', 'Đang chạy',   'active'];
}
function promo_giam($loai, $htgiam, $gtgiam)
{
    $prefix = $htgiam == 1 ? '' : '-';
    $suffix = $htgiam == 1 ? '%' : 'đ';
    $val    = $htgiam == 1 ? $gtgiam : number_format($gtgiam, 0, ',', '.');
    $type   = $loai == 1 ? '🚚 Giảm ship' : '🏷️ Giảm SP';
    return "$type · $prefix$val$suffix";
}
function fmt_dt($dt)
{
    return $dt ? date('d/m/Y', strtotime($dt)) : '—';
}
?>

<!-- ── KPI ── -->
<div class="kpi-grid" style="grid-template-columns:repeat(4,1fr);margin-bottom:20px">
    <div class="kpi-card">
        <div class="kpi-header">
            <span class="kpi-label">Tổng voucher</span>
            <div class="kpi-icon" style="background:var(--accent-light)">
                <svg viewBox="0 0 16 16" fill="none" stroke="#1d4ed8" stroke-width="1.6" stroke-linecap="round">
                    <rect x="1" y="5" width="14" height="8" rx="1.5" />
                    <path d="M5 5V3.5a2.5 2.5 0 015 0V5" />
                    <path d="M1 9h2M13 9h2" stroke-dasharray="2 1" />
                </svg>
            </div>
        </div>
        <div class="kpi-value"><?= $kpi['tong'] ?></div>
        <div class="kpi-foot"><span class="kpi-period"><?= $kpi['con_lai'] ?> lượt còn lại</span></div>
    </div>
    <div class="kpi-card">
        <div class="kpi-header">
            <span class="kpi-label">Đang chạy</span>
            <div class="kpi-icon" style="background:#f0fdf4">
                <svg viewBox="0 0 16 16" fill="none" stroke="#16a34a" stroke-width="1.6" stroke-linecap="round">
                    <circle cx="8" cy="8" r="6.5" />
                    <path d="M5 8l2 2 4-4" />
                </svg>
            </div>
        </div>
        <div class="kpi-value" style="color:var(--success,#16a34a)"><?= $kpi['dang_chay'] ?></div>
        <div class="kpi-foot"><span class="kpi-period">voucher hiệu lực</span></div>
    </div>
    <div class="kpi-card">
        <div class="kpi-header">
            <span class="kpi-label">Sắp diễn ra</span>
            <div class="kpi-icon" style="background:#eff6ff">
                <svg viewBox="0 0 16 16" fill="none" stroke="#1d4ed8" stroke-width="1.6" stroke-linecap="round">
                    <circle cx="8" cy="8" r="6.5" />
                    <path d="M8 4.5v3.5l2.5 1.5" />
                </svg>
            </div>
        </div>
        <div class="kpi-value" style="color:#1d4ed8"><?= $kpi['sap_dien_ra'] ?></div>
        <div class="kpi-foot"><span class="kpi-period">chờ kích hoạt</span></div>
    </div>
    <div class="kpi-card">
        <div class="kpi-header">
            <span class="kpi-label">Đã kết thúc</span>
            <div class="kpi-icon" style="background:#fef2f2">
                <svg viewBox="0 0 16 16" fill="none" stroke="#dc2626" stroke-width="1.6" stroke-linecap="round">
                    <circle cx="8" cy="8" r="6.5" />
                    <path d="M5.5 5.5l5 5M10.5 5.5l-5 5" />
                </svg>
            </div>
        </div>
        <div class="kpi-value" style="color:var(--danger)"><?= $kpi['da_ket_thuc'] ?></div>
        <div class="kpi-foot"><span class="kpi-period">đã hết hạn</span></div>
    </div>
</div>

<!-- ── Tabs ── -->
<div class="promo-tabs">
    <button class="promo-tab <?= $tab === 'voucher' ? 'active' : '' ?>" onclick="promoTab('voucher')">
        🏷️ Mã Voucher
    </button>
    <button class="promo-tab <?= $tab === 'campaign' ? 'active' : '' ?>" onclick="promoTab('campaign')">
        📢 Chiến dịch
    </button>
</div>

<?php if ($tab === 'voucher'): ?>
    <!-- ════════════════ TAB VOUCHER ════════════════ -->

    <!-- Filter -->
    <div class="filter-row" style="margin:14px 0">
        <button class="filter-chip <?= $filter === 'all' ? 'active' : '' ?>" onclick="promoFilter('all')">Tất cả (<?= $kpi['tong'] ?>)</button>
        <button class="filter-chip <?= $filter === 'active' ? 'active' : '' ?>" onclick="promoFilter('active')">✅ Đang chạy (<?= $kpi['dang_chay'] ?>)</button>
        <button class="filter-chip <?= $filter === 'upcoming' ? 'active' : '' ?>" onclick="promoFilter('upcoming')">🔵 Sắp diễn ra (<?= $kpi['sap_dien_ra'] ?>)</button>
        <button class="filter-chip <?= $filter === 'expired' ? 'active' : '' ?>" onclick="promoFilter('expired')">⛔ Kết thúc (<?= $kpi['da_ket_thuc'] ?>)</button>
        <input class="search-input" placeholder="Tìm tên, mã code..."
            value="<?= htmlspecialchars($search) ?>"
            oninput="promoSearchDebounce(this.value)">
    </div>

    <?php
    // WHERE voucher
    $vw = [];
    if ($search !== '') {
        $s = $conn->real_escape_string($search);
        $vw[] = "(ma_code LIKE '%$s%' OR ten_voucher LIKE '%$s%')";
    }
    switch ($filter) {
        case 'active':
            $vw[] = "ngay_bat_dau <= '$today' AND ngay_ket_thuc >= '$today'";
            break;
        case 'upcoming':
            $vw[] = "ngay_bat_dau > '$today'";
            break;
        case 'expired':
            $vw[] = "ngay_ket_thuc < '$today'";
            break;
    }
    $vw_sql    = $vw ? 'WHERE ' . implode(' AND ', $vw) : '';
    $total_v   = (int)$conn->query("SELECT COUNT(*) AS c FROM voucher $vw_sql")->fetch_assoc()['c'];
    $res_v     = $conn->query("SELECT * FROM voucher $vw_sql ORDER BY id_voucher DESC LIMIT $limit");
    $has_more_v = $total_v > $limit;

    // Đếm khách đã lưu mỗi voucher
    $used_map  = [];
    $ur = $conn->query("SELECT id_voucher, COUNT(*) AS cnt FROM khachhang_voucher GROUP BY id_voucher");
    if ($ur) while ($r = $ur->fetch_assoc()) $used_map[$r['id_voucher']] = $r['cnt'];
    ?>

    <div class="card card-full">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Tên voucher</th>
                        <th>Mã code</th>
                        <th>Loại giảm</th>
                        <th>Đơn tối thiểu</th>
                        <th>Số lượng</th>
                        <th>Thời hạn</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($res_v && $res_v->num_rows > 0):
                        while ($r = $res_v->fetch_assoc()):
                            [$bc, $bl] = promo_status($r['ngay_bat_dau'], $r['ngay_ket_thuc']);
                            $da_dung  = $used_map[$r['id_voucher']] ?? 0;
                            $sl_con   = $r['so_luong'] > 0 ? ($r['so_luong'] - $da_dung) : '∞';
                    ?>
                            <tr>
                                <td>
                                    <div style="font-weight:500;color:var(--text-primary)"><?= htmlspecialchars($r['ten_voucher']) ?></div>
                                    <div style="font-size:11px;color:var(--text-muted);margin-top:1px">
                                        <?= $r['ap_dung_tat_ca'] ? 'Toàn shop' : 'SP nhất định' ?>
                                        <?= $r['giam_toi_da'] > 0 ? ' · Giảm tối đa ' . number_format($r['giam_toi_da'], 0, ',', '.') . 'đ' : '' ?>
                                    </div>
                                </td>
                                <td>
                                    <code class="promo-code"><?= htmlspecialchars($r['ma_code']) ?></code>
                                </td>
                                <td style="font-size:12.5px"><?= promo_giam($r['loai_voucher'], $r['hinh_thuc_giam'], $r['gia_tri_giam']) ?></td>
                                <td><?= $r['don_toi_thieu'] > 0 ? number_format($r['don_toi_thieu'], 0, ',', '.') . 'đ' : 'Không giới hạn' ?></td>
                                <td>
                                    <div style="font-size:13px">
                                        <?php if ($r['so_luong'] > 0): ?>
                                            <strong><?= $sl_con ?></strong>
                                            <span style="color:var(--text-muted);font-size:11px"> / <?= $r['so_luong'] ?></span>
                                            <?php
                                            $pct = $r['so_luong'] > 0 ? round(($da_dung / $r['so_luong']) * 100) : 0;
                                            $barCls = $pct >= 80 ? 'stock-low' : ($pct >= 50 ? 'stock-mid' : 'stock-high');
                                            ?>
                                            <div class="stock-track" style="margin-top:4px">
                                                <div class="stock-fill <?= $barCls ?>" style="width:<?= $pct ?>%"></div>
                                            </div>
                                        <?php else: ?>
                                            <span style="color:var(--text-muted)">Không giới hạn</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td style="font-size:12.5px">
                                    <?= fmt_dt($r['ngay_bat_dau']) ?> →<br>
                                    <?= fmt_dt($r['ngay_ket_thuc']) ?>
                                </td>
                                <td><span class="badge <?= $bc ?>"><?= $bl ?></span></td>
                                <td>
                                    <div class="actions">
                                        <button class="act-btn" onclick="openVoucherView(<?= $r['id_voucher'] ?>)">Xem</button>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile;
                        if ($has_more_v): ?>
                            <tr>
                                <td colspan="8" style="text-align:center;padding:14px">
                                    <button class="btn-load-more" onclick="promoLoadMore(<?= $limit + 10 ?>)">
                                        Xem thêm (còn <?= $total_v - $limit ?>)
                                    </button>
                                </td>
                            </tr>
                        <?php endif;
                    else: ?>
                        <tr>
                            <td colspan="8" style="text-align:center;padding:24px;color:var(--text-muted)">Không tìm thấy voucher nào.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

<?php else: ?>
    <!-- ════════════════ TAB CHIẾN DỊCH ════════════════ -->
    <?php
    $res_c = $conn->query("SELECT * FROM khuyenmai ORDER BY MaKhuyenMai DESC");
    ?>
    <div class="card card-full" style="margin-top:14px">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Tên chiến dịch</th>
                        <th>Giảm giá</th>
                        <th>Ngày bắt đầu</th>
                        <th>Ngày kết thúc</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($res_c && $res_c->num_rows > 0):
                        while ($r = $res_c->fetch_assoc()):
                            [$bc, $bl] = promo_status($r['NgayBatDau'] . ' 00:00:00', $r['NgayKetThuc'] . ' 23:59:59');
                    ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($r['TenKhuyenMai']) ?></strong></td>
                                <td><span class="badge badge-amber"><?= $r['GiamGia'] ?>%</span></td>
                                <td><?= fmt_dt($r['NgayBatDau']) ?></td>
                                <td><?= fmt_dt($r['NgayKetThuc']) ?></td>
                                <td><span class="badge <?= $bc ?>"><?= $bl ?></span></td>
                                <td>
                                    <div class="actions">
                                        <button class="act-btn" onclick="openCampaignView(<?= $r['MaKhuyenMai'] ?>)">Xem</button>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile;
                    else: ?>
                        <tr>
                            <td colspan="6" style="text-align:center;padding:24px;color:var(--text-muted)">Chưa có chiến dịch nào.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

<style>
    .promo-tabs {
        display: flex;
        gap: 4px;
        margin-bottom: 4px;
        border-bottom: 2px solid var(--border);
    }

    .promo-tab {
        padding: 9px 18px;
        background: none;
        border: none;
        font-size: 13px;
        font-weight: 500;
        color: var(--text-muted);
        cursor: pointer;
        border-bottom: 2px solid transparent;
        margin-bottom: -2px;
        font-family: 'Be Vietnam Pro', sans-serif;
        transition: color .15s;
    }

    .promo-tab.active {
        color: var(--accent-mid);
        border-bottom-color: var(--accent-mid);
    }

    .promo-tab:hover:not(.active) {
        color: var(--text-primary);
    }

    .promo-code {
        background: var(--bg-page);
        border: 1px dashed var(--border);
        padding: 3px 8px;
        border-radius: 5px;
        font-size: 12px;
        font-family: monospace;
        color: var(--accent-mid);
        letter-spacing: .04em;
    }
</style>

<script>
    function promoUpdateUrl(params) {
        const url = new URL(window.location.href);
        url.searchParams.set('page', 'promotions');
        for (const [k, v] of Object.entries(params)) {
            if (v === '') url.searchParams.delete(k);
            else url.searchParams.set(k, v);
        }
        window.location.href = url.toString();
    }

    function promoTab(t) {
        promoUpdateUrl({
            promo_tab: t,
            promo_f: 'all',
            promo_q: '',
            promo_limit: 10
        });
    }

    function promoFilter(f) {
        promoUpdateUrl({
            promo_f: f,
            promo_limit: 10
        });
    }

    function promoLoadMore(n) {
        const u = new URL(window.location.href);
        u.searchParams.set('page', 'promotions');
        u.searchParams.set('promo_limit', n);
        window.location.href = u.toString();
    }
    let _promoT;

    function promoSearchDebounce(v) {
        clearTimeout(_promoT);
        _promoT = setTimeout(() => promoUpdateUrl({
            promo_q: v,
            promo_limit: 10
        }), 400);
    }
    (function() {
        const p = new URLSearchParams(window.location.search).get('page');
        if (p === 'promotions') {
            document.querySelectorAll('.page').forEach(x => x.classList.remove('active'));
            document.querySelectorAll('.nav-item').forEach(x => x.classList.remove('active'));
            const pg = document.getElementById('page-promotions');
            if (pg) pg.classList.add('active');
            document.querySelectorAll('.nav-item').forEach(n => {
                if (n.getAttribute('onclick')?.includes("'promotions'")) n.classList.add('active');
            });
            const t = document.getElementById('page-title');
            if (t) t.textContent = 'Chương trình Khuyến mãi';
        }
    })();
</script>