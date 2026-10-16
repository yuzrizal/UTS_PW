<?php
include 'koneksi.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: index.php");
    exit;
}

// Ambil data lama
$stmt = $pdo->prepare("SELECT * FROM barang WHERE id = ?");
$stmt->execute([$id]);
$barang = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$barang) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_barang   = $_POST['nama_barang'];
    $kategori      = $_POST['kategori'];
    $jumlah        = $_POST['jumlah'];
    $harga         = $_POST['harga'];
    $tanggal_masuk = $_POST['tanggal_masuk'];

    $stmt = $pdo->prepare("UPDATE barang SET nama_barang = ?, kategori = ?, jumlah = ?, harga = ?, tanggal_masuk = ? WHERE id = ?");
    $stmt->execute([$nama_barang, $kategori, $jumlah, $harga, $tanggal_masuk, $id]);

    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Barang</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        form { width: 300px; }
        div { margin-bottom: 10px; }
        label { display: block; margin-bottom: 3px; }
        input[type="text"], input[type="number"], input[type="date"] { width: 100%; padding: 5px; }
        button { padding: 8px 15px; background: #ffc107; color: black; border: none; cursor: pointer; }
        a { display: inline-block; margin-top: 10px; color: #555; }
    </style>
</head>
<body>

    <h2>Edit Barang Inventaris</h2>

    <form method="POST">
        <div>
            <label>Nama Barang:</label>
            <input type="text" name="nama_barang" value="<?= htmlspecialchars($barang['nama_barang']); ?>" required>
        </div>
        <div>
            <label>Kategori:</label>
            <input type="text" name="kategori" value="<?= htmlspecialchars($barang['kategori']); ?>" required>
        </div>
        <div>
            <label>Jumlah:</label>
            <input type="number" name="jumlah" value="<?= $barang['jumlah']; ?>" required>
        </div>
        <div>
            <label>Harga (Rp):</label>
            <input type="number" step="any" name="harga" value="<?= $barang['harga']; ?>" required>
        </div>
        <div>
            <label>Tanggal Masuk:</label>
            <input type="date" name="tanggal_masuk" value="<?= $barang['tanggal_masuk']; ?>" required>
        </div>
        <button type="submit">Update</button>
    </form>

    <a href="index.php">&laquo; Kembali</a>

</body>
</html>
