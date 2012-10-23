-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 21, 2012 at 03:59 PM
-- Server version: 5.5.24
-- PHP Version: 5.3.10-1ubuntu3.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `evespeak`
--

-- --------------------------------------------------------

--
-- Table structure for table `config`
--

CREATE TABLE IF NOT EXISTS `config` (
  `crypto_general_key` varchar(64) NOT NULL,
  `crypto_general_iv` varchar(64) NOT NULL,
  `crypto_ciphername` varchar(32) NOT NULL,
  `crypto_ciphermode` varchar(32) NOT NULL,
  `validation_substr` varchar(12) NOT NULL,
  `secure_cookie` tinyint(1) NOT NULL,
  `teamspeak_host` varchar(15) NOT NULL,
  `teamspeak_SAName` varchar(18) NOT NULL,
  `teamspeak_SAPassword` varchar(32) NOT NULL,
  `teamspeak_query_port` int(5) NOT NULL,
  `teamspeak_client_port` int(5) NOT NULL,
  `teamspeak_alliance_group` int(3) NOT NULL,
  `teamspeak_whitelist_group` int(3) NOT NULL,
  `teamspeak_ticker_format` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `config`
--

INSERT INTO `config` (`crypto_general_key`, `crypto_general_iv`, `crypto_ciphername`, `crypto_ciphermode`, `validation_substr`, `secure_cookie`, `teamspeak_host`, `teamspeak_SAName`, `teamspeak_SAPassword`, `teamspeak_query_port`, `teamspeak_client_port`, `teamspeak_alliance_group`, `teamspeak_whitelist_group`, `teamspeak_ticker_format`) VALUES
('JldQVnNoPEBudWJXSyElVTNTNmVnN2g1aEUyaGdWI1o=', 'aTJ4TFEsPVg6Wn4zUCY4QmY4RiYja2RzOG5sYTgzOTA=', 'MCRYPT_RIJNDAEL_256', 'MCRYPT_MODE_CBC', '3R8!UM', 0, 'localhost', 'serveradmin', 'theSApassword', 10011, 9987, 13, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `hashwords`
--

CREATE TABLE IF NOT EXISTS `hashwords` (
  `strings` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE IF NOT EXISTS `sessions` (
  `uid` int(6) NOT NULL,
  `hash` varchar(255) NOT NULL,
  `key` varchar(255) NOT NULL COMMENT 'The "key" that actually validates the session when being read from cookie',
  `agent_ip` varchar(15) NOT NULL,
  `user_agent` varchar(255) NOT NULL,
  `length_of_validity` int(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Stores sessions, used to validate cookies for login';

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `uid` int(16) NOT NULL AUTO_INCREMENT,
  `locked` tinyint(1) NOT NULL,
  `admin` tinyint(1) NOT NULL,
  `username` int(255) NOT NULL,
  `api_ID` int(18) NOT NULL,
  `api_Key` varchar(255) NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
