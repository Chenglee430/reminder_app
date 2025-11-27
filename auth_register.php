<?php
session_start();
require __DIR__ . '/config/db.php';

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

// 檢查是否已有 Email
$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute([$email]);

if ($stmt->rowCount() > 0) {
    header("Location: register.php?error=Email 已被註冊");
    exit;
}

// 密碼加密
$hash = password_hash($password, PASSWORD_DEFAULT);

// 寫入 users 表
$stmt = $pdo->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
$stmt->execute([$email, $hash]);

// 自動登入
$_SESSION['user_id'] = $pdo->lastInsertId();
$_SESSION['email'] = $email;

header("Location: reminders.php");
exit;
