-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Client :  127.0.0.1
-- Généré le :  Dim 22 Octobre 2017 à 09:50
-- Version du serveur :  5.7.14
-- Version de PHP :  5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `paroisse_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `activites`
--

CREATE TABLE `activites` (
  `id` int(11) NOT NULL,
  `Nom` varchar(53) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL COMMENT 'Activité',
  `Fraternite` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `Service` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `Formation` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `Priere` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `Mission` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `Souhait` tinyint(1) NOT NULL DEFAULT '0',
  `YearReq` varchar(3) COLLATE latin1_general_ci NOT NULL COMMENT 'Admin_Tools : Reconduction automatique chaque année, en aout, des personnes en ressourcement',
  `ActualSession` varchar(7) COLLATE latin1_general_ci NOT NULL,
  `x_Eglise` tinyint(1) NOT NULL,
  `Menu_Ordre` tinyint(4) NOT NULL DEFAULT '0',
  `Menu_PHP_File` varchar(30) COLLATE latin1_general_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `admin_counter`
--

CREATE TABLE `admin_counter` (
  `counter` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `admin_membres`
--

CREATE TABLE `admin_membres` (
  `id` int(11) NOT NULL,
  `Individu_id` int(11) NOT NULL,
  `droit_acces` int(11) NOT NULL DEFAULT '0',
  `username` varchar(50) NOT NULL,
  `Naissance` date NOT NULL,
  `membre_counter` int(11) NOT NULL,
  `password` varchar(40) NOT NULL,
  `membre_derniere_visite` datetime NOT NULL,
  `membre_adresse_ip` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `admin_user_online`
--

CREATE TABLE `admin_user_online` (
  `session` char(100) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `time` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `bapteme`
--

CREATE TABLE `bapteme` (
  `id` int(11) NOT NULL,
  `Activite_id` int(11) NOT NULL DEFAULT '0',
  `MAJ` datetime NOT NULL,
  `Session` varchar(8) COLLATE latin1_general_ci NOT NULL,
  `Reunion` bigint(64) UNSIGNED NOT NULL,
  `Baptise_id` int(11) NOT NULL,
  `Date` datetime NOT NULL,
  `Lieu_id` int(11) NOT NULL,
  `Celebrant_id` int(11) NOT NULL,
  `Accompagnateur_id` int(11) NOT NULL,
  `Parrain` varchar(80) COLLATE latin1_general_ci NOT NULL,
  `Marraine` varchar(80) COLLATE latin1_general_ci NOT NULL,
  `Extrait_Naissance` tinyint(1) NOT NULL,
  `Dossier_Renseigne` tinyint(1) NOT NULL,
  `Livret_de_famille` tinyint(1) NOT NULL,
  `Aspersion_Immersion` tinyint(2) NOT NULL COMMENT 'Le baptême sera par aspersion ou immersion ?',
  `Commentaire` varchar(350) COLLATE latin1_general_ci NOT NULL,
  `Finance` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `celebrations_futur`
--

CREATE TABLE `celebrations_futur` (
  `date` datetime NOT NULL,
  `Lieu_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs;

-- --------------------------------------------------------

--
-- Structure de la table `celebrations_rec`
--

CREATE TABLE `celebrations_rec` (
  `id` int(11) NOT NULL,
  `DateDeb` date NOT NULL,
  `DateFin` date NOT NULL,
  `Jour` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `Heure` time NOT NULL,
  `Lieu_id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs;

-- --------------------------------------------------------

--
-- Structure de la table `debug`
--

CREATE TABLE `debug` (
  `id` int(11) NOT NULL,
  `Date` datetime NOT NULL,
  `Comment` varchar(1000) COLLATE latin1_general_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `denier`
--

CREATE TABLE `denier` (
  `id` int(11) NOT NULL,
  `Date` date NOT NULL,
  `Paroissien_id` int(11) NOT NULL,
  `Montant` decimal(10,2) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ecoles`
--

CREATE TABLE `ecoles` (
  `id` int(11) NOT NULL,
  `Nom` varchar(40) COLLATE latin1_general_cs DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs;

-- --------------------------------------------------------

--
-- Structure de la table `fiancés`
--

CREATE TABLE `fiancés` (
  `id` int(5) NOT NULL,
  `Actif` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=en couple, 0=Séparé',
  `MAJ` datetime NOT NULL,
  `Lieu_mariage` varchar(30) COLLATE latin1_general_ci NOT NULL,
  `Date_mariage` datetime NOT NULL,
  `Status` varchar(15) COLLATE latin1_general_ci NOT NULL,
  `Prem_Accueil_id` int(11) NOT NULL COMMENT 'Premier ministre ayant accueilli les fiancés',
  `Celebrant_id` int(11) NOT NULL,
  `Celebrant` varchar(40) COLLATE latin1_general_ci NOT NULL,
  `Accompagnateur_id` int(11) NOT NULL,
  `Accompagnateurs` varchar(30) COLLATE latin1_general_ci NOT NULL,
  `LUI_id` int(11) NOT NULL,
  `LUI_Prenom` varchar(40) COLLATE latin1_general_ci NOT NULL,
  `LUI_Nom` varchar(40) COLLATE latin1_general_ci NOT NULL,
  `LUI_DateNaissance` varchar(10) COLLATE latin1_general_ci NOT NULL,
  `LUI_Confession` varchar(15) COLLATE latin1_general_ci NOT NULL,
  `LUI_Extrait_Naissance` tinyint(1) NOT NULL,
  `LUI_Extrait_Bapteme` tinyint(1) NOT NULL,
  `LUI_Lettre_Intention` tinyint(1) NOT NULL,
  `ELLE_id` int(11) NOT NULL,
  `ELLE_Prenom` varchar(40) COLLATE latin1_general_ci NOT NULL,
  `ELLE_Nom` varchar(40) COLLATE latin1_general_ci NOT NULL,
  `ELLE_DateNaissance` varchar(10) COLLATE latin1_general_ci NOT NULL,
  `ELLE_Confession` varchar(15) COLLATE latin1_general_ci NOT NULL,
  `ELLE_Extrait_Naissance` tinyint(1) NOT NULL,
  `ELLE_Extrait_Bapteme` tinyint(1) NOT NULL,
  `ELLE_Lettre_Intention` tinyint(1) NOT NULL,
  `Telephone` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `Email` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `Adresse` varchar(70) COLLATE latin1_general_ci NOT NULL,
  `Enfant` varchar(40) COLLATE latin1_general_ci NOT NULL,
  `Commentaire` varchar(350) COLLATE latin1_general_ci NOT NULL,
  `Session` varchar(7) COLLATE latin1_general_ci NOT NULL DEFAULT '0' COMMENT 'Année de session de préparation',
  `Finance_total` int(5) NOT NULL COMMENT 'Total de participation',
  `Finance_commentaire` varchar(100) COLLATE latin1_general_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 CHECKSUM=1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `fraternite`
--

CREATE TABLE `fraternite` (
  `id` int(11) NOT NULL,
  `MAJ` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  `Activite_id` int(11) NOT NULL,
  `Session` varchar(7) COLLATE latin1_general_ci NOT NULL,
  `SS_Session` varchar(30) COLLATE latin1_general_ci NOT NULL,
  `NoFrat` varchar(10) COLLATE latin1_general_ci NOT NULL,
  `Date` datetime NOT NULL,
  `Jour` tinyint(4) DEFAULT '0',
  `Lieu_id` int(11) NOT NULL,
  `Commentaire` varchar(350) COLLATE latin1_general_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `individu`
--

CREATE TABLE `individu` (
  `id` int(11) NOT NULL,
  `Actif` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0=n''est plus un paroissien, 1=paroissien présent',
  `Dead` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=vivant, 1=décédé',
  `Diacre` tinyint(1) NOT NULL,
  `Pretre` tinyint(1) NOT NULL,
  `MAJ` datetime NOT NULL,
  `Nom` varchar(40) COLLATE latin1_general_ci NOT NULL,
  `Prenom` varchar(40) COLLATE latin1_general_ci NOT NULL,
  `Sex` enum('M','F','') COLLATE latin1_general_ci NOT NULL,
  `Naissance` date NOT NULL,
  `LangueMaternelle` varchar(20) COLLATE latin1_general_ci NOT NULL,
  `e_mail` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `Telephone` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `Adresse` varchar(70) COLLATE latin1_general_ci NOT NULL,
  `Pere_id` int(11) NOT NULL,
  `Mere_id` int(11) NOT NULL,
  `Conjoint_id` int(11) NOT NULL,
  `Confession` varchar(15) COLLATE latin1_general_ci NOT NULL,
  `Bapteme` date NOT NULL,
  `Communion` date NOT NULL,
  `ProfessionFoi` date NOT NULL,
  `Confirmation` date NOT NULL,
  `Souhaits` bigint(64) UNSIGNED NOT NULL,
  `Commentaire` varchar(350) COLLATE latin1_general_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `lieux`
--

CREATE TABLE `lieux` (
  `id` int(11) NOT NULL,
  `Lieu` varchar(30) COLLATE latin1_general_ci NOT NULL,
  `IsParoisse` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `qualite`
--

CREATE TABLE `qualite` (
  `id` int(11) NOT NULL,
  `Qualite` varchar(20) COLLATE latin1_general_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `quiquoi`
--

CREATE TABLE `quiquoi` (
  `id` int(11) NOT NULL,
  `Individu_id` int(11) NOT NULL,
  `Activite_id` int(11) NOT NULL,
  `Engagement_id` int(11) NOT NULL DEFAULT '0',
  `QuoiQuoi_id` int(11) DEFAULT '0',
  `Session` varchar(7) COLLATE latin1_general_ci DEFAULT NULL,
  `Participation` float UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Participation financière en Euro',
  `Essentiel_Fraternite` tinyint(1) NOT NULL DEFAULT '0',
  `Essentiel_Adoration` tinyint(1) NOT NULL DEFAULT '0',
  `Essentiel_Service` tinyint(1) NOT NULL DEFAULT '0',
  `Essentiel_Formation` tinyint(1) NOT NULL DEFAULT '0',
  `Essentiel_Mission` tinyint(1) NOT NULL DEFAULT '0',
  `Responsable` tinyint(1) NOT NULL DEFAULT '0',
  `Point_de_contact` tinyint(1) NOT NULL DEFAULT '0',
  `WEB_G` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Gestionnaire Database',
  `Lieu_id` int(11) NOT NULL DEFAULT '0',
  `Detail` varchar(10) COLLATE latin1_general_ci DEFAULT NULL,
  `Ecole_id` int(11) DEFAULT '0',
  `Detail_02` varchar(6) COLLATE latin1_general_ci NOT NULL DEFAULT '' COMMENT 'KT et Aumônerie, contient Sacrement demandé + Certificat Baptême'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `quoiquoi`
--

CREATE TABLE `quoiquoi` (
  `id` int(11) NOT NULL,
  `QuoiDansEngagement` varchar(20) COLLATE latin1_general_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `rencontres`
--

CREATE TABLE `rencontres` (
  `id` int(11) NOT NULL,
  `Activite_id` int(11) NOT NULL,
  `Session` varchar(7) COLLATE latin1_general_ci NOT NULL,
  `Date` datetime NOT NULL,
  `Classement` varchar(11) COLLATE latin1_general_ci NOT NULL,
  `Intitule` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `Lieux_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Index pour les tables exportées
--

--
-- Index pour la table `activites`
--
ALTER TABLE `activites`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `admin_membres`
--
ALTER TABLE `admin_membres`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `userid` (`Individu_id`);

--
-- Index pour la table `bapteme`
--
ALTER TABLE `bapteme`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `celebrations_rec`
--
ALTER TABLE `celebrations_rec`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `debug`
--
ALTER TABLE `debug`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `denier`
--
ALTER TABLE `denier`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `ecoles`
--
ALTER TABLE `ecoles`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `fiancés`
--
ALTER TABLE `fiancés`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `fraternite`
--
ALTER TABLE `fraternite`
  ADD KEY `id` (`id`);

--
-- Index pour la table `individu`
--
ALTER TABLE `individu`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);
ALTER TABLE `individu` ADD FULLTEXT KEY `Nom` (`Nom`,`Prenom`,`e_mail`,`Adresse`,`Commentaire`);

--
-- Index pour la table `lieux`
--
ALTER TABLE `lieux`
  ADD UNIQUE KEY `id` (`id`);

--
-- Index pour la table `qualite`
--
ALTER TABLE `qualite`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `quiquoi`
--
ALTER TABLE `quiquoi`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `quoiquoi`
--
ALTER TABLE `quoiquoi`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `rencontres`
--
ALTER TABLE `rencontres`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `activites`
--
ALTER TABLE `activites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=170;
--
-- AUTO_INCREMENT pour la table `admin_membres`
--
ALTER TABLE `admin_membres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;
--
-- AUTO_INCREMENT pour la table `bapteme`
--
ALTER TABLE `bapteme`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2068;
--
-- AUTO_INCREMENT pour la table `celebrations_rec`
--
ALTER TABLE `celebrations_rec`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT pour la table `debug`
--
ALTER TABLE `debug`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `denier`
--
ALTER TABLE `denier`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1950;
--
-- AUTO_INCREMENT pour la table `ecoles`
--
ALTER TABLE `ecoles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT pour la table `fiancés`
--
ALTER TABLE `fiancés`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=974;
--
-- AUTO_INCREMENT pour la table `fraternite`
--
ALTER TABLE `fraternite`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=221;
--
-- AUTO_INCREMENT pour la table `individu`
--
ALTER TABLE `individu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6423;
--
-- AUTO_INCREMENT pour la table `lieux`
--
ALTER TABLE `lieux`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT pour la table `qualite`
--
ALTER TABLE `qualite`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT pour la table `quiquoi`
--
ALTER TABLE `quiquoi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13817;
--
-- AUTO_INCREMENT pour la table `quoiquoi`
--
ALTER TABLE `quoiquoi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
--
-- AUTO_INCREMENT pour la table `rencontres`
--
ALTER TABLE `rencontres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=323;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
