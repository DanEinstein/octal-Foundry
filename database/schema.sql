-- Octal Foundry Database Schema
-- MySQL 8.0+
-- Run: mysql -u root -p < schema.sql

-- Create database
CREATE DATABASE IF NOT EXISTS octal_foundry
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE octal_foundry;

-- ============================================
-- USERS TABLE
-- Stores registered student accounts
-- ============================================
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    university VARCHAR(255) DEFAULT 'University of Nairobi',
    course VARCHAR(255) DEFAULT NULL,
    year_of_study INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_email (email)
) ENGINE=InnoDB;

-- ============================================
-- UNITS TABLE
-- Course units enrolled by students
-- ============================================
CREATE TABLE IF NOT EXISTS units (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    unit_code VARCHAR(50) NOT NULL,
    unit_name VARCHAR(255) NOT NULL,
    lecturer_name VARCHAR(255) DEFAULT NULL,
    semester VARCHAR(50) DEFAULT NULL,
    year INT DEFAULT NULL,
    status ENUM('not_started', 'in_progress', 'completed') DEFAULT 'not_started',
    progress_percent INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_status (status)
) ENGINE=InnoDB;

-- ============================================
-- ROADMAPS TABLE
-- 12-week learning roadmap per unit
-- ============================================
CREATE TABLE IF NOT EXISTS roadmaps (
    id INT AUTO_INCREMENT PRIMARY KEY,
    unit_id INT NOT NULL,
    week_number INT NOT NULL,
    week_title VARCHAR(255) NOT NULL,
    week_description TEXT DEFAULT NULL,
    topics JSON DEFAULT NULL,
    status ENUM('locked', 'current', 'completed') DEFAULT 'locked',
    tasks_completed INT DEFAULT 0,
    tasks_total INT DEFAULT 4,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (unit_id) REFERENCES units(id) ON DELETE CASCADE,
    UNIQUE KEY unique_unit_week (unit_id, week_number),
    INDEX idx_unit_id (unit_id),
    INDEX idx_status (status)
) ENGINE=InnoDB;

-- ============================================
-- VIDEOS TABLE
-- YouTube videos curated for each roadmap week
-- ============================================
CREATE TABLE IF NOT EXISTS videos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    roadmap_id INT NOT NULL,
    video_id VARCHAR(50) NOT NULL,
    title VARCHAR(500) NOT NULL,
    channel_name VARCHAR(255) DEFAULT NULL,
    thumbnail_url VARCHAR(500) DEFAULT NULL,
    duration VARCHAR(20) DEFAULT NULL,
    view_count VARCHAR(50) DEFAULT NULL,
    description TEXT DEFAULT NULL,
    position INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (roadmap_id) REFERENCES roadmaps(id) ON DELETE CASCADE,
    INDEX idx_roadmap_id (roadmap_id),
    INDEX idx_position (position)
) ENGINE=InnoDB;

-- ============================================
-- USER_PROGRESS TABLE
-- Track user progress through videos and tasks
-- ============================================
CREATE TABLE IF NOT EXISTS user_progress (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    video_id INT DEFAULT NULL,
    roadmap_id INT DEFAULT NULL,
    is_completed BOOLEAN DEFAULT FALSE,
    watch_time_seconds INT DEFAULT 0,
    completed_at TIMESTAMP NULL,
    notes TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (video_id) REFERENCES videos(id) ON DELETE SET NULL,
    FOREIGN KEY (roadmap_id) REFERENCES roadmaps(id) ON DELETE SET NULL,
    UNIQUE KEY unique_user_video (user_id, video_id),
    INDEX idx_user_id (user_id)
) ENGINE=InnoDB;

-- ============================================
-- CERTIFICATES TABLE
-- Certificates earned upon unit completion
-- ============================================
CREATE TABLE IF NOT EXISTS certificates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    unit_id INT NOT NULL,
    certificate_code VARCHAR(50) NOT NULL UNIQUE,
    title VARCHAR(255) NOT NULL,
    skills JSON DEFAULT NULL,
    issued_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (unit_id) REFERENCES units(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_certificate_code (certificate_code)
) ENGINE=InnoDB;

-- ============================================
-- Insert test user for development
-- Password: password123 (bcrypt hashed)
-- ============================================
INSERT INTO users (full_name, email, password_hash, university, course, year_of_study) 
VALUES (
    'John Kamau',
    'john.kamau@uon.ac.ke',
    '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/X4.LJGdYV3oC5cVoS',
    'University of Nairobi',
    'Computer Science',
    3
) ON DUPLICATE KEY UPDATE full_name = full_name;
