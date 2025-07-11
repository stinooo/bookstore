-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 26 dec 2024 om 17:25
-- Serverversie: 10.4.32-MariaDB
-- PHP-versie: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `webshop`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `genres`
--

CREATE TABLE `genres` (
  `id` int(11) NOT NULL,
  `genre` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `genres`
--

INSERT INTO `genres` (`id`, `genre`) VALUES
(1, 'Horror'),
(2, 'Fantasy'),
(3, 'Mystery'),
(4, 'Thriller'),
(5, 'Adventure'),
(6, 'True Crime'),
(7, 'Fiction'),
(8, 'Drama'),
(9, 'Spooky'),
(10, 'Kinderen'),
(11, 'Adult'),
(12, 'Young-Adults'),
(13, 'Monsters'),
(14, 'GOD'),
(15, 'GODS'),
(16, 'Movie'),
(17, 'Old');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total` float NOT NULL,
  `made@` timestamp NULL DEFAULT current_timestamp(),
  `name` text NOT NULL,
  `phone` int(11) DEFAULT NULL,
  `address` text NOT NULL,
  `city` text NOT NULL,
  `country` text NOT NULL,
  `postal_code` int(11) NOT NULL,
  `payment_method` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total`, `made@`, `name`, `phone`, `address`, `city`, `country`, `postal_code`, `payment_method`) VALUES
(7, 9, 266, '2024-12-09 02:21:39', ' ', , ' ', '', 'Belgium', 2570, 'card'),
(8, 9, 104, '2024-12-22 23:21:13', '2121', 0, '', 'pod', 'belguim', 2570, 'paypal'),
(9, 9, 216, '2024-12-25 06:58:47', '', 812381239, 'janlaan 203', 'Lier', 'Belguim', 25000, 'paypal'),
(10, 25, 112, '2024-12-26 03:49:27', 'pookie', 123456789, ' ', 'Duffel', 'Belguim', 1203, 'paypal'),
(11, 26, 152, '2024-12-26 05:32:05', 'harryPottr ', 1255679, 'joepistraat 29', 'harry', 'sweden', 12312, 'paypal'),
(12, 9, 18, '2024-12-26 05:36:06', 'done ', 12455646, 'janny 123', 'lier', 'belgum', 2234, 'paypal'),
(13, 9, 18, '2024-12-26 05:40:18', 'jan', 1234567800, 'liersesteenweg', 'city', 'france', 123453, 'bank'),
(14, 9, 12, '2024-12-26 05:44:50', '', 123456780, '', 'Lier', 'France', 24243, 'card'),
(15, 9, 12, '2024-12-26 05:51:16', 'try', 21312413, 'liersesteenweg', 'Lier', 'Belguim', 123123, 'card'),
(16, 9, 12, '2024-12-26 05:52:58', ' ', 12312312, ' ', '', 'France', 24243, 'paypal'),
(17, 27, 183, '2024-12-26 10:18:58', 'pookie', 123456789, '', 'duffel', 'pookieland', 12345, 'bank'),
(18, 27, 69.984, '2024-12-26 10:49:00', 'thomas', 1234567890, 'achtbaan 23', 'evergem', 'germany', 1231231, 'paypal');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `order_items`
--

CREATE TABLE `order_items` (
  `product_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `amount` int(11) DEFAULT NULL,
  `price` float NOT NULL,
  `sale` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `order_items`
--

INSERT INTO `order_items` (`product_id`, `order_id`, `amount`, `price`, `sale`) VALUES
(5, 7, 10, 14, 0),
(4, 7, 4, 12, 0),
(3, 7, 3, 18, 3),
(2, 7, 1, 12, 0),
(6, 7, 1, 15, 0),
(3, 8, 5, 18, 3),
(4, 8, 1, 12, 0),
(4, 9, 1, 12, 0),
(3, 9, 11, 19, 4),
(3, 10, 1, 19, 4),
(2, 10, 1, 12, 0),
(1, 10, 1, 14, 10),
(6, 10, 1, 15, 0),
(8, 10, 1, 30, 34),
(9, 10, 1, 10, 54),
(5, 10, 1, 14, 0),
(4, 11, 1, 12, 0),
(3, 11, 1, 19, 4),
(8, 11, 1, 30, 34),
(6, 11, 1, 15, 0),
(11, 11, 1, 44, 0),
(12, 11, 1, 33, 3),
(3, 12, 1, 19, 4),
(3, 13, 1, 19, 4),
(4, 14, 1, 12, 0),
(4, 15, 1, 12, 0),
(4, 16, 1, 12, 0),
(11, 17, 2, 44, 0),
(8, 17, 1, 30, 34),
(13, 17, 1, 33, 3),
(12, 17, 1, 33, 3),
(3, 18, 1, 18.624, 4),
(4, 18, 1, 12, 0),
(8, 18, 1, 29.7, 34),
(9, 18, 1, 9.66, 54);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `productname` text NOT NULL,
  `author` text NOT NULL,
  `price` float NOT NULL,
  `description` text DEFAULT NULL,
  `imageplace` text DEFAULT 'product_1.png',
  `madeby` int(11) NOT NULL,
  `show_on_web` tinyint(1) DEFAULT 0,
  `sale` float DEFAULT 0,
  `Featured` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `products`
--

INSERT INTO `products` (`id`, `productname`, `author`, `price`, `description`, `imageplace`, `madeby`, `show_on_web`, `sale`, `Featured`) VALUES
(1, 'change', 'James', 12, 'dit boek gaat over Harry Potter en zijn nieuwe avanturen ', 'HarryPotter4.jpg', 11, 1, 9, 1),
(2, 'becoming', 'Michelle Obama', 12, 'An intimate and inspiring memoir by the former First Lady of the United States, chronicling her life from childhood on the South Side of Chicago to her time in the White House.', 'product_1.png', 9, 1, 0, 0),
(3, 'james', 'james', 123, 'A dystopian novel that delves into the perils of totalitarianism and excessive government control, set in a society under constant surveillance.', 'HarryPotter4.jpg', 9, 1, 70, 1),
(4, 'becoming', 'Michelle Obama', 12, 'An intimate and inspiring memoir by the former First Lady of the United States, chronicling her life from childhood on the South Side of Chicago to her time in the White House.', 'product_1.png', 9, 1, 0, 1),
(5, 'Where the Crawdads Sing', 'Delia Owens', 13.67, 'A poignant coming-of-age mystery about Kya, the “marsh girl,” who grows up isolated in the swamps of North Carolina and becomes entangled in a murder investigation.', 'HarryPotter.jpg', 9, 1, 0, 0),
(6, 'The Four Agreements', 'Don Miguel Ruiz', 14.99, 'A transformative guide rooted in ancient Toltec wisdom, offering four principles to achieve personal freedom and self-fulfillment.', 'shark.jpg', 9, 1, 0, 0),
(8, 'The Midnight Library', 'Michelle Obama', 45, 'A magical and enchanting story about a mysterious circus that appears without warning and serves as the stage for a fierce competition between two young illusionists.', 'HarryPotter3.jpg', 9, 1, 34, 1),
(9, 'Circe', ' Madeline Miller', 21, 'A retelling of Greek mythology focusing on the witch Circe, exploring her struggles and journey of self-discovery in a world of gods and mortals.', 'HarryPotter4.jpg', 9, 1, 54, 1),
(11, 'The Night Circus', 'Erin Morgenstern', 44, 'A magical and enchanting story about a mysterious circus that appears without warning and serves as the stage for a fierce competition between two young illusionists.', 'HarryPotter.jpg', 9, 1, 0, 1),
(12, 'The Night Circus', 'Erin Morgenstern', 34, 'A magical and enchanting story about a mysterious circus that appears without warning and serves as the stage for a fierce competition between two young illusionists.', 'Gone2.jpg', 9, 1, 3, 0),
(13, 'The Alchemist', ' Paulo Coelho', 34, 'A philosophical novel about a shepherd named Santiago who embarks on a journey to find a treasure, learning profound lessons about life and destiny.', 'Gone.jpg', 9, 1, 3, 0),
(14, 'Circe', 'Michelle Obama', 9, 'A retelling of Greek mythology from the perspective of Circe, the enchantress who was exiled to an island for her powers.', 'HarryPotter2.jpg', 9, 1, 8, 0),
(15, 'The Night Circus', 'james', 123, 'An intimate and inspiring memoir by the former First Lady of the United States, chronicling her life from childhood on the South Side of Chicago to her time in the White House.', 'gone3.jpg', 9, 1, 23, 1);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `product_genre`
--

CREATE TABLE `product_genre` (
  `productID` int(11) NOT NULL,
  `genreID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `product_genre`
--

INSERT INTO `product_genre` (`productID`, `genreID`) VALUES
(1, 8),
(1, 9),
(1, 10),
(2, 5),
(2, 8),
(2, 10),
(5, 12),
(5, 13),
(9, 5),
(9, 8),
(9, 10),
(9, 12),
(13, 2),
(13, 5),
(13, 9),
(13, 11),
(13, 13),
(14, 3),
(14, 10),
(8, 3),
(8, 8),
(8, 11),
(3, 4),
(3, 5),
(3, 6),
(3, 12),
(15, 9),
(15, 10),
(15, 11);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` text NOT NULL,
  `email` text NOT NULL,
  `pass` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_nopad_ci NOT NULL,
  `role` enum('user','admin','','') NOT NULL DEFAULT 'user',
  `active` enum('1','0','','') NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `pass`, `role`, `active`) VALUES
(9, 'pookie', '.@.com', '$argon2i$v=19$m=65536,t=4,p=1$NURPMWRtSnE5MlJ2L3N4cQ$p0QpP13GAo9nasGlkbbSUUkto43GgErZM/jc9NPAArA', 'admin', '1'),
(10, 'pog', '.@passmail.net', '$argon2i$v=19$m=65536,t=4,p=1$UFkvUE9nZVJXbjFiaEUyaA$uTbYZKdRr40E7uYGeI6S2Zlyy8TybtxoB0ckSmJRG6c', 'admin', ''),
(11, 'pogooo', '@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$Q1NHaGJmLmtScFpjQmFWNA$6thNtn3qbvePjz/PJ18wRa8KFPneVkUtgPUOXFsJR8I', 'user', '0'),
(13, 'stinoo1', 'jan.wouter', '$argon2i$v=19$m=65536,t=4,p=1$VTZBaGZjNEtVZXlvS3l3cA$yK1OMZzDK2kmdjRahVXaUkc5oCs9GzzaJt4P5Cfy+Jk', 'admin', '1'),
(14, 'aj', '.@outlook.com', '$argon2i$v=19$m=65536,t=4,p=1$b1hwa2JteFlsY1ouSm1oaA$Ac1xVfjNy1zuM+YtiqTLyD8gH4O1QJAPM3f+aT/pp0A', 'user', '1'),
(15, 'aùsod', 'amdb@sldh.com', '$argon2i$v=19$m=65536,t=4,p=1$OGJTVGpEM0xrbjlidVFDNg$ikXeHiBKA94YNBu9pMIE3aGYlpLR1W++/Mmc2mVb69o', 'user', '1'),
(16, 'ze:kfnn', 'laekhdvd@msrrofb.com', '$argon2i$v=19$m=65536,t=4,p=1$VzNOZjlyam96Q242YnVZRw$5nX1Fzqw4fO9aez7xYmd+xbjTC5F4hoMQlB6uDVXJyw', 'user', '1'),
(17, 'jan', '.@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$TWFIbkRzd3hHWGFpWE5XcA$CKzUq0wQLCzDbxwWQxdfWANH7fnVbxe0fzdsWVUlndA', 'user', '1'),
(18, 'papa', 'robe.rubens@outlook.com', '$argon2i$v=19$m=65536,t=4,p=1$Y0lKRUV5T3FoZG9idkt1aQ$mEPmuER8pGSWNzd6iZ1XShe/pmhFu6kvQu0zc0lKiPc', 'user', '1'),
(19, 'sam', 'sam.klap@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$N0poOXdNN1pnREtrZFg1eQ$qmfvnJCOUC7N7c9+viEPh+8d+oNKa14N9Z+40J3INYI', 'user', '1'),
(20, 'stinoo23', 'pog@pog.pog', '$argon2i$v=19$m=65536,t=4,p=1$SFFaNFM5dXdJb1B1RDBHRA$BeZK5ui3nu/jrmVAb4erZSi9CTw6A9QPY3NTk3DB1qc', 'user', '0'),
(21, 'pino', 'gabber@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$eGdxMkZGZmVDYXBuQWlZYw$RwvCkt5W8bFtpG6oP6H/mJHzAYx9+A27NhgtEt0+YH8', 'user', '1'),
(22, 'pookie129', 'pookie.po@gmaim.com', '$argon2i$v=19$m=65536,t=4,p=1$R3J5NW1jS0pNZ3pYZk5PNA$1BNX/A3rXG0iBcEoXwyyiBVbwF+m6mWOUcigIcY29aA', 'user', '1'),
(23, 'pookie23', '.pookie@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$b3QuREcyU2F6NUlvRlhVaA$DFpb2Kku8aw5a4E3FrPUWlG2DqN1zz4fJ9rZK/J2kqg', 'user', '1'),
(24, 'zmeib', '123@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$dlQvVzhZdWQ4SWNYODlZYw$gt0TZoGmjx134SiPsJlQQMQQsfvHar0QK2zVc7ydryc', 'user', '1'),
(25, 'slùvjb', 'pookie@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$Vm1YdXJ3SUdyTUh0UnZtUQ$YhyD52rHhy2e0IMrHdMtdG77vCIrsZtw1OrjDgJ+JF0', 'user', '1'),
(26, 'stinks209', '.@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$VkZ2blZNdEJEZGxWOFlUWQ$lek7mryHl8h1YqhuHBzNUv0wfEN2hVfQmGiGYYFVXUE', 'admin', '1'),
(27, 'skibidy', 'skibidi@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$SDdJLzhkZ3pRWVJ2NGVYNQ$dPUY1OOshtNN9Rvab8Ae8viarTK4+Vus2rwZ5o5/54w', 'user', '1');

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `genres`
--
ALTER TABLE `genres`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userid` (`user_id`);

--
-- Indexen voor tabel `order_items`
--
ALTER TABLE `order_items`
  ADD KEY `productID` (`product_id`),
  ADD KEY `orderID` (`order_id`);

--
-- Indexen voor tabel `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user` (`madeby`);

--
-- Indexen voor tabel `product_genre`
--
ALTER TABLE `product_genre`
  ADD KEY `productID` (`productID`),
  ADD KEY `genreID` (`genreID`);

--
-- Indexen voor tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `genres`
--
ALTER TABLE `genres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT voor een tabel `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT voor een tabel `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT voor een tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `userid` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Beperkingen voor tabel `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);

--
-- Beperkingen voor tabel `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `user` FOREIGN KEY (`madeby`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Beperkingen voor tabel `product_genre`
--
ALTER TABLE `product_genre`
  ADD CONSTRAINT `product_genre_ibfk_1` FOREIGN KEY (`productID`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `product_genre_ibfk_2` FOREIGN KEY (`genreID`) REFERENCES `genres` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
