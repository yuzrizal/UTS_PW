<?php
if (isset($_POST['tambah_masuk'])) {
    $id_barang = $_POST['id_barang'];
    $jumlah    = $_POST['jumlah'];
    $tanggal   = $_POST['tanggal'];
    $keterangan= $_POST['keterangan'];

    // Simpan ke tabel barang masuk
    mysqli_query($koneksi, "INSERT INTO barang_masuk (id_barang, jumlah, tanggal, keterangan) VALUES ('$id_barang', '$jumlah', '$tanggal', '$keterangan')");
    
    // Update tambah stok di tabel barang
    mysqli_query($koneksi, "UPDATE barang SET stok = stok + $jumlah WHERE id = '$id_barang'");
    
    echo "<script>alert('Transaksi barang masuk berhasil dicatat!');window.location='index.php?page=barang_masuk';</script>";
}
?>
<h2>Transaksi Barang Masuk</h2>
<form action="" method="POST">
    <label>Pilih Barang</label>
    <select name="id_barang" required>
        <option value="">-- Pilih Barang --</option>
        <?php
        $brg = mysqli_query($koneksi, "SELECT * FROM barang");
        while($b = mysqli_fetch_assoc($brg)){
            echo "<option value='".$b['id']."'>".$b['nama_barang']." (Stok: ".$b['stok'].")</option>";
        }
        ?>
    </select>
    <label>Jumlah Masuk</label>
    <input type="number" name="jumlah" required>
    <label>Tanggal Masuk</label>
    <input type="date" name="tanggal" required>
    <label>Keterangan / Supplier</label>
    <textarea name="keterangan" rows="3"></textarea>
    <button type="submit" name="tambah_masuk" class="btn btn-success">Simpan Transaksi</button>
</form>

<h3 style="margin-top: 40px;">Riwayat Barang Masuk</h3>
<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Nama Barang</th>
            <th>Jumlah Masuk</th>
            <th>Keterangan</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no = 1;
        $query = mysqli_query($koneksi, "SELECT barang_masuk.*, barang.nama_barang FROM barang_masuk JOIN barang ON barang_masuk.id_barang = barang.id ORDER BY barang_masuk.id DESC");
        while ($row = mysqli_fetch_assoc($query)) {
        ?>
        <tr>
            <td><?= $no++; ?></td>
            <td><?= $row['tanggal']; ?></td>
            <td><?= $row['nama_barang']; ?></td>
            <td><?= $row['jumlah']; ?></td>
            <td><?= $row['keterangan']; ?></td>
        </tr>
        <?php } ?>
    </tbody>
</table>
