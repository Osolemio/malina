-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb2+deb7u1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Янв 06 2016 г., 19:33
-- Версия сервера: 5.5.44
-- Версия PHP: 5.4.45-0+deb7u1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT=0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `map`
--
CREATE DATABASE `map` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `map`;

-- --------------------------------------------------------

--
-- Структура таблицы `bms`
--

CREATE TABLE IF NOT EXISTS `bms` (
  `cell_number` tinyint(3) unsigned NOT NULL,
  `U` decimal(3,2) unsigned NOT NULL,
  `I` decimal(3,2) unsigned NOT NULL,
  `t` tinyint(3) NOT NULL
) ENGINE=MEMORY DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `bms_alert`
--

CREATE TABLE IF NOT EXISTS `bms_alert` (
  `series_number` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `cell_number` tinyint(2) unsigned NOT NULL,
  `U` decimal(4,2) unsigned NOT NULL,
  `I` decimal(4,2) unsigned NOT NULL,
  `t` tinyint(3) NOT NULL,
  PRIMARY KEY (`series_number`,`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `data`
--

CREATE TABLE IF NOT EXISTS `data` (
  `number` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `_MODE` tinyint(3) unsigned NOT NULL,
  `_Status_Char` tinyint(3) unsigned NOT NULL,
  `_Uacc` decimal(3,1) unsigned NOT NULL,
  `_Iacc` tinyint(3) unsigned NOT NULL,
  `_PLoad` smallint(5) unsigned NOT NULL,
  `_F_Acc_Over` tinyint(5) unsigned NOT NULL,
  `_F_Net_Over` tinyint(5) unsigned NOT NULL,
  `_UNET` smallint(5) unsigned NOT NULL,
  `_INET` tinyint(3) unsigned NOT NULL,
  `_PNET` smallint(5) unsigned NOT NULL,
  `_TFNET` tinyint(3) unsigned NOT NULL,
  `_ThFMAP` tinyint(3) unsigned NOT NULL,
  `_UOUTmed` smallint(3) unsigned NOT NULL,
  `_TFNET_Limit` tinyint(3) unsigned NOT NULL,
  `_UNET_Limit` smallint(3) unsigned NOT NULL,
  `_RSErrSis` tinyint(3) unsigned NOT NULL,
  `_RSErrJobM` tinyint(3) unsigned NOT NULL,
  `_RSErrJob` tinyint(3) unsigned NOT NULL,
  `_RSWarning` tinyint(3) unsigned NOT NULL,
  `_Temp_Grad0` tinyint(3) NOT NULL,
  `_Temp_Grad2` tinyint(3) NOT NULL,
  `_INET_16_4` decimal(3,1) unsigned NOT NULL,
  `_IAcc_med_A_u16` decimal(4,1) unsigned NOT NULL,
  `_Temp_off` tinyint(3) unsigned NOT NULL,
  `_E_NET` decimal(10,1) unsigned NOT NULL,
  `_E_ACC` decimal(10,1) unsigned NOT NULL,
  `_E_ACC_CHARGE` decimal(10,1) unsigned NOT NULL,
  `_Uacc_optim` decimal(3,1) unsigned NOT NULL,
  `_I_acc_avg` decimal(4,1) unsigned NOT NULL,
  `_I_mppt_avg` decimal(4,1) unsigned NOT NULL,
  `_I2C_Err` tinyint(3) unsigned NOT NULL,
  `_Temp_Grad1` tinyint(3) NOT NULL,
  `_Relay1` tinyint(3) unsigned NOT NULL,
  `_Relay2` tinyint(3) unsigned NOT NULL,
  `_Flag_ECO` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`number`,`date`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8
/*!50100 PARTITION BY RANGE (DAYOFYEAR(date))
(PARTITION p01 VALUES LESS THAN (10) ENGINE = InnoDB,
 PARTITION p02 VALUES LESS THAN (20) ENGINE = InnoDB,
 PARTITION p03 VALUES LESS THAN (30) ENGINE = InnoDB,
 PARTITION p04 VALUES LESS THAN (40) ENGINE = InnoDB,
 PARTITION p05 VALUES LESS THAN (50) ENGINE = InnoDB,
 PARTITION p06 VALUES LESS THAN (60) ENGINE = InnoDB,
 PARTITION p07 VALUES LESS THAN (70) ENGINE = InnoDB,
 PARTITION p08 VALUES LESS THAN (80) ENGINE = InnoDB,
 PARTITION p09 VALUES LESS THAN (90) ENGINE = InnoDB,
 PARTITION p11 VALUES LESS THAN (110) ENGINE = InnoDB,
 PARTITION p12 VALUES LESS THAN (120) ENGINE = InnoDB,
 PARTITION p13 VALUES LESS THAN (130) ENGINE = InnoDB,
 PARTITION p14 VALUES LESS THAN (140) ENGINE = InnoDB,
 PARTITION p15 VALUES LESS THAN (150) ENGINE = InnoDB,
 PARTITION p16 VALUES LESS THAN (160) ENGINE = InnoDB,
 PARTITION p17 VALUES LESS THAN (170) ENGINE = InnoDB,
 PARTITION p18 VALUES LESS THAN (180) ENGINE = InnoDB,
 PARTITION p19 VALUES LESS THAN (190) ENGINE = InnoDB,
 PARTITION p20 VALUES LESS THAN (200) ENGINE = InnoDB,
 PARTITION p21 VALUES LESS THAN (210) ENGINE = InnoDB,
 PARTITION p22 VALUES LESS THAN (220) ENGINE = InnoDB,
 PARTITION p23 VALUES LESS THAN (230) ENGINE = InnoDB,
 PARTITION p24 VALUES LESS THAN (240) ENGINE = InnoDB,
 PARTITION p25 VALUES LESS THAN (250) ENGINE = InnoDB,
 PARTITION p26 VALUES LESS THAN (260) ENGINE = InnoDB,
 PARTITION p27 VALUES LESS THAN (270) ENGINE = InnoDB,
 PARTITION p28 VALUES LESS THAN (280) ENGINE = InnoDB,
 PARTITION p29 VALUES LESS THAN (290) ENGINE = InnoDB,
 PARTITION p30 VALUES LESS THAN (300) ENGINE = InnoDB,
 PARTITION p31 VALUES LESS THAN (310) ENGINE = InnoDB,
 PARTITION p32 VALUES LESS THAN (320) ENGINE = InnoDB,
 PARTITION p33 VALUES LESS THAN (330) ENGINE = InnoDB,
 PARTITION p34 VALUES LESS THAN (340) ENGINE = InnoDB,
 PARTITION p35 VALUES LESS THAN (350) ENGINE = InnoDB,
 PARTITION p36 VALUES LESS THAN (360) ENGINE = InnoDB,
 PARTITION p37 VALUES LESS THAN (370) ENGINE = InnoDB) */ AUTO_INCREMENT=1300887 ;

-- --------------------------------------------------------

--
-- Структура таблицы `eeprom_result`
--

CREATE TABLE IF NOT EXISTS `eeprom_result` (
  `offset` tinyint(3) unsigned NOT NULL,
  `result` tinyint(3) unsigned NOT NULL
) ENGINE=MEMORY DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `map_errors`
--

CREATE TABLE IF NOT EXISTS `map_errors` (
  `number` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `_RSErrSis` tinyint(3) unsigned NOT NULL,
  `_RSErrJobM` tinyint(3) unsigned NOT NULL,
  `_RSErrJob` tinyint(3) unsigned NOT NULL,
  `_RSWarning` tinyint(3) unsigned NOT NULL,
  `_I2C_Err` tinyint(3) unsigned NOT NULL,
  UNIQUE KEY `number` (`number`),
  KEY `date` (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='map errors if =/= 0' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `mppt`
--

CREATE TABLE IF NOT EXISTS `mppt` (
  `number` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `Vc_PV` decimal(4,1) unsigned NOT NULL,
  `Ic_PV` decimal(4,1) unsigned NOT NULL,
  `V_Bat` decimal(3,1) unsigned NOT NULL,
  `P_PV` smallint(5) unsigned NOT NULL,
  `P_Out` smallint(5) unsigned NOT NULL,
  `P_Load` smallint(5) unsigned NOT NULL,
  `P_curr` smallint(5) unsigned NOT NULL,
  `I_Ch` decimal(3,1) unsigned NOT NULL,
  `I_Out` decimal(3,1) unsigned NOT NULL,
  `Temp_Int` tinyint(3) unsigned NOT NULL,
  `Temp_Bat` tinyint(3) unsigned NOT NULL,
  `Pwr_kW` decimal(5,3) unsigned NOT NULL,
  `Sign_C0` tinyint(3) unsigned NOT NULL,
  `Sign_C1` tinyint(3) unsigned NOT NULL,
  `I_EXTS0` smallint(5) unsigned NOT NULL,
  `I_EXTS1` smallint(5) unsigned NOT NULL,
  `P_EXTS0` smallint(5) unsigned NOT NULL,
  `P_EXTS1` smallint(5) unsigned NOT NULL,
  `Relay_C` tinyint(3) unsigned NOT NULL,
  `RSErrSis` tinyint(3) unsigned NOT NULL,
  `Mode` char(1) NOT NULL,
  `Sign` char(1) NOT NULL,
  `MPP` char(1) NOT NULL,
  `windspeed` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`number`,`date`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8
/*!50100 PARTITION BY RANGE (DAYOFYEAR(date))
(PARTITION p01 VALUES LESS THAN (10) ENGINE = InnoDB,
 PARTITION p02 VALUES LESS THAN (20) ENGINE = InnoDB,
 PARTITION p03 VALUES LESS THAN (30) ENGINE = InnoDB,
 PARTITION p04 VALUES LESS THAN (40) ENGINE = InnoDB,
 PARTITION p05 VALUES LESS THAN (50) ENGINE = InnoDB,
 PARTITION p06 VALUES LESS THAN (60) ENGINE = InnoDB,
 PARTITION p07 VALUES LESS THAN (70) ENGINE = InnoDB,
 PARTITION p08 VALUES LESS THAN (80) ENGINE = InnoDB,
 PARTITION p09 VALUES LESS THAN (90) ENGINE = InnoDB,
 PARTITION p11 VALUES LESS THAN (110) ENGINE = InnoDB,
 PARTITION p12 VALUES LESS THAN (120) ENGINE = InnoDB,
 PARTITION p13 VALUES LESS THAN (130) ENGINE = InnoDB,
 PARTITION p14 VALUES LESS THAN (140) ENGINE = InnoDB,
 PARTITION p15 VALUES LESS THAN (150) ENGINE = InnoDB,
 PARTITION p16 VALUES LESS THAN (160) ENGINE = InnoDB,
 PARTITION p17 VALUES LESS THAN (170) ENGINE = InnoDB,
 PARTITION p18 VALUES LESS THAN (180) ENGINE = InnoDB,
 PARTITION p19 VALUES LESS THAN (190) ENGINE = InnoDB,
 PARTITION p20 VALUES LESS THAN (200) ENGINE = InnoDB,
 PARTITION p21 VALUES LESS THAN (210) ENGINE = InnoDB,
 PARTITION p22 VALUES LESS THAN (220) ENGINE = InnoDB,
 PARTITION p23 VALUES LESS THAN (230) ENGINE = InnoDB,
 PARTITION p24 VALUES LESS THAN (240) ENGINE = InnoDB,
 PARTITION p25 VALUES LESS THAN (250) ENGINE = InnoDB,
 PARTITION p26 VALUES LESS THAN (260) ENGINE = InnoDB,
 PARTITION p27 VALUES LESS THAN (270) ENGINE = InnoDB,
 PARTITION p28 VALUES LESS THAN (280) ENGINE = InnoDB,
 PARTITION p29 VALUES LESS THAN (290) ENGINE = InnoDB,
 PARTITION p30 VALUES LESS THAN (300) ENGINE = InnoDB,
 PARTITION p31 VALUES LESS THAN (310) ENGINE = InnoDB,
 PARTITION p32 VALUES LESS THAN (320) ENGINE = InnoDB,
 PARTITION p33 VALUES LESS THAN (330) ENGINE = InnoDB,
 PARTITION p34 VALUES LESS THAN (340) ENGINE = InnoDB,
 PARTITION p35 VALUES LESS THAN (350) ENGINE = InnoDB,
 PARTITION p36 VALUES LESS THAN (360) ENGINE = InnoDB,
 PARTITION p37 VALUES LESS THAN (370) ENGINE = InnoDB) */ AUTO_INCREMENT=8083187 ;

-- --------------------------------------------------------

--
-- Структура таблицы `mppt_errors`
--

CREATE TABLE IF NOT EXISTS `mppt_errors` (
  `number` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `_RSErrSis` tinyint(3) unsigned NOT NULL,
  `_I2C_Err` tinyint(3) unsigned NOT NULL,
  UNIQUE KEY `number` (`number`),
  KEY `date` (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='mppt errors if =/=0' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `offset` smallint(3) unsigned NOT NULL,
  `value` tinyint(3) unsigned NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `sms_alert`
--

CREATE TABLE IF NOT EXISTS `sms_alert` (
  `number` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `field` text NOT NULL,
  `le` decimal(10,2) NOT NULL,
  `ge` decimal(10,2) NOT NULL,
  `sms` text NOT NULL,
  PRIMARY KEY (`number`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
