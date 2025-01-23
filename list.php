<?php
require_once 'config.php';
$pdo = connectDB();
$stmt = $pdo->query('SELECT * FROM urls ORDER BY created_at DESC');
$urls = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>投稿一覧 - 動画編集URL掲示板</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container py-4">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">ホーム</a></li>
                <li class="breadcrumb-item active">投稿一覧</li>
            </ol>
        </nav>

        <h1 class="mb-4">投稿一覧</h1>

        <div class="card">
            <div class="card-body">
                <div class="list-group">
                    <?php if (empty($urls)): ?>
                        <p class="text-muted text-center">まだ投稿はありません。</p>
                    <?php else: ?>
                        <?php foreach ($urls as $url): ?>
                        <a href="board.php?id=<?= h($url['id']) ?>" class="list-group-item list-group-item-action">
                            <div class="d-flex justify-content-between align-items-center">
                                <h3 class="h6 mb-1"><?= h($url['title']) ?></h3>
                                <small class="text-muted"><?= h(date('Y/m/d H:i', strtotime($url['created_at']))) ?></small>
                            </div>
                            <small class="text-muted"><?= h($url['url']) ?></small>
                        </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="text-center mt-3">
            <a href="index.html" class="btn btn-secondary">戻る</a>
        </div>
    </div>
</body>
</html>