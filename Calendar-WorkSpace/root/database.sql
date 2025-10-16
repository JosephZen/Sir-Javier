CREATE DATABASE IF NOT EXISTS team_calendar;
USE team_calendar;

-- Users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tasks table
CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    start_time DATETIME NOT NULL,
    end_time DATETIME NOT NULL,
    progress INT DEFAULT 0,
    status ENUM('ongoing', 'almost-finished', 'help-requested', 'finished') DEFAULT 'ongoing',
    clickup_id VARCHAR(100) NULL,  -- For ClickUp integration
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id)
);

-- Task assignments (assigned to)
CREATE TABLE task_assignments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    task_id INT NOT NULL,
    user_id INT NOT NULL,
    FOREIGN KEY (task_id) REFERENCES tasks(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Task collaborators
CREATE TABLE task_collaborators (
    id INT AUTO_INCREMENT PRIMARY KEY,
    task_id INT NOT NULL,
    user_id INT NOT NULL,
    FOREIGN KEY (task_id) REFERENCES tasks(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Seed data: 5 mock users
INSERT INTO users (username, email, password) VALUES
('alice', 'alice@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),  -- password: 'password'
('bob', 'bob@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('charlie', 'charlie@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('diana', 'diana@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('admin', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');  -- For help notifications

-- Seed data: 20 mock tasks (spanning days, assigned/collaborators)
INSERT INTO tasks (title, start_time, end_time, progress, status, created_by, clickup_id) VALUES
('Design UI Kit', '2024-01-15 09:00:00', '2024-01-18 17:00:00', 50, 'ongoing', 1, 'CKP-123'),
('API Development', '2024-01-10 10:00:00', '2024-01-12 16:00:00', 80, 'almost-finished', 2, NULL),
('Bug Fix', '2024-01-20 14:00:00', '2024-01-20 15:00:00', 100, 'finished', 3, 'CKP-456'),
-- Add more as needed (up to 20 for demo)

-- Sample assignments
INSERT INTO task_assignments (task_id, user_id) VALUES (1, 1), (1, 2), (2, 2);

-- Sample collaborators
INSERT INTO task_collaborators (task_id, user_id) VALUES (1, 3), (2, 4);