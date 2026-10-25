<?php
include 'koneksi.php';
$id = $_GET['id'];
$data = mysqli_query($koneksi, "SELECT * FROM barang WHERE id_barang='$id'");
$barang = mysqli_fetch_assoc($data);

if (isset($_POST['update'])) {
    $kode = $_POST['kode_barang'];
    $nama = $_POST['nama_barang'];
    $kategori = $_POST['id_kategori'];
    $supplier = $_POST['id_supplier'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $satuan = $_POST['satuan'];
    $minimum = $_POST['stok_minimum'];

    $supplier = ($supplier == '') ? NULL : $supplier;

    $stmt = mysqli_prepare($koneksi, "UPDATE barang SET kode_barang=?, nama_barang=?, id_kategori=?, id_supplier=?, harga=?, stok=?, satuan=?, stok_minimum=? WHERE id_barang=?");

    mysqli_stmt_bind_param(
        $stmt, "ssiidisii",
        $kode, $nama, $kategori, $supplier,
        $harga, $stok, $satuan, $minimum, $id
    );

    if (mysqli_stmt_execute($stmt)) {
        header("Location: index.php");
        exit;
    } else {
        echo "Gagal mengubah data: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Barang</title>
</head>
<body>
    <h2>Edit Data Barang</h2>
    <form method="POST">
        Kode Barang:<br>
        <input type="text" name="kode_barang" value="<?= htmlspecialchars($barang['kode_barang']) ?>" required><br><br>
        Nama Barang:<br>
        <input type="text" name="nama_barang" value="<?= htmlspecialchars($barang['nama_barang']) ?>" required><br><br>
        ID Kategori:<br>
        <input type="number" name="id_kategori" value="<?= $barang['id_kategori'] ?>" required><br><br>
        ID Supplier (boleh kosong):<br>
        <input type="number" name="id_supplier" value="<?= $barang['id_supplier'] ?? '' ?>"><br><br>
        Harga:<br>
        <input type="number" name="harga" min="0" step="0.01" value="<?= $barang['harga'] ?>" required><br><br>
        Stok:<br>
        <input type="number" name="stok" min="0" value="<?= $barang['stok'] ?>" required><br><br>
        Satuan:<br>
        <input type="text" name="satuan" value="<?= htmlspecialchars($barang['satuan']) ?>" required><br><br>
        Stok Minimum:<br>
        <input type="number" name="stok_minimum" min="0" value="<?= $barang['stok_minimum'] ?>" required><br><br>
        <button type="submit" name="update">Update Barang</button>
        <a href="index.php">Kembali</a>
    </form>
</body>
</html>