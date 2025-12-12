-- phpMyAdmin SQL Dump
-- version 4.0.10.18
-- https://www.phpmyadmin.net
--
-- 호스트: localhost
-- 처리한 시간: 17-11-27 22:14
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
-- 테이블 구조 `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `seq` int(30) NOT NULL AUTO_INCREMENT,
  `email` varchar(30) COLLATE utf8_bin NOT NULL,
  `password` varchar(1000) COLLATE utf8_bin DEFAULT NULL,
  `name` varchar(30) COLLATE utf8_bin NOT NULL,
  `point` int(100) NOT NULL DEFAULT '0',
  `type` varchar(30) COLLATE utf8_bin NOT NULL,
  `id` varchar(30) COLLATE utf8_bin NOT NULL,
  `profile_image` varchar(1000) COLLATE utf8_bin NOT NULL,
  `thumbnail_image` varchar(1000) COLLATE utf8_bin NOT NULL,
  `date_` datetime NOT NULL,
  `ip` varchar(30) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`seq`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=14 ;

--
-- 테이블의 덤프 데이터 `user`
--

INSERT INTO `user` (`seq`, `email`, `password`, `name`, `point`, `type`, `id`, `profile_image`, `thumbnail_image`, `date_`, `ip`) VALUES
(1, 'dlsnf@naver.com', NULL, '이누리', 0, 'kakao', '443306479', 'https://mud-kage.kakao.com/14/dn/btqgIkwmZ3U/pz1K1x8mkKNKjq2l94zg8k/o.jpg', 'https://mud-kage.kakao.com/14/dn/btqgIjRNFAJ/TDflYi7CHxTv0HU0drsAy1/o.jpg', '2017-06-06 16:07:50', '221.151.185.93'),
(2, '456fnsk@naver.com', NULL, '김소희', 0, 'kakao', '443357961', 'https://mud-kage.kakao.com/14/dn/btqgIf9U9Ud/KQiAITVN9sKWUOkKkOi080/o.jpg', 'https://mud-kage.kakao.com/14/dn/btqgHSHoTon/nZiTZSNjgIk1MpGgikl7v1/o.jpg', '2017-06-06 17:58:54', '221.151.185.93'),
(3, 'dlsnf@naver.com', NULL, '이누리', 68, 'kakao', '447038048', '3_2017-11-26_22:56:32.jpg', 'http://samplusil.cafe24.com:8080/chulsago/upload/img_profile/thumbnail/400_3_2017-11-26_22:56:32.jpg', '2017-06-13 17:30:21', '1.230.252.45'),
(4, 'samplusil@naver.com', NULL, '삼더하기일', 0, 'kakao', '447040238', '', '', '2017-06-13 17:33:28', '1.230.252.45'),
(5, '456fnsk@naver.com', NULL, '김소희', 6, 'kakao', '466258730', 'https://mud-kage.kakao.com/14/dn/btqgSXntE5d/ORIzecYwGa0vFrEk4jiMy1/o.jpg', 'https://mud-kage.kakao.com/14/dn/btqgTuYSIFN/Kk7RySkhnKLzhFYlBCK1m0/o.jpg', '2017-07-13 14:38:11', '39.7.58.145'),
(10, 'samplusil@naver.com', '5ced9622022af1f350002c0ec257c7b5ed32a096d92625aff1d823877a8227e7', 'nick_511', 1, 'app', '', '10_2017-11-26_13:48:54.jpg', 'http://samplusil.cafe24.com:8080/chulsago/upload/img_profile/thumbnail/400_10_2017-11-26_13:48:54.jpg', '2017-10-11 16:46:03', '119.207.161.127'),
(11, 'dlsnf@naver.com', '5ced9622022af1f350002c0ec257c7b5ed32a096d92625aff1d823877a8227e7', '꾸꾸까까', 3, 'app', '', '11_2017-11-27_22:02:26.jpg', 'http://samplusil.cafe24.com:8080/chulsago/upload/img_profile/thumbnail/400_11_2017-11-27_22:02:26.jpg', '2017-10-11 17:16:56', '119.207.161.127'),
(12, 'kssng093@naver.com', NULL, '김상성', 9, 'kakao', '558851671', 'https://k.kakaocdn.net/dn/bvTLsP/btqijlfLNOL/PZ5BZZX43jOYA8N2fyWRVk/profile_640x640s.jpg', 'https://k.kakaocdn.net/dn/bvTLsP/btqijlfLNOL/PZ5BZZX43jOYA8N2fyWRVk/profile_110x110c.jpg', '2017-11-09 12:22:02', '220.149.255.19'),
(13, '9rlaalswns5@naver.com', NULL, '김민준', 0, 'kakao', '558889181', 'https://k.kakaocdn.net/dn/CDTne/btqiihLLKtR/P5sFOrmMZMTfkPeKHcaeyk/profile_640x640s.jpg', 'https://k.kakaocdn.net/dn/CDTne/btqiihLLKtR/P5sFOrmMZMTfkPeKHcaeyk/profile_110x110c.jpg', '2017-11-09 13:25:26', '220.149.255.19');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
