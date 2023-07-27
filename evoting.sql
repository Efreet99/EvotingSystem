-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 08, 2021 at 01:28 PM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `evoting`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `aid` int(11) NOT NULL,
  `adminname` varchar(100) NOT NULL,
  `adminemail` varchar(150) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `date_of_birth` date NOT NULL,
  `gender` char(1) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `admin_status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`aid`, `adminname`, `adminemail`, `password_hash`, `date_of_birth`, `gender`, `created_at`, `admin_status`) VALUES
(1, 'superadmin', 'admin@hotmail.com', '$2y$10$ISf5mO1Goq6sZF.CcqRTee32pZPKUOpucx8gFpHZuJ/Ur7VlPaPZm', '2000-01-01', 'M', '2021-01-03 21:16:50', 'active'),
(4, 'chong', 'yapyapyap01@hotmail.com', '$2y$10$tnHaD1YNEBjSM.nOjSVaO.vKADS2f253fPhVUuOOU0DWzFCsKyV7C', '2000-04-20', 'M', '2021-03-07 17:02:54', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE `comment` (
  `comid` int(11) NOT NULL,
  `comment_content` text NOT NULL,
  `pid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `comment_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`comid`, `comment_content`, `pid`, `uid`, `comment_at`) VALUES
(10, 'Wei Kang Handsome!', 19, 13, '2021-03-08 12:00:59'),
(11, 'Sheng Qin Handsome too!!', 19, 15, '2021-03-08 12:13:15'),
(12, 'I like Korea girl!!', 15, 15, '2021-03-08 12:14:15'),
(13, 'Nice!', 9, 15, '2021-03-08 12:14:35'),
(14, 'So amazing!!', 1, 15, '2021-03-08 12:14:54');

-- --------------------------------------------------------

--
-- Table structure for table `countoption`
--

CREATE TABLE `countoption` (
  `cid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `oid` int(11) NOT NULL,
  `count` int(11) NOT NULL,
  `count_created_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `countoption`
--

INSERT INTO `countoption` (`cid`, `uid`, `pid`, `oid`, `count`, `count_created_date`) VALUES
(1, 1, 8, 11, 3, '2021-02-16 02:13:44'),
(2, 1, 8, 12, 2, '2021-02-16 02:13:52'),
(3, 1, 9, 17, 2, '2021-02-16 02:29:39'),
(4, 1, 9, 18, 2, '2021-02-16 02:29:47'),
(5, 1, 9, 19, 1, '2021-02-16 02:29:59'),
(6, 2, 1, 7, 1, '2021-02-16 02:31:18'),
(7, 2, 1, 9, 1, '2021-02-16 02:32:09'),
(8, 2, 1, 1, 1, '2021-02-16 02:32:22'),
(12, 11, 15, 32, 1, '2021-02-28 18:54:37'),
(13, 13, 19, 50, 1, '2021-03-08 12:00:15'),
(14, 13, 15, 34, 1, '2021-03-08 12:04:23'),
(15, 14, 19, 49, 1, '2021-03-08 12:04:26'),
(16, 13, 15, 35, 1, '2021-03-08 12:04:28'),
(17, 13, 15, 32, 1, '2021-03-08 12:04:32'),
(18, 14, 15, 33, 1, '2021-03-08 12:04:47'),
(19, 15, 19, 50, 1, '2021-03-08 12:12:45'),
(20, 15, 15, 31, 1, '2021-03-08 12:13:37'),
(21, 15, 15, 32, 1, '2021-03-08 12:13:45'),
(22, 15, 15, 34, 1, '2021-03-08 12:13:50'),
(23, 11, 15, 36, 1, '2021-03-08 13:16:16'),
(24, 11, 15, 31, 1, '2021-03-08 13:16:16'),
(25, 11, 19, 50, 1, '2021-03-08 13:16:41');

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `mid` int(11) NOT NULL,
  `message_email` varchar(150) NOT NULL,
  `message_content` varchar(500) NOT NULL,
  `message_send_at` datetime DEFAULT current_timestamp(),
  `message_status` varchar(50) NOT NULL,
  `replied_message` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`mid`, `message_email`, `message_content`, `message_send_at`, `message_status`, `replied_message`) VALUES
(1, 'yapch987@gmail.com', 'First Try Contact us.', '2021-02-18 00:47:51', 'pending', '-'),
(2, 'jackey991206@gmail.com', 'Hi there.', '2021-02-18 23:25:00', 'replied', 'Hi there'),
(3, 'steven20010617@gmail.com', 'hi', '2021-03-07 17:17:37', 'pending', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `optiontable`
--

CREATE TABLE `optiontable` (
  `oid` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `option_name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `option_desc` varchar(600) COLLATE utf8_unicode_ci DEFAULT NULL,
  `option_image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `optiontable`
--

INSERT INTO `optiontable` (`oid`, `pid`, `option_name`, `option_desc`, `option_image`) VALUES
(1, 1, 'Hainan Chicken Rice', '', '1613290479_6028dbef913c8.jpg'),
(2, 1, 'Nasi Kerabu', '', '1613290479_6028dbef96966.jpg'),
(3, 1, 'Nasi Lemak', '', '1613290479_6028dbef973e1.jpg'),
(4, 1, 'Nasi Kandar', '', '1613290479_6028dbef97ce4.jpg'),
(5, 1, 'Nasi Ambang', '', '1613290479_6028dbef9859f.jpg'),
(6, 1, 'Penang Asam Laksa', '', '1613290479_6028dbef98ba2.jpg'),
(7, 1, 'Penang Hokkian Prawn Noodles', '', '1613290479_6028dbef990d6.jpg'),
(8, 1, 'Penang Fried Hokkian Noodles', '', '1613290479_6028dbef99680.jpg'),
(9, 1, 'Curry Chicken Rice', '', '1613290479_6028dbef99df8.jpg'),
(10, 1, 'Mi Goreng Mamak', '', '1613290479_6028dbef9a284.jpg'),
(11, 8, 'Usada Pekora', '', ''),
(12, 8, 'Uruha Rushia', '', ''),
(13, 8, 'Shiranui Flare', '', ''),
(14, 8, 'Shirogane Noel', '', ''),
(15, 8, 'Houshou Marine', '', ''),
(16, 9, 'Shiranui Flare', '', ''),
(17, 9, 'Usada Pekora', '', ''),
(18, 9, 'Uruha Rushia', '', ''),
(19, 9, 'Houshou Marine', '', ''),
(20, 9, 'Shirogane Noel', '', ''),
(31, 15, 'Singapore', 'officially the Republic of Singapore, is a sovereign island city-state in maritime Southeast Asia.', '1614061794_6034a0e240c76.jpg'),
(32, 15, 'Japan', 'is an island country in East Asia, located in the northwest Pacific Ocean.', '1614061794_6034a0e250fbf.jpg'),
(33, 15, 'Dubai', 'is the most populous city in the United Arab Emirates (UAE) and the capital of the Emirate of Dubai.', '1614061794_6034a0e251250.jpg'),
(34, 15, 'Korea', 'is a region in East Asia.', '1614061794_6034a0e255977.jpg'),
(35, 15, 'U.S.A', 'it consists of 50 states, a federal district, five major self-governing territories, 326 Indian reservations, and some minor possessions.', '1614061794_6034a0e255bf0.jpg'),
(36, 15, 'U.K', 'is a sovereign country in north-western Europe, off the north-­western coast of the European mainland.', '1614061794_6034a0e255dff.jpeg'),
(37, 15, 'France', 'is a country primarily located in Western Europe, consisting of metropolitan France and several overseas regions and territories.', '1614061794_6034a0e258747.jpg'),
(48, 19, 'Wong Siong Yen', '', ''),
(49, 19, 'Chan Yi Fan', '', ''),
(50, 19, 'Too Wei Kang', '', ''),
(51, 19, 'Lim Sheng Qin', '', ''),
(52, 19, 'Wee Pei Xiang', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `pid` int(11) NOT NULL,
  `post_title` varchar(100) NOT NULL,
  `post_desc` varchar(300) NOT NULL,
  `post_mode` varchar(10) NOT NULL,
  `post_password` varchar(255) NOT NULL,
  `post_created_date` datetime DEFAULT current_timestamp(),
  `post_expired_date` varchar(20) NOT NULL,
  `vote_method` varchar(25) NOT NULL,
  `number_votes` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `post_status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`pid`, `post_title`, `post_desc`, `post_mode`, `post_password`, `post_created_date`, `post_expired_date`, `vote_method`, `number_votes`, `uid`, `post_status`) VALUES
(1, 'Favourite food', 'Malaysia is a multiracial country, this make Malaysian have many choice when we are taking breakfast, lunch and dinner. This voting is want to know which food that many people likes.', 'public', '', '2021-02-14 16:14:39', '2021-02-28T03:40:34', 'multiple', 3, 1, 'approve'),
(8, 'Favourite Hololive Fantasy Vtuber', 'Like the title.', 'public', '', '2021-02-15 21:26:04', '2021-02-16T09:24:37', 'multi-point', 5, 2, 'blocked'),
(9, 'Favourite Hololive Fantasy Vtuber', 'Like the title.', 'public', '', '2021-02-15 21:29:35', '2021-02-16T09:28:06', 'multi-point', 5, 2, 'approve'),
(15, 'Which country do you wish to traveling', 'If you have ability and chance, which country you wish to traveling.', 'public', '', '2021-02-23 14:29:54', '2021-03-09T14:59:37', 'multiple', 3, 2, 'approve'),
(19, 'E-Sport Club President Election, 2021', 'The winner of the election will become the president of the E-Sport club in year 2021.', 'private', 'bqrrRQ', '2021-02-23 15:02:41', '2021-03-09T12:01:32', 'single', 1, 2, 'approve');

-- --------------------------------------------------------

--
-- Table structure for table `report`
--

CREATE TABLE `report` (
  `rid` int(11) NOT NULL,
  `report_reason` varchar(500) NOT NULL,
  `report_desc` text DEFAULT NULL,
  `reporter_uid` int(11) NOT NULL,
  `reported_uid` int(11) NOT NULL,
  `reported_pid` int(11) NOT NULL,
  `action` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `report`
--

INSERT INTO `report` (`rid`, `report_reason`, `report_desc`, `reporter_uid`, `reported_uid`, `reported_pid`, `action`) VALUES
(9, 'false news or spam', '', 2, 1, 1, 'pending'),
(11, 'nudity', '', 1, 2, 8, 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `uid` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `date_of_birth` date NOT NULL,
  `gender` char(1) NOT NULL,
  `user_status` varchar(50) NOT NULL,
  `user_created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`uid`, `username`, `email`, `password_hash`, `date_of_birth`, `gender`, `user_status`, `user_created_at`) VALUES
(1, 'efreet', 'jackey991206@gmail.com', '$2y$10$OdQzjJmdl.rCVL6jSaB5mOOuZlcckH4rjy3KTE3.p8uK4dnmIHvD6', '1998-11-05', 'M', 'active', '2021-02-14 14:36:34'),
(2, 'elf', 'ifleet990803@gmail.com', '$2y$10$bNemWHCdTe5iwUZoKo757OfrfFfjEbL6s/w1J6m7wcyFJHz1l350.', '2000-01-07', 'M', 'active', '2021-02-15 17:46:08'),
(4, 'ahxiang', '1181202928@student.mmu.edu.my', '$2y$10$8cr1HUtXI9IzPHbNQzGTFeg63/mlGb8Ze/jjiUW.kAgZJbvk95S1i', '1969-04-16', 'M', 'active', '2021-02-23 13:48:30'),
(8, 'ahchia', '1181203548@student.mmu.edu.my', '$2y$10$TjII.9IsYO.bFpELq5L/o.riNLOyWpdyhPlyJphpe3EBAnpl5Y8IC', '1971-08-19', 'M', 'active', '2021-02-23 13:52:02'),
(10, 'Hong', 'lauchunhong0921@gmail.com', '$2y$10$Yf7gSM8Wl8IX1BzIdwAQL.abL09n1Zyc7J5ZFhF44Qiq.5WrWjWi.', '2000-07-23', 'M', 'active', '2021-02-28 00:35:37'),
(11, 'Custard', '1191100793@student.mmu.edu.my', '$2y$10$P/aGzpIUNSem.2r6hkT1eO/QW1yEdS5Z9vQqWr.FKGThiaprA4Dbi', '2001-11-28', 'F', 'active', '2021-02-28 18:53:58'),
(13, 'Lim Sheng Qin', 'limshengqin@gmail.com', '$2y$10$Kl.XmZq3SSKbgP8Se0rCee77.VZXwZo0xS4x9Lt4p8XL7ouzZYqIu', '2001-01-11', 'M', 'active', '2021-03-08 11:59:05'),
(14, 'chan', 'chanyifan25@gmail.com', '$2y$10$wcW2sRxVDLK3eT73Emqecust89sDFxupu5xduPAhTF8ARpWVZhx12', '2001-01-25', 'M', 'active', '2021-03-08 12:01:22'),
(15, 'louistoo0818', 'louistoo0818@gmail.com', '$2y$10$a5tOIVtuZZV9fYBprCWbFe/E8h5giwFTAoIaeaetzt00Mcfx8o432', '2001-08-18', 'M', 'active', '2021-03-08 12:12:05');

-- --------------------------------------------------------

--
-- Table structure for table `verify`
--

CREATE TABLE `verify` (
  `email` varchar(150) NOT NULL,
  `hash` varchar(50) NOT NULL,
  `expired_date` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `verify`
--

INSERT INTO `verify` (`email`, `hash`, `expired_date`) VALUES
('steven20010617@gmial.com', 'c22abfa379f38b5b0411bc11fa9bf92f', '2021-03-10T17:19:35'),
('theeky-jm20@student.tarc.edu.my', '05049e90fa4f5039a8cadc6acbb4b2cc', '2021-03-06T21:52:31');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`aid`),
  ADD UNIQUE KEY `adminname` (`adminname`),
  ADD UNIQUE KEY `adminemail` (`adminemail`);

--
-- Indexes for table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`comid`),
  ADD KEY `uid` (`uid`),
  ADD KEY `pid` (`pid`);

--
-- Indexes for table `countoption`
--
ALTER TABLE `countoption`
  ADD PRIMARY KEY (`cid`),
  ADD KEY `uid` (`uid`),
  ADD KEY `pid` (`pid`),
  ADD KEY `oid` (`oid`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`mid`);

--
-- Indexes for table `optiontable`
--
ALTER TABLE `optiontable`
  ADD PRIMARY KEY (`oid`),
  ADD KEY `pid` (`pid`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`pid`),
  ADD KEY `uid` (`uid`);

--
-- Indexes for table `report`
--
ALTER TABLE `report`
  ADD PRIMARY KEY (`rid`),
  ADD KEY `reporter_uid` (`reporter_uid`),
  ADD KEY `reported_pid` (`reported_pid`),
  ADD KEY `reported_uid` (`reported_uid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`uid`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `verify`
--
ALTER TABLE `verify`
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `aid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `comment`
--
ALTER TABLE `comment`
  MODIFY `comid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `countoption`
--
ALTER TABLE `countoption`
  MODIFY `cid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `mid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `optiontable`
--
ALTER TABLE `optiontable`
  MODIFY `oid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `pid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `report`
--
ALTER TABLE `report`
  MODIFY `rid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`),
  ADD CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`pid`) REFERENCES `posts` (`pid`);

--
-- Constraints for table `countoption`
--
ALTER TABLE `countoption`
  ADD CONSTRAINT `countoption_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`),
  ADD CONSTRAINT `countoption_ibfk_2` FOREIGN KEY (`pid`) REFERENCES `posts` (`pid`),
  ADD CONSTRAINT `countoption_ibfk_3` FOREIGN KEY (`oid`) REFERENCES `optiontable` (`oid`);

--
-- Constraints for table `optiontable`
--
ALTER TABLE `optiontable`
  ADD CONSTRAINT `optiontable_ibfk_1` FOREIGN KEY (`pid`) REFERENCES `posts` (`pid`);

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`);

--
-- Constraints for table `report`
--
ALTER TABLE `report`
  ADD CONSTRAINT `report_ibfk_1` FOREIGN KEY (`reporter_uid`) REFERENCES `users` (`uid`),
  ADD CONSTRAINT `report_ibfk_2` FOREIGN KEY (`reported_pid`) REFERENCES `posts` (`pid`),
  ADD CONSTRAINT `report_ibfk_3` FOREIGN KEY (`reported_uid`) REFERENCES `users` (`uid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
