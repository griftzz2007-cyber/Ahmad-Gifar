<?php
require_once 'config.php';

if (isset($_POST['update_status'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $status = mysqli_real_escape_string($conn, $_POST['status_penawaran']);
    $catatan = mysqli_real_escape_string($conn, $_POST['catatan_admin']);
    
    $sql = "UPDATE penjualan_mobil SET status_penawaran = '$status', catatan_admin = '$catatan' WHERE id = $id";
    if (mysqli_query($conn, $sql)) {
        $success = "Status berhasil diupdate!";
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}

if (isset($_GET['delete'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete']);
    
    $sql_img = "SELECT gambar FROM penjualan_mobil WHERE id = $id";
    $result_img = mysqli_query($conn, $sql_img);
    $row_img = mysqli_fetch_assoc($result_img);
    
    $sql = "DELETE FROM penjualan_mobil WHERE id = $id";
    if (mysqli_query($conn, $sql)) {
        // Hapus file gambar
        if ($row_img['gambar'] && file_exists('uploads/' . $row_img['gambar'])) {
            unlink('uploads/' . $row_img['gambar']);
        }
        $success = "Data berhasil dihapus!";
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}

$sql = "SELECT * FROM penjualan_mobil ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);

$sql_stats = "SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN status_penawaran = 'Pending' THEN 1 ELSE 0 END) as pending,
    SUM(CASE WHEN status_penawaran = 'Dihubungi' THEN 1 ELSE 0 END) as dihubungi,
    SUM(CASE WHEN status_penawaran = 'Diterima' THEN 1 ELSE 0 END) as diterima,
    SUM(CASE WHEN status_penawaran = 'Ditolak' THEN 1 ELSE 0 END) as ditolak
    FROM penjualan_mobil";
$result_stats = mysqli_query($conn, $sql_stats);
$stats = mysqli_fetch_assoc($result_stats);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Penawaran Penjualan Mobil</title>
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
            padding: 20px;
        }

        .container {
            max-width: 1600px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #ff9800;
        }

        h1 {
            color: #333;
            font-size: 2.5em;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: linear-gradient(135deg, #ff9800 0%, #ff5722 100%);
            color: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .stat-card h3 {
            font-size: 2.5em;
            margin-bottom: 5px;
        }

        .stat-card p {
            font-size: 1.1em;
            opacity: 0.9;
        }

        .stat-pending { background: linear-gradient(135deg, #FFA726 0%, #FB8C00 100%); }
        .stat-dihubungi { background: linear-gradient(135deg, #42A5F5 0%, #1E88E5 100%); }
        .stat-diterima { background: linear-gradient(135deg, #66BB6A 0%, #43A047 100%); }
        .stat-ditolak { background: linear-gradient(135deg, #EF5350 0%, #E53935 100%); }

        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 500;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background: linear-gradient(135deg, #ff9800 0%, #ff5722 100%);
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 13px;
        }

        tr:hover {
            background: #f5f5f5;
        }

        .car-img {
            width: 100px;
            height: 75px;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        .status-pending { background: #fff3cd; color: #856404; }
        .status-dihubungi { background: #cce5ff; color: #004085; }
        .status-diterima { background: #d4edda; color: #155724; }
        .status-ditolak { background: #f8d7da; color: #721c24; }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-block;
            margin: 2px;
        }

        .btn-view {
            background: #2196F3;
            color: white;
        }

        .btn-edit {
            background: #4CAF50;
            color: white;
        }

        .btn-delete {
            background: #f44336;
            color: white;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 3px 10px rgba(0,0,0,0.2);
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            overflow: auto;
        }

        .modal-content {
            background: white;
            margin: 50px auto;
            padding: 30px;
            border-radius: 15px;
            max-width: 800px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #ff9800;
        }

        .close {
            font-size: 35px;
            font-weight: bold;
            color: #999;
            cursor: pointer;
            line-height: 1;
        }

        .close:hover {
            color: #333;
        }

        .detail-row {
            display: grid;
            grid-template-columns: 200px 1fr;
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .detail-label {
            font-weight: 600;
            color: #666;
        }

        .detail-value {
            color: #333;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }

        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 15px;
            font-family: inherit;
        }

        .form-group textarea {
            min-height: 80px;
            resize: vertical;
        }

        .btn-primary {
            background: linear-gradient(135deg, #ff9800 0%, #ff5722 100%);
            color: white;
            padding: 12px 30px;
            font-size: 16px;
        }

        .no-data {
            text-align: center;
            padding: 50px;
            color: #999;
            font-size: 18px;
        }

        .price {
            color: #ff9800;
            font-weight: 700;
        }

        @media (max-width: 768px) {
            .stats {
                grid-template-columns: 1fr 1fr;
            }
            
            table {
                font-size: 13px;
            }
            
            th, td {
                padding: 10px 5px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1> Admin - Penawaran Penjualan Mobil</h1>
            <a href="jual_mobil.php" class="btn btn-view">Lihat Form Pelanggan</a>
        </div>

        <?php if (isset($success)): ?>
            <div class="alert alert-success">✓ <?php echo $success; ?></div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="alert alert-error">✗ <?php echo $error; ?></div>
        <?php endif; ?>

        <div class="stats">
            <div class="stat-card">
                <h3><?php echo $stats['total']; ?></h3>
                <p>Total Penawaran</p>
            </div>
            <div class="stat-card stat-pending">
                <h3><?php echo $stats['pending']; ?></h3>
                <p>Pending</p>
            </div>
            <div class="stat-card stat-dihubungi">
                <h3><?php echo $stats['dihubungi']; ?></h3>
                <p>Dihubungi</p>
            </div>
            <div class="stat-card stat-diterima">
                <h3><?php echo $stats['diterima']; ?></h3>
                <p>Diterima</p>
            </div>
            <div class="stat-card stat-ditolak">
                <h3><?php echo $stats['ditolak']; ?></h3>
                <p>Ditolak</p>
            </div>
        </div>

        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Gambar</th>
                        <th>Pemilik</th>
                        <th>Kontak</th>
                        <th>Mobil</th>
                        <th>Tahun</th>
                        <th>Harga</th>
                        <th>KM</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td>
                                    <?php if ($row['gambar']): ?>
                                        <img src="uploads/<?php echo $row['gambar']; ?>" class="car-img" alt="Mobil">
                                    <?php else: ?>
                                        <div style="width: 100px; height: 75px; background: #f0f0f0; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #999;">No Image</div>
                                    <?php endif; ?>
                                </td>
                                <td><strong><?php echo $row['nama_pemilik']; ?></strong></td>
                                <td>
                                     <?php echo $row['no_telepon']; ?><br>
                                    <?php if ($row['email']): ?>
                                         <?php echo $row['email']; ?>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <strong><?php echo $row['nama_mobil']; ?></strong><br>
                                    <small><?php echo $row['merk']; ?> - <?php echo $row['transmisi']; ?></small>
                                </td>
                                <td><?php echo $row['tahun']; ?></td>
                                <td class="price">Rp <?php echo number_format($row['harga_penawaran'], 0, ',', '.'); ?></td>
                                <td><?php echo number_format($row['kilometer'], 0, ',', '.'); ?> km</td>
                                <td>
                                    <span class="status-badge status-<?php echo strtolower($row['status_penawaran']); ?>">
                                        <?php echo $row['status_penawaran']; ?>
                                    </span>
                                </td>
                                <td><?php echo date('d/m/Y', strtotime($row['created_at'])); ?></td>
                                <td>
                                    <button class="btn btn-view" onclick="viewDetail(<?php echo $row['id']; ?>)">Detail</button>
                                    <button class="btn btn-edit" onclick="editStatus(<?php echo $row['id']; ?>)">Update</button>
                                    <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-delete" onclick="return confirm('Yakin hapus data ini?')">Hapus</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="11" class="no-data">Belum ada penawaran masuk</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Detail -->
    <div id="detailModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Detail Penawaran</h2>
                <span class="close" onclick="closeModal('detailModal')">&times;</span>
            </div>
            <div id="detailContent"></div>
        </div>
    </div>

    <!-- Modal Edit Status -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Update Status Penawaran</h2>
                <span class="close" onclick="closeModal('editModal')">&times;</span>
            </div>
            <form method="POST" id="editForm">
                <input type="hidden" name="id" id="edit_id">
                
                <div class="form-group">
                    <label>Status Penawaran</label>
                    <select name="status_penawaran" id="edit_status">
                        <option value="Pending">Pending</option>
                        <option value="Dihubungi">Dihubungi</option>
                        <option value="Diterima">Diterima</option>
                        <option value="Ditolak">Ditolak</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Catatan Admin</label>
                    <textarea name="catatan_admin" id="edit_catatan" placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                </div>

                <button type="submit" name="update_status" class="btn btn-primary"> Simpan Perubahan</button>
            </form>
        </div>
    </div>

    <script>
        const data = <?php 
            mysqli_data_seek($result, 0);
            $dataArray = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $dataArray[$row['id']] = $row;
            }
            echo json_encode($dataArray);
        ?>;

        function viewDetail(id) {
            const item = data[id];
            const content = `
                <div class="detail-row">
                    <div class="detail-label">Nama Pemilik:</div>
                    <div class="detail-value">${item.nama_pemilik}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">No. Telepon:</div>
                    <div class="detail-value">${item.no_telepon}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Email:</div>
                    <div class="detail-value">${item.email || '-'}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Nama Mobil:</div>
                    <div class="detail-value"><strong>${item.nama_mobil}</strong></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Merk:</div>
                    <div class="detail-value">${item.merk}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Tahun:</div>
                    <div class="detail-value">${item.tahun}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Harga Penawaran:</div>
                    <div class="detail-value"><strong style="color: #ff9800;">Rp ${parseInt(item.harga_penawaran).toLocaleString('id-ID')}</strong></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Transmisi:</div>
                    <div class="detail-value">${item.transmisi}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Warna:</div>
                    <div class="detail-value">${item.warna || '-'}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Kilometer:</div>
                    <div class="detail-value">${parseInt(item.kilometer).toLocaleString('id-ID')} km</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Kondisi Mobil:</div>
                    <div class="detail-value">${item.kondisi_mobil || '-'}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Alasan Jual:</div>
                    <div class="detail-value">${item.alasan_jual || '-'}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Status:</div>
                    <div class="detail-value"><span class="status-badge status-${item.status_penawaran.toLowerCase()}">${item.status_penawaran}</span></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Catatan Admin:</div>
                    <div class="detail-value">${item.catatan_admin || '-'}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Tanggal Daftar:</div>
                    <div class="detail-value">${new Date(item.created_at).toLocaleString('id-ID')}</div>
                </div>
            `;
            
            document.getElementById('detailContent').innerHTML = content;
            document.getElementById('detailModal').style.display = 'block';
        }

        function editStatus(id) {
            const item = data[id];
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_status').value = item.status_penawaran;
            document.getElementById('edit_catatan').value = item.catatan_admin || '';
            document.getElementById('editModal').style.display = 'block';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = 'none';
            }
        }
    </script>
</body>
</html>

<?php mysqli_close($conn); ?>
