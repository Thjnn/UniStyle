<?php

/**
 * banner.php — Include dùng chung cho tất cả trang front-end
 * Cách dùng:
 *   require_once 'banner.php';
 *   echo render_banner('banner_home');   // trang chủ
 *   echo render_banner('banner_top');    // trang shop
 *
 * Tự tạo bảng quangcao nếu chưa có.
 */

function render_banner(string $vi_tri, array $opts = []): string
{
    global $conn;

    $default_imgs = [
        'banner_home' => './assets/file_anh/1920_x_600___cta___6_.webp',
        'banner_top'  => './assets/file_anh/1920_x_600___cta__1_d652d361086646d3b12a89b38ce6c294.jpg',
        'popup'       => '',
        'sidebar'     => '',
    ];

    // Đảm bảo bảng tồn tại
    static $table_checked = false;
    if (!$table_checked) {
        $conn->query("
            CREATE TABLE IF NOT EXISTS `quangcao` (
                `id`             INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `ten_qc`         VARCHAR(255) NOT NULL,
                `hinh_anh`       VARCHAR(255) DEFAULT '',
                `link`           VARCHAR(500) DEFAULT '',
                `vi_tri`         ENUM('banner_top','banner_home','popup','sidebar') DEFAULT 'banner_home',
                `mo_ta`          TEXT,
                `trang_thai`     TINYINT(1)   DEFAULT 1,
                `thu_tu`         INT          DEFAULT 0,
                `ngay_bat_dau`   DATE         DEFAULT NULL,
                `ngay_ket_thuc`  DATE         DEFAULT NULL,
                `created_at`     DATETIME     DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
        $table_checked = true;
    }

    $vt    = $conn->real_escape_string($vi_tri);
    $today = date('Y-m-d');

    // Query banners đang hoạt động, đúng vị trí, đúng thời hạn
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
    if ($res) {
        while ($r = $res->fetch_assoc()) $banners[] = $r;
    }

    // Không có banner trong DB → dùng ảnh mặc định (fallback)
    if (empty($banners)) {
        $default = $default_imgs[$vi_tri] ?? '';
        if (!$default) return '';
        $banners = [[
            'id'       => 0,
            'ten_qc'   => '',
            'hinh_anh' => $default,
            'link'     => '',
        ]];
    }

    // ── Render ──────────────────────────────────────────────────────────────
    if (count($banners) === 1) {
        // Chỉ 1 banner → ảnh đơn giản
        $b    = $banners[0];
        $src  = $b['id'] === 0 ? htmlspecialchars($b['hinh_anh'])
            : './assets/file_anh/' . htmlspecialchars($b['hinh_anh']);
        $alt  = htmlspecialchars($b['ten_qc'] ?: 'Banner quảng cáo');
        $wrap_open  = $b['link'] ? '<a href="' . htmlspecialchars($b['link']) . '" target="_blank">' : '<a href="#!">';
        $wrap_close = '</a>';
        return "$wrap_open<img src=\"$src\" alt=\"$alt\" style=\"width:100%;display:block\">$wrap_close";
    }

    // Nhiều banner → slider tự động
    $uid = 'banner_' . substr(md5($vi_tri . microtime()), 0, 6);
    ob_start();
?>
    <div class="qc-slider" id="<?= $uid ?>" data-vi_tri="<?= htmlspecialchars($vi_tri) ?>">
        <div class="qc-track">
            <?php foreach ($banners as $i => $b):
                $src = $b['id'] === 0 ? htmlspecialchars($b['hinh_anh'])
                    : './assets/file_anh/' . htmlspecialchars($b['hinh_anh']);
                $alt = htmlspecialchars($b['ten_qc'] ?: 'Banner quảng cáo');
                $lnk = $b['link'] ? htmlspecialchars($b['link']) : '#!';
            ?>
                <div class="qc-slide <?= $i === 0 ? 'active' : '' ?>">
                    <a href="<?= $lnk ?>" <?= $b['link'] ? 'target="_blank"' : '' ?>>
                        <img src="<?= $src ?>" alt="<?= $alt ?>" style="width:100%;display:block">
                    </a>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Dots điều hướng -->
        <?php if (count($banners) > 1): ?>
            <div class="qc-dots">
                <?php for ($i = 0; $i < count($banners); $i++): ?>
                    <button class="qc-dot <?= $i === 0 ? 'active' : '' ?>"
                        onclick="qcGoTo('<?= $uid ?>', <?= $i ?>)"></button>
                <?php endfor; ?>
            </div>
            <!-- Nút prev/next -->
            <button class="qc-nav qc-prev" onclick="qcPrev('<?= $uid ?>')">&#8249;</button>
            <button class="qc-nav qc-next" onclick="qcNext('<?= $uid ?>')">&#8250;</button>
        <?php endif; ?>
    </div>
<?php
    return ob_get_clean();
}
