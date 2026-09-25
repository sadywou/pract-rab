<?php
class Attendance
{
    public function __construct(private Database $db) {}

    public function addRecord(int $studentId, string $lessonDate, string $status, string $comment = ''): bool
    {
        $this->db->query(
            'INSERT INTO attendance (student_id, lesson_date, status, comment)
             VALUES (?, ?, ?, ?)',
            [$studentId, $lessonDate, $status, $comment]
        );
        return true;
    }

    public function all(): array
    {
        return $this->db->query(
            'SELECT a.*, s.full_name
             FROM attendance a
             JOIN students s ON s.id = a.student_id
             ORDER BY a.lesson_date DESC, a.id DESC'
        )->fetchAll();
    }
}
