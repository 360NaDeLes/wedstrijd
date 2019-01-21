-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 18 jan 2019 om 17:06
-- Serverversie: 10.1.37-MariaDB
-- PHP-versie: 7.3.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `spelshit`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `authors`
--

CREATE TABLE `authors` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `birthdate` date NOT NULL,
  `added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Gegevens worden geëxporteerd voor tabel `authors`
--

INSERT INTO `authors` (`id`, `first_name`, `last_name`, `email`, `birthdate`, `added`) VALUES
(1, 'Emelie', 'Sporer', 'tyreek.boehm@example.org', '2005-01-01', '2010-05-29 22:22:30'),
(2, 'Lou', 'Koepp', 'ericka.wintheiser@example.com', '1989-12-13', '1995-12-28 03:22:36'),
(3, 'Jude', 'Grimes', 'jeremie.kirlin@example.com', '2006-05-18', '1996-12-26 16:34:43'),
(4, 'Foster', 'Dare', 'daphne25@example.net', '1982-01-27', '2013-01-18 13:43:26'),
(5, 'Jairo', 'Hessel', 'evert18@example.net', '1977-10-28', '1979-07-08 15:46:50'),
(6, 'Quincy', 'Welch', 'pagac.alison@example.net', '2011-01-24', '1992-10-30 12:44:46'),
(7, 'Garth', 'Lynch', 'bauch.meagan@example.org', '2005-03-18', '2005-05-31 13:21:31'),
(8, 'Kelli', 'Hickle', 'stehr.graham@example.net', '1984-03-03', '2014-02-24 14:27:54'),
(9, 'Jamir', 'King', 'tavares19@example.com', '1972-01-12', '1970-05-26 04:17:28'),
(10, 'Patrick', 'Oberbrunner', 'jesus.hammes@example.org', '1975-05-22', '1985-07-21 11:47:11'),
(11, 'Blanca', 'Toy', 'xlubowitz@example.net', '2001-07-24', '1990-03-12 00:50:14'),
(12, 'Adelia', 'Abernathy', 'blanda.brigitte@example.net', '1978-04-02', '1979-08-17 17:19:04'),
(13, 'Dejon', 'Bergstrom', 'kassandra.doyle@example.net', '1976-06-06', '1974-11-03 23:14:09'),
(14, 'Axel', 'Schaden', 'jan.ebert@example.com', '1986-03-26', '1994-11-26 12:11:50'),
(15, 'Chanelle', 'Paucek', 'ronaldo48@example.org', '2009-09-07', '2015-07-16 08:26:48'),
(16, 'Sadie', 'Bahringer', 'ellis59@example.net', '1976-10-26', '2018-08-30 19:31:19'),
(17, 'Gerard', 'Gerhold', 'clotilde37@example.org', '2010-11-23', '1973-03-10 18:01:35'),
(18, 'Raphael', 'Schultz', 'nicholaus.blanda@example.net', '1970-07-21', '1984-02-20 22:19:28'),
(19, 'Orville', 'Littel', 'sspinka@example.org', '2015-10-08', '1973-02-26 08:03:03'),
(20, 'Grace', 'Walter', 'gladys.turcotte@example.com', '2016-04-19', '1979-09-14 07:57:55'),
(21, 'Rosella', 'Reichert', 'nparisian@example.net', '2013-10-27', '2016-08-04 06:53:17'),
(22, 'Emilia', 'Walsh', 'marisol49@example.org', '1977-09-03', '1997-01-27 01:16:31'),
(23, 'Vena', 'Murazik', 'anna31@example.com', '2016-05-29', '1977-08-10 05:28:54'),
(24, 'Jean', 'Robel', 'muhammad54@example.net', '2012-08-28', '2009-03-11 05:41:47'),
(25, 'Rosetta', 'Stark', 'schamberger.mathilde@example.org', '2013-08-16', '2002-07-16 18:43:10'),
(26, 'Nicholaus', 'Auer', 'howell.bert@example.org', '1998-07-20', '2007-02-03 14:08:30'),
(27, 'Gerda', 'Leffler', 'collins.nia@example.org', '2011-03-19', '1987-03-31 08:29:39'),
(28, 'Imani', 'Brakus', 'pborer@example.net', '2011-02-01', '1974-06-19 10:39:56'),
(29, 'Adele', 'Cruickshank', 'hansen.noel@example.org', '2003-03-27', '2007-02-22 08:42:39'),
(30, 'Vella', 'Ankunding', 'marty26@example.com', '2001-09-06', '1983-11-06 20:05:32'),
(31, 'Juliana', 'West', 'beier.annetta@example.org', '1973-08-14', '1998-04-07 10:17:39'),
(32, 'Izabella', 'Zboncak', 'candace.ullrich@example.org', '1982-10-08', '1988-03-08 05:16:57'),
(33, 'fafafa', 'afafa', 'afafaf', '2019-01-02', '2019-01-17 13:21:46'),
(34, 'Henk', 'Theunissen', 'Henk@home.nl', '2000-02-03', '2019-01-17 13:29:58');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `matches`
--

CREATE TABLE `matches` (
  `matchid` int(11) NOT NULL,
  `wedstrijd` int(11) NOT NULL,
  `ronde` int(11) NOT NULL,
  `author_1` int(11) NOT NULL,
  `author_2` int(11) NOT NULL,
  `winner` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `matches`
--

INSERT INTO `matches` (`matchid`, `wedstrijd`, `ronde`, `author_1`, `author_2`, `winner`) VALUES
(1, 1, 1, 2, 27, 2),
(2, 1, 1, 34, 13, 34),
(3, 1, 1, 9, 10, 9),
(4, 1, 1, 4, 28, 4),
(5, 1, 1, 5, 7, 5),
(6, 1, 1, 12, 15, 12),
(7, 1, 1, 20, 6, 20),
(8, 1, 1, 30, 33, 30),
(9, 1, 1, 21, 11, 21),
(10, 1, 1, 1, 18, 1),
(11, 1, 1, 8, 25, 8),
(12, 1, 1, 19, 26, 19),
(13, 1, 1, 29, 31, 29),
(14, 1, 1, 23, 14, 23),
(15, 1, 1, 16, 22, 16),
(16, 1, 1, 24, 3, 24),
(17, 2, 1, 23, 24, 23),
(18, 2, 1, 29, 22, 29),
(19, 2, 1, 28, 18, 28),
(20, 2, 1, 15, 10, 15),
(21, 2, 1, 8, 25, 8),
(22, 2, 1, 2, 1, 2),
(23, 2, 1, 4, 19, 4),
(24, 2, 1, 17, 12, 17),
(25, 1, 2, 5, 2, 5),
(26, 1, 2, 1, 21, 1),
(27, 1, 2, 9, 24, 9),
(28, 1, 2, 30, 16, 30),
(29, 1, 2, 29, 8, 29),
(30, 1, 2, 4, 12, 4),
(31, 1, 2, 19, 20, 19),
(32, 1, 2, 34, 23, 34),
(33, 1, 3, 4, 1, 4),
(34, 1, 3, 9, 34, 9),
(35, 1, 3, 19, 30, 19),
(36, 1, 3, 29, 5, 29),
(37, 1, 4, 29, 9, 29),
(38, 1, 4, 4, 19, 4),
(39, 1, 5, 4, 29, 4),
(40, 2, 2, 2, 15, 2),
(41, 2, 2, 4, 29, 4),
(42, 2, 2, 17, 8, 17),
(43, 2, 2, 28, 23, 28),
(44, 2, 3, 17, 2, 17),
(45, 2, 3, 28, 4, 28),
(46, 2, 4, 17, 28, 17);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `author_id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `winners`
--

CREATE TABLE `winners` (
  `winnersId` int(11) NOT NULL,
  `wedstrijdId` int(11) NOT NULL,
  `authorId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `winners`
--

INSERT INTO `winners` (`winnersId`, `wedstrijdId`, `authorId`) VALUES
(4, 1, 4),
(18, 2, 17);

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `authors`
--
ALTER TABLE `authors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexen voor tabel `matches`
--
ALTER TABLE `matches`
  ADD PRIMARY KEY (`matchid`);

--
-- Indexen voor tabel `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `winners`
--
ALTER TABLE `winners`
  ADD PRIMARY KEY (`winnersId`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `authors`
--
ALTER TABLE `authors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT voor een tabel `matches`
--
ALTER TABLE `matches`
  MODIFY `matchid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT voor een tabel `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `winners`
--
ALTER TABLE `winners`
  MODIFY `winnersId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
