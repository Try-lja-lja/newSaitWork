<?php
// В обычном режиме — JSON. В debug-режиме — text/plain + подробный echo.
$debug = (isset($_GET['debug']) && $_GET['debug'] !== '0');
header('Content-Type: ' . ($debug ? 'text/plain; charset=utf-8' : 'application/json; charset=utf-8'));
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

require_once __DIR__ . '/../common.php';
require_once __DIR__ . '/../connect.php';

/** Быстрая подстановка параметров в SQL для echo (ТОЛЬКО ДЛЯ ОТЛАДКИ) */
function expand_sql_for_debug($sql, $params) {
    // Нормализуем ключи (:name или name)
    $norm = array();
    foreach ($params as $k => $v) {
        $k2 = ($k !== '' && $k[0] === ':') ? $k : (':' . $k);
        $norm[$k2] = $v;
    }
    // Подставим значения
    foreach ($norm as $k => $v) {
        if (is_int($v) || is_float($v)) {
            $rep = (string)$v;
        } else {
            $rep = "'" . str_replace("'", "''", (string)$v) . "'";
        }
        $sql = str_replace($k, $rep, $sql);
    }
    // Чуть компактнее формат
    return preg_replace('/\s+/', ' ', trim($sql));
}

/** echo-помощник в debug-режиме */
function dbg($msg, $debug) {
    if ($debug) {
        echo $msg, "\n";
    }
}

// ===== Получаем фильтры (POST — как ждёт фронт) =====
$tema           = isset($_POST['tema'])           ? FormChars($_POST['tema'])           : '43';
$level          = isset($_POST['level'])          ? FormChars($_POST['level'])          : 'all';
$part_of_speech = isset($_POST['part_of_speech']) ? FormChars($_POST['part_of_speech']) : '13';
$word           = isset($_POST['word'])           ? FormChars($_POST['word'])           : '';
$letter         = isset($_POST['letter'])         ? FormChars($_POST['letter'])         : '';

// Нормализуем "пустую букву"
$letter_is_empty = ($letter === '' || $letter === 'ყველა ასო');

// Входные параметры — в debug-echo
dbg("== FILTERS ==", $debug);
dbg("tema={$tema}", $debug);
dbg("level={$level}", $debug);
dbg("part_of_speech={$part_of_speech}", $debug);
dbg("word={$word}", $debug);
dbg("letter=" . ($letter_is_empty ? '(empty)' : $letter), $debug);

// Ответ
$rows = array();
$started_at = microtime(true);

try {
    // Ветка «без фильтров»
    $noFilters =
        ($tema === '43') &&
        ($level === 'all') &&
        ($part_of_speech === '13') &&
        ($word === '') &&
        $letter_is_empty;

    if ($noFilters) {
        dbg(">> BRANCH: FAST (no filters)", $debug);

        // Простой и быстрый DISTINCT JOIN
        $sql = "
            SELECT
                w.id,
                w.word_view,
                COALESCE(NULLIF(u.level, ''), 'WL') AS level
            FROM words AS w
            JOIN `use` AS u ON u.word_id = w.id
            GROUP BY w.id, w.word_view
            ORDER BY w.word_view ASC
        ";

        dbg("SQL (compact): " . preg_replace('/\s+/', ' ', trim($sql)), $debug);

        $tq = microtime(true);
        $stmt = $pdo->query($sql);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $rows[] = $row;
        }
        $dt_q = microtime(true) - $tq;
        dbg(sprintf("Query time: %.4f s", $dt_q), $debug);

    } else {
        dbg(">> BRANCH: WITH FILTERS", $debug);

        // Базовый SQL
        $sql = "
            SELECT
                w.id,
                w.word_view,
                MIN(u.level) AS level
            FROM words AS w
            JOIN `use`  AS u ON u.word_id = w.id
        ";

        $conditions = array();
        $params     = array();

        // Тема — три одинаковых плейсхолдера для надёжности в PDO
        if ($tema !== '43') {
            $conditions[] = "(u.tema1 = :t1 OR u.tema2 = :t2 OR u.tema3 = :t3)";
            $params[':t1'] = (int)$tema;
            $params[':t2'] = (int)$tema;
            $params[':t3'] = (int)$tema;
        }

        // Уровень
        if ($level !== 'all') {
            $conditions[] = "u.level = :level";
            $params[':level'] = $level;
        }

        // Часть речи
        if ($part_of_speech !== '13') {
            // NB: колонка именно part_of_speech_id (не part_of_speech)
            $conditions[] = "w.part_of_speech_id = :pos";
            $params[':pos'] = (int)$part_of_speech;
        }

        // Поиск по слову
        if ($word !== '') {
            $conditions[] = "w.word_view LIKE :word";
            $params[':word'] = $word . '%';
        }

        // По первой букве (если выбрана)
        if (!$letter_is_empty) {
            $conditions[] = "w.word_view LIKE :letter";
            $params[':letter'] = $letter . '%';
        }

        if (!empty($conditions)) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }

        $sql .= ' GROUP BY w.id, w.word_view ORDER BY w.word_view ASC';

        // Debug: SQL и параметры
        dbg("SQL (compact): " . preg_replace('/\s+/', ' ', trim($sql)), $debug);
        if (!empty($params)) {
            $pairs = array();
            foreach ($params as $k => $v) {
                $pairs[] = $k . '=' . (is_scalar($v) ? $v : json_encode($v));
            }
            dbg('PARAMS: ' . implode(', ', $pairs), $debug);
            dbg('SQL expanded: ' . expand_sql_for_debug($sql, $params), $debug);
        } else {
            dbg('PARAMS: (empty)', $debug);
        }

        // Выполняем
        $tq = microtime(true);
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $rows[] = $row;
        }
        $dt_q = microtime(true) - $tq;
        dbg(sprintf("Query time: %.4f s", $dt_q), $debug);
    }

    $total_time = microtime(true) - $started_at;
    dbg(sprintf("TOTAL time: %.4f s, rows: %d", $total_time, count($rows)), $debug);

    if ($debug) {
        $preview = array_slice($rows, 0, 10);
        dbg("-- preview (first 10 rows) --", $debug);
        for ($i=0; $i<count($preview); $i++) {
            $r = $preview[$i];
            dbg(sprintf("%d) id=%s word_view=%s level=%s",
                $i+1,
                isset($r['id']) ? $r['id'] : '?',
                isset($r['word_view']) ? $r['word_view'] : '?',
                isset($r['level']) ? $r['level'] : '?'
            ), $debug);
        }
        dbg("-- end preview --", $debug);
        echo "\n--- JSON ---\n";
        echo json_encode(array('rows' => $rows));
        exit;
    }

    echo json_encode(array('rows' => $rows));

} catch (Exception $e) { // PHP5: ловим Exception, не Throwable
    if ($debug) {
        echo "ERROR: " . $e->getMessage() . "\n";
        echo "TRACE:\n" . $e->getTraceAsString() . "\n";
        echo "\n--- JSON ---\n";
    }
    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
    echo json_encode(array('success' => false, 'error' => $e->getMessage()));
}
