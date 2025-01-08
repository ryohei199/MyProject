<?php
class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        $config = include 'config.php';
        
        try {
            $this->pdo = new PDO(
                "mysql:host={$config['DB_HOST']};port={$config['DB_PORT']};dbname={$config['DB_NAME']};charset=utf8mb4",
                $config['DB_USER'],
                $config['DB_PASS'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch (PDOException $e) {
            error_log('データベース接続エラー: ' . $e->getMessage());
            throw new Exception('データベース接続エラーが発生しました。');
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getPdo() {
        return $this->pdo;
    }
}