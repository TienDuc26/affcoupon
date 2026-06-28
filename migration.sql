-- 1. Update categories table
ALTER TABLE `categories` 
ADD COLUMN `type` VARCHAR(50) NOT NULL DEFAULT 'coupon',
ADD COLUMN `parent_id` INT NULL DEFAULT NULL,
ADD CONSTRAINT `fk_categories_parent` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

-- 2. Update users table
ALTER TABLE `users`
ADD COLUMN `avatar` VARCHAR(500) NULL DEFAULT NULL,
ADD COLUMN `bio` TEXT NULL DEFAULT NULL;

-- 3. Create media table
CREATE TABLE IF NOT EXISTS `media` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `url` VARCHAR(500) NOT NULL,
    `alt` VARCHAR(255) NULL,
    `caption` VARCHAR(255) NULL,
    `type` VARCHAR(50) NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Create posts table
CREATE TABLE IF NOT EXISTS `posts` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL UNIQUE,
    `summary` VARCHAR(1000) NULL,
    `content` LONGTEXT NULL,
    `thumbnail` VARCHAR(500) NULL,
    `status` VARCHAR(50) NOT NULL DEFAULT 'draft',
    `published_at` DATETIME NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
    `author_id` INT NOT NULL,
    `category_id` INT NULL,
    `view_count` INT NOT NULL DEFAULT 0,
    `reading_time` VARCHAR(50) NULL,
    `seo_title` VARCHAR(255) NULL,
    `seo_description` VARCHAR(500) NULL,
    `canonical_url` VARCHAR(500) NULL,
    CONSTRAINT `fk_posts_users` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_posts_categories` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Create tags table
CREATE TABLE IF NOT EXISTS `tags` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `slug` VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Create post_tags table (N-N relation)
CREATE TABLE IF NOT EXISTS `post_tags` (
    `post_id` INT NOT NULL,
    `tag_id` INT NOT NULL,
    PRIMARY KEY (`post_id`, `tag_id`),
    CONSTRAINT `fk_post_tags_posts` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_post_tags_tags` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
