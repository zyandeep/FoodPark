-- MariaDB dump 10.17  Distrib 10.4.11-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: foodpark
-- ------------------------------------------------------
-- Server version	10.4.11-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `foodpark`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `foodpark` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;

USE `foodpark`;

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin` (
  `a_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  PRIMARY KEY (`a_id`),
  UNIQUE KEY `user_name` (`user_name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` VALUES (1,'admin','admin123');
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bill`
--

DROP TABLE IF EXISTS `bill`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bill` (
  `bill_no` int(11) NOT NULL AUTO_INCREMENT,
  `o_id` int(11) NOT NULL,
  `c_id` int(11) NOT NULL,
  `total_amount` int(11) NOT NULL,
  `status` tinyint(5) NOT NULL DEFAULT 0,
  `date` date NOT NULL,
  PRIMARY KEY (`bill_no`),
  KEY `o_id` (`o_id`,`c_id`),
  KEY `bill_ibfk_2` (`c_id`),
  CONSTRAINT `bill_ibfk_1` FOREIGN KEY (`o_id`) REFERENCES `order` (`o_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `bill_ibfk_2` FOREIGN KEY (`c_id`) REFERENCES `customer` (`c_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bill`
--

LOCK TABLES `bill` WRITE;
/*!40000 ALTER TABLE `bill` DISABLE KEYS */;
INSERT INTO `bill` VALUES (9,9,9,320,1,'2018-05-28'),(10,10,11,450,0,'2020-01-03');
/*!40000 ALTER TABLE `bill` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customer`
--

DROP TABLE IF EXISTS `customer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customer` (
  `c_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `address` varchar(200) NOT NULL,
  `phone_no` varchar(10) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`c_id`),
  UNIQUE KEY `phone_no` (`phone_no`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customer`
--

LOCK TABLES `customer` WRITE;
/*!40000 ALTER TABLE `customer` DISABLE KEYS */;
INSERT INTO `customer` VALUES (9,'Zyandeep Baruah','Jorhat','8638367851','$2y$10$S6j/1FmAEWqnidIOnA99N.xHXMP6IKANswJocgllNcRStWWaymiBm'),(10,'Trinayan','Jorhat','9577509923','$2y$10$Tbg2x6SRK4USuxvi9LUage.I.kOy55nTb8Go8YIor9IWK/yzbUBbS'),(11,'Bob','B.P.ROAD','9859335453','$2y$10$Ta45pi/L4BQrRGsTPZ4m1uqV4itvD1YX42bOISGdhKW/3x2RQAL1.');
/*!40000 ALTER TABLE `customer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `item`
--

DROP TABLE IF EXISTS `item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `item` (
  `im_id` int(11) NOT NULL AUTO_INCREMENT,
  `i_name` varchar(200) NOT NULL,
  `category` varchar(30) NOT NULL,
  `price` int(11) NOT NULL,
  `image` varchar(500) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  PRIMARY KEY (`im_id`),
  UNIQUE KEY `i_name` (`i_name`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `item`
--

LOCK TABLES `item` WRITE;
/*!40000 ALTER TABLE `item` DISABLE KEYS */;
INSERT INTO `item` VALUES (1,'Palak Paneer','veg',140,'img/item/2018-05-15 03-48-46_veg1.jpg',1),(2,'Veg Briyani','veg',50,'img/item/2018-05-15 03-49-08_veg2.jpg',1),(4,'Chicken Leg Piece','non-veg',40,'img/item/2018-05-15 03-50-18_nonveg2.jpg',1),(10,'Singra','veg',10,'img/item/2018-05-28 07-46-47_chingra.jpg',1),(11,'Veg Roll','veg',50,'img/item/2018-05-28 12-21-54_veg_roll.jpg',1),(12,'Shahi Paneer','veg',180,'img/item/2018-05-28 12-24-56_paneer.gif',1),(13,'Dal Makhani','veg',120,'img/item/2018-05-28 12-26-36_dal-makhani_620x350_41478501912.jpg',1),(14,'Chicken Momo','non-veg',50,'img/item/2018-05-28 12-37-02_chicken_momo.jpeg',1),(15,'Chicken Butter Masala','non-veg',190,'img/item/2018-05-28 12-38-13_Butter_chicken_recipe.jpg',1),(16,'Roasted Pork','non-veg',100,'img/item/2018-05-28 12-39-58_pork.jpg',1),(17,'Chicken Tandoori','non-veg',220,'img/item/2018-05-28 12-41-08_whole-chicken-13.jpg',1),(18,'Chicken Burger','other',80,'img/item/2018-05-28 12-54-34_burger.jpg',1),(19,'Cheese Pizza','other',160,'img/item/2018-05-28 12-55-14_pizza.gif',1),(20,'Chicken Popcorn','other',70,'img/item/2018-05-28 12-55-57_chicken-popcorn1.jpg',1),(21,'Pepsi Can','other',35,'img/item/2018-05-28 12-56-38_pepsi_can_feature.jpg',1),(22,'Coca-cola Bottle(2lt)','other',90,'img/item/2018-05-28 12-57-55_coca-cola-soft-drink.jpg',1),(23,'Kinley Mineral Water Bottle(1lt)','other',10,'img/item/2018-05-28 12-59-13_bottle.jpg',1);
/*!40000 ALTER TABLE `item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order`
--

DROP TABLE IF EXISTS `order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order` (
  `o_id` int(11) NOT NULL AUTO_INCREMENT,
  `c_id` int(11) NOT NULL,
  PRIMARY KEY (`o_id`),
  KEY `c_id` (`c_id`),
  CONSTRAINT `order_ibfk_1` FOREIGN KEY (`c_id`) REFERENCES `customer` (`c_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order`
--

LOCK TABLES `order` WRITE;
/*!40000 ALTER TABLE `order` DISABLE KEYS */;
INSERT INTO `order` VALUES (9,9),(10,11);
/*!40000 ALTER TABLE `order` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_master`
--

DROP TABLE IF EXISTS `order_master`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_master` (
  `om_id` int(11) NOT NULL AUTO_INCREMENT,
  `o_id` int(11) NOT NULL,
  `im_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`om_id`),
  KEY `o_id` (`o_id`,`im_id`),
  KEY `order_master_ibfk_2` (`im_id`),
  CONSTRAINT `order_master_ibfk_1` FOREIGN KEY (`o_id`) REFERENCES `order` (`o_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `order_master_ibfk_2` FOREIGN KEY (`im_id`) REFERENCES `item` (`im_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_master`
--

LOCK TABLES `order_master` WRITE;
/*!40000 ALTER TABLE `order_master` DISABLE KEYS */;
INSERT INTO `order_master` VALUES (13,9,17,1,'2018-05-28'),(14,9,2,2,'2018-05-28'),(15,10,4,1,'2020-01-03'),(16,10,2,1,'2020-01-03'),(17,10,12,2,'2020-01-03');
/*!40000 ALTER TABLE `order_master` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `todays_special`
--

DROP TABLE IF EXISTS `todays_special`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `todays_special` (
  `t_id` int(11) NOT NULL AUTO_INCREMENT,
  `im_id` int(11) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`t_id`),
  KEY `im_id` (`im_id`),
  CONSTRAINT `todays_special_ibfk_1` FOREIGN KEY (`im_id`) REFERENCES `item` (`im_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `todays_special`
--

LOCK TABLES `todays_special` WRITE;
/*!40000 ALTER TABLE `todays_special` DISABLE KEYS */;
INSERT INTO `todays_special` VALUES (14,12,'2018-05-28'),(15,16,'2018-05-28');
/*!40000 ALTER TABLE `todays_special` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-01-03 14:28:35
