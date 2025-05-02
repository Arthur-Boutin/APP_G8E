-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 28 avr. 2025 à 08:49
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `app_g8e`
--

-- --------------------------------------------------------

--
-- Structure de la table `administrateur`
--

CREATE TABLE `administrateur` (
  `idAdmin` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `artisan`
--

CREATE TABLE `artisan` (
  `idArtisant` varchar(255) NOT NULL,
  `adresse` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `infoCompteBancaire` varchar(255) DEFAULT NULL,
  `infoProduit` varchar(255) DEFAULT NULL,
  `soldeCompte` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `client`
--

CREATE TABLE `client` (
  `idClient` varchar(255) NOT NULL,
  `adresse` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `infoCarteCrédit` varchar(255) DEFAULT NULL,
  `infoExpédition` varchar(255) DEFAULT NULL,
  `soldeCompte` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `commandes`
--

CREATE TABLE `commandes` (
  `idCommande` int(11) NOT NULL,
  `dateCreation` varchar(255) DEFAULT NULL,
  `dateExpedition` varchar(255) DEFAULT NULL,
  `nomClient` varchar(255) DEFAULT NULL,
  `idClient` varchar(255) DEFAULT NULL,
  `statut` varchar(50) DEFAULT NULL,
  `idExpedition` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `commentaire`
--

CREATE TABLE `commentaire` (
  `idCommentaire` int(11) NOT NULL,
  `idClient` varchar(255) DEFAULT NULL,
  `nProduit` int(11) DEFAULT NULL,
  `contenu` varchar(255) DEFAULT NULL,
  `note` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `detailscommande`
--

CREATE TABLE `detailscommande` (
  `idCommande` int(11) NOT NULL,
  `idProduit` int(11) NOT NULL,
  `nomProduit` varchar(255) DEFAULT NULL,
  `quantite` int(11) DEFAULT NULL,
  `coutUnitaire` float DEFAULT NULL,
  `sousTotal` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `informationsexpedition`
--

CREATE TABLE `informationsexpedition` (
  `idExpedition` int(11) NOT NULL,
  `typeExpedition` varchar(255) DEFAULT NULL,
  `fraisExpedition` int(11) DEFAULT NULL,
  `idRegionExpedition` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `paiement`
--

CREATE TABLE `paiement` (
  `idPaiement` int(11) NOT NULL,
  `idClient` varchar(255) DEFAULT NULL,
  `infoPaiement` varchar(255) DEFAULT NULL,
  `nCommande` int(11) DEFAULT NULL,
  `modePaiement` varchar(255) DEFAULT NULL,
  `statut` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `panierachat`
--

CREATE TABLE `panierachat` (
  `idPanier` int(11) NOT NULL,
  `idProduit` int(11) DEFAULT NULL,
  `quantite` int(11) DEFAULT NULL,
  `dateAjoutee` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

CREATE TABLE `produit` (
  `nProduit` int(11) NOT NULL,
  `nom` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `prix` float DEFAULT NULL,
  `quantitee` int(11) DEFAULT NULL,
  `idArtisan` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `idUtilisateur` varchar(255) NOT NULL,
  `mdp` varchar(255) DEFAULT NULL,
  `statutConnexion` varchar(50) DEFAULT NULL,
  `dateInscription` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `administrateur`
--
ALTER TABLE `administrateur`
  ADD PRIMARY KEY (`idAdmin`);

--
-- Index pour la table `artisan`
--
ALTER TABLE `artisan`
  ADD PRIMARY KEY (`idArtisant`);

--
-- Index pour la table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`idClient`);

--
-- Index pour la table `commandes`
--
ALTER TABLE `commandes`
  ADD PRIMARY KEY (`idCommande`),
  ADD KEY `idClient` (`idClient`),
  ADD KEY `idExpedition` (`idExpedition`);

--
-- Index pour la table `commentaire`
--
ALTER TABLE `commentaire`
  ADD PRIMARY KEY (`idCommentaire`),
  ADD KEY `idClient` (`idClient`),
  ADD KEY `nProduit` (`nProduit`);

--
-- Index pour la table `detailscommande`
--
ALTER TABLE `detailscommande`
  ADD PRIMARY KEY (`idCommande`,`idProduit`),
  ADD KEY `idProduit` (`idProduit`);

--
-- Index pour la table `informationsexpedition`
--
ALTER TABLE `informationsexpedition`
  ADD PRIMARY KEY (`idExpedition`);

--
-- Index pour la table `paiement`
--
ALTER TABLE `paiement`
  ADD PRIMARY KEY (`idPaiement`),
  ADD KEY `idClient` (`idClient`),
  ADD KEY `nCommande` (`nCommande`);

--
-- Index pour la table `panierachat`
--
ALTER TABLE `panierachat`
  ADD PRIMARY KEY (`idPanier`),
  ADD KEY `idProduit` (`idProduit`);

--
-- Index pour la table `produit`
--
ALTER TABLE `produit`
  ADD PRIMARY KEY (`nProduit`),
  ADD KEY `idArtisan` (`idArtisan`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`idUtilisateur`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `paiement`
--
ALTER TABLE `paiement`
  MODIFY `idPaiement` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `administrateur`
--
ALTER TABLE `administrateur`
  ADD CONSTRAINT `administrateur_ibfk_1` FOREIGN KEY (`idAdmin`) REFERENCES `utilisateur` (`idUtilisateur`);

--
-- Contraintes pour la table `commandes`
--
ALTER TABLE `commandes`
  ADD CONSTRAINT `commandes_ibfk_1` FOREIGN KEY (`idClient`) REFERENCES `client` (`idClient`),
  ADD CONSTRAINT `commandes_ibfk_2` FOREIGN KEY (`idExpedition`) REFERENCES `informationsexpedition` (`idExpedition`);

--
-- Contraintes pour la table `commentaire`
--
ALTER TABLE `commentaire`
  ADD CONSTRAINT `commentaire_ibfk_1` FOREIGN KEY (`idClient`) REFERENCES `client` (`idClient`),
  ADD CONSTRAINT `commentaire_ibfk_2` FOREIGN KEY (`nProduit`) REFERENCES `produit` (`nProduit`);

--
-- Contraintes pour la table `detailscommande`
--
ALTER TABLE `detailscommande`
  ADD CONSTRAINT `detailscommande_ibfk_1` FOREIGN KEY (`idCommande`) REFERENCES `commandes` (`idCommande`),
  ADD CONSTRAINT `detailscommande_ibfk_2` FOREIGN KEY (`idProduit`) REFERENCES `produit` (`nProduit`);

--
-- Contraintes pour la table `paiement`
--
ALTER TABLE `paiement`
  ADD CONSTRAINT `paiement_ibfk_1` FOREIGN KEY (`idClient`) REFERENCES `client` (`idClient`),
  ADD CONSTRAINT `paiement_ibfk_2` FOREIGN KEY (`nCommande`) REFERENCES `commandes` (`idCommande`);

--
-- Contraintes pour la table `panierachat`
--
ALTER TABLE `panierachat`
  ADD CONSTRAINT `panierachat_ibfk_1` FOREIGN KEY (`idProduit`) REFERENCES `produit` (`nProduit`);

--
-- Contraintes pour la table `produit`
--
ALTER TABLE `produit`
  ADD CONSTRAINT `produit_ibfk_1` FOREIGN KEY (`idArtisan`) REFERENCES `artisan` (`idArtisant`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
