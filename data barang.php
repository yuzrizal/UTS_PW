<h2>Data Barang Inventaris</h2>
<a href="index.php?page=tambah_barang" class="btn btn-primary">+ Tambah Barang Baru</a>
<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Kode Barang</th>
            <th>Nama Barang</th>
            <th>Kategori</th>
            <th>Stok</th>
            <th>Satuan</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no = 1;
        $query = mysqli_query($koneksi, "SELECT * FROM barang ORDER BY id DESC");
        while ($row = mysqli_fetch_assoc($query)) {
        ?>
        <tr>
            <td><?= $no++; ?></td>
            <td><?= $row['kode_barang']; ?></td>
            <td><?= $row['nama_barang']; ?></td>
            <td><?= $row['kategori']; ?></td>
            <td><?= $row['stok']; ?></td>
            <td><?= $row['satuan']; ?></td>
            <td>
                <a href="index.php?page=edit_barang&id=<?= $row['id']; ?>" class="btn btn-warning">Edit</a>
                <a href="index.php?page=hapus_barang&id=<?= $row['id']; ?>" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>
