-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 19, 2019 at 03:21 AM
-- Server version: 10.4.6-MariaDB
-- PHP Version: 7.3.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `library`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `title` varchar(250) NOT NULL,
  `author` varchar(250) NOT NULL,
  `genre` varchar(250) NOT NULL,
  `section` varchar(250) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` enum('Available','Borrowed') NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `title`, `author`, `genre`, `section`, `user_id`, `status`) VALUES
(1, 'Moby Dick', 'Herman Melville', 'Novel', 'Fiction', 0, 'Available'),
(2, 'Frozen', 'Elsa', 'Fiction', 'Children\'s Section', 0, 'Available'),
(3, 'Hello', 'Marion Rosales', 'Horror', 'General Reference', 0, 'Available'),
(4, 'Slam Dunk', 'Sakuragi', 'Romance', 'Periodical Section', 0, 'Available'),
(5, 'Red Velvet', 'Irene', 'Thriller', 'Circulation', 0, 'Available');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `name` varchar(50) NOT NULL,
  `address` varchar(250) NOT NULL,
  `contact` varchar(20) NOT NULL,
  `userlevel` enum('user','admin') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `name`, `address`, `contact`, `userlevel`) VALUES
(7, 'rio', '$2y$10$rj2Rq6wOmo2JuMixBpf9Cux.cfycAxxuHaik2dgOojjdIXtV/jby6', 'Rio', 'Makati City', '091564165', 'admin'),
(8, 'marion', '$2y$10$DKPOupU390i1/KFCiXcn5e6typ0M..AFiZ1Fn2Fn2u7rHeMRe1isa', 'Marion', 'QC', '09177008298', 'user'),
(9, 'user', '$2y$10$ADr99owSbcuIBIZoltjxK.AFmyy.I7TIO9nL7/glbNpIb0fm82k.W', 'user', 'tabi tabi', '091566545', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
