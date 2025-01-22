<?php
// データベース接続
$pdo = new PDO('mysql:host=localhost;dbname=掲示板', 'ユーザー名', 'パスワード');

$url_id = $_GET['id'];

// URL情報取得
$stmt = $pdo->prepare('SELECT * FROM urls WHERE id = ?');
$stmt->execute([$url_id]);
$url = $stmt->fetch();

// コメント取得
$comments_stmt = $pdo->prepare('SELECT * FROM comments WHERE url_id = ? ORDER BY created_at ASC');
$comments_stmt->execute([$url_id]);
$comments = $comments_stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($url['title']); ?> - 掲示板</title>
</head>
<body>
    <h1><?php echo htmlspecialchars($url['title']); ?></h1>
    <p>URL: <a href="<?php echo htmlspecialchars($url['url']); ?>" target="_blank"><?php echo htmlspecialchars($url['url']); ?></a></p>

    <div class="comments">
        <h2>コメント一覧</h2>
        <?php foreach ($comments as $comment): ?>
            <p><?php echo htmlspecialchars($comment['comment']); ?> (<?php echo $comment['created_at']; ?>)</p>
        <?php endforeach; ?>
    </div>

    <div class="comment-form">
        <form action="post_comment.php" method="POST">
            <input type="hidden" name="url_id" value="<?php echo $url_id; ?>">
            <label for="comment">コメント:</label><br>
            <textarea id="comment" name="comment" required></textarea><br>
            <button type="submit">投稿</button>
        </form>
    </div>
</body>
</html>
