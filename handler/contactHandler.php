<?php
header('Content-Type: application/json');

// Только POST-запросы
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(array('success' => false, 'message' => 'Method not allowed'));
    exit;
}

// Антибот-проверка
if (isset($_POST['honeypot']) && $_POST['honeypot'] !== '') {
    echo json_encode(array('success' => false, 'message' => 'Spam detected'));
    exit;
}

// Получаем поля
$name    = isset($_POST['name']) ? trim($_POST['name']) : '';
$email   = isset($_POST['email']) ? trim($_POST['email']) : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';

// Проверка обязательных полей
if (!$name || !$email || !$message) {
    echo json_encode(array('success' => false, 'message' => 'Fill in all required fields'));
    exit;
}

// Проверка email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(array('success' => false, 'message' => 'Invalid email'));
    exit;
}

// Отправка письма
$to = 'geolang@mes.gov.ge';
$subject = 'Contact Form Message';
$body = "Name: $name\nEmail: $email\n\nMessage:\n$message";
$headers = "From: $email";

$sent = mail($to, $subject, $body, $headers);

if ($sent) {
    echo json_encode(array('success' => true, 'message' => 'Message sent successfully'));
} else {
    echo json_encode(array('success' => false, 'message' => 'Message could not be sent'));
}
