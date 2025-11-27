<?php
date_default_timezone_set('Asia/Taipei');
ini_set('display_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/config/db.php';

require __DIR__ . '/libs/PHPMailer/PHPMailer.php';
require __DIR__ . '/libs/PHPMailer/SMTP.php';
require __DIR__ . '/libs/PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// ===== SMTP 設定 =====
$SMTP_HOST = 'smtp.gmail.com';     // SMTP 主機
$SMTP_PORT = 587;                  // Port (Gmail 用 587)
$SMTP_USER = 'wf60501@gmail.com';    // 你的寄件者 Email
$SMTP_PASS = 'koveenkzjwfsfcej';  // Gmail 應用程式密碼
$FROM_EMAIL = 'wf60501@gmail.com';   // 寄件者 Email
$FROM_NAME  = '提醒事項系統';            // 寄件顯示名稱

// 初始化 PHPMailer
$mail = new PHPMailer(true);
$mail->CharSet  = 'UTF-8';
$mail->Encoding = 'base64';

// 查詢所有應該寄信的提醒
$sql = "SELECT 
            r.id,
            r.user_id,
            r.title,
            r.content,
            r.notify_at,
            u.email
        FROM reminders r
        JOIN users u ON r.user_id = u.id
        WHERE r.send_email = 1
          AND r.completed = 0
          AND r.notify_at <= NOW()";


$stmt = $pdo->prepare($sql);
$stmt->execute();
$reminders = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($reminders as $row) {
    $result    = 'SUCCESS';
    $errorMsg  = null;

    try {
        // --- SMTP / 信件設定 ---
        $mail->isSMTP();
        $mail->Host       = $SMTP_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = $SMTP_USER;
        $mail->Password   = $SMTP_PASS;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = $SMTP_PORT;

        $mail->setFrom($FROM_EMAIL, $FROM_NAME);
        $mail->addAddress($row['email']);

        $mail->isHTML(false);
        $mail->Subject = "提醒：{$row['title']}";
        $mail->Body    = $row['content'];

        $mail->send();
        echo "[成功] 提醒 ID {$row['id']} 已寄給 {$row['email']}" . PHP_EOL;

    } catch (Exception $e) {
        $result   = 'FAIL';
        $errorMsg = $mail->ErrorInfo;
        echo "[失敗] 無法寄給 {$row['email']}, 錯誤: {$errorMsg}" . PHP_EOL;
    }

    // --- 寫入 mail_logs ---
    $logStmt = $pdo->prepare("
        INSERT INTO mail_logs
            (reminder_id, user_id, subject, body, result, error_message, sent_at)
        VALUES
            (:reminder_id, :user_id, :subject, :body, :result, :error_message, NOW())
    ");

    $logStmt->execute([
        ':reminder_id'   => $row['id'],
        ':user_id'       => $row['user_id'],
        ':subject'       => "提醒：{$row['title']}",
        ':body'          => $row['content'],
        ':result'        => $result,          // SUCCESS / FAIL
        ':error_message' => $errorMsg,        // 失敗才有錯誤訊息
    ]);

    $mail->clearAddresses();
}

echo "寄信腳本執行完成。" . PHP_EOL;

