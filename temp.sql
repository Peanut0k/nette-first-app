INSERT INTO `users` (`username`, `password`, `role_id`) VALUES
('admin', '$2y$10$EIXDVfDyxKGNPwHXxoGjReBj5jqD8fEjkWWdcUVQfV2pVfjzJ9u9K', 4)
ON DUPLICATE KEY UPDATE `role_id` = 4;