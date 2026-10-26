-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 19, 2026 at 05:09 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cipamilk`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id_cart` int(11) NOT NULL,
  `id_customer` int(11) NOT NULL,
  `id_product` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id_cart`, `id_customer`, `id_product`, `jumlah`) VALUES
(13, 1, 1, 1),
(17, 1, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id_category` int(11) NOT NULL,
  `nama_kategori` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id_category`, `nama_kategori`, `created_at`) VALUES
(1, 'Susu Segar', '2026-09-18 10:32:08'),
(2, 'Yogurt', '2026-09-18 10:32:08'),
(3, 'Keju', '2026-09-18 10:32:08'),
(4, 'Es Krim', '2026-09-18 10:32:08'),
(5, 'Susu Sapi', '2026-09-18 14:25:12');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id_customer` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `alamat` text DEFAULT NULL,
  `nomor_telepon` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id_customer`, `id_user`, `alamat`, `nomor_telepon`) VALUES
(1, 2, 'Jl. Merdeka No. 10, Bandung', '081234567890'),
(2, 3, 'Jl. Sudirman No. 25, Jakarta', '081298765432'),
(5, 6, 'kp Benjot, RT/RW 01/04, Kec.Cugenang, Kab.Cianjur', '081573854325');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id_order` int(11) NOT NULL,
  `id_customer` int(11) NOT NULL,
  `tanggal` datetime DEFAULT current_timestamp(),
  `total` decimal(12,2) NOT NULL DEFAULT 0.00,
  `status` enum('pending','diproses','dikirim','selesai','dibatalkan') NOT NULL DEFAULT 'pending',
  `alamat_pengiriman` text DEFAULT NULL,
  `nomor_telepon` varchar(20) DEFAULT NULL,
  `metode_pembayaran` enum('cod','qris') NOT NULL DEFAULT 'cod'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id_order`, `id_customer`, `tanggal`, `total`, `status`, `alamat_pengiriman`, `nomor_telepon`, `metode_pembayaran`) VALUES
(1, 1, '2026-09-18 18:18:25', '15000.00', 'selesai', 'Jl. Merdeka No. 10, Bandung', '081234567890', 'cod'),
(2, 1, '2026-09-18 18:19:00', '36000.00', 'selesai', 'Jl. Merdeka No. 10, Bandung', '081234567890', 'cod'),
(3, 2, '2026-09-18 21:28:36', '30000.00', 'selesai', 'Jl. Sudirman No. 25, Jakarta, Desa Cibulakan RT/RW 01/04, Jakarta Barat', '081298765432', 'cod'),
(4, 1, '2026-09-18 22:35:08', '349000.00', 'selesai', 'Jl. Merdeka No. 10, Bandung', '081234567890', 'cod'),
(5, 1, '2026-09-18 22:41:53', '30000.00', 'selesai', 'Jl. Test No. 123, Bandung', '081234567890', 'cod'),
(6, 1, '2026-09-18 22:48:07', '105000.00', 'selesai', 'Alamat Pengujian', '08123456789', 'cod'),
(7, 1, '2026-09-18 22:50:58', '126000.00', 'selesai', 'Jl. Merdeka No. 10, Bandung', '081234567890', 'cod'),
(8, 2, '2026-09-18 22:55:55', '72000.00', 'selesai', 'Jl. Sudirman No. 25, Jakarta', '081298765432', 'cod'),
(9, 2, '2026-09-18 22:57:58', '750000.00', 'selesai', 'Jl. Sudirman No. 25, Jakarta', '081298765432', 'cod'),
(10, 5, '2026-09-19 10:01:56', '225000.00', 'selesai', 'kp Benjot, RT/RW 01/04, Kec.Cugenang, Kab.Cianjur', '081573854325', 'qris');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id_detail` int(11) NOT NULL,
  `id_order` int(11) NOT NULL,
  `id_product` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL DEFAULT 1,
  `subtotal` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id_detail`, `id_order`, `id_product`, `jumlah`, `subtotal`) VALUES
(1, 1, 1, 1, '15000.00'),
(2, 2, 2, 2, '36000.00'),
(3, 3, 1, 2, '30000.00'),
(4, 4, 1, 7, '105000.00'),
(5, 4, 3, 3, '54000.00'),
(6, 4, 8, 1, '28000.00'),
(7, 4, 5, 1, '25000.00'),
(8, 4, 6, 3, '105000.00'),
(9, 4, 7, 1, '32000.00'),
(10, 5, 1, 2, '30000.00'),
(11, 6, 1, 7, '105000.00'),
(12, 7, 3, 7, '126000.00'),
(13, 8, 2, 2, '36000.00'),
(14, 8, 3, 2, '36000.00'),
(15, 9, 9, 10, '750000.00'),
(16, 10, 5, 9, '225000.00');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id_product` int(11) NOT NULL,
  `id_category` int(11) NOT NULL,
  `nama_produk` varchar(150) NOT NULL,
  `harga` decimal(12,2) NOT NULL,
  `stok` int(11) NOT NULL DEFAULT 0,
  `gambar` varchar(255) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id_product`, `id_category`, `nama_produk`, `harga`, `stok`, `gambar`, `deskripsi`, `created_at`) VALUES
(1, 1, 'Susu Segar Original', '15000.00', 81, 'prod_6aad18e08763c.jpg', 'Susu segar murni langsung dari peternakan Cipamilk. Kaya akan kalsium dan protein untuk kebutuhan gizi harian Anda.', '2026-09-18 10:32:08'),
(2, 1, 'Susu Segar Coklat', '18000.00', 76, 'prod_6aad4eb50b7b4.jpg', 'Perpaduan susu segar dengan coklat premium. Rasa manis alami yang disukai semua kalangan.', '2026-09-18 10:32:08'),
(3, 1, 'Susu Segar Strawberry', '18000.00', 63, 'prod_6aad4edf3317c.jpg', 'Susu segar dengan rasa strawberry alami. Segar dan menyehatkan untuk keluarga.', '2026-09-18 10:32:08'),
(4, 2, 'Yogurt Plain', '22000.00', 60, 'prod_6aad4f2d62c4a.jpg', 'Yogurt plain kaya probiotik dari susu segar Cipamilk. Baik untuk pencernaan.', '2026-09-18 10:32:08'),
(5, 2, 'Yogurt Mangga', '25000.00', 40, 'prod_6aad4fac466da.jpg', 'Yogurt dengan potongan mangga asli. Nikmat dan menyehatkan.', '2026-09-18 10:32:08'),
(6, 3, 'Keju Mozzarella', '35000.00', 37, 'prod_6aad4fe779214.jpg', 'Keju mozzarella lembut dan elastis. Cocok untuk pizza dan berbagai masakan.', '2026-09-18 10:32:08'),
(7, 3, 'Keju Cheddar', '32000.00', 44, 'prod_6aad5011e9db0.jpg', 'Keju cheddar premium dengan rasa gurih yang khas. Cocok untuk sandwich dan snack.', '2026-09-18 10:32:08'),
(8, 4, 'Es Krim Vanilla', '28000.00', 0, 'prod_6aad5045ac51a.jpg', 'Es krim vanilla lembut dari susu segar Cipamilk. Nikmat disajikan kapan saja.', '2026-09-18 10:32:08'),
(9, 5, 'Susu Sapi Original', '75000.00', 15, 'prod_6aad578f4fd34.jpg', 'Susu sapi langsung dari sumbernya tanpa ada susu tambahan alias susu sapi murni dari sapi eropa', '2026-09-18 15:23:59');

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id_subscription` int(11) NOT NULL,
  `id_customer` int(11) NOT NULL,
  `id_product` int(11) NOT NULL,
  `periode` enum('harian','mingguan','bulanan') NOT NULL DEFAULT 'mingguan',
  `tanggal_mulai` date NOT NULL,
  `status` enum('aktif','selesai','dibatalkan') NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subscriptions`
--

INSERT INTO `subscriptions` (`id_subscription`, `id_customer`, `id_product`, `periode`, `tanggal_mulai`, `status`, `created_at`) VALUES
(1, 1, 1, 'mingguan', '2026-09-01', 'aktif', '2026-09-18 10:32:08');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','customer') NOT NULL DEFAULT 'customer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `nama`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'Admin Cipamilk', 'admin@cipamilk.com', '$2y$10$3fcQdZ9MyZtb241GtP7v8.DEd.2X2Is9uFOlPlgdaK4eb3W/IXV6O', 'admin', '2026-09-18 10:32:08'),
(2, 'Budi Santoso', 'budi@email.com', '$2y$10$6xeQeCI2cgWcOXB8HXvCQe7zkKvUB/9HOXNMv3V5QHQu0Ktz3HSH6', 'customer', '2026-09-18 10:32:08'),
(3, 'Siti Nurhaliza', 'siti@email.com', '$2y$10$6xeQeCI2cgWcOXB8HXvCQe7zkKvUB/9HOXNMv3V5QHQu0Ktz3HSH6', 'customer', '2026-09-18 10:32:08'),
(6, 'Mas Saepul', 'fauzisalman2411@gmail.com', '$2y$10$kK6eGfnKNWpn5lgRzrRwI.jSxXNODXsrCkAprbFGTf6yZOfl12e9i', 'customer', '2026-09-19 02:46:41');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id_cart`),
  ADD KEY `id_customer` (`id_customer`),
  ADD KEY `id_product` (`id_product`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id_category`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id_customer`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id_order`),
  ADD KEY `id_customer` (`id_customer`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `id_order` (`id_order`),
  ADD KEY `id_product` (`id_product`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id_product`),
  ADD KEY `id_category` (`id_category`);

--
-- Indexes for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id_subscription`),
  ADD KEY `id_customer` (`id_customer`),
  ADD KEY `id_product` (`id_product`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id_cart` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id_category` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id_customer` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id_order` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id_product` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id_subscription` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`id_customer`) REFERENCES `customers` (`id_customer`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`id_product`) REFERENCES `products` (`id_product`) ON DELETE CASCADE;

--
-- Constraints for table `customers`
--
ALTER TABLE `customers`
  ADD CONSTRAINT `customers_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`id_customer`) REFERENCES `customers` (`id_customer`) ON DELETE CASCADE;

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`id_order`) REFERENCES `orders` (`id_order`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`id_product`) REFERENCES `products` (`id_product`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`id_category`) REFERENCES `categories` (`id_category`) ON DELETE CASCADE;

--
-- Constraints for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD CONSTRAINT `subscriptions_ibfk_1` FOREIGN KEY (`id_customer`) REFERENCES `customers` (`id_customer`) ON DELETE CASCADE,
  ADD CONSTRAINT `subscriptions_ibfk_2` FOREIGN KEY (`id_product`) REFERENCES `products` (`id_product`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
