-- phpMyAdmin SQL Dump
-- version 4.6.6
-- https://www.phpmyadmin.net/
--
-- Client :  localhost
-- Généré le :  Jeu 18 Mai 2017 à 12:33
-- Version du serveur :  10.1.21-MariaDB
-- Version de PHP :  7.0.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `bdd2folie`
--

-- --------------------------------------------------------

--
-- Structure de la table `Notification`
--

CREATE TABLE `Notification` (
  `id_notification` int(4) NOT NULL,
  `mail_destinataire` varchar(128) NOT NULL,
  `mail_source` varchar(128) DEFAULT NULL,
  `message` varchar(300) NOT NULL,
  `besoin_validation` tinyint(1) NOT NULL,
  `validation` tinyint(1) NOT NULL,
  `type_notification` enum('PPIL\\models\\NotificationChgtUE','PPIL\\models\\NotificationInscription','PPIL\\models\\Notification','PPIL\\models\\NotificationIntervention','PPIL\\models\\NotificationResponsabilite') NOT NULL DEFAULT 'PPIL\\models\\Notification',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `Notification`
--

INSERT INTO `Notification` (`id_notification`, `mail_destinataire`, `mail_source`, `message`, `besoin_validation`, `validation`, `type_notification`, `created_at`, `updated_at`) VALUES
(42, 'root@root', 'root@root', 'Ajout intervention', 1, 0, 'PPIL\\models\\NotificationIntervention', '2017-05-18 07:18:28', '2017-05-18 07:18:28');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `Notification`
--
ALTER TABLE `Notification`
  ADD PRIMARY KEY (`id_notification`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `Notification`
--
ALTER TABLE `Notification`
  MODIFY `id_notification` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
