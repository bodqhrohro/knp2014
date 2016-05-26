ALTER TABLE courses DROP DateBegin, DROP DateEnd, DROP hours;
CREATE TABLE IF NOT EXISTS `lessons` (
 `idLesson` int(11) NOT NULL AUTO_INCREMENT,
 `idCourse` int(11) NOT NULL,
 `date` date NULL,
 `time` time NOT NULL DEFAULT '13:00',
 `type` tinyint(1) NOT NULL,
 `affectedBy` varchar(32) NOT NULL,
 PRIMARY KEY (`idLesson`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
