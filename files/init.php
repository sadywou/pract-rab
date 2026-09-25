<?php
require_once __DIR__ . '/config.php';

function initializeDatabase(): void
{
    static $done = false;
    if ($done) {
        return;
    }

    try {
        // First connect without selecting a database.
        $pdo = new PDO(
            'mysql:host=' . DB_HOST . ';charset=utf8mb4',
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );

        $pdo->exec(
            'CREATE DATABASE IF NOT EXISTS `' . str_replace('`', '``', DB_NAME) .
            '` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci'
        );

        $pdo->exec('USE `' . str_replace('`', '``', DB_NAME) . '`');

        $pdo->exec(
            'CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                login VARCHAR(50) NOT NULL UNIQUE,
                password_hash VARCHAR(255) NOT NULL,
                role ENUM("student","teacher","admin") NOT NULL
            ) ENGINE=InnoDB'
        );

        $pdo->exec(
            'CREATE TABLE IF NOT EXISTS students (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NULL,
                full_name VARCHAR(150) NOT NULL,
                group_name VARCHAR(50) NOT NULL,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
            ) ENGINE=InnoDB'
        );

        $pdo->exec(
            'CREATE TABLE IF NOT EXISTS grades (
                id INT AUTO_INCREMENT PRIMARY KEY,
                student_id INT NOT NULL,
                subject VARCHAR(100) NOT NULL,
                grade TINYINT NOT NULL,
                grade_date DATE NOT NULL,
                FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
            ) ENGINE=InnoDB'
        );

        $pdo->exec(
            'CREATE TABLE IF NOT EXISTS attendance (
                id INT AUTO_INCREMENT PRIMARY KEY,
                student_id INT NOT NULL,
                lesson_date DATE NOT NULL,
                status ENUM("present","absent") NOT NULL,
                comment VARCHAR(255) DEFAULT "",
                FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
            ) ENGINE=InnoDB'
        );

        // Seed demo users only if they do not exist.
        $users = [
            ['student', password_hash('student123', PASSWORD_DEFAULT), 'student'],
            ['teacher', password_hash('teacher123', PASSWORD_DEFAULT), 'teacher'],
            ['admin', password_hash('admin123', PASSWORD_DEFAULT), 'admin'],
        ];

        $stmt = $pdo->prepare(
            'INSERT INTO users (login, password_hash, role)
             VALUES (?, ?, ?)
             ON DUPLICATE KEY UPDATE login = login'
        );
        foreach ($users as $user) {
            $stmt->execute($user);
        }

        $count = (int)$pdo->query('SELECT COUNT(*) FROM students')->fetchColumn();
        if ($count === 0) {
            $studentUserId = (int)$pdo->query("SELECT id FROM users WHERE login='student'")->fetchColumn();
            $stmt = $pdo->prepare(
                'INSERT INTO students (user_id, full_name, group_name) VALUES (?, ?, ?)'
            );
            $stmt->execute([$studentUserId, 'Иванов Иван Иванович', 'ИС-21']);
            $stmt->execute([null, 'Петров Пётр Петрович', 'ИС-21']);
        }

        $count = (int)$pdo->query('SELECT COUNT(*) FROM grades')->fetchColumn();
        if ($count === 0) {
            $students = $pdo->query('SELECT id FROM students ORDER BY id')->fetchAll(PDO::FETCH_COLUMN);
            if (count($students) >= 2) {
                $stmt = $pdo->prepare(
                    'INSERT INTO grades (student_id, subject, grade, grade_date) VALUES (?, ?, ?, ?)'
                );
                $stmt->execute([$students[0], 'PHP', 5, '2026-09-20']);
                $stmt->execute([$students[0], 'Базы данных', 4, '2026-09-22']);
                $stmt->execute([$students[1], 'PHP', 4, '2026-09-21']);
            }
        }

        $count = (int)$pdo->query('SELECT COUNT(*) FROM attendance')->fetchColumn();
        if ($count === 0) {
            $students = $pdo->query('SELECT id FROM students ORDER BY id')->fetchAll(PDO::FETCH_COLUMN);
            if (count($students) >= 2) {
                $stmt = $pdo->prepare(
                    'INSERT INTO attendance (student_id, lesson_date, status, comment) VALUES (?, ?, ?, ?)'
                );
                $stmt->execute([$students[0], '2026-09-23', 'present', 'Без замечаний']);
                $stmt->execute([$students[1], '2026-09-23', 'absent', 'Болезнь']);
            }
        }

        $done = true;
    } catch (PDOException $e) {
        throw new RuntimeException(
            'Не удалось подключиться к MySQL. Проверьте, что MySQL запущен в XAMPP. ' .
            'Детали: ' . $e->getMessage()
        );
    }
}
