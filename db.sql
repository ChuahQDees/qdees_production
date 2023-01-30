-- MySQL dump 10.13  Distrib 5.5.62, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: xkyqfvfekd
-- ------------------------------------------------------
-- Server version	5.5.62-0+deb8u1

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
-- Table structure for table `allocation`
--

DROP TABLE IF EXISTS `allocation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `allocation` (
  `id` int(100) unsigned NOT NULL AUTO_INCREMENT,
  `year` varchar(4) NOT NULL,
  `student_id` int(100) unsigned NOT NULL,
  `course_id` int(100) unsigned NOT NULL,
  `class_id` int(100) unsigned NOT NULL,
  `deleted` int(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `year_student_id_course_id_class_id_deleted` (`year`,`student_id`,`course_id`,`class_id`,`deleted`)
) ENGINE=MyISAM AUTO_INCREMENT=34 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `allocation`
--

LOCK TABLES `allocation` WRITE;
/*!40000 ALTER TABLE `allocation` DISABLE KEYS */;
INSERT INTO `allocation` VALUES (1,'2019',31,2,1,0),(2,'2019',31,81,1,0),(3,'2019',32,2,1,0),(5,'2019',32,15,1,0),(6,'2019',33,2,1,0),(7,'2019',33,41,1,0),(8,'2019',33,11,1,0),(9,'2019',33,81,1,0),(10,'2019',34,2,1,0),(11,'2019',34,41,1,0),(12,'2019',34,81,1,0),(13,'2019',34,82,1,0),(14,'2019',35,2,1,0),(15,'2019',35,41,1,0),(16,'2019',35,91,1,0),(17,'2019',35,1,1,0),(18,'2019',35,42,1,0),(19,'2019',35,92,1,0),(20,'2019',41,94,1,1),(21,'2019',41,2,1,1),(22,'2019',42,2,1,0),(23,'2019',42,81,1,1),(24,'2019',42,31,1,0),(25,'2019',35,81,1,0),(26,'2019',35,97,1,0),(27,'2019',41,97,1,0),(28,'2019',35,98,1,0),(29,'2019',41,98,1,0),(30,'2019',35,2,3,1),(31,'2019',35,2,3,1),(32,'2019',35,4,3,1),(33,'2019',35,4,3,1);
/*!40000 ALTER TABLE `allocation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `busket`
--

DROP TABLE IF EXISTS `busket`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `busket` (
  `id` int(100) unsigned NOT NULL AUTO_INCREMENT,
  `session_id` varchar(200) NOT NULL,
  `student_code` varchar(100) NOT NULL,
  `product_code` varchar(100) NOT NULL,
  `qty` double(12,2) NOT NULL,
  `unit_price` double(12,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `busket`
--

LOCK TABLES `busket` WRITE;
/*!40000 ALTER TABLE `busket` DISABLE KEYS */;
INSERT INTO `busket` VALUES (15,'8grk1pjalnlsdqkujk8dpto9od','MMQWEM1C10001-0001','INT-ENG GL 1-MOD 02',1.00,0.00),(14,'8grk1pjalnlsdqkujk8dpto9od','MMQWEM1C10001-0001','INT-ENG GL 1-MO 01',1.00,0.00),(12,'8grk1pjalnlsdqkujk8dpto9od','MMQWEM1C10001-0001','BIC-BEAM-MATH L2-MOD 11',1.00,0.00),(13,'8grk1pjalnlsdqkujk8dpto9od','MMQWEM1C10001-0001','BIC-BEAM-MATH L2-SUP 11',1.00,0.00),(20,'jfrs0r2bmc8763l75r13udeu7d','MYQWEC1C10001-0003','BIC-BEAM-ENG L3-MOD 21',1.00,0.00),(28,'1aj2nfrv3ratf3v7raokg3c303','MYQWEC1C10001-0004','INT-ENG GL 1-MO 01',1.00,0.00),(27,'1aj2nfrv3ratf3v7raokg3c303','MYQWEC1C10001-0004','123445',1.00,0.00),(31,'l84k9ebcsuahnkgpdfskeg614r','MYQWEC1C10001-0007','123445',1.00,0.00),(32,'l84k9ebcsuahnkgpdfskeg614r','MYQWEC1C10001-0004','123445',1.00,0.00),(33,'l84k9ebcsuahnkgpdfskeg614r','MYQWEC1C10001-0004','INT-ENG GL 1-MO 01',1.00,0.00);
/*!40000 ALTER TABLE `busket` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cart`
--

DROP TABLE IF EXISTS `cart`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cart` (
  `id` int(100) unsigned NOT NULL AUTO_INCREMENT,
  `user_name` varchar(50) NOT NULL,
  `product_code` varchar(100) NOT NULL,
  `qty` decimal(12,2) unsigned NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=164 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart`
--

LOCK TABLES `cart` WRITE;
/*!40000 ALTER TABLE `cart` DISABLE KEYS */;
/*!40000 ALTER TABLE `cart` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `centre`
--

DROP TABLE IF EXISTS `centre`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `centre` (
  `id` int(100) unsigned NOT NULL AUTO_INCREMENT,
  `centre_code` varchar(50) NOT NULL,
  `kindergarten_name` varchar(100) NOT NULL,
  `company_name` varchar(100) NOT NULL,
  `upline` varchar(100) NOT NULL,
  `year_of_commencement` date NOT NULL,
  `year_of_renewal` int(11) NOT NULL DEFAULT '0',
  `expiry_date` date NOT NULL,
  `SSM_file` varchar(100) NOT NULL,
  `MOE_license_file` varchar(100) NOT NULL,
  `operator_name` varchar(100) NOT NULL,
  `operator_nric` varchar(100) NOT NULL,
  `operator_contact_no` varchar(100) NOT NULL,
  `principle_name` varchar(100) NOT NULL,
  `principle_contact_no` varchar(100) NOT NULL,
  `assistant_name` varchar(100) NOT NULL,
  `ANP_tel` varchar(100) NOT NULL,
  `personal_tel` varchar(100) NOT NULL,
  `status` varchar(100) NOT NULL COMMENT 'C-Closed | A-Active | S-Sell Off | T-Transferred | O-Others',
  `other_status` varchar(100) NOT NULL,
  `ANP_email` varchar(100) NOT NULL,
  `company_email` varchar(100) NOT NULL,
  `can_adjust_fee` varchar(1) NOT NULL DEFAULT 'N',
  `can_adjust_product` varchar(1) NOT NULL DEFAULT 'N',
  `address1` varchar(100) NOT NULL,
  `address2` varchar(100) NOT NULL,
  `address3` varchar(100) NOT NULL,
  `address4` varchar(100) NOT NULL,
  `address5` varchar(100) NOT NULL,
  `country` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `franchisor_company_name` varchar(100) NOT NULL,
  `centre_franchisee_company_id` varchar(100) NOT NULL,
  `centre_franchisee_name_id` varchar(100) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `centre_code` (`centre_code`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `centre`
--

LOCK TABLES `centre` WRITE;
/*!40000 ALTER TABLE `centre` DISABLE KEYS */;
INSERT INTO `centre` VALUES (7,'MYQWEC1C10001','Tadika Pandan Indah','Pandan Indah','MYQWEC10001','2015-02-01',5,'2020-02-01','HK6rcKBs.pdf','Qk9wwLfU.pdf','Grace','343343-01-3434','03 9274 0076 ','Pauline','011-23813341','zuza','03 9274 0076 ','011-23813341','A','','qdeescholars.pandanindah@gmail.com','qdeescholars.pandanindah@gmail.com','N','N','Club House 1686, ','Jalan Pandan Indah 1/16, ','Pandan Indah, Kuala Lumpur','','','Malaysia','Wilayah Persekutuan Kuala Lumpur','Corporate HQ','36','29','2019-03-12 06:29:19');
/*!40000 ALTER TABLE `centre` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `centre_agreement_file`
--

DROP TABLE IF EXISTS `centre_agreement_file`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `centre_agreement_file` (
  `id` int(100) unsigned NOT NULL AUTO_INCREMENT,
  `centre_code` varchar(50) NOT NULL,
  `doc_type` varchar(50) NOT NULL,
  `attachment` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `centre_agreement_file`
--

LOCK TABLES `centre_agreement_file` WRITE;
/*!40000 ALTER TABLE `centre_agreement_file` DISABLE KEYS */;
INSERT INTO `centre_agreement_file` VALUES (1,'MYQWEC1C10004','Franchisee Agreement','MYQWEC1C10004-kwB3RX6u.pdf'),(2,'MYQWEC1C10004','Software License Agreement','MYQWEC1C10004-lBifHhPK.pdf'),(3,'MYQWEC1C10004','Product Supply Agreement','MYQWEC1C10004-0ulshLLL.pdf'),(4,'MYQWEC1C10001','Franchisee Agreement','MYQWEC1C10001-vwduNHlW.pdf'),(5,'MYQWEC1C10001','Software License Agreement','MYQWEC1C10001-HTNkyW1M.pdf'),(7,'MYQWEC1C10001','Product Supply Agreement','MYQWEC1C10001-r1PwYJTJ.pdf');
/*!40000 ALTER TABLE `centre_agreement_file` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `centre_franchisee_company`
--

DROP TABLE IF EXISTS `centre_franchisee_company`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `centre_franchisee_company` (
  `id` int(100) unsigned NOT NULL AUTO_INCREMENT,
  `centre_code` varchar(100) NOT NULL,
  `franchisee_company_name` varchar(100) NOT NULL,
  `franchisee_company_no` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `centre_franchisee_company`
--

LOCK TABLES `centre_franchisee_company` WRITE;
/*!40000 ALTER TABLE `centre_franchisee_company` DISABLE KEYS */;
INSERT INTO `centre_franchisee_company` VALUES (36,'MYQWEC1C10001','Pusat Perkembangan Minda Detik Bersatu','SA/024993/2014'),(3,'MYQWEC1C10002','123','123'),(4,'MYQWEC1C10003','qqq','1234'),(5,'Master Code is required','tgest','test'),(8,'MYQWEC1C10004','test','test'),(33,'MYQWEC1C10005','123','123');
/*!40000 ALTER TABLE `centre_franchisee_company` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `centre_franchisee_name`
--

DROP TABLE IF EXISTS `centre_franchisee_name`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `centre_franchisee_name` (
  `id` int(100) unsigned NOT NULL AUTO_INCREMENT,
  `centre_code` varchar(100) NOT NULL,
  `franchisee_name` varchar(100) NOT NULL,
  `franchisee_passport` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `centre_franchisee_name`
--

LOCK TABLES `centre_franchisee_name` WRITE;
/*!40000 ALTER TABLE `centre_franchisee_name` DISABLE KEYS */;
INSERT INTO `centre_franchisee_name` VALUES (29,'MYQWEC1C10001','Pusat Perkembangan Minda Detik Bersatu',''),(3,'MYQWEC1C10002','chan','123'),(4,'MYQWEC1C10003','derrick','12343435'),(7,'MYQWEC1C10004','test','test'),(27,'MYQWEC1C10005','123','123'),(26,'MYQWEC1C10005','123','13');
/*!40000 ALTER TABLE `centre_franchisee_name` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `class`
--

DROP TABLE IF EXISTS `class`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `class` (
  `id` int(100) unsigned NOT NULL AUTO_INCREMENT,
  `class` varchar(100) NOT NULL,
  `description` varchar(100) NOT NULL,
  `year` varchar(4) NOT NULL,
  `centre_code` varchar(100) NOT NULL,
  `deleted` int(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `class_year` (`class`,`year`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `class`
--

LOCK TABLES `class` WRITE;
/*!40000 ALTER TABLE `class` DISABLE KEYS */;
/*!40000 ALTER TABLE `class` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `codes`
--

DROP TABLE IF EXISTS `codes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `codes` (
  `id` int(100) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(100) NOT NULL,
  `description` varchar(100) DEFAULT NULL,
  `module` varchar(100) NOT NULL,
  `country` varchar(100) NOT NULL,
  `use_code` varchar(100) NOT NULL,
  `parent` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`,`module`,`parent`)
) ENGINE=MyISAM AUTO_INCREMENT=961 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `codes`
--

LOCK TABLES `codes` WRITE;
/*!40000 ALTER TABLE `codes` DISABLE KEYS */;
INSERT INTO `codes` VALUES (144,'Malay','Malay','RACE','Malaysia','',''),(143,'Malaysia','Malaysia','COUNTRY','','MY',''),(145,'Chinese','Chinese','RACE','Malaysia','',''),(146,'Indian','Indian','RACE','Malaysia','',''),(147,'Philippines','Philippines','COUNTRY','','PH',''),(148,'Indonesia','Indonesia','COUNTRY','','ID',''),(149,'Engineer','Engineer','OCCUPATION','','',''),(151,'Islam','Islam','RELIGION','','',''),(153,'Christian','Christian','RELIGION','','',''),(154,'Malaysian','Malaysian','NATIONALITY','Malaysia','',''),(155,'Indonesian','Indonesian','NATIONALITY','Indonesia','',''),(156,'Philippino','Philippino','NATIONALITY','Philippines','',''),(162,'Selangor','Selangor','STATE','Malaysia','Y',''),(160,'Kedah','Kedah','STATE','Malaysia','Y',''),(163,'Perak','Perak','STATE','Malaysia','Y',''),(164,'Perlis','Perlis','STATE','Malaysia','Y',''),(165,'Melaka','Melaka','STATE','Malaysia','Y',''),(166,'Negeri Sembilan','Negeri Sembilan','STATE','Malaysia','Y',''),(167,'Kelantan','Kelantan','STATE','Malaysia','Y',''),(168,'Terengganu','Terengganu','STATE','Malaysia','Y',''),(169,'Pahang','Pahang','STATE','Malaysia','Y',''),(170,'Johor','Johor','STATE','Malaysia','Y',''),(171,'Sabah','Sabah','STATE','Malaysia','Y',''),(172,'Sarawak','Sarawak','STATE','Malaysia','Y',''),(173,'Pulau Pinang','Pulau Pinang','STATE','Malaysia','Y',''),(194,'Aceh','Aceh','STATE','Indonesia','',''),(176,'Wilayah Persekutuan Labuan','Wilayah Persekutuan Labuan','STATE','Malaysia','Y',''),(177,'Singapore','Singapore','COUNTRY','','SG',''),(179,'Region','Regional Office','MASTERTYPE','','',''),(180,'Country','Country Office','MASTERTYPE','','',''),(185,'Vietnam','Vietnam','COUNTRY','','VN',''),(184,'Master/Territory','Master/Territory Office','MASTERTYPE','','',''),(183,'HQ','HQ Office','MASTERTYPE','','',''),(186,'Thailand','Thailand','COUNTRY','','TH',''),(187,'Cambodia','Cambodia','COUNTRY','','KH',''),(188,'Laos','Laos','COUNTRY','','LA',''),(189,'Myanmar','Myanmar','COUNTRY','','MM',''),(190,'Brunei','Brunei','COUNTRY','','BN',''),(530,'Timor-Leste','Timor-Leste','COUNTRY','','TL',''),(192,'Wilayah Persekutuan Putrajaya',' Wilayah Persekutuan Putrajaya','STATE','Malaysia','Y',''),(193,'Wilayah Persekutuan Kuala Lumpur',' Wilayah Persekutuan Kuala Lumpur','STATE','Malaysia','Y',''),(195,'Bali','Bali','STATE','Indonesia','',''),(196,'Bangka Belitung Islands','Bangka Belitung Islands','STATE','Indonesia','',''),(197,'Bengkulu','Bengkulu','STATE','Indonesia','',''),(198,'Banten','Banten','STATE','Indonesia','',''),(199,'Central Java','Central Java','STATE','Indonesia','',''),(200,'Central Kalimantan','Central Kalimantan','STATE','Indonesia','',''),(201,'Central Sulawesi','Central Sulawesi','STATE','Indonesia','',''),(202,'East Java','East Java','STATE','Indonesia','',''),(203,'East Kalimantan','East Kalimantan','STATE','Indonesia','',''),(204,'East Nusa Tenggara','East Nusa Tenggara','STATE','Indonesia','',''),(205,'Gorontalo','Gorontalo','STATE','Indonesia','',''),(206,'Jakarta ','Jakarta ','STATE','Indonesia','',''),(207,'Jambi','Jambi','STATE','Indonesia','',''),(208,'Lampung','Lampung','STATE','Indonesia','',''),(209,'Maluku','Maluku','STATE','Indonesia','',''),(210,'North Kalimantan','North Kalimantan','STATE','Indonesia','',''),(211,'North Maluku','North Maluku','STATE','Indonesia','',''),(212,'North Sulawesi','North Sulawesi','STATE','Indonesia','',''),(213,'North Sumatra','North Sumatra','STATE','Indonesia','',''),(217,'Riau Islands','Riau Islands','STATE','Indonesia','',''),(215,'Riau','Riau','STATE','Indonesia','',''),(216,'Papua','Papua','STATE','Indonesia','',''),(218,'Southeast Sulawesi','Southeast Sulawesi','STATE','Indonesia','',''),(219,'South Kalimantan','South Kalimantan','STATE','Indonesia','',''),(220,'South Sulawesi','South Sulawesi','STATE','Indonesia','',''),(536,'Lao','Lao','NATIONALITY','Laos','',''),(628,'Kinh','Kinh','RACE','Vietnam','',''),(223,'South Sumatra','South Sumatra','STATE','Indonesia','',''),(224,'West Java','West Java','STATE','Indonesia','',''),(225,'West Kalimantan','West Kalimantan','STATE','Indonesia','',''),(226,'West Nusa Tenggara','West Nusa Tenggara','STATE','Indonesia','',''),(227,'Special Region of West Papua','Special Region of West Papua','STATE','Indonesia','',''),(228,'West Sulawesi','West Sulawesi','STATE','Indonesia','',''),(229,'West Sumatra','West Sumatra','STATE','Indonesia','',''),(230,'Special Region of Yogyakarta','Special Region of Yogyakarta','STATE','Indonesia','',''),(231,'Amnat Charoen','Amnat Charoen','STATE','Thailand','',''),(232,'Ang Thong','Ang Thong','STATE','Thailand','',''),(233,'Bueng Kan','Bueng Kan','STATE','Thailand','',''),(234,'Buriram','Buriram','STATE','Thailand','',''),(235,'Chachoengsao','Chachoengsao','STATE','Thailand','',''),(236,'Chai Nat','Chai Nat','STATE','Thailand','',''),(237,'Chaiyaphum','Chaiyaphum','STATE','Thailand','',''),(238,'Chanthaburi','Chanthaburi','STATE','Thailand','',''),(239,'Chiang Mai','Chiang Mai','STATE','Thailand','',''),(240,'Chiang Rai','Chiang Rai','STATE','Thailand','',''),(241,'Chonburi','Chonburi','STATE','Thailand','',''),(242,'Chumphon','Chumphon','STATE','Thailand','',''),(243,'Kalasin','Kalasin','STATE','Thailand','',''),(244,'Kamphaeng Phet','Kamphaeng Phet','STATE','Thailand','',''),(245,'Kanchanaburi','Kanchanaburi','STATE','Thailand','',''),(246,'Khon Kaen','Khon Kaen','STATE','Thailand','',''),(247,'Krabi','Krabi','STATE','Thailand','',''),(248,'Lampang','Lampang','STATE','Thailand','',''),(249,'Lamphun','Lamphun','STATE','Thailand','',''),(250,'Loei','Loei','STATE','Thailand','',''),(251,'Lopburi','Lopburi','STATE','Thailand','',''),(252,'Mae Hong Son','Mae Hong Son','STATE','Thailand','',''),(253,'Maha Sarakham','Maha Sarakham','STATE','Thailand','',''),(254,'Mukdahan','Mukdahan','STATE','Thailand','',''),(255,'Nakhon Nayok','Nakhon Nayok','STATE','Thailand','',''),(256,'Nakhon Pathom','Nakhon Pathom','STATE','Thailand','',''),(257,'Nakhon Phanom','Nakhon Phanom','STATE','Thailand','',''),(258,'Nakhon Ratchasima','Nakhon Ratchasima','STATE','Thailand','',''),(259,'Nakhon Sawan','Nakhon Sawan','STATE','Thailand','',''),(260,'Nakhon Si Thammarat','Nakhon Si Thammarat','STATE','Thailand','',''),(261,'Nan','Nan','STATE','Thailand','',''),(262,'Narathiwat','Narathiwat','STATE','Thailand','',''),(263,'Nong Bua Lam Phu','Nong Bua Lam Phu','STATE','Thailand','',''),(264,'Nong Khai','Nong Khai','STATE','Thailand','',''),(265,'Nonthaburi','Nonthaburi','STATE','Thailand','',''),(266,'Pathum Thani','Pathum Thani','STATE','Thailand','',''),(267,'Pattani','Pattani','STATE','Thailand','',''),(268,'Phang Nga','Phang Nga','STATE','Thailand','',''),(269,'Phatthalung','Phatthalung','STATE','Thailand','',''),(270,'Phayao','Phayao','STATE','Thailand','',''),(271,'Phetchabun','Phetchabun','STATE','Thailand','',''),(272,'Phetchaburi','Phetchaburi','STATE','Thailand','',''),(273,'Phichit','Phichit','STATE','Thailand','',''),(274,'Phitsanulok','Phitsanulok','STATE','Thailand','',''),(275,'Phra Nakhon Si Ayutthaya','Phra Nakhon Si Ayutthaya','STATE','Thailand','',''),(276,'Phrae','Phrae','STATE','Thailand','',''),(277,' Phuket',' Phuket','STATE','Thailand','',''),(278,' Prachinburi',' Prachinburi','STATE','Thailand','',''),(279,'Prachuap Khiri Khan','Prachuap Khiri Khan','STATE','Thailand','',''),(280,' Ranong',' Ranong','STATE','Thailand','',''),(281,'Ratchaburi','Ratchaburi','STATE','Thailand','',''),(282,'Rayong','Rayong','STATE','Thailand','',''),(283,'Roi Et','Roi Et','STATE','Thailand','',''),(284,'Sa Kaeo','Sa Kaeo','STATE','Thailand','',''),(285,'Sakon Nakhon','Sakon Nakhon','STATE','Thailand','',''),(286,'Samut Prakan','Samut Prakan','STATE','Thailand','',''),(287,'Samut Sakhon','Samut Sakhon','STATE','Thailand','',''),(288,'Samut Songkhram','Samut Songkhram','STATE','Thailand','',''),(289,'Saraburi','Saraburi','STATE','Thailand','',''),(290,'Satun','Satun','STATE','Thailand','',''),(291,'Sing Buri','Sing Buri','STATE','Thailand','',''),(292,'Sisaket','Sisaket','STATE','Thailand','',''),(293,'Songkhla','Songkhla','STATE','Thailand','',''),(294,'Sukhothai','Sukhothai','STATE','Thailand','',''),(295,'Suphan Buri','Suphan Buri','STATE','Thailand','',''),(296,'Surat Thani','Surat Thani','STATE','Thailand','',''),(297,' Surin',' Surin','STATE','Thailand','',''),(298,'Tak','Tak','STATE','Thailand','',''),(299,'Trang','Trang','STATE','Timor-Leste','',''),(300,'Trat','Trat','STATE','Thailand','',''),(301,'Ubon Ratchathani','Ubon Ratchathani','STATE','Thailand','',''),(302,'Udon Thani','Udon Thani','STATE','Thailand','',''),(303,'Uthai Thani','Uthai Thani','STATE','Thailand','',''),(304,'Uttaradit','Uttaradit','STATE','Thailand','',''),(305,'Yala','Yala','STATE','Thailand','',''),(306,'Yasothon','Yasothon','STATE','Thailand','',''),(307,' Bangkok',' Bangkok','STATE','Thailand','',''),(308,'Phnom Penh','Phnom Penh','STATE','Cambodia','',''),(309,'Banteay Meanchey','Banteay Meanchey','STATE','Cambodia','',''),(310,'Battambang','Battambang','STATE','Cambodia','',''),(311,'Kampong Cham','Kampong Cham','STATE','Cambodia','',''),(312,'Kampong Chhnang','Kampong Chhnang','STATE','Cambodia','',''),(313,'Kampong Speu','Kampong Speu','STATE','Cambodia','',''),(314,'Kampong Thom','Kampong Thom','STATE','Cambodia','',''),(315,'Kampot','Kampot','STATE','Cambodia','',''),(316,'Kandal','Kandal','STATE','Cambodia','',''),(317,'Koh Kong','Koh Kong','STATE','Cambodia','',''),(318,'Kep','Kep','STATE','Cambodia','',''),(319,'Kratie','Kratie','STATE','Cambodia','',''),(320,'Mondulkiri','Mondulkiri','STATE','Cambodia','',''),(321,'Oddar Meanchey','Oddar Meanchey','STATE','Cambodia','',''),(322,'Pailin','Pailin','STATE','Cambodia','',''),(323,'Sihanoukville','Sihanoukville','STATE','Cambodia','',''),(324,'Preah Vihear','Preah Vihear','STATE','Cambodia','',''),(325,'Pursat','Pursat','STATE','Cambodia','P',''),(326,'Prey Veng','Prey Veng','STATE','Cambodia','',''),(327,'Ratanakiri','Ratanakiri','STATE','Cambodia','',''),(328,'Siem Reap','Siem Reap','STATE','Cambodia','',''),(329,'Stung Treng','Stung Treng','STATE','Cambodia','',''),(330,'Svay Rieng','Svay Rieng','STATE','Cambodia','',''),(331,'Takéo','Takéo','STATE','Cambodia','',''),(332,'Tbong Khmum','Tbong Khmum','STATE','Cambodia','',''),(333,'Attapeu','Attapeu','STATE','Laos','',''),(334,'Bokeo','Bokeo','STATE','Laos','',''),(335,'Bolikhamxai','Bolikhamxai','STATE','Laos','',''),(336,'Champasak','Champasak','STATE','Cambodia','',''),(337,'Houaphan','Houaphan','STATE','Laos','',''),(338,'Khammouan','Khammouan','STATE','Laos','',''),(339,'Luang Namtha','Luang Namtha','STATE','Laos','',''),(340,'Louangphabang','Louangphabang','STATE','Laos','',''),(341,'Oudomxai','Oudomxai','STATE','Laos','',''),(342,'Phongsali','Phongsali','STATE','Laos','',''),(343,'Salavan','Salavan','STATE','Laos','',''),(344,'Savannakhet','Savannakhet','STATE','Laos','',''),(345,'Vientiane','Vientiane','STATE','Laos','',''),(346,'Xaignabouli','Xaignabouli','STATE','Laos','',''),(347,'Xekong','Xekong','STATE','Laos','',''),(348,'Xaisomboun','Xaisomboun','STATE','Laos','',''),(349,'Xiangkhouang','Xiangkhouang','STATE','Laos','',''),(350,'Thaninthayi','Thaninthayi','STATE','Myanmar','',''),(351,'Mon','Mon','STATE','Myanmar','',''),(352,'Yangon','Yangon','STATE','Myanmar','',''),(353,'Ayeyarwaddy','Ayeyarwaddy','STATE','Myanmar','',''),(354,'Kayin','Kayin','STATE','Myanmar','',''),(355,'Bago','Bago','STATE','Myanmar','',''),(356,'Rakhine','Rakhine','STATE','Myanmar','',''),(357,'Magwe','Magwe','STATE','Myanmar','',''),(358,'Mandalay','Mandalay','STATE','Myanmar','',''),(359,'Kayah','Kayah','STATE','Myanmar','',''),(360,'Shan','Shan','STATE','Myanmar','',''),(361,'Sagaing','Sagaing','STATE','Myanmar','',''),(362,'Chin','Chin','STATE','Myanmar','',''),(363,'Kachin','Kachin','STATE','Myanmar','',''),(364,'Abra','Abra','STATE','Philippines','',''),(365,'Agusan del Norte','Agusan del Norte','STATE','Philippines','',''),(366,'Agusan del Sur','Agusan del Sur','STATE','Philippines','',''),(367,'Aklan','Aklan','STATE','Philippines','',''),(368,'Albay','Albay','STATE','Philippines','',''),(369,'Antique','Antique','STATE','Philippines','',''),(370,'Apayao','Apayao','STATE','Philippines','',''),(371,'Aurora','Aurora','STATE','Philippines','',''),(372,'Basilan','Basilan','STATE','Philippines','',''),(373,'Bataan','Bataan','STATE','Philippines','',''),(374,'Batanes','Batanes','STATE','Philippines','',''),(375,'Batangas','Batangas','STATE','Philippines','',''),(376,'Benguet','Benguet','STATE','Philippines','',''),(377,'Biliran','Biliran','STATE','Philippines','',''),(378,'Bohol','Bohol','STATE','Philippines','',''),(379,'Bukidnon','Bukidnon','STATE','Philippines','',''),(380,'Bulacan','Bulacan','STATE','Philippines','',''),(381,'Cagayan','Cagayan','STATE','Philippines','',''),(382,'Camarines Norte','Camarines Norte','STATE','Philippines','',''),(383,'Camarines Sur','Camarines Sur','STATE','Philippines','',''),(384,'Camiguin','Camiguin','STATE','Philippines','',''),(385,'Capiz','Capiz','STATE','Philippines','',''),(386,'Catanduanes','Catanduanes','STATE','Philippines','',''),(387,'Cavite','Cavite','STATE','Philippines','',''),(388,'Cebu','Cebu','STATE','Philippines','',''),(389,'Compostela Valley','Compostela Valley','STATE','Philippines','',''),(390,'Cotabato','Cotabato','STATE','Philippines','',''),(391,'Davao del Norte','Davao del Norte','STATE','Philippines','',''),(392,'Davao del Sur','Davao del Sur','STATE','Philippines','',''),(393,'Davao Occidental','Davao Occidental','STATE','Philippines','',''),(394,'Davao Oriental','Davao Oriental','STATE','Philippines','',''),(395,'Dinagat Islands','Dinagat Islands','STATE','Philippines','',''),(396,'Eastern Samar','Eastern Samar','STATE','Philippines','',''),(397,'Guimaras','Guimaras','STATE','Philippines','',''),(398,'Ifugao','Ifugao','STATE','Philippines','',''),(399,'Ilocos Norte','Ilocos Norte','STATE','Philippines','',''),(400,'Ilocos Sur','Ilocos Sur','STATE','Philippines','',''),(401,'Iloilo','Iloilo','STATE','Philippines','',''),(402,'Isabela','Isabela','STATE','Philippines','',''),(403,'Kalinga','Kalinga','STATE','Philippines','',''),(404,'La Union','La Union','STATE','Philippines','',''),(405,'Laguna','Laguna','STATE','Philippines','',''),(406,'	Lanao del Norte','	Lanao del Norte','STATE','Philippines','P',''),(407,'Lanao del Norte','Lanao del Norte','STATE','Philippines','',''),(408,'Lanao del Sur','Lanao del Sur','STATE','Philippines','',''),(409,'Leyte','Leyte','STATE','Philippines','',''),(410,'Maguindanao','Maguindanao','STATE','Philippines','',''),(411,'Marinduque','Marinduque','STATE','Philippines','',''),(412,'Masbate','Masbate','STATE','Philippines','',''),(413,'Misamis Occidental','Misamis Occidental','STATE','Philippines','',''),(414,'Misamis Oriental','Misamis Oriental','STATE','Philippines','',''),(415,'Mountain Province','Mountain Province','STATE','Philippines','',''),(416,'Negros Occidental','Negros Occidental','STATE','Philippines','',''),(417,'Negros Oriental','Negros Oriental','STATE','Philippines','',''),(418,'Northern Samar','Northern Samar','STATE','Philippines','',''),(419,'Nueva Ecija','Nueva Ecija','STATE','Philippines','',''),(420,'Nueva Vizcaya','Nueva Vizcaya','STATE','Philippines','',''),(421,'Occidental Mindoro','Occidental Mindoro','STATE','Philippines','',''),(422,'Oriental Mindoro','Oriental Mindoro','STATE','Philippines','',''),(423,'Palawan','Palawan','STATE','Philippines','',''),(424,'Pampanga','Pampanga','STATE','Philippines','',''),(425,'Pangasinan','Pangasinan','STATE','Philippines','',''),(426,'Quezon','Quezon','STATE','Philippines','',''),(427,'Quirino','Quirino','STATE','Philippines','',''),(428,'Rizal','Rizal','STATE','Philippines','',''),(429,'Romblon','Romblon','STATE','Philippines','',''),(430,'Samar','Samar','STATE','Philippines','',''),(431,'Sarangani','Sarangani','STATE','Philippines','',''),(432,'Siquijor','Siquijor','STATE','Philippines','',''),(433,'Sorsogon','Sorsogon','STATE','Philippines','',''),(434,'South Cotabato','South Cotabato','STATE','Philippines','',''),(435,'Southern Leyte','Southern Leyte','STATE','Philippines','',''),(436,'Sultan Kudarat','Sultan Kudarat','STATE','Philippines','',''),(437,'Sulu','Sulu','STATE','Philippines','',''),(438,'Surigao del Norte','Surigao del Norte','STATE','Philippines','',''),(439,'Surigao del Sur','Surigao del Sur','STATE','Philippines','',''),(440,'Tarlac','Tarlac','STATE','Philippines','',''),(441,'Tawi-Tawi','Tawi-Tawi','STATE','Philippines','',''),(442,'Zambales','Zambales','STATE','Philippines','',''),(443,'Zamboanga del Norte','Zamboanga del Norte','STATE','Philippines','',''),(444,'Zamboanga del Sur','Zamboanga del Sur','STATE','Philippines','',''),(445,'Zamboanga Sibugay','Zamboanga Sibugay','STATE','Philippines','',''),(461,'Bac Giang','Bac Giang','STATE','Vietnam','',''),(447,'Oecusse','Oecusse','STATE','Timor-Leste','',''),(448,'Liquica','Liquica','STATE','Timor-Leste','',''),(449,'Dili','Dili','STATE','Timor-Leste','',''),(450,'Manatuto','Manatuto','STATE','Timor-Leste','',''),(451,'Baucau','Baucau','STATE','Timor-Leste','',''),(452,'Lautem','Lautem','STATE','Timor-Leste','',''),(453,'Bobonaro','Bobonaro','STATE','Timor-Leste','',''),(454,'Ermera','Ermera','STATE','Timor-Leste','',''),(455,'Aileu','Aileu','STATE','Timor-Leste','',''),(456,'Viqueque','Viqueque','STATE','Timor-Leste','',''),(457,'Cova Lima','Cova Lima','STATE','Timor-Leste','',''),(458,'Ainaro','Ainaro','STATE','Timor-Leste','',''),(459,'Manufahi','Manufahi','STATE','Timor-Leste','',''),(460,'Manila','Manila','STATE','Philippines','',''),(469,'Thai Nguyen','Thai Nguyen','STATE','Vietnam','',''),(463,'Cao Bang','Cao Bang','STATE','Vietnam','',''),(464,'Ha Giang','Ha Giang','STATE','Vietnam','',''),(465,'Lang Son','Lang Son','STATE','Vietnam','',''),(466,'Phu Tho','Phu Tho','STATE','Vietnam','',''),(467,'Quang Ninh','Quang Ninh','STATE','Vietnam','',''),(468,'Bac Kan','Bac Kan','STATE','Vietnam','',''),(470,'Tuyen Quang','Tuyen Quang','STATE','Vietnam','',''),(471,'Lao Cai','Lao Cai','STATE','Vietnam','',''),(472,'Yen Bai','Yen Bai','STATE','Vietnam','',''),(473,'Dien Bien','Dien Bien','STATE','Vietnam','',''),(474,'Hoa Binh','Hoa Binh','STATE','Vietnam','',''),(475,'Lai Chau','Lai Chau','STATE','Vietnam','',''),(476,'Son La','Son La','STATE','Vietnam','',''),(477,'Bac Ninh','Bac Ninh','STATE','Vietnam','',''),(478,'Ha Nam','Ha Nam','STATE','Vietnam','',''),(479,'Hai Duong','Hai Duong','STATE','Vietnam','',''),(480,'Hung Yen','Hung Yen','STATE','Vietnam','',''),(481,'Nam Dinh','Nam Dinh','STATE','Vietnam','',''),(482,'Ninh Binh','Ninh Binh','STATE','Vietnam','',''),(483,'Thai Binh','Thai Binh','STATE','Vietnam','',''),(484,'Vinh Phuc','Vinh Phuc','STATE','Vietnam','',''),(485,'Hanoi','Hanoi','STATE','Vietnam','',''),(486,'Hai Phong','Hai Phong','STATE','Vietnam','',''),(487,'Ha Tinh','Ha Tinh','STATE','Vietnam','',''),(488,'Nghe An','Nghe An','STATE','Vietnam','',''),(489,'Quang Binh','Quang Binh','STATE','Vietnam','',''),(490,'Quang Tri','Quang Tri','STATE','Vietnam','',''),(491,'Thanh Hoa','Thanh Hoa','STATE','Vietnam','',''),(492,'Thua Thien–Hue','Thua Thien–Hue','STATE','Vietnam','',''),(493,'Dak Lak','Dak Lak','STATE','Vietnam','',''),(494,'Dak Nong','Dak Nong','STATE','Vietnam','',''),(495,'Gia Lai','Gia Lai','STATE','Vietnam','',''),(496,'Kon Tum','Kon Tum','STATE','Vietnam','',''),(497,'Lam Dong','Lam Dong','STATE','Vietnam','',''),(498,'Binh Dinh','Binh Dinh','STATE','Vietnam','',''),(499,'Binh Thuan','Binh Thuan','STATE','Vietnam','',''),(500,'Khanh Hoa','Khanh Hoa','STATE','Vietnam','',''),(501,'Ninh Thuan','Ninh Thuan','STATE','Vietnam','',''),(502,'Phu Yen','Phu Yen','STATE','Vietnam','',''),(503,'Quang Nam','Quang Nam','STATE','Vietnam','',''),(504,'Quang Ngai','Quang Ngai','STATE','Timor-Leste','',''),(505,'Da Nang','Da Nang','STATE','Vietnam','',''),(506,'Ba Ria–Vung Tau ','Ba Ria–Vung Tau ','STATE','Vietnam','',''),(507,'Binh Duong','Binh Duong','STATE','Vietnam','',''),(508,'Binh Phuoc','Binh Phuoc','STATE','Vietnam','',''),(509,'Dong Nai','Dong Nai','STATE','Vietnam','',''),(510,'Tay Ninh','Tay Ninh','STATE','Vietnam','',''),(511,'Ho Chi Minh','Ho Chi Minh','STATE','Vietnam','',''),(512,'An Giang','An Giang','STATE','Vietnam','',''),(513,'Bac Lieu','Bac Lieu','STATE','Vietnam','',''),(514,'Ben Tre','Ben Tre','STATE','Vietnam','',''),(515,'Ca Mau','Ca Mau','STATE','Vietnam','',''),(516,'Dong Thap','Dong Thap','STATE','Vietnam','',''),(517,'Hau Giang','Hau Giang','STATE','Vietnam','',''),(518,'Kien Giang','Kien Giang','STATE','Vietnam','',''),(519,'Long An','Long An','STATE','Vietnam','',''),(520,'Soc Trang','Soc Trang','STATE','Vietnam','',''),(521,'Tien Giang','Tien Giang','STATE','Vietnam','',''),(522,'Tra Vinh','Tra Vinh','STATE','Vietnam','',''),(523,'Vinh Long','Vinh Long','STATE','Vietnam','',''),(524,'Can Tho','Can Tho','STATE','Vietnam','',''),(525,'Tutong','Tutong','STATE','Brunei','',''),(526,'Temburong','Temburong','STATE','Brunei','',''),(527,'Brunei-Muara','Brunei-Muara','STATE','Brunei','',''),(528,'Belait','Belait','STATE','Brunei','',''),(538,'Bruneian','Bruneian','NATIONALITY','Brunei','',''),(539,'Cambodian','Cambodian','NATIONALITY','Cambodia','',''),(540,'Myanma','Myanma','NATIONALITY','Myanmar','',''),(541,'Singaporean','Singaporean','NATIONALITY','Singapore','',''),(542,'Thai','Thai','NATIONALITY','Thailand','',''),(543,'Timorese','Timorese','NATIONALITY','Timor-Leste','',''),(544,'Vietnamese ','Vietnamese ','NATIONALITY','Vietnam','',''),(547,'Kedayan','Kedayan','RACE','Malaysia','',''),(548,'Dusun','Dusun','RACE','Malaysia','',''),(549,'Iban','Iban','RACE','Malaysia','',''),(550,'Melanau','Melanau','RACE','Malaysia','',''),(551,' Khmer',' Khmer','RACE','Cambodia','',''),(553,'Lao Loum','Lao Loum','RACE','Laos','',''),(554,'Lao Theung','Lao Theung','RACE','Laos','',''),(555,'Lao Soung','Lao Soung','RACE','Laos','',''),(573,'Bicolano','Bicolano','RACE','Philippines','',''),(557,'Kapampangan','Kapampangan','RACE','Philippines','',''),(558,'Bisaya','Bisaya','RACE','Brunei','',''),(559,' Visayans',' Visayans','RACE','Philippines','',''),(560,'Tagalogs','Tagalogs','RACE','Philippines','',''),(561,' Ilocanos',' Ilocanos','RACE','Philippines','',''),(574,'Tai ','Tai ','RACE','Thailand','',''),(563,'Moros','Moros','RACE','Philippines','',''),(564,' Kapampangans',' Kapampangans','RACE','Philippines','',''),(565,' Pangasinenses',' Pangasinenses','RACE','Philippines','',''),(566,' Zamboanguenos',' Zamboanguenos','RACE','Philippines','',''),(567,' Spanish',' Spanish','RACE','Philippines','',''),(568,'Latino','Latino','RACE','','',''),(569,'American','American','RACE','Philippines','',''),(570,'Japanese','Japanese','RACE','Singapore','',''),(571,'Arab','Arab','RACE','Indonesia','',''),(572,'Bamar','Bamar','RACE','Myanmar','',''),(575,'Karen ','Karen ','RACE','Myanmar','',''),(576,'Kuy','Kuy','RACE','Thailand','',''),(577,'Hmong','Hmong','RACE','Laos','',''),(578,'Phuan','Phuan','RACE','Laos','',''),(579,'Lahu','Lahu','RACE','Myanmar','',''),(582,'Lue','Lue','RACE','Laos','',''),(581,'Nyaw','Nyaw','RACE','Thailand','',''),(583,'Lua','Lua','RACE','Thailand','',''),(584,'Lisu','Lisu','RACE','Myanmar','',''),(585,'Yao','Yao','RACE','Vietnam','',''),(586,'Bru','Bru','RACE','Vietnam','',''),(587,'Akha','Akha','RACE','Laos','',''),(588,'Phai','Phai','RACE','Thailand','',''),(589,'Lawa','Lawa','RACE','Thailand','',''),(590,'Saek','Saek','RACE','Laos','',''),(591,'Khmu','Khmu','RACE','Laos','',''),(592,'Palaung','Palaung','RACE','Myanmar','',''),(593,'Cham','Cham','RACE','Vietnam','',''),(594,'Urak Lawoi','Urak Lawoi','RACE','Thailand','',''),(595,'Moken','Moken','RACE','Thailand','',''),(596,'Nyahkur','Nyahkur','RACE','Thailand','',''),(597,'Tai Dam','Tai Dam','RACE','Thailand','',''),(598,'Chong','Chong','RACE','Thailand','',''),(599,'Pear','Pear','RACE','Cambodia','',''),(600,'Saoch','Saoch','RACE','Cambodia','',''),(601,'Mlabri','Mlabri','RACE','Thailand','',''),(602,'Mani','Mani','RACE','Thailand','',''),(603,'Javanese','Javanese','RACE','Indonesia','',''),(604,'Sundanese','Sundanese','RACE','Indonesia','',''),(605,'Madurese','Madurese','RACE','Indonesia','',''),(606,'Batak','Batak','RACE','Indonesia','',''),(607,'Minangkabau','Minangkabau','RACE','Indonesia','',''),(608,'Betawi','Betawi','RACE','Indonesia','',''),(609,'Bugis','Bugis','RACE','Indonesia','',''),(610,'Acehnese','Acehnese','RACE','Indonesia','',''),(611,'Bantenese','Bantenese','RACE','Indonesia','',''),(612,'Banjarese','Banjarese','RACE','Indonesia','',''),(613,'Balinese','Balinese','RACE','Indonesia','',''),(614,'Sasak','Sasak','RACE','Indonesia','',''),(615,'Makassarese','Makassarese','RACE','Indonesia','',''),(616,'Minahasan','Minahasan','RACE','Indonesia','',''),(617,'Cirebonese','Cirebonese','RACE','Indonesia','',''),(618,'Dayak','Dayak','RACE','Malaysia','',''),(619,'Papuan','Papuan','RACE','Indonesia','',''),(620,'Moluccans','Moluccans','RACE','Indonesia','',''),(621,'Gorontaloan','Gorontaloan','RACE','Indonesia','',''),(622,'Nias','Nias','RACE','Indonesia','',''),(624,'Portuguese','Portuguese','RACE','Timor-Leste','',''),(625,'European','European','RACE','Vietnam','',''),(626,'Malayo-Polynesian','Malayo-Polynesian','RACE','Philippines','',''),(629,'Chut','Chut','RACE','Vietnam','',''),(630,'Muong','Muong','RACE','Vietnam','',''),(631,'Tho','Tho','RACE','Vietnam','',''),(632,'Bouyei','Bouyei','RACE','Vietnam','',''),(633,'Giay','Giay','RACE','Laos','',''),(634,'Nung','Nung','RACE','Vietnam','',''),(635,'San Chay','San Chay','RACE','Vietnam','',''),(636,'Tay','Tay','RACE','Vietnam','',''),(637,'Gelao','Gelao','RACE','Vietnam','',''),(638,'La Chí','La Chí','RACE','Vietnam','',''),(639,'La Ha','La Ha','RACE','Vietnam','',''),(640,'Qabiao','Qabiao','RACE','Vietnam','',''),(641,'Bahnar','Bahnar','RACE','Vietnam','',''),(642,'Brau','Brau','RACE','Vietnam','',''),(643,'Cho Ro','Cho Ro','RACE','Vietnam','',''),(644,'Chor','Chor','RACE','Vietnam','',''),(645,'Koho','Koho','RACE','Vietnam','',''),(646,'Katu','Katu','RACE','Vietnam','',''),(647,'Gie Trieng','Gie Trieng','RACE','Vietnam','',''),(648,'Hre','Hre','RACE','Vietnam','',''),(649,'Khang','Khang','RACE','Vietnam','',''),(650,'Khmer Krom','Khmer Krom','RACE','Vietnam','',''),(651,'Kho Mu','Kho Mu','RACE','Laos','',''),(652,'Ma','Ma','RACE','Vietnam','',''),(653,'Mang','Mang','RACE','Vietnam','',''),(654,'Mnong','Mnong','RACE','Vietnam','',''),(655,'O Du','O Du','RACE','Vietnam','',''),(656,'Ro Mam','Ro Mam','RACE','Vietnam','',''),(657,'Ta Oi','Ta Oi','RACE','Vietnam','',''),(658,'Xinh Mun','Xinh Mun','RACE','Vietnam','',''),(659,'Xo Dang','Xo Dang','RACE','Vietnam','',''),(660,'Stieng','Stieng','RACE','Cambodia','',''),(661,'Dao','Dao','RACE','Indonesia','',''),(738,'Pos Laju','Pos Laju','COURIER','','',''),(663,'Pa Then','Pa Then','RACE','Vietnam','',''),(664,'Churu','Churu','RACE','Vietnam','',''),(665,'Jarai ','Jarai ','RACE','Cambodia','',''),(666,'Raglai','Raglai','RACE','Vietnam','',''),(667,'Phunoi','Phunoi','RACE','Laos','',''),(668,'Hani','Hani','RACE','Vietnam','',''),(669,'Phu La','Phu La','RACE','Vietnam','',''),(670,'Si La','Si La','RACE','Laos','',''),(739,'Skynet','Skynet','COURIER','','',''),(932,'Q-dees IQ Math Modules Level 4','','CATEGORY','','','BIMP'),(924,'International English Modules (Level1)','','CATEGORY','','','IE'),(925,'International English Modules (Level2)','','CATEGORY','','','IE'),(916,'IE','','CATEGORY','','','Q-dees Holdings Sdn. Bhd.'),(917,'International English Modules (Pre-Level1)','','CATEGORY','','','IE'),(918,'Mindspectrum Sdn.Bhd','','CATEGORY','','',''),(920,'Tenations Global Sdn. Bhd.','','CATEGORY','','',''),(921,'BIEP','','CATEGORY','','','Tenations Global Sdn. Bhd.'),(922,'BIEP Beamodules (Level3)','','CATEGORY','','','BIEP'),(923,'IE','IE','CATEGORY','','','Mindspectrum Sdn.Bhd'),(740,'B. Sc.','','EDUCATION','','',''),(741,'M. Sc.','','EDUCATION','','',''),(933,'Q-dees IQ Math Modules Level 5','','CATEGORY','','','BIMP'),(908,'Self-Pickup','Self-Pickup','COURIER','','',''),(915,'Q-dees Holdings Sdn. Bhd.','','CATEGORY','','',''),(742,'Ph. D.','','EDUCATION','','',''),(931,'BIMP','','CATEGORY','','','Mindspectrum Sdn.Bhd'),(746,'Asia Pacific','Asia Pacific','REGION','','',''),(747,'Africa','Africa','REGION','','',''),(748,'Caribbean','Caribbean','REGION','','',''),(749,'Central America','Central America','REGION','','',''),(750,'Europe','Europe','REGION','','',''),(751,'North America','North America','REGION','','',''),(752,'Oceania','Oceania','REGION','','',''),(753,'South America','South America','REGION','','',''),(852,'Law, Public Safety, Corrections and Security','Law, Public Safety, Corrections and Security','OCCUPATION','','',''),(849,'Finance','Finance','OCCUPATION','','',''),(858,'Science, Technology, and Mathematics','Science, Technology, and Mathematics','OCCUPATION','','',''),(851,'Human Services','Human Services','OCCUPATION','','',''),(758,'Education','Education','OCCUPATION','','',''),(759,'Energy and Utilities','Energy and Utilities','OCCUPATION','','',''),(850,'Hospitality and Tourism','Hospitality and Tourism','OCCUPATION','','',''),(761,'Government','Government','OCCUPATION','','',''),(762,'Health, Pharmaceuticals, and Biotech','Health, Pharmaceuticals, and Biotech','OCCUPATION','','',''),(763,'Manufacturing','Manufacturing','OCCUPATION','','',''),(764,'Media and Entertainment','Media and Entertainment','OCCUPATION','','',''),(765,'Retail','Retail','OCCUPATION','','',''),(766,'Telecommunications','Telecommunications','OCCUPATION','','',''),(857,'Information Technology','Information Technology','OCCUPATION','','',''),(768,'Wholesale and Distribution','Wholesale and Distribution','OCCUPATION','','',''),(910,'Transporter','Transporter','COURIER','','',''),(926,'BIEP','','CATEGORY','','','Q-dees Holdings Sdn. Bhd.'),(927,'BIEP Supplementary Sheets (Level3)','','CATEGORY','','','BIEP'),(892,'Hinduism','Hinduism','RELIGION','','',''),(893,'Buddhism','Buddhism','RELIGION','','',''),(909,'GDex','GDex','COURIER','','',''),(821,'Others','Others','RELIGION','','',''),(846,'Architecture and Construction','Architecture and Construction','OCCUPATION','','',''),(848,'Business Management and Administration','Business Management and Administration','OCCUPATION','','',''),(853,'Marketing, Sales and Service','Marketing, Sales and Service','OCCUPATION','','',''),(856,'Transportation','Transportation','OCCUPATION','','',''),(855,'Agriculture','Agriculture','OCCUPATION','','',''),(859,'Khmer','Khmer','RACE','Thailand','',''),(930,'BIMP Supplementary Sheets (Level3)','','CATEGORY','','','BIMP'),(929,'BIMP Supplementary Sheets (Level2)','','CATEGORY','','','BIMP'),(928,'BIMP','','CATEGORY','','','Q-dees Holdings Sdn. Bhd.'),(941,'Banner/Bunting','','VISITREASON','','',''),(934,'BIEP Beamodules (Level4)','','CATEGORY','','','BIEP'),(935,'BIMP','','CATEGORY','','','Tenations Global Sdn. Bhd.'),(936,'BIMP Beamodules (Level1)','','CATEGORY','','','BIMP'),(937,'BIMP Beamodules (Level2)','','CATEGORY','','','BIMP'),(938,'BIMP Beamodules (Level3)','','CATEGORY','','','BIMP'),(939,'BIMP Beamodules (Level4)','','CATEGORY','','','BIMP'),(942,'Exhibition','','VISITREASON','','',''),(944,'Fun Fair','','VISITREASON','','',''),(945,'Flyer','','VISITREASON','','',''),(946,'Magazine','','VISITREASON','','',''),(947,'Neighbourhood','','VISITREASON','','',''),(948,'Newspaper','','VISITREASON','','',''),(949,'Radio','','VISITREASON','','',''),(950,'Sibling','','VISITREASON','','',''),(951,'Television','','VISITREASON','','',''),(952,'Website','','VISITREASON','','',''),(953,'Word of Mouth','','VISITREASON','','',''),(954,'Transfer to Other Scholars','','DROPOUTREASON','','',''),(955,'Family Relocation','','DROPOUTREASON','','',''),(956,'Financial Difficulties','','DROPOUTREASON','','',''),(957,'Poor Q-Coach Delivery','','DROPOUTREASON','','',''),(958,'Program Quality','','DROPOUTREASON','','',''),(959,'Other Dropout Reason','','DROPOUTREASON','','',''),(960,'Nationwide','Nationwide','COURIER','','','');
/*!40000 ALTER TABLE `codes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `collection`
--

DROP TABLE IF EXISTS `collection`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `collection` (
  `id` int(100) unsigned NOT NULL AUTO_INCREMENT,
  `allocation_id` int(100) unsigned NOT NULL,
  `centre_code` varchar(100) DEFAULT NULL,
  `batch_no` varchar(100) NOT NULL,
  `collection_date_time` datetime NOT NULL,
  `product_code` varchar(100) NOT NULL,
  `qty` double(12,2) NOT NULL,
  `unit_price` double(12,2) NOT NULL,
  `amount` double(12,2) NOT NULL DEFAULT '0.00',
  `collection_type` varchar(100) NOT NULL,
  `year` varchar(4) NOT NULL,
  `collection_month` varchar(2) NOT NULL,
  `pic` varchar(100) NOT NULL,
  `void` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `void_reason` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=128 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `collection`
--

LOCK TABLES `collection` WRITE;
/*!40000 ALTER TABLE `collection` DISABLE KEYS */;
INSERT INTO `collection` VALUES (1,2,'MYQWEC1C10001','9B9D3ZAj','2019-02-27 03:32:27','',0.00,0.00,370.00,'tuition','2019','t1','test',1,'Wrongly entered'),(2,2,'MYQWEC1C10001','9B9D3ZAj','2019-02-27 03:32:27','',0.00,0.00,50.00,'registration','2019','','test',1,'Wrongly entered, sorry'),(3,2,'MYQWEC1C10001','9B9D3ZAj','2019-02-27 03:32:27','',0.00,0.00,200.00,'deposit','2019','','test',1,'refund'),(4,2,'MYQWEC1C10001','9B9D3ZAj','2019-02-27 03:32:27','',0.00,0.00,100.00,'placement','2019','','test',0,''),(5,1,'MYQWEC1C10001','9B9D3ZAj','2019-02-27 03:32:27','',0.00,0.00,170.00,'tuition','2019','1','test',0,''),(6,1,'MYQWEC1C10001','9B9D3ZAj','2019-02-27 03:32:27','',0.00,0.00,50.00,'registration','2019','','test',0,''),(7,1,'MYQWEC1C10001','9B9D3ZAj','2019-02-27 03:32:27','',0.00,0.00,200.00,'deposit','2019','','test',0,''),(8,1,'MYQWEC1C10001','9B9D3ZAj','2019-02-27 03:32:27','',0.00,0.00,100.00,'placement','2019','','test',0,''),(9,1,'MYQWEC1C10001','hNlvNIu9','2019-02-27 06:50:27','INT-ENG GL 1-MO 01',1.00,0.00,0.00,'product','2019','2','test',0,''),(10,5,'MYQWEC1C10001','fBMTufit','2019-02-28 05:31:46','BIC-BEAM-ENG L3-MOD 25',1.00,0.00,0.00,'product','2019','1','test',0,''),(11,3,'MYQWEC1C10001','eIaHBI9J','2019-02-28 05:32:08','',0.00,0.00,170.00,'tuition','2019','1','test',0,''),(12,3,'MYQWEC1C10001','eIaHBI9J','2019-02-28 05:32:08','',0.00,0.00,50.00,'registration','2019','','test',0,''),(13,3,'MYQWEC1C10001','eIaHBI9J','2019-02-28 05:32:08','',0.00,0.00,200.00,'deposit','2019','','test',0,''),(14,3,'MYQWEC1C10001','eIaHBI9J','2019-02-28 05:32:08','',0.00,0.00,100.00,'placement','2019','','test',0,''),(15,5,'MYQWEC1C10001','eIaHBI9J','2019-02-28 05:32:08','',0.00,0.00,170.00,'tuition','2019','1','test',0,''),(16,5,'MYQWEC1C10001','eIaHBI9J','2019-02-28 05:32:08','',0.00,0.00,50.00,'registration','2019','','test',0,''),(17,5,'MYQWEC1C10001','eIaHBI9J','2019-02-28 05:32:08','',0.00,0.00,200.00,'deposit','2019','','test',0,''),(18,5,'MYQWEC1C10001','eIaHBI9J','2019-02-28 05:32:08','',0.00,0.00,100.00,'placement','2019','','test',0,''),(19,3,'MYQWEC1C10001','gQ1bz6OX','2019-02-28 05:32:23','',0.00,0.00,170.00,'tuition','2019','2','test',0,''),(20,5,'MYQWEC1C10001','gQ1bz6OX','2019-02-28 05:32:23','',0.00,0.00,170.00,'tuition','2019','2','test',0,''),(21,5,'MYQWEC1C10001','tcu4GJFc','2019-02-28 05:32:45','',0.00,0.00,170.00,'tuition','2019','3','test',0,''),(22,5,'MYQWEC1C10001','tcu4GJFc','2019-02-28 05:32:45','BIC-BEAM-ENG L3-MOD 25',1.00,0.00,0.00,'product','2019','2','test',0,''),(23,2,'MYQWEC1C10001','jndTtAX8','2019-02-28 07:09:10','',0.00,0.00,370.00,'tuition','2019','t2','test',0,''),(24,9,'MYQWEC1C10004','MsfCSGds','2019-02-28 09:13:29','',0.00,0.00,370.00,'tuition','2019','t1','SS17',0,''),(25,9,'MYQWEC1C10004','MsfCSGds','2019-02-28 09:13:29','',0.00,0.00,50.00,'registration','2019','','SS17',0,''),(26,9,'MYQWEC1C10004','MsfCSGds','2019-02-28 09:13:29','',0.00,0.00,200.00,'deposit','2019','','SS17',0,''),(27,9,'MYQWEC1C10004','MsfCSGds','2019-02-28 09:13:29','',0.00,0.00,100.00,'placement','2019','','SS17',0,''),(28,6,'MYQWEC1C10004','MsfCSGds','2019-02-28 09:13:29','',0.00,0.00,170.00,'tuition','2019','1','SS17',0,''),(29,6,'MYQWEC1C10004','MsfCSGds','2019-02-28 09:13:29','',0.00,0.00,50.00,'registration','2019','','SS17',0,''),(30,6,'MYQWEC1C10004','MsfCSGds','2019-02-28 09:13:29','',0.00,0.00,200.00,'deposit','2019','','SS17',0,''),(31,6,'MYQWEC1C10004','MsfCSGds','2019-02-28 09:13:29','',0.00,0.00,100.00,'placement','2019','','SS17',0,''),(32,8,'MYQWEC1C10004','MsfCSGds','2019-02-28 09:13:29','',0.00,0.00,170.00,'tuition','2019','1','SS17',0,''),(33,8,'MYQWEC1C10004','MsfCSGds','2019-02-28 09:13:29','',0.00,0.00,50.00,'registration','2019','','SS17',0,''),(34,8,'MYQWEC1C10004','MsfCSGds','2019-02-28 09:13:29','',0.00,0.00,200.00,'deposit','2019','','SS17',0,''),(35,8,'MYQWEC1C10004','MsfCSGds','2019-02-28 09:13:29','',0.00,0.00,100.00,'placement','2019','','SS17',0,''),(36,7,'MYQWEC1C10004','MsfCSGds','2019-02-28 09:13:29','',0.00,0.00,170.00,'tuition','2019','1','SS17',0,''),(37,7,'MYQWEC1C10004','MsfCSGds','2019-02-28 09:13:29','',0.00,0.00,50.00,'registration','2019','','SS17',0,''),(38,7,'MYQWEC1C10004','MsfCSGds','2019-02-28 09:13:29','',0.00,0.00,200.00,'deposit','2019','','SS17',0,''),(39,7,'MYQWEC1C10004','MsfCSGds','2019-02-28 09:13:29','',0.00,0.00,100.00,'placement','2019','','SS17',0,''),(40,7,'MYQWEC1C10004','MsfCSGds','2019-02-28 09:13:29','BIC-BEAM-MATH L2-MOD 11',1.00,0.00,0.00,'product','2019','2','SS17',0,''),(41,7,'MYQWEC1C10004','MsfCSGds','2019-02-28 09:13:29','BIC-BEAM-MATH L2-SUP 11',1.00,0.00,0.00,'product','2019','2','SS17',0,''),(42,7,'MYQWEC1C10004','MsfCSGds','2019-02-28 09:13:29','BIC-BEAM-ENG L3-MOD 21',1.00,0.00,0.00,'product','2019','2','SS17',0,''),(43,7,'MYQWEC1C10004','MsfCSGds','2019-02-28 09:13:29','INT-ENG GL 1-MO 01',1.00,0.00,0.00,'product','2019','2','SS17',0,''),(44,3,'MYQWEC1C10001','73dUpeon','2019-03-01 03:45:22','',0.00,0.00,170.00,'tuition','2019','3','test',1,''),(45,12,'MMQWEM1C10001','pNNNW2Es','2019-03-01 04:14:06','',0.00,0.00,370.00,'tuition','2019','t1','test1234',1,''),(46,12,'MMQWEM1C10001','pNNNW2Es','2019-03-01 04:14:06','',0.00,0.00,50.00,'registration','2019','','test1234',1,'test'),(47,12,'MMQWEM1C10001','pNNNW2Es','2019-03-01 04:14:06','',0.00,0.00,200.00,'deposit','2019','','test1234',1,'test'),(48,12,'MMQWEM1C10001','pNNNW2Es','2019-03-01 04:14:06','',0.00,0.00,100.00,'placement','2019','','test1234',0,''),(49,10,'MMQWEM1C10001','pNNNW2Es','2019-03-01 04:14:06','',0.00,0.00,170.00,'tuition','2019','1','test1234',1,''),(50,10,'MMQWEM1C10001','pNNNW2Es','2019-03-01 04:14:06','',0.00,0.00,50.00,'registration','2019','','test1234',0,''),(51,10,'MMQWEM1C10001','pNNNW2Es','2019-03-01 04:14:06','',0.00,0.00,200.00,'deposit','2019','','test1234',0,''),(52,10,'MMQWEM1C10001','pNNNW2Es','2019-03-01 04:14:06','',0.00,0.00,100.00,'placement','2019','','test1234',0,''),(53,11,'MMQWEM1C10001','pNNNW2Es','2019-03-01 04:14:06','',0.00,0.00,170.00,'tuition','2019','1','test1234',0,''),(54,11,'MMQWEM1C10001','pNNNW2Es','2019-03-01 04:14:06','',0.00,0.00,50.00,'registration','2019','','test1234',0,''),(55,11,'MMQWEM1C10001','pNNNW2Es','2019-03-01 04:14:06','',0.00,0.00,200.00,'deposit','2019','','test1234',0,''),(56,11,'MMQWEM1C10001','pNNNW2Es','2019-03-01 04:14:06','',0.00,0.00,100.00,'placement','2019','','test1234',0,''),(57,11,'MMQWEM1C10001','pNNNW2Es','2019-03-01 04:14:06','INT-ENG GL 1-MO 01',2.00,0.00,0.00,'product','2019','3','test1234',0,''),(58,11,'MMQWEM1C10001','pNNNW2Es','2019-03-01 04:14:06','BIC-BEAM-MATH L2-SUP 11',1.00,0.00,0.00,'product','2019','3','test1234',0,''),(59,11,'MMQWEM1C10001','pNNNW2Es','2019-03-01 04:14:06','BIC-BEAM-MATH L2-MOD 11',1.00,0.00,0.00,'product','2019','3','test1234',0,''),(91,16,'MYQWEC1C10001','4MvSCj7P','2019-03-15 03:05:45','',0.00,0.00,370.00,'tuition','2019','t1','test',0,''),(92,16,'MYQWEC1C10001','4MvSCj7P','2019-03-15 03:05:45','',0.00,0.00,50.00,'registration','2019','','test',0,''),(93,16,'MYQWEC1C10001','4MvSCj7P','2019-03-15 03:05:45','',0.00,0.00,200.00,'deposit','2019','','test',0,''),(94,16,'MYQWEC1C10001','4MvSCj7P','2019-03-15 03:05:45','',0.00,0.00,100.00,'placement','2019','','test',0,''),(95,14,'MYQWEC1C10001','EDZDKdow','2019-03-15 03:06:03','',0.00,0.00,170.00,'tuition','2019','1','test',0,''),(96,14,'MYQWEC1C10001','C8KHWZm0','2019-03-15 03:07:50','',0.00,0.00,170.00,'tuition','2019','2','test1',0,''),(97,16,'MYQWEC1C10001','zHbayXf5','2019-03-15 08:12:23','',0.00,0.00,370.00,'tuition','2019','t1','Pandan Indah',0,''),(98,19,'MYQWEC1C10001','zHbayXf5','2019-03-15 08:12:23','',0.00,0.00,370.00,'tuition','2019','t1','Pandan Indah',0,''),(99,14,'MYQWEC1C10001','zHbayXf5','2019-03-15 08:12:23','',0.00,0.00,170.00,'tuition','2019','1','Pandan Indah',0,''),(100,17,'MYQWEC1C10001','zHbayXf5','2019-03-15 08:12:23','',0.00,0.00,170.00,'tuition','2019','1','Pandan Indah',0,''),(101,15,'MYQWEC1C10001','zHbayXf5','2019-03-15 08:12:23','',0.00,0.00,170.00,'tuition','2019','1','Pandan Indah',0,''),(102,18,'MYQWEC1C10001','zHbayXf5','2019-03-15 08:12:23','',0.00,0.00,170.00,'tuition','2019','1','Pandan Indah',0,''),(103,17,'MYQWEC1C10001','MYQWEC1C10001-000001','2019-03-19 12:13:45','',0.00,0.00,170.00,'tuition','2019','2','test1',0,''),(105,15,'MYQWEC1C10001','MYQWEC1C10001-000002','2019-03-19 12:21:35','',0.00,0.00,170.00,'tuition','2019','2','test1',0,''),(106,23,'MYQWEC1C10001','MYQWEC1C10001-000003','2019-03-25 07:06:30','',0.00,0.00,370.00,'tuition','2019','t1','Pandan Indah',0,''),(107,23,'MYQWEC1C10001','MYQWEC1C10001-000003','2019-03-25 07:06:30','',0.00,0.00,50.00,'registration','2019','','Pandan Indah',0,''),(108,23,'MYQWEC1C10001','MYQWEC1C10001-000003','2019-03-25 07:06:30','',0.00,0.00,200.00,'deposit','2019','','Pandan Indah',0,''),(109,23,'MYQWEC1C10001','MYQWEC1C10001-000003','2019-03-25 07:06:30','',0.00,0.00,100.00,'placement','2019','','Pandan Indah',0,''),(110,22,'MYQWEC1C10001','MYQWEC1C10001-000003','2019-03-25 07:06:30','',0.00,0.00,170.00,'tuition','2019','1','Pandan Indah',0,''),(111,22,'MYQWEC1C10001','MYQWEC1C10001-000003','2019-03-25 07:06:30','',0.00,0.00,50.00,'registration','2019','','Pandan Indah',0,''),(112,22,'MYQWEC1C10001','MYQWEC1C10001-000003','2019-03-25 07:06:30','',0.00,0.00,200.00,'deposit','2019','','Pandan Indah',0,''),(113,22,'MYQWEC1C10001','MYQWEC1C10001-000003','2019-03-25 07:06:30','',0.00,0.00,100.00,'placement','2019','','Pandan Indah',0,''),(114,24,'MYQWEC1C10001','MYQWEC1C10001-000003','2019-03-25 07:06:30','',0.00,0.00,170.00,'tuition','2019','1','Pandan Indah',0,''),(115,24,'MYQWEC1C10001','MYQWEC1C10001-000003','2019-03-25 07:06:30','',0.00,0.00,50.00,'registration','2019','','Pandan Indah',0,''),(116,24,'MYQWEC1C10001','MYQWEC1C10001-000003','2019-03-25 07:06:30','',0.00,0.00,200.00,'deposit','2019','','Pandan Indah',0,''),(117,24,'MYQWEC1C10001','MYQWEC1C10001-000003','2019-03-25 07:06:30','',0.00,0.00,100.00,'placement','2019','','Pandan Indah',0,''),(118,24,'MYQWEC1C10001','MYQWEC1C10001-000003','2019-03-25 07:06:30','BIC-BEAM-MATH L1-SUP 01',1.00,0.00,0.00,'product','2019','3','Pandan Indah',0,''),(119,24,'MYQWEC1C10001','MYQWEC1C10001-000003','2019-03-25 07:06:30','INT-ENG GL 1-MO 01',1.00,0.00,0.00,'product','2019','3','Pandan Indah',0,''),(120,23,'MYQWEC1C10001','MYQWEC1C10001-000004','2019-03-27 05:50:44','',0.00,0.00,370.00,'tuition','2019','t1','Pandan Indah',0,''),(121,18,'MYQWEC1C10001','MYQWEC1C10001-000005','2019-03-28 06:22:13','INT-ENG GL 2-MOD 12',1.00,0.00,0.00,'product','2019','3','Pandan Indah',0,''),(122,18,'MYQWEC1C10001','MYQWEC1C10001-000005','2019-03-28 06:22:13','INT-ENG GL 2-MOD 11',1.00,0.00,0.00,'product','2019','3','Pandan Indah',0,''),(123,25,'MYQWEC1C10001','MYQWEC1C10001-000006','2019-03-29 05:24:32','',0.00,0.00,370.00,'tuition','2019','t5','Pandan Indah',0,''),(124,25,'MYQWEC1C10001','MYQWEC1C10001-000007','2019-03-29 05:24:37','',0.00,0.00,370.00,'tuition','2019','t5','Pandan Indah',0,''),(125,26,'MYQWEC1C10001','MYQWEC1C10001-000008','2019-04-08 07:53:04','',0.00,0.00,370.00,'tuition','2019','t2','Pandan Indah',0,''),(126,27,'MYQWEC1C10001','MYQWEC1C10001-000009','2019-04-08 07:53:25','',0.00,0.00,370.00,'tuition','2019','t2','Pandan Indah',0,''),(127,28,'MYQWEC1C10001','MYQWEC1C10001-000010','2019-04-08 07:54:41','',0.00,0.00,370.00,'tuition','2019','t2','Pandan Indah',0,'');
/*!40000 ALTER TABLE `collection` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `course`
--

DROP TABLE IF EXISTS `course`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `course` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `course_name` varchar(100) NOT NULL,
  `fees` double(12,2) NOT NULL,
  `registration` double(12,2) NOT NULL DEFAULT '0.00',
  `deposit` double(12,2) NOT NULL DEFAULT '0.00',
  `placement` double(12,2) NOT NULL DEFAULT '0.00',
  `deleted` int(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=132 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `course`
--

LOCK TABLES `course` WRITE;
/*!40000 ALTER TABLE `course` DISABLE KEYS */;
INSERT INTO `course` VALUES (1,'BIEPL2M12 - BIEP Internation English Level 2 Module 12',170.00,50.00,200.00,100.00,0),(2,'BIEPL2M11 - BIEP Internation English Level 2 Module 11',170.00,50.00,200.00,100.00,0),(3,'BIEPL2M13 - BIEP Internation English Level 2 Module 13',170.00,50.00,200.00,100.00,0),(4,'BIEPL2M14 - BIEP Internation English Level 2 Module 14',170.00,50.00,200.00,100.00,0),(5,'BIEPL2M15 - BIEP Internation English Level 2 Module 15',170.00,50.00,200.00,100.00,0),(6,'BIEPL2M16 - BIEP Internation English Level 2 Module 16',170.00,50.00,200.00,100.00,0),(7,'BIEPL2M17 - BIEP Internation English Level 2 Module 17',170.00,50.00,200.00,100.00,0),(8,'BIEPL2M18 - BIEP Internation English Level 2 Module 18',170.00,50.00,200.00,100.00,0),(9,'BIEPL2M19 - BIEP Internation English Level 2 Module 19',170.00,50.00,200.00,100.00,0),(10,'BIEPL2M20 - BIEP Internation English Level 2 Module 20',170.00,50.00,200.00,100.00,0),(11,'BIEPL3M21 - BIEP Internation English Level 3 Module 21',170.00,50.00,200.00,100.00,0),(12,'BIEPL3M22 - BIEP Internation English Level 3 Module 22',170.00,50.00,200.00,100.00,0),(13,'BIEPL3M23 - BIEP Internation English Level 3 Module 23',170.00,50.00,200.00,100.00,0),(14,'BIEPL3M24 - BIEP Internation English Level 3 Module 24',170.00,50.00,200.00,100.00,0),(15,'BIEPL3M25 - BIEP Internation English Level 3 Module 25',170.00,50.00,200.00,100.00,0),(16,'BIEPL3M26 - BIEP Internation English Level 3 Module 26',170.00,50.00,200.00,100.00,0),(17,'BIEPL3M27 - BIEP Internation English Level 3 Module 27',170.00,50.00,200.00,100.00,0),(18,'BIEPL3M28 - BIEP Internation English Level 3 Module 28',170.00,50.00,200.00,100.00,0),(19,'BIEPL3M29 - BIEP Internation English Level 3 Module 29',170.00,50.00,200.00,100.00,0),(20,'BIEPL3M30 - BIEP Internation English Level 3 Module 30',170.00,50.00,200.00,100.00,0),(21,'BIEPL4M31 - BIEP Internation English Level 4 Module 31',170.00,50.00,200.00,100.00,0),(22,'BIEPL4M32 - BIEP Internation English Level 4 Module 32',170.00,50.00,200.00,100.00,0),(23,'BIEPL4M33 - BIEP Internation English Level 4 Module 33',170.00,50.00,200.00,100.00,0),(24,'BIEPL4M34 - BIEP Internation English Level 4 Module 34',170.00,50.00,200.00,100.00,0),(25,'BIEPL4M35 - BIEP Internation English Level 4 Module 35',170.00,50.00,200.00,100.00,0),(26,'BIEPL4M36 - BIEP Internation English Level 4 Module 36',170.00,50.00,200.00,100.00,0),(27,'BIEPL4M37 - BIEP Internation English Level 4 Module 37',170.00,50.00,200.00,100.00,0),(28,'BIEPL4M38 - BIEP Internation English Level 4 Module 38',170.00,50.00,200.00,100.00,0),(29,'BIEPL4M39 - BIEP Internation English Level 4 Module 39',170.00,50.00,200.00,100.00,0),(30,'BIEPL4M40 - BIEP Internation English Level 4 Module 40',170.00,50.00,200.00,100.00,0),(31,'BIMPL1M1 - BIMP IQ Math Level 1 Module 1',170.00,50.00,200.00,100.00,0),(32,'BIMPL1M2 - BIMP IQ Math Level 1 Module 2',170.00,50.00,200.00,100.00,0),(33,'BIMPL1M3 - BIMP IQ Math Level 1 Module 3',170.00,50.00,200.00,100.00,0),(34,'BIMPL1M4 - BIMP IQ Math Level 1 Module 4',170.00,50.00,200.00,100.00,0),(35,'BIMPL1M5 - BIMP IQ Math Level 1 Module 5',170.00,50.00,200.00,100.00,0),(36,'BIMPL1M6 - BIMP IQ Math Level 1 Module 6',170.00,50.00,200.00,100.00,0),(37,'BIMPL1M7 - BIMP IQ Math Level 1 Module 7',170.00,50.00,200.00,100.00,0),(38,'BIMPL1M8 - BIMP IQ Math Level 1 Module 8',170.00,50.00,200.00,100.00,0),(39,'BIMPL1M9 - BIMP IQ Math Level 1 Module 9',170.00,50.00,200.00,100.00,0),(40,'BIMPL1M10 - BIMP IQ Math Level 1 Module 10',170.00,50.00,200.00,100.00,0),(41,'BIMPL2M11 - BIMP IQ Math Level 2 Module 11',170.00,50.00,200.00,100.00,0),(42,'BIMPL2M12 - BIMP IQ Math Level 2 Module 12',170.00,50.00,200.00,100.00,0),(43,'BIMPL2M13 - BIMP IQ Math Level 2 Module 13',170.00,50.00,200.00,100.00,0),(44,'BIMPL2M14 - BIMP IQ Math Level 2 Module 14',170.00,50.00,200.00,100.00,0),(45,'BIMPL2M15 - BIMP IQ Math Level 2 Module 15',170.00,50.00,200.00,100.00,0),(46,'BIMPL2M16 - BIMP IQ Math Level 2 Module 16',170.00,50.00,200.00,100.00,0),(47,'BIMPL2M17 - BIMP IQ Math Level 2 Module 17',170.00,50.00,200.00,100.00,0),(48,'BIMPL2M18 - BIMP IQ Math Level 2 Module 18',170.00,50.00,200.00,100.00,0),(49,'BIMPL2M19 - BIMP IQ Math Level 2 Module 19',170.00,50.00,200.00,100.00,0),(50,'BIMPL2M20 - BIMP IQ Math Level 2 Module 20',170.00,50.00,200.00,100.00,0),(51,'BIMPL3M21 - BIMP IQ Math Level 3 Module 21',170.00,50.00,200.00,100.00,0),(52,'BIMPL3M22 - BIMP IQ Math Level 3 Module 22',170.00,50.00,200.00,100.00,0),(53,'BIMPL3M23 - BIMP IQ Math Level 3 Module 23',170.00,50.00,200.00,100.00,0),(54,'BIMPL3M24 - BIMP IQ Math Level 3 Module 24',170.00,50.00,200.00,100.00,0),(55,'BIMPL3M25 - BIMP IQ Math Level 3 Module 25',170.00,50.00,200.00,100.00,0),(56,'BIMPL3M26 - BIMP IQ Math Level 3 Module 26',170.00,50.00,200.00,100.00,0),(57,'BIMPL3M27 - BIMP IQ Math Level 3 Module 27',170.00,50.00,200.00,100.00,0),(58,'BIMPL3M28 - BIMP IQ Math Level 3 Module 28',170.00,50.00,200.00,100.00,0),(59,'BIMPL3M29 - BIMP IQ Math Level 3 Module 29',170.00,50.00,200.00,100.00,0),(60,'BIMPL3M30 - BIMP IQ Math Level 3 Module 30',170.00,50.00,200.00,100.00,0),(61,'BIMPL4M31 - BIMP IQ Math Level 4 Module 31',170.00,50.00,200.00,100.00,0),(62,'BIMPL4M32 - BIMP IQ Math Level 4 Module 32',170.00,50.00,200.00,100.00,0),(63,'BIMPL4M33 - BIMP IQ Math Level 4 Module 33',170.00,50.00,200.00,100.00,0),(64,'BIMPL4M34 - BIMP IQ Math Level 4 Module 34',170.00,50.00,200.00,100.00,0),(65,'BIMPL4M35 - BIMP IQ Math Level 4 Module 35',170.00,50.00,200.00,100.00,0),(66,'BIMPL4M36 - BIMP IQ Math Level 4 Module 36',170.00,50.00,200.00,100.00,0),(67,'BIMPL4M37 - BIMP IQ Math Level 4 Module 37',170.00,50.00,200.00,100.00,0),(68,'BIMPL4M38 - BIMP IQ Math Level 4 Module 38',170.00,50.00,200.00,100.00,0),(69,'BIMPL4M39 - BIMP IQ Math Level 4 Module 39',170.00,50.00,200.00,100.00,0),(70,'BIMPL4M40 - BIMP IQ Math Level 4 Module 40',170.00,50.00,200.00,100.00,0),(71,'BIMPL5M41 - BIMP IQ Math Level 5 Module 41',170.00,50.00,200.00,100.00,0),(72,'BIMPL5M42 - BIMP IQ Math Level 5 Module 42',170.00,50.00,200.00,100.00,0),(73,'BIMPL5M43 - BIMP IQ Math Level 5 Module 43',170.00,50.00,200.00,100.00,0),(74,'BIMPL5M44 - BIMP IQ Math Level 5 Module 44',170.00,50.00,200.00,100.00,0),(75,'BIMPL5M45 - BIMP IQ Math Level 5 Module 45',170.00,50.00,200.00,100.00,0),(76,'BIMPL5M46 - BIMP IQ Math Level 5 Module 46',170.00,50.00,200.00,100.00,0),(77,'BIMPL5M47 - BIMP IQ Math Level 5 Module 47',170.00,50.00,200.00,100.00,0),(78,'BIMPL5M48 - BIMP IQ Math Level 5 Module 48',170.00,50.00,200.00,100.00,0),(79,'BIMPL5M49 - BIMP IQ Math Level 5 Module 49',170.00,50.00,200.00,100.00,0),(80,'BIMPL5M50 - BIMP IQ Math Level 5 Module 50',170.00,50.00,200.00,100.00,0),(81,'IEL1M1 - IE International English Level 1 Module 1',370.00,50.00,200.00,100.00,0),(82,'IEL1M2 - IE International English Level 1 Module 2',370.00,50.00,200.00,100.00,0),(83,'IEL1M3 - IE International English Level 1 Module 3',370.00,50.00,200.00,100.00,0),(84,'IEL1M4 - IE International English Level 1 Module 4',370.00,50.00,200.00,100.00,0),(85,'IEL1M5 - IE International English Level 1 Module 5',370.00,50.00,200.00,100.00,0),(86,'IEL1M6 - IE International English Level 1 Module 6',370.00,50.00,200.00,100.00,0),(87,'IEL1M7 - IE International English Level 1 Module 7',370.00,50.00,200.00,100.00,0),(88,'IEL1M8 - IE International English Level 1 Module 8',370.00,50.00,200.00,100.00,0),(89,'IEL1M9 - IE International English Level 1 Module 9',370.00,50.00,200.00,100.00,0),(90,'IEL1M10 - IE International English Level 1 Module 10',370.00,50.00,200.00,100.00,0),(91,'IEL2M11 - IE International English Level 2 Module 11',370.00,50.00,200.00,100.00,0),(92,'IEL2M12 - IE International English Level 2 Module 12',370.00,50.00,200.00,100.00,0),(93,'IEL2M13 - IE International English Level 2 Module 13',370.00,50.00,200.00,100.00,0),(94,'IEL2M14 - IE International English Level 2 Module 14',370.00,50.00,200.00,100.00,0),(95,'IEL2M15 - IE International English Level 2 Module 15',370.00,50.00,200.00,100.00,0),(96,'IEL2M16 - IE International English Level 2 Module 16',370.00,50.00,200.00,100.00,0),(97,'IEL2M17 - IE International English Level 2 Module 17',370.00,50.00,200.00,100.00,0),(98,'IEL2M18 - IE International English Level 2 Module 18',370.00,50.00,200.00,100.00,0),(99,'IEL2M19 - IE International English Level 2 Module 19',370.00,50.00,200.00,100.00,0),(100,'IEL2M20 - IE International English Level 2 Module 20',370.00,50.00,200.00,100.00,0),(101,'IEL3M21 - IE International English Level 3 Module 21',370.00,50.00,200.00,100.00,0),(102,'IEL3M22 - IE International English Level 3 Module 22',370.00,50.00,200.00,100.00,0),(103,'IEL3M23 - IE International English Level 3 Module 23',370.00,50.00,200.00,100.00,0),(104,'IEL3M24 - IE International English Level 3 Module 24',370.00,50.00,200.00,100.00,0),(105,'IEL3M25 - IE International English Level 3 Module 25',370.00,50.00,200.00,100.00,0),(106,'IEL3M26 - IE International English Level 3 Module 26',370.00,50.00,200.00,100.00,0),(107,'IEL3M27 - IE International English Level 3 Module 27',370.00,50.00,200.00,100.00,0),(108,'IEL3M28 - IE International English Level 3 Module 28',370.00,50.00,200.00,100.00,0),(109,'IEL3M29 - IE International English Level 3 Module 29',370.00,50.00,200.00,100.00,0),(110,'IEL3M30 - IE International English Level 3 Module 30',370.00,50.00,200.00,100.00,0),(111,'IEL4M31 - IE International English Level 4 Module 31',370.00,50.00,200.00,100.00,0),(112,'IEL4M32 - IE International English Level 4 Module 32',370.00,50.00,200.00,100.00,0),(113,'IEL4M33 - IE International English Level 4 Module 33',370.00,50.00,200.00,100.00,0),(114,'IEL4M34 - IE International English Level 4 Module 34',370.00,50.00,200.00,100.00,0),(115,'IEL4M35 - IE International English Level 4 Module 35',370.00,50.00,200.00,100.00,0),(116,'IEL4M36 - IE International English Level 4 Module 36',370.00,50.00,200.00,100.00,0),(117,'IEL4M37 - IE International English Level 4 Module 37',370.00,50.00,200.00,100.00,0),(118,'IEL4M38 - IE International English Level 4 Module 38',370.00,50.00,200.00,100.00,0),(119,'IEL4M39 - IE International English Level 4 Module 39',370.00,50.00,200.00,100.00,0),(120,'IEL4M40 - IE International English Level 4 Module 40',370.00,50.00,200.00,100.00,0),(121,'IEL5M41 - IE International English Level 5 Module 41',370.00,50.00,200.00,100.00,0),(122,'IEL5M42 - IE International English Level 5 Module 42',370.00,50.00,200.00,100.00,0),(123,'IEL5M43 - IE International English Level 5 Module 43',370.00,50.00,200.00,100.00,0),(124,'IEL5M44 - IE International English Level 5 Module 44',370.00,50.00,200.00,100.00,0),(125,'IEL5M45 - IE International English Level 5 Module 45',370.00,50.00,200.00,100.00,0),(126,'IEL5M46 - IE International English Level 5 Module 46',370.00,50.00,200.00,100.00,0),(127,'IEL5M47 - IE International English Level 5 Module 47',370.00,50.00,200.00,100.00,0),(128,'IEL5M48 - IE International English Level 5 Module 48',370.00,50.00,200.00,100.00,0),(129,'IEL5M49 - IE International English Level 5 Module 49',370.00,50.00,200.00,100.00,0),(130,'IEL5M50 - IE International English Level 5 Module 50',370.00,50.00,200.00,100.00,0),(131,'TTL2M11 - Test subject',200.00,100.00,400.00,200.00,0);
/*!40000 ALTER TABLE `course` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `defective`
--

DROP TABLE IF EXISTS `defective`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `defective` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `order_no` varchar(100) NOT NULL,
  `centre_code` varchar(100) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `product_code` varchar(100) NOT NULL,
  `qty` double(12,2) NOT NULL DEFAULT '0.00',
  `unit_price` double(12,2) NOT NULL DEFAULT '0.00',
  `total` double(12,2) NOT NULL DEFAULT '0.00',
  `ordered_by` varchar(100) NOT NULL,
  `ordered_on` datetime NOT NULL,
  `acknowledged_by` varchar(100) NOT NULL,
  `acknowledged_on` datetime NOT NULL,
  `logistic_approved_by` varchar(100) NOT NULL,
  `logistic_approved_on` datetime NOT NULL,
  `finance_approved_by` varchar(100) NOT NULL,
  `finance_approved_on` datetime NOT NULL,
  `finance_payment_paid_by` varchar(100) NOT NULL,
  `finance_payment_paid_on` datetime NOT NULL,
  `payment_document` varchar(100) NOT NULL,
  `packed_by` varchar(100) NOT NULL,
  `packed_on` datetime NOT NULL,
  `tracking_no` varchar(100) NOT NULL,
  `delivered_to_logistic_by` varchar(100) NOT NULL,
  `delivered_to_logistic_on` datetime NOT NULL,
  `name` varchar(100) NOT NULL,
  `ic_no` varchar(100) NOT NULL,
  `signature` text NOT NULL,
  `courier` varchar(100) NOT NULL,
  `cancelled_by` varchar(100) NOT NULL,
  `cancelled_on` datetime NOT NULL,
  `cancel_reason` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_no` (`order_no`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `defective`
--

LOCK TABLES `defective` WRITE;
/*!40000 ALTER TABLE `defective` DISABLE KEYS */;
INSERT INTO `defective` VALUES (3,'7656028240','MYQWEC1C10001','','BIC-BEAM-ENG L3-MOD 21',1.00,0.00,0.00,'super','2019-03-31 16:27:27','super','2019-03-31 11:24:55','super','2019-03-31 11:25:01','super','2019-03-31 11:25:11','super','2019-03-31 11:25:55','xpbp7JfJ.pdf','super','2019-03-31 11:25:06','999999','super','2019-03-31 11:25:35','ST','kljs998080998','{\"lines\":[[[95.2,83.73],[94.2,87.73],[94.2,89.73],[94.2,96.73],[96.2,115.73],[105.2,141.73],[113.2,153.73],[123.2,161.73],[137.2,166.73],[157.2,168.73],[188.2,166.73],[231.2,149.73],[275.2,125.73],[296.2,110.73],[304.2,108.73],[301.2,109.73],[301.2,111.73],[299.2,117.73],[299.2,121.73],[299.2,127.73],[299.2,132.73],[299.2,136.73],[301.2,137.73],[302.2,138.73],[303.2,138.73]]]}','Pos Laju','','0000-00-00 00:00:00','');
/*!40000 ALTER TABLE `defective` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dropout`
--

DROP TABLE IF EXISTS `dropout`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dropout` (
  `id` int(100) unsigned NOT NULL AUTO_INCREMENT,
  `centre_code` varchar(50) NOT NULL,
  `dropout_date` date NOT NULL,
  `student_code` varchar(50) NOT NULL,
  `reason` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dropout`
--

LOCK TABLES `dropout` WRITE;
/*!40000 ALTER TABLE `dropout` DISABLE KEYS */;
INSERT INTO `dropout` VALUES (4,'MYQWEC1C10001','2019-03-26','MYQWEC1C10001-0005','Family Relocation'),(5,'MYQWEC1C10001','2019-03-27','MYQWEC1C10001-0008','Family Relocation');
/*!40000 ALTER TABLE `dropout` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kiv`
--

DROP TABLE IF EXISTS `kiv`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `kiv` (
  `id` int(100) unsigned NOT NULL AUTO_INCREMENT,
  `product_code` varchar(100) NOT NULL,
  `qty` double NOT NULL,
  `unit_price` double NOT NULL,
  `total` double NOT NULL,
  `status` varchar(100) NOT NULL,
  `remarks` text NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kiv`
--

LOCK TABLES `kiv` WRITE;
/*!40000 ALTER TABLE `kiv` DISABLE KEYS */;
INSERT INTO `kiv` VALUES (5,'BIC-BEAM-ENG L3-MOD 21',11,0,0,'Pending','lksfjlksdj','0000-00-00 00:00:00');
/*!40000 ALTER TABLE `kiv` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `master`
--

DROP TABLE IF EXISTS `master`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `master` (
  `id` int(100) unsigned NOT NULL AUTO_INCREMENT,
  `master_code` varchar(100) NOT NULL,
  `mastertype` varchar(100) NOT NULL,
  `company_name` varchar(100) NOT NULL,
  `company_no` varchar(100) NOT NULL,
  `country` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `year_of_commencement` date NOT NULL,
  `franchise_fee` double(12,2) NOT NULL,
  `expiry_date` date NOT NULL,
  `year_of_renewal` int(11) NOT NULL DEFAULT '0',
  `sign_with` varchar(100) NOT NULL,
  `add1` varchar(100) NOT NULL,
  `add2` varchar(100) NOT NULL,
  `add3` varchar(100) NOT NULL,
  `add4` varchar(100) NOT NULL,
  `tel1` varchar(100) NOT NULL,
  `tel2` varchar(100) NOT NULL,
  `fax` varchar(100) NOT NULL,
  `number_of_master_franchisee` int(11) NOT NULL DEFAULT '0',
  `upline` varchar(100) NOT NULL,
  `franchisor_company_name` varchar(100) NOT NULL,
  `franchisor_company_no` varchar(100) NOT NULL,
  `franchisor_registered_address1` varchar(100) NOT NULL,
  `franchisor_registered_address2` varchar(100) NOT NULL,
  `franchisor_registered_address3` varchar(100) NOT NULL,
  `franchisor_registered_address4` varchar(100) NOT NULL,
  `master_franchisee_name_id` int(100) NOT NULL DEFAULT '0',
  `master_franchisee_company_id` int(100) NOT NULL DEFAULT '0',
  `remarks` text NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `master_code` (`master_code`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `master`
--

LOCK TABLES `master` WRITE;
/*!40000 ALTER TABLE `master` DISABLE KEYS */;
INSERT INTO `master` VALUES (22,'MYQWEC10001','Country','','1700815077','Malaysia','','1992-03-01',0.00,'2092-03-01',100,'Corporate HQ','Q-dees Worldwide Edusystems','No. 6-4, Level 4, Jalan SS6/6 Kelana Jaya ,','47301 Petaling Jaya, Selangor, ','Malaysia.','1700815077','1700815077','170081207',1,'','Q-dees Worlwide Edusystems Sdn. Bhd','347038-T','No. 6-4, Level 4, Jalan SS6/6 Kelana Jaya ,','47301 Petaling Jaya, Selangor, ','Malaysia.','',36,50,'','2019-04-08 08:54:13');
/*!40000 ALTER TABLE `master` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `master_agreement_file`
--

DROP TABLE IF EXISTS `master_agreement_file`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `master_agreement_file` (
  `id` int(100) unsigned NOT NULL AUTO_INCREMENT,
  `master_code` varchar(50) NOT NULL,
  `attachment` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `master_agreement_file`
--

LOCK TABLES `master_agreement_file` WRITE;
/*!40000 ALTER TABLE `master_agreement_file` DISABLE KEYS */;
INSERT INTO `master_agreement_file` VALUES (3,'MYQWEC10001','MYQWEC10001-JMybB1tM.pdf'),(2,'MYQWEM10001','MYQWEM10001-Yy1CAJwU.pdf');
/*!40000 ALTER TABLE `master_agreement_file` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `master_franchise_date`
--

DROP TABLE IF EXISTS `master_franchise_date`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `master_franchise_date` (
  `id` int(100) unsigned NOT NULL AUTO_INCREMENT,
  `master_code` varchar(100) NOT NULL,
  `date` date NOT NULL,
  `description` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `master_franchise_date`
--

LOCK TABLES `master_franchise_date` WRITE;
/*!40000 ALTER TABLE `master_franchise_date` DISABLE KEYS */;
INSERT INTO `master_franchise_date` VALUES (29,'MYQWEC10001','2019-04-08','remarks'),(3,'Negeri Sembilan','2019-02-26','no'),(6,'#QWE0001','2019-02-27','bnmncc'),(24,'MYQWEC10003','2019-02-27','fdsfsdfsdfsd'),(20,'MYQWEC10002','2019-02-28','asdfasdfasdf'),(27,'MYQWEM10002','2019-02-27','');
/*!40000 ALTER TABLE `master_franchise_date` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `master_franchisee_company`
--

DROP TABLE IF EXISTS `master_franchisee_company`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `master_franchisee_company` (
  `id` int(100) unsigned NOT NULL AUTO_INCREMENT,
  `master_code` varchar(100) NOT NULL,
  `franchisee_company_name` varchar(100) NOT NULL,
  `franchisee_company_no` varchar(100) NOT NULL,
  `franchisee_registered_address1` varchar(200) NOT NULL,
  `franchisee_registered_address2` varchar(200) NOT NULL,
  `franchisee_registered_address3` varchar(200) NOT NULL,
  `franchisee_registered_address4` varchar(200) NOT NULL,
  `franchisee_company_contact_no` varchar(100) NOT NULL,
  `franchisee_company_email` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `master_franchisee_company`
--

LOCK TABLES `master_franchisee_company` WRITE;
/*!40000 ALTER TABLE `master_franchisee_company` DISABLE KEYS */;
INSERT INTO `master_franchisee_company` VALUES (46,'#QWE0001','vvb','gh','hh','hjk','fdc','bjju','vbjgg','vbnj'),(29,'MYQWEC10002','Webjkj','sfasdfa','sdfasdfasd','asdfasdf','asdfasdf','asdf','asdfasd','fasdfasdfa'),(40,'MYQWEM10002','123','111','123','','','','',''),(28,'MYQWEM10001','hgjhg','jhghjg','jhgjhg','kjhkjhk','hjhhgg','bgkjhkjhjk','jhhghjghj','jhgjhghj'),(41,'MYQWEC10003','Q-dees Worldwide Edusystems Sdn Bhd','A-3T454','No. 6-4, Level 4,','Jalan SS6/6 Kelana Jaya, ','47301 Petaling Jaya, ','Selangor,  Malaysia','1700-81-5077','enquiry@q-dees.com'),(50,'MYQWEC10001','Q-dees Worldwide Edusystems sdn Bhd','347038-T','No. 6-4, Level 4, Jalan SS6/6 Kelana Jaya, ','47301 Petaling Jaya, Selangor,','Malaysia.','','1700815077','enquiry@q-dees.com');
/*!40000 ALTER TABLE `master_franchisee_company` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `master_franchisee_name`
--

DROP TABLE IF EXISTS `master_franchisee_name`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `master_franchisee_name` (
  `id` int(100) unsigned NOT NULL AUTO_INCREMENT,
  `master_code` varchar(100) NOT NULL,
  `franchisee_name` varchar(100) NOT NULL,
  `franchisee_passport` varchar(100) NOT NULL,
  `franchisee_residential_address1` varchar(200) NOT NULL,
  `franchisee_residential_address2` varchar(200) NOT NULL,
  `franchisee_residential_address3` varchar(200) NOT NULL,
  `franchisee_residential_address4` varchar(200) NOT NULL,
  `franchisee_contact_no` varchar(100) NOT NULL,
  `franchisee_email` varchar(100) NOT NULL,
  `franchisee_ic_no` varchar(100) NOT NULL,
  `franchisee_id_no` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `master_franchisee_name`
--

LOCK TABLES `master_franchisee_name` WRITE;
/*!40000 ALTER TABLE `master_franchisee_name` DISABLE KEYS */;
INSERT INTO `master_franchisee_name` VALUES (3,'Negeri Sembilan','ggg','32132','ggfg','gfgfg','ggfg','fgfgfdg','32434','gfdgfgfg','',''),(27,'MYQWEC10003','sdfasd','fasdfadsfa','asdfasdf','asdfasdf','fghgfhdfhgdf','gfjhjfg','hfghfghjf','ghjfghjfghjfg','',''),(26,'MYQWEC10003','asdfasdfasdfasdfsd','hbmh','fgfdhgfjhgjhkjhdhfdsgretytrytrurtyutyurt','jhjhkj','gfg','nvghjgq','hhghjg','gffgfh','',''),(23,'MYQWEC10002','Ooi','asdfasdf','asdfasdfasdf','adsfasdfa','sdfasdf','asdfasdf','asdfasdf','asdfasdfasdf','',''),(30,'MYQWEM10002','123','','','','','','','','',''),(38,'MYQWEC10001','Corporate HQ','','Q-dees Worldwide Edusystems','No. 6-4, Level 4, Jalan SS6/6 Kelana Jaya, ','47301 Petaling Jaya, Selangor, ','Malaysia.','0176215443','enquiry@q-dees.com','840823-01-3097',''),(37,'#QWE0001','fds','sdfg','sdfg','sfdg','dsfg','sdfg','sdfg','sdfg','sdfg','sdfg');
/*!40000 ALTER TABLE `master_franchisee_name` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `master_state`
--

DROP TABLE IF EXISTS `master_state`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `master_state` (
  `id` int(100) unsigned NOT NULL AUTO_INCREMENT,
  `master_code` varchar(100) NOT NULL,
  `country` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=654 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `master_state`
--

LOCK TABLES `master_state` WRITE;
/*!40000 ALTER TABLE `master_state` DISABLE KEYS */;
INSERT INTO `master_state` VALUES (653,'MYQWEC10001','Malaysia','Wilayah Persekutuan Putrajaya'),(652,'MYQWEC10001','Malaysia','Wilayah Persekutuan Labuan'),(650,'MYQWEC10001','Malaysia','Terengganu'),(651,'MYQWEC10001','Malaysia','Wilayah Persekutuan Kuala Lumpur'),(649,'MYQWEC10001','Malaysia','Selangor'),(648,'MYQWEC10001','Malaysia','Sarawak'),(647,'MYQWEC10001','Malaysia','Sabah'),(646,'MYQWEC10001','Malaysia','Pulau Pinang'),(645,'MYQWEC10001','Malaysia','Perlis'),(644,'MYQWEC10001','Malaysia','Perak'),(208,'MYQWEC10002','Malaysia','Wilayah Persekutuan Putrajaya'),(207,'MYQWEC10002','Malaysia','Wilayah Persekutuan Labuan'),(205,'MYQWEC10002','Malaysia','Terengganu'),(206,'MYQWEC10002','Malaysia','Wilayah Persekutuan Kuala Lumpur'),(204,'MYQWEC10002','Malaysia','Selangor'),(203,'MYQWEC10002','Malaysia','Sarawak'),(202,'MYQWEC10002','Malaysia','Sabah'),(201,'MYQWEC10002','Malaysia','Pulau Pinang'),(200,'MYQWEC10002','Malaysia','Perlis'),(199,'MYQWEC10002','Malaysia','Perak'),(198,'MYQWEC10002','Malaysia','Pahang'),(197,'MYQWEC10002','Malaysia','Negeri Sembilan'),(196,'MYQWEC10002','Malaysia','Melaka'),(195,'MYQWEC10002','Malaysia','Kelantan'),(194,'MYQWEC10002','Malaysia','Kedah'),(559,'MYQWEC10003','Malaysia','Wilayah Persekutuan Putrajaya'),(556,'MYQWEC10003','Malaysia','Terengganu'),(557,'MYQWEC10003','Malaysia','Wilayah Persekutuan Kuala Lumpur'),(558,'MYQWEC10003','Malaysia','Wilayah Persekutuan Labuan'),(555,'MYQWEC10003','Malaysia','Selangor'),(554,'MYQWEC10003','Malaysia','Sarawak'),(553,'MYQWEC10003','Malaysia','Sabah'),(552,'MYQWEC10003','Malaysia','Pulau Pinang'),(551,'MYQWEC10003','Malaysia','Perlis'),(550,'MYQWEC10003','Malaysia','Perak'),(193,'MYQWEC10002','Malaysia','Johor'),(549,'MYQWEC10003','Malaysia','Pahang'),(548,'MYQWEC10003','Malaysia','Negeri Sembilan'),(547,'MYQWEC10003','Malaysia','Melaka'),(546,'MYQWEC10003','Malaysia','Kelantan'),(545,'MYQWEC10003','Malaysia','Kedah'),(209,'MYQWEC10004','Malaysia','Johor'),(210,'MYQWEC10004','Malaysia','Kedah'),(211,'MYQWEC10004','Malaysia','Kelantan'),(212,'MYQWEC10004','Malaysia','Melaka'),(213,'MYQWEC10004','Malaysia','Negeri Sembilan'),(214,'MYQWEC10004','Malaysia','Pahang'),(215,'MYQWEC10004','Malaysia','Perak'),(216,'MYQWEC10004','Malaysia','Perlis'),(217,'MYQWEC10004','Malaysia','Pulau Pinang'),(218,'MYQWEC10004','Malaysia','Sabah'),(219,'MYQWEC10004','Malaysia','Sarawak'),(220,'MYQWEC10004','Malaysia','Selangor'),(221,'MYQWEC10004','Malaysia','Terengganu'),(222,'MYQWEC10004','Malaysia','Wilayah Persekutuan Kuala Lumpur'),(223,'MYQWEC10004','Malaysia','Wilayah Persekutuan Labuan'),(224,'MYQWEC10004','Malaysia','Wilayah Persekutuan Putrajaya'),(252,'MYQWEM10001','Malaysia','Selangor'),(251,'MYQWEM10001','Malaysia','Sarawak'),(250,'MYQWEM10001','Malaysia','Sabah'),(249,'MYQWEM10001','Malaysia','Pulau Pinang'),(248,'MYQWEM10001','Malaysia','Perlis'),(247,'MYQWEM10001','Malaysia','Perak'),(246,'MYQWEM10001','Malaysia','Pahang'),(245,'MYQWEM10001','Malaysia','Negeri Sembilan'),(244,'MYQWEM10001','Malaysia','Melaka'),(243,'MYQWEM10001','Malaysia','Kelantan'),(242,'MYQWEM10001','Malaysia','Kedah'),(241,'MYQWEM10001','Malaysia','Johor'),(253,'MYQWEM10001','Malaysia','Terengganu'),(254,'MYQWEM10001','Malaysia','Wilayah Persekutuan Kuala Lumpur'),(255,'MYQWEM10001','Malaysia','Wilayah Persekutuan Labuan'),(256,'MYQWEM10001','Malaysia','Wilayah Persekutuan Putrajaya'),(527,'MYQWEM10002','Malaysia','Wilayah Persekutuan Putrajaya'),(524,'MYQWEM10002','Malaysia','Terengganu'),(525,'MYQWEM10002','Malaysia','Wilayah Persekutuan Kuala Lumpur'),(526,'MYQWEM10002','Malaysia','Wilayah Persekutuan Labuan'),(523,'MYQWEM10002','Malaysia','Selangor'),(522,'MYQWEM10002','Malaysia','Sarawak'),(521,'MYQWEM10002','Malaysia','Sabah'),(520,'MYQWEM10002','Malaysia','Pulau Pinang'),(519,'MYQWEM10002','Malaysia','Perlis'),(518,'MYQWEM10002','Malaysia','Perak'),(517,'MYQWEM10002','Malaysia','Pahang'),(516,'MYQWEM10002','Malaysia','Negeri Sembilan'),(515,'MYQWEM10002','Malaysia','Melaka'),(514,'MYQWEM10002','Malaysia','Kelantan'),(513,'MYQWEM10002','Malaysia','Kedah'),(544,'MYQWEC10003','Malaysia','Johor'),(512,'MYQWEM10002','Malaysia','Johor'),(560,'MMQWEM10001','Myanmar','Ayeyarwaddy'),(561,'MMQWEM10001','Myanmar','Bago'),(562,'MMQWEM10001','Myanmar','Chin'),(563,'MMQWEM10001','Myanmar','Kachin'),(564,'MMQWEM10001','Myanmar','Kayah'),(565,'MMQWEM10001','Myanmar','Kayin'),(566,'MMQWEM10001','Myanmar','Magwe'),(567,'MMQWEM10001','Myanmar','Mandalay'),(568,'MMQWEM10001','Myanmar','Mon'),(569,'MMQWEM10001','Myanmar','Rakhine'),(570,'MMQWEM10001','Myanmar','Sagaing'),(571,'MMQWEM10001','Myanmar','Shan'),(572,'MMQWEM10001','Myanmar','Thaninthayi'),(573,'MMQWEM10001','Myanmar','Yangon'),(643,'MYQWEC10001','Malaysia','Pahang'),(642,'MYQWEC10001','Malaysia','Negeri Sembilan'),(641,'MYQWEC10001','Malaysia','Melaka'),(640,'MYQWEC10001','Malaysia','Kelantan'),(639,'MYQWEC10001','Malaysia','Kedah'),(638,'MYQWEC10001','Malaysia','Johor');
/*!40000 ALTER TABLE `master_state` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order`
--

DROP TABLE IF EXISTS `order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `order_no` varchar(100) NOT NULL,
  `centre_code` varchar(100) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `product_code` varchar(100) NOT NULL,
  `qty` double(12,2) NOT NULL DEFAULT '0.00',
  `unit_price` double(12,2) NOT NULL DEFAULT '0.00',
  `total` double(12,2) NOT NULL DEFAULT '0.00',
  `ordered_by` varchar(100) NOT NULL,
  `ordered_on` datetime NOT NULL,
  `acknowledged_by` varchar(100) NOT NULL,
  `acknowledged_on` datetime NOT NULL,
  `logistic_approved_by` varchar(100) NOT NULL,
  `logistic_approved_on` datetime NOT NULL,
  `finance_approved_by` varchar(100) NOT NULL,
  `finance_approved_on` datetime NOT NULL,
  `finance_payment_paid_by` varchar(100) NOT NULL,
  `finance_payment_paid_on` datetime NOT NULL,
  `payment_document` varchar(100) NOT NULL,
  `packed_by` varchar(100) NOT NULL,
  `packed_on` datetime NOT NULL,
  `tracking_no` varchar(100) NOT NULL,
  `delivered_to_logistic_by` varchar(100) NOT NULL,
  `delivered_to_logistic_on` datetime NOT NULL,
  `name` varchar(100) NOT NULL,
  `ic_no` varchar(100) NOT NULL,
  `signature` text NOT NULL,
  `courier` varchar(100) NOT NULL,
  `cancelled_by` varchar(100) NOT NULL,
  `cancelled_on` datetime NOT NULL,
  `cancel_reason` varchar(100) NOT NULL,
  `remarks` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_no` (`order_no`)
) ENGINE=MyISAM AUTO_INCREMENT=58 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order`
--

LOCK TABLES `order` WRITE;
/*!40000 ALTER TABLE `order` DISABLE KEYS */;
INSERT INTO `order` VALUES (1,'1242468076','MYQWEC1C10001','test','INT-ENG.BRIDGING-PROGM',2.00,122.50,245.00,'test','2019-02-25 06:26:49','super','2019-02-26 03:08:59','super','2019-02-26 03:15:02','super','2019-02-26 03:15:13','super','2019-02-27 03:30:31','Mt1o2852.pdf','super','2019-02-26 03:15:09','23','super','2019-02-27 03:30:49','chan xian ze','13','{\"lines\":[[[200.7,32.23],[202.7,30.23],[202.7,31.23],[201.7,35.23],[189.7,59.23],[173.7,89.23],[162.7,125.23],[161.7,146.23],[165.7,157.23],[175.7,160.23],[196.7,156.23],[227.7,136.23],[247.7,118.23],[255.7,105.23],[256.7,102.23],[255.7,107.23],[249.7,120.23],[246.7,127.23],[246.7,130.23],[246.7,132.23],[246.7,133.23]]]}','GDex','','0000-00-00 00:00:00','',''),(2,'1819731372','MYQWEC1C10001','test','INT-ENG.BRIDGING-PROGM',2.00,122.50,245.00,'test','2019-02-25 06:28:34','super','2019-02-25 07:08:20','super','2019-02-25 07:08:28','super','2019-02-25 07:08:36','super','2019-02-27 02:18:22','iaZvPFfx.pdf','super','2019-02-25 07:08:34','123456789','super','2019-02-26 02:56:44','chan','950511105787','{\"lines\":[[[171.53,106.7],[169.53,112.7],[158.53,125.7],[147.53,140.7],[134.53,157.7],[125.53,174.7],[120.53,187.7],[119.53,192.7],[127.53,190.7],[155.53,176.7],[185.53,155.7],[206.53,138.7],[219.53,124.7],[222.53,121.7],[215.53,126.7],[206.53,143.7],[200.53,162.7],[199.53,176.7],[205.53,188.7],[211.53,192.7],[221.53,192.7],[231.53,188.7],[235.53,183.7]]]}','Pos Laju','','0000-00-00 00:00:00','',''),(3,'6702676027','MYQWEC1C10001','test','INT-ENG.BRIDGING-PROGM',4.00,122.50,490.00,'test','2019-02-28 03:35:58','super','2019-02-28 03:36:42','super','2019-02-28 03:36:50','super','2019-02-28 03:36:57','super','2019-02-28 03:37:50','815ux8sl.pdf','super','2019-02-28 03:36:53','12456','super','2019-02-28 03:37:15','test','13456','{\"lines\":[[[115.53,136.7],[120.53,154.7],[133.53,176.7],[145.53,184.7],[173.53,183.7],[192.53,175.7],[222.53,152.7],[239.53,133.7],[256.53,110.7],[265.53,82.7],[269.53,62.7],[269.53,48.7],[269.53,38.7],[267.53,36.7],[265.53,36.7],[260.53,42.7],[253.53,54.7],[242.53,70.7],[229.53,100.7],[223.53,119.7],[219.53,135.7],[219.53,153.7],[219.53,160.7],[219.53,168.7],[219.53,170.7],[221.53,172.7],[221.53,172.7],[225.53,170.7],[226.53,168.7],[228.53,164.7],[230.53,161.7]]]}','Self-Pickup','','0000-00-00 00:00:00','',''),(4,'9099019558','MYQWEC1C10001','test','INT-ENG.BRIDGING-PROGM',1.00,122.50,122.50,'test','2019-02-28 05:27:54','chan','2019-02-28 05:28:10','chan','2019-02-28 05:28:15','super','2019-02-28 05:28:40','super','2019-02-28 05:28:47','HhDxGb2h.pdf','chan','2019-02-28 05:28:17','1234578','chan','2019-02-28 05:29:21','chan','95051105787','{\"lines\":[[[120.53,102.7],[119.53,106.7],[115.53,143.7],[115.53,164.7],[117.53,180.7],[123.53,191.7],[136.53,198.7],[161.53,198.7],[205.53,176.7],[225.53,160.7],[239.53,143.7],[248.53,126.7],[249.53,116.7],[249.53,110.7],[247.53,108.7],[244.53,110.7],[241.53,114.7],[231.53,129.7],[221.53,148.7],[216.53,170.7],[216.53,191.7],[220.53,201.7],[227.53,208.7],[233.53,212.7],[235.53,212.7],[237.53,212.7],[237.53,212.7],[238.53,212.7]]]}','Self-Pickup','','0000-00-00 00:00:00','',''),(5,'3384861775','MYQWEC1C10004','SS17','INT-ENG GL 1-MOD 10',2.00,122.50,245.00,'SS17','2019-02-28 09:42:50','super','2019-02-28 09:49:08','super','2019-02-28 09:49:13','super','2019-02-28 09:49:24','','0000-00-00 00:00:00','','super','2019-02-28 09:49:17','self pickup','super','2019-02-28 09:51:36','chan','950511105787','{\"lines\":[[[110.2,110.7],[117.2,112.7],[119.2,113.7],[121.2,114.7],[123.2,114.7],[123.2,114.7],[127.2,108.7],[128.2,104.7],[131.2,88.7],[131.2,86.7],[131.2,80.7],[131.2,76.7],[132.2,65.7],[134.2,53.7],[136.2,46.7],[137.2,42.7],[139.2,38.7],[140.2,36.7],[143.2,34.7],[146.2,32.7],[148.2,30.7],[151.2,28.7],[151.2,27.7],[152.2,27.7],[153.2,27.7],[153.2,28.7],[153.2,30.7],[149.2,43.7],[135.2,69.7],[121.2,93.7],[115.2,100.7],[109.2,114.7],[104.2,131.7],[101.2,143.7],[99.2,150.7],[96.2,158.7],[95.2,162.7],[95.2,169.7],[95.2,171.7],[95.2,176.7],[95.2,176.7],[94.2,177.7],[93.2,177.7],[93.2,176.7],[94.2,172.7],[101.2,168.7],[105.2,164.7],[118.2,158.7],[123.2,154.7],[132.2,150.7],[141.2,148.7],[146.2,145.7],[149.2,144.7],[151.2,144.7],[151.2,144.7],[153.2,146.7],[153.2,148.7],[153.2,152.7],[153.2,156.7],[152.2,158.7],[152.2,161.7],[152.2,163.7],[151.2,164.7],[151.2,164.7],[153.2,164.7],[156.2,164.7],[160.2,164.7],[165.2,161.7],[170.2,160.7],[175.2,157.7],[181.2,156.7],[186.2,154.7],[186.2,155.7],[186.2,156.7],[186.2,157.7],[186.2,158.7],[187.2,158.7],[188.2,156.7],[189.2,156.7],[190.2,155.7],[193.2,154.7],[197.2,153.7],[207.2,152.7],[218.2,152.7],[234.2,158.7],[277.2,178.7],[315.2,199.7],[319.2,202.7]]]}','Self-Pickup','','0000-00-00 00:00:00','',''),(6,'5239673086','MMQWEM1C10001','test1234','INT-ENG GL 1-MO 01',1.00,122.50,122.50,'test1234','2019-03-01 04:24:17','super','2019-03-01 04:26:34','super','2019-03-01 04:26:41','super','2019-03-01 04:26:50','','0000-00-00 00:00:00','','super','2019-03-01 04:26:46','selfpickup','super','2019-03-01 04:28:06','chan','12345678','{\"lines\":[[[145.21,142.23],[144.21,137.23],[142.21,122.23],[141.21,120.23],[138.21,118.23],[130.21,118.23],[114.21,125.23],[74.21,162.23],[56.21,186.23],[44.21,227.23],[46.21,242.23],[57.21,252.23],[75.21,256.23],[82.21,252.23],[101.21,225.23],[142.21,130.23],[141.21,106.23],[135.21,97.23],[128.21,95.23],[126.21,97.23],[112.21,120.23],[107.21,134.23],[115.21,159.23],[136.21,176.23],[180.21,179.23],[222.21,156.23],[264.21,117.23],[284.21,94.23],[282.21,89.23],[276.21,88.23],[270.21,90.23],[269.21,92.23],[269.21,94.23],[273.21,96.23],[289.21,99.23],[308.21,99.23],[322.21,99.23],[324.21,99.23]]]}','Self-Pickup','','0000-00-00 00:00:00','',''),(7,'5239673086','MMQWEM1C10001','test1234','INT-ENG GL 1-MOD 06',1.00,122.50,122.50,'test1234','2019-03-01 04:24:17','super','2019-03-01 04:26:34','super','2019-03-01 04:26:41','super','2019-03-01 04:26:50','','0000-00-00 00:00:00','','super','2019-03-01 04:26:46','selfpickup','super','2019-03-01 04:28:06','chan','12345678','{\"lines\":[[[145.21,142.23],[144.21,137.23],[142.21,122.23],[141.21,120.23],[138.21,118.23],[130.21,118.23],[114.21,125.23],[74.21,162.23],[56.21,186.23],[44.21,227.23],[46.21,242.23],[57.21,252.23],[75.21,256.23],[82.21,252.23],[101.21,225.23],[142.21,130.23],[141.21,106.23],[135.21,97.23],[128.21,95.23],[126.21,97.23],[112.21,120.23],[107.21,134.23],[115.21,159.23],[136.21,176.23],[180.21,179.23],[222.21,156.23],[264.21,117.23],[284.21,94.23],[282.21,89.23],[276.21,88.23],[270.21,90.23],[269.21,92.23],[269.21,94.23],[273.21,96.23],[289.21,99.23],[308.21,99.23],[322.21,99.23],[324.21,99.23]]]}','Self-Pickup','','0000-00-00 00:00:00','',''),(8,'5239673086','MMQWEM1C10001','test1234','INT-ENG GL 1-MOD 02',1.00,122.50,122.50,'test1234','2019-03-01 04:24:17','super','2019-03-01 04:26:34','super','2019-03-01 04:26:41','super','2019-03-01 04:26:50','','0000-00-00 00:00:00','','super','2019-03-01 04:26:46','selfpickup','super','2019-03-01 04:28:06','chan','12345678','{\"lines\":[[[145.21,142.23],[144.21,137.23],[142.21,122.23],[141.21,120.23],[138.21,118.23],[130.21,118.23],[114.21,125.23],[74.21,162.23],[56.21,186.23],[44.21,227.23],[46.21,242.23],[57.21,252.23],[75.21,256.23],[82.21,252.23],[101.21,225.23],[142.21,130.23],[141.21,106.23],[135.21,97.23],[128.21,95.23],[126.21,97.23],[112.21,120.23],[107.21,134.23],[115.21,159.23],[136.21,176.23],[180.21,179.23],[222.21,156.23],[264.21,117.23],[284.21,94.23],[282.21,89.23],[276.21,88.23],[270.21,90.23],[269.21,92.23],[269.21,94.23],[273.21,96.23],[289.21,99.23],[308.21,99.23],[322.21,99.23],[324.21,99.23]]]}','Self-Pickup','','0000-00-00 00:00:00','',''),(9,'5239673086','MMQWEM1C10001','test1234','MS-IQ MATH L4-MOD 37',1.00,0.00,0.00,'test1234','2019-03-01 04:24:17','super','2019-03-01 04:26:34','super','2019-03-01 04:26:41','super','2019-03-01 04:26:50','','0000-00-00 00:00:00','','super','2019-03-01 04:26:46','selfpickup','super','2019-03-01 04:28:06','chan','12345678','{\"lines\":[[[145.21,142.23],[144.21,137.23],[142.21,122.23],[141.21,120.23],[138.21,118.23],[130.21,118.23],[114.21,125.23],[74.21,162.23],[56.21,186.23],[44.21,227.23],[46.21,242.23],[57.21,252.23],[75.21,256.23],[82.21,252.23],[101.21,225.23],[142.21,130.23],[141.21,106.23],[135.21,97.23],[128.21,95.23],[126.21,97.23],[112.21,120.23],[107.21,134.23],[115.21,159.23],[136.21,176.23],[180.21,179.23],[222.21,156.23],[264.21,117.23],[284.21,94.23],[282.21,89.23],[276.21,88.23],[270.21,90.23],[269.21,92.23],[269.21,94.23],[273.21,96.23],[289.21,99.23],[308.21,99.23],[322.21,99.23],[324.21,99.23]]]}','Self-Pickup','','0000-00-00 00:00:00','',''),(10,'1700661781','MYQWEC1C10001','Pandan Indah','INT-ENG GL 2-MOD 13',1.00,122.50,122.50,'Pandan Indah','2019-03-15 08:21:45','super','2019-03-15 08:22:56','super','2019-03-15 08:23:18','super','2019-03-15 08:23:47','super','2019-03-15 08:26:40','8rfM5BDp.jpg','super','2019-03-15 08:23:36','wdrdsa3435','super','2019-03-15 08:24:50','kevin','232332-43-22224','{\"lines\":[[[191.53,57.17],[185.53,61.17],[176.53,73.17],[170.53,78.17],[166.53,82.17],[159.53,91.17],[138.53,111.17],[120.53,128.17],[105.53,144.17],[100.53,154.17],[82.53,177.17],[82.53,178.17],[81.53,181.17],[79.53,186.17],[76.53,194.17],[76.53,198.17],[76.53,202.17],[77.53,214.17],[80.53,221.17],[83.53,229.17],[86.53,234.17],[93.53,240.17],[101.53,245.17],[127.53,258.17],[134.53,258.17],[152.53,262.17],[163.53,262.17],[174.53,262.17],[201.53,256.17],[211.53,254.17],[213.53,252.17],[224.53,246.17],[229.53,242.17],[232.53,236.17],[234.53,228.17],[239.53,215.17],[239.53,206.17],[232.53,169.17],[226.53,154.17],[212.53,143.17],[189.53,125.17],[168.53,113.17],[157.53,106.17],[147.53,102.17],[140.53,101.17],[127.53,100.17],[121.53,100.17],[115.53,102.17],[103.53,112.17],[100.53,116.17],[94.53,129.17],[92.53,137.17],[88.53,148.17],[87.53,153.17],[86.53,159.17],[85.53,159.17],[83.53,166.17],[80.53,172.17],[71.53,191.17],[56.53,218.17],[47.53,229.17],[40.53,236.17],[35.53,239.17],[30.53,240.17],[29.53,240.17],[25.53,228.17],[24.53,218.17],[24.53,194.17],[24.53,186.17],[30.53,163.17],[31.53,158.17],[31.53,157.17],[32.53,154.17],[32.53,153.17],[33.53,153.17],[33.53,150.17],[38.53,141.17],[52.53,115.17],[65.53,94.17],[70.53,86.17],[96.53,53.17],[104.53,46.17],[106.53,46.17],[111.53,41.17],[114.53,38.17],[115.53,37.17],[119.53,35.17],[130.53,32.17],[143.53,29.17],[152.53,28.17],[166.53,27.17],[181.53,26.17],[186.53,26.17],[193.53,28.17],[196.53,32.17],[202.53,38.17],[208.53,58.17],[209.53,71.17],[212.53,103.17],[212.53,109.17],[212.53,135.17],[212.53,148.17],[213.53,164.17],[214.53,171.17],[216.53,183.17],[216.53,186.17],[217.53,188.17],[217.53,190.17],[218.53,190.17],[221.53,195.17],[224.53,197.17],[233.53,203.17],[243.53,208.17],[248.53,210.17],[252.53,210.17],[255.53,210.17],[256.53,210.17],[244.53,212.17],[226.53,218.17],[207.53,225.17],[178.53,236.17],[160.53,240.17],[147.53,242.17],[140.53,241.17],[133.53,237.17],[129.53,233.17],[126.53,227.17],[125.53,224.17],[125.53,221.17],[127.53,218.17],[130.53,213.17],[137.53,206.17],[143.53,203.17],[149.53,199.17],[153.53,197.17],[159.53,194.17],[176.53,187.17],[183.53,185.17],[186.53,187.17],[194.53,191.17],[193.53,190.17]]]}','Pos Laju','','0000-00-00 00:00:00','',''),(11,'1700661781','MYQWEC1C10001','Pandan Indah','INT-ENG GL 2-MOD 12',1.00,122.50,122.50,'Pandan Indah','2019-03-15 08:21:45','super','2019-03-15 08:22:56','super','2019-03-15 08:23:18','super','2019-03-15 08:23:47','super','2019-03-15 08:26:40','8rfM5BDp.jpg','super','2019-03-15 08:23:36','wdrdsa3435','super','2019-03-15 08:24:50','kevin','232332-43-22224','{\"lines\":[[[191.53,57.17],[185.53,61.17],[176.53,73.17],[170.53,78.17],[166.53,82.17],[159.53,91.17],[138.53,111.17],[120.53,128.17],[105.53,144.17],[100.53,154.17],[82.53,177.17],[82.53,178.17],[81.53,181.17],[79.53,186.17],[76.53,194.17],[76.53,198.17],[76.53,202.17],[77.53,214.17],[80.53,221.17],[83.53,229.17],[86.53,234.17],[93.53,240.17],[101.53,245.17],[127.53,258.17],[134.53,258.17],[152.53,262.17],[163.53,262.17],[174.53,262.17],[201.53,256.17],[211.53,254.17],[213.53,252.17],[224.53,246.17],[229.53,242.17],[232.53,236.17],[234.53,228.17],[239.53,215.17],[239.53,206.17],[232.53,169.17],[226.53,154.17],[212.53,143.17],[189.53,125.17],[168.53,113.17],[157.53,106.17],[147.53,102.17],[140.53,101.17],[127.53,100.17],[121.53,100.17],[115.53,102.17],[103.53,112.17],[100.53,116.17],[94.53,129.17],[92.53,137.17],[88.53,148.17],[87.53,153.17],[86.53,159.17],[85.53,159.17],[83.53,166.17],[80.53,172.17],[71.53,191.17],[56.53,218.17],[47.53,229.17],[40.53,236.17],[35.53,239.17],[30.53,240.17],[29.53,240.17],[25.53,228.17],[24.53,218.17],[24.53,194.17],[24.53,186.17],[30.53,163.17],[31.53,158.17],[31.53,157.17],[32.53,154.17],[32.53,153.17],[33.53,153.17],[33.53,150.17],[38.53,141.17],[52.53,115.17],[65.53,94.17],[70.53,86.17],[96.53,53.17],[104.53,46.17],[106.53,46.17],[111.53,41.17],[114.53,38.17],[115.53,37.17],[119.53,35.17],[130.53,32.17],[143.53,29.17],[152.53,28.17],[166.53,27.17],[181.53,26.17],[186.53,26.17],[193.53,28.17],[196.53,32.17],[202.53,38.17],[208.53,58.17],[209.53,71.17],[212.53,103.17],[212.53,109.17],[212.53,135.17],[212.53,148.17],[213.53,164.17],[214.53,171.17],[216.53,183.17],[216.53,186.17],[217.53,188.17],[217.53,190.17],[218.53,190.17],[221.53,195.17],[224.53,197.17],[233.53,203.17],[243.53,208.17],[248.53,210.17],[252.53,210.17],[255.53,210.17],[256.53,210.17],[244.53,212.17],[226.53,218.17],[207.53,225.17],[178.53,236.17],[160.53,240.17],[147.53,242.17],[140.53,241.17],[133.53,237.17],[129.53,233.17],[126.53,227.17],[125.53,224.17],[125.53,221.17],[127.53,218.17],[130.53,213.17],[137.53,206.17],[143.53,203.17],[149.53,199.17],[153.53,197.17],[159.53,194.17],[176.53,187.17],[183.53,185.17],[186.53,187.17],[194.53,191.17],[193.53,190.17]]]}','Pos Laju','','0000-00-00 00:00:00','',''),(12,'3737113453','MYQWEC1C10001','Pandan Indah','BIC-BEAM-ENG L3-MOD 26',1.00,0.00,0.00,'Pandan Indah','2019-03-18 07:46:48','super','2019-03-18 08:04:33','super','2019-03-18 08:04:43','super','2019-03-18 08:06:46','super','2019-03-18 08:06:42','zbYa6xmk.jpg','super','2019-03-18 08:04:53','44646389','super','2019-03-18 08:14:28','halo','123456789012','{\"lines\":[[[28.02,125.2],[29.02,125.2],[31.02,125.2],[33.02,123.2],[38.02,122.2],[41.02,120.2],[48.02,116.2],[57.02,112.2],[80.02,102.2],[88.02,99.2],[103.02,96.2],[110.02,95.2],[113.02,95.2],[115.02,95.2],[117.02,95.2],[118.02,95.2],[118.02,96.2],[118.02,103.2],[118.02,109.2],[116.02,136.2],[103.02,164.2],[97.02,175.2],[89.02,190.2],[81.02,200.2],[78.02,205.2],[77.02,208.2],[72.02,212.2],[65.02,218.2],[62.02,222.2],[55.02,224.2],[49.02,226.2],[46.02,227.2],[43.02,227.2],[41.02,227.2],[41.02,227.2],[41.02,226.2],[41.02,226.2],[44.02,224.2],[57.02,217.2],[76.02,207.2],[83.02,203.2],[89.02,200.2],[93.02,197.2],[94.02,197.2],[97.02,197.2],[97.02,197.2],[98.02,198.2],[98.02,199.2],[98.02,202.2],[90.02,223.2],[84.02,240.2],[81.02,245.2],[78.02,250.2],[77.02,251.2],[77.02,252.2],[86.02,252.2],[119.02,233.2],[150.02,213.2],[157.02,210.2],[164.02,205.2],[156.02,216.2],[147.02,226.2],[142.02,231.2],[142.02,232.2],[145.02,228.2],[152.02,223.2],[166.02,208.2],[172.02,204.2],[175.02,202.2],[176.02,202.2],[176.02,202.2],[181.02,197.2],[202.02,170.2],[213.02,157.2],[224.02,144.2],[227.02,141.2],[228.02,140.2],[229.02,140.2],[230.02,140.2]]]}','Transporter','','0000-00-00 00:00:00','',''),(13,'3737113453','MYQWEC1C10001','Pandan Indah','INT-ENG GL 1-MO 01',1.00,122.50,122.50,'Pandan Indah','2019-03-18 07:46:48','super','2019-03-18 08:04:33','super','2019-03-18 08:04:43','super','2019-03-18 08:06:46','super','2019-03-18 08:06:42','zbYa6xmk.jpg','super','2019-03-18 08:04:53','44646389','super','2019-03-18 08:14:28','halo','123456789012','{\"lines\":[[[28.02,125.2],[29.02,125.2],[31.02,125.2],[33.02,123.2],[38.02,122.2],[41.02,120.2],[48.02,116.2],[57.02,112.2],[80.02,102.2],[88.02,99.2],[103.02,96.2],[110.02,95.2],[113.02,95.2],[115.02,95.2],[117.02,95.2],[118.02,95.2],[118.02,96.2],[118.02,103.2],[118.02,109.2],[116.02,136.2],[103.02,164.2],[97.02,175.2],[89.02,190.2],[81.02,200.2],[78.02,205.2],[77.02,208.2],[72.02,212.2],[65.02,218.2],[62.02,222.2],[55.02,224.2],[49.02,226.2],[46.02,227.2],[43.02,227.2],[41.02,227.2],[41.02,227.2],[41.02,226.2],[41.02,226.2],[44.02,224.2],[57.02,217.2],[76.02,207.2],[83.02,203.2],[89.02,200.2],[93.02,197.2],[94.02,197.2],[97.02,197.2],[97.02,197.2],[98.02,198.2],[98.02,199.2],[98.02,202.2],[90.02,223.2],[84.02,240.2],[81.02,245.2],[78.02,250.2],[77.02,251.2],[77.02,252.2],[86.02,252.2],[119.02,233.2],[150.02,213.2],[157.02,210.2],[164.02,205.2],[156.02,216.2],[147.02,226.2],[142.02,231.2],[142.02,232.2],[145.02,228.2],[152.02,223.2],[166.02,208.2],[172.02,204.2],[175.02,202.2],[176.02,202.2],[176.02,202.2],[181.02,197.2],[202.02,170.2],[213.02,157.2],[224.02,144.2],[227.02,141.2],[228.02,140.2],[229.02,140.2],[230.02,140.2]]]}','Transporter','','0000-00-00 00:00:00','',''),(14,'3737113453','MYQWEC1C10001','Pandan Indah','INT-ENG GL 1-MOD 10',1.00,122.50,122.50,'Pandan Indah','2019-03-18 07:46:48','super','2019-03-18 08:04:33','super','2019-03-18 08:04:43','super','2019-03-18 08:06:46','super','2019-03-18 08:06:42','zbYa6xmk.jpg','super','2019-03-18 08:04:53','44646389','super','2019-03-18 08:14:28','halo','123456789012','{\"lines\":[[[28.02,125.2],[29.02,125.2],[31.02,125.2],[33.02,123.2],[38.02,122.2],[41.02,120.2],[48.02,116.2],[57.02,112.2],[80.02,102.2],[88.02,99.2],[103.02,96.2],[110.02,95.2],[113.02,95.2],[115.02,95.2],[117.02,95.2],[118.02,95.2],[118.02,96.2],[118.02,103.2],[118.02,109.2],[116.02,136.2],[103.02,164.2],[97.02,175.2],[89.02,190.2],[81.02,200.2],[78.02,205.2],[77.02,208.2],[72.02,212.2],[65.02,218.2],[62.02,222.2],[55.02,224.2],[49.02,226.2],[46.02,227.2],[43.02,227.2],[41.02,227.2],[41.02,227.2],[41.02,226.2],[41.02,226.2],[44.02,224.2],[57.02,217.2],[76.02,207.2],[83.02,203.2],[89.02,200.2],[93.02,197.2],[94.02,197.2],[97.02,197.2],[97.02,197.2],[98.02,198.2],[98.02,199.2],[98.02,202.2],[90.02,223.2],[84.02,240.2],[81.02,245.2],[78.02,250.2],[77.02,251.2],[77.02,252.2],[86.02,252.2],[119.02,233.2],[150.02,213.2],[157.02,210.2],[164.02,205.2],[156.02,216.2],[147.02,226.2],[142.02,231.2],[142.02,232.2],[145.02,228.2],[152.02,223.2],[166.02,208.2],[172.02,204.2],[175.02,202.2],[176.02,202.2],[176.02,202.2],[181.02,197.2],[202.02,170.2],[213.02,157.2],[224.02,144.2],[227.02,141.2],[228.02,140.2],[229.02,140.2],[230.02,140.2]]]}','Transporter','','0000-00-00 00:00:00','',''),(15,'1964900893','MYQWEC1C10001','Pandan Indah','INT-ENG GL 2-MOD 17',2.00,122.50,245.00,'Pandan Indah','2019-03-18 08:19:53','super','2019-03-18 08:20:25','super','2019-03-18 08:21:40','super','2019-03-18 08:26:37','super','2019-03-18 08:27:29','XlImvOF9.jpg','super','2019-03-18 08:22:10','','','0000-00-00 00:00:00','','','','Self-Pickup','','0000-00-00 00:00:00','',''),(16,'1964900893','MYQWEC1C10001','Pandan Indah','INT-ENG GL 1-MOD 03',7.00,122.50,857.50,'Pandan Indah','2019-03-18 08:19:53','super','2019-03-18 08:20:25','super','2019-03-18 08:21:40','super','2019-03-18 08:26:37','super','2019-03-18 08:27:29','XlImvOF9.jpg','super','2019-03-18 08:22:10','','','0000-00-00 00:00:00','','','','Self-Pickup','','0000-00-00 00:00:00','',''),(17,'1964900893','MYQWEC1C10001','Pandan Indah','INT-ENG GL 1-MOD 07',8.00,122.50,980.00,'Pandan Indah','2019-03-18 08:19:53','super','2019-03-18 08:20:25','super','2019-03-18 08:21:40','super','2019-03-18 08:26:37','super','2019-03-18 08:27:29','XlImvOF9.jpg','super','2019-03-18 08:22:10','','','0000-00-00 00:00:00','','','','Self-Pickup','','0000-00-00 00:00:00','',''),(18,'0265313223','MYQWEC1C10001','Pandan Indah','INT-ENG GL 2-MOD 12',1.00,122.50,122.50,'Pandan Indah','2019-03-18 08:28:29','super','2019-03-18 08:31:26','super','2019-03-18 08:31:46','super','2019-03-18 08:32:40','super','2019-03-18 08:32:04','AuzGeEXn.jpg','super','2019-03-18 08:32:22','','','0000-00-00 00:00:00','','','','','','0000-00-00 00:00:00','',''),(19,'0265313223','MYQWEC1C10001','Pandan Indah','INT-ENG GL 2-MOD 18',1.00,122.50,122.50,'Pandan Indah','2019-03-18 08:28:29','super','2019-03-18 08:31:26','super','2019-03-18 08:31:46','super','2019-03-18 08:32:40','super','2019-03-18 08:32:04','AuzGeEXn.jpg','super','2019-03-18 08:32:22','','','0000-00-00 00:00:00','','','','','','0000-00-00 00:00:00','',''),(20,'6801037072','MYQWEC1C10001','Pandan Indah','INT-ENG.BRIDGING-PROGM',1.00,122.50,122.50,'Pandan Indah','2019-03-21 03:04:11','super','2019-03-21 03:04:29','super','2019-03-21 03:04:31','super','2019-03-21 03:04:34','','0000-00-00 00:00:00','','super','2019-03-21 03:04:32','teste','super','2019-03-21 03:04:49','test','test','{\"lines\":[[[159.97,163],[155.97,163],[112.97,198],[98.97,223],[98.97,237],[113.97,246],[138.97,246],[171.97,227],[201.97,203],[218.97,174],[224.97,153],[226.97,142],[225.97,139],[224.97,143],[224.97,149],[224.97,152],[226.97,154],[229.97,154],[237.97,154],[254.97,153]]]}','Pos Laju','','0000-00-00 00:00:00','',''),(21,'3310473256','MYQWEC1C10001','test1','BIC-BEAM-ENG L3-MOD 21',5.00,0.00,0.00,'test1','2019-03-21 03:24:27','super','2019-03-21 03:25:05','super','2019-03-21 03:25:09','super','2019-03-21 03:25:15','','0000-00-00 00:00:00','','super','2019-03-21 03:25:12','jkjkj','super','2019-03-21 03:25:32','ST Ooi','lkskdjflk','{\"lines\":[[[223.2,106.23],[221.2,106.23],[224.2,100.23],[228.2,95.23],[237.2,80.23],[248.2,68.23],[257.2,60.23],[272.2,53.23],[281.2,53.23],[291.2,53.23],[296.2,63.23],[298.2,75.23],[298.2,87.23],[298.2,104.23],[298.2,118.23],[295.2,131.23],[293.2,141.23],[290.2,147.23],[291.2,149.23],[294.2,149.23],[296.2,148.23],[305.2,169.23],[309.2,188.23],[309.2,191.23]]]}','Pos Laju','','0000-00-00 00:00:00','',''),(22,'4781981681','MYQWEC1C10001','Pandan Indah','INT-ENG GL 1-MOD 04',1.00,122.50,122.50,'Pandan Indah','2019-03-25 06:45:10','super','2019-03-25 06:47:48','super','2019-03-25 06:49:57','super','2019-03-25 06:50:42','super','2019-03-25 06:52:55','CfEe3dUd.pdf','super','2019-03-25 06:50:31','12345789-K','super','2019-03-25 06:53:57','chan','13456789','{\"lines\":[[[85.53,146.7],[88.53,140.7],[92.53,133.7],[132.53,72.7],[185.53,-8.3],[207.53,-49.3],[215.53,-65.3],[215.53,-65.3],[215.53,-63.3],[205.53,-43.3],[205.53,-41.3],[190.53,3.7],[188.53,35.7],[188.53,42.7],[189.53,46.7],[195.53,46.7],[199.53,45.7],[202.53,41.7],[211.53,32.7],[215.53,28.7],[219.53,26.7],[220.53,26.7],[221.53,38.7],[213.53,56.7],[197.53,100.7],[189.53,128.7],[189.53,140.7],[191.53,147.7],[194.53,148.7],[195.53,148.7],[197.53,147.7],[198.53,145.7],[200.53,140.7],[199.53,143.7],[175.53,168.7],[167.53,186.7],[166.53,190.7],[165.53,192.7],[167.53,177.7],[167.53,161.7],[167.53,156.7],[155.53,131.7],[145.53,129.7],[137.53,132.7],[129.53,143.7],[127.53,148.7],[127.53,150.7],[138.53,150.7],[164.53,140.7],[205.53,126.7],[239.53,115.7],[256.53,107.7],[269.53,91.7],[275.53,57.7],[271.53,48.7],[253.53,46.7],[224.53,65.7],[207.53,90.7],[203.53,98.7],[208.53,124.7],[214.53,126.7],[231.53,126.7],[245.53,120.7],[251.53,117.7],[256.53,110.7],[257.53,108.7],[255.53,106.7],[237.53,108.7],[218.53,118.7],[205.53,130.7],[198.53,142.7],[199.53,150.7],[208.53,153.7],[220.53,150.7],[233.53,149.7],[248.53,146.7],[253.53,145.7],[255.53,145.7],[256.53,145.7],[257.53,149.7],[257.53,156.7],[257.53,158.7],[259.53,166.7],[270.53,168.7],[291.53,168.7],[307.53,162.7]]]}','Self-Pickup','','0000-00-00 00:00:00','',''),(23,'4781981681','MYQWEC1C10001','Pandan Indah','INT-ENG GL 1-MOD 03',1.00,122.50,122.50,'Pandan Indah','2019-03-25 06:45:10','super','2019-03-25 06:47:48','super','2019-03-25 06:49:57','super','2019-03-25 06:50:42','super','2019-03-25 06:52:55','CfEe3dUd.pdf','super','2019-03-25 06:50:31','12345789-K','super','2019-03-25 06:53:57','chan','13456789','{\"lines\":[[[85.53,146.7],[88.53,140.7],[92.53,133.7],[132.53,72.7],[185.53,-8.3],[207.53,-49.3],[215.53,-65.3],[215.53,-65.3],[215.53,-63.3],[205.53,-43.3],[205.53,-41.3],[190.53,3.7],[188.53,35.7],[188.53,42.7],[189.53,46.7],[195.53,46.7],[199.53,45.7],[202.53,41.7],[211.53,32.7],[215.53,28.7],[219.53,26.7],[220.53,26.7],[221.53,38.7],[213.53,56.7],[197.53,100.7],[189.53,128.7],[189.53,140.7],[191.53,147.7],[194.53,148.7],[195.53,148.7],[197.53,147.7],[198.53,145.7],[200.53,140.7],[199.53,143.7],[175.53,168.7],[167.53,186.7],[166.53,190.7],[165.53,192.7],[167.53,177.7],[167.53,161.7],[167.53,156.7],[155.53,131.7],[145.53,129.7],[137.53,132.7],[129.53,143.7],[127.53,148.7],[127.53,150.7],[138.53,150.7],[164.53,140.7],[205.53,126.7],[239.53,115.7],[256.53,107.7],[269.53,91.7],[275.53,57.7],[271.53,48.7],[253.53,46.7],[224.53,65.7],[207.53,90.7],[203.53,98.7],[208.53,124.7],[214.53,126.7],[231.53,126.7],[245.53,120.7],[251.53,117.7],[256.53,110.7],[257.53,108.7],[255.53,106.7],[237.53,108.7],[218.53,118.7],[205.53,130.7],[198.53,142.7],[199.53,150.7],[208.53,153.7],[220.53,150.7],[233.53,149.7],[248.53,146.7],[253.53,145.7],[255.53,145.7],[256.53,145.7],[257.53,149.7],[257.53,156.7],[257.53,158.7],[259.53,166.7],[270.53,168.7],[291.53,168.7],[307.53,162.7]]]}','Self-Pickup','','0000-00-00 00:00:00','',''),(24,'4781981681','MYQWEC1C10001','Pandan Indah','INT-ENG GL 1-MO 01',1.00,122.50,122.50,'Pandan Indah','2019-03-25 06:45:10','super','2019-03-25 06:47:48','super','2019-03-25 06:49:57','super','2019-03-25 06:50:42','super','2019-03-25 06:52:55','CfEe3dUd.pdf','super','2019-03-25 06:50:31','12345789-K','super','2019-03-25 06:53:57','chan','13456789','{\"lines\":[[[85.53,146.7],[88.53,140.7],[92.53,133.7],[132.53,72.7],[185.53,-8.3],[207.53,-49.3],[215.53,-65.3],[215.53,-65.3],[215.53,-63.3],[205.53,-43.3],[205.53,-41.3],[190.53,3.7],[188.53,35.7],[188.53,42.7],[189.53,46.7],[195.53,46.7],[199.53,45.7],[202.53,41.7],[211.53,32.7],[215.53,28.7],[219.53,26.7],[220.53,26.7],[221.53,38.7],[213.53,56.7],[197.53,100.7],[189.53,128.7],[189.53,140.7],[191.53,147.7],[194.53,148.7],[195.53,148.7],[197.53,147.7],[198.53,145.7],[200.53,140.7],[199.53,143.7],[175.53,168.7],[167.53,186.7],[166.53,190.7],[165.53,192.7],[167.53,177.7],[167.53,161.7],[167.53,156.7],[155.53,131.7],[145.53,129.7],[137.53,132.7],[129.53,143.7],[127.53,148.7],[127.53,150.7],[138.53,150.7],[164.53,140.7],[205.53,126.7],[239.53,115.7],[256.53,107.7],[269.53,91.7],[275.53,57.7],[271.53,48.7],[253.53,46.7],[224.53,65.7],[207.53,90.7],[203.53,98.7],[208.53,124.7],[214.53,126.7],[231.53,126.7],[245.53,120.7],[251.53,117.7],[256.53,110.7],[257.53,108.7],[255.53,106.7],[237.53,108.7],[218.53,118.7],[205.53,130.7],[198.53,142.7],[199.53,150.7],[208.53,153.7],[220.53,150.7],[233.53,149.7],[248.53,146.7],[253.53,145.7],[255.53,145.7],[256.53,145.7],[257.53,149.7],[257.53,156.7],[257.53,158.7],[259.53,166.7],[270.53,168.7],[291.53,168.7],[307.53,162.7]]]}','Self-Pickup','','0000-00-00 00:00:00','',''),(25,'4781981681','MYQWEC1C10001','Pandan Indah','INT-ENG GL 1-MOD 02',1.00,122.50,122.50,'Pandan Indah','2019-03-25 06:45:10','super','2019-03-25 06:47:48','super','2019-03-25 06:49:57','super','2019-03-25 06:50:42','super','2019-03-25 06:52:55','CfEe3dUd.pdf','super','2019-03-25 06:50:31','12345789-K','super','2019-03-25 06:53:57','chan','13456789','{\"lines\":[[[85.53,146.7],[88.53,140.7],[92.53,133.7],[132.53,72.7],[185.53,-8.3],[207.53,-49.3],[215.53,-65.3],[215.53,-65.3],[215.53,-63.3],[205.53,-43.3],[205.53,-41.3],[190.53,3.7],[188.53,35.7],[188.53,42.7],[189.53,46.7],[195.53,46.7],[199.53,45.7],[202.53,41.7],[211.53,32.7],[215.53,28.7],[219.53,26.7],[220.53,26.7],[221.53,38.7],[213.53,56.7],[197.53,100.7],[189.53,128.7],[189.53,140.7],[191.53,147.7],[194.53,148.7],[195.53,148.7],[197.53,147.7],[198.53,145.7],[200.53,140.7],[199.53,143.7],[175.53,168.7],[167.53,186.7],[166.53,190.7],[165.53,192.7],[167.53,177.7],[167.53,161.7],[167.53,156.7],[155.53,131.7],[145.53,129.7],[137.53,132.7],[129.53,143.7],[127.53,148.7],[127.53,150.7],[138.53,150.7],[164.53,140.7],[205.53,126.7],[239.53,115.7],[256.53,107.7],[269.53,91.7],[275.53,57.7],[271.53,48.7],[253.53,46.7],[224.53,65.7],[207.53,90.7],[203.53,98.7],[208.53,124.7],[214.53,126.7],[231.53,126.7],[245.53,120.7],[251.53,117.7],[256.53,110.7],[257.53,108.7],[255.53,106.7],[237.53,108.7],[218.53,118.7],[205.53,130.7],[198.53,142.7],[199.53,150.7],[208.53,153.7],[220.53,150.7],[233.53,149.7],[248.53,146.7],[253.53,145.7],[255.53,145.7],[256.53,145.7],[257.53,149.7],[257.53,156.7],[257.53,158.7],[259.53,166.7],[270.53,168.7],[291.53,168.7],[307.53,162.7]]]}','Self-Pickup','','0000-00-00 00:00:00','',''),(26,'4781981681','MYQWEC1C10001','Pandan Indah','INT-ENG GL 1-MOD 05',1.00,122.50,122.50,'Pandan Indah','2019-03-25 06:45:10','super','2019-03-25 06:47:48','super','2019-03-25 06:49:57','super','2019-03-25 06:50:42','super','2019-03-25 06:52:55','CfEe3dUd.pdf','super','2019-03-25 06:50:31','12345789-K','super','2019-03-25 06:53:57','chan','13456789','{\"lines\":[[[85.53,146.7],[88.53,140.7],[92.53,133.7],[132.53,72.7],[185.53,-8.3],[207.53,-49.3],[215.53,-65.3],[215.53,-65.3],[215.53,-63.3],[205.53,-43.3],[205.53,-41.3],[190.53,3.7],[188.53,35.7],[188.53,42.7],[189.53,46.7],[195.53,46.7],[199.53,45.7],[202.53,41.7],[211.53,32.7],[215.53,28.7],[219.53,26.7],[220.53,26.7],[221.53,38.7],[213.53,56.7],[197.53,100.7],[189.53,128.7],[189.53,140.7],[191.53,147.7],[194.53,148.7],[195.53,148.7],[197.53,147.7],[198.53,145.7],[200.53,140.7],[199.53,143.7],[175.53,168.7],[167.53,186.7],[166.53,190.7],[165.53,192.7],[167.53,177.7],[167.53,161.7],[167.53,156.7],[155.53,131.7],[145.53,129.7],[137.53,132.7],[129.53,143.7],[127.53,148.7],[127.53,150.7],[138.53,150.7],[164.53,140.7],[205.53,126.7],[239.53,115.7],[256.53,107.7],[269.53,91.7],[275.53,57.7],[271.53,48.7],[253.53,46.7],[224.53,65.7],[207.53,90.7],[203.53,98.7],[208.53,124.7],[214.53,126.7],[231.53,126.7],[245.53,120.7],[251.53,117.7],[256.53,110.7],[257.53,108.7],[255.53,106.7],[237.53,108.7],[218.53,118.7],[205.53,130.7],[198.53,142.7],[199.53,150.7],[208.53,153.7],[220.53,150.7],[233.53,149.7],[248.53,146.7],[253.53,145.7],[255.53,145.7],[256.53,145.7],[257.53,149.7],[257.53,156.7],[257.53,158.7],[259.53,166.7],[270.53,168.7],[291.53,168.7],[307.53,162.7]]]}','Self-Pickup','','0000-00-00 00:00:00','',''),(27,'4781981681','MYQWEC1C10001','Pandan Indah','INT-ENG GL 1-MOD 06',1.00,122.50,122.50,'Pandan Indah','2019-03-25 06:45:10','super','2019-03-25 06:47:48','super','2019-03-25 06:49:57','super','2019-03-25 06:50:42','super','2019-03-25 06:52:55','CfEe3dUd.pdf','super','2019-03-25 06:50:31','12345789-K','super','2019-03-25 06:53:57','chan','13456789','{\"lines\":[[[85.53,146.7],[88.53,140.7],[92.53,133.7],[132.53,72.7],[185.53,-8.3],[207.53,-49.3],[215.53,-65.3],[215.53,-65.3],[215.53,-63.3],[205.53,-43.3],[205.53,-41.3],[190.53,3.7],[188.53,35.7],[188.53,42.7],[189.53,46.7],[195.53,46.7],[199.53,45.7],[202.53,41.7],[211.53,32.7],[215.53,28.7],[219.53,26.7],[220.53,26.7],[221.53,38.7],[213.53,56.7],[197.53,100.7],[189.53,128.7],[189.53,140.7],[191.53,147.7],[194.53,148.7],[195.53,148.7],[197.53,147.7],[198.53,145.7],[200.53,140.7],[199.53,143.7],[175.53,168.7],[167.53,186.7],[166.53,190.7],[165.53,192.7],[167.53,177.7],[167.53,161.7],[167.53,156.7],[155.53,131.7],[145.53,129.7],[137.53,132.7],[129.53,143.7],[127.53,148.7],[127.53,150.7],[138.53,150.7],[164.53,140.7],[205.53,126.7],[239.53,115.7],[256.53,107.7],[269.53,91.7],[275.53,57.7],[271.53,48.7],[253.53,46.7],[224.53,65.7],[207.53,90.7],[203.53,98.7],[208.53,124.7],[214.53,126.7],[231.53,126.7],[245.53,120.7],[251.53,117.7],[256.53,110.7],[257.53,108.7],[255.53,106.7],[237.53,108.7],[218.53,118.7],[205.53,130.7],[198.53,142.7],[199.53,150.7],[208.53,153.7],[220.53,150.7],[233.53,149.7],[248.53,146.7],[253.53,145.7],[255.53,145.7],[256.53,145.7],[257.53,149.7],[257.53,156.7],[257.53,158.7],[259.53,166.7],[270.53,168.7],[291.53,168.7],[307.53,162.7]]]}','Self-Pickup','','0000-00-00 00:00:00','',''),(28,'4781981681','MYQWEC1C10001','Pandan Indah','INT-ENG GL 1-MOD 07',1.00,122.50,122.50,'Pandan Indah','2019-03-25 06:45:10','super','2019-03-25 06:47:48','super','2019-03-25 06:49:57','super','2019-03-25 06:50:42','super','2019-03-25 06:52:55','CfEe3dUd.pdf','super','2019-03-25 06:50:31','12345789-K','super','2019-03-25 06:53:57','chan','13456789','{\"lines\":[[[85.53,146.7],[88.53,140.7],[92.53,133.7],[132.53,72.7],[185.53,-8.3],[207.53,-49.3],[215.53,-65.3],[215.53,-65.3],[215.53,-63.3],[205.53,-43.3],[205.53,-41.3],[190.53,3.7],[188.53,35.7],[188.53,42.7],[189.53,46.7],[195.53,46.7],[199.53,45.7],[202.53,41.7],[211.53,32.7],[215.53,28.7],[219.53,26.7],[220.53,26.7],[221.53,38.7],[213.53,56.7],[197.53,100.7],[189.53,128.7],[189.53,140.7],[191.53,147.7],[194.53,148.7],[195.53,148.7],[197.53,147.7],[198.53,145.7],[200.53,140.7],[199.53,143.7],[175.53,168.7],[167.53,186.7],[166.53,190.7],[165.53,192.7],[167.53,177.7],[167.53,161.7],[167.53,156.7],[155.53,131.7],[145.53,129.7],[137.53,132.7],[129.53,143.7],[127.53,148.7],[127.53,150.7],[138.53,150.7],[164.53,140.7],[205.53,126.7],[239.53,115.7],[256.53,107.7],[269.53,91.7],[275.53,57.7],[271.53,48.7],[253.53,46.7],[224.53,65.7],[207.53,90.7],[203.53,98.7],[208.53,124.7],[214.53,126.7],[231.53,126.7],[245.53,120.7],[251.53,117.7],[256.53,110.7],[257.53,108.7],[255.53,106.7],[237.53,108.7],[218.53,118.7],[205.53,130.7],[198.53,142.7],[199.53,150.7],[208.53,153.7],[220.53,150.7],[233.53,149.7],[248.53,146.7],[253.53,145.7],[255.53,145.7],[256.53,145.7],[257.53,149.7],[257.53,156.7],[257.53,158.7],[259.53,166.7],[270.53,168.7],[291.53,168.7],[307.53,162.7]]]}','Self-Pickup','','0000-00-00 00:00:00','',''),(29,'4781981681','MYQWEC1C10001','Pandan Indah','INT-ENG GL 1-MOD 08',1.00,122.50,122.50,'Pandan Indah','2019-03-25 06:45:10','super','2019-03-25 06:47:48','super','2019-03-25 06:49:57','super','2019-03-25 06:50:42','super','2019-03-25 06:52:55','CfEe3dUd.pdf','super','2019-03-25 06:50:31','12345789-K','super','2019-03-25 06:53:57','chan','13456789','{\"lines\":[[[85.53,146.7],[88.53,140.7],[92.53,133.7],[132.53,72.7],[185.53,-8.3],[207.53,-49.3],[215.53,-65.3],[215.53,-65.3],[215.53,-63.3],[205.53,-43.3],[205.53,-41.3],[190.53,3.7],[188.53,35.7],[188.53,42.7],[189.53,46.7],[195.53,46.7],[199.53,45.7],[202.53,41.7],[211.53,32.7],[215.53,28.7],[219.53,26.7],[220.53,26.7],[221.53,38.7],[213.53,56.7],[197.53,100.7],[189.53,128.7],[189.53,140.7],[191.53,147.7],[194.53,148.7],[195.53,148.7],[197.53,147.7],[198.53,145.7],[200.53,140.7],[199.53,143.7],[175.53,168.7],[167.53,186.7],[166.53,190.7],[165.53,192.7],[167.53,177.7],[167.53,161.7],[167.53,156.7],[155.53,131.7],[145.53,129.7],[137.53,132.7],[129.53,143.7],[127.53,148.7],[127.53,150.7],[138.53,150.7],[164.53,140.7],[205.53,126.7],[239.53,115.7],[256.53,107.7],[269.53,91.7],[275.53,57.7],[271.53,48.7],[253.53,46.7],[224.53,65.7],[207.53,90.7],[203.53,98.7],[208.53,124.7],[214.53,126.7],[231.53,126.7],[245.53,120.7],[251.53,117.7],[256.53,110.7],[257.53,108.7],[255.53,106.7],[237.53,108.7],[218.53,118.7],[205.53,130.7],[198.53,142.7],[199.53,150.7],[208.53,153.7],[220.53,150.7],[233.53,149.7],[248.53,146.7],[253.53,145.7],[255.53,145.7],[256.53,145.7],[257.53,149.7],[257.53,156.7],[257.53,158.7],[259.53,166.7],[270.53,168.7],[291.53,168.7],[307.53,162.7]]]}','Self-Pickup','','0000-00-00 00:00:00','',''),(30,'4781981681','MYQWEC1C10001','Pandan Indah','INT-ENG GL 1-MOD 09',1.00,122.50,122.50,'Pandan Indah','2019-03-25 06:45:10','super','2019-03-25 06:47:48','super','2019-03-25 06:49:57','super','2019-03-25 06:50:42','super','2019-03-25 06:52:55','CfEe3dUd.pdf','super','2019-03-25 06:50:31','12345789-K','super','2019-03-25 06:53:57','chan','13456789','{\"lines\":[[[85.53,146.7],[88.53,140.7],[92.53,133.7],[132.53,72.7],[185.53,-8.3],[207.53,-49.3],[215.53,-65.3],[215.53,-65.3],[215.53,-63.3],[205.53,-43.3],[205.53,-41.3],[190.53,3.7],[188.53,35.7],[188.53,42.7],[189.53,46.7],[195.53,46.7],[199.53,45.7],[202.53,41.7],[211.53,32.7],[215.53,28.7],[219.53,26.7],[220.53,26.7],[221.53,38.7],[213.53,56.7],[197.53,100.7],[189.53,128.7],[189.53,140.7],[191.53,147.7],[194.53,148.7],[195.53,148.7],[197.53,147.7],[198.53,145.7],[200.53,140.7],[199.53,143.7],[175.53,168.7],[167.53,186.7],[166.53,190.7],[165.53,192.7],[167.53,177.7],[167.53,161.7],[167.53,156.7],[155.53,131.7],[145.53,129.7],[137.53,132.7],[129.53,143.7],[127.53,148.7],[127.53,150.7],[138.53,150.7],[164.53,140.7],[205.53,126.7],[239.53,115.7],[256.53,107.7],[269.53,91.7],[275.53,57.7],[271.53,48.7],[253.53,46.7],[224.53,65.7],[207.53,90.7],[203.53,98.7],[208.53,124.7],[214.53,126.7],[231.53,126.7],[245.53,120.7],[251.53,117.7],[256.53,110.7],[257.53,108.7],[255.53,106.7],[237.53,108.7],[218.53,118.7],[205.53,130.7],[198.53,142.7],[199.53,150.7],[208.53,153.7],[220.53,150.7],[233.53,149.7],[248.53,146.7],[253.53,145.7],[255.53,145.7],[256.53,145.7],[257.53,149.7],[257.53,156.7],[257.53,158.7],[259.53,166.7],[270.53,168.7],[291.53,168.7],[307.53,162.7]]]}','Self-Pickup','','0000-00-00 00:00:00','',''),(31,'4781981681','MYQWEC1C10001','Pandan Indah','INT-ENG GL 1-MOD 10',1.00,122.50,122.50,'Pandan Indah','2019-03-25 06:45:10','super','2019-03-25 06:47:48','super','2019-03-25 06:49:57','super','2019-03-25 06:50:42','super','2019-03-25 06:52:55','CfEe3dUd.pdf','super','2019-03-25 06:50:31','12345789-K','super','2019-03-25 06:53:57','chan','13456789','{\"lines\":[[[85.53,146.7],[88.53,140.7],[92.53,133.7],[132.53,72.7],[185.53,-8.3],[207.53,-49.3],[215.53,-65.3],[215.53,-65.3],[215.53,-63.3],[205.53,-43.3],[205.53,-41.3],[190.53,3.7],[188.53,35.7],[188.53,42.7],[189.53,46.7],[195.53,46.7],[199.53,45.7],[202.53,41.7],[211.53,32.7],[215.53,28.7],[219.53,26.7],[220.53,26.7],[221.53,38.7],[213.53,56.7],[197.53,100.7],[189.53,128.7],[189.53,140.7],[191.53,147.7],[194.53,148.7],[195.53,148.7],[197.53,147.7],[198.53,145.7],[200.53,140.7],[199.53,143.7],[175.53,168.7],[167.53,186.7],[166.53,190.7],[165.53,192.7],[167.53,177.7],[167.53,161.7],[167.53,156.7],[155.53,131.7],[145.53,129.7],[137.53,132.7],[129.53,143.7],[127.53,148.7],[127.53,150.7],[138.53,150.7],[164.53,140.7],[205.53,126.7],[239.53,115.7],[256.53,107.7],[269.53,91.7],[275.53,57.7],[271.53,48.7],[253.53,46.7],[224.53,65.7],[207.53,90.7],[203.53,98.7],[208.53,124.7],[214.53,126.7],[231.53,126.7],[245.53,120.7],[251.53,117.7],[256.53,110.7],[257.53,108.7],[255.53,106.7],[237.53,108.7],[218.53,118.7],[205.53,130.7],[198.53,142.7],[199.53,150.7],[208.53,153.7],[220.53,150.7],[233.53,149.7],[248.53,146.7],[253.53,145.7],[255.53,145.7],[256.53,145.7],[257.53,149.7],[257.53,156.7],[257.53,158.7],[259.53,166.7],[270.53,168.7],[291.53,168.7],[307.53,162.7]]]}','Self-Pickup','','0000-00-00 00:00:00','',''),(32,'4781981681','MYQWEC1C10001','Pandan Indah','INT-ENG GL 2-MOD 11',1.00,122.50,122.50,'Pandan Indah','2019-03-25 06:45:10','super','2019-03-25 06:47:48','super','2019-03-25 06:49:57','super','2019-03-25 06:50:42','super','2019-03-25 06:52:55','CfEe3dUd.pdf','super','2019-03-25 06:50:31','12345789-K','super','2019-03-25 06:53:57','chan','13456789','{\"lines\":[[[85.53,146.7],[88.53,140.7],[92.53,133.7],[132.53,72.7],[185.53,-8.3],[207.53,-49.3],[215.53,-65.3],[215.53,-65.3],[215.53,-63.3],[205.53,-43.3],[205.53,-41.3],[190.53,3.7],[188.53,35.7],[188.53,42.7],[189.53,46.7],[195.53,46.7],[199.53,45.7],[202.53,41.7],[211.53,32.7],[215.53,28.7],[219.53,26.7],[220.53,26.7],[221.53,38.7],[213.53,56.7],[197.53,100.7],[189.53,128.7],[189.53,140.7],[191.53,147.7],[194.53,148.7],[195.53,148.7],[197.53,147.7],[198.53,145.7],[200.53,140.7],[199.53,143.7],[175.53,168.7],[167.53,186.7],[166.53,190.7],[165.53,192.7],[167.53,177.7],[167.53,161.7],[167.53,156.7],[155.53,131.7],[145.53,129.7],[137.53,132.7],[129.53,143.7],[127.53,148.7],[127.53,150.7],[138.53,150.7],[164.53,140.7],[205.53,126.7],[239.53,115.7],[256.53,107.7],[269.53,91.7],[275.53,57.7],[271.53,48.7],[253.53,46.7],[224.53,65.7],[207.53,90.7],[203.53,98.7],[208.53,124.7],[214.53,126.7],[231.53,126.7],[245.53,120.7],[251.53,117.7],[256.53,110.7],[257.53,108.7],[255.53,106.7],[237.53,108.7],[218.53,118.7],[205.53,130.7],[198.53,142.7],[199.53,150.7],[208.53,153.7],[220.53,150.7],[233.53,149.7],[248.53,146.7],[253.53,145.7],[255.53,145.7],[256.53,145.7],[257.53,149.7],[257.53,156.7],[257.53,158.7],[259.53,166.7],[270.53,168.7],[291.53,168.7],[307.53,162.7]]]}','Self-Pickup','','0000-00-00 00:00:00','',''),(33,'0767768577','MYQWEC1C10001','Pandan Indah','INT-ENG.BRIDGING-PROGM',1.00,122.50,122.50,'Pandan Indah','2019-03-25 07:38:49','super','2019-03-25 07:39:15','super','2019-03-25 07:39:28','','0000-00-00 00:00:00','','0000-00-00 00:00:00','','super','2019-03-25 07:39:45','','','0000-00-00 00:00:00','','','','GDex','','0000-00-00 00:00:00','',''),(34,'3946750922','MYQWEC1C10001','Pandan Indah','INT-ENG.BRIDGING-PROGM',1.00,122.50,122.50,'Pandan Indah','2019-03-28 06:32:09','','0000-00-00 00:00:00','','0000-00-00 00:00:00','','0000-00-00 00:00:00','','0000-00-00 00:00:00','','','0000-00-00 00:00:00','','','0000-00-00 00:00:00','','','','GDex','','0000-00-00 00:00:00','',''),(35,'0559032515','MYQWEC1C10001','test1','INT-ENG.BRIDGING-PROGM',1.00,122.50,122.50,'test1','2019-03-28 22:42:46','','0000-00-00 00:00:00','','0000-00-00 00:00:00','','0000-00-00 00:00:00','','0000-00-00 00:00:00','','','0000-00-00 00:00:00','','','0000-00-00 00:00:00','','','','','','0000-00-00 00:00:00','',''),(36,'1197884856','MYQWEC1C10001','test1','INT-ENG GL 1-MO 01',1.00,122.50,122.50,'test1','2019-03-28 22:47:22','','0000-00-00 00:00:00','','0000-00-00 00:00:00','','0000-00-00 00:00:00','','0000-00-00 00:00:00','','','0000-00-00 00:00:00','','','0000-00-00 00:00:00','','','','Pos Laju','','0000-00-00 00:00:00','',''),(37,'1051771932','MYQWEC1C10001','test1','INT-ENG GL 1-MOD 02',1.00,122.50,122.50,'test1','2019-03-28 22:49:01','','0000-00-00 00:00:00','','0000-00-00 00:00:00','','0000-00-00 00:00:00','','0000-00-00 00:00:00','','','0000-00-00 00:00:00','','','0000-00-00 00:00:00','','','','Pos Laju','','0000-00-00 00:00:00','',''),(38,'4433418168','MYQWEC1C10001','test1','INT-ENG GL 1-MOD 04',1.00,122.50,122.50,'test1','2019-03-28 22:49:41','super','2019-03-29 08:59:25','super','2019-03-29 08:59:30','super','2019-03-29 08:59:46','','0000-00-00 00:00:00','','super','2019-03-29 08:59:38','','','0000-00-00 00:00:00','','','','Pos Laju','','0000-00-00 00:00:00','',''),(39,'2668950892','MYQWEC1C10001','Pandan Indah','INT-ENG.BRIDGING-PROGM',1.00,122.50,122.50,'Pandan Indah','2019-03-29 05:49:02','super','2019-03-29 05:49:34','super','2019-03-29 05:49:37','super','2019-03-29 05:49:43','super','2019-03-29 05:50:09','D9IhWfLG.pdf','super','2019-03-29 05:49:40','12567890','super','2019-03-29 05:49:55','chan','12346789','{\"lines\":[[[69.53,134.7],[67.53,153.7],[60.53,177.7],[60.53,194.7],[71.53,207.7],[84.53,207.7],[109.53,190.7],[133.53,163.7],[145.53,136.7],[149.53,117.7],[150.53,110.7],[150.53,110.7],[150.53,110.7],[146.53,125.7],[145.53,144.7],[145.53,162.7],[153.53,168.7],[164.53,168.7],[177.53,160.7],[189.53,141.7],[193.53,127.7],[193.53,123.7],[197.53,130.7],[203.53,140.7],[207.53,148.7],[208.53,148.7]]]}','Pos Laju','','0000-00-00 00:00:00','',''),(40,'3605542851','MYQWEC1C10001','Pandan Indah','INT-ENG GL 2-MOD 17',1.00,122.50,122.50,'Pandan Indah','2019-03-29 09:17:12','super','2019-04-01 08:05:58','','0000-00-00 00:00:00','','0000-00-00 00:00:00','','0000-00-00 00:00:00','','','0000-00-00 00:00:00','','','0000-00-00 00:00:00','','','','Pos Laju','','0000-00-00 00:00:00','',''),(41,'9335081772','MYQWEC1C10001','Pandan Indah','BIC-BEAM-ENG L3-MOD 22',1.00,0.00,0.00,'Pandan Indah','2019-04-01 09:07:53','super','2019-04-01 09:14:47','super','2019-04-01 09:17:14','super','2019-04-01 09:19:44','','0000-00-00 00:00:00','','super','2019-04-01 09:17:51','123122','super','2019-04-01 09:27:25','yeyeye','112112244509','{\"lines\":[[[202.7,115.73],[202.7,117.73]],[[163.7,99.73],[163.7,98.73],[164.7,95.73],[169.7,90.73],[185.7,81.73],[216.7,69.73],[254.7,63.73],[269.7,63.73],[287.7,68.73],[311.7,82.73],[318.7,90.73],[326.7,98.73],[336.7,112.73],[338.7,118.73],[339.7,121.73],[332.7,137.73],[312.7,153.73],[277.7,169.73],[235.7,177.73],[220.7,178.73],[206.7,177.73],[180.7,165.73],[171.7,157.73],[164.7,149.73],[157.7,141.73],[157.7,140.73],[157.7,139.73],[157.7,138.73],[157.7,136.73],[159.7,136.73],[161.7,136.73],[162.7,136.73],[163.7,137.73],[168.7,140.73],[178.7,148.73],[199.7,158.73],[232.7,167.73],[265.7,171.73],[271.7,171.73],[273.7,171.73],[277.7,166.73],[279.7,157.73],[279.7,149.73],[266.7,134.73],[245.7,127.73],[222.7,124.73],[201.7,124.73],[195.7,128.73],[189.7,133.73],[180.7,147.73],[179.7,157.73],[179.7,165.73],[188.7,185.73],[196.7,193.73],[206.7,198.73],[222.7,204.73],[226.7,204.73],[228.7,204.73],[232.7,199.73],[233.7,192.73],[233.7,184.73],[216.7,165.73],[199.7,160.73],[181.7,158.73],[164.7,158.73],[159.7,159.73],[155.7,164.73],[150.7,174.73],[149.7,180.73],[148.7,187.73],[149.7,201.73],[152.7,206.73],[154.7,207.73],[155.7,208.73],[155.7,205.73],[154.7,193.73],[132.7,161.73],[122.7,153.73],[116.7,150.73],[109.7,149.73],[108.7,149.73],[105.7,153.73],[101.7,167.73],[100.7,174.73],[100.7,178.73],[100.7,181.73],[99.7,181.73],[97.7,181.73],[73.7,162.73],[62.7,149.73],[53.7,139.73],[39.7,126.73],[33.7,120.73],[29.7,111.73],[25.7,93.73],[25.7,86.73],[28.7,80.73],[42.7,67.73],[52.7,59.73],[67.7,51.73],[102.7,41.73],[113.7,41.73],[123.7,41.73],[143.7,51.73],[152.7,58.73],[159.7,65.73],[169.7,85.73],[171.7,93.73],[171.7,99.73],[168.7,108.73],[158.7,115.73],[142.7,121.73],[105.7,120.73],[86.7,108.73],[72.7,99.73],[57.7,86.73],[53.7,83.73],[53.7,82.73],[52.7,81.73],[51.7,81.73],[51.7,82.73],[51.7,86.73],[51.7,91.73],[51.7,96.73],[53.7,114.73],[56.7,123.73],[56.7,127.73],[62.7,137.73],[67.7,141.73],[72.7,146.73],[80.7,153.73],[82.7,155.73],[83.7,156.73],[84.7,157.73],[85.7,159.73],[85.7,160.73],[86.7,164.73],[86.7,170.73],[86.7,175.73],[90.7,181.73],[87.7,183.73],[73.7,188.73],[30.7,207.73],[-4.3,224.73],[-14.3,228.73],[-19.3,231.73],[-22.3,234.73],[-23.3,236.73],[-23.3,240.73],[-17.3,251.73],[-9.3,259.73],[0.7,267.73],[18.7,278.73],[24.7,280.73],[30.7,282.73],[41.7,281.73],[47.7,278.73],[51.7,274.73],[60.7,264.73],[63.7,260.73],[68.7,255.73],[75.7,248.73],[77.7,247.73],[79.7,247.73],[84.7,242.73],[88.7,237.73],[92.7,232.73],[102.7,223.73],[108.7,220.73],[112.7,217.73],[117.7,215.73],[119.7,215.73],[120.7,215.73],[123.7,215.73],[124.7,217.73],[125.7,222.73],[114.7,253.73],[111.7,262.73],[110.7,264.73],[109.7,266.73],[109.7,267.73],[110.7,267.73],[115.7,265.73],[124.7,261.73],[135.7,253.73],[145.7,246.73],[155.7,235.73],[157.7,232.73],[159.7,230.73],[163.7,228.73],[165.7,227.73],[167.7,226.73],[176.7,226.73],[181.7,226.73],[184.7,226.73],[190.7,226.73],[191.7,226.73],[192.7,226.73],[194.7,226.73],[195.7,226.73],[195.7,225.73],[191.7,224.73],[176.7,223.73],[159.7,225.73],[158.7,227.73],[158.7,229.73],[165.7,237.73],[173.7,241.73],[181.7,243.73],[222.7,239.73],[232.7,235.73],[237.7,233.73],[240.7,230.73],[240.7,229.73],[241.7,228.73],[241.7,227.73],[241.7,226.73],[240.7,225.73],[238.7,223.73],[238.7,222.73],[238.7,220.73],[238.7,217.73],[243.7,211.73],[248.7,206.73],[253.7,199.73],[255.7,199.73],[262.7,199.73],[289.7,199.73],[337.7,204.73],[358.7,206.73],[360.7,206.73],[361.7,206.73],[360.7,206.73],[349.7,205.73],[317.7,210.73],[294.7,219.73],[286.7,225.73],[283.7,227.73],[280.7,235.73],[280.7,241.73],[282.7,247.73],[290.7,259.73],[292.7,261.73],[292.7,262.73],[293.7,263.73],[294.7,260.73],[297.7,256.73],[306.7,251.73],[316.7,246.73],[325.7,244.73],[326.7,244.73],[327.7,243.73],[330.7,235.73],[332.7,214.73],[332.7,172.73],[316.7,66.73],[311.7,56.73],[304.7,49.73],[280.7,41.73],[266.7,38.73],[256.7,35.73],[244.7,28.73],[240.7,24.73],[236.7,19.73],[232.7,11.73],[231.7,10.73],[231.7,8.73],[230.7,8.73],[230.7,7.73],[233.7,7.73],[247.7,12.73],[269.7,22.73],[280.7,28.73],[291.7,34.73],[292.7,35.73],[293.7,35.73],[293.7,36.73],[288.7,37.73],[267.7,47.73],[230.7,62.73],[212.7,69.73],[196.7,77.73]]]}','Skynet','','0000-00-00 00:00:00','',''),(42,'9335081772','MYQWEC1C10001','Pandan Indah','INT-ENG.BRIDGING-PROGM',1.00,122.50,122.50,'Pandan Indah','2019-04-01 09:07:53','super','2019-04-01 09:14:47','super','2019-04-01 09:17:14','super','2019-04-01 09:19:44','','0000-00-00 00:00:00','','super','2019-04-01 09:17:51','123122','super','2019-04-01 09:27:25','yeyeye','112112244509','{\"lines\":[[[202.7,115.73],[202.7,117.73]],[[163.7,99.73],[163.7,98.73],[164.7,95.73],[169.7,90.73],[185.7,81.73],[216.7,69.73],[254.7,63.73],[269.7,63.73],[287.7,68.73],[311.7,82.73],[318.7,90.73],[326.7,98.73],[336.7,112.73],[338.7,118.73],[339.7,121.73],[332.7,137.73],[312.7,153.73],[277.7,169.73],[235.7,177.73],[220.7,178.73],[206.7,177.73],[180.7,165.73],[171.7,157.73],[164.7,149.73],[157.7,141.73],[157.7,140.73],[157.7,139.73],[157.7,138.73],[157.7,136.73],[159.7,136.73],[161.7,136.73],[162.7,136.73],[163.7,137.73],[168.7,140.73],[178.7,148.73],[199.7,158.73],[232.7,167.73],[265.7,171.73],[271.7,171.73],[273.7,171.73],[277.7,166.73],[279.7,157.73],[279.7,149.73],[266.7,134.73],[245.7,127.73],[222.7,124.73],[201.7,124.73],[195.7,128.73],[189.7,133.73],[180.7,147.73],[179.7,157.73],[179.7,165.73],[188.7,185.73],[196.7,193.73],[206.7,198.73],[222.7,204.73],[226.7,204.73],[228.7,204.73],[232.7,199.73],[233.7,192.73],[233.7,184.73],[216.7,165.73],[199.7,160.73],[181.7,158.73],[164.7,158.73],[159.7,159.73],[155.7,164.73],[150.7,174.73],[149.7,180.73],[148.7,187.73],[149.7,201.73],[152.7,206.73],[154.7,207.73],[155.7,208.73],[155.7,205.73],[154.7,193.73],[132.7,161.73],[122.7,153.73],[116.7,150.73],[109.7,149.73],[108.7,149.73],[105.7,153.73],[101.7,167.73],[100.7,174.73],[100.7,178.73],[100.7,181.73],[99.7,181.73],[97.7,181.73],[73.7,162.73],[62.7,149.73],[53.7,139.73],[39.7,126.73],[33.7,120.73],[29.7,111.73],[25.7,93.73],[25.7,86.73],[28.7,80.73],[42.7,67.73],[52.7,59.73],[67.7,51.73],[102.7,41.73],[113.7,41.73],[123.7,41.73],[143.7,51.73],[152.7,58.73],[159.7,65.73],[169.7,85.73],[171.7,93.73],[171.7,99.73],[168.7,108.73],[158.7,115.73],[142.7,121.73],[105.7,120.73],[86.7,108.73],[72.7,99.73],[57.7,86.73],[53.7,83.73],[53.7,82.73],[52.7,81.73],[51.7,81.73],[51.7,82.73],[51.7,86.73],[51.7,91.73],[51.7,96.73],[53.7,114.73],[56.7,123.73],[56.7,127.73],[62.7,137.73],[67.7,141.73],[72.7,146.73],[80.7,153.73],[82.7,155.73],[83.7,156.73],[84.7,157.73],[85.7,159.73],[85.7,160.73],[86.7,164.73],[86.7,170.73],[86.7,175.73],[90.7,181.73],[87.7,183.73],[73.7,188.73],[30.7,207.73],[-4.3,224.73],[-14.3,228.73],[-19.3,231.73],[-22.3,234.73],[-23.3,236.73],[-23.3,240.73],[-17.3,251.73],[-9.3,259.73],[0.7,267.73],[18.7,278.73],[24.7,280.73],[30.7,282.73],[41.7,281.73],[47.7,278.73],[51.7,274.73],[60.7,264.73],[63.7,260.73],[68.7,255.73],[75.7,248.73],[77.7,247.73],[79.7,247.73],[84.7,242.73],[88.7,237.73],[92.7,232.73],[102.7,223.73],[108.7,220.73],[112.7,217.73],[117.7,215.73],[119.7,215.73],[120.7,215.73],[123.7,215.73],[124.7,217.73],[125.7,222.73],[114.7,253.73],[111.7,262.73],[110.7,264.73],[109.7,266.73],[109.7,267.73],[110.7,267.73],[115.7,265.73],[124.7,261.73],[135.7,253.73],[145.7,246.73],[155.7,235.73],[157.7,232.73],[159.7,230.73],[163.7,228.73],[165.7,227.73],[167.7,226.73],[176.7,226.73],[181.7,226.73],[184.7,226.73],[190.7,226.73],[191.7,226.73],[192.7,226.73],[194.7,226.73],[195.7,226.73],[195.7,225.73],[191.7,224.73],[176.7,223.73],[159.7,225.73],[158.7,227.73],[158.7,229.73],[165.7,237.73],[173.7,241.73],[181.7,243.73],[222.7,239.73],[232.7,235.73],[237.7,233.73],[240.7,230.73],[240.7,229.73],[241.7,228.73],[241.7,227.73],[241.7,226.73],[240.7,225.73],[238.7,223.73],[238.7,222.73],[238.7,220.73],[238.7,217.73],[243.7,211.73],[248.7,206.73],[253.7,199.73],[255.7,199.73],[262.7,199.73],[289.7,199.73],[337.7,204.73],[358.7,206.73],[360.7,206.73],[361.7,206.73],[360.7,206.73],[349.7,205.73],[317.7,210.73],[294.7,219.73],[286.7,225.73],[283.7,227.73],[280.7,235.73],[280.7,241.73],[282.7,247.73],[290.7,259.73],[292.7,261.73],[292.7,262.73],[293.7,263.73],[294.7,260.73],[297.7,256.73],[306.7,251.73],[316.7,246.73],[325.7,244.73],[326.7,244.73],[327.7,243.73],[330.7,235.73],[332.7,214.73],[332.7,172.73],[316.7,66.73],[311.7,56.73],[304.7,49.73],[280.7,41.73],[266.7,38.73],[256.7,35.73],[244.7,28.73],[240.7,24.73],[236.7,19.73],[232.7,11.73],[231.7,10.73],[231.7,8.73],[230.7,8.73],[230.7,7.73],[233.7,7.73],[247.7,12.73],[269.7,22.73],[280.7,28.73],[291.7,34.73],[292.7,35.73],[293.7,35.73],[293.7,36.73],[288.7,37.73],[267.7,47.73],[230.7,62.73],[212.7,69.73],[196.7,77.73]]]}','Skynet','','0000-00-00 00:00:00','',''),(43,'9335081772','MYQWEC1C10001','Pandan Indah','INT-ENG GL 1-MOD 02',3.00,122.50,367.50,'Pandan Indah','2019-04-01 09:07:53','super','2019-04-01 09:14:47','super','2019-04-01 09:17:14','super','2019-04-01 09:19:44','','0000-00-00 00:00:00','','super','2019-04-01 09:17:51','123122','super','2019-04-01 09:27:25','yeyeye','112112244509','{\"lines\":[[[202.7,115.73],[202.7,117.73]],[[163.7,99.73],[163.7,98.73],[164.7,95.73],[169.7,90.73],[185.7,81.73],[216.7,69.73],[254.7,63.73],[269.7,63.73],[287.7,68.73],[311.7,82.73],[318.7,90.73],[326.7,98.73],[336.7,112.73],[338.7,118.73],[339.7,121.73],[332.7,137.73],[312.7,153.73],[277.7,169.73],[235.7,177.73],[220.7,178.73],[206.7,177.73],[180.7,165.73],[171.7,157.73],[164.7,149.73],[157.7,141.73],[157.7,140.73],[157.7,139.73],[157.7,138.73],[157.7,136.73],[159.7,136.73],[161.7,136.73],[162.7,136.73],[163.7,137.73],[168.7,140.73],[178.7,148.73],[199.7,158.73],[232.7,167.73],[265.7,171.73],[271.7,171.73],[273.7,171.73],[277.7,166.73],[279.7,157.73],[279.7,149.73],[266.7,134.73],[245.7,127.73],[222.7,124.73],[201.7,124.73],[195.7,128.73],[189.7,133.73],[180.7,147.73],[179.7,157.73],[179.7,165.73],[188.7,185.73],[196.7,193.73],[206.7,198.73],[222.7,204.73],[226.7,204.73],[228.7,204.73],[232.7,199.73],[233.7,192.73],[233.7,184.73],[216.7,165.73],[199.7,160.73],[181.7,158.73],[164.7,158.73],[159.7,159.73],[155.7,164.73],[150.7,174.73],[149.7,180.73],[148.7,187.73],[149.7,201.73],[152.7,206.73],[154.7,207.73],[155.7,208.73],[155.7,205.73],[154.7,193.73],[132.7,161.73],[122.7,153.73],[116.7,150.73],[109.7,149.73],[108.7,149.73],[105.7,153.73],[101.7,167.73],[100.7,174.73],[100.7,178.73],[100.7,181.73],[99.7,181.73],[97.7,181.73],[73.7,162.73],[62.7,149.73],[53.7,139.73],[39.7,126.73],[33.7,120.73],[29.7,111.73],[25.7,93.73],[25.7,86.73],[28.7,80.73],[42.7,67.73],[52.7,59.73],[67.7,51.73],[102.7,41.73],[113.7,41.73],[123.7,41.73],[143.7,51.73],[152.7,58.73],[159.7,65.73],[169.7,85.73],[171.7,93.73],[171.7,99.73],[168.7,108.73],[158.7,115.73],[142.7,121.73],[105.7,120.73],[86.7,108.73],[72.7,99.73],[57.7,86.73],[53.7,83.73],[53.7,82.73],[52.7,81.73],[51.7,81.73],[51.7,82.73],[51.7,86.73],[51.7,91.73],[51.7,96.73],[53.7,114.73],[56.7,123.73],[56.7,127.73],[62.7,137.73],[67.7,141.73],[72.7,146.73],[80.7,153.73],[82.7,155.73],[83.7,156.73],[84.7,157.73],[85.7,159.73],[85.7,160.73],[86.7,164.73],[86.7,170.73],[86.7,175.73],[90.7,181.73],[87.7,183.73],[73.7,188.73],[30.7,207.73],[-4.3,224.73],[-14.3,228.73],[-19.3,231.73],[-22.3,234.73],[-23.3,236.73],[-23.3,240.73],[-17.3,251.73],[-9.3,259.73],[0.7,267.73],[18.7,278.73],[24.7,280.73],[30.7,282.73],[41.7,281.73],[47.7,278.73],[51.7,274.73],[60.7,264.73],[63.7,260.73],[68.7,255.73],[75.7,248.73],[77.7,247.73],[79.7,247.73],[84.7,242.73],[88.7,237.73],[92.7,232.73],[102.7,223.73],[108.7,220.73],[112.7,217.73],[117.7,215.73],[119.7,215.73],[120.7,215.73],[123.7,215.73],[124.7,217.73],[125.7,222.73],[114.7,253.73],[111.7,262.73],[110.7,264.73],[109.7,266.73],[109.7,267.73],[110.7,267.73],[115.7,265.73],[124.7,261.73],[135.7,253.73],[145.7,246.73],[155.7,235.73],[157.7,232.73],[159.7,230.73],[163.7,228.73],[165.7,227.73],[167.7,226.73],[176.7,226.73],[181.7,226.73],[184.7,226.73],[190.7,226.73],[191.7,226.73],[192.7,226.73],[194.7,226.73],[195.7,226.73],[195.7,225.73],[191.7,224.73],[176.7,223.73],[159.7,225.73],[158.7,227.73],[158.7,229.73],[165.7,237.73],[173.7,241.73],[181.7,243.73],[222.7,239.73],[232.7,235.73],[237.7,233.73],[240.7,230.73],[240.7,229.73],[241.7,228.73],[241.7,227.73],[241.7,226.73],[240.7,225.73],[238.7,223.73],[238.7,222.73],[238.7,220.73],[238.7,217.73],[243.7,211.73],[248.7,206.73],[253.7,199.73],[255.7,199.73],[262.7,199.73],[289.7,199.73],[337.7,204.73],[358.7,206.73],[360.7,206.73],[361.7,206.73],[360.7,206.73],[349.7,205.73],[317.7,210.73],[294.7,219.73],[286.7,225.73],[283.7,227.73],[280.7,235.73],[280.7,241.73],[282.7,247.73],[290.7,259.73],[292.7,261.73],[292.7,262.73],[293.7,263.73],[294.7,260.73],[297.7,256.73],[306.7,251.73],[316.7,246.73],[325.7,244.73],[326.7,244.73],[327.7,243.73],[330.7,235.73],[332.7,214.73],[332.7,172.73],[316.7,66.73],[311.7,56.73],[304.7,49.73],[280.7,41.73],[266.7,38.73],[256.7,35.73],[244.7,28.73],[240.7,24.73],[236.7,19.73],[232.7,11.73],[231.7,10.73],[231.7,8.73],[230.7,8.73],[230.7,7.73],[233.7,7.73],[247.7,12.73],[269.7,22.73],[280.7,28.73],[291.7,34.73],[292.7,35.73],[293.7,35.73],[293.7,36.73],[288.7,37.73],[267.7,47.73],[230.7,62.73],[212.7,69.73],[196.7,77.73]]]}','Skynet','','0000-00-00 00:00:00','',''),(44,'9335081772','MYQWEC1C10001','Pandan Indah','BIC-BEAM-ENG L3-MOD 21',1.00,0.00,0.00,'Pandan Indah','2019-04-01 09:07:53','super','2019-04-01 09:14:47','super','2019-04-01 09:17:14','super','2019-04-01 09:19:44','','0000-00-00 00:00:00','','super','2019-04-01 09:17:51','123122','super','2019-04-01 09:27:25','yeyeye','112112244509','{\"lines\":[[[202.7,115.73],[202.7,117.73]],[[163.7,99.73],[163.7,98.73],[164.7,95.73],[169.7,90.73],[185.7,81.73],[216.7,69.73],[254.7,63.73],[269.7,63.73],[287.7,68.73],[311.7,82.73],[318.7,90.73],[326.7,98.73],[336.7,112.73],[338.7,118.73],[339.7,121.73],[332.7,137.73],[312.7,153.73],[277.7,169.73],[235.7,177.73],[220.7,178.73],[206.7,177.73],[180.7,165.73],[171.7,157.73],[164.7,149.73],[157.7,141.73],[157.7,140.73],[157.7,139.73],[157.7,138.73],[157.7,136.73],[159.7,136.73],[161.7,136.73],[162.7,136.73],[163.7,137.73],[168.7,140.73],[178.7,148.73],[199.7,158.73],[232.7,167.73],[265.7,171.73],[271.7,171.73],[273.7,171.73],[277.7,166.73],[279.7,157.73],[279.7,149.73],[266.7,134.73],[245.7,127.73],[222.7,124.73],[201.7,124.73],[195.7,128.73],[189.7,133.73],[180.7,147.73],[179.7,157.73],[179.7,165.73],[188.7,185.73],[196.7,193.73],[206.7,198.73],[222.7,204.73],[226.7,204.73],[228.7,204.73],[232.7,199.73],[233.7,192.73],[233.7,184.73],[216.7,165.73],[199.7,160.73],[181.7,158.73],[164.7,158.73],[159.7,159.73],[155.7,164.73],[150.7,174.73],[149.7,180.73],[148.7,187.73],[149.7,201.73],[152.7,206.73],[154.7,207.73],[155.7,208.73],[155.7,205.73],[154.7,193.73],[132.7,161.73],[122.7,153.73],[116.7,150.73],[109.7,149.73],[108.7,149.73],[105.7,153.73],[101.7,167.73],[100.7,174.73],[100.7,178.73],[100.7,181.73],[99.7,181.73],[97.7,181.73],[73.7,162.73],[62.7,149.73],[53.7,139.73],[39.7,126.73],[33.7,120.73],[29.7,111.73],[25.7,93.73],[25.7,86.73],[28.7,80.73],[42.7,67.73],[52.7,59.73],[67.7,51.73],[102.7,41.73],[113.7,41.73],[123.7,41.73],[143.7,51.73],[152.7,58.73],[159.7,65.73],[169.7,85.73],[171.7,93.73],[171.7,99.73],[168.7,108.73],[158.7,115.73],[142.7,121.73],[105.7,120.73],[86.7,108.73],[72.7,99.73],[57.7,86.73],[53.7,83.73],[53.7,82.73],[52.7,81.73],[51.7,81.73],[51.7,82.73],[51.7,86.73],[51.7,91.73],[51.7,96.73],[53.7,114.73],[56.7,123.73],[56.7,127.73],[62.7,137.73],[67.7,141.73],[72.7,146.73],[80.7,153.73],[82.7,155.73],[83.7,156.73],[84.7,157.73],[85.7,159.73],[85.7,160.73],[86.7,164.73],[86.7,170.73],[86.7,175.73],[90.7,181.73],[87.7,183.73],[73.7,188.73],[30.7,207.73],[-4.3,224.73],[-14.3,228.73],[-19.3,231.73],[-22.3,234.73],[-23.3,236.73],[-23.3,240.73],[-17.3,251.73],[-9.3,259.73],[0.7,267.73],[18.7,278.73],[24.7,280.73],[30.7,282.73],[41.7,281.73],[47.7,278.73],[51.7,274.73],[60.7,264.73],[63.7,260.73],[68.7,255.73],[75.7,248.73],[77.7,247.73],[79.7,247.73],[84.7,242.73],[88.7,237.73],[92.7,232.73],[102.7,223.73],[108.7,220.73],[112.7,217.73],[117.7,215.73],[119.7,215.73],[120.7,215.73],[123.7,215.73],[124.7,217.73],[125.7,222.73],[114.7,253.73],[111.7,262.73],[110.7,264.73],[109.7,266.73],[109.7,267.73],[110.7,267.73],[115.7,265.73],[124.7,261.73],[135.7,253.73],[145.7,246.73],[155.7,235.73],[157.7,232.73],[159.7,230.73],[163.7,228.73],[165.7,227.73],[167.7,226.73],[176.7,226.73],[181.7,226.73],[184.7,226.73],[190.7,226.73],[191.7,226.73],[192.7,226.73],[194.7,226.73],[195.7,226.73],[195.7,225.73],[191.7,224.73],[176.7,223.73],[159.7,225.73],[158.7,227.73],[158.7,229.73],[165.7,237.73],[173.7,241.73],[181.7,243.73],[222.7,239.73],[232.7,235.73],[237.7,233.73],[240.7,230.73],[240.7,229.73],[241.7,228.73],[241.7,227.73],[241.7,226.73],[240.7,225.73],[238.7,223.73],[238.7,222.73],[238.7,220.73],[238.7,217.73],[243.7,211.73],[248.7,206.73],[253.7,199.73],[255.7,199.73],[262.7,199.73],[289.7,199.73],[337.7,204.73],[358.7,206.73],[360.7,206.73],[361.7,206.73],[360.7,206.73],[349.7,205.73],[317.7,210.73],[294.7,219.73],[286.7,225.73],[283.7,227.73],[280.7,235.73],[280.7,241.73],[282.7,247.73],[290.7,259.73],[292.7,261.73],[292.7,262.73],[293.7,263.73],[294.7,260.73],[297.7,256.73],[306.7,251.73],[316.7,246.73],[325.7,244.73],[326.7,244.73],[327.7,243.73],[330.7,235.73],[332.7,214.73],[332.7,172.73],[316.7,66.73],[311.7,56.73],[304.7,49.73],[280.7,41.73],[266.7,38.73],[256.7,35.73],[244.7,28.73],[240.7,24.73],[236.7,19.73],[232.7,11.73],[231.7,10.73],[231.7,8.73],[230.7,8.73],[230.7,7.73],[233.7,7.73],[247.7,12.73],[269.7,22.73],[280.7,28.73],[291.7,34.73],[292.7,35.73],[293.7,35.73],[293.7,36.73],[288.7,37.73],[267.7,47.73],[230.7,62.73],[212.7,69.73],[196.7,77.73]]]}','Skynet','','0000-00-00 00:00:00','',''),(45,'9335081772','MYQWEC1C10001','Pandan Indah','INT-ENG GL 1-MO 01',5.00,122.50,612.50,'Pandan Indah','2019-04-01 09:07:53','super','2019-04-01 09:14:47','super','2019-04-01 09:17:14','super','2019-04-01 09:19:44','','0000-00-00 00:00:00','','super','2019-04-01 09:17:51','123122','super','2019-04-01 09:27:25','yeyeye','112112244509','{\"lines\":[[[202.7,115.73],[202.7,117.73]],[[163.7,99.73],[163.7,98.73],[164.7,95.73],[169.7,90.73],[185.7,81.73],[216.7,69.73],[254.7,63.73],[269.7,63.73],[287.7,68.73],[311.7,82.73],[318.7,90.73],[326.7,98.73],[336.7,112.73],[338.7,118.73],[339.7,121.73],[332.7,137.73],[312.7,153.73],[277.7,169.73],[235.7,177.73],[220.7,178.73],[206.7,177.73],[180.7,165.73],[171.7,157.73],[164.7,149.73],[157.7,141.73],[157.7,140.73],[157.7,139.73],[157.7,138.73],[157.7,136.73],[159.7,136.73],[161.7,136.73],[162.7,136.73],[163.7,137.73],[168.7,140.73],[178.7,148.73],[199.7,158.73],[232.7,167.73],[265.7,171.73],[271.7,171.73],[273.7,171.73],[277.7,166.73],[279.7,157.73],[279.7,149.73],[266.7,134.73],[245.7,127.73],[222.7,124.73],[201.7,124.73],[195.7,128.73],[189.7,133.73],[180.7,147.73],[179.7,157.73],[179.7,165.73],[188.7,185.73],[196.7,193.73],[206.7,198.73],[222.7,204.73],[226.7,204.73],[228.7,204.73],[232.7,199.73],[233.7,192.73],[233.7,184.73],[216.7,165.73],[199.7,160.73],[181.7,158.73],[164.7,158.73],[159.7,159.73],[155.7,164.73],[150.7,174.73],[149.7,180.73],[148.7,187.73],[149.7,201.73],[152.7,206.73],[154.7,207.73],[155.7,208.73],[155.7,205.73],[154.7,193.73],[132.7,161.73],[122.7,153.73],[116.7,150.73],[109.7,149.73],[108.7,149.73],[105.7,153.73],[101.7,167.73],[100.7,174.73],[100.7,178.73],[100.7,181.73],[99.7,181.73],[97.7,181.73],[73.7,162.73],[62.7,149.73],[53.7,139.73],[39.7,126.73],[33.7,120.73],[29.7,111.73],[25.7,93.73],[25.7,86.73],[28.7,80.73],[42.7,67.73],[52.7,59.73],[67.7,51.73],[102.7,41.73],[113.7,41.73],[123.7,41.73],[143.7,51.73],[152.7,58.73],[159.7,65.73],[169.7,85.73],[171.7,93.73],[171.7,99.73],[168.7,108.73],[158.7,115.73],[142.7,121.73],[105.7,120.73],[86.7,108.73],[72.7,99.73],[57.7,86.73],[53.7,83.73],[53.7,82.73],[52.7,81.73],[51.7,81.73],[51.7,82.73],[51.7,86.73],[51.7,91.73],[51.7,96.73],[53.7,114.73],[56.7,123.73],[56.7,127.73],[62.7,137.73],[67.7,141.73],[72.7,146.73],[80.7,153.73],[82.7,155.73],[83.7,156.73],[84.7,157.73],[85.7,159.73],[85.7,160.73],[86.7,164.73],[86.7,170.73],[86.7,175.73],[90.7,181.73],[87.7,183.73],[73.7,188.73],[30.7,207.73],[-4.3,224.73],[-14.3,228.73],[-19.3,231.73],[-22.3,234.73],[-23.3,236.73],[-23.3,240.73],[-17.3,251.73],[-9.3,259.73],[0.7,267.73],[18.7,278.73],[24.7,280.73],[30.7,282.73],[41.7,281.73],[47.7,278.73],[51.7,274.73],[60.7,264.73],[63.7,260.73],[68.7,255.73],[75.7,248.73],[77.7,247.73],[79.7,247.73],[84.7,242.73],[88.7,237.73],[92.7,232.73],[102.7,223.73],[108.7,220.73],[112.7,217.73],[117.7,215.73],[119.7,215.73],[120.7,215.73],[123.7,215.73],[124.7,217.73],[125.7,222.73],[114.7,253.73],[111.7,262.73],[110.7,264.73],[109.7,266.73],[109.7,267.73],[110.7,267.73],[115.7,265.73],[124.7,261.73],[135.7,253.73],[145.7,246.73],[155.7,235.73],[157.7,232.73],[159.7,230.73],[163.7,228.73],[165.7,227.73],[167.7,226.73],[176.7,226.73],[181.7,226.73],[184.7,226.73],[190.7,226.73],[191.7,226.73],[192.7,226.73],[194.7,226.73],[195.7,226.73],[195.7,225.73],[191.7,224.73],[176.7,223.73],[159.7,225.73],[158.7,227.73],[158.7,229.73],[165.7,237.73],[173.7,241.73],[181.7,243.73],[222.7,239.73],[232.7,235.73],[237.7,233.73],[240.7,230.73],[240.7,229.73],[241.7,228.73],[241.7,227.73],[241.7,226.73],[240.7,225.73],[238.7,223.73],[238.7,222.73],[238.7,220.73],[238.7,217.73],[243.7,211.73],[248.7,206.73],[253.7,199.73],[255.7,199.73],[262.7,199.73],[289.7,199.73],[337.7,204.73],[358.7,206.73],[360.7,206.73],[361.7,206.73],[360.7,206.73],[349.7,205.73],[317.7,210.73],[294.7,219.73],[286.7,225.73],[283.7,227.73],[280.7,235.73],[280.7,241.73],[282.7,247.73],[290.7,259.73],[292.7,261.73],[292.7,262.73],[293.7,263.73],[294.7,260.73],[297.7,256.73],[306.7,251.73],[316.7,246.73],[325.7,244.73],[326.7,244.73],[327.7,243.73],[330.7,235.73],[332.7,214.73],[332.7,172.73],[316.7,66.73],[311.7,56.73],[304.7,49.73],[280.7,41.73],[266.7,38.73],[256.7,35.73],[244.7,28.73],[240.7,24.73],[236.7,19.73],[232.7,11.73],[231.7,10.73],[231.7,8.73],[230.7,8.73],[230.7,7.73],[233.7,7.73],[247.7,12.73],[269.7,22.73],[280.7,28.73],[291.7,34.73],[292.7,35.73],[293.7,35.73],[293.7,36.73],[288.7,37.73],[267.7,47.73],[230.7,62.73],[212.7,69.73],[196.7,77.73]]]}','Skynet','','0000-00-00 00:00:00','',''),(46,'9335081772','MYQWEC1C10001','Pandan Indah','INT-ENG GL 1-MOD 06',2.00,122.50,245.00,'Pandan Indah','2019-04-01 09:07:53','super','2019-04-01 09:14:47','super','2019-04-01 09:17:14','super','2019-04-01 09:19:44','','0000-00-00 00:00:00','','super','2019-04-01 09:17:51','123122','super','2019-04-01 09:27:25','yeyeye','112112244509','{\"lines\":[[[202.7,115.73],[202.7,117.73]],[[163.7,99.73],[163.7,98.73],[164.7,95.73],[169.7,90.73],[185.7,81.73],[216.7,69.73],[254.7,63.73],[269.7,63.73],[287.7,68.73],[311.7,82.73],[318.7,90.73],[326.7,98.73],[336.7,112.73],[338.7,118.73],[339.7,121.73],[332.7,137.73],[312.7,153.73],[277.7,169.73],[235.7,177.73],[220.7,178.73],[206.7,177.73],[180.7,165.73],[171.7,157.73],[164.7,149.73],[157.7,141.73],[157.7,140.73],[157.7,139.73],[157.7,138.73],[157.7,136.73],[159.7,136.73],[161.7,136.73],[162.7,136.73],[163.7,137.73],[168.7,140.73],[178.7,148.73],[199.7,158.73],[232.7,167.73],[265.7,171.73],[271.7,171.73],[273.7,171.73],[277.7,166.73],[279.7,157.73],[279.7,149.73],[266.7,134.73],[245.7,127.73],[222.7,124.73],[201.7,124.73],[195.7,128.73],[189.7,133.73],[180.7,147.73],[179.7,157.73],[179.7,165.73],[188.7,185.73],[196.7,193.73],[206.7,198.73],[222.7,204.73],[226.7,204.73],[228.7,204.73],[232.7,199.73],[233.7,192.73],[233.7,184.73],[216.7,165.73],[199.7,160.73],[181.7,158.73],[164.7,158.73],[159.7,159.73],[155.7,164.73],[150.7,174.73],[149.7,180.73],[148.7,187.73],[149.7,201.73],[152.7,206.73],[154.7,207.73],[155.7,208.73],[155.7,205.73],[154.7,193.73],[132.7,161.73],[122.7,153.73],[116.7,150.73],[109.7,149.73],[108.7,149.73],[105.7,153.73],[101.7,167.73],[100.7,174.73],[100.7,178.73],[100.7,181.73],[99.7,181.73],[97.7,181.73],[73.7,162.73],[62.7,149.73],[53.7,139.73],[39.7,126.73],[33.7,120.73],[29.7,111.73],[25.7,93.73],[25.7,86.73],[28.7,80.73],[42.7,67.73],[52.7,59.73],[67.7,51.73],[102.7,41.73],[113.7,41.73],[123.7,41.73],[143.7,51.73],[152.7,58.73],[159.7,65.73],[169.7,85.73],[171.7,93.73],[171.7,99.73],[168.7,108.73],[158.7,115.73],[142.7,121.73],[105.7,120.73],[86.7,108.73],[72.7,99.73],[57.7,86.73],[53.7,83.73],[53.7,82.73],[52.7,81.73],[51.7,81.73],[51.7,82.73],[51.7,86.73],[51.7,91.73],[51.7,96.73],[53.7,114.73],[56.7,123.73],[56.7,127.73],[62.7,137.73],[67.7,141.73],[72.7,146.73],[80.7,153.73],[82.7,155.73],[83.7,156.73],[84.7,157.73],[85.7,159.73],[85.7,160.73],[86.7,164.73],[86.7,170.73],[86.7,175.73],[90.7,181.73],[87.7,183.73],[73.7,188.73],[30.7,207.73],[-4.3,224.73],[-14.3,228.73],[-19.3,231.73],[-22.3,234.73],[-23.3,236.73],[-23.3,240.73],[-17.3,251.73],[-9.3,259.73],[0.7,267.73],[18.7,278.73],[24.7,280.73],[30.7,282.73],[41.7,281.73],[47.7,278.73],[51.7,274.73],[60.7,264.73],[63.7,260.73],[68.7,255.73],[75.7,248.73],[77.7,247.73],[79.7,247.73],[84.7,242.73],[88.7,237.73],[92.7,232.73],[102.7,223.73],[108.7,220.73],[112.7,217.73],[117.7,215.73],[119.7,215.73],[120.7,215.73],[123.7,215.73],[124.7,217.73],[125.7,222.73],[114.7,253.73],[111.7,262.73],[110.7,264.73],[109.7,266.73],[109.7,267.73],[110.7,267.73],[115.7,265.73],[124.7,261.73],[135.7,253.73],[145.7,246.73],[155.7,235.73],[157.7,232.73],[159.7,230.73],[163.7,228.73],[165.7,227.73],[167.7,226.73],[176.7,226.73],[181.7,226.73],[184.7,226.73],[190.7,226.73],[191.7,226.73],[192.7,226.73],[194.7,226.73],[195.7,226.73],[195.7,225.73],[191.7,224.73],[176.7,223.73],[159.7,225.73],[158.7,227.73],[158.7,229.73],[165.7,237.73],[173.7,241.73],[181.7,243.73],[222.7,239.73],[232.7,235.73],[237.7,233.73],[240.7,230.73],[240.7,229.73],[241.7,228.73],[241.7,227.73],[241.7,226.73],[240.7,225.73],[238.7,223.73],[238.7,222.73],[238.7,220.73],[238.7,217.73],[243.7,211.73],[248.7,206.73],[253.7,199.73],[255.7,199.73],[262.7,199.73],[289.7,199.73],[337.7,204.73],[358.7,206.73],[360.7,206.73],[361.7,206.73],[360.7,206.73],[349.7,205.73],[317.7,210.73],[294.7,219.73],[286.7,225.73],[283.7,227.73],[280.7,235.73],[280.7,241.73],[282.7,247.73],[290.7,259.73],[292.7,261.73],[292.7,262.73],[293.7,263.73],[294.7,260.73],[297.7,256.73],[306.7,251.73],[316.7,246.73],[325.7,244.73],[326.7,244.73],[327.7,243.73],[330.7,235.73],[332.7,214.73],[332.7,172.73],[316.7,66.73],[311.7,56.73],[304.7,49.73],[280.7,41.73],[266.7,38.73],[256.7,35.73],[244.7,28.73],[240.7,24.73],[236.7,19.73],[232.7,11.73],[231.7,10.73],[231.7,8.73],[230.7,8.73],[230.7,7.73],[233.7,7.73],[247.7,12.73],[269.7,22.73],[280.7,28.73],[291.7,34.73],[292.7,35.73],[293.7,35.73],[293.7,36.73],[288.7,37.73],[267.7,47.73],[230.7,62.73],[212.7,69.73],[196.7,77.73]]]}','Skynet','','0000-00-00 00:00:00','',''),(47,'5058778006','MYQWEC1C10001','Pandan Indah','INT-ENG GL 1-MOD 06',1.00,122.50,122.50,'Pandan Indah','2019-04-01 09:08:53','super','2019-04-01 09:16:19','super','2019-04-01 09:19:23','super','2019-04-02 08:57:51','','0000-00-00 00:00:00','','super','2019-04-01 09:19:42','','','0000-00-00 00:00:00','','','','Self-Pickup','','0000-00-00 00:00:00','',''),(48,'5058778006','MYQWEC1C10001','Pandan Indah','INT-ENG GL 1-MO 01',1.00,122.50,122.50,'Pandan Indah','2019-04-01 09:08:53','super','2019-04-01 09:16:19','super','2019-04-01 09:19:23','super','2019-04-02 08:57:51','','0000-00-00 00:00:00','','super','2019-04-01 09:19:42','','','0000-00-00 00:00:00','','','','Self-Pickup','','0000-00-00 00:00:00','',''),(49,'5058778006','MYQWEC1C10001','Pandan Indah','INT-ENG.BRIDGING-PROGM',1.00,122.50,122.50,'Pandan Indah','2019-04-01 09:08:53','super','2019-04-01 09:16:19','super','2019-04-01 09:19:23','super','2019-04-02 08:57:51','','0000-00-00 00:00:00','','super','2019-04-01 09:19:42','','','0000-00-00 00:00:00','','','','Self-Pickup','','0000-00-00 00:00:00','',''),(50,'4351716558','MYQWEC1C10001','Pandan Indah','INT-ENG.BRIDGING-PROGM',1.00,122.50,122.50,'Pandan Indah','2019-04-01 09:10:52','super','2019-04-01 09:14:52','super','2019-04-01 09:17:20','super','2019-04-01 09:19:48','super','2019-04-01 09:25:35','Oja74boY.jpg','super','2019-04-01 09:19:20','aaaaaa','super','2019-04-01 09:27:37','aaa','aaaaaaaaaaaaaaaaaaaaaaa','{\"lines\":[[[47.7,67.23],[47.7,69.23]],[[118.7,103.23],[118.7,105.23]],[[118.7,103.23],[118.7,105.23]]]}','Pos Laju','','0000-00-00 00:00:00','',''),(51,'9319329114','MYQWEC1C10001','Pandan Indah','INT-ENG GL 1-MOD 02',1.00,122.50,122.50,'Pandan Indah','2019-04-01 09:16:29','super','2019-04-01 09:17:00','super','2019-04-01 09:17:19','super','2019-04-01 09:19:53','super','2019-04-01 09:25:23','nCrJCl8G.pdf','super','2019-04-01 09:18:36','12345','super','2019-04-01 09:27:29','Siti Matahari','123456','{\"lines\":[[[199.7,15.23],[199.7,17.23]],[[199.7,15.23],[199.7,17.23]],[[189.7,56.23],[188.7,56.23],[187.7,55.23],[186.7,55.23],[185.7,53.23],[180.7,49.23],[177.7,48.23],[171.7,46.23],[162.7,43.23],[159.7,43.23],[157.7,42.23],[155.7,42.23],[154.7,42.23],[152.7,45.23],[152.7,46.23],[150.7,49.23],[149.7,52.23],[146.7,59.23],[144.7,64.23],[143.7,67.23],[141.7,76.23],[141.7,82.23],[140.7,96.23],[140.7,101.23],[141.7,108.23],[145.7,123.23],[148.7,129.23],[152.7,135.23],[161.7,147.23],[166.7,154.23],[173.7,160.23],[188.7,174.23],[194.7,181.23],[201.7,187.23],[211.7,197.23],[216.7,201.23],[220.7,205.23],[226.7,210.23],[228.7,211.23],[229.7,212.23],[230.7,213.23],[230.7,209.23],[227.7,203.23]],[[188.7,61.23],[188.7,62.23],[189.7,63.23],[190.7,65.23],[192.7,68.23],[193.7,71.23],[195.7,74.23],[197.7,78.23],[198.7,79.23],[199.7,80.23],[199.7,81.23],[199.7,82.23],[201.7,84.23],[201.7,86.23],[201.7,87.23],[202.7,87.23],[202.7,84.23],[203.7,78.23],[204.7,72.23],[204.7,64.23],[204.7,56.23],[204.7,50.23],[205.7,43.23],[205.7,41.23],[205.7,40.23],[206.7,39.23],[207.7,39.23],[208.7,38.23],[213.7,38.23],[216.7,38.23],[218.7,38.23],[223.7,43.23],[225.7,47.23],[228.7,50.23],[233.7,57.23],[234.7,62.23],[238.7,71.23],[239.7,75.23],[240.7,80.23],[240.7,88.23],[240.7,94.23],[240.7,99.23],[238.7,112.23],[236.7,120.23],[233.7,128.23],[226.7,148.23],[224.7,155.23],[223.7,162.23],[219.7,174.23],[218.7,178.23],[215.7,190.23],[214.7,195.23],[213.7,199.23],[213.7,202.23],[212.7,205.23],[212.7,206.23],[212.7,207.23],[211.7,206.23]]]}','Nationwide','','0000-00-00 00:00:00','',''),(52,'9319329114','MYQWEC1C10001','Pandan Indah','INT-ENG GL 1-MO 01',1.00,122.50,122.50,'Pandan Indah','2019-04-01 09:16:29','super','2019-04-01 09:17:00','super','2019-04-01 09:17:19','super','2019-04-01 09:19:53','super','2019-04-01 09:25:23','nCrJCl8G.pdf','super','2019-04-01 09:18:36','12345','super','2019-04-01 09:27:29','Siti Matahari','123456','{\"lines\":[[[199.7,15.23],[199.7,17.23]],[[199.7,15.23],[199.7,17.23]],[[189.7,56.23],[188.7,56.23],[187.7,55.23],[186.7,55.23],[185.7,53.23],[180.7,49.23],[177.7,48.23],[171.7,46.23],[162.7,43.23],[159.7,43.23],[157.7,42.23],[155.7,42.23],[154.7,42.23],[152.7,45.23],[152.7,46.23],[150.7,49.23],[149.7,52.23],[146.7,59.23],[144.7,64.23],[143.7,67.23],[141.7,76.23],[141.7,82.23],[140.7,96.23],[140.7,101.23],[141.7,108.23],[145.7,123.23],[148.7,129.23],[152.7,135.23],[161.7,147.23],[166.7,154.23],[173.7,160.23],[188.7,174.23],[194.7,181.23],[201.7,187.23],[211.7,197.23],[216.7,201.23],[220.7,205.23],[226.7,210.23],[228.7,211.23],[229.7,212.23],[230.7,213.23],[230.7,209.23],[227.7,203.23]],[[188.7,61.23],[188.7,62.23],[189.7,63.23],[190.7,65.23],[192.7,68.23],[193.7,71.23],[195.7,74.23],[197.7,78.23],[198.7,79.23],[199.7,80.23],[199.7,81.23],[199.7,82.23],[201.7,84.23],[201.7,86.23],[201.7,87.23],[202.7,87.23],[202.7,84.23],[203.7,78.23],[204.7,72.23],[204.7,64.23],[204.7,56.23],[204.7,50.23],[205.7,43.23],[205.7,41.23],[205.7,40.23],[206.7,39.23],[207.7,39.23],[208.7,38.23],[213.7,38.23],[216.7,38.23],[218.7,38.23],[223.7,43.23],[225.7,47.23],[228.7,50.23],[233.7,57.23],[234.7,62.23],[238.7,71.23],[239.7,75.23],[240.7,80.23],[240.7,88.23],[240.7,94.23],[240.7,99.23],[238.7,112.23],[236.7,120.23],[233.7,128.23],[226.7,148.23],[224.7,155.23],[223.7,162.23],[219.7,174.23],[218.7,178.23],[215.7,190.23],[214.7,195.23],[213.7,199.23],[213.7,202.23],[212.7,205.23],[212.7,206.23],[212.7,207.23],[211.7,206.23]]]}','Nationwide','','0000-00-00 00:00:00','',''),(53,'9319329114','MYQWEC1C10001','Pandan Indah','INT-ENG GL 1-MOD 06',1.00,122.50,122.50,'Pandan Indah','2019-04-01 09:16:29','super','2019-04-01 09:17:00','super','2019-04-01 09:17:19','super','2019-04-01 09:19:53','super','2019-04-01 09:25:23','nCrJCl8G.pdf','super','2019-04-01 09:18:36','12345','super','2019-04-01 09:27:29','Siti Matahari','123456','{\"lines\":[[[199.7,15.23],[199.7,17.23]],[[199.7,15.23],[199.7,17.23]],[[189.7,56.23],[188.7,56.23],[187.7,55.23],[186.7,55.23],[185.7,53.23],[180.7,49.23],[177.7,48.23],[171.7,46.23],[162.7,43.23],[159.7,43.23],[157.7,42.23],[155.7,42.23],[154.7,42.23],[152.7,45.23],[152.7,46.23],[150.7,49.23],[149.7,52.23],[146.7,59.23],[144.7,64.23],[143.7,67.23],[141.7,76.23],[141.7,82.23],[140.7,96.23],[140.7,101.23],[141.7,108.23],[145.7,123.23],[148.7,129.23],[152.7,135.23],[161.7,147.23],[166.7,154.23],[173.7,160.23],[188.7,174.23],[194.7,181.23],[201.7,187.23],[211.7,197.23],[216.7,201.23],[220.7,205.23],[226.7,210.23],[228.7,211.23],[229.7,212.23],[230.7,213.23],[230.7,209.23],[227.7,203.23]],[[188.7,61.23],[188.7,62.23],[189.7,63.23],[190.7,65.23],[192.7,68.23],[193.7,71.23],[195.7,74.23],[197.7,78.23],[198.7,79.23],[199.7,80.23],[199.7,81.23],[199.7,82.23],[201.7,84.23],[201.7,86.23],[201.7,87.23],[202.7,87.23],[202.7,84.23],[203.7,78.23],[204.7,72.23],[204.7,64.23],[204.7,56.23],[204.7,50.23],[205.7,43.23],[205.7,41.23],[205.7,40.23],[206.7,39.23],[207.7,39.23],[208.7,38.23],[213.7,38.23],[216.7,38.23],[218.7,38.23],[223.7,43.23],[225.7,47.23],[228.7,50.23],[233.7,57.23],[234.7,62.23],[238.7,71.23],[239.7,75.23],[240.7,80.23],[240.7,88.23],[240.7,94.23],[240.7,99.23],[238.7,112.23],[236.7,120.23],[233.7,128.23],[226.7,148.23],[224.7,155.23],[223.7,162.23],[219.7,174.23],[218.7,178.23],[215.7,190.23],[214.7,195.23],[213.7,199.23],[213.7,202.23],[212.7,205.23],[212.7,206.23],[212.7,207.23],[211.7,206.23]]]}','Nationwide','','0000-00-00 00:00:00','',''),(54,'8552814006','MYQWEC1C10001','Pandan Indah','INT-ENG GL 2-MOD 17',2.00,122.50,245.00,'Pandan Indah','2019-04-08 08:02:13','super','2019-04-08 08:03:49','super','2019-04-08 08:04:28','super','2019-04-08 08:05:04','super','2019-04-08 08:04:14','hAkOdO1W.jpg','super','2019-04-08 08:04:58','wdrdsa3435','super','2019-04-08 14:30:47','amos','232332-43-22224','{\"lines\":[[[286.18,76.4],[280.18,86.4],[263.18,115.4],[257.18,128.4],[247.18,140.4],[241.18,152.4],[226.18,176.4],[201.18,213.4],[193.18,226.4],[190.18,232.4],[179.18,244.4],[166.18,250.4],[146.18,262.4],[129.18,270.4],[120.18,271.4],[104.18,272.4],[90.18,272.4],[86.18,271.4],[72.18,263.4],[60.18,242.4],[51.18,208.4],[48.18,181.4],[50.18,155.4],[51.18,138.4],[56.18,111.4],[64.18,91.4],[67.18,85.4],[73.18,74.4],[76.18,72.4],[81.18,70.4],[88.18,67.4],[98.18,67.4],[105.18,71.4],[118.18,83.4],[136.18,113.4],[147.18,137.4],[176.18,214.4],[179.18,229.4],[190.18,271.4],[197.18,293.4],[207.18,319.4],[210.18,321.4],[213.18,324.4],[223.18,330.4],[238.18,336.4],[250.18,336.4],[272.18,336.4],[336.18,323.4],[346.18,320.4],[369.18,318.4],[378.18,316.4],[380.18,316.4],[384.18,316.4],[384.18,314.4],[380.18,310.4],[371.18,298.4],[352.18,265.4],[313.18,201.4],[295.18,176.4],[282.18,156.4],[244.18,114.4],[234.18,103.4],[229.18,102.4],[226.18,97.4],[222.18,95.4],[218.18,94.4],[215.18,91.4],[212.18,91.4],[206.18,91.4],[201.18,91.4],[197.18,91.4],[194.18,90.4],[192.18,89.4],[187.18,89.4],[185.18,88.4],[183.18,87.4],[180.18,86.4],[178.18,85.4],[177.18,85.4],[176.18,85.4]]]}','Self-Pickup','','0000-00-00 00:00:00','',''),(55,'8552814006','MYQWEC1C10001','Pandan Indah','INT-ENG GL 2-MOD 19',3.00,122.50,367.50,'Pandan Indah','2019-04-08 08:02:13','super','2019-04-08 08:03:49','super','2019-04-08 08:04:28','super','2019-04-08 08:05:04','super','2019-04-08 08:04:14','hAkOdO1W.jpg','super','2019-04-08 08:04:58','wdrdsa3435','super','2019-04-08 14:30:47','amos','232332-43-22224','{\"lines\":[[[286.18,76.4],[280.18,86.4],[263.18,115.4],[257.18,128.4],[247.18,140.4],[241.18,152.4],[226.18,176.4],[201.18,213.4],[193.18,226.4],[190.18,232.4],[179.18,244.4],[166.18,250.4],[146.18,262.4],[129.18,270.4],[120.18,271.4],[104.18,272.4],[90.18,272.4],[86.18,271.4],[72.18,263.4],[60.18,242.4],[51.18,208.4],[48.18,181.4],[50.18,155.4],[51.18,138.4],[56.18,111.4],[64.18,91.4],[67.18,85.4],[73.18,74.4],[76.18,72.4],[81.18,70.4],[88.18,67.4],[98.18,67.4],[105.18,71.4],[118.18,83.4],[136.18,113.4],[147.18,137.4],[176.18,214.4],[179.18,229.4],[190.18,271.4],[197.18,293.4],[207.18,319.4],[210.18,321.4],[213.18,324.4],[223.18,330.4],[238.18,336.4],[250.18,336.4],[272.18,336.4],[336.18,323.4],[346.18,320.4],[369.18,318.4],[378.18,316.4],[380.18,316.4],[384.18,316.4],[384.18,314.4],[380.18,310.4],[371.18,298.4],[352.18,265.4],[313.18,201.4],[295.18,176.4],[282.18,156.4],[244.18,114.4],[234.18,103.4],[229.18,102.4],[226.18,97.4],[222.18,95.4],[218.18,94.4],[215.18,91.4],[212.18,91.4],[206.18,91.4],[201.18,91.4],[197.18,91.4],[194.18,90.4],[192.18,89.4],[187.18,89.4],[185.18,88.4],[183.18,87.4],[180.18,86.4],[178.18,85.4],[177.18,85.4],[176.18,85.4]]]}','Self-Pickup','','0000-00-00 00:00:00','',''),(56,'8552814006','MYQWEC1C10001','Pandan Indah','INT-ENG.BRIDGING-PROGM',1.00,122.50,122.50,'Pandan Indah','2019-04-08 08:02:13','super','2019-04-08 08:03:49','super','2019-04-08 08:04:28','super','2019-04-08 08:05:04','super','2019-04-08 08:04:14','hAkOdO1W.jpg','super','2019-04-08 08:04:58','wdrdsa3435','super','2019-04-08 14:30:47','amos','232332-43-22224','{\"lines\":[[[286.18,76.4],[280.18,86.4],[263.18,115.4],[257.18,128.4],[247.18,140.4],[241.18,152.4],[226.18,176.4],[201.18,213.4],[193.18,226.4],[190.18,232.4],[179.18,244.4],[166.18,250.4],[146.18,262.4],[129.18,270.4],[120.18,271.4],[104.18,272.4],[90.18,272.4],[86.18,271.4],[72.18,263.4],[60.18,242.4],[51.18,208.4],[48.18,181.4],[50.18,155.4],[51.18,138.4],[56.18,111.4],[64.18,91.4],[67.18,85.4],[73.18,74.4],[76.18,72.4],[81.18,70.4],[88.18,67.4],[98.18,67.4],[105.18,71.4],[118.18,83.4],[136.18,113.4],[147.18,137.4],[176.18,214.4],[179.18,229.4],[190.18,271.4],[197.18,293.4],[207.18,319.4],[210.18,321.4],[213.18,324.4],[223.18,330.4],[238.18,336.4],[250.18,336.4],[272.18,336.4],[336.18,323.4],[346.18,320.4],[369.18,318.4],[378.18,316.4],[380.18,316.4],[384.18,316.4],[384.18,314.4],[380.18,310.4],[371.18,298.4],[352.18,265.4],[313.18,201.4],[295.18,176.4],[282.18,156.4],[244.18,114.4],[234.18,103.4],[229.18,102.4],[226.18,97.4],[222.18,95.4],[218.18,94.4],[215.18,91.4],[212.18,91.4],[206.18,91.4],[201.18,91.4],[197.18,91.4],[194.18,90.4],[192.18,89.4],[187.18,89.4],[185.18,88.4],[183.18,87.4],[180.18,86.4],[178.18,85.4],[177.18,85.4],[176.18,85.4]]]}','Self-Pickup','','0000-00-00 00:00:00','',''),(57,'5035611233','MYQWEC1C10001','test1','INT-ENG GL 1-MOD 03',1.00,122.50,122.50,'test1','2019-04-09 02:05:26','super','2019-04-09 02:06:00','super','2019-04-09 02:06:06','super','2019-04-09 02:06:14','','0000-00-00 00:00:00','','super','2019-04-09 02:06:10','AAAAA','super','2019-04-09 02:16:21','ST Ooi','BBBBB','{\"lines\":[[[103.2,128.73],[102.2,127.73],[102.2,124.73],[102.2,116.73],[106.2,105.73],[110.2,94.73],[116.2,82.73],[123.2,75.73],[132.2,69.73],[134.2,67.73],[136.2,67.73],[141.2,67.73],[147.2,67.73],[150.2,68.73],[151.2,72.73],[151.2,78.73],[151.2,85.73],[149.2,93.73],[147.2,106.73],[147.2,120.73],[147.2,129.73],[147.2,138.73],[150.2,148.73],[157.2,158.73],[169.2,168.73],[183.2,175.73],[195.2,183.73],[206.2,192.73],[208.2,195.73],[208.2,202.73],[208.2,204.73],[209.2,205.73],[211.2,205.73],[215.2,205.73],[220.2,205.73],[225.2,205.73],[235.2,205.73],[239.2,205.73],[241.2,205.73],[245.2,205.73],[248.2,205.73]]]}','Pos Laju','','0000-00-00 00:00:00','','This is remarks');
/*!40000 ALTER TABLE `order` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `parent`
--

DROP TABLE IF EXISTS `parent`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `parent` (
  `id` int(100) unsigned NOT NULL AUTO_INCREMENT,
  `parent_type` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `nric_no` varchar(100) NOT NULL,
  `occupation` varchar(100) NOT NULL,
  `hp_no` varchar(100) NOT NULL,
  `home_no` varchar(100) NOT NULL,
  `office_no` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `office_address1` varchar(100) NOT NULL,
  `office_address2` varchar(100) NOT NULL,
  `office_address3` varchar(100) NOT NULL,
  `office_address4` varchar(100) NOT NULL,
  `office_address5` varchar(100) NOT NULL,
  `postcode` varchar(100) NOT NULL,
  `country` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `marriage_status` varchar(100) NOT NULL,
  `remarks` varchar(200) NOT NULL,
  `centre_code` varchar(50) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nric_no` (`nric_no`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `parent`
--

LOCK TABLES `parent` WRITE;
/*!40000 ALTER TABLE `parent` DISABLE KEYS */;
/*!40000 ALTER TABLE `parent` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product`
--

DROP TABLE IF EXISTS `product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product` (
  `id` int(100) unsigned NOT NULL AUTO_INCREMENT,
  `product_code` varchar(100) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `category` varchar(100) NOT NULL,
  `sub_category` varchar(100) NOT NULL,
  `sub_sub_category` varchar(100) NOT NULL,
  `uom` varchar(100) NOT NULL,
  `unit_price` double(12,2) NOT NULL,
  `retail_price` double(12,2) NOT NULL,
  `product_photo` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_code` (`product_code`)
) ENGINE=MyISAM AUTO_INCREMENT=117 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product`
--

LOCK TABLES `product` WRITE;
/*!40000 ALTER TABLE `product` DISABLE KEYS */;
INSERT INTO `product` VALUES (2,'INT-ENG.BRIDGING-PROGM','International English Bridging Program','Q-dees Holdings Sdn. Bhd.','IE','International English Modules (Pre-Level1)','1',122.50,0.00,'FXtfGbRn.jpg'),(3,'INT-ENG GL 1-MO 01','International English GL 1-Module 1','Mindspectrum Sdn.Bhd','IE','International English Modules (Level1)','1',122.50,0.00,''),(4,'INT-ENG GL 1-MOD 06','International English GL 1- Module 6','Mindspectrum Sdn.Bhd','IE','International English Modules (Level1)','1',122.50,0.00,''),(5,'INT-ENG GL 1-MOD 02','International English GL 1- Module 2','Mindspectrum Sdn.Bhd','IE','International English Modules (Level1)','1',122.50,0.00,''),(6,'INT-ENG GL 1-MOD 07','International English GL 1- Module 7','Mindspectrum Sdn.Bhd','IE','International English Modules (Level1)','1',122.50,0.00,''),(7,'INT-ENG GL 1-MOD 03','International English GL 1- Module 3','Mindspectrum Sdn.Bhd','IE','International English Modules (Level1)','1',122.50,0.00,''),(8,'INT-ENG GL 1-MOD 08','International English GL 1- Module 8','Mindspectrum Sdn.Bhd','IE','International English Modules (Level1)','1',122.50,0.00,''),(9,'INT-ENG GL 1-MOD 04','International English GL 1- Module 4','Mindspectrum Sdn.Bhd','IE','International English Modules (Level1)','1',122.50,0.00,''),(10,'INT-ENG GL 1-MOD 09','International English GL 1- Module 9','Mindspectrum Sdn.Bhd','IE','International English Modules (Level1)','1',122.50,0.00,''),(11,'INT-ENG GL 1-MOD 05','International English GL 1- Module 5','Mindspectrum Sdn.Bhd','IE','International English Modules (Level1)','1',122.50,0.00,''),(12,'INT-ENG GL 1-MOD 10','International English GL 1- Module 10','Mindspectrum Sdn.Bhd','IE','International English Modules (Level1)','1',122.50,0.00,''),(13,'INT-ENG GL 2-MOD 11','International English GL 2- Module 11','Mindspectrum Sdn.Bhd','IE','International English Modules (Level2)','1',122.50,0.00,''),(14,'INT-ENG GL 2-MOD 16','International English GL 2- Module 16','Mindspectrum Sdn.Bhd','IE','International English Modules (Level2)','1',122.50,0.00,''),(15,'INT-ENG GL 2-MOD 12','International English GL 2- Module 12','Mindspectrum Sdn.Bhd','IE','International English Modules (Level2)','1',122.50,0.00,''),(16,'INT-ENG GL 2-MOD 17','International English GL 2- Module 17','Mindspectrum Sdn.Bhd','IE','International English Modules (Level2)','1',122.50,0.00,''),(17,'INT-ENG GL 2-MOD 13','International English GL 2- Module 13','Mindspectrum Sdn.Bhd','IE','International English Modules (Level2)','1',122.50,0.00,''),(18,'INT-ENG GL 2-MOD 18','International English GL 1- Module 18','Mindspectrum Sdn.Bhd','IE','International English Modules (Level2)','1',122.50,0.00,''),(19,'INT-ENG GL 2-MOD 14','International English GL 2- Module 14','Mindspectrum Sdn.Bhd','IE','International English Modules (Level2)','1',122.50,0.00,''),(20,'INT-ENG GL 2-MOD 19','International English GL 2- Module 19','Mindspectrum Sdn.Bhd','IE','International English Modules (Level2)','1',122.50,0.00,''),(21,'INT-ENG GL 2-MOD 15','International English GL 2- Module 15','Mindspectrum Sdn.Bhd','IE','International English Modules (Level2)','1',122.50,0.00,''),(22,'INT-ENG GL 2-MOD 20','International English GL 2- Module 20','Mindspectrum Sdn.Bhd','IE','International English Modules (Level2)','1',122.50,0.00,''),(23,'BIC-BEAM-ENG L3-MOD 21','BEAMODULE ENGLISH L3-MOD 21','Tenations Global Sdn. Bhd.','BIEP','BIEP Beamodules (Level3)','1',0.00,0.00,''),(24,'BIC-BEAM-ENG L3-MOD 22','BEAMODULE ENGLISH L3-MOD 22','Tenations Global Sdn. Bhd.','BIEP','BIEP Beamodules (Level3)','1',0.00,0.00,''),(25,'BIC-BEAM-ENG L3-MOD 23','BEAMODULE ENGLISH L3-MOD 23','Tenations Global Sdn. Bhd.','BIEP','BIEP Beamodules (Level3)','1',0.00,0.00,''),(26,'BIC-BEAM-ENG L3-MOD 24','BEAMODULE ENGLISH L3-MOD 24','Tenations Global Sdn. Bhd.','BIEP','BIEP Beamodules (Level3)','1',0.00,0.00,''),(27,'BIC-BEAM-ENG L3-MOD 25','BEAMODULE ENGLISH L3-MOD 25','Tenations Global Sdn. Bhd.','BIEP','BIEP Beamodules (Level3)','1',0.00,0.00,''),(28,'BIC-BEAM-ENG L3-MOD 26','BEAMODULE ENGLISH L3-MOD 26','Tenations Global Sdn. Bhd.','BIEP','BIEP Beamodules (Level3)','1',0.00,0.00,''),(29,'BIC-BEAM-ENG L3-MOD 27','BEAMODULE ENGLISH L3-MOD 27','Tenations Global Sdn. Bhd.','BIEP','BIEP Beamodules (Level3)','1',0.00,0.00,''),(30,'BIC-BEAM-ENG L3-MOD 28','BEAMODULE ENGLISH L3-MOD 28','Tenations Global Sdn. Bhd.','BIEP','BIEP Beamodules (Level3)','1',0.00,0.00,''),(31,'BIC-BEAM-ENG L3-MOD 29','BEAMODULE ENGLISH L3-MOD 29','Tenations Global Sdn. Bhd.','BIEP','BIEP Beamodules (Level3)','1',0.00,0.00,''),(32,'BIC-BEAM-ENG L3-MOD 30','BEAMODULE ENGLISH L3-MOD 30','Tenations Global Sdn. Bhd.','BIEP','BIEP Beamodules (Level3)','1',0.00,0.00,''),(33,'BIC-BEAM-ENG L4-MOD 31','BEAMODULE ENGLISH L4-MOD 31','Tenations Global Sdn. Bhd.','BIEP','BIEP Beamodules (Level4)','1',0.00,0.00,''),(34,'BIC-BEAM-ENG L4-MOD 32','BEAMODULE ENGLISH L4-MOD 32','Tenations Global Sdn. Bhd.','BIEP','BIEP Beamodules (Level4)','1',0.00,0.00,''),(35,'BIC-BEAM-ENG L4-MOD 33','BEAMODULE ENGLISH L4-MOD 33','Tenations Global Sdn. Bhd.','BIEP','BIEP Beamodules (Level4)','1',0.00,0.00,''),(36,'BIC-BEAM-ENG L4-MOD 34','BEAMODULE ENGLISH L4-MOD 34','Tenations Global Sdn. Bhd.','BIEP','BIEP Beamodules (Level4)','1',0.00,0.00,''),(37,'BIC-BEAM-ENG L4-MOD 35','BEAMODULE ENGLISH L4-MOD 35','Tenations Global Sdn. Bhd.','BIEP','BIEP Beamodules (Level4)','1',0.00,0.00,''),(38,'BIC-BEAM-ENG L4-MOD 36','BEAMODULE ENGLISH L4-MOD 36','Tenations Global Sdn. Bhd.','BIEP','BIEP Beamodules (Level4)','1',0.00,0.00,''),(39,'BIC-BEAM-ENG L4-MOD 37','BEAMODULE ENGLISH L4-MOD 37','Tenations Global Sdn. Bhd.','BIEP','BIEP Beamodules (Level4)','1',0.00,0.00,''),(40,'BIC-BEAM-ENG L4-MOD 38','BEAMODULE ENGLISH L4-MOD 38','Tenations Global Sdn. Bhd.','BIEP','BIEP Beamodules (Level4)','1',0.00,0.00,''),(41,'BIC-BEAM-ENG L4-MOD 39','BEAMODULE ENGLISH L4-MOD 39','Tenations Global Sdn. Bhd.','BIEP','BIEP Beamodules (Level4)','1',0.00,0.00,''),(42,'BIC-BEAM-ENG L4-MOD 40','BEAMODULE ENGLISH L4-MOD 40','Tenations Global Sdn. Bhd.','BIEP','BIEP Beamodules (Level4)','1',0.00,0.00,''),(43,'BIC-BEAM-MATH L1-MOD 01','BEAMANUAL MATH L1-MOD 01','Tenations Global Sdn. Bhd.','BIMP','BIMP Beamodules (Level1)','1',0.00,0.00,''),(44,'BIC-BEAM-MATH L1-SUP 01','SUPPLEMENTARY SHEET for MOD 01','Tenations Global Sdn. Bhd.','BIMP','BIMP Beamodules (Level1)','1',0.00,0.00,''),(45,'BIC-BEAM-MATH L1-MOD 02','BEAMANUAL MATH L1-MOD 02','Tenations Global Sdn. Bhd.','BIMP','BIMP Beamodules (Level1)','1',0.00,0.00,''),(46,'BIC-BEAM-MATH L1-MOD 03','BEAMANUAL MATH L1-MOD 03','Tenations Global Sdn. Bhd.','BIMP','BIMP Beamodules (Level1)','1',0.00,0.00,''),(47,'BIC-BEAM-MATH L1-MOD 04','BEAMANUAL MATH L1-MOD 04','Tenations Global Sdn. Bhd.','BIMP','BIMP Beamodules (Level1)','1',0.00,0.00,''),(48,'BIC-BEAM-MATH L1-SUP 04','SUPPLEMENTARY SHEET for MOD 04','Tenations Global Sdn. Bhd.','BIMP','BIMP Beamodules (Level1)','1',0.00,0.00,''),(49,'BIC-BEAM-MATH L1-MOD 05','BEAMANUAL MATH L1-MOD 05','Tenations Global Sdn. Bhd.','BIMP','BIMP Beamodules (Level1)','1',0.00,0.00,''),(50,'BIC-BEAM-MATH L1-SUP 05','SUPPLEMENTARY SHEET for MOD 05','Tenations Global Sdn. Bhd.','BIMP','BIMP Beamodules (Level1)','1',0.00,0.00,''),(51,'BIC-BEAM-MATH L1-MOD 06','BEAMANUAL MATH L1-MOD 06','Tenations Global Sdn. Bhd.','BIMP','BIMP Beamodules (Level1)','1',0.00,0.00,''),(52,'BIC-BEAM-MATH L1-MOD 07','BEAMANUAL MATH L1-MOD 07','Tenations Global Sdn. Bhd.','BIMP','BIMP Beamodules (Level1)','1',0.00,0.00,''),(53,'BIC-BEAM-MATH L1-MOD 08','BEAMANUAL MATH L1-MOD 08','Tenations Global Sdn. Bhd.','BIMP','BIMP Beamodules (Level1)','1',0.00,0.00,''),(54,'BIC-BEAM-MATH L1-SUP 08','SUPPLEMENTARY SHEET for MOD 08','Tenations Global Sdn. Bhd.','BIMP','BIMP Beamodules (Level1)','1',0.00,0.00,''),(55,'BIC-BEAM-MATH L1-MOD 09','BEAMANUAL MATH L1-MOD 09','Tenations Global Sdn. Bhd.','BIMP','BIMP Beamodules (Level1)','1',0.00,0.00,''),(56,'BIC-BEAM-MATH L1-MOD 10','BEAMANUAL MATH L1-MOD 10','Tenations Global Sdn. Bhd.','BIMP','BIMP Beamodules (Level1)','1',0.00,0.00,''),(57,'BIC-BEAM-MATH L2-MOD 11','BEAMANUAL MATH L2-MOD 11','Tenations Global Sdn. Bhd.','BIMP','BIMP Beamodules (Level2)','1',0.00,0.00,''),(58,'BIC-BEAM-MATH L2-SUP 11','SUPPLEMENTARY SHEETS for MOD 11','Tenations Global Sdn. Bhd.','BIMP','BIMP Beamodules (Level2)','1',0.00,0.00,''),(59,'BIC-BEAM-MATH L2-MOD 12','BEAMANUAL MATH L2-MOD 12','Tenations Global Sdn. Bhd.','BIMP','BIMP Beamodules (Level2)','1',0.00,0.00,''),(60,'BIC-BEAM-MATH L2-SUP 12','SUPPLEMENTARY SHEETS for MOD 12','Tenations Global Sdn. Bhd.','BIMP','BIMP Beamodules (Level2)','1',0.00,0.00,''),(61,'BIC-BEAM-MATH L2-MOD 13','BEAMANUAL MATH L2-MOD 13','Tenations Global Sdn. Bhd.','BIMP','BIMP Beamodules (Level2)','1',0.00,0.00,''),(62,'BIC-BEAM-MATH L2-SUP 13','SUPPLEMENTARY SHEETS for MOD 13','Tenations Global Sdn. Bhd.','BIMP','BIMP Beamodules (Level2)','1',0.00,0.00,''),(63,'BIC-BEAM-MATH L2-MOD 14','BEAMANUAL MATH L2-MOD 14','Tenations Global Sdn. Bhd.','BIMP','BIMP Beamodules (Level2)','1',0.00,0.00,''),(64,'BIC-BEAM-MATH L2-SUP 14','SUPPLEMENTARY SHEET for MOD 14','Tenations Global Sdn. Bhd.','BIMP','BIMP Beamodules (Level2)','1',0.00,0.00,''),(65,'BIC-BEAM-MATH L2-MOD 15','BEAMANUAL MATH L2-MOD 15','Tenations Global Sdn. Bhd.','BIMP','BIMP Beamodules (Level2)','1',0.00,0.00,''),(66,'BIC-BEAM-MATH L2-MOD 16','BEAMANUAL MATH L2-MOD 16','Tenations Global Sdn. Bhd.','BIMP','BIMP Beamodules (Level2)','1',0.00,0.00,''),(67,'BIC-BEAM-MATH L2-SUP 16','SUPPLEMENTARY SHEETS for MOD 16','Tenations Global Sdn. Bhd.','BIMP','BIMP Beamodules (Level2)','1',0.00,0.00,''),(68,'BIC-BEAM-MATH L2-MOD 17','BEAMANUAL MATH L2-MOD 17','Tenations Global Sdn. Bhd.','BIMP','BIMP Beamodules (Level2)','1',0.00,0.00,''),(69,'BIC-BEAM-MATH L2-MOD 18','BEAMANUAL MATH L2-MOD 18','Tenations Global Sdn. Bhd.','BIMP','BIMP Beamodules (Level2)','1',0.00,0.00,''),(70,'BIC-BEAM-MATH L2-MOD 19','BEAMANUAL MATH L2-MOD 19','Tenations Global Sdn. Bhd.','BIMP','BIMP Beamodules (Level2)','1',0.00,0.00,''),(71,'BIC-BEAM-MATH L2-SUP 19','SUPPLEMENTARY SHEETS for MOD 19','Tenations Global Sdn. Bhd.','BIMP','BIMP Beamodules (Level2)','1',0.00,0.00,''),(72,'BIC-BEAM-MATH L2-MOD 20','BEAMANUAL MATH L2-MOD 20','Tenations Global Sdn. Bhd.','BIMP','BIMP Beamodules (Level2)','1',0.00,0.00,''),(73,'BIC-BEAM-MATH L2-SUP 20','SUPPLEMENTARY SHEETS for MOD 20','Tenations Global Sdn. Bhd.','BIMP','BIMP Beamodules (Level2)','1',0.00,0.00,''),(74,'BIC-BEAM-MATH L2-SUP 15','SUPPLEMENTARY SHEET for MOD 15','Q-dees Holdings Sdn. Bhd.','BIMP','BIMP Supplementary Sheets (Level2)','1',0.00,0.00,''),(75,'BIC-BEAM-MATH L2-SUP 17','SUPPLEMENTARY SHEET for MOD  17','Q-dees Holdings Sdn. Bhd.','BIMP','BIMP Supplementary Sheets (Level2)','1',0.00,0.00,''),(76,'BIC-BEAM-MATH L2-SUP 18','SUPPLEMENTARY SHEET for MOD 18','Q-dees Holdings Sdn. Bhd.','BIMP','BIMP Supplementary Sheets (Level2)','1',0.00,0.00,''),(77,'BIC-BEAM-MATH L3-MOD 21','BEAMANUAL MATH L3-MOD 21','Tenations Global Sdn. Bhd.','BIMP','BIMP Beamodules (Level3)','1',0.00,0.00,''),(78,'BIC-BEAM-MATH L3-MOD 22','BEAMANUAL MATH L3-MOD 22','Tenations Global Sdn. Bhd.','BIMP','BIMP Beamodules (Level3)','1',0.00,0.00,''),(79,'BIC-BEAM-MATH L3-MOD 23','BEAMANUAL MATH L3-MOD 23','Tenations Global Sdn. Bhd.','BIMP','BIMP Beamodules (Level3)','1',0.00,0.00,''),(80,'BIC-BEAM-MATH L3-MOD 24','BEAMANUAL MATH L3-MOD 24','Tenations Global Sdn. Bhd.','BIMP','BIMP Beamodules (Level3)','1',0.00,0.00,''),(81,'BIC-BEAM-MATH L3-MOD 25','BEAMANUAL MATH L3-MOD 25','Tenations Global Sdn. Bhd.','BIMP','BIMP Beamodules (Level3)','1',0.00,0.00,''),(82,'BIC-BEAM-MATH L3-MOD 26','BEAMANUAL MATH L3-MOD 26','Tenations Global Sdn. Bhd.','BIMP','BIMP Beamodules (Level3)','1',0.00,0.00,''),(83,'BIC-BEAM-MATH L3-MOD 27','BEAMANUAL MATH L3-MOD 27','Tenations Global Sdn. Bhd.','BIMP','BIMP Beamodules (Level3)','1',0.00,0.00,''),(84,'BIC-BEAM-MATH L3-MOD 28','BEAMANUAL MATH L3-MOD 28','Tenations Global Sdn. Bhd.','BIMP','BIMP Beamodules (Level3)','1',0.00,0.00,''),(85,'BIC-BEAM-MATH L3-MOD 29','BEAMANUAL MATH L3-MOD 29','Tenations Global Sdn. Bhd.','BIMP','BIMP Beamodules (Level3)','1',0.00,0.00,''),(86,'BIC-BEAM-MATH L3-MOD 30','BEAMANUAL MATH L3-MOD 30','Tenations Global Sdn. Bhd.','BIMP','BIMP Beamodules (Level3)','1',0.00,0.00,''),(87,'BIC-BEAM-MATH L3-SUP 21','SUPPLEMENTARY SHEET for MOD 21','Q-dees Holdings Sdn. Bhd.','BIMP','BIMP Supplementary Sheets (Level3)','1',0.00,0.00,''),(88,'BIC-BEAM-MATH L3-SUP 22','SUPPLEMENTARY SHEET for MOD 22','Q-dees Holdings Sdn. Bhd.','BIMP','BIMP Supplementary Sheets (Level3)','1',0.00,0.00,''),(89,'BIC-BEAM-MATH L3-SUP 23','SUPPLEMENTARY SHEET for MOD 23','Tenations Global Sdn. Bhd.','BIMP','BIMP Supplementary Sheets (Level3)','1',0.00,0.00,''),(90,'BIC-BEAM-MATH L3-SUP 24','SUPPLEMENTARY SHEET for MOD 24','Q-dees Holdings Sdn. Bhd.','BIMP','BIMP Supplementary Sheets (Level3)','1',0.00,0.00,''),(91,'BIC-BEAM-MATH L3-SUP 25','SUPPLEMENTARY SHEET for MOD 25','Q-dees Holdings Sdn. Bhd.','BIMP','BIMP Supplementary Sheets (Level3)','1',0.00,0.00,''),(92,'BIC-BEAM-MATH L3-SUP 26','SUPPLEMENTARY SHEET for MOD 26','Q-dees Holdings Sdn. Bhd.','BIMP','BIMP Supplementary Sheets (Level3)','1',0.00,0.00,''),(93,'BIC-BEAM-MATH L3-SUP 27','SUPPLEMENTARY SHEET for MOD  27','Q-dees Holdings Sdn. Bhd.','BIMP','BIMP Supplementary Sheets (Level3)','1',0.00,0.00,''),(94,'BIC-BEAM-MATH L3-SUP 28','SUPPLEMENTARY SHEET for MOD 28','Q-dees Holdings Sdn. Bhd.','BIMP','BIMP Supplementary Sheets (Level3)','1',0.00,0.00,''),(95,'BIC-BEAM-MATH L3-SUP 29','SUPPLEMENTARY SHEET for MOD  29','Q-dees Holdings Sdn. Bhd.','BIMP','BIMP Supplementary Sheets (Level3)','1',0.00,0.00,''),(96,'BIC-BEAM-MATH L3-SUP 30','SUPPLEMENTARY SHEET for MOD 30','Q-dees Holdings Sdn. Bhd.','BIMP','BIMP Supplementary Sheets (Level3)','1',0.00,0.00,''),(97,'BIC-BEAM-MATH L4-MOD 31','BEAMANUAL MATH L4-MOD 31','Tenations Global Sdn. Bhd.','BIMP','BIMP Beamodules (Level4)','1',0.00,0.00,''),(98,'BIC-BEAM-MATH L4-MOD 32','BEAMANUAL MATH L4-MOD 32','Tenations Global Sdn. Bhd.','BIMP','BIMP Beamodules (Level4)','1',0.00,0.00,''),(99,'BIC-BEAM-MATH L4-MOD 33','BEAMANUAL MATH L4-MOD 33','Tenations Global Sdn. Bhd.','BIMP','BIMP Beamodules (Level4)','1',0.00,0.00,''),(100,'BIC-BEAM-MATH L4-MOD 34','BEAMANUAL MATH L4-MOD 34','Tenations Global Sdn. Bhd.','BIMP','BIMP Beamodules (Level4)','1',0.00,0.00,''),(101,'BIC-BEAM-MATH L4-MOD 35','BEAMANUAL MATH L4-MOD 35','Tenations Global Sdn. Bhd.','BIMP','BIMP Beamodules (Level4)','1',0.00,0.00,''),(102,'BIC-BEAM-MATH L4-MOD 36','BEAMANUAL MATH L4-MOD 36','Tenations Global Sdn. Bhd.','BIMP','BIMP Beamodules (Level4)','1',0.00,0.00,''),(103,'MS-IQ MATH L4-MOD 37',' IQ Math Manual L4- MOD 37','Mindspectrum Sdn.Bhd','BIMP','Q-dees IQ Math Modules Level 4','1',0.00,0.00,''),(104,'MS-IQ MATH L4-MOD 38',' IQ Math Manual L4- MOD 38','Mindspectrum Sdn.Bhd','BIMP','Q-dees IQ Math Modules Level 4','1',0.00,0.00,''),(105,'MS-IQ MATH L4-MOD 39',' IQ Math Manual L4- MOD 39','Mindspectrum Sdn.Bhd','BIMP','Q-dees IQ Math Modules Level 4','1',0.00,0.00,''),(106,'MS-IQ MATH L4-MOD 40',' IQ Math Manual L4- MOD 40','Mindspectrum Sdn.Bhd','BIMP','Q-dees IQ Math Modules Level 4','1',0.00,0.00,''),(107,'MS-IQ MATH L5-MOD 41',' IQ Math Manual L5- MOD 41','Mindspectrum Sdn.Bhd','BIMP','Q-dees IQ Math Modules Level 5','1',0.00,0.00,''),(108,'MS-IQ MATH L5-MOD 42',' IQ Math Manual L5- MOD 42','Mindspectrum Sdn.Bhd','BIMP','Q-dees IQ Math Modules Level 5','1',0.00,0.00,''),(109,'MS-IQ MATH L5-MOD 43',' IQ Math Manual L5- MOD 43','Mindspectrum Sdn.Bhd','BIMP','Q-dees IQ Math Modules Level 5','1',0.00,0.00,''),(110,'MS-IQ MATH L5-MOD 45',' IQ Math Manual L5- MOD 45','Mindspectrum Sdn.Bhd','BIMP','Q-dees IQ Math Modules Level 5','1',0.00,0.00,''),(111,'MS-IQ MATH L5-MOD 46',' IQ Math Manual L5- MOD 46','Mindspectrum Sdn.Bhd','BIMP','Q-dees IQ Math Modules Level 5','1',0.00,0.00,''),(112,'MS-IQ MATH L5-MOD 47',' IQ Math Manual L5- MOD 47','Mindspectrum Sdn.Bhd','BIMP','Q-dees IQ Math Modules Level 5','1',0.00,0.00,''),(113,'MS-IQ MATH L5-MOD 48',' IQ Math Manual L5- MOD 48','Mindspectrum Sdn.Bhd','BIMP','Q-dees IQ Math Modules Level 5','1',0.00,0.00,''),(114,'MS-IQ MATH L5-MOD 49',' IQ Math Manual L5- MOD 49','Mindspectrum Sdn.Bhd','BIMP','Q-dees IQ Math Modules Level 5','1',0.00,0.00,''),(115,'MS-IQ MATH L5-MOD 50',' IQ Math Manual L5- MOD 50','Mindspectrum Sdn.Bhd','BIMP','Q-dees IQ Math Modules Level 5','1',0.00,0.00,''),(116,'123445','product 1','Mindspectrum Sdn.Bhd','IE','International English Modules (Level1)','1',120.00,0.00,'');
/*!40000 ALTER TABLE `product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_course`
--

DROP TABLE IF EXISTS `product_course`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_course` (
  `id` int(100) unsigned NOT NULL AUTO_INCREMENT,
  `product_code` varchar(100) NOT NULL,
  `course_id` int(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_code_course_id` (`product_code`,`course_id`)
) ENGINE=MyISAM AUTO_INCREMENT=119 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_course`
--

LOCK TABLES `product_course` WRITE;
/*!40000 ALTER TABLE `product_course` DISABLE KEYS */;
INSERT INTO `product_course` VALUES (2,'INT-ENG.BRIDGING-PROGM',0),(117,'INT-ENG GL 1-MO 01',81),(4,'INT-ENG GL 1-MOD 06',86),(5,'INT-ENG GL 1-MOD 02',82),(6,'INT-ENG GL 1-MOD 07',87),(7,'INT-ENG GL 1-MOD 03',83),(8,'INT-ENG GL 1-MOD 08',88),(9,'INT-ENG GL 1-MOD 04',84),(10,'INT-ENG GL 1-MOD 09',89),(11,'INT-ENG GL 1-MOD 05',85),(12,'INT-ENG GL 1-MOD 10',90),(13,'INT-ENG GL 2-MOD 11',91),(14,'INT-ENG GL 2-MOD 16',96),(15,'INT-ENG GL 2-MOD 12',92),(16,'INT-ENG GL 2-MOD 17',97),(17,'INT-ENG GL 2-MOD 13',93),(18,'INT-ENG GL 2-MOD 18',98),(19,'INT-ENG GL 2-MOD 14',94),(20,'INT-ENG GL 2-MOD 19',99),(21,'INT-ENG GL 2-MOD 15',95),(22,'INT-ENG GL 2-MOD 20',100),(23,'BIC-BEAM-ENG L3-MOD 21',11),(24,'BIC-BEAM-ENG L3-MOD 22',12),(25,'BIC-BEAM-ENG L3-MOD 23',13),(26,'BIC-BEAM-ENG L3-MOD 24',14),(27,'BIC-BEAM-ENG L3-MOD 25',15),(28,'BIC-BEAM-ENG L3-MOD 26',16),(29,'BIC-BEAM-ENG L3-MOD 27',17),(30,'BIC-BEAM-ENG L3-MOD 28',18),(31,'BIC-BEAM-ENG L3-MOD 29',19),(32,'BIC-BEAM-ENG L3-MOD 30',20),(33,'BIC-BEAM-ENG L4-MOD 31',21),(34,'BIC-BEAM-ENG L4-MOD 32',22),(35,'BIC-BEAM-ENG L4-MOD 33',23),(36,'BIC-BEAM-ENG L4-MOD 34',24),(37,'BIC-BEAM-ENG L4-MOD 35',25),(38,'BIC-BEAM-ENG L4-MOD 36',26),(39,'BIC-BEAM-ENG L4-MOD 37',27),(40,'BIC-BEAM-ENG L4-MOD 38',28),(41,'BIC-BEAM-ENG L4-MOD 39',29),(42,'BIC-BEAM-ENG L4-MOD 40',30),(43,'BIC-BEAM-MATH L1-MOD 01',31),(44,'BIC-BEAM-MATH L1-SUP 01',31),(45,'BIC-BEAM-MATH L1-MOD 02',32),(46,'BIC-BEAM-MATH L1-MOD 03',33),(47,'BIC-BEAM-MATH L1-MOD 04',34),(48,'BIC-BEAM-MATH L1-SUP 04',34),(49,'BIC-BEAM-MATH L1-MOD 05',35),(50,'BIC-BEAM-MATH L1-SUP 05',35),(51,'BIC-BEAM-MATH L1-MOD 06',36),(52,'BIC-BEAM-MATH L1-MOD 07',37),(53,'BIC-BEAM-MATH L1-MOD 08',38),(54,'BIC-BEAM-MATH L1-SUP 08',38),(55,'BIC-BEAM-MATH L1-MOD 09',39),(56,'BIC-BEAM-MATH L1-MOD 10',40),(57,'BIC-BEAM-MATH L2-MOD 11',41),(58,'BIC-BEAM-MATH L2-SUP 11',41),(59,'BIC-BEAM-MATH L2-MOD 12',42),(60,'BIC-BEAM-MATH L2-SUP 12',42),(61,'BIC-BEAM-MATH L2-MOD 13',43),(62,'BIC-BEAM-MATH L2-SUP 13',43),(63,'BIC-BEAM-MATH L2-MOD 14',44),(64,'BIC-BEAM-MATH L2-SUP 14',44),(65,'BIC-BEAM-MATH L2-MOD 15',45),(66,'BIC-BEAM-MATH L2-MOD 16',46),(67,'BIC-BEAM-MATH L2-SUP 16',46),(68,'BIC-BEAM-MATH L2-MOD 17',47),(69,'BIC-BEAM-MATH L2-MOD 18',48),(70,'BIC-BEAM-MATH L2-MOD 19',49),(71,'BIC-BEAM-MATH L2-SUP 19',49),(72,'BIC-BEAM-MATH L2-MOD 20',50),(73,'BIC-BEAM-MATH L2-SUP 20',50),(74,'BIC-BEAM-MATH L2-SUP 15',45),(75,'BIC-BEAM-MATH L2-SUP 17',47),(76,'BIC-BEAM-MATH L2-SUP 18',48),(77,'BIC-BEAM-MATH L3-MOD 21',51),(78,'BIC-BEAM-MATH L3-MOD 22',52),(79,'BIC-BEAM-MATH L3-MOD 23',53),(80,'BIC-BEAM-MATH L3-MOD 24',54),(81,'BIC-BEAM-MATH L3-MOD 25',55),(82,'BIC-BEAM-MATH L3-MOD 26',56),(83,'BIC-BEAM-MATH L3-MOD 27',57),(84,'BIC-BEAM-MATH L3-MOD 28',58),(85,'BIC-BEAM-MATH L3-MOD 29',59),(86,'BIC-BEAM-MATH L3-MOD 30',60),(87,'BIC-BEAM-MATH L3-SUP 21',51),(88,'BIC-BEAM-MATH L3-SUP 22',52),(89,'BIC-BEAM-MATH L3-SUP 23',53),(90,'BIC-BEAM-MATH L3-SUP 24',54),(91,'BIC-BEAM-MATH L3-SUP 25',55),(92,'BIC-BEAM-MATH L3-SUP 26',56),(93,'BIC-BEAM-MATH L3-SUP 27',57),(94,'BIC-BEAM-MATH L3-SUP 28',58),(95,'BIC-BEAM-MATH L3-SUP 29',59),(96,'BIC-BEAM-MATH L3-SUP 30',60),(97,'BIC-BEAM-MATH L4-MOD 31',61),(98,'BIC-BEAM-MATH L4-MOD 32',62),(99,'BIC-BEAM-MATH L4-MOD 33',63),(100,'BIC-BEAM-MATH L4-MOD 34',24),(101,'BIC-BEAM-MATH L4-MOD 35',65),(102,'BIC-BEAM-MATH L4-MOD 36',66),(103,'MS-IQ MATH L4-MOD 37',67),(104,'MS-IQ MATH L4-MOD 38',68),(105,'MS-IQ MATH L4-MOD 39',69),(106,'MS-IQ MATH L4-MOD 40',70),(107,'MS-IQ MATH L5-MOD 41',71),(108,'MS-IQ MATH L5-MOD 42',72),(109,'MS-IQ MATH L5-MOD 43',73),(110,'MS-IQ MATH L5-MOD 45',75),(111,'MS-IQ MATH L5-MOD 46',76),(112,'MS-IQ MATH L5-MOD 47',77),(113,'MS-IQ MATH L5-MOD 48',78),(114,'MS-IQ MATH L5-MOD 49',79),(115,'MS-IQ MATH L5-MOD 50',80),(118,'123445',2);
/*!40000 ALTER TABLE `product_course` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stock_adjustment`
--

DROP TABLE IF EXISTS `stock_adjustment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stock_adjustment` (
  `id` int(100) unsigned NOT NULL AUTO_INCREMENT,
  `centre_code` varchar(50) NOT NULL,
  `product_code` varchar(100) NOT NULL,
  `adjust_qty` double(12,2) NOT NULL DEFAULT '0.00',
  `effective_date` date NOT NULL,
  `adjusted_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `adjusted_by` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_adjustment`
--

LOCK TABLES `stock_adjustment` WRITE;
/*!40000 ALTER TABLE `stock_adjustment` DISABLE KEYS */;
INSERT INTO `stock_adjustment` VALUES (8,'MYQWEC1C10001','BIC-BEAM-ENG L3-MOD 21',5.00,'2019-03-13','2019-03-13 15:23:42','super'),(9,'MYQWEC1C10001','BIC-BEAM-ENG L3-MOD 23',3.00,'2019-03-15','2019-03-15 14:58:54','super'),(10,'MYQWEC1C10001','BIC-BEAM-ENG L3-MOD 21',-1.00,'2019-02-27','2019-03-25 11:22:50','super'),(11,'MYQWEC1C10001','BIC-BEAM-MATH L1-SUP 01',1.00,'2019-03-25','2019-03-25 15:01:09','super'),(12,'MYQWEC1C10001','BIC-BEAM-ENG L3-MOD 21',1.00,'2019-03-27','2019-03-28 14:45:33','super'),(13,'MYQWEC1C10001','123445',1.00,'2019-03-28','2019-03-29 13:52:50','super'),(14,'MYQWEC1C10001','BIC-BEAM-ENG L3-MOD 21',1.00,'2019-04-02','2019-04-03 15:27:01','super');
/*!40000 ALTER TABLE `stock_adjustment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `student`
--

DROP TABLE IF EXISTS `student`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `student` (
  `id` int(100) unsigned NOT NULL AUTO_INCREMENT,
  `student_code` varchar(50) NOT NULL,
  `centre_code` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `form_serial_no` varchar(100) NOT NULL,
  `country` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `birth_cert_no` varchar(100) NOT NULL,
  `dob` date NOT NULL,
  `nationality` varchar(100) NOT NULL,
  `registered_on` datetime NOT NULL,
  `start_date_at_centre` date NOT NULL,
  `sex` varchar(100) NOT NULL,
  `nric_no` varchar(100) NOT NULL,
  `age` double(12,0) NOT NULL,
  `add1` varchar(100) NOT NULL,
  `add2` varchar(100) NOT NULL,
  `add3` varchar(100) NOT NULL,
  `add4` varchar(100) NOT NULL,
  `race` varchar(100) NOT NULL,
  `religion` varchar(100) NOT NULL,
  `student_status` varchar(100) NOT NULL,
  `primary_tel` varchar(100) NOT NULL,
  `primary_email` varchar(100) NOT NULL,
  `health_problem` varchar(200) NOT NULL,
  `remarks` varchar(200) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `student_code` (`student_code`)
) ENGINE=MyISAM AUTO_INCREMENT=43 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student`
--

LOCK TABLES `student` WRITE;
/*!40000 ALTER TABLE `student` DISABLE KEYS */;
INSERT INTO `student` VALUES (30,'MYQWEC1C10001-0001','MYQWEC1C10001','ST Ooi','','Malaysia','','','0000-00-00','','0000-00-00 00:00:00','0000-00-00','','',0,'','','','','','','','','','','','2019-02-25 08:46:54',1),(31,'MYQWEC1C10001-0002','MYQWEC1C10001','cy','01','Malaysia','Selangor','02','2009-03-03','Malaysian','0000-00-00 00:00:00','2019-02-26','M','090303-03-5233',9,'111','aaa','aaa','aaa','Chinese','Christian','A','01934324443','cy@gmail.com','good','no','2019-02-26 09:16:13',1),(32,'MYQWEC1C10001-0003','MYQWEC1C10001','chan xian ze','123456','Malaysia','Kelantan','123456','1995-05-11','Malaysian','0000-00-00 00:00:00','2019-02-26','M','950511105787',23,'65, jalan medan 12, Taman medan 12','','','','Chinese','Buddhism','A','1137252347','xianzechan@gmail.com','good','nothing','2019-02-27 06:48:36',1),(33,'MYQWEC1C10004-0001','MYQWEC1C10004','chan','1','Malaysia','Selangor','123','2016-02-29','Malaysian','0000-00-00 00:00:00','2019-02-28','M','950511105787',2,'test','test','test','','Chinese','Others','A','0183115659','xianzechan@gmail.com','bad','-','2019-02-28 09:03:01',0),(34,'MMQWEM1C10001-0001','MMQWEM1C10001','david lim','','Myanmar','','','0000-00-00','','0000-00-00 00:00:00','0000-00-00','','1223456789',0,'','','','','','','A','','','','','2019-03-01 04:03:18',0),(35,'MYQWEC1C10001-0004','MYQWEC1C10001','Elisha Kee','1234','Malaysia','Wilayah Persekutuan Kuala Lumpur','n/a','2016-03-01','Malaysian','0000-00-00 00:00:00','2019-03-01','M','38432-14-2343',3,'Q-dees Worldwide Edusystems','No. 6-4, Level 4, Jalan SS6/6 Kelana Jaya, ','47301 Petaling Jaya, Selangor, ','Malaysia.','Chinese','Christian','A','01234345432','elisha@q-dees.com','n/a','','2019-03-12 06:44:39',0),(39,'MYQWEC1C10001-0005','MYQWEC1C10001','ST Ooi','','','','','0000-00-00','','0000-00-00 00:00:00','0000-00-00','','',0,'','','','','','','I','','','','','2019-03-15 11:33:56',1),(41,'MYQWEC1C10001-0007','MYQWEC1C10001','Tilly Lilly','','Malaysia','Selangor','','2015-06-07','Malaysian','0000-00-00 00:00:00','2019-04-01','F','150607012342',3,'','','','','Malay','Islam','A','','','','','2019-03-18 07:51:56',0),(42,'MYQWEC1C10001-0008','MYQWEC1C10001','Harmonie ','','Malaysia','','','1999-03-12','Malaysian','0000-00-00 00:00:00','0000-00-00','F','900819146598',20,'','','','','Iban','Buddhism','I','0122935800','','','','2019-03-25 06:31:01',1);
/*!40000 ALTER TABLE `student` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `student_emergency_contacts`
--

DROP TABLE IF EXISTS `student_emergency_contacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `student_emergency_contacts` (
  `id` int(100) unsigned NOT NULL AUTO_INCREMENT,
  `student_code` varchar(50) NOT NULL,
  `contact_type` varchar(50) NOT NULL,
  `email` varchar(200) NOT NULL,
  `occupation` varchar(200) NOT NULL,
  `education_level` varchar(200) NOT NULL,
  `company_no` varchar(200) NOT NULL,
  `office_no` varchar(200) NOT NULL,
  `can_pick_up` int(4) NOT NULL DEFAULT '0',
  `vehicle_no` varchar(50) NOT NULL,
  `remarks` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student_emergency_contacts`
--

LOCK TABLES `student_emergency_contacts` WRITE;
/*!40000 ALTER TABLE `student_emergency_contacts` DISABLE KEYS */;
INSERT INTO `student_emergency_contacts` VALUES (3,'MYQWEC1C10001-0001','Father','cyy@gmail.com','Agriculture','','03555645','04546546',1,'vcg1234','no'),(4,'MYQWEC1C10001-0002','Father','cyy@gmail.com','Agriculture','','03434434','04324324',1,'bbb444','no'),(6,'MYQWEC1C10001-0003','Mother','xianzechan@gmail.com','Agriculture','','123456','123456',1,'123456','yes'),(7,'MYQWEC1C10004-0001','Father','xianzechan@gmail.com','Information Technology','','0183115659','0183115659',1,'WVJ7680','-'),(14,'MYQWEC1C10001-0006','Father','patrick@webhyper.com','Retail','','dsfsdf','sdfsdf',1,'435435345','faasf'),(17,'MYQWEC1C10001-0004','Father','mrkee@q-dees.com','Education','','03445332','03222112',1,'3044d',''),(18,'MYQWEC1C10001-0008','Mother','','','','','42764646868',1,'','');
/*!40000 ALTER TABLE `student_emergency_contacts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tmp_stock`
--

DROP TABLE IF EXISTS `tmp_stock`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tmp_stock` (
  `id` int(100) unsigned NOT NULL AUTO_INCREMENT,
  `session_id` varchar(100) NOT NULL,
  `product_code` varchar(100) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `trans_date` date NOT NULL,
  `description` varchar(200) NOT NULL,
  `in` double(12,2) NOT NULL,
  `out` double(12,2) NOT NULL,
  `bal` double(12,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `session_id` (`session_id`,`product_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tmp_stock`
--

LOCK TABLES `tmp_stock` WRITE;
/*!40000 ALTER TABLE `tmp_stock` DISABLE KEYS */;
/*!40000 ALTER TABLE `tmp_stock` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tmp_student`
--

DROP TABLE IF EXISTS `tmp_student`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tmp_student` (
  `id` int(100) unsigned NOT NULL AUTO_INCREMENT,
  `student_code` varchar(50) NOT NULL,
  `centre_code` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `form_serial_no` varchar(100) NOT NULL,
  `country` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `birth_cert_no` varchar(100) NOT NULL,
  `dob` date NOT NULL,
  `nationality` varchar(100) NOT NULL,
  `registered_on` datetime NOT NULL,
  `start_date_at_centre` date NOT NULL,
  `sex` varchar(100) NOT NULL,
  `nric_no` varchar(100) NOT NULL,
  `age` double(12,0) NOT NULL,
  `add1` varchar(100) NOT NULL,
  `add2` varchar(100) NOT NULL,
  `add3` varchar(100) NOT NULL,
  `add4` varchar(100) NOT NULL,
  `race` varchar(100) NOT NULL,
  `religion` varchar(100) NOT NULL,
  `student_status` varchar(100) NOT NULL,
  `primary_tel` varchar(100) NOT NULL,
  `primary_email` varchar(100) NOT NULL,
  `health_problem` varchar(200) NOT NULL,
  `remarks` varchar(200) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `student_code` (`student_code`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tmp_student`
--

LOCK TABLES `tmp_student` WRITE;
/*!40000 ALTER TABLE `tmp_student` DISABLE KEYS */;
/*!40000 ALTER TABLE `tmp_student` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tmp_student_emergency_contacts`
--

DROP TABLE IF EXISTS `tmp_student_emergency_contacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tmp_student_emergency_contacts` (
  `id` int(100) unsigned NOT NULL AUTO_INCREMENT,
  `student_code` varchar(50) NOT NULL,
  `contact_type` varchar(50) NOT NULL,
  `email` varchar(200) NOT NULL,
  `occupation` varchar(200) NOT NULL,
  `education_level` varchar(200) NOT NULL,
  `company_no` varchar(200) NOT NULL,
  `office_no` varchar(200) NOT NULL,
  `can_pick_up` int(4) NOT NULL DEFAULT '0',
  `vehicle_no` varchar(50) NOT NULL,
  `remarks` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tmp_student_emergency_contacts`
--

LOCK TABLES `tmp_student_emergency_contacts` WRITE;
/*!40000 ALTER TABLE `tmp_student_emergency_contacts` DISABLE KEYS */;
/*!40000 ALTER TABLE `tmp_student_emergency_contacts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(100) unsigned NOT NULL AUTO_INCREMENT,
  `user_name` varchar(50) NOT NULL,
  `user_type` varchar(50) NOT NULL COMMENT 'S-Super Admin|A-Admin|O-Operator',
  `password` varchar(100) NOT NULL,
  `centre_code` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `tel` varchar(100) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_active` tinyint(2) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_name_centre_code` (`user_name`)
) ENGINE=MyISAM AUTO_INCREMENT=42 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (34,'chan','S','*E7D2C6F90A3877EB57AAE9A8AA25BA6208921D72','MYQWEC10001','Chan Xian Ze','hello@fube.com.my','0183115659','2019-02-28 05:26:29',1),(4,'super','S','*1F9CEA32339C6C818E25B325401C88389EA7571E','','ST Ooi','patrick@webhyper.com','0124868823','2018-08-12 16:17:11',1),(40,'test1','A','*1F9CEA32339C6C818E25B325401C88389EA7571E','MYQWEC1C10001','ST Ooi','qdeescholars.pandanindah@gmail.com','011-23813341','2019-03-13 00:43:37',1),(41,'admin 2','O','*6BB4837EB74329105EE4568DDA7DC67ED2CA2AD9','MYQWEC1C10001','Amossss','qbc@example.com','0123456789','2019-03-15 07:36:09',1),(35,'chan1','S','*785AB3BAE062C2A8684DEFE546496DE29A7934D9','MYQWEC10001','chan xian ze','xianzechan@gmail.com','0183115659','2019-02-28 08:27:05',1),(36,'SS17','A','*E240A5691103B33BEFE930D051BA76C4BCB96620','MYQWEC1C10004','test','test@gmail.com','test','2019-02-28 08:49:51',1),(39,'Pandan Indah','A','*6BB4837EB74329105EE4568DDA7DC67ED2CA2AD9','MYQWEC1C10001','Grace','qdeescholars.pandanindah@gmail.com','011-23813341','2019-03-12 06:35:50',1);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_right`
--

DROP TABLE IF EXISTS `user_right`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_right` (
  `id` int(100) unsigned NOT NULL AUTO_INCREMENT,
  `user_name` varchar(50) NOT NULL,
  `right` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1444 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_right`
--

LOCK TABLES `user_right` WRITE;
/*!40000 ALTER TABLE `user_right` DISABLE KEYS */;
INSERT INTO `user_right` VALUES (617,'chan1','CentreEdit'),(1390,'super','FinanceApproveEdit'),(607,'chan','DeliveryEdit'),(606,'chan','PackedEdit'),(605,'chan','LogisticApproveEdit'),(1389,'super','ConfirmPaymentEdit'),(1388,'super','DeliveryEdit'),(1387,'super','PackedEdit'),(1386,'super','LogisticApproveEdit'),(1385,'super','AcknowledgeOrderEdit'),(1384,'super','FinanceApproveDefectiveEdit'),(1383,'super','ConfirmPaymentDefectiveEdit'),(1381,'super','PackedDefectiveEdit'),(1382,'super','DeliveryDefectiveEdit'),(604,'chan','AcknowledgeOrderEdit'),(603,'chan','OrderStatusEdit'),(602,'chan','UserPasswordEdit'),(601,'chan','UserRightsEdit'),(600,'chan','UserEdit'),(599,'chan','ProductEdit'),(598,'chan','ClassEdit'),(597,'chan','MasterRecordsEdit'),(596,'chan','CentreEdit'),(595,'chan','MasterEdit'),(776,'test','DeclarationEdit'),(775,'test','ExportContactEdit'),(1426,'test1','DefectiveStatusView'),(1425,'test1','DefectiveProductEdit'),(1424,'test1','ReportingView'),(1423,'test1','DeclarationEdit'),(1422,'test1','ExportContactEdit'),(774,'test','OrderStatusView'),(772,'test','StockAdjustmentEdit'),(1380,'super','LogisticApproveDefectiveEdit'),(773,'test','OrderEdit'),(771,'test','StockBalancesView'),(770,'test','CreditNoteEdit'),(769,'test','PointOfSalesEdit'),(768,'test','SalesEdit'),(767,'test','VisitorEdit'),(766,'test','UserRightsEdit'),(765,'test','UserEdit'),(764,'test','AllocationEdit'),(763,'test','StudentEdit'),(618,'chan1','ClassEdit'),(619,'chan1','ProductView'),(620,'chan1','UserEdit'),(621,'chan1','UserRightsEdit'),(622,'chan1','UserPasswordEdit'),(623,'chan1','OrderStatusEdit'),(624,'chan1','OrderStatusView'),(625,'SS17','StudentEdit'),(626,'SS17','AllocationEdit'),(627,'SS17','UserPasswordEdit'),(628,'SS17','VisitorEdit'),(629,'SS17','SalesEdit'),(630,'SS17','PointOfSalesEdit'),(631,'SS17','CreditNoteEdit'),(632,'SS17','StockBalancesEdit'),(633,'SS17','StockAdjustmentEdit'),(634,'SS17','OrderEdit'),(635,'SS17','OrderStatusEdit'),(636,'SS17','ExportContactEdit'),(660,'test1234','StockBalancesEdit'),(659,'test1234','CreditNoteEdit'),(658,'test1234','PointOfSalesEdit'),(657,'test1234','SalesEdit'),(656,'test1234','VisitorEdit'),(655,'test1234','UserPasswordEdit'),(654,'test1234','UserRightsEdit'),(653,'test1234','UserEdit'),(652,'test1234','AllocationEdit'),(651,'test1234','StudentEdit'),(661,'test1234','StockAdjustmentEdit'),(662,'test1234','OrderEdit'),(663,'test1234','OrderStatusEdit'),(664,'test1234','ExportContactEdit'),(665,'test1234','DeclarationEdit'),(1421,'test1','OrderStatusView'),(1420,'test1','OrderEdit'),(1419,'test1','StockBalancesView'),(1418,'test1','PointOfSalesEdit'),(1417,'test1','SalesView'),(1416,'test1','SalesEdit'),(1443,'Pandan Indah','DefectiveStatusView'),(1434,'Pandan Indah','SalesEdit'),(1435,'Pandan Indah','PointOfSalesEdit'),(1436,'Pandan Indah','StockBalancesView'),(1437,'Pandan Indah','OrderEdit'),(1438,'Pandan Indah','OrderStatusView'),(1439,'Pandan Indah','ExportContactEdit'),(1440,'Pandan Indah','DeclarationEdit'),(1441,'Pandan Indah','ReportingView'),(1377,'super','DefectiveStatusEdit'),(1442,'Pandan Indah','DefectiveProductEdit'),(1433,'Pandan Indah','VisitorEdit'),(1432,'Pandan Indah','UserPasswordEdit'),(1271,'admin 2','OrderStatusView'),(1270,'admin 2','OrderView'),(1269,'admin 2','StockBalancesView'),(1268,'admin 2','PointOfSalesView'),(1267,'admin 2','VisitorView'),(1266,'admin 2','UserPasswordView'),(1431,'Pandan Indah','UserRightsView'),(1430,'Pandan Indah','UserView'),(1415,'test1','VisitorEdit'),(1414,'test1','UserRightsView'),(1429,'Pandan Indah','AllocationEdit'),(1413,'test1','UserEdit'),(1379,'super','AcknowledgeDefectiveEdit'),(1378,'super','StockAdjustmentEdit'),(1376,'super','OrderStatusEdit'),(1375,'super','SalesView'),(1374,'super','UserPasswordEdit'),(1373,'super','UserRightsEdit'),(1372,'super','UserEdit'),(1371,'super','ProductEdit'),(1370,'super','ClassEdit'),(1369,'super','MasterRecordsEdit'),(1368,'super','CentreEdit'),(1367,'super','MasterEdit'),(1428,'Pandan Indah','StudentEdit'),(1272,'admin 2','ExportContactView'),(1265,'admin 2','AllocationView'),(1264,'admin 2','StudentView'),(1273,'admin 2','DeclarationView'),(1412,'test1','AllocationEdit'),(1411,'test1','StudentEdit'),(1410,'test1','DashboardView'),(1427,'Pandan Indah','DashboardView'),(1391,'super','KIVEdit'),(1392,'super','ReportingView');
/*!40000 ALTER TABLE `user_right` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `visitor`
--

DROP TABLE IF EXISTS `visitor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `visitor` (
  `id` int(100) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `tel` varchar(100) NOT NULL,
  `age` double(12,2) NOT NULL,
  `email` varchar(100) NOT NULL,
  `find_out` varchar(100) NOT NULL,
  `close` int(4) NOT NULL DEFAULT '0',
  `centre_code` varchar(50) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `visitor`
--

LOCK TABLES `visitor` WRITE;
/*!40000 ALTER TABLE `visitor` DISABLE KEYS */;
INSERT INTO `visitor` VALUES (1,'ken','019-544234432',7.00,'ken@gmail.com','Banner/Bunting',0,'MYQWEC1C10001','2019-02-25 05:48:38'),(2,'daniel','0153434324',22.00,'daniel@gmail.com','Magazine',0,'MYQWEC1C10001','2019-02-26 09:19:26'),(3,'test','123456789',3.00,'xianzechan@gmail.com','Exhibition',0,'MYQWEC1C10001','2019-02-27 06:48:55'),(4,'chan','123456789',3.00,'xianzechan@gmail.com','Exhibition',0,'MYQWEC1C10004','2019-02-28 08:58:07'),(5,'Amos father','01739232343',35.00,'amosintech@gmail.com','Neighbourhood',0,'MYQWEC1C10001','2019-03-12 06:57:08'),(6,'Maximillion Power','0122057971',28.00,'heaven@hotmail.com.my','Website',0,'MYQWEC1C10001','2019-03-12 06:57:48');
/*!40000 ALTER TABLE `visitor` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-04-18 11:53:33
