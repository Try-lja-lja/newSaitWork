<?php
// includes/header_page.php

// Стартуем сессию (если ещё не стартована), чтобы прочитать язык.
if (session_status() === PHP_SESSION_NONE) {
    @session_start();
}

// Заголовок страницы по умолчанию
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

  <!-- Подключения стилей/иконок размещай здесь при необходимости -->
  <!-- <link rel="stylesheet" href="/assets/css/main.css"> -->
</head>
<body>
