<?php
require_once 'config.php';
$pdo = connectDB();

$url_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$url_id) {
    header('Location: index.php');
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM urls WHERE id = ?');
$stmt->execute([$url_id]);
$url = $stmt->fetch();

if (!$url) {
    header('Location: index.php');
    exit;
}

$comments_stmt = $pdo->prepare('SELECT * FROM comments WHERE url_id = ? ORDER BY created_at ASC');
$comments_stmt->execute([$url_id]);
$comments = $comments_stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= h($url['title']) ?> - 掲示板</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container py-4">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">ホーム</a></li>
                <li class="breadcrumb-item active"><?= h($url['title']) ?></li>
            </ol>
        </nav>

        <div class="card mb-4">
            <div class="card-body">
                <h1 class="h3 mb-3"><?= h($url['title']) ?></h1>
                <p class="mb-0">
                    URL: <a href="<?= h($url['url']) ?>" target="_blank" rel="noopener noreferrer"><?= h($url['url']) ?></a>
                </p>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <h2 class="card-title h5 mb-3">コメント投稿</h2>
                <form action="post_comment.php" method="POST" class="needs-validation" novalidate>
                    <input type="hidden" name="url_id" value="<?= h($url_id) ?>">
                    <div class="mb-3">
                        <label for="comment" class="form-label">コメント:</label>
                        <textarea class="form-control" id="comment" name="comment" rows="3" required></textarea>
                        <div class="invalid-feedback">コメントを入力してください。</div>
                    </div>
                    <button type="submit" class="btn btn-primary">投稿</button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h2 class="card-title h5 mb-3">コメント一覧</h2>
                <?php if (empty($comments)): ?>
                    <p class="text-muted">まだコメントはありません。</p>
                <?php else: ?>
                    <div class="list-group">
                        <?php foreach ($comments as $comment): ?>
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-break"><?= nl2br(h($comment['comment'])) ?></div>
                                <small class="text-muted ms-3"><?= h(date('Y/m/d H:i', strtotime($comment['created_at']))) ?></small>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="validation.js"></script>
</body>
</html>