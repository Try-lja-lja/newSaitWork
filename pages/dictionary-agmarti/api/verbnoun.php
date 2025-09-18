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
    $sql = "SELECT vn.*, w.word_view
            FROM verbnoun AS vn
            JOIN words    AS w ON w.id = vn.word_ID
            WHERE vn.word_ID = :id
            LIMIT 1";
    $st = $pdo->prepare($sql);
    $st->execute([':id' => $id]);
    $row = $st->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        echo json_encode(['success' => true, 'exists' => false], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // поле 's' может быть случайно заведено кириллической 'с' → подстрахуемся
    $verb_of = $row['s'] ?? ($row['с'] ?? '');

    $cases = [
        'nominative'     => ['s' => $row['word_view']        ?? ''],
        'ergative'       => ['s' => $row['ergative_s']       ?? ''],
        'dative'         => ['s' => $row['dative_s']         ?? ''],
        'genetive'       => ['s' => $row['genetive_s']       ?? ''],
        'instrumental'   => ['s' => $row['instrumental_s']   ?? ''],
        'transformative' => ['s' => $row['transformative_s'] ?? ''],
        'vocative'       => ['s' => $row['vocative_s']       ?? ''],
    ];

    echo json_encode([
        'success' => true,
        'exists'  => true,
        'verb_of' => $verb_of, // ზმნ(ებ)ისა
        'cases'   => $cases,   // только ед.ч.
        'labels'  => [
            'cases'       => $LABELS['cases'],
            'cases_order' => $LABELS['cases_order'],
        ],
    ], JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {
    echo json_encode(['success' => false, 'error' => 'DB error'], JSON_UNESCAPED_UNICODE);
}
