<?php
declare(strict_types=1);

/**
 * connect.php
 * Централизованное подключение к БД через PDO.
 * Подключает / использует константы из setting.php:
 *   HOST, USER, PASS, DB
 *
 * После подключения в этом файле доступна переменная $pdo (тип PDO).
 *
 * Примечания безопасности/эксплуатации:
 * - Файлы должны быть в кодировке UTF-8 без BOM.
 * - В production не показываем детальные ошибки пользователю — логируем их.
 * - Рекомендуется хранить setting.php вне webroot, но если невозможно — защитить файл (например .htaccess).
 */

// Подключаем файл с константами подключения (существует у тебя)
require_once __DIR__ . '/setting.php';

// Проверим, что расширение PDO + драйвер MySQL доступен
if (!extension_loaded('pdo')) {
    // fatal — без PDO работать не будет
    http_response_code(500);
    die('Ошибка: PHP-extension PDO не включен (pdo).');
}
if (!extension_loaded('pdo_mysql')) {
    http_response_code(500);
    die('Ошибка: PHP-extension pdo_mysql не включен. Включите pdo_mysql в php.ini.');
}

// DSN: используем utf8mb4 для корректной работы с эмодзи/полным UTF-8
$dsn = 'mysql:host=' . HOST . ';dbname=' . DB . ';charset=utf8mb4';

// Опции PDO — важные и рекомендованные для современных приложений
$pdoOptions = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // исключения в случае ошибок
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // fetch_assoc по-умолчанию
    PDO::ATTR_EMULATE_PREPARES   => false,                  // использовать нативные prepared statements (без эмуляции)
    //PDO::ATTR_PERSISTENT        => true,                  // опционально: persistent connection
];

// Попытка подключения в try/catch
try {
    // Создаём экземпляр PDO и делаем его доступным в глобальной области
    $pdo = new PDO($dsn, USER, PASS, $pdoOptions);
} catch (PDOException $e) {
    // Логируем техническое сообщение (error_log), но пользователю даём нейтральный текст
    error_log('DB connection error: ' . $e->getMessage());
    http_response_code(500);
    die('Ошибка подключения к базе данных. Подробности в логе сервера.');
}


/**
 * Удобная функция — возвращает PDO-инстанс.
 * Можно вызывать в других файлах: $pdo = pdo();
 * (это аккуратно, если кто-то не хочет использовать глобальную переменную)
 */
function pdo(): PDO
{
    global $pdo; // обращаемся к глобальной переменной, созданной выше
    return $pdo;
}

/**
 * Примеры использования PDO (комментарии, не выполняются):
 *
 * 1) SELECT несколько строк:
 * $stmt = pdo()->prepare('SELECT id, word, word_view FROM words WHERE word LIKE ? LIMIT 50');
 * $stmt->execute(["abc%"]);
 * $rows = $stmt->fetchAll(); // вернёт массив ассоциативных массивов
 *
 * 2) SELECT одна строка:
 * $stmt = pdo()->prepare('SELECT * FROM words WHERE id = ?');
 * $stmt->execute([ $id ]);
 * $row = $stmt->fetch(); // false если ничего не найдено
 *
 * 3) INSERT и получение lastInsertId:
 * $stmt = pdo()->prepare('INSERT INTO words (word, word_view, part_of_speech_id) VALUES (?, ?, ?)');
 * $stmt->execute([ $word, $word_view, $part_id ]);
 * $newId = pdo()->lastInsertId();
 *
 * 4) UPDATE:
 * $stmt = pdo()->prepare('UPDATE words SET word = ?, word_view = ?, part_of_speech_id = ? WHERE id = ?');
 * $stmt->execute([ $word, $word_view, $part_id, $id ]);
 * $affected = $stmt->rowCount();
 *
 * 5) Транзакции:
 * try {
 *     pdo()->beginTransaction();
 *     // несколько операций INSERT/UPDATE...
 *     pdo()->commit();
 * } catch (Exception $e) {
 *     pdo()->rollBack();
 *     throw $e; // или логировать
 * }
 *
 * 6) Защита от SQL-инъекций:
 * - Всегда используйте prepared statements (prepare + execute с placehoders), не подставляйте переменные напрямую в SQL.
 * - Для динамических имен таблиц/сортировки – используйте белый список (whitelist), а не подставляйте пользовательский ввод.
 *
 */

/* END OF FILE */
