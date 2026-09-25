<?php
require_once 'config.php';
    require_once 'init.php';
require_once 'classes/Database.php';
require_once 'classes/User.php';
session_start();
if (empty($_SESSION['user'])) { header('Location: login.php'); exit; }
require 'header.php';
?>
<div class="card">
  <h1>Главная страница</h1>
  <p>Добро пожаловать, <b><?= htmlspecialchars($_SESSION['user']['full_name'] ?: $_SESSION['user']['login']) ?></b>!</p>
  <div class="grid">
    <div class="card"><h3>Логин</h3><p><?= htmlspecialchars($_SESSION['user']['login']) ?></p></div>
    <div class="card"><h3>Роль</h3><p><?= htmlspecialchars($_SESSION['user']['role']) ?></p></div>
    <div class="card"><h3>ООП</h3><p>Database и User работают как независимые классы.</p></div>
  </div>
</div>
<?php require 'footer.php'; ?>
