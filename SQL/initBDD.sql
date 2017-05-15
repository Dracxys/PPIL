-- phpMyAdmin SQL Dump
-- version 4.6.6
-- https://www.phpmyadmin.net/
--
-- Client :  localhost
-- Généré le :  Lun 15 Mai 2017 à 09:01
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
  `mail` varchar(64) NOT NULL,
  `nom` varchar(32) NOT NULL,
  `prenom` varchar(32) NOT NULL,
  `mdp` varchar(200) NOT NULL,
  `statut` enum('Professeur des universités','Maître de conférences','PRAG','ATER','1/2 ATER','Doctorant','Vacataire') DEFAULT NULL,
  `volumeCourant` int(4) DEFAULT NULL,
  `volumeMin` int(4) DEFAULT NULL,
  `volumeMax` int(4) DEFAULT NULL,
  `photo` varchar(2048) DEFAULT NULL,
  `id_responsabilite` int(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `Enseignant`
--

INSERT INTO `Enseignant` (`mail`, `nom`, `prenom`, `mdp`, `statut`, `volumeCourant`, `volumeMin`, `volumeMax`, `photo`, `id_responsabilite`) VALUES
('root@root', 'admin', 'admin', '$2y$10$RaRQdLR6ntOKuOD/vxKtDOgWWG/664Gp0A2YcxS9Kf/mlCSoE6pIG', 'Professeur des universités', NULL, 192, 384, NULL, 3);

-- --------------------------------------------------------

--
-- Structure de la table `Formation`
--

CREATE TABLE `Formation` (
  `nomFormation` varchar(32) NOT NULL,
  `nomUE` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  `mail_enseignant` varchar(64) NOT NULL,
  `nomUE` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `Notification`
--

CREATE TABLE `Notification` (
  `id_notification` int(4) NOT NULL,
  `mail_destinataire` varchar(64) NOT NULL,
  `mail_source` varchar(64) DEFAULT NULL,
  `message` varchar(64) NOT NULL,
  `besoin_validation` tinyint(1) NOT NULL,
  `validation` tinyint(1) NOT NULL,
  `type_notification` enum('PPIL\\models\\NotificationChgtUE','PPIL\\models\\NotificationInscription','PPIL\\models\\Notification') NOT NULL DEFAULT 'PPIL\\models\\Notification',
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
  `nomUE` varchar(32) NOT NULL
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
  `mail` varchar(32) NOT NULL,
  `mot_de_passe` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `Responsabilite`
--

CREATE TABLE `Responsabilite` (
  `id_resp` int(4) NOT NULL,
  `intituleResp` enum('Responsable UE','Responsable formation','Responsable du departement informatique') DEFAULT NULL,
  `nomFormation` varchar(32) DEFAULT NULL,
  `nomUE` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `Responsabilite`
--

INSERT INTO `Responsabilite` (`id_resp`, `intituleResp`, `nomFormation`, `nomUE`) VALUES
(3, 'Responsable du departement informatique', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `UE`
--

CREATE TABLE `UE` (
  `nom_UE` varchar(32) NOT NULL,
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
-- Index pour les tables exportées
--

--
-- Index pour la table `Enseignant`
--
ALTER TABLE `Enseignant`
  ADD PRIMARY KEY (`mail`),
  ADD KEY `id_responsabilite` (`id_responsabilite`);

--
-- Index pour la table `Formation`
--
ALTER TABLE `Formation`
  ADD PRIMARY KEY (`nomFormation`);

--
-- Index pour la table `Intervention`
--
ALTER TABLE `Intervention`
  ADD PRIMARY KEY (`id_intervention`),
  ADD KEY `nomUE` (`nomUE`),
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
  ADD KEY `fk_nomUE` (`nomUE`);

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
  ADD KEY `fk_form` (`nomFormation`),
  ADD KEY `fk_ue` (`nomUE`);

--
-- Index pour la table `UE`
--
ALTER TABLE `UE`
  ADD PRIMARY KEY (`nom_UE`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `Intervention`
--
ALTER TABLE `Intervention`
  MODIFY `id_intervention` int(4) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `Notification`
--
ALTER TABLE `Notification`
  MODIFY `id_notification` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pour la table `Responsabilite`
--
ALTER TABLE `Responsabilite`
  MODIFY `id_resp` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `Enseignant`
--
ALTER TABLE `Enseignant`
  ADD CONSTRAINT `enseignant_ibfk_2` FOREIGN KEY (`id_responsabilite`) REFERENCES `Responsabilite` (`id_resp`);

--
-- Contraintes pour la table `Intervention`
--
ALTER TABLE `Intervention`
  ADD CONSTRAINT `intervention_ibfk_1` FOREIGN KEY (`nomUE`) REFERENCES `UE` (`nom_UE`),
  ADD CONSTRAINT `intervention_ibfk_2` FOREIGN KEY (`mail_enseignant`) REFERENCES `Enseignant` (`mail`);

--
-- Contraintes pour la table `NotificationChgtUE`
--
ALTER TABLE `NotificationChgtUE`
  ADD CONSTRAINT `fk_id_notificationChgt` FOREIGN KEY (`id_notification`) REFERENCES `Notification` (`id_notification`),
  ADD CONSTRAINT `fk_nomUE` FOREIGN KEY (`nomUE`) REFERENCES `UE` (`nom_UE`);

--
-- Contraintes pour la table `NotificationInscription`
--
ALTER TABLE `NotificationInscription`
  ADD CONSTRAINT `fk_id_notificationIns` FOREIGN KEY (`id_notification`) REFERENCES `Notification` (`id_notification`);

--
-- Contraintes pour la table `Responsabilite`
--
ALTER TABLE `Responsabilite`
  ADD CONSTRAINT `fk_form` FOREIGN KEY (`nomFormation`) REFERENCES `Formation` (`nomFormation`),
  ADD CONSTRAINT `fk_ue` FOREIGN KEY (`nomUE`) REFERENCES `UE` (`nom_UE`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
