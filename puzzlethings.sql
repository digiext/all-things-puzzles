-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: mariadb
-- Generation Time: Jul 14, 2025 at 01:55 PM
-- Server version: 10.9.8-MariaDB-1:10.9.8+maria~ubu2204
-- PHP Version: 8.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `puzzlethingstemp`
--

-- --------------------------------------------------------

--
-- Table structure for table `auth`
--

CREATE TABLE `auth` (
  `authid` bigint(20) UNSIGNED NOT NULL,
  `selector` varchar(255) NOT NULL,
  `hashed_validator` varchar(255) NOT NULL,
  `userid` bigint(20) UNSIGNED NOT NULL,
  `expiry` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `brand`
--

CREATE TABLE `brand` (
  `brandid` bigint(20) UNSIGNED NOT NULL,
  `brandname` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `brand`
--

INSERT INTO `brand` (`brandid`, `brandname`) VALUES
(1, 'American Flat'),
(2, 'Anthology Puzzles'),
(3, 'Areaware'),
(4, 'Art and Fable'),
(5, 'Artifact'),
(6, 'BetterCo'),
(7, 'Bits and Pieces'),
(8, 'Blue Kazoo'),
(9, 'Buffalo Games'),
(10, 'Ceaco'),
(11, 'Chronicle'),
(12, 'Cra-Z-Art / Kodak / Lafayette Puzzle Factory'),
(13, 'Crown Point Graphics'),
(14, 'Dope Pieces'),
(15, 'Dowdle'),
(16, 'eeBoo'),
(17, 'Elms Handcut Puzzles'),
(18, 'Galison'),
(19, 'Geotoys'),
(20, 'Golden Contempo'),
(21, 'Heritage'),
(22, 'Joyful Nook Gallery'),
(23, 'Lemonade Pursuits'),
(24, 'Liberty'),
(25, 'Lighthouse Puzzles'),
(26, 'Lucky Puzzles'),
(27, 'Madd Capp'),
(28, 'MasterPieces'),
(29, 'MicroPuzzles'),
(30, 'Mintyfizz Puzzles'),
(31, 'Modern World'),
(32, 'Nervous System'),
(33, 'New York Puzzle Co'),
(34, 'Paper House Productions'),
(35, 'Peter Pauper Press'),
(36, 'The Play Group'),
(37, 'Pomegranate'),
(38, 'Puzzle Sensei'),
(39, 'PuzzleTwist'),
(40, 'Puzzledly'),
(41, 'Ravensburger North America'),
(42, 'Re-marks'),
(43, 'Springbok'),
(44, 'Snappy Puzzles'),
(45, 'Sunsout'),
(46, 'The Puzzled Co'),
(47, 'True South Puzzle Company'),
(48, 'University Games'),
(49, 'Vermont Christmas Company'),
(50, 'Very Good Puzzle'),
(51, 'Wander Puzzle Co'),
(52, 'White Mountain'),
(53, 'Cobble Hill'),
(54, 'Eurographics'),
(55, 'Smyth Puzzles'),
(56, 'StandOut Puzzles'),
(57, 'StumpCraft'),
(58, 'Anatolian'),
(59, 'Bluebird'),
(60, 'Castorland'),
(61, 'Clementoni'),
(62, 'Cloudberries'),
(63, 'D-Toys'),
(64, 'Educa'),
(65, 'Falcon'),
(66, 'Gibsons'),
(67, 'Grafika'),
(68, 'Heye'),
(69, 'Jumbo'),
(70, 'King'),
(71, 'Piatnik'),
(72, 'Schmidt'),
(73, 'Trefl'),
(74, 'Wasgij'),
(75, 'Wentworth'),
(76, 'JaCaRou'),
(77, 'Ridley\'s'),
(78, 'All Jigsaw Puzzles'),
(79, 'Lazy One'),
(80, 'RMS International');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `categoryid` bigint(20) UNSIGNED NOT NULL,
  `categorydesc` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `disposition`
--

CREATE TABLE `disposition` (
  `dispositionid` bigint(20) UNSIGNED NOT NULL,
  `dispositiondesc` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `disposition`
--

INSERT INTO `disposition` (`dispositionid`, `dispositiondesc`) VALUES
(1, 'Keep'),
(2, 'Donate'),
(3, 'Sell'),
(4, 'Give Away');

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

CREATE TABLE `location` (
  `locationid` bigint(20) UNSIGNED NOT NULL,
  `locationdesc` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `location`
--

INSERT INTO `location` (`locationid`, `locationdesc`) VALUES
(1, 'Unknown');

-- --------------------------------------------------------

--
-- Table structure for table `ownership`
--

CREATE TABLE `ownership` (
  `ownershipid` bigint(20) UNSIGNED NOT NULL,
  `ownershipdesc` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ownership`
--

INSERT INTO `ownership` (`ownershipid`, `ownershipdesc`) VALUES
(1, 'Owned'),
(2, 'Donated'),
(3, 'Given Away'),
(4, 'Sold'),
(5, 'Traded'),
(6, 'Wanted'),
(7, 'Loaned Out'),
(8, 'Borrowed');

-- --------------------------------------------------------

--
-- Table structure for table `puzcat`
--

CREATE TABLE `puzcat` (
  `puzcatid` bigint(20) UNSIGNED NOT NULL,
  `puzzleid` bigint(20) UNSIGNED NOT NULL,
  `categoryid` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `puzzleinv`
--

CREATE TABLE `puzzleinv` (
  `puzzleid` bigint(20) UNSIGNED NOT NULL,
  `puzname` text NOT NULL,
  `pieces` smallint(6) UNSIGNED NOT NULL,
  `brandid` bigint(20) UNSIGNED NOT NULL,
  `cost` decimal(8,2) NOT NULL,
  `dateacquired` date NOT NULL,
  `sourceid` bigint(20) UNSIGNED NOT NULL,
  `locationid` bigint(20) UNSIGNED NOT NULL,
  `dispositionid` bigint(20) UNSIGNED NOT NULL,
  `pictureurl` text DEFAULT NULL,
  `upc` varchar(13) NOT NULL,
  `addeddate` datetime NOT NULL DEFAULT current_timestamp(),
  `moddate` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `puzzlewish`
--

CREATE TABLE `puzzlewish` (
  `wishid` bigint(20) UNSIGNED NOT NULL,
  `userid` bigint(20) UNSIGNED NOT NULL,
  `puzname` text NOT NULL,
  `pieces` smallint(6) UNSIGNED NOT NULL,
  `brandid` bigint(20) UNSIGNED NOT NULL,
  `upc` varchar(13) NOT NULL,
  `addeddate` datetime NOT NULL DEFAULT current_timestamp(),
  `moddate` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `setup`
--

CREATE TABLE `setup` (
  `installed` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `setup`
--

INSERT INTO `setup` (`installed`) VALUES
(0);

-- --------------------------------------------------------

--
-- Table structure for table `source`
--

CREATE TABLE `source` (
  `sourceid` bigint(20) UNSIGNED NOT NULL,
  `sourcedesc` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `source`
--

INSERT INTO `source` (`sourceid`, `sourcedesc`) VALUES
(1, 'New Purchase'),
(2, 'Thrifted'),
(3, 'Gift');

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE `status` (
  `statusid` bigint(20) UNSIGNED NOT NULL,
  `statusdesc` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`statusid`, `statusdesc`) VALUES
(1, 'To Do'),
(2, 'Completed'),
(3, 'In Progress');

-- --------------------------------------------------------

--
-- Table structure for table `theme`
--

CREATE TABLE `theme` (
  `themeid` bigint(20) UNSIGNED NOT NULL,
  `themedesc` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `theme`
--

INSERT INTO `theme` (`themeid`, `themedesc`) VALUES
(1, 'Default');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `userid` bigint(20) UNSIGNED NOT NULL,
  `user_name` text NOT NULL,
  `full_name` text NOT NULL,
  `email` text NOT NULL,
  `emailconfirmed` tinyint(1) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_hash` varchar(32) NOT NULL,
  `usergroupid` bigint(20) UNSIGNED NOT NULL,
  `themeid` bigint(20) UNSIGNED NOT NULL,
  `lastlogin` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `usergroup`
--

CREATE TABLE `usergroup` (
  `usergroupid` bigint(20) UNSIGNED NOT NULL,
  `groupname` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usergroup`
--

INSERT INTO `usergroup` (`usergroupid`, `groupname`) VALUES
(1, 'Admins'),
(2, 'Users');

-- --------------------------------------------------------

--
-- Table structure for table `userinv`
--

CREATE TABLE `userinv` (
  `userinvid` bigint(20) UNSIGNED NOT NULL,
  `userid` bigint(20) UNSIGNED NOT NULL,
  `puzzleid` bigint(20) UNSIGNED NOT NULL,
  `statusid` bigint(20) UNSIGNED NOT NULL,
  `missingpieces` tinyint(3) UNSIGNED NOT NULL,
  `startdate` date NOT NULL,
  `enddate` date NOT NULL,
  `totaldays` smallint(6) NOT NULL,
  `difficultyrating` float UNSIGNED NOT NULL,
  `qualityrating` float UNSIGNED NOT NULL,
  `overallrating` float UNSIGNED NOT NULL,
  `ownershipid` bigint(20) UNSIGNED NOT NULL,
  `loanedoutto` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `auth`
--
ALTER TABLE `auth`
  ADD UNIQUE KEY `authid` (`authid`),
  ADD KEY `fkauthuserid` (`userid`);

--
-- Indexes for table `brand`
--
ALTER TABLE `brand`
  ADD PRIMARY KEY (`brandid`),
  ADD UNIQUE KEY `brandid` (`brandid`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`categoryid`),
  ADD UNIQUE KEY `catid` (`categoryid`);

--
-- Indexes for table `disposition`
--
ALTER TABLE `disposition`
  ADD PRIMARY KEY (`dispositionid`),
  ADD UNIQUE KEY `dispositionid` (`dispositionid`);

--
-- Indexes for table `location`
--
ALTER TABLE `location`
  ADD PRIMARY KEY (`locationid`),
  ADD UNIQUE KEY `locationid` (`locationid`);

--
-- Indexes for table `ownership`
--
ALTER TABLE `ownership`
  ADD PRIMARY KEY (`ownershipid`),
  ADD UNIQUE KEY `ownershipid` (`ownershipid`);

--
-- Indexes for table `puzcat`
--
ALTER TABLE `puzcat`
  ADD PRIMARY KEY (`puzcatid`),
  ADD UNIQUE KEY `puzcatid` (`puzcatid`),
  ADD KEY `fkpuzzleid2` (`puzzleid`),
  ADD KEY `fkcategoryid` (`categoryid`);

--
-- Indexes for table `puzzleinv`
--
ALTER TABLE `puzzleinv`
  ADD UNIQUE KEY `puzzleid` (`puzzleid`),
  ADD KEY `fkbrandid` (`brandid`),
  ADD KEY `fksourceid` (`sourceid`),
  ADD KEY `fklocationid` (`locationid`),
  ADD KEY `fkdispositionid` (`dispositionid`);

--
-- Indexes for table `puzzlewish`
--
ALTER TABLE `puzzlewish`
  ADD PRIMARY KEY (`wishid`),
  ADD UNIQUE KEY `puzzleid` (`wishid`),
  ADD KEY `fkbrand` (`brandid`),
  ADD KEY `fkuser` (`userid`);

--
-- Indexes for table `setup`
--
ALTER TABLE `setup`
  ADD PRIMARY KEY (`installed`);

--
-- Indexes for table `source`
--
ALTER TABLE `source`
  ADD PRIMARY KEY (`sourceid`),
  ADD UNIQUE KEY `sourceid` (`sourceid`);

--
-- Indexes for table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`statusid`),
  ADD UNIQUE KEY `statusid` (`statusid`);

--
-- Indexes for table `theme`
--
ALTER TABLE `theme`
  ADD PRIMARY KEY (`themeid`),
  ADD UNIQUE KEY `themeid` (`themeid`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userid`),
  ADD UNIQUE KEY `userid` (`userid`),
  ADD KEY `fkusergroupid` (`usergroupid`),
  ADD KEY `fkthemeid` (`themeid`);

--
-- Indexes for table `usergroup`
--
ALTER TABLE `usergroup`
  ADD PRIMARY KEY (`usergroupid`),
  ADD UNIQUE KEY `usergroupid` (`usergroupid`);

--
-- Indexes for table `userinv`
--
ALTER TABLE `userinv`
  ADD PRIMARY KEY (`userinvid`),
  ADD UNIQUE KEY `userinvid` (`userinvid`),
  ADD KEY `fkstatusid` (`statusid`),
  ADD KEY `fkownershipid` (`ownershipid`),
  ADD KEY `fkpuzzleid` (`puzzleid`),
  ADD KEY `fkuserid` (`userid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `auth`
--
ALTER TABLE `auth`
  MODIFY `authid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `brand`
--
ALTER TABLE `brand`
  MODIFY `brandid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `categoryid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `disposition`
--
ALTER TABLE `disposition`
  MODIFY `dispositionid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `location`
--
ALTER TABLE `location`
  MODIFY `locationid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ownership`
--
ALTER TABLE `ownership`
  MODIFY `ownershipid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `puzcat`
--
ALTER TABLE `puzcat`
  MODIFY `puzcatid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `puzzleinv`
--
ALTER TABLE `puzzleinv`
  MODIFY `puzzleid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `puzzlewish`
--
ALTER TABLE `puzzlewish`
  MODIFY `wishid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `source`
--
ALTER TABLE `source`
  MODIFY `sourceid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `status`
--
ALTER TABLE `status`
  MODIFY `statusid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `theme`
--
ALTER TABLE `theme`
  MODIFY `themeid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `userid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `usergroup`
--
ALTER TABLE `usergroup`
  MODIFY `usergroupid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `userinv`
--
ALTER TABLE `userinv`
  MODIFY `userinvid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `auth`
--
ALTER TABLE `auth`
  ADD CONSTRAINT `fkauthuserid` FOREIGN KEY (`userid`) REFERENCES `user` (`userid`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `puzcat`
--
ALTER TABLE `puzcat`
  ADD CONSTRAINT `fkcategoryid` FOREIGN KEY (`categoryid`) REFERENCES `categories` (`categoryid`) ON DELETE CASCADE,
  ADD CONSTRAINT `fkpuzzleid2` FOREIGN KEY (`puzzleid`) REFERENCES `puzzleinv` (`puzzleid`) ON DELETE CASCADE;

--
-- Constraints for table `puzzleinv`
--
ALTER TABLE `puzzleinv`
  ADD CONSTRAINT `fkbrandid` FOREIGN KEY (`brandid`) REFERENCES `brand` (`brandid`),
  ADD CONSTRAINT `fkdispositionid` FOREIGN KEY (`dispositionid`) REFERENCES `disposition` (`dispositionid`),
  ADD CONSTRAINT `fklocationid` FOREIGN KEY (`locationid`) REFERENCES `location` (`locationid`),
  ADD CONSTRAINT `fksourceid` FOREIGN KEY (`sourceid`) REFERENCES `source` (`sourceid`);

--
-- Constraints for table `puzzlewish`
--
ALTER TABLE `puzzlewish`
  ADD CONSTRAINT `fkbrand` FOREIGN KEY (`brandid`) REFERENCES `brand` (`brandid`),
  ADD CONSTRAINT `fkuser` FOREIGN KEY (`userid`) REFERENCES `user` (`userid`);

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `fkthemeid` FOREIGN KEY (`themeid`) REFERENCES `theme` (`themeid`),
  ADD CONSTRAINT `fkusergroupid` FOREIGN KEY (`usergroupid`) REFERENCES `usergroup` (`usergroupid`);

--
-- Constraints for table `userinv`
--
ALTER TABLE `userinv`
  ADD CONSTRAINT `fkownershipid` FOREIGN KEY (`ownershipid`) REFERENCES `ownership` (`ownershipid`),
  ADD CONSTRAINT `fkpuzzleid` FOREIGN KEY (`puzzleid`) REFERENCES `puzzleinv` (`puzzleid`) ON DELETE CASCADE,
  ADD CONSTRAINT `fkstatusid` FOREIGN KEY (`statusid`) REFERENCES `status` (`statusid`),
  ADD CONSTRAINT `fkuserid` FOREIGN KEY (`userid`) REFERENCES `user` (`userid`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
