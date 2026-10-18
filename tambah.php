<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_barang   = $_POST['nama_barang'];
    $kategori      = $_POST['kategori'];
    $jumlah        = $_POST['jumlah'];
    $harga         = $_POST['harga'];
    $tanggal_masuk = $_POST['tanggal_masuk'];

    $stmt = $pdo->prepare("INSERT INTO barang (nama_barang, kategori, jumlah, harga, tanggal_masuk) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$nama_barang, $kategori, $jumlah, $harga, $tanggal_masuk]);

    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Barang</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        form { width: 300px; }
        div { margin-bottom: 10px; }
        label { display: block; margin-bottom: 3px; }
        input[type="text"], input[type="number"], input[type="date"] { width: 100%; padding: 5px; }
        button { padding: 8px 15px; background: #28a745; color: white; border: none; cursor: pointer; }
        a { display: inline-block; margin-top: 10px; color: #555; }
    </style>
</head>
<body>

    <h2>Tambah Barang Inventaris</h2>

    <form method="POST">
        <div>
            <label>Nama Barang:</label>
            <input type="text" name="nama_barang" required>
        </div>
        <div>
            <label>Kategori:</label>
            <input type="text" name="kategori" required>
        </div>
        <div>
            <label>Jumlah:</label>
            <input type="number" name="jumlah" min="0" required>
        </div>
        <div>
            <label>Harga (Rp):</label>
            <input type="number" step="any" min="0" name="harga" required>
        </div>
        <div>
            <label>Tanggal Masuk:</label>
            <input type="date" name="tanggal_masuk" value="<?= date('Y-m-d'); ?>" required>
        </div>
        <button type="submit">Simpan</button>
    </form>

    <a href="index.php">&laquo; Kembali</a>

</body>
</html>