-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 24, 2025 at 04:16 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ppp2`
--

-- --------------------------------------------------------

--
-- Table structure for table `grupe_zadataka`
--

CREATE TABLE `grupe_zadataka` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `grupe_zadataka`
--

INSERT INTO `grupe_zadataka` (`id`, `name`, `created_by`) VALUES
(1, 'novinaziv', 11),
(6, 'test', 11);

-- --------------------------------------------------------

--
-- Table structure for table `komentari_zadatka`
--

CREATE TABLE `komentari_zadatka` (
  `id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `komentari_zadatka`
--

INSERT INTO `komentari_zadatka` (`id`, `task_id`, `user_id`, `comment`, `created_at`) VALUES
(7, 5, 11, 'komentar', '2025-08-18 12:44:35'),
(8, 5, 13, 'komentar2', '2025-08-18 12:44:49'),
(17, 5, 11, 'aaa', '2025-08-20 21:16:11'),
(20, 10, 11, 'radi', '2025-08-21 02:17:01'),
(23, 10, 14, 'komentar', '2025-08-23 19:24:40');

-- --------------------------------------------------------

--
-- Table structure for table `korisnici`
--

CREATE TABLE `korisnici` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `role` enum('admin','manager','executor','') NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `korisnici`
--

INSERT INTO `korisnici` (`id`, `username`, `email`, `password`, `name`, `phone`, `birth_date`, `role`, `is_active`, `created`) VALUES
(11, 'Admin', 'admin@mef.edu', '$2y$10$AUf7fWWX/vBBfoSFDKLYleoWHCnmtI/3Oq0Q0OngbV/SP39CRg1Qy', 'Admin', '', '0000-00-00', 'admin', 1, '0000-00-00 00:00:00'),
(12, 'bezpotvrde', 'bezpotvrde@example.com', '$2y$10$v0tsV5aa13rGnJgV178yw.0dmqKa3jGeK7AFcdspw7g3yGk9JUD2i', 'bezpotvrde', '', '0000-00-00', 'executor', 0, '0000-00-00 00:00:00'),
(13, 'Milos', 'milos@example.com', '$2y$10$ts5dwF/KxcH1YIzSzsfmg.hK1dH/Tw2tzcyy4DzXRE5yyQij2gHpG', 'Milos', '631234567', NULL, 'manager', 1, '0000-00-00 00:00:00'),
(14, 'Tina', 'tina@mef.rs', '$2y$10$SlwCe3Q.0xVVgw3zGamZuux25CbjuCwc8WEE9/0NH4lPQBwg.mJCq', 'Tina', NULL, '2004-10-07', 'executor', 1, '2025-08-21 22:37:48'),
(15, 'Pera', 'peraperic@gmail.com', '$2y$10$Q9bp0Yz/wtfXq3OYVxW/6eSmuvGVQgXBwrEeaphCOoMnLoQy/jqmq', 'Pera Peric', '65124567', '2025-08-15', 'executor', 1, '2025-08-24 15:49:33');

-- --------------------------------------------------------

--
-- Table structure for table `prilozi_zadataka`
--

CREATE TABLE `prilozi_zadataka` (
  `id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `file_path` varchar(500) NOT NULL COMMENT 'putanja do fajla',
  `uploaded_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prilozi_zadataka`
--

INSERT INTO `prilozi_zadataka` (`id`, `task_id`, `file_path`, `uploaded_by`, `created_at`) VALUES
(6, 10, 'uploads/Screenshot 2025-08-21 021634.png', 11, '2025-08-21 02:16:47'),
(8, 5, 'uploads/690eefe3ba1f553e0ea527f51ee407b604b681b4.jpg', 11, '2025-08-23 17:57:01'),
(9, 5, 'uploads/401441.jpg', 11, '2025-08-23 17:57:28');

-- --------------------------------------------------------

--
-- Table structure for table `token_aktivacije`
--

CREATE TABLE `token_aktivacije` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `token_aktivacije`
--

INSERT INTO `token_aktivacije` (`id`, `user_id`, `token`, `expires_at`) VALUES
(9, 12, '2dead7b285877be0bff461ad2cd745ee00c702bb99d3e5f0c4bf41aeadb5f612', '2025-08-16 17:17:10');

-- --------------------------------------------------------

--
-- Table structure for table `token_resetlozinke`
--

CREATE TABLE `token_resetlozinke` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `token_resetlozinke`
--

INSERT INTO `token_resetlozinke` (`id`, `user_id`, `token`, `expires_at`) VALUES
(1, 11, '216000491c34cafa3d5cc6235e379fed1ef3a78f58f112e8e00d9e40f373e667', '2025-08-16 17:00:23'),
(2, 11, 'd122c094360d095fb56a00d83c5b679fcab4d8ff936e800541845e04db989ddc', '2025-08-16 17:03:43');

-- --------------------------------------------------------

--
-- Table structure for table `veza_izvrsilaczadatak`
--

CREATE TABLE `veza_izvrsilaczadatak` (
  `task_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `completed` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `veza_izvrsilaczadatak`
--

INSERT INTO `veza_izvrsilaczadatak` (`task_id`, `user_id`, `completed`) VALUES
(10, 14, 1),
(6, 12, 0),
(6, 14, 0),
(5, 14, 0),
(5, 15, 0);

-- --------------------------------------------------------

--
-- Table structure for table `zadaci`
--

CREATE TABLE `zadaci` (
  `id` int(11) NOT NULL,
  `title` varchar(191) NOT NULL,
  `description` text NOT NULL,
  `group_id` int(11) NOT NULL,
  `manager_id` int(11) NOT NULL,
  `deadline` datetime NOT NULL,
  `priority` int(10) NOT NULL,
  `status` enum('open','completed','canceled','') NOT NULL DEFAULT 'open',
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `zadaci`
--

INSERT INTO `zadaci` (`id`, `title`, `description`, `group_id`, `manager_id`, `deadline`, `priority`, `status`, `created_at`) VALUES
(5, 'Kreiranje administratora', 'Kreiranje administratora po instrukcijama iz primera.', 6, 11, '2025-08-22 02:00:00', 9, 'open', '2025-08-16 19:39:51'),
(6, 'Zadatak2', 'zadatak2', 1, 11, '2025-08-21 12:34:00', 7, 'open', '2025-08-18 13:37:15'),
(10, 'Provera', 'proveriti da li sve radi', 6, 11, '1212-12-12 12:12:00', 9, 'open', '2025-08-21 02:16:47');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `grupe_zadataka`
--
ALTER TABLE `grupe_zadataka`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `komentari_zadatka`
--
ALTER TABLE `komentari_zadatka`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_id` (`task_id`,`user_id`),
  ADD KEY `komentari_zadatka_ibfk_2` (`user_id`);

--
-- Indexes for table `korisnici`
--
ALTER TABLE `korisnici`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `prilozi_zadataka`
--
ALTER TABLE `prilozi_zadataka`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_id` (`task_id`,`uploaded_by`),
  ADD KEY `prilozi_zadataka_ibfk_2` (`uploaded_by`);

--
-- Indexes for table `token_aktivacije`
--
ALTER TABLE `token_aktivacije`
  ADD PRIMARY KEY (`id`),
  ADD KEY `users_id` (`user_id`);

--
-- Indexes for table `token_resetlozinke`
--
ALTER TABLE `token_resetlozinke`
  ADD PRIMARY KEY (`id`),
  ADD KEY `users_id` (`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `veza_izvrsilaczadatak`
--
ALTER TABLE `veza_izvrsilaczadatak`
  ADD KEY `task_id` (`task_id`,`user_id`),
  ADD KEY `veza_izvrsilaczadatak_ibfk_2` (`user_id`);

--
-- Indexes for table `zadaci`
--
ALTER TABLE `zadaci`
  ADD PRIMARY KEY (`id`),
  ADD KEY `group_id` (`group_id`,`manager_id`),
  ADD KEY `zadaci_ibfk_2` (`manager_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `grupe_zadataka`
--
ALTER TABLE `grupe_zadataka`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `komentari_zadatka`
--
ALTER TABLE `komentari_zadatka`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `korisnici`
--
ALTER TABLE `korisnici`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `prilozi_zadataka`
--
ALTER TABLE `prilozi_zadataka`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `token_aktivacije`
--
ALTER TABLE `token_aktivacije`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `token_resetlozinke`
--
ALTER TABLE `token_resetlozinke`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `zadaci`
--
ALTER TABLE `zadaci`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `grupe_zadataka`
--
ALTER TABLE `grupe_zadataka`
  ADD CONSTRAINT `grupe_zadataka_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `korisnici` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `komentari_zadatka`
--
ALTER TABLE `komentari_zadatka`
  ADD CONSTRAINT `komentari_zadatka_ibfk_1` FOREIGN KEY (`task_id`) REFERENCES `zadaci` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `komentari_zadatka_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `korisnici` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `prilozi_zadataka`
--
ALTER TABLE `prilozi_zadataka`
  ADD CONSTRAINT `prilozi_zadataka_ibfk_1` FOREIGN KEY (`task_id`) REFERENCES `zadaci` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `prilozi_zadataka_ibfk_2` FOREIGN KEY (`uploaded_by`) REFERENCES `korisnici` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `token_aktivacije`
--
ALTER TABLE `token_aktivacije`
  ADD CONSTRAINT `token_aktivacije_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `korisnici` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `token_resetlozinke`
--
ALTER TABLE `token_resetlozinke`
  ADD CONSTRAINT `token_resetlozinke_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `korisnici` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `veza_izvrsilaczadatak`
--
ALTER TABLE `veza_izvrsilaczadatak`
  ADD CONSTRAINT `veza_izvrsilaczadatak_ibfk_1` FOREIGN KEY (`task_id`) REFERENCES `zadaci` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `veza_izvrsilaczadatak_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `korisnici` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `zadaci`
--
ALTER TABLE `zadaci`
  ADD CONSTRAINT `zadaci_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `grupe_zadataka` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `zadaci_ibfk_2` FOREIGN KEY (`manager_id`) REFERENCES `korisnici` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
