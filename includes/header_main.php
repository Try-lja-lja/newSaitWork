<!DOCTYPE html>
<html lang="<?php echo isset($_SESSION['lang']) ? $_SESSION['lang'] : 'ka'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Глобальные стили -->
    <link rel="stylesheet" href="assets/css/main.css">
    <!-- Стили меню (desktop + off‑canvas) -->
    <link rel="stylesheet" href="assets/css/menu.css">
</head>
<body>
<header>
  <div class="top-bar">
    <div class="left-side">
      <div class="hamburger" id="hamburgerBtn">
        <span></span>
        <span></span>
        <span></span>
      </div>
  <div class="logo">
    <a href="#">
      <img id="logo" src="assets/img/logo.svg" alt="Logo">
    </a>
  </div>
    </div>
    <div class="right-side">
      <div class="language-switcher">
        <a href="?lang=ka"><img src="assets/img/geo.svg" alt="KA" /></a>
        <a href="?lang=en"><img src="assets/img/eng.svg" alt="EN" /></a>
      </div>
      <div class="title">
        <h1 class="boxoFont">ქართული როგორც უცხო ენა</h1>
        <h1 class="goticFont">GEORGIAN AS A FOREIGN LANGUAGE</h1>
      </div>
    </div>
  </div>
</header>
