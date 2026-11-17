-- ============================================================
-- THE EAST HOTEL - Database SQL untuk phpMyAdmin / XAMPP
-- Database: the_east_hotel
-- Jalankan di phpMyAdmin atau via: mysql -u root -p < the_east_hotel.sql
-- ============================================================

SET FOREIGN_KEY_CHECKS = 0;
DROP DATABASE IF EXISTS `the_east_hotel`;
CREATE DATABASE `the_east_hotel` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `the_east_hotel`;

-- ----------------------------
-- Table: users
-- ----------------------------
CREATE TABLE `users` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `email_verified_at` TIMESTAMP NULL DEFAULT NULL,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('admin','staff') NOT NULL DEFAULT 'staff',
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `remember_token` VARCHAR(100) NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table: password_reset_tokens
-- ----------------------------
CREATE TABLE `password_reset_tokens` (
  `email` VARCHAR(255) NOT NULL,
  `token` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table: sessions
-- ----------------------------
CREATE TABLE `sessions` (
  `id` VARCHAR(255) NOT NULL,
  `user_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `ip_address` VARCHAR(45) NULL DEFAULT NULL,
  `user_agent` TEXT NULL DEFAULT NULL,
  `payload` LONGTEXT NOT NULL,
  `last_activity` INT NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table: cache
-- ----------------------------
CREATE TABLE `cache` (
  `key` VARCHAR(255) NOT NULL,
  `value` MEDIUMTEXT NOT NULL,
  `expiration` INT NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cache_locks` (
  `key` VARCHAR(255) NOT NULL,
  `owner` VARCHAR(255) NOT NULL,
  `expiration` INT NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table: jobs
-- ----------------------------
CREATE TABLE `jobs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` VARCHAR(255) NOT NULL,
  `payload` LONGTEXT NOT NULL,
  `attempts` TINYINT UNSIGNED NOT NULL,
  `reserved_at` INT UNSIGNED NULL DEFAULT NULL,
  `available_at` INT UNSIGNED NOT NULL,
  `created_at` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `job_batches` (
  `id` VARCHAR(255) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `total_jobs` INT NOT NULL,
  `pending_jobs` INT NOT NULL,
  `failed_jobs` INT NOT NULL,
  `failed_job_ids` LONGTEXT NOT NULL,
  `options` MEDIUMTEXT NULL DEFAULT NULL,
  `cancelled_at` INT NULL DEFAULT NULL,
  `created_at` INT NOT NULL,
  `finished_at` INT NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `failed_jobs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` VARCHAR(255) NOT NULL,
  `connection` TEXT NOT NULL,
  `queue` TEXT NOT NULL,
  `payload` LONGTEXT NOT NULL,
  `exception` LONGTEXT NOT NULL,
  `failed_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table: personal_access_tokens
-- ----------------------------
CREATE TABLE `personal_access_tokens` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` VARCHAR(255) NOT NULL,
  `tokenable_id` BIGINT UNSIGNED NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `token` VARCHAR(64) NOT NULL,
  `abilities` TEXT NULL DEFAULT NULL,
  `last_used_at` TIMESTAMP NULL DEFAULT NULL,
  `expires_at` TIMESTAMP NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table: guests
-- ----------------------------
CREATE TABLE `guests` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `full_name` VARCHAR(120) NOT NULL,
  `email` VARCHAR(150) NOT NULL,
  `phone` VARCHAR(30) NOT NULL,
  `country` VARCHAR(80) NULL DEFAULT NULL,
  `id_number` VARCHAR(50) NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `guests_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table: room_types
-- ----------------------------
CREATE TABLE `room_types` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(120) NOT NULL,
  `description` TEXT NOT NULL,
  `capacity` TINYINT UNSIGNED NOT NULL,
  `base_price` DECIMAL(12,2) NOT NULL,
  `size_m2` SMALLINT UNSIGNED NULL DEFAULT NULL,
  `bed_type` VARCHAR(60) NULL DEFAULT NULL,
  `image_url` VARCHAR(255) NULL DEFAULT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `room_types_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table: rooms
-- ----------------------------
CREATE TABLE `rooms` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `room_type_id` BIGINT UNSIGNED NOT NULL,
  `room_number` VARCHAR(10) NOT NULL,
  `floor` TINYINT UNSIGNED NOT NULL DEFAULT 1,
  `status` ENUM('available','maintenance','out_of_service') NOT NULL DEFAULT 'available',
  `notes` VARCHAR(255) NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `rooms_room_number_unique` (`room_number`),
  KEY `rooms_room_type_id_status_index` (`room_type_id`,`status`),
  CONSTRAINT `rooms_room_type_id_foreign` FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table: amenities
-- ----------------------------
CREATE TABLE `amenities` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `description` VARCHAR(255) NULL DEFAULT NULL,
  `scope` ENUM('hotel','room') NOT NULL DEFAULT 'room',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table: amenity_room_type (pivot)
-- ----------------------------
CREATE TABLE `amenity_room_type` (
  `amenity_id` BIGINT UNSIGNED NOT NULL,
  `room_type_id` BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY (`amenity_id`,`room_type_id`),
  CONSTRAINT `amenity_room_type_amenity_id_foreign` FOREIGN KEY (`amenity_id`) REFERENCES `amenities` (`id`) ON DELETE CASCADE,
  CONSTRAINT `amenity_room_type_room_type_id_foreign` FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table: reservations
-- ----------------------------
CREATE TABLE `reservations` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(12) NOT NULL,
  `guest_id` BIGINT UNSIGNED NOT NULL,
  `check_in` DATE NOT NULL,
  `check_out` DATE NOT NULL,
  `guests_count` TINYINT UNSIGNED NOT NULL,
  `nights` SMALLINT UNSIGNED NOT NULL,
  `subtotal` DECIMAL(12,2) NOT NULL,
  `service_amount` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `tax_amount` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `total_amount` DECIMAL(12,2) NOT NULL,
  `status` ENUM('pending','confirmed','checked_in','checked_out','cancelled') NOT NULL DEFAULT 'pending',
  `special_requests` VARCHAR(500) NULL DEFAULT NULL,
  `expires_at` TIMESTAMP NULL DEFAULT NULL,
  `cancelled_at` TIMESTAMP NULL DEFAULT NULL,
  `cancel_reason` VARCHAR(255) NULL DEFAULT NULL,
  `created_by` BIGINT UNSIGNED NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `reservations_code_unique` (`code`),
  KEY `reservations_check_in_check_out_index` (`check_in`,`check_out`),
  KEY `reservations_status_index` (`status`),
  CONSTRAINT `reservations_guest_id_foreign` FOREIGN KEY (`guest_id`) REFERENCES `guests` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `reservations_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table: reservation_details
-- ----------------------------
CREATE TABLE `reservation_details` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `reservation_id` BIGINT UNSIGNED NOT NULL,
  `room_id` BIGINT UNSIGNED NOT NULL,
  `room_type_id` BIGINT UNSIGNED NOT NULL,
  `price_per_night` DECIMAL(12,2) NOT NULL,
  `nights` SMALLINT UNSIGNED NOT NULL,
  `subtotal` DECIMAL(12,2) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  KEY `reservation_details_room_id_index` (`room_id`),
  CONSTRAINT `reservation_details_reservation_id_foreign` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `reservation_details_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `reservation_details_room_type_id_foreign` FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table: payments
-- ----------------------------
CREATE TABLE `payments` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `reservation_id` BIGINT UNSIGNED NOT NULL,
  `amount` DECIMAL(12,2) NOT NULL,
  `method` ENUM('simulation','pay_at_hotel','cash','bank_transfer') NOT NULL DEFAULT 'simulation',
  `status` ENUM('pending','paid','failed','refunded') NOT NULL DEFAULT 'pending',
  `reference` VARCHAR(40) NULL DEFAULT NULL,
  `paid_at` TIMESTAMP NULL DEFAULT NULL,
  `notes` VARCHAR(255) NULL DEFAULT NULL,
  `processed_by` BIGINT UNSIGNED NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payments_reference_unique` (`reference`),
  KEY `payments_status_index` (`status`),
  CONSTRAINT `payments_reservation_id_foreign` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `payments_processed_by_foreign` FOREIGN KEY (`processed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table: migrations (Laravel tracker)
-- ----------------------------
CREATE TABLE `migrations` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` VARCHAR(255) NOT NULL,
  `batch` INT NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `migrations` (`migration`, `batch`) VALUES
('0001_01_01_000000_create_users_table', 1),
('0001_01_01_000001_create_cache_table', 1),
('0001_01_01_000002_create_jobs_table', 1),
('2025_01_01_000001_add_role_to_users_table', 1),
('2025_01_01_000002_create_hotel_tables', 1),
('2026_11_17_034042_create_personal_access_tokens_table', 1);

-- ----------------------------
-- Data seed: Admin & Staff
-- Password: password123 (bcrypt)
-- ----------------------------
INSERT INTO `users` (`name`, `email`, `password`, `role`, `is_active`, `created_at`, `updated_at`) VALUES
('Admin The East', 'admin@theeasthotel.test', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 1, NOW(), NOW()),
('Staf Resepsionis', 'staf@theeasthotel.test', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'staff', 1, NOW(), NOW());

-- ----------------------------
-- Data seed: Amenities
-- ----------------------------
INSERT INTO `amenities` (`name`, `scope`, `description`, `created_at`, `updated_at`) VALUES
('Kolam renang tanpa batas', 'hotel', 'Kolam di tepi tebing menghadap Samudra Hindia', NOW(), NOW()),
('Spa & wellness', 'hotel', 'Pijat tradisional Bali dan ritual mandi bunga', NOW(), NOW()),
('Restoran tepi laut', 'hotel', 'Masakan Bali dan Nusantara dengan bahan lokal', NOW(), NOW()),
('Antar-jemput bandara', 'hotel', 'Tersedia dengan reservasi sebelumnya', NOW(), NOW()),
('Taman tropis', 'hotel', 'Jalur jalan kaki di antara kebun frangipani', NOW(), NOW()),
('Pendingin ruangan', 'room', NULL, NOW(), NOW()),
('Wi-Fi cepat', 'room', NULL, NOW(), NOW()),
('Balkon pribadi', 'room', NULL, NOW(), NOW()),
('Kolam renang pribadi', 'room', NULL, NOW(), NOW()),
('Bathtub outdoor', 'room', NULL, NOW(), NOW()),
('Sarapan harian', 'room', NULL, NOW(), NOW()),
('Dapur kecil', 'room', NULL, NOW(), NOW());

-- ----------------------------
-- Data seed: Room Types
-- ----------------------------
INSERT INTO `room_types` (`name`, `slug`, `description`, `capacity`, `base_price`, `size_m2`, `bed_type`, `is_active`, `created_at`, `updated_at`) VALUES
('Deluxe Garden', 'deluxe-garden', 'Kamar lapang dengan taman tropis pribadi, tempat tidur king, dan bathtub batu alam.', 2, 1450000.00, 42, 'King', 1, NOW(), NOW()),
('Ocean View Suite', 'ocean-view-suite', 'Suite dengan ruang tamu terpisah dan balkon menghadap laut, tempat menyaksikan matahari terbit.', 3, 2350000.00, 68, 'King + sofa bed', 1, NOW(), NOW()),
('Private Pool Villa', 'private-pool-villa', 'Vila satu kamar dengan kolam renang pribadi, paviliun bale, dan taman berpagar.', 3, 4800000.00, 110, 'King', 1, NOW(), NOW()),
('Family Villa', 'family-villa', 'Vila dua kamar dengan dapur kecil dan kolam, cocok untuk keluarga atau rombongan kecil.', 5, 6200000.00, 180, '2 King', 1, NOW(), NOW());

-- ----------------------------
-- Data seed: amenity_room_type pivot
-- ----------------------------
-- Deluxe Garden (id=1): AC(6), WiFi(7), Balkon(8), Sarapan(11)
INSERT INTO `amenity_room_type` (`amenity_id`, `room_type_id`) VALUES (6,1),(7,1),(8,1),(11,1);
-- Ocean View Suite (id=2): AC(6), WiFi(7), Balkon(8), Bathtub(10), Sarapan(11)
INSERT INTO `amenity_room_type` (`amenity_id`, `room_type_id`) VALUES (6,2),(7,2),(8,2),(10,2),(11,2);
-- Private Pool Villa (id=3): AC(6), WiFi(7), Kolam(9), Bathtub(10), Sarapan(11)
INSERT INTO `amenity_room_type` (`amenity_id`, `room_type_id`) VALUES (6,3),(7,3),(9,3),(10,3),(11,3);
-- Family Villa (id=4): AC(6), WiFi(7), Kolam(9), Dapur(12), Sarapan(11)
INSERT INTO `amenity_room_type` (`amenity_id`, `room_type_id`) VALUES (6,4),(7,4),(9,4),(11,4),(12,4);

-- ----------------------------
-- Data seed: Rooms
-- ----------------------------
-- Deluxe Garden - Floor 1
INSERT INTO `rooms` (`room_type_id`, `room_number`, `floor`, `status`, `created_at`, `updated_at`) VALUES
(1,'101',1,'available',NOW(),NOW()),(1,'102',1,'available',NOW(),NOW()),
(1,'103',1,'available',NOW(),NOW()),(1,'104',1,'available',NOW(),NOW()),
(1,'105',1,'available',NOW(),NOW()),(1,'106',1,'available',NOW(),NOW());
-- Ocean View Suite - Floor 2
INSERT INTO `rooms` (`room_type_id`, `room_number`, `floor`, `status`, `created_at`, `updated_at`) VALUES
(2,'201',2,'available',NOW(),NOW()),(2,'202',2,'available',NOW(),NOW()),
(2,'203',2,'available',NOW(),NOW()),(2,'204',2,'available',NOW(),NOW()),
(2,'205',2,'available',NOW(),NOW());
-- Private Pool Villa - Floor 0 (ground)
INSERT INTO `rooms` (`room_type_id`, `room_number`, `floor`, `status`, `created_at`, `updated_at`) VALUES
(3,'V1',0,'available',NOW(),NOW()),(3,'V2',0,'available',NOW(),NOW()),
(3,'V3',0,'available',NOW(),NOW()),(3,'V4',0,'available',NOW(),NOW());
-- Family Villa - Floor 0
INSERT INTO `rooms` (`room_type_id`, `room_number`, `floor`, `status`, `created_at`, `updated_at`) VALUES
(4,'F1',0,'available',NOW(),NOW()),(4,'F2',0,'available',NOW(),NOW()),
(4,'F3',0,'available',NOW(),NOW());

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- Selesai! Database the_east_hotel siap digunakan.
-- Login admin: admin@theeasthotel.test / password123
-- Login staf : staf@theeasthotel.test / password123
-- ============================================================
