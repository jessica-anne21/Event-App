-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 24, 2025 at 03:23 PM
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
-- Database: `event_registration_db`
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
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `location` varchar(255) NOT NULL,
  `speaker` varchar(255) NOT NULL,
  `poster_path` varchar(255) DEFAULT NULL,
  `registration_fee` decimal(10,2) NOT NULL DEFAULT 0.00,
  `max_participants` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `name`, `date`, `time`, `location`, `speaker`, `poster_path`, `registration_fee`, `max_participants`, `description`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'Seminar AI', '2025-06-03', '14:24:00', 'Gedung GWM', 'Dr. Jane Doe', 'event_posters/rgBjidVU13uPnV5SJK0JcnCFsUw2eSyk1IIpCPCX.jpg', 100000.00, 100, NULL, 5, '2025-06-03 00:24:45', '2025-06-03 00:24:45'),
(2, 'IoT Championship', '2025-06-04', '15:23:00', 'Lantai 8, Gedung GWM', 'Bapak Bernard', 'event_posters/fMBusqU4GYr6cjhE4id9do3rmCrV0oC25HZatqVx.jpg', 200000.00, 50, 'Kegiatan ini berupa lomba IoT yang mencakup sesi Technical Meeting, Live Coding, dan Awarding.', 5, '2025-06-04 01:24:49', '2025-06-04 01:24:49'),
(3, 'Programming Competition', '2025-06-08', '12:30:00', 'Exhibition Hall, Gedung GSG, Universitas Kristen Maranatha', 'Bpk. Robby Tan, S. T, M. Kom', 'event_posters/4JJ40UGTgU0VZxo3bQKMNPTtxJePCXbXEIdakhE4.jpg', 200000.00, 30, 'Lomba programming kompetitif tingkat internasional.', 5, '2025-06-08 05:25:59', '2025-06-08 06:13:44'),
(4, 'Test', '2025-06-11', '15:26:00', 'Gedung GWM', 'Bapak Bernard', NULL, 0.00, 2, NULL, 5, '2025-06-11 01:26:38', '2025-06-11 01:26:38'),
(5, 'Cloud Computing Seminar', '2025-06-24', '19:28:00', 'GWM Lt. 3', 'Dr. Jonathan', 'event_posters/33EwyHDKoq8uvM4uUIc62mTTkimF9uqxuD9IHdGO.jpg', 100000.00, 20, 'Ini adalah seminar cloud computing.', 5, '2025-06-24 05:29:09', '2025-06-24 05:29:09');

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
(4, '2025_06_03_061032_create_events_table', 1),
(5, '2025_06_03_061055_create_registrations_table', 1),
(6, '2025_06_03_063416_create_personal_access_tokens_table', 1),
(7, '2025_06_03_073852_create_sub_events_table', 2),
(8, '2025_06_03_073853_create_session_registrations_table', 2);

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
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `registrations`
--

CREATE TABLE `registrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `event_id` bigint(20) UNSIGNED NOT NULL,
  `registration_code` varchar(255) NOT NULL,
  `payment_status` enum('pending','paid','cancelled') NOT NULL DEFAULT 'pending',
  `payment_proof_path` varchar(255) DEFAULT NULL,
  `attended` tinyint(1) NOT NULL DEFAULT 0,
  `certificate_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `registrations`
--

INSERT INTO `registrations` (`id`, `user_id`, `event_id`, `registration_code`, `payment_status`, `payment_proof_path`, `attended`, `certificate_path`, `created_at`, `updated_at`) VALUES
(3, 2, 1, 'MtreOKd6T11748935849', 'paid', 'payment_proofs/em9ilcx0UndX3My4Qvv2MBj5gfGRxsKzh0xj7KjW.png', 1, 'certificates/XRupfrjRDwjLv8754U0NunaiPwLMppU7Oe7Qrs9u.pdf', '2025-06-03 00:30:49', '2025-06-03 10:38:11'),
(4, 6, 1, 'jF48u4MSUc1749015830', 'paid', 'payment_proofs/JCYU0uivHdFNU6B1Hbt0JMWv6thcRAEuWG4iU2DM.png', 1, 'certificates/95vlEaMkEFpLKzuJ5xCoGXzFlmFJadKvcde788WH.pdf', '2025-06-03 22:43:50', '2025-06-03 22:46:28'),
(5, 2, 2, 'ju6B6qc3NZ1749026353', 'paid', 'payment_proofs/ntV7jxcagrVMZUp3RyAZsiqUhqiZQHlib75Rjrsg.jpg', 1, 'certificates/m3uUC8LfAsUtWlRgWrVu2g5180RYnHY39VWGyL7G.pdf', '2025-06-04 01:39:13', '2025-06-04 01:41:11'),
(7, 6, 2, 'M89y9rqscK1749026633', 'paid', 'payment_proofs/pbNNoc5IqAqYDSIoY7HOfH73vB0Pytj2u2Aj4ajb.jpg', 0, NULL, '2025-06-04 01:43:53', '2025-06-04 01:44:16'),
(8, 9, 1, '593iHe6Hd21749026891', 'pending', NULL, 0, NULL, '2025-06-04 01:48:11', '2025-06-04 01:48:29'),
(9, 10, 1, 'VI95D4TyJW1749294132', 'pending', NULL, 0, NULL, '2025-06-07 04:02:12', '2025-06-07 04:02:12'),
(10, 9, 3, 'D81fSOWDtH1749385600', 'paid', 'payment_proofs/ShGlzVz3uV6uZdc3QiuTgRt0wMhu7SjtxZc3s3lZ.jpg', 0, NULL, '2025-06-08 05:26:40', '2025-06-11 01:30:50'),
(11, 11, 2, 'inEgXEuqaf1749386895', 'paid', 'payment_proofs/eTIK0eIXmQg1QzVdqsFPtXttJRKJpXlOFkKa7Tzq.jpg', 1, 'certificates/VKKyNPKFMJ4vC3U0ZMYBd085bIOj5kud0p3G7xtS.pdf', '2025-06-08 05:48:15', '2025-06-08 06:07:54'),
(12, 9, 4, '6JxCbjbTTW1749630419', 'pending', NULL, 0, NULL, '2025-06-11 01:26:59', '2025-06-11 01:26:59'),
(13, 6, 4, 'BmFZWDVrad1749630443', 'paid', 'payment_proofs/WrJv9hjE2PkYF4ln2pllQOzTG59QBl4uwHYqt4L0.png', 0, NULL, '2025-06-11 01:27:23', '2025-06-11 01:30:51'),
(14, 11, 4, 'JFHxA1Me8J1749630467', 'paid', 'payment_proofs/u6ug4XJr1ki6jdmLk9eMFfDX2Pqn12s5iu6IH4hr.png', 0, NULL, '2025-06-11 01:27:47', '2025-06-11 01:30:54'),
(15, 12, 2, '8Gpx5dRpC31750766443', 'paid', 'payment_proofs/i2n7rhI3bRzFodsck7b9bRSnkwy0kb6uWgFWRU3u.jpg', 1, NULL, '2025-06-24 05:00:43', '2025-06-24 05:41:05'),
(16, 13, 1, '1rUB651mRo1750767249', 'paid', 'payment_proofs/VSwdermJbK8MUSaVX6HvLy2A5HEU7EvsONJy7XZQ.jpg', 1, NULL, '2025-06-24 05:14:09', '2025-06-24 05:44:36'),
(17, 14, 1, 'VFdMSxRn1f1750767550', 'paid', 'payment_proofs/Glci47Y3yalT8NEUrcV2025b3YQ7wWqBqCVCMzv2.jpg', 1, 'certificates/aZoIwQ8goNN7mBT4oRsLKVM0uhvMKP9wp7r35I5m.pdf', '2025-06-24 05:19:10', '2025-06-24 05:25:17');

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
('8DhaZE4KJfYWH6d5M1N3qb60yMk9AUSYNNtAZ5Ug', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNWVQMDgxVDF2R21XUVBxNjRnMEM3Y1dZcEZwTEZ6Z1BCdmxyclJCYyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fX0=', 1750769543);

-- --------------------------------------------------------

--
-- Table structure for table `session_registrations`
--

CREATE TABLE `session_registrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `registration_id` bigint(20) UNSIGNED NOT NULL,
  `sub_event_id` bigint(20) UNSIGNED NOT NULL,
  `attended_session` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `session_registrations`
--

INSERT INTO `session_registrations` (`id`, `registration_id`, `sub_event_id`, `attended_session`, `created_at`, `updated_at`) VALUES
(1, 3, 1, 1, '2025-06-03 00:58:53', '2025-06-03 10:34:36'),
(2, 4, 1, 0, '2025-06-03 22:47:15', '2025-06-03 22:47:15'),
(3, 5, 2, 0, '2025-06-04 01:40:04', '2025-06-04 01:40:04'),
(4, 11, 2, 1, '2025-06-08 05:49:12', '2025-06-08 06:07:08'),
(5, 14, 4, 0, '2025-06-11 01:31:19', '2025-06-11 01:31:19'),
(6, 13, 4, 0, '2025-06-11 01:31:42', '2025-06-11 01:31:42'),
(7, 15, 2, 0, '2025-06-24 05:03:28', '2025-06-24 05:03:28'),
(8, 15, 3, 0, '2025-06-24 05:03:28', '2025-06-24 05:03:28'),
(9, 17, 1, 1, '2025-06-24 05:21:29', '2025-06-24 05:24:02'),
(10, 16, 1, 1, '2025-06-24 05:42:52', '2025-06-24 05:45:09');

-- --------------------------------------------------------

--
-- Table structure for table `sub_events`
--

CREATE TABLE `sub_events` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `event_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `location` varchar(255) NOT NULL,
  `speaker` varchar(255) NOT NULL,
  `max_participants` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sub_events`
--

INSERT INTO `sub_events` (`id`, `event_id`, `name`, `description`, `date`, `start_time`, `end_time`, `location`, `speaker`, `max_participants`, `created_at`, `updated_at`) VALUES
(1, 1, 'Talkshow', 'Ini adalah sesi talkshow langsung dengan narasumber.', '2025-06-03', '14:30:00', '15:30:00', 'Lab Adv 1', 'Dr. Jane Doe', 10, '2025-06-03 00:44:01', '2025-06-03 00:44:01'),
(2, 2, 'Technical Meeting', 'Technical Meeting untuk membahas teknis kegiatan.', '2025-06-04', '10:00:00', '12:00:00', 'Lantai 8, Gedung GWM', 'Bapak Bernard', 50, '2025-06-04 01:25:34', '2025-06-04 01:25:34'),
(3, 2, 'Live Coding & Arduino Development', 'Sesi lomba dengan live coding & implementasi Arduino.', '2025-06-05', '15:00:00', '19:00:00', 'Lab Adv 1', 'Bapak Bernard', 50, '2025-06-04 01:26:42', '2025-06-04 01:26:42'),
(4, 4, 'test sesi', NULL, '2025-06-11', '15:28:00', '15:34:00', 'Gedung GWM', 'Bapak Bernard', 2, '2025-06-11 01:29:03', '2025-06-11 01:29:03'),
(5, 5, 'QnA Session', NULL, '2025-06-24', '19:29:00', '19:33:00', 'GWM Lt. 3', 'Dr. Jonathan', 10, '2025-06-24 05:29:53', '2025-06-24 05:29:53');

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
  `role` enum('guest','member','admin','finance','committee') NOT NULL DEFAULT 'member',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `is_active`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Test User', 'test@example.com', '2025-06-02 23:48:38', '$2y$12$5MDsvmLdjaMdcAdFHFglKuGyxKm..etCdVC9WjVAZUTXwetWQgX1C', 'member', 1, 'OQvoBFNXDm', '2025-06-02 23:48:38', '2025-06-02 23:48:38'),
(2, 'Jessica', 'jessica@gmail.com', NULL, '$2y$12$j61B4/NssiR8/7AiYBZvtOnfFMjIsfafr8MDwIvy/zC5tbO6JKeDa', 'member', 1, NULL, '2025-06-02 23:51:15', '2025-06-02 23:51:15'),
(3, 'Super Administrator', 'admin@example.com', '2025-06-02 23:52:33', '$2y$12$R9e3C07JHMe8ge0sLuua.uLLdAFqv3UbCKIAhXq63msSl3mXhm2Ju', 'admin', 1, NULL, '2025-06-02 23:52:33', '2025-06-02 23:52:33'),
(4, 'Jonathan', 'jonathan@gmail.com', NULL, '$2y$12$rqTzUXi4SCO1Pjkb2TjWe.qa1osrrmLtMFOQ7yx0eRh14/7nVUF9a', 'finance', 1, NULL, '2025-06-03 00:07:16', '2025-06-24 05:27:52'),
(5, 'Ujang Iskandar', 'ujang@gmail.com', NULL, '$2y$12$8lNL6nqKbLIYE6WCZDtiJenxlVxHuLLC43KvgbMjc75xV9Zb6gvs6', 'committee', 1, NULL, '2025-06-03 00:20:59', '2025-06-07 03:29:36'),
(6, 'Jonathan Christandy', 'chris@gmail.com', NULL, '$2y$12$TcW3C73s3IDZVOIKbkP9FOpH.ztxTgVdDcWv/ccxahw0oMWifjNKm', 'member', 1, NULL, '2025-06-03 22:43:39', '2025-06-03 22:43:39'),
(9, 'Anne', 'anne@gmail.com', NULL, '$2y$12$nGCQ3DVTHCqeBrfpPurrA.Fy5pKVG/5gj1qtNeYlu.ebP9Bs1fEU6', 'member', 1, NULL, '2025-06-04 01:48:05', '2025-06-04 01:48:05'),
(10, 'Jess', 'jess@gmail.com', NULL, '$2y$12$MVSJt7lIx6IeSYbWDpBKhe0pqredez9anVV8weGGRfZbaT.v/gKtS', 'member', 1, NULL, '2025-06-07 03:34:43', '2025-06-07 03:34:43'),
(11, 'Matthew', 'matthew@gmail.com', NULL, '$2y$12$aDaja0Om2AgIjAwJs.HOAewOYk0reg25NrqXmLecaZBdvmR5ibdlm', 'member', 1, NULL, '2025-06-08 05:47:55', '2025-06-08 05:47:55'),
(12, 'Jonathan Anne', 'jonathananne@gmail.com', NULL, '$2y$12$u4s2/nrO5C0SzutPXVrSNOCnzyyedPG320euhY3W24TNq8KVH1cta', 'member', 1, NULL, '2025-06-24 05:00:17', '2025-06-24 05:00:17'),
(13, 'Jessica Christandy', 'jesschris@gmail.com', NULL, '$2y$12$njzr.pZ.CQ9tD1EkYOtvG.Hi77VtP3mTE6dGVCZYtTnu4A3dL4rTe', 'member', 1, NULL, '2025-06-24 05:13:51', '2025-06-24 05:13:51'),
(14, 'Jane Doe', 'janedoe@gmail.com', NULL, '$2y$12$mS4j7dB1jRqsF3m7OdKiQ.afHrMTmGVqWB5o7LiL.Xm8X7UB1XbpO', 'member', 1, NULL, '2025-06-24 05:18:55', '2025-06-24 05:18:55'),
(15, 'Anne', 'anne21@gmail.com', NULL, '$2y$12$PJms4CVt1xmIrouyQ0mSTuC2RO1HG6bbvK9aBIJBDR5cRA9clBsZi', 'finance', 1, NULL, '2025-06-24 05:26:56', '2025-06-24 05:26:56');

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
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `events_created_by_foreign` (`created_by`);

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
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `registrations`
--
ALTER TABLE `registrations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `registrations_user_id_event_id_unique` (`user_id`,`event_id`),
  ADD UNIQUE KEY `registrations_registration_code_unique` (`registration_code`),
  ADD KEY `registrations_event_id_foreign` (`event_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `session_registrations`
--
ALTER TABLE `session_registrations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `session_registrations_registration_id_sub_event_id_unique` (`registration_id`,`sub_event_id`),
  ADD KEY `session_registrations_sub_event_id_foreign` (`sub_event_id`);

--
-- Indexes for table `sub_events`
--
ALTER TABLE `sub_events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sub_events_event_id_foreign` (`event_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `registrations`
--
ALTER TABLE `registrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `session_registrations`
--
ALTER TABLE `session_registrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `sub_events`
--
ALTER TABLE `sub_events`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `registrations`
--
ALTER TABLE `registrations`
  ADD CONSTRAINT `registrations_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`),
  ADD CONSTRAINT `registrations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `session_registrations`
--
ALTER TABLE `session_registrations`
  ADD CONSTRAINT `session_registrations_registration_id_foreign` FOREIGN KEY (`registration_id`) REFERENCES `registrations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `session_registrations_sub_event_id_foreign` FOREIGN KEY (`sub_event_id`) REFERENCES `sub_events` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sub_events`
--
ALTER TABLE `sub_events`
  ADD CONSTRAINT `sub_events_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
