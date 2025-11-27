<?php
session_start();

// 若已登入 → 導到提醒頁
if (isset($_SESSION['user_id'])) {
    header("Location: reminders.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>註冊｜提醒事項系統</title>
</head>
<body>
    <h2>註冊帳號</h2>

    <?php if (isset($_GET['error'])): ?>
        <p style="color:red;"><?php echo htmlspecialchars($_GET['error']); ?></p>
    <?php endif; ?>

    <form action="auth_register.php" method="post">
        <label>Email：</label><br>
        <input type="email" name="email" required><br><br>

        <label>密碼：</label><br>
        <input type="password" name="password" required><br><br>

        <button type="submit">註冊</button>
    </form>

    <p>已經有帳號？ <a href="login.php">前往登入</a></p>

</body>
</html>
