<?php
declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../common.php';
require_once __DIR__ . '/../connect.php';

function fail(string $msg, int $code = 400): void {
    http_response_code($code);
    echo json_encode(['success' => false, 'error' => $msg], JSON_UNESCAPED_UNICODE);
    exit;
}

$tema = isset($_GET['tema']) ? (int)$_GET['tema'] : 0;
if ($tema <= 0) {
    fail('Bad or missing tema');
}

try {
    // как в старом PHP: A1/A2 только
    $sql = "
        SELECT w.id, w.word_view
        FROM words w
        JOIN `use` u ON u.word_id = w.id
        WHERE (:t IN (u.tema1, u.tema2, u.tema3))
          AND (u.level = 'A1' OR u.level = 'A2')
        GROUP BY w.id, w.word_view
        ORDER BY w.word_view ASC
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':t' => $tema]);

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'rows' => $rows], JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {
    error_log('topic_words error: ' . $e->getMessage());
    fail('DB error', 500);
}
