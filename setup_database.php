<?php
$host = 'localhost';
$user = 'root';
$pass = '';

$conn = mysqli_connect($host, $user, $pass);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

$sql_db = "CREATE DATABASE IF NOT EXISTS nuri_jaya_motor";
if (mysqli_query($conn, $sql_db)) {
    echo "✓ Database berhasil dibuat atau sudah ada<br>";
} else {
    echo "✗ Error membuat database: " . mysqli_error($conn) . "<br>";
}

mysqli_select_db($conn, 'nuri_jaya_motor');

$sql_table = "CREATE TABLE IF NOT EXISTS penjualan_mobil (
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

if (mysqli_query($conn, $sql_table)) {
    echo "✓ Tabel penjualan_mobil berhasil dibuat atau sudah ada<br>";
} else {
    echo "✗ Error membuat tabel: " . mysqli_error($conn) . "<br>";
}

if (!file_exists('uploads')) {
    mkdir('uploads', 0777, true);
    echo "✓ Folder uploads berhasil dibuat<br>";
}

echo "<br><strong> Setup database selesai!</strong><br><br>";
echo "<div style='background: #f0f0f0; padding: 15px; border-radius: 8px; margin-top: 20px;'>";
echo "<h3>Langkah selanjutnya:</h3>";
echo "<a href='jual_mobil.php' style='display: inline-block; padding: 10px 20px; background: #4CAF50; color: white; text-decoration: none; border-radius: 5px; margin: 5px;'>Form Jual Mobil (Pelanggan)</a><br>";
echo "<a href='admin_penjualan.php' style='display: inline-block; padding: 10px 20px; background: #2196F3; color: white; text-decoration: none; border-radius: 5px; margin: 5px;'>Admin Panel</a>";
echo "</div>";

mysqli_close($conn);
?>
