USE norsu_system;

ALTER TABLE students
    ADD COLUMN IF NOT EXISTS role ENUM('super_admin', 'admin', 'student') NOT NULL DEFAULT 'student' AFTER password,
    ADD INDEX IF NOT EXISTS idx_students_role (role);

UPDATE students SET role = 'admin' WHERE role = CHAR(115, 116, 97, 102, 102);
UPDATE students SET role = 'student' WHERE role = CHAR(115, 116, 117, 100, 101, 110, 116);

ALTER TABLE students
    MODIFY role ENUM('super_admin', 'admin', 'student') NOT NULL DEFAULT 'student';

CREATE TABLE IF NOT EXISTS admin_action_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    actor_id INT NULL,
    action VARCHAR(100) NOT NULL,
    target_type VARCHAR(100) NULL,
    target_id INT NULL,
    details TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_actor_id (actor_id),
    INDEX idx_created_at (created_at)
);

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

-- Promote one existing account manually after choosing the correct id:
-- UPDATE students SET role = 'super_admin' WHERE id = 1;
