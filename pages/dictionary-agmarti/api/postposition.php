<?php
declare(strict_types=1);
require_once __DIR__ . '/../common.php';
require_once __DIR__ . '/../connect.php';

header('Content-Type: application/json; charset=utf-8');

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    echo json_encode(['success' => false, 'error' => 'bad id'], JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    $sql = "SELECT `case` FROM postposition WHERE word_ID = :id LIMIT 1";
    $st  = $pdo->prepare($sql);
    $st->execute([':id' => $id]);
    $row = $st->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        echo json_encode(['success' => true, 'exists' => false], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $key   = (string)($row['case'] ?? '0');
    $label = $LABELS['postposition_case'][$key] ?? '-';

    echo json_encode([
        'success' => true,
        'exists'  => true,
        'case'    => ['key' => $key, 'label' => $label],
    ], JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {
    echo json_encode(['success' => false, 'error' => 'DB error'], JSON_UNESCAPED_UNICODE);
}
