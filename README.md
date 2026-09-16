# UniStyle

UniStyle là một website bán hàng thời trang/đồng phục được xây dựng bằng **PHP thuần** kết hợp **MySQL**, có các trang cửa hàng (shop, product-detail, checkout), quản trị (admin), tin tức, liên hệ (gửi mail qua PHPMailer), và tính năng xuất Excel (PhpSpreadsheet).

## 1. Yêu cầu hệ thống

- PHP **8.1 trở lên** (do thư viện `phpoffice/phpspreadsheet` yêu cầu PHP ^8.1)
- MySQL / MariaDB
- Composer
- Web server: Apache/Nginx hoặc dùng luôn PHP built-in server
- Extension PHP cần bật: `mysqli`, `mbstring`, `gd`, `zip`, `xml`, `curl`
- Khuyến nghị dùng **XAMPP/Laragon** nếu chạy trên Windows cho tiện

## 2. Clone dự án

```bash
git clone https://github.com/Thjnn/UniStyle.git
cd UniStyle
```

## 3. Cài đặt thư viện qua Composer

```bash
composer install
```

Lệnh này sẽ cài `phpoffice/phpspreadsheet` (dùng cho tính năng xuất Excel). Thư viện `PHPMailer` đã có sẵn trong thư mục `PHPMailer/`, không cần cài thêm.

## 4. Tạo cơ sở dữ liệu

1. Mở phpMyAdmin (hoặc client MySQL bất kỳ), tạo một database tên **`doan`** (đúng tên biến `$dbname` trong `config/db.php`).
2. Import file `doan.sql` (nằm ở thư mục gốc dự án) vào database vừa tạo:

```bash
mysql -u root -p doan < doan.sql
```

hoặc dùng phpMyAdmin: chọn database `doan` → tab **Import** → chọn file `doan.sql`.

## 5. Cấu hình kết nối database

Mở file `config/db.php` và chỉnh lại thông tin kết nối cho khớp với môi trường của bạn:

```php
<?php
$servername = "localhost";
$username   = "root";
$password   = "";        // mật khẩu MySQL của bạn
$dbname     = "doan";
$conn = new mysqli($servername, $username, $password, $dbname);
```

Mặc định file đang cấu hình sẵn cho MySQL local không mật khẩu (kiểu XAMPP), nếu bạn dùng cấu hình khác thì sửa lại 3 dòng đầu.

## 6. Cấu hình gửi email (chức năng Liên hệ)

Chức năng liên hệ (`contact.php`) dùng PHPMailer gửi mail qua SMTP Gmail. Bạn cần:

1. Mở `contact.php`, tìm đoạn cấu hình SMTP:

```php
$mail->isSMTP();
$mail->Host       = 'smtp.gmail.com';
$mail->SMTPAuth   = true;
$mail->Username   = 'email-cua-ban@gmail.com';
$mail->Password   = 'app-password-16-ky-tu';
$mail->SMTPSecure = 'tls';
```

2. Thay bằng email Gmail của bạn và **mật khẩu ứng dụng (App Password)** — không dùng mật khẩu Gmail thường. Tạo App Password tại: Google Account → Bảo mật → Xác minh 2 bước → Mật khẩu ứng dụng.

> ⚠️ **Lưu ý bảo mật quan trọng:** hiện tại `contact.php` đang chứa trực tiếp tài khoản Gmail và App Password ngay trong code (đã public trên repo). Bạn nên:
> - Đổi App Password đó ngay trên tài khoản Google (coi như nó đã bị lộ).
> - Chuyển các thông tin nhạy cảm (DB, SMTP) ra file `.env` hoặc biến môi trường, thêm `.env` vào `.gitignore`, không commit thẳng vào code.

## 7. Chạy dự án

Cách đơn giản nhất là dùng **Laragon** (hoặc XAMPP tương tự):

1. Copy toàn bộ thư mục `UniStyle` vào thư mục `www` của Laragon (thường là `C:\laragon\www\UniStyle`), hoặc `htdocs` nếu dùng XAMPP.
2. Mở Laragon, nhấn **Start All** để bật Apache + MySQL.
3. Mở trình duyệt và truy cập:

```
http://localhost/UniStyle/
```

hoặc

```
http://localhost/UniStyle/index.php
```

> ⚠️ Lưu ý: không mở file `index.php` trực tiếp bằng cách double-click trong Explorer — làm vậy trình duyệt sẽ hiển thị sai hoặc không chạy được code PHP. Phải truy cập qua địa chỉ `localhost/...` như trên thì Apache mới xử lý được PHP.

**Cách khác** — dùng PHP built-in server (không cần Laragon/XAMPP):

```bash
php -S localhost:8000
```

Rồi mở trình duyệt tại `http://localhost:8000/index.php`.

## 8. Cấu trúc thư mục chính

```
UniStyle/
├── admin/              # Trang quản trị
├── assets/             # CSS, JS, hình ảnh
├── config/             # Cấu hình kết nối database (db.php)
├── includes/           # Các file dùng chung (header, footer, hàm tiện ích...)
├── layout/             # Layout giao diện
├── PHPMailer/          # Thư viện gửi mail
├── vendor/             # Thư viện Composer (sinh ra sau khi composer install)
├── doan.sql            # File dữ liệu database
├── index.php           # Trang chủ
├── shop.php            # Trang cửa hàng
├── product-detail.php  # Chi tiết sản phẩm
├── checkout.php        # Thanh toán
├── contact.php         # Liên hệ (gửi mail)
├── login.php / logout.php / profile.php
└── composer.json
```

## 9. Đăng nhập trang quản trị

Sau khi import xong `doan.sql`, kiểm tra bảng tài khoản admin trong database (thường là bảng `admin` hoặc `users`) để lấy tài khoản đăng nhập mặc định, rồi truy cập thư mục `admin/` để vào trang quản trị.

## 10. Xử lý lỗi thường gặp

| Lỗi | Nguyên nhân | Cách khắc phục |
|---|---|---|
| `Kết nối thất bại: ...` | Sai thông tin DB trong `config/db.php` | Kiểm tra lại host/user/password/database |
| Trang trắng, không hiển thị gì | PHP version không tương thích | Dùng PHP >= 8.1 |
| Không gửi được mail liên hệ | Sai SMTP Username/Password | Dùng App Password Gmail hợp lệ, kiểm tra SMTPSecure |
| Composer install lỗi | PHP thiếu extension | Bật `ext-gd`, `ext-zip`, `ext-xml`, `ext-mbstring` trong `php.ini` |
