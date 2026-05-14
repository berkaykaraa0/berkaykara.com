-- ============================================================
-- Portfolio Database Schema
-- ============================================================

CREATE DATABASE IF NOT EXISTS portfolio_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE portfolio_db;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    role ENUM('admin', 'editor') DEFAULT 'admin',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Default admin password: Admin@123
INSERT INTO users (username, email, password, full_name, role) VALUES
('admin', 'admin@portfolio.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin User', 'admin');

CREATE TABLE IF NOT EXISTS projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    description TEXT NOT NULL,
    long_description TEXT,
    technologies VARCHAR(255) NOT NULL,
    category ENUM('web', 'backend', 'fullstack', 'mobile', 'other') DEFAULT 'web',
    image VARCHAR(255) DEFAULT 'default.jpg',
    github_url VARCHAR(255),
    live_url VARCHAR(255),
    featured TINYINT(1) DEFAULT 0,
    sort_order INT DEFAULT 0,
    status ENUM('active', 'hidden') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO projects (title, description, long_description, technologies, category, image, github_url, live_url, featured, sort_order) VALUES
('E-Commerce Platform', 'A full-featured online store with cart, payments and admin panel.', 'Built a complete e-commerce solution with product management, shopping cart, Stripe payments, order tracking, and an admin dashboard for inventory control.', 'PHP, MySQL, JavaScript, CSS3', 'fullstack', 'default.jpg', 'https://github.com', 'https://demo.com', 1, 1),
('Task Manager App', 'Collaborative task management tool with real-time updates.', 'A Trello-inspired task manager supporting drag-and-drop boards, team collaboration, due dates, file attachments, and email notifications.', 'React, Node.js, MySQL', 'web', 'default.jpg', 'https://github.com', 'https://demo.com', 1, 2),
('REST API Service', 'Scalable RESTful API with JWT authentication and rate limiting.', 'Designed a production-grade REST API with JWT auth, role-based access, rate limiting, request logging, and full OpenAPI documentation.', 'PHP, MySQL, JWT', 'backend', 'default.jpg', 'https://github.com', NULL, 0, 3),
('Portfolio Website', 'Personal portfolio built with PHP, MySQL and vanilla JS.', 'This very website — a dynamic portfolio with admin dashboard, AJAX-driven content, dark mode, and a contact system backed by MySQL.', 'PHP, MySQL, CSS3, JavaScript', 'fullstack', 'default.jpg', 'https://github.com', 'https://demo.com', 1, 4),
('Weather Dashboard', 'Real-time weather app consuming multiple public APIs.', 'Fetches live weather data from OpenWeatherMap, displays 7-day forecasts, radar maps, and lets users save favourite locations via localStorage.', 'JavaScript, CSS3, OpenWeatherMap API', 'web', 'default.jpg', 'https://github.com', 'https://demo.com', 0, 5),
('Blog CMS', 'Custom content management system with Markdown support.', 'A lightweight CMS with Markdown editor, category/tag management, comment moderation, SEO meta fields, and RSS feed generation.', 'PHP, MySQL, JavaScript', 'fullstack', 'default.jpg', 'https://github.com', NULL, 0, 6);

CREATE TABLE IF NOT EXISTS contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    subject VARCHAR(200),
    message TEXT NOT NULL,
    is_read TINYINT(1) DEFAULT 0,
    replied TINYINT(1) DEFAULT 0,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO contacts (name, email, subject, message, is_read) VALUES
('Alice Johnson', 'alice@example.com', 'Job Opportunity', 'Hi! I came across your portfolio and I am very impressed with your work. We have a full-stack position open. Would you be interested in a quick call?', 1),
('Bob Smith', 'bob@example.com', 'Project Collaboration', 'Hey, I have an exciting freelance project and think your skills are a great fit. Let me know if you are available.', 0),
('Carol White', 'carol@example.com', 'Portfolio Feedback', 'Your portfolio looks amazing! The design and projects are really impressive. Keep up the great work!', 1);

CREATE TABLE IF NOT EXISTS skills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(80) NOT NULL,
    category ENUM('frontend', 'backend', 'database', 'tools') NOT NULL,
    proficiency INT NOT NULL DEFAULT 70,
    sort_order INT DEFAULT 0,
    status ENUM('active', 'hidden') DEFAULT 'active'
);

INSERT INTO skills (name, category, proficiency, sort_order) VALUES
('HTML5', 'frontend', 90, 1), ('CSS3', 'frontend', 88, 2), ('JavaScript', 'frontend', 82, 3),
('React', 'frontend', 65, 4), ('Bootstrap', 'frontend', 85, 5),
('PHP 8', 'backend', 80, 1), ('Node.js', 'backend', 60, 2), ('REST APIs', 'backend', 78, 3),
('MySQL', 'database', 80, 1), ('SQLite', 'database', 70, 2),
('Git', 'tools', 85, 1), ('Docker', 'tools', 72, 2), ('Linux', 'tools', 75, 3), ('GitHub Actions', 'tools', 60, 4);

-- Add portfolio project
INSERT INTO projects (title, description, long_description, technologies, category, image, github_url, live_url, featured, sort_order) VALUES
('Personal Portfolio', 'My personal portfolio website built with PHP, MySQL and vanilla JS.', 'A dynamic portfolio website featuring glassmorphism design, admin dashboard, AJAX-driven content, dark/light mode toggle, and a contact system backed by MySQL. Built as both a university project and real-world showcase.', 'PHP, MySQL, CSS3, JavaScript', 'fullstack', 'default.jpg', 'https://github.com/berkaykaraa0', NULL, 1, 7);
