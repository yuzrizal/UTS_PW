<?php
include 'koneksi.php';

// Proses hapus data jika ada parameter ?hapus=id
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $stmt = $pdo->prepare("DELETE FROM barang WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: index.php");
    exit;
}

// Ambil semua data barang
$stmt = $pdo->query("SELECT * FROM barang ORDER BY id DESC");
$barangs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Inventaris Gudang Sederhana</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        a { text-decoration: none; padding: 5px 10px; background: #007BFF; color: white; border-radius: 3px; }
        a.hapus { background: #dc3545; }
        .tambah { margin-bottom: 15px; display: inline-block; }
    </style>
</head>
<body>

    <h2>Daftar Inventaris Gudang</h2>

    <a href="tambah.php" class="tambah">+ Tambah Barang Baru</a>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th>Jumlah</th>
                <th>Harga</th>
                <th>Tanggal Masuk</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($barangs) > 0): ?>
                <?php $no = 1; foreach ($barangs as $row): ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= htmlspecialchars($row['nama_barang']); ?></td>
                    <td><?= htmlspecialchars($row['kategori']); ?></td>
                    <td><?= $row['jumlah']; ?></td>
                    <td>Rp <?= number_format($row['harga'], 0, ',', '.'); ?></td>
                    <td><?= $row['tanggal_masuk']; ?></td>
                    <td>
                        <a href="edit.php?id=<?= $row['id']; ?>">Edit</a>
                        <a href="index.php?hapus=<?= $row['id']; ?>" class="hapus" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" style="text-align: center;">Belum ada data barang.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>
