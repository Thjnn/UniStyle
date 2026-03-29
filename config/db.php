<?php
$conn = mysqli_connect("localhost", "root", "", "doannganh");

if (!$conn) {
    die("Kết nối CSDL thất bại: " . mysqli_connect_error());
}
