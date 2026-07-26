CREATE DATABASE `ecoride`;

USE `ecoride`;

CREATE TABLE `roles` (
  `id` int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `role` varchar(50) NOT NULL
);

INSERT INTO `roles` (`id`, `role`) VALUES
(2, 'chauffeur'),
(3, 'passager'),
(4, 'employé'),
(5, 'administrateur'),
(6, 'passager_chauffeur');

CREATE TABLE `users` (
  `id` int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(60) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `address` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('active', 'suspended') NOT NULL DEFAULT 'active',
  `role_id` int(11) NOT NULL,
  FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
);

INSERT INTO `users` (`id`, `pseudo`, `email`, `password`, `last_name`, `first_name`, `address`, `role_id`, `image`, `status`) VALUES
(1, 'BlueSnack26', 'mathieu@test.fr', '$2y$10$KP5mvxXlEUrKkS6j/73OgeoHcmFC1Dkx3tJR514PdxRrkdB6m/J1y', 'Test', 'Mathieu', '1 rue du test 75001 Paris', 2, 'se1.png', 'active'),
(2, 'KoalaCute34', 'koala@cute.fr', '$2y$10$7hGIi3GG9pHAu0zqL5.9t.If9v4Xoam9VCa7onwld2vaitpvsLYri', 'Martin', 'Milla', '2 av du test 75002 Paris', 6, 'se2.png', 'active'),
(3, 'CatRina28', 'cat@rina.fr', '$2y$10$eCyI6I.h7k.OT5R9KGh5/.gVSR.a7i4K8C9BE0ZmVMfwSH/L5AauS', 'Martinez', 'Catrina', '3 av Toulouse 34000 Montpellier', 2, 'se3.png', 'active'),
(7, 'FoxBro146', 'mani@karan.fr', '$2y$10$Es036.dTtxMcb/VqJb2d7OHsWwSlEjIOcZ9EmxvUqErFzOatqjNGS', 'Karan', 'Mani', '4 place de la comedie 34000 Montpellier', 2, '67952d7d1473b-se4.png', 'active'),
(9, 'admin', 'admin34@test.fr', '$2y$10$1CP2gQNT1hcFfe9fmQYHVeMUzYT0MfppM1APCqvCaxNlz7RvEAFQG', 'super', 'admin', 'adresse admin', 5, NULL, 'active'),
(10, 'test', 'test@test.fr', '$2y$10$XqcaR89r8McR6ikCXY/VjOyX5ZRgLsQMqebeKJdh19sCbRRnrrsxu', 'test', 'test', 'rue du test', 2, NULL, 'active'),
(11, 'passagerTest', 'passager@test.test', '$2y$10$mNqH99zjfHLM5Hon5/FKAeWDFUS10/XQD51jFbFEAs1A8HvyV/Nhq', 'passagerTest', 'passagerTest', 'passagerTest', 3, NULL, 'active'),
(13, 'John341', 'john@doe.fr', '$2y$10$6adODG4qyAnW6w80qlnCz./98vDLNrIAlPjncWGmxNTwtJhHw11fq', 'Doe', 'John', '4 rue du canal 75004 Paris', 3, NULL, 'active'),
(14, 'djasou', 'djasouboutique@gmail.com', '$2y$10$MeYije9TgU8qZBhl7Wgo/u1i7fSQ/I3Gnt24V6zbxeB0ZFwdOR5Fy', 'nel', 'ly', '11 rue le test 33000 Bordeaux', 6, '68299202368ef-nel photo.jpg', 'active'),
(16, 'lorisB42', 'lorisb@laposte.net', '$2y$10$ijt57GbDokbPktzJhuBrDOfGq6/taC267P6ta2fYvVvThzWP83pbm', 'bou', 'loris', '11 Lotissement le coeur 33000 Bordeaux', 3, '68299d3c94d22-45h.png', 'active'),
(17, 'nelly34', 'nellyboussekhane@free.fr', '$2y$10$E5nZtrg7o.a8HuC3nz.IF.iTTCIUNe6wzti.sBhMwuHLmwsbmj5Lu', 'Nelly', 'nelly', 'rue des rêves', 6, NULL, 'active'),
(21, 'employe1', 'employe1@ecoride.fr', '$2y$10$E5nZtrg7o.a8HuC3nz.IF.iTTCIUNe6wzti.sBhMwuHLmwsbmj5Lu', 'Durand', 'Martin', 'Ecoride', 4, NULL, 'active');

CREATE TABLE `cars` (
  `id` int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `brand` varchar(50) NOT NULL,
  `model` varchar(50) NOT NULL,
  `color` varchar(50) NOT NULL,
  `energy` varchar(50) NOT NULL,
  `registration` varchar(11) NOT NULL,
  `date_first_registration` date NOT NULL,
  `id_user` int(11) NOT NULL,
  FOREIGN KEY (`id_user`) REFERENCES `users` (`id`)
);

INSERT INTO `cars` (`id`, `brand`, `model`, `color`, `energy`, `registration`, `date_first_registration`, `id_user`) VALUES
(3, 'peugeot', '206', 'noir', 'diesel', 'de 456 ef', '2019-05-02', 2),
(5, 'toyota', 'prius', 'grise', 'hybride', 'GH-789HI', '2024-01-19', 3),
(6, 'Audi', 'A3', 'verte', 'essence', 'IJ321JK', '2018-10-18', 7),
(9, 'renault', 'zoé', 'turquoise', 'éléctrique', 'AB-123-BC', '2023-07-10', 1),
(11, 'citroen', 'c3', 'jaune', 'essence', 'wx-456-vw', '2022-10-26', 10),
(12, 'kia', 'sportage', 'orange', 'hybride', 'kl-574-lm', '2024-12-12', 14),
(13, 'Honda ', 'civic', 'rose', 'éléctrique', 'RO-258-SE', '2040-02-01', 17);

CREATE TABLE `journeys` (
  `id` int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `place_departure` varchar(50) NOT NULL,
  `place_arrival` varchar(50) NOT NULL,
  `departure_time` time NOT NULL,
  `arrival_time` time NOT NULL,
  `total_seats` int(1) NOT NULL,
  `price` int(11) NOT NULL,
  `date` date NOT NULL,
  `status` enum('pending','ongoing','completed','complet') NOT NULL DEFAULT 'pending',
  `user_id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  FOREIGN KEY (`car_id`) REFERENCES `cars` (`id`)
);

INSERT INTO `journeys` (`id`, `place_departure`, `place_arrival`, `departure_time`, `arrival_time`, `price`, `user_id`, `car_id`, `date`, `status`, `total_seats`) VALUES
(3, 'paris', 'montpellier', '10:00:00', '17:00:00', 10, 1, 9, '2025-07-17', 'completed', 2),
(4, 'Paris', 'Montpellier', '08:30:00', '16:00:00', 8, 2, 3, '2025-07-17', 'ongoing', 1),
(7, 'paris', 'montpellier', '10:00:00', '17:00:00', 10, 7, 6, '2025-07-17', 'pending', 2),
(8, 'Paris', 'Montpellier', '09:00:00', '17:00:00', 12, 3, 5, '2025-07-17', 'pending', 3),
(10, 'paris', 'lyon', '09:30:00', '14:30:00', 15, 10, 11, '2025-03-06', 'pending', 3),
(17, 'bordeaux', 'lyon', '09:00:00', '16:00:00', 10, 14, 12, '2025-06-25', 'completed', 2),
(18, 'paris', 'montpellier', '08:00:00', '15:00:00', 5, 14, 12, '2025-09-22', 'completed', 2),
(19, 'Montpellier', 'Paris', '08:45:00', '15:00:00', 8, 1, 13, '2025-10-22', 'completed', 2),
(22, 'Marseille', 'Lille', '12:00:00', '22:30:00', 10, 14, 12, '2025-12-24', 'ongoing', 1),
(28, 'Lille', 'Lyon', '08:00:00', '18:00:00', 5, 17, 13, '2025-12-20', 'completed', 2),
(29, 'Montpellier', 'Marseille', '15:00:00', '18:00:00', 5, 17, 13, '2025-12-05', 'completed', 2),
(30, 'Millau', 'Rodez', '20:30:00', '22:30:00', 5, 2, 12, '2025-08-29', 'completed', 2),
(31, 'Rodez', 'Millau', '20:30:00', '22:30:00', 5, 17, 13, '2025-11-06', 'completed', 2),
(32, 'Lille', 'Roubaix', '15:40:00', '17:40:00', 4, 14, 12, '2025-11-25', 'completed', 2),
(33, 'Bordeaux', 'Toulouse', '15:00:00', '18:00:00', 5, 3, 12, '2025-10-14', 'completed', 1),
(34, 'Montpellier', 'Beziers', '20:00:00', '21:00:00', 5, 17, 13, '2025-08-29', 'completed', 2),
(35, 'Montpellier', 'Sète', '15:30:00', '16:15:00', 4, 17, 13, '2026-02-19', 'ongoing', 2),
(36, 'Beziers', 'Sete', '12:00:00', '13:00:00', 4, 14, 12, '2025-12-10', 'ongoing', 3),
(38, 'Paris', 'Brest', '10:00:00', '14:00:00', 5, 17, 13, '2025-09-14', 'completed', 2),
(39, 'Montpellier', 'Nimes', '10:00:00', '11:00:00', 4, 17, 13, '2026-02-25', 'pending', 2);

CREATE TABLE `commissions` (
  `id` int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `amount` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `journey_id` int(11) NOT NULL,
  `driver_id` int(11) NOT NULL,
  FOREIGN KEY (`journey_id`) REFERENCES `journeys` (`id`),
  FOREIGN KEY (`driver_id`) REFERENCES `users` (`id`)
);

INSERT INTO `commissions` (`id`, `journey_id`, `amount`, `driver_id`, `created_at`) VALUES
(1, 31, 2, 17, '2025-08-08 18:38:32'),
(2, 32, 2, 14, '2025-08-08 18:55:14'),
(3, 33, 2, 14, '2025-08-08 19:02:45'),
(4, 34, 2, 17, '2025-08-08 19:14:51'),
(5, 22, 2, 14, '2025-09-14 10:03:50'),
(6, 38, 2, 17, '2025-09-14 10:26:02'),
(7, 17, 2, 14, '2025-09-20 09:25:13'),
(8, 19, 2, 1, '2025-09-20 09:41:30'),
(9, 30, 2, 2, '2025-09-20 20:05:30');

CREATE TABLE `credits` (
  `id` int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `credit` int(5) NOT NULL,
  `user_id` int(11) NOT NULL,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
);

INSERT INTO `credits` (`id`, `credit`, `user_id`) VALUES
(1, 34, 1),
(2, 28, 2),
(3, 20, 3),
(4, 20, 7),
(5, 20, 10),
(6, 23, 11),
(7, 20, 13),
(8, 57, 14),
(10, 5, 16),
(11, 50, 17);

CREATE TABLE `driver_preferences` (
  `id` int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `pets` tinyint(1) NOT NULL,
  `smoking` tinyint(1) NOT NULL,
  `others` text DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
);

INSERT INTO `driver_preferences` (`id`, `pets`, `smoking`, `others`, `user_id`) VALUES
(1, 1, 0, 'Je préfère voyager avec des personnes calmes', 1),
(2, 0, 1, 'Je suis d''accord pour faire des poses pour les fumeurs', 2),
(3, 1, 1, 'Jaime bien faire la conversation pendant le trajet', 3),
(4, 0, 0, 'J''aime conduire en écoutant de la musique country, si vous n''aimez pas cela, Pensez à vos écouteurs', 7),
(9, 1, 0, '', 10),
(10, 1, 1, 'Accepte seulement les petits animaux.', 14);

CREATE TABLE `reservations` (
  `id` int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `status` enum('upcoming','ongoing','completed','problem_reported') NOT NULL DEFAULT 'upcoming',
  `user_id` int(11) NOT NULL,
  `journey_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
   FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
   FOREIGN KEY (`journey_id`) REFERENCES `journeys` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
   FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
);

INSERT INTO `reservations` (`id`, `user_id`, `journey_id`, `role_id`, `status`) VALUES
(39, 11, 4, 3, 'ongoing'),
(57, 17, 17, 3, 'ongoing'),
(58, 16, 17, 3, 'completed'),
(79, 14, 19, 6, 'completed'),
(80, 16, 19, 3, 'completed'),
(81, 14, 28, 6, 'completed'),
(82, 16, 28, 3, 'completed'),
(83, 16, 29, 3, 'completed'),
(84, 14, 29, 6, 'completed'),
(85, 11, 30, 6, 'completed'),
(86, 16, 30, 3, 'completed'),
(87, 16, 31, 3, 'completed'),
(88, 14, 31, 6, 'completed'),
(89, 17, 32, 6, 'completed'),
(90, 13, 33, 6, 'completed'),
(91, 14, 34, 6, 'completed'),
(92, 14, 35, 6, 'problem_reported'),
(94, 17, 22, 6, 'completed'),
(95, 14, 38, 6, 'completed');

CREATE TABLE `problems` (
    `id` INT(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    `descriptive` TEXT NOT NULL,
    `created_at` datetime NOT NULL DEFAULT current_timestamp(),
    `status` enum('to do', 'in progress', 'completed') NOT NULL DEFAULT 'to do',
    `id_journey` INT NOT NULL,
    `id_user_reporter` INT NOT NULL,
    FOREIGN KEY (id_journey) REFERENCES journeys(id),
    FOREIGN KEY (id_user_reporter) REFERENCES users(id)
);

INSERT INTO `problems` (`id`, `descriptive`, `created_at`, `status`, `id_journey`, `id_user_reporter`) VALUES
(1, 'prob', '2025-08-17 15:41:58', 'completed', 17, 17);