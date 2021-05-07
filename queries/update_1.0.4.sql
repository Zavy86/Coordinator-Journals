--
-- Journals - Update (1.0.4)
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
-- Alter table `journals__tasks`
--

ALTER TABLE `journals__tasks` ADD `today` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' AFTER `completed`;

-- --------------------------------------------------------

SET FOREIGN_KEY_CHECKS = 1;

-- --------------------------------------------------------
