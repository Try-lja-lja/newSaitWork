<?php
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'ka';
}

if (isset($_GET['lang']) && in_array($_GET['lang'], ['ka', 'en'])) {
    $_SESSION['lang'] = $_GET['lang'];
    header('Location: index.php');
    exit;
}

require_once __DIR__ . '/../lang/' . $_SESSION['lang'] . '.php';
