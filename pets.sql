-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 10 ное 2019 в 18:11
-- Версия на сървъра: 10.1.36-MariaDB
-- PHP Version: 7.2.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pets`
--

-- --------------------------------------------------------

--
-- Структура на таблица `animals`
--

CREATE TABLE `animals` (
  `id` int(10) UNSIGNED NOT NULL,
  `type_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(64) NOT NULL,
  `description` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Схема на данните от таблица `animals`
--

INSERT INTO `animals` (`id`, `type_id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(7, 1, 'Макс', 'Хъски, игриво', '2019-11-10 19:02:09', '2019-11-10 19:02:09'),
(8, 2, 'Айк', 'доста палава и игрива', '2019-11-10 19:03:14', '2019-11-10 19:03:14'),
(9, 3, 'Папи', 'папагал порода Корела', '2019-11-10 19:05:02', '2019-11-10 19:05:02'),
(10, 4, 'Артьом', 'Сибирски тигър', '2019-11-10 19:05:26', '2019-11-10 19:05:26');

-- --------------------------------------------------------

--
-- Структура на таблица `animal_types`
--

CREATE TABLE `animal_types` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(32) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Схема на данните от таблица `animal_types`
--

INSERT INTO `animal_types` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Куче', '2019-11-10 00:00:00', '2019-11-10 00:00:00'),
(2, 'Котка', '2019-11-10 00:00:00', '2019-11-10 00:00:00'),
(3, 'Папагал', '2019-11-10 00:00:00', '2019-11-10 00:00:00'),
(4, 'Тигър', '2019-11-10 00:00:00', '2019-11-10 00:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `animals`
--
ALTER TABLE `animals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `animal_types`
--
ALTER TABLE `animal_types`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `animals`
--
ALTER TABLE `animals`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `animal_types`
--
ALTER TABLE `animal_types`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
