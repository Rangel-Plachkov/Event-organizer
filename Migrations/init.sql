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


DROP TABLE IF EXISTS `events`;
CREATE TABLE `events` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL COMMENT 'Заглавие на събитието',
  `event_date` DATE NOT NULL COMMENT 'Дата на събитието',
  `type` VARCHAR(255) DEFAULT 'Other' COMMENT 'Тип на събитието, напр. рожден ден',
  `visibility` VARCHAR(50) DEFAULT 'public' COMMENT 'Видимост на събитието: public, friends, private',
  `has_organization` BOOLEAN DEFAULT FALSE COMMENT 'Флаг дали събитието има активна организация',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `event_organization`;
CREATE TABLE `event_organization` (
  `event_id` INT NOT NULL COMMENT 'ID на свързаното събитие',
  `organizer_id` INT DEFAULT NULL COMMENT 'ID на организатора, ако има организация',
  `organizer_payment_details` VARCHAR(255) COMMENT 'Информация за плащане',
  `place_address` TEXT NOT NULL COMMENT 'Адрес на мястото за провеждане',
  `is_anonymous` BOOLEAN DEFAULT FALSE COMMENT 'Флаг дали събитието е анонимно',
  `excluded_user_name` varchar(255) DEFAULT NULL COMMENT 'Username на потребителя, за когото е събитието, ако събитието е анонимно',

  PRIMARY KEY (`event_id`),
  FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`organizer_id`) REFERENCES `Users` (`id`),
  FOREIGN KEY (`excluded_user_name`) REFERENCES `Users` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `participants`;
CREATE TABLE `participants` (
  `event_id` INT NOT NULL COMMENT 'ID на свързаното събитие',
  `user_id` INT NOT NULL COMMENT 'ID на потребителя участник',
  PRIMARY KEY (`event_id`, `user_id`),
  FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `Users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `comments`;
CREATE TABLE `comments` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `target_id` INT NOT NULL COMMENT 'ID на свързания обект',
  `target_type` ENUM('event', 'gift_idea', 'fundraising', 'comment') NOT NULL COMMENT 'Тип на свързания обект',
  `user_id` INT NOT NULL COMMENT 'ID на автора на коментара',
  `content` TEXT NOT NULL COMMENT 'Текст на коментара',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Време на създаване',
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `Users` (`id`),
  FOREIGN KEY (`target_id`) REFERENCES `events` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS gifts;
CREATE TABLE gifts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    gift_name VARCHAR(255) NOT NULL,
    gift_price DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS votes;
CREATE TABLE votes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    gift_id INT NOT NULL,
    user_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (gift_id) REFERENCES gifts(id) ON DELETE CASCADE,
    UNIQUE (gift_id, user_id) -- A user can vote only once for a gift
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;;

DROP TABLE IF EXISTS polls;
CREATE TABLE polls (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ends_at TIMESTAMP NOT NULL,
    hasEnded TINYINT(1) DEFAULT 0,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;





-- USEFULL ?? 
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
