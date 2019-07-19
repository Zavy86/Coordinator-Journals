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



-- --------------------------------------------------------

--
-- Authorizations
--

INSERT IGNORE INTO `framework__modules__authorizations` (`id`,`fkModule`,`order`) VALUES
('journals-manage','journals',1);

-- --------------------------------------------------------

SET FOREIGN_KEY_CHECKS = 1;

-- --------------------------------------------------------
