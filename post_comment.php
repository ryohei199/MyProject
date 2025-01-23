<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$url_id = filter_input(INPUT_POST, 'url_id', FILTER_VALIDATE_INT);
$comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_STRING);

if (!$url_id || !$comment) {
    header('Location: index.php');
    exit;
}

$pdo = connectDB();

try {
    $stmt = $pdo->prepare('INSERT INTO comments (url_id, comment) VALUES (?, ?)');
    $stmt->execute([$url_id, $comment]);
    header('Location: board.php?id=' . $url_id);
} catch (PDOException $e) {
    die('エラーが発生しました: ' . $e->getMessage());
}
?>