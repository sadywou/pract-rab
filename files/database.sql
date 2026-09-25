CREATE DATABASE IF NOT EXISTS journal_practice CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE journal_practice;

DROP TABLE IF EXISTS attendance;
DROP TABLE IF EXISTS grades;
DROP TABLE IF EXISTS students;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  login VARCHAR(50) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('student','teacher','admin') NOT NULL
) ENGINE=InnoDB;

CREATE TABLE students (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NULL,
  full_name VARCHAR(150) NOT NULL,
  group_name VARCHAR(50) NOT NULL,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE grades (
  id INT AUTO_INCREMENT PRIMARY KEY,
  student_id INT NOT NULL,
  subject VARCHAR(100) NOT NULL,
  grade TINYINT NOT NULL,
  grade_date DATE NOT NULL,
  FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE attendance (
  id INT AUTO_INCREMENT PRIMARY KEY,
  student_id INT NOT NULL,
  lesson_date DATE NOT NULL,
  status ENUM('present','absent') NOT NULL,
  comment VARCHAR(255) DEFAULT '',
  FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
) ENGINE=InnoDB;

INSERT INTO users (login, password_hash, role) VALUES
('student', '$2y$12$FXZrs4xekymbuOowj09GY.O0zbV5MjjMO4e/ZvPKeiqBQDHVM.PEm', 'student'),
('teacher', '$2y$12$Nvauxl4wjDOXbc/suT8S9.ZuahFt7R62fNyNmQBBmROBXKxQDSlG2', 'teacher'),
('admin', '$2y$12$wH9UN.Vu0HcOys44TYo5pOCbdE1v1QmZz1Ey6HfckEDjBcdFM/ghq', 'admin');

INSERT INTO students (user_id, full_name, group_name) VALUES
(1, 'Иванов Иван Иванович', 'ИС-21'),
(NULL, 'Петров Пётр Петрович', 'ИС-21');

INSERT INTO grades (student_id, subject, grade, grade_date) VALUES
(1, 'PHP', 5, '2026-09-20'),
(1, 'Базы данных', 4, '2026-09-22'),
(2, 'PHP', 4, '2026-09-21');

INSERT INTO attendance (student_id, lesson_date, status, comment) VALUES
(1, '2026-09-23', 'present', 'Без замечаний'),
(2, '2026-09-23', 'absent', 'Болезнь');
