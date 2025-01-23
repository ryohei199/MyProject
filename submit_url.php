<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$url = filter_input(INPUT_POST, 'url', FILTER_VALIDATE_URL);
$title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);

if (!$url || !$title) {
    header('Location: index.php');
    exit;
}

$pdo = connectDB();

try {
    $stmt = $pdo->prepare('INSERT INTO urls (url, title) VALUES (?, ?)');
    $stmt->execute([$url, $title]);
    header('Location: index.php');
} catch (PDOException $e) {
    die('エラーが発生しました: ' . $e->getMessage());
}
?>