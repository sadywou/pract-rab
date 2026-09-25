<?php
require_once 'config.php';
    require_once 'init.php';
require_once 'classes/Database.php';
require_once 'classes/User.php';
session_start();
if (empty($_SESSION['user'])) { header('Location: login.php'); exit; }
$db = new Database();
$user = User::findByLogin($_SESSION['user']['login'], $db);
require 'header.php';
?>
<div class="card">
  <h1>Профиль</h1>
  <table>
    <tr><th>ФИО</th><td><?= htmlspecialchars($user->fullName ?: 'Не указано') ?></td></tr>
    <tr><th>Логин</th><td><?= htmlspecialchars($user->login) ?></td></tr>
    <tr><th>Роль</th><td><?= htmlspecialchars($user->role) ?></td></tr>
  </table>
</div>
<?php require 'footer.php'; ?>
