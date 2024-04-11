-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: db
-- Tiempo de generación: 23-02-2024 a las 16:48:05
-- Versión del servidor: 8.0.27
-- Versión de PHP: 8.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `db_grupo25`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `final_teams`
--

CREATE TABLE `final_teams` (
  `id` int NOT NULL,
  `abbreviation` varchar(10) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `conference` varchar(50) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `division` varchar(50) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `full_name` varchar(255) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_spanish2_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `final_teams`
--

INSERT INTO `final_teams` (`id`, `abbreviation`, `city`, `conference`, `division`, `full_name`, `name`) VALUES
(1, 'ATL', 'Atlanta', 'East', 'Southeast', 'Atlanta Hawks', 'Hawks'),
(2, 'BOS', 'Boston', 'East', 'Atlantic', 'Boston Celtics', 'Celtics'),
(3, 'BKN', 'Brooklyn', 'East', 'Atlantic', 'Brooklyn Nets', 'Nets'),
(4, 'CHA', 'Charlotte', 'East', 'Southeast', 'Charlotte Hornets', 'Hornets'),
(5, 'CHI', 'Chicago', 'East', 'Central', 'Chicago Bulls', 'Bulls'),
(6, 'CLE', 'Cleveland', 'East', 'Central', 'Cleveland Cavaliers', 'Cavaliers'),
(7, 'DAL', 'Dallas', 'West', 'Southwest', 'Dallas Mavericks', 'Mavericks'),
(8, 'DEN', 'Denver', 'West', 'Northwest', 'Denver Nuggets', 'Nuggets'),
(9, 'DET', 'Detroit', 'East', 'Central', 'Detroit Pistons', 'Pistons'),
(10, 'GSW', 'Golden State', 'West', 'Pacific', 'Golden State Warriors', 'Warriors'),
(11, 'HOU', 'Houston', 'West', 'Southwest', 'Houston Rockets', 'Rockets'),
(12, 'IND', 'Indiana', 'East', 'Central', 'Indiana Pacers', 'Pacers'),
(13, 'LAC', 'LA', 'West', 'Pacific', 'LA Clippers', 'Clippers'),
(14, 'LAL', 'Los Angeles', 'West', 'Pacific', 'Los Angeles Lakers', 'Lakers'),
(15, 'MEM', 'Memphis', 'West', 'Southwest', 'Memphis Grizzlies', 'Grizzlies'),
(16, 'MIA', 'Miami', 'East', 'Southeast', 'Miami Heat', 'Heat'),
(17, 'MIL', 'Milwaukee', 'East', 'Central', 'Milwaukee Bucks', 'Bucks'),
(18, 'MIN', 'Minnesota', 'West', 'Northwest', 'Minnesota Timberwolves', 'Timberwolves'),
(19, 'NOP', 'New Orleans', 'West', 'Southwest', 'New Orleans Pelicans', 'Pelicans'),
(20, 'NYK', 'New York', 'East', 'Atlantic', 'New York Knicks', 'Knicks'),
(21, 'OKC', 'Oklahoma City', 'West', 'Northwest', 'Oklahoma City Thunder', 'Thunder'),
(22, 'ORL', 'Orlando', 'East', 'Southeast', 'Orlando Magic', 'Magic'),
(23, 'PHI', 'Philadelphia', 'East', 'Atlantic', 'Philadelphia 76ers', '76ers'),
(24, 'PHX', 'Phoenix', 'West', 'Pacific', 'Phoenix Suns', 'Suns'),
(25, 'POR', 'Portland', 'West', 'Northwest', 'Portland Trail Blazers', 'Trail Blazers'),
(26, 'SAC', 'Sacramento', 'West', 'Pacific', 'Sacramento Kings', 'Kings'),
(27, 'SAS', 'San Antonio', 'West', 'Southwest', 'San Antonio Spurs', 'Spurs'),
(28, 'TOR', 'Toronto', 'East', 'Atlantic', 'Toronto Raptors', 'Raptors'),
(29, 'UTA', 'Utah', 'West', 'Northwest', 'Utah Jazz', 'Jazz'),
(30, 'WAS', 'Washington', 'East', 'Southeast', 'Washington Wizards', 'Wizards'),
(37, 'CHS', '', '    ', '', 'Chicago Stags', 'Chicago Stags'),
(38, 'BOM', '', '    ', '', 'St. Louis Bombers', 'St. Louis Bombers'),
(39, 'CLR', '', '    ', '', 'Cleveland Rebels', 'Cleveland Rebels'),
(40, 'DEF', '', '    ', '', 'Detroit Falcons', 'Detroit Falcons'),
(41, 'HUS', '', '    ', '', 'Toronto Huskies', 'Toronto Huskies'),
(42, 'WAS', '', '    ', '', 'Washington Capitols', 'Washington Capitols'),
(43, 'PRO', '', '    ', '', 'Providence Steamrollers', 'Providence Steamrollers'),
(44, 'PIT', '', '    ', '', 'Pittsburgh Ironmen', 'Pittsburgh Ironmen'),
(45, 'BAL', '', '    ', '', 'Baltimore Bullets', 'Baltimore Bullets'),
(46, 'JET', '', '    ', '', 'Indianapolis Jets', 'Indianapolis Jets'),
(47, 'AND', '', '    ', '', 'Anderson Packers', 'Anderson Packers'),
(48, 'WAT', '', '    ', '', 'Waterloo Hawks', 'Waterloo Hawks'),
(49, 'INO', '', '    ', '', 'Indianapolis Olympians', 'Indianapolis Olympians'),
(50, 'DN', '', '    ', '', 'Denver Nuggets', 'Denver Nuggets'),
(51, 'SHE', '', '    ', '', 'Sheboygan Redskins', 'Sheboygan Redskins');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `final_teams`
--
ALTER TABLE `final_teams`
  ADD PRIMARY KEY (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
