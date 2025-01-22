<?php
// データベース接続
$pdo = new PDO('mysql:host=localhost;dbname=掲示板', 'ユーザー名', 'パスワード');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $url = $_POST['url'];
    $title = $_POST['title'];

    // URLをデータベースに保存
    $stmt = $pdo->prepare('INSERT INTO urls (url, title) VALUES (?, ?)');
    $stmt->execute([$url, $title]);

    // トップページへリダイレクト
    header('Location: index.html');
    exit;
}
?>
