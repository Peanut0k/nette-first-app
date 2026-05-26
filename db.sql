CREATE TABLE IF NOT EXISTS `roles` (
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`name` VARCHAR(50) NOT NULL UNIQUE,
	`description` TEXT,
	`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `roles` (`name`, `description`) VALUES
('guest', 'Can view posts and comments'),
('user', 'Can comment on posts and edit own comments'),
('author', 'Can create and manage own posts, plus all User permissions'),
('admin', 'Can manage all content');

CREATE TABLE IF NOT EXISTS `users` (
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`username` VARCHAR(255) NOT NULL UNIQUE,
	`password` VARCHAR(255) NOT NULL,
	`role_id` INT UNSIGNED NOT NULL DEFAULT 1,
	`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `posts` (
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`user_id` INT UNSIGNED,
	`title` VARCHAR(255) NOT NULL,
	`content` TEXT NOT NULL,
	`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `posts` (`user_id`, `title`, `content`, `created_at`) VALUES
(1, 'Article One', 'Lorem ipsum dolor one', CURRENT_TIMESTAMP),
(1, 'Article Two', 'Lorem ipsum dolor two', CURRENT_TIMESTAMP),
(1, 'Article Three', 'Lorem ipsum dolor three', CURRENT_TIMESTAMP);

CREATE TABLE IF NOT EXISTS `comments` (
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`post_id` INT UNSIGNED NOT NULL,
	`user_id` INT UNSIGNED,
	`name` VARCHAR(250) NOT NULL,
	`email` VARCHAR(250) DEFAULT NULL,
	`content` TEXT NOT NULL,
	`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
	FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
