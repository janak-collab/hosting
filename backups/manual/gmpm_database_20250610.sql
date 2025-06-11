/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19  Distrib 10.6.22-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: gmpmus_gmpm
-- ------------------------------------------------------
-- Server version	10.6.22-MariaDB

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
-- Table structure for table `ASC_dictation_audit_log`
--

DROP TABLE IF EXISTS `ASC_dictation_audit_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `ASC_dictation_audit_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(100) NOT NULL,
  `user_ip` varchar(45) NOT NULL,
  `action` varchar(50) NOT NULL,
  `procedure_id` int(11) DEFAULT NULL,
  `procedure_name` varchar(255) DEFAULT NULL,
  `timestamp` datetime DEFAULT current_timestamp(),
  `user_agent` text DEFAULT NULL,
  `session_id` varchar(128) DEFAULT NULL,
  `duration_seconds` int(11) DEFAULT NULL,
  `dictation_count` int(11) DEFAULT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  PRIMARY KEY (`id`),
  KEY `idx_user_timestamp` (`user_name`,`timestamp`),
  KEY `idx_action` (`action`),
  KEY `idx_timestamp` (`timestamp`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ASC_dictation_audit_log`
--

LOCK TABLES `ASC_dictation_audit_log` WRITE;
/*!40000 ALTER TABLE `ASC_dictation_audit_log` DISABLE KEYS */;
INSERT INTO `ASC_dictation_audit_log` VALUES (1,'Anonymous','65.181.111.128','view_dictation_system',NULL,NULL,'2025-06-08 23:47:30','curl/7.61.1','1faa15b3af485e0263b73c1d7a3bd1a2',NULL,NULL,NULL),(2,'jvidyarthi','68.134.31.125','view_dictation_system',NULL,NULL,'2025-06-08 23:47:38','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','7cd9c46b9163f287b8e68684a4d6943e',NULL,NULL,NULL),(3,'Anonymous','65.181.111.128','view_dictation_system',NULL,NULL,'2025-06-08 23:48:03','curl/7.61.1','c6481524086c1dec071100753eceee2e',NULL,NULL,NULL),(4,'Anonymous','65.181.111.128','view_dictation_system',NULL,NULL,'2025-06-08 23:48:41','curl/7.61.1','6c50f800d46b7eeb983e62866cddf9bf',NULL,NULL,NULL),(5,'jvidyarthi','68.134.31.125','view_dictation_system',NULL,NULL,'2025-06-08 23:48:47','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','7cd9c46b9163f287b8e68684a4d6943e',NULL,NULL,NULL),(6,'jvidyarthi','68.134.31.125','view_dictation_system',NULL,NULL,'2025-06-08 23:49:12','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','7cd9c46b9163f287b8e68684a4d6943e',NULL,NULL,NULL),(7,'jvidyarthi','68.134.31.125','view_dictation_system',NULL,NULL,'2025-06-08 23:50:07','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','7cd9c46b9163f287b8e68684a4d6943e',NULL,NULL,NULL),(8,'jvidyarthi','68.134.31.125','view_dictation_system',NULL,NULL,'2025-06-08 23:50:50','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','7cd9c46b9163f287b8e68684a4d6943e',NULL,NULL,NULL),(9,'jvidyarthi','68.134.31.125','view_dictation_system',NULL,NULL,'2025-06-08 23:51:41','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','7cd9c46b9163f287b8e68684a4d6943e',NULL,NULL,NULL),(10,'jvidyarthi','68.134.31.125','view_dictation_system',NULL,NULL,'2025-06-08 23:52:09','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','7cd9c46b9163f287b8e68684a4d6943e',NULL,NULL,NULL),(11,'jvidyarthi','68.134.31.125','view_dictation_system',NULL,NULL,'2025-06-08 23:52:10','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','7cd9c46b9163f287b8e68684a4d6943e',NULL,NULL,NULL),(12,'jvidyarthi','68.134.31.125','view_dictation_system',NULL,NULL,'2025-06-08 23:52:57','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','7cd9c46b9163f287b8e68684a4d6943e',NULL,NULL,NULL),(13,'jvidyarthi','68.134.31.125','view_dictation_system',NULL,NULL,'2025-06-08 23:52:58','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','7cd9c46b9163f287b8e68684a4d6943e',NULL,NULL,NULL),(14,'jvidyarthi','68.134.31.125','view_dictation_system',NULL,NULL,'2025-06-08 23:53:14','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','7cd9c46b9163f287b8e68684a4d6943e',NULL,NULL,NULL),(15,'jvidyarthi','68.134.31.125','view_dictation_system',NULL,NULL,'2025-06-08 23:53:15','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','7cd9c46b9163f287b8e68684a4d6943e',NULL,NULL,NULL),(16,'jvidyarthi','68.134.31.125','view_dictation_system',NULL,NULL,'2025-06-08 23:55:32','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','7cd9c46b9163f287b8e68684a4d6943e',NULL,NULL,NULL),(17,'jvidyarthi','68.134.31.125','view_dictation_system',NULL,NULL,'2025-06-08 23:56:51','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','7cd9c46b9163f287b8e68684a4d6943e',NULL,NULL,NULL),(18,'jvidyarthi','68.134.31.125','view_dictation_system',NULL,NULL,'2025-06-08 23:59:30','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','7cd9c46b9163f287b8e68684a4d6943e',NULL,NULL,NULL),(19,'jvidyarthi','68.134.31.125','view_dictation_system',NULL,NULL,'2025-06-09 00:14:28','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','7cd9c46b9163f287b8e68684a4d6943e',NULL,NULL,NULL),(20,'jvidyarthi','68.134.31.125','view_dictation_system',NULL,NULL,'2025-06-09 00:14:32','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','7cd9c46b9163f287b8e68684a4d6943e',NULL,NULL,NULL),(21,'jvidyarthi','68.134.31.125','view_dictation_system',NULL,NULL,'2025-06-09 00:14:37','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','7cd9c46b9163f287b8e68684a4d6943e',NULL,NULL,NULL),(22,'jvidyarthi','68.134.31.125','view_dictation_system',NULL,NULL,'2025-06-09 00:14:39','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','7cd9c46b9163f287b8e68684a4d6943e',NULL,NULL,NULL),(23,'jvidyarthi','68.134.31.125','view_dictation_system',NULL,NULL,'2025-06-09 00:15:56','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','7cd9c46b9163f287b8e68684a4d6943e',NULL,NULL,NULL),(24,'jvidyarthi','68.134.31.125','view_dictation_system',NULL,NULL,'2025-06-09 00:15:59','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','7cd9c46b9163f287b8e68684a4d6943e',NULL,NULL,NULL),(25,'jvidyarthi','68.134.31.125','view_dictation_system',NULL,NULL,'2025-06-09 00:16:01','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','7cd9c46b9163f287b8e68684a4d6943e',NULL,NULL,NULL),(26,'jvidyarthi','68.134.31.125','view_dictation_system',NULL,NULL,'2025-06-09 00:20:10','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','7cd9c46b9163f287b8e68684a4d6943e',NULL,NULL,NULL),(27,'jvidyarthi','68.134.31.125','view_dictation_system',NULL,NULL,'2025-06-09 00:20:13','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','7cd9c46b9163f287b8e68684a4d6943e',NULL,NULL,NULL),(28,'jvidyarthi','68.134.31.125','view_dictation_system',NULL,NULL,'2025-06-09 00:20:21','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','7cd9c46b9163f287b8e68684a4d6943e',NULL,NULL,NULL),(29,'jvidyarthi','68.134.31.125','view_dictation_system',NULL,NULL,'2025-06-09 00:21:15','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','78feda2ff22fcc56bc5161859b336ee4',NULL,NULL,NULL),(30,'jvidyarthi','68.134.31.125','view_dictation_system',NULL,NULL,'2025-06-09 00:30:56','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','7cd9c46b9163f287b8e68684a4d6943e',NULL,NULL,NULL),(31,'jvidyarthi','68.134.31.125','view_dictation_system',NULL,NULL,'2025-06-09 00:35:52','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','7cd9c46b9163f287b8e68684a4d6943e',NULL,NULL,NULL),(32,'jvidyarthi','68.134.31.125','view_dictation_system',NULL,NULL,'2025-06-09 00:37:32','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','7cd9c46b9163f287b8e68684a4d6943e',NULL,NULL,NULL),(33,'jvidyarthi','68.134.31.125','view_dictation_system',NULL,NULL,'2025-06-09 00:37:35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','7cd9c46b9163f287b8e68684a4d6943e',NULL,NULL,NULL);
/*!40000 ALTER TABLE `ASC_dictation_audit_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ASC_procedures`
--

DROP TABLE IF EXISTS `ASC_procedures`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `ASC_procedures` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `code` varchar(50) NOT NULL,
  `template_content` text NOT NULL,
  `category` varchar(50) DEFAULT 'General',
  `active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `idx_code` (`code`),
  KEY `idx_category` (`category`),
  KEY `idx_active` (`active`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ASC_procedures`
--

LOCK TABLES `ASC_procedures` WRITE;
/*!40000 ALTER TABLE `ASC_procedures` DISABLE KEYS */;
INSERT INTO `ASC_procedures` VALUES (7,'Lumbar Epidural Steroid Injection','LESI','PROCEDURE: Lumbar Epidural Steroid Injection\nDATE: [PROCEDURE_DATE]\nPATIENT: [PATIENT_NAME]\nDOB: [DOB]\nPROVIDER: [PROVIDER]\nLOCATION: [LOCATION]\n\nINDICATION: Lumbar radiculopathy with lower extremity pain.\n\nPROCEDURE: After informed consent was obtained, the patient was placed in the prone position. The lumbar area was prepped and draped in a sterile fashion. Under fluoroscopic guidance, the epidural space was accessed at the L[LEVEL] level using a [GAUGE]-gauge Tuohy needle with loss of resistance technique. Correct needle placement was confirmed with contrast injection showing appropriate epidural spread. [STEROID] mg of steroid with [VOLUME] mL of preservative-free saline was injected. The needle was removed and a bandage was applied.\n\nThe patient tolerated the procedure well without complications.\n\nPOST-PROCEDURE: The patient was monitored for 30 minutes and discharged in stable condition with follow-up instructions.','Spine Injections',1,'2025-06-09 14:38:47','2025-06-09 14:38:47'),(8,'Cervical Epidural Steroid Injection','CESI','PROCEDURE: Cervical Epidural Steroid Injection\nDATE: [PROCEDURE_DATE]\nPATIENT: [PATIENT_NAME]\nDOB: [DOB]\nPROVIDER: [PROVIDER]\nLOCATION: [LOCATION]\n\nINDICATION: Cervical radiculopathy with upper extremity pain.\n\nPROCEDURE: After informed consent was obtained, the patient was placed in the prone position. The cervical area was prepped and draped in a sterile fashion. Under fluoroscopic guidance, the epidural space was accessed at the C[LEVEL] level using a [GAUGE]-gauge Tuohy needle. Correct needle placement was confirmed with contrast injection. [STEROID] mg of steroid with [VOLUME] mL of preservative-free saline was injected. The needle was removed and a bandage was applied.\n\nThe patient tolerated the procedure well without complications.\n\nPOST-PROCEDURE: The patient was monitored for 30 minutes and discharged in stable condition.','Spine Injections',1,'2025-06-09 14:38:47','2025-06-09 14:38:47'),(9,'Knee Joint Injection','KNEE','PROCEDURE: Knee Joint Injection\nDATE: [PROCEDURE_DATE]\nPATIENT: [PATIENT_NAME]\nDOB: [DOB]\nPROVIDER: [PROVIDER]\nLOCATION: [LOCATION]\n\nINDICATION: Knee osteoarthritis with pain and functional limitation.\n\nPROCEDURE: After informed consent was obtained, the patient was positioned supine with the knee slightly flexed. The [SIDE] knee was prepped and draped in a sterile fashion. Using a [APPROACH] approach, a [GAUGE]-gauge needle was advanced into the joint space. Aspiration revealed [FLUID]. [MEDICATION] was injected into the joint space. The needle was removed and a bandage was applied.\n\nThe patient tolerated the procedure well without complications.','Joint Injections',1,'2025-06-09 14:38:47','2025-06-09 14:38:47'),(10,'Trigger Point Injection','TPI','PROCEDURE: Trigger Point Injection\nDATE: [PROCEDURE_DATE]\nPATIENT: [PATIENT_NAME]\nDOB: [DOB]\nPROVIDER: [PROVIDER]\nLOCATION: [LOCATION]\n\nINDICATION: Myofascial pain syndrome with trigger points.\n\nPROCEDURE: After informed consent was obtained, the patient was positioned comfortably. The trigger points were identified by palpation in the [MUSCLES] muscles. The area was prepped with alcohol. Using a [GAUGE]-gauge needle, [MEDICATION] was injected into each trigger point. A total of [NUMBER] trigger points were injected.\n\nThe patient tolerated the procedure well without complications.','Soft Tissue Injections',1,'2025-06-09 14:38:47','2025-06-09 14:38:47'),(11,'Sacroiliac Joint Injection','SIJI','PROCEDURE: Sacroiliac Joint Injection\nDATE: [PROCEDURE_DATE]\nPATIENT: [PATIENT_NAME]\nDOB: [DOB]\nPROVIDER: [PROVIDER]\nLOCATION: [LOCATION]\n\nINDICATION: Sacroiliac joint dysfunction with lower back and buttock pain.\n\nPROCEDURE: After informed consent was obtained, the patient was placed in the prone position. The [SIDE] sacroiliac joint region was prepped and draped in a sterile fashion. Under fluoroscopic guidance, a [GAUGE]-gauge spinal needle was advanced into the sacroiliac joint. Correct placement was confirmed with contrast injection showing intra-articular spread. [MEDICATION] was injected. The needle was removed and a bandage was applied.\n\nThe patient tolerated the procedure well without complications.','Spine Injections',1,'2025-06-09 14:38:47','2025-06-09 14:38:47');
/*!40000 ALTER TABLE `ASC_procedures` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ASC_procedures_billing_codes`
--

DROP TABLE IF EXISTS `ASC_procedures_billing_codes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `ASC_procedures_billing_codes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `procedure_id` int(11) NOT NULL,
  `cpt_code` varchar(20) NOT NULL,
  `description` varchar(255) NOT NULL,
  `laterality` enum('unilateral','bilateral','n/a') DEFAULT 'n/a',
  `default_units` int(11) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `procedure_id` (`procedure_id`),
  KEY `idx_cpt` (`cpt_code`),
  CONSTRAINT `ASC_procedures_billing_codes_ibfk_1` FOREIGN KEY (`procedure_id`) REFERENCES `ASC_procedures` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ASC_procedures_billing_codes`
--

LOCK TABLES `ASC_procedures_billing_codes` WRITE;
/*!40000 ALTER TABLE `ASC_procedures_billing_codes` DISABLE KEYS */;
INSERT INTO `ASC_procedures_billing_codes` VALUES (25,7,'62322','Injection, single interlaminar epidural, lumbar','n/a',1,'2025-06-09 14:45:57'),(26,7,'77003','Fluoroscopic guidance for spine injection','n/a',1,'2025-06-09 14:45:57'),(27,8,'62320','Injection, single interlaminar epidural, cervical','n/a',1,'2025-06-09 14:45:57'),(28,8,'77003','Fluoroscopic guidance for spine injection','n/a',1,'2025-06-09 14:45:57'),(29,9,'20610','Arthrocentesis, major joint','n/a',1,'2025-06-09 14:45:57'),(30,10,'20552','Injection, single or multiple trigger points, 1-2 muscles','n/a',1,'2025-06-09 14:45:57'),(31,11,'27096','Injection procedure for sacroiliac joint, anesthetic/steroid','n/a',1,'2025-06-09 14:45:57'),(32,11,'77003','Fluoroscopic guidance for spine injection','n/a',1,'2025-06-09 14:45:57');
/*!40000 ALTER TABLE `ASC_procedures_billing_codes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ASC_provider_procedures`
--

DROP TABLE IF EXISTS `ASC_provider_procedures`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `ASC_provider_procedures` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `provider_id` int(11) NOT NULL,
  `procedure_id` int(11) NOT NULL,
  `can_perform` tinyint(1) DEFAULT 1,
  `custom_intro` text DEFAULT NULL,
  `custom_closing` text DEFAULT NULL,
  `is_favorite` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_provider_procedure` (`provider_id`,`procedure_id`),
  KEY `procedure_id` (`procedure_id`),
  CONSTRAINT `ASC_provider_procedures_ibfk_1` FOREIGN KEY (`provider_id`) REFERENCES `providers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `ASC_provider_procedures_ibfk_2` FOREIGN KEY (`procedure_id`) REFERENCES `ASC_procedures` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ASC_provider_procedures`
--

LOCK TABLES `ASC_provider_procedures` WRITE;
/*!40000 ALTER TABLE `ASC_provider_procedures` DISABLE KEYS */;
INSERT INTO `ASC_provider_procedures` VALUES (1,2,7,1,NULL,NULL,1),(2,2,8,1,NULL,NULL,0),(3,2,9,1,NULL,NULL,1),(4,2,10,1,NULL,NULL,0),(5,2,11,1,NULL,NULL,0);
/*!40000 ALTER TABLE `ASC_provider_procedures` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `audit_log`
--

DROP TABLE IF EXISTS `audit_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `audit_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(50) DEFAULT NULL,
  `table_affected` varchar(50) DEFAULT NULL,
  `record_id` int(11) DEFAULT NULL,
  `timestamp` datetime DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`details`)),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `audit_log`
--

LOCK TABLES `audit_log` WRITE;
/*!40000 ALTER TABLE `audit_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `audit_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ip_access_rules`
--

DROP TABLE IF EXISTS `ip_access_rules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `ip_access_rules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(45) NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `description` varchar(255) NOT NULL,
  `access_type` enum('allow','deny') NOT NULL DEFAULT 'allow',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_ip_address` (`ip_address`),
  KEY `idx_is_active` (`is_active`),
  KEY `idx_access_type` (`access_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ip_access_rules`
--

LOCK TABLES `ip_access_rules` WRITE;
/*!40000 ALTER TABLE `ip_access_rules` DISABLE KEYS */;
/*!40000 ALTER TABLE `ip_access_rules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `it_support_tickets`
--

DROP TABLE IF EXISTS `it_support_tickets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `it_support_tickets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `location` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `category` varchar(20) DEFAULT 'general',
  `priority` varchar(20) DEFAULT 'normal',
  `status` varchar(20) DEFAULT 'open',
  `ip_address` varchar(45) DEFAULT NULL,
  `created_by` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `resolved_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_status` (`status`),
  KEY `idx_priority` (`priority`),
  KEY `idx_created_at` (`created_at`),
  KEY `idx_created_by` (`created_by`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `it_support_tickets`
--

LOCK TABLES `it_support_tickets` WRITE;
/*!40000 ALTER TABLE `it_support_tickets` DISABLE KEYS */;
INSERT INTO `it_support_tickets` VALUES (5,'Migration Test User','Catonsville','Test ticket created during migration completion to verify system functionality.','other','normal','open',NULL,NULL,'2025-06-06 23:33:54','2025-06-06 23:33:54',NULL),(6,'Direct Test User','Leonardtown','This is a direct test submission to verify the system is working correctly.','hardware','normal','open','127.0.0.1',NULL,'2025-06-06 23:41:55','2025-06-06 23:41:55',NULL),(7,'Direct Test User','Leonardtown','This is a direct test submission to verify the system is working correctly.','hardware','normal','open','127.0.0.1',NULL,'2025-06-06 23:44:00','2025-06-06 23:44:00',NULL),(8,'Bill Clinton','Leonardtown','This is a test does this work?','hardware','low','open','68.134.31.125',NULL,'2025-06-07 00:16:38','2025-06-07 00:16:38',NULL),(9,'Janak Vidyarthi','Prince Frederick','What is the doing?','hardware','low','open','68.134.31.125',NULL,'2025-06-07 00:37:23','2025-06-07 00:37:23',NULL),(10,'barcodes','Odenton','Will this work now?','software','low','open','68.134.31.125',NULL,'2025-06-07 00:43:51','2025-06-07 00:43:51',NULL),(11,'John Smith','Leonardtown','Testing IT support form. Computer not starting properly. Error message appears on boot.','hardware','normal','open','127.0.0.1',NULL,'2025-06-07 01:35:41','2025-06-07 01:35:41',NULL),(12,'Status Check - 21:36:44','Leonardtown','Automated status check at 2025-06-06 21:36:44','other','low','open','127.0.0.1',NULL,'2025-06-07 01:36:44','2025-06-07 01:36:44',NULL),(13,'Janak Vidyarthi','Odenton','afdasddsfsdfsdfsdfsdf','hardware','low','open','68.134.31.125',NULL,'2025-06-07 02:34:27','2025-06-07 02:34:27',NULL),(14,'test','Odenton','dsfsdfsdfsdf sdf','hardware','low','open','68.134.31.125',NULL,'2025-06-07 02:46:54','2025-06-07 02:46:54',NULL),(15,'test','Odenton','sdfsdfsdfsdfsdf','hardware','low','open','68.134.31.125',NULL,'2025-06-07 02:50:32','2025-06-07 02:50:32',NULL),(16,'cache','Odenton','sdfs dfsd f sdf sdf sd','hardware','low','open','68.134.31.125',NULL,'2025-06-07 18:15:40','2025-06-07 18:15:40',NULL),(17,'Janak Vidyarthi','Leonardtown','asdasdasdsadasdas','software','low','open','68.134.31.125',NULL,'2025-06-07 19:08:02','2025-06-07 19:08:02',NULL),(18,'Test User','Leonardtown','Test ticket from script','other','normal','open',NULL,'testuser','2025-06-07 19:35:37','2025-06-07 19:35:37',NULL),(19,'Test User','Test Location','Test ticket from script','hardware','normal','open',NULL,'Unknown','2025-06-07 22:18:29','2025-06-07 22:18:29',NULL),(20,'cache','Odenton','testsetasdsd','hardware','low','open','68.134.31.125','jvidyarthi','2025-06-07 22:26:39','2025-06-07 22:26:39',NULL),(21,'Janak Vidyarthi','Odenton','fghdfg hghfgh dfhg dfgh fgh','hardware','normal','open','68.134.31.125','jvidyarthi','2025-06-07 22:26:56','2025-06-07 22:26:56',NULL),(22,'Janak Vidyarthi','Odenton','dfgdbgdfgbdfgbdfg','software','normal','open','68.134.31.125','jvidyarthi','2025-06-07 22:52:38','2025-06-07 22:52:38',NULL);
/*!40000 ALTER TABLE `it_support_tickets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `it_ticket_comments`
--

DROP TABLE IF EXISTS `it_ticket_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `it_ticket_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ticket_id` int(11) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `comment` text NOT NULL,
  `is_internal` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_ticket_id` (`ticket_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `it_ticket_comments`
--

LOCK TABLES `it_ticket_comments` WRITE;
/*!40000 ALTER TABLE `it_ticket_comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `it_ticket_comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `phone_notes`
--

DROP TABLE IF EXISTS `phone_notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `phone_notes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `patient_name` varchar(100) NOT NULL,
  `dob` date NOT NULL,
  `phone` varchar(10) NOT NULL,
  `caller_name` varchar(100) DEFAULT NULL,
  `location` varchar(50) NOT NULL,
  `provider` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `last_seen` date NOT NULL,
  `upcoming_appointment` date NOT NULL,
  `created_by` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` enum('new','reviewed','closed') DEFAULT 'new',
  `follow_up_notes` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_patient_name` (`patient_name`),
  KEY `idx_provider` (`provider`),
  KEY `idx_status` (`status`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `phone_notes`
--

LOCK TABLES `phone_notes` WRITE;
/*!40000 ALTER TABLE `phone_notes` DISABLE KEYS */;
INSERT INTO `phone_notes` VALUES (1,'test','0001-11-11','7033471962','','Edgewater','Dr. Johnson','etsdfrsdfsdf','0011-11-11','2025-11-11','Unknown','2025-05-30 23:01:47','2025-05-30 23:01:47','new',NULL),(2,'test','0001-11-11','7033471962','','Elkridge','Dr. Smith','etsdfrsdfsdf','0011-11-11','2025-11-11','Unknown','2025-05-30 23:02:44','2025-05-30 23:02:44','new',NULL),(3,'test','0001-11-11','7033471962','','Elkridge','Dr. Smith','rtdrtd','0001-11-11','0002-02-22','Unknown','2025-05-30 23:09:58','2025-05-30 23:09:58','new',NULL),(4,'Test Patient','1980-01-01','4105551234','','Leonardtown','Dr. Smith','Test phone note created at 2025-06-06 21:20:44','2025-05-30','2025-06-13','Unknown','2025-06-07 01:20:44','2025-06-07 01:20:44','new',NULL),(5,'Janak','0001-11-11','7033471962','','Edgewater','Dr. Johnson','What is this test','0001-11-11','2025-08-22','Unknown','2025-06-07 01:23:10','2025-06-07 01:23:10','new',NULL);
/*!40000 ALTER TABLE `phone_notes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `provider_locations`
--

DROP TABLE IF EXISTS `provider_locations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `provider_locations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `provider_id` int(11) NOT NULL,
  `location` varchar(50) NOT NULL,
  `is_primary` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_provider_location` (`provider_id`,`location`),
  CONSTRAINT `provider_locations_ibfk_1` FOREIGN KEY (`provider_id`) REFERENCES `providers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `provider_locations`
--

LOCK TABLES `provider_locations` WRITE;
/*!40000 ALTER TABLE `provider_locations` DISABLE KEYS */;
/*!40000 ALTER TABLE `provider_locations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `providers`
--

DROP TABLE IF EXISTS `providers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `providers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `display_name` varchar(100) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `idx_active` (`active`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `providers`
--

LOCK TABLES `providers` WRITE;
/*!40000 ALTER TABLE `providers` DISABLE KEYS */;
INSERT INTO `providers` VALUES (1,'The billing team','Billing Team','billing@gmpm.com',NULL,1,'2025-05-30 22:35:16'),(2,'Carley Morris','Carley Morris','cmorris@gmpm.com',NULL,1,'2025-05-30 22:35:16'),(3,'Dr. Smith','Dr. John Smith','jsmith@gmpm.com',NULL,1,'2025-05-30 22:35:16'),(4,'Dr. Johnson','Dr. Sarah Johnson','sjohnson@gmpm.com',NULL,1,'2025-05-30 22:35:16'),(5,'Front Desk','Front Desk Staff','frontdesk@gmpm.com',NULL,1,'2025-05-30 22:35:16');
/*!40000 ALTER TABLE `providers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `display_name` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'front_desk','Front Desk Staff','Handles patient check-in, scheduling, and basic administrative tasks','2025-06-08 03:08:52'),(2,'clinical','Clinical Staff','Medical assistants and nurses who work with patients','2025-06-08 03:08:52'),(3,'billing','Billing Staff','Handles insurance claims, patient billing, and financial tasks','2025-06-08 03:08:52'),(4,'admin','Administrator','Full system access and administrative functions','2025-06-08 03:08:52'),(5,'provider','Healthcare Provider','Doctors and practitioners','2025-06-08 03:08:52');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_audit_log`
--

DROP TABLE IF EXISTS `user_audit_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_audit_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `performed_by` int(11) DEFAULT NULL,
  `action` enum('created','updated','deleted','password_changed','role_changed','locked','unlocked','login_failed') DEFAULT NULL,
  `old_value` varchar(255) DEFAULT NULL,
  `new_value` varchar(255) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_performed_by` (`performed_by`),
  KEY `idx_action` (`action`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_audit_log`
--

LOCK TABLES `user_audit_log` WRITE;
/*!40000 ALTER TABLE `user_audit_log` DISABLE KEYS */;
INSERT INTO `user_audit_log` VALUES (1,3,3,'created',NULL,'Initial setup','127.0.0.1',NULL,'2025-06-10 11:02:17'),(2,12,1,'created',NULL,'Imported from htpasswd','127.0.0.1',NULL,'2025-06-10 11:09:18'),(3,23,0,'created',NULL,'user',NULL,NULL,'2025-06-10 12:56:46'),(4,24,0,'created',NULL,'user',NULL,NULL,'2025-06-10 12:58:11'),(5,25,1,'created',NULL,'user',NULL,NULL,'2025-06-10 13:26:11'),(6,26,NULL,'created',NULL,'user','65.181.111.128','curl/7.61.1','2025-06-10 13:29:03'),(7,26,NULL,'updated',NULL,NULL,'65.181.111.128','curl/7.61.1','2025-06-10 13:36:54'),(8,27,1,'deleted',NULL,NULL,'65.181.111.128','curl/7.61.1','2025-06-10 13:45:43'),(9,28,1,'created',NULL,'user','65.181.111.128','curl/7.61.1','2025-06-10 14:21:34'),(10,26,1,'updated',NULL,NULL,'65.181.111.128','curl/7.61.1','2025-06-10 14:21:41');
/*!40000 ALTER TABLE `user_audit_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','staff','provider','super_admin','user') DEFAULT 'staff',
  `is_active` tinyint(1) DEFAULT 1,
  `active` tinyint(1) DEFAULT 1,
  `failed_attempts` int(11) DEFAULT 0,
  `locked_until` timestamp NULL DEFAULT NULL,
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `notes` text DEFAULT NULL,
  `primary_role` varchar(50) DEFAULT 'front_desk',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `idx_username` (`username`),
  KEY `idx_email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (12,'jvidyarthi',NULL,'J Vidyarthi','','super_admin',1,1,0,NULL,NULL,'2025-06-10 11:09:18',1,'2025-06-10 12:38:41','Imported from htpasswd','front_desk'),(16,'admin','admin@gmpm.us','Admin User','$2y$10$0tInnKqhM.Zm8G3QLyqto.i8r0V.qWQO2c3Is6U4r9CEK.40eDFv2','admin',1,1,0,NULL,'2025-06-10 14:49:10','2025-06-10 12:16:41',1,'2025-06-10 14:49:10','Admin user','front_desk'),(17,'test','test@gmpm.us','Test User','','user',1,1,0,NULL,NULL,'2025-06-10 12:16:41',1,'2025-06-10 12:39:51','Test user','front_desk');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-06-10 15:16:21
