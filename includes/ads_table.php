<?php
require_once __DIR__ . '/../config/db.php';

// Tự tạo bảng nếu chưa có
$conn->query("
    CREATE TABLE IF NOT EXISTS `quangcao` (
        `id`          INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        `ten_qc`      VARCHAR(255) NOT NULL,
        `hinh_anh`    VARCHAR(255) DEFAULT '',
        `link`        VARCHAR(500) DEFAULT '',
        `vi_tri`      ENUM('banner_top','banner_home','popup','sidebar') DEFAULT 'banner_home',
        `mo_ta`       TEXT,
        `trang_thai`  TINYINT(1) DEFAULT 1,
        `thu_tu`      INT DEFAULT 0,
        `ngay_bat_dau` DATE DEFAULT NULL,
        `ngay_ket_thuc` DATE DEFAULT NULL,
        `created_at`  DATETIME DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
");

$search = trim($_GET['ads_q'] ?? '');
$vitri  = trim($_GET['ads_vt'] ?? '');
$tt     = trim($_GET['ads_tt'] ?? '');
$limit  = isset($_GET['ads_limit']) ? (int)$_GET['ads_limit'] : 15;

$where = [];
if ($search !== '') {
    $s = $conn->real_escape_string($search);
    $where[] = "ten_qc LIKE '%$s%'";
}
if ($vitri  !== '') {
    $v = $conn->real_escape_string($vitri);
    $where[] = "vi_tri='$v'";
}
if ($tt     !== '') {
    $where[] = "trang_thai=" . ($tt === '1' ? 1 : 0);
}
$ws = $where ? 'WHERE ' . implode(' AND ', $where) : '';

$total  = (int)$conn->query("SELECT COUNT(*) AS c FROM quangcao $ws")->fetch_assoc()['c'];
$result = $conn->query("SELECT * FROM quangcao $ws ORDER BY thu_tu ASC, id DESC LIMIT $limit");
$has_more = $total > $limit;

// KPI
$kpi = $conn->query("
    SELECT COUNT(*) AS tong,
           SUM(trang_thai=1) AS dang_chay,
           SUM(trang_thai=0) AS tam_dung
    FROM quangcao
")->fetch_assoc() ?? ['tong' => 0, 'dang_chay' => 0, 'tam_dung' => 0];

$vitri_labels = [
    'banner_top'    => '🔝 Banner Top (Trang Shop)',
    'banner_home'   => '🏠 Banner Home 1 (Chính)',
    'banner_home_2' => '🏠 Banner Home 2 (Giữa trang)',
    'banner_home_3' => '🏠 Banner Home 3 (Cuối trang)',
    'popup'         => '💬 Popup',
    'sidebar'       => '📌 Sidebar',
];

function fmt_date($d)
{
    return $d ? date('d/m/Y', strtotime($d)) : '—';
}
function is_running($start, $end)
{
    $today = date('Y-m-d');
    if ($start && $start > $today) return 'upcoming';
    if ($end   && $end   < $today) return 'expired';
    return 'active';
}
?>

<!-- KPI -->
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:20px">
    <div class="kpi-card">
        <div class="kpi-header">
            <span class="kpi-label">Tổng quảng cáo</span>
            <div class="kpi-icon" style="background:#eff6ff">
                <svg viewBox="0 0 16 16" fill="none" stroke="#1d4ed8" stroke-width="1.6" stroke-linecap="round">
                    <rect x="1" y="4" width="14" height="8" rx="1.5" />
                    <path d="M4 8h8M4 10.5h5" />
                </svg>
            </div>
        </div>
        <div class="kpi-value"><?= $kpi['tong'] ?></div>
        <div class="kpi-foot"><span class="kpi-period">tất cả vị trí</span></div>
    </div>
    <div class="kpi-card">
        <div class="kpi-header">
            <span class="kpi-label">Đang hiển thị</span>
            <div class="kpi-icon" style="background:#f0fdf4">
                <svg viewBox="0 0 16 16" fill="none" stroke="#16a34a" stroke-width="1.6" stroke-linecap="round">
                    <circle cx="8" cy="8" r="6.5" />
                    <path d="M5 8l2 2 4-4" />
                </svg>
            </div>
        </div>
        <div class="kpi-value" style="color:#16a34a"><?= $kpi['dang_chay'] ?></div>
        <div class="kpi-foot"><span class="kpi-period">đang hoạt động</span></div>
    </div>
    <div class="kpi-card">
        <div class="kpi-header">
            <span class="kpi-label">Tạm dừng</span>
            <div class="kpi-icon" style="background:#fef2f2">
                <svg viewBox="0 0 16 16" fill="none" stroke="#dc2626" stroke-width="1.6" stroke-linecap="round">
                    <circle cx="8" cy="8" r="6.5" />
                    <path d="M6 5.5v5M10 5.5v5" />
                </svg>
            </div>
        </div>
        <div class="kpi-value" style="color:var(--danger)"><?= $kpi['tam_dung'] ?></div>
        <div class="kpi-foot"><span class="kpi-period">không hiển thị</span></div>
    </div>
</div>

<!-- Filter -->
<div class="filter-row" style="margin-bottom:16px;flex-wrap:wrap;gap:8px">
    <button class="filter-chip <?= $vitri === '' ? 'active' : '' ?>" onclick="adsFilter('','')">Tất cả (<?= $kpi['tong'] ?>)</button>
    <?php foreach ($vitri_labels as $k => $v):
        $cnt = (int)$conn->query("SELECT COUNT(*) AS c FROM quangcao WHERE vi_tri='$k'")->fetch_assoc()['c'];
    ?>
        <button class="filter-chip <?= $vitri === $k ? 'active' : '' ?>" onclick="adsFilter('<?= $k ?>','')">
            <?= $v ?> (<?= $cnt ?>)
        </button>
    <?php endforeach; ?>
    <button class="filter-chip <?= $tt === '1' ? 'active' : '' ?>" onclick="adsFilter('','1')" style="margin-left:auto">✅ Đang chạy</button>
    <button class="filter-chip <?= $tt === '0' ? 'active' : '' ?>" onclick="adsFilter('','0')">⏸ Tạm dừng</button>
    <input class="search-input" placeholder="Tìm tên quảng cáo..."
        value="<?= htmlspecialchars($search) ?>"
        oninput="adsSearchDebounce(this.value)">
</div>

<!-- Bảng -->
<div class="card card-full">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th style="width:48px;text-align:center">STT</th>
                    <th>Tên quảng cáo</th>
                    <th style="width:130px">Vị trí</th>
                    <th style="width:130px">Thời gian</th>
                    <th style="width:90px;text-align:center">Thứ tự</th>
                    <th style="width:100px;text-align:center">Trạng thái</th>
                    <th style="width:80px;text-align:center">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stt = ($limit * 0) + 1; // simple STT
                if ($result && $result->num_rows > 0):
                    $i = 1;
                    while ($row = $result->fetch_assoc()):
                        $status = is_running($row['ngay_bat_dau'], $row['ngay_ket_thuc']);
                        $isOn   = $row['trang_thai'] == 1;
                        $ttBadge = $isOn
                            ? ($status === 'upcoming' ? ['badge-blue', 'Sắp chạy']
                                : ($status === 'expired' ? ['badge-red', 'Hết hạn'] : ['badge-green', 'Đang chạy']))
                            : ['badge-gray', 'Tạm dừng'];
                ?>
                        <tr data-adsid="<?= $row['id'] ?>">
                            <td style="text-align:center;color:var(--text-muted);font-size:12px"><?= $i++ ?></td>
                            <td>
                                <div class="td-flex">
                                    <?php if ($row['hinh_anh']): ?>
                                        <div style="width:52px;height:36px;border-radius:6px;overflow:hidden;flex-shrink:0;background:var(--bg-page);border:1px solid var(--border)">
                                            <img src="../assets/file_anh/<?= htmlspecialchars($row['hinh_anh']) ?>"
                                                onerror="this.parentNode.style.display='none'"
                                                style="width:100%;height:100%;object-fit:cover">
                                        </div>
                                    <?php else: ?>
                                        <div style="width:52px;height:36px;border-radius:6px;background:var(--bg-page);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0">🖼️</div>
                                    <?php endif; ?>
                                    <div style="min-width:0">
                                        <div style="font-weight:500;color:var(--text-primary);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:280px">
                                            <?= htmlspecialchars($row['ten_qc']) ?>
                                        </div>
                                        <?php if ($row['link']): ?>
                                            <div style="font-size:11px;color:var(--text-muted);margin-top:1px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:280px">
                                                🔗 <?= htmlspecialchars($row['link']) ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge badge-blue" style="font-size:11px"><?= $vitri_labels[$row['vi_tri']] ?? $row['vi_tri'] ?></span></td>
                            <td style="font-size:12px;color:var(--text-secondary)">
                                <?php if ($row['ngay_bat_dau'] || $row['ngay_ket_thuc']): ?>
                                    <?= fmt_date($row['ngay_bat_dau']) ?><br>→ <?= fmt_date($row['ngay_ket_thuc']) ?>
                                <?php else: ?>
                                    <span style="color:var(--text-muted)">Không giới hạn</span>
                                <?php endif; ?>
                            </td>
                            <td style="text-align:center;font-weight:600"><?= $row['thu_tu'] ?></td>
                            <td style="text-align:center">
                                <span class="badge <?= $ttBadge[0] ?>"><?= $ttBadge[1] ?></span>
                            </td>
                            <td style="text-align:center">
                                <div class="actions" style="justify-content:center">
                                    <button class="act-btn" onclick="openAdsView(<?= $row['id'] ?>)">Xem</button>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile;
                    if ($has_more): ?>
                        <tr>
                            <td colspan="7" style="text-align:center;padding:14px">
                                <button class="btn-load-more" onclick="adsLoadMore(<?= $limit + 15 ?>)">
                                    Xem thêm (còn <?= $total - $limit ?>)
                                </button>
                            </td>
                        </tr>
                    <?php endif;
                else: ?>
                    <tr>
                        <td colspan="7" style="text-align:center;padding:32px;color:var(--text-muted)">
                            Chưa có quảng cáo nào. <button class="btn btn-primary" style="margin-left:8px" onclick="openAdsForm(0)">+ Tạo ngay</button>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    function adsUpdateUrl(params) {
        const url = new URL(window.location.href);
        url.searchParams.set('page', 'ads');
        for (const [k, v] of Object.entries(params)) {
            if (v === '') url.searchParams.delete(k);
            else url.searchParams.set(k, v);
        }
        window.location.href = url.toString();
    }

    function adsFilter(vt, tt) {
        adsUpdateUrl({
            ads_vt: vt,
            ads_tt: tt,
            ads_q: '',
            ads_limit: 15
        });
    }

    function adsLoadMore(n) {
        const u = new URL(window.location.href);
        u.searchParams.set('page', 'ads');
        u.searchParams.set('ads_limit', n);
        window.location.href = u.toString();
    }
    let _adsT;

    function adsSearchDebounce(v) {
        clearTimeout(_adsT);
        _adsT = setTimeout(() => adsUpdateUrl({
            ads_q: v,
            ads_limit: 15
        }), 400);
    }
    (function() {
        const p = new URLSearchParams(window.location.search).get('page');
        if (p === 'ads') {
            document.querySelectorAll('.page').forEach(x => x.classList.remove('active'));
            document.querySelectorAll('.nav-item').forEach(x => x.classList.remove('active'));
            const pg = document.getElementById('page-ads');
            if (pg) pg.classList.add('active');
            document.querySelectorAll('.nav-item').forEach(n => {
                if (n.getAttribute('onclick')?.includes("'ads'")) n.classList.add('active');
            });
            const t = document.getElementById('page-title');
            if (t) t.textContent = 'Quản lý Quảng cáo';
        }
    })();
</script>