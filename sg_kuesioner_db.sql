-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 13, 2019 at 03:05 AM
-- Server version: 10.1.34-MariaDB
-- PHP Version: 7.2.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sg_kuesioner_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `CategoryID` int(11) NOT NULL,
  `CategoryName` varchar(15) DEFAULT NULL,
  `Description` varchar(255) DEFAULT NULL,
  `Picture` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`CategoryID`, `CategoryName`, `Description`, `Picture`) VALUES
(1, 'Beverages m', 'Soft drinks, coffees, teas, beers, and ales,Ciu', NULL),
(2, 'Condiments', 'Sweet and savory sauces, relishes, spreads, and seasonings', ''),
(3, 'Confections', 'Desserts, candies, and sweet breads', ''),
(4, 'Dairy Productio', 'Cheeses', ''),
(5, 'Grains and Cere', 'Breads, crackers, pasta, and cerealist', ''),
(6, 'Web Dinamis', 'Siswa El rahma', '737Image4484.jpg'),
(8, 'UnCategory v', 'No One Category', '284Image4476.jpg'),
(9, 'hvhvh', 'vhvh gvhvhvh', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `id` mediumint(8) UNSIGNED NOT NULL,
  `name` varchar(20) NOT NULL,
  `description` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `name`, `description`) VALUES
(1, 'admin', 'Administrator'),
(2, 'members', 'General User');

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

CREATE TABLE `login_attempts` (
  `id` int(11) UNSIGNED NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `login` varchar(100) NOT NULL,
  `time` int(11) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `ProductID` int(11) NOT NULL,
  `ProductName` varchar(40) DEFAULT NULL,
  `SupplierID` int(11) DEFAULT NULL,
  `CategoryID` int(11) DEFAULT NULL,
  `QuantityPerUnit` varchar(20) DEFAULT NULL,
  `UnitPrice` float(1,0) DEFAULT '0',
  `UnitsInStock` smallint(6) DEFAULT '0',
  `UnitsOnOrder` smallint(6) DEFAULT '0',
  `ReorderLevel` smallint(6) DEFAULT '0',
  `Discontinued` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`ProductID`, `ProductName`, `SupplierID`, `CategoryID`, `QuantityPerUnit`, `UnitPrice`, `UnitsInStock`, `UnitsOnOrder`, `ReorderLevel`, `Discontinued`) VALUES
(1, 'Chai', 1, 1, '10 boxes x 20 bags', 9, 39, 0, 10, 0),
(2, 'Chang', 1, 1, '24 - 12 oz bottles', 9, 17, 40, 25, 0),
(3, 'Aniseed Syrup', 1, 2, '12 - 550 ml bottles', 9, 13, 70, 25, 0),
(4, 'Chef Anton\'s Cajun Seasoning', 2, 2, '48 - 6 oz jars', 9, 53, 0, 0, 0),
(5, 'Chef Anton\'s Gumbo Mix', 2, 2, '36 boxes', 0, 0, 0, 0, 1),
(6, 'Grandma\'s Boysenberry Spread', 3, 2, '12 - 8 oz jars', 9, 120, 0, 25, 0),
(7, 'Uncle Bob\'s Organic Dried Pears', 3, 7, '12 - 1 lb pkgs.', 9, 15, 0, 10, 0),
(8, 'Northwoods Cranberry Sauce', 3, 2, '12 - 12 oz jars', 9, 6, 0, 0, 0),
(9, 'Mishi Kobe Niku', 4, 6, '18 - 500 g pkgs.', 9, 29, 0, 0, 1),
(10, 'Ikura', 4, 8, '12 - 200 ml jars', 9, 31, 0, 0, 0),
(11, 'Queso Cabrales', 5, 4, '1 kg pkg.', 9, 22, 30, 30, 0),
(12, 'Queso Manchego La Pastora', 5, 4, '10 - 500 g pkgs.', 9, 86, 0, 0, 0),
(13, 'Konbu', 6, 8, '2 kg box', 9, 24, 0, 5, 0),
(14, 'Tofu', 6, 7, '40 - 100 g pkgs.', 9, 35, 0, 0, 0),
(15, 'Genen Shouyu', 6, 2, '24 - 250 ml bottles', 9, 39, 0, 5, 0),
(16, 'Pavlova', 7, 3, '32 - 500 g boxes', 9, 29, 0, 10, 0),
(17, 'Alice Mutton', 7, 6, '20 - 1 kg tins', 0, 0, 0, 0, 1),
(18, 'Carnarvon Tigers', 7, 8, '16 kg pkg.', 9, 42, 0, 0, 0),
(19, 'Teatime Chocolate Biscuits', 8, 3, '10 boxes x 12 pieces', 9, 25, 0, 5, 0),
(20, 'Sir Rodney\'s Marmalade', 8, 3, '30 gift boxes', 9, 40, 0, 0, 0),
(21, 'Sir Rodney\'s Scones', 8, 3, '24 pkgs. x 4 pieces', 9, 3, 40, 5, 0),
(22, 'Gustaf\'s Kn?ckebr?d', 9, 5, '24 - 500 g pkgs.', 9, 104, 0, 25, 0),
(23, 'Tunnbr?d', 9, 5, '12 - 250 g pkgs.', 9, 61, 0, 25, 0),
(24, 'Guaran? Fant?stica', 10, 1, '12 - 355 ml cans', 9, 20, 0, 0, 1),
(25, 'NuNuCa Nu?-Nougat-Creme', 11, 3, '20 - 450 g glasses', 9, 76, 0, 30, 0),
(26, 'Gumb?r Gummib?rchen', 11, 3, '100 - 250 g bags', 9, 15, 0, 0, 0),
(27, 'Schoggi Schokolade', 11, 3, '100 - 100 g pieces', 9, 49, 0, 30, 0),
(28, 'R?ssle Sauerkraut', 12, 7, '25 - 825 g cans', 9, 26, 0, 0, 1),
(29, 'Th?ringer Rostbratwurst', 12, 6, '50 bags x 30 sausgs.', 0, 0, 0, 0, 1),
(30, 'Nord-Ost Matjeshering', 13, 8, '10 - 200 g glasses', 9, 10, 0, 15, 0),
(31, 'Gorgonzola Telino', 14, 4, '12 - 100 g pkgs', 0, 0, 70, 20, 0),
(32, 'Mascarpone Fabioli', 14, 4, '24 - 200 g pkgs.', 9, 9, 40, 25, 0),
(33, 'Geitost', 15, 4, '500 g', 9, 112, 0, 20, 0),
(34, 'Sasquatch Ale', 16, 1, '24 - 12 oz bottles', 9, 111, 0, 15, 0),
(35, 'Steeleye Stout', 16, 1, '24 - 12 oz bottles', 9, 20, 0, 15, 0),
(36, 'Inlagd Sill', 17, 8, '24 - 250 g  jars', 9, 112, 0, 20, 0),
(37, 'Gravad lax', 17, 8, '12 - 500 g pkgs.', 9, 11, 50, 25, 0),
(38, 'C?te de Blaye', 18, 1, '12 - 75 cl bottles', 9, 17, 0, 15, 0),
(39, 'Chartreuse verte', 18, 1, '750 cc per bottle', 9, 69, 0, 5, 0),
(40, 'Boston Crab Meat', 19, 8, '24 - 4 oz tins', 9, 123, 0, 30, 0),
(41, 'Jack\'s New England Clam Chowder', 19, 8, '12 - 12 oz cans', 9, 85, 0, 10, 0),
(42, 'Singaporean Hokkien Fried Mee', 20, 5, '32 - 1 kg pkgs.', 9, 26, 0, 0, 1),
(43, 'Ipoh Coffee', 20, 1, '16 - 500 g tins', 9, 17, 10, 25, 0),
(44, 'Gula Malacca', 20, 2, '20 - 2 kg bags', 9, 27, 0, 15, 0),
(45, 'R?gede sild', 21, 8, '1k pkg.', 9, 5, 70, 15, 0),
(46, 'Spegesild', 21, 8, '4 - 450 g glasses', 9, 95, 0, 0, 0),
(47, 'Zaanse koeken', 22, 3, '10 - 4 oz boxes', 9, 36, 0, 0, 0),
(48, 'Chocolade', 22, 3, '10 pkgs.', 9, 15, 70, 25, 0),
(49, 'Maxilaku', 23, 3, '24 - 50 g pkgs.', 9, 10, 60, 15, 0),
(50, 'Valkoinen suklaa', 23, 3, '12 - 100 g bars', 9, 65, 0, 30, 0),
(51, 'Manjimup Dried Apples', 24, 7, '50 - 300 g pkgs.', 9, 20, 0, 10, 0),
(52, 'Filo Mix', 24, 5, '16 - 2 kg boxes', 9, 38, 0, 25, 0),
(53, 'Perth Pasties', 24, 6, '48 pieces', 0, 0, 0, 0, 1),
(54, 'Tourti?re', 25, 6, '16 pies', 9, 21, 0, 10, 0),
(55, 'P?t? chinois', 25, 6, '24 boxes x 2 pies', 9, 115, 0, 20, 0),
(56, 'Gnocchi di nonna Alice', 26, 5, '24 - 250 g pkgs.', 9, 21, 10, 30, 0),
(57, 'Ravioli Angelo', 26, 5, '24 - 250 g pkgs.', 9, 36, 0, 20, 0),
(58, 'Escargots de Bourgogne', 27, 8, '24 pieces', 9, 62, 0, 20, 0),
(59, 'Raclette Courdavault', 28, 4, '5 kg pkg.', 9, 79, 0, 0, 0),
(60, 'Camembert Pierrot', 28, 4, '15 - 300 g rounds', 9, 19, 0, 0, 0),
(61, 'Sirop d\'?rable', 29, 2, '24 - 500 ml bottles', 9, 113, 0, 25, 0),
(62, 'Tarte au sucre', 29, 3, '48 pies', 9, 17, 0, 0, 0),
(63, 'Vegie-spread', 7, 2, '15 - 625 g jars', 9, 24, 0, 5, 0),
(64, 'Wimmers gute Semmelkn?del', 12, 5, '20 bags x 4 pieces', 9, 22, 80, 30, 0),
(65, 'Louisiana Fiery Hot Pepper Sauce', 2, 2, '32 - 8 oz bottles', 9, 76, 0, 0, 0),
(66, 'Louisiana Hot Spiced Okra', 2, 2, '24 - 8 oz jars', 9, 4, 100, 20, 0),
(67, 'Laughing Lumberjack Lager', 16, 1, '24 - 12 oz bottles', 9, 52, 0, 10, 0),
(68, 'Scottish Longbreads', 8, 3, '10 boxes x 8 pieces', 9, 6, 10, 15, 0),
(69, 'Gudbrandsdalsost', 15, 4, '10 kg pkg.', 9, 26, 0, 15, 0),
(70, 'Outback Lager', 7, 1, '24 - 355 ml bottles', 9, 15, 10, 30, 0),
(71, 'Fl?temysost', 15, 4, '10 - 500 g pkgs.', 9, 26, 0, 0, 0),
(72, 'Mozzarella di Giovanni', 14, 4, '24 - 200 g pkgs.', 9, 14, 0, 0, 0),
(73, 'R?d Kaviar', 17, 8, '24 - 150 g jars', 9, 101, 0, 5, 0),
(74, 'Longlife Tofu', 4, 7, '5 kg pkg.', 9, 4, 20, 5, 0),
(75, 'Rh?nbr?u Klosterbier', 12, 1, '24 - 0.5 l bottles', 9, 125, 0, 25, 0),
(76, 'Lakkalik??ri', 23, 1, '500 ml', 9, 57, 0, 20, 0),
(77, 'Original Frankfurter gr?ne So?e', 12, 2, '12 boxes', 9, 32, 0, 15, 0),
(78, 'Mie Sedap Dobel', 15, 3, '10 boxes x 20 bags', 8, 1000, 1, 5, 10),
(79, 'Pepsodent', 4, 8, '100 ml', 6, 12, 12, 10, 10);

-- --------------------------------------------------------

--
-- Table structure for table `programstudi`
--

CREATE TABLE `programstudi` (
  `id` int(11) NOT NULL,
  `nama_prodi` varchar(50) NOT NULL,
  `jenjang` enum('D3','S1','S2','S3','Profesi') NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `status` bit(1) NOT NULL DEFAULT b'0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `programstudi`
--

INSERT INTO `programstudi` (`id`, `nama_prodi`, `jenjang`, `user_id`, `created_at`, `updated_at`, `status`) VALUES
(13201, 'Kesehatan Masyarakat', 'S1', 1, '2015-09-06 10:08:02', '2015-09-06 10:08:02', b'1'),
(14001, 'Ilmu Keperawatan', 'S1', 1, '2015-09-06 10:08:22', '2015-09-06 10:08:28', b'1'),
(14901, 'Profesi Ners', 'Profesi', 1, '2015-09-06 09:57:19', '2015-09-22 21:36:34', b'1'),
(48401, 'Farmasi', 'D3', 1, '2015-09-06 10:05:02', '2018-04-11 20:51:54', b'1');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `SupplierID` int(11) NOT NULL,
  `CompanyName` varchar(40) DEFAULT NULL,
  `ContactName` varchar(30) DEFAULT NULL,
  `ContactTitle` varchar(30) DEFAULT NULL,
  `Address` varchar(60) DEFAULT NULL,
  `City` varchar(15) DEFAULT NULL,
  `Region` varchar(15) DEFAULT NULL,
  `PostalCode` varchar(10) DEFAULT NULL,
  `Country` varchar(15) DEFAULT NULL,
  `Phone` varchar(24) DEFAULT NULL,
  `Fax` varchar(24) DEFAULT NULL,
  `HomePage` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`SupplierID`, `CompanyName`, `ContactName`, `ContactTitle`, `Address`, `City`, `Region`, `PostalCode`, `Country`, `Phone`, `Fax`, `HomePage`) VALUES
(1, 'Exotic Liquids', 'Charlotte Cooper', 'Purchasing Manager', '49 Gilbert St.', 'London', 'London', 'EC1 4SD', 'United Kingdom', '(171) 555-2222', '085647541087', 'https://exotic-liquids.com'),
(2, 'New Orleans Cajun Delights', 'Shelley Burke', 'Order Administrator', 'P.O. Box 78934', 'New Orleans', 'LA', '70117', 'United States', '(100) 555-4822', '', '#CAJUN.HTM#'),
(3, 'Grandma Kelly\'s Homestead', 'Regina Murphy', 'Sales Representative', '707 Oxford Rd.', 'Ann Arbor', 'MI', '48104', 'United States', '(313) 555-5735', '(313) 555-3349', ''),
(4, 'Tokyo Traders', 'Yoshi Nagase', 'Marketing Manager', '9-8 Sekimai\r\nMUnited Statesshino-shi', 'Tokyo', '', '100', 'Japan', '(03) 3555-5011', '', ''),
(5, 'Cooperativa de Quesos \'Las Cabras\'', 'Antonio del Valle Saavedra', 'Export Administrator', 'Calle del Rosal 4', 'Oviedo', 'Asturias', '33007', 'Spain', '(98) 598 76 54', '', ''),
(6, 'Mayumi\'s', 'Mayumi Ohno', 'Marketing Representative', '92 Setsuko\r\nChuo-ku', 'Osaka', '', '545', 'Japan', '(06) 431-7877', '', 'Mayumi\'s (on the World Wide Web)#http://www.microsoft.com/accessdev/sampleapps/mayumi.htm#'),
(7, 'Pavlova, Ltd.', 'Ian Devling', 'Marketing Manager', '74 Rose St.\r\nMoonie Ponds', 'Melbourne', 'Victoria', '3058', 'Australia', '(03) 444-2343', '(03) 444-6588', ''),
(8, 'Specialty Biscuits, Ltd.', 'Peter Wilson', 'Sales Representative', '29 King\'s Way', 'Manchester', '', 'M14 GSD', 'United Kingdom', '(161) 555-4448', '', ''),
(9, 'PB Kn?ckebr?d AB', 'Lars Peterson', 'Sales Agent', 'Kaloadagatan 13', 'G?teborg', '', 'S-345 67', 'Sweden', '031-987 65 43', '031-987 65 91', ''),
(10, 'Refrescos Americanas LTDA', 'Carlos Diaz', 'Marketing Manager', 'Av. das Americanas 12.890', 'S?o Paulo', '', '5442', 'Brazil', '(11) 555 4640', '', ''),
(11, 'Heli S??waren GmbH & Co. KG', 'Petra Winkler', 'Sales Manager', 'Tiergartenstra?e 5', 'Berlin', '', '10785', 'Germany', '(010) 9984510', '', ''),
(12, 'Plutzer Lebensmittelgro?m?rkte AG', 'Martin Bein', 'International Marketing Mgr.', 'Bogenallee 51', 'Frankfurt', '', '60439', 'Germany', '(069) 992755', '', 'Plutzer (on the World Wide Web)#http://www.microsoft.com/accessdev/sampleapps/plutzer.htm#'),
(13, 'Nord-Ost-Fisch Handelsgesellschaft mbH', 'Sven Petersen', 'Coordinator Foreign Markets', 'Frahmredder 112a', 'Cuxhaven', '', '27478', 'Germany', '(04721) 8713', '(04721) 8714', ''),
(14, 'Formaggi Fortini s.r.l.', 'Elio Rossi', 'Sales Representative', 'Viale Dante, 75', 'Ravenna', '', '48100', 'Italy', '(0544) 60323', '(0544) 60603', '#FORMAGGI.HTM#'),
(15, 'Norske Meierier', 'Beate Vileid', 'Marketing Manager', 'Hatlevegen 5', 'Sandvika', '', '1320', 'Norway', '(0)2-953010', '', ''),
(16, 'Bigfoot Breweries', 'Cheryl Saylor', 'Regional Account Rep.', '3400 - 8th Avenue\r\nSuite 210', 'Bend', 'OR', '97101', 'United States', '(503) 555-9931', '', ''),
(17, 'Svensk Sj?f?da AB', 'Michael Bj?rn', 'Sales Representative', 'Brovallav?gen 231', 'Stockholm', '', 'S-123 45', 'Sweden', '08-123 45 67', '', ''),
(18, 'Aux joyeux eccl?siastiques', 'Guyl?ne Nodier', 'Sales Manager', '203, Rue des Francs-Bourgeois', 'Paris', '', '75004', 'France', '(1) 03.83.00.68', '(1) 03.83.00.62', ''),
(19, 'New England Seafood Cannery', 'Robb Merchant', 'Wholesale Account Agent', 'Order Processing Dept.\r\n2100 Paul Revere Blvd.', 'Boston', 'MA', '02134', 'United States', '(617) 555-3267', '(617) 555-3389', ''),
(20, 'Leka Trading', 'Chandra Leka', 'Owner', '471 Serangoon Loop, Suite #402', 'Singapore', 'Singapore', '0512', 'Singapore', '555-8787', '', ''),
(21, 'Lyngbysild', 'Niels Petersen', 'Sales Manager', 'Lyngbysild\r\nFiskebakken 10', 'Lyngby', '', '2800', 'Denmark', '43844108', '43844115', ''),
(22, 'Zaanse Snoepfabriek', 'Dirk Luchte', 'Accounting Manager', 'Verkoop\r\nRijnweg 22', 'Zaandam', '', '9999 ZZ', 'Netherlands', '(12345) 1212', '(12345) 1210', ''),
(23, 'Karkki Oy', 'Anne Heikkonen', 'Product Manager', 'Valtakatu 12', 'Lappeenranta', '', '53120', 'Finland', '(953) 10956', '', ''),
(24, 'G\'day, Mate', 'Wendy Mackenzie', 'Sales Representative', '170 Prince Edward Parade\r\nHunter\'s Hill', 'Sydney', 'NSW', '2042', 'Australia', '(02) 555-5914', '(02) 555-4873', 'G\'day Mate (on the World Wide Web)#http://www.microsoft.com/accessdev/sampleapps/gdaymate.htm#'),
(25, 'Ma Maison', 'Jean-Guy Lauzon', 'Marketing Manager', '2960 Rue St. Laurent', 'Montr?al', 'Qu?bec', 'H1J 1C3', 'Canada', '(514) 555-9022', '', ''),
(26, 'Pasta Buttini s.r.l.', 'Giovanni Giudici', 'Order Administrator', 'Via dei Gelsomini, 153', 'Salerno', '', '84100', 'Italy', '(089) 6547665', '(089) 6547667', ''),
(27, 'Escargots Nouveaux', 'Marie Delamare', 'Sales Manager', '22, rue H. Voiron', 'Montceau', '', '71300', 'France', '85.57.00.07', '', ''),
(28, 'Gai p?turage', 'Eliane Noz', 'Sales Representative', 'Bat. B\r\n3, rue des Alpes', 'Annecy', '', '74000', 'France', '38.76.98.06', '38.76.98.58', ''),
(29, 'For?ts d\'?rables', 'Chantal Goulet', 'Accounting Manager', '148 rue Chasseur', 'Ste-Hyacinthe', 'Qu?bec', 'J2S 7S8', 'Canada', '(514) 555-2955', '(514) 555-2921', '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `salt` varchar(255) DEFAULT NULL,
  `email` varchar(254) NOT NULL,
  `activation_code` varchar(40) DEFAULT NULL,
  `forgotten_password_code` varchar(40) DEFAULT NULL,
  `forgotten_password_time` int(11) UNSIGNED DEFAULT NULL,
  `remember_code` varchar(40) DEFAULT NULL,
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `last_login` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `active` tinyint(1) UNSIGNED DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `ip_address`, `username`, `password`, `salt`, `email`, `activation_code`, `forgotten_password_code`, `forgotten_password_time`, `remember_code`, `created_on`, `last_login`, `active`, `first_name`, `last_name`, `company`, `phone`) VALUES
(1, '127.0.0.1', 'administrator', '$2a$07$SeBknntpZror9uyftVopmu61qg0ms8Qv1yV6FG.kQOSM.9QhmTo36', '', 'admin@admin.com', '', NULL, NULL, '9i8C6Rc52DH3JcsoVaIpsu', '2019-12-13 08:40:01', '2019-12-13 02:40:01', 1, 'Admin', 'istrator', 'ADMIN', '0');

-- --------------------------------------------------------

--
-- Table structure for table `users_groups`
--

CREATE TABLE `users_groups` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `group_id` mediumint(8) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users_groups`
--

INSERT INTO `users_groups` (`id`, `user_id`, `group_id`) VALUES
(1, 1, 1),
(2, 1, 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`CategoryID`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`ProductID`);

--
-- Indexes for table `programstudi`
--
ALTER TABLE `programstudi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`SupplierID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_groups`
--
ALTER TABLE `users_groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uc_users_groups` (`user_id`,`group_id`),
  ADD KEY `fk_users_groups_users1_idx` (`user_id`),
  ADD KEY `fk_users_groups_groups1_idx` (`group_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `CategoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `ProductID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `SupplierID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users_groups`
--
ALTER TABLE `users_groups`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `users_groups`
--
ALTER TABLE `users_groups`
  ADD CONSTRAINT `fk_users_groups_groups1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_users_groups_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
