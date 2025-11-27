<?php
session_start();
require __DIR__ . '/config/db.php';

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user) {
    header("Location: login.php?error=帳號不存在");
    exit;
}

if (!password_verify($password, $user['password'])) {
    header("Location: login.php?error=密碼錯誤");
    exit;
}

// 登入成功
$_SESSION['user_id'] = $user['id'];
$_SESSION['email'] = $user['email'];

header("Location: reminders.php");
exit;
