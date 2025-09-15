<?php
// db.php

// Подключаем конфигурационные настройки
require_once __DIR__ . '/config.php';

// Проверяем, существует ли уже подключение к базе данных
if (!isset($pdo)) {
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8";
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_PERSISTENT => false // Используем постоянное соединение
        ]);
    } catch (PDOException $e) {
        // Логируем ошибку в файл, если не удалось подключиться
        error_log("Ошибка подключения к базе данных: " . $e->getMessage(), 3, ROOT_PATH . '/logs/db_error.log');
        die("Не удалось подключиться к базе данных.");
    }
}

// Функция для выполнения запросов на выборку данных
function fetchData($query, $params = []) {
    global $pdo; // Используем глобальное соединение
    try {
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(); // Возвращаем все результаты выборки
    } catch (PDOException $e) {
        // Логируем ошибку
        error_log("Ошибка выборки данных: " . $e->getMessage(), 3, ROOT_PATH . '/logs/db_error.log');
        return [];
    }
}
?>
