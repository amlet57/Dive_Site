<?php
// admin/logout.php
session_start();

if (isset($_SESSION['admin_id'])) {
    // Можно залогировать выход
    require_once '../config/database.php';
    logAdminAction($pdo, $_SESSION['admin_id'], 'logout', 'Выход из системы');
}

$_SESSION = [];
session_destroy();

header('Location: index.php');
exit();
?>