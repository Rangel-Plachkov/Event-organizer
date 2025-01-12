-- MySQL dump 10.13  Distrib 9.0.1, for macos14.7 (arm64)
--
-- Host: localhost    Database: Event-organizer
-- ------------------------------------------------------
-- Server version	9.0.1

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
  `excluded_user_id` INT DEFAULT NULL COMMENT 'ID на потребителя, за когото е събитието, ако събитието е анонимно',

  PRIMARY KEY (`event_id`),
  FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`organizer_id`) REFERENCES `Users` (`id`),
  FOREIGN KEY (`excluded_user_id`) REFERENCES `Users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `participants`;
CREATE TABLE `participants` (
  `event_id` INT NOT NULL COMMENT 'ID на свързаното събитие',
  `user_id` INT NOT NULL COMMENT 'ID на потребителя участник',
  PRIMARY KEY (`event_id`),
  FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `Users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- TODO: should comments delete when the event is deleted?