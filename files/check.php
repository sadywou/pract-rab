<?php
require_once 'config.php';
require_once 'init.php';

try {
    initializeDatabase();
    echo '<h2>Подключение к MySQL работает!</h2>';
    echo '<p>База <b>' . htmlspecialchars(DB_NAME) . '</b> создана/доступна.</p>';
    echo '<p><a href="login.php">Перейти ко входу</a></p>';
} catch (Throwable $e) {
    http_response_code(500);
    echo '<h2>Ошибка подключения</h2>';
    echo '<pre>' . htmlspecialchars($e->getMessage()) . '</pre>';
}
