-- MySQL dump 10.13  Distrib 9.0.1, for macos14.7 (arm64)
--
-- Host: localhost    Database: Event-organizer
-- ------------------------------------------------------
-- Server version	9.0.1

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
-- Table structure for table `Users`
--

DROP TABLE IF EXISTS `Users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `birthdate` date NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Users`
--

LOCK TABLES `Users` WRITE;
/*!40000 ALTER TABLE `Users` DISABLE KEYS */;
/*!40000 ALTER TABLE `Users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-12-27 17:09:11

-- Table structure for table `events`

-- (трябва да се добави участници ако има организация)
DROP TABLE IF EXISTS `events`;
CREATE TABLE `events` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL COMMENT 'Заглавие на събитието',
  `event_date` DATE NOT NULL COMMENT 'Дата на събитието',
  `type` VARCHAR(255) DEFAULT 'Other' COMMENT 'Тип на събитието, напр. рожден ден',
  `visibility` VARCHAR(50) DEFAULT 'public' COMMENT 'Видимост на събитието: public, friends, private',
  `has_organization` BOOLEAN DEFAULT FALSE COMMENT 'Флаг дали събитието има активна организация',
  `organizer_id` INT DEFAULT NULL COMMENT 'ID на организатора, ако има организация',
  `is_anonymous` BOOLEAN DEFAULT FALSE COMMENT 'Флаг дали събитието е анонимно',
  `excluded_user_id` INT DEFAULT NULL COMMENT 'ID на потребителя, за когото е събитието, ако събитието е анонимно',
  PRIMARY KEY (`id`),
  FOREIGN KEY (`organizer_id`) REFERENCES `Users` (`id`),
  FOREIGN KEY (`excluded_user_id`) REFERENCES `Users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Table structure for table `event_invitations`

-- (Tрябва да се промени да бъде таблица която показва дали даден юзър е джойннал евента)
-- event id
-- joined user

DROP TABLE IF EXISTS `event_invitations`;
CREATE TABLE `event_invitations` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `event_id` INT NOT NULL COMMENT 'ID на свързаното събитие',
  `user_id` INT NOT NULL COMMENT 'ID на поканения потребител',
  `status` VARCHAR(50) DEFAULT 'pending' COMMENT 'Статус на поканата: pending, accepted, declined',
  PRIMARY KEY (`id`),
  FOREIGN KEY (`event_id`) REFERENCES `events` (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `Users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Table structure for table `gift_ideas`

DROP TABLE IF EXISTS `gift_ideas`;
CREATE TABLE `gift_ideas` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `event_id` INT NOT NULL COMMENT 'ID на свързаното събитие',
  `user_id` INT NOT NULL COMMENT 'ID на потребителя, който е предложил идеята',
  `idea` VARCHAR(255) NOT NULL COMMENT 'Описание на идеята за подарък',
  `votes` INT DEFAULT 0 COMMENT 'Брой гласове за идеята',
  PRIMARY KEY (`id`),
  FOREIGN KEY (`event_id`) REFERENCES `events` (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `Users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Table structure for table `comments`
DROP TABLE IF EXISTS `comments`;
CREATE TABLE `comments` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `target_id` INT NOT NULL COMMENT 'ID на свързания обект',
  `target_type` ENUM('event', 'gift_idea', 'fundraising', 'comment') NOT NULL COMMENT 'Тип на свързания обект',
  `user_id` INT NOT NULL COMMENT 'ID на автора на коментара',
  `content` TEXT NOT NULL COMMENT 'Текст на коментара',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Време на създаване',
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `Users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


-- Table structure for table `fundraising`

DROP TABLE IF EXISTS `fundraising`;
CREATE TABLE `fundraising` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `target_id` INT NOT NULL COMMENT 'ID на свързания обект за който се събират пари',
  `organizer_id` INT NOT NULL COMMENT 'ID на организатора на събирането',
  `target_amount` DECIMAL(10,2) NOT NULL COMMENT 'Целева сума за събиране',
  `current_amount` DECIMAL(10,2) DEFAULT 0 COMMENT 'Текущо събрана сума',
  `deadline` DATE NOT NULL COMMENT 'Краен срок за събиране',
  `payment_details` VARCHAR(255) COMMENT 'Информация за плащане',
  PRIMARY KEY (`id`),
  FOREIGN KEY (`organizer_id`) REFERENCES `Users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Table structure for table `follows`

DROP TABLE IF EXISTS `follows`;
CREATE TABLE `follows` (
  `user_id` INT NOT NULL COMMENT 'ID на потребителя',
  `follower_id` INT NOT NULL COMMENT 'ID на последователя',
  PRIMARY KEY (`user_id`, `follower_id`),
  FOREIGN KEY (`user_id`) REFERENCES `Users` (`id`),
  FOREIGN KEY (`follower_id`) REFERENCES `Users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Table structure for table `favorites`

DROP TABLE IF EXISTS `favorites`;
CREATE TABLE `favorites` (
  `user_id` INT NOT NULL COMMENT 'ID на потребителя',
  `follower_id` INT NOT NULL COMMENT 'ID на последователя, любим на потребителя',
  PRIMARY KEY (`user_id`, `follower_id`),
  FOREIGN KEY (`user_id`) REFERENCES `Users` (`id`),
  FOREIGN KEY (`follower_id`) REFERENCES `Users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
