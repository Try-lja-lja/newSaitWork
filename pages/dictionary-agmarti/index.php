<?php
// Глобальные пути/URL (ASSETS_URL, PAGES_URL и т.п.)
require_once __DIR__ . '/../../includes/config.php';

// Общие константы словаря (LEVEL, PARTS_OF_SPEECH, TOPICS, LABELS и т.д.)
require_once __DIR__ . '/common.php';

// Подключение к БД словаря (создаёт $pdo и функцию pdo())
require_once __DIR__ . '/connect.php';
?>
<!DOCTYPE html>
<html lang="ka">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>სასწავლო ლექსიკონი</title>

  <!-- Общие стили меню + базовые шрифты  -->
  <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>css/main.css">
  <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>css/menu.css">

  <!-- Стили словаря -->
  <link rel="stylesheet" href="<?php echo PAGES_URL; ?>dictionary-agmarti/assets/css/dictionary.css">
</head>
<body>

  <!-- ШАПКА словаря (у словаря своя, отличная от главной) -->
  <header class="site-header">
    <div class="header-inner">
      <div class="brand">
        <div class="logo">
          <img src="<?php echo ASSETS_URL; ?>img/logo.svg" alt="logo" id="site-logo" />
        </div>
        <h1 class="title">ლექსიკონი <span>აღმართი</span></h1>
      </div>

      <!-- ПОИСК (в шапке) -->
      <form id="searchForm" class="search-form" autocomplete="off" onsubmit="return false;">
        <div class="filters-wrap">
          <!-- Поле поиска -->
          <input id="form_Search" name="word" type="search" placeholder="სიტყვა">

          <!-- Три селекта -->
          <div class="filters">
            <select id="form_level" name="level">
              <option value="" selected hidden>დონეები</option>
              <?php foreach ($LEVEL as $id => $label): ?>
                <option value="<?php echo (int)$id; ?>"><?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?></option>
              <?php endforeach; ?>
            </select>

            <select id="form_part_of_speech" name="part_of_speech">
              <option value="" selected hidden>მეტყველების ნაწილები</option>
              <?php foreach ($PARTS_OF_SPEECH as $id => $label): ?>
                <option value="<?php echo (int)$id; ?>"><?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?></option>
              <?php endforeach; ?>
            </select>

            <select id="form_tema" name="tema">
              <option value="" selected hidden>თემატური ჯგუფები</option>
              <?php foreach ($TOPICS as $id => $label): ?>
                <option value="<?php echo (int)$id; ?>"><?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
      </form>
    </div>
  </header>

  <!-- ОСНОВНОЙ FLEX-КОНТЕЙНЕР: слева меню, справа контент -->
  <div class="main-content">

    <!-- ЛЕВОЕ МЕНЮ (ВАЖНО: внутри .main-content) -->
    <?php
      $depth      = 2;
      $parentId   = null; // корневой уровень
      include __DIR__ . '/../../includes/menu.php';
    ?>

    <!-- ПРАВАЯ КОЛОНКА -->
    <div class="content-block">
      <section id="results" class="results"></section>
      <div id="details-panel"></div>
    </div>
  </div>

  <!-- Затемнение фона для offcanvas -->
  <div class="offcanvasOverlay" id="offcanvasOverlay"></div>

  <!-- Off-canvas меню (на мобилке) -->
  <div id="offcanvasMenu" class="offcanvas-menu" aria-hidden="true">
    <nav class="offcanvas-nav" role="navigation">
      <?php
        // единственный рендер offcanvas-меню
        require_once __DIR__ . '/../../includes/menuData.php';
        require_once __DIR__ . '/../../includes/buildMenu.php';
        echo buildMenu($menuItems, 2, null);
      ?>
    </nav>
  </div>

  <script>
    window.LABELS = <?php echo json_encode($LABELS, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;
  </script>

  <!-- Скрипты -->
  <script src="<?php echo ASSETS_URL; ?>js/menu.js" defer></script>
  <script src="<?php echo PAGES_URL; ?>dictionary-agmarti/assets/js/dictionary.js"></script>
</body>
</html>
