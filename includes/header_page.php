<head>
  <meta charset="UTF-8">
  <title><?php echo htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <?php if (!empty($metaNoindex)): ?>
    <meta name="robots" content="noindex, nofollow">
  <?php endif; ?>
</head>

<?php
// includes/header_page.php
// Общая шапка для внутренних страниц (без формы поиска).
// ОЖИДАЕТ: $pageTitle (строка). Если не задано — пусто.

if (!isset($pageTitle)) { $pageTitle = ''; }
?>
<header class="site-header">
  <div class="header-inner">
    <div class="brand">
      <div class="logo-wrap">
        <img src="<?php echo ASSETS_URL; ?>img/logo.svg" alt="logo" id="site-logo">
      </div>
      <h1 class="title"><?php echo htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?></h1>
    </div>

    <!-- Хамбургер для off-canvas на мобиле (визуально скрыт на десктопе) -->
    <button class="hamburger" id="hamburgerBtn" aria-label="Open menu" type="button">
      <span></span><span></span><span></span>
    </button>
  </div>
</header>
