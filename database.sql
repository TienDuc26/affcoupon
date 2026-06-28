-- Create database if not exists
CREATE DATABASE IF NOT EXISTS `AffCouponDb` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `AffCouponDb`;

-- 1. Roles table
CREATE TABLE IF NOT EXISTS `roles` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(50) NOT NULL UNIQUE,
    `description` VARCHAR(255) NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Users table
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(100) NOT NULL UNIQUE,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `password_hash` VARCHAR(500) NOT NULL,
    `fullname` VARCHAR(255) NULL,
    `role_id` INT NOT NULL DEFAULT 2,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_users_roles` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Categories table
CREATE TABLE IF NOT EXISTS `categories` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(150) NOT NULL,
    `slug` VARCHAR(150) NOT NULL UNIQUE,
    `description` VARCHAR(500) NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Coupons (Stores) table
CREATE TABLE IF NOT EXISTS `coupons` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL UNIQUE,
    `description` VARCHAR(1000) NULL,
    `content` TEXT NULL,
    `thumbnail_url` VARCHAR(500) NULL,
    `affiliate_url` VARCHAR(2000) NOT NULL,
    `category_id` INT NOT NULL,
    `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `view_count` INT NOT NULL DEFAULT 0,
    `created_by` INT NOT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_coupons_categories` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE RESTRICT,
    CONSTRAINT `fk_coupons_users` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Vouchers (Offers) table
CREATE TABLE IF NOT EXISTS `vouchers` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `coupon_id` INT NOT NULL,
    `voucher_code` VARCHAR(100) NOT NULL,
    `discount_text` VARCHAR(100) NULL,
    `description` VARCHAR(500) NULL,
    `start_date` DATETIME NULL,
    `expired_date` DATETIME NULL,
    `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `sort_order` INT NOT NULL DEFAULT 0,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_vouchers_coupons` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. CouponFAQs table
CREATE TABLE IF NOT EXISTS `coupon_faqs` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `coupon_id` INT NOT NULL,
    `question` TEXT NOT NULL,
    `answer` TEXT NOT NULL,
    CONSTRAINT `fk_coupon_faqs_coupons` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. CouponClicks table
CREATE TABLE IF NOT EXISTS `coupon_clicks` (
    `id` BIGINT AUTO_INCREMENT PRIMARY KEY,
    `voucher_id` INT NOT NULL,
    `ip_address` VARCHAR(50) NULL,
    `user_agent` VARCHAR(1000) NULL,
    `referer` VARCHAR(500) NULL,
    `clicked_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_coupon_clicks_vouchers` FOREIGN KEY (`voucher_id`) REFERENCES `vouchers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed initial data
INSERT INTO `roles` (`id`, `name`, `description`) VALUES 
(1, 'Admin', 'Quản trị viên hệ thống'),
(2, 'User', 'Người dùng thông thường')
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);

-- Insert default admin user: username=admin, password=admin123 (SHA-256 base64 is: JPyJGHcCwo4gI12anLJSh1FagcppiUz3uNTpjw/oVNs=)
INSERT INTO `users` (`id`, `username`, `email`, `password_hash`, `fullname`, `role_id`, `is_active`) VALUES
(1, 'admin', 'admin@gmail.com', 'JAvlGPq9JyTdtvBO6x2llnRI1+gxwIyPqCKAn3THIKk=', 'Administrator', 1, 1)
ON DUPLICATE KEY UPDATE `username` = VALUES(`username`);

-- Seed categories
INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `is_active`) VALUES
(1, 'Thời Trang', 'thoi-trang', 'Mã giảm giá và ưu đãi ngành hàng quần áo, giày dép, phụ kiện.', 1),
(2, 'Công Nghệ', 'cong-nghe', 'Khuyến mãi máy tính, điện thoại, phụ kiện công nghệ cực hot.', 1),
(3, 'Ẩm Thực & Du Lịch', 'am-thuc-du-lich', 'Mã giảm giá đặt đồ ăn GrabFood, ShopeeFood, vé máy bay, phòng khách sạn.', 1),
(4, 'Sách & Giáo Dục', 'sach-giao-duc', 'Ưu đãi sách quốc văn, ngoại văn, văn phòng phẩm Fahasa, Tiki.', 1)
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`);

-- Seed stores (coupons)
INSERT INTO `coupons` (`id`, `title`, `slug`, `description`, `content`, `thumbnail_url`, `affiliate_url`, `category_id`, `is_featured`, `is_active`, `view_count`, `created_by`) VALUES
(1, 'Shopee', 'shopee', 'Mã giảm giá Shopee mới nhất, mã hoàn xu, mã freeship toàn quốc cực hot.', 'Giới thiệu về Shopee: Shopee là sàn giao dịch thương mại điện tử hàng đầu tại Việt Nam và Đông Nam Á. Tại đây, bạn có thể tìm thấy mọi mặt hàng từ thời trang, mỹ phẩm, đồ gia dụng đến thiết bị điện tử với mức giá siêu hời cùng hàng ngàn deal sốc mỗi ngày.', '/img/img-01.jpg', 'https://shopee.vn', 1, 1, 1, 1420, 1),
(2, 'Lazada', 'lazada', 'Tổng hợp mã giảm giá Lazada cực hot, mã tích lũy, freeship max và deal 1k.', 'Giới thiệu về Lazada: Lazada Việt Nam là một trong những sàn thương mại điện tử tiên phong, cung cấp trải nghiệm mua sắm nhanh chóng, an toàn và thuận tiện cùng các chương trình lễ hội mua sắm hoành tráng hàng tháng.', '/img/img-02.jpg', 'https://lazada.vn', 1, 1, 1, 985, 1),
(3, 'Tiki', 'tiki', 'Mã giảm giá Tiki mới nhất, voucher TikiCARD, TikiPRO giao nhanh trong 2h.', 'Giới thiệu về Tiki: Tiki là sàn thương mại điện tử uy tín hàng đầu Việt Nam, nổi tiếng với dịch vụ TikiNOW giao nhanh 2h, chính sách hàng chính hãng 100% cùng dịch vụ khách hàng tận tâm.', '/img/img-03.jpg', 'https://tiki.vn', 2, 0, 1, 540, 1),
(4, 'Grab', 'grab', 'Mã giảm giá GrabCar, GrabBike, voucher GrabFood siêu hot giảm tới 50k.', 'Giới thiệu về Grab: Grab là siêu ứng dụng đa dịch vụ hàng đầu Đông Nam Á, cung cấp các dịch vụ di chuyển tiện lợi, đặt đồ ăn giao nhanh, đi chợ hộ và dịch vụ giao hàng nhanh chóng.', '/img/img-04.jpg', 'https://grab.com/vn', 3, 1, 1, 1105, 1),
(5, 'Fahasa', 'fahasa', 'Mã giảm giá sách Fahasa mới nhất, voucher giảm 10%, 20% cho mọi đơn hàng.', 'Giới thiệu về Fahasa: Fahasa là hệ thống nhà sách hàng đầu Việt Nam, cung cấp hàng ngàn đầu sách quốc văn, ngoại văn chính hãng cùng văn phòng phẩm đa dạng với chất lượng tốt nhất.', '/img/img-05.jpg', 'https://fahasa.com', 4, 0, 1, 320, 1)
ON DUPLICATE KEY UPDATE `title`=VALUES(`title`);

-- Seed vouchers (offers)
INSERT INTO `vouchers` (`id`, `coupon_id`, `voucher_code`, `discount_text`, `description`, `start_date`, `expired_date`, `is_featured`, `is_active`, `sort_order`) VALUES
(1, 1, 'SPFREE50', 'Giảm 50k cho đơn từ 250k', 'Áp dụng cho mọi ngành hàng trên sàn Shopee, ưu tiên thanh toán qua ví ShopeePay.', '2026-05-01 00:00:00', '2026-12-31 23:59:59', 1, 1, 1),
(2, 1, 'SPFS0', 'Miễn phí vận chuyển 0đ', 'Mã miễn phí vận chuyển toàn quốc cho đơn hàng từ 0đ, giảm tối đa 15k.', '2026-05-01 00:00:00', '2026-12-31 23:59:59', 1, 1, 2),
(3, 2, 'LZD100K', 'Giảm 100k cho đơn từ 500k', 'Áp dụng cho các sản phẩm LazMall chính hãng, không áp dụng cho sữa và bỉm.', '2026-05-01 00:00:00', '2026-12-31 23:59:59', 1, 1, 1),
(4, 3, 'TIKINEW', 'Giảm 30k cho khách hàng mới', 'Ưu đãi dành riêng cho tài khoản mua hàng lần đầu tiên trên Tiki, áp dụng mọi ngành hàng.', '2026-05-01 00:00:00', '2026-12-31 23:59:59', 0, 1, 1),
(5, 4, 'GRABFOOD50', 'Giảm 50% tối đa 40k', 'Áp dụng cho dịch vụ đặt đồ ăn GrabFood, không giới hạn cửa hàng đối tác.', '2026-05-01 00:00:00', '2026-12-31 23:59:59', 1, 1, 1),
(6, 4, 'GRABCAR30', 'Giảm 30k đặt chuyến GrabCar', 'Mã ưu đãi giảm 30k khi đặt xe GrabCar tại Hà Nội và TP. Hồ Chí Minh.', '2026-05-01 00:00:00', '2026-12-31 23:59:59', 0, 1, 2),
(7, 5, 'FAHASA20', 'Giảm 20k đơn từ 150k', 'Áp dụng cho toàn bộ các đầu sách văn học quốc văn tại nhà sách online Fahasa.', '2026-05-01 00:00:00', '2026-12-31 23:59:59', 0, 1, 1)
ON DUPLICATE KEY UPDATE `voucher_code`=VALUES(`voucher_code`);

-- Seed FAQs
INSERT INTO `coupon_faqs` (`id`, `coupon_id`, `question`, `answer`) VALUES
(1, 1, 'Làm thế nào để áp dụng mã giảm giá Shopee?', 'Bạn chỉ cần copy mã giảm giá tại trang này, truy cập Shopee, chọn sản phẩm vào giỏ hàng, sau đó tiến hành thanh toán và dán mã vào ô "Shopee Voucher" trước khi bấm Đặt hàng.'),
(2, 1, 'Mã hoàn xu Shopee hoạt động như thế nào?', 'Sau khi đơn hàng hoàn thành thành công, số lượng Shopee Xu tương ứng sẽ được cộng trực tiếp vào tài khoản Shopee của bạn. Bạn có thể sử dụng Xu này để giảm trừ tiền cho các đơn hàng tiếp theo.'),
(3, 2, 'Lazada Freeship Max là gì?', 'Đây là chương trình hỗ trợ phí vận chuyển đặc biệt từ Lazada. Các sản phẩm có gắn logo Freeship Max sẽ được giảm phí vận chuyển lên tới 50k tùy thuộc vào giá trị đơn hàng.'),
(4, 4, 'Tôi có thể dùng nhiều mã giảm giá Grab trên cùng một chuyến đi không?', 'Không, ứng dụng Grab chỉ cho phép áp dụng tối đa một mã ưu đãi (promo code) trên một chuyến xe hoặc một đơn hàng GrabFood.')
ON DUPLICATE KEY UPDATE `question`=VALUES(`question`);
