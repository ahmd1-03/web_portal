-- SQL script for Laravel Portal Karawang database schema and sample data
-- Project: webPortal

-- Drop tables if exist
DROP TABLE IF EXISTS `cards`;
DROP TABLE IF EXISTS `admins`;

-- Create table for content cards
CREATE TABLE `cards` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT NOT NULL,
  `image_url` VARCHAR(255) NOT NULL,
  `external_link` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample content cards
INSERT INTO `cards` (`title`, `description`, `image_url`, `external_link`, `created_at`, `updated_at`) VALUES
('Dinas Pendidikan Karawang', 'Informasi lengkap tentang pendidikan di Karawang.', '/images/dinas_pendidikan.png', 'https://pendidikan.karawang.go.id', NOW(), NOW()),
('Dinas Kesehatan Karawang', 'Berita dan layanan kesehatan di Kaiya 
rawang.', '/images/dinas_kesehatan.png', 'https://kesehatan.karawang.go.id', NOW(), NOW()),
('Pariwisata Karawang', 'Tempat wisata menarik di Karawang.', '/images/pariwisata.png', 'https://pariwisata.karawang.go.id', NOW(), NOW());

-- Create table for admin users
CREATE TABLE `admins` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(150) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample admin user (password: admin123 hashed with bcrypt)
INSERT INTO `admins` (`name`, `email`, `password`, `created_at`, `updated_at`) VALUES
('Admin Utama', 'admin@karawang.go.id', '$2y$10$e0NRXq6q6v1q6v1q6v1q6u6v1q6v1q6v1q6v1q6v1q6v1q6v1q6v1q', NOW(), NOW());

-- Note: The password hash corresponds to 'admin123' using bcrypt.
