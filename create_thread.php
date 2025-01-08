<?php
require_once 'Database.php';
$db = Database::getInstance()->getPdo();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

try {
    $db->beginTransaction();

    // スレッド作成
    $stmt = $db->prepare("INSERT INTO threads (title) VALUES (?)");
    $stmt->execute([
        filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS)
    ]);
    
    $thread_id = $db->lastInsertId();

    // 最初の投稿を作成
    $stmt = $db->prepare("
        INSERT INTO responses (thread_id, name, content, ip_address) 
        VALUES (?, ?, ?, ?)
    ");
    $stmt->execute([
        $thread_id,
        filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?: '名無しさん',
        filter_input(INPUT_POST, 'content', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
        $_SERVER['REMOTE_ADDR']
    ]);

    // レス数を更新
    $stmt = $db->prepare("UPDATE threads SET response_count = 1 WHERE id = ?");
    $stmt->execute([$thread_id]);

    $db->commit();
    header("Location: thread.php?id=$thread_id");

} catch (Exception $e) {
    $db->rollBack();
    error_log('スレッド作成エラー: ' . $e->getMessage());
    header('Location: index.php?error=1');
}
exit;