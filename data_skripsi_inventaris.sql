-- SQL Script untuk Input Data Master (Pemasok, Barang) dan Data Stok / Transaksi Skripsi
-- Ditujukan untuk database MySQL: db_inventory_rop (tanpa tabel pengadaan_barang yang sudah dihapus)

SET FOREIGN_KEY_CHECKS = 0;
DELETE FROM `transaksi`;
DELETE FROM `barang`;
DELETE FROM `pemasok`;
SET FOREIGN_KEY_CHECKS = 1;

-- 1. INPUT DATA PEMASOK / SUPPLIER
INSERT INTO `pemasok` (`id_pemasok`, `nama_pemasok`, `alamat`, `telepon`, `created_at`, `updated_at`) VALUES
('PMS-001', 'Sinar Timur Abadi', 'Jl. Perindustrian Timur No. 10, Makassar', '081122334455', NOW(), NOW()),
('PMS-002', 'Yohan', 'Jl. Veteran Selatan No. 45, Makassar', '081233445566', NOW(), NOW()),
('PMS-003', 'Puroxa', 'Jl. Bawakaraeng No. 88, Makassar', '081344556677', NOW(), NOW()),
('PMS-004', 'Samarana', 'Jl. Perintis Kemerdekaan KM 10, Makassar', '081455667788', NOW(), NOW()),
('PMS-005', 'Setia Tunggal', 'Jl. Kandea No. 12, Makassar', '081566778899', NOW(), NOW()),
('PMS-006', 'Karisma', 'Jl. Tentara Pelajar No. 25, Makassar', '081677889900', NOW(), NOW()),
('PMS-007', 'Samalona', 'Jl. Somba Opu No. 70, Makassar', '081788990011', NOW(), NOW()),
('PMS-008', 'Bola Balu', 'Jl. Toddopuli Raya No. 15, Makassar', '081899001122', NOW(), NOW()),
('PMS-009', 'Multi Mandiri', 'Jl. Pengayoman No. 33, Makassar', '081900112233', NOW(), NOW()),
('PMS-010', 'Puan', 'Jl. Sultan Alauddin No. 99, Makassar', '082111223344', NOW(), NOW()),
('PMS-011', 'Makmur', 'Jl. Cendrawasih No. 50, Makassar', '082222334455', NOW(), NOW()),
('PMS-012', 'Sinar Balado', 'Jl. Gunung Bawakaraeng No. 101, Makassar', '082333445566', NOW(), NOW()),
('PMS-013', 'Maju Makmur', 'Jl. Landak Baru No. 18, Makassar', '082444556677', NOW(), NOW());

-- 2. INPUT DATA MASTER BARANG (Sesuai Tabel Data Master Skripsi 1 - 24)
INSERT INTO `barang` (`id_barang`, `id_pemasok`, `lead_time_hari`, `lead_time_menit`, `nama_barang`, `satuan`, `satuan_besar`, `isi_per_satuan_besar`, `stok_saat_ini`, `stok_minimum`, `harga_beli`, `harga_jual`, `biaya_pesan`, `biaya_simpan`, `status_barang`, `deleted_at`, `created_at`, `updated_at`) VALUES
('8998009040023', 'PMS-001', 0, 20, 'Teh kotak', 'KTK', 'DUS', 24, 44, 30, 3296.00, 5000.00, 25000.00, 500.00, 'Aktif', NULL, NOW(), NOW()),
('8997001600146', 'PMS-002', 0, 15, 'Teh pucuk', 'BTL', 'DUS', 24, 236, 100, 3083.00, 5000.00, 25000.00, 500.00, 'Aktif', NULL, NOW(), NOW()),
('8997014250090', 'PMS-003', 0, 10, 'Puroxa 1.500 ml', 'BTL', 'DUS', 12, 198, 80, 3917.00, 6000.00, 25000.00, 500.00, 'Aktif', NULL, NOW(), NOW()),
('8996006008491', 'PMS-002', 0, 20, 'Nipis madu', 'BTL', 'DUS', 24, 82, 50, 4083.00, 7000.00, 25000.00, 500.00, 'Aktif', NULL, NOW(), NOW()),
('8997030131070', 'PMS-004', 0, 25, 'Gress jeruk', 'BTL', 'DUS', 24, 142, 42, 3880.00, 5000.00, 25000.00, 500.00, 'Aktif', NULL, NOW(), NOW()),
('8992760223115', 'PMS-005', 0, 60, 'Oreo coklat', 'BKS', 'DUS', 24, 26, 10, 8100.00, 10000.00, 25000.00, 500.00, 'Aktif', NULL, NOW(), NOW()),
('8993175557733', 'PMS-006', 0, 15, 'Nabati coklat', 'BKS', 'DUS', 20, 83, 50, 4743.00, 6000.00, 25000.00, 500.00, 'Aktif', NULL, NOW(), NOW()),
('8996001301142', 'PMS-002', 0, 15, 'Roma kelapa', 'BKS', 'DUS', 20, 39, 20, 9300.00, 12000.00, 25000.00, 500.00, 'Aktif', NULL, NOW(), NOW()),
('8992696525036', 'PMS-007', 0, 25, 'Dancow coklat sachet', 'BKS', 'RENTENG', 10, 160, 60, 3524.00, 4500.00, 25000.00, 500.00, 'Aktif', NULL, NOW(), NOW()),
('089686535044', 'PMS-008', 0, 60, 'Bubur sun beras merah', 'KTK', 'DUS', 16, 39, 20, 9500.00, 11000.00, 25000.00, 500.00, 'Aktif', NULL, NOW(), NOW()),
('2014', 'PMS-006', 0, 15, 'Telur', 'Butir', 'RAK', 30, 570, 200, 2000.00, 25000.00, 25000.00, 500.00, 'Aktif', NULL, NOW(), NOW()),
('8992857010528', 'PMS-009', 0, 60, 'Vape jumbo bakar', 'KTK', 'DUS', 40, 34, 20, 4532.00, 6000.00, 25000.00, 500.00, 'Aktif', NULL, NOW(), NOW()),
('8998866084959', 'PMS-010', 0, 5, 'Daia putih 280 gram', 'BKS', 'DUS', 24, 48, 20, 4375.00, 6000.00, 25000.00, 500.00, 'Aktif', NULL, NOW(), NOW()),
('899886600097', 'PMS-005', 0, 5, 'Jas-Jus jeruk', 'RENTENG', 'DUS', 24, 100, 50, 3330.00, 4000.00, 25000.00, 500.00, 'Aktif', NULL, NOW(), NOW()),
('GL-001', 'PMS-006', 0, 15, 'Gula 1 kg', 'BKS', 'KARUNG', 50, 410, 200, 18900.00, 20000.00, 25000.00, 500.00, 'Aktif', NULL, NOW(), NOW()),
('899305500601', 'PMS-011', 0, 80, 'Teh bendera kotak', 'KTK', 'DUS', 24, 116, 80, 6146.00, 7500.00, 25000.00, 500.00, 'Aktif', NULL, NOW(), NOW()),
('8991002105409', 'PMS-006', 0, 15, 'Kapal api 380 gram', 'BKS', 'DUS', 12, 100, 50, 34000.00, 37000.00, 25000.00, 500.00, 'Aktif', NULL, NOW(), NOW()),
('899338098842', 'PMS-012', 0, 10, 'Uang mas 350 gram', 'BKS', 'DUS', 24, 100, 50, 23000.00, 26000.00, 25000.00, 500.00, 'Aktif', NULL, NOW(), NOW()),
('08968610527', 'PMS-013', 0, 80, 'Indomie kari ayam', 'BKS', 'DUS', 40, 200, 80, 3000.00, 3500.00, 25000.00, 500.00, 'Aktif', NULL, NOW(), NOW()),
('8991100000020', 'PMS-001', 0, 20, 'Golda latte', 'BTL', 'DUS', 24, 240, 80, 3100.00, 4500.00, 25000.00, 500.00, 'Aktif', NULL, NOW(), NOW()),
('8991100000021', 'PMS-001', 0, 20, 'Good day capucino botol', 'BTL', 'DUS', 24, 96, 30, 5000.00, 6500.00, 25000.00, 500.00, 'Aktif', NULL, NOW(), NOW()),
('8991100000022', 'PMS-005', 0, 15, 'Tiga sapi 500 gr putih', 'KALENG', 'DUS', 48, 96, 30, 12000.00, 14500.00, 25000.00, 500.00, 'Aktif', NULL, NOW(), NOW()),
('8991100000023', 'PMS-003', 0, 30, 'Minyak goreng Bimoli 2l', 'POUCH', 'DUS', 6, 72, 20, 32000.00, 35000.00, 25000.00, 500.00, 'Aktif', NULL, NOW(), NOW()),
('8991100000024', 'PMS-013', 0, 45, 'Beras 5kg', 'SAK', 'KARUNG', 5, 100, 30, 62000.00, 68000.00, 25000.00, 500.00, 'Aktif', NULL, NOW(), NOW());

-- 3. INPUT DATA TRANSAKSI (Stok Masuk & Keluar sesuai Tabel Data Stok & Pengirinan Skripsi)
INSERT INTO `transaksi` (`id_transaksi`, `id_barang`, `tanggal`, `jenis`, `jumlah`, `keterangan`, `created_at`, `updated_at`) VALUES
('TRX-001', '8998009040023', '2026-07-01', 'Masuk', 480, 'Penerimaan Stok Bulanan - Sinar Timur Abadi', NOW(), NOW()),
('TRX-002', '8998009040023', '2026-07-02', 'Keluar', 400, 'Penjualan Bulanan (Demand 10 ktk/hari)', NOW(), NOW()),
('TRX-003', '8997001600146', '2026-06-22', 'Masuk', 480, 'Penerimaan Stok Bulanan - Yohan', NOW(), NOW()),
('TRX-004', '8997001600146', '2026-06-23', 'Keluar', 400, 'Penjualan Bulanan (Demand 15 btl/hari)', NOW(), NOW()),
('TRX-005', '8997014250090', '2026-06-29', 'Masuk', 384, 'Penerimaan Stok Bulanan - Puroxa', NOW(), NOW()),
('TRX-006', '8997014250090', '2026-06-30', 'Keluar', 300, 'Penjualan Bulanan (Demand 6 btl/hari)', NOW(), NOW()),
('TRX-007', '8996006008491', '2026-06-23', 'Masuk', 480, 'Penerimaan Stok Bulanan - Yohan', NOW(), NOW()),
('TRX-008', '8996006008491', '2026-06-24', 'Keluar', 400, 'Penjualan Bulanan (Demand 4 btl/hari)', NOW(), NOW()),
('TRX-009', '8997030131070', '2026-07-07', 'Masuk', 200, 'Penerimaan Stok Bulanan - Samarana', NOW(), NOW()),
('TRX-010', '8997030131070', '2026-07-08', 'Keluar', 150, 'Penjualan Bulanan (Demand 10 btl/hari)', NOW(), NOW()),
('TRX-011', '8992760223115', '2026-06-09', 'Masuk', 24, 'Penerimaan Stok - Setia Tunggal', NOW(), NOW()),
('TRX-012', '8992760223115', '2026-06-10', 'Keluar', 20, 'Penjualan Bulanan (Demand 3 bks/hari)', NOW(), NOW()),
('TRX-013', '8993175557733', '2026-06-23', 'Masuk', 120, 'Penerimaan Stok - Karisma', NOW(), NOW()),
('TRX-014', '8993175557733', '2026-06-24', 'Keluar', 100, 'Penjualan Bulanan (Demand 6 bks/hari)', NOW(), NOW()),
('TRX-015', '8996001301142', '2026-06-22', 'Masuk', 144, 'Penerimaan Stok - Yohan', NOW(), NOW()),
('TRX-016', '8996001301142', '2026-06-23', 'Keluar', 100, 'Penjualan Bulanan (Demand 6 bks/hari)', NOW(), NOW()),
('TRX-017', '8992696525036', '2026-07-03', 'Masuk', 156, 'Penerimaan Stok - Samalona', NOW(), NOW()),
('TRX-018', '8992696525036', '2026-07-04', 'Keluar', 120, 'Penjualan Bulanan (Demand 10 bks/hari)', NOW(), NOW()),
('TRX-019', '089686535044', '2026-06-01', 'Masuk', 80, 'Penerimaan Stok - Bola Balu', NOW(), NOW()),
('TRX-020', '089686535044', '2026-06-02', 'Keluar', 40, 'Penjualan Bulanan (Demand 5 ktk/hari)', NOW(), NOW()),
('TRX-021', '2014', '2026-07-08', 'Masuk', 2160, 'Penerimaan Stok Telur - Karisma', NOW(), NOW()),
('TRX-022', '2014', '2026-07-09', 'Keluar', 2160, 'Penjualan Bulanan (Demand 60 butir/hari)', NOW(), NOW()),
('TRX-023', '8992857010528', '2026-07-07', 'Masuk', 60, 'Penerimaan Stok - Multi Mandiri', NOW(), NOW()),
('TRX-024', '8992857010528', '2026-07-08', 'Keluar', 40, 'Penjualan Bulanan', NOW(), NOW()),
('TRX-025', '8998866084959', '2026-07-06', 'Masuk', 96, 'Penerimaan Stok - Puan', NOW(), NOW()),
('TRX-026', '8998866084959', '2026-07-07', 'Keluar', 72, 'Penjualan Bulanan', NOW(), NOW()),
('TRX-027', '899886600097', '2026-06-08', 'Masuk', 100, 'Penerimaan Stok - Setia Tunggal', NOW(), NOW()),
('TRX-028', '899886600097', '2026-06-09', 'Keluar', 75, 'Penjualan Bulanan', NOW(), NOW()),
('TRX-029', 'GL-001', '2026-07-01', 'Masuk', 400, 'Penerimaan Stok Gula - Karisma', NOW(), NOW()),
('TRX-030', 'GL-001', '2026-07-02', 'Keluar', 300, 'Penjualan Bulanan (Demand 10 kg/hari)', NOW(), NOW()),
('TRX-031', '899305500601', '2026-06-22', 'Masuk', 80, 'Penerimaan Stok - Makmur', NOW(), NOW()),
('TRX-032', '899305500601', '2026-06-23', 'Keluar', 60, 'Penjualan Bulanan', NOW(), NOW()),
('TRX-033', '8991002105409', '2026-06-22', 'Masuk', 108, 'Penerimaan Stok - Karisma', NOW(), NOW()),
('TRX-034', '8991002105409', '2026-06-23', 'Keluar', 100, 'Penjualan Bulanan (Demand 5 bks/hari)', NOW(), NOW()),
('TRX-035', '899338098842', '2026-06-23', 'Masuk', 72, 'Penerimaan Stok - Sinar Balado', NOW(), NOW()),
('TRX-036', '899338098842', '2026-06-24', 'Keluar', 65, 'Penjualan Bulanan (Demand 5 bks/hari)', NOW(), NOW()),
('TRX-037', '08968610527', '2026-06-23', 'Masuk', 480, 'Penerimaan Stok Indomie - Maju Makmur', NOW(), NOW()),
('TRX-038', '08968610527', '2026-06-24', 'Keluar', 400, 'Penjualan Bulanan (Demand 40 bks/hari)', NOW(), NOW()),
('TRX-039', '8991100000020', '2026-06-29', 'Masuk', 240, 'Penerimaan Stok - Sinar Timur Abadi', NOW(), NOW()),
('TRX-040', '8991100000020', '2026-06-30', 'Keluar', 240, 'Penjualan Bulanan (Demand 10 btl/hari)', NOW(), NOW()),
('TRX-041', '8991100000021', '2026-06-10', 'Masuk', 96, 'Penerimaan Stok - Sinar Timur Abadi', NOW(), NOW()),
('TRX-042', '8991100000021', '2026-06-11', 'Keluar', 80, 'Penjualan Bulanan (Demand 10 btl/hari)', NOW(), NOW()),
('TRX-043', '8991100000022', '2026-07-01', 'Masuk', 96, 'Penerimaan Stok - Setia Tunggal', NOW(), NOW()),
('TRX-044', '8991100000022', '2026-07-02', 'Keluar', 85, 'Penjualan Bulanan', NOW(), NOW()),
('TRX-045', '8991100000023', '2026-06-01', 'Masuk', 72, 'Penerimaan Stok - Puroxa', NOW(), NOW()),
('TRX-046', '8991100000023', '2026-06-02', 'Keluar', 65, 'Penjualan Bulanan', NOW(), NOW()),
('TRX-047', '8991100000024', '2026-06-11', 'Masuk', 100, 'Penerimaan Stok - Maju Makmur', NOW(), NOW()),
('TRX-048', '8991100000024', '2026-06-12', 'Keluar', 100, 'Penjualan Bulanan', NOW(), NOW());
