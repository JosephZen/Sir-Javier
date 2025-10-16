<?php
session_start();
$host = 'localhost';
$db = 'team_calendar';
$user = 'root';  // Change for production
$pass = '';      // Change for production

// ClickUp Configuration (replace with your values; use env vars in prod)
define('pk_294763129_CUGK4W8FD8PFPN7WED4U712CN3Q1CYOW', 'pk_294763129_CUGK4W8FD8PFPN7WED4U712CN3Q1CYOW'); 
define('90161284620', '90161284620');          // e.g., '89a123bc4d5e6f'

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Log function for ClickUp errors
function logClickUpError($message) {
    error_log("[ClickUp] " . $message . " - " . date('Y-m-d H:i:s'), 3, 'logs/clickup_errors.log');
}
?>