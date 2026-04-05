<?php
session_start();
include './config/db.php'; 

$login_error = ''; 
$register_error = '';
$register_success = '';

if (isset($_POST['btn_dangnhap'])) {
    $tendangnhap = $_POST['tendangnhap'];
    $matkhau = $_POST['matkhau'];
    
    $sql_kh = "SELECT * FROM khachhang WHERE tendangnhap = '$tendangnhap' AND matkhau = '$matkhau'";
    $result_kh = $conn->query($sql_kh);

    if ($result_kh->num_rows > 0) {
        $row = $result_kh->fetch_assoc();
        $_SESSION['khachhang_id'] = $row['makh'];
        $_SESSION['khachhang_ten'] = $row['tenkh'];
        header("Location: index.php");
        exit();
    } else {
        $sql_ad = "SELECT * FROM quantrivien WHERE tendangnhap = '$tendangnhap' AND matkhau = '$matkhau'";
        $result_ad = $conn->query($sql_ad);
        
        if ($result_ad->num_rows > 0) {
            $row = $result_ad->fetch_assoc();
            $_SESSION['admin_id'] = $row['maqtv'];
            $_SESSION['admin_ten'] = $row['tenqtv'];
            header("Location: admin/index.php");
            exit();
        } else {
            $login_error = "Tên đăng nhập hoặc mật khẩu không chính xác!";
        }
    }
}

if (isset($_POST['btn_dangky'])) {
    $hoten = $_POST['hoten'];
    $tendangnhap = $_POST['tendangnhap'];
    $matkhau = $_POST['matkhau'];
    
    $check_sql = "SELECT * FROM khachhang WHERE tendangnhap = '$tendangnhap'";
    $check_res = $conn->query($check_sql);
    
    if ($check_res->num_rows > 0) {
        $register_error = "Email hoặc Số điện thoại này đã được đăng ký!";
    } else {
        $sdt = '';
        if (is_numeric($tendangnhap) && strlen($tendangnhap) == 10) {
            $sdt = $tendangnhap;
        }
        
        $insert_sql = "INSERT INTO khachhang (tenkh, tendangnhap, matkhau, sdt) VALUES ('$hoten', '$tendangnhap', '$matkhau', '$sdt')";
        
        if ($conn->query($insert_sql) === TRUE) {
            $register_success = "Đăng ký thành công! Bạn có thể đăng nhập ngay.";
        } else {
            $register_error = "Đã xảy ra lỗi, vui lòng thử lại!";
        }
    }
}
?>
<!doctype html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Đăng Nhập / Đăng Ký - UniStyle</title>
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,0..1"
    />
    <link
      rel="shortcut icon"
      href="assets/file_anh/0c4690d7-3599-4de4-a0a4-841817ead1c0.png"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
    />
    <link rel="stylesheet" href="./assets/css/login.css" />
    <link rel="stylesheet" href="./assets/css/reposive.css" />
  </head>
  <body>
    <header class="auth-header">
      <div class="container header-content">
        <a href="index.php" class="logo-group">
          <img
            src="./assets/file_anh/0c4690d7-3599-4de4-a0a4-841817ead1c0.png"
            alt="Logo"
          />
          <span class="logo-title" id="headerActionTitle">Đăng nhập</span>
        </a>
        <a href="#!" class="help-link">Bạn cần giúp đỡ?</a>
      </div>
    </header>

    <div class="main-content">
      <div class="main-wrapper">
        <div class="banner-section">
          <img
            src="./assets/file_anh/0c4690d7-3599-4de4-a0a4-841817ead1c0.png"
            alt="Big Logo"
          />
          <h1>
            UniStyle - Định hình phong cách<br />
            khẳng định chất riêng
          </h1>
        </div>

        <div class="forms-container">
          <div class="form-section" id="loginFormSection">
            <div class="form-header">
              <h2>Đăng nhập</h2>
            </div>

            <form method="POST" action="login.php">
              <div class="form-group">
                <input
                  type="text"
                  name="tendangnhap"
                  id="loginContact"
                  placeholder="Email/Số điện thoại/Tên đăng nhập"
                  required
                />
                <div class="error-message" id="loginError"></div>
              </div>
              <div class="form-group" style="position: relative">
                <input type="password" name="matkhau" placeholder="Mật khẩu" required />
                <span
                  class="material-symbols-outlined toggle-password"
                  style="
                    position: absolute;
                    right: 10px;
                    top: 12px;
                    color: #999;
                    cursor: pointer;
                    font-size: 20px;
                  "
                  >visibility_off</span
                >
              </div>
              
              <?php if($login_error != ''): ?>
                <div style="color: #ee4d2d; font-size: 13px; margin-bottom: 10px; text-align: center;">
                  <?php echo $login_error; ?>
                </div>
              <?php endif; ?>

              <button type="submit" name="btn_dangnhap" class="auth-submit-btn">Đăng nhập</button>

              <div class="forgot-pass">
                <a href="#!" id="switchToForgotPassword">Quên mật khẩu?</a>
              </div>
            </form>

            <div class="switch-to-register">
              Bạn mới biết đến UniStyle?
              <a href="#!" id="switchToRegister">Đăng ký</a>
            </div>
          </div>

          <div
            class="form-section"
            id="registerFormSection"
            style="display: none"
          >
            <div class="form-header">
              <h2>Đăng ký</h2>
            </div>

            <form method="POST" action="login.php" id="registerForm">
              <div class="form-group">
                <input type="text" name="hoten" placeholder="Họ và tên" required />
              </div>
              <div class="form-group">
                <input
                  type="text"
                  name="tendangnhap"
                  id="regContact"
                  placeholder="Email hoặc Số điện thoại"
                  required
                />
                <div class="error-message" id="regContactError"></div>
              </div>
              <div class="form-group" style="position: relative">
                <input
                  type="password"
                  name="matkhau"
                  id="regPassword"
                  placeholder="Mật khẩu"
                  required
                />
                <span
                  class="material-symbols-outlined toggle-password"
                  style="
                    position: absolute;
                    right: 10px;
                    top: 12px;
                    color: #999;
                    cursor: pointer;
                    font-size: 20px;
                  "
                  >visibility_off</span
                >
              </div>
              <div class="form-group" style="position: relative">
                <input
                  type="password"
                  id="regConfirmPassword"
                  placeholder="Xác nhận mật khẩu"
                  required
                />
                <span
                  class="material-symbols-outlined toggle-password"
                  style="
                    position: absolute;
                    right: 10px;
                    top: 12px;
                    color: #999;
                    cursor: pointer;
                    font-size: 20px;
                  "
                  >visibility_off</span
                >
                <div class="error-message" id="regPassError"></div>
              </div>
              
              <?php if($register_error != ''): ?>
                <div style="color: #ee4d2d; font-size: 13px; margin-bottom: 10px; text-align: center;">
                  <?php echo $register_error; ?>
                </div>
              <?php endif; ?>

              <?php if($register_success != ''): ?>
                <div style="color: #28a745; font-size: 13px; margin-bottom: 10px; text-align: center;">
                  <?php echo $register_success; ?>
                </div>
              <?php endif; ?>

              <button type="submit" name="btn_dangky" class="auth-submit-btn">Đăng ký</button>
            </form>

            <div class="switch-to-register">
              Bạn đã có tài khoản UniStyle?
              <a href="#!" id="switchToLoginFromRegister">Đăng nhập</a>
            </div>
          </div>

          <div
            class="form-section"
            id="forgotPasswordFormSection"
            style="display: none"
          >
            <div class="form-header">
              <h2>Quên Mật Khẩu</h2>
            </div>

            <p class="forgot-pass-desc">
              Vui lòng nhập địa chỉ email của bạn. Chúng tôi sẽ gửi một liên kết
              để bạn đặt lại mật khẩu.
            </p>

            <form id="forgotPasswordForm">
              <div class="form-group">
                <input type="email" placeholder="Email của bạn" required />
              </div>
              <button type="submit" class="auth-submit-btn">
                Gửi liên kết
              </button>
            </form>

            <div class="back-to-login">
              <a href="#!" id="switchToLoginFromForgot">Trở về Đăng nhập</a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <footer class="footer">
      <div class="container">
        <div class="footer-container">
          <div class="footer-col">
            <h2 class="logo">
              <img
                src="./assets/file_anh/0c4690d7-3599-4de4-a0a4-841817ead1c0.png"
                alt=""
              />
              UniStyle
            </h2>
            <p>
              Nếu bạn có bất kỳ thắc mắc nào, vui lòng liên hệ với chúng tôi
              theo địa chỉ sau:
              <span>support@example.com</span>
            </p>

            <p>📍 16 Thiên Hộ Vương, P1, Mỹ Tho, Tiền Giang</p>
            <p>📞 (+84) 0777331314</p>

            <div class="social-icons">
              <a href="#"><i class="fa-brands fa-x-twitter"></i></a>
              <a href="#"><i class="fa-brands fa-instagram"></i></a>
              <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
              <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
              <a href="#"><i class="fa-brands fa-pinterest-p"></i></a>
            </div>
          </div>

          <div class="footer-col">
            <h3>Công ty</h3>
            <ul>
              <li><a href="#">Tuyển dụng</a></li>
              <li><a href="#">Về chúng tôi</a></li>
              <li><a href="#">Quy tắc kinh doanh</a></li>
              <li><a href="#">Hợp tác sự kiện</a></li>
              <li><a href="#">Nhà cung cấp</a></li>
              <li><a href="#">Chương trình cộng tác viên</a></li>
            </ul>
          </div>

          <div class="footer-col">
            <h3>Chăm sóc khách hàng</h3>
            <ul>
              <li><a href="#">Theo dõi đơn hàng</a></li>
              <li><a href="#">Đổi / Trả hàng</a></li>
              <li><a href="#">Thông tin vận chuyển</a></li>
              <li><a href="#">Chính sách bảo hành</a></li>
              <li><a href="#">Hệ thống cửa hàng</a></li>
              <li><a href="#">Liên hệ</a></li>
            </ul>
          </div>

          <div class="footer-col">
            <h3>Dịch vụ</h3>
            <ul>
              <li><a href="#">In ấn - Photo</a></li>
              <li><a href="#">Đóng gáy tài liệu</a></li>
              <li><a href="#">Laminating (Ép nhựa)</a></li>
              <li><a href="#">Cung cấp sỉ văn phòng phẩm</a></li>
              <li><a href="#">Đặt hàng theo yêu cầu</a></li>
              <li><a href="#">Trung tâm hỗ trợ</a></li>
            </ul>
          </div>
        </div>
      </div>
    </footer>

    <script>
      document.addEventListener("DOMContentLoaded", function () {
        const loginForm = document.getElementById("loginFormSection");
        const registerForm = document.getElementById("registerFormSection");
        const forgotPasswordForm = document.getElementById(
          "forgotPasswordFormSection",
        );
        const switchToRegister = document.getElementById("switchToRegister");
        const switchToLoginFromRegister = document.getElementById(
          "switchToLoginFromRegister",
        );
        const switchToForgotPassword = document.getElementById(
          "switchToForgotPassword",
        );
        const switchToLoginFromForgot = document.getElementById(
          "switchToLoginFromForgot",
        );
        const headerActionTitle = document.getElementById("headerActionTitle");

        function hideAllForms() {
          loginForm.style.display = "none";
          registerForm.style.display = "none";
          forgotPasswordForm.style.display = "none";
        }

        switchToRegister.addEventListener("click", function (e) {
          if(e) e.preventDefault();
          hideAllForms();
          registerForm.style.display = "block";
          headerActionTitle.textContent = "Đăng ký";
        });

        switchToLoginFromRegister.addEventListener("click", function (e) {
          e.preventDefault();
          hideAllForms();
          loginForm.style.display = "block";
          headerActionTitle.textContent = "Đăng nhập";
        });

        switchToForgotPassword.addEventListener("click", function (e) {
          e.preventDefault();
          hideAllForms();
          forgotPasswordForm.style.display = "block";
          headerActionTitle.textContent = "Quên mật khẩu";
        });

        switchToLoginFromForgot.addEventListener("click", function (e) {
          e.preventDefault();
          hideAllForms();
          loginForm.style.display = "block";
          headerActionTitle.textContent = "Đăng nhập";
        });

        <?php if($register_error != '' || $register_success != ''): ?>
          switchToRegister.click();
        <?php endif; ?>

        const togglePasswords = document.querySelectorAll(".toggle-password");
        togglePasswords.forEach((icon) => {
          icon.addEventListener("click", function () {
            const input = this.previousElementSibling;
            if (input.type === "password") {
              input.type = "text";
              this.textContent = "visibility";
            } else {
              input.type = "password";
              this.textContent = "visibility_off";
            }
          });
        });

        const phoneRegex = /^0\d{9}$/;
        const emailRegex =
          /^[a-zA-Z0-9._%+-]+@(gmail\.com|yahoo\.com|outlook\.com|hotmail\.com|icloud\.com)$/i;

        const loginContact = document.getElementById("loginContact");
        const loginError = document.getElementById("loginError");

        if(loginContact) {
            loginContact.addEventListener("input", function () {
              if(loginError) loginError.style.display = "none";
            });
        }

        const loginFormElement = document.querySelector("#loginFormSection form");
        if(loginFormElement) {
            loginFormElement.addEventListener("submit", function (e) {
              const contactInput = loginContact.value.trim();
              let isValid = true;
              let msg = "";

              if (contactInput.includes("@")) {
                if (!emailRegex.test(contactInput)) {
                  isValid = false;
                  msg =
                    "Email không hợp lệ (Hệ thống hỗ trợ @gmail.com, @yahoo.com...).";
                }
              } else if (/^\d+$/.test(contactInput)) {
                if (!phoneRegex.test(contactInput)) {
                  isValid = false;
                  msg =
                    "Số điện thoại đăng nhập phải đủ 10 số và bắt đầu bằng số 0.";
                }
              }

              if (!isValid) {
                e.preventDefault();
                loginError.textContent = msg;
                loginError.style.display = "block";
              }
            });
        }

        const regContact = document.getElementById("regContact");
        const regPass = document.getElementById("regPassword");
        const regConfirmPass = document.getElementById("regConfirmPassword");
        const regContactError = document.getElementById("regContactError");
        const regPassError = document.getElementById("regPassError");

        if(regContact) {
            regContact.addEventListener(
              "input",
              () => { if(regContactError) regContactError.style.display = "none"; }
            );
        }
        if(regConfirmPass) {
            regConfirmPass.addEventListener(
              "input",
              () => { if(regPassError) regPassError.style.display = "none"; }
            );
        }
        if(regPass) {
            regPass.addEventListener(
              "input",
              () => { if(regPassError) regPassError.style.display = "none"; }
            );
        }

        const registerFormElement = document.getElementById("registerForm");
        if(registerFormElement) {
            registerFormElement.addEventListener("submit", function (e) {
              const contactInput = regContact.value.trim();
              const pass = regPass.value;
              const confirmPass = regConfirmPass.value;
              let isRegValid = true;

              if (
                !phoneRegex.test(contactInput) &&
                !emailRegex.test(contactInput)
              ) {
                regContactError.textContent =
                  "Vui lòng nhập SĐT hợp lệ (10 số, đầu 0) hoặc Email hợp lệ.";
                regContactError.style.display = "block";
                isRegValid = false;
              }

              if (pass !== confirmPass) {
                regPassError.textContent =
                  "Mật khẩu và Xác nhận mật khẩu không khớp!";
                regPassError.style.display = "block";
                isRegValid = false;
              }

              if (!isRegValid) {
                e.preventDefault();
              }
            });
        }
      });
    </script>
    <button id="backToTop">
      <span class="material-symbols-outlined"> keyboard_arrow_up </span>
    </button>
    <script>
      const btn = document.getElementById("backToTop");

      window.onscroll = function () {
        if (document.documentElement.scrollTop > 200) {
          btn.style.display = "block";
        } else {
          btn.style.display = "none";
        }
      };

      btn.onclick = function () {
        window.scrollTo({
          top: 0,
          behavior: "smooth",
        });
      };
    </script>
  </body>
</html>