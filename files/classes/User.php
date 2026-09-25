<?php
class User
{
    public int $id;
    public string $login;
    public string $role;
    public ?int $studentId = null;
    public string $fullName = '';

    public static function findByLogin(string $login, Database $db): ?self
    {
        $stmt = $db->query(
            'SELECT u.*, s.id AS student_id, s.full_name
             FROM users u
             LEFT JOIN students s ON s.user_id = u.id
             WHERE u.login = ? LIMIT 1',
            [$login]
        );
        $data = $stmt->fetch();

        if (!$data) {
            return null;
        }

        $user = new self();
        $user->id = (int)$data['id'];
        $user->login = $data['login'];
        $user->role = $data['role'];
        $user->studentId = $data['student_id'] !== null ? (int)$data['student_id'] : null;
        $user->fullName = $data['full_name'] ?? '';
        return $user;
    }

    public function isStudent(): bool { return $this->role === 'student'; }
    public function isTeacher(): bool { return $this->role === 'teacher'; }
    public function isAdmin(): bool { return $this->role === 'admin'; }
}
