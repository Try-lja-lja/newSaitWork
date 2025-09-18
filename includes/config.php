<?php
// includes/config.php

/**
 * Глобальный конфиг (без strict_types и без типизаций — совместимо с PHP 5.x).
 */

// БАЗОВЫЕ URL
define('BASE_URL', 'https://geofl.ge/newSaitWork/'); // с хвостовым /
define('ASSETS_URL', BASE_URL . 'assets/');
define('PAGES_URL',  BASE_URL . 'pages/');

// Файловая структура
define('ROOT_DIR',  dirname(__DIR__));      // .../newSaitWork
define('ASSETS_DIR', ROOT_DIR . '/assets');
define('PAGES_DIR',  ROOT_DIR . '/pages');

// Произвольные флаги
define('DEFAULT_LANGUAGE', 'ka');
define('DEVELOPMENT_MODE', true);

// Если главному сайту нужна своя БД — оставляем; иначе можете удалить
define('DB_HOST', '192.168.64.81');
define('DB_USER', 'site@work.geofl.');
define('DB_PASS', 'lU9o1cOBNLhE45f');
define('DB_NAME', 'geofl_work');
define('DB_CHARSET', 'utf8mb4');
