<?php
require_once 'config.php';

$success = '';
$error = '';

// Cek jika ada parameter success dari redirect
if (isset($_GET['success']) && $_GET['success'] == '1') {
    $success = "Terima kasih! Data mobil Anda berhasil dikirim. Tim kami akan segera menghubungi Anda.";
}

// Proses form submit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_pemilik = mysqli_real_escape_string($conn, $_POST['nama_pemilik']);
    $no_telepon = mysqli_real_escape_string($conn, $_POST['no_telepon']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $nama_mobil = mysqli_real_escape_string($conn, $_POST['nama_mobil']);
    $merk = mysqli_real_escape_string($conn, $_POST['merk']);
    $tahun = mysqli_real_escape_string($conn, $_POST['tahun']);
    $harga_penawaran = mysqli_real_escape_string($conn, $_POST['harga_penawaran']);
    $transmisi = mysqli_real_escape_string($conn, $_POST['transmisi']);
    $warna = mysqli_real_escape_string($conn, $_POST['warna']);
    $kilometer = mysqli_real_escape_string($conn, $_POST['kilometer']);
    $kondisi_mobil = mysqli_real_escape_string($conn, $_POST['kondisi_mobil']);
    $alasan_jual = mysqli_real_escape_string($conn, $_POST['alasan_jual']);
    
    // Upload gambar
    $gambar = '';
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['gambar']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (in_array($ext, $allowed)) {
            // Pastikan folder uploads ada
            if (!file_exists('uploads')) {
                mkdir('uploads', 0777, true);
            }
            
            $newname = uniqid() . '.' . $ext;
            $upload_path = 'uploads/' . $newname;
            
            if (move_uploaded_file($_FILES['gambar']['tmp_name'], $upload_path)) {
                $gambar = $newname;
            } else {
                $error = "Gagal upload gambar. Pastikan folder uploads memiliki permission yang tepat.";
            }
        } else {
            $error = "Format gambar tidak didukung. Gunakan JPG, JPEG, PNG, atau GIF.";
        }
    }
    
    if (empty($error)) {
        $sql = "INSERT INTO penjualan_mobil (nama_pemilik, no_telepon, email, nama_mobil, merk, tahun, harga_penawaran, transmisi, warna, kilometer, kondisi_mobil, alasan_jual, gambar) 
                VALUES ('$nama_pemilik', '$no_telepon', '$email', '$nama_mobil', '$merk', '$tahun', '$harga_penawaran', '$transmisi', '$warna', '$kilometer', '$kondisi_mobil', '$alasan_jual', '$gambar')";
        
        if (mysqli_query($conn, $sql)) {
            $success = "Terima kasih! Data mobil Anda berhasil dikirim. Tim kami akan segera menghubungi Anda.";
            
            // Reset form dengan redirect
            if (!empty($success)) {
                echo "<script>
                    setTimeout(function() {
                        window.location.href = 'jual_mobil.php?success=1';
                    }, 2000);
                </script>";
            }
        } else {
            $error = "Maaf, terjadi kesalahan database: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jual Mobil Anda - Soppeng Jaya Motor</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #ff9800 0%, #ff5722 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
        }

        .header h1 {
            color: #333;
            font-size: 2.5em;
            margin-bottom: 10px;
        }

        .header p {
            color: #666;
            font-size: 1.1em;
        }

        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            font-weight: 500;
            text-align: center;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 2px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 2px solid #f5c6cb;
        }

        .form-group {
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
            font-size: 14px;
        }

        .required {
            color: #f44336;
        }

        input[type="text"],
        input[type="email"],
        input[type="tel"],
        input[type="number"],
        input[type="file"],
        select,
        textarea {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 15px;
            transition: all 0.3s;
            font-family: inherit;
        }

        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .btn {
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 10px;
            font-size: 18px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 10px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: #f0f0f0;
            color: #333;
        }

        .btn-secondary:hover {
            background: #e0e0e0;
        }

        .info-box {
            background: #e3f2fd;
            border-left: 4px solid #2196F3;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 30px;
        }

        .info-box h3 {
            color: #1976D2;
            margin-bottom: 10px;
        }

        .info-box ul {
            margin-left: 20px;
            color: #555;
        }

        .info-box li {
            margin-bottom: 5px;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .container {
                padding: 25px;
            }

            .header h1 {
                font-size: 2em;
            }
        }

        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="index.html" class="back-link">← Kembali ke Beranda</a>
        
        <div class="header">
            <h1> Jual Mobil Anda</h1>
            <p>Isi formulir di bawah ini dan kami akan segera menghubungi Anda</p>
        </div>

        <?php if ($success): ?>
            <div class="alert alert-success">
                ✓ <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-error">
                ✗ <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <div class="info-box">
            <h3> Syarat & Ketentuan</h3>
            <ul>
                <li>Mobil dalam kondisi layak jalan</li>
                <li>Surat-surat lengkap (STNK, BPKB)</li>
                <li>Foto mobil yang jelas</li>
                <li>Informasi yang diberikan harus akurat</li>
            </ul>
        </div>

        <form method="POST" enctype="multipart/form-data">
            <h3 style="margin-bottom: 20px; color: #667eea;">Data Pemilik</h3>
            
            <div class="form-group">
                <label>Nama Lengkap <span class="required">*</span></label>
                <input type="text" name="nama_pemilik" required placeholder="Masukkan nama lengkap Anda">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>No. Telepon/WhatsApp <span class="required">*</span></label>
                    <input type="tel" name="no_telepon" required placeholder="08xxxxxxxxxx">
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="email@example.com">
                </div>
            </div>

            <h3 style="margin: 30px 0 20px; color: #667eea;">Data Mobil</h3>

            <div class="form-group">
                <label>Nama Mobil <span class="required">*</span></label>
                <input type="text" name="nama_mobil" required placeholder="Contoh: Honda Jazz RS">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Merk <span class="required">*</span></label>
                    <select name="merk" required>
                        <option value="">Pilih Merk</option>
                        <option value="Toyota">Toyota</option>
                        <option value="Honda">Honda</option>
                        <option value="Mitsubishi">Mitsubishi</option>
                        <option value="Daihatsu">Daihatsu</option>
                        <option value="Suzuki">Suzuki</option>
                        <option value="Nissan">Nissan</option>
                        <option value="Mazda">Mazda</option>
                        <option value="BMW">BMW</option>
                        <option value="Mercedes">Mercedes</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Tahun Pembuatan <span class="required">*</span></label>
                    <input type="number" name="tahun" required min="1990" max="2025" placeholder="2020">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Harga Penawaran (Rp) <span class="required">*</span></label>
                    <input type="number" name="harga_penawaran" required placeholder="150000000">
                </div>

                <div class="form-group">
                    <label>Transmisi <span class="required">*</span></label>
                    <select name="transmisi" required>
                        <option value="">Pilih Transmisi</option>
                        <option value="Manual">Manual</option>
                        <option value="Automatic">Automatic</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Warna</label>
                    <input type="text" name="warna" placeholder="Hitam, Putih, Silver, dll">
                </div>

                <div class="form-group">
                    <label>Kilometer <span class="required">*</span></label>
                    <input type="number" name="kilometer" required placeholder="50000">
                </div>
            </div>

            <div class="form-group">
                <label>Kondisi Mobil <span class="required">*</span></label>
                <textarea name="kondisi_mobil" required placeholder="Jelaskan kondisi mobil (mesin, body, interior, dll)"></textarea>
            </div>

            <div class="form-group">
                <label>Alasan Jual</label>
                <textarea name="alasan_jual" placeholder="Opsional - Alasan Anda menjual mobil ini"></textarea>
            </div>

            <div class="form-group">
                <label>Foto Mobil</label>
                <input type="file" name="gambar" accept="image/*">
                <small style="color: #666; display: block; margin-top: 5px;">Format: JPG, PNG, GIF (Max 5MB)</small>
            </div>

            <button type="submit" class="btn btn-primary"> Kirim Penawaran</button>
            <button type="reset" class="btn btn-secondary"> Reset Form</button>
        </form>
    </div>
</body>
</html>

<?php mysqli_close($conn); ?>
