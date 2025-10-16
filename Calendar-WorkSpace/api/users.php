<?php
require '../config.php';
header('Content-Type: application/json');

$query = $_GET['q'] ?? '';
$stmt = $pdo->prepare("SELECT id, username FROM users WHERE username LIKE ? AND id != ? LIMIT 10");
$stmt->execute(["%$query%", $_SESSION['user_id']]);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($users);
?>