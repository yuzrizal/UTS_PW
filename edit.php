<?php
$id = $_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM barang WHERE id = '$id'"));

if (isset($_POST['update'])) {
    $kode     = $_POST['kode_barang'];
    $nama     = $_POST['nama_barang'];
    $kategori = $_POST['kategori'];
    $satuan   = $_POST['satuan'];

    $query = mysqli_query($koneksi, "UPDATE barang SET kode_barang='$kode', nama_barang='$nama', kategori='$kategori', satuan='$satuan' WHERE id='$id'");
    
    if ($query) {
        echo "<script>alert('Data barang berhasil diperbarui!');window.location='index.php?page=barang';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data!');</script>";
    }
}
?>
<h2>Edit Barang</h2>
<form action="" method="POST">
    <label>Kode Barang</label>
    <input type="text" name="kode_barang" value="<?= $data['kode_barang']; ?>" required>
    <label>Nama Barang</label>
    <input type="text" name="nama_barang" value="<?= $data['nama_barang']; ?>" required>
    <label>Kategori</label>
    <input type="text" name="kategori" value="<?= $data['kategori']; ?>" required>
    <label>Satuan</label>
    <input type="text" name="satuan" value="<?= $data['satuan']; ?>" required>
    <button type="submit" name="update" class="btn btn-success">Update Barang</button>
    <a href="index.php?page=barang" class="btn btn-primary">Kembali</a>
</form>
