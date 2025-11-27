<?php
// api/reminders_list.php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => '未登入']);
    exit;
}

require __DIR__ . '/../config/db.php';

$userId = $_SESSION['user_id'];

// status: 0 = 未完成, 1 = 已完成
$status = isset($_POST['status']) ? (int)$_POST['status'] : 0;
$status = ($status === 1) ? 1 : 0;

try {
    $stmt = $pdo->prepare("
        SELECT id, title, content, is_completed, send_email, notify_at,
               created_at, updated_at, completed_at
        FROM reminders
        WHERE user_id = ? AND is_completed = ?
        ORDER BY created_at DESC
    ");
    $stmt->execute([$userId, $status]);
    $rows = $stmt->fetchAll();

    echo json_encode([
        'success' => true,
        'data'    => $rows
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => '讀取失敗'
    ]);
}
