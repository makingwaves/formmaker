-- MySQL dump 10.13  Distrib 5.5.24, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: ezpublish47
-- ------------------------------------------------------
-- Server version	5.5.24-0ubuntu0.12.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `form_validators`
--

DROP TABLE IF EXISTS `form_validators`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `form_validators` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `form_validators`
--

LOCK TABLES `form_validators` WRITE;
/*!40000 ALTER TABLE `form_validators` DISABLE KEYS */;
INSERT INTO `form_validators` VALUES (1,'Digits','Only digits'),(2,'EmailAddress','Email address'),(3,'Float','Float point value'),(5,'NotEmpty','Not empty'),(6,'Hostname','Hostname'),(7,'Ip','IP address');
/*!40000 ALTER TABLE `form_validators` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `form_definitions`
--

DROP TABLE IF EXISTS `form_definitions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `form_definitions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `owner_user_id` int(11) DEFAULT NULL,
  `recipients` text,
  `css_class` varchar(255) DEFAULT NULL,
  `send_email` tinyint(4) NOT NULL DEFAULT '1',
  `store_data` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `owner_user_id` (`owner_user_id`),
  CONSTRAINT `form_definitions_ibfk_2` FOREIGN KEY (`owner_user_id`) REFERENCES `ezuser` (`contentobject_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `form_definitions`
--

LOCK TABLES `form_definitions` WRITE;
/*!40000 ALTER TABLE `form_definitions` DISABLE KEYS */;
INSERT INTO `form_definitions` VALUES (1,'Our first form','2012-10-22 19:59:58',14,'my@mail.com','form-class',1,1);
/*!40000 ALTER TABLE `form_definitions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `form_attributes`
--

DROP TABLE IF EXISTS `form_attributes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `form_attributes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `attr_order` int(5) DEFAULT NULL,
  `definition_id` int(11) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `default_value` varchar(255) DEFAULT NULL,
  `label` varchar(255) DEFAULT NULL,
  `css_class` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `definition_id` (`definition_id`),
  CONSTRAINT `form_attributes_ibfk_1` FOREIGN KEY (`definition_id`) REFERENCES `form_definitions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `form_attributes`
--

LOCK TABLES `form_attributes` WRITE;
/*!40000 ALTER TABLE `form_attributes` DISABLE KEYS */;
INSERT INTO `form_attributes` VALUES (1,0,1,'text','','Your name',''),(2,1,1,'text','','Email address',''),(3,2,1,'textarea','','Message',''),(4,3,1,'checkbox','0','Accept our terms','');
/*!40000 ALTER TABLE `form_attributes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `form_attr_valid`
--

DROP TABLE IF EXISTS `form_attr_valid`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `form_attr_valid` (
  `attribute_id` int(11) NOT NULL,
  `validator_id` int(11) NOT NULL,
  UNIQUE KEY `unique` (`attribute_id`,`validator_id`),
  KEY `validator_id` (`validator_id`),
  CONSTRAINT `form_attr_valid_ibfk_1` FOREIGN KEY (`attribute_id`) REFERENCES `form_attributes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `form_attr_valid_ibfk_2` FOREIGN KEY (`validator_id`) REFERENCES `form_validators` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `form_attr_valid`
--

LOCK TABLES `form_attr_valid` WRITE;
/*!40000 ALTER TABLE `form_attr_valid` DISABLE KEYS */;
INSERT INTO `form_attr_valid` VALUES (2,2),(1,5),(3,5),(4,5);
/*!40000 ALTER TABLE `form_attr_valid` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2012-10-30 14:01:41
