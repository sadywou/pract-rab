<?php
require_once 'config.php';
require_once 'init.php';
require_once 'classes/Database.php';
require_once 'classes/User.php';

if (session_status() !== PHP_SESSION_ACTIVE) session_start();

if (!empty($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';

    try {
        $db = new Database();
        $user = User::findByLogin($login, $db);
        $stmt = $user ? $db->query('SELECT password_hash FROM users WHERE id = ?', [$user->id]) : null;
        $row = $stmt ? $stmt->fetch() : null;

        if ($user && $row && password_verify($password, $row['password_hash'])) {
            $_SESSION['user'] = [
                'id' => $user->id,
                'login' => $user->login,
                'role' => $user->role,
                'student_id' => $user->studentId,
                'full_name' => $user->fullName
            ];
            header('Location: index.php');
            exit;
        }
        $error = 'Неверный логин или пароль.';
    } catch (Throwable $e) {
        $error = $e->getMessage();
    }
}

require 'header.php';
?>
<div class="card" style="max-width:480px;margin:50px auto;">
  <h1>Вход в систему</h1>
  <p class="small">Практический пример объектной авторизации.</p>
  <?php if ($error): ?><div class="alert"><?= htmlspecialchars($error) ?></div><?php endif; ?>
  <form method="post">
    <label>Логин</label>
    <input name="login" required>
    <label>Пароль</label>
    <input type="password" name="password" required>
    <br><br><button type="submit">Войти</button>
  </form>
  <hr>
  <p class="small"><b>Тестовые пользователи:</b><br>
  student / student123<br>teacher / teacher123<br>admin / admin123</p>
</div>
<?php require 'footer.php'; ?>
