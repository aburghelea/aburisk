-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 02, 2013 at 01:41 PM
-- Server version: 5.5.24-log
-- PHP Version: 5.4.3

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT=0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `aburisk`
--

-- --------------------------------------------------------

--
-- Table structure for table `galaxies`
--

CREATE TABLE IF NOT EXISTS `galaxies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL DEFAULT 'Milky Way',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `galaxies`
--

INSERT INTO `galaxies` (`id`, `name`) VALUES
(1, 'Alteran'),
(2, 'Milky Way'),
(3, 'Pegassus'),
(4, 'Othalla'),
(5, 'Kalien');

-- --------------------------------------------------------

--
-- Table structure for table `games`
--

CREATE TABLE IF NOT EXISTS `games` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `noplayers` tinyint(4) NOT NULL DEFAULT '2',
  `state` enum('WAITING_PLAYERS','PLANET_CLAIM','SHIP_PLACING','ATTACK','GAME_END') DEFAULT NULL,
  `current_player_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_games_users1_idx` (`current_player_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `games`
--

INSERT INTO `games` (`id`, `noplayers`, `state`, `current_player_id`) VALUES
(1, 2, 'GAME_END', 1),
(3, 2, 'SHIP_PLACING', 1),
(4, 2, 'WAITING_PLAYERS', 1),
(5, 2, 'WAITING_PLAYERS', 1),
(6, 2, 'WAITING_PLAYERS', 1);

-- --------------------------------------------------------

--
-- Table structure for table `planets`
--

CREATE TABLE IF NOT EXISTS `planets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL DEFAULT 'Tatooine',
  `containing_galaxy_id` int(11) NOT NULL,
  `image` varchar(100) NOT NULL,
  `x_pos` int(11) NOT NULL DEFAULT '0',
  `y_pos` int(11) NOT NULL DEFAULT '0',
  `diameter` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_planets_galaxies1_idx` (`containing_galaxy_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

--
-- Dumping data for table `planets`
--

INSERT INTO `planets` (`id`, `name`, `containing_galaxy_id`, `image`, `x_pos`, `y_pos`, `diameter`) VALUES
(1, 'Celestis', 1, 'cyan1.png', 73, 59, 60),
(2, 'Ver Omesh', 1, 'cyan2.png', 43, 145, 85),
(3, 'Ver Isca', 1, 'cyan3.png', 170, 14, 60),
(4, 'Abydos', 2, 'green1.png', 53, 255, 55),
(5, 'Dakara', 2, 'green2.png', 50, 320, 65),
(6, 'P3X-888', 2, 'green3.png', 195, 280, 90),
(7, 'Hebridan', 3, 'blue1.png', 195, 120, 50),
(8, 'Svoriin', 3, 'blue2.png', 170, 210, 50),
(9, 'Lantea', 3, 'blue3.png', 335, 50, 50),
(10, 'Doranda', 3, 'blue4.png', 345, 155, 30),
(11, 'Taranis', 3, 'blue5.png', 480, 55, 60),
(12, 'Athos', 4, 'red1.png', 310, 240, 70),
(13, 'Hala', 4, 'red2.png', 350, 330, 70),
(14, 'Orila', 4, 'red3.png', 470, 250, 90),
(15, 'Freyr', 4, 'red4.png', 560, 330, 50),
(16, 'Vanir', 5, 'gray1.png', 525, 150, 70),
(17, 'Othala', 5, 'gray2.png', 600, 15, 60),
(18, 'Tauri', 5, 'gray3.png', 630, 220, 56);

-- --------------------------------------------------------

--
-- Table structure for table `planets_games`
--

CREATE TABLE IF NOT EXISTS `planets_games` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `planet_id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `game_id` int(11) NOT NULL,
  `noships` int(11) NOT NULL,
  `x_axis` int(11) NOT NULL DEFAULT '0',
  `y_axis` int(11) NOT NULL DEFAULT '0',
  `radius` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_planets_games_planets1_idx` (`planet_id`),
  KEY `fk_planets_games_users1_idx` (`owner_id`),
  KEY `fk_planets_games_games1_idx` (`game_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `planets_games`
--

INSERT INTO `planets_games` (`id`, `planet_id`, `owner_id`, `game_id`, `noships`, `x_axis`, `y_axis`, `radius`) VALUES
(1, 1, 1, 1, 3, 1, 1, 5);

-- --------------------------------------------------------

--
-- Table structure for table `planets_neighbours`
--

CREATE TABLE IF NOT EXISTS `planets_neighbours` (
  `first_planet_id` int(11) NOT NULL,
  `second_planet_id` int(11) NOT NULL,
  PRIMARY KEY (`first_planet_id`,`second_planet_id`),
  KEY `fk_planets_has_planets_planets2_idx` (`second_planet_id`),
  KEY `fk_planets_has_planets_planets1_idx` (`first_planet_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `planets_neighbours`
--

INSERT INTO `planets_neighbours` (`first_planet_id`, `second_planet_id`) VALUES
(2, 1),
(3, 1),
(1, 2),
(4, 2),
(8, 2),
(1, 3),
(7, 3),
(9, 3),
(2, 4),
(5, 4),
(6, 4),
(4, 5),
(4, 6),
(8, 6),
(12, 6),
(3, 7),
(8, 7),
(10, 7),
(2, 8),
(6, 8),
(7, 8),
(10, 8),
(3, 9),
(10, 9),
(11, 9),
(7, 10),
(8, 10),
(9, 10),
(11, 10),
(12, 10),
(15, 10),
(9, 11),
(10, 11),
(17, 11),
(6, 12),
(10, 12),
(13, 12),
(14, 12),
(12, 13),
(15, 13),
(12, 14),
(15, 14),
(16, 14),
(10, 15),
(13, 15),
(14, 15),
(14, 16),
(17, 16),
(18, 16),
(11, 17),
(16, 17),
(16, 18);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `email` varchar(45) NOT NULL,
  `password` varchar(45) NOT NULL,
  `played_games` int(11) NOT NULL DEFAULT '0',
  `won_games` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `played_games`, `won_games`) VALUES
(1, 'iceman', 'iceman.ftg@gmail.com', '1', 6, 1),
(2, 'iceman2', 'i2ceman.ftg@gmail.com', '1', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `users_games`
--

CREATE TABLE IF NOT EXISTS `users_games` (
  `user_id` int(11) NOT NULL,
  `score` int(11) DEFAULT '0',
  `game_id` int(11) NOT NULL,
  KEY `fk_users_games_users1_idx` (`user_id`),
  KEY `fk_users_games_games1_idx` (`game_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users_games`
--

INSERT INTO `users_games` (`user_id`, `score`, `game_id`) VALUES
(1, 10, 1),
(1, 0, 3),
(1, 0, 4),
(1, 0, 5),
(1, 0, 6),
(2, 0, 4);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `games`
--
ALTER TABLE `games`
  ADD CONSTRAINT `fk_games_users1` FOREIGN KEY (`current_player_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `planets`
--
ALTER TABLE `planets`
  ADD CONSTRAINT `fk_planets_galaxies1` FOREIGN KEY (`containing_galaxy_id`) REFERENCES `galaxies` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `planets_games`
--
ALTER TABLE `planets_games`
  ADD CONSTRAINT `fk_planets_games_planets1` FOREIGN KEY (`planet_id`) REFERENCES `planets` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_planets_games_users1` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_planets_games_games1` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `planets_neighbours`
--
ALTER TABLE `planets_neighbours`
  ADD CONSTRAINT `fk_planets_has_planets_planets1` FOREIGN KEY (`first_planet_id`) REFERENCES `planets` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_planets_has_planets_planets2` FOREIGN KEY (`second_planet_id`) REFERENCES `planets` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `users_games`
--
ALTER TABLE `users_games`
  ADD CONSTRAINT `fk_users_games_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_users_games_games1` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
SET FOREIGN_KEY_CHECKS=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
