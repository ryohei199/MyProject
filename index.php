<?php
require_once 'Database.php';
$config = include 'config.php';
$db = Database::getInstance()->getPdo();

// スレッド一覧を取得
$threads = $db->query("
    SELECT 
        threads.*,
        MAX(responses.created_at) as last_response
    FROM threads 
    LEFT JOIN responses ON threads.id = responses.thread_id
    GROUP BY threads.id 
    ORDER BY last_response DESC, threads.created_at DESC
")->fetchAll();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($config['SITE_NAME']); ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1><?php echo htmlspecialchars($config['SITE_NAME']); ?></h1>
        </header>

        <section class="thread-form">
            <h2>新規スレッド作成</h2>
            <form action="create_thread.php" method="POST">
                <div>
                    <label for="title">スレッドタイトル:</label>
                    <input type="text" id="title" name="title" required maxlength="255">
                </div>
                <div>
                    <label for="content">最初の投稿:</label>
                    <textarea id="content" name="content" required></textarea>
                </div>
                <div>
                    <label for="name">名前:</label>
                    <input type="text" id="name" name="name" value="名無しさん">
                </div>
                <button type="submit">スレッド作成</button>
            </form>
        </section>

        <section class="thread-list">
            <h2>スレッド一覧</h2>
            <?php foreach ($threads as $thread): ?>
                <div class="thread">
                    <h3>
                        <a href="thread.php?id=<?php echo $thread['id']; ?>">
                            <?php echo htmlspecialchars($thread['title']); ?>
                        </a>
                    </h3>
                    <div class="thread-info">
                        レス数: <?php echo $thread['response_count']; ?> |
                        作成日時: <?php echo $thread['created_at']; ?> |
                        最終投稿: <?php echo $thread['last_response'] ?? '投稿なし'; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </section>
    </div>
</body>
</html>