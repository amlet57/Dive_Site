<?php
session_start();
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: order.php');
    exit();
}

function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

$clothing_type = sanitize($_POST['clothing_type'] ?? '');
$size = sanitize($_POST['size'] ?? '');
$quantity = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);
$customer_name = sanitize($_POST['customer_name'] ?? '');
$phone = sanitize($_POST['phone'] ?? '');
$address = sanitize($_POST['address'] ?? '');
$comment = sanitize($_POST['comment'] ?? '');

// Валидация
$errors = [];
?>