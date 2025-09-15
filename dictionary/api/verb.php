<?php
declare(strict_types=1);
require_once __DIR__ . '/../common.php';
require_once __DIR__ . '/../connect.php';

header('Content-Type: application/json; charset=utf-8');

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    echo json_encode(['success' => false, 'error' => 'bad id']);
    exit;
}

try {
    $sql = "SELECT * FROM verb WHERE word_ID = :id LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        echo json_encode(['success' => true, 'exists' => false], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $infinitive = $row['infinitive'] ?? '';
    $trans_key  = (string)($row['transilive_intransilive'] ?? '0');
    $voice_key  = (string)($row['voice'] ?? '0');
    $peculiarity= $row['peculiarity'] ?? '';

    $tenses = [
        'present_lindicative'  => $row['present_lindicative']  ?? '',
        'imperfect'            => $row['imperfect']            ?? '',
        'present_stubjunctive' => $row['present_stubjunctive'] ?? '',
        'future'               => $row['future']               ?? '',
        'conditional'          => $row['conditional']          ?? '',
        'future_subjunctive'   => $row['future_subjunctive']   ?? '',
        'aorist'               => $row['aorist']               ?? '',
        'conjuctive_II'        => $row['conjuctive_II']        ?? '',
        'resultative_I'        => $row['resultative_I']        ?? '',
        'resultative_II'       => $row['resultative_II']       ?? '',
        'conjuctive_III'       => $row['conjuctive_III']       ?? '',
    ];

    echo json_encode([
        'success' => true,
        'exists'  => true,
        'grammar' => [
            'infinitive'  => $infinitive,
            'transitivity'=> ['key' => $trans_key, 'label' => $LABELS['verb_transitivity'][$trans_key] ?? '-'],
            'voice'       => ['key' => $voice_key,  'label' => $LABELS['verb_voice'][$voice_key]       ?? '-'],
            'peculiarity' => $peculiarity,
        ],
        'tenses' => $tenses,
        'labels' => [
            'tenses' => $LABELS['verb_tense_labels'],
        ],
    ], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    echo json_encode(['success' => false, 'error' => 'DB error']);
}
