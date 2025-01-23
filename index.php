<?php
require_once 'config.php';
$pdo = connectDB();
$stmt = $pdo->query('SELECT * FROM urls ORDER BY created_at DESC');
$urls = $stmt->fetchAll();
?>