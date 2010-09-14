-- phpMyAdmin SQL Dump
-- version 3.1.1
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Mar 14 Septembre 2010 à 12:09
-- Version du serveur: 5.1.30
-- Version de PHP: 5.2.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `cy_tagaudio`
--

-- --------------------------------------------------------

--
-- Structure de la table `flux_docs`
--

CREATE TABLE IF NOT EXISTS `flux_docs` (
  `id_doc` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `branche` int(11) NOT NULL,
  `tronc` varchar(255) NOT NULL,
  `content_type` varchar(255) NOT NULL,
  PRIMARY KEY (`id_doc`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=901 ;

-- --------------------------------------------------------

--
-- Structure de la table `flux_exis`
--

CREATE TABLE IF NOT EXISTS `flux_exis` (
  `id_exi` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `mail` varchar(255) NOT NULL,
  PRIMARY KEY (`id_exi`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=102 ;

-- --------------------------------------------------------

--
-- Structure de la table `flux_exis_docs`
--

CREATE TABLE IF NOT EXISTS `flux_exis_docs` (
  `id_exi` int(11) NOT NULL,
  `id_doc` int(11) NOT NULL,
  `id_instant` int(11) NOT NULL,
  PRIMARY KEY (`id_exi`,`id_doc`,`id_instant`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `flux_instants`
--

CREATE TABLE IF NOT EXISTS `flux_instants` (
  `id_instant` int(11) NOT NULL AUTO_INCREMENT,
  `maintenant` datetime NOT NULL,
  `ici` varchar(255) NOT NULL,
  `id_exi` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  PRIMARY KEY (`id_instant`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=26 ;

-- --------------------------------------------------------

--
-- Structure de la table `flux_tags`
--

CREATE TABLE IF NOT EXISTS `flux_tags` (
  `id_tag` int(11) NOT NULL AUTO_INCREMENT,
  `tag` varchar(255) NOT NULL,
  `poids` int(11) NOT NULL,
  PRIMARY KEY (`id_tag`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1765 ;

-- --------------------------------------------------------

--
-- Structure de la table `flux_tags_docs`
--

CREATE TABLE IF NOT EXISTS `flux_tags_docs` (
  `id_tag` int(11) NOT NULL,
  `id_doc` int(11) NOT NULL,
  `id_instant` int(11) NOT NULL,
  PRIMARY KEY (`id_tag`,`id_doc`,`id_instant`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `flux_tags_exis`
--

CREATE TABLE IF NOT EXISTS `flux_tags_exis` (
  `id_exi` int(11) NOT NULL AUTO_INCREMENT,
  `id_tag` int(11) NOT NULL,
  `poids` int(11) NOT NULL,
  `id_instant` int(11) NOT NULL,
  PRIMARY KEY (`id_tag`,`id_exi`,`id_instant`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `flux_tags_tags`
--

CREATE TABLE IF NOT EXISTS `flux_tags_tags` (
  `id_tag_src` int(11) NOT NULL,
  `id_tag_dst` int(11) NOT NULL,
  `poids` int(11) NOT NULL,
  `id_instant` int(11) NOT NULL,
  PRIMARY KEY (`id_tag_src`,`id_tag_dst`,`id_instant`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
