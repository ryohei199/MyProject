<?php
// データベース接続
$pdo = new PDO('mysql:host=localhost;dbname=掲示板', 'ユーザー名', 'パスワード');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $url_id = $_POST['url_id'];
    $comment = $_POST['comment'];

    // コメントをデータベースに保存
    $stmt = $pdo->prepare('INSERT INTO comments (url_id, comment) VALUES (?, ?)');
    $stmt->execute([$url_id, $comment]);

    // 掲示板ページへリダイレクト
    header('Location: board.php?id=' . $url_id);
    exit;
}
?>
