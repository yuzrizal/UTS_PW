<?php
if (isset($_POST['tambah_keluar'])) {
    $id_barang = $_POST['id_barang'];
    $jumlah    = $_POST['jumlah'];
    $tanggal   = $_POST['tanggal'];
    $penerima  = $_POST['penerima'];

    // Cek apakah stok mencukupi
    $cek_stok = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT stok FROM barang WHERE id = '$id_barang'"));
    if ($cek_stok['stok'] < $jumlah) {
        echo "<script>alert('Gagal! Stok barang tidak mencukupi.');</script>";
    } else {
        // Simpan ke tabel barang keluar
        mysqli_query($koneksi, "INSERT INTO barang_keluar (id_barang, jumlah, tanggal, penerima) VALUES ('$id_barang', '$jumlah', '$tanggal', '$penerima')");
        
        // Kurangi stok di tabel barang
        mysqli_query($koneksi, "UPDATE barang SET stok = stok - $jumlah WHERE id = '$id_barang'");
        
        echo "<script>alert('Transaksi barang keluar berhasil dicatat!');window.location='index.php?page=barang_keluar';</script>";
    }
}
?>
<h2>Transaksi Barang Keluar</h2>
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
    <label>Jumlah Keluar</label>
    <input type="number" name="jumlah" required>
    <label>Tanggal Keluar</label>
    <input type="date" name="tanggal" required>
    <label>Penerima / Unit Tujuan</label>
    <input type="text" name="penerima" required>
    <button type="submit" name="tambah_keluar" class="btn btn-success">Simpan Transaksi</button>
</form>

<h3 style="margin-top: 40px;">Riwayat Barang Keluar</h3>
<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Nama Barang</th>
            <th>Jumlah Keluar</th>
            <th>Penerima</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no = 1;
        $query = mysqli_query($koneksi, "SELECT barang_keluar.*, barang.nama_barang FROM barang_keluar JOIN barang ON barang_keluar.id_barang = barang.id ORDER BY barang_keluar.id DESC");
        while ($row = mysqli_fetch_assoc($query)) {
        ?>
        <tr>
            <td><?= $no++; ?></td>
            <td><?= $row['tanggal']; ?></td>
            <td><?= $row['nama_barang']; ?></td>
            <td><?= $row['jumlah']; ?></td>
            <td><?= $row['penerima']; ?></td>
        </tr>
        <?php } ?>
    </tbody>
</table>
