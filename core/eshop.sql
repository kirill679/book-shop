SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DELIMITER ;;

DROP PROCEDURE IF EXISTS `spAddItemToCatalog`;;
CREATE PROCEDURE `spAddItemToCatalog`(IN `title` varchar(255), IN `author` varchar(255), IN `price` int unsigned, IN `pub_year` int unsigned)
INSERT INTO `catalog` (`title`, `author`, `price`, `pub_year`)
VALUES (title, author, price, pub_year);;

DROP PROCEDURE IF EXISTS `spGetAdmin`;;
CREATE PROCEDURE `spGetAdmin`(IN `u_login` varchar(255))
SELECT `id`, `login`, `password`, `email`, `created`
FROM `admins`
WHERE `login` = u_login;;

DROP PROCEDURE IF EXISTS `spGetCatalog`;;
CREATE PROCEDURE `spGetCatalog`()
SELECT `id`, `title`, `author`, `pub_year`, `price`
FROM `catalog`;;

DROP PROCEDURE IF EXISTS `spGetItemById`;;
CREATE PROCEDURE `spGetItemById`(IN `item_id` int unsigned)
SELECT `id`, `title`, `author`, `price`, `pub_year`
FROM `catalog`
WHERE `id` = item_id
LIMIT 1;;

DROP PROCEDURE IF EXISTS `spGetItemsForBasket`;;
CREATE PROCEDURE `spGetItemsForBasket`(IN `ids` varchar(255))
SELECT `id`, `title`, `author`, `pub_year`, `price`
FROM `catalog`
WHERE FIND_IN_SET(`id`, ids);;

DROP PROCEDURE IF EXISTS `spGetOrderedItems`;;
CREATE PROCEDURE `spGetOrderedItems`(IN `order_id` varchar(255))
SELECT `c`.`title`, `c`.`author`, `c`.`pub_year`, `c`.`price`, `o`.`quantity`
FROM `catalog` AS `c`
         INNER JOIN `ordered_items` AS `o`
                    ON `c`.`id` = `o`.`item_id`
WHERE `o`.`order_id` = order_id;;

DROP PROCEDURE IF EXISTS `spGetOrders`;;
CREATE PROCEDURE `spGetOrders`()
SELECT `order_id` AS `id`, `customer`, `email`, `phone`, `address`, UNIX_TIMESTAMP(`created`) AS `created`
FROM `orders`;;

DROP PROCEDURE IF EXISTS `spSaveAdmin`;;
CREATE PROCEDURE `spSaveAdmin`(IN `u_login` varchar(255), IN `u_password` varchar(255), IN `email` varchar(255))
INSERT INTO `admins` (`login`, `password`, `email`)
VALUES(u_login, u_password, email);;

DROP PROCEDURE IF EXISTS `spSaveOrder`;;
CREATE PROCEDURE `spSaveOrder`(IN `order_id` varchar(255), IN `customer` varchar(255), IN `email` varchar(255), IN `phone` varchar(255), IN `address` varchar(255))
INSERT INTO `orders` (`order_id`, `customer`, `email`, `phone`, `address`)
VALUES (order_id, customer, email, phone, address);;

DROP PROCEDURE IF EXISTS `spSaveOrderedItems`;;
CREATE PROCEDURE `spSaveOrderedItems`(IN `order_id` varchar(255), IN `item_id` int unsigned, IN `quantity` int unsigned)
INSERT INTO `ordered_items` (`order_id`, `item_id`, `quantity`)
VALUES (order_id, item_id, quantity);;

DELIMITER ;

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `admins`;
CREATE TABLE `admins` (
                          `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                          `login` varchar(255) NOT NULL,
                          `password` varchar(255) NOT NULL,
                          `email` varchar(255) NOT NULL,
                          `created` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
                          PRIMARY KEY (`id`),
                          UNIQUE KEY `login` (`login`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `catalog`;
CREATE TABLE `catalog` (
                           `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                           `title` varchar(255) NOT NULL,
                           `author` varchar(255) NOT NULL,
                           `price` int(11) unsigned NOT NULL,
                           `pub_year` int(11) unsigned NOT NULL,
                           PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
                          `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                          `order_id` varchar(255) NOT NULL,
                          `customer` varchar(255) NOT NULL,
                          `email` varchar(255) NOT NULL,
                          `phone` varchar(255) NOT NULL,
                          `address` varchar(255) NOT NULL,
                          `created` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
                          PRIMARY KEY (`id`),
                          UNIQUE KEY `order_id` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `ordered_items`;
CREATE TABLE `ordered_items` (
                                 `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                                 `order_id` varchar(255) NOT NULL,
                                 `item_id` int(11) unsigned NOT NULL,
                                 `quantity` int(10) unsigned NOT NULL,
                                 PRIMARY KEY (`id`),
                                 KEY `item_id` (`item_id`),
                                 KEY `order_id` (`order_id`),
                                 CONSTRAINT `ordered_items_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `catalog` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
                                 CONSTRAINT `ordered_items_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;