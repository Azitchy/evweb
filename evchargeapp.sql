-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 23, 2026 at 06:47 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `evchargeapp`
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
-- Table structure for table `charging_sessions`
--

CREATE TABLE `charging_sessions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `charging_station_id` bigint(20) UNSIGNED DEFAULT NULL,
  `start_percentage` decimal(5,2) NOT NULL,
  `end_percentage` decimal(5,2) DEFAULT NULL,
  `charged_percentage` decimal(5,2) DEFAULT NULL,
  `cost` decimal(12,2) DEFAULT NULL,
  `price_per_percentage` decimal(10,2) NOT NULL,
  `status` enum('charging','completed','cancelled') NOT NULL DEFAULT 'charging',
  `started_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ended_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `charging_sessions`
--

INSERT INTO `charging_sessions` (`id`, `user_id`, `charging_station_id`, `start_percentage`, `end_percentage`, `charged_percentage`, `cost`, `price_per_percentage`, `status`, `started_at`, `ended_at`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 20.00, 85.00, 65.00, 487.50, 7.50, 'completed', '2026-03-17 04:07:34', '2026-03-17 05:07:34', '2026-03-22 00:07:34', '2026-03-22 00:07:34'),
(2, 3, 2, 10.00, 90.00, 80.00, 600.00, 7.50, 'completed', '2026-03-18 09:07:34', '2026-03-18 10:07:34', '2026-03-22 00:07:34', '2026-03-22 00:07:34'),
(3, 4, 3, 30.00, 70.00, 40.00, 300.00, 7.50, 'completed', '2026-03-19 03:07:34', '2026-03-19 04:07:34', '2026-03-22 00:07:34', '2026-03-22 00:07:34'),
(4, 5, 4, 5.00, 95.00, 90.00, 675.00, 7.50, 'completed', '2026-03-20 06:07:34', '2026-03-20 08:07:34', '2026-03-22 00:07:34', '2026-03-22 00:07:34'),
(5, 6, 5, 40.00, 80.00, 40.00, 300.00, 7.50, 'completed', '2026-03-21 11:07:34', '2026-03-21 12:07:34', '2026-03-22 00:07:34', '2026-03-22 00:07:34'),
(6, 2, 1, 15.00, NULL, NULL, NULL, 7.50, 'charging', '2026-03-21 23:37:34', NULL, '2026-03-22 00:07:34', '2026-03-22 00:07:34'),
(7, 3, 3, 50.00, 55.00, 5.00, 37.50, 7.50, 'cancelled', '2026-03-16 05:07:34', '2026-03-16 05:22:34', '2026-03-22 00:07:34', '2026-03-22 00:07:34'),
(8, 4, 2, 25.00, 25.00, 0.00, 0.00, 7.50, 'completed', '2026-03-22 06:28:56', '2026-03-22 00:43:56', '2026-03-22 00:07:34', '2026-03-22 00:43:56');

-- --------------------------------------------------------

--
-- Table structure for table `charging_stations`
--

CREATE TABLE `charging_stations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `latitude` decimal(10,7) NOT NULL,
  `longitude` decimal(10,7) NOT NULL,
  `status` enum('active','inactive','maintenance') NOT NULL DEFAULT 'active',
  `total_ports` int(11) NOT NULL DEFAULT 1,
  `available_ports` int(11) NOT NULL DEFAULT 1,
  `charger_type` varchar(255) NOT NULL DEFAULT 'Type 2',
  `power_kw` decimal(6,2) NOT NULL DEFAULT 22.00,
  `description` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `charging_stations`
--

INSERT INTO `charging_stations` (`id`, `name`, `address`, `latitude`, `longitude`, `status`, `total_ports`, `available_ports`, `charger_type`, `power_kw`, `description`, `image_url`, `created_at`, `updated_at`) VALUES
(1, 'Kathmandu Central Station', 'New Baneshwor, Kathmandu', 27.6915000, 85.3420000, 'active', 4, 3, 'DC Fast', 50.00, 'Main hub in the heart of Kathmandu with DC fast chargers.', NULL, '2026-03-22 00:07:34', '2026-03-22 00:07:34'),
(2, 'Lalitpur EV Hub', 'Pulchowk, Lalitpur', 27.6780000, 85.3165000, 'active', 3, 2, 'AC Level 2', 22.00, 'Convenient Level 2 charging near Pulchowk Campus.', NULL, '2026-03-22 00:07:34', '2026-03-22 00:07:34'),
(3, 'Bhaktapur Charge Point', 'Suryabinayak, Bhaktapur', 27.6710000, 85.4298000, 'active', 2, 2, 'DC Fast', 60.00, 'High-power DC station on the highway corridor.', NULL, '2026-03-22 00:07:34', '2026-03-22 00:07:34'),
(4, 'Pokhara Lakeside Station', 'Lakeside, Pokhara', 28.2096000, 83.9856000, 'active', 3, 3, 'AC Level 2', 22.00, 'Scenic lakeside charging for tourists and locals.', NULL, '2026-03-22 00:07:34', '2026-03-22 00:07:34'),
(5, 'Chitwan EV Station', 'Bharatpur, Chitwan', 27.6833000, 84.4333000, 'active', 2, 1, 'DC Fast', 50.00, 'Terai highway pit-stop for long-range EV trips.', NULL, '2026-03-22 00:07:34', '2026-03-22 00:07:34'),
(6, 'Biratnagar Super Charger', 'Rani, Biratnagar', 26.4525000, 87.2718000, 'maintenance', 4, 0, 'DC Fast', 120.00, 'Under maintenance – ultra-fast charger upgrade in progress.', NULL, '2026-03-22 00:07:34', '2026-03-22 00:07:34'),
(7, 'Butwal Green Charge', 'Traffic Chowk, Butwal', 27.7006000, 83.4486000, 'inactive', 2, 0, 'AC Level 2', 22.00, 'Currently inactive, awaiting grid connection.', NULL, '2026-03-22 00:07:34', '2026-03-22 00:07:34');

-- --------------------------------------------------------

--
-- Table structure for table `device_tokens`
--

CREATE TABLE `device_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `token` varchar(255) NOT NULL,
  `platform` varchar(255) NOT NULL DEFAULT 'android',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `device_tokens`
--

INSERT INTO `device_tokens` (`id`, `user_id`, `token`, `platform`, `created_at`, `updated_at`) VALUES
(1, 2, 'fcm_token_aarav_device1_abc123def456', 'android', '2026-03-22 00:07:34', '2026-03-22 00:07:34'),
(2, 3, 'fcm_token_sita_device1_ghi789jkl012', 'ios', '2026-03-22 00:07:34', '2026-03-22 00:07:34'),
(3, 4, 'fcm_token_binod_device1_mno345pqr678', 'android', '2026-03-22 00:07:34', '2026-03-22 00:07:34'),
(4, 5, 'fcm_token_priya_device1_stu901vwx234', 'ios', '2026-03-22 00:07:34', '2026-03-22 00:07:34'),
(5, 6, 'fcm_token_rajesh_device1_yza567bcd890', 'android', '2026-03-22 00:07:34', '2026-03-22 00:07:34');

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
-- Table structure for table `iot_devices`
--

CREATE TABLE `iot_devices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `charging_station_id` bigint(20) UNSIGNED NOT NULL,
  `device_id` varchar(255) NOT NULL,
  `device_name` varchar(255) NOT NULL,
  `status` enum('online','offline','error') NOT NULL DEFAULT 'offline',
  `firmware_version` varchar(255) DEFAULT NULL,
  `current_power_kw` decimal(6,2) NOT NULL DEFAULT 0.00,
  `voltage` decimal(6,2) NOT NULL DEFAULT 0.00,
  `current_amps` decimal(6,2) NOT NULL DEFAULT 0.00,
  `temperature` decimal(5,2) NOT NULL DEFAULT 0.00,
  `last_heartbeat_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `iot_devices`
--

INSERT INTO `iot_devices` (`id`, `charging_station_id`, `device_id`, `device_name`, `status`, `firmware_version`, `current_power_kw`, `voltage`, `current_amps`, `temperature`, `last_heartbeat_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'IOT-KTM-001', 'KTM DC Charger A', 'online', 'v2.1.0', 48.50, 400.00, 121.25, 42.30, '2026-03-22 00:05:34', '2026-03-22 00:07:34', '2026-03-22 00:07:34'),
(2, 1, 'IOT-KTM-002', 'KTM DC Charger B', 'online', 'v2.1.0', 0.00, 0.00, 0.00, 28.10, '2026-03-22 00:06:34', '2026-03-22 00:07:34', '2026-03-22 00:07:34'),
(3, 2, 'IOT-LAL-001', 'Lalitpur AC Unit 1', 'online', 'v1.8.3', 21.50, 230.00, 93.48, 38.50, '2026-03-22 00:04:34', '2026-03-22 00:07:34', '2026-03-22 00:07:34'),
(4, 3, 'IOT-BKT-001', 'Bhaktapur DC Unit 1', 'offline', 'v2.0.1', 0.00, 0.00, 0.00, 25.00, '2026-03-21 18:07:34', '2026-03-22 00:07:34', '2026-03-22 00:07:34'),
(5, 4, 'IOT-PKR-001', 'Pokhara AC Unit 1', 'online', 'v1.8.3', 18.00, 230.00, 78.26, 35.20, '2026-03-22 00:06:34', '2026-03-22 00:07:34', '2026-03-22 00:07:34'),
(6, 5, 'IOT-CTW-001', 'Chitwan DC Unit 1', 'online', 'v2.1.0', 45.00, 400.00, 112.50, 44.00, '2026-03-22 00:02:34', '2026-03-22 00:07:34', '2026-03-22 00:07:34'),
(7, 6, 'IOT-BRT-001', 'Biratnagar Super Charger', 'error', 'v3.0.0-beta', 0.00, 0.00, 0.00, 65.00, '2026-03-21 12:07:34', '2026-03-22 00:07:34', '2026-03-22 00:07:34');

-- --------------------------------------------------------

--
-- Table structure for table `iot_telemetry`
--

CREATE TABLE `iot_telemetry` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `iot_device_id` bigint(20) UNSIGNED NOT NULL,
  `charging_session_id` bigint(20) UNSIGNED DEFAULT NULL,
  `power_kw` decimal(6,2) NOT NULL,
  `voltage` decimal(6,2) NOT NULL,
  `current_amps` decimal(6,2) NOT NULL,
  `temperature` decimal(5,2) NOT NULL,
  `energy_kwh` decimal(8,3) NOT NULL DEFAULT 0.000,
  `battery_percentage` decimal(5,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `iot_telemetry`
--

INSERT INTO `iot_telemetry` (`id`, `iot_device_id`, `charging_session_id`, `power_kw`, `voltage`, `current_amps`, `temperature`, `energy_kwh`, `battery_percentage`, `created_at`, `updated_at`) VALUES
(1, 1, 6, 48.50, 400.00, 121.25, 42.30, 12.125, 35.00, '2026-03-22 00:07:34', '2026-03-22 00:07:34'),
(2, 1, 6, 47.80, 398.00, 120.10, 43.10, 24.075, 50.00, '2026-03-22 00:07:34', '2026-03-22 00:07:34'),
(3, 1, 1, 50.00, 402.00, 124.38, 40.50, 35.000, 85.00, '2026-03-22 00:07:34', '2026-03-22 00:07:34'),
(4, 3, 8, 21.50, 230.00, 93.48, 38.50, 3.583, 32.00, '2026-03-22 00:07:34', '2026-03-22 00:07:34'),
(5, 3, 2, 22.00, 231.00, 95.24, 37.00, 22.000, 90.00, '2026-03-22 00:07:34', '2026-03-22 00:07:34'),
(6, 5, 4, 20.00, 229.00, 87.34, 36.00, 18.500, 70.00, '2026-03-22 00:07:34', '2026-03-22 00:07:34'),
(7, 5, 4, 18.00, 228.00, 78.95, 35.50, 30.000, 95.00, '2026-03-22 00:07:34', '2026-03-22 00:07:34'),
(8, 6, 5, 45.00, 400.00, 112.50, 44.00, 15.000, 60.00, '2026-03-22 00:07:34', '2026-03-22 00:07:34'),
(9, 6, 5, 42.00, 398.00, 105.53, 45.20, 28.000, 80.00, '2026-03-22 00:07:34', '2026-03-22 00:07:34'),
(10, 4, 3, 55.00, 405.00, 135.80, 41.00, 20.000, 70.00, '2026-03-22 00:07:34', '2026-03-22 00:07:34');

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
(4, '2026_03_21_000001_create_wallets_table', 1),
(5, '2026_03_21_000002_create_pricing_settings_table', 1),
(6, '2026_03_21_000003_create_charging_sessions_table', 1),
(7, '2026_03_21_000004_create_transactions_table', 1),
(8, '2026_03_21_100001_create_charging_stations_table', 1),
(9, '2026_03_21_100002_create_payment_tables', 1),
(10, '2026_03_21_100003_create_subscription_tables', 1),
(11, '2026_03_21_100004_create_notification_tables', 1),
(12, '2026_03_21_100005_create_iot_tables', 1),
(13, '2026_03_22_062635_create_personal_access_tokens_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'general',
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`data`)),
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `title`, `body`, `type`, `data`, `is_read`, `created_at`, `updated_at`) VALUES
(1, 2, 'Charging Complete', 'Your EV has been charged to 85%. Total cost: Rs. 487.50.', 'charging', '{\"session_id\":1}', 1, '2026-03-22 00:07:34', '2026-03-22 00:07:34'),
(2, 3, 'Wallet Top-up Successful', 'Rs. 3,000 has been added to your wallet via Khalti.', 'payment', '{\"amount\":3000}', 1, '2026-03-22 00:07:34', '2026-03-22 00:07:34'),
(3, 4, 'Subscription Expired', 'Your Standard plan has expired. Renew to keep enjoying discounts.', 'subscription', '{\"plan\":\"Standard\"}', 0, '2026-03-22 00:07:34', '2026-03-22 00:07:34'),
(4, 5, 'Welcome to Enterprise!', 'You now enjoy 25% discount and priority support on all sessions.', 'subscription', '{\"plan\":\"Enterprise\"}', 1, '2026-03-22 00:07:34', '2026-03-22 00:07:34'),
(5, 2, 'Session In Progress', 'Your charging session at Kathmandu Central Station has started.', 'charging', '{\"session_id\":6}', 0, '2026-03-22 00:07:34', '2026-03-22 00:07:34'),
(6, 6, 'Account Deactivated', 'Your account has been deactivated. Contact support for assistance.', 'general', NULL, 0, '2026-03-22 00:07:34', '2026-03-22 00:07:34'),
(7, 3, 'New Station Nearby', 'Bhaktapur Charge Point is now available near you with DC Fast chargers.', 'general', NULL, 0, '2026-03-22 00:07:34', '2026-03-22 00:07:34'),
(8, 4, 'Subscription Activated', 'Your Basic plan is now active until 21 Apr 2026.', 'subscription', NULL, 0, '2026-03-22 00:45:32', '2026-03-22 00:45:32');

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
-- Table structure for table `payment_methods`
--

CREATE TABLE `payment_methods` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `gateway` enum('esewa','khalti') NOT NULL,
  `gateway_customer_id` varchar(255) DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_methods`
--

INSERT INTO `payment_methods` (`id`, `user_id`, `gateway`, `gateway_customer_id`, `is_default`, `created_at`, `updated_at`) VALUES
(1, 2, 'esewa', 'esewa_cust_1001', 1, '2026-03-22 00:07:34', '2026-03-22 00:07:34'),
(2, 2, 'khalti', 'khalti_cust_1001', 0, '2026-03-22 00:07:34', '2026-03-22 00:07:34'),
(3, 3, 'khalti', 'khalti_cust_1002', 1, '2026-03-22 00:07:34', '2026-03-22 00:07:34'),
(4, 4, 'esewa', 'esewa_cust_1003', 1, '2026-03-22 00:07:34', '2026-03-22 00:07:34'),
(5, 5, 'khalti', 'khalti_cust_1004', 1, '2026-03-22 00:07:34', '2026-03-22 00:07:34'),
(6, 6, 'esewa', 'esewa_cust_1005', 1, '2026-03-22 00:07:34', '2026-03-22 00:07:34');

-- --------------------------------------------------------

--
-- Table structure for table `payment_transactions`
--

CREATE TABLE `payment_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `wallet_id` bigint(20) UNSIGNED NOT NULL,
  `gateway` varchar(255) NOT NULL,
  `gateway_transaction_id` varchar(255) DEFAULT NULL,
  `amount` decimal(12,2) NOT NULL,
  `status` enum('initiated','completed','failed','refunded') NOT NULL DEFAULT 'initiated',
  `purpose` varchar(255) NOT NULL DEFAULT 'wallet_topup',
  `gateway_response` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`gateway_response`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_transactions`
--

INSERT INTO `payment_transactions` (`id`, `user_id`, `wallet_id`, `gateway`, `gateway_transaction_id`, `amount`, `status`, `purpose`, `gateway_response`, `created_at`, `updated_at`) VALUES
(1, 2, 2, 'esewa', 'TXN_ESW_20260317_001', 2000.00, 'completed', 'wallet_topup', '{\"code\":\"SUCCESS\",\"ref\":\"ESW001\"}', '2026-03-22 00:07:34', '2026-03-22 00:07:34'),
(2, 3, 3, 'khalti', 'TXN_KHL_20260317_002', 3000.00, 'completed', 'wallet_topup', '{\"code\":\"SUCCESS\",\"ref\":\"KHL002\"}', '2026-03-22 00:07:34', '2026-03-22 00:07:34'),
(3, 4, 4, 'esewa', 'TXN_ESW_20260318_003', 1500.00, 'completed', 'wallet_topup', '{\"code\":\"SUCCESS\",\"ref\":\"ESW003\"}', '2026-03-22 00:07:34', '2026-03-22 00:07:34'),
(4, 5, 5, 'khalti', 'TXN_KHL_20260319_004', 5000.00, 'completed', 'wallet_topup', '{\"code\":\"SUCCESS\",\"ref\":\"KHL004\"}', '2026-03-22 00:07:34', '2026-03-22 00:07:34'),
(5, 6, 6, 'esewa', 'TXN_ESW_20260320_005', 500.00, 'completed', 'wallet_topup', '{\"code\":\"SUCCESS\",\"ref\":\"ESW005\"}', '2026-03-22 00:07:34', '2026-03-22 00:07:34'),
(6, 2, 2, 'khalti', NULL, 1000.00, 'failed', 'wallet_topup', '{\"code\":\"INSUFFICIENT_BALANCE\"}', '2026-03-22 00:07:34', '2026-03-22 00:07:34');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 4, 'auth-token', '2e523018cca2afca05da5713b0c7d4e86ad9c99498aad1490f1f790c965b46c2', '[\"*\"]', '2026-03-22 00:49:17', NULL, '2026-03-22 00:43:05', '2026-03-22 00:49:17');

-- --------------------------------------------------------

--
-- Table structure for table `pricing_settings`
--

CREATE TABLE `pricing_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `price_per_percentage` decimal(10,2) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pricing_settings`
--

INSERT INTO `pricing_settings` (`id`, `price_per_percentage`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 5.00, 0, '2026-03-22 00:07:34', '2026-03-22 00:07:34'),
(2, 6.00, 0, '2026-03-22 00:07:34', '2026-03-22 00:07:34'),
(3, 6.50, 0, '2026-03-22 00:07:34', '2026-03-22 00:07:34'),
(4, 7.00, 0, '2026-03-22 00:07:34', '2026-03-22 00:07:34'),
(5, 7.50, 1, '2026-03-22 00:07:34', '2026-03-22 00:07:34');

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
('I14UBbXVBoOY3Vygu2MnDDlI2ncktc03oZYIeZ2l', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiamRRVnpCVVRnM1hKeU1vMUpSZVk2SEhQYnhoSkxKdlBxcE1GUmdkcCI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjIxOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAiO3M6NToicm91dGUiO3M6MTM6IndlYi5kYXNoYm9hcmQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1774197911),
('NSS2BJNm1emfVc5aB61uSr0c5Qbcw3vodWCf6Pbe', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiOTViMzdpMTlyQnBEenpCM1FsUVRyVE1Ra1pWUlRndDV0eUtFOXRUMSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyMToiaHR0cDovLzEyNy4wLjAuMTo4MDAwIjt9czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo5OiJ3ZWIubG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1774193935);

-- --------------------------------------------------------

--
-- Table structure for table `subscription_plans`
--

CREATE TABLE `subscription_plans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `duration_days` int(11) NOT NULL,
  `discount_percentage` decimal(5,2) NOT NULL DEFAULT 0.00,
  `free_charging_percentage` decimal(8,2) NOT NULL DEFAULT 0.00,
  `priority_support` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subscription_plans`
--

INSERT INTO `subscription_plans` (`id`, `name`, `description`, `price`, `duration_days`, `discount_percentage`, `free_charging_percentage`, `priority_support`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Basic', 'Get 5% discount on all charging sessions', 299.00, 30, 5.00, 0.00, 0, 1, '2026-03-22 00:07:34', '2026-03-22 00:07:34'),
(2, 'Standard', 'Get 10% discount on all charging sessions', 499.00, 30, 10.00, 0.00, 0, 1, '2026-03-22 00:07:34', '2026-03-22 00:07:34'),
(3, 'Premium', 'Get 15% discount and priority support', 799.00, 30, 15.00, 0.00, 1, 1, '2026-03-22 00:07:34', '2026-03-22 00:07:34'),
(4, 'Enterprise', 'Get 25% discount, 10% free charging, and priority support', 1499.00, 30, 25.00, 10.00, 1, 1, '2026-03-22 00:07:34', '2026-03-22 00:07:34'),
(5, 'Annual Pro', 'Get 30% discount, 15% free charging, and priority support for a full year', 9999.00, 365, 30.00, 15.00, 1, 1, '2026-03-22 00:07:34', '2026-03-22 00:07:34');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `wallet_id` bigint(20) UNSIGNED NOT NULL,
  `charging_session_id` bigint(20) UNSIGNED DEFAULT NULL,
  `type` enum('credit','debit') NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `balance_after` decimal(12,2) NOT NULL,
  `description` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `user_id`, `wallet_id`, `charging_session_id`, `type`, `amount`, `balance_after`, `description`, `created_at`, `updated_at`) VALUES
(1, 2, 2, NULL, 'credit', 2000.00, 2000.00, 'Wallet top-up via eSewa', '2026-03-22 00:07:34', '2026-03-22 00:07:34'),
(2, 3, 3, NULL, 'credit', 3000.00, 3000.00, 'Wallet top-up via Khalti', '2026-03-22 00:07:34', '2026-03-22 00:07:34'),
(3, 4, 4, NULL, 'credit', 1500.00, 1500.00, 'Wallet top-up via eSewa', '2026-03-22 00:07:34', '2026-03-22 00:07:34'),
(4, 5, 5, NULL, 'credit', 5000.00, 5000.00, 'Wallet top-up via Khalti', '2026-03-22 00:07:34', '2026-03-22 00:07:34'),
(5, 6, 6, NULL, 'credit', 500.00, 500.00, 'Wallet top-up via eSewa', '2026-03-22 00:07:34', '2026-03-22 00:07:34'),
(6, 2, 2, 1, 'debit', 487.50, 1500.00, 'Charging session #1', '2026-03-22 00:07:34', '2026-03-22 00:07:34'),
(7, 3, 3, 2, 'debit', 600.00, 2300.50, 'Charging session #2', '2026-03-22 00:07:34', '2026-03-22 00:07:34'),
(8, 4, 4, 3, 'debit', 300.00, 800.00, 'Charging session #3', '2026-03-22 00:07:34', '2026-03-22 00:07:34'),
(9, 5, 5, 4, 'debit', 675.00, 4325.00, 'Charging session #4', '2026-03-22 00:07:34', '2026-03-22 00:07:34'),
(10, 6, 6, 5, 'debit', 300.00, 120.75, 'Charging session #5', '2026-03-22 00:07:34', '2026-03-22 00:07:34'),
(11, 4, 4, NULL, 'credit', 100.00, 900.00, 'Wallet top-up', '2026-03-22 00:44:44', '2026-03-22 00:44:44'),
(12, 4, 4, NULL, 'debit', 299.00, 601.00, 'Wallet deduction', '2026-03-22 00:45:31', '2026-03-22 00:45:31');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') NOT NULL DEFAULT 'user',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `email_verified_at`, `password`, `role`, `is_active`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@evcharging.com', NULL, NULL, '$2y$12$zsgvD5mVAtAxszmrKKTVl.iFcrecFVYEhMiQRySZFIvDZpU9IAiUG', 'admin', 1, NULL, '2026-03-22 00:07:32', '2026-03-22 00:07:32'),
(2, 'Aarav Sharma', 'aarav@evcharging.com', '9841000001', NULL, '$2y$12$zsgvD5mVAtAxszmrKKTVl.iFcrecFVYEhMiQRySZFIvDZpU9IAiUG', 'user', 1, NULL, '2026-03-22 00:07:33', '2026-03-22 00:07:33'),
(3, 'Sita Thapa', 'sita@evcharging.com', '9841000002', NULL, '$2y$12$wYGnmhk4voZjw6zFD4pcHuBXX5f.Rk5X0YVXIHJhNGd9y6nl3zFtm', 'user', 1, NULL, '2026-03-22 00:07:33', '2026-03-22 00:07:33'),
(4, 'azu', 'azu@evcharging.com', '9841000003', NULL, '$2y$12$euq3qJyieOMiCj6f9H62pOktK79.Aiqjel0s66Pat1VhVv0BY5Qk.', 'user', 1, NULL, '2026-03-22 00:07:33', '2026-03-22 00:20:25'),
(5, 'Priya Maharjan', 'priya@evcharging.com', '9841000004', NULL, '$2y$12$Q4AHT3stNXsKKz3LSp15teclGYQFhiXF6tFtm9Lyf0SDqBTS94qWC', 'user', 1, NULL, '2026-03-22 00:07:34', '2026-03-22 00:07:34'),
(6, 'Rajesh Shrestha', 'rajesh@evcharging.com', '9841000005', NULL, '$2y$12$sDHM1HjsMahkidbHKBs9keBhAg6yYH0qPejDNUAZ6krEi7sX/1ZHW', 'user', 0, NULL, '2026-03-22 00:07:34', '2026-03-22 00:07:34');

-- --------------------------------------------------------

--
-- Table structure for table `user_subscriptions`
--

CREATE TABLE `user_subscriptions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `subscription_plan_id` bigint(20) UNSIGNED NOT NULL,
  `starts_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('active','expired','cancelled') NOT NULL DEFAULT 'active',
  `amount_paid` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_subscriptions`
--

INSERT INTO `user_subscriptions` (`id`, `user_id`, `subscription_plan_id`, `starts_at`, `expires_at`, `status`, `amount_paid`, `created_at`, `updated_at`) VALUES
(1, 2, 3, '2026-03-12 00:07:34', '2026-04-11 00:07:34', 'active', 799.00, '2026-03-22 00:07:34', '2026-03-22 00:07:34'),
(2, 3, 1, '2026-02-25 00:07:34', '2026-03-27 00:07:34', 'active', 299.00, '2026-03-22 00:07:34', '2026-03-22 00:07:34'),
(3, 4, 2, '2026-02-10 00:07:34', '2026-03-12 00:07:34', 'expired', 499.00, '2026-03-22 00:07:34', '2026-03-22 00:07:34'),
(4, 5, 4, '2026-03-17 00:07:34', '2026-04-16 00:07:34', 'active', 1499.00, '2026-03-22 00:07:34', '2026-03-22 00:07:34'),
(5, 6, 1, '2026-02-15 00:07:34', '2026-03-17 00:07:34', 'cancelled', 299.00, '2026-03-22 00:07:34', '2026-03-22 00:07:34'),
(6, 4, 1, '2026-03-22 00:45:31', '2026-04-21 00:45:31', 'active', 299.00, '2026-03-22 00:45:31', '2026-03-22 00:45:31');

-- --------------------------------------------------------

--
-- Table structure for table `wallets`
--

CREATE TABLE `wallets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `balance` decimal(12,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wallets`
--

INSERT INTO `wallets` (`id`, `user_id`, `balance`, `created_at`, `updated_at`) VALUES
(1, 1, 0.00, '2026-03-22 00:07:34', '2026-03-22 00:07:34'),
(2, 2, 1500.00, '2026-03-22 00:07:34', '2026-03-22 00:07:34'),
(3, 3, 2300.50, '2026-03-22 00:07:34', '2026-03-22 00:07:34'),
(4, 4, 601.00, '2026-03-22 00:07:34', '2026-03-22 00:45:31'),
(5, 5, 5000.00, '2026-03-22 00:07:34', '2026-03-22 00:07:34'),
(6, 6, 120.75, '2026-03-22 00:07:34', '2026-03-22 00:07:34');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `charging_sessions`
--
ALTER TABLE `charging_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `charging_sessions_user_id_foreign` (`user_id`),
  ADD KEY `charging_sessions_charging_station_id_foreign` (`charging_station_id`);

--
-- Indexes for table `charging_stations`
--
ALTER TABLE `charging_stations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `device_tokens`
--
ALTER TABLE `device_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `device_tokens_user_id_token_unique` (`user_id`,`token`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `iot_devices`
--
ALTER TABLE `iot_devices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `iot_devices_device_id_unique` (`device_id`),
  ADD KEY `iot_devices_charging_station_id_foreign` (`charging_station_id`);

--
-- Indexes for table `iot_telemetry`
--
ALTER TABLE `iot_telemetry`
  ADD PRIMARY KEY (`id`),
  ADD KEY `iot_telemetry_iot_device_id_foreign` (`iot_device_id`),
  ADD KEY `iot_telemetry_charging_session_id_foreign` (`charging_session_id`);

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
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_user_id_foreign` (`user_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payment_methods_user_id_foreign` (`user_id`);

--
-- Indexes for table `payment_transactions`
--
ALTER TABLE `payment_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payment_transactions_user_id_foreign` (`user_id`),
  ADD KEY `payment_transactions_wallet_id_foreign` (`wallet_id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Indexes for table `pricing_settings`
--
ALTER TABLE `pricing_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `subscription_plans`
--
ALTER TABLE `subscription_plans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transactions_user_id_foreign` (`user_id`),
  ADD KEY `transactions_wallet_id_foreign` (`wallet_id`),
  ADD KEY `transactions_charging_session_id_foreign` (`charging_session_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `user_subscriptions`
--
ALTER TABLE `user_subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_subscriptions_user_id_foreign` (`user_id`),
  ADD KEY `user_subscriptions_subscription_plan_id_foreign` (`subscription_plan_id`);

--
-- Indexes for table `wallets`
--
ALTER TABLE `wallets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wallets_user_id_foreign` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `charging_sessions`
--
ALTER TABLE `charging_sessions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `charging_stations`
--
ALTER TABLE `charging_stations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `device_tokens`
--
ALTER TABLE `device_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `iot_devices`
--
ALTER TABLE `iot_devices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `iot_telemetry`
--
ALTER TABLE `iot_telemetry`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `payment_transactions`
--
ALTER TABLE `payment_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pricing_settings`
--
ALTER TABLE `pricing_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `subscription_plans`
--
ALTER TABLE `subscription_plans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `user_subscriptions`
--
ALTER TABLE `user_subscriptions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `wallets`
--
ALTER TABLE `wallets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `charging_sessions`
--
ALTER TABLE `charging_sessions`
  ADD CONSTRAINT `charging_sessions_charging_station_id_foreign` FOREIGN KEY (`charging_station_id`) REFERENCES `charging_stations` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `charging_sessions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `device_tokens`
--
ALTER TABLE `device_tokens`
  ADD CONSTRAINT `device_tokens_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `iot_devices`
--
ALTER TABLE `iot_devices`
  ADD CONSTRAINT `iot_devices_charging_station_id_foreign` FOREIGN KEY (`charging_station_id`) REFERENCES `charging_stations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `iot_telemetry`
--
ALTER TABLE `iot_telemetry`
  ADD CONSTRAINT `iot_telemetry_charging_session_id_foreign` FOREIGN KEY (`charging_session_id`) REFERENCES `charging_sessions` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `iot_telemetry_iot_device_id_foreign` FOREIGN KEY (`iot_device_id`) REFERENCES `iot_devices` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD CONSTRAINT `payment_methods_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payment_transactions`
--
ALTER TABLE `payment_transactions`
  ADD CONSTRAINT `payment_transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payment_transactions_wallet_id_foreign` FOREIGN KEY (`wallet_id`) REFERENCES `wallets` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_charging_session_id_foreign` FOREIGN KEY (`charging_session_id`) REFERENCES `charging_sessions` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transactions_wallet_id_foreign` FOREIGN KEY (`wallet_id`) REFERENCES `wallets` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_subscriptions`
--
ALTER TABLE `user_subscriptions`
  ADD CONSTRAINT `user_subscriptions_subscription_plan_id_foreign` FOREIGN KEY (`subscription_plan_id`) REFERENCES `subscription_plans` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_subscriptions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wallets`
--
ALTER TABLE `wallets`
  ADD CONSTRAINT `wallets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
