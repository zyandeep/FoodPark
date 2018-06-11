-- phpMyAdmin SQL Dump
-- version 4.8.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 28, 2018 at 01:50 PM
-- Server version: 10.1.32-MariaDB
-- PHP Version: 7.2.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `foodpark`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `a_id` int(11) NOT NULL,
  `user_name` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`a_id`, `user_name`, `password`) VALUES
(1, 'admin', 'admin123');

-- --------------------------------------------------------

--
-- Table structure for table `bill`
--

CREATE TABLE `bill` (
  `bill_no` int(11) NOT NULL,
  `o_id` int(11) NOT NULL,
  `c_id` int(11) NOT NULL,
  `total_amount` int(11) NOT NULL,
  `status` tinyint(5) NOT NULL DEFAULT '0',
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bill`
--

INSERT INTO `bill` (`bill_no`, `o_id`, `c_id`, `total_amount`, `status`, `date`) VALUES
(9, 9, 9, 320, 1, '2018-05-28');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `c_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `address` varchar(200) NOT NULL,
  `phone_no` varchar(10) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`c_id`, `name`, `address`, `phone_no`, `password`) VALUES
(9, 'Zyandeep Baruah', 'Jorhat', '8638367851', '$2y$10$S6j/1FmAEWqnidIOnA99N.xHXMP6IKANswJocgllNcRStWWaymiBm'),
(10, 'Trinayan', 'Jorhat', '9577509923', '$2y$10$Tbg2x6SRK4USuxvi9LUage.I.kOy55nTb8Go8YIor9IWK/yzbUBbS');

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `im_id` int(11) NOT NULL,
  `i_name` varchar(200) NOT NULL,
  `category` varchar(30) NOT NULL,
  `price` int(11) NOT NULL,
  `image` varchar(500) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`im_id`, `i_name`, `category`, `price`, `image`, `status`) VALUES
(1, 'Palak Paneer', 'veg', 140, 'img/item/2018-05-15 03-48-46_veg1.jpg', 1),
(2, 'Veg Briyani', 'veg', 50, 'img/item/2018-05-15 03-49-08_veg2.jpg', 1),
(4, 'Chicken Leg Piece', 'non-veg', 40, 'img/item/2018-05-15 03-50-18_nonveg2.jpg', 1),
(10, 'Singra', 'veg', 10, 'img/item/2018-05-28 07-46-47_chingra.jpg', 1),
(11, 'Veg Roll', 'veg', 50, 'img/item/2018-05-28 12-21-54_veg_roll.jpg', 1),
(12, 'Shahi Paneer', 'veg', 180, 'img/item/2018-05-28 12-24-56_paneer.gif', 1),
(13, 'Dal Makhani', 'veg', 120, 'img/item/2018-05-28 12-26-36_dal-makhani_620x350_41478501912.jpg', 1),
(14, 'Chicken Momo', 'non-veg', 50, 'img/item/2018-05-28 12-37-02_chicken_momo.jpeg', 1),
(15, 'Chicken Butter Masala', 'non-veg', 190, 'img/item/2018-05-28 12-38-13_Butter_chicken_recipe.jpg', 1),
(16, 'Roasted Pork', 'non-veg', 100, 'img/item/2018-05-28 12-39-58_pork.jpg', 1),
(17, 'Chicken Tandoori', 'non-veg', 220, 'img/item/2018-05-28 12-41-08_whole-chicken-13.jpg', 1),
(18, 'Chicken Burger', 'other', 80, 'img/item/2018-05-28 12-54-34_burger.jpg', 1),
(19, 'Cheese Pizza', 'other', 160, 'img/item/2018-05-28 12-55-14_pizza.gif', 1),
(20, 'Chicken Popcorn', 'other', 70, 'img/item/2018-05-28 12-55-57_chicken-popcorn1.jpg', 1),
(21, 'Pepsi Can', 'other', 35, 'img/item/2018-05-28 12-56-38_pepsi_can_feature.jpg', 1),
(22, 'Coca-cola Bottle(2lt)', 'other', 90, 'img/item/2018-05-28 12-57-55_coca-cola-soft-drink.jpg', 1),
(23, 'Kinley Mineral Water Bottle(1lt)', 'other', 10, 'img/item/2018-05-28 12-59-13_bottle.jpg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE `order` (
  `o_id` int(11) NOT NULL,
  `c_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `order`
--

INSERT INTO `order` (`o_id`, `c_id`) VALUES
(9, 9);

-- --------------------------------------------------------

--
-- Table structure for table `order_master`
--

CREATE TABLE `order_master` (
  `om_id` int(11) NOT NULL,
  `o_id` int(11) NOT NULL,
  `im_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `order_master`
--

INSERT INTO `order_master` (`om_id`, `o_id`, `im_id`, `quantity`, `date`) VALUES
(13, 9, 17, 1, '2018-05-28'),
(14, 9, 2, 2, '2018-05-28');

-- --------------------------------------------------------

--
-- Table structure for table `todays_special`
--

CREATE TABLE `todays_special` (
  `t_id` int(11) NOT NULL,
  `im_id` int(11) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `todays_special`
--

INSERT INTO `todays_special` (`t_id`, `im_id`, `date`) VALUES
(14, 12, '2018-05-28'),
(15, 16, '2018-05-28');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`a_id`),
  ADD UNIQUE KEY `user_name` (`user_name`);

--
-- Indexes for table `bill`
--
ALTER TABLE `bill`
  ADD PRIMARY KEY (`bill_no`),
  ADD KEY `o_id` (`o_id`,`c_id`),
  ADD KEY `bill_ibfk_2` (`c_id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`c_id`),
  ADD UNIQUE KEY `phone_no` (`phone_no`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`im_id`),
  ADD UNIQUE KEY `i_name` (`i_name`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`o_id`),
  ADD KEY `c_id` (`c_id`);

--
-- Indexes for table `order_master`
--
ALTER TABLE `order_master`
  ADD PRIMARY KEY (`om_id`),
  ADD KEY `o_id` (`o_id`,`im_id`),
  ADD KEY `order_master_ibfk_2` (`im_id`);

--
-- Indexes for table `todays_special`
--
ALTER TABLE `todays_special`
  ADD PRIMARY KEY (`t_id`),
  ADD KEY `im_id` (`im_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `a_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bill`
--
ALTER TABLE `bill`
  MODIFY `bill_no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `c_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `im_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `o_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `order_master`
--
ALTER TABLE `order_master`
  MODIFY `om_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `todays_special`
--
ALTER TABLE `todays_special`
  MODIFY `t_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bill`
--
ALTER TABLE `bill`
  ADD CONSTRAINT `bill_ibfk_1` FOREIGN KEY (`o_id`) REFERENCES `order` (`o_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `bill_ibfk_2` FOREIGN KEY (`c_id`) REFERENCES `customer` (`c_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `order_ibfk_1` FOREIGN KEY (`c_id`) REFERENCES `customer` (`c_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `order_master`
--
ALTER TABLE `order_master`
  ADD CONSTRAINT `order_master_ibfk_1` FOREIGN KEY (`o_id`) REFERENCES `order` (`o_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `order_master_ibfk_2` FOREIGN KEY (`im_id`) REFERENCES `item` (`im_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `todays_special`
--
ALTER TABLE `todays_special`
  ADD CONSTRAINT `todays_special_ibfk_1` FOREIGN KEY (`im_id`) REFERENCES `item` (`im_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
