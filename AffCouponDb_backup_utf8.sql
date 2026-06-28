-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: AffCouponDb
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `slug` varchar(150) NOT NULL,
  `description` varchar(500) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Thời Trang','thoi-trang','Mã giảm giá và ưu đãi ngành hàng quần áo, giày dép, phụ kiện.',1,'2026-05-19 17:38:17'),(2,'Công Nghệ','cong-nghe','Khuyến mãi máy tính, điện thoại, phụ kiện công nghệ cực hot.',1,'2026-05-19 17:38:17'),(3,'Ẩm Thực & Du Lịch','am-thuc-du-lich','Mã giảm giá đặt đồ ăn GrabFood, ShopeeFood, vé máy bay, phòng khách sạn.',1,'2026-05-19 17:38:17'),(4,'Sách & Giáo Dục','sach-giao-duc','Ưu đãi sách quốc văn, ngoại văn, văn phòng phẩm Fahasa, Tiki.',1,'2026-05-19 17:38:17'),(5,'Chó Mèo','cho-meo','abcabcabcabacc',1,'2026-05-20 12:16:10');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `coupon_clicks`
--

DROP TABLE IF EXISTS `coupon_clicks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `coupon_clicks` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `voucher_id` int(11) NOT NULL,
  `ip_address` varchar(50) DEFAULT NULL,
  `user_agent` varchar(1000) DEFAULT NULL,
  `referer` varchar(500) DEFAULT NULL,
  `clicked_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_coupon_clicks_vouchers` (`voucher_id`),
  CONSTRAINT `fk_coupon_clicks_vouchers` FOREIGN KEY (`voucher_id`) REFERENCES `vouchers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `coupon_clicks`
--

LOCK TABLES `coupon_clicks` WRITE;
/*!40000 ALTER TABLE `coupon_clicks` DISABLE KEYS */;
/*!40000 ALTER TABLE `coupon_clicks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `coupon_faqs`
--

DROP TABLE IF EXISTS `coupon_faqs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `coupon_faqs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `coupon_id` int(11) NOT NULL,
  `question` text NOT NULL,
  `answer` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_coupon_faqs_coupons` (`coupon_id`),
  CONSTRAINT `fk_coupon_faqs_coupons` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `coupon_faqs`
--

LOCK TABLES `coupon_faqs` WRITE;
/*!40000 ALTER TABLE `coupon_faqs` DISABLE KEYS */;
INSERT INTO `coupon_faqs` VALUES (4,4,'Tôi có thể dùng nhiều mã giảm giá Grab trên cùng một chuyến đi không?','Không, ứng dụng Grab chỉ cho phép áp dụng tối đa một mã ưu đãi (promo code) trên một chuyến xe hoặc một đơn hàng GrabFood.'),(5,2,'Lazada Freeship Max là gì?','Đây là chương trình hỗ trợ phí vận chuyển đặc biệt từ Lazada. Các sản phẩm có gắn logo Freeship Max sẽ được giảm phí vận chuyển lên tới 50k tùy thuộc vào giá trị đơn hàng.');
/*!40000 ALTER TABLE `coupon_faqs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `coupons`
--

DROP TABLE IF EXISTS `coupons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `coupons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` varchar(1000) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `thumbnail_url` varchar(500) DEFAULT NULL,
  `affiliate_url` varchar(2000) NOT NULL,
  `category_id` int(11) NOT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `view_count` int(11) NOT NULL DEFAULT 0,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `fk_coupons_categories` (`category_id`),
  KEY `fk_coupons_users` (`created_by`),
  CONSTRAINT `fk_coupons_categories` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  CONSTRAINT `fk_coupons_users` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `coupons`
--

LOCK TABLES `coupons` WRITE;
/*!40000 ALTER TABLE `coupons` DISABLE KEYS */;
INSERT INTO `coupons` VALUES (2,'shoppe','shoppe','Tổng hợp mã giảm giá Lazada cực hot, mã tích lũy, freeship max và deal 1k.','Giới thiệu về Lazada: Lazada Việt Nam là một trong những sàn thương mại điện tử tiên phong, cung cấp trải nghiệm mua sắm nhanh chóng, an toàn và thuận tiện cùng các chương trình lễ hội mua sắm hoành tráng hàng tháng.','/img/img-02.jpg','https://lazada.vn',1,0,0,986,1,'2026-05-19 17:38:17','2026-05-20 12:10:12'),(3,'Tiki','tiki','Mã giảm giá Tiki mới nhất, voucher TikiCARD, TikiPRO giao nhanh trong 2h.','Giới thiệu về Tiki: Tiki là sàn thương mại điện tử uy tín hàng đầu Việt Nam, nổi tiếng với dịch vụ TikiNOW giao nhanh 2h, chính sách hàng chính hãng 100% cùng dịch vụ khách hàng tận tâm.','/img/img-03.jpg','https://tiki.vn',2,0,1,543,1,'2026-05-19 17:38:17','2026-05-20 12:27:27'),(4,'Grab','grab','Mã giảm giá GrabCar, GrabBike, voucher GrabFood siêu hot giảm tới 50k.','Giới thiệu về Grab: Grab là siêu ứng dụng đa dịch vụ hàng đầu Đông Nam Á, cung cấp các dịch vụ di chuyển tiện lợi, đặt đồ ăn giao nhanh, đi chợ hộ và dịch vụ giao hàng nhanh chóng.','/img/img-04.jpg','https://grab.com/vn',3,1,1,1106,1,'2026-05-19 17:38:17','2026-05-20 12:24:17'),(5,'Fahasa','fahasa','Mã giảm giá sách Fahasa mới nhất, voucher giảm 10%, 20% cho mọi đơn hàng.','Giới thiệu về Fahasa: Fahasa là hệ thống nhà sách hàng đầu Việt Nam, cung cấp hàng ngàn đầu sách quốc văn, ngoại văn chính hãng cùng văn phòng phẩm đa dạng với chất lượng tốt nhất.','/img/img-05.jpg','https://fahasa.com',4,0,1,321,1,'2026-05-19 17:38:17','2026-05-20 12:24:20');
/*!40000 ALTER TABLE `coupons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'Admin','Quản trị viên hệ thống'),(2,'User','Người dùng thông thường');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(500) NOT NULL,
  `fullname` varchar(255) DEFAULT NULL,
  `role_id` int(11) NOT NULL DEFAULT 2,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `fk_users_roles` (`role_id`),
  CONSTRAINT `fk_users_roles` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','admin@gmail.com','JAvlGPq9JyTdtvBO6x2llnRI1+gxwIyPqCKAn3THIKk=','Administrator',1,1,'2026-05-19 17:36:27'),(2,'duc','duc@gmail.com','pmWkWSBCL51Bfkhn79xPuKBKHz//H6B+mY6G9/eieuM=','tienduc',2,1,'2026-05-20 12:10:44');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vouchers`
--

DROP TABLE IF EXISTS `vouchers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vouchers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `coupon_id` int(11) NOT NULL,
  `voucher_code` varchar(100) NOT NULL,
  `discount_text` varchar(100) DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL,
  `start_date` datetime DEFAULT NULL,
  `expired_date` datetime DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_vouchers_coupons` (`coupon_id`),
  CONSTRAINT `fk_vouchers_coupons` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vouchers`
--

LOCK TABLES `vouchers` WRITE;
/*!40000 ALTER TABLE `vouchers` DISABLE KEYS */;
INSERT INTO `vouchers` VALUES (3,2,'LZD100K','Giảm 100k cho đơn từ 500k','Áp dụng cho các sản phẩm LazMall chính hãng, không áp dụng cho sữa và bỉm.','2026-05-01 00:00:00','2026-12-31 23:59:59',1,1,1,'2026-05-19 17:38:17'),(4,3,'TIKINEW','Giảm 30k cho khách hàng mới','Ưu đãi dành riêng cho tài khoản mua hàng lần đầu tiên trên Tiki, áp dụng mọi ngành hàng.','2026-05-01 00:00:00','2026-12-31 23:59:59',0,1,1,'2026-05-19 17:38:17'),(5,4,'GRABFOOD50','Giảm 50% tối đa 40k','Áp dụng cho dịch vụ đặt đồ ăn GrabFood, không giới hạn cửa hàng đối tác.','2026-05-01 00:00:00','2026-12-31 23:59:59',1,1,1,'2026-05-19 17:38:17'),(6,4,'GRABCAR30','Giảm 30k đặt chuyến GrabCar','Mã ưu đãi giảm 30k khi đặt xe GrabCar tại Hà Nội và TP. Hồ Chí Minh.','2026-05-01 00:00:00','2026-12-31 23:59:59',0,1,2,'2026-05-19 17:38:17'),(7,5,'FAHASA20','Giảm 20k đơn từ 150k','Áp dụng cho toàn bộ các đầu sách văn học quốc văn tại nhà sách online Fahasa.','2026-05-01 00:00:00','2026-12-31 23:59:59',0,1,1,'2026-05-19 17:38:17');
/*!40000 ALTER TABLE `vouchers` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-05-20 13:56:44
