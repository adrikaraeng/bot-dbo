-- MariaDB dump 10.17  Distrib 10.4.13-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: dbo_bot
-- ------------------------------------------------------
-- Server version	10.4.13-MariaDB

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
-- Table structure for table `app_version`
--

DROP TABLE IF EXISTS `app_version`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `app_version` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `version` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_version`
--

LOCK TABLES `app_version` WRITE;
/*!40000 ALTER TABLE `app_version` DISABLE KEYS */;
INSERT INTO `app_version` VALUES (1,'PARTNER'),(2,'3.00'),(3,'3.10'),(4,'3.70'),(5,'3.80'),(6,'3.81'),(7,'3.85');
/*!40000 ALTER TABLE `app_version` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `backend`
--

DROP TABLE IF EXISTS `backend`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `backend` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `nama_backend` varchar(25) NOT NULL,
  `id_sub_kategori` int(5) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=192 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `backend`
--

LOCK TABLES `backend` WRITE;
/*!40000 ALTER TABLE `backend` DISABLE KEYS */;
INSERT INTO `backend` VALUES (1,'DBO',1),(2,'DBO',2),(3,'DIT / Neuron',3),(4,'DBO',4),(5,'DBO',5),(6,'DIT / Neuron',6),(7,'DBO',7),(8,'DBO',8),(9,'DBO',9),(10,'DBO',10),(11,'DBO',11),(12,'DBO',12),(13,'DBO',13),(14,'DBO',14),(15,'DBO',15),(16,'DBO',16),(17,'DBO',17),(18,'DBO',18),(19,'DBO',19),(20,'DBO',20),(21,'DBO',21),(22,'DBO',22),(23,'DBO',23),(24,'DBO',24),(25,'DBO',25),(26,'DBO',26),(27,'DBO',27),(28,'DBO',28),(29,'DBO',29),(30,'DBO',30),(31,'DBO ',31),(32,'DBO ',32),(33,'DBO ',33),(34,'DBO ',34),(35,'DBO ',35),(36,'DBO ',36),(37,'DBO ',37),(38,'TVV',38),(39,'TVV',39),(40,'TVV',40),(41,'TVV',41),(42,'TVV',42),(43,'T-Money',43),(44,'T-Money',44),(45,'T-Money',45),(46,'T-Money',46),(47,'T-Money',47),(48,'DBO',48),(49,'DBO',49),(50,'DBO',50),(51,'DBO',51),(52,'DBO',52),(53,'DBO',53),(54,'DBO',54),(55,'DIT / Neuron',55),(56,'DBO',56),(57,'DBO',57),(58,'DBO',58),(59,'DBO',59),(60,'DBO',60),(61,'DIT / Neuron',9),(62,'DIT / Neuron',10),(63,'DIT',11),(64,'DIT',12),(65,'DIT',13),(66,'DIT',14),(67,'DIT',15),(68,'DIT',17),(69,'DIT',18),(70,'DIT',19),(71,'DIT',20),(72,'DIT',21),(73,'DIT',22),(74,'DIT',23),(75,'DIT',24),(76,'DIT',25),(77,'DIT',26),(78,'DIT',27),(79,'DIT',28),(80,'DIT',29),(81,'DIT',31),(82,'DIT',32),(83,'DIT',33),(84,'DIT',34),(85,'DIT',35),(86,'DIT',36),(87,'DIT',37),(88,'CRL',48),(89,'CRL',49),(90,'CRL',50),(91,'FCC',53),(92,'FCC',54),(93,'DIT',56),(94,'DIT',57),(124,'T-Money',17),(125,'T-Money',18),(126,'T-Money',19),(127,'T-Money',20),(128,'T-Money',21),(129,'T-Money',22),(130,'T-Money',24),(131,'T-Money',25),(132,'T-Money',26),(133,'T-Money',27),(134,'T-Money',28),(135,'T-Money',29),(136,'Privy',53),(137,'Privy',54),(138,'T-Money',56),(139,'Finnet ',17),(140,'Finnet',18),(141,'Finnet',19),(142,'Finnet',20),(143,'Finnet',21),(144,'Finnet',22),(145,'Finnet',24),(146,'Finnet',25),(147,'Finnet',26),(148,'Finnet',27),(149,'Finnet',28),(150,'Finnet',29),(151,'Finnet',56),(152,'DBO',61),(153,'DIT',61),(154,'Finnet',61),(155,'DBO',6),(156,'DBO',62),(157,'DIT',62),(158,'DBO',63),(159,'DIT',63),(160,'Finnet',14),(161,'T-Money',14),(162,'DBO',64),(163,'DIT',64),(164,'Finnet',64),(165,'DBO',65),(166,'DIT',65),(167,'Finnet',65),(168,'Finnet',15),(169,'T-Money',15),(170,'Finnet',16),(171,'T-Money',16),(172,'DIT',16),(173,'DBO',66),(174,'DBO',67),(175,'DBO',68),(176,'DBO',69),(177,'DBO',70),(178,'DBO',71),(179,'DBO',72),(180,'Finnet',72),(181,'DE',72),(182,'FCC',72),(183,'DBO',73),(184,'Finnet',73),(185,'DE',73),(186,'FCC',73),(187,'DBO',74),(188,'DBO',55),(189,'DBO',75),(190,'Finnet',75),(191,'DIT',75);
/*!40000 ALTER TABLE `backend` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cases`
--

DROP TABLE IF EXISTS `cases`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cases` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(150) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `hp` varchar(15) DEFAULT NULL,
  `tiket` varchar(20) DEFAULT NULL,
  `pstn` varchar(20) DEFAULT NULL,
  `inet` varchar(20) DEFAULT NULL,
  `no_tiket` varchar(35) DEFAULT NULL,
  `app_version` varchar(20) DEFAULT NULL,
  `keluhan` text DEFAULT NULL,
  `tanggal_masuk` datetime DEFAULT NULL,
  `status_owner` enum('TO','On Progress','Closed','New') NOT NULL,
  `gambar` text DEFAULT NULL,
  `feedback` text DEFAULT NULL,
  `login` int(11) DEFAULT NULL,
  `source` varchar(100) DEFAULT NULL,
  `source_email` varchar(150) DEFAULT NULL,
  `kategori` int(11) DEFAULT NULL,
  `sub_kategori` int(11) DEFAULT NULL,
  `backend` int(11) DEFAULT NULL,
  `urgensi_status` varchar(200) DEFAULT NULL,
  `channel` int(11) DEFAULT NULL,
  `sub_channel` int(11) DEFAULT NULL,
  `feedback_gambar` text DEFAULT NULL,
  `telegram_id` varchar(50) DEFAULT NULL,
  `tanggal_closed` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cases`
--

LOCK TABLES `cases` WRITE;
/*!40000 ALTER TABLE `cases` DISABLE KEYS */;
INSERT INTO `cases` VALUES (1,'Reyhand','rey@gmail.com','08262773637','23120589','','112353738383','','3.85','Berhasil bayar tapi masih isolir','2020-07-28 06:59:37','TO','1.jpg','Dikoordinasikan degan pihak terkait',105626,'147','147@gmail.com',5,15,15,'Normal',2,12,'','777422931',NULL),(3,'Marny','marny@gmail.com','0837363738','19539299','0223737834','112373784447','','3.85','Gagal unsub seamless wifi.id','2020-07-28 07:12:39','On Progress',NULL,'asd',105626,'147','147@gmail.com',7,34,34,'Normal',2,13,'','777422931',NULL),(4,'Joko Widodo','bbs@yahoo.com','081909812298','95701198','02517551262','1928401283','IN3123149123','3.85','Mau nambah add on tapi error','2020-07-28 07:27:09','Closed',NULL,'KSKSKSKS',105626,'147','fahmuzaki@gmail.com',6,17,17,'Normal',2,8,'','536614236','2020-07-28 07:28:35');
/*!40000 ALTER TABLE `cases` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `channel`
--

DROP TABLE IF EXISTS `channel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `channel` (
  `id_channel` int(50) NOT NULL AUTO_INCREMENT,
  `nama_channel` varchar(100) NOT NULL,
  PRIMARY KEY (`id_channel`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `channel`
--

LOCK TABLES `channel` WRITE;
/*!40000 ALTER TABLE `channel` DISABLE KEYS */;
INSERT INTO `channel` VALUES (1,'Whatsapp'),(2,'Telegram'),(3,'Nossa');
/*!40000 ALTER TABLE `channel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kategori`
--

DROP TABLE IF EXISTS `kategori`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `kategori` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kategori`
--

LOCK TABLES `kategori` WRITE;
/*!40000 ALTER TABLE `kategori` DISABLE KEYS */;
INSERT INTO `kategori` VALUES (1,'Sign Up'),(2,'Log In'),(3,'Reset Akun'),(4,'Tambah No.Layanan (Mapping)'),(5,'Tagihan'),(6,'Transaksi Add On'),(7,'Unsubscribe'),(8,'Dompet myIndiHome'),(9,'POIN'),(10,'Order PSB'),(11,'Top Up Kuota'),(12,'Lapor Gangguan'),(13,'OOT'),(14,'FUP');
/*!40000 ALTER TABLE `kategori` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `list_progress_cases`
--

DROP TABLE IF EXISTS `list_progress_cases`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `list_progress_cases` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cases` int(11) DEFAULT NULL,
  `login` varchar(20) DEFAULT NULL,
  `feedback` text DEFAULT NULL,
  `feedback_gambar` text DEFAULT NULL,
  `status` enum('Closed','On Progress') DEFAULT NULL,
  `insert_date` datetime DEFAULT NULL,
  `backend` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_progress_in_cases` (`cases`),
  KEY `idx_progress_in_login` (`login`),
  KEY `idx_progress_in_backend` (`backend`),
  CONSTRAINT `fk_progress_in_backend` FOREIGN KEY (`backend`) REFERENCES `backend` (`id`),
  CONSTRAINT `fk_progress_in_cases` FOREIGN KEY (`cases`) REFERENCES `cases` (`id`),
  CONSTRAINT `fk_progress_in_login` FOREIGN KEY (`login`) REFERENCES `user` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `list_progress_cases`
--

LOCK TABLES `list_progress_cases` WRITE;
/*!40000 ALTER TABLE `list_progress_cases` DISABLE KEYS */;
INSERT INTO `list_progress_cases` VALUES (1,1,'105626','Dikoordinasikan degan pihak terkait','','On Progress','2020-07-28 06:59:37',15),(2,3,'105626','asd','','On Progress','2020-07-28 07:12:39',34),(3,4,'105626','KSKSKSKS','','On Progress','2020-07-28 07:27:09',17),(4,4,'105626','asdasfsa','','On Progress','2020-07-28 07:28:18',17),(5,4,'105626','close','','Closed','2020-07-28 07:28:35',68),(6,3,'105626','Testing send feedback','202007280832121595917932.jpg','On Progress','2020-07-28 08:32:12',34);
/*!40000 ALTER TABLE `list_progress_cases` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `progress_case`
--

DROP TABLE IF EXISTS `progress_case`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `progress_case` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `case` int(11) DEFAULT NULL,
  `feedback` text DEFAULT NULL,
  `feedback_gambar` text DEFAULT NULL,
  `login` int(11) DEFAULT NULL,
  `update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_case` (`case`),
  KEY `idx_login` (`login`),
  CONSTRAINT `fk_progress_case` FOREIGN KEY (`case`) REFERENCES `cases` (`id`),
  CONSTRAINT `fk_progress_login` FOREIGN KEY (`login`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `progress_case`
--

LOCK TABLES `progress_case` WRITE;
/*!40000 ALTER TABLE `progress_case` DISABLE KEYS */;
/*!40000 ALTER TABLE `progress_case` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `session_bot`
--

DROP TABLE IF EXISTS `session_bot`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `session_bot` (
  `my_session` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `session_bot`
--

LOCK TABLES `session_bot` WRITE;
/*!40000 ALTER TABLE `session_bot` DISABLE KEYS */;
/*!40000 ALTER TABLE `session_bot` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sub_channel`
--

DROP TABLE IF EXISTS `sub_channel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sub_channel` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `nama_sub_channel` varchar(100) DEFAULT NULL,
  `id_channel` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sub_channel`
--

LOCK TABLES `sub_channel` WRITE;
/*!40000 ALTER TABLE `sub_channel` DISABLE KEYS */;
INSERT INTO `sub_channel` VALUES (1,'Helpdesk myIndiHome',1),(2,'MYINDIHOME 3.0',1),(3,'myIH Ops - Operations',1),(4,'Nomor DBO [Japri] [WA]',1),(5,'DBO Bogor',1),(6,'myIndiHome Roll-Out-Team',1),(7,'myIndiHome Socmed',1),(8,'myIndiHome Prov Nasional',2),(9,'DBO - FCC - C4',2),(12,'0800-1NDIHOME',2),(13,'Nomor DBO [Japri] [TL]',2),(14,'Prov IndiHome [myIH-FCC-SCBE-NOSS]',2),(15,'Koordinasi DBO - Salper',2),(16,'147',3),(17,'Team Solution',3),(19,'HD MyIndiHome Cust',2),(20,'MyIndiHome Customer',3),(21,'Movin Assurance',2),(22,'Sosial Media',3),(23,'My IndiHome Plasa - DBO',2),(24,'ALL IN(MyIndiHome)',1),(25,'DSO - DBO myIndiHome',1),(26,'IndiHome Smart Helpdesk',2),(27,'Wifi.id Seamless',2),(28,'C4 [Japri]',2),(29,'Digital Outlet',2),(30,'Helpdesk MIC TR6',2),(31,'Treg 5 - DBO - SVM',1),(32,'Treg 6 - DBO - SVM 1',1),(33,'Deposit IndiHome TREG1',1),(34,'Deposit IndiHome TREG2',1),(35,'Deposit IndiHome TREG3',1),(36,'Deposit IndiHome TREG4',1),(37,'Deposit IndiHome TREG5',1),(38,'Deposit IndiHome TREG6',1),(39,'Deposit IndiHome TREG7',1),(40,'Koordinasi FCC - DBO - DE',1);
/*!40000 ALTER TABLE `sub_channel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sub_kategori`
--

DROP TABLE IF EXISTS `sub_kategori`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sub_kategori` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `sub_kategori` varchar(100) NOT NULL,
  `id_kategori` int(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sub_kategori`
--

LOCK TABLES `sub_kategori` WRITE;
/*!40000 ALTER TABLE `sub_kategori` DISABLE KEYS */;
INSERT INTO `sub_kategori` VALUES (1,'Tidak menerima kode OTP Sign Up',1),(2,'Email dan No.CP sudah terdaftar',1),(3,'Hapus akun myIH',1),(4,'Akun suspend',2),(5,'Tidak menerima kode OTP reset password',2),(6,'No.CP typo atau sudah terdaftar',2),(7,'Lupa Password',2),(8,'Cek no.HP atau email',2),(9,'Status PSB tidak update',3),(10,'Tampilan paket tidak update',3),(11,'Hapus Portofolio',4),(12,'Gagal verifikasi jaringan (SVM 2)',4),(13,'Gagal verifikasi KTP (SVM 1)',4),(14,'Gagal cek billing',5),(15,'Tagihan tidak sesuai',5),(16,'Gagal bayar tagihan',5),(17,'[AddOn] Speed On Demand',6),(18,'[AddOn] Minipack UseeTV',6),(19,'[AddOn] Wifi.id Seamless',6),(20,'[AddOn] Movin',6),(21,'[AddOn] Cloud Storage',6),(22,'[AddOn] IndiHome Cloud',6),(23,'[AddOn] IndiHome Smart',6),(24,'[AddOn] Hybrid Box',6),(25,'[AddOn] TV Storage',6),(26,'[AddOn] Iflix',6),(27,'[AddOn] Catchplay',6),(28,'[AddOn] HOOQ',6),(29,'[AddOn] Edukids',6),(30,'[Unsubscribe] Speed On Demand',7),(31,'[Unsubscribe] Wifi.id Seamless',7),(32,'[Unsubscribe] Movin',7),(33,'[Unsubscribe] Cloud Storage',7),(34,'[Unsubscribe] IndiHome Cloud',7),(35,'[Unsubscribe] IndiHome Smart',7),(36,'[Unsubscribe] Hybrid Box',7),(37,'[Unsubscribe] TV Storage',7),(38,'[Unsubscribe] Minipack UseeTV',7),(39,'[Unsubscribe] Iflix',7),(40,'[Unsubscribe] Catchplay',7),(41,'[Unsubscribe] HOOQ',7),(42,'[Unsubscribe] Edukids',7),(43,'Tidak menerima PIN dompet',8),(44,'Akun dompet terblokir',8),(45,'Gagal Top Up saldo',8),(46,'No.Token tidak diterima',8),(47,'Refund belum masuk di Dompet',8),(48,'Poin tidak update',9),(49,'Gagal redeem poin',9),(50,'Voucher tidak dapat dipakai',9),(51,'Gagal feasibility',10),(52,'Gagal pilih paket IndiHome',10),(53,'Follow Up Status Order PSB\r\n',10),(54,'Tidak bisa upload foto KTP',10),(55,'Kuota tidak bertambah',11),(56,'Gagal bayar',11),(57,'Gagal lapor gangguan',12),(58,'myIH Partner',13),(59,'Teknis',13),(60,'Non Teknis',13),(61,'[AddOn] Upgrade Speed',6),(62,'No.layanan/inet tidak terdaftar',4),(63,'Permintaan Ganti no.CP',2),(64,'Resend Link Deposit\r\n',10),(65,'Renew Speed',6),(66,'Permintaan Remapping',4),(67,'Aktifasi Manual SVM 1',4),(68,'Aktifasi Manual SVM 2',4),(69,'Aktifasi Manual Speed On Demand',6),(70,'Aktifasi Manual Wifi.id Seamless',6),(71,'Aktifasi Manual Kuota',11),(72,'Resend Link Refund',10),(73,'Update Status Deposit\r\n',10),(74,'FUP Menurun',14),(75,'Update Status Refund',10);
/*!40000 ALTER TABLE `sub_kategori` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `temp_active_id`
--

DROP TABLE IF EXISTS `temp_active_id`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `temp_active_id` (
  `chat_id` int(50) NOT NULL,
  `username` varchar(200) DEFAULT NULL,
  `first_name` varchar(200) DEFAULT NULL,
  `last_name` varchar(200) DEFAULT NULL,
  `active_date` datetime DEFAULT NULL,
  `max_active_date` datetime DEFAULT NULL,
  PRIMARY KEY (`chat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `temp_active_id`
--

LOCK TABLES `temp_active_id` WRITE;
/*!40000 ALTER TABLE `temp_active_id` DISABLE KEYS */;
/*!40000 ALTER TABLE `temp_active_id` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `temp_app_version`
--

DROP TABLE IF EXISTS `temp_app_version`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `temp_app_version` (
  `version` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `temp_app_version`
--

LOCK TABLES `temp_app_version` WRITE;
/*!40000 ALTER TABLE `temp_app_version` DISABLE KEYS */;
/*!40000 ALTER TABLE `temp_app_version` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `temp_cek_ticket`
--

DROP TABLE IF EXISTS `temp_cek_ticket`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `temp_cek_ticket` (
  `cek_ticket` enum('1','2') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `temp_cek_ticket`
--

LOCK TABLES `temp_cek_ticket` WRITE;
/*!40000 ALTER TABLE `temp_cek_ticket` DISABLE KEYS */;
/*!40000 ALTER TABLE `temp_cek_ticket` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `temp_email`
--

DROP TABLE IF EXISTS `temp_email`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `temp_email` (
  `email` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `temp_email`
--

LOCK TABLES `temp_email` WRITE;
/*!40000 ALTER TABLE `temp_email` DISABLE KEYS */;
/*!40000 ALTER TABLE `temp_email` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `temp_kategori`
--

DROP TABLE IF EXISTS `temp_kategori`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `temp_kategori` (
  `kategori` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `temp_kategori`
--

LOCK TABLES `temp_kategori` WRITE;
/*!40000 ALTER TABLE `temp_kategori` DISABLE KEYS */;
/*!40000 ALTER TABLE `temp_kategori` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `temp_source`
--

DROP TABLE IF EXISTS `temp_source`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `temp_source` (
  `source` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `temp_source`
--

LOCK TABLES `temp_source` WRITE;
/*!40000 ALTER TABLE `temp_source` DISABLE KEYS */;
/*!40000 ALTER TABLE `temp_source` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `urgensi_status`
--

DROP TABLE IF EXISTS `urgensi_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `urgensi_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `urgensi_status` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `urgensi_status`
--

LOCK TABLES `urgensi_status` WRITE;
/*!40000 ALTER TABLE `urgensi_status` DISABLE KEYS */;
INSERT INTO `urgensi_status` VALUES (1,'Normal'),(2,'Hard Complaint');
/*!40000 ALTER TABLE `urgensi_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_lengkap` varchar(200) DEFAULT NULL,
  `username` varchar(30) DEFAULT NULL,
  `inisial` char(3) DEFAULT NULL,
  `password` text DEFAULT NULL,
  `level` int(1) DEFAULT NULL,
  `authKey` text DEFAULT NULL,
  `accessToken` text DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_login_user` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'Administrator','admin','ADM','7c4a8d09ca3762af61e59520943dc26494f8941b',0,'0','0',0),(2,'Adrianus Bonggakaraeng','105626','ADR','7c4a8d09ca3762af61e59520943dc26494f8941b',2,'','',0),(3,'Sholahuddin','105623','SHU','7c4a8d09ca3762af61e59520943dc26494f8941b',2,'','',1),(4,'Novianto Nugroho','105625','NRG','7c4a8d09ca3762af61e59520943dc26494f8941b',2,'','',1),(5,'Enggar Bayu Adhi','105629','EBA','7c4a8d09ca3762af61e59520943dc26494f8941b',2,'','',0),(6,'Abdul Qaadir Zailani','105624','AQZ','7c4a8d09ca3762af61e59520943dc26494f8941b',2,'','',0),(7,'Muhamad Ripanji Maulana','119781','MRM','7c4a8d09ca3762af61e59520943dc26494f8941b',2,'','',0),(8,'Ari Syahrudin','119780','ASR','7c4a8d09ca3762af61e59520943dc26494f8941b',2,'','',0),(9,'Raafi','137617','RFF','7c4a8d09ca3762af61e59520943dc26494f8941b',2,'','',1),(10,'Peris Ilham','137621','PIF','7c4a8d09ca3762af61e59520943dc26494f8941b',2,'','',1),(11,'Wichy','138474','WCH','7c4a8d09ca3762af61e59520943dc26494f8941b',2,'','',1),(12,'Fujilestari','106928','FJI','7c4a8d09ca3762af61e59520943dc26494f8941b',2,'','',1),(13,'Hamdika Dwidenta','106923','HDW','7c4a8d09ca3762af61e59520943dc26494f8941b',2,'','',1),(14,'Dina Beautric','136134','DBB','7c4a8d09ca3762af61e59520943dc26494f8941b',2,'','',1),(15,'Siti Ayu Meishanny','106926','SAM','3d4f2bf07dc1be38b20cd6e46949a1071f9d0e3d',2,'','',1),(16,'Ira Nurlatifah','107932','INL','7c4a8d09ca3762af61e59520943dc26494f8941b',2,'','',1);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_telegram`
--

DROP TABLE IF EXISTS `user_telegram`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_telegram` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `telegram_id` varchar(50) DEFAULT NULL,
  `layanan` varchar(100) DEFAULT NULL,
  `no_handphone` varchar(50) DEFAULT NULL,
  `nama_lengkap` varchar(200) DEFAULT NULL,
  `status` enum('off','on') DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_telegram`
--

LOCK TABLES `user_telegram` WRITE;
/*!40000 ALTER TABLE `user_telegram` DISABLE KEYS */;
INSERT INTO `user_telegram` VALUES (1,'777422931','C4','082292803650','Adrianus Bonggakaraeng','on'),(2,'738830114','DBO','082374238432','Wichy','on'),(3,'536614236','DBO','089653792348','Han','on');
/*!40000 ALTER TABLE `user_telegram` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-07-28 18:06:55
