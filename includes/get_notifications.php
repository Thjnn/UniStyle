<?php
require_once __DIR__ . '/../config/db.php';

header('Content-Type: application/json; charset=utf-8');

$notifications = [];

function buildNotificationLink($page, $targetId = null, $targetKey = null)
{
    $parts = ["page={$page}"];
    if ($targetId !== null && $targetKey !== null) {
        $parts[] = "targetKey={$targetKey}";
        $parts[] = "targetId={$targetId}";
    }
    return '../admin/index.php#' . implode('&', $parts);
}

function pushNotification(&$notifications, $text, $type, $time, $page, $targetId = null, $targetKey = null)
{
    $notifications[] = [
        'id' => md5($page . '|' . $targetKey . '|' . $targetId . '|' . $text . '|' . $time),
        'text' => $text,
        'type' => $type,
        'time' => $time,
        'link' => buildNotificationLink($page, $targetId, $targetKey),
        'page' => $page,
        'target_id' => $targetId,
        'target_key' => $targetKey,
    ];
}

$pendingCount = (int) ($conn->query("
    SELECT COUNT(*) AS total
    FROM dathang
    WHERE trangthai = 'Chờ xử lý'
")->fetch_assoc()['total'] ?? 0);

if ($pendingCount > 0) {
    pushNotification(
        $notifications,
        "Có {$pendingCount} đơn hàng đang chờ xử lý",
        'warning',
        date('Y-m-d H:i:s'),
        'orders'
    );
}

$shippingCount = (int) ($conn->query("
    SELECT COUNT(*) AS total
    FROM dathang
    WHERE trangthai = 'Đang giao'
")->fetch_assoc()['total'] ?? 0);

if ($shippingCount > 0) {
    pushNotification(
        $notifications,
        "Có {$shippingCount} đơn hàng đang giao",
        'info',
        date('Y-m-d H:i:s'),
        'orders'
    );
}

$cancelledTodayCount = (int) ($conn->query("
    SELECT COUNT(*) AS total
    FROM dathang
    WHERE trangthai = 'Đã hủy'
      AND DATE(ngaydat) = CURDATE()
")->fetch_assoc()['total'] ?? 0);

if ($cancelledTodayCount > 0) {
    pushNotification(
        $notifications,
        "Hôm nay có {$cancelledTodayCount} đơn hàng đã bị hủy",
        'danger',
        date('Y-m-d H:i:s'),
        'orders'
    );
}

$pendingOrders = $conn->query("
    SELECT dh.madh, dh.ngaydat, kh.tenkh
    FROM dathang dh
    LEFT JOIN khachhang kh ON kh.makh = dh.makh
    WHERE dh.trangthai = 'Chờ xử lý'
    ORDER BY dh.ngaydat DESC
    LIMIT 3
");

if ($pendingOrders) {
    while ($row = $pendingOrders->fetch_assoc()) {
        $customerName = trim((string) ($row['tenkh'] ?? ''));
        $suffix = $customerName !== '' ? " từ {$customerName}" : '';
        pushNotification(
            $notifications,
            "Đơn #DH-{$row['madh']} đang chờ xử lý{$suffix}",
            'warning',
            $row['ngaydat'],
            'orders',
            (int) $row['madh'],
            'data-oid'
        );
    }
}

$lowStockProducts = $conn->query("
    SELECT MaSP, TenSP, SoLuongTon
    FROM sanpham
    WHERE SoLuongTon > 0 AND SoLuongTon <= 100
    ORDER BY SoLuongTon ASC, MaSP DESC
    LIMIT 3
");

if ($lowStockProducts) {
    while ($row = $lowStockProducts->fetch_assoc()) {
        pushNotification(
            $notifications,
            "Sản phẩm {$row['TenSP']} sắp hết hàng, còn {$row['SoLuongTon']} sản phẩm",
            'warning',
            date('Y-m-d H:i:s'),
            'inventory',
            (int) $row['MaSP'],
            'data-pid'
        );
    }
}

$outOfStockCount = (int) ($conn->query("
    SELECT COUNT(*) AS total
    FROM sanpham
    WHERE SoLuongTon <= 0
")->fetch_assoc()['total'] ?? 0);

if ($outOfStockCount > 0) {
    pushNotification(
        $notifications,
        "Có {$outOfStockCount} sản phẩm đã hết hàng trong kho",
        'danger',
        date('Y-m-d H:i:s'),
        'inventory'
    );
}

$newCustomersToday = (int) ($conn->query("
    SELECT COUNT(*) AS total
    FROM khachhang
    WHERE DATE(ngay_dangky) = CURDATE()
")->fetch_assoc()['total'] ?? 0);

if ($newCustomersToday > 0) {
    pushNotification(
        $notifications,
        "Hôm nay có {$newCustomersToday} khách hàng mới đăng ký",
        'success',
        date('Y-m-d H:i:s'),
        'customers'
    );
}

$latestCustomers = $conn->query("
    SELECT makh, tenkh, ngay_dangky
    FROM khachhang
    WHERE ngay_dangky IS NOT NULL
    ORDER BY ngay_dangky DESC
    LIMIT 2
");

if ($latestCustomers) {
    while ($row = $latestCustomers->fetch_assoc()) {
        pushNotification(
            $notifications,
            "Khách hàng mới: {$row['tenkh']}",
            'success',
            $row['ngay_dangky'],
            'customers',
            (int) $row['makh'],
            'data-khmakh'
        );
    }
}

usort($notifications, function ($a, $b) {
    return strtotime($b['time']) <=> strtotime($a['time']);
});

echo json_encode(array_slice($notifications, 0, 10), JSON_UNESCAPED_UNICODE);
