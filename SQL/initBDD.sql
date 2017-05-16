-- phpMyAdmin SQL Dump
-- version 4.6.6
-- https://www.phpmyadmin.net/
--
-- Client :  localhost
-- Généré le :  Mar 16 Mai 2017 à 11:01
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
-- Structure de la table `Responsabilite`
--

CREATE TABLE `Responsabilite` (
  `id_resp` int(4) NOT NULL,
  `intituleResp` enum('Responsable UE','Responsable formation','Responsable du departement informatique') DEFAULT NULL,
  `id_formation` int(11) DEFAULT NULL,
  `id_UE` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `Responsabilite`
--

INSERT INTO `Responsabilite` (`id_resp`, `intituleResp`, `id_formation`, `id_UE`) VALUES
(3, 'Responsable du departement informatique', NULL, NULL);

--
-- Index pour les tables exportées
--

--
-- Index pour la table `Responsabilite`
--
ALTER TABLE `Responsabilite`
  ADD PRIMARY KEY (`id_resp`),
  ADD KEY `fk_form` (`id_formation`),
  ADD KEY `fk_ue` (`id_UE`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `Responsabilite`
--
ALTER TABLE `Responsabilite`
  MODIFY `id_resp` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `Responsabilite`
--
ALTER TABLE `Responsabilite`
  ADD CONSTRAINT `fk_responsabilite_id_UE` FOREIGN KEY (`id_UE`) REFERENCES `UE` (`id_UE`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
