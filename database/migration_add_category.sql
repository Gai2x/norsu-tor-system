-- Add category column to requests table
ALTER TABLE `requests` ADD COLUMN `category` ENUM('Document Request', 'Consultation', 'Enrollment Concern', 'Grade Concern', 'Other') DEFAULT 'Document Request' AFTER `service_type`;

-- Add category column to appointments table
ALTER TABLE `appointments` ADD COLUMN `category` ENUM('Document Request', 'Consultation', 'Enrollment Concern', 'Grade Concern', 'Other') DEFAULT 'Consultation' AFTER `appointment_type`;

-- Add category column to one_time_requests table if it exists
ALTER TABLE `one_time_requests` ADD COLUMN `category` ENUM('Document Request', 'Consultation', 'Enrollment Concern', 'Grade Concern', 'Other') DEFAULT 'Document Request' AFTER `service_type`;

-- Create index for faster filtering
CREATE INDEX `idx_requests_category` ON `requests`(`category`);
CREATE INDEX `idx_appointments_category` ON `appointments`(`category`);
