<?php
session_start();

// 如果已登入 → 導到首頁（提醒事項頁）
if (isset($_SESSION['user_id'])) {
    header("Location: reminders.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>登入｜提醒事項系統</title>
</head>
<body>
    <h2>登入頁面</h2>

    <?php if (isset($_GET['error'])): ?>
        <p style="color:red;"><?php echo htmlspecialchars($_GET['error']); ?></p>
    <?php endif; ?>

    <form action="auth_login.php" method="post">
        <label>Email：</label><br>
        <input type="email" name="email" required><br><br>

        <label>密碼：</label><br>
        <input type="password" name="password" required><br><br>

        <button type="submit">登入</button>
    </form>

    <p>還沒有帳號？ <a href="register.php">前往註冊</a></p>
</body>
</html>
