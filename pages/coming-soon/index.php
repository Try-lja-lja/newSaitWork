<?php
// pages/coming-soon/index.php

// 1) Не индексировать
if (!headers_sent()) {
    header('X-Robots-Tag: noindex, nofollow', true);
}
$metaNoindex = true;

// 2) Текущий язык (если у тебя глобально он в сессии/константе — подстрой)
$lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'ka';

// 3) Получаем slug из query
$slug = isset($_GET['slug']) ? trim($_GET['slug']) : '';
$slug = preg_replace('~[^a-z0-9\-_\/]~i', '', $slug);

// 4) Пытаемся мягко подключить БД (без фатала, если файл не найден)
$root = dirname(__DIR__, 2); // .../newSaitWork
$pdo = null;
$tryFiles = [
    $root . '/includes/connect.php',
    $root . '/connect.php',
    $root . '/includes/db.php',
];
foreach ($tryFiles as $f) {
    if (is_file($f)) {
        include_once $f;
        break;
    }
}
// теперь если в инклюженом файле был создан $pdo (PDO) — используем. Если нет — просто без БД.

// 5) Заголовок страницы
$pageTitle = ($lang === 'en') ? 'Coming soon' : 'გვერდი მუშავდება';

if ($slug !== '' && $pdo instanceof PDO) {
    $stmt = $pdo->prepare("
        SELECT title_geo, title_en
        FROM geofl_work.menuMain
        WHERE url_slug = :slug
        LIMIT 1
    ");
    if ($stmt->execute([':slug' => $slug])) {
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $pageTitle = ($lang === 'en')
                ? (!empty($row['title_en']) ? $row['title_en'] : 'Coming soon')
                : (!empty($row['title_geo']) ? $row['title_geo'] : 'გვერდი მუშავდება');
        }
    }
}

// 6) Подключаем общий хедер/футер (если есть)
$header = $root . '/includes/header_page.php';
$footer = $root . '/includes/footer.php'; // если у тебя иначе — поправь путь

if (is_file($header)) include $header;
?>
<section class="coming-soon">
  <div class="cs-wrap">
    <h1><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></h1>
    <p class="cs-lead">
      <?= ($lang === 'en')
        ? 'This page is under construction and will be available soon.'
        : 'ეს გვერდი ჯერ მზად არ არის. გთხოვთ, შემოხვიდეთ მოგვიანებით.' ?>
    </p>
    <?php if ($slug !== ''): ?>
      <p class="cs-eta">
        <?= ($lang === 'en') ? 'Section:' : 'განყოფილება:' ?>
        <strong><?= htmlspecialchars($slug, ENT_QUOTES, 'UTF-8') ?></strong>
      </p>
    <?php endif; ?>
    <a class="cs-btn" href="/"> <?= ($lang === 'en') ? 'Go to homepage' : 'მთავარი გვერდი' ?> </a>
  </div>
</section>

<style>
.coming-soon{min-height:60vh;display:flex;align-items:center;justify-content:center}
.coming-soon .cs-wrap{max-width:720px;padding:2rem;text-align:center}
.coming-soon h1{margin:0 0 .5rem 0;font-size:clamp(20px,3vw,32px)}
.coming-soon .cs-lead{opacity:.8;margin-bottom:1.25rem;line-height:1.6}
.cs-btn{display:inline-block;padding:.75rem 1.25rem;border-radius:.75rem;border:1px solid #ddd;text-decoration:none}
</style>
<?php if (is_file($footer)) include $footer; ?>
