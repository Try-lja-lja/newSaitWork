<?php
// pages/resources-adults/index.php
if (session_status() === PHP_SESSION_NONE) {
    @session_start();
}

// Нужны константы PAGES_URL, чтобы вычислить базовые URL
require_once __DIR__ . '/../../includes/config.php';

// Вычислим базовые URL
$__PAGES_URL = rtrim(PAGES_URL, '/');                 // /newSaitWork/pages
$ROOT_URL    = rtrim(dirname($__PAGES_URL), '/');     // /newSaitWork
$ASSETS_URL  = $ROOT_URL . '/assets';

// <title> для вкладки — опционально
$pageTitle = 'მოზარდებისა და ზრდასრულებისთვის';

require_once __DIR__ . '/../../includes/header_page.php';
?>
<!-- Хлебные крошки: заполняет main.js -->
<nav class="breadcrumbs" data-breadcrumbs aria-label="Breadcrumb"></nav>
<div class="main-content">
  
  <!-- ЛЕВОЕ МЕНЮ (как в dictionary) -->
  <?php
    // Ветка взрослых: parentId = 7, глубина 2 уровня (3-й и 4-й)
    $depth    = 2;
    $parentId = 7;
    include __DIR__ . '/../../includes/menu.php';
  ?>

  <!-- ПРАВАЯ ПАНЕЛЬ: контент -->
  <div class="content-block">
    <p class="title" style="margin-bottom: 20px; text-align: right;">საანბანო ნაწილი</p>
    <!-- Контейнер «букв»; JS сам построит сетку и сцену -->
    <div id="lettersApp"></div>
  </div>
</div>

<!-- Страничные стили и скрипты -->
<script src="<?php echo ASSETS_URL; ?>js/main.js" defer></script>
<link rel="stylesheet" href="<?php echo $ROOT_URL; ?>/pages/resources-adults/assets/css/resources-adults.css">
<script src="<?php echo $ROOT_URL; ?>/pages/resources-adults/assets/js/resources-adults.js" defer></script>
