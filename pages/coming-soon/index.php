<?php
// pages/coming-soon/index.php

// 0) noindex в HTTP (надежно для поисковиков)
if (!headers_sent()) {
    header('X-Robots-Tag: noindex, nofollow', true);
}
$metaNoindex = true;

// 1) язык (если у тебя он в сессии)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'ka';

// 2) безопасно получаем slug
$slug = isset($_GET['slug']) ? $_GET['slug'] : '';
$slug = preg_replace('~[^a-z0-9\-_\/]~i', '', trim($slug));

// 3) корень проекта и мягкое подключение конфигов/БД
$root = dirname(__DIR__, 2); // .../newSaitWork

// твоя связка: config.php + db.php (mysqli + fetchData())
@require_once $root . '/includes/config.php';
@require_once $root . '/includes/db.php';

// 4) заголовок страницы по умолчанию
$pageTitle = ($lang === 'en') ? 'Coming soon' : 'გვერდი მუშავდება';

// 5) если есть fetchData(), подтягиваем заголовок из БД по slug
if ($slug !== '' && function_exists('fetchData')) {
    $rows = fetchData("
        SELECT title_geo, title_en
        FROM geofl_work.menuMain
        WHERE url_slug = ?
        LIMIT 1
    ", array($slug));

    if (!empty($rows[0])) {
        $row = $rows[0];
        $pageTitle = ($lang === 'en')
            ? (!empty($row['title_en']) ? $row['title_en'] : 'Coming soon')
            : (!empty($row['title_geo']) ? $row['title_geo'] : 'გვერდი მუშავდება');
    }
}

// 6) общий header/footer проекта (если есть)
$header = $root . '/includes/header_page.php';
// $footerCandidates = [
//     $root . '/includes/footer.php',
//     $root . '/includes/footer_page.php',
// ];
if (is_file($header)) {
    include $header;
}
?>
<section class="coming-soon">
  <div class="cs-wrap">
    <h1><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></h1>
    <p class="cs-lead">
      <?= ($lang === 'en')
        ? 'This page is under construction and will be available soon.'
        : 'ეს გვერდი ჯერ მზად არ არის. გთხოვთ, შეამოწმეთ მოგვიანებით.' ?>
    </p>

    <?php if ($slug !== ''): ?>
      <p class="cs-eta">
        <?= ($lang === 'en') ? 'Section:' : 'განყოფილება:' ?>
        <strong><?= htmlspecialchars($slug, ENT_QUOTES, 'UTF-8') ?></strong>
      </p>
    <?php endif; ?>

    <a class="cs-btn" href="http://test.geofl.ge/newSaitWork/index.php"> <?= ($lang === 'en') ? 'Go to homepage' : 'მთავარი გვერდი' ?> </a>
  </div>
</section>

<style>
.coming-soon{min-height:60vh;display:flex;align-items:center;justify-content:center}
.coming-soon .cs-wrap{max-width:720px;padding:2rem;text-align:center}
.coming-soon h1{margin:0 0 .5rem 0;font-size:clamp(20px,3vw,32px)}
.coming-soon .cs-lead{opacity:.8;margin-bottom:1.25rem;line-height:1.6}
.cs-btn{display:inline-block;padding:.75rem 1.25rem;border-radius:.75rem;border:1px solid #ddd;text-decoration:none}
</style>
<?php
// foreach ($footerCandidates as $f) {
//     if (is_file($f)) { include $f; break; }
// }
