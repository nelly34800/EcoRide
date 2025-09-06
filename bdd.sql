CREATE DATABASE `ecoride`;

CREATE TABLE `roles` (
  `id` int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `role` varchar(50) NOT NULL
);


CREATE TABLE `users` (
  `id` int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(60) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `address` text NOT NULL,
  `role_id` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('active', 'suspended') DEFAULT 'active';
  FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
);


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


CREATE TABLE `journeys` (
  `id` int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `place_departure` varchar(50) NOT NULL,
  `place_arrival` varchar(50) NOT NULL,
  `departure_time` time NOT NULL,
  `arrival_time` time NOT NULL,
  `total_seats` int(1) NOT NULL,
  `price` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `status` enum('pending','ongoing','completed','complet') DEFAULT 'pending',
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  FOREIGN KEY (`car_id`) REFERENCES `cars` (`id`)
);


CREATE TABLE `commissions` (
  `id` int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `journey_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `driver_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  FOREIGN KEY (`journey_id`) REFERENCES `journeys` (`id`),
  FOREIGN KEY (`driver_id`) REFERENCES `users` (`id`)
);


CREATE TABLE `complaints` (
  `id` int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `journey_id` int(11) DEFAULT NULL,
   FOREIGN KEY (`journey_id`) REFERENCES `journeys` (`id`)
);


CREATE TABLE `credits` (
  `id` int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `credit` int(5) NOT NULL,
  `user_id` int(11) NOT NULL,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
);


CREATE TABLE `driver_preferences` (
  `id` int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `pets` tinyint(1) NOT NULL,
  `smoking` tinyint(1) NOT NULL,
  `others` text DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
);


CREATE TABLE `reservations` (
  `id` int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `journey_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `status` enum('upcoming','ongoing','completed','problem_reported') NOT NULL DEFAULT 'upcoming',
   FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
   FOREIGN KEY (`journey_id`) REFERENCES `journeys` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
   FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
);

CREATE TABLE `problems` (
    `id` INT(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    `descriptive` TEXT NOT NULL,
    `created_at` datetime DEFAULT current_timestamp(),
    `status` enum('to do', 'in progress', 'completed') NOT NULL DEFAULT 'to do',
    `id_journey` INT NOT NULL,
    `id_user_reporter` INT NOT NULL,
    FOREIGN KEY (id_journey) REFERENCES journeys(id),
    FOREIGN KEY (id_user_reporter) REFERENCES users(id)
);


