<?php
ob_start();
ini_set('display_errors', 0);
error_reporting(0);
require_once __DIR__ . '/../config/db.php';
$b = ob_get_clean();
if ($b) {
    header('Content-Type: application/json');
    echo json_encode(['ok' => false, 'error' => strip_tags(trim($b))]);
    exit;
}
header('Content-Type: application/json; charset=utf-8');
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$id) {
    echo json_encode(['ok' => false, 'error' => 'Thiếu ID']);
    exit;
}
$ok = $conn->query("DELETE FROM quangcao WHERE id=$id");
echo json_encode($ok ? ['ok' => true] : ['ok' => false, 'error' => $conn->error], JSON_UNESCAPED_UNICODE);
