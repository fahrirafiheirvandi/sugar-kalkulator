-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 29 Sep 2026 pada 18.43
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sugar_kalkulator_db`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `konsumsi`
--

CREATE TABLE `konsumsi` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `minuman_id` int(11) NOT NULL,
  `jumlah_porsi` int(11) NOT NULL DEFAULT 1,
  `tanggal` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `konsumsi`
--

INSERT INTO `konsumsi` (`id`, `user_id`, `minuman_id`, `jumlah_porsi`, `tanggal`, `created_at`) VALUES
(5, 2, 7, 1, '2026-09-27', '2026-09-27 10:06:44'),
(6, 2, 9, 1, '2026-09-27', '2026-09-27 10:07:45'),
(25, 3, 6, 1, '2026-09-28', '2026-09-27 18:11:09'),
(26, 3, 12, 2, '2026-09-28', '2026-09-28 13:27:48');

-- --------------------------------------------------------

--
-- Struktur dari tabel `minuman`
--

CREATE TABLE `minuman` (
  `id` int(11) NOT NULL,
  `nama_minuman` varchar(100) NOT NULL,
  `kategori` varchar(50) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `merek` varchar(50) DEFAULT NULL,
  `ukuran_saji` varchar(30) NOT NULL,
  `kadar_gula_gram` decimal(5,1) NOT NULL,
  `kalori` int(11) DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `minuman`
--

INSERT INTO `minuman` (`id`, `nama_minuman`, `kategori`, `deskripsi`, `merek`, `ukuran_saji`, `kadar_gula_gram`, `kalori`, `gambar`, `created_at`) VALUES
(1, 'Es Teh Manis', 'Teh', 'Air, Seduhan Teh Tubruk/Melati, Gula Pasir, Es Batu', 'Warteg, Warkop, Restoran', '250 ml', 20.0, 90, 'uploads/minuman/minuman_6ab903aeb9d25.png', '2026-09-27 08:55:50'),
(2, 'Teh Tawar', 'Teh', 'Air, Seduhan Daun Teh Melati (Tanpa Gula)', 'Warkop, Rumah Makan', '250 ml', 0.0, 2, 'uploads/minuman/minuman_6ab905cbe02b0.jpg', '2026-09-27 08:55:50'),
(3, 'Boba Milk Tea Original', 'Boba', 'Air\r\n\r\nSeduhan Teh Hitam (Black Tea)\r\n\r\nSusu Cair / Susu Evaporasi / Krimer Nabati (Non-Dairy Creamer)\r\n\r\nGula Pasir / Sirup Fruktosa / Sirup Gula Merah (Brown Sugar)\r\n\r\nEs Batu', 'Chatime, Haus, DLL.', '500 ml', 38.0, 320, 'uploads/minuman/minuman_6ab8ff30b8352.jpg', '2026-09-27 08:55:50'),
(4, 'Kopi Susu Gula Aren', 'Kopi', 'Air, Ekstrak Kopi Espresso, Susu Segar, Sirup Gula Aren', 'Kopi Kenangan, Janji Jiwa, Point Coffee', '250 ml', 25.0, 180, 'uploads/minuman/minuman_6ab90461ae304.jpg', '2026-09-27 08:55:50'),
(5, 'Kopi Hitam Tanpa Gula', 'Kopi', 'Air, Ekstrak Biji Kopi Hitam Murni (Tanpa Gula)', 'Kapal Api, Luwak, Nescafe', '150 ml', 0.0, 5, 'uploads/minuman/minuman_6ab904334fd61.jpg', '2026-09-27 08:55:50'),
(6, 'Soda Cola', 'Soda', 'Air Berkarbonasi, Gula Pasir, Pewarna Karamel IV, Pengatur Keasaman Asam Fosfat, Kafein', 'Coca-Cola, Pepsi, Big Cola', '330 ml', 35.0, 140, 'uploads/minuman/minuman_6ab9053f041d6.png', '2026-09-27 08:55:50'),
(7, 'Jus Jeruk Kemasan', 'Jus', 'Air, Konsentrat Sari Buah Jeruk, Gula Pasir, Pengatur Keasaman, Perisa Jeruk', 'Buavita, ABC, Minute Maid Pulpy', '250 ml', 22.0, 110, 'uploads/minuman/minuman_6ab904073ef1d.png', '2026-09-27 08:55:50'),
(8, 'Jus Buah Segar Tanpa Gula Tambahan', 'Jus', 'Murni Ekstrak Buah Segar, Air Alami (Tanpa Tambahan Pemanis Bukatan)', 'Buavita, Sunpride, Re.juve', '250 ml', 12.0, 90, 'uploads/minuman/minuman_6ab903d13a5a2.png', '2026-09-27 08:55:50'),
(9, 'Air Mineral', 'Air Putih', 'Kalsium (Ca) – Menjaga kesehatan tulang, gigi, dan fungsi otot.\r\n\r\nMagnesium (Mg) – Membantu metabolisme energi dan fungsi saraf.\r\n\r\nNatrium (Na) – Menjaga keseimbangan cairan tubuh dan tekanan darah.\r\n\r\nKalium (K) – Mendukung fungsi jantung dan kerja sistem saraf.\r\n\r\nBikarbonat (HCO3) – Menjaga keseimbangan asam-basa (pH) tubuh.\r\n\r\nSilika (SiO2) – Menjaga kesehatan jaringan ikat dan kulit.\r\n\r\nKlorida (Cl) & Sulfat (SO4) – Mengatur keseimbangan elektrolit.', 'Aqua, Le Mineral, Cleo, dll.', '600 ml', 0.0, NULL, 'uploads/minuman/minuman_6ab8fea64449a.jpg', '2026-09-27 08:55:50'),
(10, 'Minuman Isotonik', 'Isotonik', 'Air, Gula, Natrium Klorida, Kalium Klorida, Kalsium Laktat, Magnesium Karbonat', 'Pocari Sweat, Mizone, Hydro Coco', '500 ml', 28.0, 130, 'uploads/minuman/minuman_6ab9051a3ecce.png', '2026-09-27 08:55:50'),
(11, 'Es Coklat', 'Coklat', 'Air, Bubuk Cokelat, Susu Kental Manis, Gula Pasir, Es Batu', 'Warkop, Haus, Cadbury', '350 ml', 30.0, 250, 'uploads/minuman/minuman_6ab9037d671fd.png', '2026-09-27 08:55:50'),
(12, 'Matcha Latte', 'Teh', 'Air, Bubuk Matcha Pure, Susu UHT/Suku Segar, Sirup Pemanis', 'Starbucks, Chatime, Haus', '350 ml', 26.0, 210, 'uploads/minuman/minuman_6ab904cfc8260.png', '2026-09-27 08:55:50'),
(13, 'Teh Pucuk', 'Teh', 'Air (Water)\r\nGula (Sugar)\r\nTeh Melati (Jasmine Tea - daun pucuk teh dan bunga melati)\r\nPerisa Sintetik / Identik Alami Bunga Melati (Jasmine Flavoring)\r\nPenstabil / Pengatur Keasaman (seperti Trikalium Fosfat / Tripotassium Phosphate)', 'Teh Pucuk Harum, Teh Botol Sosro, Frestea', '350 ml', 30.0, 130, 'uploads/minuman/minuman_6ab8f2529e0a8.jpg', '2026-09-27 10:39:14'),
(14, 'Susu UHT Cokelat', 'Susu', 'Susu Sapi Segar, Gula, Kakao Bubuk, Penstabil Nabati, Perisa Sintetik Cokelat, Garam.', 'Ultra Milk, Indomilk, Bear Brand', '250 ml', 19.0, 160, 'uploads/minuman/minuman_6ab905667a6a3.jpg', '2026-09-27 11:43:59'),
(15, 'Kopi Susu Gula Aren Botol', 'Kopi', 'Air, Ekstrak Kopi, Susu Segar, Gula Aren, Krimer Nabati.', 'Kopi Kenangan, Janji Jiwa, Point Coffee', '350 ml', 25.0, 210, 'uploads/minuman/minuman_6ab90486138b8.jpg', '2026-09-27 11:43:59'),
(17, 'Boba Brown Sugar Milk Tea', 'Boba', 'Air, Seduhan Teh Hitam, Susu Evaporasi, Sirup Gula Merah, Tapioka Pearl.', 'Chatime, Mixue, Xing Fu Tang', '500 ml', 42.0, 380, 'uploads/minuman/minuman_6ab9031bb9c45.jpg', '2026-09-27 11:43:59');

-- --------------------------------------------------------

--
-- Struktur dari tabel `rules_risiko`
--

CREATE TABLE `rules_risiko` (
  `id` int(11) NOT NULL,
  `batas_bawah_gram` decimal(5,1) NOT NULL,
  `batas_atas_gram` decimal(5,1) DEFAULT NULL,
  `kondisi_khusus` enum('normal','diabetes','prediabetes') NOT NULL DEFAULT 'normal',
  `level_risiko` varchar(20) NOT NULL,
  `pesan_warning` text NOT NULL,
  `rekomendasi_olahraga` text NOT NULL,
  `rekomendasi_pola_makan` text NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `rules_risiko`
--

INSERT INTO `rules_risiko` (`id`, `batas_bawah_gram`, `batas_atas_gram`, `kondisi_khusus`, `level_risiko`, `pesan_warning`, `rekomendasi_olahraga`, `rekomendasi_pola_makan`, `is_active`, `created_at`) VALUES
(1, 0.0, 15.0, 'normal', 'Aman', 'Konsumsi gula kamu hari ini masih sangat aman, jauh di bawah batas ideal WHO. Pertahankan terus!', 'Olahraga ringan 15-20 menit seperti jalan santai sudah cukup buat jaga kebugaran.', 'Lanjutkan pola makan seimbang, perbanyak air putih dan serat.', 1, '2026-09-27 08:55:50'),
(2, 25.1, 40.0, 'normal', 'Waspada', 'Konsumsi gula kamu udah di atas batas ideal (25g). Mulai dikurangi ya, jangan tambah minuman manis lagi hari ini.', 'Coba olahraga sedang 30 menit seperti jogging atau bersepeda buat bantu bakar kalori berlebih.', 'Kurangi minuman manis besok, ganti sebagian dengan air putih atau teh tawar.', 1, '2026-09-27 08:55:50'),
(3, 60.1, NULL, 'normal', 'Bahaya', 'Konsumsi gula kamu udah jauh melewati batas maksimal harian! Kalau sering kejadian, risiko diabetes & obesitas meningkat.', 'Disarankan olahraga intensif minimal 45 menit, seperti HIIT atau lari, untuk membantu metabolisme gula.', 'Stop konsumsi minuman manis untuk 2-3 hari ke depan. Fokus makanan rendah gula & tinggi serat, perbanyak air putih.', 1, '2026-09-27 08:55:50'),
(4, 0.0, 15.0, 'diabetes', 'Aman', 'Konsumsi gula kamu hari ini masih terkontrol. Tetap jaga terus konsistensi ini.', 'Olahraga ringan rutin 20 menit tiap hari, seperti jalan kaki, sangat dianjurkan.', 'Pertahankan pola makan rendah gula dan pantau terus kadar gula darah secara berkala.', 1, '2026-09-27 08:55:50'),
(5, 15.1, 30.0, 'diabetes', 'Waspada', 'Sebagai penderita riwayat diabetes, konsumsi gula kamu hari ini sudah cukup tinggi. Perlu perhatian ekstra!', 'Tingkatkan aktivitas fisik jadi 30-40 menit, dan lakukan pengecekan gula darah lebih sering.', 'Kurangi drastis minuman manis, konsultasikan pola makan dengan dokter/ahli gizi.', 1, '2026-09-27 08:55:50'),
(6, 30.1, NULL, 'diabetes', 'Bahaya', 'PERINGATAN: konsumsi gula kamu hari ini sangat berisiko untuk kondisi riwayat diabetes kamu. Segera lakukan tindakan!', 'Konsultasikan ke dokter sebelum olahraga berat. Aktivitas ringan-sedang dengan pengawasan.', 'Segera batasi total konsumsi gula, disarankan konsultasi langsung dengan dokter/ahli gizi secepatnya.', 1, '2026-09-27 08:55:50'),
(10, 0.0, 20.0, 'prediabetes', 'Aman', 'Konsumsi gula kamu masih tergolong aman untuk kondisi prediabetes. Jaga terus!', 'Jalan kaki atau jalan cepat 20 menit sehari.', 'Pertahankan pola makan rendah indeks glikemik.', 1, '2026-09-27 18:02:26'),
(11, 20.1, 35.0, 'prediabetes', 'Waspada', 'Asupan gula mendekati ambang batas aman prediabetes. Mulai batasi porsi manis!', 'Jogging ringan atau bersepeda 30 menit.', 'Ganti minuman manis dengan air putih atau teh tawar.', 1, '2026-09-27 18:02:26'),
(12, 35.1, NULL, 'prediabetes', 'Bahaya', 'PERINGATAN: Batas gula harian melebihi rekomendasi kondisi prediabetes!', 'Olahraga kardio sedang 40 menit untuk mengontrol resistensi insulin.', 'Hentikan konsumsi minuman bersoda/kemasan. Utamakan makanan berserat tinggi.', 1, '2026-09-27 18:02:26'),
(13, 0.0, 15.0, 'diabetes', 'Aman', 'Konsumsi gula kamu hari ini masih dalam batas aman buat kamu yang punya riwayat diabetes. Tetap jaga terus ya!', 'Jalan kaki ringan 20-30 menit setelah makan bisa bantu jaga kadar gula darah tetap stabil.', 'Pertahankan pola makan rendah gula, perbanyak sayur, protein, dan serat. Tetap pantau gula darah secara rutin.', 1, '2026-09-27 18:07:40'),
(14, 15.1, 30.0, 'diabetes', 'Waspada', 'Konsumsi gula kamu udah mendekati batas yang disarankan buat penderita diabetes. Sebaiknya mulai dikurangi dari sekarang.', 'Coba olahraga sedang 30-40 menit seperti jalan cepat atau bersepeda santai buat bantu kontrol gula darah.', 'Kurangi porsi minuman/makanan manis hari ini, ganti dengan air putih atau teh tanpa gula. Cek gula darah kalau perlu.', 1, '2026-09-27 18:07:40'),
(15, 30.1, 45.0, 'diabetes', 'Bahaya', 'Konsumsi gula kamu udah melewati batas aman harian buat penderita diabetes! Risiko lonjakan gula darah cukup tinggi.', 'Disarankan olahraga lebih intensif (40-50 menit) seperti jalan cepat atau senam ringan, sesuai kemampuan tubuh.', 'Stop dulu semua minuman/makanan manis untuk hari ini dan besok. Segera konsultasi ke dokter kalau muncul gejala tidak nyaman.', 1, '2026-09-27 18:07:40'),
(16, 45.1, NULL, 'diabetes', 'Kritis', 'Konsumsi gula kamu sangat tinggi dan berbahaya buat kondisi diabetes kamu! Segera ambil tindakan.', 'Hindari aktivitas berat dulu, fokus istirahat. Kalau kondisi tubuh memungkinkan, jalan santai ringan saja.', 'Segera hentikan konsumsi gula tambahan. Periksa gula darah sekarang dan hubungi dokter/tenaga medis kalau ada gejala pusing, lemas, atau haus berlebihan.', 1, '2026-09-27 18:07:40'),
(17, 15.1, 25.0, 'normal', 'Sedang', 'Konsumsi gula kamu udah mendekati batas ideal harian (25g). Masih aman, tapi mulai kurangi minuman manis tambahan.', 'Jalan santai 15-20 menit atau naik tangga beberapa kali cukup buat imbangin.', 'Kurangi tambahan gula di makanan/minuman lain hari ini, perbanyak air putih.', 1, '2026-09-27 18:09:28'),
(18, 40.1, 60.0, 'normal', 'Tinggi', 'Konsumsi gula kamu udah cukup tinggi hari ini, mendekati batas maksimal WHO (50g). Perlu perhatian lebih.', 'Olahraga sedang-berat 30-40 menit seperti jogging atau bersepeda buat bantu bakar kalori berlebih.', 'Stop dulu tambahan minuman manis hari ini, ganti dengan air putih atau infused water.', 1, '2026-09-27 18:09:28');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `foto_profil` varchar(255) DEFAULT NULL,
  `berat_badan` int(11) DEFAULT NULL,
  `usia` int(11) DEFAULT NULL,
  `riwayat_diabetes` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `nama`, `username`, `email`, `password`, `role`, `foto_profil`, `berat_badan`, `usia`, `riwayat_diabetes`, `created_at`) VALUES
(1, 'Fahri Rafi Heirvandi', 'admin', 'admin@sugarcalc.com', '$2y$10$QMsScAbu9lb31YiFc2iZmO6ne16P1vs8p39tV0rzFvoTeya1DrKoS', 'admin', NULL, NULL, NULL, 0, '2026-09-27 08:55:49'),
(2, 'Rina Wijaya', 'rina', 'rina@gmail.com', '$2y$10$XrsI6Oa5yWbMo1Eb7NCO0uxK/JGISfgon745k6Y/vVYTYDSxM9Ksm', 'user', NULL, 69, 23, 0, '2026-09-27 08:55:49'),
(3, 'Fahri Rafi Heirvandi', 'Fahri', NULL, '$2y$10$Dq98lWtsuPk1R0hSIJOxB.GM/gt7X6Rv2f6jrH58BOq4CetwkRGTi', 'user', 'uploads/profil/profil_6ab8fc0e95192.jpg', 45, 21, 0, '2026-09-27 10:39:49');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `konsumsi`
--
ALTER TABLE `konsumsi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `minuman_id` (`minuman_id`);

--
-- Indeks untuk tabel `minuman`
--
ALTER TABLE `minuman`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `rules_risiko`
--
ALTER TABLE `rules_risiko`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `konsumsi`
--
ALTER TABLE `konsumsi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT untuk tabel `minuman`
--
ALTER TABLE `minuman`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT untuk tabel `rules_risiko`
--
ALTER TABLE `rules_risiko`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `konsumsi`
--
ALTER TABLE `konsumsi`
  ADD CONSTRAINT `konsumsi_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `konsumsi_ibfk_2` FOREIGN KEY (`minuman_id`) REFERENCES `minuman` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
