<?php
require_once 'config.php';
    require_once 'init.php';
require_once 'classes/Database.php';
require_once 'classes/User.php';
require_once 'classes/Attendance.php';
session_start();
if (empty($_SESSION['user'])) { header('Location: login.php'); exit; }
if (!in_array($_SESSION['user']['role'], ['teacher','admin'], true)) { http_response_code(403); exit('Доступ запрещён'); }

$db = new Database();
$attendance = new Attendance($db);
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $attendance->addRecord(
        (int)$_POST['student_id'],
        $_POST['lesson_date'],
        $_POST['status'],
        trim($_POST['comment'] ?? '')
    );
    $message = 'Запись посещаемости добавлена.';
}

$students = $db->query('SELECT id, full_name FROM students ORDER BY full_name')->fetchAll();
$records = $attendance->all();
require 'header.php';
?>
<div class="card">
  <h1>Посещаемость</h1>
  <?php if ($message): ?><div class="alert success"><?= htmlspecialchars($message) ?></div><?php endif; ?>
  <form method="post">
    <div class="grid">
      <div><label>Студент</label><select name="student_id" required>
        <?php foreach ($students as $s): ?><option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['full_name']) ?></option><?php endforeach; ?>
      </select></div>
      <div><label>Дата</label><input type="date" name="lesson_date" value="<?= date('Y-m-d') ?>" required></div>
      <div><label>Статус</label><select name="status"><option value="present">Присутствовал</option><option value="absent">Отсутствовал</option></select></div>
    </div>
    <label>Комментарий</label><input name="comment">
    <br><br><button>Добавить запись</button>
  </form>
</div>
<div class="card">
  <h2>Журнал</h2>
  <table><tr><th>Студент</th><th>Дата</th><th>Статус</th><th>Комментарий</th></tr>
  <?php foreach ($records as $r): ?>
    <tr><td><?= htmlspecialchars($r['full_name']) ?></td><td><?= htmlspecialchars($r['lesson_date']) ?></td><td><?= htmlspecialchars($r['status']) ?></td><td><?= htmlspecialchars($r['comment']) ?></td></tr>
  <?php endforeach; ?>
  </table>
</div>
<?php require 'footer.php'; ?>
