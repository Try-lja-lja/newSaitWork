<?php
// api/get_word.php — возвращает детальную информацию о слове {word row + use rows}
declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../connect.php';

$input = json_decode(file_get_contents('php://input'), true) ?? $_POST;

if (empty($input['id'])) {
    echo json_encode(['success' => false, 'error' => 'Missing id']);
    exit;
}
$id = (int)$input['id'];

try {
    $stmt = $pdo->prepare('SELECT id, word, word_view, part_of_speech_id FROM `words` WHERE id = :id');
    $stmt->execute([':id' => $id]);
    $word = $stmt->fetch();

    if (!$word) {
        echo json_encode(['success' => false, 'error' => 'Word not found']);
        exit;
    }

    $stmt2 = $pdo->prepare('SELECT * FROM `use` WHERE word_id = :id ORDER BY id');
    $stmt2->execute([':id' => $id]);
    $uses = $stmt2->fetchAll();

    echo json_encode(['success' => true, 'data' => array_merge($word, ['uses' => $uses])], JSON_UNESCAPED_UNICODE);
    exit;
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database error', 'details' => $e->getMessage()]);
    exit;
}
