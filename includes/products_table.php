<?php

/**
 * products_table.php — Grouped Category Filter
 * ─────────────────────────────────────────────
 * Hỗ trợ 2 chế độ tự động:
 *   A) Nếu bảng danhmuc CÓ cột parent_id → dùng cấu trúc cha–con thật
 *   B) Nếu KHÔNG có parent_id → fallback: gom nhóm theo từ khóa tên
 */

require_once __DIR__ . '/../config/db.php';

// ── Params ────────────────────────────────────────────────────────────────────
$search  = trim($_GET['sp_q']      ?? '');
$danhmuc = trim($_GET['danhmuc']   ?? '');
$limit   = isset($_GET['sp_limit']) ? (int)$_GET['sp_limit'] : 10;

// ══════════════════════════════════════════════════════════════════════════════
//  BƯỚC 1: Kiểm tra bảng có cột parent_id không
// ══════════════════════════════════════════════════════════════════════════════
$has_parent_id = false;
$cols = $conn->query("SHOW COLUMNS FROM danhmuc LIKE 'parent_id'");
if ($cols && $cols->num_rows > 0) $has_parent_id = true;

// ══════════════════════════════════════════════════════════════════════════════
//  BƯỚC 2: Lấy danh mục + đếm SP — 1 query duy nhất (không N+1)
// ══════════════════════════════════════════════════════════════════════════════
if ($has_parent_id) {
    // ── Chế độ A: có parent_id thật ──────────────────────────────────────────
    $dm_rows = $conn->query("
        SELECT d.madanhmuc, d.tendanhmuc, d.parent_id,
               COUNT(sp.MaSP) AS so_luong
        FROM danhmuc d
        LEFT JOIN sanpham sp ON sp.madanhmuc = d.madanhmuc
        GROUP BY d.madanhmuc, d.tendanhmuc, d.parent_id
        ORDER BY COALESCE(d.parent_id, d.madanhmuc), d.parent_id IS NULL DESC, d.tendanhmuc
    ");

    $all_dm     = [];
    $parent_map = []; // parent_id → [children]
    $root_dm    = []; // danh mục cha (parent_id IS NULL)

    while ($r = $dm_rows->fetch_assoc()) {
        $all_dm[$r['madanhmuc']] = $r;
        if ($r['parent_id'] === null) {
            $root_dm[] = $r;
        } else {
            $parent_map[$r['parent_id']][] = $r;
        }
    }
} else {
    // ── Chế độ B: Fallback — gom nhóm theo từ khóa tên ──────────────────────
    // Mapping: "tên chứa từ khóa" → nhóm cha
    $group_rules = [
        'Bút'    => ['Bút bi', 'Bút kí', 'Bút lông', 'Bút Chì'],
        'Giấy'   => ['Giấy ghi nhớ', 'Giấy các loại'],
        'Văn phòng phẩm' => ['Bấm kim', 'Băng keo', 'Dao rọc giấy', 'Hộp dấu', 'Hình dán'],
        'Sách & Vở' => ['Sổ', 'Vở', 'Bìa hồ sơ'],
        'Khác'   => [], // các danh mục còn lại vào đây
    ];

    $dm_rows = $conn->query("
        SELECT d.madanhmuc, d.tendanhmuc,
               COUNT(sp.MaSP) AS so_luong
        FROM danhmuc d
        LEFT JOIN sanpham sp ON sp.madanhmuc = d.madanhmuc
        GROUP BY d.madanhmuc, d.tendanhmuc
        ORDER BY d.tendanhmuc
    ");

    $all_dm  = [];
    while ($r = $dm_rows->fetch_assoc()) {
        $all_dm[$r['madanhmuc']] = $r;
    }

    // Phân loại vào nhóm
    $root_dm    = [];
    $parent_map = []; // group_key → [items]
    $assigned   = [];

    foreach ($group_rules as $group => $members) {
        if ($group === 'Khác') continue;
        $grp_id  = 'grp_' . md5($group); // ID ảo
        $grp_total = 0;
        $children  = [];

        foreach ($all_dm as $id => $dm) {
            if (in_array($dm['tendanhmuc'], $members)) {
                $children[]     = $dm + ['parent_id' => $grp_id];
                $grp_total     += (int)$dm['so_luong'];
                $assigned[$id]  = true;
            }
        }

        if (!empty($children)) {
            $root_dm[] = [
                'madanhmuc'  => $grp_id,
                'tendanhmuc' => $group,
                'so_luong'   => $grp_total,
                'is_group'   => true,
            ];
            $parent_map[$grp_id] = $children;
        }
    }

    // Các danh mục chưa được gán → nhóm "Khác" hoặc hiển thị thẳng
    $ungrouped = array_filter($all_dm, fn($dm) => !isset($assigned[$dm['madanhmuc']]));
    foreach ($ungrouped as $dm) {
        $root_dm[] = $dm + ['is_group' => false, 'parent_id' => null];
    }
}

// ══════════════════════════════════════════════════════════════════════════════
//  BƯỚC 3: WHERE động cho sản phẩm
// ══════════════════════════════════════════════════════════════════════════════
$where = [];
if ($search !== '') {
    $s       = $conn->real_escape_string($search);
    $where[] = "sp.TenSP LIKE '%$s%'";
}
if ($danhmuc !== '') {
    $where[] = "sp.madanhmuc = " . (int)$danhmuc;
}
$where_sql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

$total_sp  = (int)$conn->query("SELECT COUNT(*) AS c FROM sanpham sp $where_sql")->fetch_assoc()['c'];

// ══════════════════════════════════════════════════════════════════════════════
//  BƯỚC 4: Query sản phẩm chính
// ══════════════════════════════════════════════════════════════════════════════
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

// ══════════════════════════════════════════════════════════════════════════════
//  BƯỚC 5: Helpers
// ══════════════════════════════════════════════════════════════════════════════
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

// Tính tổng sp của danhmuc đang active
$total_sp_of_selected = $danhmuc !== '' ? (int)$conn->query(
    "SELECT COUNT(*) AS c FROM sanpham WHERE madanhmuc=" . (int)$danhmuc
)->fetch_assoc()['c'] : $total_sp;
?>

<!-- ══════════════════════════════════════════════════════════════════
     FILTER: Grouped Category
══════════════════════════════════════════════════════════════════ -->
<div class="cat-filter-wrap" id="cat-filter-wrap">

    <!-- Thanh search nhỏ gọn -->
    <div class="cat-search-row">
        <button class="filter-chip <?= $danhmuc === '' ? 'active' : '' ?>"
            onclick="prodFilter(null, '')">
            Tất cả&nbsp;<span class="cat-count"><?= $total_sp ?></span>
        </button>


    </div>

    <!-- Danh sách nhóm danh mục -->
    <div class="cat-groups" id="cat-groups">
        <?php foreach ($root_dm as $parent):
            $pid      = $parent['madanhmuc'];
            $children = $parent_map[$pid] ?? [];
            $hasChild = !empty($children);

            // Kiểm tra child nào đang được chọn
            $childActive = false;
            if ($danhmuc !== '' && $hasChild) {
                foreach ($children as $ch) {
                    if ((string)$ch['madanhmuc'] === $danhmuc) {
                        $childActive = true;
                        break;
                    }
                }
            }
            $isSelfActive = (string)$pid === $danhmuc;
        ?>

            <div class="cat-group <?= ($childActive || $isSelfActive) ? 'open' : '' ?>"
                data-pid="<?= htmlspecialchars((string)$pid) ?>">

                <!-- Header nhóm -->
                <button class="cat-parent-btn <?= ($isSelfActive || $childActive) ? 'active' : '' ?>"
                    onclick="<?= $hasChild
                                    ? "toggleCatGroup(this.closest('.cat-group'))"
                                    : "prodFilter(null, '" . htmlspecialchars((string)$pid) . "')"
                                ?>">
                    <span class="cat-parent-label"><?= htmlspecialchars($parent['tendanhmuc']) ?></span>
                    <span class="cat-count"><?= $parent['so_luong'] ?? 0 ?></span>
                    <?php if ($hasChild): ?>
                        <svg class="cat-arrow" width="11" height="11" viewBox="0 0 16 16"
                            fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round">
                            <path d="M4 6l4 4 4-4" />
                        </svg>
                    <?php endif; ?>
                </button>

                <!-- Danh mục con -->
                <?php if ($hasChild): ?>
                    <div class="cat-children">
                        <?php foreach ($children as $child):
                            $cid         = $child['madanhmuc'];
                            $isChildActive = (string)$cid === $danhmuc;
                        ?>
                            <button class="cat-child-btn <?= $isChildActive ? 'active' : '' ?>"
                                onclick="prodFilter(null, '<?= htmlspecialchars((string)$cid) ?>')">
                                <span class="cat-dot"></span>
                                <?= htmlspecialchars($child['tendanhmuc']) ?>
                                <span class="cat-count"><?= $child['so_luong'] ?? 0 ?></span>
                            </button>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

            </div><!-- /.cat-group -->
        <?php endforeach; ?>
    </div><!-- /.cat-groups -->

</div><!-- /.cat-filter-wrap -->


<!-- ══════════════════════════════════════════════════════════════════
     BẢNG SẢN PHẨM
══════════════════════════════════════════════════════════════════ -->
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
                        $tonColor = $row['SoLuongTon'] <= 0  ? 'var(--danger)'
                            : ($row['SoLuongTon'] <= 20 ? 'var(--warning)' : '');
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
                                        <div class="prod-sku">SP-<?= str_pad($row['MaSP'], 3, '0', STR_PAD_LEFT) ?></div>
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


<!-- ══════════════════════════════════════════════════════════════════
     CSS
══════════════════════════════════════════════════════════════════ -->
<style>
    /* Wrapper */
    .cat-filter-wrap {
        margin-bottom: 18px;
    }

    /* Hàng search + nút "Tất cả" */
    .cat-search-row {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 10px;
        flex-wrap: wrap;
    }

    .cat-search-box {
        display: flex;
        align-items: center;
        gap: 7px;
        background: var(--bg-page);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        padding: 6px 12px;
        flex: 1;
        min-width: 180px;
        max-width: 320px;
        transition: border-color .15s;
    }

    .cat-search-box:focus-within {
        border-color: var(--accent-mid);
    }

    .cat-search-box input {
        border: none;
        background: none;
        outline: none;
        font-size: 13px;
        color: var(--text-primary);
        font-family: 'Be Vietnam Pro', sans-serif;
        width: 100%;
    }

    .cat-search-box input::placeholder {
        color: var(--text-muted);
    }

    /* Nhóm danh mục — dạng hàng ngang có thể expand */
    .cat-groups {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        align-items: flex-start;
    }

    /* Mỗi nhóm danh mục */
    .cat-group {
        position: relative;
    }

    /* Nút cha */
    .cat-parent-btn {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 12px;
        background: var(--bg-page);
        border: 1px solid var(--border);
        border-radius: 20px;
        font-size: 12.5px;
        font-weight: 500;
        color: var(--text-secondary);
        cursor: pointer;
        font-family: 'Be Vietnam Pro', sans-serif;
        transition: all .15s;
        white-space: nowrap;
    }

    .cat-parent-btn:hover {
        border-color: var(--accent-mid);
        color: var(--accent-mid);
    }

    .cat-parent-btn.active {
        background: var(--accent-hover);
        border-color: var(--accent-mid);
        color: #fff;
        font-weight: 600;
        box-shadow: inset 0 0 0 1px rgba(255, 255, 255, .08), 0 4px 10px rgba(29, 78, 216, .18);
        filter: saturate(1.08) brightness(.95);
    }

    .cat-parent-btn.active .cat-count {
        color: rgba(255, 255, 255, .75);
    }

    /* Mũi tên xoay khi open */
    .cat-arrow {
        transition: transform .2s;
        flex-shrink: 0;
        color: var(--text-muted);
    }

    .cat-group.open .cat-arrow {
        transform: rotate(180deg);
    }

    /* Dropdown con */
    .cat-children {
        display: none;
        position: absolute;
        top: calc(100% + 6px);
        left: 0;
        min-width: 180px;
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 10px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, .10);
        z-index: 200;
        padding: 4px;
        animation: catDropIn .14s ease;
    }

    @keyframes catDropIn {
        from {
            opacity: 0;
            transform: translateY(-6px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .cat-group.open .cat-children {
        display: block;
    }

    /* Nút con */
    .cat-child-btn {
        display: flex;
        align-items: center;
        gap: 8px;
        width: 100%;
        padding: 8px 12px;
        background: none;
        border: none;
        border-radius: 7px;
        font-size: 13px;
        color: var(--text-primary);
        cursor: pointer;
        font-family: 'Be Vietnam Pro', sans-serif;
        transition: background .12s;
        text-align: left;
    }

    .cat-child-btn:hover {
        background: var(--bg-page);
    }

    .cat-child-btn.active {
        background: var(--accent-light);
        color: var(--accent-mid);
        font-weight: 700;
        box-shadow: inset 0 0 0 1px rgba(29, 78, 216, .10);
    }

    .cat-child-btn.active .cat-dot {
        background: var(--accent-mid);
    }

    /* Dot nhỏ bên trái */
    .cat-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: var(--border);
        flex-shrink: 0;
        transition: background .15s;
    }

    /* Badge số lượng */
    .cat-count {
        font-size: 11px;
        color: var(--text-muted);
        margin-left: auto;
        padding-left: 6px;
    }

    .cat-parent-btn.active .cat-count {
        color: rgba(255, 255, 255, .8);
    }

    /* Filter chip "Tất cả" tái dùng */
    .filter-chip {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 14px;
        background: var(--bg-page);
        border: 1px solid var(--border);
        border-radius: 20px;
        font-size: 12.5px;
        font-weight: 500;
        color: var(--text-secondary);
        cursor: pointer;
        font-family: 'Be Vietnam Pro', sans-serif;
        transition: all .15s;
        white-space: nowrap;
    }

    .filter-chip.active {
        background: var(--accent-hover);
        border-color: var(--accent-mid);
        color: #fff;
        font-weight: 600;
        box-shadow: inset 0 0 0 1px rgba(255, 255, 255, .08), 0 4px 10px rgba(29, 78, 216, .18);
        filter: saturate(1.08) brightness(.95);
    }

    .filter-chip.active .cat-count {
        color: rgba(255, 255, 255, .75);
    }
</style>


<!-- ══════════════════════════════════════════════════════════════════
     JAVASCRIPT
══════════════════════════════════════════════════════════════════ -->
<script>
    /* ── Toggle nhóm danh mục ── */
    function toggleCatGroup(groupEl) {
        const isOpen = groupEl.classList.contains('open');

        // Đóng tất cả nhóm khác
        document.querySelectorAll('.cat-group.open').forEach(g => {
            if (g !== groupEl) g.classList.remove('open');
        });

        // Toggle nhóm hiện tại
        groupEl.classList.toggle('open', !isOpen);
    }

    /* Đóng dropdown khi click ra ngoài */
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.cat-group')) {
            document.querySelectorAll('.cat-group.open').forEach(g => g.classList.remove('open'));
        }
    });

    /* ── URL helpers — KHÔNG thay đổi logic backend ── */
    function prodUpdateUrl(params) {
        const url = new URL(window.location.href);
        url.searchParams.set('page', 'products');
        for (const [k, v] of Object.entries(params)) {
            if (v === '') url.searchParams.delete(k);
            else url.searchParams.set(k, v);
        }
        window.location.href = url.toString();
    }

    /* prodFilter: el không dùng (null), chỉ dùng madanhmuc */
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
        _prodTimer = setTimeout(() =>
            prodUpdateUrl({
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

    /* Kích hoạt trang products nếu URL có page=products */
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