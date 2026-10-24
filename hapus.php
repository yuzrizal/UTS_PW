<?php
$id = $_GET['id'];
$query = mysqli_query($koneksi, "DELETE FROM barang WHERE id = '$id'");

if ($query) {
    echo "<script>alert('Data berhasil dihapus!');window.location='index.php?page=barang';</script>";
} else {
    echo "<script>alert('Gagal menghapus data!');window.location='index.php?page=barang';</script>";
}
?>
