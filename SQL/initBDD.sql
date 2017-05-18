-- phpMyAdmin SQL Dump
-- version 4.6.6
-- https://www.phpmyadmin.net/
--
-- Client :  localhost
-- Généré le :  Jeu 18 Mai 2017 à 14:28
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
('g@h', 'g', 'g', '$2y$10$PNVCJAVJF5vr6NzBwJiAhuzqqwmNoitrmBawLwvcKUIZ34RgJXsTW', 'Professeur des universités', NULL, 192, 384, NULL, 696792579),
('root@root', 'admin', 'admin', '$2y$10$RaRQdLR6ntOKuOD/vxKtDOgWWG/664Gp0A2YcxS9Kf/mlCSoE6pIG', 'Professeur des universités', 60, 192, 384, NULL, 589347120),
('z@z', 'z', 'z', '$2y$10$DkJg/HxwWYn3do3LGq2aVuKy90VAMAI12E9Ke4RkqrqlL8R4l1D8a', 'Professeur des universités', NULL, 192, 384, NULL, 813424858);

-- --------------------------------------------------------

--
-- Structure de la table `Formation`
--

CREATE TABLE `Formation` (
  `id_formation` int(11) NOT NULL,
  `nomFormation` varchar(32) NOT NULL,
  `fst` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `Formation`
--

INSERT INTO `Formation` (`id_formation`, `nomFormation`, `fst`) VALUES
(1, 'Licence informatique', 1),
(2, 'Master Informatique', 1),
(10, 'sdfghjklm', 0);

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
  `groupeCM` int(4) DEFAULT '1',
  `groupeTP` int(4) DEFAULT '1',
  `groupeTD` int(4) DEFAULT '1',
  `groupeEI` int(4) DEFAULT '1',
  `mail_enseignant` varchar(128) NOT NULL,
  `id_UE` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `Intervention`
--

INSERT INTO `Intervention` (`id_intervention`, `fst`, `heuresCM`, `heuresTP`, `heuresTD`, `heuresEI`, `groupeCM`, `groupeTP`, `groupeTD`, `groupeEI`, `mail_enseignant`, `id_UE`) VALUES
(1, 0, 30, 4, 5, 0, 1, 4, 5, 0, 'root@root', 3),
(2, 1, 0, 0, 0, 0, 1, 1, 1, 1, 'root@root', 4),
(7, 0, 4, 0, 0, 0, 1, 0, 0, 0, 'root@root', 13);

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

-- --------------------------------------------------------

--
-- Structure de la table `NotificationIntervention`
--

CREATE TABLE `NotificationIntervention` (
  `id_notification` int(11) NOT NULL,
  `heuresCM` int(11) NOT NULL,
  `heuresTP` int(11) NOT NULL,
  `heuresTD` int(11) NOT NULL,
  `heuresEI` int(11) NOT NULL,
  `groupeTP` int(11) NOT NULL,
  `groupeTD` int(11) NOT NULL,
  `groupeEI` int(11) NOT NULL,
  `id_UE` int(11) DEFAULT NULL,
  `supprimer` tinyint(4) NOT NULL DEFAULT '0',
  `id_intervention` int(11) DEFAULT NULL,
  `nom_UE` varchar(32) DEFAULT NULL,
  `nom_formation` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `NotificationIntervention`
--

INSERT INTO `NotificationIntervention` (`id_notification`, `heuresCM`, `heuresTP`, `heuresTD`, `heuresEI`, `groupeTP`, `groupeTD`, `groupeEI`, `id_UE`, `supprimer`, `id_intervention`, `nom_UE`, `nom_formation`) VALUES
(63, 4, 0, 0, 0, 0, 0, 0, 13, 0, 7, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `NotificationResponsabilite`
--

CREATE TABLE `NotificationResponsabilite` (
  `id_notification` int(11) NOT NULL,
  `intitule` enum('Responsable UE','Responsable formation','Responsable du departement informatique') NOT NULL,
  `privilege` tinyint(4) NOT NULL DEFAULT '0',
  `id_UE` int(11) DEFAULT NULL,
  `id_formation` int(11) DEFAULT NULL,
  `id_resp` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
(5, 'root@root', 'Responsable UE', NULL, 3, 0),
(6, 'root@root', 'Responsable UE', NULL, 4, 0),
(7, 'root@root', 'Responsable UE', NULL, 5, 0),
(8, 'root@root', 'Responsable UE', NULL, 6, 0);

-- --------------------------------------------------------

--
-- Structure de la table `UE`
--

CREATE TABLE `UE` (
  `id_UE` int(11) NOT NULL,
  `nom_UE` varchar(32) NOT NULL,
  `fst` tinyint(1) NOT NULL,
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

INSERT INTO `UE` (`id_UE`, `nom_UE`, `fst`, `id_formation`, `heuresTD`, `heuresTP`, `heuresCM`, `heuresEI`, `prevision_heuresTD`, `prevision_heuresTP`, `prevision_heuresCM`, `prevision_heuresEI`, `groupeTD`, `groupeTP`, `groupeEI`, `prevision_groupeTD`, `prevision_groupeTP`, `prevision_groupeEI`) VALUES
(3, 'Modélisation', 1, 1, 5, 4, 30, 0, 10, 12, 16, 0, 2, 2, 2, 2, 3, 1),
(4, 'bdd', 1, 1, 0, 0, 0, 0, 10, 12, 16, 0, 0, 0, 0, 2, 3, 1),
(5, 'UE de Master - 1', 1, 2, 0, 0, 0, 0, 10, 10, 79, 3, 1, 1, 1, 0, 8, 1),
(6, 'UE de Master - 2', 1, 2, 0, 0, 0, 0, 3, 5, 10, 51, 0, 0, 0, 1, 42, 42),
(13, 'sdfghjklm', 0, 10, 0, 0, 4, 0, 0, 0, 0, 0, 1, 1, 1, 0, 0, 0);

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
-- Index pour la table `NotificationIntervention`
--
ALTER TABLE `NotificationIntervention`
  ADD PRIMARY KEY (`id_notification`),
  ADD KEY `fk_notification_intervention_ue` (`id_UE`);

--
-- Index pour la table `NotificationResponsabilite`
--
ALTER TABLE `NotificationResponsabilite`
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
  MODIFY `id_formation` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT pour la table `Intervention`
--
ALTER TABLE `Intervention`
  MODIFY `id_intervention` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT pour la table `Notification`
--
ALTER TABLE `Notification`
  MODIFY `id_notification` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;
--
-- AUTO_INCREMENT pour la table `Responsabilite`
--
ALTER TABLE `Responsabilite`
  MODIFY `id_resp` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT pour la table `UE`
--
ALTER TABLE `UE`
  MODIFY `id_UE` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
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
-- Contraintes pour la table `NotificationIntervention`
--
ALTER TABLE `NotificationIntervention`
  ADD CONSTRAINT `fk_notification_intervention_ue` FOREIGN KEY (`id_UE`) REFERENCES `UE` (`id_UE`);

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
