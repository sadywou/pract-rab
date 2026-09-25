<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Электронный журнал 2.0 — Практическая работа</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
  <div class="container">
    <nav>
      <strong>Электронный журнал 2.0</strong>
      <div>
        <?php if (!empty($_SESSION['user'])): ?>
          <a href="index.php">Главная</a>
          <a href="profile.php">Профиль</a>
          <?php if ($_SESSION['user']['role'] === 'teacher' || $_SESSION['user']['role'] === 'admin'): ?>
            <a href="grades.php">Оценки</a>
          <?php endif; ?>
          <a href="logout.php">Выйти</a>
        <?php endif; ?>
      </div>
    </nav>
  </div>
</header>
<main class="container">
