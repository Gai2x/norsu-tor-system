-- =====================================================
-- ADD MISSING COLUMNS TO STUDENTS TABLE
-- =====================================================
ALTER TABLE students 
ADD COLUMN IF NOT EXISTS year_level VARCHAR(20) DEFAULT '1st Year' AFTER course,
ADD COLUMN IF NOT EXISTS contact_number VARCHAR(20) AFTER email,
ADD COLUMN IF NOT EXISTS role ENUM('super_admin', 'admin', 'user') DEFAULT 'user' AFTER password,
ADD COLUMN IF NOT EXISTS profile_picture VARCHAR(255) AFTER role,
ADD COLUMN IF NOT EXISTS created_at DATETIME DEFAULT CURRENT_TIMESTAMP AFTER profile_picture,
ADD COLUMN IF NOT EXISTS updated_at DATETIME ON UPDATE CURRENT_TIMESTAMP AFTER created_at;

UPDATE students SET role = 'admin' WHERE role = CHAR(115, 116, 97, 102, 102);
UPDATE students SET role = 'user' WHERE role = CHAR(115, 116, 117, 100, 101, 110, 116);

ALTER TABLE students
MODIFY role ENUM('super_admin', 'admin', 'user') DEFAULT 'user';

-- Add indexes for better performance
ALTER TABLE students 
ADD INDEX IF NOT EXISTS idx_email (email),
ADD INDEX IF NOT EXISTS idx_student_id (student_id),
ADD INDEX IF NOT EXISTS idx_course (course),
ADD INDEX IF NOT EXISTS idx_role (role);

-- add profile_picture column to store the path of the user's profile picture
ALTER TABLE students
ADD middle_name VARCHAR(100) NULL,
ADD dob DATE NULL,
ADD profile_image VARCHAR(255) NULL;

-- =====================================================
-- APPOINTMENTS TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    appointment_type VARCHAR(100) NOT NULL,
    appointment_date DATE NOT NULL,
    appointment_time TIME NOT NULL,
    duration INT DEFAULT 30,
    purpose TEXT,
    status ENUM('pending', 'confirmed', 'cancelled', 'completed') DEFAULT 'pending',
    admin_notes TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES students(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_appointment_date (appointment_date),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE appointments 
ADD advisor_name VARCHAR(255) NULL;

ALTER TABLE appointments
ADD COLUMN IF NOT EXISTS schedule_id INT NULL AFTER appointment_time,
ADD INDEX IF NOT EXISTS idx_schedule_id (schedule_id);

ALTER TABLE appointments
MODIFY status ENUM('pending', 'confirmed', 'approved', 'rejected', 'cancelled', 'completed') DEFAULT 'pending';

UPDATE appointments SET status = 'approved' WHERE status = 'confirmed';
UPDATE appointments SET status = LOWER(status);

ALTER TABLE appointments
MODIFY status ENUM('pending', 'approved', 'rejected', 'cancelled', 'completed') DEFAULT 'pending';

ALTER TABLE schedules
ADD COLUMN IF NOT EXISTS service_type VARCHAR(100) NULL AFTER office;

-- Create appointment_types table for available services
CREATE TABLE IF NOT EXISTS appointment_types (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    duration_minutes INT DEFAULT 30,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =====================================================
-- NOTIFICATIONS TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    type ENUM('request', 'appointment', 'system', 'announcement') DEFAULT 'system',
    is_read BOOLEAN DEFAULT FALSE,
    link VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES students(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_is_read (is_read)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- FEEDBACK TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS feedback (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    request_id INT,
    appointment_id INT,
    rating INT CHECK (rating >= 1 AND rating <= 5),
    comment TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (request_id) REFERENCES requests(id) ON DELETE SET NULL,
    FOREIGN KEY (appointment_id) REFERENCES appointments(id) ON DELETE SET NULL,
    INDEX idx_user_id (user_id),
    INDEX idx_rating (rating)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- SYSTEM_LOGS TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS system_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    action VARCHAR(100) NOT NULL,
    details TEXT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES students(id) ON DELETE SET NULL,
    INDEX idx_user_id (user_id),
    INDEX idx_action (action)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- ANNOUNCEMENTS TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS announcements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    target_audience ENUM('all', 'students', 'admin') DEFAULT 'all',
    is_active BOOLEAN DEFAULT TRUE,
    created_by INT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    expires_at DATETIME,
    FOREIGN KEY (created_by) REFERENCES students(id) ON DELETE SET NULL,
    INDEX idx_is_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- SERVICE_CATEGORIES TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS service_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(100) NOT NULL,
    description TEXT,
    icon_class VARCHAR(50),
    is_active BOOLEAN DEFAULT TRUE,
    display_order INT DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- SERVICES TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT,
    service_name VARCHAR(100) NOT NULL,
    description TEXT,
    estimated_processing_days INT DEFAULT 3,
    requirements TEXT,
    is_active BOOLEAN DEFAULT TRUE,
    display_order INT DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES service_categories(id) ON DELETE SET NULL,
    INDEX idx_category_id (category_id),
    INDEX idx_is_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- CREATE VIEWS FOR DASHBOARD
-- =====================================================

-- View for student dashboard statistics
CREATE OR REPLACE VIEW vw_student_dashboard_stats AS
SELECT 
    s.id as user_id,
    s.name,
    s.student_id,
    s.course,
    s.year_level,
    s.email,
    s.contact_number,
    COUNT(DISTINCT r.id) as total_requests,
    COUNT(DISTINCT CASE WHEN r.status = 'pending' THEN r.id END) as pending_requests,
    COUNT(DISTINCT CASE WHEN r.status = 'approved' THEN r.id END) as approved_requests,
    COUNT(DISTINCT CASE WHEN r.status = 'rejected' THEN r.id END) as rejected_requests,
    COUNT(DISTINCT CASE WHEN r.status = 'cancelled' THEN r.id END) as cancelled_requests,
    COUNT(DISTINCT a.id) as total_appointments,
    COUNT(DISTINCT CASE WHEN a.status IN ('pending', 'approved') AND a.appointment_date >= CURDATE() THEN a.id END) as upcoming_appointments,
    COUNT(DISTINCT CASE WHEN a.status = 'completed' THEN a.id END) as completed_appointments,
    COUNT(DISTINCT n.id) as unread_notifications,
    ROUND(
        CASE 
            WHEN COUNT(DISTINCT r.id) > 0 
            THEN (COUNT(DISTINCT CASE WHEN r.status IN ('approved', 'rejected') THEN r.id END) / COUNT(DISTINCT r.id)) * 100 
            ELSE 0 
        END, 1
    ) as completion_rate
FROM students s
LEFT JOIN requests r ON s.id = r.user_id
LEFT JOIN appointments a ON s.id = a.user_id
LEFT JOIN notifications n ON s.id = n.user_id AND n.is_read = FALSE
WHERE s.role = 'user'
GROUP BY s.id;

-- View for recent activity
CREATE OR REPLACE VIEW vw_recent_activity AS
SELECT 
    'request' as activity_type,
    r.id as reference_id,
    r.user_id,
    r.service_type as title,
    r.status,
    r.created_at
FROM requests r
UNION ALL
SELECT 
    'appointment' as activity_type,
    a.id as reference_id,
    a.user_id,
    a.appointment_type as title,
    a.status,
    a.created_at
FROM appointments a
ORDER BY created_at DESC;

-- =====================================================
-- CREATE STORED PROCEDURE FOR DASHBOARD DATA
-- =====================================================

DELIMITER //

CREATE PROCEDURE sp_get_user_dashboard_stats(IN p_user_id INT)
BEGIN
    -- Get request statistics
    SELECT 
        COUNT(*) as total_requests,
        SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_requests,
        SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved_requests,
        SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected_requests,
        SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled_requests
    FROM requests
    WHERE user_id = p_user_id;
    
    -- Get recent requests
    SELECT * FROM requests
    WHERE user_id = p_user_id
    ORDER BY created_at DESC
    LIMIT 5;
    
    -- Get upcoming appointments
    SELECT 
        id, 
        appointment_type, 
        appointment_date, 
        appointment_time, 
        purpose, 
        status
    FROM appointments
    WHERE user_id = p_user_id 
        AND appointment_date >= CURDATE() 
        AND status IN ('pending', 'approved')
    ORDER BY appointment_date ASC, appointment_time ASC
    LIMIT 5;
    
    -- Get unread notifications count
    SELECT COUNT(*) as unread_count
    FROM notifications
    WHERE user_id = p_user_id AND is_read = FALSE;
    
    -- Get user information
    SELECT 
        name, 
        email, 
        student_id, 
        course, 
        year_level, 
        contact_number,
        role
    FROM students
    WHERE id = p_user_id;
    
    -- Get completion rate
    SELECT 
        ROUND(
            CASE 
                WHEN COUNT(*) > 0 
                THEN (SUM(CASE WHEN status IN ('approved', 'rejected') THEN 1 ELSE 0 END) / COUNT(*)) * 100 
                ELSE 0 
            END, 1
        ) as completion_rate
    FROM requests
    WHERE user_id = p_user_id;
    
END //

DELIMITER ;

-- =====================================================
-- CREATE TRIGGERS
-- =====================================================

DELIMITER //

-- Trigger to log request submissions
CREATE TRIGGER trg_request_after_insert
AFTER INSERT ON requests
FOR EACH ROW
BEGIN
    INSERT INTO system_logs (user_id, action, details)
    VALUES (NEW.user_id, 'request_created', CONCAT('Created request for service: ', NEW.service_type));
    
    -- Create notification for user
    INSERT INTO notifications (user_id, title, message, type)
    VALUES (NEW.user_id, 'Request Submitted', 'Your request has been submitted successfully and is now pending review.', 'request');
END //

-- Trigger to log request status changes
CREATE TRIGGER trg_request_status_update
AFTER UPDATE ON requests
FOR EACH ROW
BEGIN
    IF OLD.status != NEW.status THEN
        INSERT INTO system_logs (user_id, action, details)
        VALUES (NEW.user_id, 'request_status_changed', CONCAT('Request status changed from ', OLD.status, ' to ', NEW.status));
        
        -- Create notification for status change
        INSERT INTO notifications (user_id, title, message, type)
        VALUES (NEW.user_id, 'Request Updated', CONCAT('Your request status has been updated to: ', UCASE(NEW.status)), 'request');
    END IF;
END //

-- Trigger to log appointment creations
CREATE TRIGGER trg_appointment_after_insert
AFTER INSERT ON appointments
FOR EACH ROW
BEGIN
    INSERT INTO system_logs (user_id, action, details)
    VALUES (NEW.user_id, 'appointment_created', CONCAT('Created appointment for: ', NEW.appointment_type));
    
    -- Create notification for appointment
    INSERT INTO notifications (user_id, title, message, type)
    VALUES (NEW.user_id, 'Appointment Scheduled', CONCAT('Your appointment for ', NEW.appointment_type, ' has been scheduled on ', NEW.appointment_date, ' at ', NEW.appointment_time), 'appointment');
END //

-- Trigger to log appointment status changes
CREATE TRIGGER trg_appointment_status_update
AFTER UPDATE ON appointments
FOR EACH ROW
BEGIN
    IF OLD.status != NEW.status THEN
        INSERT INTO system_logs (user_id, action, details)
        VALUES (NEW.user_id, 'appointment_status_changed', CONCAT('Appointment status changed from ', OLD.status, ' to ', NEW.status));
        
        -- Create notification for status change
        INSERT INTO notifications (user_id, title, message, type)
        VALUES (NEW.user_id, 'Appointment Updated', CONCAT('Your appointment status has been updated to: ', UCASE(NEW.status)), 'appointment');
    END IF;
END //

DELIMITER ;

-- =================================================================================================
-- SETTINGS TABLE
-- =================================================================================================
CREATE TABLE settings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    system_name VARCHAR(255),
    academic_year VARCHAR(50),
    maintenance_mode TINYINT DEFAULT 0,
    session_timeout INT DEFAULT 30
);


ALTER TABLE settings
ADD dark_mode TINYINT(1) DEFAULT 0;


