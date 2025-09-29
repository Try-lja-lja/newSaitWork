<?php
// includes/header_page.php

if (session_status() === PHP_SESSION_NONE) {
    @session_start();
}

// Нужно для константы PAGES_URL
if (!defined('PAGES_URL')) {
    require_once __DIR__ . '/config.php';
}

// Вычислим базовый префикс проекта: из /newSaitWork/pages -> /newSaitWork
$__PAGES_URL = rtrim(PAGES_URL, '/');                  // напр. /newSaitWork/pages
$ROOT_URL    = rtrim(dirname($__PAGES_URL), '/');      // -> /newSaitWork
$ASSETS_URL  = $ROOT_URL . '/assets';

// Заголовок <title> по умолчанию
if (!isset($pageTitle)) {
    $pageTitle = '';
}

// Язык интерфейса (ka/en), по умолчанию — ka
$lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'ka';
$htmlLang = ($lang === 'en') ? 'en' : 'ka';
?>
<!DOCTYPE html>
<html lang="<?php echo $htmlLang; ?>">
<head>
  <meta charset="UTF-8">
  <title><?php echo htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <?php if (!empty($metaNoindex)): ?>
    <meta name="robots" content="noindex, nofollow">
  <?php endif; ?>

  <!-- Глобальные стили -->
  <link rel="stylesheet" href="<?php echo $ASSETS_URL; ?>/css/main.css">
  <link rel="stylesheet" href="<?php echo $ASSETS_URL; ?>/css/menu.css">
</head>
<body>

<!-- ЕДИНАЯ ШАПКА ДЛЯ ВНУТРЕННИХ СТРАНИЦ -->
<header class="site-header" role="banner">
  <div class="header-inner">
    <div class="logo">
      <a href="<?php echo $ROOT_URL; ?>/" aria-label="Home">
        <img src="<?php echo $ASSETS_URL; ?>/img/logo.svg" alt="Logo">
      </a>
    </div>

    <h1 class="boxoFont" data-page-header></h1>
  </div>
  <div class="header__underline" aria-hidden="true"></div>
</header>

<!-- Глобальные скрипты (ядро и меню) -->
<script src="<?php echo $ASSETS_URL; ?>/js/main.js" defer></script>
<script src="<?php echo $ASSETS_URL; ?>/js/menu.js" defer></script>
