-- MySQL dump 10.13  Distrib 8.4.3, for Win64 (x86_64)
--
-- Host: localhost    Database: if0_39600889_obvitnas_db
-- ------------------------------------------------------
-- Server version	8.4.3

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admin-gi`
--

DROP TABLE IF EXISTS `admin-gi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin-gi` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `username` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `gi_id` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin-gi`
--

LOCK TABLES `admin-gi` WRITE;
/*!40000 ALTER TABLE `admin-gi` DISABLE KEYS */;
INSERT INTO `admin-gi` VALUES (1,NULL,'Admin GI 1','Admingi1@gmail.com','$2y$10$/6yrc0HY5NL0.6HGn8SNUu5FVnGqIONt6SBeaaXcaOKyb/MNjLQdi',1,'2025-07-17 21:21:32'),(3,NULL,'Admin GI 4 ','Admingi4@gmail.com','$2y$10$8DCg6Yyl1b2ZFMbZ84oT7u7Z4rJ3ohdzKkjoHDTl1ijVJuchuCW4u',4,'2025-07-20 18:39:41');
/*!40000 ALTER TABLE `admin-gi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin_k3l`
--

DROP TABLE IF EXISTS `admin_k3l`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_k3l` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `username` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_k3l`
--

LOCK TABLES `admin_k3l` WRITE;
/*!40000 ALTER TABLE `admin_k3l` DISABLE KEYS */;
INSERT INTO `admin_k3l` VALUES (1,'Admin K3L','Admink3l1','Admink3l1@gmail.com','$2y$10$DYZ7bZY3Cn6x6eYSNlNyRemqX3Z22zxLGCTx0eVsN9hoOcXiwH3Vu','2025-07-17 08:46:15');
/*!40000 ALTER TABLE `admin_k3l` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `audit_log`
--

DROP TABLE IF EXISTS `audit_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `audit_log` (
  `id` int NOT NULL AUTO_INCREMENT,
  `table_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `record_id` int NOT NULL,
  `action_type` enum('INSERT','UPDATE','DELETE') COLLATE utf8mb4_unicode_ci NOT NULL,
  `old_values` json DEFAULT NULL,
  `new_values` json DEFAULT NULL,
  `changed_by` int DEFAULT NULL,
  `changed_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_table_record` (`table_name`,`record_id`),
  KEY `idx_changed_at` (`changed_at`),
  KEY `idx_changed_by` (`changed_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `audit_log`
--

LOCK TABLES `audit_log` WRITE;
/*!40000 ALTER TABLE `audit_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `audit_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `barang_keluar`
--

DROP TABLE IF EXISTS `barang_keluar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `barang_keluar` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `tanggal` date NOT NULL,
  `waktu` time NOT NULL,
  `nama_instansi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_petugas` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_hp` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pemilik_barang` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pejabat_penerbit_surat` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_surat` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keterangan_barang` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `foto_surat_jalan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto_barang` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `satpam_pemeriksa` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_tanggal` (`tanggal`),
  KEY `idx_nama_instansi` (`nama_instansi`),
  KEY `idx_pemilik_barang` (`pemilik_barang`),
  KEY `idx_satpam_pemeriksa` (`satpam_pemeriksa`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `barang_keluar`
--

LOCK TABLES `barang_keluar` WRITE;
/*!40000 ALTER TABLE `barang_keluar` DISABLE KEYS */;
INSERT INTO `barang_keluar` VALUES (1,'2025-09-20','09:15:00','PT PLN Persero','Budi Santoso','081234567890','PT Maju Jaya','Andi Wijaya','SJ-001/IX/2025','Pengeluaran 5 unit trafo kecil untuk distribusi','suratjalan_sj001.pdf','trafo_5unit.jpg','Satpam Agus','2025-09-24 05:12:04','2025-09-24 05:12:04'),(2,'2025-09-21','14:30:00','PT Dirgantara Indonesia','Siti Aminah','082112223333','PT Sumber Makmur','Rina Kurnia','SJ-002/IX/2025','Barang berupa 20 box kabel instalasi','suratjalan_sj002.pdf','kabel_box20.jpg','Satpam Dedi','2025-09-24 05:12:04','2025-09-24 05:12:04'),(3,'2025-09-22','10:45:00','PT Telkom Indonesia','Joko Priyono','081399988877','CV Cipta Abadi','Surya Dharma','SJ-003/IX/2025','Pengeluaran perangkat router dan switch','suratjalan_sj003.pdf','router_switch.jpg','Satpam Yudi','2025-09-24 05:12:04','2025-09-24 05:12:04'),(4,'2025-10-02','21:33:00','PLN PUSAT','PLN PUSAT','088798765432','Nirwan','kk','p08988','Dokumen','1759415768_1c79c1f54575d0aafbc9.png','1759415768_c405455ee427b1992b1c.jpg','Dian','2025-10-02 21:36:08','2025-10-02 21:36:08');
/*!40000 ALTER TABLE `barang_keluar` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `barang_masuk`
--

DROP TABLE IF EXISTS `barang_masuk`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `barang_masuk` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `tanggal` date NOT NULL,
  `waktu` time NOT NULL,
  `nama_instansi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_petugas` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_hp` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_pic_tujuan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_surat_pengantar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keterangan_barang` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `konfirmasi_nama_pic` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `konfirmasi_jabatan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kesesuaian` enum('sesuai','tidak sesuai') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `serah_nama` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `serah_jabatan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `serah_tanggal` date DEFAULT NULL,
  `foto_barang` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `satpam_pemeriksa` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_tanggal` (`tanggal`),
  KEY `idx_nama_instansi` (`nama_instansi`),
  KEY `idx_satpam_pemeriksa` (`satpam_pemeriksa`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `barang_masuk`
--

LOCK TABLES `barang_masuk` WRITE;
/*!40000 ALTER TABLE `barang_masuk` DISABLE KEYS */;
INSERT INTO `barang_masuk` VALUES (1,'2025-09-15','09:15:00','PT PLN Persero','Budi Santoso','081234567890','Sari Dewi - Bagian Logistik','SP/LOG/001/2024','Komputer Desktop unit baru, merk ASUS, processor Intel Core i7, RAM 16GB, Storage SSD 1TB, untuk keperluan operasional kantor divisi keuangan.','Sari Dewi','Staff Logistik','sesuai','Budi Santoso','Kurir','2025-09-15','komputer_desktop_001.jpg','Joko Widodo','2025-09-19 16:21:29','2025-09-19 16:21:29'),(2,'2025-09-16','14:30:00','PT Telkom Indonesia','Andi Wijaya','081298765432','Ahmad Rahman - IT Support','TI/HW/045/2024','Router jaringan Cisco, switch 24 port, kabel UTP cat6, untuk upgrade infrastruktur jaringan kantor.','Ahmad Rahman','IT Support','sesuai','Andi Wijaya','Teknisi','2025-09-16','router_cisco_002.jpg','Siti Aminah','2025-09-19 16:21:29','2025-09-19 16:21:29'),(3,'2025-09-17','10:45:00','CV Maju Bersama','Dian Pratama','081367894521','Rini Susanti - Administrasi','ADM/DOK/012/2024','Dokumen kontrak kerjasama, proposal proyek, surat-surat penting dalam map khusus.','Rini Susanti','Admin','sesuai','Dian Pratama','Courier','2025-09-17','dokumen_kontrak_003.jpg','Bambang Setiawan','2025-09-19 16:21:29','2025-09-19 16:21:29'),(4,'2025-09-22','10:30:00','PT Dirgantara Indonesia','Budi Santoso','081234567890','Andi Pratama','SP-001/IX/2025','10 unit laptop','Andi Pratama','Manager IT','sesuai','Rina Lestari','Supervisor Logistik','2025-09-22','laptop.jpg','Satpam A','2025-09-22 07:03:39','2025-09-22 07:03:39'),(5,'2025-09-22','10:30:00','PT Dirgantara Indonesia','Budi Santoso','081234567890','Andi Pratama','SP-001/IX/2025','10 unit laptop','Andi Pratama','Manager IT','sesuai','Rina Lestari','Supervisor Logistik','2025-09-22','laptop.jpg','Satpam A','2025-09-22 07:55:07','2025-09-22 07:55:07'),(6,'2025-09-23','08:03:00','PLN PUSAT','PLN PUSAT','088798765432','MSB K3L','12293-492456','dokumen','Pak Yeni','MSB K3L',NULL,'Pak Yeni','MSB K3L','2025-09-23','1758589444_9bac98a37a92960231b8.jpg','Dian','2025-09-23 08:04:04','2025-09-23 08:04:04'),(7,'2025-09-23','08:28:00','PLN PUSAT','PLN PUSAT','088798765432','MSB K3L','12293-492456','dokumen','Pak Yeni','MSB K3L',NULL,'Pak Yeni','MSB K3L','2025-09-23','1758590952_ff7c56643940e5037c9c.jpg','Dian','2025-09-23 08:29:12','2025-09-23 08:29:12'),(8,'2025-09-23','10:33:00','PLN PUSAT','PLN PUSAT','088798765432','MSB K3L','12293-492456','Dokumen','Pak Yeni','MSB K3L',NULL,'Pak Yeni','MSB K3L','2025-09-23','1758598465_f1e4ea0766899512dab4.jpg','Dian','2025-09-23 10:34:25','2025-09-23 10:34:25'),(9,'2025-10-02','21:47:00','PLN PUSAT','PLN PUSAT','088798765432','MSB K3L','12293-492456','Dokumen','Pak Yeni','MSB K3L',NULL,'Pak Yeni','MSB K3L','2025-10-02','1759416479_f7a77297557d975d2033.jpg','Dian','2025-10-02 21:47:59','2025-10-02 21:47:59');
/*!40000 ALTER TABLE `barang_masuk` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jadwal_satpam`
--

DROP TABLE IF EXISTS `jadwal_satpam`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jadwal_satpam` (
  `id` int NOT NULL AUTO_INCREMENT,
  `satpam_id` int DEFAULT NULL,
  `nama_satpam` varchar(100) DEFAULT NULL,
  `regu_number` int DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `shift` enum('P','S','M','L') DEFAULT NULL,
  `jam_mulai` time DEFAULT NULL,
  `jam_selesai` time DEFAULT NULL,
  `status` enum('normal','edited','emergency') NOT NULL DEFAULT 'normal',
  `keterangan` varchar(100) DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `notes` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `satpam_id` (`satpam_id`),
  KEY `idx_regu_number` (`regu_number`),
  KEY `idx_tanggal` (`tanggal`),
  KEY `idx_status` (`status`),
  CONSTRAINT `jadwal_satpam_ibfk_1` FOREIGN KEY (`satpam_id`) REFERENCES `satpam` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jadwal_satpam`
--

LOCK TABLES `jadwal_satpam` WRITE;
/*!40000 ALTER TABLE `jadwal_satpam` DISABLE KEYS */;
INSERT INTO `jadwal_satpam` VALUES (2,2,NULL,2,'2025-09-18',NULL,'16:00:00','00:00:00','normal',NULL,1,'Shift Siang (S)','2025-09-18 02:15:41','2025-09-18 09:15:41'),(3,3,NULL,3,'2025-09-18',NULL,'00:00:00','08:00:00','normal',NULL,1,'Shift Malam (M)','2025-09-18 02:15:41','2025-09-18 09:15:41'),(4,4,NULL,4,'2025-09-18',NULL,'08:00:00','16:00:00','normal',NULL,1,'Libur (L)','2025-09-18 02:15:41','2025-09-18 09:15:41'),(5,NULL,'Regu 2',2,'2025-09-19','P','08:00:00','16:00:00','normal',NULL,1,'Shift Pagi','2025-09-18 02:17:20','2025-09-18 09:17:20'),(6,NULL,'Regu 3',3,'2025-09-19','S','16:00:00','00:00:00','normal',NULL,1,'Shift Siang','2025-09-18 02:17:20','2025-09-18 09:17:20'),(7,NULL,'Regu 4',4,'2025-09-19','M','00:00:00','08:00:00','normal',NULL,1,'Shift Malam','2025-09-18 02:17:20','2025-09-18 09:17:20'),(8,NULL,'Regu 1',1,'2025-09-19','L','08:00:00','16:00:00','normal',NULL,1,'Libur','2025-09-18 02:17:20','2025-09-18 09:17:20'),(9,NULL,'Regu 3',3,'2025-09-20','P','08:00:00','16:00:00','normal',NULL,NULL,NULL,'2025-09-20 06:38:57','2025-09-20 13:38:57'),(10,NULL,'Regu 4',4,'2025-09-20','S','16:00:00','00:00:00','normal',NULL,NULL,NULL,'2025-09-20 16:28:34','2025-09-20 23:28:34'),(11,NULL,'Regu 1',1,'2025-09-21','M','00:00:00','08:00:00','normal',NULL,10,NULL,'2025-09-20 17:17:27','2025-09-21 00:17:27'),(12,NULL,'Regu 2',2,'2025-09-21','P','08:00:00','16:00:00','normal',NULL,NULL,NULL,'2025-09-21 01:44:26','2025-09-21 08:44:26'),(13,NULL,'Regu 4',4,'2025-09-21','L','00:00:00','00:00:00','normal',NULL,NULL,NULL,'2025-09-21 01:45:20','2025-09-21 08:45:20'),(14,NULL,'Regu 3',3,'2025-09-21','S','16:00:00','00:00:00','normal',NULL,NULL,NULL,'2025-09-21 14:11:37','2025-09-21 21:11:37'),(15,NULL,'Regu 4',4,'2025-09-22','P','08:00:00','16:00:00','normal',NULL,NULL,NULL,'2025-09-21 22:55:06','2025-09-22 05:55:06'),(16,NULL,'Regu 1',1,'2025-09-23','P','08:00:00','16:00:00','normal',NULL,NULL,NULL,'2025-09-23 01:03:01','2025-09-23 08:03:01'),(17,NULL,'Regu 2',2,'2025-09-23','S','16:00:00','00:00:00','normal',NULL,NULL,NULL,'2025-09-23 15:36:17','2025-09-23 22:36:17'),(18,NULL,'Regu 3',3,'2025-09-23','M','00:00:00','08:00:00','normal',NULL,NULL,NULL,'2025-09-23 15:36:32','2025-09-23 22:36:32'),(19,NULL,'Regu 4',4,'2025-09-24','P','08:00:00','16:00:00','normal',NULL,NULL,NULL,'2025-09-23 22:48:07','2025-09-24 05:48:07'),(20,NULL,'Regu 1',1,'2025-09-29','P','08:00:00','16:00:00','normal',NULL,NULL,NULL,'2025-09-28 22:41:33','2025-09-29 05:41:33'),(21,NULL,'Regu 4',4,'2025-09-30','P','08:00:00','16:00:00','edited',NULL,NULL,'izin','2025-09-29 22:24:22','2025-09-30 05:43:07'),(22,NULL,'Regu 1',1,'2025-10-02','S','16:00:00','00:00:00','normal',NULL,NULL,NULL,'2025-10-02 11:36:01','2025-10-02 18:36:01');
/*!40000 ALTER TABLE `jadwal_satpam` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kunjungan`
--

DROP TABLE IF EXISTS `kunjungan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kunjungan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `gi_id` int DEFAULT '1',
  `no_kendaraan` varchar(100) DEFAULT NULL,
  `keperluan` varchar(100) DEFAULT NULL,
  `undangan` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'pending',
  `warna_kartu_visitor` varchar(50) DEFAULT NULL,
  `nomor_kartu_visitor` varchar(50) DEFAULT NULL,
  `jam_masuk` datetime DEFAULT NULL,
  `jam_keluar` datetime DEFAULT NULL,
  `nama_satpam_checkin` varchar(100) DEFAULT NULL,
  `satpam_id_checkin` int DEFAULT NULL,
  `nama_satpam_checkout` varchar(100) DEFAULT NULL,
  `verified_by_satpam_id` int DEFAULT NULL,
  `shift_id` int DEFAULT NULL,
  `verification_method` enum('face_recognition','manual') NOT NULL DEFAULT 'face_recognition',
  `satpam_id_checkout` int DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `face_match_score` float DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `idx_kunjungan_satpam_checkin` (`satpam_id_checkin`),
  KEY `idx_kunjungan_satpam_checkout` (`satpam_id_checkout`),
  KEY `idx_kunjungan_gi_status` (`gi_id`,`status`),
  KEY `idx_verified_by` (`verified_by_satpam_id`),
  KEY `idx_shift_id` (`shift_id`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kunjungan`
--

LOCK TABLES `kunjungan` WRITE;
/*!40000 ALTER TABLE `kunjungan` DISABLE KEYS */;
INSERT INTO `kunjungan` VALUES (13,8,1,'d 3422 CC','Rapat','1754304300_869a955b8ea84e3af057.pdf','approved','Biru','V001','2025-08-07 08:19:52',NULL,'Satpam Test',NULL,NULL,NULL,NULL,'face_recognition',NULL,'2025-08-04 10:45:00','2025-08-07 08:19:52',NULL),(14,9,1,'d 3422 CC','Kunjungan',NULL,'checkout','Kuning','001','2025-08-07 08:22:16','2025-08-07 08:22:31','Lidia',NULL,'Xx',NULL,NULL,'face_recognition',NULL,'2025-08-04 11:25:02','2025-08-07 08:22:31',NULL),(15,12,1,'d 3425 fg','Rapat',NULL,'approved','Hijau','004','2025-08-07 21:40:51',NULL,'Lidia',NULL,NULL,NULL,NULL,'face_recognition',NULL,'2025-08-04 16:01:11','2025-08-07 21:40:51',NULL),(16,13,1,'D 1111 ABS','Kunjungan',NULL,'pending',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'face_recognition',NULL,'2025-08-04 18:19:03','2025-08-04 18:19:03',NULL),(17,14,1,'sada','Kunjungan',NULL,'pending',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'face_recognition',NULL,'2025-08-06 23:44:22','2025-08-06 23:44:22',NULL),(18,14,1,'sada','Kunjungan',NULL,'pending',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'face_recognition',NULL,'2025-08-06 23:44:33','2025-08-06 23:44:33',NULL),(19,15,1,'D 1122 ABE','Kunjungan',NULL,'checkout','Hijau','001','2025-08-18 14:32:28','2025-08-18 14:32:55','Budi',NULL,'Asep',NULL,NULL,'face_recognition',NULL,'2025-08-18 05:26:47','2025-08-18 14:32:55',NULL),(20,15,1,'D 1122 ABE','Kunjungan',NULL,'checkout','Hijau','001','2025-08-20 14:17:47','2025-08-20 14:17:59','Budi',NULL,'Asep',NULL,NULL,'face_recognition',NULL,'2025-08-20 07:16:35','2025-08-20 14:17:59',NULL),(21,15,1,'D 1122 ABE','Kunjungan',NULL,'checkout','Biru','001','2025-08-20 14:20:45','2025-08-20 14:21:07','Budi',NULL,'Asep',NULL,NULL,'face_recognition',NULL,'2025-08-20 07:19:56','2025-08-20 14:21:07',NULL),(22,16,1,'D 1122 AB','Kunjungan',NULL,'checkout','Biru','10','2025-09-02 08:23:59','2025-09-02 08:24:23','Budi',NULL,'Asep',NULL,NULL,'face_recognition',NULL,'2025-09-02 01:21:29','2025-09-02 08:24:23',NULL),(23,15,1,'-','Kunjungan',NULL,'approved','Biru','11','2025-09-02 08:33:00',NULL,'Ade',NULL,NULL,NULL,NULL,'face_recognition',NULL,'2025-09-02 01:30:51','2025-09-02 08:33:00',NULL),(24,15,1,'D 1122 ABE','Kunjungan',NULL,'checkout','Biru','11','2025-09-02 09:22:07','2025-09-19 16:48:59','Ade',NULL,'Asep',NULL,NULL,'face_recognition',NULL,'2025-09-02 02:20:55','2025-09-19 16:48:59',NULL),(25,15,1,'D 1122 ABE','Kunjungan',NULL,'approved','Biru','10','2025-09-19 16:47:59',NULL,'Budi',NULL,NULL,NULL,NULL,'face_recognition',NULL,'2025-09-10 13:01:28','2025-09-19 16:47:59',NULL),(26,15,1,'D 1122 ABE','Kunjungan',NULL,'pending',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'face_recognition',NULL,'2025-09-21 09:43:04','2025-09-21 09:43:04',NULL);
/*!40000 ALTER TABLE `kunjungan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `version` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `class` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `group` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `namespace` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `time` int NOT NULL,
  `batch` int unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2025-09-16-031428','App\\Database\\Migrations\\AddFaceVerificationColumns','default','App',1757992471,1),(2,'2025-09-16-031430','App\\Database\\Migrations\\CreateFaceVerificationLogs','default','App',1757992471,1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `satpam`
--

DROP TABLE IF EXISTS `satpam`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `satpam` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `no_hp` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_general_ci,
  `gi_id` int DEFAULT NULL,
  `regu_number` int DEFAULT NULL,
  `is_koordinator` tinyint(1) NOT NULL DEFAULT '0',
  `status` enum('active','inactive','replacement') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`email`),
  UNIQUE KEY `email` (`email`),
  KEY `idx_regu_number` (`regu_number`),
  KEY `idx_koordinator` (`is_koordinator`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `satpam`
--

LOCK TABLES `satpam` WRITE;
/*!40000 ALTER TABLE `satpam` DISABLE KEYS */;
INSERT INTO `satpam` VALUES (1,'DIAN H','dian.h@gitet.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',NULL,NULL,1,NULL,1,'active','2025-09-18 02:12:52','2025-09-18 09:12:52'),(2,'DIDIN S',NULL,NULL,NULL,NULL,1,1,0,'active','2025-09-18 02:12:52','2025-09-18 09:12:52'),(3,'SANDI PURNAMA',NULL,NULL,NULL,NULL,1,1,0,'active','2025-09-18 02:12:52','2025-09-18 09:12:52'),(4,'DIK DIK',NULL,NULL,NULL,NULL,1,1,0,'active','2025-09-18 02:12:52','2025-09-18 09:12:52'),(6,'ARI H',NULL,NULL,NULL,NULL,1,2,0,'active','2025-09-18 02:12:52','2025-09-18 09:12:52'),(7,'M. WIJATHMINTA',NULL,NULL,NULL,NULL,1,2,0,'active','2025-09-18 02:12:52','2025-09-18 09:12:52'),(8,'AHMAD RIFA F',NULL,NULL,NULL,NULL,1,2,0,'active','2025-09-18 02:12:52','2025-09-18 09:12:52'),(10,'AAN',NULL,NULL,NULL,NULL,1,3,0,'active','2025-09-18 02:12:52','2025-09-18 09:12:52'),(11,'ATIF HIDAYAT',NULL,NULL,NULL,NULL,1,3,0,'active','2025-09-18 02:12:52','2025-09-18 09:12:52'),(12,'DADANG S',NULL,NULL,NULL,NULL,1,3,0,'active','2025-09-18 02:12:52','2025-09-18 09:12:52'),(14,'TAUFIK Z',NULL,NULL,NULL,NULL,1,4,0,'active','2025-09-18 02:12:52','2025-09-18 09:12:52'),(15,'ABDUL AZIZ',NULL,NULL,NULL,NULL,1,4,0,'active','2025-09-18 02:12:52','2025-09-18 09:12:52'),(16,'DIAN EFFENDI',NULL,NULL,NULL,NULL,1,4,0,'active','2025-09-18 02:12:52','2025-09-18 09:12:52');
/*!40000 ALTER TABLE `satpam` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `satpam_absensi`
--

DROP TABLE IF EXISTS `satpam_absensi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `satpam_absensi` (
  `id` int NOT NULL AUTO_INCREMENT,
  `satpam_id` int NOT NULL,
  `tanggal` date NOT NULL,
  `jam_masuk` timestamp NULL DEFAULT NULL,
  `jam_keluar` timestamp NULL DEFAULT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `status` enum('masuk','keluar','tidak_hadir') COLLATE utf8mb4_unicode_ci DEFAULT 'masuk',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_satpam_date` (`satpam_id`,`tanggal`),
  KEY `idx_tanggal` (`tanggal`),
  KEY `idx_satpam_tanggal` (`satpam_id`,`tanggal`),
  KEY `idx_satpam_absensi_date` (`tanggal`,`satpam_id`),
  CONSTRAINT `satpam_absensi_ibfk_1` FOREIGN KEY (`satpam_id`) REFERENCES `satpam` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `satpam_absensi`
--

LOCK TABLES `satpam_absensi` WRITE;
/*!40000 ALTER TABLE `satpam_absensi` DISABLE KEYS */;
/*!40000 ALTER TABLE `satpam_absensi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `satpam_shifts`
--

DROP TABLE IF EXISTS `satpam_shifts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `satpam_shifts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `satpam_id` int NOT NULL,
  `tanggal_shift` date NOT NULL,
  `shift_start` time NOT NULL,
  `shift_end` time NOT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_satpam_date` (`satpam_id`,`tanggal_shift`),
  KEY `idx_tanggal_shift` (`tanggal_shift`),
  KEY `idx_satpam_tanggal` (`satpam_id`,`tanggal_shift`),
  KEY `idx_satpam_shifts_date_time` (`tanggal_shift`,`shift_start`,`shift_end`),
  CONSTRAINT `satpam_shifts_ibfk_1` FOREIGN KEY (`satpam_id`) REFERENCES `satpam` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `satpam_shifts`
--

LOCK TABLES `satpam_shifts` WRITE;
/*!40000 ALTER TABLE `satpam_shifts` DISABLE KEYS */;
/*!40000 ALTER TABLE `satpam_shifts` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `validate_shift_schedule` BEFORE INSERT ON `satpam_shifts` FOR EACH ROW BEGIN
    DECLARE conflict_count INT DEFAULT 0;
    
    -- Cek konflik waktu shift dengan satpam lain pada tanggal yang sama
    SELECT COUNT(*) INTO conflict_count
    FROM satpam_shifts 
    WHERE tanggal_shift = NEW.tanggal_shift
    AND satpam_id != NEW.satpam_id
    AND (
        (NEW.shift_start < shift_end AND NEW.shift_end > shift_start)
        OR (shift_start < NEW.shift_end AND shift_end > NEW.shift_start)
    );
    
    IF conflict_count > 0 THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'Konflik jadwal shift dengan satpam lain pada waktu yang sama';
    END IF;
    
    -- Validasi shift_end harus setelah shift_start (kecuali shift malam)
    IF NEW.shift_start >= NEW.shift_end AND NEW.shift_start < '22:00:00' THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'Waktu selesai shift harus setelah waktu mulai';
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `shift_members`
--

DROP TABLE IF EXISTS `shift_members`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `shift_members` (
  `id` int NOT NULL AUTO_INCREMENT,
  `jadwal_id` int NOT NULL,
  `satpam_id` int NOT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT '1',
  `replacement_for` int DEFAULT NULL COMMENT 'ID satpam yang diganti',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_jadwal_id` (`jadwal_id`),
  KEY `idx_satpam_id` (`satpam_id`),
  KEY `idx_available` (`is_available`),
  KEY `idx_replacement` (`replacement_for`)
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shift_members`
--

LOCK TABLES `shift_members` WRITE;
/*!40000 ALTER TABLE `shift_members` DISABLE KEYS */;
INSERT INTO `shift_members` VALUES (1,1,1,1,NULL,'Koordinator - bisa masuk semua regu','2025-09-18 09:17:38','2025-09-18 09:17:38'),(2,1,2,1,NULL,'Anggota Regu 1','2025-09-18 09:17:38','2025-09-18 09:17:38'),(3,1,3,1,NULL,'Anggota Regu 1','2025-09-18 09:17:38','2025-09-18 09:17:38'),(4,1,4,1,NULL,'Anggota Regu 1','2025-09-18 09:17:38','2025-09-18 09:17:38'),(5,1,5,1,NULL,'Anggota Regu 1','2025-09-18 09:17:38','2025-09-18 09:17:38'),(8,2,1,1,NULL,'Koordinator - bisa masuk semua regu','2025-09-18 09:17:59','2025-09-18 09:17:59'),(9,2,6,1,NULL,'Anggota Regu 2','2025-09-18 09:17:59','2025-09-18 09:17:59'),(10,2,7,1,NULL,'Anggota Regu 2','2025-09-18 09:17:59','2025-09-18 09:17:59'),(11,2,8,1,NULL,'Anggota Regu 2','2025-09-18 09:17:59','2025-09-18 09:17:59'),(12,2,9,1,NULL,'Anggota Regu 2','2025-09-18 09:17:59','2025-09-18 09:17:59'),(15,3,1,1,NULL,'Koordinator - bisa masuk semua regu','2025-09-18 09:18:32','2025-09-18 09:18:32'),(16,3,10,1,NULL,'Anggota Regu 3','2025-09-18 09:18:32','2025-09-18 09:18:32'),(17,3,11,1,NULL,'Anggota Regu 3','2025-09-18 09:18:32','2025-09-18 09:18:32'),(18,3,12,1,NULL,'Anggota Regu 3','2025-09-18 09:18:32','2025-09-18 09:18:32'),(19,3,13,1,NULL,'Anggota Regu 3','2025-09-18 09:18:32','2025-09-18 09:18:32'),(22,4,1,1,NULL,'Koordinator standby saat regu libur','2025-09-18 09:18:46','2025-09-18 09:18:46'),(23,9,8,1,NULL,NULL,'2025-09-20 13:38:57','2025-09-20 13:38:57'),(24,9,9,1,NULL,NULL,'2025-09-20 13:38:57','2025-09-20 13:38:57'),(25,9,10,1,NULL,NULL,'2025-09-20 13:38:57','2025-09-20 13:38:57'),(26,9,1,1,NULL,NULL,'2025-09-20 13:38:57','2025-09-20 13:38:57'),(27,10,11,1,NULL,NULL,'2025-09-20 23:28:34','2025-09-20 23:28:34'),(28,10,12,1,NULL,NULL,'2025-09-20 23:28:34','2025-09-20 23:28:34'),(29,10,13,1,NULL,NULL,'2025-09-20 23:28:34','2025-09-20 23:28:34'),(30,11,2,1,NULL,NULL,'2025-09-21 00:17:27','2025-09-21 00:17:27'),(31,11,3,1,NULL,NULL,'2025-09-21 00:17:27','2025-09-21 00:17:27'),(32,11,4,1,NULL,NULL,'2025-09-21 00:17:27','2025-09-21 00:17:27'),(33,12,5,1,NULL,NULL,'2025-09-21 08:44:26','2025-09-21 08:44:26'),(34,12,6,1,NULL,NULL,'2025-09-21 08:44:26','2025-09-21 08:44:26'),(35,12,7,1,NULL,NULL,'2025-09-21 08:44:26','2025-09-21 08:44:26'),(36,12,1,1,NULL,NULL,'2025-09-21 08:44:26','2025-09-21 08:44:26'),(37,13,1,1,NULL,NULL,'2025-09-21 08:45:20','2025-09-21 08:45:20'),(38,14,8,1,NULL,NULL,'2025-09-21 21:11:37','2025-09-21 21:11:37'),(39,14,9,1,NULL,NULL,'2025-09-21 21:11:37','2025-09-21 21:11:37'),(40,14,10,1,NULL,NULL,'2025-09-21 21:11:37','2025-09-21 21:11:37'),(41,14,1,1,NULL,NULL,'2025-09-21 21:11:37','2025-09-21 21:11:37'),(42,15,11,1,NULL,NULL,'2025-09-22 05:55:06','2025-09-22 05:55:06'),(43,15,12,1,NULL,NULL,'2025-09-22 05:55:06','2025-09-22 05:55:06'),(44,15,13,1,NULL,NULL,'2025-09-22 05:55:06','2025-09-22 05:55:06'),(45,15,1,1,NULL,NULL,'2025-09-22 05:55:06','2025-09-22 05:55:06'),(46,16,2,1,NULL,NULL,'2025-09-23 08:03:01','2025-09-23 08:03:01'),(47,16,3,1,NULL,NULL,'2025-09-23 08:03:01','2025-09-23 08:03:01'),(48,16,4,1,NULL,NULL,'2025-09-23 08:03:01','2025-09-23 08:03:01'),(49,16,1,1,NULL,NULL,'2025-09-23 08:03:01','2025-09-23 08:03:01'),(50,17,5,1,NULL,NULL,'2025-09-23 22:36:17','2025-09-23 22:36:17'),(51,17,6,1,NULL,NULL,'2025-09-23 22:36:17','2025-09-23 22:36:17'),(52,17,7,1,NULL,NULL,'2025-09-23 22:36:17','2025-09-23 22:36:17'),(53,18,8,1,NULL,NULL,'2025-09-23 22:36:32','2025-09-23 22:36:32'),(54,18,9,1,NULL,NULL,'2025-09-23 22:36:32','2025-09-23 22:36:32'),(55,18,10,1,NULL,NULL,'2025-09-23 22:36:32','2025-09-23 22:36:32'),(56,19,11,1,NULL,NULL,'2025-09-24 05:48:07','2025-09-24 05:48:07'),(57,19,12,1,NULL,NULL,'2025-09-24 05:48:07','2025-09-24 05:48:07'),(58,19,13,1,NULL,NULL,'2025-09-24 05:48:07','2025-09-24 05:48:07'),(59,19,1,1,NULL,NULL,'2025-09-24 05:48:07','2025-09-24 05:48:07'),(60,20,2,1,NULL,NULL,'2025-09-29 05:41:33','2025-09-29 05:41:33'),(61,20,3,1,NULL,NULL,'2025-09-29 05:41:33','2025-09-29 05:41:33'),(62,20,4,1,NULL,NULL,'2025-09-29 05:41:33','2025-09-29 05:41:33'),(63,20,1,1,NULL,NULL,'2025-09-29 05:41:33','2025-09-29 05:41:33'),(71,21,14,1,NULL,NULL,'2025-09-30 05:43:07','2025-09-30 05:43:07'),(72,21,15,1,NULL,NULL,'2025-09-30 05:43:07','2025-09-30 05:43:07'),(73,21,16,1,NULL,NULL,'2025-09-30 05:43:07','2025-09-30 05:43:07'),(74,22,2,1,NULL,NULL,'2025-10-02 18:36:01','2025-10-02 18:36:01'),(75,22,3,1,NULL,NULL,'2025-10-02 18:36:01','2025-10-02 18:36:01'),(76,22,4,1,NULL,NULL,'2025-10-02 18:36:01','2025-10-02 18:36:01'),(77,22,1,1,NULL,NULL,'2025-10-02 18:36:01','2025-10-02 18:36:01');
/*!40000 ALTER TABLE `shift_members` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `asal_unit` varchar(50) NOT NULL,
  `asal_unit_lain` varchar(100) DEFAULT NULL,
  `alamat` text NOT NULL,
  `no_hp` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `foto_identitas` varchar(255) DEFAULT NULL,
  `foto_selfie` varchar(255) DEFAULT NULL,
  `no_kendaraan` varchar(100) DEFAULT NULL,
  `keperluan` varchar(100) DEFAULT NULL,
  `detail_keperluan` text,
  `file_undangan` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (8,'Lidia Permata Rahma','',NULL,'Bandung','62895707992888','lidia1@gmail.com','$2y$10$UVaFiXd/CY4ulVpb8CrYwu950XoRTGpfOyQLPk3tPx8qHc9bZspf.','1754304300_84286f450e19fc73c738.jpg',NULL,'d 3422 CC','Rapat','BIMTEK B3',NULL,'2025-08-04 10:45:00','2025-08-04 03:45:00'),(9,'Sentosa','',NULL,'Bandung Timur','62895707992888','crm.patner4@gmail.com','$2y$10$oLnh1kMBkgsFYyh1NtLqYOuEH6Qx43.oQLBmM2iOETW45/qW3X6Nm','1754306702_b3de210fd978aeaa5021.jpg',NULL,'d 3422 CC','Kunjungan','PKL',NULL,'2025-08-04 11:25:02','2025-08-04 04:25:02'),(10,'Aura Aulia','UPT','UPT Bandung','Bandung','62895707992888','aur12@gmail.com','$2y$10$NfZhVFhyNd8G4gDRIi3OTOp7uJ2FB2PqdHYqVK5MOuvXm0iLewE6C','1754322072_92d2ae563d56e35f4de9.jpg',NULL,'Z 4848 PP','Kunjungan','Inspeksi',NULL,'2025-08-04 15:41:12','2025-08-04 15:41:12'),(11,'Auila','UPT','Bekasi','Bekasi','6288224898731','aul12@gmail.com','$2y$10$dBbcuby3rFLL1jrO4cObEOlP2T7JM026WpJUchP9h7J8Dx40A/JHi','1754322879_231830de117a6037861d.jpg','1754322879_4af468828f5f13962218.jpeg','B 4888 JK','Kunjungan','Bertemu SRM',NULL,'2025-08-04 15:54:39','2025-08-04 15:54:39'),(12,'Auila Rahma','UPT','Karawang','Karawang','6288224898731','aull2@gmail.com','$2y$10$/rWMN8tmsOA3bmr4g.8yHe1Vaae7wd7EvxI54JukXUu5zphfbga/q','1754323271_abeed297078f3a47b711.jpg','1754323271_12fbc1ed9e8b3ab72559.jpeg','d 3425 fg','Rapat','Pembahasan Audit Internal',NULL,'2025-08-04 16:01:11','2025-08-04 16:01:11'),(13,'Permata Rahma','UPT','Bandung','Bandung','62895707992888','rahmalidia@gmail.com','$2y$10$0.K7wEAxfY/6CcIYJVqTget8MlHAkKT0SYRB/LD6XNWm7WegqQgGy','1752225965_7b8af435a52bf471da96.png','1754331543_a76c7f607f41ab6db9c9.jpg','D 1111 ABS','Kunjungan','Bertemu pak agil',NULL,'2025-08-04 18:19:03','2025-08-14 07:44:01'),(14,'Maheswara rivai jamhur','UPT','upt bandung','Jl. Bangka 8c no 54 rt5/rw3 jakarta Selatan','085731648455','rivaijamhur@yahoo.com','$2y$10$NAr8LnLwfJpFab2as5k1ruy3W1hPEhEvKxZIAJ/WoWVGKtQqBYk0.','1752225965_7b8af435a52bf471da96.png','1754523862_7deb7742c2cc42205ae9.png','sada','Kunjungan','sasda',NULL,'2025-08-06 23:44:22','2025-08-14 07:44:01'),(15,'Permata Rahma','UPT','UPT BANDUNG','BANDNG','62895707992888','permata@gmail.com','$2y$10$OO5MN22pddjFA6OWRzt2COzzp3DasNzPg.pBBD7pQkL0jZtq/77l6','1755494807_5819b52ef151d8c2762c.jpeg','1755494807_192a43e02dbe6b60684a.jpeg','D 1122 ABE','Kunjungan','ESDM',NULL,'2025-08-18 05:26:47','2025-08-18 05:26:47'),(16,'Rahma Permata','UPT','UPT BANDUNG','Cijerah','62895707992888','rahmaa1@gmail.com','$2y$10$Fn6g45HeMH10jW3/2qSyq.rswULaUhho2Kau7Y6CC7pqUCXgAg1ry','1756776089_4dbb5af9f1ce4eb0a3d9.jpeg','1756776089_14747ae5293494b3ed3a.jpeg','D 1122 AB','Kunjungan','Bertemu ESDM ',NULL,'2025-09-02 01:21:29','2025-09-02 01:21:29');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_satpam`
--

DROP TABLE IF EXISTS `users_satpam`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users_satpam` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('koordinator','regu') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'regu',
  `regu_number` int DEFAULT NULL,
  `gi_id` int NOT NULL DEFAULT '1',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `idx_role` (`role`),
  KEY `idx_regu_number` (`regu_number`),
  KEY `idx_active` (`is_active`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_satpam`
--

LOCK TABLES `users_satpam` WRITE;
/*!40000 ALTER TABLE `users_satpam` DISABLE KEYS */;
INSERT INTO `users_satpam` VALUES (6,'koordinator','dian.h@gitet.com','$2y$10$xpMwRjri9kWwcMl.VgETmuI6m.DUG5ymBH73YA.2lyiCd267pSrGm','koordinator',NULL,1,1,'2025-09-18 09:31:56','2025-09-18 09:57:00'),(7,'regu1','regu1@gitet.com','$2y$10$xpMwRjri9kWwcMl.VgETmuI6m.DUG5ymBH73YA.2lyiCd267pSrGm','regu',1,1,1,'2025-09-18 09:31:56','2025-09-18 09:57:38'),(8,'regu2','regu2@gitet.com','$2y$10$xpMwRjri9kWwcMl.VgETmuI6m.DUG5ymBH73YA.2lyiCd267pSrGm','regu',2,1,1,'2025-09-18 09:31:56','2025-09-18 09:57:46'),(9,'regu3','regu3@gitet.com','$2y$10$xpMwRjri9kWwcMl.VgETmuI6m.DUG5ymBH73YA.2lyiCd267pSrGm','regu',3,1,1,'2025-09-18 09:31:56','2025-09-18 09:57:54'),(10,'regu4','regu4@gitet.com','$2y$10$xpMwRjri9kWwcMl.VgETmuI6m.DUG5ymBH73YA.2lyiCd267pSrGm','regu',4,1,1,'2025-09-18 09:31:56','2025-09-18 09:58:02');
/*!40000 ALTER TABLE `users_satpam` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary view structure for view `view_kunjungan_detail`
--

DROP TABLE IF EXISTS `view_kunjungan_detail`;
/*!50001 DROP VIEW IF EXISTS `view_kunjungan_detail`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `view_kunjungan_detail` AS SELECT 
 1 AS `id`,
 1 AS `user_id`,
 1 AS `gi_id`,
 1 AS `nama_tamu`,
 1 AS `no_hp`,
 1 AS `no_kendaraan`,
 1 AS `keperluan`,
 1 AS `status`,
 1 AS `jam_masuk`,
 1 AS `jam_keluar`,
 1 AS `nama_satpam_checkin`,
 1 AS `detail_satpam_checkin`,
 1 AS `nama_satpam_checkout`,
 1 AS `detail_satpam_checkout`,
 1 AS `warna_kartu_visitor`,
 1 AS `nomor_kartu_visitor`,
 1 AS `created_at`,
 1 AS `updated_at`,
 1 AS `durasi_kunjungan`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `view_satpam_shift_monitoring`
--

DROP TABLE IF EXISTS `view_satpam_shift_monitoring`;
/*!50001 DROP VIEW IF EXISTS `view_satpam_shift_monitoring`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `view_satpam_shift_monitoring` AS SELECT 
 1 AS `satpam_id`,
 1 AS `nama_satpam`,
 1 AS `email`,
 1 AS `no_hp`,
 1 AS `gi_id`,
 1 AS `shift_id`,
 1 AS `tanggal_shift`,
 1 AS `shift_start`,
 1 AS `shift_end`,
 1 AS `keterangan_shift`,
 1 AS `absensi_id`,
 1 AS `jam_masuk`,
 1 AS `jam_keluar`,
 1 AS `keterangan_absensi`,
 1 AS `status_absensi`,
 1 AS `status_kehadiran`,
 1 AS `status_shift_saat_ini`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `zona_gi`
--

DROP TABLE IF EXISTS `zona_gi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `zona_gi` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama_gi` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `ultg_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ultg_id` (`ultg_id`),
  CONSTRAINT `zona_gi_ibfk_1` FOREIGN KEY (`ultg_id`) REFERENCES `zona_ultg` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=103 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zona_gi`
--

LOCK TABLES `zona_gi` WRITE;
/*!40000 ALTER TABLE `zona_gi` DISABLE KEYS */;
INSERT INTO `zona_gi` VALUES (1,'GI 150KV BANDUNG UTARA',1),(2,'GI 150KV CIANJUR',1),(3,'GI 150KV CIBEUREUM BARU',1),(4,'GI 150KV CIGERELENG',1),(5,'GI 150KV PADALARANG BARU',1),(6,'GI 150KV PANASIA',1),(7,'GI 150KV RAJAMANDALA',1),(8,'GI 150KV SUKALUYU',1),(9,'GI 70KV CIGERELENG',1),(10,'GIS 150KV BRAGA',1),(11,'GIS 150KV CIBABAT',1),(12,'GIS 150KV CIBABAT BARU',1),(13,'GIS 150kV DAYEUHKOLOT',1),(14,'GIS 150KV KIARACONDONG',1),(15,'GISTET 500KV SAGULING',1),(16,'GI 150KV BANDUNG SELATAN',2),(17,'GI 150KV LAGADAR',2),(18,'GI 150KV PATUHA',2),(19,'GI 150KV WAYANG WINDU',2),(20,'GI 30KV PLENGAN',2),(21,'GI 70KV CIKALONG',2),(22,'GI 70KV LAMAJAN',2),(23,'GI 70KV SANTOSA',2),(24,'GI 70KV WAYANG WINDU',2),(25,'GITET 500KV BANDUNG SELATAN',2),(26,'GI 150KV CIKASUNGKA',3),(27,'GI 150KV DAGOPAKAR',3),(28,'GI 150KV NEW RANCAKASUMBA',3),(29,'GI 150KV RANCAEKEK',3),(30,'GI 150KV RANCAKASUMBA',3),(31,'GI 150KV UJUNGBERUNG',3),(32,'GI 70KV MAJALAYA',3),(33,'GI 70KV SUMEDANG',3),(34,'GI 70KV UJUNGBERUNG',3),(35,'GIS 150KV BANDUNG TIMUR',3),(36,'GIS 150KV GEDEBAGE',3),(37,'GITET 500KV NEW UJUNGBERUNG',3),(38,'GI 150KV AMPEL',4),(39,'GI 150KV BANYUDONO',4),(40,'GI 150KV BAWEN',4),(41,'GI 150KV BOYOLALI',4),(42,'GI 150KV BRINGIN',4),(43,'GI 150KV JELOK',4),(44,'GI 150KV MOJOSONGO',4),(45,'GI 150KV SANGGRAHAN',4),(46,'GI 150KV SECANG',4),(47,'GITET 500KV BOYOLALI',4),(48,'GI 30KV PLTA JELOK',4),(49,'GI 150KV BANTUL',5),(50,'GI 150KV GODEAN',5),(51,'GI 150KV KALASAN',5),(52,'GI 150KV KENTUNGAN',5),(53,'GI 150KV KLATEN',5),(54,'GI 150KV MEDARI',5),(55,'GI 150KV PURWOREJO',5),(56,'GI 150KV SEMANU',5),(57,'GI 150KV WATES',5),(58,'GIS 150KV GEJAYAN',5),(59,'GIS 150KV WIROBRAJAN',5),(60,'GI 150KV GONDANGREJO',6),(61,'GI 150KV JAJAR',6),(62,'GI 150KV MASARAN',6),(63,'GI 150KV NGUNTORONADI',6),(64,'GI 150KV PALUR',6),(65,'GI 150KV PEDAN',6),(66,'GI 150KV RAYON UTAMA MAKMUR',6),(67,'GI 150KV SOLOBARU',6),(68,'GI 150KV SRAGEN',6),(69,'GI 150KV WONOGIRI',6),(70,'GI 150KV WONOSARI',6),(71,'GIS 150KV MANGKUNEGARAN',6),(72,'GITET 500KV PEDAN',6),(73,'GI 150KV BUMI SEMARANG BARU',7),(74,'GI 150KV KALIWUNGU',7),(75,'GI 150KV KRAPYAK',7),(76,'GI 150KV MRANGGEN',7),(77,'GI 150KV PANDEANLAMPER',7),(78,'GI 150KV SRONDOL',7),(79,'GI 150KV TAMBAKLOROK',7),(80,'GI 150KV UNGARAN',7),(81,'GI 150KV WELERI',7),(82,'GIS 150KV KALISARI',7),(83,'GIS 150KV PUDAKPAYUNG',7),(84,'GIS 150KV RANDUGARUT',7),(85,'GIS 150KV SIMPANGLIMA',7),(86,'GIS 150KV TAMBAKLOROK',7),(87,'GIS 150kV TAMBAKLOROK III',7),(88,'GITET 500KV UNGARAN',7),(89,'GI 150KV BLORA',8),(90,'GI 150KV CEPU',8),(91,'GI 150KV PATI',8),(92,'GI 150KV PLTU REMBANG (SLUKE)',8),(93,'GI 150KV REMBANG',8),(94,'GI 150KV SEMEN INDONESIA',8),(95,'GI 150KV JEKULO',9),(96,'GI 150KV JEPARA',9),(97,'GI 150KV KEDUNGOMBO',9),(98,'GI 150KV KUDUS',9),(99,'GI 150KV PURWODADI',9),(100,'GI 150KV SAYUNG',9),(101,'GI 150KV SEMEN GROBOGAN',9),(102,'GI 150KV TANJUNG JATI',9);
/*!40000 ALTER TABLE `zona_gi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zona_ultg`
--

DROP TABLE IF EXISTS `zona_ultg`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `zona_ultg` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama_ultg` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `upt_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `upt_id` (`upt_id`),
  CONSTRAINT `zona_ultg_ibfk_1` FOREIGN KEY (`upt_id`) REFERENCES `zona_upt` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zona_ultg`
--

LOCK TABLES `zona_ultg` WRITE;
/*!40000 ALTER TABLE `zona_ultg` DISABLE KEYS */;
INSERT INTO `zona_ultg` VALUES (1,'BANDUNG BARAT',1),(2,'BANDUNG SELATAN',1),(3,'BANDUNG TIMUR',1),(4,'SALATIGA',2),(5,'YOGYAKARTA',2),(6,'SURAKARTA',2),(7,'SEMARANG',3),(8,'REMBANG',3),(9,'KUDUS',3);
/*!40000 ALTER TABLE `zona_ultg` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zona_upt`
--

DROP TABLE IF EXISTS `zona_upt`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `zona_upt` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama_upt` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zona_upt`
--

LOCK TABLES `zona_upt` WRITE;
/*!40000 ALTER TABLE `zona_upt` DISABLE KEYS */;
INSERT INTO `zona_upt` VALUES (1,'BANDUNG'),(2,'SALATIGA'),(3,'SEMARANG'),(4,'BANDUNG'),(5,'SALATIGA'),(6,'SEMARANG');
/*!40000 ALTER TABLE `zona_upt` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Final view structure for view `view_kunjungan_detail`
--

/*!50001 DROP VIEW IF EXISTS `view_kunjungan_detail`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_kunjungan_detail` AS select `k`.`id` AS `id`,`k`.`user_id` AS `user_id`,`k`.`gi_id` AS `gi_id`,`u`.`nama` AS `nama_tamu`,`u`.`no_hp` AS `no_hp`,`u`.`no_kendaraan` AS `no_kendaraan`,`u`.`keperluan` AS `keperluan`,`k`.`status` AS `status`,`k`.`jam_masuk` AS `jam_masuk`,`k`.`jam_keluar` AS `jam_keluar`,`k`.`nama_satpam_checkin` AS `nama_satpam_checkin`,`s1`.`nama` AS `detail_satpam_checkin`,`k`.`nama_satpam_checkout` AS `nama_satpam_checkout`,`s2`.`nama` AS `detail_satpam_checkout`,`k`.`warna_kartu_visitor` AS `warna_kartu_visitor`,`k`.`nomor_kartu_visitor` AS `nomor_kartu_visitor`,`k`.`created_at` AS `created_at`,`k`.`updated_at` AS `updated_at`,(case when ((`k`.`jam_masuk` is not null) and (`k`.`jam_keluar` is not null)) then timediff(`k`.`jam_keluar`,`k`.`jam_masuk`) else NULL end) AS `durasi_kunjungan` from (((`kunjungan` `k` left join `users` `u` on((`u`.`id` = `k`.`user_id`))) left join `satpam` `s1` on((`s1`.`id` = `k`.`satpam_id_checkin`))) left join `satpam` `s2` on((`s2`.`id` = `k`.`satpam_id_checkout`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_satpam_shift_monitoring`
--

/*!50001 DROP VIEW IF EXISTS `view_satpam_shift_monitoring`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_satpam_shift_monitoring` AS select `s`.`id` AS `satpam_id`,`s`.`nama` AS `nama_satpam`,`s`.`email` AS `email`,`s`.`no_hp` AS `no_hp`,`s`.`gi_id` AS `gi_id`,`ss`.`id` AS `shift_id`,`ss`.`tanggal_shift` AS `tanggal_shift`,`ss`.`shift_start` AS `shift_start`,`ss`.`shift_end` AS `shift_end`,`ss`.`keterangan` AS `keterangan_shift`,`sa`.`id` AS `absensi_id`,`sa`.`jam_masuk` AS `jam_masuk`,`sa`.`jam_keluar` AS `jam_keluar`,`sa`.`keterangan` AS `keterangan_absensi`,`sa`.`status` AS `status_absensi`,(case when (`sa`.`jam_masuk` is null) then 'Belum Absen' when (`sa`.`jam_keluar` is null) then 'Sedang Bertugas' else 'Selesai Tugas' end) AS `status_kehadiran`,(case when ((`ss`.`tanggal_shift` = curdate()) and (curtime() between `ss`.`shift_start` and `ss`.`shift_end`)) then 'Sedang Bertugas' when ((`ss`.`tanggal_shift` = curdate()) and (curtime() > `ss`.`shift_end`)) then 'Shift Selesai' when ((`ss`.`tanggal_shift` = curdate()) and (curtime() < `ss`.`shift_start`)) then 'Belum Mulai' else 'Tidak Bertugas Hari Ini' end) AS `status_shift_saat_ini` from ((`satpam` `s` left join `satpam_shifts` `ss` on(((`s`.`id` = `ss`.`satpam_id`) and (`ss`.`tanggal_shift` = curdate())))) left join `satpam_absensi` `sa` on(((`s`.`id` = `sa`.`satpam_id`) and (`sa`.`tanggal` = curdate())))) where (`s`.`status` = 'aktif') order by `ss`.`shift_start` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-10-02 22:19:05
