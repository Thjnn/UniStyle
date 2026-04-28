<?php

/**
 * banner.php — Include dùng chung cho tất cả trang front-end
 * Đặt vào: includes/banner.php
 *
 * Cách dùng (trong index.php, shop.php...):
 *   require_once __DIR__ . '/includes/banner.php';
 *   echo render_banner('banner_home');
 *   echo render_banner('banner_top');
 */

function render_banner(string $vi_tri, string $asset_base = './assets/file_anh/'): string
{
    global $conn;

    // ── Ảnh mặc định khi chưa có quảng cáo trong DB ─────────────────────────
    $defaults = [
        'banner_home'   => $asset_base . '1920_x_600___cta___6_.webp',
        'banner_home_2' => $asset_base . '8wthty42wz8modg-784-he-thong-tu-thien-fly-to-sky-cong-ty-tnhh-doanh-nghiep-xa-hoi-tu-thien-va-ho-tro-phat-trien-cong-dong-fly-to-sky.png',
        'banner_home_3' => $asset_base . '1920_x_600___cta__1_d652d361086646d3b12a89b38ce6c294.jpg',
        'banner_top'    => $asset_base . '1920_x_600___cta__1_d652d361086646d3b12a89b38ce6c294.jpg',
        'popup'         => '',
        'sidebar'       => '',
    ];

    // ── Tự tạo bảng nếu chưa có ─────────────────────────────────────────────
    static $table_ok = false;
    if (!$table_ok) {
        $conn->query("
            CREATE TABLE IF NOT EXISTS `quangcao` (
                `id`             INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `ten_qc`         VARCHAR(255) NOT NULL,
                `hinh_anh`       VARCHAR(255) DEFAULT '',
                `link`           VARCHAR(500) DEFAULT '',
                `vi_tri`         ENUM('banner_top','banner_home','banner_home_2','banner_home_3','popup','sidebar') DEFAULT 'banner_home',
                `mo_ta`          TEXT,
                `trang_thai`     TINYINT(1)   DEFAULT 1,
                `thu_tu`         INT          DEFAULT 0,
                `ngay_bat_dau`   DATE         DEFAULT NULL,
                `ngay_ket_thuc`  DATE         DEFAULT NULL,
                `created_at`     DATETIME     DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
        $table_ok = true;
    }

    // ── Query banners đang hoạt động ─────────────────────────────────────────
    $vt    = $conn->real_escape_string($vi_tri);
    $today = date('Y-m-d');

    $res = $conn->query("
        SELECT id, ten_qc, hinh_anh, link
        FROM quangcao
        WHERE vi_tri = '$vt'
          AND trang_thai = 1
          AND (ngay_bat_dau  IS NULL OR ngay_bat_dau  <= '$today')
          AND (ngay_ket_thuc IS NULL OR ngay_ket_thuc >= '$today')
        ORDER BY thu_tu ASC, id DESC
        LIMIT 5
    ");

    $banners = [];
    if ($res) while ($r = $res->fetch_assoc()) $banners[] = $r;

    // ── Fallback: dùng ảnh mặc định nếu chưa có quảng cáo ──────────────────
    if (empty($banners)) {
        $img = $defaults[$vi_tri] ?? '';
        if (!$img) return ''; // không có fallback → không render gì
        $banners = [['id' => 0, 'ten_qc' => '', 'hinh_anh' => $img, 'link' => '']];
    }

    // ── Tính src ảnh ─────────────────────────────────────────────────────────
    foreach ($banners as &$b) {
        $b['src'] = $b['id'] === 0
            ? htmlspecialchars($b['hinh_anh'])                        // fallback: đường dẫn đầy đủ
            : $asset_base . htmlspecialchars($b['hinh_anh']);         // từ DB: prefix assets
    }
    unset($b);

    // ══════════════════════════════════════════════════════════════════════════
    //  Chỉ 1 banner → render ảnh đơn giản
    // ══════════════════════════════════════════════════════════════════════════
    if (count($banners) === 1) {
        $b   = $banners[0];
        $alt = htmlspecialchars($b['ten_qc'] ?: 'Banner quảng cáo');
        $href = $b['link'] ? htmlspecialchars($b['link']) : '#!';
        $target = $b['link'] ? ' target="_blank"' : '';
        return "<a href=\"{$href}\"{$target}>"
            . "<img src=\"{$b['src']}\" alt=\"{$alt}\" style=\"width:100%;display:block\">"
            . "</a>";
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  Nhiều banner → Slider tự động
    // ══════════════════════════════════════════════════════════════════════════
    $uid = 'qcs_' . substr(md5($vi_tri . mt_rand()), 0, 7);

    ob_start();
?>
    <div class="qc-slider" id="<?= $uid ?>">
        <div class="qc-track">
            <?php foreach ($banners as $i => $b):
                $alt  = htmlspecialchars($b['ten_qc'] ?: 'Banner quảng cáo');
                $href = $b['link'] ? htmlspecialchars($b['link']) : '#!';
                $tgt  = $b['link'] ? ' target="_blank"' : '';
            ?>
                <div class="qc-slide <?= $i === 0 ? 'active' : '' ?>">
                    <a href="<?= $href ?>" <?= $tgt ?>>
                        <img src="<?= $b['src'] ?>" alt="<?= $alt ?>" style="width:100%;display:block">
                    </a>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Dots -->
        <div class="qc-dots">
            <?php for ($i = 0; $i < count($banners); $i++): ?>
                <button class="qc-dot <?= $i === 0 ? 'active' : '' ?>"
                    onclick="qcGoTo('<?= $uid ?>', <?= $i ?>)"></button>
            <?php endfor; ?>
        </div>

        <!-- Prev / Next -->
        <button class="qc-nav qc-prev" onclick="qcPrev('<?= $uid ?>')">&#8249;</button>
        <button class="qc-nav qc-next" onclick="qcNext('<?= $uid ?>')">&#8250;</button>
    </div>
<?php
    return ob_get_clean();
}
