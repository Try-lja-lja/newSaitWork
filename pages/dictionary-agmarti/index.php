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
<header class="site-header">
  <div class="header-inner">
    <div class="brand">
      <div class="logo-wrap">
        <img src="<?php echo PAGES_URL; ?>dictionary-agmarti/assets/img/logo.svg" alt="logo" id="site-logo">
      </div>
      <h1 class="title">სასწავლო ლექსიკონი <span>აღმართი</span></h1>
    </div>

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

<?php
  // Левый блок меню (тот же компонент, что и на главной)
  $startLevel = 1;
  $depth      = 2;
  include __DIR__ . '/../../includes/menu.php';
?>

<main class="main-content">
  <section id="results" class="results">
    <!-- Список слов будет рендериться сюда (JSON -> DOM) -->
  </section>

  <div id="details-panel">
    <!-- При клике на слово — сюда подгружаются детали/форма -->
  </div>
</main>

<script>
  // Константы-лейблы для фронта
  window.LABELS = <?php echo json_encode($LABELS, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;
</script>

  <!-- Скрипты меню + логика словаря -->
  <script src="<?php echo ASSETS_URL; ?>js/menu.js" defer></script>
  <script src="<?php echo PAGES_URL; ?>dictionary-agmarti/assets/js/dictionary.js"></script>

</body>
</html>
