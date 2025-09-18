<?php
/**
 * Единная точка подключения словаря к БД через PDO (PHP 5.x совместимо).
 * Приоритет настроек:
 *   1) pages/dictionary-agmarti/config.local.php  — константы DICT_DB_*
 *   2) pages/dictionary-agmarti/setting.php       — HOST/USER/PASS/DB
 */

// 1) Пытаемся подхватить локальные константы словаря (желательно)
$cfgLocal = __DIR__ . '/config.local.php';
if (is_file($cfgLocal)) {
    require_once $cfgLocal;
}

// 2) Если DICT_DB_* не определены — пробуем старый setting.php
if (!defined('DICT_DB_HOST')) {
    $legacy = __DIR__ . '/setting.php';
    if (is_file($legacy)) {
        require_once $legacy;
        if (defined('HOST') && defined('DB') && defined('USER') && defined('PASS')) {
            if (!defined('DICT_DB_HOST')) define('DICT_DB_HOST', HOST);
            if (!defined('DICT_DB_NAME')) define('DICT_DB_NAME', DB);
            if (!defined('DICT_DB_USER')) define('DICT_DB_USER', USER);
            if (!defined('DICT_DB_PASS')) define('DICT_DB_PASS', PASS);
        }
    }
}

// Проверка, что всё есть
if (!defined('DICT_DB_HOST') || !defined('DICT_DB_NAME') || !defined('DICT_DB_USER') || !defined('DICT_DB_PASS')) {
    header('HTTP/1.1 500 Internal Server Error');
    exit('Dictionary DB config is missing. Provide config.local.php with DICT_DB_* or setting.php with HOST/DB/USER/PASS.');
}

// Charset по умолчанию
if (!defined('DICT_DB_CHARSET')) {
    define('DICT_DB_CHARSET', 'utf8mb4');
}

// Проверим наличие расширений
if (!extension_loaded('pdo')) {
    header('HTTP/1.1 500 Internal Server Error');
    exit('PHP extension PDO is not enabled.');
}
if (!extension_loaded('pdo_mysql')) {
    header('HTTP/1.1 500 Internal Server Error');
    exit('PHP extension pdo_mysql is not enabled.');
}

// DSN
$dsn = 'mysql:host=' . DICT_DB_HOST . ';dbname=' . DICT_DB_NAME . ';charset=' . DICT_DB_CHARSET;

// Опции PDO
$options = array(
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
);

// Подключаемся один раз
if (!isset($pdo) || !($pdo instanceof PDO)) {
    try {
        $pdo = new PDO($dsn, DICT_DB_USER, DICT_DB_PASS, $options);
    } catch (Exception $e) {
        error_log('[dictionary-agmarti] DB connection error: ' . $e->getMessage());
        header('HTTP/1.1 500 Internal Server Error');
        exit('Database connection error (dictionary).');
    }
}

/**
 * Удобный аксессор PDO.
 * Пример: $db = pdo();
 */
function pdo()
{
    global $pdo;
    return $pdo;
}
