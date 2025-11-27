# reminder_app
🌟 Reminder System — Email Notification Web App

一個具備 使用者系統、提醒事項 CRUD、Email 寄送、自動排程、外網存取 的完整 Web 應用程式。
後端使用 PHP + MariaDB，Email 使用 PHPMailer (Gmail SMTP)，排程使用 Windows Task Scheduler。

📌 功能特色 (Features)
🔐 1. 使用者帳號系統

註冊 / 登入 / 登出

密碼加密（password_hash）

Session 管理

📝 2. 提醒事項 CRUD

➕ 新增提醒（reminder_create.php）

✏️ 修改提醒（reminder_update.php）

🗑️ 刪除提醒（reminder_delete.php）

✔ 標記完成（reminder_complete.php）

📄 查看所有提醒（reminders_list.php）

所有資料都儲存在 reminders 資料表中。

✉️ 3. Email 通知（PHPMailer）

Gmail SMTP 寄信

支援 App Password

每次寄信會寫入 mail_logs

失敗也會記錄原因（方便除錯）

⏰ 4. 自動寄信排程（Windows Scheduler）

使用 send_due_reminders.php

利用 .bat 檔讓 Windows 每 5 分鐘自動執行

無需人工觸發

🌍 5. 可外網存取（Ngrok）

一鍵讓整個網站在外網使用

支援完整功能（CRUD + Login + Email）

🧱 6. 完整分離式架構

db.php（資料庫連線集中管理）

PHPMailer/ 原始碼完整保留

程式碼模組化可維護度高

📂 專案結構 (Project Structure)
reminder_app/
│
├── index.php
├── login.php
├── register.php
├── logout.php
│
├── auth_login.php
├── auth_register.php
│
├── reminders_list.php
├── reminder_create.php
├── reminder_update.php
├── reminder_delete.php
├── reminder_complete.php
│
├── send_due_reminders.php
├── run_send_due_reminders.bat
│
├── db.php                 # 資料庫連線模組
│
├── PHPMailer/             # 郵件寄送套件
  ├── PHPMailer.php
  ├── SMTP.php
  └── Exception.php


🗄️ 資料庫結構 (Database Structure)

| 欄位         | 型態       |
| ---------- | -------- |
| id         | INT, PK  |
| email      | VARCHAR  |
| password   | VARCHAR  |
| created_at | DATETIME |

| 欄位         | 型態       |
| ---------- | -------- |
| id         | INT, PK  |
| user_id    | INT      |
| title      | VARCHAR  |
| content    | TEXT     |
| notify_at  | DATETIME |
| is_email   | TINYINT  |
| completed  | TINYINT  |
| created_at | DATETIME |

| 欄位          | 型態       |
| ----------- | -------- |
| id          | INT, PK  |
| reminder_id | INT      |
| email       | VARCHAR  |
| status      | VARCHAR  |
| message     | TEXT     |
| sent_at     | DATETIME |


作者：李承勳 (Li Cheng-Syun)

專案名稱：reminder_app
