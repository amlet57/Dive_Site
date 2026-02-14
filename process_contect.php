<?php
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: contact.php');
    exit();
}

$name = htmlspecialchars(trim($_POST['name'] ?? ''));
$phone = htmlspecialchars(trim($_POST['phone'] ?? ''));
$email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
$subject = htmlspecialchars(trim($_POST['subject'] ?? 'order'));
$message = htmlspecialchars(trim($_POST['message'] ?? ''));

$errors = [];

if (empty($name)) {
    $errors[] = 'Имя обязательно';
}

if (empty($phone) || !preg_match('/^\+7\s\(\d{3}\)\s\d{3}-\d{2}-\d{2}$/', $phone)) {
    $errors[] = 'Корректный телефон обязателен';
}

if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Некорректный email';
}

if (empty($message)) {
    $errors[] = 'Сообщение обязательно';
}

if (!empty($errors)) {
    $_SESSION['contact_errors'] = $errors;
    $_SESSION['contact_data'] = $_POST;
    header('Location: contact.php#contact-form');
    exit();
}

try {
    $stmt = $pdo->prepare("INSERT INTO contact_messages (name, phone, email, subject, message) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$name, $phone, $email ?: null, $subject, $message]);
    header('Location: contact.php?message_sent=1');
    exit();
} catch (PDOException $e) {
    error_log('Contact save error: ' . $e->getMessage());
    header('Location: contact.php?error=1');
    exit();
}
?>