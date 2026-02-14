<?php
// admin/login.php
session_start();
require_once '../config/database.php';

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// Ищем администратора
$stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ? AND is_active = 1");
$stmt->execute([$username]);
$admin = $stmt->fetch();

if ($admin && password_verify($password, $admin['password_hash'])) {
    // Успешный вход
    $_SESSION['admin_id'] = $admin['id'];
    $_SESSION['admin_username'] = $admin['username'];
    $_SESSION['admin_name'] = $admin['full_name'];
    $_SESSION['admin_role'] = $admin['role'];
    $_SESSION['admin_logged_in'] = true;
    
    // Обновляем время последнего входа
    $updateStmt = $pdo->prepare("UPDATE admins SET last_login = NOW() WHERE id = ?");
    $updateStmt->execute([$admin['id']]);
    
    // Логируем действие
    logAdminAction($pdo, $admin['id'], 'login', 'Успешный вход в систему');
    
    header('Location: dashboard.php');
    exit();
}

// Неудачный вход
header('Location: index.php?error=1');
exit();
?>