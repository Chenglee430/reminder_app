<?php
// api/reminder_delete.php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => '未登入']);
    exit;
}

require __DIR__ . '/../config/db.php';

$userId = $_SESSION['user_id'];
$id     = isset($_POST['id']) ? (int)$_POST['id'] : 0;

if ($id <= 0) {
    echo json_encode(['success' => false, 'message' => 'ID 錯誤']);
    exit;
}

try {
    $stmt = $pdo->prepare("
        DELETE FROM reminders
        WHERE id = ? AND user_id = ?
    ");
    $stmt->execute([$id, $userId]);

    echo json_encode(['success' => true, 'message' => '刪除成功']);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => '刪除失敗']);
}
