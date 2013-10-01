-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.6.11 - MySQL Community Server (GPL)
-- Server OS:                    Win32
-- HeidiSQL Version:             8.0.0.4396
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for table radford_rate_em.classes
DROP TABLE IF EXISTS `classes`;
CREATE TABLE IF NOT EXISTS `classes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `course` char(10) NOT NULL,
  `professor` varchar(255) NOT NULL,
  `grade` tinyint(2) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `course` (`course`,`professor`),
  KEY `professor` (`professor`),
  CONSTRAINT `professor` FOREIGN KEY (`professor`) REFERENCES `professors` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=latin1;

-- Dumping data for table radford_rate_em.classes: ~33 rows (approximately)
DELETE FROM `classes`;
/*!40000 ALTER TABLE `classes` DISABLE KEYS */;
INSERT INTO `classes` (`id`, `course`, `professor`, `grade`) VALUES
	(1, 'ECON 105', 'Charles L. Vehorn', NULL),
	(2, 'ECON 105', 'Eftila Tanellari', NULL),
	(3, 'ECON 105', 'Nozar Hashemzadeh', NULL),
	(4, 'ECON 105', 'Sanaz Fesharaki', NULL),
	(5, 'ECON 105', 'Thomas K. Duncan', NULL),
	(6, 'ECON 106', 'Jennifer Elias', NULL),
	(7, 'ECON 106', 'Prahlad Kasturi', NULL),
	(8, 'ECON 106', 'Sanaz Fesharaki', NULL),
	(9, 'ECON 106', 'Seife Dendir', NULL),
	(10, 'ECON 106', 'Sumati Srinivas', NULL),
	(11, 'ITEC 100', 'Chen-Chi Shing', 8),
	(12, 'ITEC 100', 'Sallie B. Dodson', NULL),
	(13, 'ITEC 109', 'Arthur E. Carter', NULL),
	(14, 'ITEC 109', 'Hui Wang', NULL),
	(15, 'ITEC 110', 'Muang M. Htay', NULL),
	(16, 'ITEC 112', 'Sallie B. Dodson', NULL),
	(17, 'ITEC 120', 'Donald J. Braffitt', NULL),
	(18, 'ITEC 120', 'Jeffrey J. Pittges', NULL),
	(19, 'ITEC 122', 'Chen-Chi Shing', NULL),
	(20, 'ITEC 122', 'David P. Daughtery', NULL),
	(21, 'ITEC 220', 'David P. Daughtery', NULL),
	(22, 'ITEC 220', 'Joseph D. Chase', NULL),
	(23, 'ITEC 225', 'Jack C. Davis', NULL),
	(24, 'ITEC 226', 'Samuel R. Jennings', NULL),
	(25, 'ITEC 281', 'Arthur E. Carter', NULL),
	(26, 'ITEC 281', 'Robert D. Spillman', NULL),
	(27, 'ITEC 304', 'Robert H. Phillips', NULL),
	(28, 'ITEC 307', 'Ian Barland', 8),
	(29, 'ITEC 310', 'Chen-Chi Shing', 7),
	(30, 'ITEC 320', 'Edward G. Okie', NULL),
	(31, 'ITEC 324', 'Hwajung Lee', NULL),
	(32, 'ITEC 325', 'Jack C. Davis', NULL),
	(33, 'ITEC 340', 'Robert H. Phillips', NULL);
/*!40000 ALTER TABLE `classes` ENABLE KEYS */;


-- Dumping structure for table radford_rate_em.colleges
DROP TABLE IF EXISTS `colleges`;
CREATE TABLE IF NOT EXISTS `colleges` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `college` varchar(255) NOT NULL,
  `abbr` char(5) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `college` (`college`),
  UNIQUE KEY `abbr` (`abbr`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- Dumping data for table radford_rate_em.colleges: ~5 rows (approximately)
DELETE FROM `colleges`;
/*!40000 ALTER TABLE `colleges` DISABLE KEYS */;
INSERT INTO `colleges` (`id`, `college`, `abbr`) VALUES
	(1, 'Information Science and Technology', 'ITEC'),
	(2, 'School of Dance and Theatre', 'DNCE'),
	(3, 'Art', 'ART'),
	(4, 'Art Education', 'ARTE'),
	(5, 'Economics', 'ECON');
/*!40000 ALTER TABLE `colleges` ENABLE KEYS */;


-- Dumping structure for table radford_rate_em.courses
DROP TABLE IF EXISTS `courses`;
CREATE TABLE IF NOT EXISTS `courses` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `college` char(5) NOT NULL,
  `course_number` smallint(4) unsigned NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `college` (`college`,`course_number`),
  CONSTRAINT `college` FOREIGN KEY (`college`) REFERENCES `colleges` (`abbr`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=latin1;

-- Dumping data for table radford_rate_em.courses: ~19 rows (approximately)
DELETE FROM `courses`;
/*!40000 ALTER TABLE `courses` DISABLE KEYS */;
INSERT INTO `courses` (`id`, `college`, `course_number`, `name`) VALUES
	(2, 'ITEC', 100, 'Introduction to Information Technology'),
	(5, 'ITEC', 109, 'Problem Solving and Programming'),
	(6, 'ITEC', 110, 'Principles of Information Technology'),
	(7, 'ITEC', 112, 'A Global Perspective of IT'),
	(9, 'ITEC', 120, 'Principles of Computer Science I'),
	(11, 'ITEC', 122, 'Discrete Mathematics'),
	(12, 'ITEC', 220, 'Priciples of Computer Science II'),
	(15, 'ITEC', 225, 'Web Programming I'),
	(17, 'ITEC', 226, 'Digital Imaging for the Web'),
	(19, 'ITEC', 281, 'Data Management and Analysis'),
	(20, 'ITEC', 304, 'Database from the Manager\'s Perspective'),
	(21, 'ITEC', 307, 'Programming Practicum'),
	(22, 'ITEC', 310, 'Programming in C and Unix'),
	(23, 'ITEC', 320, 'Procedural Analysis and Design'),
	(25, 'ITEC', 324, 'Principles of Computer Science III'),
	(27, 'ITEC', 325, 'Web Programming II'),
	(28, 'ITEC', 340, 'Database I'),
	(31, 'ECON', 105, 'Principles of Macroeconomics'),
	(38, 'ECON', 106, 'Principles of Microeconomics');
/*!40000 ALTER TABLE `courses` ENABLE KEYS */;


-- Dumping structure for table radford_rate_em.enrolled
DROP TABLE IF EXISTS `enrolled`;
CREATE TABLE IF NOT EXISTS `enrolled` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user` int(11) unsigned NOT NULL,
  `class` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_enroll` (`user`,`class`),
  KEY `enrolled_class` (`class`),
  CONSTRAINT `enrolled_class` FOREIGN KEY (`class`) REFERENCES `classes` (`id`),
  CONSTRAINT `enrolled_user` FOREIGN KEY (`user`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table radford_rate_em.enrolled: ~0 rows (approximately)
DELETE FROM `enrolled`;
/*!40000 ALTER TABLE `enrolled` DISABLE KEYS */;
/*!40000 ALTER TABLE `enrolled` ENABLE KEYS */;


-- Dumping structure for table radford_rate_em.professors
DROP TABLE IF EXISTS `professors`;
CREATE TABLE IF NOT EXISTS `professors` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `grade` tinyint(2) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=latin1;

-- Dumping data for table radford_rate_em.professors: ~25 rows (approximately)
DELETE FROM `professors`;
/*!40000 ALTER TABLE `professors` DISABLE KEYS */;
INSERT INTO `professors` (`id`, `name`, `grade`) VALUES
	(1, 'Chen-Chi Shing', 4),
	(2, 'Sallie B. Dodson', NULL),
	(3, 'Hui Wang', NULL),
	(4, 'Arthur E. Carter', NULL),
	(5, 'Muang M. Htay', NULL),
	(6, 'Donald J. Braffitt', NULL),
	(7, 'Jeffrey J. Pittges', NULL),
	(8, 'David P. Daughtery', NULL),
	(9, 'Joseph D. Chase', NULL),
	(10, 'Jack C. Davis', NULL),
	(11, 'Samuel R. Jennings', NULL),
	(12, 'Robert D. Spillman', NULL),
	(13, 'Robert H. Phillips', NULL),
	(14, 'Ian Barland', 8),
	(15, 'Edward G. Okie', NULL),
	(16, 'Hwajung Lee', NULL),
	(17, 'Nozar Hashemzadeh', NULL),
	(18, 'Charles L. Vehorn', NULL),
	(19, 'Sanaz Fesharaki', NULL),
	(20, 'Eftila Tanellari', NULL),
	(21, 'Thomas K. Duncan', NULL),
	(22, 'Seife Dendir', NULL),
	(23, 'Jennifer Elias', NULL),
	(24, 'Prahlad Kasturi', NULL),
	(25, 'Sumati Srinivas', NULL);
/*!40000 ALTER TABLE `professors` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
