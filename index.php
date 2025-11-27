<?php
session_start();
$loggedIn = isset($_SESSION['user_id']);
$email = $_SESSION['email'] ?? '';
?>
<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>JS 效果展示｜提醒事項系統</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        body {
            background-color: #f5f5f5;
        }
        .banner-carousel img {
            object-fit: cover;
            height: 360px;
        }
        .multi-carousel-wrapper {
            position: relative;
            overflow: hidden;
        }
        .multi-carousel-track {
            display: flex;
            transition: transform 0.5s ease;
        }
        .multi-card {
            min-width: 200px;
            margin-right: 16px;
        }
        .multi-card img {
            height: 200px;
            object-fit: cover;
        }
        /* 滑動淡入效果 */
        .reveal {
            opacity: 0;
            transform: translateY(40px);
            transition: all 0.6s ease-out;
        }
        .reveal-visible {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>
<body>

<!-- 導覽列 -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">JS 效果展示</a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
            aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav">
        <?php if ($loggedIn): ?>
            <li class="nav-item">
              <a class="nav-link" href="reminders.php">我的提醒事項</a>
            </li>
            <li class="nav-item">
              <span class="navbar-text text-white me-2">
                <?php echo htmlspecialchars($email); ?>
              </span>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="logout.php">登出</a>
            </li>
        <?php else: ?>
            <li class="nav-item">
              <a class="nav-link" href="login.php">登入提醒系統</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="register.php">註冊</a>
            </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<!-- Banner 輪播 -->
<section class="container mt-4 banner-carousel reveal">
    <div id="bannerCarousel" class="carousel slide" data-bs-ride="carousel">
      <div class="carousel-inner">
        <!-- 你可以把 src 換成自己專案的圖片路徑 -->
        <div class="carousel-item active">
          <img src="https://picsum.photos/id/1018/1200/360" class="d-block w-100" alt="banner1">
          <div class="carousel-caption d-none d-md-block">
            <h5>歡迎使用提醒事項系統</h5>
            <p>管理你的每日代辦與重要事項。</p>
          </div>
        </div>
        <div class="carousel-item">
          <img src="https://picsum.photos/id/1025/1200/360" class="d-block w-100" alt="banner2">
          <div class="carousel-caption d-none d-md-block">
            <h5> Banner 輪播效果 </h5>
            <p>可自動輪播，也能手動切換。</p>
          </div>
        </div>
        <div class="carousel-item">
          <img src="https://picsum.photos/id/1039/1200/360" class="d-block w-100" alt="banner3">
          <div class="carousel-caption d-none d-md-block">
            <h5>支援多終端</h5>
            <p>桌機、筆電、平板、手機皆可瀏覽。</p>
          </div>
        </div>
      </div>

      <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">上一張</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">下一張</span>
      </button>
    </div>
</section>

<!-- 多元件輪播 -->
<section class="container mt-5 reveal">
    <h3 class="mb-3">多元件輪播（範例：電影卡片）</h3>

    <div class="multi-carousel-wrapper bg-white p-3 rounded shadow-sm">
        <div class="multi-carousel-track" id="multiTrack">
            <!-- 8~12 個卡片，每個有圖片 + 標題 -->
            <?php
            $movies = [
                ['title' => '電影 1', 'img' => 'https://picsum.photos/id/100/300/200'],
                ['title' => '電影 2', 'img' => 'https://picsum.photos/id/101/300/200'],
                ['title' => '電影 3', 'img' => 'https://picsum.photos/id/102/300/200'],
                ['title' => '電影 4', 'img' => 'https://picsum.photos/id/103/300/200'],
                ['title' => '電影 5', 'img' => 'https://picsum.photos/id/104/300/200'],
                ['title' => '電影 6', 'img' => 'https://picsum.photos/id/110/300/200'],
                ['title' => '電影 7', 'img' => 'https://picsum.photos/id/106/300/200'],
                ['title' => '電影 8', 'img' => 'https://picsum.photos/id/107/300/200'],
                ['title' => '電影 9', 'img' => 'https://picsum.photos/id/108/300/200'],
                ['title' => '電影 10', 'img' => 'https://picsum.photos/id/109/300/200'],
            ];
            foreach ($movies as $m): ?>
                <div class="card multi-card">
                    <img src="<?php echo htmlspecialchars($m['img']); ?>" class="card-img-top" alt="">
                    <div class="card-body">
                        <h6 class="card-title"><?php echo htmlspecialchars($m['title']); ?></h6>
                        <p class="card-text small text-muted">範例多元件輪播卡片，可替換為商品或其他內容。</p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- 左右切換按鈕 -->
        <button id="multiPrev" class="btn btn-outline-secondary btn-sm"
                style="position:absolute; top:50%; left:5px; transform:translateY(-50%);">
            ‹
        </button>
        <button id="multiNext" class="btn btn-outline-secondary btn-sm"
                style="position:absolute; top:50%; right:5px; transform:translateY(-50%);">
            ›
        </button>
    </div>
</section>

<!-- 滑動淡入的內容區塊 -->
<section class="container mt-5 mb-5">
    <div class="row g-4">
        <div class="col-md-4 reveal">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">滑動淡入效果 1</h5>
                    <p class="card-text">往下滾動時，此區塊會以淡入方式出現，對應 PDF 的「滑動後出現」JS 進階功能。</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 reveal">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">滑動淡入效果 2</h5>
                    <p class="card-text">提醒系統。</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 reveal">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">滑動淡入效果 3</h5>
                    <p class="card-text"></p>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
// 多元件輪播：簡單水平滑動 + 自動輪播
$(function () {
    const $track = $('#multiTrack');
    const $cards = $track.find('.multi-card');
    const cardWidth = $cards.outerWidth(true); // 含 margin
    const visibleCount = 4; // 同時顯示幾張，可依版面調整
    const maxIndex = $cards.length - visibleCount;
    let currentIndex = 0;
    let autoTimer = null;

    function updateMultiCarousel() {
        const offset = -currentIndex * cardWidth;
        $track.css('transform', 'translateX(' + offset + 'px)');
    }

    function startAuto() {
        autoTimer = setInterval(function () {
            currentIndex = (currentIndex >= maxIndex) ? 0 : currentIndex + 1;
            updateMultiCarousel();
        }, 2500);
    }

    function stopAuto() {
        if (autoTimer) {
            clearInterval(autoTimer);
            autoTimer = null;
        }
    }

    $('#multiPrev').on('click', function () {
        stopAuto();
        currentIndex = (currentIndex <= 0) ? maxIndex : currentIndex - 1;
        updateMultiCarousel();
        startAuto();
    });

    $('#multiNext').on('click', function () {
        stopAuto();
        currentIndex = (currentIndex >= maxIndex) ? 0 : currentIndex + 1;
        updateMultiCarousel();
        startAuto();
    });

    startAuto();

    // 滑動淡入：IntersectionObserver
    const reveals = document.querySelectorAll('.reveal');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('reveal-visible');
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1
    });

    reveals.forEach(el => observer.observe(el));
});
</script>

</body>
</html>
