<?php
// includes/menu.php — универсальная «обёртка» меню (PHP 5 совместимо)

// Требуется: includes/config.php, includes/menuData.php, includes/buildMenu.php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/menuData.php';
require_once __DIR__ . '/buildMenu.php';

// дефолты, если не заданы до include
if (!isset($startLevel)) { $startLevel = 1; }
if (!isset($depth))      { $depth      = 2; }

echo '<nav class="menu-block" aria-label="Main">' . PHP_EOL;
echo '  <button class="menu-rail" id="menuRail" aria-label="Toggle side menu" type="button"></button>' . PHP_EOL;
echo '  <div class="menu-inner">' . PHP_EOL;
echo        buildMenu($menuItems, (int)$startLevel, (int)$depth);
echo '  </div>' . PHP_EOL;
echo '</nav>' . PHP_EOL;
