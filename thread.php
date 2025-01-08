<?php
require_once 'Database.php';
$config = include 'config.php';
$db = Database::getInstance()->getPdo();

$thread_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
if (!$thread_id) {
    header('Location: index.php');
    exit;
}

// スレッド情報を取得
$thread = $db->prepare("SELECT * FROM threads WHERE id = ?");
$thread->execute([$thread_id]);
$thread = $thread->fetch();

if (!$thread) {
    header('Location: index.php');
    exit;
}

// レス一覧を取得
$responses = $db->prepare("
    SELECT * FROM responses 
    WHERE thread_id = ? 
    ORDER BY created_at ASC
");
$responses->execute([$thread_id]);
$responses = $responses->fetchAll();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($thread['title']); ?> - <?php echo htmlspecialchars($config['SITE_NAME']); ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1><?php echo htmlspecialchars($thread['title']); ?></h1>
            <a href="index.php">掲示板に戻る</a>
        </header>

        <section class="responses">
            <?php foreach ($responses as $index => $response): ?>
                <div class="response">
                    <div class="response-header">
                        <?php echo $index + 1; ?> 
                        名前: <?php echo htmlspecialchars($response['name']); ?> 
                        投稿日時: <?php echo $response['created_at']; ?>
                    </div>
                    <div class="response-content">
                        <?php echo nl2br(htmlspecialchars($response['content'])); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </section>

        <section class="response-form">
            <h2>レス投稿</h2>
            <form action="post_response.php" method="POST">
                <input type="hidden" name="thread_id" value="<?php echo $thread_id; ?>">
                <div>
                    <label for="name">名前:</label>
                    <input type="text" id="name" name="name" value="名無しさん">
                </div>
                <div>
                    <label for="content">内容:</label>
                    <textarea id="content" name="content" required></textarea>
                </div>
                <button type="submit">投稿する</button>
            </form>
        </section>
    </div>
</body>
</html>