/*
SQLyog Community
MySQL - 5.7.36 : Database - tidsplan
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `kategorier` */

CREATE TABLE `kategorier` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Kategori` varchar(30) COLLATE utf8_swedish_ci DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `uix_k` (`Kategori`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

/*Data for the table `kategorier` */

insert  into `kategorier`(`ID`,`Kategori`) values 
(1,'ID'),
(2,'Kategorier'),
(3,'Tid'),
(4,'Datum'),
(5,'KategoriID'),
(6,'Beskrivning');

/*Table structure for table `uppgifter` */

CREATE TABLE `uppgifter` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Tid` time NOT NULL,
  `Datum` date NOT NULL,
  `KategoriID` int(11) NOT NULL,
  `Beskrivning` varchar(255) COLLATE utf8_swedish_ci DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `KategoriID` (`KategoriID`),
  CONSTRAINT `uppgifter_ibfk_1` FOREIGN KEY (`KategoriID`) REFERENCES `uppgifter` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

/*Data for the table `uppgifter` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
