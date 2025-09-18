<?php
// includes/db.php — PHP 5 совместимо, без записи логов в файлы
require_once __DIR__ . '/config.php';

if (!defined('DB_HOST')) {
    // если общая БД не нужна — эти константы можешь удалить из config.php,
    // но для меню они сейчас используются
    define('DB_HOST', '');
    define('DB_USER', '');
    define('DB_PASS', '');
    define('DB_NAME', '');
    if (!defined('DB_CHARSET')) define('DB_CHARSET', 'utf8');
}

/** Возвращает mysqli-подключение или null при ошибке */
function db() {
    static $mysqli = null;
    if ($mysqli instanceof mysqli) return $mysqli;

    if (DB_HOST === '' || DB_USER === '' || DB_NAME === '') {
        error_log('[db] DB constants are not set.');
        return null;
    }

    $mysqli = @new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($mysqli->connect_errno) {
        error_log('[db] connect error ' . $mysqli->connect_errno . ': ' . $mysqli->connect_error);
        $mysqli = null;
        return null;
    }

    // Кодировка
    if (defined('DB_CHARSET') && DB_CHARSET) {
        @$mysqli->set_charset(DB_CHARSET);
    } else {
        @$mysqli->set_charset('utf8');
    }

    return $mysqli;
}

/**
 * Выполняет SELECT и возвращает массив ассоциативных массивов.
 * $params — необязательные параметры для prepared statement (все как строки).
 */
function fetchData($sql, $params = array()) {
    $conn = db();
    if (!$conn) return array();

    if (!empty($params)) {
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            error_log('[db] prepare error: ' . $conn->error);
            return array();
        }
        // все параметры — строки (s...s)
        $types = str_repeat('s', count($params));
        // PHP 5.6+: распаковка массива, для 5.4/5.5 — call_user_func_array
        if (version_compare(PHP_VERSION, '5.6.0', '>=')) {
            $stmt->bind_param($types, ...array_values($params));
        } else {
            $bind = array_merge(array($types), array_values($params));
            call_user_func_array(array($stmt, 'bind_param'), refValues($bind));
        }

        if (!$stmt->execute()) {
            error_log('[db] exec error: ' . $stmt->error);
            $stmt->close();
            return array();
        }
        $res = $stmt->get_result();
        $rows = array();
        if ($res) {
            while ($row = $res->fetch_assoc()) $rows[] = $row;
            $res->free();
        }
        $stmt->close();
        return $rows;
    } else {
        $res = $conn->query($sql);
        if (!$res) {
            error_log('[db] query error: ' . $conn->error);
            return array();
        }
        $rows = array();
        while ($row = $res->fetch_assoc()) $rows[] = $row;
        $res->free();
        return $rows;
    }
}

// Хелпер для PHP 5.4/5.5 bind_param
if (!function_exists('refValues')) {
    function refValues($arr) {
        $refs = array();
        foreach ($arr as $k => $v) $refs[$k] =& $arr[$k];
        return $refs;
    }
}
