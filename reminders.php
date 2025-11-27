<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$email = $_SESSION['email'] ?? '';
?>
<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>提醒事項系統</title>

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- jQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">提醒事項系統</a>
    <div class="d-flex">
      <span class="navbar-text text-white me-3">
        Hello, <?php echo htmlspecialchars($email); ?>
      </span>
      <a href="logout.php" class="btn btn-outline-light btn-sm">登出</a>
    </div>
  </div>
</nav>

<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <button id="btnShowUnfinished" class="btn btn-primary btn-sm me-2">未完成提醒事項</button>
            <button id="btnShowFinished" class="btn btn-secondary btn-sm">已完成提醒事項</button>
        </div>
        <button id="btnNewReminder" class="btn btn-success btn-sm">新增提醒事項</button>
    </div>

    <div class="card">
        <div class="card-header">
            <span id="listTitle">未完成提醒事項</span>
        </div>
        <div class="card-body p-0">
            <table class="table mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 30%;">主題</th>
                        <th style="width: 35%;">內容</th>
                        <th style="width: 15%;">建立時間</th>
                        <th style="width: 20%;">操作</th>
                    </tr>
                </thead>
                <tbody id="reminderTableBody">
                    <!-- JS 動態插入 -->
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- 新增/編輯 Modal -->
<div class="modal fade" id="reminderModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="reminderForm" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="reminderModalLabel">新增提醒事項</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- 隱藏欄位：id，用來判斷是新增還是編輯 -->
        <input type="hidden" id="reminderId" name="id">

        <div class="mb-3">
            <label for="title" class="form-label">主題</label>
            <input type="text" id="title" name="title" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="content" class="form-label">內容</label>
            <textarea id="content" name="content" class="form-control" rows="3"></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label d-block">是否寄送 email 提醒（進階功能預留）</label>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="send_email" id="send_email_no" value="0" checked>
              <label class="form-check-label" for="send_email_no">否</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="send_email" id="send_email_yes" value="1">
              <label class="form-check-label" for="send_email_yes">是</label>
            </div>
        </div>

        <div class="mb-3">
            <label for="notify_at" class="form-label">寄信提醒時間（若有勾選「是」才會生效）</label>
            <input type="datetime-local" id="notify_at" name="notify_at" class="form-control">
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
        <button type="submit" class="btn btn-primary">儲存</button>
      </div>
    </form>
  </div>
</div>

<script>
let currentStatus = 0; // 0 未完成, 1 已完成
let remindersCache = []; // 暫存目前列表，給「編輯」使用

function loadReminders() {
    $.ajax({
        url: 'api/reminders_list.php',
        method: 'POST',
        dataType: 'json',
        data: { status: currentStatus },
        success: function (res) {
            if (!res.success) {
                alert(res.message || '讀取失敗');
                return;
            }

            remindersCache = res.data || [];
            const tbody = $('#reminderTableBody');
            tbody.empty();

            if (remindersCache.length === 0) {
                tbody.append('<tr><td colspan="4" class="text-center text-muted py-3">目前沒有提醒事項</td></tr>');
                return;
            }

            remindersCache.forEach(function (item) {
                const tr = $('<tr></tr>');
                tr.append('<td>' + htmlspecialchars(item.title) + '</td>');
                tr.append('<td>' + htmlspecialchars(item.content || "") + '</td>');
                tr.append('<td>' + htmlspecialchars(item.created_at || "") + '</td>');

                let btns = '';

                if (currentStatus === 0) {
                    // 未完成：可以 編輯 / 刪除 / 已完成
                    btns += '<button class="btn btn-sm btn-warning me-1 btn-edit" data-id="' + item.id + '">編輯</button>';
                    btns += '<button class="btn btn-sm btn-danger me-1 btn-delete" data-id="' + item.id + '">刪除</button>';
                    btns += '<button class="btn btn-sm btn-success btn-complete" data-id="' + item.id + '">已完成</button>';
                } else {
                    // 已完成：只提供刪除
                    btns += '<button class="btn btn-sm btn-danger btn-delete" data-id="' + item.id + '">刪除</button>';
                }

                tr.append('<td>' + btns + '</td>');
                tbody.append(tr);
            });
        },
        error: function () {
            alert('無法讀取提醒事項（可能未登入或伺服器錯誤）');
        }
    });
}

function htmlspecialchars(str) {
    if (!str) return '';
    return $('<div>').text(str).html();
}

$(function () {
    // 初始載入
    loadReminders();

    // 切換 未完成／已完成
    $('#btnShowUnfinished').on('click', function () {
        currentStatus = 0;
        $('#listTitle').text('未完成提醒事項');
        $('#btnShowUnfinished').removeClass('btn-secondary').addClass('btn-primary');
        $('#btnShowFinished').removeClass('btn-primary').addClass('btn-secondary');
        loadReminders();
    });

    $('#btnShowFinished').on('click', function () {
        currentStatus = 1;
        $('#listTitle').text('已完成提醒事項');
        $('#btnShowFinished').removeClass('btn-secondary').addClass('btn-primary');
        $('#btnShowUnfinished').removeClass('btn-primary').addClass('btn-secondary');
        loadReminders();
    });

    // 新增按鈕：清空表單、打開 Modal
    $('#btnNewReminder').on('click', function () {
        $('#reminderModalLabel').text('新增提醒事項');
        $('#reminderId').val('');
        $('#title').val('');
        $('#content').val('');
        $('#send_email_no').prop('checked', true);
        $('#notify_at').val('');
        const modal = new bootstrap.Modal(document.getElementById('reminderModal'));
        modal.show();
    });

    // 表單送出：依照有沒有 id 決定是新增還是更新
    $('#reminderForm').on('submit', function (e) {
        e.preventDefault();

        const id         = $('#reminderId').val();
        const title      = $('#title').val().trim();
        const content    = $('#content').val().trim();
        const send_email = $('input[name="send_email"]:checked').val();
        const notify_at  = $('#notify_at').val();

        if (!title) {
            alert('主題不得為空');
            return;
        }

        const url = id ? 'api/reminder_update.php' : 'api/reminder_create.php';

        $.ajax({
            url: url,
            method: 'POST',
            dataType: 'json',
            data: {
                id: id,
                title: title,
                content: content,
                send_email: send_email,
                notify_at: notify_at
            },
            success: function (res) {
                if (!res.success) {
                    alert(res.message || '儲存失敗');
                    return;
                }
                // 關閉 modal
                const modalEl = document.getElementById('reminderModal');
                const modal = bootstrap.Modal.getInstance(modalEl);
                modal.hide();

                loadReminders();
            },
            error: function () {
                alert('儲存時發生錯誤');
            }
        });
    });

    // 事件代理：編輯 / 刪除 / 已完成
    $('#reminderTableBody').on('click', '.btn-edit', function () {
        const id = $(this).data('id');
        const item = remindersCache.find(r => parseInt(r.id) === parseInt(id));
        if (!item) return;

        $('#reminderModalLabel').text('編輯提醒事項');
        $('#reminderId').val(item.id);
        $('#title').val(item.title);
        $('#content').val(item.content || '');

        if (item.send_email == 1) {
            $('#send_email_yes').prop('checked', true);
        } else {
            $('#send_email_no').prop('checked', true);
        }

        // 把資料庫的 datetime 轉成 datetime-local 格式（簡單處理：只取前 16 字元）
        if (item.notify_at) {
            $('#notify_at').val(item.notify_at.replace(' ', 'T').substring(0, 16));
        } else {
            $('#notify_at').val('');
        }

        const modal = new bootstrap.Modal(document.getElementById('reminderModal'));
        modal.show();
    });

    $('#reminderTableBody').on('click', '.btn-delete', function () {
        const id = $(this).data('id');
        if (!confirm('確定要刪除這筆提醒事項嗎？')) return;

        $.ajax({
            url: 'api/reminder_delete.php',
            method: 'POST',
            dataType: 'json',
            data: { id: id },
            success: function (res) {
                if (!res.success) {
                    alert(res.message || '刪除失敗');
                    return;
                }
                loadReminders();
            },
            error: function () {
                alert('刪除時發生錯誤');
            }
        });
    });

    $('#reminderTableBody').on('click', '.btn-complete', function () {
        const id = $(this).data('id');
        $.ajax({
            url: 'api/reminder_complete.php',
            method: 'POST',
            dataType: 'json',
            data: { id: id },
            success: function (res) {
                if (!res.success) {
                    alert(res.message || '更新失敗');
                    return;
                }
                loadReminders();
            },
            error: function () {
                alert('更新狀態時發生錯誤');
            }
        });
    });

});
</script>

</body>
</html>
