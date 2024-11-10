-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql110.infinityfree.com
-- Generation Time: Nov 10, 2024 at 03:56 PM
-- Server version: 10.11.9-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_37164226_buketkafe`
--

-- --------------------------------------------------------

--
-- Table structure for table `adisyonlar`
--

CREATE TABLE `adisyonlar` (
  `adisyon_id` int(11) NOT NULL,
  `siparis_tarihi` datetime DEFAULT NULL,
  `toplam_tutar` decimal(10,0) DEFAULT NULL,
  `odeme_durumu` tinyint(1) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `adisyonlar`
--

INSERT INTO `adisyonlar` (`adisyon_id`, `siparis_tarihi`, `toplam_tutar`, `odeme_durumu`) VALUES
(1, '2024-09-01 07:47:07', '0', 0),
(2, '2024-09-01 07:50:42', '0', 0),
(3, '2024-09-02 00:44:44', '0', 0),
(4, '2024-09-02 09:32:47', '0', 0),
(5, '2024-09-02 09:39:34', '0', 0),
(6, '2024-09-08 12:13:50', '0', 0),
(7, '2024-09-08 12:16:55', '0', 0),
(8, '2024-09-08 12:37:10', '0', 0),
(9, '2024-09-08 12:43:32', '0', 0),
(10, '2024-09-08 12:54:07', '0', 0),
(11, '2024-09-09 12:39:04', '0', 0),
(12, '2024-09-10 01:05:50', '0', 0),
(13, '2024-09-10 01:06:06', '0', 0),
(14, '2024-09-10 02:16:50', '0', 0),
(15, '2024-09-10 03:31:18', '0', 0),
(16, '2024-09-10 04:23:13', '0', 0),
(17, '2024-09-10 05:02:18', '0', 0);

-- --------------------------------------------------------

--
-- Table structure for table `adisyon_urunleri`
--

CREATE TABLE `adisyon_urunleri` (
  `id` int(11) NOT NULL,
  `adisyon_id` int(11) NOT NULL,
  `urun_id` int(11) NOT NULL,
  `adet` int(11) NOT NULL,
  `fiyat` decimal(10,2) NOT NULL,
  `toplam_fiyat` decimal(10,2) NOT NULL,
  `tarih` datetime NOT NULL,
  `masa_id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `adisyon_urunleri`
--

INSERT INTO `adisyon_urunleri` (`id`, `adisyon_id`, `urun_id`, `adet`, `fiyat`, `toplam_fiyat`, `tarih`, `masa_id`) VALUES
(1, 1, 7, 3, '58.00', '174.00', '2024-09-01 17:48:15', NULL),
(2, 1, 9, 1, '20.00', '20.00', '2024-09-01 17:48:15', NULL),
(3, 1, 12, 2, '60.00', '120.00', '2024-09-01 17:48:15', NULL),
(4, 1, 1, 1, '40.00', '40.00', '2024-09-01 17:48:28', NULL),
(5, 1, 2, 1, '38.00', '38.00', '2024-09-01 17:48:28', NULL),
(6, 1, 3, 1, '65.00', '65.00', '2024-09-01 17:48:28', NULL),
(7, 1, 4, 1, '45.00', '45.00', '2024-09-01 17:48:28', NULL),
(8, 1, 5, 1, '60.00', '60.00', '2024-09-01 17:48:28', NULL),
(9, 1, 6, 1, '65.00', '65.00', '2024-09-01 17:48:28', NULL),
(10, 2, 10, 3, '60.00', '180.00', '2024-09-01 17:49:24', NULL),
(11, 2, 11, 3, '30.00', '90.00', '2024-09-01 17:49:24', NULL),
(12, 2, 1, 8, '40.00', '320.00', '2024-09-01 17:51:32', NULL),
(13, 2, 4, 2, '45.00', '90.00', '2024-09-01 17:51:32', NULL),
(14, 8, 11, 1, '30.00', '30.00', '2024-09-08 22:37:41', NULL),
(15, 8, 12, 0, '60.00', '0.00', '2024-09-08 22:37:41', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `kullanici_adi` varchar(255) NOT NULL,
  `sifre` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `kullanici_adi`, `sifre`) VALUES
(1, 'admin', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `masalar`
--

CREATE TABLE `masalar` (
  `id` int(11) NOT NULL,
  `status` enum('empty','occupied') NOT NULL DEFAULT 'empty',
  `adisyon_id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `masalar`
--

INSERT INTO `masalar` (`id`, `status`, `adisyon_id`) VALUES
(1, 'empty', NULL),
(2, 'empty', NULL),
(3, 'empty', NULL),
(4, 'empty', NULL),
(5, 'empty', NULL),
(6, 'empty', NULL),
(7, 'empty', NULL),
(8, 'empty', NULL),
(9, 'empty', NULL),
(10, 'empty', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `rezervasyonlar`
--

CREATE TABLE `rezervasyonlar` (
  `id` int(11) NOT NULL,
  `ad_soyad` varchar(255) NOT NULL,
  `telefon` varchar(10) NOT NULL,
  `kisi_sayisi` varchar(15) NOT NULL,
  `saat` varchar(127) NOT NULL,
  `mesaj` text NOT NULL,
  `tarih` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `rezervasyonlar`
--

INSERT INTO `rezervasyonlar` (`id`, `ad_soyad`, `telefon`, `kisi_sayisi`, `saat`, `mesaj`, `tarih`) VALUES
(1, 'Buket Girginc', '5013459668', '3', '14:00', '', '2024-08-22 00:00:00'),
(2, 'Jane Doe', '5079096245', '1', '15:00', '', '2024-08-22 12:00:00'),
(3, 'Seyma', '5555555555', '3', '14:38', 'cam kenari masa', '2024-08-22 10:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `urunler`
--

CREATE TABLE `urunler` (
  `urun_id` int(11) NOT NULL,
  `urun_adi` varchar(127) NOT NULL,
  `fiyat` decimal(10,0) NOT NULL,
  `kategori` int(11) NOT NULL,
  `resim_yolu` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `urunler`
--

INSERT INTO `urunler` (`urun_id`, `urun_adi`, `fiyat`, `kategori`, `resim_yolu`) VALUES
(12, 'Buzlu Mocha', '60', 2, 'images/icemocha.jpeg'),
(11, 'Kola', '30', 2, 'images/coke.jpeg'),
(10, 'Hibiskus Çayi', '60', 2, 'images/hibiscus.jpeg'),
(9, 'Soda', '20', 2, 'images/soda.jpeg'),
(8, 'Limonata', '50', 2, 'images/lemonade.jpeg'),
(7, 'Buzlu Latte', '58', 2, 'images/icelatte.jpeg'),
(6, 'Latte', '65', 1, 'images/latte.jpg'),
(5, 'Sahlep', '60', 1, 'images/sahlep.jpg'),
(4, 'Türk Kahvesi', '45', 1, 'images/turkishcoffee.jpg'),
(3, 'Cappuccino', '65', 1, 'images/cappuccino.jpeg'),
(2, 'Espresso', '38', 1, 'images/espresso.jpeg'),
(1, 'Çay', '40', 1, 'images/cay.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `adisyonlar`
--
ALTER TABLE `adisyonlar`
  ADD PRIMARY KEY (`adisyon_id`);

--
-- Indexes for table `adisyon_urunleri`
--
ALTER TABLE `adisyon_urunleri`
  ADD PRIMARY KEY (`id`),
  ADD KEY `adisyon_id` (`adisyon_id`);

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `masalar`
--
ALTER TABLE `masalar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `adisyon_id` (`adisyon_id`);

--
-- Indexes for table `rezervasyonlar`
--
ALTER TABLE `rezervasyonlar`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `urunler`
--
ALTER TABLE `urunler`
  ADD PRIMARY KEY (`urun_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `adisyonlar`
--
ALTER TABLE `adisyonlar`
  MODIFY `adisyon_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `adisyon_urunleri`
--
ALTER TABLE `adisyon_urunleri`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `masalar`
--
ALTER TABLE `masalar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `rezervasyonlar`
--
ALTER TABLE `rezervasyonlar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `urunler`
--
ALTER TABLE `urunler`
  MODIFY `urun_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
