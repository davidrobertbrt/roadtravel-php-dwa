-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 05, 2023 at 01:17 AM
-- Server version: 10.6.12-MariaDB-0ubuntu0.22.04.1
-- PHP Version: 8.1.2-1ubuntu2.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `roadtravel`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`phpmyadmin`@`localhost` PROCEDURE `CheckBookingAvailability` (IN `tripId` INT, IN `numOfSeats` INT, OUT `canBook` INT)  BEGIN
  DECLARE totalBookedSeats INT;
  
  SELECT IFNULL(SUM(bk.numOfPersons), 0)
  INTO totalBookedSeats
  FROM bookings AS bk
  WHERE bk.tripId = tripId;
  
  SET canBook = IF(totalBookedSeats + numOfSeats <= (
      SELECT b.nrSeats
      FROM buses AS b
      WHERE b.id = (
        SELECT busId
        FROM trips
        WHERE id = tripId
      )
    ), 1, 0);
END$$

CREATE DEFINER=`simplemvc`@`localhost` PROCEDURE `CheckBookingValidityForBus` (IN `busId` INT, IN `numOfSeats` INT, OUT `isValid` INT)  BEGIN
  DECLARE totalBookedSeats INT;
  
  -- Get the total number of seats booked for the bus
  SELECT IFNULL(SUM(b.numOfPersons), 0)
  INTO totalBookedSeats
  FROM bookings AS b
  INNER JOIN trips AS t ON b.tripId = t.id
  WHERE t.busId = busId;
  
  -- Check if the total booked seats plus the new number of seats is less than or equal to the bus's total seats
  IF (totalBookedSeats + numOfSeats) <= (SELECT nrSeats FROM buses WHERE id = busId) THEN
    SET isValid = 1; -- Bookings are still valid
  ELSE
    SET isValid = 0; -- Bookings are not valid
  END IF;
  
END$$

CREATE DEFINER=`phpmyadmin`@`localhost` PROCEDURE `ListTripsWithAvailableSeats` (IN `targetDateTime` DATETIME, IN `startLocationId` INT)  BEGIN
  SELECT t.id, t.busId, t.locationStartId,t.locationEndId,t.dateTimeStart, t.dateTimeEnd
  FROM trips AS t
  INNER JOIN buses AS b ON t.busId = b.id
  WHERE t.dateTimeStart >= targetDateTime
    AND t.locationStartId = startLocationId
    AND (
      SELECT IFNULL(SUM(bk.numOfPersons), 0)
      FROM bookings AS bk
      WHERE bk.tripId = t.id
    ) < b.nrSeats;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `tripId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `numOfPersons` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `datePurchase` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `buses`
--

CREATE TABLE `buses` (
  `id` int(11) NOT NULL,
  `nrSeats` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `buses`
--

INSERT INTO `buses` (`id`, `nrSeats`) VALUES
(3, 70),
(4, 50),
(6, 30);

-- --------------------------------------------------------

--
-- Table structure for table `credentials`
--

CREATE TABLE `credentials` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `password` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `credentials`
--

INSERT INTO `credentials` (`id`, `userId`, `password`) VALUES
(1, 1, '$2y$12$KStXyikEm4KE0akbxNYn/O89K39C0kKrGCGae10hgM0.5GYeD4fMa'),
(2, 2, '$2y$12$dcPtNjhV8KDIRF/83ptN7el7usuTDha0pB56BLd9H62dOAk1bAuf2'),
(3, 3, '$2y$12$QGh1L6ZI4DvftriCmHfU/.Bt7mKhgIdpoeYkZXJQFMNPdTU.wq0Gi'),
(4, 4, '$2y$12$Jdgv5YAn9sjmYqLzDV0hLehP6Y22eUQpxewmjLwWGeFv/3/LUx/de'),
(5, 5, '$2y$12$XM2MNhMCVL4p41WSaC.XPeaPvXGCaA49lUWi5ypFrf.SLYe9.d1i.'),
(6, 8, '$2y$12$kL/Hl2MWpITsMAd2Ccs9O.DbIggyDNrclc3K5PK2XMDqosqzhG7AW'),
(8, 11, '$2y$12$v5Hb8u4DQnrJ/Kj0gYI.x.Aw5KLWpNU2LLE1j7xXwCjKYQJQ.R6jW');

-- --------------------------------------------------------

--
-- Table structure for table `discounts`
--

CREATE TABLE `discounts` (
  `id` int(11) NOT NULL,
  `factor` decimal(2,1) DEFAULT NULL,
  `used` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `discounts`
--

INSERT INTO `discounts` (`id`, `factor`, `used`) VALUES
(3, '0.8', 0);

-- --------------------------------------------------------

--
-- Table structure for table `hits`
--

CREATE TABLE `hits` (
  `id` int(11) NOT NULL,
  `ip_address` varchar(256) DEFAULT NULL,
  `timestamp` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hits`
--

INSERT INTO `hits` (`id`, `ip_address`, `timestamp`) VALUES
(5, '192.168.0.1', '2023-06-01 09:15:00'),
(6, '192.168.0.2', '2023-06-01 10:30:00'),
(7, '192.168.0.3', '2023-06-01 12:45:00'),
(8, '192.168.0.4', '2023-06-01 14:00:00'),
(9, '192.168.0.5', '2023-06-01 15:30:00'),
(10, '192.168.0.6', '2023-06-02 09:45:00'),
(11, '192.168.0.7', '2023-06-02 11:00:00'),
(12, '192.168.0.8', '2023-06-02 12:15:00'),
(13, '192.168.0.9', '2023-06-02 14:30:00'),
(14, '192.168.0.10', '2023-06-02 16:00:00'),
(15, '192.168.0.11', '2023-06-03 10:15:00'),
(16, '192.168.0.12', '2023-06-03 11:30:00'),
(17, '192.168.0.13', '2023-06-03 13:45:00'),
(18, '192.168.0.14', '2023-06-03 15:00:00'),
(19, '192.168.0.15', '2023-06-03 16:30:00'),
(20, '192.168.0.15', '2023-06-03 18:30:00'),
(21, '::1', '2023-06-04 19:18:17'),
(22, '::1', '2023-06-04 21:54:18'),
(23, '::1', '2023-06-04 23:19:40'),
(24, '::1', '2023-06-04 23:24:27'),
(25, '::1', '2023-06-05 00:19:19'),
(26, '::1', '2023-06-05 00:23:12'),
(27, '::1', '2023-06-05 00:57:48');

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `longitude` decimal(10,8) NOT NULL,
  `latitude` decimal(10,8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `name`, `longitude`, `latitude`) VALUES
(1, 'Bucuresti', '26.09665000', '44.42802000'),
(2, 'Cluj-Napoca', '23.60000000', '46.76667000'),
(3, 'Timisoara', '21.22571000', '45.75372000'),
(4, 'Iasi', '27.60000000', '47.16667000'),
(5, 'Constanta', '28.63432000', '44.18073000'),
(6, 'Craiova', '23.80000000', '44.31667000'),
(7, 'Brasov', '25.60613000', '45.64861000'),
(8, 'Galati', '28.05028000', '45.43687000'),
(9, 'Ploiesti', '26.01667000', '44.95000000'),
(10, 'Oradea', '21.91833000', '47.04580000'),
(11, 'Braila', '27.97429000', '45.27152000'),
(12, 'Arad', '21.31667000', '46.18333000'),
(13, 'Pitesti', '24.86667000', '44.85000000'),
(14, 'Sibiu', '24.15000000', '45.80000000'),
(15, 'Bacau', '26.91384000', '46.56718000'),
(16, 'Targu Mures', '24.55747000', '46.54245000'),
(17, 'Baia Mare', '23.56808000', '47.65729000'),
(18, 'Buzau', '26.83333000', '45.15000000'),
(19, 'Satu Mare', '22.86255000', '47.79926000'),
(20, 'Ramnicu Valcea', '24.36667000', '45.10000000'),
(25, 'Otopeni', '26.06667000', '44.55000000'),
(26, 'Suceava', '26.25000000', '47.63333000'),
(30, 'Tulcea', '28.80501000', '45.17870000');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `role` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `userId`, `role`) VALUES
(1, 1, 'default'),
(2, 2, 'default'),
(3, 3, 'default'),
(5, 4, 'default'),
(6, 5, 'default'),
(7, 8, 'admin'),
(8, 11, 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `trips`
--

CREATE TABLE `trips` (
  `id` int(11) NOT NULL,
  `busId` int(11) NOT NULL,
  `locationStartId` int(11) NOT NULL,
  `locationEndId` int(11) NOT NULL,
  `dateTimeStart` datetime NOT NULL,
  `dateTimeEnd` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trips`
--

INSERT INTO `trips` (`id`, `busId`, `locationStartId`, `locationEndId`, `dateTimeStart`, `dateTimeEnd`) VALUES
(2, 3, 1, 18, '2023-05-23 22:20:00', '2023-05-23 23:30:00'),
(3, 3, 1, 3, '2023-05-20 10:00:00', '2023-05-20 17:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `firstName` varchar(256) NOT NULL,
  `lastName` varchar(256) NOT NULL,
  `dateOfBirth` date NOT NULL,
  `phoneNumber` varchar(15) NOT NULL,
  `address` varchar(256) NOT NULL,
  `emailAddress` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstName`, `lastName`, `dateOfBirth`, `phoneNumber`, `address`, `emailAddress`) VALUES
(1, 'Ana', 'Popescu', '1980-01-15', '0722123456', 'Strada Mihai Eminescu nr. 5, Bucuresti', 'ana.popescu@example.com'),
(2, 'Ion', 'Ionescu', '1995-07-22', '0744556677', 'Bulevardul Unirii nr. 10, Cluj-Napoca', 'ion.ionescu@example.com'),
(3, 'Maria', 'Georgescu', '1987-11-30', '0733998877', 'Piata Victoriei nr. 20, Timisoara', 'maria.georgescu@example.com'),
(4, 'Alexandru', 'Dumitrescu', '2001-03-05', '0766123456', 'Strada Libertatii nr. 15, Iasi', 'alexandru.dumitrescu@example.com'),
(5, 'Andreea', 'Stanescu', '1990-12-10', '0755778899', 'Aleea Magnoliei nr. 8, Constanta', 'andreea.stanescu@example.com'),
(8, 'Johnny', 'Doe', '2000-05-23', '0726779004', 'bulevardul Unirii', 'john.doe@exemplu.ro'),
(11, 'Mihai', 'Andries', '2000-05-23', '0711111', 'str. Exemplu nr.100', 'mihai.andries@exemplu.ro');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_bookings_users` (`userId`),
  ADD KEY `FK_bookings_trips` (`tripId`);

--
-- Indexes for table `buses`
--
ALTER TABLE `buses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `credentials`
--
ALTER TABLE `credentials`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_credentials_users` (`userId`);

--
-- Indexes for table `discounts`
--
ALTER TABLE `discounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hits`
--
ALTER TABLE `hits`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userId` (`userId`);

--
-- Indexes for table `trips`
--
ALTER TABLE `trips`
  ADD PRIMARY KEY (`id`),
  ADD KEY `busId` (`busId`),
  ADD KEY `locationStartId` (`locationStartId`),
  ADD KEY `locationEndId` (`locationEndId`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `buses`
--
ALTER TABLE `buses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `credentials`
--
ALTER TABLE `credentials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `discounts`
--
ALTER TABLE `discounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `hits`
--
ALTER TABLE `hits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `trips`
--
ALTER TABLE `trips`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `FK_bookings_trips` FOREIGN KEY (`tripId`) REFERENCES `trips` (`id`),
  ADD CONSTRAINT `FK_bookings_users` FOREIGN KEY (`userId`) REFERENCES `users` (`id`);

--
-- Constraints for table `credentials`
--
ALTER TABLE `credentials`
  ADD CONSTRAINT `FK_credentials_users` FOREIGN KEY (`userId`) REFERENCES `users` (`id`);

--
-- Constraints for table `roles`
--
ALTER TABLE `roles`
  ADD CONSTRAINT `roles_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`id`);

--
-- Constraints for table `trips`
--
ALTER TABLE `trips`
  ADD CONSTRAINT `trips_ibfk_1` FOREIGN KEY (`busId`) REFERENCES `buses` (`id`),
  ADD CONSTRAINT `trips_ibfk_2` FOREIGN KEY (`locationStartId`) REFERENCES `locations` (`id`),
  ADD CONSTRAINT `trips_ibfk_3` FOREIGN KEY (`locationEndId`) REFERENCES `locations` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
