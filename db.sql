-- Database schema for test project
CREATE TABLE IF NOT EXISTS `posts` (
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`title` VARCHAR(255) NOT NULL,
	`content` TEXT NOT NULL,
	`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `comments` (
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`post_id` INT UNSIGNED NOT NULL,
	`name` VARCHAR(250) NOT NULL,
	`email` VARCHAR(250) DEFAULT NULL,
	`content` TEXT NOT NULL,
	`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`)
) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


INSERT INTO `posts` (`title`, `content`, `created_at`) VALUES
('Article One', 'Lorem ipusm dolor one', CURRENT_TIMESTAMP),
('Article Two', 'Lorem ipsum dolor two', CURRENT_TIMESTAMP),
('Article Three', 'Lorem ipsum dolor three', CURRENT_TIMESTAMP);

CREATE TABLE IF NOT EXISTS `users` (
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`username` VARCHAR(255) NOT NULL,
	`password` VARCHAR(255) NOT NULL,
	`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;