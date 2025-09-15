<?php
// config.php

// Настройки для подключения к базе данных
define('DB_HOST', '192.168.64.81');
define('DB_USER', 'site@work.geofl.');
define('DB_PASS', 'lU9o1cOBNLhE45f');
define('DB_NAME', 'geofl_work');

// Настройки для путей и папок
define('BASE_URL', 'https://geofl.ge/siteUnderDevelopment'); // Основной URL подпроекта
define('ROOT_PATH', __DIR__ . '/siteUnderDevelopment'); // Корневая директория подпроекта
// define('CACHE_PATH', ROOT_PATH . '/cache'); // Папка с кешированными данными
// define('MEDIA_PATH', ROOT_PATH . '/media'); // Папка с медиафайлами
define('ASSETS_PATH', ROOT_PATH . '/assets'); // Папка с ассетами (стили, скрипты, изображения)

// define('VIDEOS_PATH', MEDIA_PATH . '/videos'); // Папка с видеороликами
// define('AUDIO_PATH', MEDIA_PATH . '/audio'); // Папка с аудиофайлами
// define('EXERCISES_PATH', MEDIA_PATH . '/exercises'); // Папка с медиафайлами для упражнений

// Настройки кэширования
define('CACHE_EXPIRATION', 3600); // Время жизни кеша в секундах (например, 1 час)

// Настройки шрифтов
define('FONT_PATH', ASSETS_PATH . '/fonts'); // Папка с шрифтами
define('FONT_KA_BOXO', FONT_PATH . '/ka_boxo'); // Путь к шрифту ka_boxo
define('FONT_KA_MRGVLOVANI', FONT_PATH . '/ka_mrgvlovani'); // Путь к шрифту ka_mrgvlovani
define('FONT_KA_MRGVLOVANI_CAPS', FONT_PATH . '/ka_mrgvlovani_caps'); // Путь к шрифту ka_mrgvlovani_caps

// Другие настройки проекта
define('DEFAULT_LANGUAGE', 'ka'); // Язык по умолчанию
define('DEVELOPMENT_MODE', true); // Режим разработки (true/false)

?>
