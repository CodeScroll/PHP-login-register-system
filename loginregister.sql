-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Φιλοξενητής: 127.0.0.1
-- Χρόνος δημιουργίας: 19 Μάη 2018 στις 15:16:19
-- Έκδοση διακομιστή: 10.1.26-MariaDB
-- Έκδοση PHP: 7.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Βάση δεδομένων: `loginregister`
--

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `autologin`
--

CREATE TABLE `autologin` (
  `user_key` char(64) NOT NULL,
  `token` char(64) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `used` tinyint(1) NOT NULL DEFAULT '0',
  `ip` char(80) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `facebook_users`
--

CREATE TABLE `facebook_users` (
  `fbid` varchar(50) NOT NULL,
  `fbname` varchar(50) NOT NULL,
  `fbemail` varchar(100) NOT NULL,
  `user_key` char(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `google_users`
--

CREATE TABLE `google_users` (
  `sub` varchar(60) NOT NULL,
  `google_name` varchar(60) NOT NULL,
  `google_email` varchar(100) NOT NULL,
  `user_key` char(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `login_attempts`
--

CREATE TABLE `login_attempts` (
  `email` varchar(100) NOT NULL,
  `attempts` tinyint(1) NOT NULL,
  `ipaddress` char(80) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `resetpassword`
--

CREATE TABLE `resetpassword` (
  `user_key` char(64) NOT NULL,
  `reset_code` char(64) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `email` varchar(100) NOT NULL,
  `verify` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `users`
--

CREATE TABLE `users` (
  `user_key` char(64) NOT NULL,
  `username` varchar(50) NOT NULL,
  `surname` varchar(50) NOT NULL,
  `pwd` char(64) NOT NULL,
  `email` varchar(100) NOT NULL,
  `verify` tinyint(1) NOT NULL DEFAULT '0',
  `userid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `verifying`
--

CREATE TABLE `verifying` (
  `user_key` char(64) NOT NULL,
  `user_code` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Ευρετήρια για άχρηστους πίνακες
--

--
-- Ευρετήρια για πίνακα `autologin`
--
ALTER TABLE `autologin`
  ADD PRIMARY KEY (`user_key`,`token`);

--
-- Ευρετήρια για πίνακα `facebook_users`
--
ALTER TABLE `facebook_users`
  ADD PRIMARY KEY (`user_key`),
  ADD UNIQUE KEY `user_key` (`user_key`);

--
-- Ευρετήρια για πίνακα `google_users`
--
ALTER TABLE `google_users`
  ADD PRIMARY KEY (`sub`);

--
-- Ευρετήρια για πίνακα `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD PRIMARY KEY (`email`);

--
-- Ευρετήρια για πίνακα `resetpassword`
--
ALTER TABLE `resetpassword`
  ADD PRIMARY KEY (`user_key`);

--
-- Ευρετήρια για πίνακα `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_key`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `user_key` (`user_key`),
  ADD UNIQUE KEY `userid` (`userid`),
  ADD KEY `username` (`username`);

--
-- Ευρετήρια για πίνακα `verifying`
--
ALTER TABLE `verifying`
  ADD PRIMARY KEY (`user_key`),
  ADD UNIQUE KEY `user_key` (`user_key`);

--
-- AUTO_INCREMENT για άχρηστους πίνακες
--

--
-- AUTO_INCREMENT για πίνακα `users`
--
ALTER TABLE `users`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
