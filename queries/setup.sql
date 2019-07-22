--
-- Journals - Setup (1.0.0)
--
-- @package Coordinator\Modules\Journals
-- @author  Manuel Zavatta <manuel.zavatta@gmail.com>
-- @link    http://www.coordinator.it
--

-- --------------------------------------------------------

SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

-- --------------------------------------------------------

--
-- Struttura della tabella `journals__tags`
--

CREATE TABLE IF NOT EXISTS `journals__tags` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `deleted` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `fkUser` int(11) unsigned NOT NULL,
  `name` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `color` varchar(7) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE` (`fkUser`,`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `journals__tasks`
--

CREATE TABLE IF NOT EXISTS `journals__tasks` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `deleted` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `completed` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `fkUser` int(11) unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Struttura della tabella `journals__tasks__join__tags`
--

CREATE TABLE IF NOT EXISTS `journals__tasks__join__tags` (
  `fkTask` int(11) unsigned NOT NULL,
  `fkTag` int(11) unsigned NOT NULL,
  PRIMARY KEY (`fkTask`,`fkTag`),
  KEY `fkTask` (`fkTask`),
  KEY `fkTag` (`fkTag`),
  CONSTRAINT `journals__tasks__join__tags_ibfk_1` FOREIGN KEY (`fkTask`) REFERENCES `journals__tasks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `journals__tasks__join__tags_ibfk_2` FOREIGN KEY (`fkTag`) REFERENCES `journals__tags` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `journals__notebooks`
--

CREATE TABLE IF NOT EXISTS `journals__notebooks` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `deleted` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `fkUser` int(11) unsigned NOT NULL,
  `content` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `fkUser` (`fkUser`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Authorizations
--

INSERT IGNORE INTO `framework__modules__authorizations` (`id`,`fkModule`,`order`) VALUES
('journals-manage','journals',1),
('journals-usage','journals',2);

-- --------------------------------------------------------

SET FOREIGN_KEY_CHECKS = 1;

-- --------------------------------------------------------
