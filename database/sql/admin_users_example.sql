-- SQL script to create admin table and insert example admin user

CREATE TABLE IF NOT EXISTS `admins` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL UNIQUE,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert example admin user
-- Password: Admin@123 (hashed with bcrypt)
INSERT INTO `admins` (`name`, `email`, `password`, `created_at`, `updated_at`) VALUES
('Admin User', 'admin@example.com', '$2y$10$e0NRzQ6v6q6Q6Q6Q6Q6Q6O6Q6Q6Q6Q6Q6Q6Q6Q6Q6Q6Q6Q6Q6Q6Q6', NOW(), NOW());
