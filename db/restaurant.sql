-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 21, 2024 at 09:16 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `restaurant`
--

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE `address` (
  `addressid` int(11) NOT NULL,
  `house_number` int(11) NOT NULL,
  `postal_code` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `address`
--

INSERT INTO `address` (`addressid`, `house_number`, `postal_code`) VALUES
(6, 123, 'V4S1T4'),
(2, 324, 'V6Z 1W5'),
(5, 675, 'V6K2P3'),
(1, 1234, 'V5Z3A8'),
(4, 4502, 'V5R4V4'),
(3, 9876, 'V5T3E6');

-- --------------------------------------------------------

--
-- Table structure for table `address_details`
--

CREATE TABLE `address_details` (
  `house_number` int(11) NOT NULL,
  `postal_code` varchar(10) NOT NULL,
  `street_number` int(11) DEFAULT NULL,
  `street_name` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `address_details`
--

INSERT INTO `address_details` (`house_number`, `postal_code`, `street_number`, `street_name`) VALUES
(123, 'V4S1T4', 789, 'Avenue'),
(324, 'V6Z 1W5', 8, 'Avenue'),
(675, 'V6K2P3', 4, 'Avenue'),
(1234, 'V5Z3A8', 45, 'Avenue'),
(4502, 'V5R4V4', 33, 'Avenue'),
(9876, 'V5T3E6', 16, 'Avenue');

-- --------------------------------------------------------

--
-- Table structure for table `contain`
--

CREATE TABLE `contain` (
  `menu_itemid` int(11) NOT NULL,
  `orderid` int(11) NOT NULL,
  `menu_item_quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contain`
--

INSERT INTO `contain` (`menu_itemid`, `orderid`, `menu_item_quantity`) VALUES
(1, 1, 2),
(1, 3, 6),
(2, 1, 3),
(2, 2, 3),
(3, 4, 9),
(4, 1, 4),
(5, 5, 7);

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customerid` int(11) NOT NULL,
  `customer_name` varchar(50) DEFAULT NULL,
  `customer_phone_number` varchar(20) NOT NULL,
  `userid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customerid`, `customer_name`, `customer_phone_number`, `userid`) VALUES
(1, 'Emma Johnson', '+14165551024', 1),
(2, 'Liam Brown', '+16045552187', 2),
(3, 'Sophia Davis', '+15145553410', 3),
(4, 'Noah Wilson', '+14035554679', 4),
(5, 'Olivia Martin', '+16135555823', 5);

-- --------------------------------------------------------

--
-- Table structure for table `customer_has_address`
--

CREATE TABLE `customer_has_address` (
  `customerid` int(11) NOT NULL,
  `addressid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer_has_address`
--

INSERT INTO `customer_has_address` (`customerid`, `addressid`) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(5, 5);

-- --------------------------------------------------------

--
-- Table structure for table `delivery`
--

CREATE TABLE `delivery` (
  `deliveryid` int(11) NOT NULL,
  `delivery_datetime` datetime NOT NULL,
  `delivery_number` int(11) NOT NULL,
  `orderid` int(11) NOT NULL,
  `customerid` int(11) DEFAULT NULL,
  `delivery_status` varchar(20) DEFAULT 'Pending',
  `house_number` int(11) DEFAULT NULL,
  `street_number` int(11) DEFAULT NULL,
  `street_name` varchar(20) DEFAULT NULL,
  `postal_code` varchar(10) DEFAULT NULL,
  `special_instructions` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `delivery`
--

INSERT INTO `delivery` (`deliveryid`, `delivery_datetime`, `delivery_number`, `orderid`, `customerid`, `delivery_status`, `house_number`, `street_number`, `street_name`, `postal_code`, `special_instructions`) VALUES
(1, '2024-12-12 08:45:00', 1, 2, 2, 'Pending', 324, 8, 'Avenue', 'V6Z 1W5', NULL),
(2, '2024-12-03 21:05:00', 2, 3, 3, 'Pending', 9876, 16, 'Avenue', 'V5T3E6', NULL),
(3, '2024-11-24 15:20:00', 3, 4, 5, 'Pending', 675, 4, 'Avenue', 'V6K2P3', NULL),
(4, '2024-11-23 11:44:00', 4, 1, 4, 'Pending', 4502, 33, 'Avenue', 'V5R4V4', NULL),
(5, '2024-11-25 13:50:00', 5, 5, 1, 'Pending', 1234, 45, 'Avenue', 'V5Z3A8', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `employeeid` int(11) NOT NULL,
  `employee_address` varchar(50) DEFAULT NULL,
  `employee_email` varchar(50) NOT NULL,
  `employee_name` varchar(30) NOT NULL,
  `employee_phone_number` varchar(20) NOT NULL,
  `roleid` int(11) DEFAULT NULL,
  `userid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`employeeid`, `employee_address`, `employee_email`, `employee_name`, `employee_phone_number`, `roleid`, `userid`) VALUES
(1, '123 Oak St.', 'alice.smith@email.com', 'Alice Smith', '+15559362913', 1, 6),
(2, '456 Pine Ave', 'bob.jones@email.com', 'Bob Jones', '+14162089866', 2, 7),
(3, '789 Maple Dr.', 'carla.johnson@email.com', 'Carla Johnson', '+17807305279', 3, 8),
(4, '101 Birch Ln', 'dave.miller@email.com', 'Dave Miller', '+17806551803', 4, 9),
(5, '202 Cedar Ct', 'eve.lee@email.com', 'Eve Lee', '+14167864081', 2, 10);

-- --------------------------------------------------------

--
-- Table structure for table `employee_manages_inventory`
--

CREATE TABLE `employee_manages_inventory` (
  `employeeid` int(11) NOT NULL,
  `inventoryid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee_manages_inventory`
--

INSERT INTO `employee_manages_inventory` (`employeeid`, `inventoryid`) VALUES
(3, 1),
(3, 2),
(3, 3),
(3, 4),
(3, 5);

-- --------------------------------------------------------

--
-- Table structure for table `encryption_keys`
--

CREATE TABLE `encryption_keys` (
  `id` int(11) NOT NULL,
  `encrypted_key` blob NOT NULL,
  `iv` blob NOT NULL,
  `tag` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `encryption_keys`
--

INSERT INTO `encryption_keys` (`id`, `encrypted_key`, `iv`, `tag`) VALUES
(1, 0x6177484d2b6d454c385769476350767446576d78486232414f49444d6e596c473634655a762f484a2b74303d, 0xde025b430d5c2a374253bcd13644064d, 0x3366663366623963373362393666333136653638643932373061326532313736),
(2, 0x494e62466a6241796b5848632b725a4434465732476e6e415831564d3264394f5462384c33637956434c513d, 0xc36c2456f747523a7bb5f2d537b8f888, 0x3232633439646431393461613564666438613032353435393065616134653030),
(3, 0x4f4a446561514d492f765962516664746d4f654e3072375455324d4d443439327a3255456737634e7976673d, 0xd339f3a898481fb5533cbe9e6cd85424, 0x3264343763336537613730646330616332373564663531333438386462663139),
(4, 0x4558595576704e35586f326f656955564235682b694e645665696641797a5233717365616170616b754c773d, 0x7238dd99dd4f7f9ef61a3f1669edb2dd, 0x3937363638633831396332303033353236386131383561393462376538343831),
(5, 0x52737477552b66416431544c423954556673366165314a7a70486d534a6851615a756870333472393578513d, 0x28435ce0d1bd04ea25e74031497a11e2, 0x3135393333376262623565346264663238633831386637303666303538373936),
(6, 0x70443065785a314b584f49796a6b61737746624e444d655a47487a4468517a306d435433452b59696b33343d, 0x2252bd56642dc34ece54af40ea0889fa, 0x3232663838393963663032333438393033616433383761383462656532306463);

-- --------------------------------------------------------

--
-- Table structure for table `has_payment_information`
--

CREATE TABLE `has_payment_information` (
  `paymentid` int(11) NOT NULL,
  `card_number` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`card_number`)),
  `payment_method` varchar(20) NOT NULL,
  `card_type` varchar(20) NOT NULL,
  `expiry_date` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`expiry_date`)),
  `cvv` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`cvv`)),
  `customerid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `has_payment_information`
--

INSERT INTO `has_payment_information` (`paymentid`, `card_number`, `payment_method`, `card_type`, `expiry_date`, `cvv`, `customerid`) VALUES
(1, '\"{\\\"ciphertext\\\":\\\"gsmN8aWrU6kzXYjSRnhoBQ==\\\",\\\"iv\\\":\\\"tqWulcXKKbRl2+2R\\\",\\\"tag\\\":\\\"N5dXJii1dm5l+lFjG1byXQ==\\\"}\"', 'Credit Card', 'Visa', '\"{\\\"ciphertext\\\":\\\"MJtu6UKVmA==\\\",\\\"iv\\\":\\\"artZcKOHAFMJPRaZ\\\",\\\"tag\\\":\\\"dDrRUIigh6ykdn+t5nTGpA==\\\"}\"', '\"{\\\"ciphertext\\\":\\\"gam+\\\",\\\"iv\\\":\\\"RROC8w5UBw6yrWA\\/\\\",\\\"tag\\\":\\\"mljjhIKcEbxQoG3wNA46wg==\\\"}\"', 1),
(2, '\"{\\\"ciphertext\\\":\\\"wVCqUV3GECpmbcHOZaMqcw==\\\",\\\"iv\\\":\\\"TJN3fA7YRekfThMg\\\",\\\"tag\\\":\\\"bEMiTlJGuXYeyLGvd5xAZg==\\\"}\"', 'Credit Card', 'Mastercard', '\"{\\\"ciphertext\\\":\\\"BpbG7GFzCg==\\\",\\\"iv\\\":\\\"tFG8X1xELhffJcpw\\\",\\\"tag\\\":\\\"CXr+hZMO5H6SrouhGs\\\\\\/dLA==\\\"}\"', '\"{\\\"ciphertext\\\":\\\"L8h5\\\",\\\"iv\\\":\\\"cnGLQG6QdIIrpjyC\\\",\\\"tag\\\":\\\"BhXi\\\\\\/6J3TB+ykMue7wADAA==\\\"}\"', 2),
(3, '\"{\\\"ciphertext\\\":\\\"lnNHXRj3FNfWrWL\\\\\\/zvMlZg==\\\",\\\"iv\\\":\\\"y9znLNu03yPd2Tjs\\\",\\\"tag\\\":\\\"ynl7n02A8qUEHw6lsqCcqQ==\\\"}\"', 'Debit Card', 'Mastercard', '\"{\\\"ciphertext\\\":\\\"ZO0q8awxAg==\\\",\\\"iv\\\":\\\"FPJL9H\\\\\\/P2yrqoEGJ\\\",\\\"tag\\\":\\\"8TXrw8aZlTLmUIXyethuvA==\\\"}\"', '\"{\\\"ciphertext\\\":\\\"Jj0J\\\",\\\"iv\\\":\\\"wmEjLrqid29LFHge\\\",\\\"tag\\\":\\\"gC8XsT7PVouC\\\\\\/JMOr+GVbA==\\\"}\"', 3),
(4, '\"{\\\"ciphertext\\\":\\\"iS9t23N0NJpEeagSHBEFGg==\\\",\\\"iv\\\":\\\"z9GwJTaHPumLESZu\\\",\\\"tag\\\":\\\"ps6RcGxUmunfsr5n09PVFw==\\\"}\"', 'Debit Card', 'Visa', '\"{\\\"ciphertext\\\":\\\"2V+lRh+uQQ==\\\",\\\"iv\\\":\\\"A9xQvITt3nsPyDoK\\\",\\\"tag\\\":\\\"OXoozlidnNZUsV4xKdXEug==\\\"}\"', '\"{\\\"ciphertext\\\":\\\"7oa+\\\",\\\"iv\\\":\\\"AGPBvK4aTcVp6+gX\\\",\\\"tag\\\":\\\"ljLcgON8MKYeZ3lxZ55mmg==\\\"}\"', 4),
(5, '\"{\\\"ciphertext\\\":\\\"ZqHkwhRcYbRnRjflUDL\\\\\\/Hw==\\\",\\\"iv\\\":\\\"6FlDmTRjHUe6GeIS\\\",\\\"tag\\\":\\\"Z56YU0\\\\\\/WmWRbaUSqMB0Sng==\\\"}\"', 'Credit Card', 'Visa', '\"{\\\"ciphertext\\\":\\\"oPXhRbWRpw==\\\",\\\"iv\\\":\\\"k7f1DFPcp4FEtMyL\\\",\\\"tag\\\":\\\"2HD2L3Yp7xZy\\\\\\/2bsJOj+Ug==\\\"}\"', '\"{\\\"ciphertext\\\":\\\"ptO9\\\",\\\"iv\\\":\\\"gqOC4Z1qteYdP5BY\\\",\\\"tag\\\":\\\"KofgheB003qZ13ourZhCAQ==\\\"}\"', 5);

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `inventoryid` int(11) NOT NULL,
  `item_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`inventoryid`, `item_name`) VALUES
(4, 'Butter'),
(5, 'Chicken'),
(2, 'Lobster'),
(1, 'Pork Chop'),
(3, 'Rosemary');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_item`
--

CREATE TABLE `inventory_item` (
  `item_name` varchar(50) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `item_quantity` int(11) NOT NULL DEFAULT 0,
  `unit_of_measurement` varchar(50) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory_item`
--

INSERT INTO `inventory_item` (`item_name`, `unit_price`, `item_quantity`, `unit_of_measurement`, `category`) VALUES
('Butter', 31.50, 5, '2 kg', 'Dairy'),
('Chicken', 96.48, 6, '12 kg', 'Meat'),
('Lobster', 363.40, 16, '9 kg', 'Seafood'),
('Pork Chop', 88.96, 25, '6.8 kg', 'Meat'),
('Rosemary', 4.00, 5, '200 g', 'Produce');

-- --------------------------------------------------------

--
-- Table structure for table `menu_beverage`
--

CREATE TABLE `menu_beverage` (
  `menu_itemid` int(11) NOT NULL,
  `size_options` varchar(20) DEFAULT NULL,
  `alcoholic` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu_beverage`
--

INSERT INTO `menu_beverage` (`menu_itemid`, `size_options`, `alcoholic`) VALUES
(17, NULL, 1),
(18, '1oz, 2oz', 1),
(19, '12oz', 0),
(20, '12oz', 0),
(21, '16oz', 0);

-- --------------------------------------------------------

--
-- Table structure for table `menu_item`
--

CREATE TABLE `menu_item` (
  `menu_itemid` int(11) NOT NULL,
  `menu_item_name` varchar(100) NOT NULL,
  `menu_description` varchar(200) DEFAULT NULL,
  `menu_price` decimal(10,2) NOT NULL,
  `availability` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu_item`
--

INSERT INTO `menu_item` (`menu_itemid`, `menu_item_name`, `menu_description`, `menu_price`, `availability`) VALUES
(1, 'Truffle Mac and Cheese Balls', 'Deep-fried mac and cheese with a rich truffle béchamel sauce.', 25.99, 1),
(2, 'Lobster-Stuffed Jalapeño Poppers', 'Jalapeños stuffed with lobster and cream cheese, wrapped in bacon, with a chipotle aioli.', 61.99, 1),
(3, 'Foie Gras Cornbread Muffins', 'Sweet cornbread muffins with a foie gras butter drizzle.', 49.99, 1),
(4, 'Smoked Wagyu Brisket Bites', 'Small bites of Wagyu beef brisket, smoked to perfection, served with truffle BBQ sauce.', 52.99, 1),
(5, 'BBQ Bone In Tomahawk Steak', 'A massive tomahawk steak, chargrilled with a house-made rub, paired with smoked garlic butter.', 359.99, 1),
(6, 'Lobster & Wagyu Brisket Platter', 'Surf and turf BBQ platter featuring Maine lobster tails and Wagyu brisket, with upscale sides.', 259.99, 1),
(7, 'Dry-Aged Porkchop', 'Thick-cut, dry-aged pork chop with apple cider glaze, served with caramelized shallots', 68.99, 1),
(8, 'Gourmet BBQ Chicken', 'Organic chicken smoked with hickory wood, served with a rosemary honey glaze', 55.99, 1),
(9, '24-Hour Smoked Short Ribs', 'Tender, smoked short ribs, served with a bourbon glaze and gold leaf garnish', 59.99, 1),
(10, 'Truffle Parmesan Fries', 'Hand-cut fries with white truffle oil and shaved Parmesan.', 21.99, 1),
(11, 'Smoked Gouda Mac & Cheese', 'Creamy mac and cheese made with smoked Gouda and aged cheddar.', 31.99, 1),
(12, 'Charred Brussels Sprouts', 'Roasted Brussel sprouts with pancetta and balasmic glaze.', 14.99, 1),
(13, 'Caviar Potato Salad', 'Yukon gold potato salad with crème fraîche, topped with premium caviar', 259.99, 1),
(14, 'BBQ Bourbon Pecan Pie', 'A rich pecan pie with bourbon-infused caramel and smoked sea salt.', 19.99, 1),
(15, 'Maple Bacon Ice Cream Sundae', 'Maple-flavored ice cream with candied bacon, bourbon caramel, and pecan brittle.', 18.99, 1),
(16, 'Smoked Chocolate Lava Cake', 'Molten chocolate cake with a hint of smoked chili, served with vanilla bean ice cream.', 17.99, 1),
(17, 'Craft Whiskey Flight', 'A selection of premium, small-batch whiskeys.', 149.99, 1),
(18, 'Smoked Old Fashioned', 'Classic Old Fashioned cocktail, smoked with hickory wood.', 34.99, 1),
(19, 'Smoked Berry Fizz', 'Smoky blackberry, rosemary, and lemon, topped with sparkling water; refreshing and bold', 11.99, 1),
(20, 'Caviar Lime Spritz', 'Bright elderflower and cucumber with a pop of lime pearls; light, crisp, and sophisticated.', 13.99, 1),
(21, 'Gourmet Lemonade Trio', 'Unique flavors like lavender, rosemary, and hibiscus.', 10.99, 1),
(22, 'Lobster Cornbread', 'Sweet cornbread with chunks of lobster', 44.99, 1);

-- --------------------------------------------------------

--
-- Table structure for table `menu_special`
--

CREATE TABLE `menu_special` (
  `menu_itemid` int(11) NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu_special`
--

INSERT INTO `menu_special` (`menu_itemid`, `start_date`, `end_date`) VALUES
(1, '2025-09-06', '2025-12-29'),
(2, '2025-05-01', '2025-06-30'),
(3, '2025-03-01', '2025-03-16'),
(4, '2025-01-05', '2025-01-31'),
(5, '2025-09-06', '2025-12-29');

-- --------------------------------------------------------

--
-- Table structure for table `order_table`
--

CREATE TABLE `order_table` (
  `orderid` int(11) NOT NULL,
  `order_number` int(11) NOT NULL,
  `order_type` varchar(20) DEFAULT NULL,
  `order_datetime` datetime NOT NULL,
  `employeeid` int(11) NOT NULL,
  `customer_phone_number` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_table`
--

INSERT INTO `order_table` (`orderid`, `order_number`, `order_type`, `order_datetime`, `employeeid`, `customer_phone_number`) VALUES
(1, 1, 'Delivery', '2024-12-12 08:10:00', 4, NULL),
(2, 2, 'Delivery', '2024-12-03 20:30:00', 4, NULL),
(3, 3, 'Delivery', '2024-11-24 14:45:00', 4, NULL),
(4, 4, 'Delivery', '2024-11-23 11:09:00', 4, NULL),
(5, 5, 'Delivery', '2024-11-25 13:15:00', 4, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `order_transaction`
--

CREATE TABLE `order_transaction` (
  `transactionid` int(11) NOT NULL,
  `transaction_number` int(11) NOT NULL,
  `payment_type` varchar(20) DEFAULT NULL,
  `transaction_datetime` datetime NOT NULL,
  `orderid` int(11) NOT NULL,
  `card_number` varchar(255) DEFAULT 'NULL',
  `expiry_date` varchar(255) DEFAULT 'NULL',
  `cvv` varchar(255) DEFAULT 'NULL'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_transaction`
--

INSERT INTO `order_transaction` (`transactionid`, `transaction_number`, `payment_type`, `transaction_datetime`, `orderid`, `card_number`, `expiry_date`, `cvv`) VALUES
(1, 1, 'Debit Card', '2024-12-12 08:15:00', 1, '\"{\\\"ciphertext\\\":\\\"iS9t23N0NJpEeagSHBEFGg==\\\",\\\"iv\\\":\\\"z9GwJTaHPumLESZu\\\",\\\"tag\\\":\\\"ps6RcGxUmunfsr5n09PVFw==\\\"}\"', '\"{\\\"ciphertext\\\":\\\"2V+lRh+uQQ==\\\",\\\"iv\\\":\\\"A9xQvITt3nsPyDoK\\\",\\\"tag\\\":\\\"OXoozlidnNZUsV4xKdXEug==\\\"}\"', '\"{\\\"ciphertext\\\":\\\"7oa+\\\",\\\"iv\\\":\\\"AGPBvK4aTcVp6+gX\\\",\\\"tag\\\":\\\"ljLcgON8MKYeZ3lxZ55mmg==\\\"}\"'),
(2, 2, 'Credit Card', '2024-12-03 20:35:00', 2, '\"{\\\"ciphertext\\\":\\\"wVCqUV3GECpmbcHOZaMqcw==\\\",\\\"iv\\\":\\\"TJN3fA7YRekfThMg\\\",\\\"tag\\\":\\\"bEMiTlJGuXYeyLGvd5xAZg==\\\"}\"', '\"{\\\"ciphertext\\\":\\\"BpbG7GFzCg==\\\",\\\"iv\\\":\\\"tFG8X1xELhffJcpw\\\",\\\"tag\\\":\\\"CXr+hZMO5H6SrouhGs\\\\\\/dLA==\\\"}\"', '\"{\\\"ciphertext\\\":\\\"L8h5\\\",\\\"iv\\\":\\\"cnGLQG6QdIIrpjyC\\\",\\\"tag\\\":\\\"BhXi\\\\\\/6J3TB+ykMue7wADAA==\\\"}\"'),
(3, 3, 'Debit Card', '2024-11-24 14:50:00', 3, '\"{\\\"ciphertext\\\":\\\"lnNHXRj3FNfWrWL\\\\\\/zvMlZg==\\\",\\\"iv\\\":\\\"y9znLNu03yPd2Tjs\\\",\\\"tag\\\":\\\"ynl7n02A8qUEHw6lsqCcqQ==\\\"}\"', '\"{\\\"ciphertext\\\":\\\"ZO0q8awxAg==\\\",\\\"iv\\\":\\\"FPJL9H\\\\\\/P2yrqoEGJ\\\",\\\"tag\\\":\\\"8TXrw8aZlTLmUIXyethuvA==\\\"}\"', '\"{\\\"ciphertext\\\":\\\"Jj0J\\\",\\\"iv\\\":\\\"wmEjLrqid29LFHge\\\",\\\"tag\\\":\\\"gC8XsT7PVouC\\\\\\/JMOr+GVbA==\\\"}\"'),
(4, 4, 'Credit Card', '2024-11-23 11:14:00', 4, '\"{\\\"ciphertext\\\":\\\"ZqHkwhRcYbRnRjflUDL\\\\\\/Hw==\\\",\\\"iv\\\":\\\"6FlDmTRjHUe6GeIS\\\",\\\"tag\\\":\\\"Z56YU0\\\\\\/WmWRbaUSqMB0Sng==\\\"}\"', '\"{\\\"ciphertext\\\":\\\"oPXhRbWRpw==\\\",\\\"iv\\\":\\\"k7f1DFPcp4FEtMyL\\\",\\\"tag\\\":\\\"2HD2L3Yp7xZy\\\\\\/2bsJOj+Ug==\\\"}\"', '\"{\\\"ciphertext\\\":\\\"ptO9\\\",\\\"iv\\\":\\\"gqOC4Z1qteYdP5BY\\\",\\\"tag\\\":\\\"KofgheB003qZ13ourZhCAQ==\\\"}\"'),
(5, 5, 'Credit Card', '2024-11-25 13:20:00', 5, '\"{\\\"ciphertext\\\":\\\"gsmN8aWrU6kzXYjSRnhoBQ==\\\",\\\"iv\\\":\\\"tqWulcXKKbRl2+2R\\\",\\\"tag\\\":\\\"N5dXJii1dm5l+lFjG1byXQ==\\\"}\"', '\"{\\\"ciphertext\\\":\\\"MJtu6UKVmA==\\\",\\\"iv\\\":\\\"artZcKOHAFMJPRaZ\\\",\\\"tag\\\":\\\"dDrRUIigh6ykdn+t5nTGpA==\\\"}\"', '\"{\\\"ciphertext\\\":\\\"gam+\\\",\\\"iv\\\":\\\"RROC8w5UBw6yrWA\\/\\\",\\\"tag\\\":\\\"mljjhIKcEbxQoG3wNA46wg==\\\"}\"');

-- --------------------------------------------------------

--
-- Table structure for table `order_transaction_summary`
--

CREATE TABLE `order_transaction_summary` (
  `transaction_number` int(11) NOT NULL,
  `transaction_datetime` datetime NOT NULL,
  `tax` decimal(10,2) NOT NULL,
  `tip` decimal(10,2) DEFAULT NULL,
  `sub_total` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_transaction_summary`
--

INSERT INTO `order_transaction_summary` (`transaction_number`, `transaction_datetime`, `tax`, `tip`, `sub_total`, `total`) VALUES
(1, '2024-12-12 08:15:00', 25.44, 25.00, 211.96, 262.40),
(2, '2024-12-03 20:35:00', 22.32, 10.00, 185.97, 218.29),
(3, '2024-11-24 14:50:00', 18.71, 35.00, 155.94, 209.65),
(4, '2024-11-23 11:14:00', 53.99, 50.00, 449.91, 553.90),
(5, '2024-11-25 13:20:00', 57.95, 40.00, 482.93, 580.88);

-- --------------------------------------------------------

--
-- Table structure for table `permission`
--

CREATE TABLE `permission` (
  `permissionid` int(11) NOT NULL,
  `access_level` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `permission`
--

INSERT INTO `permission` (`permissionid`, `access_level`) VALUES
(1, 'account'),
(2, 'delivery'),
(3, 'inventory'),
(4, 'order'),
(5, 'reservation'),
(6, 'employee'),
(7, 'customer'),
(8, 'supply_order');

-- --------------------------------------------------------

--
-- Table structure for table `reservation`
--

CREATE TABLE `reservation` (
  `reservationid` int(11) NOT NULL,
  `reservation_number` int(11) NOT NULL,
  `party_size` int(11) NOT NULL,
  `reservation_datetime` datetime NOT NULL,
  `customerid` int(11) DEFAULT NULL,
  `tableid` int(11) NOT NULL,
  `customer_name` varchar(50) DEFAULT NULL,
  `customer_phone_number` varchar(20) DEFAULT 'NULL',
  `reservation_end_datetime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservation`
--

INSERT INTO `reservation` (`reservationid`, `reservation_number`, `party_size`, `reservation_datetime`, `customerid`, `tableid`, `customer_name`, `customer_phone_number`, `reservation_end_datetime`) VALUES
(1, 1, 4, '2024-11-25 19:00:00', 1, 1, 'Emma Johnson', '+14165551024', '2024-11-25 20:30:00'),
(2, 1, 2, '2024-11-26 18:30:00', 2, 2, 'Liam Brown', '+16045552187', '2024-11-26 20:00:00'),
(3, 1, 6, '2024-11-27 20:00:00', 3, 4, 'Sophia Davis', '+15145553410', '2024-11-27 21:30:00'),
(4, 1, 3, '2024-11-28 17:45:00', 4, 1, 'Noah Wilson', '+14035554679', '2024-11-28 19:15:00'),
(5, 1, 5, '2024-11-29 19:30:00', 5, 3, 'Olivia Martin', '+16135555823', '2024-11-29 21:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `restaurant_table`
--

CREATE TABLE `restaurant_table` (
  `tableid` int(11) NOT NULL,
  `table_number` int(11) NOT NULL,
  `seating_capacity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `restaurant_table`
--

INSERT INTO `restaurant_table` (`tableid`, `table_number`, `seating_capacity`) VALUES
(1, 1, 4),
(2, 2, 2),
(3, 3, 6),
(4, 4, 8),
(5, 5, 4);

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `roleid` int(11) NOT NULL,
  `role_name` varchar(30) NOT NULL,
  `role_description` varchar(255) DEFAULT NULL,
  `promotion_roleid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`roleid`, `role_name`, `role_description`, `promotion_roleid`) VALUES
(1, 'Manager', 'Oversees restaurant operations and staff managament', NULL),
(2, 'Cashier', 'Handles customer orders and manages transactions', 1),
(3, 'Inventory Clerk', 'Manages inventory levels and orders necessary supplies', 1),
(4, 'Delivery Driver', 'Delivers orders to customers efficiently and accurately', 2),
(6, 'Customer', 'Customer role', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permission`
--

CREATE TABLE `role_has_permission` (
  `permissionid` int(11) NOT NULL,
  `roleid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role_has_permission`
--

INSERT INTO `role_has_permission` (`permissionid`, `roleid`) VALUES
(1, 1),
(2, 1),
(2, 4),
(3, 1),
(3, 3),
(4, 1),
(4, 2),
(4, 6),
(5, 1),
(5, 2),
(5, 6),
(6, 1),
(8, 1);

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `supplierid` int(11) NOT NULL,
  `supplier_name` varchar(50) NOT NULL,
  `supplier_phone_number` varchar(20) NOT NULL,
  `supplier_address` varchar(50) DEFAULT NULL,
  `supplier_email_address` varchar(50) NOT NULL,
  `contact_person` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`supplierid`, `supplier_name`, `supplier_phone_number`, `supplier_address`, `supplier_email_address`, `contact_person`) VALUES
(1, 'Kitchen Supplies & CO', '+14165550123', '123 Orchard Lane', 'service@kitchensupplies.ca', 'Alice Green'),
(2, 'Ocean Delights Seafood', '+16045550198', '456 Fisherman Way', 'contact@oceandelights.com', 'Mark Fisher'),
(3, 'Fresh Produce LTD', '+14035550167', '789 Chef Street', 'sales@kitchenessentials.com', 'Sarah Baker'),
(4, 'DairyLand', '+15145550145', '101  Oak Street', 'support@dairyland.com', 'Tom Griller'),
(5, 'Meat Co', '+12505550112', '202 Vine Ave', 'service@meatco.ca', 'Emily Brewer');

-- --------------------------------------------------------

--
-- Table structure for table `supply_order`
--

CREATE TABLE `supply_order` (
  `supply_orderid` int(11) NOT NULL,
  `inventoryid` int(11) NOT NULL,
  `supplierid` int(11) NOT NULL,
  `cost_per_unit` decimal(10,2) NOT NULL,
  `quantity_ordered` int(11) NOT NULL,
  `total_cost` decimal(10,2) NOT NULL,
  `supply_order_datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supply_order`
--

INSERT INTO `supply_order` (`supply_orderid`, `inventoryid`, `supplierid`, `cost_per_unit`, `quantity_ordered`, `total_cost`, `supply_order_datetime`) VALUES
(1, 1, 5, 18.00, 10, 180.00, '2024-12-21 23:28:00'),
(2, 2, 2, 40.07, 15, 601.05, '2024-11-25 20:42:00'),
(3, 3, 3, 20.00, 17, 340.00, '2025-01-07 13:02:00'),
(4, 4, 4, 15.75, 22, 346.50, '2024-11-25 04:46:00'),
(5, 5, 5, 8.04, 25, 201.00, '2024-11-24 21:16:00');

-- --------------------------------------------------------

--
-- Table structure for table `supply_order_details`
--

CREATE TABLE `supply_order_details` (
  `supply_orderid` int(11) NOT NULL,
  `supply_order_datetime` datetime NOT NULL,
  `order_status` varchar(20) NOT NULL DEFAULT 'Waiting for Approval',
  `employeeid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supply_order_details`
--

INSERT INTO `supply_order_details` (`supply_orderid`, `supply_order_datetime`, `order_status`, `employeeid`) VALUES
(1, '2024-12-21 23:28:00', 'Waiting for Approval', 3),
(2, '2024-11-25 20:42:00', 'Waiting for Approval', 3),
(3, '2025-01-07 13:02:00', 'Waiting for Approval', 3),
(4, '2024-11-25 04:46:00', 'Waiting for Approval', 3),
(5, '2024-11-24 21:16:00', 'Waiting for Approval', 3);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `userid` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `name` varchar(20) NOT NULL,
  `password` varchar(32) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `role` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userid`, `username`, `name`, `password`, `active`, `role`) VALUES
(1, 'Emma', 'Emma Johnson', '098f6bcd4621d373cade4e832627b4f6', 1, 'Customer'),
(2, 'Liam', 'Liam Brown', '098f6bcd4621d373cade4e832627b4f6', 1, 'Customer'),
(3, 'Sophia', 'Sophia Davis', '098f6bcd4621d373cade4e832627b4f6', 1, 'Customer'),
(4, 'Noah', 'Noah Wilson', '098f6bcd4621d373cade4e832627b4f6', 1, 'Customer'),
(5, 'Olivia', 'Olivia Martin', '098f6bcd4621d373cade4e832627b4f6', 1, 'Customer'),
(6, 'Alice ', 'Alice Smith', '098f6bcd4621d373cade4e832627b4f6', 1, 'Employee'),
(7, 'Bob ', 'Bob Jones', '098f6bcd4621d373cade4e832627b4f6', 1, 'Employee'),
(8, 'Carla ', 'Carla Johnson', '098f6bcd4621d373cade4e832627b4f6', 1, 'Employee'),
(9, 'Dave ', 'Dave Miller', '098f6bcd4621d373cade4e832627b4f6', 1, 'Employee'),
(10, 'Eve ', 'Eve Lee', '098f6bcd4621d373cade4e832627b4f6', 1, 'Employee');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`addressid`),
  ADD UNIQUE KEY `unique_house_postal` (`house_number`,`postal_code`);

--
-- Indexes for table `address_details`
--
ALTER TABLE `address_details`
  ADD PRIMARY KEY (`house_number`,`postal_code`);

--
-- Indexes for table `contain`
--
ALTER TABLE `contain`
  ADD PRIMARY KEY (`menu_itemid`,`orderid`),
  ADD KEY `orderid` (`orderid`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customerid`),
  ADD KEY `userid` (`userid`);

--
-- Indexes for table `customer_has_address`
--
ALTER TABLE `customer_has_address`
  ADD PRIMARY KEY (`customerid`,`addressid`),
  ADD KEY `addressid` (`addressid`);

--
-- Indexes for table `delivery`
--
ALTER TABLE `delivery`
  ADD PRIMARY KEY (`deliveryid`),
  ADD KEY `orderid` (`orderid`),
  ADD KEY `customerid` (`customerid`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`employeeid`),
  ADD KEY `roleid` (`roleid`),
  ADD KEY `userid` (`userid`);

--
-- Indexes for table `employee_manages_inventory`
--
ALTER TABLE `employee_manages_inventory`
  ADD PRIMARY KEY (`employeeid`,`inventoryid`),
  ADD KEY `inventoryid` (`inventoryid`);

--
-- Indexes for table `encryption_keys`
--
ALTER TABLE `encryption_keys`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `has_payment_information`
--
ALTER TABLE `has_payment_information`
  ADD PRIMARY KEY (`paymentid`),
  ADD UNIQUE KEY `unique_card_customer` (`card_number`,`customerid`) USING HASH,
  ADD KEY `customerid` (`customerid`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`inventoryid`),
  ADD UNIQUE KEY `item_name` (`item_name`);

--
-- Indexes for table `inventory_item`
--
ALTER TABLE `inventory_item`
  ADD PRIMARY KEY (`item_name`);

--
-- Indexes for table `menu_beverage`
--
ALTER TABLE `menu_beverage`
  ADD PRIMARY KEY (`menu_itemid`);

--
-- Indexes for table `menu_item`
--
ALTER TABLE `menu_item`
  ADD PRIMARY KEY (`menu_itemid`);

--
-- Indexes for table `menu_special`
--
ALTER TABLE `menu_special`
  ADD PRIMARY KEY (`menu_itemid`);

--
-- Indexes for table `order_table`
--
ALTER TABLE `order_table`
  ADD PRIMARY KEY (`orderid`),
  ADD KEY `employeeid` (`employeeid`);

--
-- Indexes for table `order_transaction`
--
ALTER TABLE `order_transaction`
  ADD PRIMARY KEY (`transactionid`,`orderid`),
  ADD UNIQUE KEY `unique_transaction_datetime` (`transaction_number`,`transaction_datetime`),
  ADD KEY `orderid` (`orderid`);

--
-- Indexes for table `order_transaction_summary`
--
ALTER TABLE `order_transaction_summary`
  ADD PRIMARY KEY (`transaction_number`,`transaction_datetime`);

--
-- Indexes for table `permission`
--
ALTER TABLE `permission`
  ADD PRIMARY KEY (`permissionid`);

--
-- Indexes for table `reservation`
--
ALTER TABLE `reservation`
  ADD PRIMARY KEY (`reservationid`),
  ADD KEY `customerid` (`customerid`),
  ADD KEY `tableid` (`tableid`);

--
-- Indexes for table `restaurant_table`
--
ALTER TABLE `restaurant_table`
  ADD PRIMARY KEY (`tableid`),
  ADD UNIQUE KEY `table_number` (`table_number`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`roleid`),
  ADD KEY `promotion_roleid` (`promotion_roleid`);

--
-- Indexes for table `role_has_permission`
--
ALTER TABLE `role_has_permission`
  ADD PRIMARY KEY (`permissionid`,`roleid`),
  ADD KEY `roleid` (`roleid`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`supplierid`);

--
-- Indexes for table `supply_order`
--
ALTER TABLE `supply_order`
  ADD PRIMARY KEY (`supply_orderid`,`inventoryid`,`supply_order_datetime`),
  ADD KEY `supply_orderid` (`supply_orderid`,`supply_order_datetime`),
  ADD KEY `inventoryid` (`inventoryid`),
  ADD KEY `supplierid` (`supplierid`);

--
-- Indexes for table `supply_order_details`
--
ALTER TABLE `supply_order_details`
  ADD PRIMARY KEY (`supply_orderid`,`supply_order_datetime`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `address`
--
ALTER TABLE `address`
  MODIFY `addressid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customerid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `delivery`
--
ALTER TABLE `delivery`
  MODIFY `deliveryid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `employeeid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `encryption_keys`
--
ALTER TABLE `encryption_keys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `has_payment_information`
--
ALTER TABLE `has_payment_information`
  MODIFY `paymentid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `inventoryid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `menu_item`
--
ALTER TABLE `menu_item`
  MODIFY `menu_itemid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `order_table`
--
ALTER TABLE `order_table`
  MODIFY `orderid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `order_transaction`
--
ALTER TABLE `order_transaction`
  MODIFY `transactionid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `reservation`
--
ALTER TABLE `reservation`
  MODIFY `reservationid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `restaurant_table`
--
ALTER TABLE `restaurant_table`
  MODIFY `tableid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `roleid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `supplierid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `address_details`
--
ALTER TABLE `address_details`
  ADD CONSTRAINT `address_details_ibfk_1` FOREIGN KEY (`house_number`,`postal_code`) REFERENCES `address` (`house_number`, `postal_code`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `contain`
--
ALTER TABLE `contain`
  ADD CONSTRAINT `contain_ibfk_1` FOREIGN KEY (`menu_itemid`) REFERENCES `menu_item` (`menu_itemid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `contain_ibfk_2` FOREIGN KEY (`orderid`) REFERENCES `order_table` (`orderid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `customer`
--
ALTER TABLE `customer`
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`userid`) REFERENCES `user` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `customer_has_address`
--
ALTER TABLE `customer_has_address`
  ADD CONSTRAINT `customer_has_address_ibfk_1` FOREIGN KEY (`customerid`) REFERENCES `customer` (`customerid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `customer_has_address_ibfk_2` FOREIGN KEY (`addressid`) REFERENCES `address` (`addressid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `delivery`
--
ALTER TABLE `delivery`
  ADD CONSTRAINT `delivery_ibfk_1` FOREIGN KEY (`orderid`) REFERENCES `order_table` (`orderid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `delivery_ibfk_2` FOREIGN KEY (`customerid`) REFERENCES `customer` (`customerid`) ON UPDATE CASCADE;

--
-- Constraints for table `employee`
--
ALTER TABLE `employee`
  ADD CONSTRAINT `employee_ibfk_1` FOREIGN KEY (`roleid`) REFERENCES `role` (`roleid`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `employee_ibfk_2` FOREIGN KEY (`userid`) REFERENCES `user` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `employee_manages_inventory`
--
ALTER TABLE `employee_manages_inventory`
  ADD CONSTRAINT `employee_manages_inventory_ibfk_1` FOREIGN KEY (`employeeid`) REFERENCES `employee` (`employeeid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `employee_manages_inventory_ibfk_2` FOREIGN KEY (`inventoryid`) REFERENCES `inventory` (`inventoryid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `has_payment_information`
--
ALTER TABLE `has_payment_information`
  ADD CONSTRAINT `has_payment_information_ibfk_1` FOREIGN KEY (`customerid`) REFERENCES `customer` (`customerid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `inventory_item`
--
ALTER TABLE `inventory_item`
  ADD CONSTRAINT `inventory_item_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `inventory` (`item_name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `menu_beverage`
--
ALTER TABLE `menu_beverage`
  ADD CONSTRAINT `menu_beverage_ibfk_1` FOREIGN KEY (`menu_itemid`) REFERENCES `menu_item` (`menu_itemid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `menu_special`
--
ALTER TABLE `menu_special`
  ADD CONSTRAINT `menu_special_ibfk_1` FOREIGN KEY (`menu_itemid`) REFERENCES `menu_item` (`menu_itemid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `order_table`
--
ALTER TABLE `order_table`
  ADD CONSTRAINT `order_table_ibfk_1` FOREIGN KEY (`employeeid`) REFERENCES `employee` (`employeeid`);

--
-- Constraints for table `order_transaction`
--
ALTER TABLE `order_transaction`
  ADD CONSTRAINT `order_transaction_ibfk_1` FOREIGN KEY (`orderid`) REFERENCES `order_table` (`orderid`) ON UPDATE CASCADE;

--
-- Constraints for table `order_transaction_summary`
--
ALTER TABLE `order_transaction_summary`
  ADD CONSTRAINT `order_transaction_summary_ibfk_1` FOREIGN KEY (`transaction_number`,`transaction_datetime`) REFERENCES `order_transaction` (`transaction_number`, `transaction_datetime`) ON UPDATE CASCADE;

--
-- Constraints for table `reservation`
--
ALTER TABLE `reservation`
  ADD CONSTRAINT `reservation_ibfk_1` FOREIGN KEY (`customerid`) REFERENCES `customer` (`customerid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reservation_ibfk_2` FOREIGN KEY (`tableid`) REFERENCES `restaurant_table` (`tableid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `role`
--
ALTER TABLE `role`
  ADD CONSTRAINT `role_ibfk_1` FOREIGN KEY (`promotion_roleid`) REFERENCES `role` (`roleid`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `role_has_permission`
--
ALTER TABLE `role_has_permission`
  ADD CONSTRAINT `role_has_permission_ibfk_1` FOREIGN KEY (`permissionid`) REFERENCES `permission` (`permissionid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `role_has_permission_ibfk_2` FOREIGN KEY (`roleid`) REFERENCES `role` (`roleid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `supply_order`
--
ALTER TABLE `supply_order`
  ADD CONSTRAINT `supply_order_ibfk_1` FOREIGN KEY (`supply_orderid`,`supply_order_datetime`) REFERENCES `supply_order_details` (`supply_orderid`, `supply_order_datetime`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `supply_order_ibfk_2` FOREIGN KEY (`inventoryid`) REFERENCES `inventory` (`inventoryid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `supply_order_ibfk_3` FOREIGN KEY (`supplierid`) REFERENCES `supplier` (`supplierid`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
