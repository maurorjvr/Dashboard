-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Gegenereerd op: 10 feb 2025 om 09:27
-- Serverversie: 8.0.33
-- PHP-versie: 7.4.33


SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `oneportal`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `companies`
--

CREATE TABLE `companies` (
  `id` bigint NOT NULL,
  `name` varchar(255) NOT NULL,
  `useSSO` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `companyusers`
--

CREATE TABLE `companyusers` (
  `companyid` bigint NOT NULL,
  `userid` bigint NOT NULL,
  `companyadmin` tinyint(1) NOT NULL DEFAULT '0',
  `groupid` bigint NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `dashboards`
--

CREATE TABLE `dashboards` (
  `id` bigint NOT NULL,
  `guid` varchar(255) NOT NULL,
  `companyid` bigint NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `icon` varchar(50) NOT NULL,
  `content` text NOT NULL,
  `orderindex` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `groupdashboards`
--

CREATE TABLE `groupdashboards` (
  `groupid` bigint NOT NULL,
  `dashboardid` bigint NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `groupintegrations`
--

CREATE TABLE `groupintegrations` (
  `groupid` bigint NOT NULL,
  `integrationid` bigint NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `inputparameters`
--

CREATE TABLE `inputparameters` (
  `id` bigint NOT NULL,
  `integrationid` bigint NOT NULL,
  `settingid` bigint NOT NULL,
  `value` varchar(255) NOT NULL,
  `rundate` datetime DEFAULT NULL,
  `filecontent` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `integrations`
--

CREATE TABLE `integrations` (
  `id` bigint NOT NULL,
  `guid` varchar(255) NOT NULL,
  `companyid` bigint NOT NULL,
  `name` varchar(255) NOT NULL,
  `lastrun` datetime NOT NULL,
  `nextrun` datetime NOT NULL,
  `startedat` datetime DEFAULT NULL,
  `state` varchar(50) NOT NULL,
  `cronschedule` varchar(255) NOT NULL,
  `path` varchar(8000) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `logs`
--

CREATE TABLE `logs` (
  `id` bigint NOT NULL,
  `logdatetime` datetime NOT NULL,
  `entry` varchar(8000) NOT NULL,
  `integrationid` bigint NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `settings`
--

CREATE TABLE `settings` (
  `id` bigint NOT NULL,
  `integrationid` bigint NOT NULL,
  `name` varchar(255) NOT NULL,
  `setting` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  `defaultvalue` varchar(255) NOT NULL,
  `inputparameter` tinyint(1) NOT NULL DEFAULT '0',
  `datatype` varchar(50) NOT NULL,
  `secure` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `usergroups`
--

CREATE TABLE `usergroups` (
  `id` bigint NOT NULL,
  `companyid` bigint NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `users`
--

CREATE TABLE `users` (
  `id` bigint NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `secret` varchar(255) NOT NULL,
  `apiuser` tinyint(1) NOT NULL,
  `superadmin` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `user_tokens`
--

CREATE TABLE `user_tokens` (
  `id` int NOT NULL,
  `token` varchar(255) NOT NULL,
  `expiry` datetime NOT NULL,
  `user_id` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `companyusers`
--
ALTER TABLE `companyusers`
  ADD PRIMARY KEY (`companyid`,`userid`);

--
-- Indexen voor tabel `dashboards`
--
ALTER TABLE `dashboards`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `groupdashboards`
--
ALTER TABLE `groupdashboards`
  ADD PRIMARY KEY (`groupid`,`dashboardid`);

--
-- Indexen voor tabel `groupintegrations`
--
ALTER TABLE `groupintegrations`
  ADD PRIMARY KEY (`groupid`,`integrationid`);

--
-- Indexen voor tabel `inputparameters`
--
ALTER TABLE `inputparameters`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `integrations`
--
ALTER TABLE `integrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `usergroups`
--
ALTER TABLE `usergroups`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `user_tokens`
--
ALTER TABLE `user_tokens`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `companies`
--
ALTER TABLE `companies`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `dashboards`
--
ALTER TABLE `dashboards`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `inputparameters`
--
ALTER TABLE `inputparameters`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `integrations`
--
ALTER TABLE `integrations`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `logs`
--
ALTER TABLE `logs`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `usergroups`
--
ALTER TABLE `usergroups`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `user_tokens`
--
ALTER TABLE `user_tokens`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
