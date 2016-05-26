SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE DATABASE IF NOT EXISTS `course_payments_2014` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
USE `course_payments_2014`;

CREATE TABLE IF NOT EXISTS `courses` (
  `idCourse` int(6) NOT NULL AUTO_INCREMENT,
  `Name` varchar(50) NOT NULL,
  `Description` varchar(400) DEFAULT NULL,
  `isIndividual` bit(1) NOT NULL,
  `idTeacher` int(11) DEFAULT NULL,
  `isCompleted` bit(1) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `state` tinyint(1) NOT NULL,
  `affectedBy` varchar(32) NOT NULL,
  `spec` tinyint(1) NOT NULL,
  PRIMARY KEY (`idCourse`)
) ENGINE=MyISAM AUTO_INCREMENT=288 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `Course_Listeners` (
  `idCourse` int(6) NOT NULL,
  `idListener` int(6) NOT NULL,
  `idCL` int(6) NOT NULL AUTO_INCREMENT,
  `havePaid` int(6) DEFAULT NULL,
  `confirmed` bit(1) NOT NULL DEFAULT b'1',
  `affectedBy` varchar(32) NOT NULL,
  `mark` tinyint(3) DEFAULT NULL,
  PRIMARY KEY (`idCL`)
) ENGINE=MyISAM AUTO_INCREMENT=408 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `lessons` (
 `idLesson` int(11) NOT NULL AUTO_INCREMENT,
 `idCourse` int(11) NOT NULL,
 `date` date NULL,
 `time` time NOT NULL DEFAULT '13:00',
 `type` tinyint(1) NOT NULL,
 `affectedBy` varchar(32) NOT NULL,
 PRIMARY KEY (`idLesson`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `listeners` (
  `idListener` int(6) NOT NULL AUTO_INCREMENT,
  `Name` varchar(30) DEFAULT NULL,
  `Surname` varchar(30) NOT NULL,
  `Patronymic` varchar(30) DEFAULT NULL,
  `UGroup` varchar(6) DEFAULT NULL,
  `Phone` varchar(20) DEFAULT NULL,
  `Email` varchar(40) DEFAULT NULL,
  `Notes` mediumtext,
  `affectedBy` varchar(32) NOT NULL,
  PRIMARY KEY (`idListener`)
) ENGINE=MyISAM AUTO_INCREMENT=702 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `payments` (
  `idPayment` int(11) NOT NULL AUTO_INCREMENT,
  `idListener` int(11) NOT NULL,
  `idGroup` int(11) NOT NULL,
  `delta` int(11) DEFAULT NULL,
  `endSum` int(11) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idPayment`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `prices` (
  `degree` tinyint(1) NOT NULL AUTO_INCREMENT,
  `deglab` varchar(30) NOT NULL,
  `salary` decimal(10,2) NOT NULL,
  PRIMARY KEY (`degree`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

INSERT INTO `prices` (`degree`, `deglab`, `salary`) VALUES
(1, 'Б. с.', 3.55),
(2, 'Доц. к. т. н.', 4.66),
(3, 'Проф.', 7.99);

CREATE TABLE IF NOT EXISTS `settings` (
  `name` varchar(64) COLLATE utf8_bin NOT NULL,
  `title` varchar(128) COLLATE utf8_bin DEFAULT NULL,
  `value` varchar(128) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO `settings` (`name`, `title`, `value`) VALUES
('bonus', 'Начисление на заработную плату', '1.111'),
('others', 'Другие услуги и издержки', '1.222'),
('personal', 'Зарплата персонала', '1.333');

CREATE TABLE IF NOT EXISTS `teachers` (
  `idTeacher` int(6) NOT NULL AUTO_INCREMENT,
  `Name` varchar(30) DEFAULT NULL,
  `Surname` varchar(30) NOT NULL,
  `Patronymic` varchar(30) DEFAULT NULL,
  `Phone` varchar(20) DEFAULT NULL,
  `Email` varchar(40) DEFAULT NULL,
  `Login` varchar(20) DEFAULT NULL,
  `Password` varchar(32) DEFAULT NULL,
  `confirmed` bit(1) DEFAULT NULL,
  `isAdmin` bit(1) DEFAULT NULL,
  `affectedBy` varchar(32) NOT NULL,
  `degree` tinyint(1) DEFAULT NULL,
  `SessionID` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`idTeacher`)
) ENGINE=MyISAM AUTO_INCREMENT=196 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `users` (
  `login` varchar(32) COLLATE utf8_bin NOT NULL,
  `password` text COLLATE utf8_bin NOT NULL,
  `salt` text COLLATE utf8_bin NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `sessionid` text COLLATE utf8_bin,
  PRIMARY KEY (`login`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO `users` (`login`, `password`, `salt`, `type`, `sessionid`) VALUES
('asdf', 'b53qUOlIx9XvY', 'b5d84e03eb40e26ca7a3dbb4dab91f6d', 1, NULL),
('fdsa', '44fcapma5gd1A', '4448701f2ab628b3dd899043ad9a6ffe', 0, NULL),
('pnd', '80TSpzxp..xlo', '80657bc31ddd7fb870bd8690e8214898', 2, NULL),
('qwer', '9c7u1GcQ4WuJk', '9c3a6da7427d5846d1b6bba7b660da8f', 2, NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
