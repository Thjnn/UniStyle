<?php
function formatVND($tien)
{
    return number_format($tien, 0, ',', '.') . 'đ';
}
