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

-- Dumping database structure for radford_rate_em
CREATE DATABASE IF NOT EXISTS `radford_rate_em` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `radford_rate_em`;


-- Dumping structure for table radford_rate_em.assignments
DROP TABLE IF EXISTS `assignments`;
CREATE TABLE IF NOT EXISTS `assignments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `class` int(10) unsigned NOT NULL,
  `asgn_name` char(255) NOT NULL,
  `asgn_desc` varchar(500) NOT NULL,
  `score` int(10) unsigned DEFAULT '0',
  `num_scores` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `assignment` (`class`,`asgn_name`,`asgn_desc`),
  CONSTRAINT `class` FOREIGN KEY (`class`) REFERENCES `classes` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table radford_rate_em.colleges
DROP TABLE IF EXISTS `colleges`;
CREATE TABLE IF NOT EXISTS `colleges` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `college` varchar(255) NOT NULL,
  `abbr` char(5) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `college` (`college`),
  UNIQUE KEY `abbr` (`abbr`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table radford_rate_em.comments
DROP TABLE IF EXISTS `comments`;
CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `assignment` int(10) unsigned NOT NULL,
  `user` int(11) unsigned NOT NULL,
  `text` varchar(500) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `comments` (`assignment`,`user`),
  KEY `comments_user` (`user`),
  CONSTRAINT `comments_assignment` FOREIGN KEY (`assignment`) REFERENCES `assignments` (`id`),
  CONSTRAINT `comments_user` FOREIGN KEY (`user`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


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

-- Data exporting was unselected.


-- Dumping structure for table radford_rate_em.professors
DROP TABLE IF EXISTS `professors`;
CREATE TABLE IF NOT EXISTS `professors` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `grade` tinyint(2) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table radford_rate_em.ratings
DROP TABLE IF EXISTS `ratings`;
CREATE TABLE IF NOT EXISTS `ratings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user` int(11) unsigned NOT NULL,
  `assignment` int(10) unsigned NOT NULL,
  `rating` tinyint(2) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_rating` (`user`,`assignment`,`rating`),
  KEY `ratings_assignment` (`assignment`),
  CONSTRAINT `ratings_assignment` FOREIGN KEY (`assignment`) REFERENCES `assignments` (`id`),
  CONSTRAINT `ratings_user` FOREIGN KEY (`user`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table radford_rate_em.users
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` char(64) COLLATE utf8_unicode_ci NOT NULL,
  `salt` char(16) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Data exporting was unselected.


-- Dumping structure for trigger radford_rate_em.after_insert_ratings
DROP TRIGGER IF EXISTS `after_insert_ratings`;
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `after_insert_ratings` AFTER INSERT ON `ratings` FOR EACH ROW BEGIN
	UPDATE assignments
	SET assignments.score = assignments.score + NEW.rating, 
	assignments.num_scores = assignments.num_scores + 1
	WHERE assignments.id = NEW.assignment;
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;


-- Dumping structure for trigger radford_rate_em.after_update_assignment
DROP TRIGGER IF EXISTS `after_update_assignment`;
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `after_update_assignment` AFTER UPDATE ON `assignments` FOR EACH ROW BEGIN
	UPDATE classes 
	SET classes.grade = 
	(SELECT SUM(score) FROM assignments WHERE class = NEW.class) / 
	(SELECT SUM(num_scores) FROM assignments WHERE class = NEW.class) 
	WHERE classes.id = NEW.class;
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;


-- Dumping structure for trigger radford_rate_em.after_update_class
DROP TRIGGER IF EXISTS `after_update_class`;
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `after_update_class` AFTER UPDATE ON `classes` FOR EACH ROW BEGIN
	UPDATE professors 
	SET professors.grade = 
	(SELECT SUM(grade) FROM classes WHERE id = NEW.id) / 
	(SELECT COUNT(*) FROM classes 
		WHERE professor = NEW.professor AND grade IS NOT NULL) 
	WHERE professors.name = NEW.professor;
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;


-- Dumping structure for trigger radford_rate_em.after_update_ratings
DROP TRIGGER IF EXISTS `after_update_ratings`;
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `after_update_ratings` AFTER UPDATE ON `ratings` FOR EACH ROW BEGIN
	UPDATE assignments
	SET assignments.score = assignments.score + NEW.rating - OLD.rating
	WHERE assignments.id = NEW.assignment;
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
