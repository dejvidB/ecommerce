SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE DATABASE IF NOT EXISTS `ecommerce` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `ecommerce`;

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `ordering` int(11) NOT NULL,
  `alias` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `categories` (`category_id`, `name`, `description`, `ordering`, `alias`) VALUES
(1, 'Computers & Laptops', 'Shop laptops, desktops, monitors, tablets, PC gaming, hard drives and storage, accessories and more', 1, 'computers'),
(2, 'Smartphones & Accessories', 'Shop smartphones, cases, accessories and more', 2, 'smartphones-and-accessories');

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `total` float NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `state` text NOT NULL DEFAULT 'New'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `order_info` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `mail` text NOT NULL,
  `name` text NOT NULL,
  `lastname` text NOT NULL,
  `country` text NOT NULL,
  `region` text NOT NULL,
  `city` text NOT NULL,
  `address` text NOT NULL,
  `zip` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `order_products` (
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `price` float NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `title` text NOT NULL,
  `alias` text NOT NULL,
  `old_price` float DEFAULT NULL,
  `price` float NOT NULL,
  `sub_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `products` (`id`, `title`, `alias`, `old_price`, `price`, `sub_id`) VALUES
(1, 'Laptop Quest Slimbook Plus 14.1\" (x5Z8350/4GB/32GB/HD) Grey', 'laptop-quest-slimbook-plus', NULL, 199, 1),
(2, 'Laptop Dell XPS 9250 12.5\" (m56Y57/8GB/256GB/ HD) ', 'dell-xps-9250-8GB-256GB', 2499.99, 1499.99, 1),
(3, 'Laptop Quest Slimbook 360 Convertible - 13.3\" (Celeron N3350/4GB/32GB/HD)', 'quest-slimbook-360-convertible-4gb-32gb', 349, 249, 1),
(4, 'Laptop Dell XPS 9560 15.6\" UHD Touch (i7-7700HQ/16GB/512GB/GTX 1050)', 'dell-xps-9560-16gb-512gb', NULL, 1999, 1),
(5, 'Samsung Galaxy A10 32GB Smartphone Black', 'samsung-galaxy-a10-32gb-smartphone', NULL, 169, 2),
(6, 'WhatAthens Iphone 7 Case Blue Map', 'whatathens-iphone-7-case-blue-map', NULL, 2.49, 3),
(7, 'Universal 4.5\" - Puro Bi-Color Wallet Case Black/Grey', 'puro-bi-color-wallet-case-black', NULL, 14.99, 3);

CREATE TABLE `ratings` (
  `review_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `text` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `sub_categories` (
  `sub_id` int(11) NOT NULL,
  `name` text NOT NULL,
  `alias` text NOT NULL,
  `cat_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `sub_categories` (`sub_id`, `name`, `alias`, `cat_id`) VALUES
(1, 'Laptops', 'laptops', 1),
(2, 'Smartphones', 'smartphones', 2),
(3, 'Accessories', 'accessories', 2);

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `mail` text NOT NULL,
  `password` char(128) NOT NULL,
  `name` text NOT NULL,
  `lastname` text NOT NULL,
  `country` text NOT NULL,
  `region` text NOT NULL,
  `city` text NOT NULL,
  `address` text NOT NULL,
  `zip` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `categories`
  ADD UNIQUE KEY `category_id` (`category_id`);

ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `ratings`
  ADD PRIMARY KEY (`review_id`),
  ADD UNIQUE KEY `product_id` (`product_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

ALTER TABLE `sub_categories`
  ADD PRIMARY KEY (`sub_id`);

ALTER TABLE `users`
  ADD UNIQUE KEY `id` (`id`);


ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

ALTER TABLE `ratings`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `sub_categories`
  MODIFY `sub_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;


ALTER TABLE `ratings`
  ADD CONSTRAINT `ratings_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `ratings_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
