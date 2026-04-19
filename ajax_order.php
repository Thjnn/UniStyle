<?php
/*
 * ajax_order.php
 * Xử lý các hành động AJAX trên đơn hàng từ profile.php
 * Đặt file này cùng thư mục gốc với profile.php
 */
session_start();
include('./config/db.php');

header('Content-Type: application/json');

// Kiểm tra đăng nhập
if (!isset($_SESSION['khachhang_id'])) {
    echo json_encode(['success' => false, 'message' => 'Chưa đăng nhập']);
    exit();
}

$kh_id  = (int)$_SESSION['khachhang_id'];
$action = $_POST['action'] ?? '';
$madh   = (int)($_POST['madh'] ?? 0);

if ($madh <= 0) {
    echo json_encode(['success' => false, 'message' => 'Mã đơn hàng không hợp lệ']);
    exit();
}

// Kiểm tra đơn hàng thuộc về khách này
$stmt = $conn->prepare("SELECT madh, trangthai FROM dathang WHERE madh = ? AND makh = ?");
$stmt->bind_param("ii", $madh, $kh_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$order) {
    echo json_encode(['success' => false, 'message' => 'Không tìm thấy đơn hàng']);
    exit();
}

// ── Hủy đơn ──────────────────────────────────────────────────────────────
if ($action === 'cancel') {
    if ($order['trangthai'] !== 'Chờ xác nhận') {
        echo json_encode(['success' => false, 'message' => 'Chỉ có thể hủy đơn đang chờ xác nhận']);
        exit();
    }

    $conn->begin_transaction();
    try {
        // Hoàn lại tồn kho
        $stmt = $conn->prepare(
            "SELECT MaSP, soluong FROM chitietdathang WHERE madh = ?"
        );
        $stmt->bind_param("i", $madh);
        $stmt->execute();
        $items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        foreach ($items as $it) {
            $stmt2 = $conn->prepare(
                "UPDATE sanpham SET SoLuongTon = SoLuongTon + ?, SoLuongDaBan = SoLuongDaBan - ?
                 WHERE MaSP = ?"
            );
            $stmt2->bind_param("iii", $it['soluong'], $it['soluong'], $it['MaSP']);
            $stmt2->execute();
            $stmt2->close();
        }

        // Cập nhật trạng thái dathang
        $stmt = $conn->prepare("UPDATE dathang SET trangthai = 'Đã hủy' WHERE madh = ?");
        $stmt->bind_param("i", $madh);
        $stmt->execute();
        $stmt->close();

        // Cập nhật trạng thái hoadon nếu có
        $stmt = $conn->prepare("UPDATE hoadon SET trangthai = 'Đã hủy' WHERE madh = ?");
        $stmt->bind_param("i", $madh);
        $stmt->execute();
        $stmt->close();

        $conn->commit();
        echo json_encode(['success' => true]);

    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }

// ── Xác nhận đã nhận hàng ────────────────────────────────────────────────
} elseif ($action === 'received') {
    if ($order['trangthai'] !== 'Đang giao') {
        echo json_encode(['success' => false, 'message' => 'Đơn hàng chưa ở trạng thái đang giao']);
        exit();
    }

    $conn->begin_transaction();
    try {
        $stmt = $conn->prepare("UPDATE dathang SET trangthai = 'Hoàn thành' WHERE madh = ?");
        $stmt->bind_param("i", $madh);
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare("UPDATE hoadon SET trangthai = 'Đã thanh toán' WHERE madh = ?");
        $stmt->bind_param("i", $madh);
        $stmt->execute();
        $stmt->close();

        $conn->commit();
        echo json_encode(['success' => true]);

    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }

} else {
    echo json_encode(['success' => false, 'message' => 'Hành động không hợp lệ']);
}