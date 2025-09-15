<?php
declare(strict_types=1);

require_once __DIR__ . '/common.php';
require_once __DIR__ . '/connect.php';
?>
<!DOCTYPE html>
<html lang="ka">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>სასწავლო ლექსიკონი</title>
    <link rel="stylesheet" href="../assets/css/dictionary.css">
</head>
<body>
<header class="site-header">
  <div class="header-inner">
    <div class="brand">
      <div class="logo-wrap">
        <img src="resource/img/logo.svg" alt="logo" id="site-logo">
      </div>
      <h1 class="title">სასწავლო ლექსიკონი <span>აღმართი</span></h1>
    </div>

    <form id="searchForm" class="search-form" autocomplete="off" onsubmit="return false;">
      <div class="filters-wrap">

        <!-- Поле поиска (на месте бывшего алфавита) -->
        <input id="form_Search" name="word" type="search" placeholder="სიტყვა" />

        <!-- Три селекта -->
        <div class="filters">
          <select id="form_level" name="level">
            <option value="" selected hidden>დონეები</option>
            <?php foreach ($LEVEL as $id => $label): ?>
              <option value="<?= $id ?>"><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></option>
            <?php endforeach; ?>
          </select>

          <select id="form_part_of_speech" name="part_of_speech">  
            <option value="" selected hidden>მეტყველების ნაწილები</option>
            <?php foreach ($PARTS_OF_SPEECH as $id => $label): ?>
              <option value="<?= $id ?>"><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></option>
            <?php endforeach; ?>
          </select>

          <select id="form_tema" name="tema">
             <option value="" selected hidden>თემატური ჯგუფები</option>
            <?php foreach ($TOPICS as $id => $label): ?>
              <option value="<?= $id ?>"><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
    </form>
  </div>
</header>


<main class="main-content">
    <section id="results" class="results">
        <!-- Список слов будет рендериться сюда (JSON -> DOM) -->
    </section>

    <div id="details-panel">
        <!-- При клике на слово — сюда можно загрузить edit-форму (будем дописывать) -->
    </div>
</main>
<script>
  window.LABELS = <?= json_encode($LABELS, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
</script>
<script src="../assets/js/dictionary.js" type="module"></script>
</body>
</html>
