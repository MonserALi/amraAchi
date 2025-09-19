-- Create database
CREATE DATABASE IF NOT EXISTS `niramoy_health` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE `niramoy_health`;

-- Drop existing tables (for clean installation)
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `system_issues`,
`doctor_verification_requests`,
`patient_reports`,
`doctor_departments`,
`departments`,
`doctor_ratings`;

SET FOREIGN_KEY_CHECKS = 1;

-- Core Tables
CREATE TABLE `divisions` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(50) NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`),
    UNIQUE KEY `name` (`name`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE `districts` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(50) NOT NULL,
    `division_id` int(11) NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`),
    UNIQUE KEY `name` (`name`),
    KEY `division_id` (`division_id`),
    CONSTRAINT `districts_ibfk_1` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE `users` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(100) NOT NULL,
    `email` varchar(100) NOT NULL,
    `password` varchar(255) NOT NULL,
    `phone` varchar(20) DEFAULT NULL,
    `address` text DEFAULT NULL,
    `date_of_birth` date DEFAULT NULL,
    `gender` enum('male', 'female', 'other') DEFAULT NULL,
    `blood_group` enum(
        'A+',
        'A-',
        'B+',
        'B-',
        'AB+',
        'AB-',
        'O+',
        'O-'
    ) DEFAULT NULL,
    `profile_image` varchar(255) DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`),
    UNIQUE KEY `email` (`email`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE `roles` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(50) NOT NULL,
    `description` text DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`),
    UNIQUE KEY `name` (`name`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE `user_roles` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11) NOT NULL,
    `role_id` int(11) NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`),
    UNIQUE KEY `user_role_unique` (`user_id`, `role_id`),
    KEY `role_id` (`role_id`),
    CONSTRAINT `user_roles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `user_roles_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE `permissions` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(100) NOT NULL,
    `description` text DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`),
    UNIQUE KEY `name` (`name`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE `role_permissions` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `role_id` int(11) NOT NULL,
    `permission_id` int(11) NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`),
    UNIQUE KEY `role_permission_unique` (`role_id`, `permission_id`),
    KEY `permission_id` (`permission_id`),
    CONSTRAINT `role_permissions_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
    CONSTRAINT `role_permissions_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE `hospitals` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(100) NOT NULL,
    `description` text DEFAULT NULL,
    `address` varchar(255) NOT NULL,
    `district` varchar(50) NOT NULL,
    `division` varchar(50) NOT NULL,
    `latitude` decimal(10, 8) NOT NULL,
    `longitude` decimal(11, 8) NOT NULL,
    `phone` varchar(20) DEFAULT NULL,
    `email` varchar(100) DEFAULT NULL,
    `website` varchar(255) DEFAULT NULL,
    `hospital_type` enum(
        'government',
        'private',
        'diagnostic',
        'specialized'
    ) DEFAULT 'private',
    `is_active` tinyint(1) DEFAULT 1,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE `hospital_admins` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `hospital_id` int(11) NOT NULL,
    `admin_id` int(11) NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`),
    UNIQUE KEY `hospital_admin_unique` (`hospital_id`, `admin_id`),
    KEY `admin_id` (`admin_id`),
    CONSTRAINT `hospital_admins_ibfk_1` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE CASCADE,
    CONSTRAINT `hospital_admins_ibfk_2` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE `departments` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(150) NOT NULL,
    `description` text DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_departments_name` (`name`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE `doctors` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11) NOT NULL,
    `bmdc_code` varchar(20) NOT NULL,
    `specialization` varchar(100) DEFAULT NULL,
    `experience_years` int(11) DEFAULT 0,
    `consultation_fee` decimal(10, 2) DEFAULT 0.00,
    `verification_status` enum(
        'pending',
        'verified',
        'rejected'
    ) DEFAULT 'pending',
    `verification_document` varchar(255) DEFAULT NULL,
    `verified_hospital_id` int(11) DEFAULT NULL,
    `verification_date` timestamp NULL DEFAULT NULL,
    `rejection_reason` text DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`),
    UNIQUE KEY `bmdc_code` (`bmdc_code`),
    KEY `user_id` (`user_id`),
    KEY `verified_hospital_id` (`verified_hospital_id`),
    CONSTRAINT `doctors_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `doctors_ibfk_2` FOREIGN KEY (`verified_hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE SET NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE `doctor_departments` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `doctor_id` int(11) NOT NULL,
    `department_id` int(11) NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_doctor_department_doctor` (`doctor_id`),
    KEY `idx_department` (`department_id`),
    CONSTRAINT `fk_dd_doctor` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_dd_department` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE `doctor_verification_requests` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `doctor_id` int(11) NOT NULL,
    `hospital_id` int(11) NOT NULL,
    `requested_by` enum('doctor', 'admin') NOT NULL,
    `requested_by_id` int(11) NOT NULL,
    `status` enum(
        'pending',
        'approved',
        'rejected'
    ) DEFAULT 'pending',
    `message` text DEFAULT NULL,
    `response_message` text DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`),
    KEY `doctor_id` (`doctor_id`),
    KEY `hospital_id` (`hospital_id`),
    KEY `requested_by_id` (`requested_by_id`),
    CONSTRAINT `doctor_verification_requests_ibfk_1` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE,
    CONSTRAINT `doctor_verification_requests_ibfk_2` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE CASCADE,
    CONSTRAINT `doctor_verification_requests_ibfk_3` FOREIGN KEY (`requested_by_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE `doctor_ratings` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `doctor_id` int(11) NOT NULL,
    `user_id` int(11) DEFAULT NULL,
    `rating` tinyint(1) NOT NULL CHECK (`rating` BETWEEN 1 AND 5),
    `comment` text DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_dr_doctor` (`doctor_id`),
    KEY `user_id` (`user_id`),
    CONSTRAINT `fk_dr_doctor` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_dr_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE `doctor_hospitals` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `doctor_id` int(11) NOT NULL,
    `hospital_id` int(11) NOT NULL,
    `status` enum(
        'pending',
        'accepted',
        'rejected'
    ) DEFAULT 'pending',
    `requested_by` enum('doctor', 'admin') NOT NULL,
    `requested_by_id` int(11) NOT NULL,
    `request_date` timestamp NOT NULL DEFAULT current_timestamp(),
    `response_date` timestamp NULL DEFAULT NULL,
    `response_message` text DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `doctor_hospital_unique` (`doctor_id`, `hospital_id`),
    KEY `requested_by_id` (`requested_by_id`),
    KEY `idx_doctor_status` (`doctor_id`, `status`),
    KEY `idx_hospital_status` (`hospital_id`, `status`),
    CONSTRAINT `doctor_hospitals_ibfk_1` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE,
    CONSTRAINT `doctor_hospitals_ibfk_2` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE CASCADE,
    CONSTRAINT `doctor_hospitals_ibfk_3` FOREIGN KEY (`requested_by_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE `doctor_slots` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `doctor_id` int(11) NOT NULL,
    `hospital_id` int(11) NOT NULL,
    `day_of_week` enum(
        'saturday',
        'sunday',
        'monday',
        'tuesday',
        'wednesday',
        'thursday',
        'friday'
    ) NOT NULL,
    `start_time` time NOT NULL,
    `end_time` time NOT NULL,
    `max_patients` int(11) DEFAULT 10,
    `is_active` tinyint(1) DEFAULT 1,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`),
    KEY `doctor_id` (`doctor_id`),
    KEY `hospital_id` (`hospital_id`),
    CONSTRAINT `doctor_slots_ibfk_1` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE,
    CONSTRAINT `doctor_slots_ibfk_2` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE CASCADE,
    CONSTRAINT `chk_time_order` CHECK (`end_time` > `start_time`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE `nurses` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11) NOT NULL,
    `is_daycare` tinyint(1) DEFAULT 0,
    `is_compounder` tinyint(1) DEFAULT 0,
    `specialization` varchar(100) DEFAULT NULL,
    `license_number` varchar(50) DEFAULT NULL,
    `verification_status` enum(
        'pending',
        'verified',
        'rejected'
    ) DEFAULT 'pending',
    `verified_hospital_id` int(11) DEFAULT NULL,
    `verification_date` timestamp NULL DEFAULT NULL,
    `rejection_reason` text DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`),
    KEY `user_id` (`user_id`),
    KEY `verified_hospital_id` (`verified_hospital_id`),
    CONSTRAINT `nurses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `nurses_ibfk_2` FOREIGN KEY (`verified_hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE SET NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE `nurse_verification_requests` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `nurse_id` int(11) NOT NULL,
    `hospital_id` int(11) NOT NULL,
    `requested_by` enum('nurse', 'hospital') NOT NULL,
    `requested_by_id` int(11) NOT NULL,
    `status` enum(
        'pending',
        'approved',
        'rejected'
    ) DEFAULT 'pending',
    `message` text DEFAULT NULL,
    `response_message` text DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`),
    KEY `nurse_id` (`nurse_id`),
    KEY `hospital_id` (`hospital_id`),
    KEY `requested_by_id` (`requested_by_id`),
    CONSTRAINT `nurse_verification_requests_ibfk_1` FOREIGN KEY (`nurse_id`) REFERENCES `nurses` (`id`) ON DELETE CASCADE,
    CONSTRAINT `nurse_verification_requests_ibfk_2` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE CASCADE,
    CONSTRAINT `nurse_verification_requests_ibfk_3` FOREIGN KEY (`requested_by_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE `doctor_compounders` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `doctor_id` int(11) NOT NULL,
    `nurse_id` int(11) NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`),
    UNIQUE KEY `doctor_nurse_unique` (`doctor_id`, `nurse_id`),
    KEY `nurse_id` (`nurse_id`),
    CONSTRAINT `doctor_compounders_ibfk_1` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE,
    CONSTRAINT `doctor_compounders_ibfk_2` FOREIGN KEY (`nurse_id`) REFERENCES `nurses` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE `nurse_slots` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `nurse_id` int(11) NOT NULL,
    `hospital_id` int(11) NOT NULL,
    `date` date NOT NULL,
    `start_time` time NOT NULL,
    `end_time` time NOT NULL,
    `purpose` enum(
        'daycare',
        'compounder_support'
    ) NOT NULL,
    `max_patients` int(11) DEFAULT 5,
    `is_active` tinyint(1) DEFAULT 1,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`),
    KEY `nurse_id` (`nurse_id`),
    KEY `hospital_id` (`hospital_id`),
    CONSTRAINT `nurse_slots_ibfk_1` FOREIGN KEY (`nurse_id`) REFERENCES `nurses` (`id`) ON DELETE CASCADE,
    CONSTRAINT `nurse_slots_ibfk_2` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE CASCADE,
    CONSTRAINT `chk_nurse_time_order` CHECK (`end_time` > `start_time`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE `appointment_slots` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `doctor_id` int(11) NOT NULL,
    `hospital_id` int(11) NOT NULL,
    `start_time` datetime NOT NULL,
    `end_time` datetime NOT NULL,
    `max_patients` int(11) DEFAULT 10,
    `is_active` tinyint(1) DEFAULT 1,
    `created_by` int(11) NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`),
    KEY `created_by` (`created_by`),
    KEY `idx_doctor_time` (
        `doctor_id`,
        `start_time`,
        `end_time`
    ),
    KEY `idx_hospital_time` (
        `hospital_id`,
        `start_time`,
        `end_time`
    ),
    CONSTRAINT `appointment_slots_ibfk_1` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE,
    CONSTRAINT `appointment_slots_ibfk_2` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE CASCADE,
    CONSTRAINT `appointment_slots_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `chk_appointment_time_order` CHECK (`end_time` > `start_time`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE `appointments` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `patient_id` int(11) NOT NULL,
    `doctor_id` int(11) NOT NULL,
    `hospital_id` int(11) NOT NULL,
    `slot_id` int(11) NOT NULL,
    `appointment_date` date NOT NULL,
    `appointment_time` time NOT NULL,
    `status` enum(
        'pending',
        'confirmed',
        'in_progress',
        'completed',
        'cancelled',
        'no_show'
    ) DEFAULT 'pending',
    `symptoms` text DEFAULT NULL,
    `notes` text DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`),
    KEY `patient_id` (`patient_id`),
    KEY `doctor_id` (`doctor_id`),
    KEY `hospital_id` (`hospital_id`),
    KEY `slot_id` (`slot_id`),
    KEY `idx_appointment_datetime` (
        `appointment_date`,
        `appointment_time`
    ),
    CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE,
    CONSTRAINT `appointments_ibfk_3` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE CASCADE,
    CONSTRAINT `appointments_ibfk_4` FOREIGN KEY (`slot_id`) REFERENCES `doctor_slots` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE `patient_vitals` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `appointment_id` int(11) NOT NULL,
    `patient_id` int(11) NOT NULL,
    `recorded_by` int(11) NOT NULL,
    `blood_pressure_systolic` int(11) DEFAULT NULL CHECK (`blood_pressure_systolic` > 0),
    `blood_pressure_diastolic` int(11) DEFAULT NULL CHECK (
        `blood_pressure_diastolic` > 0
    ),
    `spo2` decimal(5, 2) DEFAULT NULL CHECK (`spo2` BETWEEN 0 AND 100),
    `pulse` int(11) DEFAULT NULL CHECK (`pulse` > 0),
    `temperature` decimal(5, 2) DEFAULT NULL CHECK (`temperature` > 0),
    `weight` decimal(5, 2) DEFAULT NULL CHECK (`weight` > 0),
    `height` decimal(5, 2) DEFAULT NULL CHECK (`height` > 0),
    `symptoms` text DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`),
    KEY `appointment_id` (`appointment_id`),
    KEY `patient_id` (`patient_id`),
    KEY `recorded_by` (`recorded_by`),
    CONSTRAINT `patient_vitals_ibfk_1` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE CASCADE,
    CONSTRAINT `patient_vitals_ibfk_2` FOREIGN KEY (`patient_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `patient_vitals_ibfk_3` FOREIGN KEY (`recorded_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE `prescriptions` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `appointment_id` int(11) NOT NULL,
    `patient_id` int(11) NOT NULL,
    `doctor_id` int(11) NOT NULL,
    `diagnosis` text DEFAULT NULL,
    `notes` text DEFAULT NULL,
    `follow_up_date` date DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`),
    KEY `appointment_id` (`appointment_id`),
    KEY `patient_id` (`patient_id`),
    KEY `doctor_id` (`doctor_id`),
    CONSTRAINT `prescriptions_ibfk_1` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE CASCADE,
    CONSTRAINT `prescriptions_ibfk_2` FOREIGN KEY (`patient_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `prescriptions_ibfk_3` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- Migration: add status column to prescriptions (run this if your DB is already created)
ALTER TABLE `prescriptions`
ADD COLUMN `status` ENUM(
    'active',
    'completed',
    'cancelled'
) NOT NULL DEFAULT 'active';

CREATE TABLE `prescription_items` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `prescription_id` int(11) NOT NULL,
    `medicine_name` varchar(100) NOT NULL,
    `dosage` varchar(50) DEFAULT NULL,
    `frequency` varchar(50) DEFAULT NULL,
    `duration` varchar(50) DEFAULT NULL,
    `instructions` text DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`),
    KEY `prescription_id` (`prescription_id`),
    CONSTRAINT `prescription_items_ibfk_1` FOREIGN KEY (`prescription_id`) REFERENCES `prescriptions` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE `patient_reports` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `patient_id` int(11) NOT NULL,
    `uploaded_by` int(11) NOT NULL,
    `report_type` varchar(50) DEFAULT NULL,
    `title` varchar(255) NOT NULL,
    `description` text DEFAULT NULL,
    `file_path` varchar(255) NOT NULL,
    `is_confidential` tinyint(1) DEFAULT 0,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`),
    KEY `patient_id` (`patient_id`),
    KEY `uploaded_by` (`uploaded_by`),
    CONSTRAINT `patient_reports_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `patient_reports_ibfk_2` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE `diseases` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(100) NOT NULL,
    `description` text DEFAULT NULL,
    `is_epidemic` tinyint(1) DEFAULT 0,
    `symptoms` text DEFAULT NULL,
    `prevention` text DEFAULT NULL,
    `treatment` text DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`),
    UNIQUE KEY `name` (`name`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE `patient_diseases` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `patient_id` int(11) NOT NULL,
    `disease_id` int(11) NOT NULL,
    `diagnosis_date` date NOT NULL,
    `status` enum(
        'active',
        'recovered',
        'chronic'
    ) DEFAULT 'active',
    `notes` text DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`),
    KEY `patient_id` (`patient_id`),
    KEY `disease_id` (`disease_id`),
    CONSTRAINT `patient_diseases_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `patient_diseases_ibfk_2` FOREIGN KEY (`disease_id`) REFERENCES `diseases` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE `daycare_procedures` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(100) NOT NULL,
    `description` text DEFAULT NULL,
    `duration_minutes` int(11) DEFAULT 30,
    `price` decimal(10, 2) DEFAULT 0.00,
    `hospital_id` int(11) NOT NULL,
    `is_active` tinyint(1) DEFAULT 1,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`),
    KEY `hospital_id` (`hospital_id`),
    CONSTRAINT `daycare_procedures_ibfk_1` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE `daycare_bookings` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `patient_id` int(11) NOT NULL,
    `nurse_id` int(11) NOT NULL,
    `hospital_id` int(11) NOT NULL,
    `procedure_id` int(11) NOT NULL,
    `slot_id` int(11) NOT NULL,
    `booking_date` date NOT NULL,
    `status` enum(
        'pending',
        'approved',
        'rejected',
        'completed',
        'cancelled'
    ) DEFAULT 'pending',
    `nid_document` varchar(255) DEFAULT NULL,
    `other_documents` text DEFAULT NULL,
    `notes` text DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`),
    KEY `patient_id` (`patient_id`),
    KEY `nurse_id` (`nurse_id`),
    KEY `hospital_id` (`hospital_id`),
    KEY `procedure_id` (`procedure_id`),
    KEY `slot_id` (`slot_id`),
    CONSTRAINT `daycare_bookings_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `daycare_bookings_ibfk_2` FOREIGN KEY (`nurse_id`) REFERENCES `nurses` (`id`) ON DELETE CASCADE,
    CONSTRAINT `daycare_bookings_ibfk_3` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE CASCADE,
    CONSTRAINT `daycare_bookings_ibfk_4` FOREIGN KEY (`procedure_id`) REFERENCES `daycare_procedures` (`id`) ON DELETE CASCADE,
    CONSTRAINT `daycare_bookings_ibfk_5` FOREIGN KEY (`slot_id`) REFERENCES `nurse_slots` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE `chats` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `sender_id` int(11) NOT NULL,
    `receiver_id` int(11) NOT NULL,
    `message` text NOT NULL,
    `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
    `is_read` tinyint(1) DEFAULT 0,
    `related_appointment_id` int(11) DEFAULT NULL,
    `related_daycare_booking_id` int(11) DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `receiver_id` (`receiver_id`),
    KEY `idx_sender_receiver` (`sender_id`, `receiver_id`),
    KEY `idx_appointment` (`related_appointment_id`),
    KEY `idx_daycare_booking` (`related_daycare_booking_id`),
    CONSTRAINT `chats_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `chats_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `chats_ibfk_3` FOREIGN KEY (`related_appointment_id`) REFERENCES `appointments` (`id`) ON DELETE SET NULL,
    CONSTRAINT `chats_ibfk_4` FOREIGN KEY (`related_daycare_booking_id`) REFERENCES `daycare_bookings` (`id`) ON DELETE SET NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE `icu_beds` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `hospital_id` int(11) NOT NULL,
    `bed_number` varchar(20) NOT NULL,
    `is_occupied` tinyint(1) DEFAULT 0,
    `current_patient_id` int(11) DEFAULT NULL,
    `ventilator` tinyint(1) DEFAULT 0,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`),
    UNIQUE KEY `hospital_bed_unique` (`hospital_id`, `bed_number`),
    KEY `current_patient_id` (`current_patient_id`),
    CONSTRAINT `icu_beds_ibfk_1` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE CASCADE,
    CONSTRAINT `icu_beds_ibfk_2` FOREIGN KEY (`current_patient_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE `ambulances` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `hospital_id` int(11) NOT NULL,
    `vehicle_number` varchar(20) NOT NULL,
    `driver_name` varchar(100) DEFAULT NULL,
    `driver_phone` varchar(20) DEFAULT NULL,
    `is_available` tinyint(1) DEFAULT 1,
    `latitude` decimal(10, 8) DEFAULT NULL,
    `longitude` decimal(11, 8) DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`),
    UNIQUE KEY `vehicle_number` (`vehicle_number`),
    KEY `hospital_id` (`hospital_id`),
    CONSTRAINT `ambulances_ibfk_1` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE `ambulance_trips` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `ambulance_id` int(11) NOT NULL,
    `patient_id` int(11) NOT NULL,
    `pickup_location` varchar(255) NOT NULL,
    `pickup_latitude` decimal(10, 8) NOT NULL,
    `pickup_longitude` decimal(11, 8) NOT NULL,
    `destination_location` varchar(255) NOT NULL,
    `destination_latitude` decimal(10, 8) NOT NULL,
    `destination_longitude` decimal(11, 8) NOT NULL,
    `distance_km` decimal(10, 2) NOT NULL,
    `fare` decimal(10, 2) NOT NULL,
    `status` enum(
        'pending',
        'assigned',
        'in_progress',
        'completed',
        'cancelled'
    ) DEFAULT 'pending',
    `requested_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `assigned_at` timestamp NULL DEFAULT NULL,
    `started_at` timestamp NULL DEFAULT NULL,
    `completed_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `ambulance_id` (`ambulance_id`),
    KEY `patient_id` (`patient_id`),
    CONSTRAINT `ambulance_trips_ibfk_1` FOREIGN KEY (`ambulance_id`) REFERENCES `ambulances` (`id`) ON DELETE CASCADE,
    CONSTRAINT `ambulance_trips_ibfk_2` FOREIGN KEY (`patient_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE `ambulance_paths` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `trip_id` int(11) NOT NULL,
    `latitude` decimal(10, 8) NOT NULL,
    `longitude` decimal(11, 8) NOT NULL,
    `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`),
    KEY `trip_id` (`trip_id`),
    CONSTRAINT `ambulance_paths_ibfk_1` FOREIGN KEY (`trip_id`) REFERENCES `ambulance_trips` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE `invoices` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `patient_id` int(11) NOT NULL,
    `invoice_number` varchar(50) NOT NULL,
    `issue_date` date NOT NULL,
    `due_date` date NOT NULL,
    `subtotal` decimal(10, 2) NOT NULL,
    `tax` decimal(10, 2) DEFAULT 0.00,
    `discount` decimal(10, 2) DEFAULT 0.00,
    `total_amount` decimal(10, 2) NOT NULL,
    `amount_paid` decimal(10, 2) DEFAULT 0.00,
    `status` enum(
        'draft',
        'issued',
        'paid',
        'overdue',
        'cancelled'
    ) DEFAULT 'draft',
    `payment_method` varchar(50) DEFAULT NULL,
    `notes` text DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`),
    UNIQUE KEY `invoice_number` (`invoice_number`),
    KEY `patient_id` (`patient_id`),
    CONSTRAINT `invoices_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE `invoice_items` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `invoice_id` int(11) NOT NULL,
    `item_type` enum(
        'consultation',
        'daycare',
        'medicine',
        'test',
        'ambulance',
        'other'
    ) NOT NULL,
    `description` varchar(255) NOT NULL,
    `quantity` int(11) DEFAULT 1,
    `unit_price` decimal(10, 2) NOT NULL,
    `total_price` decimal(10, 2) NOT NULL,
    `reference_id` int(11) DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`),
    KEY `invoice_id` (`invoice_id`),
    CONSTRAINT `invoice_items_ibfk_1` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE `system_settings` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `setting_key` varchar(50) NOT NULL,
    `setting_value` text DEFAULT NULL,
    `description` text DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`),
    UNIQUE KEY `setting_key` (`setting_key`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE `system_issues` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `issue_type` enum(
        'error',
        'bug_report',
        'data_inconsistency',
        'feature_request',
        'system_downtime'
    ) NOT NULL,
    `title` varchar(255) NOT NULL,
    `description` text NOT NULL,
    `severity` enum(
        'low',
        'medium',
        'high',
        'critical'
    ) DEFAULT 'medium',
    `status` enum(
        'open',
        'in_progress',
        'resolved',
        'closed'
    ) DEFAULT 'open',
    `reported_by` int(11) NOT NULL,
    `assigned_to` int(11) DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    `resolved_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `reported_by` (`reported_by`),
    KEY `assigned_to` (`assigned_to`),
    CONSTRAINT `system_issues_ibfk_1` FOREIGN KEY (`reported_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `system_issues_ibfk_2` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- Triggers for data integrity
DELIMITER $$

CREATE TRIGGER `prevent_overlapping_doctor_slots` BEFORE INSERT ON `doctor_slots` FOR EACH ROW
BEGIN
    DECLARE overlapping_count INT;
    
    SELECT COUNT(*) INTO overlapping_count
    FROM doctor_slots
    WHERE 
        doctor_id = NEW.doctor_id AND
        day_of_week = NEW.day_of_week AND
        is_active = TRUE AND
        (
            (NEW.start_time BETWEEN start_time AND end_time) OR
            (NEW.end_time BETWEEN start_time AND end_time) OR
            (start_time BETWEEN NEW.start_time AND NEW.end_time) OR
            (end_time BETWEEN NEW.start_time AND NEW.end_time)
        );
    
    IF overlapping_count > 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Overlapping doctor slots are not allowed for the same doctor';
    END IF;
END$$

CREATE TRIGGER `prevent_overlapping_appointment_slots` BEFORE INSERT ON `appointment_slots` FOR EACH ROW
BEGIN
    DECLARE overlapping_count INT;
    
    SELECT COUNT(*) INTO overlapping_count
    FROM appointment_slots
    WHERE 
        doctor_id = NEW.doctor_id AND
        is_active = TRUE AND
        (
            (NEW.start_time BETWEEN start_time AND end_time) OR
            (NEW.end_time BETWEEN start_time AND end_time) OR
            (start_time BETWEEN NEW.start_time AND NEW.end_time) OR
            (end_time BETWEEN NEW.start_time AND NEW.end_time)
        );
    
    IF overlapping_count > 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Overlapping appointment slots are not allowed for the same doctor';
    END IF;
END$$

CREATE TRIGGER `prevent_overlapping_nurse_slots` BEFORE INSERT ON `nurse_slots` FOR EACH ROW
BEGIN
    DECLARE overlapping_count INT;
    
    -- Check for overlapping slots for the same nurse
    SELECT COUNT(*) INTO overlapping_count
    FROM nurse_slots
    WHERE 
        nurse_id = NEW.nurse_id AND
        date = NEW.date AND
        is_active = TRUE AND
        (
            (NEW.start_time BETWEEN start_time AND end_time) OR
            (NEW.end_time BETWEEN start_time AND end_time) OR
            (start_time BETWEEN NEW.start_time AND NEW.end_time) OR
            (end_time BETWEEN NEW.start_time AND NEW.end_time)
        );
    
    IF overlapping_count > 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Overlapping nurse slots are not allowed';
    END IF;
    
    -- Special check for compounder duty: must not overlap with doctor's duty slots
    IF NEW.purpose = 'compounder_support' THEN
        SELECT COUNT(*) INTO overlapping_count
        FROM doctor_compounders dc
        JOIN doctor_slots ds ON dc.doctor_id = ds.doctor_id
        WHERE 
            dc.nurse_id = NEW.nurse_id AND
            ds.day_of_week = DAYNAME(NEW.date) AND
            ds.is_active = TRUE AND
            (
                (NEW.start_time BETWEEN ds.start_time AND ds.end_time) OR
                (NEW.end_time BETWEEN ds.start_time AND ds.end_time) OR
                (ds.start_time BETWEEN NEW.start_time AND NEW.end_time) OR
                (ds.end_time BETWEEN NEW.start_time AND NEW.end_time)
            );
        
        IF overlapping_count > 0 THEN
            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Compounder duty slots must not overlap with doctor''s duty slots';
        END IF;
    END IF;
END$$

CREATE TRIGGER `auto_assign_department_after_doctor_insert` AFTER INSERT ON `doctors` FOR EACH ROW
BEGIN
  DECLARE dep_id INT;
  SELECT id INTO dep_id FROM departments WHERE LOWER(NEW.specialization) LIKE CONCAT('%', LOWER(name), '%') LIMIT 1;
  IF dep_id IS NOT NULL THEN
    INSERT IGNORE INTO doctor_departments (doctor_id, department_id, created_at) VALUES (NEW.id, dep_id, NOW());
  END IF;
END$$

CREATE TRIGGER `auto_assign_department_after_doctor_update` AFTER UPDATE ON `doctors` FOR EACH ROW
BEGIN
  DECLARE dep_id INT;
  IF NEW.specialization IS NOT NULL AND NEW.specialization != OLD.specialization THEN
    SELECT id INTO dep_id FROM departments WHERE LOWER(NEW.specialization) LIKE CONCAT('%', LOWER(name), '%') LIMIT 1;
    IF dep_id IS NOT NULL THEN
      INSERT INTO doctor_departments (doctor_id, department_id, created_at) VALUES (NEW.id, dep_id, NOW())
      ON DUPLICATE KEY UPDATE department_id = VALUES(department_id);
    END IF;
  END IF;
END$$

DELIMITER;

-- Views for common queries
CREATE VIEW `active_hospital_doctors` AS
SELECT
    `h`.`id` AS `hospital_id`,
    `h`.`name` AS `hospital_name`,
    `d`.`id` AS `doctor_id`,
    `u`.`name` AS `doctor_name`,
    `d`.`specialization` AS `specialization`,
    `d`.`consultation_fee` AS `consultation_fee`
FROM (
        (
            (
                `hospitals` `h`
                JOIN `doctor_hospitals` `dh` ON (`h`.`id` = `dh`.`hospital_id`)
            )
            JOIN `doctors` `d` ON (`dh`.`doctor_id` = `d`.`id`)
        )
        JOIN `users` `u` ON (`d`.`user_id` = `u`.`id`)
    )
WHERE
    `dh`.`status` = 'accepted'
    AND `d`.`verification_status` = 'verified';

CREATE VIEW `chat_conversations` AS
SELECT
    `c`.`id` AS `id`,
    `c`.`sender_id` AS `sender_id`,
    `s`.`name` AS `sender_name`,
    `c`.`receiver_id` AS `receiver_id`,
    `r`.`name` AS `receiver_name`,
    `c`.`message` AS `message`,
    `c`.`timestamp` AS `timestamp`,
    `c`.`is_read` AS `is_read`,
    CASE
        WHEN `c`.`related_appointment_id` IS NOT NULL THEN 'appointment'
        WHEN `c`.`related_daycare_booking_id` IS NOT NULL THEN 'daycare'
        ELSE 'general'
    END AS `chat_type`
FROM (
        (
            `chats` `c`
            JOIN `users` `s` ON (`c`.`sender_id` = `s`.`id`)
        )
        JOIN `users` `r` ON (`c`.`receiver_id` = `r`.`id`)
    )
ORDER BY `c`.`timestamp` DESC;

CREATE VIEW `doctor_upcoming_appointments` AS
SELECT
    `d`.`id` AS `doctor_id`,
    `u`.`name` AS `doctor_name`,
    `a`.`id` AS `appointment_id`,
    `p`.`id` AS `patient_id`,
    `p`.`name` AS `patient_name`,
    `a`.`appointment_date` AS `appointment_date`,
    `a`.`appointment_time` AS `appointment_time`,
    `a`.`status` AS `status`
FROM (
        (
            (
                `doctors` `d`
                JOIN `users` `u` ON (`d`.`user_id` = `u`.`id`)
            )
            JOIN `appointments` `a` ON (`d`.`id` = `a`.`doctor_id`)
        )
        JOIN `users` `p` ON (`a`.`patient_id` = `p`.`id`)
    )
WHERE
    `a`.`appointment_date` >= CURDATE()
    AND `a`.`status` IN ('pending', 'confirmed')
ORDER BY `a`.`appointment_date` ASC, `a`.`appointment_time` ASC;

CREATE VIEW `verification_status_doctors` AS
SELECT
    `d`.`id` AS `doctor_id`,
    `u`.`name` AS `doctor_name`,
    `d`.`bmdc_code` AS `bmdc_code`,
    `d`.`verification_status` AS `verification_status`,
    `d`.`verification_document` AS `verification_document`,
    `h`.`name` AS `verified_hospital`,
    `d`.`verification_date` AS `verification_date`,
    `d`.`rejection_reason` AS `rejection_reason`,
    `dvr`.`status` AS `request_status`,
    `dvr`.`response_message` AS `response_message`
FROM (
        (
            `doctors` `d`
            JOIN `users` `u` ON (`d`.`user_id` = `u`.`id`)
        )
        LEFT JOIN `hospitals` `h` ON (
            `d`.`verified_hospital_id` = `h`.`id`
        )
    )
    LEFT JOIN `doctor_verification_requests` `dvr` ON (
        `d`.`id` = `dvr`.`doctor_id`
        AND `dvr`.`status` = 'pending'
    )
ORDER BY `d`.`verification_status` ASC, `d`.`created_at` DESC;

-- Insert sample data
INSERT INTO
    `divisions` (`name`)
VALUES ('Barishal'),
    ('Chattogram'),
    ('Dhaka'),
    ('Khulna'),
    ('Mymensingh'),
    ('Rajshahi'),
    ('Rangpur'),
    ('Sylhet');

INSERT INTO
    `districts` (`name`, `division_id`)
VALUES ('Barishal', 1),
    ('Barguna', 1),
    ('Bhola', 1),
    ('Jhalokati', 1),
    ('Patuakhali', 1),
    ('Pirojpur', 1),
    ('Bandarban', 2),
    ('Brahmanbaria', 2),
    ('Chandpur', 2),
    ('Chattogram', 2),
    ('Cumilla', 2),
    ('Cox\'s Bazar', 2),
    ('Feni', 2),
    ('Khagrachhari', 2),
    ('Lakshmipur', 2),
    ('Noakhali', 2),
    ('Rangamati', 2),
    ('Dhaka', 3),
    ('Faridpur', 3),
    ('Gazipur', 3),
    ('Gopalganj', 3),
    ('Kishoreganj', 3),
    ('Madaripur', 3),
    ('Manikganj', 3),
    ('Munshiganj', 3),
    ('Narayanganj', 3),
    ('Narsingdi', 3),
    ('Rajbari', 3),
    ('Shariatpur', 3),
    ('Tangail', 3),
    ('Bagerhat', 4),
    ('Chuadanga', 4),
    ('Jessore', 4),
    ('Jhenaidah', 4),
    ('Khulna', 4),
    ('Kushtia', 4),
    ('Magura', 4),
    ('Meherpur', 4),
    ('Narail', 4),
    ('Satkhira', 4),
    ('Jamalpur', 5),
    ('Netrokona', 5),
    ('Sherpur', 5),
    ('Bogura', 6),
    ('Joypurhat', 6),
    ('Naogaon', 6),
    ('Natore', 6),
    ('Chapainawabganj', 6),
    ('Pabna', 6),
    ('Rajshahi', 6),
    ('Sirajganj', 6),
    ('Dinajpur', 7),
    ('Gaibandha', 7),
    ('Kurigram', 7),
    ('Lalmonirhat', 7),
    ('Nilphamari', 7),
    ('Panchagarh', 7),
    ('Rangpur', 7),
    ('Thakurgaon', 7),
    ('Habiganj', 8),
    ('Moulvibazar', 8),
    ('Sunamganj', 8),
    ('Sylhet', 8);

INSERT INTO
    `roles` (`name`, `description`)
VALUES ('patient', 'Patient role'),
    ('doctor', 'Doctor role'),
    ('nurse', 'Nurse role'),
    (
        'compounder',
        'Compounder role'
    ),
    (
        'ambulance',
        'Ambulance Driver role'
    ),
    ('admin', 'Administrator role');

INSERT INTO
    `permissions` (`name`, `description`)
VALUES (
        'appointment.create',
        'Ability to create appointments'
    ),
    (
        'appointment.view.self',
        'Ability to view own appointments'
    ),
    (
        'appointment.view.doctor',
        'Ability to view doctor appointments'
    ),
    (
        'patient_queue.view',
        'Ability to view patient queue'
    ),
    (
        'prescriptions.create',
        'Ability to create prescriptions'
    ),
    (
        'prescriptions.view',
        'Ability to view prescriptions'
    ),
    (
        'reports.view',
        'Ability to view reports'
    ),
    (
        'compounder.assign',
        'Ability to assign compounder'
    ),
    (
        'bmdc.verify',
        'Ability to verify BM&DC code'
    ),
    (
        'ratings.view',
        'Ability to view ratings'
    ),
    (
        'vitals.capture',
        'Ability to capture patient vitals'
    ),
    (
        'appointment_bill.update',
        'Ability to update appointment bills'
    ),
    (
        'nurse_schedule.manage',
        'Ability to manage nurse schedule'
    ),
    (
        'daycare.review_docs',
        'Ability to review daycare documents'
    ),
    (
        'verification.request',
        'Ability to request verification'
    ),
    (
        'hospital.change',
        'Ability to change hospital'
    ),
    (
        'users.manage',
        'Ability to manage users'
    ),
    (
        'reports.upload',
        'Ability to upload reports'
    ),
    (
        'reports.edit',
        'Ability to edit reports'
    ),
    (
        'reports.remove',
        'Ability to remove reports'
    ),
    (
        'icu.update',
        'Ability to update ICU information'
    ),
    (
        'ambulance.update',
        'Ability to update ambulance information'
    ),
    (
        'daycare.manage',
        'Ability to manage daycare'
    ),
    (
        'invoices.manage',
        'Ability to manage invoices'
    ),
    (
        'nurse.verify',
        'Ability to verify nurses'
    ),
    (
        'hospital_bill.update',
        'Ability to update hospital bills'
    ),
    (
        'hospital.manage',
        'Ability to manage hospitals'
    ),
    (
        'chat.with_doctor',
        'Ability to chat with doctors'
    ),
    (
        'chat.with_daycare_nurse',
        'Ability to chat with daycare nurses'
    ),
    (
        'chat.with_patient',
        'Ability to chat with patients'
    ),
    (
        'appointment_slot.create',
        'Ability to create appointment slots'
    ),
    (
        'appointment_slot.manage',
        'Ability to manage appointment slots'
    ),
    (
        'doctor_hospital_association.request',
        'Ability to request doctor-hospital association'
    ),
    (
        'doctor_hospital_association.respond',
        'Ability to respond to doctor-hospital association requests'
    ),
    (
        'system_issues.view',
        'Ability to view system issues'
    ),
    (
        'system_issues.create',
        'Ability to create system issues'
    ),
    (
        'system_issues.resolve',
        'Ability to resolve system issues'
    );

INSERT INTO
    `role_permissions` (`role_id`, `permission_id`)
VALUES (6, 22),
    (1, 1),
    (2, 3),
    (1, 2),
    (4, 12),
    (6, 31),
    (2, 31),
    (6, 32),
    (2, 32),
    (2, 9),
    (1, 29),
    (1, 28),
    (2, 30),
    (3, 30),
    (2, 8),
    (6, 23),
    (3, 14),
    (6, 33),
    (2, 34),
    (3, 16),
    (6, 27),
    (6, 26),
    (6, 21),
    (6, 24),
    (6, 25),
    (3, 13),
    (4, 4),
    (2, 4),
    (2, 5),
    (4, 6),
    (2, 6),
    (2, 10),
    (3, 10),
    (6, 19),
    (6, 20),
    (6, 18),
    (4, 7),
    (2, 7),
    (6, 17),
    (3, 15),
    (4, 11),
    (6, 35),
    (6, 36),
    (6, 37);

INSERT INTO
    `users` (
        `name`,
        `email`,
        `password`,
        `phone`,
        `date_of_birth`,
        `gender`,
        `blood_group`
    )
VALUES (
        'System Admin',
        'admin@niramoy.com.bd',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        '+8801711111111',
        NULL,
        NULL,
        NULL
    ),
    (
        'Dr. Ahmed Hasan',
        'ahmed.hasan@niramoy.com.bd',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        '+8801711111112',
        NULL,
        NULL,
        NULL
    ),
    (
        'Dr. Fatema Begum',
        'fatema.begum@niramoy.com.bd',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        '+8801711111113',
        NULL,
        NULL,
        NULL
    ),
    (
        'Dr. Mohammad Ali',
        'mohammad.ali@niramoy.com.bd',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        '+8801711111114',
        NULL,
        NULL,
        NULL
    ),
    (
        'Nurse Ayesha Siddiqua',
        'ayesha.siddiqua@niramoy.com.bd',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        '+8801711111115',
        NULL,
        NULL,
        NULL
    ),
    (
        'Nurse Karim Ahmed',
        'karim.ahmed@niramoy.com.bd',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        '+8801711111116',
        NULL,
        NULL,
        NULL
    ),
    (
        'Rahim Khan',
        'rahim.khan@niramoy.com.bd',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        '+8801711111117',
        '1985-05-15',
        'male',
        'B+'
    ),
    (
        'Karima Begum',
        'karima.begum@niramoy.com.bd',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        '+8801711111118',
        '1990-08-22',
        'female',
        'O+'
    ),
    (
        'Jamal Uddin',
        'jamal.uddin@niramoy.com.bd',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        '+8801711111119',
        '1978-12-10',
        'male',
        'A+'
    ),
    (
        'Abdul Karim',
        'abdul.karim@niramoy.com.bd',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        '+8801711111120',
        NULL,
        NULL,
        NULL
    ),
    (
        'Mohammad Rafiq',
        'mohammad.rafiq@niramoy.com.bd',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        '+8801711111121',
        NULL,
        NULL,
        NULL
    ),
    (
        'Abul Kashem',
        'abul.kashem@niramoy.com.bd',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        '+8801711111122',
        NULL,
        NULL,
        NULL
    );

INSERT INTO
    `user_roles` (`user_id`, `role_id`)
VALUES (1, 6),
    (2, 2),
    (3, 2),
    (4, 2),
    (5, 3),
    (6, 3),
    (7, 1),
    (8, 1),
    (9, 1),
    (10, 5),
    (11, 5),
    (12, 5);

INSERT INTO
    `hospitals` (
        `name`,
        `address`,
        `district`,
        `division`,
        `latitude`,
        `longitude`,
        `phone`,
        `hospital_type`
    )
VALUES (
        'Dhaka Medical College Hospital',
        'Shahbag, Dhaka',
        'Dhaka',
        'Dhaka',
        23.73290000,
        90.40840000,
        '+8802-9661051',
        'government'
    ),
    (
        'Bangabandhu Sheikh Mujib Medical University',
        'Shahbag, Dhaka',
        'Dhaka',
        'Dhaka',
        23.73440000,
        90.40410000,
        '+8802-9663000',
        'government'
    ),
    (
        'National Institute of Cardiovascular Diseases',
        'Sher-E-Bangla Nagar, Dhaka',
        'Dhaka',
        'Dhaka',
        23.77740000,
        90.37650000,
        '+8802-9130275',
        'government'
    ),
    (
        'Chittagong Medical College Hospital',
        'Chawkbazar, Chittagong',
        'Chattogram',
        'Chattogram',
        22.35150000,
        91.83120000,
        '+88031-619400',
        'government'
    ),
    (
        'United Hospital Limited',
        'Plot 15, Road 71, Gulshan, Dhaka',
        'Dhaka',
        'Dhaka',
        23.79250000,
        90.40780000,
        '+8802-8836000',
        'private'
    ),
    (
        'Ibn Sina Diagnostic & Consultation Center',
        '1/1, Bir Uttam AK Khandakar Road, Dhaka',
        'Dhaka',
        'Dhaka',
        23.75760000,
        90.38940000,
        '+8802-9661555',
        'diagnostic'
    );

INSERT INTO
    `hospital_admins` (`hospital_id`, `admin_id`)
VALUES (1, 1);

INSERT INTO
    `departments` (`name`, `description`)
VALUES (
        'Cardiology',
        'Heart and vascular care'
    ),
    (
        'Neurology',
        'Brain and nervous system'
    ),
    ('Pediatrics', 'Child health'),
    (
        'Radiology',
        'Imaging and diagnostics'
    ),
    (
        'Obstetrics & Gynecology',
        'Women and maternal care'
    );

INSERT INTO
    `doctors` (
        `user_id`,
        `bmdc_code`,
        `specialization`,
        `experience_years`,
        `consultation_fee`,
        `verification_status`
    )
VALUES (
        2,
        'A-12345',
        'Cardiologist',
        15,
        1500.00,
        'verified'
    ),
    (
        3,
        'B-12345',
        'Gynecologist',
        12,
        1200.00,
        'verified'
    ),
    (
        4,
        'C-12345',
        'Pediatrician',
        10,
        1000.00,
        'pending'
    );

INSERT INTO
    `doctor_departments` (`doctor_id`, `department_id`)
VALUES (1, 1),
    (2, 5),
    (3, 3);

INSERT INTO
    `doctor_verification_requests` (
        `doctor_id`,
        `hospital_id`,
        `requested_by`,
        `requested_by_id`,
        `status`,
        `message`
    )
VALUES (
        3,
        1,
        'doctor',
        4,
        'pending',
        'Please verify my credentials'
    );

INSERT INTO
    `doctor_hospitals` (
        `doctor_id`,
        `hospital_id`,
        `status`,
        `requested_by`,
        `requested_by_id`
    )
VALUES (1, 1, 'accepted', 'admin', 1),
    (2, 1, 'accepted', 'admin', 1),
    (3, 1, 'accepted', 'admin', 1),
    (1, 5, 'pending', 'doctor', 2),
    (2, 6, 'pending', 'doctor', 3);

INSERT INTO
    `doctor_slots` (
        `doctor_id`,
        `hospital_id`,
        `day_of_week`,
        `start_time`,
        `end_time`,
        `max_patients`
    )
VALUES (
        1,
        1,
        'saturday',
        '09:00:00',
        '12:00:00',
        10
    ),
    (
        1,
        1,
        'sunday',
        '09:00:00',
        '12:00:00',
        10
    ),
    (
        1,
        1,
        'monday',
        '14:00:00',
        '17:00:00',
        8
    ),
    (
        2,
        1,
        'tuesday',
        '10:00:00',
        '13:00:00',
        10
    ),
    (
        2,
        1,
        'wednesday',
        '10:00:00',
        '13:00:00',
        10
    ),
    (
        3,
        1,
        'thursday',
        '15:00:00',
        '18:00:00',
        12
    );

INSERT INTO
    `nurses` (
        `user_id`,
        `is_daycare`,
        `is_compounder`,
        `verification_status`
    )
VALUES (5, 1, 1, 'pending'),
    (6, 1, 0, 'pending');

INSERT INTO
    `nurse_verification_requests` (
        `nurse_id`,
        `hospital_id`,
        `requested_by`,
        `requested_by_id`,
        `status`,
        `message`
    )
VALUES (
        1,
        1,
        'nurse',
        5,
        'pending',
        'Please verify my nursing license'
    ),
    (
        2,
        1,
        'nurse',
        6,
        'pending',
        'Requesting verification for daycare services'
    );

INSERT INTO
    `nurse_slots` (
        `nurse_id`,
        `hospital_id`,
        `date`,
        `start_time`,
        `end_time`,
        `purpose`,
        `max_patients`
    )
VALUES (
        1,
        1,
        '2025-09-19',
        '09:00:00',
        '12:00:00',
        'daycare',
        5
    ),
    (
        1,
        1,
        '2025-09-19',
        '14:00:00',
        '17:00:00',
        'compounder_support',
        8
    ),
    (
        2,
        1,
        '2025-09-20',
        '10:00:00',
        '13:00:00',
        'daycare',
        5
    );

INSERT INTO
    `appointment_slots` (
        `doctor_id`,
        `hospital_id`,
        `start_time`,
        `end_time`,
        `max_patients`,
        `created_by`
    )
VALUES (
        1,
        1,
        '2025-09-19 09:00:00',
        '2025-09-19 12:00:00',
        8,
        1
    ),
    (
        1,
        1,
        '2025-09-20 09:00:00',
        '2025-09-20 12:00:00',
        8,
        1
    ),
    (
        2,
        1,
        '2025-09-19 10:00:00',
        '2025-09-19 13:00:00',
        10,
        2
    ),
    (
        3,
        1,
        '2025-09-21 15:00:00',
        '2025-09-21 18:00:00',
        12,
        3
    );

INSERT INTO
    `daycare_procedures` (
        `name`,
        `description`,
        `duration_minutes`,
        `price`,
        `hospital_id`
    )
VALUES (
        'Dressing Change',
        'Simple wound dressing change',
        15,
        500.00,
        1
    ),
    (
        'IV Fluid Administration',
        'Intravenous fluid therapy',
        30,
        800.00,
        1
    ),
    (
        'Injection Administration',
        'Intramuscular or subcutaneous injection',
        10,
        300.00,
        1
    ),
    (
        'Vital Signs Monitoring',
        'Regular monitoring of vital signs',
        20,
        400.00,
        1
    ),
    (
        'Blood Pressure Check',
        'Blood pressure measurement and monitoring',
        15,
        300.00,
        1
    );

INSERT INTO
    `diseases` (
        `name`,
        `description`,
        `is_epidemic`,
        `symptoms`,
        `prevention`,
        `treatment`
    )
VALUES (
        'Dengue',
        'Dengue fever is a mosquito-borne tropical disease caused by the dengue virus.',
        1,
        'Sudden high fever, Severe headaches, Pain behind the eyes, Severe joint and muscle pain, Fatigue, Nausea, Skin rash',
        'Use mosquito repellent, Wear long-sleeved clothes, Eliminate standing water where mosquitoes breed',
        'No specific treatment, Rest, Fluid intake, Pain relievers (avoid aspirin)'
    ),
    (
        'Chikungunya',
        'Chikungunya is a viral disease transmitted to humans by infected mosquitoes.',
        1,
        'Sudden fever, Severe joint pain, Muscle pain, Headache, Nausea, Fatigue, Rash',
        'Use mosquito repellent, Wear protective clothing, Sleep under mosquito nets',
        'No specific antiviral treatment, Rest, Fluids, Pain relievers'
    ),
    (
        'Malaria',
        'Malaria is a mosquito-borne infectious disease affecting humans and other animals.',
        1,
        'Fever, Chills, Headache, Nausea and vomiting, Muscle pain and fatigue',
        'Use mosquito nets, Insect repellent, Antimalarial medication',
        'Antimalarial drugs'
    ),
    (
        'COVID-19',
        'Coronavirus disease 2019 (COVID-19) is an infectious disease caused by SARS-CoV-2.',
        1,
        'Fever, Cough, Shortness of breath, Loss of taste or smell, Fatigue',
        'Vaccination, Wear masks, Social distancing, Hand hygiene',
        'Antiviral medications, Supportive care'
    ),
    (
        'Tuberculosis',
        'Tuberculosis (TB) is a potentially serious infectious disease that mainly affects the lungs.',
        1,
        'Persistent cough (sometimes with blood), Chest pain, Weakness, Weight loss, Fever, Night sweats',
        'BCG vaccine, Avoid contact with infected people, Good ventilation',
        'Antibiotic treatment for several months'
    ),
    (
        'Typhoid',
        'Typhoid fever is a bacterial infection that can spread throughout the body.',
        1,
        'Sustained fever, Weakness, Stomach pain, Headache, Diarrhea or constipation, Loss of appetite',
        'Typhoid vaccination, Safe food and water, Hand hygiene',
        'Antibiotic treatment'
    ),
    (
        'Hepatitis A',
        'Hepatitis A is a viral liver disease that can cause mild to severe illness.',
        1,
        'Fatigue, Sudden nausea and vomiting, Abdominal pain, Loss of appetite, Low-grade fever, Dark urine',
        'Vaccination, Good hygiene, Safe food and water',
        'Supportive care, Rest, Proper nutrition'
    ),
    (
        'Hepatitis B',
        'Hepatitis B is a viral infection that attacks the liver.',
        1,
        'Abdominal pain, Dark urine, Fever, Joint pain, Loss of appetite, Nausea, Weakness and fatigue',
        'Vaccination, Safe sex, Avoid sharing needles',
        'Antiviral medications, Liver transplant in severe cases'
    ),
    (
        'Diarrhea',
        'Diarrhea is a common condition characterized by loose, watery stools.',
        0,
        'Loose, watery stools, Abdominal cramps, Bloating, Urgent need to have a bowel movement',
        'Hand hygiene, Safe drinking water, Proper food handling',
        'Rehydration, Zinc supplements, Antibiotics for bacterial cases'
    ),
    (
        'Pneumonia',
        'Pneumonia is an infection that inflames the air sacs in one or both lungs.',
        0,
        'Chest pain when breathing or coughing, Confusion or changes in mental awareness, Cough, Fatigue, Fever',
        'Vaccination, Good hygiene, Not smoking',
        'Antibiotics, Antiviral drugs, Fever reducers'
    );

INSERT INTO
    `patient_diseases` (
        `patient_id`,
        `disease_id`,
        `diagnosis_date`,
        `status`
    )
VALUES (7, 1, '2025-09-18', 'active'),
    (
        8,
        2,
        '2025-09-13',
        'recovered'
    ),
    (9, 3, '2025-09-08', 'active');

INSERT INTO
    `ambulances` (
        `hospital_id`,
        `vehicle_number`,
        `driver_name`,
        `driver_phone`,
        `is_available`
    )
VALUES (
        1,
        'DHK-AMB-001',
        'Abdul Karim',
        '+8801711111120',
        1
    ),
    (
        1,
        'DHK-AMB-002',
        'Mohammad Rafiq',
        '+8801711111121',
        1
    ),
    (
        4,
        'CTG-AMB-001',
        'Abul Kashem',
        '+8801711111122',
        1
    );

INSERT INTO
    `icu_beds` (
        `hospital_id`,
        `bed_number`,
        `is_occupied`,
        `ventilator`
    )
VALUES (1, 'ICU-001', 0, 1),
    (1, 'ICU-002', 0, 1),
    (1, 'ICU-003', 0, 0),
    (1, 'ICU-004', 0, 0),
    (1, 'ICU-005', 0, 1);

INSERT INTO
    `chats` (
        `sender_id`,
        `receiver_id`,
        `message`,
        `is_read`,
        `related_appointment_id`,
        `related_daycare_booking_id`
    )
VALUES (
        7,
        2,
        'Hello Dr. Ahmed, I have a question about my prescription.',
        0,
        NULL,
        NULL
    ),
    (
        2,
        7,
        'Hello Rahim, sure. What would you like to know?',
        0,
        NULL,
        NULL
    ),
    (
        7,
        2,
        'I wanted to confirm if I should take the medicine before or after meals.',
        0,
        NULL,
        NULL
    ),
    (
        2,
        7,
        'You should take it after meals. Twice a day as prescribed.',
        0,
        NULL,
        NULL
    ),
    (
        8,
        5,
        'Hello Nurse Ayesha, I need to reschedule my daycare appointment.',
        0,
        NULL,
        NULL
    ),
    (
        5,
        8,
        'Hello Karima, sure. When would you like to reschedule to?',
        0,
        NULL,
        NULL
    ),
    (
        8,
        5,
        'Can we move it to tomorrow at the same time?',
        0,
        NULL,
        NULL
    ),
    (
        5,
        8,
        'Let me check my schedule and confirm with you shortly.',
        0,
        NULL,
        NULL
    );

INSERT INTO
    `system_settings` (
        `setting_key`,
        `setting_value`,
        `description`
    )
VALUES (
        'site_name',
        'Niramoy (নিরাময়)',
        'Name of the healthcare system'
    ),
    (
        'site_description',
        'Bangladesh Healthcare Management System',
        'Description of the system'
    ),
    (
        'admin_email',
        'admin@niramoy.com.bd',
        'Default admin email'
    ),
    (
        'ambulance_base_fare',
        '100.00',
        'Base fare for ambulance service'
    ),
    (
        'ambulance_per_km_fare',
        '30.00',
        'Per kilometer fare for ambulance service'
    ),
    (
        'max_appointment_per_slot',
        '10',
        'Maximum patients per doctor slot'
    ),
    (
        'max_daycare_per_slot',
        '5',
        'Maximum patients per nurse slot'
    ),
    (
        'default_currency',
        'BDT',
        'Default currency for the system'
    ),
    (
        'date_format',
        'd-m-Y',
        'Default date format'
    ),
    (
        'time_format',
        'h:i A',
        'Default time format'
    );

INSERT INTO
    `patient_reports` (
        `patient_id`,
        `uploaded_by`,
        `report_type`,
        `title`,
        `description`,
        `file_path`,
        `is_confidential`
    )
VALUES (
        7,
        2,
        'Blood Test',
        'Complete Blood Count',
        'Routine blood test results',
        '/reports/patient_7/blood_test_20250918.pdf',
        0
    ),
    (
        7,
        2,
        'X-Ray',
        'Chest X-Ray',
        'Chest X-ray for respiratory checkup',
        '/reports/patient_7/chest_xray_20250918.jpg',
        0
    ),
    (
        8,
        3,
        'Ultrasound',
        'Abdominal Ultrasound',
        'Routine abdominal ultrasound',
        '/reports/patient_8/abdominal_ultrasound_20250915.pdf',
        1
    ),
    (
        9,
        4,
        'ECG',
        'Electrocardiogram',
        'Heart rhythm monitoring',
        '/reports/patient_9/ecg_20250910.pdf',
        0
    );

INSERT INTO
    `doctor_ratings` (
        `doctor_id`,
        `user_id`,
        `rating`,
        `comment`
    )
VALUES (
        1,
        7,
        5,
        'Excellent doctor, very thorough and caring.'
    ),
    (
        1,
        8,
        4,
        'Good experience, though waiting time was a bit long.'
    ),
    (
        2,
        9,
        5,
        'Dr. Fatema is very professional and knowledgeable.'
    ),
    (
        3,
        7,
        3,
        'Average experience, could improve communication skills.'
    );

INSERT INTO
    `system_issues` (
        `issue_type`,
        `title`,
        `description`,
        `severity`,
        `status`,
        `reported_by`,
        `assigned_to`
    )
VALUES (
        'bug_report',
        'Appointment scheduling error',
        'Patients are able to book appointments outside doctor\'s available hours',
        'high',
        'in_progress',
        7,
        1
    ),
    (
        'feature_request',
        'Add prescription reminder',
        'Patients would benefit from automated medication reminders',
        'medium',
        'open',
        8,
        NULL
    ),
    (
        'error',
        'Ambulance location not updating',
        'Ambulance location data is not refreshing in real-time',
        'critical',
        'open',
        10,
        1
    );