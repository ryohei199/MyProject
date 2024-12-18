<?php
set_include_path( __DIR__ . '/./'); 
require 'vendor/autoload.php';
$config = include 'config/config.php';

try {
    $pdo = new PDO(
        "mysql:host={$config['DB_HOST']};port={$config['DB_PORT']};dbname={$config['DB_NAME']};charset=utf8mb4",
        $config['DB_USER'],
        $config['DB_PASS']
    );
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
};

// POSTリクエストの処理
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['_method']) && $_POST['_method'] === 'DELETE') {
        // DELETE リクエスト (投稿の削除)
        $stmt = $pdo->prepare('DELETE FROM posts WHERE id = :id');
        $stmt->execute(['id' => $_POST['id']]);
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    } else {
        // 新しい投稿を追加
        $stmt = $pdo->prepare('INSERT INTO posts (name, title, content) VALUES (:name, :title, :content)');
        $stmt->execute([
            'name' => $_POST['name'] ?? '',
            'title' => $_POST['title'] ?? '',
            'content' => $_POST['content'] ?? '',
        ]);
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
}
