<?php
// pages/coming-soon/index.php
require_once __DIR__ . '/../../includes/connect.php';
require_once __DIR__ . '/../../includes/language.php'; // где определяется $lang (ka|en), если нужно

$slug = isset($_GET['slug']) ? trim($_GET['slug']) : '';
$slug = preg_replace('~[^a-z0-9\-_\/]~i', '', $slug);

$pageTitle = ($lang ?? 'ka') === 'en' ? 'Coming soon' : 'გვერდი მუშავდება';
$menuRow = null;

if ($slug !== '') {
    $stmt = $pdo->prepare("
        SELECT title_geo, title_en
        FROM geofl_work.menuMain
        WHERE url_slug = :slug
        LIMIT 1
    ");
    $stmt->execute([':slug' => $slug]);
    $menuRow = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($menuRow) {
        $pageTitle = (($lang ?? 'ka') === 'en')
            ? ($menuRow['title_en'] ?: 'Coming soon')
            : ($menuRow['title_geo'] ?: 'გვერდი მუშავდება');
    }
}

// сигнал для <head>, чтобы отдать noindex
$metaNoindex = true;

// если у тебя есть общий header/footer — подключи их.
// иначе оставь как голую секцию.
@include __DIR__ . '/../../includes/header.php';
?>
<section class="coming-soon">
  <div class="cs-wrap">
    <h1><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></h1>
    <p class="cs-lead">
      <?= (($lang ?? 'ka') === 'en')
        ? 'This page is under construction and will be available soon.'
        : 'ეს გვერდი ჯერ მზად არ არის. გთხოვთ, შემოხვიდეთ მოგვიანებით.' ?>
    </p>

    <?php if ($slug !== ''): ?>
      <p class="cs-eta">
        <?= (($lang ?? 'ka') === 'en') ? 'Section:' : 'განყოფილება:' ?>
        <strong><?= htmlspecialchars($slug, ENT_QUOTES, 'UTF-8') ?></strong>
      </p>
    <?php endif; ?>

    <a class="cs-btn" href="/"><?= (($lang ?? 'ka') === 'en') ? 'Go to homepage' : 'მთავარი გვერდი' ?></a>
  </div>
</section>

<style>
.coming-soon{min-height:60vh;display:flex;align-items:center;justify-content:center}
.coming-soon .cs-wrap{max-width:720px;padding:2rem;text-align:center}
.coming-soon h1{margin:0 0 .5rem 0;font-size:clamp(20px,3vw,32px)}
.coming-soon .cs-lead{opacity:.8;margin-bottom:1.25rem;line-height:1.6}
.cs-btn{display:inline-block;padding:.75rem 1.25rem;border-radius:.75rem;border:1px solid #ddd;text-decoration:none}
</style>
<?php @include __DIR__ . '/../../includes/footer.php';
