-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 17, 2025 at 11:25 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `niramoy_health`
--

-- --------------------------------------------------------

--
-- Stand-in structure for view `active_hospital_doctors`
-- (See below for the actual view)
--
CREATE TABLE `active_hospital_doctors` (
`hospital_id` int(11)
,`hospital_name` varchar(100)
,`doctor_id` int(11)
,`doctor_name` varchar(100)
,`specialization` varchar(100)
,`consultation_fee` decimal(10,2)
);

-- --------------------------------------------------------

--
-- Table structure for table `ambulances`
--

CREATE TABLE `ambulances` (
  `id` int(11) NOT NULL,
  `hospital_id` int(11) NOT NULL,
  `vehicle_number` varchar(20) NOT NULL,
  `driver_name` varchar(100) DEFAULT NULL,
  `driver_phone` varchar(20) DEFAULT NULL,
  `is_available` tinyint(1) DEFAULT 1,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ambulances`
--

INSERT INTO `ambulances` (`id`, `hospital_id`, `vehicle_number`, `driver_name`, `driver_phone`, `is_available`, `latitude`, `longitude`, `created_at`, `updated_at`) VALUES
(1, 1, 'DHK-AMB-001', 'Abdul Karim', '+8801711111120', 1, NULL, NULL, '2025-09-17 20:30:24', '2025-09-17 20:30:24'),
(2, 1, 'DHK-AMB-002', 'Mohammad Rafiq', '+8801711111121', 1, NULL, NULL, '2025-09-17 20:30:24', '2025-09-17 20:30:24'),
(3, 4, 'CTG-AMB-001', 'Abul Kashem', '+8801711111122', 1, NULL, NULL, '2025-09-17 20:30:24', '2025-09-17 20:30:24');

-- --------------------------------------------------------

--
-- Table structure for table `ambulance_paths`
--

CREATE TABLE `ambulance_paths` (
  `id` int(11) NOT NULL,
  `trip_id` int(11) NOT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `longitude` decimal(11,8) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ambulance_trips`
--

CREATE TABLE `ambulance_trips` (
  `id` int(11) NOT NULL,
  `ambulance_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `pickup_location` varchar(255) NOT NULL,
  `pickup_latitude` decimal(10,8) NOT NULL,
  `pickup_longitude` decimal(11,8) NOT NULL,
  `destination_location` varchar(255) NOT NULL,
  `destination_latitude` decimal(10,8) NOT NULL,
  `destination_longitude` decimal(11,8) NOT NULL,
  `distance_km` decimal(10,2) NOT NULL,
  `fare` decimal(10,2) NOT NULL,
  `status` enum('pending','assigned','in_progress','completed','cancelled') DEFAULT 'pending',
  `requested_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `assigned_at` timestamp NULL DEFAULT NULL,
  `started_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `hospital_id` int(11) NOT NULL,
  `slot_id` int(11) NOT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `status` enum('pending','confirmed','in_progress','completed','cancelled','no_show') DEFAULT 'pending',
  `symptoms` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `appointment_slots`
--

CREATE TABLE `appointment_slots` (
  `id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `hospital_id` int(11) NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `max_patients` int(11) DEFAULT 10,
  `is_active` tinyint(1) DEFAULT 1,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointment_slots`
--

INSERT INTO `appointment_slots` (`id`, `doctor_id`, `hospital_id`, `start_time`, `end_time`, `max_patients`, `is_active`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2025-09-19 02:30:24', '2025-09-19 05:30:24', 8, 1, 1, '2025-09-17 20:30:24', '2025-09-17 20:30:24'),
(2, 1, 1, '2025-09-20 02:30:24', '2025-09-20 05:30:24', 8, 1, 1, '2025-09-17 20:30:24', '2025-09-17 20:30:24'),
(3, 2, 1, '2025-09-19 02:30:24', '2025-09-19 05:30:24', 10, 1, 2, '2025-09-17 20:30:24', '2025-09-17 20:30:24'),
(4, 3, 1, '2025-09-21 02:30:24', '2025-09-21 05:30:24', 12, 1, 3, '2025-09-17 20:30:24', '2025-09-17 20:30:24');

--
-- Triggers `appointment_slots`
--
DELIMITER $$
CREATE TRIGGER `prevent_overlapping_appointment_slots` BEFORE INSERT ON `appointment_slots` FOR EACH ROW BEGIN
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
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `chats`
--

CREATE TABLE `chats` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_read` tinyint(1) DEFAULT 0,
  `related_appointment_id` int(11) DEFAULT NULL,
  `related_daycare_booking_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chats`
--

INSERT INTO `chats` (`id`, `sender_id`, `receiver_id`, `message`, `timestamp`, `is_read`, `related_appointment_id`, `related_daycare_booking_id`) VALUES
(1, 7, 2, 'Hello Dr. Ahmed, I have a question about my prescription.', '2025-09-17 20:30:24', 0, NULL, NULL),
(2, 2, 7, 'Hello Rahim, sure. What would you like to know?', '2025-09-17 20:30:24', 0, NULL, NULL),
(3, 7, 2, 'I wanted to confirm if I should take the medicine before or after meals.', '2025-09-17 20:30:24', 0, NULL, NULL),
(4, 2, 7, 'You should take it after meals. Twice a day as prescribed.', '2025-09-17 20:30:24', 0, NULL, NULL),
(5, 8, 5, 'Hello Nurse Ayesha, I need to reschedule my daycare appointment.', '2025-09-17 20:30:24', 0, NULL, NULL),
(6, 5, 8, 'Hello Karima, sure. When would you like to reschedule to?', '2025-09-17 20:30:24', 0, NULL, NULL),
(7, 8, 5, 'Can we move it to tomorrow at the same time?', '2025-09-17 20:30:24', 0, NULL, NULL),
(8, 5, 8, 'Let me check my schedule and confirm with you shortly.', '2025-09-17 20:30:24', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Stand-in structure for view `chat_conversations`
-- (See below for the actual view)
--
CREATE TABLE `chat_conversations` (
`id` int(11)
,`sender_id` int(11)
,`sender_name` varchar(100)
,`receiver_id` int(11)
,`receiver_name` varchar(100)
,`message` text
,`timestamp` timestamp
,`is_read` tinyint(1)
,`chat_type` varchar(11)
);

-- --------------------------------------------------------

--
-- Table structure for table `daycare_bookings`
--

CREATE TABLE `daycare_bookings` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `nurse_id` int(11) NOT NULL,
  `hospital_id` int(11) NOT NULL,
  `procedure_id` int(11) NOT NULL,
  `slot_id` int(11) NOT NULL,
  `booking_date` date NOT NULL,
  `status` enum('pending','approved','rejected','completed','cancelled') DEFAULT 'pending',
  `nid_document` varchar(255) DEFAULT NULL,
  `other_documents` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `daycare_procedures`
--

CREATE TABLE `daycare_procedures` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `duration_minutes` int(11) DEFAULT 30,
  `price` decimal(10,2) DEFAULT 0.00,
  `hospital_id` int(11) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `daycare_procedures`
--

INSERT INTO `daycare_procedures` (`id`, `name`, `description`, `duration_minutes`, `price`, `hospital_id`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Dressing Change', 'Simple wound dressing change', 15, 500.00, 1, 1, '2025-09-17 20:30:24', '2025-09-17 20:30:24'),
(2, 'IV Fluid Administration', 'Intravenous fluid therapy', 30, 800.00, 1, 1, '2025-09-17 20:30:24', '2025-09-17 20:30:24'),
(3, 'Injection Administration', 'Intramuscular or subcutaneous injection', 10, 300.00, 1, 1, '2025-09-17 20:30:24', '2025-09-17 20:30:24'),
(4, 'Vital Signs Monitoring', 'Regular monitoring of vital signs', 20, 400.00, 1, 1, '2025-09-17 20:30:24', '2025-09-17 20:30:24'),
(5, 'Blood Pressure Check', 'Blood pressure measurement and monitoring', 15, 300.00, 1, 1, '2025-09-17 20:30:24', '2025-09-17 20:30:24');

-- --------------------------------------------------------

--
-- Table structure for table `diseases`
--

CREATE TABLE `diseases` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `is_epidemic` tinyint(1) DEFAULT 0,
  `symptoms` text DEFAULT NULL,
  `prevention` text DEFAULT NULL,
  `treatment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `diseases`
--

INSERT INTO `diseases` (`id`, `name`, `description`, `is_epidemic`, `symptoms`, `prevention`, `treatment`, `created_at`, `updated_at`) VALUES
(1, 'Dengue', 'Dengue fever is a mosquito-borne tropical disease caused by the dengue virus.', 1, 'Sudden high fever, Severe headaches, Pain behind the eyes, Severe joint and muscle pain, Fatigue, Nausea, Skin rash', 'Use mosquito repellent, Wear long-sleeved clothes, Eliminate standing water where mosquitoes breed', 'No specific treatment, Rest, Fluid intake, Pain relievers (avoid aspirin)', '2025-09-17 20:30:24', '2025-09-17 20:30:24'),
(2, 'Chikungunya', 'Chikungunya is a viral disease transmitted to humans by infected mosquitoes.', 1, 'Sudden fever, Severe joint pain, Muscle pain, Headache, Nausea, Fatigue, Rash', 'Use mosquito repellent, Wear protective clothing, Sleep under mosquito nets', 'No specific antiviral treatment, Rest, Fluids, Pain relievers', '2025-09-17 20:30:24', '2025-09-17 20:30:24'),
(3, 'Malaria', 'Malaria is a mosquito-borne infectious disease affecting humans and other animals.', 1, 'Fever, Chills, Headache, Nausea and vomiting, Muscle pain and fatigue', 'Use mosquito nets, Insect repellent, Antimalarial medication', 'Antimalarial drugs', '2025-09-17 20:30:24', '2025-09-17 20:30:24'),
(4, 'COVID-19', 'Coronavirus disease 2019 (COVID-19) is an infectious disease caused by SARS-CoV-2.', 1, 'Fever, Cough, Shortness of breath, Loss of taste or smell, Fatigue', 'Vaccination, Wear masks, Social distancing, Hand hygiene', 'Antiviral medications, Supportive care', '2025-09-17 20:30:24', '2025-09-17 20:30:24'),
(5, 'Tuberculosis', 'Tuberculosis (TB) is a potentially serious infectious disease that mainly affects the lungs.', 1, 'Persistent cough (sometimes with blood), Chest pain, Weakness, Weight loss, Fever, Night sweats', 'BCG vaccine, Avoid contact with infected people, Good ventilation', 'Antibiotic treatment for several months', '2025-09-17 20:30:24', '2025-09-17 20:30:24'),
(6, 'Typhoid', 'Typhoid fever is a bacterial infection that can spread throughout the body.', 1, 'Sustained fever, Weakness, Stomach pain, Headache, Diarrhea or constipation, Loss of appetite', 'Typhoid vaccination, Safe food and water, Hand hygiene', 'Antibiotic treatment', '2025-09-17 20:30:24', '2025-09-17 20:30:24'),
(7, 'Hepatitis A', 'Hepatitis A is a viral liver disease that can cause mild to severe illness.', 1, 'Fatigue, Sudden nausea and vomiting, Abdominal pain, Loss of appetite, Low-grade fever, Dark urine', 'Vaccination, Good hygiene, Safe food and water', 'Supportive care, Rest, Proper nutrition', '2025-09-17 20:30:24', '2025-09-17 20:30:24'),
(8, 'Hepatitis B', 'Hepatitis B is a viral infection that attacks the liver.', 1, 'Abdominal pain, Dark urine, Fever, Joint pain, Loss of appetite, Nausea, Weakness and fatigue', 'Vaccination, Safe sex, Avoid sharing needles', 'Antiviral medications, Liver transplant in severe cases', '2025-09-17 20:30:24', '2025-09-17 20:30:24'),
(9, 'Diarrhea', 'Diarrhea is a common condition characterized by loose, watery stools.', 0, 'Loose, watery stools, Abdominal cramps, Bloating, Urgent need to have a bowel movement', 'Hand hygiene, Safe drinking water, Proper food handling', 'Rehydration, Zinc supplements, Antibiotics for bacterial cases', '2025-09-17 20:30:24', '2025-09-17 20:30:24'),
(10, 'Pneumonia', 'Pneumonia is an infection that inflames the air sacs in one or both lungs.', 0, 'Chest pain when breathing or coughing, Confusion or changes in mental awareness, Cough, Fatigue, Fever', 'Vaccination, Good hygiene, Not smoking', 'Antibiotics, Antiviral drugs, Fever reducers', '2025-09-17 20:30:24', '2025-09-17 20:30:24');

-- --------------------------------------------------------

--
-- Table structure for table `districts`
--

CREATE TABLE `districts` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `division_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `districts`
--

INSERT INTO `districts` (`id`, `name`, `division_id`, `created_at`) VALUES
(1, 'Barishal', 1, '2025-09-17 20:30:24'),
(2, 'Barguna', 1, '2025-09-17 20:30:24'),
(3, 'Bhola', 1, '2025-09-17 20:30:24'),
(4, 'Jhalokati', 1, '2025-09-17 20:30:24'),
(5, 'Patuakhali', 1, '2025-09-17 20:30:24'),
(6, 'Pirojpur', 1, '2025-09-17 20:30:24'),
(7, 'Bandarban', 2, '2025-09-17 20:30:24'),
(8, 'Brahmanbaria', 2, '2025-09-17 20:30:24'),
(9, 'Chandpur', 2, '2025-09-17 20:30:24'),
(10, 'Chattogram', 2, '2025-09-17 20:30:24'),
(11, 'Cumilla', 2, '2025-09-17 20:30:24'),
(12, 'Cox\'s Bazar', 2, '2025-09-17 20:30:24'),
(13, 'Feni', 2, '2025-09-17 20:30:24'),
(14, 'Khagrachhari', 2, '2025-09-17 20:30:24'),
(15, 'Lakshmipur', 2, '2025-09-17 20:30:24'),
(16, 'Noakhali', 2, '2025-09-17 20:30:24'),
(17, 'Rangamati', 2, '2025-09-17 20:30:24'),
(18, 'Dhaka', 3, '2025-09-17 20:30:24'),
(19, 'Faridpur', 3, '2025-09-17 20:30:24'),
(20, 'Gazipur', 3, '2025-09-17 20:30:24'),
(21, 'Gopalganj', 3, '2025-09-17 20:30:24'),
(22, 'Kishoreganj', 3, '2025-09-17 20:30:24'),
(23, 'Madaripur', 3, '2025-09-17 20:30:24'),
(24, 'Manikganj', 3, '2025-09-17 20:30:24'),
(25, 'Munshiganj', 3, '2025-09-17 20:30:24'),
(26, 'Narayanganj', 3, '2025-09-17 20:30:24'),
(27, 'Narsingdi', 3, '2025-09-17 20:30:24'),
(28, 'Rajbari', 3, '2025-09-17 20:30:24'),
(29, 'Shariatpur', 3, '2025-09-17 20:30:24'),
(30, 'Tangail', 3, '2025-09-17 20:30:24'),
(31, 'Bagerhat', 4, '2025-09-17 20:30:24'),
(32, 'Chuadanga', 4, '2025-09-17 20:30:24'),
(33, 'Jessore', 4, '2025-09-17 20:30:24'),
(34, 'Jhenaidah', 4, '2025-09-17 20:30:24'),
(35, 'Khulna', 4, '2025-09-17 20:30:24'),
(36, 'Kushtia', 4, '2025-09-17 20:30:24'),
(37, 'Magura', 4, '2025-09-17 20:30:24'),
(38, 'Meherpur', 4, '2025-09-17 20:30:24'),
(39, 'Narail', 4, '2025-09-17 20:30:24'),
(40, 'Satkhira', 4, '2025-09-17 20:30:24'),
(41, 'Jamalpur', 5, '2025-09-17 20:30:24'),
(42, 'Netrokona', 5, '2025-09-17 20:30:24'),
(43, 'Sherpur', 5, '2025-09-17 20:30:24'),
(44, 'Bogura', 6, '2025-09-17 20:30:24'),
(45, 'Joypurhat', 6, '2025-09-17 20:30:24'),
(46, 'Naogaon', 6, '2025-09-17 20:30:24'),
(47, 'Natore', 6, '2025-09-17 20:30:24'),
(48, 'Chapainawabganj', 6, '2025-09-17 20:30:24'),
(49, 'Pabna', 6, '2025-09-17 20:30:24'),
(50, 'Rajshahi', 6, '2025-09-17 20:30:24'),
(51, 'Sirajganj', 6, '2025-09-17 20:30:24'),
(52, 'Dinajpur', 7, '2025-09-17 20:30:24'),
(53, 'Gaibandha', 7, '2025-09-17 20:30:24'),
(54, 'Kurigram', 7, '2025-09-17 20:30:24'),
(55, 'Lalmonirhat', 7, '2025-09-17 20:30:24'),
(56, 'Nilphamari', 7, '2025-09-17 20:30:24'),
(57, 'Panchagarh', 7, '2025-09-17 20:30:24'),
(58, 'Rangpur', 7, '2025-09-17 20:30:24'),
(59, 'Thakurgaon', 7, '2025-09-17 20:30:24'),
(60, 'Habiganj', 8, '2025-09-17 20:30:24'),
(61, 'Moulvibazar', 8, '2025-09-17 20:30:24'),
(62, 'Sunamganj', 8, '2025-09-17 20:30:24'),
(63, 'Sylhet', 8, '2025-09-17 20:30:24');

-- --------------------------------------------------------

--
-- Table structure for table `divisions`
--

CREATE TABLE `divisions` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `divisions`
--

INSERT INTO `divisions` (`id`, `name`, `created_at`) VALUES
(1, 'Barishal', '2025-09-17 20:30:24'),
(2, 'Chattogram', '2025-09-17 20:30:24'),
(3, 'Dhaka', '2025-09-17 20:30:24'),
(4, 'Khulna', '2025-09-17 20:30:24'),
(5, 'Mymensingh', '2025-09-17 20:30:24'),
(6, 'Rajshahi', '2025-09-17 20:30:24'),
(7, 'Rangpur', '2025-09-17 20:30:24'),
(8, 'Sylhet', '2025-09-17 20:30:24');

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `bmdc_code` varchar(20) NOT NULL,
  `specialization` varchar(100) DEFAULT NULL,
  `experience_years` int(11) DEFAULT 0,
  `consultation_fee` decimal(10,2) DEFAULT 0.00,
  `is_verified` tinyint(1) DEFAULT 0,
  `verification_document` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`id`, `user_id`, `bmdc_code`, `specialization`, `experience_years`, `consultation_fee`, `is_verified`, `verification_document`, `created_at`, `updated_at`) VALUES
(1, 2, 'A-12345', 'Cardiologist', 15, 1500.00, 1, NULL, '2025-09-17 20:30:24', '2025-09-17 20:30:24'),
(2, 3, 'B-12345', 'Gynecologist', 12, 1200.00, 1, NULL, '2025-09-17 20:30:24', '2025-09-17 20:30:24'),
(3, 4, 'C-12345', 'Pediatrician', 10, 1000.00, 1, NULL, '2025-09-17 20:30:24', '2025-09-17 20:30:24');

-- --------------------------------------------------------

--
-- Table structure for table `doctor_compounders`
--

CREATE TABLE `doctor_compounders` (
  `id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `nurse_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `doctor_hospitals`
--

CREATE TABLE `doctor_hospitals` (
  `id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `hospital_id` int(11) NOT NULL,
  `status` enum('pending','accepted','rejected') DEFAULT 'pending',
  `requested_by` enum('doctor','admin') NOT NULL,
  `requested_by_id` int(11) NOT NULL,
  `request_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `response_date` timestamp NULL DEFAULT NULL,
  `response_message` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctor_hospitals`
--

INSERT INTO `doctor_hospitals` (`id`, `doctor_id`, `hospital_id`, `status`, `requested_by`, `requested_by_id`, `request_date`, `response_date`, `response_message`) VALUES
(1, 1, 1, 'accepted', 'admin', 1, '2025-09-17 20:30:24', NULL, NULL),
(2, 2, 1, 'accepted', 'admin', 1, '2025-09-17 20:30:24', NULL, NULL),
(3, 3, 1, 'accepted', 'admin', 1, '2025-09-17 20:30:24', NULL, NULL),
(4, 1, 11, 'pending', 'doctor', 2, '2025-09-17 20:30:24', NULL, NULL),
(5, 2, 12, 'pending', 'doctor', 3, '2025-09-17 20:30:24', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `doctor_slots`
--

CREATE TABLE `doctor_slots` (
  `id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `hospital_id` int(11) NOT NULL,
  `day_of_week` enum('saturday','sunday','monday','tuesday','wednesday','thursday','friday') NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `max_patients` int(11) DEFAULT 10,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctor_slots`
--

INSERT INTO `doctor_slots` (`id`, `doctor_id`, `hospital_id`, `day_of_week`, `start_time`, `end_time`, `max_patients`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'saturday', '09:00:00', '12:00:00', 10, 1, '2025-09-17 20:30:24', '2025-09-17 20:30:24'),
(2, 1, 1, 'sunday', '09:00:00', '12:00:00', 10, 1, '2025-09-17 20:30:24', '2025-09-17 20:30:24'),
(3, 1, 1, 'monday', '14:00:00', '17:00:00', 8, 1, '2025-09-17 20:30:24', '2025-09-17 20:30:24'),
(4, 2, 1, 'tuesday', '10:00:00', '13:00:00', 10, 1, '2025-09-17 20:30:24', '2025-09-17 20:30:24'),
(5, 2, 1, 'wednesday', '10:00:00', '13:00:00', 10, 1, '2025-09-17 20:30:24', '2025-09-17 20:30:24'),
(6, 3, 1, 'thursday', '15:00:00', '18:00:00', 12, 1, '2025-09-17 20:30:24', '2025-09-17 20:30:24');

--
-- Triggers `doctor_slots`
--
DELIMITER $$
CREATE TRIGGER `prevent_overlapping_doctor_slots` BEFORE INSERT ON `doctor_slots` FOR EACH ROW BEGIN
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
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `doctor_upcoming_appointments`
-- (See below for the actual view)
--
CREATE TABLE `doctor_upcoming_appointments` (
`doctor_id` int(11)
,`doctor_name` varchar(100)
,`appointment_id` int(11)
,`patient_id` int(11)
,`patient_name` varchar(100)
,`appointment_date` date
,`appointment_time` time
,`status` enum('pending','confirmed','in_progress','completed','cancelled','no_show')
);

-- --------------------------------------------------------

--
-- Table structure for table `hospitals`
--

CREATE TABLE `hospitals` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `address` varchar(255) NOT NULL,
  `district` varchar(50) NOT NULL,
  `division` varchar(50) NOT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `longitude` decimal(11,8) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `hospital_type` enum('government','private','diagnostic','specialized') DEFAULT 'private',
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hospitals`
--

INSERT INTO `hospitals` (`id`, `name`, `description`, `address`, `district`, `division`, `latitude`, `longitude`, `phone`, `email`, `website`, `hospital_type`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Dhaka Medical College Hospital', NULL, 'Shahbag, Dhaka', 'Dhaka', 'Dhaka', 23.73290000, 90.40840000, '+8802-9661051', NULL, NULL, 'government', 1, '2025-09-17 20:30:24', '2025-09-17 20:30:24'),
(2, 'Bangabandhu Sheikh Mujib Medical University', NULL, 'Shahbag, Dhaka', 'Dhaka', 'Dhaka', 23.73440000, 90.40410000, '+8802-9663000', NULL, NULL, 'government', 1, '2025-09-17 20:30:24', '2025-09-17 20:30:24'),
(3, 'National Institute of Cardiovascular Diseases', NULL, 'Sher-E-Bangla Nagar, Dhaka', 'Dhaka', 'Dhaka', 23.77740000, 90.37650000, '+8802-9130275', NULL, NULL, 'government', 1, '2025-09-17 20:30:24', '2025-09-17 20:30:24'),
(4, 'Chittagong Medical College Hospital', NULL, 'Chawkbazar, Chittagong', 'Chattogram', 'Chattogram', 22.35150000, 91.83120000, '+88031-619400', NULL, NULL, 'government', 1, '2025-09-17 20:30:24', '2025-09-17 20:30:24'),
(5, 'Rajshahi Medical College Hospital', NULL, 'Rajshahi', 'Rajshahi', 'Rajshahi', 24.36360000, 88.62410000, '+880721-772055', NULL, NULL, 'government', 1, '2025-09-17 20:30:24', '2025-09-17 20:30:24'),
(6, 'Khulna Medical College Hospital', NULL, 'Khulna', 'Khulna', 'Khulna', 22.81580000, 89.53960000, '+88041-760371', NULL, NULL, 'government', 1, '2025-09-17 20:30:24', '2025-09-17 20:30:24'),
(7, 'Barishal Medical College Hospital', NULL, 'Barishal', 'Barishal', 'Barishal', 22.70100000, 90.35350000, '+880431-27670', NULL, NULL, 'government', 1, '2025-09-17 20:30:24', '2025-09-17 20:30:24'),
(8, 'Sylhet MAG Osmani Medical College', NULL, 'Sylhet', 'Sylhet', 'Sylhet', 24.75370000, 91.87190000, '+880821-713300', NULL, NULL, 'government', 1, '2025-09-17 20:30:24', '2025-09-17 20:30:24'),
(9, 'Mymensingh Medical College Hospital', NULL, 'Mymensingh', 'Mymensingh', 'Mymensingh', 24.74710000, 90.41150000, '+88091-65521', NULL, NULL, 'government', 1, '2025-09-17 20:30:24', '2025-09-17 20:30:24'),
(10, 'Rangpur Medical College Hospital', NULL, 'Rangpur', 'Rangpur', 'Rangpur', 25.74690000, 89.25070000, '+880521-62268', NULL, NULL, 'government', 1, '2025-09-17 20:30:24', '2025-09-17 20:30:24'),
(11, 'United Hospital Limited', NULL, 'Plot 15, Road 71, Gulshan, Dhaka', 'Dhaka', 'Dhaka', 23.79250000, 90.40780000, '+8802-8836000', NULL, NULL, 'private', 1, '2025-09-17 20:30:24', '2025-09-17 20:30:24'),
(12, 'Evercare Hospital Dhaka', NULL, 'Plot 81, Block E, Bashundhara R/A, Dhaka', 'Dhaka', 'Dhaka', 23.81450000, 90.42530000, '+8802-55014444', NULL, NULL, 'private', 1, '2025-09-17 20:30:24', '2025-09-17 20:30:24'),
(13, 'Ibn Sina Diagnostic & Consultation Center', NULL, '1/1, Bir Uttam AK Khandakar Road, Dhaka', 'Dhaka', 'Dhaka', 23.75760000, 90.38940000, '+8802-9661555', NULL, NULL, 'diagnostic', 1, '2025-09-17 20:30:24', '2025-09-17 20:30:24'),
(14, 'Labaid Hospital', NULL, 'House 78, Road 11A, Dhanmondi, Dhaka', 'Dhaka', 'Dhaka', 23.74650000, 90.38300000, '+8802-9143777', NULL, NULL, 'private', 1, '2025-09-17 20:30:24', '2025-09-17 20:30:24'),
(15, 'Popular Diagnostic Center', NULL, 'House 11, Road 2, Dhanmondi, Dhaka', 'Dhaka', 'Dhaka', 23.74690000, 90.38270000, '+8802-8617791', NULL, NULL, 'diagnostic', 1, '2025-09-17 20:30:24', '2025-09-17 20:30:24');

-- --------------------------------------------------------

--
-- Table structure for table `hospital_admins`
--

CREATE TABLE `hospital_admins` (
  `id` int(11) NOT NULL,
  `hospital_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hospital_admins`
--

INSERT INTO `hospital_admins` (`id`, `hospital_id`, `admin_id`, `created_at`) VALUES
(1, 1, 1, '2025-09-17 20:30:24');

-- --------------------------------------------------------

--
-- Table structure for table `icu_beds`
--

CREATE TABLE `icu_beds` (
  `id` int(11) NOT NULL,
  `hospital_id` int(11) NOT NULL,
  `bed_number` varchar(20) NOT NULL,
  `is_occupied` tinyint(1) DEFAULT 0,
  `current_patient_id` int(11) DEFAULT NULL,
  `ventilator` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `icu_beds`
--

INSERT INTO `icu_beds` (`id`, `hospital_id`, `bed_number`, `is_occupied`, `current_patient_id`, `ventilator`, `created_at`, `updated_at`) VALUES
(1, 1, 'ICU-001', 0, NULL, 1, '2025-09-17 20:30:24', '2025-09-17 20:30:24'),
(2, 1, 'ICU-002', 0, NULL, 1, '2025-09-17 20:30:24', '2025-09-17 20:30:24'),
(3, 1, 'ICU-003', 0, NULL, 0, '2025-09-17 20:30:24', '2025-09-17 20:30:24'),
(4, 1, 'ICU-004', 0, NULL, 0, '2025-09-17 20:30:24', '2025-09-17 20:30:24'),
(5, 1, 'ICU-005', 0, NULL, 1, '2025-09-17 20:30:24', '2025-09-17 20:30:24');

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `invoice_number` varchar(50) NOT NULL,
  `issue_date` date NOT NULL,
  `due_date` date NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `tax` decimal(10,2) DEFAULT 0.00,
  `discount` decimal(10,2) DEFAULT 0.00,
  `total_amount` decimal(10,2) NOT NULL,
  `amount_paid` decimal(10,2) DEFAULT 0.00,
  `status` enum('draft','issued','paid','overdue','cancelled') DEFAULT 'draft',
  `payment_method` varchar(50) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_items`
--

CREATE TABLE `invoice_items` (
  `id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `item_type` enum('consultation','daycare','medicine','test','ambulance','other') NOT NULL,
  `description` varchar(255) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `unit_price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `reference_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nurses`
--

CREATE TABLE `nurses` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `is_daycare` tinyint(1) DEFAULT 0,
  `is_compounder` tinyint(1) DEFAULT 0,
  `specialization` varchar(100) DEFAULT NULL,
  `license_number` varchar(50) DEFAULT NULL,
  `verification_status` enum('pending','verified','rejected') DEFAULT 'pending',
  `verified_hospital_id` int(11) DEFAULT NULL,
  `verification_date` timestamp NULL DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nurses`
--

INSERT INTO `nurses` (`id`, `user_id`, `is_daycare`, `is_compounder`, `specialization`, `license_number`, `verification_status`, `verified_hospital_id`, `verification_date`, `rejection_reason`, `created_at`, `updated_at`) VALUES
(1, 5, 1, 1, NULL, NULL, 'pending', NULL, NULL, NULL, '2025-09-17 20:30:24', '2025-09-17 20:30:24'),
(2, 6, 1, 0, NULL, NULL, 'pending', NULL, NULL, NULL, '2025-09-17 20:30:24', '2025-09-17 20:30:24');

-- --------------------------------------------------------

--
-- Table structure for table `nurse_slots`
--

CREATE TABLE `nurse_slots` (
  `id` int(11) NOT NULL,
  `nurse_id` int(11) NOT NULL,
  `hospital_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `purpose` enum('daycare','compounder_support') NOT NULL,
  `max_patients` int(11) DEFAULT 5,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nurse_slots`
--

INSERT INTO `nurse_slots` (`id`, `nurse_id`, `hospital_id`, `date`, `start_time`, `end_time`, `purpose`, `max_patients`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2025-09-19', '09:00:00', '12:00:00', 'daycare', 5, 1, '2025-09-17 20:30:24', '2025-09-17 20:30:24'),
(2, 1, 1, '2025-09-19', '14:00:00', '17:00:00', 'compounder_support', 8, 1, '2025-09-17 20:30:24', '2025-09-17 20:30:24'),
(3, 2, 1, '2025-09-20', '10:00:00', '13:00:00', 'daycare', 5, 1, '2025-09-17 20:30:24', '2025-09-17 20:30:24');

--
-- Triggers `nurse_slots`
--
DELIMITER $$
CREATE TRIGGER `prevent_overlapping_nurse_slots` BEFORE INSERT ON `nurse_slots` FOR EACH ROW BEGIN
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
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `nurse_verification_requests`
--

CREATE TABLE `nurse_verification_requests` (
  `id` int(11) NOT NULL,
  `nurse_id` int(11) NOT NULL,
  `hospital_id` int(11) NOT NULL,
  `requested_by` enum('nurse','hospital') NOT NULL,
  `requested_by_id` int(11) NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `message` text DEFAULT NULL,
  `response_message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `patient_diseases`
--

CREATE TABLE `patient_diseases` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `disease_id` int(11) NOT NULL,
  `diagnosis_date` date NOT NULL,
  `status` enum('active','recovered','chronic') DEFAULT 'active',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patient_diseases`
--

INSERT INTO `patient_diseases` (`id`, `patient_id`, `disease_id`, `diagnosis_date`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(1, 7, 1, '2025-09-18', 'active', NULL, '2025-09-17 20:30:24', '2025-09-17 20:30:24'),
(2, 8, 2, '2025-09-13', 'recovered', NULL, '2025-09-17 20:30:24', '2025-09-17 20:30:24'),
(3, 9, 3, '2025-09-08', 'active', NULL, '2025-09-17 20:30:24', '2025-09-17 20:30:24');

-- --------------------------------------------------------

--
-- Table structure for table `patient_reports`
--

CREATE TABLE `patient_reports` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `uploaded_by` int(11) NOT NULL,
  `report_type` varchar(50) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `file_path` varchar(255) NOT NULL,
  `is_confidential` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `patient_vitals`
--

CREATE TABLE `patient_vitals` (
  `id` int(11) NOT NULL,
  `appointment_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `recorded_by` int(11) NOT NULL,
  `blood_pressure_systolic` int(11) DEFAULT NULL,
  `blood_pressure_diastolic` int(11) DEFAULT NULL,
  `spo2` decimal(5,2) DEFAULT NULL,
  `pulse` int(11) DEFAULT NULL,
  `temperature` decimal(5,2) DEFAULT NULL,
  `weight` decimal(5,2) DEFAULT NULL,
  `height` decimal(5,2) DEFAULT NULL,
  `symptoms` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `description`, `created_at`) VALUES
(1, 'appointment.create', 'Ability to create appointments', '2025-09-17 20:30:24'),
(2, 'appointment.view.self', 'Ability to view own appointments', '2025-09-17 20:30:24'),
(3, 'appointment.view.doctor', 'Ability to view doctor appointments', '2025-09-17 20:30:24'),
(4, 'patient_queue.view', 'Ability to view patient queue', '2025-09-17 20:30:24'),
(5, 'prescriptions.create', 'Ability to create prescriptions', '2025-09-17 20:30:24'),
(6, 'prescriptions.view', 'Ability to view prescriptions', '2025-09-17 20:30:24'),
(7, 'reports.view', 'Ability to view reports', '2025-09-17 20:30:24'),
(8, 'compounder.assign', 'Ability to assign compounder', '2025-09-17 20:30:24'),
(9, 'bmdc.verify', 'Ability to verify BM&DC code', '2025-09-17 20:30:24'),
(10, 'ratings.view', 'Ability to view ratings', '2025-09-17 20:30:24'),
(11, 'vitals.capture', 'Ability to capture patient vitals', '2025-09-17 20:30:24'),
(12, 'appointment_bill.update', 'Ability to update appointment bills', '2025-09-17 20:30:24'),
(13, 'nurse_schedule.manage', 'Ability to manage nurse schedule', '2025-09-17 20:30:24'),
(14, 'daycare.review_docs', 'Ability to review daycare documents', '2025-09-17 20:30:24'),
(15, 'verification.request', 'Ability to request verification', '2025-09-17 20:30:24'),
(16, 'hospital.change', 'Ability to change hospital', '2025-09-17 20:30:24'),
(17, 'users.manage', 'Ability to manage users', '2025-09-17 20:30:24'),
(18, 'reports.upload', 'Ability to upload reports', '2025-09-17 20:30:24'),
(19, 'reports.edit', 'Ability to edit reports', '2025-09-17 20:30:24'),
(20, 'reports.remove', 'Ability to remove reports', '2025-09-17 20:30:24'),
(21, 'icu.update', 'Ability to update ICU information', '2025-09-17 20:30:24'),
(22, 'ambulance.update', 'Ability to update ambulance information', '2025-09-17 20:30:24'),
(23, 'daycare.manage', 'Ability to manage daycare', '2025-09-17 20:30:24'),
(24, 'invoices.manage', 'Ability to manage invoices', '2025-09-17 20:30:24'),
(25, 'nurse.verify', 'Ability to verify nurses', '2025-09-17 20:30:24'),
(26, 'hospital_bill.update', 'Ability to update hospital bills', '2025-09-17 20:30:24'),
(27, 'hospital.manage', 'Ability to manage hospitals', '2025-09-17 20:30:24'),
(28, 'chat.with_doctor', 'Ability to chat with doctors', '2025-09-17 20:30:24'),
(29, 'chat.with_daycare_nurse', 'Ability to chat with daycare nurses', '2025-09-17 20:30:24'),
(30, 'chat.with_patient', 'Ability to chat with patients', '2025-09-17 20:30:24'),
(31, 'appointment_slot.create', 'Ability to create appointment slots', '2025-09-17 20:30:24'),
(32, 'appointment_slot.manage', 'Ability to manage appointment slots', '2025-09-17 20:30:24'),
(33, 'doctor_hospital_association.request', 'Ability to request doctor-hospital association', '2025-09-17 20:30:24'),
(34, 'doctor_hospital_association.respond', 'Ability to respond to doctor-hospital association requests', '2025-09-17 20:30:24');

-- --------------------------------------------------------

--
-- Table structure for table `prescriptions`
--

CREATE TABLE `prescriptions` (
  `id` int(11) NOT NULL,
  `appointment_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `diagnosis` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `follow_up_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prescription_items`
--

CREATE TABLE `prescription_items` (
  `id` int(11) NOT NULL,
  `prescription_id` int(11) NOT NULL,
  `medicine_name` varchar(100) NOT NULL,
  `dosage` varchar(50) DEFAULT NULL,
  `frequency` varchar(50) DEFAULT NULL,
  `duration` varchar(50) DEFAULT NULL,
  `instructions` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `description`, `created_at`) VALUES
(1, 'patient', 'Patient role', '2025-09-17 20:30:24'),
(2, 'doctor', 'Doctor role', '2025-09-17 20:30:24'),
(3, 'nurse', 'Nurse role', '2025-09-17 20:30:24'),
(4, 'compounder', 'Compounder role', '2025-09-17 20:30:24'),
(5, 'ambulance', 'Ambulance Driver role', '2025-09-17 20:30:24'),
(6, 'admin', 'Administrator role', '2025-09-17 20:30:24');

-- --------------------------------------------------------

--
-- Table structure for table `role_permissions`
--

CREATE TABLE `role_permissions` (
  `id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role_permissions`
--

INSERT INTO `role_permissions` (`id`, `role_id`, `permission_id`, `created_at`) VALUES
(1, 6, 22, '2025-09-17 20:30:24'),
(2, 1, 1, '2025-09-17 20:30:24'),
(3, 2, 3, '2025-09-17 20:30:24'),
(4, 1, 2, '2025-09-17 20:30:24'),
(5, 4, 12, '2025-09-17 20:30:24'),
(6, 6, 31, '2025-09-17 20:30:24'),
(7, 2, 31, '2025-09-17 20:30:24'),
(8, 6, 32, '2025-09-17 20:30:24'),
(9, 2, 32, '2025-09-17 20:30:24'),
(10, 2, 9, '2025-09-17 20:30:24'),
(11, 1, 29, '2025-09-17 20:30:24'),
(12, 1, 28, '2025-09-17 20:30:24'),
(13, 2, 30, '2025-09-17 20:30:24'),
(14, 3, 30, '2025-09-17 20:30:24'),
(15, 2, 8, '2025-09-17 20:30:24'),
(16, 6, 23, '2025-09-17 20:30:24'),
(17, 3, 14, '2025-09-17 20:30:24'),
(18, 6, 33, '2025-09-17 20:30:24'),
(19, 2, 34, '2025-09-17 20:30:24'),
(20, 3, 16, '2025-09-17 20:30:24'),
(21, 6, 27, '2025-09-17 20:30:24'),
(22, 6, 26, '2025-09-17 20:30:24'),
(23, 6, 21, '2025-09-17 20:30:24'),
(24, 6, 24, '2025-09-17 20:30:24'),
(25, 6, 25, '2025-09-17 20:30:24'),
(26, 3, 13, '2025-09-17 20:30:24'),
(27, 4, 4, '2025-09-17 20:30:24'),
(28, 2, 4, '2025-09-17 20:30:24'),
(29, 2, 5, '2025-09-17 20:30:24'),
(30, 4, 6, '2025-09-17 20:30:24'),
(31, 2, 6, '2025-09-17 20:30:24'),
(32, 2, 10, '2025-09-17 20:30:24'),
(33, 3, 10, '2025-09-17 20:30:24'),
(34, 6, 19, '2025-09-17 20:30:24'),
(35, 6, 20, '2025-09-17 20:30:24'),
(36, 6, 18, '2025-09-17 20:30:24'),
(37, 4, 7, '2025-09-17 20:30:24'),
(38, 2, 7, '2025-09-17 20:30:24'),
(39, 6, 17, '2025-09-17 20:30:24'),
(40, 3, 15, '2025-09-17 20:30:24'),
(41, 4, 11, '2025-09-17 20:30:24');

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(50) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`id`, `setting_key`, `setting_value`, `description`, `created_at`, `updated_at`) VALUES
(1, 'site_name', 'Niramoy (নিরাময়)', 'Name of the healthcare system', '2025-09-17 20:30:24', '2025-09-17 20:30:24'),
(2, 'site_description', 'Bangladesh Healthcare Management System', 'Description of the system', '2025-09-17 20:30:24', '2025-09-17 20:30:24'),
(3, 'admin_email', 'admin@niramoy.com.bd', 'Default admin email', '2025-09-17 20:30:24', '2025-09-17 20:30:24'),
(4, 'ambulance_base_fare', '100.00', 'Base fare for ambulance service', '2025-09-17 20:30:24', '2025-09-17 20:30:24'),
(5, 'ambulance_per_km_fare', '30.00', 'Per kilometer fare for ambulance service', '2025-09-17 20:30:24', '2025-09-17 20:30:24'),
(6, 'max_appointment_per_slot', '10', 'Maximum patients per doctor slot', '2025-09-17 20:30:24', '2025-09-17 20:30:24'),
(7, 'max_daycare_per_slot', '5', 'Maximum patients per nurse slot', '2025-09-17 20:30:24', '2025-09-17 20:30:24'),
(8, 'default_currency', 'BDT', 'Default currency for the system', '2025-09-17 20:30:24', '2025-09-17 20:30:24'),
(9, 'date_format', 'd-m-Y', 'Default date format', '2025-09-17 20:30:24', '2025-09-17 20:30:24'),
(10, 'time_format', 'h:i A', 'Default time format', '2025-09-17 20:30:24', '2025-09-17 20:30:24');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('male','female','other') DEFAULT NULL,
  `blood_group` enum('A+','A-','B+','B-','AB+','AB-','O+','O-') DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `address`, `date_of_birth`, `gender`, `blood_group`, `profile_image`, `created_at`, `updated_at`) VALUES
(1, 'System Admin', 'admin@niramoy.com.bd', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+8801711111111', NULL, NULL, NULL, NULL, NULL, '2025-09-17 20:30:24', '2025-09-17 20:30:24'),
(2, 'Dr. Ahmed Hasan', 'ahmed.hasan@niramoy.com.bd', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+8801711111112', NULL, NULL, NULL, NULL, NULL, '2025-09-17 20:30:24', '2025-09-17 20:30:24'),
(3, 'Dr. Fatema Begum', 'fatema.begum@niramoy.com.bd', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+8801711111113', NULL, NULL, NULL, NULL, NULL, '2025-09-17 20:30:24', '2025-09-17 20:30:24'),
(4, 'Dr. Mohammad Ali', 'mohammad.ali@niramoy.com.bd', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+8801711111114', NULL, NULL, NULL, NULL, NULL, '2025-09-17 20:30:24', '2025-09-17 20:30:24'),
(5, 'Nurse Ayesha Siddiqua', 'ayesha.siddiqua@niramoy.com.bd', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+8801711111115', NULL, NULL, NULL, NULL, NULL, '2025-09-17 20:30:24', '2025-09-17 20:30:24'),
(6, 'Nurse Karim Ahmed', 'karim.ahmed@niramoy.com.bd', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+8801711111116', NULL, NULL, NULL, NULL, NULL, '2025-09-17 20:30:24', '2025-09-17 20:30:24'),
(7, 'Rahim Khan', 'rahim.khan@niramoy.com.bd', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+8801711111117', NULL, '1985-05-15', 'male', 'B+', NULL, '2025-09-17 20:30:24', '2025-09-17 20:30:24'),
(8, 'Karima Begum', 'karima.begum@niramoy.com.bd', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+8801711111118', NULL, '1990-08-22', 'female', 'O+', NULL, '2025-09-17 20:30:24', '2025-09-17 20:30:24'),
(9, 'Jamal Uddin', 'jamal.uddin@niramoy.com.bd', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+8801711111119', NULL, '1978-12-10', 'male', 'A+', NULL, '2025-09-17 20:30:24', '2025-09-17 20:30:24'),
(10, 'Abdul Karim', 'abdul.karim@niramoy.com.bd', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+8801711111120', NULL, NULL, NULL, NULL, NULL, '2025-09-17 20:30:24', '2025-09-17 20:30:24'),
(11, 'Mohammad Rafiq', 'mohammad.rafiq@niramoy.com.bd', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+8801711111121', NULL, NULL, NULL, NULL, NULL, '2025-09-17 20:30:24', '2025-09-17 20:30:24'),
(12, 'Abul Kashem', 'abul.kashem@niramoy.com.bd', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+8801711111122', NULL, NULL, NULL, NULL, NULL, '2025-09-17 20:30:24', '2025-09-17 20:30:24');

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

CREATE TABLE `user_roles` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_roles`
--

INSERT INTO `user_roles` (`id`, `user_id`, `role_id`, `created_at`) VALUES
(1, 1, 6, '2025-09-17 20:30:24'),
(2, 2, 2, '2025-09-17 20:30:24'),
(3, 3, 2, '2025-09-17 20:30:24'),
(4, 4, 2, '2025-09-17 20:30:24'),
(5, 5, 3, '2025-09-17 20:30:24'),
(6, 6, 3, '2025-09-17 20:30:24'),
(7, 7, 1, '2025-09-17 20:30:24'),
(8, 8, 1, '2025-09-17 20:30:24'),
(9, 9, 1, '2025-09-17 20:30:24'),
(10, 10, 5, '2025-09-17 20:30:24'),
(11, 11, 5, '2025-09-17 20:30:24'),
(12, 12, 5, '2025-09-17 20:30:24');

-- --------------------------------------------------------

--
-- Structure for view `active_hospital_doctors`
--
DROP TABLE IF EXISTS `active_hospital_doctors`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `active_hospital_doctors`  AS SELECT `h`.`id` AS `hospital_id`, `h`.`name` AS `hospital_name`, `d`.`id` AS `doctor_id`, `u`.`name` AS `doctor_name`, `d`.`specialization` AS `specialization`, `d`.`consultation_fee` AS `consultation_fee` FROM (((`hospitals` `h` join `doctor_hospitals` `dh` on(`h`.`id` = `dh`.`hospital_id`)) join `doctors` `d` on(`dh`.`doctor_id` = `d`.`id`)) join `users` `u` on(`d`.`user_id` = `u`.`id`)) WHERE `dh`.`status` = 'accepted' AND `d`.`is_verified` = 1 ;

-- --------------------------------------------------------

--
-- Structure for view `chat_conversations`
--
DROP TABLE IF EXISTS `chat_conversations`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `chat_conversations`  AS SELECT `c`.`id` AS `id`, `c`.`sender_id` AS `sender_id`, `s`.`name` AS `sender_name`, `c`.`receiver_id` AS `receiver_id`, `r`.`name` AS `receiver_name`, `c`.`message` AS `message`, `c`.`timestamp` AS `timestamp`, `c`.`is_read` AS `is_read`, CASE WHEN `c`.`related_appointment_id` is not null THEN 'appointment' WHEN `c`.`related_daycare_booking_id` is not null THEN 'daycare' ELSE 'general' END AS `chat_type` FROM ((`chats` `c` join `users` `s` on(`c`.`sender_id` = `s`.`id`)) join `users` `r` on(`c`.`receiver_id` = `r`.`id`)) ORDER BY `c`.`timestamp` DESC ;

-- --------------------------------------------------------

--
-- Structure for view `doctor_upcoming_appointments`
--
DROP TABLE IF EXISTS `doctor_upcoming_appointments`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `doctor_upcoming_appointments`  AS SELECT `d`.`id` AS `doctor_id`, `u`.`name` AS `doctor_name`, `a`.`id` AS `appointment_id`, `p`.`id` AS `patient_id`, `p`.`name` AS `patient_name`, `a`.`appointment_date` AS `appointment_date`, `a`.`appointment_time` AS `appointment_time`, `a`.`status` AS `status` FROM (((`doctors` `d` join `users` `u` on(`d`.`user_id` = `u`.`id`)) join `appointments` `a` on(`d`.`id` = `a`.`doctor_id`)) join `users` `p` on(`a`.`patient_id` = `p`.`id`)) WHERE `a`.`appointment_date` >= curdate() AND `a`.`status` in ('pending','confirmed') ORDER BY `a`.`appointment_date` ASC, `a`.`appointment_time` ASC ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ambulances`
--
ALTER TABLE `ambulances`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vehicle_number` (`vehicle_number`),
  ADD KEY `hospital_id` (`hospital_id`);

--
-- Indexes for table `ambulance_paths`
--
ALTER TABLE `ambulance_paths`
  ADD PRIMARY KEY (`id`),
  ADD KEY `trip_id` (`trip_id`);

--
-- Indexes for table `ambulance_trips`
--
ALTER TABLE `ambulance_trips`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ambulance_id` (`ambulance_id`),
  ADD KEY `patient_id` (`patient_id`);

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `doctor_id` (`doctor_id`),
  ADD KEY `hospital_id` (`hospital_id`),
  ADD KEY `slot_id` (`slot_id`);

--
-- Indexes for table `appointment_slots`
--
ALTER TABLE `appointment_slots`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `idx_doctor_time` (`doctor_id`,`start_time`,`end_time`),
  ADD KEY `idx_hospital_time` (`hospital_id`,`start_time`,`end_time`);

--
-- Indexes for table `chats`
--
ALTER TABLE `chats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `receiver_id` (`receiver_id`),
  ADD KEY `idx_sender_receiver` (`sender_id`,`receiver_id`),
  ADD KEY `idx_appointment` (`related_appointment_id`),
  ADD KEY `idx_daycare_booking` (`related_daycare_booking_id`);

--
-- Indexes for table `daycare_bookings`
--
ALTER TABLE `daycare_bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `nurse_id` (`nurse_id`),
  ADD KEY `hospital_id` (`hospital_id`),
  ADD KEY `procedure_id` (`procedure_id`),
  ADD KEY `slot_id` (`slot_id`);

--
-- Indexes for table `daycare_procedures`
--
ALTER TABLE `daycare_procedures`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hospital_id` (`hospital_id`);

--
-- Indexes for table `diseases`
--
ALTER TABLE `diseases`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `districts`
--
ALTER TABLE `districts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `division_id` (`division_id`);

--
-- Indexes for table `divisions`
--
ALTER TABLE `divisions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bmdc_code` (`bmdc_code`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `doctor_compounders`
--
ALTER TABLE `doctor_compounders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `doctor_nurse_unique` (`doctor_id`,`nurse_id`),
  ADD KEY `nurse_id` (`nurse_id`);

--
-- Indexes for table `doctor_hospitals`
--
ALTER TABLE `doctor_hospitals`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `doctor_hospital_unique` (`doctor_id`,`hospital_id`),
  ADD KEY `requested_by_id` (`requested_by_id`),
  ADD KEY `idx_doctor_status` (`doctor_id`,`status`),
  ADD KEY `idx_hospital_status` (`hospital_id`,`status`);

--
-- Indexes for table `doctor_slots`
--
ALTER TABLE `doctor_slots`
  ADD PRIMARY KEY (`id`),
  ADD KEY `doctor_id` (`doctor_id`),
  ADD KEY `hospital_id` (`hospital_id`);

--
-- Indexes for table `hospitals`
--
ALTER TABLE `hospitals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hospital_admins`
--
ALTER TABLE `hospital_admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `hospital_admin_unique` (`hospital_id`,`admin_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `icu_beds`
--
ALTER TABLE `icu_beds`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `hospital_bed_unique` (`hospital_id`,`bed_number`),
  ADD KEY `current_patient_id` (`current_patient_id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoice_number` (`invoice_number`),
  ADD KEY `patient_id` (`patient_id`);

--
-- Indexes for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_id` (`invoice_id`);

--
-- Indexes for table `nurses`
--
ALTER TABLE `nurses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `verified_hospital_id` (`verified_hospital_id`);

--
-- Indexes for table `nurse_slots`
--
ALTER TABLE `nurse_slots`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nurse_id` (`nurse_id`),
  ADD KEY `hospital_id` (`hospital_id`);

--
-- Indexes for table `nurse_verification_requests`
--
ALTER TABLE `nurse_verification_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nurse_id` (`nurse_id`),
  ADD KEY `hospital_id` (`hospital_id`),
  ADD KEY `requested_by_id` (`requested_by_id`);

--
-- Indexes for table `patient_diseases`
--
ALTER TABLE `patient_diseases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `disease_id` (`disease_id`);

--
-- Indexes for table `patient_reports`
--
ALTER TABLE `patient_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `uploaded_by` (`uploaded_by`);

--
-- Indexes for table `patient_vitals`
--
ALTER TABLE `patient_vitals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `appointment_id` (`appointment_id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `recorded_by` (`recorded_by`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `prescriptions`
--
ALTER TABLE `prescriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `appointment_id` (`appointment_id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- Indexes for table `prescription_items`
--
ALTER TABLE `prescription_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `prescription_id` (`prescription_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `role_permission_unique` (`role_id`,`permission_id`),
  ADD KEY `permission_id` (`permission_id`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_role_unique` (`user_id`,`role_id`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ambulances`
--
ALTER TABLE `ambulances`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ambulance_paths`
--
ALTER TABLE `ambulance_paths`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ambulance_trips`
--
ALTER TABLE `ambulance_trips`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `appointment_slots`
--
ALTER TABLE `appointment_slots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `chats`
--
ALTER TABLE `chats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `daycare_bookings`
--
ALTER TABLE `daycare_bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `daycare_procedures`
--
ALTER TABLE `daycare_procedures`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `diseases`
--
ALTER TABLE `diseases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `districts`
--
ALTER TABLE `districts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `divisions`
--
ALTER TABLE `divisions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `doctor_compounders`
--
ALTER TABLE `doctor_compounders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `doctor_hospitals`
--
ALTER TABLE `doctor_hospitals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `doctor_slots`
--
ALTER TABLE `doctor_slots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `hospitals`
--
ALTER TABLE `hospitals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `hospital_admins`
--
ALTER TABLE `hospital_admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `icu_beds`
--
ALTER TABLE `icu_beds`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoice_items`
--
ALTER TABLE `invoice_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `nurses`
--
ALTER TABLE `nurses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `nurse_slots`
--
ALTER TABLE `nurse_slots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `nurse_verification_requests`
--
ALTER TABLE `nurse_verification_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `patient_diseases`
--
ALTER TABLE `patient_diseases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `patient_reports`
--
ALTER TABLE `patient_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `patient_vitals`
--
ALTER TABLE `patient_vitals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `prescriptions`
--
ALTER TABLE `prescriptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `prescription_items`
--
ALTER TABLE `prescription_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `role_permissions`
--
ALTER TABLE `role_permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `user_roles`
--
ALTER TABLE `user_roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ambulances`
--
ALTER TABLE `ambulances`
  ADD CONSTRAINT `ambulances_ibfk_1` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ambulance_paths`
--
ALTER TABLE `ambulance_paths`
  ADD CONSTRAINT `ambulance_paths_ibfk_1` FOREIGN KEY (`trip_id`) REFERENCES `ambulance_trips` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ambulance_trips`
--
ALTER TABLE `ambulance_trips`
  ADD CONSTRAINT `ambulance_trips_ibfk_1` FOREIGN KEY (`ambulance_id`) REFERENCES `ambulances` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ambulance_trips_ibfk_2` FOREIGN KEY (`patient_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointments_ibfk_3` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointments_ibfk_4` FOREIGN KEY (`slot_id`) REFERENCES `doctor_slots` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `appointment_slots`
--
ALTER TABLE `appointment_slots`
  ADD CONSTRAINT `appointment_slots_ibfk_1` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointment_slots_ibfk_2` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointment_slots_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `chats`
--
ALTER TABLE `chats`
  ADD CONSTRAINT `chats_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `chats_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `chats_ibfk_3` FOREIGN KEY (`related_appointment_id`) REFERENCES `appointments` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `chats_ibfk_4` FOREIGN KEY (`related_daycare_booking_id`) REFERENCES `daycare_bookings` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `daycare_bookings`
--
ALTER TABLE `daycare_bookings`
  ADD CONSTRAINT `daycare_bookings_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `daycare_bookings_ibfk_2` FOREIGN KEY (`nurse_id`) REFERENCES `nurses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `daycare_bookings_ibfk_3` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `daycare_bookings_ibfk_4` FOREIGN KEY (`procedure_id`) REFERENCES `daycare_procedures` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `daycare_bookings_ibfk_5` FOREIGN KEY (`slot_id`) REFERENCES `nurse_slots` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `daycare_procedures`
--
ALTER TABLE `daycare_procedures`
  ADD CONSTRAINT `daycare_procedures_ibfk_1` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `districts`
--
ALTER TABLE `districts`
  ADD CONSTRAINT `districts_ibfk_1` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `doctors`
--
ALTER TABLE `doctors`
  ADD CONSTRAINT `doctors_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `doctor_compounders`
--
ALTER TABLE `doctor_compounders`
  ADD CONSTRAINT `doctor_compounders_ibfk_1` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `doctor_compounders_ibfk_2` FOREIGN KEY (`nurse_id`) REFERENCES `nurses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `doctor_hospitals`
--
ALTER TABLE `doctor_hospitals`
  ADD CONSTRAINT `doctor_hospitals_ibfk_1` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `doctor_hospitals_ibfk_2` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `doctor_hospitals_ibfk_3` FOREIGN KEY (`requested_by_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `doctor_slots`
--
ALTER TABLE `doctor_slots`
  ADD CONSTRAINT `doctor_slots_ibfk_1` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `doctor_slots_ibfk_2` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hospital_admins`
--
ALTER TABLE `hospital_admins`
  ADD CONSTRAINT `hospital_admins_ibfk_1` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hospital_admins_ibfk_2` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `icu_beds`
--
ALTER TABLE `icu_beds`
  ADD CONSTRAINT `icu_beds_ibfk_1` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `icu_beds_ibfk_2` FOREIGN KEY (`current_patient_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD CONSTRAINT `invoice_items_ibfk_1` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `nurses`
--
ALTER TABLE `nurses`
  ADD CONSTRAINT `nurses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `nurses_ibfk_2` FOREIGN KEY (`verified_hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `nurse_slots`
--
ALTER TABLE `nurse_slots`
  ADD CONSTRAINT `nurse_slots_ibfk_1` FOREIGN KEY (`nurse_id`) REFERENCES `nurses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `nurse_slots_ibfk_2` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `nurse_verification_requests`
--
ALTER TABLE `nurse_verification_requests`
  ADD CONSTRAINT `nurse_verification_requests_ibfk_1` FOREIGN KEY (`nurse_id`) REFERENCES `nurses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `nurse_verification_requests_ibfk_2` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `nurse_verification_requests_ibfk_3` FOREIGN KEY (`requested_by_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `patient_diseases`
--
ALTER TABLE `patient_diseases`
  ADD CONSTRAINT `patient_diseases_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `patient_diseases_ibfk_2` FOREIGN KEY (`disease_id`) REFERENCES `diseases` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `patient_reports`
--
ALTER TABLE `patient_reports`
  ADD CONSTRAINT `patient_reports_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `patient_reports_ibfk_2` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `patient_vitals`
--
ALTER TABLE `patient_vitals`
  ADD CONSTRAINT `patient_vitals_ibfk_1` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `patient_vitals_ibfk_2` FOREIGN KEY (`patient_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `patient_vitals_ibfk_3` FOREIGN KEY (`recorded_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `prescriptions`
--
ALTER TABLE `prescriptions`
  ADD CONSTRAINT `prescriptions_ibfk_1` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `prescriptions_ibfk_2` FOREIGN KEY (`patient_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `prescriptions_ibfk_3` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `prescription_items`
--
ALTER TABLE `prescription_items`
  ADD CONSTRAINT `prescription_items_ibfk_1` FOREIGN KEY (`prescription_id`) REFERENCES `prescriptions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD CONSTRAINT `role_permissions_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_permissions_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD CONSTRAINT `user_roles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_roles_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
