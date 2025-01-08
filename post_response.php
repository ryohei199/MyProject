<?php
require_once 'Database.php';
$db = Database::getInstance()->getPdo();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$thread_id = filter_input(INPUT_POST, 'thread_id', FILTER_SANITIZE_NUMBER_INT);
if (!$thread_id) {
    header('Location: index.php');
    exit;
}

try {
    $db->beginTransaction();

    // レスを投稿
    $stmt = $db->prepare("
        INSERT INTO responses (thread_id, name, content, ip_address) 
        VALUES (?, ?, ?, ?)
    ");
    $stmt->execute([
        $thread_id,
        filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?: '名無しさん',
        filter_input(INPUT_POST,
