-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb2+deb7u1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Сен 01 2015 г., 22:34
-- Версия сервера: 5.5.43
-- Версия PHP: 5.4.41-0+deb7u1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT=0;
START TRANSACTION;


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `battery`
--
CREATE DATABASE `battery` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `battery`;

-- --------------------------------------------------------

--
-- Структура таблицы `Lead_acid_OCV`
--

CREATE TABLE IF NOT EXISTS `Lead_acid_OCV` (
  `percent` tinyint(3) unsigned NOT NULL,
  `voltage` decimal(5,3) unsigned NOT NULL,
  PRIMARY KEY (`percent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `Lead_acid_OCV`
--

INSERT INTO `Lead_acid_OCV` (`percent`, `voltage`) VALUES
(0, 1.900),
(10, 1.918),
(20, 1.943),
(30, 1.969),
(40, 1.993),
(50, 2.017),
(60, 2.040),
(70, 2.062),
(80, 2.083),
(90, 2.103),
(100, 2.122);

-- --------------------------------------------------------

--
-- Структура таблицы `Lead_acid_OCV_6`
--

CREATE TABLE IF NOT EXISTS `Lead_acid_OCV_6` (
  `percent` tinyint(3) unsigned NOT NULL,
  `voltage` decimal(5,3) unsigned NOT NULL,
  PRIMARY KEY (`percent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `Lead_acid_OCV_6`
--

INSERT INTO `Lead_acid_OCV_6` (`percent`, `voltage`) VALUES
(0, 11.400),
(10, 11.510),
(20, 11.660),
(30, 11.810),
(40, 11.960),
(50, 12.100),
(60, 12.240),
(70, 12.370),
(80, 12.500),
(90, 12.620),
(100, 12.730);

-- --------------------------------------------------------

--
-- Структура таблицы `LiFePo4_37_OCV`
--

CREATE TABLE IF NOT EXISTS `LiFePo4_37_OCV` (
  `percent` tinyint(3) unsigned NOT NULL,
  `voltage` decimal(5,3) unsigned NOT NULL,
  PRIMARY KEY (`percent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `LiFePo4_37_OCV`
--

INSERT INTO `LiFePo4_37_OCV` (`percent`, `voltage`) VALUES
(0, 3.000),
(10, 3.200),
(15, 3.210),
(20, 3.230),
(25, 3.250),
(30, 3.260),
(40, 3.280),
(50, 3.290),
(60, 3.290),
(70, 3.300),
(75, 3.320),
(80, 3.330),
(90, 3.330),
(100, 3.340);

-- --------------------------------------------------------

--
-- Структура таблицы `battery_cycle`
--

CREATE TABLE IF NOT EXISTS `battery_cycle` (
  `number` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `integral_dCdt` decimal(6,3) NOT NULL,
  `C_current_Ah` decimal(7,3) unsigned NOT NULL,
  `C_current_percent` decimal(6,3) unsigned NOT NULL,
  `I_avg` decimal(6,2) NOT NULL,
  `user_counter` decimal(10,3) NOT NULL,
  `estimated_SOC` decimal(10,1) NOT NULL,
  `ah_accumulator` decimal(7,3) NOT NULL,
  `timer` int(10) unsigned NOT NULL,
  PRIMARY KEY (`number`),
  KEY `date` (`date`),
  KEY `time` (`time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `battery_info`
--

CREATE TABLE IF NOT EXISTS `battery_info` (
  `Battery_Number` tinyint(2) unsigned NOT NULL,
  `cells_number` tinyint(3) unsigned NOT NULL,
  `start_date` date NOT NULL,
  `SERVER_IP` tinytext NOT NULL,
  `C_nominal` smallint(5) unsigned NOT NULL,
  `C_measured` decimal(5,1) unsigned NOT NULL,
  `t_nominal` tinyint(3) unsigned NOT NULL,
  `alpha` decimal(5,4) NOT NULL,
  `n_p` decimal(3,2) unsigned NOT NULL,
  `coulombic_eff` decimal(4,3) unsigned NOT NULL,
  `voltage` tinyint(3) unsigned NOT NULL,
  `charged_voltage` decimal(6,3) unsigned NOT NULL,
  `min_charged_current` decimal(4,3) unsigned NOT NULL,
  `rest_time_min` smallint(5) unsigned NOT NULL,
  `SHARED` tinyint(2) unsigned NOT NULL,
  `U_ocv_invalid_min` decimal(5,3) unsigned NOT NULL,
  `U_ocv_invalid_max` decimal(5,3) unsigned NOT NULL,
  `I_source` tinyint(4) NOT NULL COMMENT '0-MAP, 1-MPPT',
  `dod_off` tinyint(3) unsigned NOT NULL,
  `ah_off` smallint(5) unsigned NOT NULL,
  `id` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `battery_info`
--

INSERT INTO `battery_info` (`Battery_Number`, `cells_number`, `start_date`, `SERVER_IP`, `C_nominal`, `C_measured`, `t_nominal`, `alpha`, `n_p`, `coulombic_eff`, `voltage`, `charged_voltage`, `min_charged_current`, `rest_time_min`, `SHARED`, `U_ocv_invalid_min`, `U_ocv_invalid_max`, `I_source`, `dod_off`, `ah_off`, `id`) VALUES
(0, 16, '2015-05-14', '', 240, 240.0, 20, 0.0100, 1.05, 0.990, 48, 54.600, 0.020, 180, 0, 0.000, 0.000, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `battery_state`
--

CREATE TABLE IF NOT EXISTS `battery_state` (
  `number` tinyint(4) unsigned NOT NULL,
  `deepest_discharge` decimal(10,1) unsigned NOT NULL,
  `last_discharge` decimal(10,1) unsigned NOT NULL,
  `average_discharge` decimal(10,1) unsigned NOT NULL,
  `discharge_cycles` int(10) unsigned NOT NULL,
  `full_discharges` smallint(5) unsigned NOT NULL,
  `summary_ah` decimal(10,1) unsigned NOT NULL,
  `lowest_voltage` decimal(5,2) unsigned NOT NULL,
  `highest_voltage` decimal(5,2) unsigned NOT NULL,
  `last_charge_date` date NOT NULL,
  `number_autosync` int(10) unsigned NOT NULL,
  `E_summary_to_battery` decimal(10,1) unsigned NOT NULL,
  `E_summary_from_battery` decimal(10,1) unsigned NOT NULL,
  `E_from_battery_since_ls` decimal(10,1) unsigned NOT NULL,
  `E_alt_daily` decimal(6,3) unsigned NOT NULL,
  `E_alt_monthly` decimal(10,2) unsigned NOT NULL,
  `E_alt_summary` decimal(10,1) unsigned NOT NULL,
  `E_alt_user` decimal(10,3) unsigned NOT NULL,
  PRIMARY KEY (`number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `battery_state`
--

INSERT INTO `battery_state` (`number`, `deepest_discharge`, `last_discharge`, `average_discharge`, `discharge_cycles`, `full_discharges`, `summary_ah`, `lowest_voltage`, `highest_voltage`, `last_charge_date`, `number_autosync`, `E_summary_to_battery`, `E_summary_from_battery`, `E_from_battery_since_ls`, `E_alt_daily`, `E_alt_monthly`, `E_alt_summary`, `E_alt_user`) VALUES
(1, 0.0, 0.0, 0.0, 0, 0, 0.0, 0.00, 0.00, '0000-00-00', 0, 0.0, 0.0, 0.0, 0.000, 0.00, 0.0, 0.000);

-- --------------------------------------------------------

--
-- Структура таблицы `estimate`
--

CREATE TABLE IF NOT EXISTS `estimate` (
  `number` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `delta_SOC` decimal(10,1) NOT NULL,
  `delta_C` decimal(10,1) NOT NULL,
  `estimated_C` decimal(10,1) unsigned NOT NULL,
  PRIMARY KEY (`number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `user_OCV`
--

CREATE TABLE IF NOT EXISTS `user_OCV` (
  `percent` tinyint(3) unsigned NOT NULL,
  `voltage` decimal(5,3) unsigned NOT NULL,
  PRIMARY KEY (`percent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `user_OCV`
--

INSERT INTO `user_OCV` (`percent`, `voltage`) VALUES
(0, 3.000),
(10, 3.200),
(15, 3.210),
(20, 3.230),
(25, 3.250),
(30, 3.260),
(40, 3.280),
(50, 3.290),
(60, 3.290),
(70, 3.300),
(75, 3.320),
(80, 3.330),
(90, 3.330),
(100, 3.340);

-- --------------------------------------------------------

--
-- Структура таблицы `work_table`
--

CREATE TABLE IF NOT EXISTS `work_table` (
  `percent` tinyint(3) unsigned NOT NULL,
  `voltage` decimal(5,3) unsigned NOT NULL,
  PRIMARY KEY (`percent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `work_table`
--

INSERT INTO `work_table` (`percent`, `voltage`) VALUES
(0, 3.000),
(10, 3.200),
(15, 3.210),
(20, 3.230),
(25, 3.250),
(30, 3.260),
(40, 3.280),
(50, 3.290),
(60, 3.290),
(70, 3.300),
(75, 3.320),
(80, 3.330),
(90, 3.330),
(100, 3.340);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
