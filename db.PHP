<?php
// config/db.php
// 資料庫連線設定（使用 PDO）

$host    = '127.0.0.1';           // 或 'localhost'
$db      = 'reminder_system';     
$user    = 'root';               
$pass    = '123456';  
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // 有錯就丟例外，方便 debug
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,                  // 使用真實預處理
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    // 這裡先簡單處理，之後可以改成更友善的錯誤頁
    http_response_code(500);
    echo "Database connection failed.";
    error_log('DB Connection Error: ' . $e->getMessage());
    exit;
}
