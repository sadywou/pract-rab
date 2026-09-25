<?php
require_once 'config.php';
    require_once 'init.php';
require_once 'classes/Database.php';
require_once 'classes/User.php';
session_start();
if (empty($_SESSION['user'])) { header('Location: login.php'); exit; }
if (!in_array($_SESSION['user']['role'], ['teacher','admin'], true)) { http_response_code(403); exit('Доступ запрещён'); }
$db = new Database();
$grades = $db->query(
    'SELECT s.full_name, g.subject, g.grade, g.grade_date
     FROM grades g JOIN students s ON s.id = g.student_id
     ORDER BY g.grade_date DESC'
)->fetchAll();
require 'header.php';
?>
<div class="card">
  <h1>Оценки</h1>
  <table><tr><th>Студент</th><th>Предмет</th><th>Оценка</th><th>Дата</th></tr>
  <?php foreach ($grades as $grade): ?>
    <tr>
      <td><?= htmlspecialchars($grade['full_name']) ?></td>
      <td><?= htmlspecialchars($grade['subject']) ?></td>
      <td><?= htmlspecialchars($grade['grade']) ?></td>
      <td><?= htmlspecialchars($grade['grade_date']) ?></td>
    </tr>
  <?php endforeach; ?>
  </table>
</div>
<?php require 'footer.php'; ?>
