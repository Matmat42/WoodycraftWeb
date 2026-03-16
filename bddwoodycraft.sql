-- --------------------------------------------------------
-- Hôte:                         127.0.0.1
-- Version du serveur:           8.0.30 - MySQL Community Server - GPL
-- --------------------------------------------------------

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- Création de la base eleve2
CREATE DATABASE IF NOT EXISTS `eleve2`
DEFAULT CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE `eleve2`;

-- --------------------------------------------------------
-- Table adresses
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `adresses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `numero` varchar(255) NOT NULL,
  `rue` varchar(255) NOT NULL,
  `ville` varchar(255) NOT NULL,
  `code_postal` varchar(255) NOT NULL,
  `pays` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `type` enum('livraison','facturation') NOT NULL DEFAULT 'livraison',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table categories
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_nom_unique` (`nom`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `categories` (`id`, `nom`) VALUES
(1,'Animaux'),
(2,'Architecture'),
(3,'Monuments'),
(4,'Vehicules');

-- --------------------------------------------------------
-- Table puzzles
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `puzzles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(150) NOT NULL,
  `categorie_id` bigint unsigned NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `prix` decimal(10,2) NOT NULL,
  `description` text,
  `stock` int unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `puzzles` (`id`,`nom`,`categorie_id`,`prix`,`description`,`stock`) VALUES
(1,'Puzzle 1',1,15.00,'Polygone',5),
(2,'Puzzle 2',1,20.00,'Triangle',10),
(3,'Puzzle 3',1,10.00,'Carré',14);

-- --------------------------------------------------------
-- Table users
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `users` (`id`,`name`,`email`,`password`) VALUES
(1,'Test','test@example.com','$2y$10$example'),
(2,'fares','fa@gmail.com','$2y$10$example'),
(3,'fares54','fares54@gmail.com','$2y$10$example');

SET FOREIGN_KEY_CHECKS = 1;