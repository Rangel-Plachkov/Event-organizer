DROP TABLE IF EXISTS `Users`;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `events`;
CREATE TABLE `events` (
                          `id` INT NOT NULL AUTO_INCREMENT,
                          `title` VARCHAR(255) NOT NULL COMMENT 'Заглавие на събитието',
                          `event_date` DATE NOT NULL COMMENT 'Дата на събитието',
                          `type` VARCHAR(255) DEFAULT 'Other' COMMENT 'Тип на събитието, напр. рожден ден',
                          `visibility` VARCHAR(50) DEFAULT 'public' COMMENT 'Видимост на събитието: public, friends, private',
                          `has_organization` BOOLEAN DEFAULT FALSE COMMENT 'Флаг дали събитието има активна организация',
                          PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `gifts`;
CREATE TABLE gifts (
                       id INT AUTO_INCREMENT PRIMARY KEY,
                       event_id INT NOT NULL,
                       gift_name VARCHAR(255) NOT NULL,
                       gift_price DECIMAL(10, 2) NOT NULL,
                       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                       FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS polls;
CREATE TABLE polls (
                       id INT AUTO_INCREMENT PRIMARY KEY,
                       event_id INT NOT NULL,
                       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                       ends_at TIMESTAMP NULL DEFAULT NULL,
                       hasEnded TINYINT(1) DEFAULT 0,
                       FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `comments`;
CREATE TABLE `comments` (
                            `id` INT NOT NULL AUTO_INCREMENT,
                            `target_id` INT NOT NULL COMMENT 'ID на свързания обект',
                            `target_type` ENUM('event', 'gift_idea', 'fundraising', 'comment') NOT NULL COMMENT 'Тип на свързания обект',
                            `username` varchar(255) NOT NULL COMMENT 'ID на автора на коментара',
                            `content` TEXT NOT NULL COMMENT 'Текст на коментара',
                            `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Време на създаване',
                            PRIMARY KEY (`id`),
                            FOREIGN KEY (`username`) REFERENCES `Users` (`username`),
                            FOREIGN KEY (`target_id`) REFERENCES `events` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS votes;
CREATE TABLE votes (
                       id INT AUTO_INCREMENT PRIMARY KEY,
                       gift_id INT NOT NULL,
                       user_id INT NOT NULL,
                       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                       FOREIGN KEY (gift_id) REFERENCES gifts(id) ON DELETE CASCADE,
                       UNIQUE (gift_id, user_id) -- A user can vote only once for a gift
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;;


DROP TABLE IF EXISTS `follows`;
CREATE TABLE `follows` (
                           `user_id` INT NOT NULL COMMENT 'ID на потребителя',
                           `followed_id` INT NOT NULL COMMENT 'ID на последователя',
                           PRIMARY KEY (`user_id`, `followed_id`),
                           FOREIGN KEY (`user_id`) REFERENCES `Users` (`id`),
                           FOREIGN KEY (`followed_id`) REFERENCES `Users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `participants`;
CREATE TABLE `participants` (
                                `event_id` INT NOT NULL COMMENT 'ID на свързаното събитие',
                                `user_id` INT NOT NULL COMMENT 'ID на потребителя участник',
                                PRIMARY KEY (`event_id`, `user_id`),
                                FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
                                FOREIGN KEY (`user_id`) REFERENCES `Users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


























