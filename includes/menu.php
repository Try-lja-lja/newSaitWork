<?php
// includes/menu.php
require_once __DIR__ . '/menuData.php';
require_once __DIR__ . '/buildMenu.php';

// Параметры
if (!isset($depth)) {
    $depth = 2;
}
if (!isset($parentId)) {
    $parentId = null; // от корня
}

// Текущее место (для menu-block--shifted на словаре)
$currentPath  = $_SERVER['REQUEST_URI'] ?? '';
$isDictionary = (strpos($currentPath, '/dictionary-agmarti') !== false);

// Каркас + дерево
echo '<nav class="menu-block' . ($isDictionary ? ' menu-block--shifted' : '') . '" aria-label="Main">' . PHP_EOL;
echo '  <div class="menu-rail" id="menuRail"></div>' . PHP_EOL;
echo '  <div class="menu-content">' . PHP_EOL;
echo buildMenu($menuItems, (int)$depth, $parentId);
echo '  </div>' . PHP_EOL;
echo '</nav>' . PHP_EOL;
