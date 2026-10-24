CREATE DATABASE inventaris_gudang;
USE inventaris_gudang;

-- Tabel Data Barang
CREATE TABLE barang (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode_barang VARCHAR(50) NOT NULL UNIQUE,
    nama_barang VARCHAR(100) NOT NULL,
    kategori VARCHAR(50) NOT NULL,
    stok INT NOT NULL DEFAULT 0,
    satuan VARCHAR(20) NOT NULL
);

-- Tabel Barang Masuk
CREATE TABLE barang_masuk (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_barang INT NOT NULL,
    jumlah INT NOT NULL,
    tanggal DATE NOT NULL,
    keterangan TEXT,
    FOREIGN KEY (id_barang) REFERENCES barang(id) ON DELETE CASCADE
);

-- Tabel Barang Keluar
CREATE TABLE barang_keluar (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_barang INT NOT NULL,
    jumlah INT NOT NULL,
    tanggal DATE NOT NULL,
    penerima VARCHAR(100) NOT NULL,
    FOREIGN KEY (id_barang) REFERENCES barang(id) ON DELETE CASCADE
);
