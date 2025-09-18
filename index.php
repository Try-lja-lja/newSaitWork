<?php
session_start();

// Язык
$lang = 'ka';
if (isset($_GET['lang'])) {
    $lang = $_GET['lang'];
} elseif (isset($_SESSION['lang'])) {
    $lang = $_SESSION['lang'];
}
$_SESSION['lang'] = $lang;

// Подключаем данные
$labels = include "lang/$lang.php";
$texts = include "data/texts.php";
$slides = include "data/slides.php";
?>

<div class="wrapper">

  <?php include 'includes/header_main.php'; ?>

  <!-- Слайдер -->
  <div class="slider" id="mainSlider" data-slides='<?php echo json_encode($slides); ?>'>
    <img src="assets/img/slider/<?php echo $slides[0]; ?>" id="sliderImage" alt="Slider">
  </div>

  <!-- Основной блок с меню и WHY -->
  <div class="main-content">

    <!-- Левое меню -->
<?php $startLevel = 1; $depth = 2; include __DIR__ . '/includes/menu.php'; ?>



    <!-- WHY GEORGIAN -->
    <div class="why-block" id="whyTextBlock">
      <h2 class="whyGeo">
        <?php echo $lang === 'ka' ? 'რატომ უნდა ვისწავლო ქართული ენა' : 'WHY SHOULD I LEARN GEORGIAN'; ?>
      </h2>

      <div class="text">
        <?php echo nl2br($texts[$lang]['why']); ?>
      </div>

      <button id="toggleText">
        <?php echo $lang === 'ka' ? 'ვრცლად' : 'Read more'; ?><span>▼</span>
      </button>
    </div>
  </div>
  <?php include 'modals/universalModal.php'; ?>
  <?php include 'modals/program.php'; ?>
  <?php include 'modals/about.php'; ?>
  <?php include 'modals/contact.php'; ?>
  <?php include 'includes/footer.php'; ?>

<!-- Затемнение фона -->
<div class="offcanvasOverlay" id="offcanvasOverlay"></div>

<!-- Off-canvas меню -->
<div id="offcanvasMenu" class="offcanvas-menu" aria-hidden="true">
  <nav class="offcanvas-nav" role="navigation">
    <?php
      // НЕ дублируем нигде больше! Здесь единственный рендер offcanvas-меню
      echo buildMenu($menuItems, 1, 2);
    ?>
  </nav>
</div>
<!-- Подключение JS -->
 <script>
  window.CURRENT_LANG = "<?php echo $lang; ?>";
</script>

<script src="assets/js/slider.js" defer></script>
<script src="assets/js/menu.js" defer></script>
<script src="assets/js/text.js" defer></script>
<script src="assets/js/universalModal.js" defer></script>
</div>



