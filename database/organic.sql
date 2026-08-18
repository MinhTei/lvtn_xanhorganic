-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1:3306
-- Thời gian đã tạo: Th8 18, 2026 lúc 02:35 AM
-- Phiên bản máy phục vụ: 9.1.0
-- Phiên bản PHP: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `organic`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `carts`
--

DROP TABLE IF EXISTS `carts`;
CREATE TABLE IF NOT EXISTS `carts` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `carts_user_id_foreign` (`user_id`),
  KEY `carts_product_id_foreign` (`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=216 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `carts`
--

INSERT INTO `carts` (`id`, `user_id`, `product_id`, `quantity`, `created_at`, `updated_at`) VALUES
(148, 7, 10, 1, '2026-08-08 15:26:58', '2026-08-08 15:26:58'),
(147, 7, 55, 3, '2026-08-08 15:26:58', '2026-08-08 15:26:58');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `parent_id` bigint UNSIGNED DEFAULT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `shelf_days` smallint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`),
  UNIQUE KEY `categories_name_unique` (`name`),
  KEY `categories_parent_id_foreign` (`parent_id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `categories`
--

INSERT INTO `categories` (`id`, `parent_id`, `slug`, `name`, `is_active`, `shelf_days`, `created_at`, `updated_at`) VALUES
(1, NULL, 'trai-cay', 'Trái cây', 1, NULL, '2026-07-08 03:43:50', '2026-07-08 03:43:50'),
(2, NULL, 'thit-hai-san', 'Thịt Hải sản', 1, NULL, '2026-07-08 03:43:50', '2026-07-08 03:43:50'),
(3, NULL, 'bo-trung-sua', 'Bơ trứng sữa', 1, NULL, '2026-07-08 03:43:50', '2026-07-29 07:35:43'),
(4, NULL, 'rau-cu', 'Rau củ', 1, NULL, '2026-07-08 03:43:50', '2026-07-31 10:50:23'),
(6, 1, 'trai-cay-nhap-khau', 'Trái cây nhập khẩu', 1, 30, '2026-07-08 03:43:50', '2026-07-08 03:43:50'),
(7, 1, 'nuoc-ep', 'Nước ép', 1, 30, '2026-07-08 03:43:50', '2026-07-08 03:43:50'),
(8, 2, 'thit-bo', 'Thịt bò', 1, 4, '2026-07-08 03:43:50', '2026-07-08 03:43:50'),
(9, 2, 'thit-heo', 'Thịt heo', 1, 3, '2026-07-08 03:43:50', '2026-07-08 03:43:50'),
(10, 2, 'thit-ga', 'Thịt gà', 1, 2, '2026-07-08 03:43:50', '2026-07-08 03:43:50'),
(11, 2, 'ca-tuoi', 'Cá tươi', 1, 2, '2026-07-08 03:43:50', '2026-07-08 03:43:50'),
(12, 2, 'hai-san-cac-loai', 'Hải sản các loại', 1, 2, '2026-07-08 03:43:50', '2026-07-08 03:43:50'),
(13, 3, 'trung-ga', 'Trứng gà', 1, 28, '2026-07-08 03:43:50', '2026-07-08 03:43:50'),
(14, 3, 'trung-vit', 'Trứng vịt', 1, 28, '2026-07-08 03:43:50', '2026-07-08 03:43:50'),
(15, 3, 'sua-cac-loai', 'Sữa các loại ', 1, 14, '2026-07-08 03:43:50', '2026-07-08 03:43:50'),
(16, 3, 'bo-cac-loai', 'Bơ các loại', 1, 60, '2026-07-08 03:43:50', '2026-07-08 03:43:50'),
(17, 4, 'rau-an-la', 'Rau ', 1, 5, '2026-07-08 03:43:50', '2026-07-08 03:43:50'),
(18, 4, 'cu-qua', 'Củ quả', 1, 30, '2026-07-08 03:43:50', '2026-07-08 03:43:50'),
(19, 4, 'nam-cac-loai', 'Nấm các loại', 1, 5, '2026-07-08 03:43:50', '2026-07-08 03:43:50'),
(23, NULL, 'gia-vi', 'Gia vị', 1, 730, '2026-08-03 07:28:52', '2026-08-03 07:28:52');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `contacts`
--

DROP TABLE IF EXISTS `contacts`;
CREATE TABLE IF NOT EXISTS `contacts` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_replied` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `contacts`
--

INSERT INTO `contacts` (`id`, `name`, `email`, `phone`, `message`, `is_replied`, `created_at`, `updated_at`) VALUES
(1, 'Minh Tài buiminhtai', 'buiminhtai97@gmail.com', '0966330634', '12343', 1, '2026-07-14 18:23:07', '2026-07-15 12:55:49');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `coupons`
--

DROP TABLE IF EXISTS `coupons`;
CREATE TABLE IF NOT EXISTS `coupons` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` bigint UNSIGNED NOT NULL,
  `discount_type` enum('percentage','fixed') COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount_value` decimal(10,2) NOT NULL,
  `min_order_value` decimal(10,2) NOT NULL DEFAULT '0.00',
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `usage_limit` int DEFAULT NULL,
  `usage_limit_per_user` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `coupons_code_unique` (`code`),
  KEY `coupons_created_by_foreign` (`created_by`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `coupons`
--

INSERT INTO `coupons` (`id`, `code`, `created_by`, `discount_type`, `discount_value`, `min_order_value`, `start_date`, `end_date`, `usage_limit`, `usage_limit_per_user`, `created_at`, `updated_at`) VALUES
(1, 'WELCOME10', 1, 'percentage', 10.00, 0.00, '2026-08-09', '2026-08-18', 10, 10, '2026-07-15 12:56:27', '2026-08-17 07:42:46'),
(5, 'MONDAY', 1, 'fixed', 50000.00, 0.00, '2026-08-15', '2026-08-20', NULL, 2, '2026-08-17 07:43:32', '2026-08-17 08:10:13');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `coupon_usages`
--

DROP TABLE IF EXISTS `coupon_usages`;
CREATE TABLE IF NOT EXISTS `coupon_usages` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `coupon_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `discount_amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `coupon_usages_coupon_id_foreign` (`coupon_id`),
  KEY `coupon_usages_user_id_foreign` (`user_id`),
  KEY `coupon_usages_order_id_foreign` (`order_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `coupon_usages`
--

INSERT INTO `coupon_usages` (`id`, `coupon_id`, `user_id`, `order_id`, `discount_amount`, `created_at`, `updated_at`) VALUES
(1, 1, 3, 10, 289500.00, '2026-07-15 12:59:10', '2026-07-15 12:59:10'),
(2, 1, 1, 23, 10500.00, '2026-07-26 14:09:28', '2026-07-26 14:09:28'),
(3, 1, 1, 24, 10000.00, '2026-07-26 15:01:29', '2026-07-26 15:01:29'),
(4, 1, 14, 45, 3500.00, '2026-08-17 07:46:09', '2026-08-17 07:46:09'),
(5, 5, 1, 46, 35000.00, '2026-08-17 08:03:42', '2026-08-17 08:03:42'),
(6, 5, 1, 48, 50000.00, '2026-08-17 08:08:35', '2026-08-17 08:08:35'),
(7, 1, 1, 50, 26300.00, '2026-08-17 08:15:20', '2026-08-17 08:15:20');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=72 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(41, '2026_06_22_073915_create_roles_table', 1),
(42, '2026_06_22_074022_create_role_permistions_table', 1),
(43, '2026_06_22_074029_create_permistions_table', 1),
(44, '2026_06_22_074059_create_users_table', 1),
(45, '2026_06_22_074131_create_categories_table', 1),
(46, '2026_06_22_074140_create_products_table', 1),
(47, '2026_06_22_074152_create_product_images_table', 1),
(48, '2026_06_22_074229_create_user_addresses_table', 1),
(49, '2026_06_22_074307_create_order_items_table', 1),
(50, '2026_06_22_074338_create_orders_table', 1),
(51, '2026_06_22_074346_create_order_payments_table', 1),
(52, '2026_06_22_074405_create_order_status_logs_table', 1),
(53, '2026_06_22_074437_create_product_reviews_table', 1),
(54, '2026_06_22_074451_create_wishlists_table', 1),
(55, '2026_06_22_074458_create_coupons_table', 1),
(56, '2026_06_22_074513_create_coupon_usages_table', 1),
(57, '2026_06_22_074521_create_notifications_table', 1),
(58, '2026_06_22_074547_create_password_reset_tokens_table', 1),
(59, '2026_06_22_074626_create_carts_table', 1),
(60, '2026_06_22_094700_create_contacts_table', 1),
(61, '2026_07_15_000001_add_manage_reviews_permission', 2),
(62, '2026_07_15_010000_add_delivery_and_coupon_fields', 3),
(63, '2026_07_15_020000_alter_order_payments_payment_method', 4),
(64, '2026_07_26_214755_add_usage_limits_to_coupons_table', 5),
(65, '2026_07_31_155423_add_shelf_days_to_categories_table', 6),
(66, '2026_07_31_155520_add_expiry_to_products_table', 7),
(67, '2026_08_01_160859_change_products_collation_to_vietnamese', 8),
(68, '2026_08_03_094403_create_recipes_table', 9),
(69, '2026_08_03_094438_create_product_recipe_table', 9),
(70, '2026_08_03_142758_change_shelf_days_column_in_categories_table', 10),
(71, '2026_08_05_150711_add_shipping_email_to_orders_table', 11);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `notifications`
--

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `link` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_user_id_foreign` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `order_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `discount_amount` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `coupon_code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_fee` decimal(10,2) NOT NULL,
  `shipping_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'standard',
  `delivery_slot` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `shipping_phone` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `shipping_email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_address` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `note` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_order_code_unique` (`order_code`),
  KEY `orders_user_id_foreign` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=55 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `order_code`, `subtotal`, `discount_amount`, `coupon_code`, `shipping_fee`, `shipping_type`, `delivery_slot`, `shipping_name`, `shipping_phone`, `shipping_email`, `shipping_address`, `total_amount`, `status`, `note`, `created_at`, `updated_at`) VALUES
(51, 14, 'OR1708264809', 348000.00, '0', NULL, 25000.00, 'standard', '2026-08-17|19-20', 'Customer', '0966330655', 'customer@gmail.com', '65/13A444444444444, Phường 12, Quận Gò Vấp, Thành phố Hồ Chí Minh', 373000.00, 'delivered', NULL, '2026-08-17 08:23:03', '2026-08-17 08:26:03'),
(50, 1, 'OR1708265574', 263000.00, '26300', 'WELCOME10', 25000.00, 'standard', '2026-08-17|17-18', 'MINH CHÂU', '0966330634', 'buiminhtai97@gmail.com', '65/13A,Ấp Dân Thằng 1, Xã Tân Thới Nhì, Hóc Môn, Phường 16, Quận 8, Thành phố Hồ Chí Minh', 261700.00, 'delivered', 'GIAO NGYA LẬP TỨC! ĐÓI LẮM RỒI', '2026-08-17 08:15:20', '2026-08-17 08:18:52'),
(49, 1, 'OR1708265632', 45000.00, '0', NULL, 25000.00, 'standard', '2026-08-18|15-16', 'Minh Tài', '0966330634', 'buiminhtai97@gmail.com', '65/13A,Ấp Dân Thằng 1, Xã Tân Thới Nhì, Hóc Môn, Phường Võ Thị Sáu, Quận 3, Thành phố Hồ Chí Minh', 70000.00, 'pending', 'GIAO GIAO GIAO', '2026-08-17 08:12:21', '2026-08-17 08:12:21'),
(48, 1, 'OR1708261267', 70000.00, '50000', 'MONDAY', 25000.00, 'standard', '2026-08-17|16-17', 'AdminUser', '0966330634', 'admin@gmail.com', '65/13A, Phường 4, Quận 5, Thành phố Hồ Chí Minh', 45000.00, 'pending', 'Giao nhanh ngya lập tức', '2026-08-17 08:08:35', '2026-08-17 08:08:35'),
(47, 1, 'OR1708267149', 70000.00, '0', NULL, 25000.00, 'standard', '2026-08-18|15-16', 'Minh Tài', '0966330634', 'buiminhtai97@gmail.com', '65/13A,Ấp Dân Thằng 1, Xã Tân Thới Nhì, Hóc Môn, Phường 7, Quận 5, Thành phố Hồ Chí Minh', 95000.00, 'cancelled', 'Giao nhanh cho tôi', '2026-08-17 08:07:40', '2026-08-17 08:07:48');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_items`
--

DROP TABLE IF EXISTS `order_items`;
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `product_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_image` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_foreign` (`order_id`),
  KEY `order_items_product_id_foreign` (`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=164 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `product_image`, `quantity`, `unit_price`, `created_at`, `updated_at`) VALUES
(163, 54, 134, 'Nước dừa tươi', 'http://127.0.0.1:8000/assets/clients/img/product/nuoc-dua-tuoi.jpg', 1, 25000.00, '2026-08-17 14:46:08', '2026-08-17 14:46:08'),
(162, 54, 132, 'Nước màu dừa', 'http://127.0.0.1:8000/assets/clients/img/product/nuoc-mau-dua.jpg', 1, 30000.00, '2026-08-17 14:46:08', '2026-08-17 14:46:08'),
(161, 53, 131, 'Me vắt', 'http://127.0.0.1:8000/assets/clients/img/product/me-vat.jpg', 1, 25000.00, '2026-08-17 14:42:31', '2026-08-17 14:42:31'),
(160, 53, 129, 'Hành tây', 'http://127.0.0.1:8000/assets/clients/img/product/hanh-tay.jpg', 1, 20000.00, '2026-08-17 14:42:31', '2026-08-17 14:42:31'),
(159, 52, 132, 'Nước màu dừa', 'http://127.0.0.1:8000/assets/clients/img/product/nuoc-mau-dua.jpg', 1, 30000.00, '2026-08-17 14:38:35', '2026-08-17 14:38:35'),
(158, 52, 133, 'Sốt mè rang trộn salad', 'http://127.0.0.1:8000/assets/clients/img/product/sot-me-rang-tron-salad.jpg', 1, 45000.00, '2026-08-17 14:38:35', '2026-08-17 14:38:35'),
(157, 52, 134, 'Nước dừa tươi', 'http://127.0.0.1:8000/assets/clients/img/product/nuoc-dua-tuoi.jpg', 1, 25000.00, '2026-08-17 14:38:35', '2026-08-17 14:38:35'),
(156, 52, 79, 'Cà chua bi', 'http://127.0.0.1:8000/assets/clients/img/product/ca-chua-bi.jpg', 2, 35000.00, '2026-08-17 14:38:35', '2026-08-17 14:38:35'),
(155, 51, 29, 'Cá basa phi lê', 'http://127.0.0.1:8000/assets/clients/img/product/ca-basa-phi-le.jpg', 1, 90000.00, '2026-08-17 08:23:03', '2026-08-17 08:23:03'),
(154, 51, 119, 'Tiêu đen xay', 'http://127.0.0.1:8000/assets/clients/img/product/tieu-den-xay.jpg', 1, 35000.00, '2026-08-17 08:23:03', '2026-08-17 08:23:03'),
(153, 51, 68, 'Trứng vịt muối', 'http://127.0.0.1:8000/assets/clients/img/product/trung-vit-muoi.jpg', 1, 55000.00, '2026-08-17 08:23:03', '2026-08-17 08:23:03'),
(152, 51, 64, 'Phô mai lát Cheddar', 'http://127.0.0.1:8000/assets/clients/img/product/pho-mai-lat-cheddar.jpg', 1, 75000.00, '2026-08-17 08:23:03', '2026-08-17 08:23:03'),
(151, 51, 132, 'Nước màu dừa', 'http://127.0.0.1:8000/assets/clients/img/product/nuoc-mau-dua.jpg', 1, 30000.00, '2026-08-17 08:23:03', '2026-08-17 08:23:03'),
(150, 51, 4, 'Hành lá', 'http://127.0.0.1:8000/assets/clients/img/product/hanh-la.jpg', 1, 8000.00, '2026-08-17 08:23:03', '2026-08-17 08:23:03'),
(149, 51, 127, 'Ớt sừng đỏ', 'http://127.0.0.1:8000/assets/clients/img/product/ot-sung-do.jpg', 1, 55000.00, '2026-08-17 08:23:03', '2026-08-17 08:23:03'),
(148, 50, 128, 'Hành tím', 'http://127.0.0.1:8000/assets/clients/img/product/hanh-tim.jpg', 1, 45000.00, '2026-08-17 08:15:20', '2026-08-17 08:15:20'),
(147, 50, 29, 'Cá basa phi lê', 'http://127.0.0.1:8000/assets/clients/img/product/ca-basa-phi-le.jpg', 1, 90000.00, '2026-08-17 08:15:20', '2026-08-17 08:15:20'),
(146, 50, 119, 'Tiêu đen xay', 'http://127.0.0.1:8000/assets/clients/img/product/tieu-den-xay.jpg', 1, 35000.00, '2026-08-17 08:15:20', '2026-08-17 08:15:20'),
(145, 50, 132, 'Nước màu dừa', 'http://127.0.0.1:8000/assets/clients/img/product/nuoc-mau-dua.jpg', 1, 30000.00, '2026-08-17 08:15:20', '2026-08-17 08:15:20'),
(144, 50, 4, 'Hành lá', 'http://127.0.0.1:8000/assets/clients/img/product/hanh-la.jpg', 1, 8000.00, '2026-08-17 08:15:20', '2026-08-17 08:15:20'),
(143, 50, 127, 'Ớt sừng đỏ', 'http://127.0.0.1:8000/assets/clients/img/product/ot-sung-do.jpg', 1, 55000.00, '2026-08-17 08:15:20', '2026-08-17 08:15:20'),
(142, 49, 131, 'Me vắt', 'http://127.0.0.1:8000/assets/clients/img/product/me-vat.jpg', 1, 25000.00, '2026-08-17 08:12:21', '2026-08-17 08:12:21'),
(141, 49, 129, 'Hành tây', 'http://127.0.0.1:8000/assets/clients/img/product/hanh-tay.jpg', 1, 20000.00, '2026-08-17 08:12:21', '2026-08-17 08:12:21'),
(140, 48, 134, 'Nước dừa tươi', 'http://127.0.0.1:8000/assets/clients/img/product/nuoc-dua-tuoi.jpg', 1, 25000.00, '2026-08-17 08:08:35', '2026-08-17 08:08:35'),
(139, 48, 133, 'Sốt mè rang trộn salad', 'http://127.0.0.1:8000/assets/clients/img/product/sot-me-rang-tron-salad.jpg', 1, 45000.00, '2026-08-17 08:08:35', '2026-08-17 08:08:35'),
(138, 47, 134, 'Nước dừa tươi', 'http://127.0.0.1:8000/assets/clients/img/product/nuoc-dua-tuoi.jpg', 1, 25000.00, '2026-08-17 08:07:40', '2026-08-17 08:07:40'),
(137, 47, 133, 'Sốt mè rang trộn salad', 'http://127.0.0.1:8000/assets/clients/img/product/sot-me-rang-tron-salad.jpg', 1, 45000.00, '2026-08-17 08:07:40', '2026-08-17 08:07:40'),
(136, 46, 79, 'Cà chua bi', 'http://127.0.0.1:8000/assets/clients/img/product/ca-chua-bi.jpg', 1, 35000.00, '2026-08-17 08:03:42', '2026-08-17 08:03:42'),
(135, 45, 79, 'Cà chua bi', 'http://127.0.0.1:8000/assets/clients/img/product/ca-chua-bi.jpg', 1, 35000.00, '2026-08-17 07:46:09', '2026-08-17 07:46:09'),
(134, 44, 134, 'Nước dừa tươi', 'http://127.0.0.1:8000/assets/clients/img/product/nuoc-dua-tuoi.jpg', 1, 25000.00, '2026-08-16 15:56:34', '2026-08-16 15:56:34'),
(133, 44, 133, 'Sốt mè rang trộn salad', 'http://127.0.0.1:8000/assets/clients/img/product/sot-me-rang-tron-salad.jpg', 1, 45000.00, '2026-08-16 15:56:34', '2026-08-16 15:56:34'),
(132, 44, 125, 'Rau mùi (Ngò rí)', 'http://127.0.0.1:8000/assets/clients/img/product/ngo-ri.jpg', 1, 6000.00, '2026-08-16 15:56:34', '2026-08-16 15:56:34'),
(131, 44, 110, 'Trứng vịt lộn', 'http://127.0.0.1:8000/assets/clients/img/product/trung-vit-lon.jpg', 1, 8000.00, '2026-08-16 15:56:34', '2026-08-16 15:56:34'),
(130, 44, 29, 'Cá basa phi lê', 'http://127.0.0.1:8000/assets/clients/img/product/ca-basa-phi-le.jpg', 1, 90000.00, '2026-08-16 15:56:34', '2026-08-16 15:56:34'),
(129, 44, 119, 'Tiêu đen xay', 'http://127.0.0.1:8000/assets/clients/img/product/tieu-den-xay.jpg', 1, 35000.00, '2026-08-16 15:56:34', '2026-08-16 15:56:34'),
(128, 44, 132, 'Nước màu dừa', 'http://127.0.0.1:8000/assets/clients/img/product/nuoc-mau-dua.jpg', 1, 30000.00, '2026-08-16 15:56:34', '2026-08-16 15:56:34'),
(127, 44, 4, 'Hành lá', 'http://127.0.0.1:8000/assets/clients/img/product/hanh-la.jpg', 1, 8000.00, '2026-08-16 15:56:34', '2026-08-16 15:56:34'),
(126, 44, 127, 'Ớt sừng đỏ', 'http://127.0.0.1:8000/assets/clients/img/product/ot-sung-do.jpg', 1, 55000.00, '2026-08-16 15:56:34', '2026-08-16 15:56:34');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_payments`
--

DROP TABLE IF EXISTS `order_payments`;
CREATE TABLE IF NOT EXISTS `order_payments` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` bigint UNSIGNED NOT NULL,
  `payment_method` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'COD',
  `transaction_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_status` enum('pending','completed','failed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_payments_order_id_foreign` (`order_id`)
) ENGINE=MyISAM AUTO_INCREMENT=55 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `order_payments`
--

INSERT INTO `order_payments` (`id`, `order_id`, `payment_method`, `transaction_id`, `amount`, `payment_status`, `paid_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'COD', NULL, 1134000.00, 'pending', NULL, '2026-07-14 09:53:07', '2026-07-14 09:53:07'),
(2, 2, 'COD', NULL, 257000.00, 'completed', '2026-07-15 13:04:04', '2026-07-14 10:37:08', '2026-07-15 13:04:04'),
(3, 3, 'VNPay', NULL, 290000.00, 'pending', NULL, '2026-07-14 10:59:16', '2026-07-14 10:59:16'),
(4, 4, 'VNPay', NULL, 52000.00, 'pending', NULL, '2026-07-14 18:01:07', '2026-07-14 18:01:07'),
(5, 5, 'VNPay', NULL, 80000.00, 'pending', NULL, '2026-07-14 18:05:17', '2026-07-14 18:05:17'),
(6, 6, 'VNPay', NULL, 125000.00, 'pending', NULL, '2026-07-14 18:08:41', '2026-07-14 18:08:41'),
(7, 7, 'VNPay', NULL, 130000.00, 'pending', NULL, '2026-07-14 18:17:05', '2026-07-14 18:17:05'),
(8, 8, 'VNPay', NULL, 225000.00, 'failed', NULL, '2026-07-14 18:19:20', '2026-07-14 18:19:46'),
(9, 9, 'VNPay', NULL, 2868000.00, 'failed', NULL, '2026-07-15 12:52:36', '2026-07-15 12:52:46'),
(10, 10, 'COD', NULL, 2605500.00, 'pending', NULL, '2026-07-15 12:59:10', '2026-07-15 12:59:10'),
(11, 11, 'COD', NULL, 52000.00, 'completed', '2026-07-15 13:04:04', '2026-07-15 12:59:52', '2026-07-15 13:04:04'),
(12, 12, 'COD', NULL, 157000.00, 'pending', NULL, '2026-07-15 13:36:31', '2026-07-15 13:36:31'),
(13, 13, 'COD', NULL, 52000.00, 'pending', NULL, '2026-07-15 15:16:00', '2026-07-15 15:16:00'),
(14, 14, 'VNPay', NULL, 2721000.00, 'failed', NULL, '2026-07-17 16:26:40', '2026-07-17 16:41:46'),
(15, 15, 'COD', NULL, 422000.00, 'completed', '2026-07-23 10:22:53', '2026-07-23 10:21:18', '2026-07-23 10:22:53'),
(16, 16, 'COD', NULL, 385000.00, 'pending', NULL, '2026-07-25 17:34:09', '2026-07-25 17:34:09'),
(17, 17, 'COD', NULL, 385000.00, 'pending', NULL, '2026-07-25 17:35:06', '2026-07-25 17:35:06'),
(18, 18, 'COD', NULL, 312000.00, 'pending', NULL, '2026-07-25 17:39:22', '2026-07-25 17:39:22'),
(19, 19, 'COD', NULL, 130000.00, 'pending', NULL, '2026-07-26 06:12:41', '2026-07-26 06:12:41'),
(20, 20, 'COD', NULL, 295000.00, 'pending', NULL, '2026-07-26 06:18:15', '2026-07-26 06:18:15'),
(21, 21, 'COD', NULL, 653000.00, 'pending', NULL, '2026-07-26 11:55:43', '2026-07-26 11:55:43'),
(22, 22, 'COD', NULL, 340000.00, 'pending', NULL, '2026-07-26 14:08:31', '2026-07-26 14:08:31'),
(23, 23, 'COD', NULL, 119500.00, 'pending', NULL, '2026-07-26 14:09:28', '2026-07-26 14:09:28'),
(24, 24, 'COD', NULL, 115000.00, 'pending', NULL, '2026-07-26 15:01:29', '2026-07-26 15:01:29'),
(25, 25, 'COD', NULL, 41000.00, 'pending', NULL, '2026-07-29 12:12:18', '2026-07-29 12:12:18'),
(26, 26, 'COD', NULL, 532000.00, 'pending', NULL, '2026-07-29 12:27:18', '2026-07-29 12:27:18'),
(27, 27, 'COD', NULL, 80000.00, 'pending', NULL, '2026-07-29 13:02:36', '2026-07-29 13:02:36'),
(28, 28, 'VNPay', NULL, 63000.00, 'failed', NULL, '2026-07-29 16:05:59', '2026-07-29 16:07:03'),
(29, 29, 'VNPay', NULL, 63000.00, 'pending', NULL, '2026-07-29 16:07:30', '2026-07-29 16:07:30'),
(30, 30, 'VNPay', '15640560', 158000.00, 'completed', '2026-07-29 16:15:13', '2026-07-29 16:14:10', '2026-07-29 16:15:13'),
(31, 31, 'COD', NULL, 157000.00, 'pending', NULL, '2026-07-30 01:23:17', '2026-07-30 01:23:17'),
(32, 32, 'COD', NULL, 509000.00, 'pending', NULL, '2026-08-05 07:34:55', '2026-08-05 07:34:55'),
(33, 33, 'COD', NULL, 322000.00, 'pending', NULL, '2026-08-05 07:40:34', '2026-08-05 07:40:34'),
(34, 34, 'COD', NULL, 322000.00, 'pending', NULL, '2026-08-05 07:43:27', '2026-08-05 07:43:27'),
(35, 35, 'COD', NULL, 594000.00, 'pending', NULL, '2026-08-05 08:11:17', '2026-08-05 08:11:17'),
(36, 36, 'COD', NULL, 135000.00, 'completed', '2026-08-07 13:27:27', '2026-08-07 03:59:06', '2026-08-07 13:27:27'),
(37, 37, 'COD', NULL, 443000.00, 'completed', '2026-08-07 13:27:05', '2026-08-07 13:25:29', '2026-08-07 13:27:05'),
(38, 38, 'COD', NULL, 95000.00, 'pending', NULL, '2026-08-08 15:28:19', '2026-08-08 15:28:19'),
(39, 39, 'VNPay', '15650647', 92000.00, 'completed', '2026-08-08 18:04:54', '2026-08-08 18:03:25', '2026-08-08 18:04:54'),
(40, 40, 'COD', NULL, 298000.00, 'pending', NULL, '2026-08-09 06:32:05', '2026-08-09 06:32:05'),
(41, 41, 'VNPay', '15651045', 1695000.00, 'completed', '2026-08-09 13:07:47', '2026-08-09 13:07:02', '2026-08-09 13:07:47'),
(42, 42, 'COD', NULL, 215000.00, 'pending', NULL, '2026-08-09 13:12:06', '2026-08-09 13:12:06'),
(43, 43, 'COD', NULL, 50000.00, 'pending', NULL, '2026-08-16 13:51:05', '2026-08-16 13:51:05'),
(44, 44, 'COD', NULL, 327000.00, 'pending', NULL, '2026-08-16 15:56:34', '2026-08-16 15:56:34'),
(45, 45, 'VNPay', '15658699', 56500.00, 'completed', '2026-08-17 07:48:29', '2026-08-17 07:46:09', '2026-08-17 07:48:29'),
(46, 46, 'VNPay', '15658733', 25000.00, 'completed', '2026-08-17 08:04:14', '2026-08-17 08:03:42', '2026-08-17 08:04:14'),
(47, 47, 'VNPay', NULL, 95000.00, 'failed', NULL, '2026-08-17 08:07:40', '2026-08-17 08:07:48'),
(48, 48, 'VNPay', '15658743', 45000.00, 'completed', '2026-08-17 08:08:59', '2026-08-17 08:08:35', '2026-08-17 08:08:59'),
(49, 49, 'COD', NULL, 70000.00, 'pending', NULL, '2026-08-17 08:12:21', '2026-08-17 08:12:21'),
(50, 50, 'COD', NULL, 261700.00, 'completed', '2026-08-17 08:18:52', '2026-08-17 08:15:20', '2026-08-17 08:18:52'),
(51, 51, 'COD', NULL, 373000.00, 'completed', '2026-08-17 08:26:03', '2026-08-17 08:23:03', '2026-08-17 08:26:03'),
(52, 52, 'VNPay', '15659205', 195000.00, 'completed', '2026-08-17 14:39:49', '2026-08-17 14:38:35', '2026-08-17 14:39:49'),
(53, 53, 'VNPay', '15659206', 70000.00, 'completed', '2026-08-17 14:42:57', '2026-08-17 14:42:31', '2026-08-17 14:42:57'),
(54, 54, 'VNPay', '15659209', 80000.00, 'completed', '2026-08-17 14:49:13', '2026-08-17 14:46:08', '2026-08-17 14:49:13');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_status_logs`
--

DROP TABLE IF EXISTS `order_status_logs`;
CREATE TABLE IF NOT EXISTS `order_status_logs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` bigint UNSIGNED NOT NULL,
  `old_status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `new_status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_status_logs_order_id_foreign` (`order_id`)
) ENGINE=MyISAM AUTO_INCREMENT=108 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `order_status_logs`
--

INSERT INTO `order_status_logs` (`id`, `order_id`, `old_status`, `new_status`, `note`, `created_at`, `updated_at`) VALUES
(1, 1, 'new', 'pending', 'Khách hàng đặt hàng', '2026-07-14 09:53:07', '2026-07-14 09:53:07'),
(2, 1, 'pending', 'processing', 'Admin đổi trạng thái: AdminUser', '2026-07-14 10:21:17', '2026-07-14 10:21:17'),
(3, 1, 'processing', 'delivered', 'Admin đổi trạng thái: AdminUser', '2026-07-14 10:21:20', '2026-07-14 10:21:20'),
(4, 1, 'delivered', 'shipped', 'Admin đổi trạng thái: AdminUser', '2026-07-14 10:21:25', '2026-07-14 10:21:25'),
(5, 2, 'new', 'pending', 'Khách hàng đặt hàng', '2026-07-14 10:37:08', '2026-07-14 10:37:08'),
(6, 2, 'pending', 'processing', 'Admin đổi trạng thái: AdminUser', '2026-07-14 10:37:39', '2026-07-14 10:37:39'),
(7, 2, 'processing', 'shipped', 'Admin đổi trạng thái: AdminUser', '2026-07-14 10:37:45', '2026-07-14 10:37:45'),
(8, 2, 'shipped', 'delivered', 'Admin đổi trạng thái: AdminUser', '2026-07-14 10:37:49', '2026-07-14 10:37:49'),
(9, 3, 'new', 'pending', 'Khách đặt hàng — chờ thanh toán VNPay', '2026-07-14 10:59:16', '2026-07-14 10:59:16'),
(10, 4, 'new', 'pending', 'Khách đặt hàng — chờ thanh toán VNPay', '2026-07-14 18:01:07', '2026-07-14 18:01:07'),
(11, 5, 'new', 'pending', 'Khách đặt hàng — chờ thanh toán VNPay', '2026-07-14 18:05:17', '2026-07-14 18:05:17'),
(12, 6, 'new', 'pending', 'Khách đặt hàng — chờ thanh toán VNPay', '2026-07-14 18:08:41', '2026-07-14 18:08:41'),
(13, 7, 'new', 'pending', 'Khách đặt hàng — chờ thanh toán VNPay', '2026-07-14 18:17:05', '2026-07-14 18:17:05'),
(14, 8, 'new', 'pending', 'Khách đặt hàng — chờ thanh toán VNPay', '2026-07-14 18:19:20', '2026-07-14 18:19:20'),
(15, 8, 'pending', 'pending', 'VNPay thất bại / hủy. Mã: 24', '2026-07-14 18:19:46', '2026-07-14 18:19:46'),
(16, 9, 'new', 'pending', 'Khách đặt hàng — chờ thanh toán VNPay', '2026-07-15 12:52:36', '2026-07-15 12:52:36'),
(17, 9, 'pending', 'cancelled', 'VNPay thất bại / hủy. Mã: 24', '2026-07-15 12:52:46', '2026-07-15 12:52:46'),
(18, 10, 'new', 'pending', 'Khách hàng đặt hàng (COD)', '2026-07-15 12:59:10', '2026-07-15 12:59:10'),
(19, 10, 'pending', 'cancelled', 'Khách hủy đơn: Phí vận chuyển cao', '2026-07-15 12:59:32', '2026-07-15 12:59:32'),
(20, 11, 'new', 'pending', 'Khách hàng đặt hàng (COD)', '2026-07-15 12:59:52', '2026-07-15 12:59:52'),
(21, 11, 'pending', 'processing', 'Admin đổi trạng thái: AdminUser', '2026-07-15 13:00:18', '2026-07-15 13:00:18'),
(22, 11, 'processing', 'shipped', 'Admin đổi trạng thái: AdminUser', '2026-07-15 13:00:34', '2026-07-15 13:00:34'),
(23, 11, 'shipped', 'delivered', 'Admin đổi trạng thái: AdminUser', '2026-07-15 13:01:18', '2026-07-15 13:01:18'),
(24, 12, 'new', 'pending', 'Khách hàng đặt hàng (COD)', '2026-07-15 13:36:31', '2026-07-15 13:36:31'),
(25, 12, 'pending', 'processing', 'Đã kiểm tra tồn kho và xác nhận đơn — AdminUser', '2026-07-15 13:37:24', '2026-07-15 13:37:24'),
(26, 13, 'new', 'pending', 'Khách hàng đặt hàng (COD)', '2026-07-15 15:16:00', '2026-07-15 15:16:00'),
(27, 14, 'new', 'pending', 'Khách đặt hàng — chờ thanh toán VNPay', '2026-07-17 16:26:40', '2026-07-17 16:26:40'),
(28, 14, 'pending', 'cancelled', 'VNPay thất bại / hủy. Mã: 15', '2026-07-17 16:41:46', '2026-07-17 16:41:46'),
(29, 13, 'pending', 'processing', 'Đã kiểm tra tồn kho và xác nhận đơn — AdminUser', '2026-07-19 08:02:57', '2026-07-19 08:02:57'),
(30, 13, 'processing', 'cancelled', 'Admin đổi trạng thái: AdminUser', '2026-07-19 08:03:10', '2026-07-19 08:03:10'),
(31, 15, 'new', 'pending', 'Khách hàng đặt hàng (COD)', '2026-07-23 10:21:18', '2026-07-23 10:21:18'),
(32, 15, 'pending', 'processing', 'Đã kiểm tra tồn kho và xác nhận đơn — AdminUser', '2026-07-23 10:22:22', '2026-07-23 10:22:22'),
(33, 15, 'processing', 'shipped', 'Admin đổi trạng thái: AdminUser', '2026-07-23 10:22:39', '2026-07-23 10:22:39'),
(34, 15, 'shipped', 'delivered', 'Admin đổi trạng thái: AdminUser', '2026-07-23 10:22:53', '2026-07-23 10:22:53'),
(35, 16, 'new', 'pending', 'Khách hàng đặt hàng (COD)', '2026-07-25 17:34:09', '2026-07-25 17:34:09'),
(36, 17, 'new', 'pending', 'Khách hàng đặt hàng (COD)', '2026-07-25 17:35:06', '2026-07-25 17:35:06'),
(37, 18, 'new', 'pending', 'Khách hàng đặt hàng (COD)', '2026-07-25 17:39:22', '2026-07-25 17:39:22'),
(38, 19, 'new', 'pending', 'Khách hàng đặt hàng (COD)', '2026-07-26 06:12:41', '2026-07-26 06:12:41'),
(39, 19, 'pending', 'cancelled', 'Admin đổi trạng thái: AdminUser', '2026-07-26 06:14:35', '2026-07-26 06:14:35'),
(40, 20, 'new', 'pending', 'Khách hàng đặt hàng (COD)', '2026-07-26 06:18:15', '2026-07-26 06:18:15'),
(41, 20, 'pending', 'processing', 'Đã kiểm tra tồn kho và xác nhận đơn — AdminUser', '2026-07-26 06:19:00', '2026-07-26 06:19:00'),
(42, 21, 'new', 'pending', 'Khách hàng đặt hàng (COD)', '2026-07-26 11:55:43', '2026-07-26 11:55:43'),
(43, 22, 'new', 'pending', 'Khách hàng đặt hàng (COD)', '2026-07-26 14:08:31', '2026-07-26 14:08:31'),
(44, 23, 'new', 'pending', 'Khách hàng đặt hàng (COD)', '2026-07-26 14:09:28', '2026-07-26 14:09:28'),
(45, 24, 'new', 'pending', 'Khách hàng đặt hàng (COD)', '2026-07-26 15:01:29', '2026-07-26 15:01:29'),
(46, 25, 'new', 'pending', 'Khách hàng đặt hàng (COD)', '2026-07-29 12:12:18', '2026-07-29 12:12:18'),
(47, 26, 'new', 'pending', 'Khách hàng đặt hàng (COD)', '2026-07-29 12:27:18', '2026-07-29 12:27:18'),
(48, 27, 'new', 'pending', 'Khách hàng đặt hàng (COD)', '2026-07-29 13:02:36', '2026-07-29 13:02:36'),
(49, 27, 'pending', 'processing', 'Đã kiểm tra tồn kho và xác nhận đơn — AdminUser', '2026-07-29 13:04:02', '2026-07-29 13:04:02'),
(50, 27, 'processing', 'cancelled', 'Khách hủy đơn: Đặt nhầm sản phẩm/số lượng', '2026-07-29 13:57:13', '2026-07-29 13:57:13'),
(51, 28, 'new', 'pending', 'Khách đặt hàng — chờ thanh toán VNPay', '2026-07-29 16:05:59', '2026-07-29 16:05:59'),
(52, 28, 'pending', 'cancelled', 'VNPay thất bại / hủy. Mã: 24', '2026-07-29 16:07:03', '2026-07-29 16:07:03'),
(53, 29, 'new', 'pending', 'Khách đặt hàng — chờ thanh toán VNPay', '2026-07-29 16:07:30', '2026-07-29 16:07:30'),
(54, 30, 'new', 'pending', 'Khách đặt hàng — chờ thanh toán VNPay', '2026-07-29 16:14:10', '2026-07-29 16:14:10'),
(55, 30, 'pending', 'pending', 'Thanh toán VNPay thành công. Mã GD: 15640560', '2026-07-29 16:15:13', '2026-07-29 16:15:13'),
(56, 31, 'new', 'pending', 'Khách hàng đặt hàng (COD)', '2026-07-30 01:23:17', '2026-07-30 01:23:17'),
(57, 32, 'new', 'pending', 'Khách hàng đặt hàng (COD)', '2026-08-05 07:34:55', '2026-08-05 07:34:55'),
(58, 33, 'new', 'pending', 'Khách hàng đặt hàng (COD)', '2026-08-05 07:40:34', '2026-08-05 07:40:34'),
(59, 34, 'new', 'pending', 'Khách hàng đặt hàng (COD)', '2026-08-05 07:43:27', '2026-08-05 07:43:27'),
(60, 34, 'pending', 'processing', '12345', '2026-08-05 07:48:27', '2026-08-05 07:48:27'),
(61, 34, 'processing', 'shipped', 'huhuhuh', '2026-08-05 07:49:34', '2026-08-05 07:49:34'),
(62, 35, 'new', 'pending', 'Khách hàng đặt hàng (COD)', '2026-08-05 08:11:17', '2026-08-05 08:11:17'),
(63, 35, 'pending', 'processing', '12345', '2026-08-05 08:11:40', '2026-08-05 08:11:40'),
(64, 36, 'new', 'pending', 'Khách hàng đặt hàng (COD)', '2026-08-07 03:59:06', '2026-08-07 03:59:06'),
(65, 37, 'new', 'pending', 'Khách hàng đặt hàng (COD)', '2026-08-07 13:25:29', '2026-08-07 13:25:29'),
(66, 37, 'pending', 'processing', 'Đã kiểm tra tồn kho và xác nhận đơn — AdminUser', '2026-08-07 13:26:09', '2026-08-07 13:26:09'),
(67, 37, 'shipped', 'delivered', NULL, '2026-08-07 13:27:05', '2026-08-07 13:27:05'),
(68, 36, 'shipped', 'delivered', NULL, '2026-08-07 13:27:27', '2026-08-07 13:27:27'),
(69, 38, 'new', 'pending', 'Khách hàng đặt hàng (COD)', '2026-08-08 15:28:19', '2026-08-08 15:28:19'),
(70, 38, 'pending', 'processing', NULL, '2026-08-08 15:39:21', '2026-08-08 15:39:21'),
(71, 38, 'processing', 'shipped', NULL, '2026-08-08 15:39:35', '2026-08-08 15:39:35'),
(72, 33, 'pending', 'processing', NULL, '2026-08-08 15:44:05', '2026-08-08 15:44:05'),
(73, 39, 'new', 'pending', 'Khách đặt hàng — chờ thanh toán VNPay', '2026-08-08 18:03:25', '2026-08-08 18:03:25'),
(74, 39, 'pending', 'pending', 'Thanh toán VNPay thành công. Mã GD: 15650647', '2026-08-08 18:04:54', '2026-08-08 18:04:54'),
(75, 39, 'pending', 'cancelled', NULL, '2026-08-08 18:05:13', '2026-08-08 18:05:13'),
(76, 40, 'new', 'pending', 'Khách hàng đặt hàng (COD)', '2026-08-09 06:32:05', '2026-08-09 06:32:05'),
(77, 41, 'new', 'pending', 'Khách đặt hàng — chờ thanh toán VNPay', '2026-08-09 13:07:02', '2026-08-09 13:07:02'),
(78, 41, 'pending', 'pending', 'Thanh toán VNPay thành công. Mã GD: 15651045', '2026-08-09 13:07:47', '2026-08-09 13:07:47'),
(79, 41, 'pending', 'processing', NULL, '2026-08-09 13:08:12', '2026-08-09 13:08:12'),
(80, 42, 'new', 'pending', 'Khách hàng đặt hàng (COD)', '2026-08-09 13:12:06', '2026-08-09 13:12:06'),
(81, 42, 'pending', 'cancelled', NULL, '2026-08-09 13:12:23', '2026-08-09 13:12:23'),
(82, 43, 'new', 'pending', 'Khách hàng đặt hàng (COD)', '2026-08-16 13:51:05', '2026-08-16 13:51:05'),
(83, 43, 'pending', 'cancelled', 'Khách hủy đơn: Thay đổi ý định mua hàng', '2026-08-16 13:51:45', '2026-08-16 13:51:45'),
(84, 44, 'new', 'pending', 'Khách hàng đặt hàng (COD)', '2026-08-16 15:56:34', '2026-08-16 15:56:34'),
(85, 45, 'new', 'pending', 'Khách đặt hàng — chờ thanh toán VNPay', '2026-08-17 07:46:09', '2026-08-17 07:46:09'),
(86, 45, 'pending', 'pending', 'Thanh toán VNPay thành công. Mã GD: 15658699', '2026-08-17 07:48:29', '2026-08-17 07:48:29'),
(87, 46, 'new', 'pending', 'Khách đặt hàng — chờ thanh toán VNPay', '2026-08-17 08:03:42', '2026-08-17 08:03:42'),
(88, 46, 'pending', 'pending', 'Thanh toán VNPay thành công. Mã GD: 15658733', '2026-08-17 08:04:14', '2026-08-17 08:04:14'),
(89, 47, 'new', 'pending', 'Khách đặt hàng — chờ thanh toán VNPay', '2026-08-17 08:07:40', '2026-08-17 08:07:40'),
(90, 47, 'pending', 'cancelled', 'VNPay thất bại / hủy. Mã: 24', '2026-08-17 08:07:48', '2026-08-17 08:07:48'),
(91, 48, 'new', 'pending', 'Khách đặt hàng — chờ thanh toán VNPay', '2026-08-17 08:08:35', '2026-08-17 08:08:35'),
(92, 48, 'pending', 'pending', 'Thanh toán VNPay thành công. Mã GD: 15658743', '2026-08-17 08:08:59', '2026-08-17 08:08:59'),
(93, 49, 'new', 'pending', 'Khách hàng đặt hàng (COD)', '2026-08-17 08:12:21', '2026-08-17 08:12:21'),
(94, 50, 'new', 'pending', 'Khách hàng đặt hàng (COD)', '2026-08-17 08:15:20', '2026-08-17 08:15:20'),
(95, 50, 'pending', 'processing', NULL, '2026-08-17 08:17:29', '2026-08-17 08:17:29'),
(96, 50, 'processing', 'shipped', NULL, '2026-08-17 08:18:26', '2026-08-17 08:18:26'),
(97, 50, 'shipped', 'delivered', NULL, '2026-08-17 08:18:52', '2026-08-17 08:18:52'),
(98, 51, 'new', 'pending', 'Khách hàng đặt hàng (COD)', '2026-08-17 08:23:03', '2026-08-17 08:23:03'),
(99, 51, 'pending', 'processing', NULL, '2026-08-17 08:25:35', '2026-08-17 08:25:35'),
(100, 51, 'processing', 'shipped', NULL, '2026-08-17 08:25:51', '2026-08-17 08:25:51'),
(101, 51, 'shipped', 'delivered', NULL, '2026-08-17 08:26:03', '2026-08-17 08:26:03'),
(102, 52, 'new', 'pending', 'Khách đặt hàng — chờ thanh toán VNPay', '2026-08-17 14:38:35', '2026-08-17 14:38:35'),
(103, 52, 'pending', 'pending', 'Thanh toán VNPay thành công. Mã GD: 15659205', '2026-08-17 14:39:49', '2026-08-17 14:39:49'),
(104, 53, 'new', 'pending', 'Khách đặt hàng — chờ thanh toán VNPay', '2026-08-17 14:42:31', '2026-08-17 14:42:31'),
(105, 53, 'pending', 'pending', 'Thanh toán VNPay thành công. Mã GD: 15659206', '2026-08-17 14:42:57', '2026-08-17 14:42:57'),
(106, 54, 'new', 'pending', 'Khách đặt hàng — chờ thanh toán VNPay', '2026-08-17 14:46:08', '2026-08-17 14:46:08'),
(107, 54, 'pending', 'pending', 'Thanh toán VNPay thành công. Mã GD: 15659209', '2026-08-17 14:49:13', '2026-08-17 14:49:13');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `permissions`
--

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'manage_users', '2026-07-07 20:38:48', '2026-07-07 20:38:48'),
(2, 'manage_products', '2026-07-07 20:38:48', '2026-07-07 20:38:48'),
(3, 'manage_categories', '2026-07-07 20:38:48', '2026-07-07 20:38:48'),
(4, 'manage_orders', '2026-07-07 20:38:48', '2026-07-07 20:38:48'),
(31, 'edit_roles', '2026-08-05 14:45:35', '2026-08-05 14:45:35'),
(6, 'manage_coupons', '2026-07-07 20:38:48', '2026-07-07 20:38:48'),
(7, 'manage_reviews', '2026-07-14 10:08:12', '2026-07-14 10:08:12'),
(30, 'add_roles', '2026-08-05 14:45:35', '2026-08-05 14:45:35'),
(29, 'manage_roles', '2026-08-05 14:45:35', '2026-08-05 14:45:35'),
(11, 'add_products', '2026-08-05 09:18:25', '2026-08-05 09:18:25'),
(12, 'edit_products', '2026-08-05 09:18:25', '2026-08-05 09:18:25'),
(13, 'delete_products', '2026-08-05 09:18:25', '2026-08-05 09:18:25'),
(14, 'add_categories', '2026-08-05 09:18:25', '2026-08-05 09:18:25'),
(15, 'edit_categories', '2026-08-05 09:18:25', '2026-08-05 09:18:25'),
(16, 'delete_categories', '2026-08-05 09:18:25', '2026-08-05 09:18:25'),
(17, 'add_users', '2026-08-05 09:18:25', '2026-08-05 09:18:25'),
(18, 'edit_users', '2026-08-05 09:18:25', '2026-08-05 09:18:25'),
(42, 'add_recipes', '2026-08-07 09:52:42', '2026-08-07 09:52:42'),
(32, 'delete_roles', '2026-08-05 14:45:35', '2026-08-05 14:45:35'),
(23, 'add_coupons', '2026-08-05 09:18:25', '2026-08-05 09:18:25'),
(24, 'edit_coupons', '2026-08-05 09:18:25', '2026-08-05 09:18:25'),
(25, 'delete_coupons', '2026-08-05 09:18:25', '2026-08-05 09:18:25'),
(41, 'manage_recipes', '2026-08-07 09:52:42', '2026-08-07 09:52:42'),
(27, 'edit_reviews', '2026-08-05 09:18:25', '2026-08-05 09:18:25'),
(28, 'delete_reviews', '2026-08-05 09:18:25', '2026-08-05 09:18:25'),
(44, 'delete_recipes', '2026-08-07 09:52:42', '2026-08-07 09:52:42'),
(43, 'edit_recipes', '2026-08-07 09:52:42', '2026-08-07 09:52:42');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_vi_0900_as_cs NOT NULL,
  `slug` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_vi_0900_as_cs,
  `price` decimal(15,2) NOT NULL,
  `sale_price` decimal(15,2) DEFAULT NULL,
  `unit` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int NOT NULL DEFAULT '0',
  `manufacture_date` date DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `delivery_mode` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'both',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_slug_unique` (`slug`),
  KEY `products_category_id_foreign` (`category_id`)
) ENGINE=MyISAM AUTO_INCREMENT=141 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `slug`, `description`, `price`, `sale_price`, `unit`, `quantity`, `manufacture_date`, `expiry_date`, `is_featured`, `is_active`, `delivery_mode`, `created_at`, `updated_at`) VALUES
(80, 6, 'Táo Envy đỏ', 'tao-envy-do', 'Táo Envy đỏ nhập khẩu chính hãng hoặc thu mua từ vườn hữu cơ được kiểm định chất lượng. Ngọt tự nhiên, giàu vitamin.', 125000.00, 110000.00, 'kg', 20, '2026-08-01', '2026-08-31', 0, 1, 'both', '2026-07-01 03:37:09', '2026-08-16 15:54:24'),
(81, 13, 'Trứng gà sạch', 'trung-ga-sach', 'Trứng gà sạch nguồn gốc rõ ràng, đảm bảo vệ sinh an toàn thực phẩm, giàu dinh dưỡng và phù hợp cho cả trẻ em lẫn người lớn.', 32000.00, NULL, 'chục', 7, '2026-08-08', '2026-09-05', 0, 0, 'both', '2026-07-01 03:37:09', '2026-08-09 07:23:18'),
(79, 6, 'Cà chua bi', 'ca-chua-bi', 'Cà chua bi chất lượng cao, được kiểm định kỹ càng theo tiêu chuẩn hữu cơ, đảm bảo an toàn và tốt cho sức khỏe người tiêu dùng.', 35000.00, NULL, 'kg', 54, '2026-08-01', '2026-08-31', 1, 1, 'both', '2026-07-01 03:37:09', '2026-08-17 14:38:35'),
(76, 12, 'Hàu sữa tươi', 'hau-sua-tuoi', 'Hàu sữa tươi tươi sống đánh bắt hoặc nuôi sạch, được kiểm dịch và bảo quản lạnh đúng chuẩn từ khi khai thác đến tay khách hàng.', 120000.00, NULL, 'kg', 0, '2026-08-04', '2026-08-06', 0, 1, 'both', '2026-07-01 03:37:09', '2026-08-16 15:53:33'),
(78, 12, 'Bạch tuộc tươi', 'bach-tuoc-tuoi', 'Bạch tuộc tươi tươi sống đánh bắt hoặc nuôi sạch, được kiểm dịch và bảo quản lạnh đúng chuẩn từ khi khai thác đến tay khách hàng.', 180000.00, NULL, 'kg', 21, '2026-07-28', '2026-07-30', 1, 0, 'both', '2026-07-01 03:37:09', '2026-08-09 06:16:07'),
(77, 11, 'Cá điêu hồng', 'ca-dieu-hong', 'Cá điêu hồng tươi sống đánh bắt hoặc nuôi sạch, được kiểm dịch và bảo quản lạnh đúng chuẩn từ khi khai thác đến tay khách hàng.', 65000.00, NULL, 'kg', 15, '2026-08-04', '2026-08-06', 1, 0, 'both', '2026-07-01 03:37:09', '2026-08-09 06:16:07'),
(75, 12, 'Nghêu sạch', 'ngheu-sach', 'Nghêu sạch tươi sống đánh bắt hoặc nuôi sạch, được kiểm dịch và bảo quản lạnh đúng chuẩn từ khi khai thác đến tay khách hàng.', 55000.00, 22000.00, 'kg', 49, '2026-08-01', '2026-08-03', 0, 1, 'both', '2026-07-01 03:37:09', '2026-08-16 15:53:19'),
(74, 11, 'Cá lóc tươi', 'ca-loc-tuoi', 'Cá lóc tươi tươi sống đánh bắt hoặc nuôi sạch, được kiểm dịch và bảo quản lạnh đúng chuẩn từ khi khai thác đến tay khách hàng.', 85000.00, NULL, 'kg', 34, '2026-08-02', '2026-08-04', 1, 1, 'both', '2026-07-01 03:37:09', '2026-08-16 15:53:04'),
(68, 14, 'Trứng vịt muối', 'trung-vit-muoi', 'Trứng vịt muối nguồn gốc rõ ràng, đảm bảo vệ sinh an toàn thực phẩm, giàu dinh dưỡng và phù hợp cho cả trẻ em lẫn người lớn.', 55000.00, NULL, 'chục', 39, '2026-07-30', '2026-08-27', 1, 1, 'both', '2026-07-01 03:37:09', '2026-08-17 08:23:03'),
(64, 16, 'Phô mai lát Cheddar', 'pho-mai-lat-cheddar', 'Phô mai lát Cheddar nguồn gốc rõ ràng, đảm bảo vệ sinh an toàn thực phẩm, giàu dinh dưỡng và phù hợp cho cả trẻ em lẫn người lớn.', 75000.00, NULL, 'gói', 44, '2026-08-05', '2026-10-04', 1, 1, 'both', '2026-07-01 03:37:09', '2026-08-17 08:23:03'),
(63, 16, 'Bơ lát Anchor', 'bo-lat-anchor', 'Bơ lát Anchor nhập khẩu chính hãng hoặc thu mua từ vườn hữu cơ được kiểm định chất lượng. Ngọt tự nhiên, giàu vitamin.', 85000.00, NULL, 'hộp', 50, '2026-07-31', '2026-09-29', 0, 1, 'both', '2026-07-01 03:37:09', '2026-08-09 06:16:07'),
(62, 13, 'Trứng gà ác', 'trung-ga-ac', 'Trứng gà ác nguồn gốc rõ ràng, đảm bảo vệ sinh an toàn thực phẩm, giàu dinh dưỡng và phù hợp cho cả trẻ em lẫn người lớn.', 50000.00, NULL, 'chục', 59, '2026-07-25', '2026-08-22', 1, 1, 'both', '2026-07-01 03:37:09', '2026-08-16 15:52:30'),
(61, 14, 'Trứng cút tươi', 'trung-cut-tuoi', 'Trứng cút tươi nguồn gốc rõ ràng, đảm bảo vệ sinh an toàn thực phẩm, giàu dinh dưỡng và phù hợp cho cả trẻ em lẫn người lớn.', 20000.00, NULL, 'chục', 99, '2026-07-30', '2026-08-27', 1, 1, 'both', '2026-07-01 03:37:09', '2026-08-09 06:16:07'),
(60, 14, 'Trứng vịt tươi', 'trung-vit-tuoi', 'Trứng vịt tươi nguồn gốc rõ ràng, đảm bảo vệ sinh an toàn thực phẩm, giàu dinh dưỡng và phù hợp cho cả trẻ em lẫn người lớn.', 35000.00, NULL, 'chục', 80, '2026-08-01', '2026-08-29', 0, 1, 'both', '2026-07-01 03:37:09', '2026-08-09 06:16:07'),
(59, 6, 'Kiwi xanh New Zealand', 'kiwi-xanh-new-zealand', 'Kiwi xanh New Zealand nhập khẩu chính hãng hoặc thu mua từ vườn hữu cơ được kiểm định chất lượng. Ngọt tự nhiên, giàu vitamin.', 130000.00, NULL, 'kg', 25, '2026-07-27', '2026-08-26', 0, 1, 'both', '2026-07-01 03:37:09', '2026-08-09 06:16:07'),
(56, 6, 'Đu đủ chín', 'du-du-chin', 'Đu đủ chín nhập khẩu chính hãng hoặc thu mua từ vườn hữu cơ được kiểm định chất lượng. Ngọt tự nhiên, giàu vitamin.', 25000.00, NULL, 'kg', 60, '2026-08-07', '2026-09-06', 0, 1, 'both', '2026-07-01 03:37:09', '2026-08-09 06:16:07'),
(57, 6, 'Bưởi da xanh', 'buoi-da-xanh', 'Bưởi da xanh nhập khẩu chính hãng hoặc thu mua từ vườn hữu cơ được kiểm định chất lượng. Ngọt tự nhiên, giàu vitamin.', 55000.00, NULL, 'quả', 35, '2026-07-26', '2026-08-25', 0, 1, 'both', '2026-07-01 03:37:09', '2026-08-09 06:16:07'),
(55, 6, 'Vải thiều Lục Ngạn', 'vai-thieu-luc-ngan', 'Vải thiều Lục Ngạn nhập khẩu chính hãng hoặc thu mua từ vườn hữu cơ được kiểm định chất lượng. Ngọt tự nhiên, giàu vitamin.', 75000.00, NULL, 'kg', 30, '2026-07-29', '2026-08-28', 0, 1, 'both', '2026-07-01 03:37:09', '2026-08-09 06:16:07'),
(54, 6, 'Sầu riêng Ri6', 'sau-rieng-ri6', 'Sầu riêng Ri6 nhập khẩu chính hãng hoặc thu mua từ vườn hữu cơ được kiểm định chất lượng. Ngọt tự nhiên, giàu vitamin.', 120000.00, NULL, 'kg', 20, '2026-07-25', '2026-08-24', 0, 1, 'both', '2026-07-01 03:37:09', '2026-08-09 06:16:07'),
(53, 6, 'Chôm chôm Java', 'chom-chom-java', 'Chôm chôm Java nhập khẩu chính hãng hoặc thu mua từ vườn hữu cơ được kiểm định chất lượng. Ngọt tự nhiên, giàu vitamin.', 40000.00, NULL, 'kg', 50, '2026-08-04', '2026-09-03', 1, 1, 'both', '2026-07-01 03:37:09', '2026-08-09 06:16:07'),
(51, 6, 'Ổi nữ hoàng', 'oi-nu-hoang', 'Ổi nữ hoàng nhập khẩu chính hãng hoặc thu mua từ vườn hữu cơ được kiểm định chất lượng. Ngọt tự nhiên, giàu vitamin.', 45000.00, NULL, 'kg', 40, '2026-08-07', '2026-09-06', 0, 1, 'both', '2026-07-01 03:37:09', '2026-08-09 06:16:07'),
(52, 6, 'Măng cụt', 'mang-cut', 'Măng cụt nhập khẩu chính hãng hoặc thu mua từ vườn hữu cơ được kiểm định chất lượng. Ngọt tự nhiên, giàu vitamin.', 90000.00, NULL, 'kg', 25, '2026-08-03', '2026-09-02', 1, 1, 'both', '2026-07-01 03:37:09', '2026-08-09 06:16:07'),
(50, 6, 'Dưa hấu ruột đỏ', 'dua-hau-ruot-do', 'Dưa hấu ruột đỏ nhập khẩu chính hãng hoặc thu mua từ vườn hữu cơ được kiểm định chất lượng. Ngọt tự nhiên, giàu vitamin.', 20000.00, NULL, 'kg', 60, '2026-08-08', '2026-09-07', 1, 1, 'both', '2026-07-01 03:37:09', '2026-08-09 06:16:07'),
(49, 19, 'Nấm đùi gà', 'nam-dui-ga', 'Nấm đùi gà đạt chuẩn VietGAP, tươi mới thu hoạch trong ngày, không sử dụng thuốc trừ sâu hay chất bảo quản hóa học.', 95000.00, NULL, 'kg', 30, '2026-08-02', '2026-08-07', 1, 1, 'both', '2026-07-01 03:37:09', '2026-08-09 06:16:07'),
(48, 18, 'Củ dền đỏ', 'cu-den-do', 'Củ dền đỏ đạt chuẩn VietGAP, tươi mới thu hoạch trong ngày, không sử dụng thuốc trừ sâu hay chất bảo quản hóa học.', 28000.00, NULL, 'kg', 55, '2026-07-30', '2026-08-29', 0, 1, 'both', '2026-07-01 03:37:09', '2026-08-09 06:16:07'),
(47, 17, 'Rau ngót', 'rau-ngot', 'Rau ngót đạt chuẩn VietGAP, tươi mới thu hoạch trong ngày, không sử dụng thuốc trừ sâu hay chất bảo quản hóa học.', 9000.00, NULL, 'bó', 100, '2026-07-28', '2026-08-02', 0, 1, 'both', '2026-07-01 03:37:09', '2026-08-09 06:16:07'),
(45, 18, 'Bí đao', 'bi-dao', 'Bí đao đạt chuẩn VietGAP, tươi mới thu hoạch trong ngày, không sử dụng thuốc trừ sâu hay chất bảo quản hóa học.', 15000.00, NULL, 'kg', 89, '2026-08-06', '2026-09-05', 0, 1, 'both', '2026-07-01 03:37:09', '2026-08-09 06:16:07'),
(46, 18, 'Mướp hương', 'muop-huong', 'Mướp hương đạt chuẩn VietGAP, tươi mới thu hoạch trong ngày, không sử dụng thuốc trừ sâu hay chất bảo quản hóa học.', 18000.00, NULL, 'kg', 74, '2026-08-01', '2026-08-31', 0, 1, 'both', '2026-07-01 03:37:09', '2026-08-09 06:16:07'),
(44, 18, 'Đậu bắp', 'dau-bap', 'Đậu bắp đạt chuẩn VietGAP, tươi mới thu hoạch trong ngày, không sử dụng thuốc trừ sâu hay chất bảo quản hóa học.', 25000.00, NULL, 'kg', 80, '2026-08-01', '2026-08-31', 1, 1, 'both', '2026-07-01 03:37:09', '2026-08-09 06:16:07'),
(43, 18, 'Ớt chuông 3 màu', 'ot-chuong-3-mau', 'Ớt chuông 3 màu đạt chuẩn VietGAP, tươi mới thu hoạch trong ngày, không sử dụng thuốc trừ sâu hay chất bảo quản hóa học.', 55000.00, NULL, 'kg', 40, '2026-07-26', '2026-08-25', 0, 1, 'both', '2026-07-01 03:37:09', '2026-08-09 06:16:07'),
(41, 18, 'Khoai lang mật', 'khoai-lang-mat', 'Khoai lang mật đạt chuẩn VietGAP, tươi mới thu hoạch trong ngày, không sử dụng thuốc trừ sâu hay chất bảo quản hóa học.', 30000.00, NULL, 'kg', 60, '2026-07-26', '2026-08-25', 0, 1, 'both', '2026-07-01 03:37:09', '2026-08-09 06:16:07'),
(42, 18, 'Cà tím', 'ca-tim', 'Cà tím chất lượng cao, được kiểm định kỹ càng theo tiêu chuẩn hữu cơ, đảm bảo an toàn và tốt cho sức khỏe người tiêu dùng.', 22000.00, NULL, 'kg', 70, '2026-07-29', '2026-08-28', 0, 1, 'both', '2026-07-01 03:37:09', '2026-08-09 06:16:07'),
(40, 17, 'Rau mồng tơi', 'rau-mong-toi', 'Rau mồng tơi đạt chuẩn VietGAP, tươi mới thu hoạch trong ngày, không sử dụng thuốc trừ sâu hay chất bảo quản hóa học.', 8000.00, NULL, 'bó', 100, '2026-08-02', '2026-08-07', 1, 1, 'both', '2026-07-01 03:37:09', '2026-08-09 06:16:07'),
(38, 18, 'Đậu đỏ hạt', 'dau-do-hat', 'Đậu đỏ hạt đạt chuẩn VietGAP, tươi mới thu hoạch trong ngày, không sử dụng thuốc trừ sâu hay chất bảo quản hóa học.', 42000.00, NULL, 'kg', 60, '2026-08-03', '2026-09-02', 1, 1, 'both', '2026-07-01 03:37:09', '2026-08-09 06:16:07'),
(37, 18, 'Đậu xanh hạt', 'dau-xanh-hat', 'Đậu xanh hạt đạt chuẩn VietGAP, tươi mới thu hoạch trong ngày, không sử dụng thuốc trừ sâu hay chất bảo quản hóa học.', 40000.00, NULL, 'kg', 70, '2026-07-29', '2026-08-28', 1, 1, 'both', '2026-07-01 03:37:09', '2026-08-09 06:16:07'),
(29, 11, 'Cá basa phi lê', 'ca-basa-phi-le', 'Cá basa phi lê nhập khẩu chính hãng hoặc thu mua từ vườn hữu cơ được kiểm định chất lượng. Ngọt tự nhiên, giàu vitamin.', 90000.00, NULL, 'kg', 42, '2026-08-06', '2026-08-08', 0, 1, 'both', '2026-07-01 03:37:09', '2026-08-17 08:23:03'),
(28, 9, 'Sườn non heo', 'suon-non-heo', 'Sườn non heo tươi sạch, được nuôi dưỡng theo quy trình an toàn sinh học, không sử dụng chất tăng trọng hay kháng sinh.', 130000.00, NULL, 'kg', 35, '2026-08-01', '2026-08-04', 1, 1, 'both', '2026-07-01 03:37:09', '2026-08-09 06:16:07'),
(27, 12, 'Ghẹ xanh tươi', 'ghe-xanh-tuoi', 'Ghẹ xanh tươi tươi sống đánh bắt hoặc nuôi sạch, được kiểm dịch và bảo quản lạnh đúng chuẩn từ khi khai thác đến tay khách hàng.', 250000.00, NULL, 'kg', 15, '2026-07-29', '2026-07-31', 0, 1, 'both', '2026-07-01 03:37:09', '2026-08-09 06:16:07'),
(26, 11, 'Cá thu cắt lát', 'ca-thu-cat-lat', 'Cá thu cắt lát tươi sống đánh bắt hoặc nuôi sạch, được kiểm dịch và bảo quản lạnh đúng chuẩn từ khi khai thác đến tay khách hàng.', 150000.00, NULL, 'kg', 20, '2026-07-31', '2026-08-02', 1, 1, 'both', '2026-07-01 03:37:09', '2026-08-09 06:16:07'),
(25, 10, 'Ức gà phi lê', 'uc-ga-phi-le', 'Ức gà phi lê nhập khẩu chính hãng hoặc thu mua từ vườn hữu cơ được kiểm định chất lượng. Ngọt tự nhiên, giàu vitamin.', 85000.00, 34000.00, 'kg', 50, '2026-07-31', '2026-08-02', 0, 1, 'both', '2026-07-01 03:37:09', '2026-08-16 15:51:28'),
(24, 12, 'Mực ống tươi', 'muc-ong-tuoi', 'Mực ống tươi tươi sống đánh bắt hoặc nuôi sạch, được kiểm dịch và bảo quản lạnh đúng chuẩn từ khi khai thác đến tay khách hàng.', 180000.00, NULL, 'kg', 30, '2026-07-30', '2026-08-01', 0, 1, 'both', '2026-07-01 03:37:09', '2026-08-09 06:16:07'),
(23, 12, 'Tôm sú tươi', 'tom-su-tuoi', 'Tôm sú tươi tươi sống đánh bắt hoặc nuôi sạch, được kiểm dịch và bảo quản lạnh đúng chuẩn từ khi khai thác đến tay khách hàng.', 220000.00, NULL, 'kg', 0, '2026-08-08', '2026-08-10', 1, 1, 'both', '2026-07-01 03:37:09', '2026-08-09 06:16:07'),
(21, 9, 'Thịt heo sạch', 'thit-heo-sach', 'Thịt heo sạch tươi sạch, được nuôi dưỡng theo quy trình an toàn sinh học, không sử dụng chất tăng trọng hay kháng sinh.', 120000.00, NULL, 'kg', 38, '2026-08-01', '2026-08-04', 0, 1, 'both', '2026-07-01 03:37:09', '2026-08-09 13:07:02'),
(22, 11, 'Cá hồi Na Uy', 'ca-hoi-na-uy', 'Cá hồi Na Uy tươi sống đánh bắt hoặc nuôi sạch, được kiểm dịch và bảo quản lạnh đúng chuẩn từ khi khai thác đến tay khách hàng.', 280000.00, NULL, 'kg', 15, '2026-07-27', '2026-07-29', 0, 1, 'both', '2026-07-01 03:37:09', '2026-08-09 06:16:07'),
(20, 8, 'Thịt bò Úc', 'thit-bo-uc', 'Thịt bò Úc tươi sạch, được nuôi dưỡng theo quy trình an toàn sinh học, không sử dụng chất tăng trọng hay kháng sinh.', 350000.00, NULL, 'kg', 20, '2026-08-05', '2026-08-09', 1, 1, 'both', '2026-07-01 03:37:09', '2026-08-09 06:16:07'),
(19, 6, 'Mận hậu Sơn La', 'man-hau-son-la', 'Mận hậu Sơn La nhập khẩu chính hãng hoặc thu mua từ vườn hữu cơ được kiểm định chất lượng. Ngọt tự nhiên, giàu vitamin.', 80000.00, NULL, 'kg', 30, '2026-07-25', '2026-08-24', 1, 1, 'both', '2026-07-01 03:37:09', '2026-08-09 06:16:07'),
(18, 6, 'Thanh long ruột đỏ', 'thanh-long-ruot-do', 'Thanh long ruột đỏ nhập khẩu chính hãng hoặc thu mua từ vườn hữu cơ được kiểm định chất lượng. Ngọt tự nhiên, giàu vitamin.', 55000.00, NULL, 'kg', 50, '2026-07-26', '2026-08-25', 1, 1, 'both', '2026-07-01 03:37:09', '2026-08-09 06:16:07'),
(17, 6, 'Bơ sáp Đắk Lắk', 'bo-sap-dak-lak', 'Bơ sáp Đắk Lắk nhập khẩu chính hãng hoặc thu mua từ vườn hữu cơ được kiểm định chất lượng. Ngọt tự nhiên, giàu vitamin.', 75000.00, NULL, 'kg', 35, '2026-07-28', '2026-08-27', 0, 1, 'both', '2026-07-01 03:37:09', '2026-08-09 06:16:07'),
(16, 6, 'Lê Hàn Quốc', 'le-han-quoc', 'Lê Hàn Quốc nhập khẩu chính hãng hoặc thu mua từ vườn hữu cơ được kiểm định chất lượng. Ngọt tự nhiên, giàu vitamin.', 180000.00, NULL, 'kg', 15, '2026-07-28', '2026-08-27', 0, 1, 'both', '2026-07-01 03:37:09', '2026-08-09 06:16:07'),
(15, 6, 'Chuối già Nam Mỹ', 'chuoi-gia-nam-my', 'Chuối già Nam Mỹ nhập khẩu chính hãng hoặc thu mua từ vườn hữu cơ được kiểm định chất lượng. Ngọt tự nhiên, giàu vitamin.', 35000.00, NULL, 'nải', 60, '2026-08-06', '2026-09-05', 1, 1, 'both', '2026-07-01 03:37:09', '2026-08-09 06:16:07'),
(14, 6, 'Xoài cát Hoà Lộc', 'xoai-cat-hoa-loc', 'Xoài cát Hoà Lộc nhập khẩu chính hãng hoặc thu mua từ vườn hữu cơ được kiểm định chất lượng. Ngọt tự nhiên, giàu vitamin.', 65000.00, NULL, 'kg', 45, '2026-07-30', '2026-08-29', 0, 1, 'both', '2026-07-01 03:37:09', '2026-08-09 06:16:07'),
(13, 6, 'Nho đen không hạt', 'nho-den-khong-hat', 'Nho đen không hạt nhập khẩu chính hãng hoặc thu mua từ vườn hữu cơ được kiểm định chất lượng. Ngọt tự nhiên, giàu vitamin.', 150000.00, NULL, 'kg', 20, '2026-08-06', '2026-09-05', 1, 1, 'both', '2026-07-01 03:37:09', '2026-08-09 06:16:07'),
(12, 6, 'Cam vàng Úc', 'cam-vang-uc', 'Cam vàng Úc nhập khẩu chính hãng hoặc thu mua từ vườn hữu cơ được kiểm định chất lượng. Ngọt tự nhiên, giàu vitamin.', 95000.00, NULL, 'kg', 25, '2026-08-07', '2026-09-06', 1, 1, 'both', '2026-07-01 03:37:09', '2026-08-09 06:16:07'),
(10, 17, 'Bắp cải trắng', 'bap-cai-trang', 'Bắp cải trắng đạt chuẩn VietGAP, tươi mới thu hoạch trong ngày, không sử dụng thuốc trừ sâu hay chất bảo quản hóa học.', 18000.00, NULL, 'kg', 55, '2026-07-29', '2026-08-03', 1, 1, 'both', '2026-07-01 03:37:09', '2026-08-09 06:16:07'),
(9, 18, 'Dưa leo', 'dua-leo', 'Dưa leo đạt chuẩn VietGAP, tươi mới thu hoạch trong ngày, không sử dụng thuốc trừ sâu hay chất bảo quản hóa học.', 20000.00, NULL, 'kg', 70, '2026-08-01', '2026-08-31', 1, 1, 'both', '2026-07-01 03:37:09', '2026-08-09 06:16:07'),
(8, 18, 'Củ su su', 'cu-su-su', 'Củ su su đạt chuẩn VietGAP, tươi mới thu hoạch trong ngày, không sử dụng thuốc trừ sâu hay chất bảo quản hóa học.', 15000.00, NULL, 'kg', 58, '2026-07-25', '2026-08-24', 0, 1, 'both', '2026-07-01 03:37:09', '2026-08-09 06:16:07'),
(7, 17, 'Cải ngọt', 'cai-ngot', 'Cải ngọt đạt chuẩn VietGAP, tươi mới thu hoạch trong ngày, không sử dụng thuốc trừ sâu hay chất bảo quản hóa học.', 12000.00, NULL, 'bó', 75, '2026-08-04', '2026-08-09', 1, 1, 'both', '2026-07-01 03:37:09', '2026-08-09 06:16:07'),
(6, 18, 'Bí đỏ hồ lô', 'bi-do-ho-lo', 'Bí đỏ hồ lô đạt chuẩn VietGAP, tươi mới thu hoạch trong ngày, không sử dụng thuốc trừ sâu hay chất bảo quản hóa học.', 22000.00, NULL, 'kg', 40, '2026-07-27', '2026-08-26', 0, 1, 'both', '2026-07-01 03:37:09', '2026-08-09 06:16:07'),
(4, 17, 'Hành lá', 'hanh-la', 'Hành lá đạt chuẩn VietGAP, tươi mới thu hoạch trong ngày, không sử dụng thuốc trừ sâu hay chất bảo quản hóa học.', 8000.00, NULL, 'bó', 95, '2026-07-26', '2026-07-31', 0, 1, 'both', '2026-07-01 03:37:09', '2026-08-17 08:23:03'),
(5, 17, 'Rau muống', 'rau-muong', 'Rau muống đạt chuẩn VietGAP, tươi mới thu hoạch trong ngày, không sử dụng thuốc trừ sâu hay chất bảo quản hóa học.', 10000.00, NULL, 'bó', 90, '2026-08-05', '2026-08-10', 0, 1, 'both', '2026-07-01 03:37:09', '2026-08-09 06:16:07'),
(3, 18, 'Khoai tây', 'khoai-tay', 'Khoai tây đạt chuẩn VietGAP, tươi mới thu hoạch trong ngày, không sử dụng thuốc trừ sâu hay chất bảo quản hóa học.', 28000.00, NULL, 'kg', 29, '2026-07-26', '2026-08-25', 0, 1, 'both', '2026-07-01 03:37:09', '2026-08-09 06:16:07'),
(2, 18, 'Cà rốt', 'ca-rot', 'Cà rốt đạt chuẩn VietGAP, tươi mới thu hoạch trong ngày, không sử dụng thuốc trừ sâu hay chất bảo quản hóa học.', 30000.00, NULL, 'kg', 60, '2026-07-28', '2026-08-27', 0, 1, 'both', '2026-07-01 03:37:09', '2026-08-09 06:16:07'),
(1, 17, 'Cải xanh', 'cai-xanh', 'Cải xanh đạt chuẩn VietGAP, tươi mới thu hoạch trong ngày, không sử dụng thuốc trừ sâu hay chất bảo quản hóa học.', 25000.00, NULL, 'kg', 46, '2026-08-04', '2026-08-09', 0, 1, 'both', '2026-07-01 03:37:09', '2026-08-09 06:16:07'),
(91, 7, 'Nước ép cam tươi', 'nuoc-ep-cam-tuoi', 'Nước ép cam tươi nguyên chất, ép trực tiếp từ cam vàng chín mọng, không pha đường, giữ trọn vitamin C tự nhiên.', 45000.00, NULL, 'chai', 50, '2026-07-27', '2026-08-26', 1, 1, 'both', '2026-08-08 23:00:00', '2026-08-09 06:16:07'),
(92, 7, 'Nước ép dưa hấu', 'nuoc-ep-dua-hau', 'Nước ép dưa hấu tươi mát, ép nguyên trái, vị ngọt thanh tự nhiên, giải nhiệt cực tốt trong ngày hè.', 35000.00, NULL, 'chai', 45, '2026-08-07', '2026-09-06', 0, 1, 'both', '2026-08-08 23:00:00', '2026-08-09 06:16:07'),
(93, 7, 'Nước ép cà rốt', 'nuoc-ep-ca-rot', 'Nước ép cà rốt nguyên chất, giàu beta-carotene, tốt cho mắt và làn da, không chất bảo quản.', 38000.00, NULL, 'chai', 40, '2026-07-27', '2026-08-26', 0, 1, 'both', '2026-08-08 23:00:00', '2026-08-09 06:16:07'),
(94, 7, 'Nước ép táo', 'nuoc-ep-tao', 'Nước ép táo tươi ép lạnh, vị ngọt dịu tự nhiên từ táo nhập khẩu, không thêm đường tinh luyện.', 42000.00, NULL, 'chai', 40, '2026-07-26', '2026-08-25', 1, 1, 'both', '2026-08-08 23:00:00', '2026-08-16 15:22:26'),
(95, 7, 'Nước ép ổi', 'nuoc-ep-oi', 'Nước ép ổi hồng nguyên chất, giàu vitamin C, vị chua ngọt hài hòa, tốt cho hệ tiêu hóa.', 36000.00, NULL, 'chai', 35, '2026-08-01', '2026-08-31', 0, 1, 'both', '2026-08-08 23:00:00', '2026-08-09 06:16:07'),
(96, 8, 'Thịt bò Mỹ', 'thit-bo-my', 'Thịt bò Mỹ nhập khẩu, thớ thịt mềm, vân mỡ đều, phù hợp chế biến bít tết và các món nướng cao cấp.', 380000.00, NULL, 'kg', 20, '2026-08-08', '2026-08-12', 1, 1, 'both', '2026-08-08 23:00:00', '2026-08-16 15:51:59'),
(97, 8, 'Thịt bò tơ Củ Chi', 'thit-bo-to-cu-chi', 'Thịt bò tơ Củ Chi tươi ngon, thịt mềm ngọt tự nhiên, được nuôi thả tại các trang trại địa phương uy tín.', 260000.00, NULL, 'kg', 30, '2026-07-31', '2026-08-04', 0, 1, 'both', '2026-08-08 23:00:00', '2026-08-09 06:16:07'),
(98, 8, 'Bắp bò', 'bap-bo', 'Bắp bò tươi, thớ thịt chắc, ít mỡ, thích hợp hầm, nấu phở hoặc làm bò khô.', 290000.00, NULL, 'kg', 25, '2026-08-04', '2026-08-08', 0, 1, 'both', '2026-08-08 23:00:00', '2026-08-09 06:16:07'),
(99, 8, 'Gân bò', 'gan-bo', 'Gân bò tươi sạch, giòn dai tự nhiên, nguyên liệu không thể thiếu cho món phở gân bò truyền thống.', 220000.00, NULL, 'kg', 20, '2026-07-29', '2026-08-02', 0, 1, 'both', '2026-08-08 23:00:00', '2026-08-09 06:16:07'),
(100, 9, 'Ba chỉ heo', 'ba-chi-heo', 'Ba chỉ heo tươi sạch, tỷ lệ nạc mỡ hài hòa, thích hợp chiên, nướng hoặc kho tiêu đậm đà.', 140000.00, NULL, 'kg', 40, '2026-08-01', '2026-08-04', 1, 1, 'both', '2026-08-08 23:00:00', '2026-08-16 15:51:44'),
(101, 9, 'Nạc vai heo', 'nac-vai-heo', 'Nạc vai heo tươi, thịt mềm ít mỡ, phù hợp làm chả, xay nhân hoặc xào các món hàng ngày.', 125000.00, NULL, 'kg', 45, '2026-08-08', '2026-08-11', 0, 1, 'both', '2026-08-08 23:00:00', '2026-08-09 06:16:07'),
(102, 9, 'Chân giò heo', 'chan-gio-heo', 'Chân giò heo tươi sạch, phần da giòn thịt chắc, lý tưởng cho món giò hầm măng hoặc hầm thuốc bắc.', 115000.00, NULL, 'kg', 35, '2026-08-05', '2026-08-08', 0, 1, 'both', '2026-08-08 23:00:00', '2026-08-09 06:16:07'),
(103, 10, 'Đùi gà góc tư', 'dui-ga-goc-tu', 'Đùi gà góc tư tươi sạch, thịt chắc, phù hợp chiên, nướng hoặc kho sả ớt.', 75000.00, NULL, 'kg', 50, '2026-07-30', '2026-08-01', 1, 1, 'both', '2026-08-08 23:00:00', '2026-08-16 15:52:15'),
(104, 10, 'Cánh gà', 'canh-ga', 'Cánh gà tươi sạch, phần da mỏng giòn, thích hợp chiên nước mắm hoặc nướng mật ong.', 70000.00, NULL, 'kg', 45, '2026-07-29', '2026-07-31', 0, 1, 'both', '2026-08-08 23:00:00', '2026-08-09 06:16:07'),
(105, 10, 'Gà ta nguyên con', 'ga-ta-nguyen-con', 'Gà ta thả vườn nguyên con, thịt săn chắc, da vàng tự nhiên, thích hợp luộc, hấp hoặc nấu cháo.', 165000.00, NULL, 'con', 20, '2026-08-01', '2026-08-03', 1, 1, 'both', '2026-08-08 23:00:00', '2026-08-09 06:16:07'),
(106, 10, 'Chân gà', 'chan-ga', 'Chân gà tươi sạch, giòn sần sật, nguyên liệu quen thuộc cho món chân gà sả tắc hoặc hầm thuốc bắc.', 60000.00, NULL, 'kg', 40, '2026-07-29', '2026-07-31', 0, 1, 'both', '2026-08-08 23:00:00', '2026-08-09 06:16:07'),
(107, 13, 'Trứng gà công nghiệp', 'trung-ga-cong-nghiep', 'Trứng gà công nghiệp size đồng đều, nguồn gốc trang trại đạt chuẩn an toàn thực phẩm.', 28000.00, NULL, 'chục', 100, '2026-08-06', '2026-09-03', 0, 1, 'both', '2026-08-08 23:00:00', '2026-08-09 06:16:07'),
(108, 13, 'Trứng gà so', 'trung-ga-so', 'Trứng gà so nhỏ đặc trưng, lòng đỏ béo thơm, thường dùng nấu cháo cho trẻ nhỏ.', 40000.00, NULL, 'chục', 60, '2026-08-07', '2026-09-04', 0, 1, 'both', '2026-08-08 23:00:00', '2026-08-09 06:16:07'),
(109, 13, 'Trứng gà hữu cơ', 'trung-ga-huu-co', 'Trứng gà hữu cơ từ gà thả vườn ăn thức ăn tự nhiên, không kháng sinh, giàu dinh dưỡng.', 45000.00, NULL, 'chục', 50, '2026-07-27', '2026-08-24', 1, 1, 'both', '2026-08-08 23:00:00', '2026-08-09 06:16:07'),
(110, 14, 'Trứng vịt lộn', 'trung-vit-lon', 'Trứng vịt lộn tươi mới, chọn lọc kỹ càng, món ăn bổ dưỡng quen thuộc của người Việt.', 8000.00, NULL, 'quả', 99, '2026-08-01', '2026-08-29', 1, 1, 'both', '2026-08-08 23:00:00', '2026-08-16 15:56:34'),
(111, 14, 'Trứng vịt bắc thảo', 'trung-vit-bac-thao', 'Trứng vịt bắc thảo ủ theo công thức truyền thống, lòng đỏ dẻo béo, lòng trắng trong như thạch.', 12000.00, NULL, 'quả', 60, '2026-08-03', '2026-08-31', 0, 1, 'both', '2026-08-08 23:00:00', '2026-08-09 06:16:07'),
(113, 16, 'Bơ đậu phộng', 'bo-dau-phong', 'Bơ đậu phộng nguyên chất, xay mịn từ đậu phộng rang, thơm béo tự nhiên, không chất bảo quản.', 65000.00, NULL, 'hũ', 45, '2026-08-02', '2026-10-01', 1, 1, 'both', '2026-08-08 23:00:00', '2026-08-09 06:16:07'),
(114, 16, 'Bơ thực vật', 'bo-thuc-vat', 'Bơ thực vật mềm mịn, phù hợp làm bánh, phết bánh mì, thay thế bơ động vật trong chế độ ăn eat clean.', 48000.00, NULL, 'hộp', 50, '2026-08-06', '2026-10-05', 0, 1, 'both', '2026-08-08 23:00:00', '2026-08-09 06:16:07'),
(115, 19, 'Nấm kim châm', 'nam-kim-cham', 'Nấm kim châm tươi giòn, thân dài trắng ngà, thích hợp nhúng lẩu hoặc xào các món chay.', 15000.00, NULL, 'gói', 70, '2026-07-29', '2026-08-03', 1, 1, 'both', '2026-08-08 23:00:00', '2026-08-09 06:16:07'),
(116, 19, 'Nấm rơm', 'nam-rom', 'Nấm rơm tươi thu hoạch trong ngày, vị ngọt tự nhiên, thích hợp nấu canh hoặc xào chay.', 55000.00, NULL, 'kg', 40, '2026-07-26', '2026-07-31', 0, 1, 'both', '2026-08-08 23:00:00', '2026-08-09 06:16:07'),
(117, 19, 'Nấm bào ngư', 'nam-bao-ngu', 'Nấm bào ngư trắng tươi, thịt dày giòn ngọt, giàu dinh dưỡng, thích hợp xào hoặc nấu súp.', 60000.00, NULL, 'kg', 35, '2026-08-06', '2026-08-11', 1, 1, 'both', '2026-08-08 23:00:00', '2026-08-09 06:16:07'),
(118, 19, 'Nấm hương khô', 'nam-huong-kho', 'Nấm hương khô thơm đậm đà, được phơi tự nhiên, dùng làm nguyên liệu cho các món hầm và nhân bánh.', 85000.00, NULL, 'gói', 30, '2026-07-29', '2026-08-03', 0, 1, 'both', '2026-08-08 23:00:00', '2026-08-09 06:16:07'),
(119, 23, 'Tiêu đen xay', 'tieu-den-xay', 'Tiêu đen xay nguyên chất từ tiêu Phú Quốc, hương thơm nồng đặc trưng, không pha trộn tạp chất.', 35000.00, NULL, 'gói', 55, '2026-08-06', '2028-08-05', 1, 1, 'both', '2026-08-08 23:00:00', '2026-08-17 08:23:03'),
(120, 23, 'Bột ngọt', 'bot-ngot', 'Bột ngọt tinh khiết, giúp món ăn thêm đậm đà, đóng gói tiện lợi cho gian bếp gia đình.', 25000.00, NULL, 'gói', 80, '2026-08-08', '2028-08-07', 0, 1, 'both', '2026-08-08 23:00:00', '2026-08-09 06:16:07'),
(121, 23, 'Hạt nêm rau củ', 'hat-nem-rau-cu', 'Hạt nêm chiết xuất từ rau củ tự nhiên, tăng vị ngọt thanh cho món canh, món xào hàng ngày.', 30000.00, NULL, 'gói', 65, '2026-07-30', '2028-07-29', 1, 1, 'both', '2026-08-08 23:00:00', '2026-08-09 06:16:07'),
(122, 23, 'Ớt bột', 'ot-bot', 'Ớt bột nguyên chất cay nồng tự nhiên, phơi sấy theo phương pháp truyền thống, không phẩm màu.', 28000.00, NULL, 'gói', 55, '2026-07-25', '2028-07-24', 0, 1, 'both', '2026-08-08 23:00:00', '2026-08-09 06:16:07'),
(123, 23, 'Tỏi khô', 'toi-kho', 'Tỏi khô Lý Sơn nguyên củ, tép đều thơm nồng đặc trưng, dùng làm gia vị hoặc ngâm tỏi mật ong.', 45000.00, NULL, 'kg', 50, '2026-07-27', '2028-07-26', 1, 1, 'both', '2026-08-08 23:00:00', '2026-08-09 06:16:07'),
(124, 17, 'Xà lách', 'xa-lach', 'Xà lách tươi giòn, lá xanh mướt, đạt chuẩn VietGAP, thu hoạch trong ngày, thích hợp làm salad và cuốn thịt.', 22000.00, NULL, 'kg', 60, '2026-08-04', '2026-08-09', 1, 1, 'both', '2026-08-08 23:00:00', '2026-08-09 06:16:07'),
(125, 17, 'Rau mùi (Ngò rí)', 'ngo-ri', 'Rau mùi tươi thơm nồng đặc trưng, thường dùng trang trí và tăng hương vị cho món salad, cháo, súp.', 6000.00, NULL, 'bó', 89, '2026-08-01', '2026-08-06', 0, 1, 'both', '2026-08-08 23:00:00', '2026-08-16 15:56:34'),
(126, 18, 'Bắp Mỹ tách hạt', 'bap-my-tach-hat', 'Bắp Mỹ ngọt tách hạt sẵn, hạt vàng óng mọng nước, tiện lợi cho món salad, súp và các món xào.', 35000.00, NULL, 'kg', 45, '2026-08-05', '2026-09-04', 1, 1, 'both', '2026-08-08 23:00:00', '2026-08-09 06:16:07'),
(127, 18, 'Ớt sừng đỏ', 'ot-sung-do', 'Ớt sừng đỏ tươi cay nhẹ, thường dùng để tăng màu sắc và vị cay cho các món xào, kho, rim.', 55000.00, NULL, 'kg', 25, '2026-08-03', '2026-09-02', 0, 1, 'both', '2026-08-08 23:00:00', '2026-08-17 08:23:03'),
(128, 18, 'Hành tím', 'hanh-tim', 'Hành tím tươi thơm nồng, củ chắc, nguyên liệu không thể thiếu trong các món kho, rim và xào.', 45000.00, NULL, 'kg', 49, '2026-07-30', '2026-08-29', 1, 1, 'both', '2026-08-08 23:00:00', '2026-08-17 08:15:20'),
(129, 18, 'Hành tây', 'hanh-tay', 'Hành tây tươi giòn ngọt, thường dùng xào cùng thịt bò hoặc làm salad, súp.', 20000.00, NULL, 'kg', 53, '2026-08-07', '2026-09-06', 0, 1, 'both', '2026-08-08 23:00:00', '2026-08-17 14:42:31'),
(130, 18, 'Gừng tươi', 'gung-tuoi', 'Gừng tươi cay nồng ấm, dùng khử mùi và tăng hương vị cho các món kho, xào và ướp thịt.', 35000.00, NULL, 'kg', 0, '2026-07-25', '2026-08-24', 0, 1, 'both', '2026-08-08 23:00:00', '2026-08-09 13:07:02'),
(131, 23, 'Me vắt', 'me-vat', 'Me vắt nguyên chất, vị chua thanh đặc trưng, dùng pha nước sốt me cho món tôm rim, canh chua.', 25000.00, NULL, 'gói', 48, '2026-08-07', '2028-08-06', 0, 1, 'both', '2026-08-08 23:00:00', '2026-08-17 14:42:31'),
(132, 23, 'Nước màu dừa', 'nuoc-mau-dua', 'Nước màu dừa thắng sẵn từ đường và dừa tươi, tạo màu cánh gián đẹp mắt cho các món kho, rim truyền thống.', 30000.00, NULL, 'chai', 38, '2026-08-02', '2028-08-01', 1, 1, 'both', '2026-08-08 23:00:00', '2026-08-17 14:46:08'),
(133, 23, 'Sốt mè rang trộn salad', 'sot-me-rang-tron-salad', 'Sốt mè rang béo bùi, vị chua ngọt hài hòa, chuyên dùng trộn salad rau củ và thịt gà áp chảo.', 45000.00, NULL, 'chai', 32, '2026-08-02', '2028-08-01', 1, 1, 'both', '2026-08-08 23:00:00', '2026-08-17 14:38:35'),
(134, 7, 'Nước dừa tươi', 'nuoc-dua-tuoi', 'Nước dừa tươi nguyên chất, vị ngọt thanh mát tự nhiên, dùng kho cá, kho thịt hoặc giải khát.', 25000.00, NULL, 'trái', 54, '2026-08-06', '2026-09-05', 0, 1, 'both', '2026-08-08 23:00:00', '2026-08-17 14:46:08');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product_images`
--

DROP TABLE IF EXISTS `product_images`;
CREATE TABLE IF NOT EXISTS `product_images` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` bigint UNSIGNED NOT NULL,
  `image_url` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_images_product_id_foreign` (`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=165 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `image_url`, `is_primary`, `created_at`, `updated_at`) VALUES
(1, 80, 'http://127.0.0.1:8000/assets/clients/img/product/tao-envy-do.jpg', 1, '2026-07-08 05:50:33', '2026-07-08 05:50:33'),
(91, 92, 'http://127.0.0.1:8000/assets/clients/img/product/nuoc-ep-dua-hau.jpg', 1, '2026-08-08 23:00:00', '2026-08-08 23:00:00'),
(3, 79, 'http://127.0.0.1:8000/assets/clients/img/product/ca-chua-bi.jpg', 1, '2026-07-08 05:50:33', '2026-07-08 05:50:33'),
(4, 76, 'http://127.0.0.1:8000/assets/clients/img/product/hau-sua-tuoi.jpg', 1, '2026-07-08 05:50:33', '2026-07-08 05:50:33'),
(5, 78, 'http://127.0.0.1:8000/assets/clients/img/product/bach-tuoc-tuoi.jpg', 1, '2026-07-08 05:50:33', '2026-07-08 05:50:33'),
(6, 77, 'http://127.0.0.1:8000/assets/clients/img/product/ca-dieu-hong.jpg', 1, '2026-07-08 05:50:33', '2026-07-08 05:50:33'),
(7, 75, 'http://127.0.0.1:8000/assets/clients/img/product/ngheu-sach.jpg', 1, '2026-07-08 05:50:33', '2026-07-08 05:50:33'),
(159, 74, '/storage/products/ca-loc-tuoi/ca-loc-tuoi-1786895584.jpg', 0, '2026-08-16 15:53:04', '2026-08-16 15:53:04'),
(9, 74, 'http://127.0.0.1:8000/assets/clients/img/product/ca-loc-tuoi.jpg', 1, '2026-07-08 05:50:33', '2026-07-08 05:50:33'),
(152, 94, '/storage/products/nuoc-ep-tao/nuoc-ep-tao-1786893746.jpg', 0, '2026-08-16 15:22:26', '2026-08-16 15:22:26'),
(158, 68, '/storage/products/trung-vit-muoi/trung-vit-muoi-1786895566.jpg', 0, '2026-08-16 15:52:46', '2026-08-16 15:52:46'),
(90, 91, 'http://127.0.0.1:8000/assets/clients/img/product/nuoc-ep-cam-tuoi.jpg', 1, '2026-08-08 23:00:00', '2026-08-08 23:00:00'),
(154, 100, '/storage/products/ba-chi-heo/ba-chi-heo-1786895504.jpg', 0, '2026-08-16 15:51:44', '2026-08-16 15:51:44'),
(155, 96, '/storage/products/thit-bo-my/thit-bo-my-1786895519.jpg', 0, '2026-08-16 15:51:59', '2026-08-16 15:51:59'),
(15, 68, 'http://127.0.0.1:8000/assets/clients/img/product/trung-vit-muoi.jpg', 1, '2026-07-08 05:50:33', '2026-07-08 05:50:33'),
(157, 62, '/storage/products/trung-ga-ac/trung-ga-ac-1786895550.jpg', 0, '2026-08-16 15:52:30', '2026-08-16 15:52:30'),
(156, 103, '/storage/products/dui-ga-goc-tu/dui-ga-goc-tu-1786895535.jpg', 0, '2026-08-16 15:52:15', '2026-08-16 15:52:15'),
(18, 64, 'http://127.0.0.1:8000/assets/clients/img/product/pho-mai-lat-cheddar.jpg', 1, '2026-07-08 05:50:33', '2026-07-08 05:50:33'),
(19, 63, 'http://127.0.0.1:8000/assets/clients/img/product/bo-lat-anchor.jpg', 1, '2026-07-08 05:50:33', '2026-07-08 05:50:33'),
(20, 62, 'http://127.0.0.1:8000/assets/clients/img/product/trung-ga-ac.jpg', 1, '2026-07-08 05:50:33', '2026-07-08 05:50:33'),
(21, 61, 'http://127.0.0.1:8000/assets/clients/img/product/trung-cut-tuoi.jpg', 1, '2026-07-08 05:50:33', '2026-07-08 05:50:33'),
(22, 60, 'http://127.0.0.1:8000/assets/clients/img/product/trung-vit-tuoi.jpg', 1, '2026-07-08 05:50:33', '2026-07-08 05:50:33'),
(23, 59, 'http://127.0.0.1:8000/assets/clients/img/product/kiwi-xanh-new-zealand.jpg', 1, '2026-07-08 05:50:33', '2026-07-08 05:50:33'),
(24, 58, 'http://127.0.0.1:8000/assets/clients/img/product/dua-mat.jpg', 1, '2026-07-08 05:50:33', '2026-07-08 05:50:33'),
(25, 56, 'http://127.0.0.1:8000/assets/clients/img/product/du-du-chin.jpg', 1, '2026-07-08 05:50:33', '2026-07-08 05:50:33'),
(26, 57, 'http://127.0.0.1:8000/assets/clients/img/product/buoi-da-xanh.jpg', 1, '2026-07-08 05:50:33', '2026-07-08 05:50:33'),
(27, 55, 'http://127.0.0.1:8000/assets/clients/img/product/vai-thieu-luc-ngan.jpg', 1, '2026-07-08 05:50:33', '2026-07-08 05:50:33'),
(28, 54, 'http://127.0.0.1:8000/assets/clients/img/product/sau-rieng-ri6.jpg', 1, '2026-07-08 05:50:33', '2026-07-08 05:50:33'),
(29, 53, 'http://127.0.0.1:8000/assets/clients/img/product/chom-chom-java.jpg', 1, '2026-07-08 05:50:33', '2026-07-08 05:50:33'),
(30, 51, 'http://127.0.0.1:8000/assets/clients/img/product/oi-nu-hoang.jpg', 1, '2026-07-08 05:50:33', '2026-07-08 05:50:33'),
(31, 52, 'http://127.0.0.1:8000/assets/clients/img/product/mang-cut.jpg', 1, '2026-07-08 05:50:33', '2026-07-08 05:50:33'),
(32, 50, 'http://127.0.0.1:8000/assets/clients/img/product/dua-hau-ruot-do.jpg', 1, '2026-07-08 05:50:33', '2026-07-08 05:50:33'),
(33, 49, 'http://127.0.0.1:8000/assets/clients/img/product/nam-dui-ga.jpg', 1, '2026-07-08 05:50:33', '2026-07-08 05:50:33'),
(34, 48, 'http://127.0.0.1:8000/assets/clients/img/product/cu-den-do.jpg', 1, '2026-07-08 05:50:33', '2026-07-08 05:50:33'),
(35, 47, 'http://127.0.0.1:8000/assets/clients/img/product/rau-ngot.jpg', 1, '2026-07-08 05:50:33', '2026-07-08 05:50:33'),
(36, 45, 'http://127.0.0.1:8000/assets/clients/img/product/bi-dao.jpg', 1, '2026-07-08 05:50:33', '2026-07-08 05:50:33'),
(37, 46, 'http://127.0.0.1:8000/assets/clients/img/product/muop-huong.jpg', 1, '2026-07-08 05:50:33', '2026-07-08 05:50:33'),
(38, 44, 'http://127.0.0.1:8000/assets/clients/img/product/dau-bap.jpg', 1, '2026-07-08 05:50:33', '2026-07-08 05:50:33'),
(39, 43, 'http://127.0.0.1:8000/assets/clients/img/product/ot-chuong-3-mau.jpg', 1, '2026-07-08 05:50:33', '2026-07-08 05:50:33'),
(40, 41, 'http://127.0.0.1:8000/assets/clients/img/product/khoai-lang-mat.jpg', 1, '2026-07-08 05:50:33', '2026-07-08 05:50:33'),
(41, 42, 'http://127.0.0.1:8000/assets/clients/img/product/ca-tim.jpg', 1, '2026-07-08 05:50:33', '2026-07-08 05:50:33'),
(42, 40, 'http://127.0.0.1:8000/assets/clients/img/product/rau-mong-toi.jpg', 1, '2026-07-08 05:50:33', '2026-07-08 05:50:33'),
(153, 25, '/storage/products/uc-ga-phi-le/uc-ga-phi-le-1786895488.jpg', 0, '2026-08-16 15:51:28', '2026-08-16 15:51:28'),
(44, 38, 'http://127.0.0.1:8000/assets/clients/img/product/dau-do-hat.jpg', 1, '2026-07-08 05:50:33', '2026-07-08 05:50:33'),
(45, 37, 'http://127.0.0.1:8000/assets/clients/img/product/dau-xanh-hat.jpg', 1, '2026-07-08 05:50:33', '2026-07-08 05:50:33'),
(160, 75, '/storage/products/ngheu-sach/ngheu-sach-1786895599.jpg', 0, '2026-08-16 15:53:19', '2026-08-16 15:53:19'),
(161, 76, '/storage/products/hau-sua-tuoi/hau-sua-tuoi-1786895613.jpg', 0, '2026-08-16 15:53:33', '2026-08-16 15:53:33'),
(162, 79, '/storage/products/ca-chua-bi/ca-chua-bi-1786895632.jpg', 0, '2026-08-16 15:53:52', '2026-08-16 15:53:52'),
(163, 80, '/storage/products/tao-envy-do/tao-envy-do-1786895664.jpg', 0, '2026-08-16 15:54:24', '2026-08-16 15:54:24'),
(53, 29, 'http://127.0.0.1:8000/assets/clients/img/product/ca-basa-phi-le.jpg', 1, '2026-07-08 05:50:33', '2026-07-08 05:50:33'),
(54, 28, 'http://127.0.0.1:8000/assets/clients/img/product/suon-non-heo.jpg', 1, '2026-07-08 05:50:33', '2026-07-08 05:50:33'),
(55, 27, 'http://127.0.0.1:8000/assets/clients/img/product/ghe-xanh-tuoi.jpg', 1, '2026-07-08 05:50:33', '2026-07-08 05:50:33'),
(56, 26, 'http://127.0.0.1:8000/assets/clients/img/product/ca-thu-cat-lat.jpg', 1, '2026-07-08 05:50:33', '2026-07-08 05:50:33'),
(57, 25, 'http://127.0.0.1:8000/assets/clients/img/product/uc-ga-phi-le.jpg', 1, '2026-07-08 05:50:33', '2026-07-08 05:50:33'),
(58, 24, 'http://127.0.0.1:8000/assets/clients/img/product/muc-ong-tuoi.jpg', 1, '2026-07-08 05:50:33', '2026-07-08 05:50:33'),
(59, 23, 'http://127.0.0.1:8000/assets/clients/img/product/tom-su-tuoi.jpg', 1, '2026-07-08 05:50:33', '2026-07-08 05:50:33'),
(60, 21, 'http://127.0.0.1:8000/assets/clients/img/product/thit-heo-sach.jpg', 1, '2026-07-08 05:50:33', '2026-07-08 05:50:33'),
(61, 22, 'http://127.0.0.1:8000/assets/clients/img/product/ca-hoi-na-uy.jpg', 1, '2026-07-08 05:50:33', '2026-07-08 05:50:33'),
(62, 20, 'http://127.0.0.1:8000/assets/clients/img/product/thit-bo-uc.jpg', 1, '2026-07-08 05:50:33', '2026-07-08 05:50:33'),
(63, 19, 'http://127.0.0.1:8000/assets/clients/img/product/man-hau-son-la.jpg', 1, '2026-07-08 05:50:33', '2026-07-08 05:50:33'),
(64, 18, 'http://127.0.0.1:8000/assets/clients/img/product/thanh-long-ruot-do.jpg', 1, '2026-07-08 05:50:33', '2026-07-08 05:50:33'),
(65, 17, 'http://127.0.0.1:8000/assets/clients/img/product/bo-sap-dak-lak.jpg', 1, '2026-07-08 05:50:33', '2026-07-08 05:50:33'),
(66, 16, 'http://127.0.0.1:8000/assets/clients/img/product/le-han-quoc.jpg', 1, '2026-07-08 05:50:33', '2026-07-08 05:50:33'),
(67, 15, 'http://127.0.0.1:8000/assets/clients/img/product/chuoi-gia-nam-my.jpg', 1, '2026-07-08 05:50:33', '2026-07-08 05:50:33'),
(68, 14, 'http://127.0.0.1:8000/assets/clients/img/product/xoai-cat-hoa-loc.jpg', 1, '2026-07-08 05:50:33', '2026-07-08 05:50:33'),
(69, 13, 'http://127.0.0.1:8000/assets/clients/img/product/nho-den-khong-hat.jpg', 1, '2026-07-08 05:50:33', '2026-07-08 05:50:33'),
(70, 12, 'http://127.0.0.1:8000/assets/clients/img/product/cam-vang-uc.jpg', 1, '2026-07-08 05:50:33', '2026-07-08 05:50:33'),
(71, 10, 'http://127.0.0.1:8000/assets/clients/img/product/bap-cai-trang.jpg', 1, '2026-07-08 05:50:33', '2026-07-08 05:50:33'),
(72, 11, 'http://127.0.0.1:8000/assets/clients/img/product/tao-envy.jpg', 1, '2026-07-08 05:50:33', '2026-07-08 05:50:33'),
(73, 9, 'http://127.0.0.1:8000/assets/clients/img/product/dua-leo.jpg', 1, '2026-07-08 05:50:33', '2026-07-08 05:50:33'),
(74, 8, 'http://127.0.0.1:8000/assets/clients/img/product/cu-su-su.jpg', 1, '2026-07-08 05:50:33', '2026-07-08 05:50:33'),
(75, 7, 'http://127.0.0.1:8000/assets/clients/img/product/cai-ngot.jpg', 1, '2026-07-08 05:50:33', '2026-07-08 05:50:33'),
(76, 6, 'http://127.0.0.1:8000/assets/clients/img/product/bi-do-ho-lo.jpg', 1, '2026-07-08 05:50:33', '2026-07-08 05:50:33'),
(77, 4, 'http://127.0.0.1:8000/assets/clients/img/product/hanh-la.jpg', 1, '2026-07-08 05:50:33', '2026-07-08 05:50:33'),
(78, 5, 'http://127.0.0.1:8000/assets/clients/img/product/rau-muong.jpg', 1, '2026-07-08 05:50:33', '2026-07-08 05:50:33'),
(79, 3, 'http://127.0.0.1:8000/assets/clients/img/product/khoai-tay.jpg', 1, '2026-07-08 05:50:33', '2026-07-08 05:50:33'),
(80, 2, 'http://127.0.0.1:8000/assets/clients/img/product/ca-rot.jpg', 1, '2026-07-08 05:50:33', '2026-07-08 05:50:33'),
(81, 1, 'http://127.0.0.1:8000/assets/clients/img/product/cai-xanh.jpg', 1, '2026-07-08 05:50:33', '2026-07-08 05:50:33'),
(92, 93, 'http://127.0.0.1:8000/assets/clients/img/product/nuoc-ep-ca-rot.jpg', 1, '2026-08-08 23:00:00', '2026-08-08 23:00:00'),
(87, 81, '/storage/products/r4PyrmwUyEyiCsZYbMCt3YW0B9x5G8JoxthUlHaG.jpg', 1, '2026-07-15 17:16:11', '2026-07-15 17:16:11'),
(93, 94, 'http://127.0.0.1:8000/assets/clients/img/product/nuoc-ep-tao.jpg', 1, '2026-08-08 23:00:00', '2026-08-08 23:00:00'),
(94, 95, 'http://127.0.0.1:8000/assets/clients/img/product/nuoc-ep-oi.jpg', 1, '2026-08-08 23:00:00', '2026-08-08 23:00:00'),
(95, 96, 'http://127.0.0.1:8000/assets/clients/img/product/thit-bo-my.jpg', 1, '2026-08-08 23:00:00', '2026-08-08 23:00:00'),
(96, 97, 'http://127.0.0.1:8000/assets/clients/img/product/thit-bo-to-cu-chi.jpg', 1, '2026-08-08 23:00:00', '2026-08-08 23:00:00'),
(97, 98, 'http://127.0.0.1:8000/assets/clients/img/product/bap-bo.jpg', 1, '2026-08-08 23:00:00', '2026-08-08 23:00:00'),
(98, 99, 'http://127.0.0.1:8000/assets/clients/img/product/gan-bo.jpg', 1, '2026-08-08 23:00:00', '2026-08-08 23:00:00'),
(99, 100, 'http://127.0.0.1:8000/assets/clients/img/product/ba-chi-heo.jpg', 1, '2026-08-08 23:00:00', '2026-08-08 23:00:00'),
(100, 101, 'http://127.0.0.1:8000/assets/clients/img/product/nac-vai-heo.jpg', 1, '2026-08-08 23:00:00', '2026-08-08 23:00:00'),
(101, 102, 'http://127.0.0.1:8000/assets/clients/img/product/chan-gio-heo.jpg', 1, '2026-08-08 23:00:00', '2026-08-08 23:00:00'),
(102, 103, 'http://127.0.0.1:8000/assets/clients/img/product/dui-ga-goc-tu.jpg', 1, '2026-08-08 23:00:00', '2026-08-08 23:00:00'),
(103, 104, 'http://127.0.0.1:8000/assets/clients/img/product/canh-ga.jpg', 1, '2026-08-08 23:00:00', '2026-08-08 23:00:00'),
(104, 105, 'http://127.0.0.1:8000/assets/clients/img/product/ga-ta-nguyen-con.jpg', 1, '2026-08-08 23:00:00', '2026-08-08 23:00:00'),
(105, 106, 'http://127.0.0.1:8000/assets/clients/img/product/chan-ga.jpg', 1, '2026-08-08 23:00:00', '2026-08-08 23:00:00'),
(106, 107, 'http://127.0.0.1:8000/assets/clients/img/product/trung-ga-cong-nghiep.jpg', 1, '2026-08-08 23:00:00', '2026-08-08 23:00:00'),
(107, 108, 'http://127.0.0.1:8000/assets/clients/img/product/trung-ga-so.jpg', 1, '2026-08-08 23:00:00', '2026-08-08 23:00:00'),
(108, 109, 'http://127.0.0.1:8000/assets/clients/img/product/trung-ga-huu-co.jpg', 1, '2026-08-08 23:00:00', '2026-08-08 23:00:00'),
(109, 110, 'http://127.0.0.1:8000/assets/clients/img/product/trung-vit-lon.jpg', 1, '2026-08-08 23:00:00', '2026-08-08 23:00:00'),
(110, 111, 'http://127.0.0.1:8000/assets/clients/img/product/trung-vit-bac-thao.jpg', 1, '2026-08-08 23:00:00', '2026-08-08 23:00:00'),
(112, 113, 'http://127.0.0.1:8000/assets/clients/img/product/bo-dau-phong.jpg', 1, '2026-08-08 23:00:00', '2026-08-08 23:00:00'),
(113, 114, 'http://127.0.0.1:8000/assets/clients/img/product/bo-thuc-vat.jpg', 1, '2026-08-08 23:00:00', '2026-08-08 23:00:00'),
(114, 115, 'http://127.0.0.1:8000/assets/clients/img/product/nam-kim-cham.jpg', 1, '2026-08-08 23:00:00', '2026-08-08 23:00:00'),
(115, 116, 'http://127.0.0.1:8000/assets/clients/img/product/nam-rom.jpg', 1, '2026-08-08 23:00:00', '2026-08-08 23:00:00'),
(116, 117, 'http://127.0.0.1:8000/assets/clients/img/product/nam-bao-ngu.jpg', 1, '2026-08-08 23:00:00', '2026-08-08 23:00:00'),
(117, 118, 'http://127.0.0.1:8000/assets/clients/img/product/nam-huong-kho.jpg', 1, '2026-08-08 23:00:00', '2026-08-08 23:00:00'),
(118, 119, 'http://127.0.0.1:8000/assets/clients/img/product/tieu-den-xay.jpg', 1, '2026-08-08 23:00:00', '2026-08-08 23:00:00'),
(119, 120, 'http://127.0.0.1:8000/assets/clients/img/product/bot-ngot.jpg', 1, '2026-08-08 23:00:00', '2026-08-08 23:00:00'),
(120, 121, 'http://127.0.0.1:8000/assets/clients/img/product/hat-nem-rau-cu.jpg', 1, '2026-08-08 23:00:00', '2026-08-08 23:00:00'),
(121, 122, 'http://127.0.0.1:8000/assets/clients/img/product/ot-bot.jpg', 1, '2026-08-08 23:00:00', '2026-08-08 23:00:00'),
(122, 123, 'http://127.0.0.1:8000/assets/clients/img/product/toi-kho.jpg', 1, '2026-08-08 23:00:00', '2026-08-08 23:00:00'),
(123, 124, 'http://127.0.0.1:8000/assets/clients/img/product/xa-lach.jpg', 1, '2026-08-08 23:00:00', '2026-08-08 23:00:00'),
(124, 125, 'http://127.0.0.1:8000/assets/clients/img/product/ngo-ri.jpg', 1, '2026-08-08 23:00:00', '2026-08-08 23:00:00'),
(125, 126, 'http://127.0.0.1:8000/assets/clients/img/product/bap-my-tach-hat.jpg', 1, '2026-08-08 23:00:00', '2026-08-08 23:00:00'),
(126, 127, 'http://127.0.0.1:8000/assets/clients/img/product/ot-sung-do.jpg', 1, '2026-08-08 23:00:00', '2026-08-08 23:00:00'),
(127, 128, 'http://127.0.0.1:8000/assets/clients/img/product/hanh-tim.jpg', 1, '2026-08-08 23:00:00', '2026-08-08 23:00:00'),
(128, 129, 'http://127.0.0.1:8000/assets/clients/img/product/hanh-tay.jpg', 1, '2026-08-08 23:00:00', '2026-08-08 23:00:00'),
(129, 130, 'http://127.0.0.1:8000/assets/clients/img/product/gung-tuoi.jpg', 1, '2026-08-08 23:00:00', '2026-08-08 23:00:00'),
(130, 131, 'http://127.0.0.1:8000/assets/clients/img/product/me-vat.jpg', 1, '2026-08-08 23:00:00', '2026-08-08 23:00:00'),
(131, 132, 'http://127.0.0.1:8000/assets/clients/img/product/nuoc-mau-dua.jpg', 1, '2026-08-08 23:00:00', '2026-08-08 23:00:00'),
(132, 133, 'http://127.0.0.1:8000/assets/clients/img/product/sot-me-rang-tron-salad.jpg', 1, '2026-08-08 23:00:00', '2026-08-08 23:00:00'),
(133, 134, 'http://127.0.0.1:8000/assets/clients/img/product/nuoc-dua-tuoi.jpg', 1, '2026-08-08 23:00:00', '2026-08-08 23:00:00');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product_recipe`
--

DROP TABLE IF EXISTS `product_recipe`;
CREATE TABLE IF NOT EXISTS `product_recipe` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `recipe_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_recipe_recipe_id_foreign` (`recipe_id`),
  KEY `product_recipe_product_id_foreign` (`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=55 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `product_recipe`
--

INSERT INTO `product_recipe` (`id`, `recipe_id`, `product_id`, `created_at`, `updated_at`) VALUES
(12, 3, 79, NULL, NULL),
(11, 3, 81, NULL, NULL),
(36, 2, 134, NULL, NULL),
(35, 2, 127, NULL, NULL),
(13, 3, 76, NULL, NULL),
(14, 3, 75, NULL, NULL),
(15, 3, 73, NULL, NULL),
(34, 2, 21, NULL, NULL),
(23, 5, 74, NULL, NULL),
(22, 5, 75, NULL, NULL),
(20, 4, 69, NULL, NULL),
(31, 6, 133, NULL, NULL),
(30, 6, 79, NULL, NULL),
(29, 6, 124, NULL, NULL),
(32, 6, 13, NULL, NULL),
(33, 6, 14, NULL, NULL),
(37, 2, 132, NULL, NULL),
(38, 2, 4, NULL, NULL),
(39, 7, 25, NULL, NULL),
(40, 7, 124, NULL, NULL),
(41, 7, 79, NULL, NULL),
(42, 7, 133, NULL, NULL),
(43, 2, 119, NULL, NULL),
(44, 7, 126, NULL, NULL),
(45, 8, 103, NULL, NULL),
(46, 8, 134, NULL, NULL),
(47, 8, 4, NULL, NULL),
(48, 8, 132, NULL, NULL),
(49, 9, 29, NULL, NULL),
(50, 9, 119, NULL, NULL),
(51, 9, 132, NULL, NULL),
(52, 9, 4, NULL, NULL),
(53, 9, 127, NULL, NULL),
(54, 10, 133, NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product_reviews`
--

DROP TABLE IF EXISTS `product_reviews`;
CREATE TABLE IF NOT EXISTS `product_reviews` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `rating` tinyint UNSIGNED NOT NULL,
  `comment` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_visible` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_reviews_user_id_foreign` (`user_id`),
  KEY `product_reviews_product_id_foreign` (`product_id`),
  KEY `product_reviews_order_id_foreign` (`order_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `product_reviews`
--

INSERT INTO `product_reviews` (`id`, `user_id`, `product_id`, `order_id`, `rating`, `comment`, `is_visible`, `created_at`, `updated_at`) VALUES
(4, 1, 128, 50, 5, 'Hành tím thơm !', 1, '2026-08-17 08:19:50', '2026-08-17 08:19:50'),
(5, 1, 29, 50, 5, 'Cá quá tươi và ngon !', 1, '2026-08-17 08:20:02', '2026-08-17 08:20:02'),
(6, 1, 119, 50, 5, 'Tiêu thơm lắm', 1, '2026-08-17 08:20:14', '2026-08-17 08:20:14'),
(7, 1, 127, 50, 2, 'Ớt quá cay !', 1, '2026-08-17 08:20:25', '2026-08-17 08:20:25'),
(8, 14, 29, 51, 2, 'Cá tươi ngon lắm', 1, '2026-08-17 08:26:31', '2026-08-17 08:27:18'),
(9, 14, 127, 51, 5, 'ớt cay ngon', 1, '2026-08-17 08:26:39', '2026-08-17 08:26:39');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `recipes`
--

DROP TABLE IF EXISTS `recipes`;
CREATE TABLE IF NOT EXISTS `recipes` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `recipes_slug_unique` (`slug`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `recipes`
--

INSERT INTO `recipes` (`id`, `title`, `slug`, `image`, `is_active`, `created_at`, `updated_at`) VALUES
(2, 'Thịt kho tiêu', 'thit-kho-tieu', 'assets/clients/img/recipes/thit-kho-tieu.jpg', 1, '2026-08-03 03:58:24', '2026-08-09 11:08:48'),
(6, 'Salad trái cây', 'salad-trai-cay', 'assets/clients/img/recipes/salad-trai-cay.jpg', 1, '2026-08-08 05:14:02', '2026-08-09 11:08:48'),
(7, 'Salad ức gà', 'salad-uc-ga', 'assets/clients/img/recipes/salad-uc-ga.jpg', 1, '2026-08-09 06:26:21', '2026-08-09 11:08:48'),
(8, 'Đùi gà hầm', 'dui-ga-ham', 'assets/clients/img/recipes/dui-ga-ham.jpg', 1, '2026-08-09 06:27:52', '2026-08-09 11:08:48'),
(9, 'Cá basa kho tộ', 'ca-basa-kho-to', 'assets/clients/img/recipes/ca-basa-kho-to.jpg', 1, '2026-08-09 06:28:46', '2026-08-09 11:08:48');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_unique` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `roles`
--

INSERT INTO `roles` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'admin', '2026-07-07 20:38:48', '2026-07-07 20:38:48'),
(2, 'staff', '2026-07-07 20:38:48', '2026-07-07 20:38:48'),
(3, 'customer', '2026-07-07 20:38:48', '2026-07-07 20:38:48');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `role_permissions`
--

DROP TABLE IF EXISTS `role_permissions`;
CREATE TABLE IF NOT EXISTS `role_permissions` (
  `role_id` bigint UNSIGNED NOT NULL,
  `permission_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  KEY `role_permissions_role_id_foreign` (`role_id`),
  KEY `role_permissions_permission_id_foreign` (`permission_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `role_permissions`
--

INSERT INTO `role_permissions` (`role_id`, `permission_id`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, NULL),
(1, 2, NULL, NULL),
(1, 17, NULL, NULL),
(1, 4, NULL, NULL),
(1, 3, NULL, NULL),
(1, 6, NULL, NULL),
(2, 28, NULL, NULL),
(2, 4, NULL, NULL),
(2, 27, NULL, NULL),
(1, 7, NULL, NULL),
(2, 7, NULL, NULL),
(1, 32, NULL, NULL),
(1, 31, NULL, NULL),
(1, 30, NULL, NULL),
(1, 29, NULL, NULL),
(1, 13, NULL, NULL),
(1, 12, NULL, NULL),
(1, 11, NULL, NULL),
(1, 25, NULL, NULL),
(1, 24, NULL, NULL),
(1, 23, NULL, NULL),
(2, 44, NULL, NULL),
(1, 16, NULL, NULL),
(2, 41, NULL, NULL),
(1, 44, NULL, NULL),
(1, 14, NULL, NULL),
(1, 41, NULL, NULL),
(1, 43, NULL, NULL),
(1, 28, NULL, NULL),
(1, 27, NULL, NULL),
(1, 18, NULL, NULL),
(1, 15, NULL, NULL),
(1, 42, NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','active','blocked','deleted') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `role_id` bigint UNSIGNED NOT NULL,
  `activation_token` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_role_id_foreign` (`role_id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `password`, `status`, `role_id`, `activation_token`, `created_at`, `updated_at`) VALUES
(1, 'AdminUser', 'admin@gmail.com', '0966330634', '$2y$12$elbUjst9Y2Uavh9tOXJqEeTRxdAHDAgkxY62uGRQbvUxfMm9k31i.', 'active', 1, NULL, '2026-07-07 20:38:48', '2026-07-07 20:38:48'),
(2, 'Staff', 'staff@gmail.com', '0966330634', '$2y$12$NyLIY9A.zggdALDDqllt7usXOMj.WAZYn.dxnRCWe59sFloQJICeO', 'active', 2, NULL, '2026-07-07 20:38:48', '2026-07-17 14:52:43'),
(14, 'Customer', 'customer@gmail.com', '0966330655', '$2y$12$iKlQPax926kEUr272As/fum0u0lJhpxm1rP1G79TmYB4ezZozV2x.', 'active', 3, 'MmSe5nvBYwZ87E6Kl1OB5LFgGSkZxL833N7CcHnrm82MHc3PtLTTe3O56BLzKlKs', '2026-08-09 09:13:42', '2026-08-17 08:29:49');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user_addresses`
--

DROP TABLE IF EXISTS `user_addresses`;
CREATE TABLE IF NOT EXISTS `user_addresses` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `receiver_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `receiver_phone` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `province` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `district` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ward` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `street_address` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_addresses_user_id_foreign` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `user_addresses`
--

INSERT INTO `user_addresses` (`id`, `user_id`, `receiver_name`, `receiver_phone`, `province`, `district`, `ward`, `street_address`, `is_default`, `created_at`, `updated_at`) VALUES
(1, 3, 'MinhTai', '0966330634', 'Thành phố Hồ Chí Minh', 'Quận 12', 'Phường Thạnh Lộc', '65/13A', 0, '2026-07-08 03:15:23', '2026-07-26 05:49:23'),
(2, 3, 'MinhTai', '0966330634', 'Thành phố Hồ Chí Minh', 'Quận 5', 'Phường 9', '65/13A444444444444', 1, '2026-07-26 05:49:20', '2026-07-26 05:49:23'),
(3, 1, 'AdminUser', '0966330634', 'Thành phố Hồ Chí Minh', 'Quận 5', 'Phường 4', '65/13A', 1, '2026-08-05 07:36:16', '2026-08-05 07:36:16'),
(4, 14, 'Customer', '0966330655', 'Thành phố Hồ Chí Minh', 'Quận Gò Vấp', 'Phường 12', '65/13A444444444444', 1, '2026-08-16 15:56:22', '2026-08-16 15:56:22');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `wishlists`
--

DROP TABLE IF EXISTS `wishlists`;
CREATE TABLE IF NOT EXISTS `wishlists` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `wishlists_user_id_foreign` (`user_id`),
  KEY `wishlists_product_id_foreign` (`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=71 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `wishlists`
--

INSERT INTO `wishlists` (`id`, `user_id`, `product_id`) VALUES
(53, 7, 27),
(46, 7, 79),
(32, 3, 69),
(31, 3, 66),
(38, 3, 81),
(29, 3, 80),
(70, 14, 79);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
