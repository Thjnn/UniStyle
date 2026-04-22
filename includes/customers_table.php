<?php
require_once __DIR__ . '/../config/db.php';

// ── Params ───────────────────────────────────────────────────────────────────
$search = trim($_GET['kh_q']    ?? '');
$loai   = trim($_GET['kh_loai'] ?? '');
$limit  = isset($_GET['kh_limit']) ? (int)$_GET['kh_limit'] : 10;

// ── WHERE ────────────────────────────────────────────────────────────────────
$where = [];
if ($search !== '') {
    $s       = $conn->real_escape_string($search);
    $where[] = "(kh.tenkh LIKE '%$s%' OR kh.sdt LIKE '%$s%' OR kh.email LIKE '%$s%')";
}
if ($loai !== '') {
    $l       = $conn->real_escape_string($loai);
    $where[] = "kh.loai = '$l'";
}
$where_sql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

// ── Đếm tổng ─────────────────────────────────────────────────────────────────
$total_kh = (int)$conn->query("SELECT COUNT(*) AS c FROM khachhang kh $where_sql")->fetch_assoc()['c'];

// Đếm theo loại
$counts = ['Cá nhân' => 0, 'Doanh nghiệp' => 0, 'VIP' => 0];
$cr = $conn->query("SELECT loai, COUNT(*) AS c FROM khachhang GROUP BY loai");
if ($cr) while ($r = $cr->fetch_assoc()) $counts[$r['loai']] = (int)$r['c'];

// ── Query chính ───────────────────────────────────────────────────────────────
$result = $conn->query("
    SELECT kh.makh, kh.tenkh, kh.sdt, kh.email, kh.avatar,
           kh.diachi, kh.gioitinh, kh.ngaysinh, kh.loai, kh.ngay_dangky,
           COUNT(dh.madh)      AS tong_don,
           COALESCE(SUM(dh.tongtien),0) AS tong_chi_tieu,
           MAX(dh.ngaydat)     AS lan_cuoi_mua
    FROM khachhang kh
    LEFT JOIN dathang dh ON dh.makh = kh.makh
    $where_sql
    GROUP BY kh.makh
    ORDER BY kh.makh DESC
    LIMIT $limit
");
$has_more = $total_kh > $limit;

// ── Helpers ───────────────────────────────────────────────────────────────────
function kh_loaiBadge($loai)
{
    return match ($loai) {
        'VIP'         => 'badge-amber',
        'Doanh nghiệp' => 'badge-blue',
        default       => 'badge-gray',
    };
}
function kh_avatar($ten)
{
    $words = preg_split('/\s+/', trim($ten ?? ''));
    $a = mb_strtoupper(mb_substr($words[0], 0, 1, 'UTF-8'), 'UTF-8');
    $b = count($words) > 1 ? mb_strtoupper(mb_substr(end($words), 0, 1, 'UTF-8'), 'UTF-8') : '';
    return $a . $b;
}
function kh_avatarBg($makh)
{
    $colors = [
        ['#eff6ff', '#1d4ed8'],
        ['#f0fdf4', '#16a34a'],
        ['#fef9c3', '#854d0e'],
        ['#fce7f3', '#9d174d'],
        ['#f5f3ff', '#6d28d9'],
        ['#fff7ed', '#c2410c'],
    ];
    return $colors[$makh % count($colors)];
}
function kh_formatDate($d)
{
    if (!$d) return '—';
    $ts = strtotime($d);
    $diff = time() - $ts;
    if ($diff < 86400) return 'Hôm nay';
    if ($diff < 172800) return 'Hôm qua';
    if ($diff < 604800) return round($diff / 86400) . ' ngày trước';
    return date('d/m/Y', $ts);
}
function kh_formatMoney($n)
{
    if ($n >= 1000000) return number_format($n / 1000000, 1, '.', '.') . 'tr';
    if ($n >= 1000)    return number_format($n / 1000, 0, ',', '.') . 'k';
    return number_format($n, 0, ',', '.') . 'đ';
}
?>

<!-- Filter row -->
<div class="filter-row">
    <button class="filter-chip <?= $loai === '' ? 'active' : '' ?>" onclick="khFilter(this,'')">
        Tất cả (<?= $total_kh ?>)
    </button>
    <button class="filter-chip <?= $loai === 'Cá nhân' ? 'active' : '' ?>" onclick="khFilter(this,'Cá nhân')">
        Cá nhân (<?= $counts['Cá nhân'] ?>)
    </button>
    <button class="filter-chip <?= $loai === 'Doanh nghiệp' ? 'active' : '' ?>" onclick="khFilter(this,'Doanh nghiệp')">
        Doanh nghiệp (<?= $counts['Doanh nghiệp'] ?>)
    </button>
    <button class="filter-chip <?= $loai === 'VIP' ? 'active' : '' ?>" onclick="khFilter(this,'VIP')">
        VIP (<?= $counts['VIP'] ?>)
    </button>
    <input class="search-input" placeholder="Tìm tên, SĐT, email..."
        value="<?= htmlspecialchars($search) ?>"
        oninput="khSearchDebounce(this.value)" />
</div>

<!-- Table -->
<div class="card card-full">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Khách hàng</th>
                    <th>Loại</th>
                    <th>Số điện thoại</th>
                    <th>Email</th>
                    <th>Tổng đơn</th>
                    <th>Tổng chi tiêu</th>
                    <th>Lần cuối mua</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0):
                    while ($row = $result->fetch_assoc()):
                        [$bg, $fg] = kh_avatarBg($row['makh']);
                        $initials  = kh_avatar($row['tenkh']);
                ?>
                        <tr data-khmakh="<?= $row['makh'] ?>">
                            <td>
                                <div class="td-flex">
                                    <div class="cust-avatar" style="background:<?= $bg ?>;color:<?= $fg ?>;flex-shrink:0">
                                        <?php if ($row['avatar']): ?>
                                            <img src="../assets/file_anh/<?= htmlspecialchars($row['avatar']) ?>"
                                                onerror="this.parentNode.textContent='<?= $initials ?>'"
                                                style="width:100%;height:100%;object-fit:cover;border-radius:50%">
                                        <?php else: ?>
                                            <?= $initials ?>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <div style="font-weight:500;color:var(--text-primary)"><?= htmlspecialchars($row['tenkh'] ?? '—') ?></div>
                                        <div style="font-size:11px;color:var(--text-muted);margin-top:1px">
                                            #KH-<?= str_pad($row['makh'], 4, '0', STR_PAD_LEFT) ?>
                                            <?= $row['ngay_dangky'] ? ' · ' . date('d/m/Y', strtotime($row['ngay_dangky'])) : '' ?>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge <?= kh_loaiBadge($row['loai']) ?>"><?= htmlspecialchars($row['loai']) ?></span></td>
                            <td><?= htmlspecialchars($row['sdt'] ?? '—') ?></td>
                            <td style="color:var(--text-secondary)"><?= htmlspecialchars($row['email'] ?? '—') ?></td>
                            <td><?= $row['tong_don'] ?></td>
                            <td><strong><?= kh_formatMoney($row['tong_chi_tieu']) ?></strong></td>
                            <td><?= kh_formatDate($row['lan_cuoi_mua']) ?></td>
                            <td>
                                <div class="actions">
                                    <button class="act-btn" onclick="openKhView(<?= $row['makh'] ?>)">Xem</button>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile;
                    if ($has_more): ?>
                        <tr>
                            <td colspan="8" style="text-align:center;padding:14px">
                                <button class="btn-load-more" onclick="khLoadMore(<?= $limit + 10 ?>)">
                                    Xem thêm (còn <?= $total_kh - $limit ?> khách hàng)
                                </button>
                            </td>
                        </tr>
                    <?php endif;
                else: ?>
                    <tr>
                        <td colspan="8" style="text-align:center;padding:28px;color:var(--text-muted)">Không tìm thấy khách hàng nào.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    function khUpdateUrl(params) {
        const url = new URL(window.location.href);
        url.searchParams.set('page', 'customers');
        for (const [k, v] of Object.entries(params)) {
            if (v === '') url.searchParams.delete(k);
            else url.searchParams.set(k, v);
        }
        window.location.href = url.toString();
    }

    function khFilter(el, loai) {
        khUpdateUrl({
            kh_loai: loai,
            kh_limit: 10,
            kh_q: ''
        });
    }
    let _khTimer;

    function khSearchDebounce(val) {
        clearTimeout(_khTimer);
        _khTimer = setTimeout(() => khUpdateUrl({
            kh_q: val,
            kh_limit: 10,
            kh_loai: ''
        }), 400);
    }

    function khLoadMore(n) {
        const url = new URL(window.location.href);
        url.searchParams.set('page', 'customers');
        url.searchParams.set('kh_limit', n);
        window.location.href = url.toString();
    }
    (function() {
        const p = new URLSearchParams(window.location.search).get('page');
        if (p === 'customers') {
            document.querySelectorAll('.page').forEach(x => x.classList.remove('active'));
            document.querySelectorAll('.nav-item').forEach(x => x.classList.remove('active'));
            const pg = document.getElementById('page-customers');
            if (pg) pg.classList.add('active');
            document.querySelectorAll('.nav-item').forEach(n => {
                if (n.getAttribute('onclick')?.includes("'customers'")) n.classList.add('active');
            });
            const t = document.getElementById('page-title');
            if (t) t.textContent = 'Quản lý khách hàng';
        }
    })();
</script>