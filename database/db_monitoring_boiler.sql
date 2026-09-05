-- ==========================================================
-- Database SQL Dump: db_monitoring_boiler
-- Aplikasi Monitoring Peternakan Ayam Broiler PT BTS
-- Platform: CodeIgniter 3 & MySQL / MariaDB
-- ==========================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+07:00";

CREATE DATABASE IF NOT EXISTS `db_monitoring_boiler` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `db_monitoring_boiler`;

-- --------------------------------------------------------
-- Table structure for `users`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL,
  `role` enum('pemilik','abk') NOT NULL DEFAULT 'abk',
  `phone` varchar(20) DEFAULT NULL,
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for `users`
-- Password default: password (menggunakan bcrypt hash)
INSERT INTO `users` (`id`, `name`, `username`, `password`, `role`, `phone`, `status`, `created_at`) VALUES
(1, 'H. Bambang Tri Santoso (Pemilik)', 'pemilik', '$2y$10$WwLbwD2wSc8xGYZ0MhpZxO4PRtyPNUuDUyZURjNXg.2yATPHm4kkW', 'pemilik', '081234567890', 'aktif', '2026-01-01 08:00:00'),
(2, 'Agus Santoso (ABK)', 'abk', '$2y$10$WwLbwD2wSc8xGYZ0MhpZxO4PRtyPNUuDUyZURjNXg.2yATPHm4kkW', 'abk', '085712345678', 'aktif', '2026-01-01 08:00:00'),
(3, 'Budi Kurniawan (Admin Kandang)', 'admin', '$2y$10$WwLbwD2wSc8xGYZ0MhpZxO4PRtyPNUuDUyZURjNXg.2yATPHm4kkW', 'abk', '087812345678', 'aktif', '2026-01-01 08:00:00');

-- --------------------------------------------------------
-- Table structure for `kandang`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `kandang`;
CREATE TABLE `kandang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode_kandang` varchar(20) NOT NULL UNIQUE,
  `nama_kandang` varchar(100) NOT NULL,
  `kapasitas` int(11) NOT NULL,
  `tipe_kandang` enum('Closed House','Open House') NOT NULL DEFAULT 'Closed House',
  `lokasi` varchar(255) DEFAULT NULL,
  `status` enum('tersedia','terisi','maintenance') NOT NULL DEFAULT 'tersedia',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `kandang` (`id`, `kode_kandang`, `nama_kandang`, `kapasitas`, `tipe_kandang`, `lokasi`, `status`, `created_at`) VALUES
(1, 'KDG-01', 'Kandang Unit Alpha', 10000, 'Closed House', 'Blok A - Sektor Utara', 'terisi', '2026-01-01 08:00:00'),
(2, 'KDG-02', 'Kandang Unit Beta', 12000, 'Closed House', 'Blok B - Sektor Barat', 'tersedia', '2026-01-01 08:00:00'),
(3, 'KDG-03', 'Kandang Unit Gamma', 8000, 'Open House', 'Blok C - Sektor Timur', 'tersedia', '2026-01-01 08:00:00');

-- --------------------------------------------------------
-- Table structure for `periode`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `periode`;
CREATE TABLE `periode` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode_periode` varchar(50) NOT NULL UNIQUE,
  `kandang_id` int(11) NOT NULL,
  `strain` varchar(50) NOT NULL DEFAULT 'Cobb 500',
  `tgl_masuk_doc` date NOT NULL,
  `populasi_awal` int(11) NOT NULL,
  `target_panen_hari` int(11) NOT NULL DEFAULT 35,
  `target_bobot_panen_gram` int(11) NOT NULL DEFAULT 2000,
  `status` enum('aktif','selesai') NOT NULL DEFAULT 'aktif',
  `tgl_selesai_panen` date DEFAULT NULL,
  `total_bobot_panen_kg` decimal(10,2) DEFAULT NULL,
  `harga_kontrak_per_kg` decimal(12,2) DEFAULT 21000.00,
  `catatan` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `kandang_id` (`kandang_id`),
  CONSTRAINT `fk_periode_kandang` FOREIGN KEY (`kandang_id`) REFERENCES `kandang` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `periode` (`id`, `kode_periode`, `kandang_id`, `strain`, `tgl_masuk_doc`, `populasi_awal`, `target_panen_hari`, `target_bobot_panen_gram`, `status`, `tgl_selesai_panen`, `total_bobot_panen_kg`, `harga_kontrak_per_kg`, `catatan`, `created_at`) VALUES
(1, 'BATCH-2026-01-ALPHA', 1, 'Cobb 500', DATE_SUB(CURDATE(), INTERVAL 14 DAY), 10000, 35, 2050, 'aktif', NULL, NULL, 21500.00, 'DOC Grade A PT BTS, Pemeliharaan intensif Closed House', NOW()),
(2, 'BATCH-2025-06-BETA', 2, 'Ross 308', DATE_SUB(CURDATE(), INTERVAL 45 DAY), 12000, 35, 2100, 'selesai', DATE_SUB(CURDATE(), INTERVAL 10 DAY), 24150.00, 21000.00, 'Panen lancar, FCR sangat efisien (1.48), siap terima DOC baru', NOW());

-- --------------------------------------------------------
-- Table structure for `input_harian`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `input_harian`;
CREATE TABLE `input_harian` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `periode_id` int(11) NOT NULL,
  `kandang_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `tgl_input` date NOT NULL,
  `hari_ke` int(11) NOT NULL,
  `jumlah_mati` int(11) NOT NULL DEFAULT 0,
  `penyebab_kematian` varchar(100) DEFAULT 'Normal / Seleksi',
  `jenis_pakan` enum('Starter','Grower','Finisher') NOT NULL DEFAULT 'Starter',
  `pakan_kg` decimal(10,2) NOT NULL DEFAULT 0.00,
  `pakan_karung` decimal(10,2) NOT NULL DEFAULT 0.00,
  `obat_vitamin` varchar(150) DEFAULT NULL,
  `dosis_obat` varchar(100) DEFAULT NULL,
  `cara_pemberian` varchar(100) DEFAULT NULL,
  `suhu_kandang` decimal(4,1) DEFAULT NULL,
  `kelembaban` decimal(4,1) DEFAULT NULL,
  `bobot_sampel_gram` decimal(8,2) DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `periode_id` (`periode_id`),
  KEY `kandang_id` (`kandang_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `fk_harian_periode` FOREIGN KEY (`periode_id`) REFERENCES `periode` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_harian_kandang` FOREIGN KEY (`kandang_id`) REFERENCES `kandang` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_harian_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Sample Log Input Harian untuk Batch 1 (Hari ke-1 s/d Hari ke-14)
INSERT INTO `input_harian` (`periode_id`, `kandang_id`, `user_id`, `tgl_input`, `hari_ke`, `jumlah_mati`, `penyebab_kematian`, `jenis_pakan`, `pakan_kg`, `pakan_karung`, `obat_vitamin`, `dosis_obat`, `cara_pemberian`, `suhu_kandang`, `kelembaban`, `bobot_sampel_gram`, `catatan`) VALUES
(1, 1, 2, DATE_SUB(CURDATE(), INTERVAL 14 DAY), 1, 15, 'Dehidrasi saat kirim', 'Starter', 120.00, 2.40, 'Vita Stress + Gula Merah', '2 gr / L', 'Air Minum', 33.5, 65.0, 48.00, 'Penerimaan DOC lancar, brooding hangat'),
(1, 1, 2, DATE_SUB(CURDATE(), INTERVAL 13 DAY), 2, 10, 'Seleksi culling kecil', 'Starter', 150.00, 3.00, 'Vitamin Neobro', '1 gr / 2L', 'Air Minum', 33.0, 64.0, 58.00, 'Aktivitas makan sangat aktif'),
(1, 1, 2, DATE_SUB(CURDATE(), INTERVAL 12 DAY), 3, 8, 'Normal', 'Starter', 200.00, 4.00, 'Vaksin ND Hitchner B1', '1 tetes', 'Tetes Mata', 32.5, 65.0, 72.00, 'Vaksinasi ND tetes mata selesai'),
(1, 1, 2, DATE_SUB(CURDATE(), INTERVAL 11 DAY), 4, 7, 'Normal', 'Starter', 260.00, 5.20, 'Vitamin B-Kompleks', '1 gr / L', 'Air Minum', 32.0, 66.0, 95.00, 'Kondisi sekam kering dan bersih'),
(1, 1, 2, DATE_SUB(CURDATE(), INTERVAL 10 DAY), 5, 5, 'Normal', 'Starter', 320.00, 6.40, 'Vitamin Vita Stress', '1 gr / L', 'Air Minum', 31.5, 65.0, 125.00, 'Pelebaran sekat brooding tahap 1'),
(1, 1, 2, DATE_SUB(CURDATE(), INTERVAL 9 DAY), 6, 6, 'Tertindih sekat', 'Starter', 380.00, 7.60, '-', '-', '-', 31.0, 67.0, 160.00, 'Sekat brooding diperlebar lagi'),
(1, 1, 2, DATE_SUB(CURDATE(), INTERVAL 8 DAY), 7, 4, 'Normal', 'Starter', 440.00, 8.80, 'Probiotik EM4 Unggas', '1 ml / L', 'Air Minum', 30.5, 66.0, 200.00, 'Timbang sampel mingguan ke-1 tercapai 200gr'),
(1, 1, 2, DATE_SUB(CURDATE(), INTERVAL 7 DAY), 8, 5, 'Normal', 'Starter', 510.00, 10.20, 'Vaksin Gumboro A', '1 tetes', 'Air Minum', 30.0, 68.0, 248.00, 'Vaksinasi Gumboro via air minum'),
(1, 1, 2, DATE_SUB(CURDATE(), INTERVAL 6 DAY), 9, 6, 'Normal', 'Starter', 580.00, 11.60, 'Vitamin Stimulant', '1 gr / 2L', 'Air Minum', 29.5, 67.0, 305.00, 'Pertumbuhan merata'),
(1, 1, 2, DATE_SUB(CURDATE(), INTERVAL 5 DAY), 10, 4, 'Normal', 'Starter', 650.00, 13.00, '-', '-', '-', 29.0, 68.0, 365.00, 'Ventilasi kipas 2 unit ON'),
(1, 1, 2, DATE_SUB(CURDATE(), INTERVAL 4 DAY), 11, 7, 'Sakit / Kerdil', 'Grower', 730.00, 14.60, 'Enrofloxacin', '0.5 ml / L', 'Air Minum', 28.5, 69.0, 435.00, 'Peralihan pakan ke Grower tahap awal'),
(1, 1, 2, DATE_SUB(CURDATE(), INTERVAL 3 DAY), 12, 5, 'Normal', 'Grower', 810.00, 16.20, 'Vitamin Turbo', '1 gr / L', 'Air Minum', 28.0, 68.0, 515.00, 'Nafsu makan stabil tinggi'),
(1, 1, 2, DATE_SUB(CURDATE(), INTERVAL 2 DAY), 13, 6, 'Normal', 'Grower', 890.00, 17.80, '-', '-', '-', 28.0, 68.0, 600.00, 'Sirkulasi udara bagus'),
(1, 1, 2, DATE_SUB(CURDATE(), INTERVAL 1 DAY), 14, 5, 'Normal', 'Grower', 970.00, 19.40, 'Vitamin Amino Acid', '2 gr / L', 'Air Minum', 27.5, 70.0, 695.00, 'Timbang sampel mingguan ke-2: rata-rata 695 gr');

-- --------------------------------------------------------
-- Table structure for `pengaturan`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `pengaturan`;
CREATE TABLE `pengaturan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_pt` varchar(150) NOT NULL DEFAULT 'PT BTS (BERKAH TERNAK SEJAHTERA)',
  `nama_sistem` varchar(150) NOT NULL DEFAULT 'Aplikasi Monitoring Peternakan Ayam Broiler',
  `alamat` text DEFAULT 'Kawasan Agribisnis Terpadu Blok C-12, Subang, Jawa Barat',
  `no_telp` varchar(50) DEFAULT '(0260) 412389 / 0812-8888-9999',
  `email` varchar(100) DEFAULT 'farm.support@ptbts-poultry.com',
  `standar_fcr` decimal(4,2) DEFAULT 1.55,
  `toleransi_kematian_persen` decimal(4,2) DEFAULT 4.00,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `pengaturan` (`id`, `nama_pt`, `nama_sistem`, `alamat`, `no_telp`, `email`, `standar_fcr`, `toleransi_kematian_persen`) VALUES
(1, 'PT BTS (BERKAH TERNAK SEJAHTERA)', 'Aplikasi Monitoring Peternakan Ayam Broiler PT BTS', 'Kawasan Agribisnis Terpadu Blok C-12, Subang, Jawa Barat', '(0260) 412389 / 0812-8888-9999', 'farm.support@ptbts-poultry.com', 1.55, 4.00);

COMMIT;
