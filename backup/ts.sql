-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Host: 72.55.188.165
-- Generation Time: Nov 09, 2011 at 06:28 AM
-- Server version: 5.5.16
-- PHP Version: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ts`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `com_id` int(11) NOT NULL AUTO_INCREMENT,
  `com_user` varchar(30) DEFAULT NULL,
  `com_dis` text,
  `youtubecode` varchar(25) DEFAULT NULL,
  `upload_date` datetime NOT NULL,
  PRIMARY KEY (`com_id`),
  KEY `youtubecode` (`youtubecode`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=121 ;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`com_id`, `com_user`, `com_dis`, `youtubecode`, `upload_date`) VALUES
(1, 'redeye', 'bitttch', 'ABqh9N-Mw5E', '2011-11-06 20:41:55'),
(2, 'test', 'test', 'esALI7kQiSQ', '2011-11-06 20:41:55'),
(3, 'redeye', 'new test', 'esALI7kQiSQ', '2011-11-06 20:41:55'),
(4, 'User Name', 'tes', 'esALI7kQiSQ', '2011-11-06 20:41:55'),
(5, 'User Name', 'test', 'esALI7kQiSQ', '2011-11-06 20:41:55'),
(6, 'User Name', 'tes', 'esALI7kQiSQ', '2011-11-06 20:41:55'),
(7, 'User Name', 'hj', 'esALI7kQiSQ', '2011-11-06 20:41:55'),
(8, 'User Name', 'adf', 'esALI7kQiSQ', '2011-11-06 20:41:55'),
(9, 'User Name', 'adf', 'esALI7kQiSQ', '2011-11-06 20:41:55'),
(10, 'User Name', 'adf', 'esALI7kQiSQ', '2011-11-06 20:41:55'),
(11, 'Anonymous', 'd', 'esALI7kQiSQ', '2011-11-06 20:41:55'),
(12, 'Anonymous', 'dfd', 'esALI7kQiSQ', '2011-11-06 20:41:55'),
(13, 'Anonymous', 'asdf', 'esALI7kQiSQ', '2011-11-06 20:41:55'),
(14, 'redeye', 'test\r\nxh4Rk-RDX5A', 'xh4Rk-RDX5A', '2011-11-06 20:41:55'),
(17, 'Anonymous', 'test', 'xh4Rk-RDX5A', '2011-11-06 20:41:55'),
(18, 'Anonymous', 'test', 'xh4Rk-RDX5A', '2011-11-06 20:41:55'),
(19, 'Anonymous', 'test', 'xh4Rk-RDX5A', '2011-11-06 20:41:55'),
(20, 'Anonymous', 'adsf', 'xh4Rk-RDX5A', '2011-11-06 20:41:55'),
(21, 'Anonymous', 'adsfasdf', 'xh4Rk-RDX5A', '2011-11-06 20:41:55'),
(22, 'Anonymous', 'test', 'xh4Rk-RDX5A', '2011-11-06 20:41:55'),
(23, 'Anonymous', 'test', 'xh4Rk-RDX5A', '2011-11-06 20:41:55'),
(24, 'Anonymous', 'test', 'xh4Rk-RDX5A', '2011-11-06 20:41:55'),
(25, 'Anonymous', 'test', 'xh4Rk-RDX5A', '2011-11-06 20:41:55'),
(26, 'Anonymous', 'test', 'xh4Rk-RDX5A', '2011-11-06 20:41:55'),
(27, 'Anonymous', 'fart', 'xh4Rk-RDX5A', '2011-11-06 20:41:55'),
(28, 'Anonymous', 'test', 'BWvmlqHsZ1Y', '0000-00-00 00:00:00'),
(29, 'Anonymous', 'test', 'BWvmlqHsZ1Y', '2011-11-06 20:44:23'),
(30, 'Anonymous', 'test', 'BWvmlqHsZ1Y', '2011-11-06 20:50:08'),
(31, 'Anonymous', 'test', 'BWvmlqHsZ1Y', '2011-11-06 20:50:28'),
(32, 'Anonymous', 'est', 'xh4Rk-RDX5A', '2011-11-06 20:51:41'),
(33, 'Anonymous', 'adf', 'xh4Rk-RDX5A', '2011-11-06 20:53:03'),
(34, 'Anonymous', 'dasf', 'xh4Rk-RDX5A', '2011-11-06 20:53:22'),
(35, 'Anonymous', 'asdf', 'xh4Rk-RDX5A', '2011-11-06 20:55:05'),
(36, 'Anonymous', 'test', 'NI2b7qXUlnE', '2011-11-06 21:00:58'),
(37, 'Anonymous', 'test', 'NI2b7qXUlnE', '2011-11-06 21:02:42'),
(38, 'Anonymous', 'test', 'NI2b7qXUlnE', '2011-11-06 21:03:12'),
(39, 'Anonymous', 'test', 'xh4Rk-RDX5A', '2011-11-06 21:04:14'),
(40, 'Anonymous', 'tes', 'xh4Rk-RDX5A', '2011-11-06 21:17:08'),
(41, 'Anonymous', 'adfadsfadsfadsfadsfasdf', 'xh4Rk-RDX5A', '2011-11-06 21:17:25'),
(42, 'Anonymous', 'weee', 'NI2b7qXUlnE', '2011-11-06 21:19:33'),
(43, 'Anonymous', 'kljlkj', 'xh4Rk-RDX5A', '2011-11-06 21:20:42'),
(44, 'Anonymous', 'kj\nkj', 'xh4Rk-RDX5A', '2011-11-06 21:20:48'),
(45, 'Anonymous', 'asdf', 'xh4Rk-RDX5A', '2011-11-06 21:22:59'),
(46, 'Anonymous', 'asdf', 'xh4Rk-RDX5A', '2011-11-06 21:29:05'),
(47, 'Anonymous', 'asdf', 'xh4Rk-RDX5A', '2011-11-06 21:29:06'),
(48, 'Anonymous', 'asdf', 'xh4Rk-RDX5A', '2011-11-06 21:33:59'),
(49, 'Anonymous', 'adf', 'xh4Rk-RDX5A', '2011-11-06 21:50:13'),
(50, 'Anonymous', 'adfdsf', 'xh4Rk-RDX5A', '2011-11-06 21:50:15'),
(51, 'Anonymous', 'adsf', 'xh4Rk-RDX5A', '2011-11-06 21:50:54'),
(52, 'Anonymous', 'adsfasdf', 'xh4Rk-RDX5A', '2011-11-06 21:50:55'),
(53, 'Anonymous', 'ert', 'xh4Rk-RDX5A', '2011-11-06 21:51:58'),
(54, 'Anonymous', 'asdf', 'xh4Rk-RDX5A', '2011-11-06 21:52:01'),
(55, 'Diana', 'Calvin is soooo hot', 'xh4Rk-RDX5A', '2011-11-06 21:52:15'),
(56, 'Anonymous', 'asdf', 'xh4Rk-RDX5A', '2011-11-07 00:49:45'),
(57, 'Anonymous', 'asdf', 'xh4Rk-RDX5A', '2011-11-07 08:48:35'),
(58, 'Anonymous', 'adf', 'xh4Rk-RDX5A', '2011-11-07 09:05:47'),
(59, 'Anonymous', 'aadfd', 'xh4Rk-RDX5A', '2011-11-07 09:08:08'),
(60, 'Anonymous', 'dfad', 'xh4Rk-RDX5A', '2011-11-07 09:08:30'),
(61, 'Anonymous', 'adf', 'xh4Rk-RDX5A', '2011-11-07 09:23:57'),
(62, 'Redeye', 'Shit is working?!', 'xh4Rk-RDX5A', '2011-11-07 09:25:10'),
(63, 'Anonymous', 'adsf', 'xh4Rk-RDX5A', '2011-11-07 09:26:05'),
(64, 'Anonymous', 'cant believe this works', 'xh4Rk-RDX5A', '2011-11-07 09:29:58'),
(65, 'Anonymous', 'asdf', 'xh4Rk-RDX5A', '2011-11-07 09:30:46'),
(66, 'Anonymous', 'adf', 'xh4Rk-RDX5A', '2011-11-07 09:32:17'),
(67, 'Anonymous', 'df', 'xh4Rk-RDX5A', '2011-11-07 09:32:28'),
(68, 'Anonymous', 'test', 'xh4Rk-RDX5A', '2011-11-07 09:39:59'),
(69, 'Anonymous', 'test', 'NI2b7qXUlnE', '2011-11-07 09:40:05'),
(70, 'Anonymous', 'test', 'NI2b7qXUlnE', '2011-11-07 09:40:44'),
(71, 'Anonymous', 'shit working?/ No way', 'xh4Rk-RDX5A', '2011-11-07 09:53:07'),
(72, 'Anonymous', 'asdfa', 'xh4Rk-RDX5A', '2011-11-07 10:59:40'),
(73, 'Anonymous', 'asdf', 'xh4Rk-RDX5A', '2011-11-07 13:28:23'),
(74, 'Anonymous', 'df', 'xh4Rk-RDX5A', '2011-11-07 14:56:37'),
(75, 'Anonymous', 'asdf', 'xh4Rk-RDX5A', '2011-11-07 16:07:42'),
(76, 'Anonymous', 'tes', 'xh4Rk-RDX5A', '2011-11-07 16:45:40'),
(77, 'Anonymous', 'adf', 'xh4Rk-RDX5A', '2011-11-07 17:03:09'),
(78, 'Anonymous', 'test', 'xh4Rk-RDX5A', '2011-11-07 19:29:36'),
(79, 'Anonymous', 'adf', 'NI2b7qXUlnE', '2011-11-07 23:42:29'),
(80, 'Anonymous', 'test', 'NI2b7qXUlnE', '2011-11-07 23:44:41'),
(81, 'Anonymous', 'asfd', 'NI2b7qXUlnE', '2011-11-08 00:14:11'),
(84, 'redeye', 'dfd', 'NI2b7qXUlnE', '2011-11-08 00:20:09'),
(85, 'redeye', 'df', 'n01UOw5uEhw', '2011-11-08 00:20:14'),
(86, 'redeye', 'fd', 'NI2b7qXUlnE', '2011-11-08 00:23:30'),
(87, 'redeye', 'dfd', 'NI2b7qXUlnE', '2011-11-08 00:24:55'),
(88, 'redeye', 'aa', 'n01UOw5uEhw', '2011-11-08 00:25:01'),
(89, 'redeye', 'aaaaa', 'NI2b7qXUlnE', '2011-11-08 00:27:17'),
(90, 'redeye', 'zas', 'NI2b7qXUlnE', '2011-11-08 00:28:37'),
(91, 'Anonymous', 'asdf', 'NI2b7qXUlnE', '2011-11-08 00:31:09'),
(92, 'Anonymous', 'test', 'NI2b7qXUlnE', '2011-11-08 00:32:11'),
(93, 'Anonymous', 'adsf', 'n01UOw5uEhw', '2011-11-08 00:32:17'),
(94, 'Anonymous', 'fd', 'NI2b7qXUlnE', '2011-11-08 00:32:47'),
(95, 'redeye', 'SERIOUSLY!?', 'n01UOw5uEhw', '2011-11-08 00:33:01'),
(96, 'Anonymous', 'fuck yea', 'jsGGHeq1W2M', '2011-11-08 00:33:29'),
(97, 'Anonymous', 'easy', 'NI2b7qXUlnE', '2011-11-08 00:33:52'),
(98, 'Anonymous', 'asdf', 'lFg299gDDSw', '2011-11-08 00:33:59'),
(99, 'Anonymous', 'adf', 'NI2b7qXUlnE', '2011-11-08 00:34:10'),
(100, 'Anonymous', 'aaa', 'xh4Rk-RDX5A', '2011-11-08 00:34:14'),
(101, 'redeye', 'AWESOME NEW MAU5!!!', 'b7XF3kry_Ck', '2011-11-08 00:38:52'),
(102, 'Anonymous', 'can''t', 'NI2b7qXUlnE', '2011-11-08 00:47:22'),
(103, 'Anonymous', 'asdfsdf', 'NI2b7qXUlnE', '2011-11-08 00:57:22'),
(104, 'agd''f', 'adf', 'NI2b7qXUlnE', '2011-11-08 00:57:55'),
(105, 'Anonymous', 'adf', 'n01UOw5uEhw', '2011-11-08 01:00:25'),
(106, 'Anonymous', 'asdf', 'xh4Rk-RDX5A', '2011-11-08 01:00:26'),
(107, 'daf', 'dfa', 'lFg299gDDSw', '2011-11-08 01:00:37'),
(108, 'Anonymous', 'tse', 'xh4Rk-RDX5A', '2011-11-08 01:22:45'),
(109, 'Anonymous', 'adf', 'xh4Rk-RDX5A', '2011-11-08 01:25:22'),
(110, 'Anonymous', 'adfd', 'esALI7kQiSQ', '2011-11-08 01:25:33'),
(111, 'Anonymous', 'Goood', '6xeg95XvynM', '2011-11-08 01:51:58'),
(112, 'redeye', 'Awesome. ', 'QJMarLOxzNk', '2011-11-08 09:58:06'),
(113, 'Anonymous', 'dsfa', 'QJMarLOxzNk', '2011-11-08 10:03:12'),
(114, 'Anonymous', 'asdfasdf', 'zcFMkSt9jMk', '2011-11-08 13:12:53'),
(115, 'Anonymous', 'Rad', 'NI2b7qXUlnE', '2011-11-08 14:44:25'),
(116, 'redeye', 'One of my favorite of netsky''s', 'cG7cRDcPY3k', '2011-11-08 15:55:22'),
(117, 'Anonymous', 'netsky''s', 'cG7cRDcPY3k', '2011-11-08 16:05:09'),
(118, 'redeye', 'still amazed the comments work', 'Eed_x1LeUu8', '2011-11-08 16:28:33'),
(119, 'Reed', 'jizz jizz jizz jizz jizz', 'TAx0eLxsYr0', '2011-11-08 16:34:49'),
(120, 'Randy', 'He''s amazing', 'xdaGfBjNB-Q', '2011-11-08 17:09:54');

-- --------------------------------------------------------

--
-- Table structure for table `songs`
--

CREATE TABLE IF NOT EXISTS `songs` (
  `title` varchar(255) NOT NULL COMMENT 'song title',
  `artist` varchar(255) NOT NULL,
  `genre` varchar(75) NOT NULL,
  `youtubecode` varchar(25) NOT NULL,
  `uploaded_on` datetime NOT NULL,
  `user` varchar(30) NOT NULL COMMENT 'username of submitting user',
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ups` int(11) NOT NULL,
  `downs` int(11) NOT NULL,
  `score` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `youtubecode` (`youtubecode`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=31 ;

--
-- Dumping data for table `songs`
--

INSERT INTO `songs` (`title`, `artist`, `genre`, `youtubecode`, `uploaded_on`, `user`, `id`, `ups`, `downs`, `score`) VALUES
('Feel So Close (Special Features Remix)', 'Calvin Harris', 'house', 'esALI7kQiSQ', '2011-11-02 12:35:56', 'redeye', 1, 1, 1, 0),
('The Longest Road (Deadmau5 Remix)', 'Deadmau5', 'house', 'xh4Rk-RDX5A', '2011-11-04 12:52:25', 'redeye', 3, 4, 2, 2),
('Hold On (Sub Focus Remix)', 'Rusko', 'dubstep', 'NI2b7qXUlnE', '2011-11-04 13:01:43', 'redeye', 4, 3, 0, 3),
('Coming Home (Dirty South Remix)', 'Dirty Money ft. Skyler Grey', 'house', '6xeg95XvynM', '2011-11-05 14:47:33', 'redeye', 5, 0, 0, 0),
('Rotunda (Original Mix)', 'Markus Schulz & Jochen Miller', 'house', 'U-9w4BkLA4U', '2011-11-05 15:11:39', 'redeye', 6, 0, 0, 0),
('Levels', 'Avicii', 'House', 'kVk1HOlkq_o', '2011-11-05 16:27:32', 'redeye', 7, 0, 0, 0),
('Slam', 'Pendulum', 'DnB', 'ABqh9N-Mw5E', '2011-11-05 16:28:39', 'redeye', 8, 0, 0, 0),
('Could You Believe', 'ATB', 'Trance', '9rH1kT8EIOU', '2011-11-05 22:35:06', 'redeye', 9, 0, 0, 0),
('The Electric Dream', 'Mord Fustang', 'Electro', 'fGksXB6a6s0', '2011-11-05 22:36:18', 'redeye', 10, 0, 0, 0),
('Grand Theft Ecstasy', 'Feed Me', 'Electro', 'SvRt-NEuV28', '2011-11-05 23:39:40', 'redeye', 11, 0, 0, 0),
('Pink Lady', 'Feed Me', 'Electro', 'n01UOw5uEhw', '2011-11-05 23:56:32', 'redeye', 12, 3, 1, 2),
('To The Stars', 'Feed Me', 'Electro', 'lFg299gDDSw', '2011-11-05 23:57:46', 'redeye', 13, 1, 0, 1),
('Fake Tan', 'Louis La Roche', 'House', 'BWvmlqHsZ1Y', '2011-11-06 00:33:54', 'redeye', 14, 0, 0, 0),
('Why Do I Care?', 'tyDi ft. Tania Zygar', 'Trance', 'Eed_x1LeUu8', '2011-11-06 00:38:23', 'redeye', 15, 0, 0, 0),
('Aural Psynapse (2nd Edit)', 'Deadmau5', 'House', 'b7XF3kry_Ck', '2011-11-06 09:55:55', 'redeye', 16, 0, 0, 0),
('Simple Things', 'Foyle & Zo', 'Trance', 'DT5sF1kBDGE', '2011-11-06 09:58:00', 'redeye', 17, 0, 0, 0),
('Strobe (Special Features Remix)', 'Deadmau5', 'House', 'QJMarLOxzNk', '2011-11-06 10:00:19', 'redeye', 18, 1, 0, 1),
('Breakn a Sweat', 'Skrillex ft. The Doors', 'Dubstep', 'F1K-BFbCTU8', '2011-11-06 10:05:10', 'redeye', 19, 0, 0, 0),
('Everyday (Netsky Remix)', 'Rusko', 'DnB', 'jsGGHeq1W2M', '2011-11-07 10:42:29', 'redeye', 20, 0, 0, 0),
('Memory Lane', 'Netsky', 'DnB', 'cG7cRDcPY3k', '2011-11-07 10:44:34', 'redeye', 21, 0, 0, 0),
('Iron Heart', 'Netsky', 'DnB', 'FPoQsOBzlgQ', '2011-11-07 10:50:32', 'redeye', 22, 0, 0, 0),
('Promises (Skrillex Remix)', 'Nero', 'Dubstep', 'zcFMkSt9jMk', '2011-11-08 09:44:04', 'redeye', 23, 0, 0, 0),
('Sick Bubblegum (Skrillex)', 'Rob Zombie', 'Electro', 'PekC-hMgabw', '2011-11-08 09:46:00', 'redeye', 24, 0, 0, 0),
('Reptile Theme', 'Skrillex', 'Electro', 'yZA91uQO9VU', '2011-11-08 09:46:41', 'redeye', 25, 0, 0, 0),
('Ice (Original Mix)', 'Kaskade & Dada Life', 'Electro', 'qbgza5zSNRU', '2011-11-08 16:30:28', 'redeye', 26, 1, 0, 1),
('Sunday Girl (Them Jeans Remix)', 'Love U More', 'House', 'cR-r63teTHA', '2011-11-08 16:32:09', 'Grant K', 27, 0, 0, 0),
('It''s Too Late (First State Remix)', 'Jes', 'Trance', 'TAx0eLxsYr0', '2011-11-08 16:33:52', 'Duncan', 28, 1, 0, 1),
('Cobra (Original Mix)', 'Hardwell', 'House', 'xdaGfBjNB-Q', '2011-11-08 17:09:06', 'Duncan', 29, 1, 0, 1),
('Bipolar', 'Mat Zo', 'Trance', 'YFRxGc8vlaU', '2011-11-08 17:16:33', 'Michael', 30, 3, 0, 3);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`youtubecode`) REFERENCES `songs` (`youtubecode`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
