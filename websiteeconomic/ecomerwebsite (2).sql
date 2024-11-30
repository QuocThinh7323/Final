-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 29, 2024 at 10:42 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ecomerwebsite`
--

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `status` enum('Active','Inactive') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `name`, `slug`, `status`, `created_at`, `updated_at`) VALUES
(3, 'SAMSUNG', 'samsung', 'Active', NULL, NULL),
(4, 'APPLE', 'apple', 'Active', NULL, NULL),
(5, 'Asus', 'asus', 'Active', NULL, NULL),
(11, 'Xiaomi', 'xiaomi', 'Active', NULL, NULL),
(12, 'Lenovo', 'lenovo', 'Active', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `quantity`, `price`, `total`, `created_at`, `updated_at`) VALUES
(47, 95, 35, 19, '677.00', '12863.00', '2024-10-12 10:46:47', '2024-10-13 07:21:48'),
(48, 95, 33, 3, '233.00', '699.00', '2024-10-12 10:46:50', '2024-10-13 07:27:30'),
(49, 95, 39, 8, '448.00', '3584.00', '2024-10-12 10:55:13', '2024-10-13 07:21:16'),
(50, 95, 37, 2, '676.00', '1352.00', '2024-10-12 11:46:56', '2024-10-13 07:00:51'),
(51, 95, 38, 1, '1.00', '1.00', '2024-10-13 07:14:40', '2024-10-13 07:14:40'),
(52, 95, 34, 1, '448.00', '448.00', '2024-10-13 07:27:43', '2024-10-13 07:27:43'),
(80, 96, 45, 1, '1200.00', '1200.00', '2024-10-31 05:45:39', '2024-10-31 05:45:39');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `status` enum('Active','Inactive') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Smartphone', 'smartphone', 'Active', NULL, NULL),
(4, 'Tablet', 'tablet', 'Active', NULL, NULL),
(5, 'Smartwatch', 'smartwatch', 'Active', NULL, NULL),
(9, 'Laptop', 'laptop', 'Active', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `contact_message`
--

CREATE TABLE `contact_message` (
  `message_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `user_email` varchar(100) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_message`
--

INSERT INTO `contact_message` (`message_id`, `name`, `user_email`, `subject`, `message`, `submitted_at`) VALUES
(1, 'Khiêm', 'khiemhuynh@gmai.com', '', 'aaaaa', '2024-09-19 07:19:54');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `rating` int(1) NOT NULL CHECK (`rating` between 1 and 5),
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `product_id`, `user_id`, `rating`, `comment`, `created_at`) VALUES
(1, 35, 64, 2, 'product ok \r\n', '2024-09-01 05:26:22'),
(8, 35, 70, 5, 'so gooddddd', '2024-09-11 07:31:48'),
(9, 50, 96, 5, 'adasfsf', '2024-10-20 17:35:25'),
(10, 35, 96, 5, 'Product so good', '2024-11-03 09:12:54');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2023_10_03_123426_create_admins_table', 1),
(6, '2023_10_03_130747_create_categories_table', 2),
(7, '2023_10_03_130946_create_brands_table', 2),
(8, '2023_10_03_132635_create_products_table', 3),
(9, '2023_10_03_135606_create_reviews_table', 4),
(10, '2023_10_04_080710_create_orders_table', 5),
(11, '2023_10_04_081411_create_order_details_table', 6);

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` bigint(20) NOT NULL,
  `title` varchar(255) NOT NULL,
  `avatar` varchar(200) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `sumary` text NOT NULL,
  `description` text NOT NULL,
  `newscategory_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `title`, `avatar`, `slug`, `sumary`, `description`, `newscategory_id`, `created_at`, `updated_at`, `user_id`) VALUES
(13, 'Samsung Galaxy S24 FE appears on Geekbench with Exynos 2400e chip', 'uploads/news/66c94e6785cb2TopZone.jpg', 'samsung-galaxy-s24-fe-appears-on-geekbench-with-exynos-2400e-chip', '                                                Samsung is expected to launch the successor to the Galaxy S23 FE in the coming months. Recently, MySmartPrice spotted the Korean variant of the Samsung Galaxy S24 FE on the Geekbench database.                                                                                                                                                                                                                                                                              ', '                                                The Geekbench database reveals that the upcoming South Korean variant of the Galaxy S24 FE with model number SM-S721N will be launched soon. The Galaxy S24 FE scored 1,625 points in the single-core test and 5,698 points in the multi-core test.\r\n\r\nThe phone has a motherboard with model number s5e9945 and a 10-core CPU architecture, clocked at a maximum speed of 3.11 GHz. The processor is paired with 8 GB of RAM, Samsung Xclipse 940 GPU. The Geekbench specs confirm that Samsung will equip the Galaxy S24 FE with a clocked variant of the Exynos 2400 chip.                                                                                                                        sss                                                                                                                                                                        ', 1, '2024-08-14 10:46:26', '2024-10-22 23:18:48', NULL),
(14, 'Instructions on how to update Windows 11 24H2 to help you experience it more smoothly and effectively', 'uploads/news/6717d01e8c52acap-nhat-windows-11-24h2-0-uiwn.jpg', 'instructions-on-how-to-update-windows-11-24h2-to-help-you-experience-it-more-smoothly-and-effectively', 'To ensure optimal performance and the best experience, updating the Windows operating system is indispensable. The latest version of Windows 11 24H2 brings many significant improvements in performance, interface and security features. Below I will guide you on how to update Windows 11 24H2 in the simplest and most effective way to help your computer always operate stably and securely.\r\n                        ', '1. How to update Windows 11 24H2\r\nIf you want to update to Windows 11 24H2, check if your computer is eligible for the update by checking if your computer can run Windows 11 24H2.\r\n\r\nAfter checking, if your laptop is eligible, follow the steps below to update Windows 11 24H2.\r\n\r\nStep 1: Open the Settings section on your computer to update Windows.\r\nStep 2: Right on the Settings homepage, click on Windows Update.\r\nStep 3: Now the Windows 11 24H2 version can be downloaded, click on Download and Install to install it.\r\nStep 4: After the download is complete, the system will automatically update. You can track its progress right in the Windows Update section.\r\n                        ', 5, '2024-10-22 23:17:34', '2024-10-22 23:17:34', NULL),
(15, 'Instructions on how to strikethrough text in Google Docs on phones and computers are extremely simple', 'uploads/news/6717d1767f206odc-sss.jpg', 'instructions-on-how-to-strikethrough-text-in-google-docs-on-phones-and-computers-are-extremely-simple', 'Google Docs is an extremely useful online office tool on phones and computers. In addition to basic features, Google Docs also supports many different text formats, including strikethrough. In this article, I will guide you how to strikethrough text in Google Docs to make your text more professional.\r\n                        ', '1. How to strikethrough text in Google Docs on your phone\r\nStep 1: Open the Google Docs file you want to edit > Highlight to select the text area you want to add strikethrough formatting > Select the letter A icon at the top as shown.\r\nStep 2: Scroll down and you will see the strikethrough format as shown, select it to format the text you want.\r\n2. How to strikethrough text in Google Docs on your computer\r\nThe way to strikethrough text in Google Docs on your computer is also very simple, follow these steps:\r\n\r\nStep 1: Open the Docs file you want to edit, highlight the area you want to strikethrough.\r\nStep 2: Select Format > Select Text > Scroll down and you will see Strikethrough, select it to format.\r\nWith the above instructions, you can easily create impressive text paragraphs with the strikethrough effect in Google Docs. With just a few simple steps, your article will become more professional and attractive. Try it now to experience it!\r\n\r\nIf you are looking for a laptop suitable for your study and office work needs, do not miss the modern models with powerful performance at The Gioi Di Dong. With a variety of options, reasonable prices and reputable warranty policies, this is the ideal place for you to own your dream laptop. Click the orange button below to explore and order today!\r\n                        ', 5, '2024-10-22 23:23:18', '2024-10-22 23:23:18', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `newscategories`
--

CREATE TABLE `newscategories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `status` enum('Active','Innactive') NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `newscategories`
--

INSERT INTO `newscategories` (`id`, `name`, `slug`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Product Reviews', 'product-reviews', 'Active', NULL, NULL),
(3, 'Newest product', 'newest-product', 'Active', NULL, NULL),
(4, 'Technology events', 'technology-events', 'Active', NULL, NULL),
(5, 'Tips for use', 'tips-for-use', 'Active', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `address` text NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `status` enum('Processing','Confirmed','Shipping','Delivered','Cancelled') NOT NULL DEFAULT 'Processing',
  `payment_method` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `firstname`, `lastname`, `address`, `phone`, `email`, `status`, `payment_method`, `created_at`, `updated_at`) VALUES
(15, 70, 'Khiem', 'Huynh', 'Hau Giang ', '0962465326', 'khiemhuynh@gmai.com', 'Delivered', '', '2024-08-28 07:47:51', '2024-08-28 07:47:51'),
(16, 64, 'Khiem', 'Huynh', 'Hau Giang province', '0473839274', 'khiemhuynh@gmai.com', 'Delivered', '', '2024-09-01 04:12:35', '2024-09-01 04:12:35'),
(17, 64, 'Phuoc ', 'Toan', 'Vinh Long province', '0262548323', 'thinhptqgbc210227@fpt.edu.vn', 'Delivered', '', '2024-09-05 04:43:42', '2024-09-05 04:43:42'),
(18, 70, 'Khiem', 'Huynh', 'Vinh Long province', '0473839274', 'thinhptqgbc210227@fpt.edu.vn', 'Delivered', '', '2024-09-08 18:38:41', '2024-09-08 18:38:41'),
(19, 70, 'Quoc', 'Thinh', 'Vinh Long province', '0962465326', 'thinhptqgbc210227@fpt.edu.vn', 'Confirmed', '', '2024-09-17 08:28:32', '2024-09-17 08:28:32'),
(29, 70, 'Khiem', 'Huynh', 'Vinh Long province', '0894373223', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', '', '2024-09-22 16:31:06', '2024-09-22 16:31:06'),
(30, 70, 'Khiem', 'Huynh', 'Vinh Long province', '0962465326', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', '', '2024-09-22 16:51:47', '2024-09-22 16:51:47'),
(31, 70, 'Khiem', 'Huynh', 'Vinh Long province', '0894373223', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', '', '2024-09-22 17:21:48', '2024-09-22 17:21:48'),
(32, 71, 'Quoc', 'Huynh', 'Vinh Long province', '0962465326', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', '', '2024-09-22 17:25:58', '2024-09-22 17:25:58'),
(33, 71, 'Khiem', 'Huynh', 'Vinh Long province', '0962465326', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', '', '2024-09-23 02:57:45', '2024-09-23 02:57:45'),
(34, 71, 'Khiem', 'Huynh', 'Vinh Long province', '0262548323', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', '', '2024-09-23 03:02:04', '2024-09-23 03:02:04'),
(35, 71, 'Khiem', 'Huynh', 'Vinh Long province', '0262548323', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', '', '2024-09-23 03:02:57', '2024-09-23 03:02:57'),
(36, 71, 'Quoc', 'Thanh', 'Vinh Long province', '0962465326', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', '', '2024-09-23 03:07:09', '2024-09-23 03:07:09'),
(37, 71, 'Quoc', 'thinh', 'Vinh Long province', '0352997883', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', '', '2024-09-23 03:13:14', '2024-09-23 03:13:14'),
(38, 71, 'Quoc', 'Huynh', 'Vinh Long province', '0262548323', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', '', '2024-09-23 03:14:31', '2024-09-23 03:14:31'),
(39, 71, 'Quoc', 'Thinh', 'Vinh Long province', '0473839274', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', '', '2024-09-23 03:17:03', '2024-09-23 03:17:03'),
(40, 71, 'Viet', 'Toan', 'Vinh Long province', '0352997883', 'thinhptqgbc210227@fpt.edu.vn', 'Delivered', '', '2024-10-23 04:54:55', '2024-10-23 04:54:55'),
(41, 95, 'tusss', 'sswww', 'Vinh Long province', '0473839274', 'thinhphan1123@gmail.com', 'Processing', 'bank_transfer', '2024-09-29 06:39:20', '2024-09-29 06:39:20'),
(42, 95, 'aaaa', 'aaaa', 'Vinh Long province', '0894373223', 'thinhphan1123@gmail.com', 'Processing', 'bank_transfer', '2024-09-29 06:42:12', '2024-09-29 06:42:12'),
(43, 95, 'Khiem', 'qqqq', 'qqqq', '0894373223', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', 'bank_transfer', '2024-09-29 07:02:57', '2024-09-29 07:02:57'),
(45, 95, 'Khiem', 'Huynh', 'Vinh Long province', '0962465326', 'phanthinh111@gmail.com', 'Processing', 'bank_transfer', '2024-10-01 06:09:05', '2024-10-01 06:09:05'),
(46, 95, 'Quoc', 'Thanh', 'Vinh Long province', '0962465326', 'thinhptqgbc210227@fpt.edu.vn', 'Delivered', 'bank_transfer', '2024-10-01 06:13:05', '2024-10-01 06:13:05'),
(47, 95, 'Viet', 'Thanh', 'Vinh Long province', '0962465326', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', 'bank_transfer', '2024-10-01 06:17:24', '2024-10-01 06:17:24'),
(48, 95, 'Khiem', 'Huynh', 'Vinh Long province', '0262548323', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', 'bank_transfer', '2024-10-01 06:23:32', '2024-10-01 06:23:32'),
(49, 95, 'Khiem', 'Thanh', 'Vinh Long province', '0894373223', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', 'cash', '2024-10-01 06:44:24', '2024-10-01 06:44:24'),
(50, 95, 'aaaa', 'aaa', 'Vinh Long province', '0262548323', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', 'bank_transfer', '2024-10-01 07:09:20', '2024-10-01 07:09:20'),
(51, 95, 'Khiem', 'Huynh', 'Vinh Long province', '0962465326', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', 'bank_transfer', '2024-10-01 07:16:34', '2024-10-01 07:16:34'),
(52, 95, 'Khiem', 'Thanh', 'Vinh Long province', '0473839274', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', 'cash', '2024-10-01 07:33:47', '2024-10-01 07:33:47'),
(53, 95, 'Viet', 'Thanh', 'Vinh Long province', '0473839274', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', 'bank_transfer', '2024-10-01 07:35:07', '2024-10-01 07:35:07'),
(54, 95, 'Khiem', 'Huynh', 'Vinh Long province', '0262548323', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', 'bank_transfer', '2024-10-01 07:43:22', '2024-10-01 07:43:22'),
(55, 95, 'Khiem', 'Huynh', 'Vinh Long province', '0473839274', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', 'bank_transfer', '2024-10-01 08:09:44', '2024-10-01 08:09:44'),
(56, 95, 'Khiem', 'Thanh', 'Vinh Long province', '0473839274', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', 'bank_transfer', '2024-10-01 08:10:22', '2024-10-01 08:10:22'),
(57, 95, 'Khiem', 'Huynh', 'Vinh Long province', '0352997883', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', 'bank_transfer', '2024-10-01 08:11:24', '2024-10-01 08:11:24'),
(58, 95, 'Khiem', 'Thanh', 'Vinh Long province', '0262548323', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', 'bank_transfer', '2024-10-01 08:21:36', '2024-10-01 08:21:36'),
(59, 95, 'Khiem', 'Toan', 'Vinh Long province', '0473839274', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', 'bank_transfer', '2024-10-01 08:22:28', '2024-10-01 08:22:28'),
(60, 95, 'Khiem', 'Huynh', 'Vinh Long province', '0262548323', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', 'cash', '2024-10-01 08:33:44', '2024-10-01 08:33:44'),
(61, 95, 'Quoc', 'Thinh', 'Vinh Long province', '0352997883', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', 'cash', '2024-10-10 05:42:11', '2024-10-10 05:42:11'),
(62, 95, 'Quoc', 'Thanh', 'Vinh Long province', '0352997883', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', 'cash', '2024-10-10 05:42:43', '2024-10-10 05:42:43'),
(63, 95, 'Quoc', 'Thinh', 'Vinh Long province', '0262548323', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', 'cash', '2024-10-10 05:48:49', '2024-10-10 05:48:49'),
(64, 95, 'Khiem', 'Huynh', 'Vinh Long province', '0352997883', 'thinhptqgbc210227@fpt.edu.vn', 'Confirmed', 'cash', '2024-10-10 05:51:35', '2024-10-10 05:51:35'),
(65, 95, 'Quoc', 'Thinh', 'Vinh Long province', '0262548323', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', 'cash', '2024-10-10 06:02:40', '2024-10-10 06:02:40'),
(66, 95, 'Khiem', 'Huynh', 'Vinh Long province', '0262548323', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', 'cash', '2024-10-10 06:12:06', '2024-10-10 06:12:06'),
(67, 95, 'Khiem', 'Huynh', 'Vinh Long province', '0352997883', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', 'cash', '2024-10-10 06:15:07', '2024-10-10 06:15:07'),
(68, 95, 'Khiem', 'Thanh', 'Vinh Long province', '0473839274', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', 'bank_transfer', '2024-10-10 06:19:20', '2024-10-10 06:19:20'),
(69, 95, 'Khiem', 'Huynh', 'Vinh Long province', '0473839274', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', 'cash', '2024-10-10 06:21:05', '2024-10-10 06:21:05'),
(70, 95, 'Quoc', 'Thinh', 'Vinh Long province', '0894373223', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', 'cash', '2024-10-10 06:29:47', '2024-10-10 06:29:47'),
(71, 95, 'Quoc', 'Thinh', 'Vinh Long province', '0962465326', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', 'cash', '2024-10-10 06:34:09', '2024-10-10 06:34:09'),
(72, 95, 'Quoc', 'Thinh', 'Vinh Long province', '0894373223', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', 'cash', '2024-10-10 06:45:26', '2024-10-10 06:45:26'),
(73, 95, 'Phuc', 'Ngo', 'Vinh Long province', '0352997883', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', 'cash', '2024-10-10 06:50:51', '2024-10-10 06:50:51'),
(74, 95, 'Phuoc ', 'Toan', 'Vinh Long province', '0894373223', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', 'cash', '2024-10-10 07:21:08', '2024-10-10 07:21:08'),
(75, 95, 'Viet', 'Thinh', 'Vinh Long province', '0352997883', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', 'cash', '2024-10-10 07:25:38', '2024-10-10 07:25:38'),
(76, 95, 'Phuoc ', 'Huynh', 'Vinh Long province', '0473839274', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', 'cash', '2024-10-10 07:27:31', '2024-10-10 07:27:31'),
(77, 95, 'Khiem', 'Huynh', 'Vinh Long province', '0352997883', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', 'cash', '2024-10-10 07:31:57', '2024-10-10 07:31:57'),
(78, 95, 'Quoc', 'Huynh', 'Vinh Long province', '0473839274', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', 'cash', '2024-10-10 07:35:36', '2024-10-10 07:35:36'),
(79, 95, 'Khiem', 'Huynh', 'Vinh Long province', '0962465326', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', 'cash', '2024-10-10 07:40:34', '2024-10-10 07:40:34'),
(80, 95, 'Khiem', 'Huynh', 'Vinh Long province', '0352997883', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', 'cash', '2024-10-10 07:50:33', '2024-10-10 07:50:33'),
(81, 95, 'Khiem', 'Thanh', 'Vinh Long province', '0473839274', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', 'cash', '2024-10-10 08:04:42', '2024-10-10 08:04:42'),
(92, 95, 'Quoc', 'Thanh', 'Vinh Long province', '0262548323', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', 'cash', '2024-10-11 09:14:31', '2024-10-11 09:14:31'),
(93, 95, 'Khiem', 'Huynh', 'Vinh Long province', '0473839274', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', 'cash', '2024-10-11 09:15:58', '2024-10-11 09:15:58'),
(94, 95, 'Khiem', 'Huynh', 'Vinh Long province', '0262548323', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', 'cash', '2024-10-11 09:19:33', '2024-10-11 09:19:33'),
(95, 95, 'qwdwqdw', 'thinh', 'Vinh Long province', '0262548323', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', 'cash', '2024-10-11 09:21:34', '2024-10-11 09:21:34'),
(98, 95, 'cbvdfbdfb', 'bdfhdfh', 'dfhdfh', 'dhdfgh', 'dfhdh@gmail.com', 'Processing', 'cash', '2024-10-11 09:57:25', '2024-10-11 09:57:25'),
(99, 95, 'grags', 'gdfgdfg', 'gsdfgsdg', 'sdfgdfgs', 'sdgdg@gmail.com', 'Processing', 'cash', '2024-10-11 10:14:47', '2024-10-11 10:14:47'),
(100, 95, 'zsdgdsf', 'fdgfdg', 'dfg', 'g', 'f@gmail.com', 'Processing', 'cash', '2024-10-11 10:18:31', '2024-10-11 10:18:31'),
(101, 95, 'dfg', 'dfh', 'f', 'f', 'f@gmail.com', 'Processing', 'cash', '2024-10-11 10:21:43', '2024-10-11 10:21:43'),
(105, 95, 'dfbgdh', 'dhdghgd', 'dhfh', 'dhdfh', 'f@gmail.com', 'Processing', 'cash', '2024-10-11 10:57:36', '2024-10-11 10:57:36'),
(106, 95, 'dfhdfhf', 'dfhdfh', 'dfhdhfh', 'dhfdhd', 'f@gmail.com', 'Processing', 'cash', '2024-10-11 11:02:34', '2024-10-11 11:02:34'),
(107, 95, 'Khiem', 'Huynh', 'Vinh Long province', '0352997883', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', 'cash', '2024-10-11 14:19:07', '2024-10-11 14:19:07'),
(108, 95, 'Quoc', 'Thinh', 'Vinh Long province', '0962465326', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', 'bank_transfer', '2024-10-11 15:47:46', '2024-10-11 15:47:46'),
(109, 95, 'Quoc', 'Thinh', 'Vinh Long province', '0962465326', 'thinhptqgbc210227@fpt.edu.vn', 'Delivered', 'cash', '2024-10-11 15:48:11', '2024-10-11 15:48:11'),
(110, 95, 'Khiem', 'Huynh', 'Vinh Long province', '0473839274', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', 'cash', '2024-10-12 04:35:12', '2024-10-12 04:35:12'),
(111, 95, 'Quoc', 'Thinh', 'Vinh Long province', '0262548323', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', 'cash', '2024-10-12 04:45:20', '2024-10-12 04:45:20'),
(112, 95, 'Quoc', 'Huynh', 'Vinh Long province', '0352997883', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', 'cash', '2024-10-12 04:49:49', '2024-10-12 04:49:49'),
(113, 95, 'Khiem', 'Huynh', 'Vinh Long province', '0894373223', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', 'cash', '2024-10-12 04:54:03', '2024-10-12 04:54:03'),
(114, 95, 'Khiem', 'Thanh', 'Vinh Long province', '0894373223', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', 'cash', '2024-10-12 04:54:59', '2024-10-12 04:54:59'),
(115, 95, 'Quoc', 'Thinh', 'Vinh Long province', '0352997883', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', 'cash', '2024-10-12 05:15:06', '2024-10-12 05:15:06'),
(116, 95, 'Phuoc ', 'Thinh', 'Vinh Long province', '0962465326', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', 'cash', '2024-10-12 05:18:37', '2024-10-12 05:18:37'),
(117, 95, 'Khiem', 'Thinh', 'Vinh Long province', '0473839274', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', 'cash', '2024-10-12 05:41:39', '2024-10-12 05:41:39'),
(118, 95, 'Quoc', 'Thinh', 'Vinh Long province', '0473839274', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', 'cash', '2024-10-12 05:54:47', '2024-10-12 05:54:47'),
(119, 95, 'Khiem', 'Huynh', 'Vinh Long province', '0262548323', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', 'cash', '2024-10-12 06:01:09', '2024-10-12 06:01:09'),
(120, 95, 'Khiem', 'Toan', 'Vinh Long province', '0962465326', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', 'cash', '2024-10-12 06:03:48', '2024-10-12 06:03:48'),
(121, 95, 'Khiem', 'Huynh', 'Vinh Long province', '0262548323', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', 'cash', '2024-10-12 06:05:27', '2024-10-12 06:05:27'),
(122, 95, 'qwdwqdw', 'thinh', 'Vinh Long province', '0473839274', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', 'cash', '2024-10-12 06:07:29', '2024-10-12 06:07:29'),
(123, 95, 'Quoc', 'Thinh', 'Vinh Long province', '0894373223', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', 'cash', '2024-10-12 06:08:34', '2024-10-12 06:08:34'),
(124, 95, 'Quoc', 'Thinh', 'Vinh Long province', '0352997883', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', 'cash', '2024-10-12 06:26:53', '2024-10-12 06:26:53'),
(125, 95, 'Quoc', 'Thinh', 'Vinh Long province', '0894373223', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', 'cash', '2024-10-12 06:28:14', '2024-10-12 06:28:14'),
(126, 95, 'Quoc', 'Thinh', 'Vinh Long province', '0894373223', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', 'cash', '2024-10-12 06:43:16', '2024-10-12 06:43:16'),
(127, 95, 'Quoc', 'Thinh', 'Vinh Long province', '0894373223', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', 'cash', '2024-10-12 06:52:51', '2024-10-12 06:52:51'),
(128, 95, 'Khiem', 'Thinh', 'Vinh Long province', '0262548323', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', 'cash', '2024-10-12 06:53:38', '2024-10-12 06:53:38'),
(129, 95, 'Quoc', 'Thinh', 'Vinh Long province', '0262548323', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', 'cash', '2024-10-12 07:19:03', '2024-10-12 07:19:03'),
(130, 95, 'Khiem', 'Huynh', 'Vinh Long province', '0352997883', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', 'cash', '2024-10-12 07:23:40', '2024-10-12 07:23:40'),
(131, 95, 'Khiem', 'Huynh', 'Vinh Long province', '0352997883', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', 'cash', '2024-10-12 07:27:49', '2024-10-12 07:27:49'),
(132, 95, 'Khiem', 'Huynh', 'Vinh Long province', '0962465326', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', 'cash', '2024-10-12 07:34:06', '2024-10-12 07:34:06'),
(133, 95, 'Khiem', 'Huynh', 'Vinh Long province', '0473839274', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', 'cash', '2024-10-12 07:39:02', '2024-10-12 07:39:02'),
(134, 95, 'Khiem', 'Huynh', 'Vinh Long province', '0262548323', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', 'cash', '2024-10-12 07:40:13', '2024-10-12 07:40:13'),
(135, 95, 'Khiem', 'Huynh', 'Vinh Long province', '0473839274', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', 'cash', '2024-10-12 07:44:33', '2024-10-12 07:44:33'),
(136, 95, 'Khiem', 'Huynh', 'Vinh Long province', '0962465326', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', 'cash', '2024-10-12 07:45:18', '2024-10-12 07:45:18'),
(137, 95, 'Khiem', 'Huynh', 'Vinh Long province', '0962465326', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', 'cash', '2024-10-12 07:46:32', '2024-10-12 07:46:32'),
(138, 95, 'Khiem', 'Huynh', 'Vinh Long province', '0894373223', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', 'cash', '2024-10-12 07:49:33', '2024-10-12 07:49:33'),
(139, 95, 'Khiem', 'Huynh', 'hau Giang province', '3635636536', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', 'cash', '2024-10-12 07:53:45', '2024-10-12 07:53:45'),
(140, 95, 'Quoc', 'Huynh', 'Vinh Long province', '0352997883', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', 'cash', '2024-10-12 07:55:28', '2024-10-12 07:55:28'),
(141, 95, 'Khiem', 'Huynh', 'Vinh Long province', '0962465326', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', 'cash', '2024-10-12 08:03:16', '2024-10-12 08:03:16'),
(142, 95, 'fdgfdhdd', 'hdfhdh', 'dhfh', 'fhdh', 'gfdsgsf@gmail.com', 'Processing', 'cash', '2024-10-12 08:07:12', '2024-10-12 08:07:12'),
(143, 95, 'rthtfyd', 'gsdsgs', 'sdgsdfg', 'sdgsdg', 'f@gmail.com', 'Processing', 'cash', '2024-10-12 08:10:50', '2024-10-12 08:10:50'),
(144, 95, 'sdgsdfg', 'sdgsdg', 'sgdsdg', 'sdgsdg', 'f@gmail.com', 'Processing', 'cash', '2024-10-12 08:20:26', '2024-10-12 08:20:26'),
(145, 95, 'Khiem', 'Huynh', 'Vinh Long province', '0962465326', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', 'bank_transfer', '2024-10-12 08:22:28', '2024-10-12 08:22:28'),
(146, 96, 'v', 'Thanh', 'Vinh Long province', '0352997883', 'vietthanh161123@gmail.com', 'Delivered', 'cash', '2024-10-13 09:11:27', '2024-10-13 09:11:27'),
(147, 96, 'aaaaa', 'bbbbb', 'wdawwdaw', '90922886322', 'thinhptqgbc210227@fpt.edu.vn', 'Delivered', 'cash', '2024-10-20 15:53:32', '2024-10-20 15:53:32'),
(148, 96, 'aaaa', 'eeee', 'Nguyen Van Linh street, Hung Loi ward', '4758697812', 'thinhptqgbc210227@fpt.edu.vn', 'Cancelled', 'cash', '2024-10-20 15:55:39', '2024-10-20 15:55:39'),
(149, 96, 'asdads', 'dsasdas', 'Vinh Long province', '4364575698', 'thinhptqgbc210227@fpt.edu.vn', 'Cancelled', 'bank_transfer', '2024-10-20 17:36:28', '2024-10-20 17:36:28'),
(150, 96, 'aaa', 'aaaa', 'bbbbb', '0739372321', 'phanbd@gmail.com', 'Processing', 'bank_transfer', '2024-10-21 06:37:31', '2024-10-21 06:37:31'),
(151, 96, 'Quoc', 'Thinh', 'Nguyen Van Linh,NInh Kieu,Can Tho', '0739786383', 'febjefb@gmail.com', 'Processing', 'bank_transfer', '2024-10-21 11:55:29', '2024-10-21 11:55:29'),
(152, 96, 'aaaaa', 'aaaaa', 'bbbbbb', '073937931', 'ebbeu@gecnje', 'Processing', 'bank_transfer', '2024-10-21 12:01:28', '2024-10-21 12:01:28'),
(153, 96, 'aaaaa', 'aaaaa', 'Nguyen Van Linh,NInh Kieu,Can Tho', '0739786383', 'febjefb@gmail.com', 'Processing', 'cash', '2024-10-21 12:26:19', '2024-10-21 12:26:19'),
(154, 96, 'aaaaa', 'aaaaa', 'Nguyen Van Linh,NInh Kieu,Can Tho', '0739786383', 'febjefb@gmail.com', 'Processing', 'cash', '2024-10-21 12:37:16', '2024-10-21 12:37:16'),
(155, 96, 'Quoc', 'Thinh', 'Nguyen Van Linh,NInh Kieu,Can Tho', '0739786383', 'febjefb@gmail.com', 'Processing', 'cash', '2024-10-21 13:22:42', '2024-10-21 13:22:42'),
(156, 96, 'Quoc', 'Thinh', 'Nguyen Van Linh,NInh Kieu,Can Tho', '0739786383', 'febjefb@gmail.com', 'Processing', 'cash', '2024-10-21 13:24:49', '2024-10-21 13:24:49'),
(157, 96, 'Viet', 'Thanh', 'Nguyen Van Linh street, Hung Loi ward', '0894373223', 'thinhptqgbc210227@fpt.edu.vn', 'Processing', 'cash', '2024-10-30 18:59:22', '2024-10-30 18:59:22'),
(158, 96, 'Viet', 'Thanh', 'Nguyen Van Linh street, Hung Loi ward', '0473839274', 'vietthanh161123@gmail.com', 'Processing', 'cash', '2024-10-30 19:03:43', '2024-10-30 19:03:43'),
(159, 96, 'Viet', 'Thanh', 'Vinh Long province', '0262548323', 'thinhptqgbc210227@fpt.edu.vn', 'Delivered', 'cash', '2024-11-03 10:24:59', '2024-11-03 10:24:59');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` int(11) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `price` double NOT NULL,
  `qty` tinyint(4) NOT NULL,
  `total` double NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `price`, `qty`, `total`, `created_at`, `updated_at`) VALUES
(11, 15, 35, 557, 3, 1671, '2024-08-28 07:47:51', '2024-08-28 07:47:51'),
(12, 16, 35, 557, 2, 1114, '2024-09-01 04:12:35', '2024-09-01 04:12:35'),
(13, 16, 34, 436, 1, 436, '2024-09-01 04:12:35', '2024-09-01 04:12:35'),
(14, 17, 38, 995, 6, 5970, '2024-09-05 04:43:42', '2024-09-05 04:43:42'),
(15, 18, 35, 557, 8, 4456, '2024-09-08 18:38:41', '2024-09-08 18:38:41'),
(16, 18, 34, 436, 5, 2180, '2024-09-08 18:38:41', '2024-09-08 18:38:41'),
(17, 18, 38, 995, 1, 995, '2024-09-08 18:38:41', '2024-09-08 18:38:41'),
(19, 33, 33, 217, 1, 217, '2024-09-23 02:57:45', '2024-09-23 02:57:45'),
(20, 37, 39, 448, 1, 448, '2024-09-23 03:13:14', '2024-09-23 03:13:14'),
(21, 38, 33, 217, 1, 217, '2024-09-23 03:14:31', '2024-09-23 03:14:31'),
(22, 38, 37, 674, 1, 674, '2024-09-23 03:14:31', '2024-09-23 03:14:31'),
(23, 38, 38, 995, 1, 995, '2024-09-23 03:14:31', '2024-09-23 03:14:31'),
(24, 39, 36, 995, 1, 995, '2024-09-23 03:17:03', '2024-09-23 03:17:03'),
(25, 40, 37, 674, 1, 674, '2024-10-23 04:54:55', '2024-10-23 04:54:55'),
(26, 41, 35, 557, 1, 557, '2024-09-29 06:39:20', '2024-09-29 06:39:20'),
(27, 42, 37, 674, 1, 674, '2024-09-29 06:42:12', '2024-09-29 06:42:12'),
(28, 43, 35, 557, 1, 557, '2024-09-29 07:02:57', '2024-09-29 07:02:57'),
(29, 45, 35, 557, 1, 557, '2024-10-01 06:09:05', '2024-10-01 06:09:05'),
(30, 46, 35, 557, 1, 557, '2024-10-01 06:13:05', '2024-10-01 06:13:05'),
(31, 47, 39, 448, 1, 448, '2024-10-01 06:17:24', '2024-10-01 06:17:24'),
(32, 48, 35, 557, 1, 557, '2024-10-01 06:23:32', '2024-10-01 06:23:32'),
(33, 49, 35, 557, 1, 557, '2024-10-01 06:44:24', '2024-10-01 06:44:24'),
(34, 50, 39, 448, 1, 448, '2024-10-01 07:09:20', '2024-10-01 07:09:20'),
(35, 51, 35, 557, 1, 557, '2024-10-01 07:16:34', '2024-10-01 07:16:34'),
(36, 52, 35, 557, 1, 557, '2024-10-01 07:33:47', '2024-10-01 07:33:47'),
(37, 53, 35, 557, 1, 557, '2024-10-01 07:35:07', '2024-10-01 07:35:07'),
(38, 54, 35, 557, 3, 1671, '2024-10-01 07:43:22', '2024-10-01 07:43:22'),
(39, 55, 33, 217, 1, 217, '2024-10-01 08:09:44', '2024-10-01 08:09:44'),
(40, 56, 33, 217, 1, 217, '2024-10-01 08:10:22', '2024-10-01 08:10:22'),
(41, 57, 38, 995, 1, 995, '2024-10-01 08:11:24', '2024-10-01 08:11:24'),
(42, 58, 37, 674, 1, 674, '2024-10-01 08:21:36', '2024-10-01 08:21:36'),
(43, 59, 37, 674, 1, 674, '2024-10-01 08:22:28', '2024-10-01 08:22:28'),
(44, 60, 35, 557, 1, 557, '2024-10-01 08:33:44', '2024-10-01 08:33:44'),
(45, 60, 33, 217, 1, 217, '2024-10-01 08:33:44', '2024-10-01 08:33:44'),
(46, 60, 34, 436, 1, 436, '2024-10-01 08:33:44', '2024-10-01 08:33:44'),
(47, 61, 35, 557, 2, 1114, '2024-10-10 05:42:11', '2024-10-10 05:42:11'),
(48, 61, 33, 217, 2, 434, '2024-10-10 05:42:11', '2024-10-10 05:42:11'),
(49, 62, 35, 557, 2, 1114, '2024-10-10 05:42:43', '2024-10-10 05:42:43'),
(50, 62, 33, 217, 2, 434, '2024-10-10 05:42:43', '2024-10-10 05:42:43'),
(51, 63, 35, 557, 2, 1114, '2024-10-10 05:48:49', '2024-10-10 05:48:49'),
(52, 63, 33, 217, 2, 434, '2024-10-10 05:48:49', '2024-10-10 05:48:49'),
(53, 64, 35, 557, 2, 1114, '2024-10-10 05:51:35', '2024-10-10 05:51:35'),
(54, 64, 33, 217, 2, 434, '2024-10-10 05:51:35', '2024-10-10 05:51:35'),
(55, 65, 35, 557, 2, 1114, '2024-10-10 06:02:40', '2024-10-10 06:02:40'),
(56, 65, 33, 217, 2, 434, '2024-10-10 06:02:40', '2024-10-10 06:02:40'),
(57, 66, 35, 557, 2, 1114, '2024-10-10 06:12:06', '2024-10-10 06:12:06'),
(58, 66, 33, 217, 2, 434, '2024-10-10 06:12:06', '2024-10-10 06:12:06'),
(59, 67, 35, 557, 2, 1114, '2024-10-10 06:15:07', '2024-10-10 06:15:07'),
(60, 67, 33, 217, 2, 434, '2024-10-10 06:15:07', '2024-10-10 06:15:07'),
(61, 68, 35, 557, 2, 1114, '2024-10-10 06:19:20', '2024-10-10 06:19:20'),
(62, 68, 33, 217, 2, 434, '2024-10-10 06:19:20', '2024-10-10 06:19:20'),
(63, 69, 35, 557, 2, 1114, '2024-10-10 06:21:05', '2024-10-10 06:21:05'),
(64, 69, 33, 217, 2, 434, '2024-10-10 06:21:05', '2024-10-10 06:21:05'),
(65, 70, 35, 557, 2, 1114, '2024-10-10 06:29:47', '2024-10-10 06:29:47'),
(66, 70, 33, 217, 2, 434, '2024-10-10 06:29:47', '2024-10-10 06:29:47'),
(67, 72, 35, 557, 2, 1114, '2024-10-10 06:45:26', '2024-10-10 06:45:26'),
(68, 72, 33, 217, 2, 434, '2024-10-10 06:45:26', '2024-10-10 06:45:26'),
(69, 73, 35, 557, 2, 1114, '2024-10-10 06:50:51', '2024-10-10 06:50:51'),
(70, 73, 33, 217, 2, 434, '2024-10-10 06:50:51', '2024-10-10 06:50:51'),
(71, 74, 35, 557, 2, 1114, '2024-10-10 07:21:08', '2024-10-10 07:21:08'),
(72, 74, 33, 217, 2, 434, '2024-10-10 07:21:08', '2024-10-10 07:21:08'),
(73, 75, 35, 557, 2, 1114, '2024-10-10 07:25:38', '2024-10-10 07:25:38'),
(74, 75, 33, 217, 2, 434, '2024-10-10 07:25:38', '2024-10-10 07:25:38'),
(75, 76, 35, 557, 1, 557, '2024-10-10 07:27:31', '2024-10-10 07:27:31'),
(76, 77, 35, 557, 1, 557, '2024-10-10 07:31:57', '2024-10-10 07:31:57'),
(77, 78, 35, 557, 1, 557, '2024-10-10 07:35:36', '2024-10-10 07:35:36'),
(78, 79, 35, 557, 3, 1671, '2024-10-10 07:40:34', '2024-10-10 07:40:34'),
(79, 80, 35, 557, 1, 557, '2024-10-10 07:50:33', '2024-10-10 07:50:33'),
(80, 81, 35, 557, 1, 557, '2024-10-10 08:04:42', '2024-10-10 08:04:42'),
(90, 95, 33, 454, 2, 908, '2024-10-11 09:59:48', '2024-10-11 09:59:48'),
(94, 105, 35, 677, 2, 1354, '2024-10-11 10:57:36', '2024-10-11 10:57:36'),
(95, 105, 39, 448, 1, 448, '2024-10-11 10:57:36', '2024-10-11 10:57:36'),
(96, 106, 35, 677, 2, 1354, '2024-10-11 11:02:34', '2024-10-11 11:02:34'),
(97, 106, 39, 448, 1, 448, '2024-10-11 11:02:34', '2024-10-11 11:02:34'),
(98, 107, 35, 677, 2, 1354, '2024-10-11 14:19:07', '2024-10-11 14:19:07'),
(99, 107, 39, 448, 1, 448, '2024-10-11 14:19:07', '2024-10-11 14:19:07'),
(100, 108, 39, 448, 8, 3584, '2024-10-11 15:47:46', '2024-10-11 15:47:46'),
(101, 109, 39, 448, 8, 3584, '2024-10-11 15:48:11', '2024-10-11 15:48:11'),
(102, 110, 39, 448, 8, 3584, '2024-10-12 04:35:12', '2024-10-12 04:35:12'),
(103, 110, 36, 1, 2, 2, '2024-10-12 04:35:12', '2024-10-12 04:35:12'),
(104, 110, 34, 448, 1, 448, '2024-10-12 04:35:12', '2024-10-12 04:35:12'),
(105, 110, 37, 676, 1, 676, '2024-10-12 04:35:12', '2024-10-12 04:35:12'),
(106, 110, 35, 677, 1, 677, '2024-10-12 04:35:12', '2024-10-12 04:35:12'),
(107, 111, 39, 448, 8, 3584, '2024-10-12 04:45:20', '2024-10-12 04:45:20'),
(108, 111, 36, 1, 2, 2, '2024-10-12 04:45:20', '2024-10-12 04:45:20'),
(109, 111, 34, 448, 1, 448, '2024-10-12 04:45:20', '2024-10-12 04:45:20'),
(110, 111, 37, 676, 1, 676, '2024-10-12 04:45:20', '2024-10-12 04:45:20'),
(111, 111, 35, 677, 1, 677, '2024-10-12 04:45:20', '2024-10-12 04:45:20'),
(112, 112, 39, 448, 8, 3584, '2024-10-12 04:49:49', '2024-10-12 04:49:49'),
(113, 112, 36, 1, 2, 2, '2024-10-12 04:49:49', '2024-10-12 04:49:49'),
(114, 112, 34, 448, 1, 448, '2024-10-12 04:49:49', '2024-10-12 04:49:49'),
(115, 112, 37, 676, 1, 676, '2024-10-12 04:49:49', '2024-10-12 04:49:49'),
(116, 112, 35, 677, 1, 677, '2024-10-12 04:49:49', '2024-10-12 04:49:49'),
(117, 113, 39, 448, 8, 3584, '2024-10-12 04:54:03', '2024-10-12 04:54:03'),
(118, 113, 36, 1, 2, 2, '2024-10-12 04:54:03', '2024-10-12 04:54:03'),
(119, 113, 34, 448, 1, 448, '2024-10-12 04:54:03', '2024-10-12 04:54:03'),
(120, 113, 37, 676, 1, 676, '2024-10-12 04:54:03', '2024-10-12 04:54:03'),
(121, 113, 35, 677, 1, 677, '2024-10-12 04:54:03', '2024-10-12 04:54:03'),
(122, 114, 39, 448, 8, 3584, '2024-10-12 04:54:59', '2024-10-12 04:54:59'),
(123, 114, 36, 1, 2, 2, '2024-10-12 04:54:59', '2024-10-12 04:54:59'),
(124, 114, 34, 448, 1, 448, '2024-10-12 04:54:59', '2024-10-12 04:54:59'),
(125, 114, 37, 676, 1, 676, '2024-10-12 04:54:59', '2024-10-12 04:54:59'),
(126, 114, 35, 677, 1, 677, '2024-10-12 04:54:59', '2024-10-12 04:54:59'),
(127, 115, 39, 448, 8, 3584, '2024-10-12 05:15:06', '2024-10-12 05:15:06'),
(128, 115, 36, 1, 2, 2, '2024-10-12 05:15:06', '2024-10-12 05:15:06'),
(129, 115, 34, 448, 1, 448, '2024-10-12 05:15:06', '2024-10-12 05:15:06'),
(130, 115, 37, 676, 1, 676, '2024-10-12 05:15:06', '2024-10-12 05:15:06'),
(131, 115, 35, 677, 1, 677, '2024-10-12 05:15:06', '2024-10-12 05:15:06'),
(132, 116, 35, 677, 1, 677, '2024-10-12 05:18:37', '2024-10-12 05:18:37'),
(133, 116, 33, 233, 1, 233, '2024-10-12 05:18:37', '2024-10-12 05:18:37'),
(134, 116, 34, 448, 1, 448, '2024-10-12 05:18:37', '2024-10-12 05:18:37'),
(135, 117, 35, 677, 1, 677, '2024-10-12 05:41:39', '2024-10-12 05:41:39'),
(136, 117, 33, 233, 1, 233, '2024-10-12 05:41:39', '2024-10-12 05:41:39'),
(137, 117, 34, 448, 1, 448, '2024-10-12 05:41:39', '2024-10-12 05:41:39'),
(138, 118, 35, 677, 1, 677, '2024-10-12 05:54:47', '2024-10-12 05:54:47'),
(139, 118, 36, 1, 1, 1, '2024-10-12 05:54:47', '2024-10-12 05:54:47'),
(140, 118, 33, 233, 1, 233, '2024-10-12 05:54:47', '2024-10-12 05:54:47'),
(141, 118, 37, 676, 1, 676, '2024-10-12 05:54:47', '2024-10-12 05:54:47'),
(142, 119, 37, 676, 1, 676, '2024-10-12 06:01:09', '2024-10-12 06:01:09'),
(143, 119, 35, 677, 1, 677, '2024-10-12 06:01:09', '2024-10-12 06:01:09'),
(144, 119, 36, 1, 1, 1, '2024-10-12 06:01:09', '2024-10-12 06:01:09'),
(145, 120, 35, 677, 1, 677, '2024-10-12 06:03:48', '2024-10-12 06:03:48'),
(146, 120, 33, 233, 1, 233, '2024-10-12 06:03:48', '2024-10-12 06:03:48'),
(147, 120, 37, 676, 1, 676, '2024-10-12 06:03:48', '2024-10-12 06:03:48'),
(148, 121, 35, 677, 1, 677, '2024-10-12 06:05:27', '2024-10-12 06:05:27'),
(149, 121, 33, 233, 1, 233, '2024-10-12 06:05:27', '2024-10-12 06:05:27'),
(150, 121, 37, 676, 1, 676, '2024-10-12 06:05:27', '2024-10-12 06:05:27'),
(151, 122, 35, 677, 1, 677, '2024-10-12 06:07:29', '2024-10-12 06:07:29'),
(152, 122, 33, 233, 1, 233, '2024-10-12 06:07:29', '2024-10-12 06:07:29'),
(153, 122, 37, 676, 1, 676, '2024-10-12 06:07:29', '2024-10-12 06:07:29'),
(154, 123, 35, 677, 1, 677, '2024-10-12 06:08:34', '2024-10-12 06:08:34'),
(155, 123, 33, 233, 1, 233, '2024-10-12 06:08:34', '2024-10-12 06:08:34'),
(156, 123, 37, 676, 1, 676, '2024-10-12 06:08:34', '2024-10-12 06:08:34'),
(157, 124, 35, 677, 1, 677, '2024-10-12 06:26:53', '2024-10-12 06:26:53'),
(158, 124, 33, 233, 1, 233, '2024-10-12 06:26:53', '2024-10-12 06:26:53'),
(159, 124, 37, 676, 1, 676, '2024-10-12 06:26:53', '2024-10-12 06:26:53'),
(160, 125, 33, 233, 1, 233, '2024-10-12 06:28:14', '2024-10-12 06:28:14'),
(161, 125, 34, 448, 1, 448, '2024-10-12 06:28:14', '2024-10-12 06:28:14'),
(162, 125, 36, 1, 1, 1, '2024-10-12 06:28:14', '2024-10-12 06:28:14'),
(163, 127, 35, 677, 1, 677, '2024-10-12 06:52:51', '2024-10-12 06:52:51'),
(164, 127, 39, 448, 1, 448, '2024-10-12 06:52:51', '2024-10-12 06:52:51'),
(165, 127, 36, 1, 1, 1, '2024-10-12 06:52:51', '2024-10-12 06:52:51'),
(166, 128, 35, 677, 1, 677, '2024-10-12 06:53:38', '2024-10-12 06:53:38'),
(167, 128, 33, 233, 1, 233, '2024-10-12 06:53:38', '2024-10-12 06:53:38'),
(168, 142, 33, 233, 1, 233, '2024-10-12 08:07:12', '2024-10-12 08:07:12'),
(169, 142, 39, 448, 1, 448, '2024-10-12 08:07:12', '2024-10-12 08:07:12'),
(170, 142, 37, 676, 1, 676, '2024-10-12 08:07:12', '2024-10-12 08:07:12'),
(171, 143, 35, 677, 1, 677, '2024-10-12 08:10:50', '2024-10-12 08:10:50'),
(172, 143, 39, 448, 1, 448, '2024-10-12 08:10:50', '2024-10-12 08:10:50'),
(173, 144, 36, 1, 1, 1, '2024-10-12 08:20:26', '2024-10-12 08:20:26'),
(174, 145, 34, 448, 1, 448, '2024-10-12 08:22:28', '2024-10-12 08:22:28'),
(175, 146, 35, 677, 1, 677, '2024-10-13 09:11:27', '2024-10-13 09:11:27'),
(176, 147, 50, 500, 15, 7500, '2024-10-20 15:53:32', '2024-10-20 15:53:32'),
(177, 148, 50, 500, 1, 500, '2024-10-20 15:55:39', '2024-10-20 15:55:39'),
(178, 149, 35, 677, 1, 677, '2024-10-20 17:36:28', '2024-10-20 17:36:28'),
(179, 150, 46, 640, 1, 640, '2024-10-21 06:37:31', '2024-10-21 06:37:31'),
(180, 151, 51, 1450, 1, 1450, '2024-10-21 11:55:29', '2024-10-21 11:55:29'),
(181, 152, 49, 1600, 1, 1600, '2024-10-21 12:01:28', '2024-10-21 12:01:28'),
(182, 153, 44, 440, 1, 440, '2024-10-21 12:26:19', '2024-10-21 12:26:19'),
(183, 154, 33, 233, 1, 233, '2024-10-21 12:37:16', '2024-10-21 12:37:16'),
(184, 155, 46, 640, 1, 640, '2024-10-21 13:22:42', '2024-10-21 13:22:42'),
(185, 156, 49, 1600, 1, 1600, '2024-10-21 13:24:49', '2024-10-21 13:24:49'),
(186, 157, 48, 1200, 1, 1200, '2024-10-30 18:59:22', '2024-10-30 18:59:22'),
(187, 158, 51, 1450, 1, 1450, '2024-10-30 19:03:43', '2024-10-30 19:03:43'),
(188, 158, 36, 1, 1, 1, '2024-10-30 19:03:43', '2024-10-30 19:03:43'),
(189, 159, 33, 233, 1, 233, '2024-11-03 10:24:59', '2024-11-03 10:24:59');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(100) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`email`, `token`, `created_at`) VALUES
('khiemhuynh@gmai.com', '2d08f8ea36f5317cf6c9dcf9aeced830f7668a0f84dd5b4652c1acbe3a90dcbc5da54e680452e2340e3ad46efa75f5cdfd6b', '2024-09-06 07:38:47'),
('khiemhuynh@gmai.com', 'f76c8f53af3953aa27886a674ab801169bbf3222359301807ebd06db9e240a2e441693f4b31a8dcf3b80f13bbc5068e023a4', '2024-09-06 07:45:00'),
('khiemhuynh@gmai.com', 'e660b8de8c5356c6b097bf4d7dcd93d0bd51f4220807d1c28d0bc453c223092b2ff88207542e7277089defc91993a1a28785', '2024-09-06 18:18:11'),
('khiemhuynh@gmai.com', 'cb5f234adb49dc245c8a5e4445cb3f590aec542561021f0d04909090e8a7fe52c3e0987fbad839f5e3ebb9cc0d01f2212292', '2024-09-06 18:20:01'),
('thinhptqgbc210227@fpt.edu.vn', '240988dcf7799d49f18a7124d2d6ff206322f4ab2eaa2776c4e88e9b2f59be52cbef85b066ec2f5cb67ea498f66e917f5159', '2024-09-06 18:20:16'),
('thinhptqgbc210227@fpt.edu.vn', 'd2510144b59709f89466a2794f51fe4ffca4c4eb7b815364903a56a38f9935e118603bb9682dc35a949ae8bbb499b7b2c53b', '2024-09-06 18:46:27'),
('thinhptqgbc210227@fpt.edu.vn', '5326b701f6e23e5d9a0812dd490f7c4bc1e7453bb791995354eaf3e42530886bdf4a5d0692980660942a67e8d1694938da26', '2024-09-06 18:46:34'),
('thinhptqgbc210227@fpt.edu.vn', '221abd9619a4ba39d4af5a66e3923c3a8a20ae52b973fa68e814e712dd3906a8476b75c3d029299f6bb38c26992b66b85678', '2024-09-08 08:54:33'),
('thinhptqgbc210227@fpt.edu.vn', '2aaeca7fc3a69a8d060e3bf70bac33655d684c65d3866f21bcd616973825dfb4f926190478448f8f50d0b744866a709c5e2a', '2024-09-08 08:58:57'),
('thinhptqgbc210227@fpt.edu.vn', '2f658ac3a8d94d42d1acac89eac884a98ff07e56f13ed59d3d380f3c0e6a6da2481eeabbb49e30cdf674ab34f72d47db39f9', '2024-09-08 08:59:48'),
('thinhptqgbc210227@fpt.edu.vn', '29fcc25af06a7b62ec94bc422bd557f438c0eeabc21ac5f7e47c0c3cdc4ba5ef0e5dd4f9befa20493a60176d1eb5dd9c5660', '2024-09-08 09:01:53'),
('thinhptqgbc210227@fpt.edu.vn', '258a3758e1bd9fa96e72dea65623e1ca7781f1767f7b66fd7ce9c9467bdc1959096a67a12c01fcd029da738db85c028182a4', '2024-09-10 01:34:17'),
('thinhptqgbc210227@fpt.edu.vn', 'abdf175f8a5c32415b6cad92c79da2713c3e09e53ac94d45eea07330584fdddddfd14d81f1734890a81d499e58a620184662', '2024-09-10 01:34:17'),
('thinhptqgbc210227@fpt.edu.vn', 'e8a22cfe586fee45f02feb55d150d1bb482484e63b854641853b1d4176fa7ca80851dd4844d0565c32ccd778f86e32c8fdc2', '2024-09-18 08:03:44'),
('khanhtran@gmail.com', '4b743193a5305ea8b408f37bcc69af389ca0356f7ef96a535c975228b2fd3cd2dc6ef2b3e76fd73d35a15156a2be2d9a3c55', '2024-09-24 07:57:01'),
('thinhptqgbc210227@fpt.edu.vn', 'de693871704bb48f5cd75ef36966fdc57ee67e3c4b2ed5f09acbae8f51ea1003dc4eb7bc05b7a2f9335e54d70ce53a77f8f3', '2024-09-24 10:09:30'),
('thinhptqgbc210227@fpt.edu.vn', 'b6716e63e01714568f46b370c08eabfe3c2cb0c105eafc1796a3ba0859445081c45b986dc8eefcc45019ce71bedde4bac214', '2024-09-24 10:09:32'),
('thinhptqgbc210227@fpt.edu.vn', '15930d7e312c7f69f2d385d9d24fae30dd0df32b10eb4df81a55e1fc2aa1f9c9d06245553dc0c2de419e62bbb7a9c573a272', '2024-10-13 09:32:04'),
('thinhptqgbc210227@fpt.edu.vn', '67da13e69cced04d97e9810fa0d27db2f64d02d945838f1148ab049290b3af54399d8d29ce0995a09691ecab575fde35fa7c', '2024-10-13 09:36:30'),
('thinhptqgbc210227@fpt.edu.vn', 'acb0cc1a54f6723c704b3d42b234b93cc353a8f2f699d304f6c9c37df66f12f4e11630cc8bb66e1581230e22a27a590ebcb1', '2024-10-13 09:38:05'),
('thinhptqgbc210227@fpt.edu.vn', 'c7db0c39f0060f081a4647e7150decebaad73380c0c9a986fb7e49394cc214c95339a5e709494e75b1576fb13500489b1ebf', '2024-10-13 09:48:07'),
('thinhptqgbc210227@fpt.edu.vn', 'ed0d98e9ee41d228dc1d2edbb4b357e0c94758f73fadcb674f157e9c7ce206913b787e3be5fa72fc871a0d2fb157fbdf07c5', '2024-10-13 09:59:10'),
('thinhptqgbc210227@fpt.edu.vn', 'ea9fdda3e507661ff041d8d425e1f4efc928a2452bae32b3b6807a60b2753317ab09ad349325bd21f2ecb737a9953e854de2', '2024-10-13 10:02:49'),
('thinhptqgbc210227@fpt.edu.vn', 'b8f85cc6a1cbdfcf9329a7f4ea8129c7bf2e19ff5ede428bd7b161247d8fe884ccc9411146e5864348cdc7f3c55188b6df8b', '2024-10-13 10:13:36'),
('thinhptqgbc210227@fpt.edu.vn', 'e9f79b613804d9ff44087a51e7a921470ac606c8c97e9e67479e641f2bc89579b18097005ecdcaa8cb31b8abfcd24558d6f8', '2024-10-13 10:14:48'),
('thinhptqgbc210227@fpt.edu.vn', '66ec94785def3656ac7577f1f214f0b29703c96482daa34d91a6a614c613d42a33be57a3fcf92d273a7dc9ed41f5da15f711', '2024-10-14 02:31:10'),
('thinhptqgbc210227@fpt.edu.vn', 'ddcb849ac611f812f810c09ff577208c843527a17ca0538a5ccdfa491793f653cf290885b06528f33080566f8ca057474078', '2024-10-23 20:14:24'),
('khiemhuynh@gmai.com', '5371bd2095fcac1b5a5a9386b452f0426c0ccd05d98cbbe2b635b9c5086fa3677721910da74923558d76528e878ec915e426', '2024-10-31 05:42:37'),
('thinhptqgbc210227@fpt.edu.vn', '44f1bff6eb753d9e48cec29af09449305a648d875b67be0b6e64ecab247a52f21389d6fd8b30afc4871076d83dac5e2e0170', '2024-10-31 10:32:57');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `summary` text NOT NULL,
  `stock` tinyint(3) UNSIGNED NOT NULL,
  `price` double NOT NULL,
  `disscounted_price` double DEFAULT NULL,
  `images` text NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `brand_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('Active','Inactive') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `slug`, `description`, `summary`, `stock`, `price`, `disscounted_price`, `images`, `category_id`, `brand_id`, `status`, `created_at`, `updated_at`) VALUES
(33, 'Macbook M2', 'macbook-m2', 'Dimensions: 162.3 x 75.6 x 8.0mm\r\nWeight: 189g\r\nFront: Gorilla Glass 3\r\nDust and water resistance: IP54\r\nDisplay: 6.67 inch AMOLED 120Hz\r\nResolution: 1080 x 2400 pixels\r\nChipset: Qualcomm SM6225 Snapdragon 685 (6nm) Adreno 610\r\nRAM: 6GB / 8GB\r\nStorage: 128GB / 256GB\r\nOperating system: Android 13\r\nInterface: MIUI 14\r\nMain camera: 108 MP, f/1.8, 24mm, 1/1.67\", 0.64μm, PDAF\r\nUltra wide-angle camera: 8 MP, f/2.2, 120°\r\nMacro camera: 2 MP, f/2.4\r\nFront camera:16 MP, f/2.4\r\nBattery capacity: 5000mAh\r\nFast charging: 33W                        ', '                     Macbook M2 still has a sophisticated and stylish look. It is dust and water resistant with an IP54 rating, while eliminating the large camera cluster for a more streamlined overall look. Macbook M2 has a large 14-inch AMOLED screen with 1080p resolution and a 120Hz refresh rate, providing a smooth and vivid visual experience. It is equipped with a powerful Snapdragon 685 4G chipset with 6GB or 8GB RAM options, 128GB or 256GB memory, and microSD expansion support. The impressive point on Macbook M2 is the rear camera system with a 108MP main camera, 8MP ultra-wide camera, and 2MP macro camera. The 16MP selfie camera meets personal photography needs well.', 8, 233, 217, 'uploads/66ea737f530c5macbookm1.jpeg', 9, 4, 'Inactive', NULL, NULL),
(34, 'Samsung Galaxy A55', 'samsung-galaxy-a55', '- Stylish and luxurious design with Corning® Gorilla® Victus®+ glass back, durable metal frame;\r\n\r\n- Exynos 1480 processor with improved 4nm processor, easy to multitask, play games or surf the web;\r\n\r\n- Sensor improves light capture, helps take vivid night shots and limits noise in low light conditions;\r\n\r\n- Camera with impressive features: wide-angle shooting, night shooting, No Shake Cam and VDIS stabilization features;\r\n\r\n- Knox security support, keeps user information safe;\r\n\r\n- Durable with IP67 dust and water resistance, giving you more peace of mind when using the device in many different environments;\r\n\r\n- 5,000mAh battery capacity for up to 2 days of use (under test conditions);\r\n\r\n- Large 6.6-inch Super AMOLED screen with high brightness, expanding display capabilities and enhancing visual experience;\r\n                        ', 'Exynos 1480 4nm Chip - Smooth and flexible use with heavy tasks without any obstacles.\r\nWith a 12MP wide-angle camera, it can capture every scene in the frame.\r\n120Hz refresh rate - Every action on the screen becomes incredibly smooth.\r\n5000 mAh battery combined with 25W fast charging - Comfortable use in all daily activities.\r\n                        ', 95, 448, 436, 'uploads/6714e5c852975Samsung-Galaxy-A55-5G.jpg;uploads/6714e5c852e1aSamsung-Galaxy-A55-5G-Black.jpg;uploads/6714e5c853251Samung-A55-pink.jpg', 1, 3, 'Active', NULL, NULL),
(35, 'iPhone 13', 'iphone-13', 'Outstanding performance thanks to Apple A15 Bionic chip\r\nThe super-powerful Apple A15 Bionic chip manufactured on a 5nm process helps the iPhone 13 achieve impressive performance, with a 50% faster CPU and 30% faster GPU than competitors in the same segment.', 'iPhone 13 uses an OLED panel with a 6.1-inch screen size for sharp, vivid color quality and image details, with a resolution of 1170 x 2532 Pixels. The dual rear camera cluster on the iPhone 13 both have a resolution of 12 MP, the main camera helps capture more light, increasing the light capture capacity by up to 47%, so the photo quality is also improved compared to the previous version. The phone has an ultra-wide-angle camera with a 120-degree viewing angle to capture more details, easily capturing majestic mountain scenery and photos of large groups of people.', 84, 677, 557, 'uploads/6714e6a2ea542apple-iphone-13-mini-128gb-verde.jpg;uploads/6714e6a2ea8c2Iphone-13.jpg;uploads/6714e6a2eab0ciphone-13-xanh.jpg', 1, 4, 'Active', NULL, NULL),
(36, 'iPhone 15 Pro max', 'iphone-15-pro-max', 'iPhone 15 Pro Max will continue to be a phone with a flat screen and back, typical of Apple, bringing elegance and luxury.\r\n\r\nThe main material of iPhone 15 Pro Max is still a metal frame and tempered glass back, creating durability and solidity. However, with advanced technology, this frame has been upgraded to titanium instead of stainless steel or aluminum in previous generations.\r\n\r\nAnother big change is in the connection port, iPhone 15 Pro Max has switched from Lightning to Type-C, providing faster data transfer speeds and more flexible integration with many other devices.\r\n\r\nNow users can use the same charging cable of iPhone 15 Pro Max for Mac, iPad, Apple Watch, AirPods*, bringing convenience in using accessories, thereby optimizing your space because it is not affected by having too many charging cables.\r\n                        ', 'Display: OLED6.7\"Super Retina XDR\r\nOperating System: iOS 17\r\nRear Camera: Main 48 MP & Secondary 12 MP, 12 MP\r\nFront Camera: 12 MP\r\nChip: Apple A17 Pro 6-core\r\nRAM: 8 GB\r\nStorage Capacity: 256 GB\r\nSIM: 1 Nano SIM & 1 eSIMSupport 5G\r\nBattery, Charger: 4422 mAh20 W\r\n                        ', 99, 1.354, 995, 'uploads/66b6c042c4761iphone-15-pro-max-blue-1-1.jpg;uploads/66b6c042c4936iphone-15-pro-max-black-1-1.jpg;uploads/66b6c042c4b66iphone-15-pro-max-tu-nhien-1-1.jpg', 1, 4, 'Active', NULL, NULL),
(37, 'Apple Watch Ultra 2', 'apple-watch-ultra-2', 'Display technology:\r\nOLED\r\nScreen size:\r\n1.92 inches\r\nResolution:\r\n410 x 502 pixels\r\nDial size:\r\n49 mm\r\n                        ', 'Apple Watch Ultra 2 GPS + Cellular 49mm Titanium bezel Ocean strap is Apple smartwatch that has attracted a lot of attention from the media and technology lovers at the Wonderlust event in 2023. The watch has a unique, trendy and sporty appearance, and the internal features also have improvements that promise to satisfy users expectations.', 79, 676, 674, 'uploads/67151a89c9a4b20.jpg;uploads/67151a89c9d8921.jpg;uploads/67151a89c9fbc22.jpg', 5, 4, 'Inactive', NULL, NULL),
(38, 'Macbook M1', 'macbook-m1', '                        dqwdqwawwd', '                        ưcdfqawffaw', 198, 1.354, 995, 'uploads/6717d2363b36dmacbookm1.jpeg', 9, 4, 'Active', NULL, NULL),
(39, 'Samsung Galaxy S23 Ultra ', 'samsung-galaxy-s23-ultra-', 'aaaaaaaaaaaaaaaaaaaaaaaaaasss', 'aaaaaaaaaaaaaaaaa', 91, 448, NULL, 'uploads/66ea7e4434a7fs24den.jpg;uploads/66ea7e4434d28s24.jpg', 1, 3, 'Active', '2024-09-18 07:16:20', '2024-09-18 07:16:20'),
(40, 'Samsung Galaxy A15 5G', 'samsung-galaxy-a15-5g', 'Configuration & Memory\r\nOperating System:\r\nAndroid 14\r\nProcessor (CPU):\r\nMediaTek Dimensity 6100+\r\nCPU Speed:\r\n2 cores 2.2 GHz & 6 cores 2.0 GHz\r\nGraphics Chip (GPU):\r\nMali-G57\r\nRAM:\r\n8 GB\r\nStorage Capacity:\r\n256 GB\r\nRemaining Capacity (available) about:\r\n236 GB\r\nMemory Card:\r\nMicroSD, support up to 1 TB\r\nContacts:\r\nUnlimited', 'The design of the Galaxy A15 5G really attracted me from the first time I saw it. With a square shape, this design not only maintains the traditional style of the Galaxy A series but also reflects the uniformity and strength of the product line.', 10, 400, NULL, 'uploads/6714e1fae7deb71QZyo8ekbL._SL1500.jpg;uploads/6714e1fae81ebSamsung-Galaxy-A15-goldnew.jpg;uploads/6714e1fae856eSamsung-Galaxy-S24-Ultra-5G-gold.png', 1, 3, 'Active', '2024-10-20 10:45:19', '2024-10-20 10:45:19'),
(41, 'Samsung Galaxy A35 5G', 'samsung-galaxy-a35-5g', 'Configuration & Memory\r\nOperating System:\r\nAndroid 14\r\nProcessor (CPU):\r\nExynos 1380 8-core\r\nCPU Speed:\r\n4-core 2.4 GHz & 4-core 2 GHz\r\nGraphics Chip (GPU):\r\nMali-G68 MP5\r\nRAM:\r\n8 GB\r\nStorage Capacity:\r\n256 GB\r\nRemaining Capacity (available) Approx.:\r\n231 GB\r\nMemory Card:\r\nMicroSD, supports up to 1 TB\r\nContacts:\r\nUnlimited', 'Samsung Galaxy A35 5G is one of Samsung notable mid-range smartphones. Possessing powerful performance, sharp screen and large battery, the phone promises to bring diverse and wonderful experiences to users.', 10, 475, NULL, 'uploads/6714eb32b6482SamsungA35-black.jpg;uploads/6714eb32b6805SamsungA35-White.jpg;uploads/6714eb32b6aaeSamsung-Galaxy-A35-5g.jpg', 1, 3, 'Active', '2024-10-20 11:07:48', '2024-10-20 11:07:48'),
(43, 'Iphone 11 Pro Max', 'iphone-11-pro-max', 'DESIGN Parameters\r\nWeight\r\n226g\r\n\r\nDimensions\r\n158 x 77.8 x 8.1 mm\r\n\r\nMaterial\r\nGlass front, Glass back, Steel frame\r\n\r\nWater & dust resistant IP68 standard\r\nColors\r\nSilver, Gold, Gray, Dark green\r\n\r\nLaunch year 2019\r\nBrand origin USA\r\nSOUND Parameters\r\nHeadphones No 3.5mm jack\r\nStereo sound system\r\nSpeaker\r\nDual speakers\r\n\r\nSCREEN Parameters\r\nSuper Retina XDR OLEDHDR10Dolby Vision display technology\r\nMaximum brightness\r\n1200 nits\r\n\r\nScreen size\r\n6.5 inches\r\n\r\nResolution\r\n1242 x 2688\r\nScreen ratio 19.5:9\r\nPixel density\r\n458ppi', 'iPhone 11 Pro Max is the \"final boss\" version of the iPhone trio launched at the end of 2019. Although there are not many significant changes compared to the previous generation, iPhone 11 Pro Max still creates a big buzz in the technology industry because of what it brings.', 20, 960, NULL, 'uploads/6714ed7f5480biphone11black1.jpg;uploads/6714ed7f54a45Iphone11prm.jpg;uploads/6714ed7f54bbdiphone11promaxhopea1.jpg', 1, 4, 'Active', '2024-10-20 11:46:07', '2024-10-20 11:46:07'),
(44, 'Xiaomi Redmi Note 13 Pro', 'xiaomi-redmi-note-13-pro', 'Operating System: Adroid 13\r\nProcessor (CPU):\r\nMediaTek Helio G99-Ultra 8-core\r\nCPU speed:\r\n2.2 GHz\r\nGraphics chip (GPU):\r\nMali-G57 MC2\r\nRAM:\r\n8 GB\r\nStorage capacity:\r\n128 GB\r\nRemaining capacity (available) about:\r\n100 GB\r\nMemory card:\r\nMicroSD, support up to 1 TB', 'The explosion of mobile technology in recent years has brought users a variety of smartphone options. In the mid-range segment, Xiaomi Redmi Note 13 Pro 128GB has emerged as a strong candidate with outstanding advantages in design and performance thanks to the Helio G99-Ultra chip, 200 MP camera and 67 W fast charging.', 100, 440, NULL, 'uploads/6714fb2850b4c65a99fc158fd7-xiaomi-redmi-note-13-pro-4g-gris-01-l-1600x1600.jpg;uploads/6714fb2850e27Xiaomi13pro1.jpg;uploads/6714fb2850fafxiaomi-redmi-note-13-pro-5g-trang.jpg.png', 1, 11, 'Active', '2024-10-20 12:14:20', '2024-10-20 12:14:20'),
(45, 'Xiaomi 14 Ultra 5G', 'xiaomi-14-ultra-5g', 'Operating System: Android 14\r\nCPU:\r\nSnapdragon 8 Gen 3 8-core\r\nCPU speed:\r\n1 core 3.3 GHz, 3 cores 3.2 GHz, 2 cores 3 GHz & 2 cores 2.3 GHz\r\nGPU:\r\nAdreno 750\r\nRAM:\r\n16 GB\r\nStorage capacity:\r\n512 GB\r\nRemaining capacity (available) about:\r\n475 GB', 'Xiaomi once again redefines perfection in the mobile technology industry with its latest product line, the Xiaomi 14 Ultra. This is not only a smart device but also a work of art, containing breakthroughs in every aspect from design to performance.', 50, 1200, NULL, 'uploads/671507f150b32Xiaomi-13-Ultra-Olive-Green1.jpg;uploads/671507f150e65Xiaomi-14-Ultra-5G1.jpg;uploads/671507f151124Xiaomi-14-Ultra-Blue1.jpg', 1, 11, 'Active', '2024-10-20 13:29:48', '2024-10-20 13:29:48'),
(46, 'Laptop Lenovo Ideapad Slim 3 ', 'laptop-lenovo-ideapad-slim-3-', 'In the market of study and office laptops, the Lenovo Ideapad Slim 3 15AMN8 R5 7520U (82XQ00J0VN) laptop quickly attracts the attention of users because of its many outstanding advantages. This laptop has all the elements to become an ideal companion for students and office workers, meeting all the needs of studying, working and entertaining effectively.', 'CPU Technology: AMD Ryzen 5 - 7520U Core: 4 Threads: 8 CPU Speed: 2.8GHz Max Speed: Turbo Boost 4.3 GHz Cache: 4 MB', 10, 640, NULL, 'uploads/67151042e82a91.png;uploads/67151042e89a52.jpg;uploads/67151042e8b383.png', 9, 12, 'Active', '2024-10-20 14:14:26', '2024-10-20 14:14:26'),
(47, 'Lenovo Gaming LOQ 15IAX9 i5 ', 'lenovo-gaming-loq-15iax9-i5-', 'CPU Technology:\r\nIntel Core i5 Alder Lake - 12450HX\r\nCore:\r\n8\r\nThreads:\r\n12\r\nCPU Speed:\r\nUnspecified\r\nMax Speed:\r\nTurbo Boost 4.4 GHz\r\nCache:\r\n12 MB', 'Lenovo LOQ Gaming 15IAX9 i5 12450HX (83GS000JVN) Laptop has the typical appearance of Lenovo gaming laptops, bringing a completely new, trendy version. Gaming laptops are also integrated with powerful configuration, smooth frame allowing you to deeply experience every game.', 30, 1250, NULL, 'uploads/6715118e171f3lenovo-loq-15iax9-core-i5-12450hx-16gb-512gb-156-rtx3050-esp-new.jpg;uploads/6715118e173bbres_2db71b6f4e44ac47630225185b84c947-new.jpg', 9, 12, 'Active', '2024-10-20 14:17:15', '2024-10-20 14:17:15'),
(48, 'Laptop Asus TUF Gaming F15 FX507ZC4 i7', 'laptop-asus-tuf-gaming-f15-fx507zc4-i7', 'Explore the wonderful entertainment space on the ASUS TUF Gaming F15 FX507ZC4 i7 12700H (HN181W) laptop model, which is extremely familiar to many gamers today. Gaming laptops have a reasonable price but have a configuration that is superior to expectations for entertainment, gaming or daily work.', 'CPU Technology: Intel Core i7 Alder Lake - 12700H Core: 14 Threads: 20 CPU Speed: 2.30 GHz Max Speed: Turbo Boost 4.7 GHz Cache: 24 MB', 20, 1200, NULL, 'uploads/671512e704c531.png;uploads/671512e7052932.jpg;uploads/671512e7055ac3.jpg', 9, 5, 'Active', '2024-10-20 14:25:43', '2024-10-20 14:25:43'),
(49, 'Laptop Apple MacBook Pro 14 inch M3 ', 'laptop-apple-macbook-pro-14-inch-m3-', 'CPU Technology:\r\nApple M3 - Undisclosed\r\nCore Count:\r\n8\r\nThread Count:\r\nUndisclosed\r\nCPU Speed:\r\n100GB/s\r\nMax Speed:\r\nUndisclosed\r\nCache:\r\nUndisclosed', 'The MacBook Pro M3 8GB is a significant step forward in Apple laptop lineup, featuring a focus on optimizing performance and cutting-edge graphics. With the powerful M3 chip, this product sets a new standard for performance and elegant design. This promises to bring a smooth and efficient working experience, a top companion for all tasks from office, entertainment to professional graphics.', 50, 1600, NULL, 'uploads/6715155dbe1554.jpg;uploads/6715155dbe55e5.jpg;uploads/6715155dbe7f26.jpg', 9, 4, 'Active', '2024-10-20 14:36:13', '2024-10-20 14:36:13'),
(50, 'Apple Watch Series 10 ', 'apple-watch-series-10-', 'Along with the launch of the iPhone 16 series at the It is Glowtime event in September 2024, the Apple Watch Series 10 46mm - a new step forward in Apple smartwatch lines was also introduced. With a large and outstandingly bright OLED screen, along with a series of advanced health and sports features, this watch is not only a fashion accessory but also a powerful assistant for modern life.\r\nScreen size:\r\nUndisclosed\r\nResolution:\r\n416 x 496 pixels\r\nDial size:\r\n46 mm', 'Display technology:\r\nOLED\r\nScreen size:\r\nUndisclosed\r\nResolution:\r\n416 x 496 pixels\r\nDial size:\r\n46 mm\r\n', 10, 500, NULL, 'uploads/671518b978fbf10.jpg;uploads/671518b97933011.jpg;uploads/671518b9797a112.jpg', 5, 4, 'Active', '2024-10-20 14:50:33', '2024-10-20 14:50:33'),
(51, ' Samsung Galaxy Tab S9 WiFi ', '-samsung-galaxy-tab-s9-wifi-', 'Display Technology:\r\nDynamic AMOLED 2X\r\nResolution:\r\n1600 x 2560 Pixels\r\nWidescreen:\r\n11\" - 120 Hz Refresh Rate\r\nOperating System & CPU\r\nOperating System:\r\nAndroid 13\r\nProcessor (CPU):\r\nSnapdragon 8 Gen 2 for Galaxy\r\nCPU Speed:\r\n1 Core 3.36 GHz, 4 Cores 2.8 GHz & 3 Cores 2 GHz\r\nGraphics Chip (GPU):\r\nAdreno 740', 'Finally, Samsung Galaxy Tab S9 WiFi 128GB was officially launched in the Vietnamese market in July 2023. This is considered an impressive comeback when the company strongly upgraded performance as well as improved battery and screen compared to the previous generation, promising to bring a great experience for all tasks.', 40, 1450, NULL, 'uploads/671520c1d3bb629.jpg;uploads/671520c1d3ea5dbddn.png;uploads/671520c1d40d4samsung-tablet.jpg', 4, 3, 'Active', '2024-10-20 15:06:55', '2024-10-20 15:06:55');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`role_id`, `role_name`) VALUES
(1, 'Admin'),
(4, 'User');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `avatar` varchar(255) NOT NULL,
  `address` varchar(300) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `full_name`, `password`, `phone_number`, `email`, `created_at`, `avatar`, `address`) VALUES
(64, 'quocthinh', 'phantruongquocthinh', '$2y$10$HHE4oDKqyOeDU/7AmOBmY..VfhEYkB3HUmNZ.ywV2fWS020I/mKlK', '0352997883', 'thinhptqgbc210227@fpt.edu.vn', '2024-08-07 01:33:25', '66c6f06b63e04-handsome boy.jpg', ''),
(70, 'khiem', 'hoangkhiem', '$2y$10$14ckbMdlyqYEFZk56S/ZyOfhQewMHCKcexW98GAWfXUr6IcgnJP5.', '0352997883', 'khiemhuynh@gmai.com', '2024-08-23 13:27:32', '66e289bb57335-handsome boy.jpg', 'Vinh Long province'),
(71, 'Quoc', 'Thinh', '$2y$10$TCV6F/4m4c6qnARaLIzx4.vFk1BGVFLk.GfkJ2Xzv1ktcgdrxMceC', '07332382392', 'thinhphan112@gmail.com', '2024-09-22 17:23:11', '', NULL),
(72, 'khanh', 'tran', '$2y$10$uF5XBN2mToF7ZXOU0W98PeKh1dT/YO47e.hASk9FbbrgSg03uL4de', '0352997883', 'khanhtran@gmail.com', '2024-09-23 12:03:04', '', NULL),
(92, 'Thinh', 'Phan Thinh', '$2y$10$miyYUYKMcbJDicS4BvH/MeTPRc1wpNvrua55NlVUQ.TNUvqQ8JHj6', '07332382392', 'phanthinh111@gmail.com', '2024-09-24 10:00:13', '', NULL),
(95, 'Thynh', 'Quoc Thinh', '$2y$10$QckOdxvfed4CmsGzv3oDce5d1GztjLIVF4PheV9rUME7ZaZhOA.ke', '0830739792', 'thinhphan1123@gmail.com', '2024-09-28 16:33:36', '670a54f436ff0-handsome boy.jpg', NULL),
(96, 'Viet', 'Phan Viet Thanh', '$2y$10$dJ42b/.ZSj1F7YyV5Pstj.QkKBMIY5m5oZdyc3a3CgVnqxCr/Ippe', '0352997883', 'vietthanh161123@gmail.com', '2024-10-13 08:00:37', '67153d7cc8160-z5924064190601_b157baaacc8adeaf3e94c19472b0df24.jpg', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

CREATE TABLE `user_roles` (
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_roles`
--

INSERT INTO `user_roles` (`user_id`, `role_id`) VALUES
(64, 1),
(71, 4),
(72, 4),
(92, 4),
(95, 4),
(96, 4);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `brands_slug_unique` (`slug`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_cart_user` (`user_id`),
  ADD KEY `fk_cart_product` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`);

--
-- Indexes for table `contact_message`
--
ALTER TABLE `contact_message`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `user_email` (`user_email`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `fk_fb_user` (`user_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_new_newcategories` (`newscategory_id`),
  ADD KEY `fk_user` (`user_id`);

--
-- Indexes for table `newscategories`
--
ALTER TABLE `newscategories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fb_userorder` (`user_id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_details_order_id_foreign` (`order_id`),
  ADD KEY `order_details_product_id_foreign` (`product_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `email` (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_slug_unique` (`slug`),
  ADD KEY `products_category_id_foreign` (`category_id`),
  ADD KEY `products_brand_id_foreign` (`brand_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`user_id`,`role_id`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `contact_message`
--
ALTER TABLE `contact_message`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `newscategories`
--
ALTER TABLE `newscategories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=160;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=190;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `fk_cart_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `fk_cart_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `contact_message`
--
ALTER TABLE `contact_message`
  ADD CONSTRAINT `contact_message_ibfk_1` FOREIGN KEY (`user_email`) REFERENCES `users` (`email`) ON DELETE CASCADE;

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `fk_fb_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `news`
--
ALTER TABLE `news`
  ADD CONSTRAINT `fk_new_newcategories` FOREIGN KEY (`newscategory_id`) REFERENCES `newscategories` (`id`),
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fb_userorder` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD CONSTRAINT `password_resets_ibfk_1` FOREIGN KEY (`email`) REFERENCES `users` (`email`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`),
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD CONSTRAINT `user_roles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_roles_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
