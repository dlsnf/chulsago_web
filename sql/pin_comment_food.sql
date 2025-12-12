-- phpMyAdmin SQL Dump
-- version 4.0.10.18
-- https://www.phpmyadmin.net
--
-- 호스트: localhost
-- 처리한 시간: 17-12-23 14:38
-- 서버 버전: 5.1.73
-- PHP 버전: 5.3.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 데이터베이스: `chulsago`
--

-- --------------------------------------------------------

--
-- 테이블 구조 `pin_comment_food`
--

CREATE TABLE IF NOT EXISTS `pin_comment_food` (
  `seq` int(100) NOT NULL AUTO_INCREMENT,
  `user_seq` int(100) NOT NULL,
  `type` varchar(30) COLLATE utf8_bin NOT NULL,
  `pin_seq` int(100) NOT NULL,
  `body` varchar(1000) COLLATE utf8_bin NOT NULL,
  `like` int(100) NOT NULL DEFAULT '0',
  `status` varchar(10) COLLATE utf8_bin NOT NULL DEFAULT 'able',
  `date_` datetime NOT NULL,
  `ip` varchar(100) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`seq`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

--
-- 테이블의 덤프 데이터 `pin_comment_food`
--


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
