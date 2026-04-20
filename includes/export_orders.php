<?php
// KHÔNG có khoảng trắng trước dòng này!

require_once __DIR__ . '/../config/db.php';

// Kiểm tra header đã gửi chưa
if (headers_sent()) {
    die("Lỗi: Header đã được gửi trước đó!");
}

// Header tải file CSV
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=Danh_sach_don_hang_' . date('d-m-Y') . '.csv');

// Mở output
$output = fopen('php://output', 'w');

// BOM UTF-8 (fix lỗi tiếng Việt trong Excel)
fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

// Tiêu đề
fputcsv($output, ['DANH SÁCH ĐƠN HÀNG']);
fputcsv($output, ['Ngày xuất:', date('d/m/Y H:i')]);
fputcsv($output, []);

// Header cột
fputcsv($output, ['Mã Đơn', 'Khách Hàng', 'Ngày Đặt', 'Tổng Tiền', 'Trạng Thái']);

// SQL (đúng theo DB của bạn)
$sql = "SELECT 
            d.madh AS madon,
            k.tenkh AS hoten,
            d.ngaydat,
            d.tongtien,
            d.trangthai
        FROM dathang d
        LEFT JOIN khachhang k ON d.makh = k.makh
        ORDER BY d.ngaydat DESC";

$result = $conn->query($sql);

// Nếu lỗi SQL → dừng luôn (để debug)
if (!$result) {
    die("SQL lỗi: " . $conn->error);
}

// Xuất dữ liệu
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, [
            $row['madon'],
            $row['hoten'] ? $row['hoten'] : 'Không rõ',
            $row['ngaydat'] ? date('d/m/Y', strtotime($row['ngaydat'])) : '',
            number_format($row['tongtien']) . ' VND',
            $row['trangthai']
        ]);
    }
} else {
    fputcsv($output, ['Không có dữ liệu']);
}

// Đóng file
fclose($output);
exit();
