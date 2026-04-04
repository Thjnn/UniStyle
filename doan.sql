-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.4.3 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table doan.bienthe_sanpham
CREATE TABLE IF NOT EXISTS `bienthe_sanpham` (
  `id` int NOT NULL AUTO_INCREMENT,
  `madanhmuc` int DEFAULT NULL,
  `LoaiThuocTinh` varchar(50) DEFAULT NULL,
  `GiaTri` varchar(50) DEFAULT NULL,
  `SoLuong` int DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `madanhmuc` (`madanhmuc`),
  CONSTRAINT `bienthe_sanpham_ibfk_1` FOREIGN KEY (`madanhmuc`) REFERENCES `danhmuc` (`madanhmuc`)
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table doan.bienthe_sanpham: ~17 rows (approximately)
INSERT INTO `bienthe_sanpham` (`id`, `madanhmuc`, `LoaiThuocTinh`, `GiaTri`, `SoLuong`) VALUES
	(1, 1, 'kich_thuoc', 'A4', 100),
	(2, 1, 'kich_thuoc', 'A5', 100),
	(3, 1, 'mau', 'xanh', 100),
	(4, 1, 'mau', 'đỏ', 100),
	(5, 2, 'mau', 'đen', 100),
	(6, 2, 'mau', 'xanh', 100),
	(7, 2, 'loai', 'dây ngắn', 100),
	(8, 2, 'loai', 'dây dài', 100),
	(9, 3, 'loai', 'cơ bản', 50),
	(10, 3, 'loai', 'nâng cao', 50),
	(11, 3, 'mau', 'đen', 50),
	(12, 4, 'chieu_dai', '15cm', 100),
	(13, 4, 'chieu_dai', '20cm', 100),
	(14, 4, 'chieu_dai', '30cm', 100),
	(15, 5, 'kich_thuoc', 'nhỏ', 50),
	(16, 5, 'kich_thuoc', 'vừa', 50),
	(17, 5, 'kich_thuoc', 'lớn', 50),
	(18, 5, 'mau', 'đen', 50),
	(19, 5, 'mau', 'xanh', 50),
	(20, 6, 'size', '38', 100),
	(21, 6, 'size', '39', 100),
	(22, 6, 'size', '40', 100),
	(23, 6, 'size', '41', 100),
	(24, 6, 'size', '42', 100),
	(25, 7, 'kich_thuoc', 'A5', 100),
	(26, 7, 'kich_thuoc', 'A6', 100),
	(27, 7, 'so_trang', '100', 100),
	(28, 7, 'so_trang', '200', 100),
	(29, 8, 'mau', 'xanh', 200),
	(30, 8, 'mau', 'đỏ', 200),
	(31, 8, 'mau', 'đen', 200),
	(32, 9, 'mau', 'đen', 100),
	(33, 9, 'mau', 'vàng', 100),
	(34, 9, 'loai', 'cao cấp', 100),
	(35, 10, 'mau', 'đen', 150),
	(36, 10, 'mau', 'đỏ', 150),
	(37, 10, 'mau', 'xanh', 150),
	(38, 11, 'kich_thuoc', 'nhỏ', 200),
	(39, 11, 'kich_thuoc', 'trung', 200),
	(40, 11, 'kich_thuoc', 'lớn', 200),
	(41, 12, 'loai', 'tròn', 50),
	(42, 12, 'loai', 'vuông', 50),
	(43, 13, 'chủ_đề', 'anime', 100),
	(44, 13, 'chủ_đề', 'cute', 100),
	(45, 13, 'chủ_đề', 'vintage', 100),
	(46, 14, 'kich_thuoc', 'nhỏ', 100),
	(47, 14, 'kich_thuoc', 'lớn', 100),
	(48, 15, 'loai', 'mini', 100),
	(49, 15, 'loai', 'lớn', 100),
	(50, 16, 'loai', 'trong', 200),
	(51, 16, 'loai', 'đục', 200),
	(52, 17, 'do_cung', '2B', 200),
	(53, 17, 'do_cung', '3B', 200),
	(54, 17, 'do_cung', 'HB', 200),
	(55, 18, 'so_trang', '100', 150),
	(56, 18, 'so_trang', '200', 150),
	(57, 19, 'kich_thuoc', 'A4', 300),
	(58, 19, 'kich_thuoc', 'A5', 300);

-- Dumping structure for table doan.chitietdathang
CREATE TABLE IF NOT EXISTS `chitietdathang` (
  `id` int NOT NULL AUTO_INCREMENT,
  `madh` int DEFAULT NULL,
  `MaSP` int DEFAULT NULL,
  `soluong` int DEFAULT NULL,
  `dongia` decimal(10,2) DEFAULT NULL,
  `thanhtien` decimal(12,2) DEFAULT NULL,
  `bienthe_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `madh` (`madh`),
  KEY `MaSP` (`MaSP`),
  KEY `fk_ctdh_bienthe` (`bienthe_id`),
  CONSTRAINT `chitietdathang_ibfk_1` FOREIGN KEY (`madh`) REFERENCES `dathang` (`madh`),
  CONSTRAINT `chitietdathang_ibfk_2` FOREIGN KEY (`MaSP`) REFERENCES `sanpham` (`MaSP`),
  CONSTRAINT `fk_ctdh_bienthe` FOREIGN KEY (`bienthe_id`) REFERENCES `bienthe_sanpham` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table doan.chitietdathang: ~3 rows (approximately)
INSERT INTO `chitietdathang` (`id`, `madh`, `MaSP`, `soluong`, `dongia`, `thanhtien`, `bienthe_id`) VALUES
	(1, 1, 1, 2, 5000.00, 10000.00, NULL),
	(2, 1, 3, 1, 7000.00, 7000.00, NULL),
	(3, 2, 1, 1, 5000.00, 5000.00, NULL);

-- Dumping structure for table doan.chitietgiohang
CREATE TABLE IF NOT EXISTS `chitietgiohang` (
  `id` int NOT NULL AUTO_INCREMENT,
  `magiahang` int DEFAULT NULL,
  `MaSP` int DEFAULT NULL,
  `soluong` int DEFAULT NULL,
  `ngaythem` date DEFAULT NULL,
  `bienthe_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `magiahang` (`magiahang`),
  KEY `MaSP` (`MaSP`),
  KEY `fk_ctgh_bienthe` (`bienthe_id`),
  CONSTRAINT `chitietgiohang_ibfk_1` FOREIGN KEY (`magiahang`) REFERENCES `giohang` (`magiahang`),
  CONSTRAINT `chitietgiohang_ibfk_2` FOREIGN KEY (`MaSP`) REFERENCES `sanpham` (`MaSP`),
  CONSTRAINT `fk_ctgh_bienthe` FOREIGN KEY (`bienthe_id`) REFERENCES `bienthe_sanpham` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table doan.chitietgiohang: ~3 rows (approximately)
INSERT INTO `chitietgiohang` (`id`, `magiahang`, `MaSP`, `soluong`, `ngaythem`, `bienthe_id`) VALUES
	(1, 1, 1, 2, '2026-03-01', NULL),
	(2, 1, 3, 1, '2026-03-01', NULL),
	(3, 2, 1, 1, '2026-03-02', NULL);

-- Dumping structure for table doan.danhmuc
CREATE TABLE IF NOT EXISTS `danhmuc` (
  `madanhmuc` int NOT NULL AUTO_INCREMENT,
  `tendanhmuc` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `hinhanh` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`madanhmuc`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table doan.danhmuc: ~19 rows (approximately)
INSERT INTO `danhmuc` (`madanhmuc`, `tendanhmuc`, `hinhanh`) VALUES
	(1, 'Bìa hồ sơ', 'folders.png'),
	(2, 'Bảng tên, dây đeo', 'employee.png'),
	(3, 'Máy tính', 'calculations.png'),
	(4, 'Thước kẻ', 'ruler.png'),
	(5, 'Balo', 'balo.png'),
	(6, 'Giày', 'shoes.png'),
	(7, 'Sổ', 'notebook.png'),
	(8, 'Bút bi', '20200615084636-but.png'),
	(9, 'Bút kí', 'stationery.png'),
	(10, 'Bút lông', 'stationery-2.png'),
	(11, 'Giấy ghi nhớ', 'post-it.png'),
	(12, 'Hộp dấu ', 'stamp.png'),
	(13, 'Hình dán', 'happy.png'),
	(14, 'Dao rọc giấy', 'cutter.png'),
	(15, 'Bấm kim', 'stapler.png'),
	(16, 'Băng keo', 'tape.png'),
	(17, 'Bút Chì', 'color-pencil.png'),
	(18, 'Vở', 'notebook-1.png'),
	(19, 'Giấy các loại', 'papers.png');

-- Dumping structure for table doan.dathang
CREATE TABLE IF NOT EXISTS `dathang` (
  `madh` int NOT NULL AUTO_INCREMENT,
  `makh` int DEFAULT NULL,
  `ngaydat` date DEFAULT NULL,
  `tongtien` decimal(12,2) DEFAULT NULL,
  `trangthai` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`madh`),
  KEY `makh` (`makh`),
  CONSTRAINT `dathang_ibfk_1` FOREIGN KEY (`makh`) REFERENCES `khachhang` (`makh`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table doan.dathang: ~2 rows (approximately)
INSERT INTO `dathang` (`madh`, `makh`, `ngaydat`, `tongtien`, `trangthai`) VALUES
	(1, 1, '2026-03-01', 17000.00, 'Đã đặt'),
	(2, 2, '2026-03-02', 5000.00, 'Đang xử lý');

-- Dumping structure for table doan.giohang
CREATE TABLE IF NOT EXISTS `giohang` (
  `magiahang` int NOT NULL AUTO_INCREMENT,
  `makh` int DEFAULT NULL,
  `tongtien` decimal(12,2) DEFAULT '0.00',
  PRIMARY KEY (`magiahang`),
  KEY `makh` (`makh`),
  CONSTRAINT `giohang_ibfk_1` FOREIGN KEY (`makh`) REFERENCES `khachhang` (`makh`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table doan.giohang: ~2 rows (approximately)
INSERT INTO `giohang` (`magiahang`, `makh`, `tongtien`) VALUES
	(1, 1, 17000.00),
	(2, 2, 5000.00);

-- Dumping structure for table doan.khachhang
CREATE TABLE IF NOT EXISTS `khachhang` (
  `makh` int NOT NULL AUTO_INCREMENT,
  `tenkh` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `diachi` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sdt` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tendangnhap` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `matkhau` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`makh`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table doan.khachhang: ~5 rows (approximately)
INSERT INTO `khachhang` (`makh`, `tenkh`, `diachi`, `sdt`, `tendangnhap`, `matkhau`) VALUES
	(1, 'Nguyễn Văn A', 'Hà Nội', '0911111111', 'user1', '123'),
	(2, 'Trần Văn B', 'TP HCM', '0922222222', 'user2', '123'),
	(3, 'Lê Văn C', 'Đà Nẵng', '0933333333', 'user3', '123'),
	(4, 'Phạm Văn D', 'Hải Phòng', '0944444444', 'user4', '123'),
	(5, 'Hoàng Văn E', 'Cần Thơ', '0955555555', 'user5', '123');

-- Dumping structure for table doan.khuyenmai
CREATE TABLE IF NOT EXISTS `khuyenmai` (
  `MaKhuyenMai` int NOT NULL AUTO_INCREMENT,
  `TenKhuyenMai` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `GiamGia` decimal(5,2) DEFAULT NULL,
  `NgayBatDau` date DEFAULT NULL,
  `NgayKetThuc` date DEFAULT NULL,
  PRIMARY KEY (`MaKhuyenMai`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table doan.khuyenmai: ~0 rows (approximately)
INSERT INTO `khuyenmai` (`MaKhuyenMai`, `TenKhuyenMai`, `GiamGia`, `NgayBatDau`, `NgayKetThuc`) VALUES
	(1, 'Giảm giá khai giảng', 10.00, '2026-08-01', '2026-09-30'),
	(2, 'Khuyến mãi mùa hè', 15.00, '2026-06-01', '2026-07-01');

-- Dumping structure for table doan.quantrivien
CREATE TABLE IF NOT EXISTS `quantrivien` (
  `maqtv` int NOT NULL AUTO_INCREMENT,
  `tenqtv` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tendangnhap` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `matkhau` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`maqtv`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table doan.quantrivien: ~0 rows (approximately)
INSERT INTO `quantrivien` (`maqtv`, `tenqtv`, `tendangnhap`, `matkhau`) VALUES
	(1, 'Admin', 'admin', '123456');

-- Dumping structure for table doan.sanpham
CREATE TABLE IF NOT EXISTS `sanpham` (
  `MaSP` int NOT NULL AUTO_INCREMENT,
  `TenSP` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `GiaBan` decimal(10,2) DEFAULT NULL,
  `SoLuongTon` int DEFAULT NULL,
  `Hinh` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `MoTa` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `madanhmuc` int DEFAULT NULL,
  `mathuonghieu` int DEFAULT NULL,
  `maqtv` int DEFAULT NULL,
  `MaKhuyenMai` int DEFAULT NULL,
  `SoLuongDaBan` int DEFAULT '0',
  `NoiBat` tinyint(1) DEFAULT '0',
  `Rating` int DEFAULT '5',
  `SoLuotDanhGia` int DEFAULT '0',
  PRIMARY KEY (`MaSP`),
  KEY `fk_danhmuc` (`madanhmuc`),
  KEY `fk_thuonghieu` (`mathuonghieu`),
  KEY `fk_sanpham_quantrivien` (`maqtv`),
  KEY `fk_sanpham_khuyenmai` (`MaKhuyenMai`),
  CONSTRAINT `fk_danhmuc` FOREIGN KEY (`madanhmuc`) REFERENCES `danhmuc` (`madanhmuc`),
  CONSTRAINT `fk_sanpham_khuyenmai` FOREIGN KEY (`MaKhuyenMai`) REFERENCES `khuyenmai` (`MaKhuyenMai`),
  CONSTRAINT `fk_sanpham_quantrivien` FOREIGN KEY (`maqtv`) REFERENCES `quantrivien` (`maqtv`),
  CONSTRAINT `fk_thuonghieu` FOREIGN KEY (`mathuonghieu`) REFERENCES `thuonghieu` (`mathuonghieu`)
) ENGINE=InnoDB AUTO_INCREMENT=107 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table doan.sanpham: ~84 rows (approximately)
INSERT INTO `sanpham` (`MaSP`, `TenSP`, `GiaBan`, `SoLuongTon`, `Hinh`, `MoTa`, `madanhmuc`, `mathuonghieu`, `maqtv`, `MaKhuyenMai`, `SoLuongDaBan`, `NoiBat`, `Rating`, `SoLuotDanhGia`) VALUES
	(1, 'Bìa đựng hồ sơ 20 lá', 3000.00, 180, 'fo-db01_ko_logo_ee4054919b824400882e63299e0ece9a.jpg', 'Một bìa có 20 bìa lá, tiết kiệm được không gian lưu trữ nhưng vẫn đảm bảo tài liệu được phân loại rõ ràng.\r\n\r\nCác file bìa lá có độ dẻo cao, độ rộng vừa đủ thuận tiện cho việc lưu trữ các dữ liệu.', 1, 2, NULL, NULL, 148, 0, 3, 335),
	(2, 'Túi Đựng Tài Liệu 13 Ngăn A4 Deli', 25000.00, 70, 'vn-11134207-81ztc-mm5m9vmz2fwgf9.webp', 'Chất liệu PP nhẹ, độ dày cân bằng, giúp bảo quản tài liệu lâu dài,Bề mặt mịn, chịu nhiệt, không dễ rách hay biến dạng. Chống thấm nước, chống mài mòn, bảo vệ tài liệu hiệu quả dù di chuyển thường xuyên.Nút bấm chắc chắn, đóng mở tiện lợi, tránh rơi rớt giấy tờ.', 1, 2, NULL, NULL, 110, 0, 3, 254),
	(3, 'BÌA ĐỰNG HỒ SƠ (1 XẤP 100 CÁI)', 7000.00, 3000, 'vn-11134207-7ras8-m0h5zea02rxp61@resize_w900_nl.webp', 'Bìa bên ngoài có in chữ Hồ sơ nội dung, thường được dùng để đi xin việc hoặc để giấy tờ', 1, 2, NULL, NULL, 104, 0, 3, 212),
	(4, '[05-08-12 Ngăn] Bìa hồ sơ lớn Mặt Cười - kích thước lớn hơn A4 - Bìa đựng hồ sơ', 15000.00, 3200, 'bd1738fb61e693de2179e89347d25927.webp', 'Mẫu này Hot lắm luôn nhé cả nhà, không biết về hàng bao nhiêu lần đều cháy hàng hết luôn đó.', 1, 5, NULL, NULL, 191, 0, 4, 399),
	(5, 'Hộp đựng hồ sơ A4, 13 ngăn | Bìa cứng chống thấm nước có quai xách, sản phẩm phổ biến cho học sinh', 32000.00, 3100, 'sg-11134201-821g0-mgx5trnlv47j0b.webp', 'Túi đựng tài liệu bền bỉ này được làm từ chất liệu PP dày, trong suốt, giúp bạn sắp xếp gọn gàng và làm việc hiệu quả.Túi đựng tài liệu dạng đứng này có 13 ngăn, giúp bạn dễ dàng sắp xếp giấy tờ, tài liệu và hồ sơ.Túi đựng tài liệu nhẹ và di động này hoàn hảo cho cuộc sống sinh viên.', 1, 1, NULL, NULL, 145, 0, 3, 315),
	(6, 'Dây thẻ đeo bản tên', 25000.00, 80, 'd976171b-254f-4e84-af21-b63be2dd771a.webp', 'Sản phẩm này đã bao gồm cả dây và bảng tên.Sản phẩm này có độ bền vượt chội so với sản phẩm thông thường.Kích thước ruột thẻ bên trong 5,5x8,5cm.kích thước vỏ ngoài 7x11 cm', 2, 4, NULL, NULL, 153, 0, 4, 328),
	(7, 'Dây thẻ đeo bảng tên nhân viên sinh viên Demon Slayer Thiên Long CHD-001/DS', 16000.00, 765, 'vn-11134207-820l4-mil0m4f971fl15.webp', 'Kích thước 470 x 19.6mm (Dây đeo); 110 x 68 x 5.2mm (Thẻ tên)', 2, NULL, NULL, NULL, 329, 0, 5, 694),
	(8, 'Bảng tên vỏ thẻ kim loại, bao đựng thẻ ngang cho nhân viên văn phòng học sinh sinh viên Legaxi', 8000.00, 534, 'vn-11134207-7qukw-lkeqqm09wyvw4b@resize_w900_nl.webp', '🌟 Điểm nổi bật của sản phẩm 🌟 - Hợp kim nhôm cao cấp: Vỏ thẻ Legaxi được làm từ hợp kim nhôm bền bỉ, mỏng và nhẹ, giúp bạn dễ dàng mang theo mọi lúc. - Thiết kế góc tròn: Giảm trầy xước tay và da, mang lại cảm giác thoải mái khi sử dụng. - Nhiều màu sắc lựa chọn: Có các màu như bạc, vàng hồng, xanh dương và đỏ, phù hợp với mọi phong cách.  🎨 Tùy chọn màu sắc và kiểu dáng 🎨 - Thẻ ngang', 2, NULL, NULL, NULL, 186, 0, 4, 387),
	(9, 'SẴN HÀNG) Thẻ đeo bảng tên sinh viên họa tiết dễ thương, dây đeo thẻ học sinh có củ rút tiện lợi kích thước 1.5 x 42cm', 12000.00, 531, 'vn-11134207-7r98o-lpen147d6st7b3.webp', 'Họa tiết dễ thương, đa dạng cho mọi lứa tuổi.Dây đeo nhẹ, bền, tiện lợi mang theo mỗi ngày.Bảng nhựa trong suốt, chống nước, bảo vệ thẻ an toàn', 2, NULL, NULL, NULL, 445, 1, 5, 909),
	(10, '[Giá Sỉ] Dây đeo thẻ bản 1cm các màu và thẻ tên loại nhỏ 107,108', 10000.00, 316, 'sg-11134201-7rbn6-lnpg7hmgs4z599@resize_w900_nl.webp', 'Đối với dây thẻ quý khách lựa theo mẫu trên hình ', 2, NULL, NULL, NULL, 168, 0, 4, 384),
	(11, 'Máy tính Casio FX570', 550000.00, 25, 'casio-fx-570-ms-may-tinh-ca-nhan-2.jpg', 'Máy tính casio FX 570 VN Plus chính hãng được sản xuất theo công nghệ tiên tiến của Nhật Bản với nhiều tính năng được cải tiến có thể đáp ứng tốt nhất nhu cầu học tập và làm việc của mọi đối tượng khách hàng.', 3, 6, NULL, NULL, 4, 0, 3, 44),
	(12, 'Máy tính CASIO Fx 580VN X thế hệ mới ( Màu Hồng ) Độ Chính Xác Cao - Máy Tính Giá Rẻ - Hàng Thái Bảo Hành 24 Tháng', 432000.00, 53, 'vn-11134201-7ras8-m0l92bqq2jn140.webp', 'Máy là hàng thường nhập khẩu Thái Lan nên giá rẻ hơn so với chính hãng. Là máy mới. chức năng cơ bản sử dụng bình thường. với những phép tính phức tạp quá máy có thể sẽ cho ra kết quả chậm. Nếu dùng để lâu dài thi cử mình vẫn khuyên các bạn mua máy cũ chính hãng hoặc máy mới chính hãng giá 685k ở ngoài nhà sách ạ.', 3, 6, NULL, NULL, 18, 0, 3, 71),
	(13, 'Máy tính Casio 570 es plus , Độ Chính Xác Cao , Giá Rẻ', 180000.00, 36, 'vn-11134207-820l4-mhzw7lx98nwo89.webp', 'Phiên bản mới này có tổng cộng 521 tính năng, bao gồm tất cả các tính năng của dòng FX-570ES PLUS và rất nhiều tính năng vượt trội mà những dòng máy tính khác trên thị trường chưa có như + Giải phương trình và bất phương trình bậc 4,. ', 3, 6, NULL, NULL, 80, 0, 3, 179),
	(14, 'Máy Tính Casio MX-120B hiển thị 12 số', 321000.00, 67, 'sg-11134253-7rd6z-m7q78gocfmdffe.webp', ' Vỏ bền chịu va đập: Được thiết kế để chịu được các tác động mạnh, đảm bảo độ bền cao. Màn hình lớn 12 số: Đọc dữ liệu dễ dàng và nhanh chóng.Nguồn hai chiều: Sử dụng cả năng lượng mặt trời và pin, giúp tiết kiệm năng lượng và vận hành liên tục.', 3, 6, NULL, NULL, 343, 0, 5, 727),
	(15, 'Thước Thẳng PVC 20 SR022 dài 20 cm', 7000.00, 120, '3_8.png', 'Thước Thẳng PVC 20 SR022 dài 20 cm, nhỏ gọn và tiện lợi khi mang theo đến trường. Chất liệu nhựa dẻo PVC trong suốt, cao cấp khiến sản phẩm giữ được màu sắc và hình dáng như lúc ban đầu trong suốt quá trình sử dụng. Đây chính là người bạn thân thiết với các em suốt những năm phổ thông.', 4, 1, NULL, NULL, 477, 1, 5, 1003),
	(16, 'Bộ thước kẻ đa năng 360 độ tiện dụng dành cho học sinh, sinh viên và kiến trúc', 21000.00, 3211, 'sg-11134253-81zus-mj095hxqfq4nb9.webp', 'Thước kẻ đa năng xoay 360 độ là lựa chọn lý tưởng cho học sinh, sinh viên và cả những ai yêu thích vẽ kỹ thuật, mỹ thuật. Thiết kế xoay linh hoạt cho phép bạn dễ dàng vẽ các hình học phức tạp như cung tròn, đa giác, hình bầu dục và nhiều hình dạng khác chỉ trong tích tắc. Thước có nhiều lỗ và khe tiện dụng, giúp bạn thao tác nhanh chóng và chính xác hơn.', 4, NULL, NULL, NULL, 358, 0, 5, 737),
	(17, 'Bộ Thước Thẳng Đa Năng Vuông Trong Suốt', 41000.00, 4123, 'sg-11134201-7ra0n-mbj2es1tqiclb1.webp', 'Vật liệu cao cấp và thiết kế trong suốt giúp bạn dễ dàng quan sát và đo lường chính xác. Bộ dụng cụ này bao gồm thước thẳng, thước đo góc, la bàn, tẩy và bút chì, tất cả đều được làm từ nhựa PP cao cấp, bền và ổn định. ', 4, NULL, NULL, NULL, 359, 1, 5, 726),
	(18, 'PVN67594 Bộ 4 thước kẻ êke thước góc sanrio shyn đa năng học tập quà tặng dễ thương', 32000.00, 2333, 'vn-11134211-7ras8-m2rvq8tis8mydb.webp', '📐Kích thước đóng gói: 7 * 18cm', 4, NULL, NULL, NULL, 222, 0, 4, 470),
	(19, 'Ba lô - cặp Thiên Long dành cho học sinh', 180000.00, 40, 'vn-11134207-7ras8-mcte935uer8c51_tn.jpg', 'Được nghiên cứu và phát triển bởi **Tập đoàn Thiên Long** – thương hiệu uy tín hàng đầu Việt Nam trong lĩnh vực giáo dục, **balo chống gù Thiên Long** là lựa chọn hoàn hảo dành cho học sinh tiểu học và trung học cơ sở.', 5, 1, NULL, NULL, 32, 0, 3, 72),
	(20, 'Balo Da Cao Cấp Thời Trang Chống Nước – Phù Hợp Cho Nam Nữ, Đi Học, Đi Làm, Du Lịch. MTBL235', 230000.00, 433, 'vn-11134207-7ra0g-m90cgz2qo70i00.webp', 'Thiết Kế Ngăn Khoa Học – Tiện Dụng Mỗi Ngày\r\n\r\nMặt trước: 1 ngăn kéo lớn – dễ truy cập vật dụng nhanh. Tiện Nghi Khi Sử Dụng – Chống Sốc & Thoáng Khí\r\n\r\nQuai đeo đệm mút lưới tổ ong – êm vai, thoáng khí, giảm áp lực.', 5, NULL, NULL, NULL, 497, 1, 5, 1008),
	(21, 'Balo Da Công Sở Nam Nữ Thời Trang Sang Trọng Tinh Tế. Balo MINTAS_', 322000.00, 541, 'vn-11134207-820l4-mek3aq1vf4lc5a.webp', 'Thiết kế nhiều ngăn: 1 ngăn chính lớn + 1 ngăn chuyên dụng đựng laptop + 1 ngăn phụ lớn + hệ thống nhiều ngăn phụ nhỏ rộng rãi chứa được rất nhiều đồ mà lại rất gọn gàng', 5, NULL, NULL, NULL, 387, 0, 5, 819),
	(22, 'Balo đi học đi làm MOONLAB vải chống nước đựng vừa laptop 15.6inch MS:ML69', 441000.00, 522, 'vn-11134207-81ztc-mkn9gsqm2gw1f2.webp', 'BALO ĐI HỌC ĐI LÀM MOONLAB VẢI CHỐNG NƯỚC ĐỰNG VỪA LAPTOP 15.6INCH', 5, NULL, NULL, NULL, 445, 0, 5, 924),
	(23, 'BALO DA PU CAO CẤP ( chống bụi, chống nước ) Đựng laptop 15"6 inch thời trang', 552000.00, 233, 'vn-11134207-7ra0g-m6kyygidgm07c4.webp', 'Balo Da Thời Trang Hàn Quốc ', 5, NULL, NULL, NULL, 63, 0, 3, 159),
	(24, 'Giày vải bata sneaker Thượng Đình KK14-1', 120000.00, 90, '44577210-8934-4623-be7a-e46deb05dec8.webp', ' Giày Vải Bata Sneaker Thượng Đình KK14-1 Ba ta Đá Banh Đế Bằng Học Sinh Đi Làm Chạy Bộ Tập Thể Dục Thể Thao Giá Rẻ Bền. Giày thể thao (vải) đế crepe - sản phẩm chính hãng của Giày Thượng Đình, Việt Nam. ', 6, 5, NULL, NULL, 482, 1, 5, 981),
	(25, '(TĂNG 1 SIZE) Giày Thể Thao Sneaker KATECO Nam Nữ, Mã KT1 Full Màu Full Box', 99000.00, 322, 'vn-11134207-820l4-mh4b8a6xds09c5.webp', 'Nếu sản phẩm muốn đổi có giá trị ngang hoặc cao hơn, bạn sẽ cần bù khoảng chênh lệch (nếu có)', 6, NULL, NULL, NULL, 220, 0, 4, 473),
	(26, 'Giày nam thể thao bata họa tiết máy bay phong cách thời trang cực đẹp', 123000.00, 666, '78d4321d2fc53bf0aebd9b8bfe514d59.webp', 'Giày nam thể thao máy bay', 6, NULL, NULL, NULL, 156, 0, 4, 330),
	(27, '[DEAL MỞ BÁN] Giày nữ thể thao đễ thương đế nhẹ QCCC full box CAT', 442000.00, 412, 'vn-11134207-7ra0g-m7zll6g4c41ad5.webp', 'Hàng bên shop là hàng QC full box, tuy nhiên do quá trình vận chuyển có thể hộp sẽ bị móp méo, điều này là không tránh khỏi được nên mong quý khách hiểu và thông cảm.', 6, NULL, NULL, NULL, 119, 0, 3, 276),
	(28, 'Giày nam sneakers thể thao phong cách Hàn Quốc, Giày nam 2026 mới về', 36000.00, 444, 'vn-11134201-7r98o-lrzxvosk7d3m1d.webp', 'LAGADO luôn dẫn đầu trong việc đáp ứng nhanh chóng những trào lưu xu hướng thời trang mới nhất châu Á. mang đến làn gió mới với xu hướng thời trang năng động, hiện đại và cá tính. Chúng tôi liên tục đổi mới chính mình và cam kết luôn cập nhật những mẫu mới nhất để đáp ứng nhu cầu và làm hài lòng tất cả khách hàng thân thiết của LAGADO', 6, NULL, NULL, NULL, 130, 0, 3, 298),
	(29, 'Sổ viết tay 36 trang', 15000.00, 100, 'vn-11134207-7ras8-m4imlj5qz4sncc.webp', 'Sổ ghi chép SheGAN có kích thước 155 x 250 x 10 mm và số trang 36 trang, với độ trắng giấy lên đến 80%, đảm bảo chất lượng in ấn rõ ràng, dễ đọc. Đây là lựa chọn lý tưởng cho việc ghi chép công việc, học tập, hoặc dùng để ghi lại những ý tưởng quan trọng.', 7, 1, NULL, NULL, 293, 0, 4, 613),
	(30, 'Sổ Tay basic khổ A6 A5 B5 A4 Lò Xo 160 trang, kẻ caro, kẻ ngang, trơn BÌA NHỰA CHỐNG THẤM NƯỚC', 17000.00, 333, 'sg-11134253-82620-mk5x5g4zpkp47a.webp', 'Điểm nổi bật không thể bỏ qua! ✨', 7, NULL, NULL, NULL, 75, 0, 3, 170),
	(31, '{6k} SỔ TAY LÒ XO A7 160 Trang Kẻ Ngang Capybara Hot Trend 2026 / Tập Vở Mini Note Peach Home 1k Rẻ', 11000.00, 2111, 'vn-11134207-7ra0g-m8exx04pvkp329.webp', 'Xin chào!!!', 7, NULL, NULL, NULL, 496, 1, 5, 1014),
	(32, 'Sổ còng thay được giấy pastel A5 B5 -120 trang kẻ ngang, caro Moca làm vở tập học sinh, planner, sổ ghi chép', 21000.00, 2231, 'vn-11134207-7ras8-m1tbp5xvxfmbca.webp', 'sổ còng vở, sổ lò xo A5 B5, vở lò so 120 trang, sổ tay ghi to giấy kẻ ngang, giấy caro, giấy trơn tiện lợi bìa nhám chống nước', 7, NULL, NULL, NULL, 257, 0, 4, 514),
	(33, 'Sổ Lò Xo Basic A7 A6 A5 B5 A4 160 Trang Kẻ Caro/Ngang/Trơn Bìa Trong Suốt Vở Tập Peach Home 1K Rẻ', 31000.00, 3222, 'vn-11134207-7r98o-lz8x50re2bm548@resize_w900_nl.webp', 'THÔNG TIN SẢN PHẨM: Sổ Lò Xo Kép 160 Trang A6 A5 B5 A4 Bìa Trong Suốt Kẻ Caro Kẻ Ngang ', 7, NULL, NULL, NULL, 297, 0, 4, 630),
	(34, 'Bút bi Thiên Long TL027', 5000.00, 200, 'artboard_6_993a955c7bc34ed983cf23d627053848.jpg', 'Bút bi Thiên Long TL-027 là một trong những chiếc bút bi được học sinh, sinh viên và dân văn phòng sử dụng rất nhiều tại Việt Nam, bút bi Thiên Long TL-027 có thiết kế tối giản nhưng tinh tế và ấn tượng. Toàn bộ thân bút làm từ nhựa trong, phối hợp hoàn hảo với màu ruột bút.', 8, 1, NULL, NULL, 215, 0, 4, 460),
	(35, 'Combo 10 Bút gel bút bi nước, bút MỰC BG05 ngòi 0.5mm ống lớn viết chữ đẹp', 20000.00, 4432, 'sg-11134253-81zu8-mj0areu5ux3bdb.webp', 'Viết Chữ Đẹp, Mực Đậm – Lựa Chọn Hoàn Hảo Cho Học Sinh! ✨', 8, 1, NULL, NULL, 183, 0, 4, 406),
	(36, 'Hộp 12 Bút Bi Nước AIHAO Chữ A Đẹp', 18000.00, 5432, 'vn-11134207-7ras8-m1yx60ak966zcc@resize_w900_nl.webp', 'Bút bi nước Aihao nổi bật với đầu bi 0.5mm cho nét chữ thanh mảnh, đều màu và cực kỳ mượt mà. Mực ra đều, không nghẽn, không phai màu dù sử dụng lâu dài, giúp bạn tự tin ghi chú, làm bài hay ký tên.', 8, 1, NULL, NULL, 273, 0, 4, 559),
	(37, 'Bút bi khổng lồ mực đen nét to, dễ viết', 31000.00, 6531, 'sg-11134253-8261j-mj8stc0f3ls438.webp', 'Điểm nổi bật không thể bỏ qua! ✨ - Bút bi khổng lồ mực đen nét to, dễ viết, mang lại trải nghiệm viết mượt mà và rõ ràng cho mọi lứa tuổi. - Thiết kế nổi bật, màu sắc đa dạng giúp bạn dễ dàng lựa chọn phong cách cá nhân. - Bút có nhiều kích thước và màu sắc, phù hợp cho học sinh, sinh viên, dân văn phòng hoặc sử dụng trong các cuộc thi, phác thảo, ký tên.  Các lựa chọn sản phẩm: - Bút bi khổng lồ vàng 13cm - Bút bi khổng lồ đỏ 13cm - Bút bi khổng lồ xanh lá 13cm - Bút bi khổng lồ xanh lam 13cm - Bút bi khổng lồ tím 13cm - Bút bi khổng lồ đen 13cm - Bút bi khổng lồ đỏ 28cm - Bút bi khổng lồ xanh lam 28cm - Bút bi khổng lồ hồng 28cm - Bút bi khổng lồ tím 28cm - Bút bi khổng lồ xanh lá 28cm - Bút bi khổng lồ vàng 28cm', 8, 1, NULL, NULL, 317, 1, 5, 680),
	(38, 'Bút gel ống lớn thiết kế vỏ trong suốt 0.5mm 3 màu Đen Đỏ Xanh Mực Đều Ngòi Nét', 23500.00, 3415, 'sg-11134275-8260g-mjdsaadhf8xz86.webp', 'Thiết kế đẹp mắt, độc đáo, vỏ bút trong suốt, nhìn thấy được lượng mực còn lại', 8, 1, NULL, NULL, 264, 1, 4, 569),
	(39, 'Bút kí cao cấp khắc tên', 25000.00, 150, 'but-ky-cao-cap-in-khac-logo-theo-yeu-cau-1.jpg', 'Điểm nổi bật của sản phẩm: - Khắc tên theo yêu cầu: Tạo nên sự cá nhân hóa và ý nghĩa cho món quà của bạn. - Vỏ kim loại cao cấp: Mang đến vẻ ngoài sang trọng và bền đẹp theo thời gian. - Ngòi mực khô: Viết mượt, không lem và tiết kiệm mực.  Chi tiết sản phẩm: Bút ký kim loại cao cấp, thiết kế tinh tế, phù hợp làm quà tặng sinh nhật, tốt nghiệp, hoặc tri ân thầy cô. Với ngòi mực khô, bạn có thể viết mượt mà và tiết kiệm mực.  ', 9, 1, NULL, NULL, 369, 0, 5, 756),
	(40, 'Bút ký cao cấp [khắc tên theo yêu cầu] SGIFT68 Xoay mở ngòi Quà sinh nhật, thầy cô, tốt nghiệp', 92500.00, 634, 'vn-11134207-7r98o-lyuusifjty6l4f.webp', 'Bút ký là một trong những món quà ý nghĩa không thể thiếu đối với mọi cá nhân trong đời sống. Đặc biệt ý nghĩa hơn là mang phong cách cá nhân và thông điệp yêu thương của từng người. Tôn vinh tính độc lập cá nhân, quảng bá thương hiệu và hơn hết là một món qua nhắn gửi yêu thương mang thông điệp cá nhân của người tặng đến với người mình yêu thương nhất.', 9, NULL, NULL, NULL, 53, 0, 3, 125),
	(41, 'Bút ký Lincoln Jr 018, bút thiết kế dành cho quân đội, doanh nhân, mực bi 0.5 mm', 344300.00, 321, 'vn-11134211-7qukw-lj5qb44nw9vwb6.webp', '- MIỄN PHÍ KHẮC TÊN + TẶNG KÈM RUỘT BÚT CAO CẤP', 9, NULL, NULL, NULL, 160, 0, 4, 359),
	(42, 'Bút lông bảng Thiên Long', 15000.00, 200, 'artboard_14_copy_33cfeba9fba3444caedac3933aead5ac_large.jpg', ' Mực Đậm, Dễ Xóa: Bút lông bảng Thiên Long WB-03 nổi bật với màu mực đậm và tươi sáng, dễ dàng xóa sạch ngay cả khi viết lâu trên bảng.', 10, 1, NULL, NULL, 141, 0, 3, 322),
	(43, 'Combo 10 Bút Lông Dầu 2 Đầu , Mực Không Trôi, Viết Trên Nhiều Bề Mặt Bút Dạ Viết Bảng', 23400.00, 888, 'vn-11134207-820l4-mi2hcso510xv9d@resize_w900_nl.webp', 'Tiện lợi: Với 2 đầu bút, bạn có thể sử dụng linh hoạt trên nhiều bề mặt khác nhau. 📏', 10, NULL, NULL, NULL, 224, 0, 4, 481),
	(44, '10 bút viết bảng Thiên Long WB 03', 43500.00, 954, 'cc7cc66457dddf514d8505a641dd41ef.webp', 'Bút được sản xuất theo công nghệ hiện đại , đạt tiêu chuẩn an toàn quốc tế ', 10, 1, NULL, NULL, 200, 0, 4, 447),
	(45, 'Bút lông dầu TL Marker chống nước - viết thùng carton, nhựa,kim loại - combo 10, 20', 54200.00, 3214, 'vn-11134207-820l4-miiiaun4acxs2d@resize_w900_nl.webp', ' Bút Lông Dầu TL Marker Chống Nước – Viết Thùng Carton, Nhựa, Kim Loại | Combo 10 Cây', 10, NULL, NULL, NULL, 326, 0, 5, 687),
	(46, 'Giấy ghi nhớ-Giấy note -nhiều kích thước', 10000.00, 300, 'giay-note-5-mau-giay-pronoti-5894.jpg', 'Xấp 100 Tờ Giấy Note / Ghi Chú Màu Vàng / Giấy Ghi Nhớ Màu Vàng / Phân Trang 3x2 3x3 3x4 3x5 Xing Li / XingLi / King', 11, 1, NULL, NULL, 33, 0, 3, 99),
	(47, 'Giấy note hình trái cây 50 tờ tiện lợi', 7750.00, 523, 'vn-11134207-7r98o-lx6qzzm5b07t9c.webp', 'Giấy note hình trái cây 50 tờ với thiết kế dễ thương, màu sắc tươi sáng, giúp bạn ghi chú nhanh chóng và hiệu quả!.Chất liệu giấy dày, không thấm nước, bám dính chắc chắn, phù hợp cho mọi nhu cầu ghi chú hàng ngày.', 11, NULL, NULL, NULL, 185, 0, 4, 381),
	(48, 'PVN62850 Set 2 gói 50 tờ giấy ghi nhớ, giấy note hình vuông màu Gradient phong cách thanh lịch cho văn phòng / học tập', 10599.00, 986, 'sg-11134201-7rbmt-lqd61susxyne45@resize_w900_nl.webp', 'Thiết kế nổi bật  ', 11, NULL, NULL, NULL, 326, 0, 5, 659),
	(49, 'Giấy Note 400 Tờ HXGS: Siêu Nhiều - Đa Năng - Tiện Lợi', 25998.00, 1423, 'vn-11134275-7ra0g-m8zynjdegtacf0.webp', 'Chào mừng bạn đến với tiệm nhà HAPPI! Những sản phẩm nhà HAPPI được lựa chọn kỹ lưỡng và được bán ra với giá thành tốt nhất!', 11, NULL, NULL, NULL, 77, 0, 3, 156),
	(50, 'Giấy Ghi Chú Loopy Họa Tiết Hoạt Hình Đáng Yêu HAPPI', 15999.00, 7654, 'vn-11134207-7ras8-m4by427iqsjr72.webp', 'Chào mừng bạn đến với tiệm nhà HAPPI! Những sản phẩm nhà HAPPI được lựa chọn kỹ lưỡng và được bán ra với giá thành tốt nhất!', 11, NULL, NULL, NULL, 409, 0, 5, 858),
	(51, 'Hộp dấu chất lượng cao', 7000.00, 200, 'dichvukhaccondau2.png', 'Chuyên khắc dấu cho các shop đóng lên bao bì, hộp giấy, nilon, bill gửi hàng...nhanh chóng , tiện lợi thể hiện sự chuyên nghiệp của shop mình, độc lạ không đụng hàng. Giúp bạn tiết kiệm chi phí in ấn', 12, 1, NULL, NULL, 312, 0, 5, 667),
	(52, 'Hộp đựng con dấu HD07 đựng 2-3 con( mầu đen)', 20999.00, 324, '2d1f5c348a5995da056d7c9dd7ea844f.webp', 'Hộp đựng con dấu VPEC( HD07) chất liệu nhựa pha vải Kaki', 12, NULL, NULL, NULL, 335, 0, 5, 718),
	(53, 'Hộp Dấu Lăn Tay - Tampon Lăn Dấu Vân Tay Shiny SM-1 màu đen - xanh - HOKAMI STORE', 42115.00, 663, '0f26114b67e5469022639c64bd187ed3.webp', 'Dùng lăn tay điểm chỉ trên hộp đồng, chứng từ quan trọng yêu cầu lăn tay điểm chỉ', 12, NULL, NULL, NULL, 241, 0, 4, 494),
	(54, 'Hộp đựng con dấu HD06 đựng 4-6 con', 66750.00, 666, '6c5b747c57199dafade87d3334074347.webp', 'Chất liệu nhựa pha Kaki bền đẹp', 12, NULL, NULL, NULL, 199, 0, 4, 413),
	(55, 'Hình dán sticker siêu đẹp', 15000.00, 150, 'vn-11134207-7r98o-ls9cnskhe1ll7f.webp', 'Bộ Sticker Trang Trí Aesthetic 100 Miếng – Cute, Dễ Thương, Vintage, Pastel – Dán Mũ Bảo Hiểm, Laptop, Sổ Tay, Điện Thoại, Bình Nước, Ván Trượt, Đàn Guitar', 13, 1, NULL, NULL, 271, 0, 4, 580),
	(56, 'Hộp 100 Tấm Sticker Dán Trang Trí Tập Vở Bình Nước Khay Đựng Bút Hình Dán Sổ Tay Nhật Ký Điện Thoại Y001', 26890.00, 642, 'sg-11134201-23030-9caw4acqy7nv2a.webp', 'Hộp 100 Tấm Sticker Dán Trang Trí Tập Vở Bình Nước Khay Đựng Bút Hình Dán Sổ Tay Nhật Ký Điện Thoại Y001', 13, NULL, NULL, NULL, 260, 0, 4, 566),
	(57, 'Set 50 cái Sticker Flork Meme chống nước, Hình Dán Flork giá rẻ, decal dán mũ bảo hiểm, laptop, dán xe, vali', 41699.00, 776, 'vn-11134201-23020-zm3wxf5jbwnvfb@resize_w900_nl.webp', 'Set 50 cái Sticker Flork Meme chống nước, Hình Dán Flork giá rẻ, decal dán mũ bảo hiểm, laptop, dán xe, vali, sticker chống thấm nước, sticker cute giá rẻ', 13, NULL, NULL, NULL, 487, 1, 5, 991),
	(58, 'Set 50 cái sticker chống nước GIẤY CHÁY CỔ ĐIỂN TIẾNG ANH HOÀI NIỆM RETRO VINTAGE dán mũ bảo hiểm vali laptop', 31999.00, 551, 'sg-11134201-7rat7-ma4zigv1batqc5.webp', 'Tên sản phẩm: Set 50 cái sticker chống nước GIẤY CHÁY CỔ ĐIỂN TIẾNG ANH HOÀI NIỆM RETRO VINTAGE dán mũ bảo hiểm vali laptop', 13, NULL, NULL, NULL, 156, 0, 4, 359),
	(59, 'Dao rọc giấy cao cấp Flex Home', 30000.00, 246, 'vn-11134207-7ras8-m4iay63xoki7a4_7de0d844462d469daa7bdb1296100763.jpg', 'Dao rọc giấy cao cấp Flex Home được thiết kế hiện đại, lưỡi dao sắc bén, an toàn, chất liệu bền bỉ và tay cầm chắc chắn giúp thao tác dễ dàng.', 14, 1, NULL, NULL, 319, 0, 5, 674),
	(60, 'Dao Rọc Giấy Hàng Cao Cấp - Cán Dao Dày Cầm Chắc Tay - Lưỡi Thép', 9800.00, 631, 'vn-11134207-820l4-mfga818rgmbu1f.webp', 'Dao rọc giấy sở hữu lưỡi thép bền bỉ, không gỉ, đảm bảo độ sắc bén lâu dài. Cán dao dày chắc tay, giúp thao tác cắt giấy, bìa cứng hay bao bì trở nên dễ dàng và an toàn hơn bao giờ hết. Sản phẩm có nhiều màu sắc trẻ trung, phù hợp với mọi không gian làm việc hoặc học tập.', 14, NULL, NULL, NULL, 128, 0, 3, 295),
	(61, 'Dao rọc giấy KX cán thép, lưỡi sắc bén, nhiều màu', 11299.00, 541, 'sg-11134253-8225c-mhuurgn39csmae.webp', 'Sắc bén và tiện lợi: Dao rọc giấy KX với lưỡi thép SK5 sắc bén, giúp bạn dễ dàng cắt giấy mỏng hay dày chỉ với một lần cắt. Cán dao bằng thép không gỉ, chắc chắn và bền bỉ, phù hợp cho công việc văn phòng và các dự án DIY. Đa dạng màu sắc: Có nhiều màu sắc để lựa chọn, phù hợp với mọi phong cách và sở thích của bạn. Tính năng khóa an toàn: Dao được trang bị tính năng khóa an toàn, giúp bạn sử dụng an toàn và tiện lợi hơn.  Tùy chọn sản phẩm: - Dao KX 9 Chức Năng - Dao KX Cán FULL INOX - Dao KX 10 Chức Năng - Cán Thép, Dao KX Vàng - Cán Thép, Dao KX Đen - Lưỡi Dao KX, X10 - CánThép, DaoKX Random  Dao rọc giấy KX là sự lựa chọn hoàn hảo cho những ai cần một công cụ cắt giấy hiệu quả và an toàn!', 14, NULL, NULL, NULL, 185, 0, 4, 408),
	(62, 'PVN47526 Dao Dọc Giấy, Cắt Bánh Ngọt Mini Cán Gỗ Trang Trí Phong Cách Hàn Quốc', 31688.00, 1234, 'vn-11134207-7ras8-m18aier3viwq3d.webp', 'Thiết kế nhỏ gọn, đáng yêu của Dao Rọc Giấy Mini Cán Gỗ Sồi sẽ làm bừng sáng không gian học tập hay văn phòng của bạn. Sản phẩm này không chỉ là một dụng cụ tiện ích mà còn là món decor cực xinh, mang lại cảm hứng mỗi ngày.', 14, NULL, NULL, NULL, 41, 0, 3, 105),
	(63, 'Bấm kim số FlexOffice', 27000.00, 148, 'new16_587d03ad4cc047f1b7b120dca65dcdeb.jpg', 'Bấm kim số FlexOffice (thường là FO-ST02, FO-ST03) là dụng cụ văn phòng phổ biến, làm từ thép không gỉ bọc nhựa ABS cao cấp, bền bỉ và êm tay. Sản phẩm chuyên dụng cho kim số 10, khả năng bấm tối đa 10-15 tờ giấy A4, thiết kế nhỏ gọn, lò xo đàn hồi tốt, hạn chế kẹt kim, lý tưởng cho việc lưu trữ tài liệu', 15, 1, NULL, NULL, 150, 0, 3, 301),
	(64, '[TẶNG KÈM GHIM] Dập ghim học sinh văn phòng mini Deli 25 trang Ghim bấm giấy số 12 màu pastel dễ thương nhỏ gọn tiện lợi', 13500.00, 856, 'sg-11134201-7rcf3-lsb60nym8d7qc5.webp', 'Tự hào là một trong những nhãn hàng văn phòng phẩm lớn nhất tại Việt Nam, @Delivietnam không ngừng nỗ lực và phát triển trong lĩnh vực phân phối các sản phẩm cho các đơn vị văn phòng, trường học,...  Với phương châm sản xuất những sản phẩm chất lượng tốt nhất và luôn đặt khách hàng làm trọng tâm, sứ mệnh của Deli là làm hài hòng người tiêu dùng toàn cầu với những sản phẩm chất lượng cao, độ tin cậy tuyệt đối và thân thiện với người dùng.', 15, NULL, NULL, NULL, 127, 0, 3, 289),
	(65, 'Bộ dụng cụ dập ghim đa năng hình ngộ nghĩnh - Máy đóng sách nhỏ gọn dễ mang theo - Dụng cụ học tập cho học sinh', 31255.00, 662, 'vn-11134207-820l4-mgzd7ciyg9vx06.webp', '￼Bộ dụng cụ dập ghim đa năng hình ngộ nghĩnh - Máy đóng sách nhỏ gọn dễ mang theo - Dụng cụ học tập cho học sinh', 15, NULL, NULL, NULL, 184, 0, 4, 393),
	(66, 'Dập Ghim Deli Xoay Chiều 40 Trang – Dùng Ghim 24/6 & 26/6, Tiện Lợi Học Sinh & Văn Phòng', 12399.00, 836, 'sg-11134253-8225h-mho6zt32r1u025.webp', '🌟 Đặc điểm nổi bật của bộ dập ghim Deli TA304 🌟 - Vỏ TPE chống trượt: Đảm bảo đứng vững trên bàn, không bị trượt hay lăn. - Góc mở rộng dễ nhìn: Giúp bạn dễ dàng xác định vị trí cần dập ghim. - Đế kim loại bền vững: Cấu trúc ổn định, sử dụng lâu dài.  🎨 Tùy chọn màu sắc 🎨 - TA304-Xanh dương - TA304-Trắng - TA304-Xanh lá cây  📝 Thông tin sản phẩm 📝 Bộ dập ghim Deli TA304 xoay chiều 40 trang là công cụ lý tưởng cho học sinh và văn phòng. Với khả năng bấm tối đa 30 tờ giấy và hỗ trợ ghim 24/6 hoặc 26/6, bạn sẽ cảm thấy dễ dàng và hiệu quả trong việc sử dụng.  ✨ Chọn bộ dập ghim Deli TA304 để trải nghiệm sự tiện lợi và bền bỉ trong công việc hàng ngày! ✨', 15, NULL, NULL, NULL, 40, 0, 3, 98),
	(67, 'Băng keo đục OOP FlexOffice', 32000.00, 322, 'upload_ce8331cb850b45adb90aae6dd07c13c5.jpg', 'Băng keo đục OPP Flexoffice FO-BKD 10.Kích thước   bề rộng 48mm, dài 100 Yard(~91m).Sản phẩm được làm từ màng BOPP có độ bền dai cao cộng keo tráng được lựa chọn để làm băng keo luôn đảm bảo độ dính cao, khả năng đàn hồi tốt.Có thể dính rất chắc trên nhiều chất liệu khác nhau.', 16, 1, NULL, NULL, 149, 0, 3, 316),
	(68, 'Băng Keo Niêm Phong Phong Thủy - Giấy Dán Hình Phù Loại Nhỏ', 22700.00, 1425, 'vn-11134207-820l4-mgf1utvchami25.webp', 'Điểm nổi bật không thể bỏ qua! 🎉', 16, NULL, NULL, NULL, 126, 0, 3, 287),
	(69, 'Băng keo HÀNG DỄ VỠ 100YARD- 1.2kg ( cây 6 cuộn )', 94500.00, 7451, 'vn-11134207-81ztc-mld1jfoz45j442.webp', ' SỔ TAY LÒ XO GHI CHÉP A7 DỌC NHỎ MINI KẺ NGANG 80 TRANG BÌA CỨNG IN HÌNH ĐẸP CUTE DỄ THƯƠNG BỎ TÚI GHI CHÚ HỌC TẬP CHO HỌC SINH, SINH VIÊN, VĂN PHÒNG... KÍCH THƯỚC 8*10.5 CM', 16, NULL, NULL, NULL, 183, 0, 4, 387),
	(70, 'Băng Keo Nano Hai Mặt 3M Trong Suốt, Băng Dính 2 Mặt Siêu Dính Dán Mọi Vật Dụng Trong Nhà Đa Năng', 21000.00, 9567, 'sg-11134253-824ik-mf00xjibe8zw64.webp', 'Chống Nước và Chịu Nhiệt: Băng keo nano hai mặt trong suốt siêu dính, chịu nước và chịu nhiệt từ 20 ~ 100°C, là giải pháp đa năng cho mọi nhu cầu dán dính trong nhà.Tái Sử Dụng và Không Để Lại Dấu Vết: Sản phẩm có thể tái sử dụng nhiều lần mà không để lại dấu vết trên bề mặt, giúp bạn tiết kiệm chi phí và bảo vệ môi trường.\r\n\r\n\r\n\r\n', 16, NULL, NULL, NULL, 39, 0, 3, 78),
	(71, 'Bút chì 2B Deli', 4000.00, 150, 'c0371_911cf346d8a041e480233aa490a740c0.jpg', 'Bút Chì Deli 37000 có màu chì đậm, rõ nét, mịn đẹp sẽ giúp cho những nét tô vẽ của bạn tinh tế và có độ thẩm mỹ cao. Sử dụng dễ cất giữ trong hộp khi đi học, rất tiện dụng. Ngòi viết êm, không quá cứng hay quá mềm, không bị gãy khi bào viết. Dễ gọt, dễ tô, dễ xóa, giúp thao tác nhanh hơn trong lúc làm bài thi.', 17, 2, NULL, NULL, 145, 0, 3, 330),
	(72, 'Hộp 10 cây bút chì TL bút chì kèm tẩy ít gãy nét đậm dùng luyện viết chữ cho học sinh, văn phòng', 22000.00, 8245, 'sg-11134253-824he-meizd2otxrsya4.webp', ' Thiết kế thân gỗ lục giác: Bút chì TL với thân gỗ lục giác dễ cầm nắm, mang lại cảm giác thoải mái khi sử dụng.', 17, NULL, NULL, NULL, 107, 1, 3, 214),
	(73, 'Bút chì HB tẩy được, không cần gọt, bút chì vĩnh cửu, không dễ gãy, giúp học sinh giữ tư thế tốt.', 22000.00, 94567, 'sg-11134201-8261x-mlor1buin954e8.webp', 'Bút chì vĩnh cửu không cần gọt bút chì HB có thể xóa được dành cho học sinh', 17, NULL, NULL, NULL, 102, 0, 3, 233),
	(74, 'COMBO Bút Chì HB Lục Giác Gỗ Màu Đỏ Đen Kèm Tẩy, Chất Lượng Cao, Dùng Viết Vẽ Cho Học Sinh ( BCTGDD)', 66129.00, 5124, 'vn-11134207-7r98o-lvispufu4sis8d.webp', 'Để đảm bảo quyền lợi cho người tiêu dùng, shop khuyến khích quý khách hàng phải quay video và kiểm tra kỹ tình trạng sản phẩm trong quá trình mở hộp. Ngoài ra quý khách cần kiểm tra sản phẩm có bể, trầy xước do tác động bên ngoài, màu sắc, số lượng, có đúng chủng loại, thông số kỹ thuật hay không. Nếu sản phẩm không đúng như thông tin mua hàng, ảnh hưởng bởi quá trình vận chuyển, quý khách vui lòng thông báo ngay cho shop để shop hỗ trợ hoàn hàng và trả.', 17, NULL, NULL, NULL, 190, 0, 4, 425),
	(75, '[Siêu Rẻ] Set 5, 10 Bút Chì Gỗ Đầu Tẩy Hình Thú Trái Cây Ngộ Nghĩnh HAPPI', 22000.00, 7345, 'vn-11134207-7ras8-m3vtd8i4lf3cc8.webp', 'Chào mừng bạn đến với tiệm nhà HAPPI! Những sản phẩm nhà HAPPI được lựa chọn kỹ lưỡng và được bán ra với giá thành tốt nhất!', 17, NULL, NULL, NULL, 145, 0, 3, 327),
	(76, 'Vở Hồng Hà 200 trang', 12000.00, 300, 'vo_10021_38734cfceb7242cf82ef2fb2b96fc0ea.jpg', 'Vở KN Hồng Hà 200 trang 1002 Pupil Bốn mùa giúp ghi chép tài liệu dễ dàng. Bề mặt giấy trơn mịn, gáy bìa dập ghim được đóng chắc chắn. Sản phẩm phù hợp cho Học sinh, Sinh viên, Văn phòng.', 18, 3, NULL, NULL, 153, 0, 4, 309),
	(77, 'Combo 5 cuôn Sổ tay kiếm hiệp, sổ bí kíp võ công cổ trang kungfu tập cầm tay ghi chép ghi chú', 32000.00, 8923, 'vn-11134207-7r98o-lxneaust69jtc8.webp', 'Sổ tay kiếm hiệp, sổ bí kíp võ công cổ trang kungfu tập cầm tay ghi chép ghi chú cao nhân phong cách cổ trang cổ điển', 18, NULL, NULL, NULL, 334, 0, 5, 674),
	(78, 'Tập Học Sinh/ Sinh Viên A5/B5 Bìa Kraft Nâu/ Đen - Sổ TaY Gáy Khâu Giấy Kẻ Ngang/ Caro / Không Dòng Kẻ 80Gsm Chống Lóa', 17999.00, 8451, 'vn-11134207-7ras8-m20t5yne2y6zd8.webp', 'Sổ khâu gáy bìa kraf đơn giản được nhiều học sinh, sinh viên yêu thích vì sự hiện đại tinh tế. Mỗi người chủ sở hữu có thể dễ dàng thể hiện cá tính của mình trên từng cuốn vở. Với thiết kế gáy khâu chắc chắn và giấy dày hơn, hãy cũng True Color học tập bạn nhé!', 18, NULL, NULL, NULL, 208, 0, 4, 435),
	(79, 'Combo 4 quyển vở A5 khâu gáy 60 trang nhiều hình dễ thương HAPPI', 60000.00, 5126, 'vn-11134201-23030-xqdp8j3yucov82.webp', 'Chào mừng Bạn đến với Tiệm nhà HAPPI!!!!!! Tất cả sản phẩm nhà HAPPI đều được lựa chọn kỹ lưỡng, được bán với giá thành tốt nhất và chất lượng được đảm bảo nhất!', 18, NULL, NULL, NULL, 40, 0, 3, 106),
	(80, 'Combo 5 Quyển Tập 96 Trang Siêu VIP Thành Đạt - Định Lượng 120gsm , Viết Không Lem - VPP Happy Kids', 42999.00, 8457, 'sg-11134201-7rffd-m9feel7w6hm19a.webp', 'Đảm bảo không lem.', 18, NULL, NULL, NULL, 78, 0, 3, 183),
	(81, 'Giấy Ford', 36000.00, 360, 'vn-11134207-820l4-mf8xpn55w9ag98.jpg', 'Đây là loại giấy không tráng phủ, bề mặt nhám, bám mực tốt và không gây chói mắt', 19, 1, NULL, NULL, 271, 0, 4, 548),
	(82, '[50 tờ Giấy A4] Cho học sinh viết, vẽ thoải mái sáng tạo cả ngày -Giấy mềm 70gsm', 22000.00, 412, 'vn-11134207-7r98o-lzhv1ltcfwb12d.webp', 'Thông tin sản phẩm: Giấy TH Paper định lượng 70gsm A4 là loại giấy trắng đẹp, sử dụng để viết, vẽ, làm nháp phù hợp cho học sinh các cấp', 19, NULL, NULL, NULL, 118, 0, 3, 285),
	(83, 'Giấy Gòn tập viết Thư Pháp set giá rẻ nhiều kích cỡ Thư Pháp Dụng Phẩm', 31999.00, 1412, 'sg-11134201-23030-nuzts2cr9nov3e@resize_w900_nl .webp', 'Trong các loại giấy tập viết thư pháp Việt thì giấy gòn được thư hữu yêu thích, bởi giấy gòn có giá thành rẻ, khi viết thư pháp tạo được nét xước đẹp, có độ thấm mực vừa phải và không bị loang. Shop sẽ cập nhật video viết trên giấy gòn sớm nhất có thể để Quý thư hữu có thể thấy thực tế hiệu quả của giấy.', 19, 5, NULL, NULL, 280, 1, 4, 588),
	(84, '100 Tờ Giấy Refill 100GSM Classmate Luyện Viết Tiếng Trung Nhật Hàn , Ruột Sổ Còng Ô Vuông, Ô Điền, Matrix Line', 51989.00, 6235, 'vn-11134207-81ztc-mlev1jzdntvn15.webp', 'Classmate - Sổ, Vở Chính Hãng chuyên cung cấp các sản phẩm thuộc nhóm sản phẩm Sổ, Vở, và các dòng Bút thuộc thương hiệu CLASSMATE... Đến với Classmate - Sổ, Vở Chính Hãng, các bạn sẽ được xem, mua sắm và trải nghiệm rất nhiều các loại sổ, vở khác nhau; và các dòng bút chuyên biệt để viết, để decor.... vô cùng đa dạng và có thiết kế đẹp mắt. Với công nghệ tiên tiến nhất trong việc in bìa, sản xuất sổ vở, Classmate cam kết sẽ đưa đến cho khách hàng các sản phẩm có độ hoàn thiện cao, chất lượng tốt.', 19, NULL, NULL, NULL, 48, 0, 3, 141);

-- Dumping structure for table doan.thanhtoan
CREATE TABLE IF NOT EXISTS `thanhtoan` (
  `MaThanhToan` int NOT NULL AUTO_INCREMENT,
  `madh` int DEFAULT NULL,
  `PhuongThucTT` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `NgayTT` date DEFAULT NULL,
  `TrangThai` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`MaThanhToan`),
  KEY `madh` (`madh`),
  CONSTRAINT `thanhtoan_ibfk_1` FOREIGN KEY (`madh`) REFERENCES `dathang` (`madh`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table doan.thanhtoan: ~0 rows (approximately)
INSERT INTO `thanhtoan` (`MaThanhToan`, `madh`, `PhuongThucTT`, `NgayTT`, `TrangThai`) VALUES
	(1, 1, 'Tiền mặt', '2026-03-01', 'Đã thanh toán'),
	(2, 2, 'Chuyển khoản', '2026-03-02', 'Chưa thanh toán');

-- Dumping structure for table doan.thuonghieu
CREATE TABLE IF NOT EXISTS `thuonghieu` (
  `mathuonghieu` int NOT NULL AUTO_INCREMENT,
  `tenthuonghieu` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`mathuonghieu`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table doan.thuonghieu: ~6 rows (approximately)
INSERT INTO `thuonghieu` (`mathuonghieu`, `tenthuonghieu`, `logo`) VALUES
	(1, 'Thiên Long', 'thienlong.png'),
	(2, 'Deli', 'deli.png'),
	(3, 'Hồng Hà', 'hongha.png'),
	(4, 'Staedtler', 'staedtler.png'),
	(5, 'Stabilo', 'stabilo.png'),
	(6, 'Casio', 'casio.png');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
