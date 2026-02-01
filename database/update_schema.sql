-- =====================================================
-- Octal Foundry - Schema Updates for New Onboarding
-- =====================================================

-- Add project_task column to roadmaps table (previous update)
ALTER TABLE roadmaps
ADD COLUMN IF NOT EXISTS project_task TEXT DEFAULT NULL AFTER week_description;

-- Create submissions table (previous update)
CREATE TABLE IF NOT EXISTS submissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    roadmap_id INT NOT NULL,
    week_number INT NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    file_type VARCHAR(50),
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (roadmap_id) REFERENCES roadmaps(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- NEW: Student Profile Fields
-- =====================================================
ALTER TABLE users ADD COLUMN IF NOT EXISTS course_name VARCHAR(255) DEFAULT NULL;
ALTER TABLE users ADD COLUMN IF NOT EXISTS year_of_study INT DEFAULT 1;
ALTER TABLE users ADD COLUMN IF NOT EXISTS current_semester INT DEFAULT 1;
ALTER TABLE users ADD COLUMN IF NOT EXISTS interests TEXT DEFAULT NULL;

-- =====================================================
-- NEW: Unit Uploads Table (from file upload)
-- =====================================================
CREATE TABLE IF NOT EXISTS unit_uploads (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    unit_code VARCHAR(50),
    unit_name VARCHAR(255) NOT NULL,
    semester INT DEFAULT NULL,
    is_practical BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- NEW: AI Recommended Curriculum
-- =====================================================
CREATE TABLE IF NOT EXISTS recommended_curriculum (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    practical_course_name VARCHAR(255) NOT NULL,
    practical_course_description TEXT,
    skill_category VARCHAR(100),
    recommended_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pending', 'accepted', 'rejected') DEFAULT 'pending',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
