-- phpMyAdmin SQL Dump
-- version 4.6.6
-- https://www.phpmyadmin.net/
--
-- Client :  localhost
-- Généré le :  Mar 16 Mai 2017 à 16:02
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
-- Structure de la table `Enseignant`
--

CREATE TABLE `Enseignant` (
  `mail` varchar(128) NOT NULL,
  `nom` varchar(32) NOT NULL,
  `prenom` varchar(32) NOT NULL,
  `mdp` varchar(200) NOT NULL,
  `statut` enum('Professeur des universités','Maître de conférences','PRAG','ATER','1/2 ATER','Doctorant','Vacataire') DEFAULT NULL,
  `volumeCourant` int(4) DEFAULT NULL,
  `volumeMin` int(4) DEFAULT NULL,
  `volumeMax` int(4) DEFAULT NULL,
  `photo` varchar(2048) DEFAULT NULL,
  `rand` int(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `Enseignant`
--

INSERT INTO `Enseignant` (`mail`, `nom`, `prenom`, `mdp`, `statut`, `volumeCourant`, `volumeMin`, `volumeMax`, `photo`, `rand`) VALUES
('root@root', 'admin', 'admin', '$2y$10$RaRQdLR6ntOKuOD/vxKtDOgWWG/664Gp0A2YcxS9Kf/mlCSoE6pIG', 'Professeur des universités', NULL, 192, 384, NULL, 589347120);

-- --------------------------------------------------------

--
-- Structure de la table `Formation`
--

CREATE TABLE `Formation` (
  `id_formation` int(11) NOT NULL,
  `nomFormation` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `Formation`
--

INSERT INTO `Formation` (`id_formation`, `nomFormation`) VALUES
(1, 'Licence informatique'),
(2, 'Master Informatique');

-- --------------------------------------------------------

--
-- Structure de la table `Intervention`
--

CREATE TABLE `Intervention` (
  `id_intervention` int(4) NOT NULL,
  `fst` tinyint(1) NOT NULL,
  `heuresCM` int(4) DEFAULT '0',
  `heuresTP` int(4) DEFAULT '0',
  `heuresTD` int(4) DEFAULT '0',
  `heuresEI` int(4) DEFAULT '0',
  `groupeCM` int(4) DEFAULT NULL,
  `groupeTP` int(4) DEFAULT NULL,
  `groupeTD` int(4) DEFAULT NULL,
  `groupeEI` int(4) DEFAULT NULL,
  `mail_enseignant` varchar(128) NOT NULL,
  `id_UE` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  `type_notification` enum('PPIL\\models\\NotificationChgtUE','PPIL\\models\\NotificationInscription','PPIL\\models\\Notification') NOT NULL DEFAULT 'PPIL\\models\\Notification',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `Notification`
--

INSERT INTO `Notification` (`id_notification`, `mail_destinataire`, `mail_source`, `message`, `besoin_validation`, `validation`, `type_notification`, `created_at`, `updated_at`) VALUES
(7, 'root@root', NULL, 'Demande d\'inscription', 1, 0, 'PPIL\\models\\NotificationInscription', '2017-05-16 13:13:11', '2017-05-16 13:13:11');

-- --------------------------------------------------------

--
-- Structure de la table `NotificationChgtUE`
--

CREATE TABLE `NotificationChgtUE` (
  `id_notification` int(4) NOT NULL,
  `heuresCM` int(4) NOT NULL,
  `heuresTP` int(4) NOT NULL,
  `heuresTD` int(4) NOT NULL,
  `heuresEI` int(4) NOT NULL,
  `groupeTP` int(4) NOT NULL,
  `groupeTD` int(4) NOT NULL,
  `groupeEI` int(4) NOT NULL,
  `id_UE` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `NotificationInscription`
--

CREATE TABLE `NotificationInscription` (
  `id_notification` int(4) NOT NULL,
  `nom` varchar(32) NOT NULL,
  `prenom` varchar(32) NOT NULL,
  `statut` enum('Professeur des universités','Maître de conférences','PRAG','ATER','1/2 ATER','Doctorant','Vacataire') NOT NULL,
  `mail` varchar(128) NOT NULL,
  `mot_de_passe` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `NotificationInscription`
--

INSERT INTO `NotificationInscription` (`id_notification`, `nom`, `prenom`, `statut`, `mail`, `mot_de_passe`) VALUES
(7, 'z', 'z', 'Professeur des universités', 'z@z', '$2y$10$DkJg/HxwWYn3do3LGq2aVuKy90VAMAI12E9Ke4RkqrqlL8R4l1D8a');

-- --------------------------------------------------------

--
-- Structure de la table `Responsabilite`
--

CREATE TABLE `Responsabilite` (
  `id_resp` int(4) NOT NULL,
  `enseignant` varchar(128) DEFAULT NULL,
  `intituleResp` enum('Responsable UE','Responsable formation','Responsable du departement informatique') DEFAULT NULL,
  `id_formation` int(11) DEFAULT NULL,
  `id_UE` int(11) DEFAULT NULL,
  `privilege` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `Responsabilite`
--

INSERT INTO `Responsabilite` (`id_resp`, `enseignant`, `intituleResp`, `id_formation`, `id_UE`, `privilege`) VALUES
(4, 'root@root', 'Responsable du departement informatique', NULL, NULL, 2),
(5, 'root@root', 'Responsable UE', NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Structure de la table `UE`
--

CREATE TABLE `UE` (
  `id_UE` int(11) NOT NULL,
  `nom_UE` varchar(32) NOT NULL,
  `composante` varchar(128) NOT NULL,
  `id_formation` int(11) NOT NULL,
  `heuresTD` int(4) NOT NULL DEFAULT '0',
  `heuresTP` int(4) NOT NULL DEFAULT '0',
  `heuresCM` int(4) NOT NULL DEFAULT '0',
  `heuresEI` int(4) NOT NULL DEFAULT '0',
  `prevision_heuresTD` int(4) NOT NULL DEFAULT '0',
  `prevision_heuresTP` int(4) NOT NULL DEFAULT '0',
  `prevision_heuresCM` int(4) NOT NULL DEFAULT '0',
  `prevision_heuresEI` int(4) NOT NULL DEFAULT '0',
  `groupeTD` int(4) NOT NULL DEFAULT '0',
  `groupeTP` int(4) NOT NULL DEFAULT '0',
  `groupeEI` int(4) NOT NULL DEFAULT '0',
  `prevision_groupeTD` int(4) NOT NULL DEFAULT '0',
  `prevision_groupeTP` int(4) NOT NULL DEFAULT '0',
  `prevision_groupeEI` int(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `UE`
--

INSERT INTO `UE` (`id_UE`, `nom_UE`, `composante`, `id_formation`, `heuresTD`, `heuresTP`, `heuresCM`, `heuresEI`, `prevision_heuresTD`, `prevision_heuresTP`, `prevision_heuresCM`, `prevision_heuresEI`, `groupeTD`, `groupeTP`, `groupeEI`, `prevision_groupeTD`, `prevision_groupeTP`, `prevision_groupeEI`) VALUES
(3, 'Modélisation', 'fst', 1, 0, 0, 0, 0, 10, 12, 16, 0, 0, 0, 0, 2, 3, 1),
(4, 'bdd', 'fst', 1, 0, 0, 0, 0, 10, 12, 16, 0, 0, 0, 0, 2, 3, 1),
(5, 'UE de Master - 1', 'fst', 2, 0, 0, 0, 0, 10, 10, 79, 3, 0, 0, 0, 0, 8, 1),
(6, 'UE de Master - 2', 'fst', 2, 0, 0, 0, 0, 3, 5, 10, 51, 0, 0, 0, 1, 42, 42);

--
-- Index pour les tables exportées
--

--
-- Index pour la table `Enseignant`
--
ALTER TABLE `Enseignant`
  ADD PRIMARY KEY (`mail`);

--
-- Index pour la table `Formation`
--
ALTER TABLE `Formation`
  ADD PRIMARY KEY (`id_formation`);

--
-- Index pour la table `Intervention`
--
ALTER TABLE `Intervention`
  ADD PRIMARY KEY (`id_intervention`),
  ADD KEY `nomUE` (`id_UE`),
  ADD KEY `mail_enseignant` (`mail_enseignant`);

--
-- Index pour la table `Notification`
--
ALTER TABLE `Notification`
  ADD PRIMARY KEY (`id_notification`);

--
-- Index pour la table `NotificationChgtUE`
--
ALTER TABLE `NotificationChgtUE`
  ADD PRIMARY KEY (`id_notification`),
  ADD KEY `fk_nomUE` (`id_UE`);

--
-- Index pour la table `NotificationInscription`
--
ALTER TABLE `NotificationInscription`
  ADD PRIMARY KEY (`id_notification`);

--
-- Index pour la table `Responsabilite`
--
ALTER TABLE `Responsabilite`
  ADD PRIMARY KEY (`id_resp`),
  ADD KEY `fk_form` (`id_formation`),
  ADD KEY `fk_ue` (`id_UE`),
  ADD KEY `fk_responsabilite_mail_enseignant` (`enseignant`);

--
-- Index pour la table `UE`
--
ALTER TABLE `UE`
  ADD PRIMARY KEY (`id_UE`),
  ADD KEY `fk_id_formation` (`id_formation`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `Formation`
--
ALTER TABLE `Formation`
  MODIFY `id_formation` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `Intervention`
--
ALTER TABLE `Intervention`
  MODIFY `id_intervention` int(4) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `Notification`
--
ALTER TABLE `Notification`
  MODIFY `id_notification` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT pour la table `Responsabilite`
--
ALTER TABLE `Responsabilite`
  MODIFY `id_resp` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT pour la table `UE`
--
ALTER TABLE `UE`
  MODIFY `id_UE` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `Intervention`
--
ALTER TABLE `Intervention`
  ADD CONSTRAINT `intervention_ibfk_2` FOREIGN KEY (`mail_enseignant`) REFERENCES `Enseignant` (`mail`),
  ADD CONSTRAINT `intervention_id_UE` FOREIGN KEY (`id_UE`) REFERENCES `UE` (`id_UE`);

--
-- Contraintes pour la table `NotificationChgtUE`
--
ALTER TABLE `NotificationChgtUE`
  ADD CONSTRAINT `fk_id_notificationChgt` FOREIGN KEY (`id_notification`) REFERENCES `Notification` (`id_notification`),
  ADD CONSTRAINT `fk_notification_id_UE` FOREIGN KEY (`id_UE`) REFERENCES `UE` (`id_UE`);

--
-- Contraintes pour la table `NotificationInscription`
--
ALTER TABLE `NotificationInscription`
  ADD CONSTRAINT `fk_id_notificationIns` FOREIGN KEY (`id_notification`) REFERENCES `Notification` (`id_notification`);

--
-- Contraintes pour la table `Responsabilite`
--
ALTER TABLE `Responsabilite`
  ADD CONSTRAINT `fk_responsabilite_id_UE` FOREIGN KEY (`id_UE`) REFERENCES `UE` (`id_UE`),
  ADD CONSTRAINT `fk_responsabilite_mail_enseignant` FOREIGN KEY (`enseignant`) REFERENCES `Enseignant` (`mail`);

--
-- Contraintes pour la table `UE`
--
ALTER TABLE `UE`
  ADD CONSTRAINT `fk_id_formation` FOREIGN KEY (`id_formation`) REFERENCES `Formation` (`id_formation`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
