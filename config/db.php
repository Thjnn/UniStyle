<?php
$conn = mysqli_connect("localhost", "root", "", "doan");

if (!$conn) {
    die("Kết nối CSDL thất bại: " . mysqli_connect_error());
}
