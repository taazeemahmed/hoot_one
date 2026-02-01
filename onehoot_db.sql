-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 29, 2026 at 02:01 PM
-- Server version: 10.11.15-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `onehoot_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('hootoneone-cache-admin@gmail.com|127.0.0.1', 'i:1;', 1767772313),
('hootoneone-cache-admin@gmail.com|127.0.0.1:timer', 'i:1767772313;', 1767772313),
('hootoneone-cache-admin@hootone.org|127.0.0.1', 'i:1;', 1767772299),
('hootoneone-cache-admin@hootone.org|127.0.0.1:timer', 'i:1767772299;', 1767772299),
('hootoneone-cache-taazeem@choose4choice.com|127.0.0.1', 'i:1;', 1767772259),
('hootoneone-cache-taazeem@choose4choice.com|127.0.0.1:timer', 'i:1767772259;', 1767772259),
('laravel-cache-soheel@choose4choice.com|110.235.224.77', 'i:1;', 1769611574),
('laravel-cache-soheel@choose4choice.com|110.235.224.77:timer', 'i:1769611574;', 1769611574);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `medicines`
--

CREATE TABLE `medicines` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `pack_duration_days` int(11) NOT NULL DEFAULT 30,
  `price` decimal(10,2) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `medicines`
--

INSERT INTO `medicines` (`id`, `name`, `code`, `description`, `pack_duration_days`, `price`, `status`, `created_at`, `updated_at`) VALUES
(1, 'HOO-IMM PLUS B', 'HIP-B', NULL, 30, 85.00, 'active', '2026-01-06 11:30:24', '2026-01-07 23:55:13'),
(2, 'HOO-IMM PLUS OD', 'HIP-OD', NULL, 30, 75.00, 'active', '2026-01-06 11:30:24', '2026-01-07 23:55:19');

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
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_01_06_164657_create_medicines_table', 1),
(5, '2026_01_06_164659_create_representatives_table', 1),
(6, '2026_01_06_164700_create_patients_table', 1),
(7, '2026_01_06_164702_create_orders_table', 1),
(8, '2026_01_06_173557_create_settings_table', 2),
(9, '2026_01_06_173558_create_whatsapp_logs_table', 2),
(10, '2026_01_08_053446_add_country_code_to_representatives_table', 3);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `patient_id` bigint(20) UNSIGNED NOT NULL,
  `medicine_id` bigint(20) UNSIGNED NOT NULL,
  `representative_id` bigint(20) UNSIGNED NOT NULL,
  `packs_ordered` int(11) NOT NULL,
  `treatment_start_date` date NOT NULL,
  `expected_renewal_date` date NOT NULL,
  `status` enum('active','completed','cancelled') NOT NULL DEFAULT 'active',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `patient_id`, `medicine_id`, `representative_id`, `packs_ordered`, `treatment_start_date`, `expected_renewal_date`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 2, 1, '2026-01-06', '2026-02-05', 'active', NULL, '2026-01-06 11:50:53', '2026-01-06 11:50:53'),
(2, 2, 1, 3, 1, '2025-09-23', '2025-10-23', 'active', 'Legacy Import', '2026-01-06 12:51:04', '2026-01-06 12:51:04'),
(3, 3, 2, 3, 1, '2025-11-05', '2025-12-05', 'active', 'Legacy Import', '2026-01-06 12:51:04', '2026-01-06 12:51:04'),
(4, 4, 1, 3, 1, '2025-09-26', '2025-10-26', 'active', 'Legacy Import', '2026-01-06 12:51:04', '2026-01-06 12:51:04'),
(5, 5, 1, 3, 1, '2025-09-25', '2025-10-25', 'active', 'Legacy Import', '2026-01-06 12:51:04', '2026-01-06 12:51:04'),
(6, 6, 1, 3, 1, '2025-09-21', '2025-10-21', 'active', 'Legacy Import', '2026-01-06 12:51:04', '2026-01-06 12:51:04'),
(7, 7, 1, 3, 1, '2025-09-03', '2025-10-03', 'active', 'Legacy Import', '2026-01-06 12:51:04', '2026-01-06 12:51:04'),
(8, 8, 1, 3, 1, '2025-09-26', '2025-10-26', 'active', 'Legacy Import', '2026-01-06 12:51:04', '2026-01-06 12:51:04'),
(9, 9, 1, 3, 1, '2025-11-29', '2025-12-29', 'active', 'Legacy Import', '2026-01-06 12:51:04', '2026-01-06 12:51:04'),
(10, 10, 1, 3, 1, '2025-09-10', '2025-10-10', 'active', 'Legacy Import', '2026-01-06 12:51:04', '2026-01-06 12:51:04'),
(11, 11, 1, 3, 1, '2025-12-05', '2026-01-04', 'active', 'Legacy Import', '2026-01-06 12:51:04', '2026-01-06 12:51:04'),
(12, 12, 2, 3, 1, '2025-12-02', '2026-01-01', 'active', 'Legacy Import', '2026-01-06 12:51:04', '2026-01-06 12:51:04'),
(13, 13, 1, 3, 1, '2025-09-12', '2025-10-12', 'active', 'Legacy Import', '2026-01-06 12:51:04', '2026-01-06 12:51:04'),
(14, 14, 1, 3, 1, '2025-09-23', '2025-10-23', 'active', 'Legacy Import', '2026-01-06 12:51:04', '2026-01-06 12:51:04'),
(15, 15, 1, 3, 1, '2025-09-10', '2025-10-10', 'active', 'Legacy Import', '2026-01-06 12:51:04', '2026-01-06 12:51:04'),
(16, 16, 1, 2, 1, '2025-12-07', '2026-01-06', 'active', 'Legacy Import', '2026-01-06 12:51:04', '2026-01-06 12:51:04'),
(17, 17, 2, 2, 1, '2025-11-30', '2025-12-30', 'active', 'Legacy Import', '2026-01-06 12:51:04', '2026-01-06 12:51:04'),
(18, 18, 1, 2, 1, '2025-11-07', '2025-12-07', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(19, 19, 1, 2, 1, '2025-09-15', '2025-10-15', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(20, 20, 1, 2, 1, '2025-09-04', '2025-10-04', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(21, 18, 1, 2, 1, '2025-10-11', '2025-11-10', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(22, 21, 2, 2, 1, '2025-11-08', '2025-12-08', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(23, 22, 1, 2, 1, '2026-03-30', '2026-04-29', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(24, 23, 1, 2, 1, '2025-09-01', '2025-10-01', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(25, 23, 1, 2, 1, '2025-08-05', '2025-09-04', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(26, 20, 1, 2, 1, '2025-05-02', '2025-06-01', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(27, 24, 2, 2, 1, '2025-03-18', '2025-04-17', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(28, 25, 1, 2, 1, '2025-05-26', '2025-06-25', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(29, 26, 1, 2, 1, '2025-05-28', '2025-06-27', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(30, 27, 2, 2, 1, '2025-06-19', '2025-07-19', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(31, 28, 2, 2, 1, '2025-11-09', '2025-12-09', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(32, 29, 1, 2, 1, '2025-11-29', '2025-12-29', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(33, 30, 1, 2, 1, '2025-06-19', '2025-07-19', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(34, 31, 1, 2, 1, '2025-02-03', '2025-03-05', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(35, 32, 1, 2, 1, '2025-11-17', '2025-12-17', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(36, 17, 2, 2, 1, '2025-08-22', '2025-09-21', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(37, 33, 1, 2, 1, '2025-12-17', '2026-01-16', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(38, 34, 2, 2, 1, '2025-06-22', '2025-07-22', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(39, 16, 1, 2, 1, '2025-07-22', '2025-08-21', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(40, 35, 1, 2, 1, '2025-10-17', '2025-11-16', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(41, 36, 1, 2, 1, '2025-11-08', '2025-12-08', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(42, 37, 1, 2, 1, '2025-02-25', '2025-03-27', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(43, 38, 1, 2, 1, '2025-11-08', '2025-12-08', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(44, 39, 2, 2, 1, '2025-11-17', '2025-12-17', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(45, 40, 1, 2, 1, '2025-12-20', '2026-01-19', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(46, 41, 1, 2, 1, '2025-09-22', '2025-10-22', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(47, 42, 2, 2, 1, '2025-12-26', '2026-01-25', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(48, 43, 1, 2, 1, '2025-09-17', '2025-10-17', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(49, 22, 1, 2, 1, '2025-11-20', '2025-12-20', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(50, 44, 1, 2, 1, '2025-11-27', '2025-12-27', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(51, 45, 1, 4, 1, '2025-08-24', '2025-09-23', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(52, 46, 1, 4, 1, '2025-10-22', '2025-11-21', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(53, 47, 1, 4, 1, '2025-08-18', '2025-09-17', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(54, 48, 1, 4, 1, '2025-03-10', '2025-04-09', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(55, 49, 1, 4, 1, '2025-12-05', '2026-01-04', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(56, 45, 1, 4, 1, '2025-11-17', '2025-12-17', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(57, 50, 1, 4, 1, '2025-08-30', '2025-09-29', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(58, 51, 1, 4, 1, '2025-12-21', '2026-01-20', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(59, 52, 1, 4, 1, '2025-10-12', '2025-11-11', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(60, 53, 1, 4, 1, '2026-01-17', '2026-02-16', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(61, 54, 1, 4, 1, '2025-09-15', '2025-10-15', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(62, 55, 1, 4, 1, '2025-04-01', '2025-05-01', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(63, 56, 1, 4, 1, '2025-10-25', '2025-11-24', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(64, 57, 1, 4, 1, '2025-11-25', '2025-12-25', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(65, 58, 1, 4, 1, '2025-12-22', '2026-01-21', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(66, 59, 1, 4, 1, '2026-01-17', '2026-02-16', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(67, 60, 1, 4, 1, '2025-09-18', '2025-10-18', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(68, 61, 1, 4, 1, '2025-07-07', '2025-08-06', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(69, 62, 1, 4, 1, '2025-03-24', '2025-04-23', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(70, 63, 1, 4, 1, '2025-11-28', '2025-12-28', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(71, 64, 1, 4, 1, '2025-03-21', '2025-04-20', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(72, 65, 1, 4, 1, '2025-11-17', '2025-12-17', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(73, 66, 1, 4, 1, '2025-11-01', '2025-12-01', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(74, 67, 1, 4, 1, '2025-08-19', '2025-09-18', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(75, 68, 1, 4, 1, '2025-12-14', '2026-01-13', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(76, 69, 1, 4, 1, '2025-03-26', '2025-04-25', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(77, 70, 1, 4, 1, '2026-01-05', '2026-02-04', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(78, 71, 1, 4, 1, '2025-04-24', '2025-05-24', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(79, 72, 1, 4, 1, '2025-05-29', '2025-06-28', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(80, 73, 1, 4, 1, '2026-01-03', '2026-02-02', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(81, 74, 1, 4, 1, '2025-06-30', '2025-07-30', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(82, 75, 1, 4, 1, '2025-12-28', '2026-01-27', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(83, 76, 1, 4, 1, '2025-12-10', '2026-01-09', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(84, 77, 1, 4, 1, '2025-11-21', '2025-12-21', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(85, 78, 1, 4, 1, '2025-12-21', '2026-01-20', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(86, 79, 1, 4, 1, '2025-09-23', '2025-10-23', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(87, 80, 1, 4, 1, '2025-12-15', '2026-01-14', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(88, 81, 1, 5, 1, '2025-06-12', '2025-07-12', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(89, 82, 2, 5, 1, '2026-01-26', '2026-02-25', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(90, 83, 1, 5, 1, '2025-09-19', '2025-10-19', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(91, 84, 1, 5, 1, '2025-09-18', '2025-10-18', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(92, 85, 1, 5, 1, '2025-06-18', '2025-07-18', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(93, 86, 1, 5, 1, '2025-07-18', '2025-08-17', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(94, 87, 1, 5, 1, '2025-12-01', '2025-12-31', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(95, 88, 2, 5, 1, '2025-12-05', '2026-01-04', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(96, 89, 1, 5, 1, '2025-09-01', '2025-10-01', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(97, 90, 1, 5, 1, '2025-07-17', '2025-08-16', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(98, 91, 1, 5, 1, '2025-09-29', '2025-10-29', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(99, 92, 1, 5, 1, '2025-08-05', '2025-09-04', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(100, 93, 2, 5, 1, '2025-12-24', '2026-01-23', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(101, 94, 1, 5, 1, '2025-12-10', '2026-01-09', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(102, 95, 2, 5, 1, '2025-10-04', '2025-11-03', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(103, 96, 1, 5, 1, '2025-09-22', '2025-10-22', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(104, 97, 1, 5, 1, '2025-07-30', '2025-08-29', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(105, 98, 1, 5, 1, '2025-09-24', '2025-10-24', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(106, 99, 1, 5, 1, '2025-08-14', '2025-09-13', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(107, 100, 1, 5, 1, '2025-09-28', '2025-10-28', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(108, 101, 1, 5, 1, '2025-08-08', '2025-09-07', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(109, 102, 1, 5, 1, '2025-06-02', '2025-07-02', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(110, 103, 1, 5, 1, '2025-06-16', '2025-07-16', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(111, 104, 1, 5, 1, '2025-09-21', '2025-10-21', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(112, 105, 1, 5, 1, '2025-08-14', '2025-09-13', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(113, 106, 1, 5, 1, '2025-08-25', '2025-09-24', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(114, 107, 1, 5, 1, '2025-12-05', '2026-01-04', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(115, 108, 1, 5, 1, '2025-06-11', '2025-07-11', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(116, 109, 1, 5, 1, '2025-10-28', '2025-11-27', 'active', 'Legacy Import', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(118, 111, 1, 4, 3, '2026-01-08', '2026-04-08', 'active', NULL, '2026-01-08 17:09:47', '2026-01-08 17:09:47'),
(119, 112, 1, 4, 1, '2026-01-05', '2026-02-04', 'active', NULL, '2026-01-09 12:13:33', '2026-01-09 12:13:33'),
(122, 115, 1, 2, 3, '2025-09-22', '2025-12-21', 'active', 'The client started on 22/09/2025', '2026-01-10 13:36:21', '2026-01-10 13:36:21'),
(123, 116, 1, 2, 3, '2025-09-22', '2025-12-21', 'active', 'The client started on 22/09/2025', '2026-01-10 13:36:23', '2026-01-10 13:36:23'),
(124, 117, 1, 4, 1, '2026-01-12', '2026-02-11', 'active', NULL, '2026-01-12 15:03:06', '2026-01-12 15:03:06'),
(125, 118, 1, 4, 1, '2026-01-13', '2026-02-12', 'active', NULL, '2026-01-13 16:18:56', '2026-01-13 16:18:56'),
(126, 119, 1, 4, 1, '2026-01-22', '2026-02-21', 'active', NULL, '2026-01-22 16:13:10', '2026-01-22 16:13:10');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `representative_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `address` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`id`, `representative_id`, `name`, `email`, `phone`, `country`, `address`, `notes`, `created_at`, `updated_at`) VALUES
(1, 2, 'Test', NULL, '2727272727', 'Nigeria', NULL, 'Created instantly from Order page', '2026-01-06 11:50:53', '2026-01-06 11:50:53'),
(2, 3, 'Mr. Benedict', NULL, '+233202404282', 'Ghana', NULL, 'Imported from legacy data', '2026-01-06 12:51:04', '2026-01-06 12:51:04'),
(3, 3, 'Mr. David', NULL, '+233244927503', 'Ghana', NULL, 'Imported from legacy data', '2026-01-06 12:51:04', '2026-01-06 12:51:04'),
(4, 3, 'Mr. Colbert', NULL, '+23354336652', 'Ghana', NULL, 'Imported from legacy data', '2026-01-06 12:51:04', '2026-01-06 12:51:04'),
(5, 3, 'Mr. Oluu', NULL, '+233245426554', 'Ghana', NULL, 'Imported from legacy data', '2026-01-06 12:51:04', '2026-01-06 12:51:04'),
(6, 3, 'Mr. Raymond', NULL, '+233245312227', 'Ghana', NULL, 'Imported from legacy data', '2026-01-06 12:51:04', '2026-01-06 12:51:04'),
(7, 3, 'Mr. Dawood', NULL, '+233206663970', 'Ghana', NULL, 'Imported from legacy data', '2026-01-06 12:51:04', '2026-01-06 12:51:04'),
(8, 3, 'Mr. Adamu', NULL, '+233240775540', 'Ghana', NULL, 'Imported from legacy data', '2026-01-06 12:51:04', '2026-01-06 12:51:04'),
(9, 3, 'Samuel', NULL, '+233550190587', 'Ghana', NULL, 'Imported from legacy data', '2026-01-06 12:51:04', '2026-01-06 12:51:04'),
(10, 3, 'Dr. Tetteh', NULL, '+233540280003', 'Ghana', NULL, 'Imported from legacy data', '2026-01-06 12:51:04', '2026-01-06 12:51:04'),
(11, 3, 'Ibrahim', NULL, '+233243413296', 'Ghana', NULL, 'Imported from legacy data', '2026-01-06 12:51:04', '2026-01-06 12:51:04'),
(12, 3, 'Mr. Bart', NULL, '+233242146151', 'Ghana', NULL, 'Imported from legacy data', '2026-01-06 12:51:04', '2026-01-06 12:51:04'),
(13, 3, 'Mr. Eugene', NULL, '+233244126605', 'Ghana', NULL, 'Imported from legacy data', '2026-01-06 12:51:04', '2026-01-06 12:51:04'),
(14, 3, 'Madam Selina', NULL, '+233543080405', 'Ghana', NULL, 'Imported from legacy data', '2026-01-06 12:51:04', '2026-01-06 12:51:04'),
(15, 3, 'Madam Aisha', NULL, '+233531343487', 'Ghana', NULL, 'Imported from legacy data', '2026-01-06 12:51:04', '2026-01-06 12:51:04'),
(16, 2, 'Mr. Chukwu Ugochukwu', NULL, '+2348034276179', 'Nigeria', NULL, 'Imported from legacy data', '2026-01-06 12:51:04', '2026-01-06 12:51:04'),
(17, 2, 'Abduljaleel', NULL, '+2348039289688', 'Nigeria', NULL, 'Imported from legacy data', '2026-01-06 12:51:04', '2026-01-06 12:51:04'),
(18, 2, 'Mr. Henry', NULL, '+2349129069876', 'Nigeria', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(19, 2, 'Jidda Garba', NULL, '+2347035852599', 'Nigeria', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(20, 2, 'Salawu Nureine', NULL, '+2349042668398', 'Nigeria', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(21, 2, 'Mr. Isaac', NULL, '+2348091646592', 'Nigeria', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(22, 2, 'Femi Fatusan', NULL, '+2348060193855', 'Nigeria', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(23, 2, 'Muhammad Abubakar', NULL, '+2348061685567', 'Nigeria', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(24, 2, 'Alh. Abdullahi', NULL, '+2348035073667', 'Nigeria', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(25, 2, 'Muhammad Mashood', NULL, '+2348086948404', 'Nigeria', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(26, 2, 'Al-Sadiq', NULL, '+2348078671733', 'Nigeria', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(27, 2, 'Anthony Etim', NULL, '+2348065904116', 'Nigeria', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(28, 2, 'Ruwaida Dikko', NULL, '+2348065789605', 'Nigeria', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(29, 2, 'Adeleke Ademola', NULL, '+2348037803333', 'Nigeria', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(30, 2, 'Ope Joseph', NULL, '+2348111226209', 'Nigeria', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(31, 2, 'Mr. Tony Nonso Okoye', NULL, '+2348024951715', 'Nigeria', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(32, 2, 'Yakubu Umar', NULL, '+2348034684088', 'Nigeria', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(33, 2, 'Hamza Rabiu', NULL, '+2348139218877', 'Nigeria', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(34, 2, 'Mr. Alade', NULL, '+2348094516454', 'Nigeria', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(35, 2, 'Mr. Owobi Partric', NULL, '+2348036839736', 'Nigeria', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(36, 2, 'Peter Iliya', NULL, '+2348107007279', 'Nigeria', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(37, 2, 'Miss Mercy Danladi', NULL, '+2349056653337', 'Nigeria', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(38, 2, 'Anthony Uchenna', NULL, '+2348132427234', 'Nigeria', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(39, 2, 'Emmanuel Nonso', NULL, '+2348130278500', 'Nigeria', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(40, 2, 'Obiara Aniagu', NULL, '+2348033431514', 'Nigeria', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(41, 2, 'Mr. Sunday Eboigbe', NULL, '+2348036497526', 'Nigeria', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(42, 2, 'Mr. Obinnax', NULL, '+2348136843338', 'Nigeria', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(43, 2, 'Mrs Blessings Jaiyeoba', NULL, '+2348055603877', 'Nigeria', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(44, 2, 'Dr. Johnson', NULL, '+2347031376430', 'Nigeria', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(45, 4, 'Emmanuel Tamba', NULL, '+231778149474', 'Liberia', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(46, 4, 'Mr Jackson Doe', NULL, '+231777644446', 'Liberia', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(47, 4, 'Linda Paye', NULL, '+231778238820', 'Liberia', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(48, 4, 'Vasco Yini', NULL, '+231775676195', 'Liberia', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(49, 4, 'Peter Gaye', NULL, '+231775031703', 'Liberia', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(50, 4, 'Patrick', NULL, '+231770336292', 'Liberia', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(51, 4, 'Alex Gbakoloi Nanoh', NULL, '+231770180880', 'Liberia', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(52, 4, 'Famata Titus', NULL, '+231770390318', 'Liberia', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(53, 4, 'Olatunji A. Adebola', NULL, '+231777971541', 'Liberia', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(54, 4, 'Maranata Nyumah', NULL, '+231880536913', 'Liberia', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(55, 4, 'Kenneth Abdullah', NULL, '+231881530950', 'Liberia', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(56, 4, 'Felton Joe', NULL, '+231555203140', 'Liberia', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(57, 4, 'Hector', NULL, '+231777313100', 'Liberia', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(58, 4, 'Progress', NULL, '+231886578790', 'Liberia', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(59, 4, 'A. Lawrence D. Baimbo', NULL, '+231777444404', 'Liberia', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(60, 4, 'Patrick', NULL, '+231886593589', 'Liberia', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(61, 4, 'Nahn Kollie', NULL, '+231886761134', 'Liberia', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(62, 4, 'Agnes Cephas', NULL, '+231777106255', 'Liberia', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(63, 4, 'Jersey Sumo', NULL, '+231774229905', 'Liberia', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(64, 4, 'Samuel', NULL, '+231776801986', 'Liberia', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(65, 4, 'Eric Togar', NULL, '+231776706260', 'Liberia', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(66, 4, 'Nyenekon Esco Sarweh', NULL, '+231886766391', 'Liberia', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(67, 4, 'Kebeh', NULL, '+231886535905', 'Liberia', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(68, 4, 'Mr Zarwu Gboluma', NULL, '+231888108000', 'Liberia', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(69, 4, 'Boima Johnson', NULL, '+231881656973', 'Liberia', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(70, 4, 'Gifty M. Koygbo', NULL, '+231555535087', 'Liberia', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(71, 4, 'Peter Koryon', NULL, '+231778256117', 'Liberia', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(72, 4, 'Daouda Kromah', NULL, '+23177665442', 'Liberia', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(73, 4, 'Finnee', NULL, '+231777818829', 'Liberia', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(74, 4, 'Venata', NULL, '+231775508450', 'Liberia', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(75, 4, 'Mr Mulbah', NULL, '+231776413089', 'Liberia', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(76, 4, 'Joseph Govo', NULL, '+231886501302', 'Liberia', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(77, 4, 'Fatumata Jabahta', NULL, '+231775243913', 'Liberia', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(78, 4, 'Mr Johnson', NULL, '+231770998856', 'Liberia', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(79, 4, 'Moses Justine', NULL, '+231888101641', 'Liberia', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(80, 4, 'Keselee Steven', NULL, '+231770189490', 'Liberia', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(81, 5, 'Christopher Chale', NULL, '+255784237713', 'Unknown', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(82, 5, 'Mathayo Anthony', NULL, '+255759290698', 'Unknown', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(83, 5, 'Marcelino Mwageni', NULL, '+255754654073', 'Unknown', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(84, 5, 'Kelvin', NULL, '+255629627148', 'Unknown', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(85, 5, 'Godfray', NULL, '+255678145466', 'Unknown', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(86, 5, 'Leocardia Katemi', NULL, '+255769151727', 'Unknown', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(87, 5, 'Adam Harisson', NULL, '+255712051068', 'Unknown', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(88, 5, 'Sarah Diana', NULL, '+255673335203', 'Unknown', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(89, 5, 'Alfred Onzima', NULL, '+256782076158', 'Unknown', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(90, 5, 'Rachel', NULL, '+255768238943', 'Unknown', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(91, 5, 'Dereck', NULL, '+255755796090', 'Unknown', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(92, 5, 'Daniel Sisty', NULL, '+255755535431', 'Unknown', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(93, 5, 'Maganga', NULL, '+255716739898', 'Unknown', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(94, 5, 'Zakayo Tairo', NULL, '+255762454026', 'Unknown', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(95, 5, 'Natasha Hospecious', NULL, '+255783554991', 'Unknown', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(96, 5, 'Richard Shirima', NULL, '+255787409455', 'Unknown', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(97, 5, 'Lefatya', NULL, '+255693925356', 'Unknown', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(98, 5, 'Ibrahim Mponzi', NULL, '+255756488610', 'Unknown', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(99, 5, 'Jabiri', NULL, '+255686782295', 'Unknown', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(100, 5, 'Andrew Urassa', NULL, '+255679353187', 'Unknown', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(101, 5, 'Amos', NULL, '+255742172873', 'Unknown', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(102, 5, 'Kimaro Edward', NULL, '+255713322856', 'Unknown', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(103, 5, 'Issac Bahati Ngaili', NULL, '+255673267677', 'Unknown', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(104, 5, 'Queen Holela', NULL, '+255783477777', 'Unknown', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(105, 5, 'Suma', NULL, '+255754966262', 'Unknown', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(106, 5, 'Waryoba', NULL, '+255674147715', 'Unknown', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(107, 5, 'Silvester Kijichi', NULL, '+255656970737', 'Unknown', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(108, 5, 'Julius Stephen', NULL, '+255752410017', 'Unknown', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(109, 5, 'Matokeo Simba', NULL, '+255753008390', 'Unknown', NULL, 'Imported from legacy data', '2026-01-06 12:51:05', '2026-01-06 12:51:05'),
(111, 4, 'Ma Gorpu Morris', NULL, '+231776518612', 'Liberia', NULL, 'Created instantly from Order page', '2026-01-08 17:09:47', '2026-01-08 17:09:47'),
(112, 4, 'Joseph Koffa', NULL, '+231880577757', 'Liberia', NULL, 'Created instantly from Order page', '2026-01-09 12:13:33', '2026-01-09 12:13:33'),
(113, 2, 'Basiru Sirajo', NULL, '+2348035611156', 'Nigeria', NULL, 'Created instantly from Order page', '2026-01-09 16:10:21', '2026-01-09 16:10:21'),
(114, 2, 'Sadi Haruna', NULL, '+2348069077102', 'Nigeria', NULL, 'Created instantly from Order page', '2026-01-09 16:12:35', '2026-01-09 16:12:35'),
(115, 2, 'Tauheed Amusan', NULL, '+2348033147764', 'Nigeria', NULL, 'Created instantly from Order page', '2026-01-10 13:36:21', '2026-01-10 13:36:21'),
(116, 2, 'Tauheed Amusan', NULL, '+2348033147764', 'Nigeria', NULL, 'Created instantly from Order page', '2026-01-10 13:36:23', '2026-01-10 13:36:23'),
(117, 4, 'Fatu Johnson', NULL, '+231776100568', 'Liberia', NULL, 'Created instantly from Order page', '2026-01-12 15:03:06', '2026-01-12 15:03:06'),
(118, 4, 'Solomon Poloyah', NULL, '+231776715094', 'Liberia', NULL, 'Created instantly from Order page', '2026-01-13 16:18:56', '2026-01-13 16:18:56'),
(119, 4, 'Christopher Peter Beyo', NULL, '+231888102221', 'Liberia', NULL, 'Created instantly from Order page', '2026-01-22 16:13:10', '2026-01-22 16:13:10');

-- --------------------------------------------------------

--
-- Table structure for table `representatives`
--

CREATE TABLE `representatives` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `country` varchar(255) NOT NULL,
  `country_code` varchar(10) DEFAULT NULL COMMENT 'Expected country code for patients (e.g. +91)',
  `region` varchar(255) DEFAULT NULL,
  `phone` varchar(255) NOT NULL,
  `address` text DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `representatives`
--

INSERT INTO `representatives` (`id`, `user_id`, `country`, `country_code`, `region`, `phone`, `address`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 'India', NULL, 'North', '+91 9876543210', NULL, 'active', '2026-01-06 11:30:24', '2026-01-06 11:30:24'),
(2, 3, 'Nigeria', '+234', 'North', '+234 803 678 5766', NULL, 'active', '2026-01-06 11:44:32', '2026-01-08 00:08:39'),
(3, 4, 'Ghana', '+233', 'Full', '+233 24 541 9914', NULL, 'active', '2026-01-06 12:44:23', '2026-01-08 00:08:33'),
(4, 5, 'Liberia', '+231', 'Full', '+231 88 858 9165', NULL, 'active', '2026-01-06 12:45:19', '2026-01-08 00:08:27'),
(5, 6, 'Tanzania', '+255', 'Full', '+255 762 817 648', NULL, 'active', '2026-01-06 12:45:58', '2026-01-08 00:08:18'),
(7, 9, 'Ethiopia', '+251', 'Full', '+251 911 179 093', NULL, 'active', '2026-01-10 02:26:21', '2026-01-10 02:26:21'),
(8, 10, 'India', '+91', 'All states', '09594000093', '103/C, Huma Park, Near Tanwar Nagar, Kausa, Mumbra.\r\nOpp Konkan Chemist', 'active', '2026-01-10 12:09:41', '2026-01-10 12:09:41');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('5UUnkAA1k6qKNyWvdxGGlIUOJs60l5vqL9dUogXf', NULL, '149.28.146.82', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoieUJOU0tjNlRFMWNjamxPcEFOTjl3YVdKdmRFWnd1SXZZVVRBbDFJSiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTU6Imh0dHBzOi8vb25lLmhvb3RvbmUub3JnL2luZGV4LnBocD9yb3V0ZT1jaGVja291dCUyRmNhcnQiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1769662617),
('BEJLvQGg14OBHMlJiFa6YqXGa93VGjo9prEv84XC', NULL, '149.28.146.82', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiWDlsdUF4YkdUeE16TWNCQzU3NDR4SVlGcklOUW5jRlhpSDZ6YTBjWCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjk6Imh0dHBzOi8vb25lLmhvb3RvbmUub3JnL2xvZ2luIjtzOjU6InJvdXRlIjtzOjU6ImxvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1769662617),
('Em9u2iIX6TkyZtKWGtmQCbYJzHnBAi28hwXp331Y', 7, '110.235.224.77', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiS3lMN0NValhlR0R6Um9LMWFJMkRVQkZuOXRUeWZBSzUzSDZ5RjlyRiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzk6Imh0dHBzOi8vb25lLmhvb3RvbmUub3JnL2FkbWluL2Rhc2hib2FyZCI7czo1OiJyb3V0ZSI7czoxNToiYWRtaW4uZGFzaGJvYXJkIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Nzt9', 1769611474),
('FYOXYpkARQ0icyudZ0xoywiyQYeE27mW8sPv5MxZ', NULL, '149.28.146.82', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoienBhS0M2WkFLQzFWQ1RlN0diaElZYnJZbFJtODROU0dKRXozTnNrWCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzk6Imh0dHBzOi8vb25lLmhvb3RvbmUub3JnL2luZGV4LnBocC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1769662618),
('rpJQwHfyYM2OeZbhwyaVJZtWoTMV1fDT79xvgVnm', NULL, '149.28.146.82', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiOGZoMUlRcjltQWtmS0NsQjZzeDJYRzltWDcxYjUxazVPNGJlMXdtWiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjM6Imh0dHBzOi8vb25lLmhvb3RvbmUub3JnIjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1769662617),
('YQ9sWXp4amu5XuBC1EpmkJ8TwBjJtYLNHxetWpEt', 7, '110.235.224.77', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoidjRzRGxjZEo0QjI2WjlJcGlONHZpQ3g4OFI2cDk5SHRSSUJTbnlQeiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzk6Imh0dHBzOi8vb25lLmhvb3RvbmUub3JnL2FkbWluL2Rhc2hib2FyZCI7czo1OiJyb3V0ZSI7czoxNToiYWRtaW4uZGFzaGJvYXJkIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Nzt9', 1769616296);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
(1, 'aisensy_api_key', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6IjY4ZmJiMDEyOTIxZDYwNjBmMTI1ZWRkNSIsIm5hbWUiOiJIb290b25lIEFQSSIsImFwcE5hbWUiOiJBaVNlbnN5IiwiY2xpZW50SWQiOiI2OGUzZjcyNTMwNTgyMjE3ODY2Mjg1ZGYiLCJhY3RpdmVQbGFuIjoiRlJFRV9GT1JFVkVSIiwiaWF0IjoxNzYxMzI1MDc0fQ.NbvFtG4c-7bPj6HT3JlgABJezOpq_T7VvFREtbqgNJM', '2026-01-06 12:21:28', '2026-01-06 12:21:28'),
(2, 'aisensy_campaign_name', 'seo', '2026-01-06 12:21:28', '2026-01-28 14:44:06'),
(3, 'aisensy_health_check_campaign', 'seo', '2026-01-28 14:44:06', '2026-01-28 14:44:06');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('super_admin','representative') NOT NULL DEFAULT 'representative',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'admin@hootone.com', NULL, '$2y$12$cX2FGro0UZQus59Bh9mi1eRcYxedjguFdwE3XvkpQFbU.zYCNxzsi', 'super_admin', NULL, '2026-01-06 11:30:24', '2026-01-06 11:30:24'),
(2, 'John Representative', 'rep@hootone.com', NULL, '$2y$12$YSwf3SuQuGsV6SCsJ8hV3.iNw2M1RyyRs7cHT0UfOD3O6CP/EJr5m', 'representative', NULL, '2026-01-06 11:30:24', '2026-01-06 11:30:24'),
(3, 'Kabir Hamza Kankara', 'kabirkt2003@gmail.com', NULL, '$2y$12$Ft3Hgjr184AQWd82fZV6t.vJjNuJuyoK.XSEtEF9DakAYjNIDtc8S', 'representative', 'iofyHvGupMkfjuAMKYSLlkYCtMfbwVSXbFHLCB613mRC5tzP78Zhq0PQJiP9', '2026-01-06 11:44:32', '2026-01-06 12:43:32'),
(4, 'Samuel Buernortey Puplampu', 'samuelpope419@gmail.com', NULL, '$2y$12$6BG5T1cNK.X6KR8Is.94Xe.UhLyLTBn9Omiui2GLrSTninUm7sV4G', 'representative', NULL, '2026-01-06 12:44:23', '2026-01-06 12:44:23'),
(5, 'Dr. Felix Boye Dimaro', 'dimdenyan1@gmail.com', NULL, '$2y$12$0FuYzeZEKUcc0YV.jciz3OwbzbysZjNk8fzerlp6QhSjXLz3FYgPK', 'representative', '17hQQFZ6TSP8sm7ltVDsX7S2SDXZ9Cy4bzHGAtlPb3V53oNf0YqesvudLOjk', '2026-01-06 12:45:19', '2026-01-06 12:45:19'),
(6, 'Dr. James A. Paul', 'jpmbucha@gmail.com', NULL, '$2y$12$Xb/KrfdlFDw7HeqY0vngQO7hpUVDlskYd3bJsjS1Em2y.Tdvx.QIS', 'representative', NULL, '2026-01-06 12:45:58', '2026-01-06 12:45:58'),
(7, 'ashraf', 'ceo@hootone.org', NULL, '$2y$12$AdL5ro5EPcjjfFHC3SgSDOXA5pI9zMtQDERLof5V5M4ql0A7JPg1e', 'super_admin', '5imHbU5dZXI3dXC8D7ImWTHxlcIYn22o5HkZpvFXUwuzKgMEx4T9HnpobmRa', '2026-01-07 23:54:22', '2026-01-08 14:09:33'),
(9, 'Endale Melese Tsegaye', 'endale@hootone.org', NULL, '$2y$12$Iepd7V7BIZht9DmUWWEAjOx8sRCGqR4..rX8CZ9a7fAz7Ff.cslE2', 'representative', 'kxg75xT8nblR5UFYSKT3vlBTDHmsnLeKEibv1N2vLKrMDrIGrNoLEmSOIRwn', '2026-01-10 02:26:21', '2026-01-10 02:26:21'),
(10, 'COMPANY DIRECT', 'info@hootone.org', NULL, '$2y$12$2zCfpREb/lWcgkz/Bmk4Fe1Gv6sGfgOuiBGh8fInHkxGZYibe1VdS', 'representative', NULL, '2026-01-10 12:09:41', '2026-01-10 12:09:41');

-- --------------------------------------------------------

--
-- Table structure for table `whatsapp_logs`
--

CREATE TABLE `whatsapp_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `patient_id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `phone_number` varchar(255) NOT NULL,
  `message_body` text DEFAULT NULL,
  `status` varchar(255) NOT NULL,
  `response` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`response`)),
  `sent_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `whatsapp_logs`
--

INSERT INTO `whatsapp_logs` (`id`, `patient_id`, `order_id`, `phone_number`, `message_body`, `status`, `response`, `sent_at`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2727272727', 'Reminder Day 0.73638311587963', 'failed', '{\"error\":\"API Key not configured\"}', '2026-01-06 12:10:23', '2026-01-06 12:10:23', '2026-01-06 12:10:23');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `medicines`
--
ALTER TABLE `medicines`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `medicines_code_unique` (`code`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_patient_id_foreign` (`patient_id`),
  ADD KEY `orders_medicine_id_foreign` (`medicine_id`),
  ADD KEY `orders_representative_id_foreign` (`representative_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patients_representative_id_foreign` (`representative_id`);

--
-- Indexes for table `representatives`
--
ALTER TABLE `representatives`
  ADD PRIMARY KEY (`id`),
  ADD KEY `representatives_user_id_foreign` (`user_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_key_unique` (`key`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `whatsapp_logs`
--
ALTER TABLE `whatsapp_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `whatsapp_logs_patient_id_foreign` (`patient_id`),
  ADD KEY `whatsapp_logs_order_id_foreign` (`order_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `medicines`
--
ALTER TABLE `medicines`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=127;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=120;

--
-- AUTO_INCREMENT for table `representatives`
--
ALTER TABLE `representatives`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `whatsapp_logs`
--
ALTER TABLE `whatsapp_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_medicine_id_foreign` FOREIGN KEY (`medicine_id`) REFERENCES `medicines` (`id`),
  ADD CONSTRAINT `orders_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_representative_id_foreign` FOREIGN KEY (`representative_id`) REFERENCES `representatives` (`id`);

--
-- Constraints for table `patients`
--
ALTER TABLE `patients`
  ADD CONSTRAINT `patients_representative_id_foreign` FOREIGN KEY (`representative_id`) REFERENCES `representatives` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `representatives`
--
ALTER TABLE `representatives`
  ADD CONSTRAINT `representatives_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `whatsapp_logs`
--
ALTER TABLE `whatsapp_logs`
  ADD CONSTRAINT `whatsapp_logs_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `whatsapp_logs_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
