-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 12, 2026 at 07:42 AM
-- Server version: 26.7.0
-- PHP Version: 8.4.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dw_accreditation`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `faculties`
--

CREATE TABLE `faculties` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000001_create_cache_table', 1),
(2, '0001_01_01_000002_create_jobs_table', 1),
(3, '2026_07_04_061443_create_faculties_table', 1),
(4, '2026_07_04_061531_create_study_programs_table', 1),
(5, '2026_07_04_061605_create_users_table', 1),
(6, '2026_07_04_062000_create_university_accreditation_agencies_table', 1),
(7, '2026_07_04_062100_create_university_indicator_templates_table', 1),
(8, '2026_07_04_062200_create_university_accreditation_batches_table', 1),
(9, '2026_07_04_062300_create_university_accreditations_table', 1),
(10, '2026_07_04_062400_create_university_accreditation_indicators_table', 1),
(11, '2026_07_04_062500_create_university_indicator_files_table', 1),
(12, '2026_07_04_062600_create_study_program_accreditation_agencies_table', 1),
(13, '2026_07_04_062700_create_study_program_indicator_templates_table', 1),
(14, '2026_07_04_062800_create_study_program_accreditation_batches_table', 1),
(15, '2026_07_04_062900_create_study_program_accreditations_table', 1),
(16, '2026_07_04_063000_create_study_program_accreditation_indicators_table', 1),
(17, '2026_07_04_063100_create_study_program_indicator_files_table', 1),
(18, '2026_10_06_151809_make_study_program_indicator_template_id_nullable_in_indicators_table', 1),
(19, '2026_10_07_030716_make_university_indicator_template_id_nullable_in_university_accreditation_indicators', 1),
(20, '2026_10_07_121800_add_study_program_id_to_study_program_indicator_templates_table', 1),
(21, '2026_10_11_000000_remove_university_accreditation_batches', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('JJx5oZa28nAXqWop8yKsICeK2a9SIeYm83iyGFfH', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJHWE1SemNNOE5uY1RRNHN2a1lmUm42ZzVqdWg3ZmFzV1hmYWFXUzZaIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2R3LWFjY3JlZGl0YXRpb24tc3lzdGVtLnRlc3RcL2Rhc2hib2FyZC1hZG1pblwvdW5pdmVyc2l0eSIsInJvdXRlIjoiZGFzaGJvYXJkLWFkbWluLnVuaXZlcnNpdHkuaW5kZXgifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJmbGFzaGVyOjplbnZlbG9wZXMiOltdLCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MX0=', 1791790839),
('o8oRQwgEgfe477wiTYmfi8AqzCypqB1ljlrssTa1', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJ6VFlxOHpnbEtOODY2cVRwMUZwb0wyTUU2SjM1UHAxcDRycTFqSFF4IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2R3LWFjY3JlZGl0YXRpb24tc3lzdGVtLnRlc3RcL2Rhc2hib2FyZC1hZG1pblwvdW5pdmVyc2l0eSIsInJvdXRlIjoiZGFzaGJvYXJkLWFkbWluLnVuaXZlcnNpdHkuaW5kZXgifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJmbGFzaGVyOjplbnZlbG9wZXMiOltdLCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MX0=', 1791773943);

-- --------------------------------------------------------

--
-- Table structure for table `study_programs`
--

CREATE TABLE `study_programs` (
  `id` bigint UNSIGNED NOT NULL,
  `faculty_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `study_program_accreditations`
--

CREATE TABLE `study_program_accreditations` (
  `id` bigint UNSIGNED NOT NULL,
  `study_program_accreditation_batch_id` bigint UNSIGNED NOT NULL,
  `study_program_id` bigint UNSIGNED NOT NULL,
  `status` enum('in_progress','completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'in_progress',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `study_program_accreditation_agencies`
--

CREATE TABLE `study_program_accreditation_agencies` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `study_program_accreditation_batches`
--

CREATE TABLE `study_program_accreditation_batches` (
  `id` bigint UNSIGNED NOT NULL,
  `study_program_accreditation_agency_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `year` year NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `study_program_accreditation_indicators`
--

CREATE TABLE `study_program_accreditation_indicators` (
  `id` bigint UNSIGNED NOT NULL,
  `study_program_accreditation_id` bigint UNSIGNED NOT NULL,
  `study_program_indicator_template_id` bigint UNSIGNED DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `formula_type` enum('percentage','ratio') COLLATE utf8mb4_unicode_ci NOT NULL,
  `label_1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `label_2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `target` decimal(10,2) DEFAULT NULL,
  `strategic_reason` text COLLATE utf8mb4_unicode_ci,
  `value_1` decimal(15,2) DEFAULT NULL,
  `value_2` decimal(15,2) DEFAULT NULL,
  `result` decimal(15,2) DEFAULT NULL,
  `target_absolute` decimal(15,2) DEFAULT NULL,
  `gap` decimal(15,2) DEFAULT NULL,
  `gap_unit` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `study_program_indicator_files`
--

CREATE TABLE `study_program_indicator_files` (
  `id` bigint UNSIGNED NOT NULL,
  `study_program_accreditation_indicator_id` bigint UNSIGNED NOT NULL,
  `original_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_size` bigint UNSIGNED NOT NULL,
  `uploaded_by` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `study_program_indicator_templates`
--

CREATE TABLE `study_program_indicator_templates` (
  `id` bigint UNSIGNED NOT NULL,
  `study_program_accreditation_agency_id` bigint UNSIGNED NOT NULL,
  `study_program_id` bigint UNSIGNED DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `formula_type` enum('percentage','ratio') COLLATE utf8mb4_unicode_ci NOT NULL,
  `label_1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `label_2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `target` decimal(10,2) DEFAULT NULL,
  `gap_unit` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `strategic_reason` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `university_accreditations`
--

CREATE TABLE `university_accreditations` (
  `id` bigint UNSIGNED NOT NULL,
  `university_accreditation_agency_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `year` year NOT NULL,
  `status` enum('in_progress','completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'in_progress',
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `university_accreditation_agencies`
--

CREATE TABLE `university_accreditation_agencies` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `university_accreditation_indicators`
--

CREATE TABLE `university_accreditation_indicators` (
  `id` bigint UNSIGNED NOT NULL,
  `university_accreditation_id` bigint UNSIGNED NOT NULL,
  `university_indicator_template_id` bigint UNSIGNED DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `formula_type` enum('percentage','ratio') COLLATE utf8mb4_unicode_ci NOT NULL,
  `label_1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `label_2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `target` decimal(10,2) DEFAULT NULL,
  `strategic_reason` text COLLATE utf8mb4_unicode_ci,
  `value_1` decimal(15,2) DEFAULT NULL,
  `value_2` decimal(15,2) DEFAULT NULL,
  `result` decimal(15,2) DEFAULT NULL,
  `target_absolute` decimal(15,2) DEFAULT NULL,
  `gap` decimal(15,2) DEFAULT NULL,
  `gap_unit` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `university_indicator_files`
--

CREATE TABLE `university_indicator_files` (
  `id` bigint UNSIGNED NOT NULL,
  `university_accreditation_indicator_id` bigint UNSIGNED NOT NULL,
  `original_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_size` bigint UNSIGNED NOT NULL,
  `uploaded_by` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `university_indicator_templates`
--

CREATE TABLE `university_indicator_templates` (
  `id` bigint UNSIGNED NOT NULL,
  `university_accreditation_agency_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `formula_type` enum('percentage','ratio') COLLATE utf8mb4_unicode_ci NOT NULL,
  `label_1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `label_2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `target` decimal(10,2) DEFAULT NULL,
  `gap_unit` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `strategic_reason` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `study_program_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','operator') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'operator',
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '/uploads/default/avatar.png',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `study_program_id`, `name`, `email`, `password`, `role`, `avatar`, `is_active`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Super Admin', 'admin@gmail.com', '$2y$12$jixmtMNd2tjwVf1ujZ7bdejv9DOV0A.K3hOyfIJdiGtvEQiEZoB5i', 'admin', '/uploads/default/avatar.png', 1, NULL, '2026-10-11 19:59:03', '2026-10-11 19:59:03');

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
-- Indexes for table `faculties`
--
ALTER TABLE `faculties`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  ADD KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`);

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
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `study_programs`
--
ALTER TABLE `study_programs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `study_programs_faculty_id_foreign` (`faculty_id`);

--
-- Indexes for table `study_program_accreditations`
--
ALTER TABLE `study_program_accreditations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sp_accred_batch_fk` (`study_program_accreditation_batch_id`),
  ADD KEY `sp_accred_program_fk` (`study_program_id`);

--
-- Indexes for table `study_program_accreditation_agencies`
--
ALTER TABLE `study_program_accreditation_agencies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `study_program_accreditation_batches`
--
ALTER TABLE `study_program_accreditation_batches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sp_batch_agency_fk` (`study_program_accreditation_agency_id`);

--
-- Indexes for table `study_program_accreditation_indicators`
--
ALTER TABLE `study_program_accreditation_indicators`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sp_indic_accred_fk` (`study_program_accreditation_id`),
  ADD KEY `sp_indic_templ_fk` (`study_program_indicator_template_id`);

--
-- Indexes for table `study_program_indicator_files`
--
ALTER TABLE `study_program_indicator_files`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sp_file_indic_unique` (`study_program_accreditation_indicator_id`),
  ADD KEY `sp_file_uploader_fk` (`uploaded_by`);

--
-- Indexes for table `study_program_indicator_templates`
--
ALTER TABLE `study_program_indicator_templates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sp_templ_agency_fk` (`study_program_accreditation_agency_id`),
  ADD KEY `sp_templ_study_program_idx` (`study_program_id`);

--
-- Indexes for table `university_accreditations`
--
ALTER TABLE `university_accreditations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uni_accred_agency_fk` (`university_accreditation_agency_id`);

--
-- Indexes for table `university_accreditation_agencies`
--
ALTER TABLE `university_accreditation_agencies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `university_accreditation_indicators`
--
ALTER TABLE `university_accreditation_indicators`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uni_indic_accred_fk` (`university_accreditation_id`),
  ADD KEY `uni_indic_templ_fk` (`university_indicator_template_id`);

--
-- Indexes for table `university_indicator_files`
--
ALTER TABLE `university_indicator_files`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uni_file_indic_unique` (`university_accreditation_indicator_id`),
  ADD KEY `uni_file_uploader_fk` (`uploaded_by`);

--
-- Indexes for table `university_indicator_templates`
--
ALTER TABLE `university_indicator_templates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uni_templ_agency_fk` (`university_accreditation_agency_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_study_program_id_foreign` (`study_program_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `faculties`
--
ALTER TABLE `faculties`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `study_programs`
--
ALTER TABLE `study_programs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `study_program_accreditations`
--
ALTER TABLE `study_program_accreditations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `study_program_accreditation_agencies`
--
ALTER TABLE `study_program_accreditation_agencies`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `study_program_accreditation_batches`
--
ALTER TABLE `study_program_accreditation_batches`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `study_program_accreditation_indicators`
--
ALTER TABLE `study_program_accreditation_indicators`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `study_program_indicator_files`
--
ALTER TABLE `study_program_indicator_files`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `study_program_indicator_templates`
--
ALTER TABLE `study_program_indicator_templates`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `university_accreditations`
--
ALTER TABLE `university_accreditations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `university_accreditation_agencies`
--
ALTER TABLE `university_accreditation_agencies`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `university_accreditation_indicators`
--
ALTER TABLE `university_accreditation_indicators`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `university_indicator_files`
--
ALTER TABLE `university_indicator_files`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `university_indicator_templates`
--
ALTER TABLE `university_indicator_templates`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `study_programs`
--
ALTER TABLE `study_programs`
  ADD CONSTRAINT `study_programs_faculty_id_foreign` FOREIGN KEY (`faculty_id`) REFERENCES `faculties` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `study_program_accreditations`
--
ALTER TABLE `study_program_accreditations`
  ADD CONSTRAINT `sp_accred_batch_fk` FOREIGN KEY (`study_program_accreditation_batch_id`) REFERENCES `study_program_accreditation_batches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sp_accred_program_fk` FOREIGN KEY (`study_program_id`) REFERENCES `study_programs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `study_program_accreditation_batches`
--
ALTER TABLE `study_program_accreditation_batches`
  ADD CONSTRAINT `sp_batch_agency_fk` FOREIGN KEY (`study_program_accreditation_agency_id`) REFERENCES `study_program_accreditation_agencies` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `study_program_accreditation_indicators`
--
ALTER TABLE `study_program_accreditation_indicators`
  ADD CONSTRAINT `sp_indic_accred_fk` FOREIGN KEY (`study_program_accreditation_id`) REFERENCES `study_program_accreditations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sp_indic_templ_fk` FOREIGN KEY (`study_program_indicator_template_id`) REFERENCES `study_program_indicator_templates` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `study_program_indicator_files`
--
ALTER TABLE `study_program_indicator_files`
  ADD CONSTRAINT `sp_file_indic_fk` FOREIGN KEY (`study_program_accreditation_indicator_id`) REFERENCES `study_program_accreditation_indicators` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sp_file_uploader_fk` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `study_program_indicator_templates`
--
ALTER TABLE `study_program_indicator_templates`
  ADD CONSTRAINT `sp_templ_agency_fk` FOREIGN KEY (`study_program_accreditation_agency_id`) REFERENCES `study_program_accreditation_agencies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sp_templ_study_program_fk` FOREIGN KEY (`study_program_id`) REFERENCES `study_programs` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `university_accreditations`
--
ALTER TABLE `university_accreditations`
  ADD CONSTRAINT `uni_accred_agency_fk` FOREIGN KEY (`university_accreditation_agency_id`) REFERENCES `university_accreditation_agencies` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `university_accreditation_indicators`
--
ALTER TABLE `university_accreditation_indicators`
  ADD CONSTRAINT `uni_indic_accred_fk` FOREIGN KEY (`university_accreditation_id`) REFERENCES `university_accreditations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `uni_indic_templ_fk` FOREIGN KEY (`university_indicator_template_id`) REFERENCES `university_indicator_templates` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `university_indicator_files`
--
ALTER TABLE `university_indicator_files`
  ADD CONSTRAINT `uni_file_indic_fk` FOREIGN KEY (`university_accreditation_indicator_id`) REFERENCES `university_accreditation_indicators` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `uni_file_uploader_fk` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `university_indicator_templates`
--
ALTER TABLE `university_indicator_templates`
  ADD CONSTRAINT `uni_templ_agency_fk` FOREIGN KEY (`university_accreditation_agency_id`) REFERENCES `university_accreditation_agencies` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_study_program_id_foreign` FOREIGN KEY (`study_program_id`) REFERENCES `study_programs` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
