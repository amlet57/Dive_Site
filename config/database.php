<?php
session_start();

$host = "localhost";
$dbname = "divegear";
$username = "root";
$password = "root";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch(PDOException $e) {
    die("Database connection error: " . $e->getMessage());
}

function generateOrderNumber() {
    $date = date('Ymd');
    $random = strtoupper(substr(uniqid(), -6));
    return "DG-{$date}-{$random}";
}

function isAdminAuthenticated() {
    return isset($_SESSION['admin_id']) && isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

function isAdmin() {
    return isset($_SESSION['admin_role']) && $_SESSION['admin_role'] === 'admin';
}

function redirect($url) {
    header("Location: $url");
    exit();
}

function logAdminAction($pdo, $admin_id, $action, $details = null) {
    $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';

    $stmt = $pdo->prepare("INSERT INTO admin_logs (admin_id, action, details, ip_address, user_agent) VALUES (?, ?, ?, ?, ?)");
    return $stmt->execute([$admin_id, $action, $details, $ip, $user_agent]);
}

function getSettings($pdo) {
    $stmt = $pdo->query("SELECT setting_key, setting_value FROM settings");
    $settings = [];
    while ($row = $stmt->fetch()) {
        $settings[$row['setting_key']] = $row['setting_value'];
    }
    return $settings;
}
?>