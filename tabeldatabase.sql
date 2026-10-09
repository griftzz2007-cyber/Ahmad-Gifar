"CREATE DATABASE IF NOT EXISTS nuri_jaya_motor"

"CREATE TABLE IF NOT EXISTS penjualan_mobil (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    nama_pemilik VARCHAR(255) NOT NULL,
    no_telepon VARCHAR(20) NOT NULL,
    email VARCHAR(255),
    nama_mobil VARCHAR(255) NOT NULL,
    merk VARCHAR(100) NOT NULL,
    tahun INT(4) NOT NULL,
    harga_penawaran DECIMAL(15,2) NOT NULL,
    transmisi ENUM('Manual', 'Automatic') NOT NULL,
    warna VARCHAR(50),
    kilometer INT(11) NOT NULL,
    kondisi_mobil TEXT,
    alasan_jual TEXT,
    gambar VARCHAR(255),
    status_penawaran ENUM('Pending', 'Dihubungi', 'Diterima', 'Ditolak') DEFAULT 'Pending',
    catatan_admin TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";