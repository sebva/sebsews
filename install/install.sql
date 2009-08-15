-- phpMyAdmin SQL Dump
-- version 3.1.3.1
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Sam 15 Août 2009 à 21:45
-- Version du serveur: 5.1.33
-- Version de PHP: 5.2.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `sebseasywebsite`
--

-- --------------------------------------------------------

--
-- Structure de la table `news`
--

DROP TABLE IF EXISTS `news`;
CREATE TABLE IF NOT EXISTS `news` (
  `id` smallint(2) NOT NULL AUTO_INCREMENT,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `link` tinyint(1) NOT NULL,
  `auteur` mediumtext NOT NULL,
  `title` mediumtext NOT NULL,
  `text` text NOT NULL,
  `categorie` text NOT NULL,
  `categorieshort` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Contenu de la table `news`
--

INSERT INTO `news` (`id`, `date`, `link`, `auteur`, `title`, `text`, `categorie`, `categorieshort`) VALUES
(1, '2009-07-08 19:48:34', 1, 'sebyx', 'Bienvenue dans Seb\\''s EasyWebSite !', '<p>Seb\\''s EasyWebSite est un système PHP/MySQL vous facilitant le gestion de votre site web !</p>\r\n<p>Il propose un module de news avec flux RSS ainsi que système de gestion des pages. L\\''édition se fait à l\\''aide de la console d\\''administration, laquelle contient FCKEditor, un éditeur WYSIWYG.</p>\r\n<p>Le système inclut aussi quelques services Google préconfigurés.</p>\r\n<p>Bon Webmastering !</p>', 'Général', 'general');

-- --------------------------------------------------------

--
-- Structure de la table `pages`
--

DROP TABLE IF EXISTS `pages`;
CREATE TABLE IF NOT EXISTS `pages` (
  `id` smallint(4) NOT NULL AUTO_INCREMENT,
  `shorttitle` mediumtext NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `title` mediumtext NOT NULL,
  `text` text NOT NULL,
  `menuseul` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `shorttitle` (`shorttitle`(256))
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Contenu de la table `pages`
--

INSERT INTO `pages` (`id`, `shorttitle`, `date`, `title`, `text`, `menuseul`) VALUES
(7, 'news', '2009-08-15 20:49:34', 'News', '', 1),
(1, 'index', '2009-08-15 20:50:21', 'Accueil', '<p>Bienvenue dans <strong>Séb\\''s</strong> <span style=\\"color: rgb(0, 0, 255);\\"><strong>EasyWebSite</strong></span> !</p>', 0);
