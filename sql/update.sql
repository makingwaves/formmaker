-- MySQL dump 10.13  Distrib 5.5.28, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: ezpublish47
-- ------------------------------------------------------
-- Server version	5.5.28-0ubuntu0.12.04.3

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
  `regex` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `form_validators`
--

LOCK TABLES `form_validators` WRITE;
/*!40000 ALTER TABLE `form_validators` DISABLE KEYS */;
INSERT INTO `form_validators` VALUES (1,'Digits','Only digits',''),(2,'EmailAddress','Email address',''),(3,'Float','Float point value',''),(5,'NotEmpty','Not empty',''),(6,'Hostname','Hostname',''),(7,'Ip','IP address',''),(8,'Regex','Date','/^(([0-2][0-9])|(3[0-1]))\\/((0[1-9])|(1[0-2]))\\/([1-2][0-9]{3})$/');
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
  `post_action` enum('email','table') DEFAULT NULL,
  `recipients` text,
  `email_sender` varchar(255) NOT NULL,
  `summary_page` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `owner_user_id` (`owner_user_id`),
  CONSTRAINT `form_definitions_ibfk_2` FOREIGN KEY (`owner_user_id`) REFERENCES `ezuser` (`contentobject_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `form_definitions`
--

LOCK TABLES `form_definitions` WRITE;
/*!40000 ALTER TABLE `form_definitions` DISABLE KEYS */;
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
  `type_id` int(10) unsigned NOT NULL,
  `default_value` varchar(255) DEFAULT NULL,
  `enabled` tinyint(4) NOT NULL DEFAULT '1',
  `label` varchar(255) DEFAULT NULL,
  `email_receiver` smallint(6) NOT NULL DEFAULT '0',
  `description` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `definition_id` (`definition_id`),
  KEY `type_id` (`type_id`),
  CONSTRAINT `form_attributes_ibfk_1` FOREIGN KEY (`definition_id`) REFERENCES `form_definitions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `form_attributes_ibfk_2` FOREIGN KEY (`type_id`) REFERENCES `form_types` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `form_attributes`
--

LOCK TABLES `form_attributes` WRITE;
/*!40000 ALTER TABLE `form_attributes` DISABLE KEYS */;
/*!40000 ALTER TABLE `form_attributes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `form_attributes_options`
--

DROP TABLE IF EXISTS `form_attributes_options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `form_attributes_options` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `attr_id` int(11) NOT NULL,
  `label` varchar(255) NOT NULL,
  `opt_order` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `attr_id` (`attr_id`),
  CONSTRAINT `form_attributes_options_ibfk_1` FOREIGN KEY (`attr_id`) REFERENCES `form_attributes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `form_attributes_options`
--

LOCK TABLES `form_attributes_options` WRITE;
/*!40000 ALTER TABLE `form_attributes_options` DISABLE KEYS */;
/*!40000 ALTER TABLE `form_attributes_options` ENABLE KEYS */;
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
/*!40000 ALTER TABLE `form_attr_valid` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `form_types`
--

DROP TABLE IF EXISTS `form_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `form_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `template` varchar(100) NOT NULL,
  `validation` tinyint(4) NOT NULL DEFAULT '0',
  `sep_order` smallint(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `form_types`
--

LOCK TABLES `form_types` WRITE;
/*!40000 ALTER TABLE `form_types` DISABLE KEYS */;
INSERT INTO `form_types` VALUES (1,'Text','textline.tpl',1,1),(2,'Textarea','textarea.tpl',0,2),(3,'Checkbox','checkbox.tpl',0,3),(4,'Radio Buttons','radio.tpl',0,4),(5,'Page separator','separator.tpl',0,1000);
/*!40000 ALTER TABLE `form_types` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-01-08 10:36:05
