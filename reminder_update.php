<?php
// api/reminder_update.php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => '未登入']);
    exit;
}

require __DIR__ . '/../config/db.php';

$userId     = $_SESSION['user_id'];
$id         = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$title      = trim($_POST['title'] ?? '');
$content    = trim($_POST['content'] ?? '');
$sendEmail  = isset($_POST['send_email']) ? (int)$_POST['send_email'] : 0;
$notifyAt   = $_POST['notify_at'] ?? null;

if ($id <= 0) {
    echo json_encode(['success' => false, 'message' => 'ID 錯誤']);
    exit;
}
if ($title === '') {
    echo json_encode(['success' => false, 'message' => '標題不得為空']);
    exit;
}

if ($sendEmail !== 1) {
    $sendEmail = 0;
    $notifyAt  = null;
}

try {
    // 限制一定是自己的資料
    $stmt = $pdo->prepare("
        UPDATE reminders
        SET title = ?, content = ?, send_email = ?, notify_at = ?
        WHERE id = ? AND user_id = ?
    ");
    $stmt->execute([$title, $content, $sendEmail, $notifyAt, $id, $userId]);

    echo json_encode(['success' => true, 'message' => '更新成功']);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => '更新失敗']);
}
