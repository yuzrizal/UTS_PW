<?php
if (isset($_POST['submit'])) {
    $kode     = $_POST['kode_barang'];
    $nama     = $_POST['nama_barang'];
    $kategori = $_POST['kategori'];
    $stok     = $_POST['stok'];
    $satuan   = $_POST['satuan'];

    $query = mysqli_query($koneksi, "INSERT INTO barang (kode_barang, nama_barang, kategori, stok, satuan) VALUES ('$kode', '$nama', '$kategori', '$stok', '$satuan')");
    
    if ($query) {
        echo "<script>alert('Data barang berhasil ditambahkan!');window.location='index.php?page=barang';</script>";
    } else {
        echo "<script>alert('Gagal menambah barang!');</script>";
    }
}
?>
<h2>Tambah Barang</h2>
<form action="" method="POST">
    <label>Kode Barang</label>
    <input type="text" name="kode_barang" required>
    <label>Nama Barang</label>
    <input type="text" name="nama_barang" required>
    <label>Kategori</label>
    <input type="text" name="kategori" required>
    <label>Stok Awal</label>
    <input type="number" name="stok" required>
    <label>Satuan (Pcs/Box/Kg, dll)</label>
    <input type="text" name="satuan" required>
    <button type="submit" name="submit" class="btn btn-success">Simpan Barang</button>
    <a href="index.php?page=barang" class="btn btn-primary">Kembali</a>
</form>
