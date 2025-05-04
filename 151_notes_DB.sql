CREATE DATABASE  IF NOT EXISTS `notes` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `notes`;
-- MySQL dump 10.13  Distrib 8.0.40, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: notes
-- ------------------------------------------------------
-- Server version	8.0.41

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `t_admin`
--

DROP TABLE IF EXISTS `t_admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `t_admin` (
  `pk_admin` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(35) NOT NULL,
  `prenom` varchar(35) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(64) NOT NULL,
  `dateNaissance` date NOT NULL,
  PRIMARY KEY (`pk_admin`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_admin`
--

LOCK TABLES `t_admin` WRITE;
/*!40000 ALTER TABLE `t_admin` DISABLE KEYS */;
INSERT INTO `t_admin` VALUES (1,'Alsawaf','Mazen','mazen.alsawaf@studentfr.ch','$2y$10$DuHDKh4TP0HaWKV7CPd1j.dD5eW/Se45D1ZJjFJttXks2usMvsMKm','2006-01-20'),(2,'Kays','Elif','kaya.elif@studentfr.ch','$2y$10$DuHDKh4TP0HaWKV7CPd1j.dD5eW/Se45D1ZJjFJttXks2usMvsMKm','2004-03-20'),(3,'Clerc','Donovan','clerc.donovan@studentfr.ch','$2y$10$PBO4MBMpQQLAz1ztnFLpXurlbdqBmQu6LYXOrwisTzbbzEkJYTolm','2005-02-17');
/*!40000 ALTER TABLE `t_admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_category`
--

DROP TABLE IF EXISTS `t_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `t_category` (
  `pk_category` int NOT NULL AUTO_INCREMENT,
  `category_name` varchar(25) NOT NULL,
  `description` varchar(100) NOT NULL,
  PRIMARY KEY (`pk_category`),
  UNIQUE KEY `type_UNIQUE` (`category_name`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_category`
--

LOCK TABLES `t_category` WRITE;
/*!40000 ALTER TABLE `t_category` DISABLE KEYS */;
INSERT INTO `t_category` VALUES (1,'Personal Thoughts','Self-reflections, musings, and day-to-day feelings or experiences.'),(2,'Motivational Quotes/Notes','Encouraging thoughts, personal goals, inspirational quotes, or affirmations.'),(3,'Ideas  Brainstorming','Creative ideas, brainstorming sessions, or concepts for projects.'),(4,'Technology  Coding','Notes on programming, technical tutorials, algorithms, code snippets, or tech trends.'),(5,'Education/Study Notes','ecture notes, study materials, summaries, or key takeaways from books and research.'),(6,'Health  Wellness','Fitness routines, diet plans, mental health tips, and wellness goals.'),(7,'Travel  Adventure','Itineraries, travel logs, bucket lists, or destination ideas.'),(8,'Recipes  Cooking\r \r ','Favorite recipes, cooking experiments, or meal planning.'),(9,'Hobbies  Leisure','Notes on personal interests such as books, movies, music, or craft projects.'),(10,'Relationships','Notes about friends, family, or love (e.g., important conversations, anniversaries, personal letters'),(11,'Finance  Budgeting\r ','Tracking expenses, saving goals, investment strategies, or financial planning.\n'),(12,'Life Goals  Plans','Long-term goals, career plans, personal growth objectives, or bucket list items.'),(13,'Shopping  Wishlist','Desired purchases, gift ideas, or product research.'),(14,'Anonymous Letters  Confe','Anonymous or private notes where users can express themselves without identifying details.\n'),(15,'Creative Writing\n','Poems, short stories, or any written work focused on creative expression.\n'),(16,'News  Information\r ','Articles, research, updates, or notes about current events, politics, or technology.\n'),(17,'Quotes  References\r ','Interesting quotes, references, or ideas from books, movies, or speeches.\n'),(18,'Animals  Nature\r ','Notes about pets, animals, nature observations, or environmental awareness.\n'),(19,'Spirituality  Religion\r ','Notes related to spiritual practices, reflections, religious teachings, or meditations.\n'),(20,'Events  Celebrations\r ','Details about upcoming events, birthdays, holidays, or special occasions.\n'),(21,'Anonymous Feedback/Compla','Private or anonymous feedback on situations, products, or services.\n');
/*!40000 ALTER TABLE `t_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_note`
--

DROP TABLE IF EXISTS `t_note`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `t_note` (
  `pk_note` int NOT NULL AUTO_INCREMENT,
  `title` varchar(20) NOT NULL,
  `message` longtext NOT NULL,
  `date` date NOT NULL,
  `likes` int DEFAULT '0',
  `fk_category` int NOT NULL,
  `fk_admin` int DEFAULT NULL,
  `time` time NOT NULL,
  PRIMARY KEY (`pk_note`),
  UNIQUE KEY `titel_UNIQUE` (`title`),
  KEY `fk_category_idx` (`fk_category`),
  KEY `fk_admin_idx` (`fk_admin`),
  CONSTRAINT `fk_admin` FOREIGN KEY (`fk_admin`) REFERENCES `t_admin` (`pk_admin`),
  CONSTRAINT `fk_category` FOREIGN KEY (`fk_category`) REFERENCES `t_category` (`pk_category`)
) ENGINE=InnoDB AUTO_INCREMENT=125 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_note`
--

LOCK TABLES `t_note` WRITE;
/*!40000 ALTER TABLE `t_note` DISABLE KEYS */;
INSERT INTO `t_note` VALUES (114,'App Concept','A to-do list app that changes background color based on urgency. Use AI to suggest task priorities.','2025-02-25',3,3,NULL,'02:36:10'),(117,'SQL Injection Notes','Always use prepared statements to prevent SQL Injection. Never trust user input directly in queries.','2025-02-25',10,5,NULL,'02:37:14'),(119,'Don\'t Forget','The key is not where you think it is. Look again.','2025-02-25',4,14,NULL,'02:38:11'),(120,'It\'s Coming','You have less time than you think. Be ready.','2025-02-25',1,21,NULL,'02:38:47'),(121,'What If?','If things had gone differently that night, would we still be here?','2025-02-25',0,1,NULL,'02:41:33'),(124,'mmmmmmmm','gggg','2025-05-04',0,14,1,'14:26:14');
/*!40000 ALTER TABLE `t_note` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-05-04 15:25:21
