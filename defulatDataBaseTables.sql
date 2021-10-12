-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.18-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             11.2.0.6213
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for ci_demo
DROP DATABASE IF EXISTS `ci_demo`;
CREATE DATABASE IF NOT EXISTS `ci_demo` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `ci_demo`;

-- Dumping structure for table ci_demo.advertisement
DROP TABLE IF EXISTS `advertisement`;
CREATE TABLE IF NOT EXISTS `advertisement` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `link` varchar(255) DEFAULT NULL,
    `status` tinyint(1) NOT NULL DEFAULT 1,
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    `deleted_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.

-- Dumping structure for table ci_demo.advertisement_media
DROP TABLE IF EXISTS `advertisement_media`;
CREATE TABLE IF NOT EXISTS `advertisement_media` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `advertisement_id` int(11) unsigned NOT NULL,
    `path` varchar(255) NOT NULL,
    PRIMARY KEY (`id`),
    KEY `advertisement_media_advertisement_id_foreign` (`advertisement_id`),
    CONSTRAINT `advertisement_media_advertisement_id_foreign` FOREIGN KEY (`advertisement_id`) REFERENCES `advertisement` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.

-- Dumping structure for table ci_demo.auth_activation_attempts
DROP TABLE IF EXISTS `auth_activation_attempts`;
CREATE TABLE IF NOT EXISTS `auth_activation_attempts` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `ip_address` varchar(255) NOT NULL,
    `user_agent` varchar(255) NOT NULL,
    `token` varchar(255) DEFAULT NULL,
    `created_at` datetime NOT NULL,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.

-- Dumping structure for table ci_demo.auth_groups
DROP TABLE IF EXISTS `auth_groups`;
CREATE TABLE IF NOT EXISTS `auth_groups` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `description` varchar(255) NOT NULL,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.

-- Dumping structure for table ci_demo.auth_groups_permissions
DROP TABLE IF EXISTS `auth_groups_permissions`;
CREATE TABLE IF NOT EXISTS `auth_groups_permissions` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `group_id` int(11) unsigned NOT NULL DEFAULT 0,
    `permission_id` int(11) unsigned NOT NULL DEFAULT 0,
    `actions` varchar(255) NOT NULL,
    PRIMARY KEY (`id`),
    KEY `auth_groups_permissions_permission_id_foreign` (`permission_id`),
    KEY `group_id_permission_id` (`group_id`,`permission_id`),
    CONSTRAINT `auth_groups_permissions_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `auth_groups` (`id`) ON DELETE CASCADE,
    CONSTRAINT `auth_groups_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `auth_permissions` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.

-- Dumping structure for table ci_demo.auth_groups_users
DROP TABLE IF EXISTS `auth_groups_users`;
CREATE TABLE IF NOT EXISTS `auth_groups_users` (
    `group_id` int(11) unsigned NOT NULL DEFAULT 0,
    `user_id` int(11) unsigned NOT NULL DEFAULT 0,
    KEY `auth_groups_users_user_id_foreign` (`user_id`),
    KEY `group_id_user_id` (`group_id`,`user_id`),
    CONSTRAINT `auth_groups_users_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `auth_groups` (`id`) ON DELETE CASCADE,
    CONSTRAINT `auth_groups_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.

-- Dumping structure for table ci_demo.auth_logins
DROP TABLE IF EXISTS `auth_logins`;
CREATE TABLE IF NOT EXISTS `auth_logins` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `ip_address` varchar(255) DEFAULT NULL,
    `email` varchar(255) DEFAULT NULL,
    `username` varchar(255) DEFAULT NULL,
    `phone` varchar(255) DEFAULT NULL,
    `user_id` int(11) unsigned DEFAULT NULL,
    `date` datetime NOT NULL,
    `success` tinyint(1) NOT NULL,
    PRIMARY KEY (`id`),
    KEY `email` (`email`),
    KEY `user_id` (`user_id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=98 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.

-- Dumping structure for table ci_demo.auth_permissions
DROP TABLE IF EXISTS `auth_permissions`;
CREATE TABLE IF NOT EXISTS `auth_permissions` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `description` varchar(255) NOT NULL,
    `active` tinyint(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.

-- Dumping structure for table ci_demo.auth_reset_attempts
DROP TABLE IF EXISTS `auth_reset_attempts`;
CREATE TABLE IF NOT EXISTS `auth_reset_attempts` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `email` varchar(255) NOT NULL,
    `ip_address` varchar(255) NOT NULL,
    `user_agent` varchar(255) NOT NULL,
    `token` varchar(255) DEFAULT NULL,
    `created_at` datetime NOT NULL,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.

-- Dumping structure for table ci_demo.auth_tokens
DROP TABLE IF EXISTS `auth_tokens`;
CREATE TABLE IF NOT EXISTS `auth_tokens` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `selector` varchar(255) NOT NULL,
    `hashedValidator` varchar(255) NOT NULL,
    `user_id` int(11) unsigned NOT NULL,
    `expires` datetime NOT NULL,
    PRIMARY KEY (`id`),
    KEY `auth_tokens_user_id_foreign` (`user_id`),
    KEY `selector` (`selector`),
    CONSTRAINT `auth_tokens_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.

-- Dumping structure for table ci_demo.auth_users_permissions
DROP TABLE IF EXISTS `auth_users_permissions`;
CREATE TABLE IF NOT EXISTS `auth_users_permissions` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11) unsigned NOT NULL DEFAULT 0,
    `permission_id` int(11) unsigned NOT NULL DEFAULT 0,
    `actions` varchar(255) NOT NULL,
    PRIMARY KEY (`id`),
    KEY `auth_users_permissions_permission_id_foreign` (`permission_id`),
    KEY `user_id_permission_id` (`user_id`,`permission_id`),
    CONSTRAINT `auth_users_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `auth_permissions` (`id`) ON DELETE CASCADE,
    CONSTRAINT `auth_users_permissions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.

-- Dumping structure for table ci_demo.chat_private
DROP TABLE IF EXISTS `chat_private`;
CREATE TABLE IF NOT EXISTS `chat_private` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `user_sender_id` int(11) unsigned NOT NULL,
    `user_receiver_id` int(11) unsigned NOT NULL,
    `message` text DEFAULT NULL,
    `reply_id` int(11) unsigned NOT NULL DEFAULT 0,
    `status` tinyint(1) NOT NULL DEFAULT 0,
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    `deleted_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `chat_private_user_sender_id_foreign` (`user_sender_id`),
    KEY `chat_private_user_receiver_id_foreign` (`user_receiver_id`),
    CONSTRAINT `chat_private_user_receiver_id_foreign` FOREIGN KEY (`user_receiver_id`) REFERENCES `users` (`id`),
    CONSTRAINT `chat_private_user_sender_id_foreign` FOREIGN KEY (`user_sender_id`) REFERENCES `users` (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.

-- Dumping structure for table ci_demo.chat_private_media
DROP TABLE IF EXISTS `chat_private_media`;
CREATE TABLE IF NOT EXISTS `chat_private_media` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `chat_private_id` int(11) unsigned NOT NULL,
    `path` varchar(255) NOT NULL,
    PRIMARY KEY (`id`),
    KEY `chat_private_media_chat_private_id_foreign` (`chat_private_id`),
    CONSTRAINT `chat_private_media_chat_private_id_foreign` FOREIGN KEY (`chat_private_id`) REFERENCES `chat_private` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.

-- Dumping structure for table ci_demo.chat_room
DROP TABLE IF EXISTS `chat_room`;
CREATE TABLE IF NOT EXISTS `chat_room` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `user_id` int(11) unsigned NOT NULL,
    `group_id` int(11) unsigned NOT NULL,
    `message` text DEFAULT NULL,
    `reply_id` int(11) unsigned NOT NULL DEFAULT 0,
    `status` tinyint(1) NOT NULL DEFAULT 0,
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    `deleted_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `chat_room_group_id_foreign` (`group_id`),
    KEY `chat_room_user_id_foreign` (`user_id`),
    CONSTRAINT `chat_room_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `auth_groups` (`id`),
    CONSTRAINT `chat_room_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.

-- Dumping structure for table ci_demo.chat_room_media
DROP TABLE IF EXISTS `chat_room_media`;
CREATE TABLE IF NOT EXISTS `chat_room_media` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `chat_room_id` int(11) unsigned NOT NULL,
    `path` varchar(255) NOT NULL,
    PRIMARY KEY (`id`),
    KEY `chat_room_media_chat_room_id_foreign` (`chat_room_id`),
    CONSTRAINT `chat_room_media_chat_room_id_foreign` FOREIGN KEY (`chat_room_id`) REFERENCES `chat_room` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.

-- Dumping structure for table ci_demo.contact
DROP TABLE IF EXISTS `contact`;
CREATE TABLE IF NOT EXISTS `contact` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `title` varchar(255) NOT NULL,
    `email` varchar(255) NOT NULL,
    `name` varchar(255) NOT NULL,
    `message` text DEFAULT NULL,
    `reply` text DEFAULT NULL,
    `phone` int(11) unsigned NOT NULL,
    `status` tinyint(1) NOT NULL DEFAULT 0,
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    `deleted_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.

-- Dumping structure for table ci_demo.contact_media
DROP TABLE IF EXISTS `contact_media`;
CREATE TABLE IF NOT EXISTS `contact_media` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `contact_id` int(11) unsigned NOT NULL,
    `path` varchar(255) NOT NULL,
    PRIMARY KEY (`id`),
    KEY `contact_file_contact_id_foreign` (`contact_id`),
    CONSTRAINT `contact_file_contact_id_foreign` FOREIGN KEY (`contact_id`) REFERENCES `contact` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.

-- Dumping structure for table ci_demo.migrations
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `version` varchar(255) NOT NULL,
    `class` text NOT NULL,
    `group` varchar(255) NOT NULL,
    `namespace` varchar(255) NOT NULL,
    `time` int(11) NOT NULL,
    `batch` int(11) unsigned NOT NULL,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.

-- Dumping structure for table ci_demo.news_category
DROP TABLE IF EXISTS `news_category`;
CREATE TABLE IF NOT EXISTS `news_category` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `language` varchar(255) NOT NULL,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.

-- Dumping structure for table ci_demo.news_comment
DROP TABLE IF EXISTS `news_comment`;
CREATE TABLE IF NOT EXISTS `news_comment` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `post_id` int(11) unsigned NOT NULL,
    `user_id` int(11) unsigned NOT NULL,
    `reply_id` int(11) unsigned NOT NULL DEFAULT 0,
    `message` varchar(300) DEFAULT NULL,
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    `deleted_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `news_comment_news_post_id_foreign` (`post_id`),
    KEY `news_comment_user_id_foreign` (`user_id`),
    CONSTRAINT `news_comment_news_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `news_post` (`id`) ON DELETE CASCADE,
    CONSTRAINT `news_comment_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.

-- Dumping structure for table ci_demo.news_media
DROP TABLE IF EXISTS `news_media`;
CREATE TABLE IF NOT EXISTS `news_media` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `post_id` int(11) unsigned NOT NULL,
    `image` varchar(255) NOT NULL,
    `thumbnail` varchar(255) NOT NULL,
    `video` varchar(255) NOT NULL,
    PRIMARY KEY (`id`),
    KEY `news_media_news_post_id_foreign` (`post_id`),
    CONSTRAINT `news_media_news_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `news_post` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.

-- Dumping structure for table ci_demo.news_post
DROP TABLE IF EXISTS `news_post`;
CREATE TABLE IF NOT EXISTS `news_post` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `category_id` int(11) unsigned NOT NULL,
    `sub_category_id` int(11) unsigned NOT NULL,
    `user_id` int(11) unsigned NOT NULL,
    `title` varchar(255) NOT NULL,
    `body` text DEFAULT NULL,
    `status` tinyint(1) NOT NULL DEFAULT 0,
    `picture` varchar(255) NOT NULL,
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    `deleted_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `news_post_user_id_foreign` (`user_id`),
    KEY `sub_category_id` (`sub_category_id`),
    KEY `category_id` (`category_id`),
    CONSTRAINT `news_post_ibfk_1` FOREIGN KEY (`sub_category_id`) REFERENCES `news_sub_category` (`id`) ON DELETE CASCADE,
    CONSTRAINT `news_post_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `news_category` (`id`) ON DELETE CASCADE,
    CONSTRAINT `news_post_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.

-- Dumping structure for table ci_demo.news_sub_category
DROP TABLE IF EXISTS `news_sub_category`;
CREATE TABLE IF NOT EXISTS `news_sub_category` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `category_id` int(11) unsigned NOT NULL,
    `name` varchar(255) NOT NULL,
    PRIMARY KEY (`id`),
    KEY `news_sub_category_category_id_foreign` (`category_id`),
    CONSTRAINT `news_sub_category_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `news_category` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.

-- Dumping structure for table ci_demo.request_category
DROP TABLE IF EXISTS `request_category`;
CREATE TABLE IF NOT EXISTS `request_category` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `language` varchar(255) NOT NULL,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

-- Data exporting was unselected.

-- Dumping structure for table ci_demo.request_post
DROP TABLE IF EXISTS `request_post`;
CREATE TABLE IF NOT EXISTS `request_post` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `user_id` int(11) NOT NULL,
    `category_id` int(11) NOT NULL,
    `title` varchar(255) NOT NULL DEFAULT '0',
    `status` tinyint(4) NOT NULL,
    `body` text NOT NULL,
    `created_at` datetime NOT NULL,
    `updated_at` datetime NOT NULL,
    `deleted_at` datetime NOT NULL,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

-- Data exporting was unselected.

-- Dumping structure for table ci_demo.request_reply
DROP TABLE IF EXISTS `request_reply`;
CREATE TABLE IF NOT EXISTS `request_reply` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `post_id` int(11) NOT NULL,
    `user_id` int(11) NOT NULL,
    `reply_id` int(11) NOT NULL,
    `message` varchar(300) NOT NULL,
    `created_at` datetime NOT NULL,
    `updated_at` datetime NOT NULL,
    `deleted_at` datetime NOT NULL,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;

-- Data exporting was unselected.

-- Dumping structure for table ci_demo.setting
DROP TABLE IF EXISTS `setting`;
CREATE TABLE IF NOT EXISTS `setting` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `key` varchar(255) NOT NULL,
    `value` varchar(255) NOT NULL,
    `description` varchar(300) NOT NULL,
    `status` tinyint(1) NOT NULL DEFAULT 1,
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    `deleted_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `key` (`key`)
    ) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.

-- Dumping structure for table ci_demo.users
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `email` varchar(255) DEFAULT NULL,
    `username` varchar(30) DEFAULT NULL,
    `password_hash` varchar(255) NOT NULL,
    `reset_hash` varchar(255) DEFAULT NULL,
    `reset_at` datetime DEFAULT NULL,
    `reset_expires` datetime DEFAULT NULL,
    `activate_hash` varchar(255) DEFAULT NULL,
    `status` varchar(255) DEFAULT NULL,
    `status_message` varchar(255) DEFAULT NULL,
    `active` tinyint(1) NOT NULL DEFAULT 0,
    `force_pass_reset` tinyint(1) NOT NULL DEFAULT 0,
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    `deleted_at` datetime DEFAULT NULL,
    `first_name` varchar(255) NOT NULL,
    `last_name` varchar(255) NOT NULL,
    `image` varchar(255) NOT NULL DEFAULT 'public/upload/profile/default-avatar.jpg',
    `gender` set('male','female') NOT NULL,
    `birthday` date NOT NULL,
    `country` varchar(255) NOT NULL DEFAULT 'N/A',
    `city` varchar(255) NOT NULL DEFAULT 'N/A',
    `address` varchar(255) NOT NULL,
    `phone` varchar(11) DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `email` (`email`),
    UNIQUE KEY `username` (`username`)
    ) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.

-- Dumping structure for table ci_demo.view_media
DROP TABLE IF EXISTS `view_media`;
CREATE TABLE IF NOT EXISTS `view_media` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `view_option_id` int(11) unsigned NOT NULL,
    `path` varchar(255) NOT NULL,
    PRIMARY KEY (`id`),
    KEY `views_media_view_option_id_foreign` (`view_option_id`),
    CONSTRAINT `views_media_view_option_id_foreign` FOREIGN KEY (`view_option_id`) REFERENCES `view_option` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.

-- Dumping structure for table ci_demo.view_option
DROP TABLE IF EXISTS `view_option`;
CREATE TABLE IF NOT EXISTS `view_option` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.

-- Dumping structure for table ci_demo.visitor
DROP TABLE IF EXISTS `visitor`;
CREATE TABLE IF NOT EXISTS `visitor` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `ip` varchar(255) NOT NULL,
    `country` varchar(255) NOT NULL,
    `city` varchar(255) NOT NULL,
    `os` varchar(255) NOT NULL,
    `lat` varchar(255) NOT NULL,
    `lang` varchar(255) NOT NULL,
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime NOT NULL,
    `deleted_at` datetime NOT NULL,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
