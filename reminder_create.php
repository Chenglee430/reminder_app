<?php
// api/reminder_create.php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => '未登入']);
    exit;
}

require __DIR__ . '/../config/db.php';

$userId     = $_SESSION['user_id'];
$title      = trim($_POST['title'] ?? '');
$content    = trim($_POST['content'] ?? '');
$sendEmail  = isset($_POST['send_email']) ? (int)$_POST['send_email'] : 0;
$notifyAt   = $_POST['notify_at'] ?? null;

if ($title === '') {
    echo json_encode(['success' => false, 'message' => '標題不得為空']);
    exit;
}

// 若沒有設定時間或 send_email = 0，notify_at 設為 NULL
if ($sendEmail !== 1) {
    $sendEmail = 0;
    $notifyAt  = null;
}

try {
    $stmt = $pdo->prepare("
        INSERT INTO reminders (user_id, title, content, send_email, notify_at)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->execute([$userId, $title, $content, $sendEmail, $notifyAt]);

    echo json_encode(['success' => true, 'message' => '新增成功']);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => '新增失敗']);
}
