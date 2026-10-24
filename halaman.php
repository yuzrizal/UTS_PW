<?php
include 'koneksi.php';

// Hitung statistik untuk Dashboard
$total_barang = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM barang"));
$total_masuk  = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM barang_masuk"));
$total_keluar = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM barang_keluar"));

$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Aplikasi Inventaris Gudang</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background-color: #f4f6f9; display: flex; }
        .sidebar { width: 250px; background: #343a40; color: #fff; height: 100vh; position: fixed; padding-top: 20px; }
        .sidebar h2 { text-align: center; font-size: 20px; margin-bottom: 30px; }
        .sidebar a { display: block; color: #c2c7d0; padding: 12px 20px; text-decoration: none; transition: 0.3s; }
        .sidebar a:hover, .sidebar a.active { background: #007bff; color: #fff; }
        .content { margin-left: 250px; padding: 30px; width: calc(100% - 250px); box-sizing: border-box; }
        .card-box { display: flex; gap: 20px; margin-bottom: 30px; }
        .card { background: #fff; padding: 20px; border-radius: 5px; flex: 1; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .card h3 { margin: 0 0 10px 0; color: #6c757d; font-size: 16px; }
        .card p { font-size: 24px; font-weight: bold; margin: 0; color: #343a40; }
        table { width: 100%; border-collapse: collapse; background: #fff; margin-top: 15px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        th, td { padding: 12px; border: 1px solid #dee2e6; text-align: left; }
        th { background-color: #f8f9fa; }
        .btn { padding: 6px 12px; text-decoration: none; border-radius: 4px; color: #fff; font-size: 14px; }
        .btn-primary { background: #007bff; }
        .btn-success { background: #28a745; }
        .btn-warning { background: #ffc107; color: #212529; }
        .btn-danger  { background: #dc3545; }
        form input, form select, form textarea { width: 100%; padding: 8px; margin: 6px 0 15px 0; display: inline-block; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>GUDANGKU</h2>
        <a href="index.php?page=dashboard" class="<?= $page == 'dashboard' ? 'active' : '' ?>">Dashboard</a>
        <a href="index.php?page=barang" class="<?= $page == 'barang' ? 'active' : '' ?>">Data Barang</a>
        <a href="index.php?page=barang_masuk" class="<?= $page == 'barang_masuk' ? 'active' : '' ?>">Barang Masuk</a>
        <a href="index.php?page=barang_keluar" class="<?= $page == 'barang_keluar' ? 'active' : '' ?>">Barang Keluar</a>
        <a href="index.php?page=laporan" class="<?= $page == 'laporan' ? 'active' : '' ?>">Laporan</a>
    </div>

    <div class="content">
        <?php
        if ($page == 'dashboard') {
            ?>
            <h2>Dashboard</h2>
            <div class="card-box">
                <div class="card">
                    <h3>Total Jenis Barang</h3>
                    <p><?= $total_barang; ?></p>
                </div>
                <div class="card">
                    <h3>Transaksi Barang Masuk</h3>
                    <p><?= $total_masuk; ?></p>
                </div>
                <div class="card">
                    <h3>Transaksi Barang Keluar</h3>
                    <p><?= $total_keluar; ?></p>
                </div>
            </div>
            <p>Selamat datang di Aplikasi Pengelolaan Inventaris Gudang. Gunakan menu di samping untuk mengelola data.</p>
            <?php
        } elseif ($page == 'barang') { include 'barang.php'; }
        elseif ($page == 'tambah_barang') { include 'tambah_barang.php'; }
        elseif ($page == 'edit_barang') { include 'edit_barang.php'; }
        elseif ($page == 'hapus_barang') { include 'hapus_barang.php'; }
        elseif ($page == 'barang_masuk') { include 'barang_masuk.php'; }
        elseif ($page == 'barang_keluar') { include 'barang_keluar.php'; }
        elseif ($page == 'laporan') { include 'laporan.php'; }
        else { echo "<h3>Halaman tidak ditemukan!</h3>"; }
        ?>
    </div>

</body>
</html>
