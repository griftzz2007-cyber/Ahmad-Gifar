# Soppeng Jaya Motor - Website Jual Beli Mobil Bekas

## Deskripsi Proyek
Website ini merupakan platform jual beli mobil bekas yang dikembangkan sebagai **Tugas UTS (Ujian Tengah Semester)**. Website ini menyediakan fitur katalog mobil bekas, form penjualan mobil, dan panel admin untuk mengelola penawaran yang masuk.

**Soppeng Jaya Motor** adalah dealer mobil bekas yang menawarkan berbagai pilihan kendaraan berkualitas dengan harga bersaing dan proses transaksi yang transparan.

##  Fitur Utama

### 1. **Halaman Utama (Home)**
- Hero section dengan tagline menarik
- Informasi tentang keunggulan layanan
- Koleksi mobil unggulan dengan detail harga dan lokasi
- Review pelanggan
- Tombol contact langsung ke WhatsApp

### 2. **Halaman Koleksi Mobil**
- Menampilkan semua mobil yang tersedia
- Informasi detail: harga, lokasi, spesifikasi
- Tombol hubungi langsung via WhatsApp

### 3. **Halaman Jual Mobil** (`jual_mobil.php`)
- Form untuk pemilik mobil yang ingin menjual kendaraannya
- Input data lengkap: nama pemilik, kontak, detail mobil
- Upload foto mobil
- Validasi form dan feedback sukses/error

### 4. **Panel Admin** (`admin_penjualan.php`)
- Dashboard untuk mengelola penawaran penjualan mobil
- Tabel data lengkap dengan foto
- Update status penawaran (Pending, Dihubungi, Diterima, Ditolak)
- Tambah catatan admin untuk setiap penawaran
- Hapus data yang tidak diinginkan

### 5. **Halaman Kontak**
- Informasi kontak lengkap
- Form kontak (jika ada)

## Teknologi yang Digunakan

- **Frontend:**
  - HTML5
  - CSS3 (dengan gradient modern dan animasi)
  - JavaScript (vanilla JS)
  - Font Awesome Icons
  - ScrollReveal.js untuk animasi scroll

- **Backend:**
  - PHP (Native)
  - MySQL Database

- **Server:**
  - XAMPP (Apache & MySQL)

##  Struktur Folder

```
nuri_jaya_motor/
├── index.html                 # Halaman utama
├── collection.html            # Halaman koleksi mobil
├── contact.html               # Halaman kontak
├── jual_mobil.php            # Form penjualan mobil
├── admin_penjualan.php       # Panel admin
├── config.php                # Konfigurasi database
├── setup_database.php        # Setup otomatis database
├── tabeldatabase.sql         # Schema database
├── style.css                 # Stylesheet utama
├── index.js                  # JavaScript utama
├── contoh mobil/             # Folder asset gambar
│   └── img/                  # Gambar hero, about, dll
├── mobil nuri jaya motor/    # Foto-foto mobil katalog
└── uploads/                  # Folder upload foto dari user
```

## Database

### Nama Database: `nuri_jaya_motor`

### Tabel: `penjualan_mobil`

| Field | Tipe | Keterangan |
|-------|------|------------|
| id | INT(11) | Primary Key, Auto Increment |
| nama_pemilik | VARCHAR(255) | Nama pemilik mobil |
| no_telepon | VARCHAR(20) | Nomor telepon |
| email | VARCHAR(255) | Email pemilik |
| nama_mobil | VARCHAR(255) | Nama/model mobil |
| merk | VARCHAR(100) | Merk mobil |
| tahun | INT(4) | Tahun pembuatan |
| harga_penawaran | DECIMAL(15,2) | Harga yang ditawarkan |
| transmisi | ENUM | Manual/Automatic |
| warna | VARCHAR(50) | Warna mobil |
| kilometer | INT(11) | Jarak tempuh (km) |
| kondisi_mobil | TEXT | Deskripsi kondisi mobil |
| alasan_jual | TEXT | Alasan penjualan |
| gambar | VARCHAR(255) | Nama file gambar |
| status_penawaran | ENUM | Pending/Dihubungi/Diterima/Ditolak |
| catatan_admin | TEXT | Catatan dari admin |
| created_at | TIMESTAMP | Waktu dibuat |
| updated_at | TIMESTAMP | Waktu update terakhir |

## Cara Instalasi

### Prasyarat
- XAMPP (atau stack LAMP/WAMP lainnya)
- Web browser modern (Chrome, Firefox, Edge)
- PHP 7.4+ 
- MySQL 5.7+

### Langkah Instalasi

1. **Clone atau Download Repository**
   ```bash
   git clone https://github.com/griftzz2007-cyber/Ahmad-Gifar.git
   ```

2. **Pindahkan Folder ke htdocs**
   ```
   Pindahkan folder ke: C:\xampp\htdocs\nuri_jaya_motor
   ```

3. **Start Apache dan MySQL di XAMPP**
   - Buka XAMPP Control Panel
   - Start Apache
   - Start MySQL

4. **Setup Database**
   
   **Opsi 1: Otomatis (Rekomendasi)**
   - Buka browser dan akses: `http://localhost/nuri_jaya_motor/setup_database.php`
   - Script akan otomatis membuat database dan tabel

   **Opsi 2: Manual**
   - Buka phpMyAdmin: `http://localhost/phpmyadmin`
   - Buat database baru dengan nama: `nuri_jaya_motor`
   - Jalankan query SQL dari file `tabeldatabase.sql`

5. **Konfigurasi Database** (Opsional)
   
   Jika menggunakan kredensial MySQL yang berbeda, edit file `config.php`:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   define('DB_NAME', 'nuri_jaya_motor');
   ```

6. **Akses Website**
   - Halaman Utama: `http://localhost/nuri_jaya_motor/index.html`
   - Panel Admin: `http://localhost/nuri_jaya_motor/admin_penjualan.php`
   - Form Jual Mobil: `http://localhost/nuri_jaya_motor/jual_mobil.php`

## Fitur WhatsApp Integration

Website ini terintegrasi dengan WhatsApp Business untuk kemudahan komunikasi:
- Tombol "Hubungi" pada setiap mobil
- Link langsung ke chat WhatsApp dealer
- Format: `https://wa.me/message/KEAN25HOEDICC1`

## Desain & UI/UX

- **Responsive Design**: Tampilan optimal di desktop, tablet, dan mobile
- **Modern Gradient**: Warna orange-red yang eye-catching
- **Smooth Animations**: ScrollReveal untuk user experience yang lebih baik
- **Clean Layout**: Navigasi mudah dan intuitif
- **Card-based Design**: Tampilan mobil dalam card yang modern

## Keamanan

- Input sanitization dengan `mysqli_real_escape_string()`
- Validasi file upload (hanya gambar JPG, PNG, GIF)
- Prepared statements untuk mencegah SQL Injection
- Validasi form di sisi client dan server

## Kontribusi

Proyek ini dibuat untuk keperluan akademik (Tugas UTS). Untuk saran dan perbaikan, silakan buat issue atau pull request.

## Developer

- **Nama**: Ahmad Gifar
- **Mata Kuliah**: Pemrograman Web
- **Semester**: 3
- **Tahun**: 2026

## Catatan Pengembangan

### Versi 1.0.0 (Initial Release)
- ✅ Halaman landing page dengan hero section
- ✅ Katalog mobil dengan detail
- ✅ Form penjualan mobil dengan upload gambar
- ✅ Panel admin untuk mengelola penawaran
- ✅ Integrasi WhatsApp
- ✅ Database MySQL
- ✅ Responsive design

### Rencana Pengembangan (Future Updates)
- 🔄 Fitur pencarian dan filter mobil
- 🔄 Sistem login admin
- 🔄 Dashboard statistik penjualan
- 🔄 Export data ke Excel/PDF
- 🔄 Fitur notifikasi email

## Lisensi

Proyek ini dibuat untuk keperluan edukasi dan tugas akademik.

## Kontak

Untuk pertanyaan atau informasi lebih lanjut:
- **GitHub**: [griftzz2007-cyber](https://github.com/griftzz2007-cyber)
- **Repository**: [Ahmad-Gifar](https://github.com/griftzz2007-cyber/Ahmad-Gifar)

---
 **Jangan lupa berikan bintang jika proyek ini membantu Anda!**

*Dibuat dengan senang hati untuk memenuhi Tugas UTS*
