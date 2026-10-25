<?php
include 'koneksi.php';

if (!isset($_GET['id'])) {
    die("ID barang tidak ditemukan.");
}

$id = (int) $_GET['id'];

$query = mysqli_query($conn, "SELECT * FROM barang WHERE id_barang = $id");
$barang = mysqli_fetch_assoc($query);

if (!$barang) {
    die("Data barang tidak ditemukan.");
}

if (isset($_POST['simpan'])) {
    $kode = mysqli_real_escape_string($conn, $_POST['kode_barang']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama_barang']);
    $kategori = (int) $_POST['id_kategori'];
    $supplier = $_POST['id_supplier'] !== ''
        ? (int) $_POST['id_supplier']
        : "NULL";
    $harga = (float) $_POST['harga'];
    $stok = (int) $_POST['stok'];
    $satuan = mysqli_real_escape_string($conn, $_POST['satuan']);
    $minimum = (int) $_POST['stok_minimum'];

    $sql = "UPDATE barang SET
        kode_barang = '$kode',
        nama_barang = '$nama',
        id_kategori = $kategori,
        id_supplier = $supplier,
        harga = $harga,
        stok = $stok,
        satuan = '$satuan',
        stok_minimum = $minimum
        WHERE id_barang = $id";

    if (mysqli_query($conn, $sql)) {
        header("Location: index.php");
        exit;
    } else {
        $error = "Gagal mengubah data: " . mysqli_error($conn);
    }
}

$kategori = mysqli_query($conn, "SELECT * FROM kategori");
$supplier = mysqli_query($conn, "SELECT * FROM supplier");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Barang - Inventaris Gudang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"rel="stylesheet">
    <link rel="stylesheet"href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-light">

<!-- Navbar -->
<nav class="navbar navbar-dark bg-primary shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">
            <i class="fa-solid fa-boxes-stacked me-2"></i>
            Inventaris Gudang
        </a>
    </div>
</nav>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white p-4">
                    <h4 class="fw-bold mb-1">
                        <i class="fa-solid fa-pen-to-square text-primary me-2"></i>
                        Edit Barang
                    </h4>
                    <p class="text-muted mb-0">
                        Ubah informasi barang inventaris gudang.
                    </p>
                </div>

                <div class="card-body p-4">

                    <?php if (isset($error)) { ?>
                        <div class="alert alert-danger">
                            <?= htmlspecialchars($error) ?>
                        </div>
                    <?php } ?>

                    <form method="POST">

                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label">Kode Barang</label>
                                <input type="text" name="kode_barang" class="form-control" value="<?= htmlspecialchars($barang['kode_barang']) ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nama Barang</label>
                                <input type="text" name="nama_barang" class="form-control" value="<?= htmlspecialchars($barang['nama_barang']) ?>" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Kategori</label>
                                <select name="id_kategori" class="form-select" required>
                                    <?php while ($k = mysqli_fetch_assoc($kategori)) { ?>
                                        <option value="<?= $k['id_kategori'] ?>"
                                            <?= $k['id_kategori'] == $barang['id_kategori']
                                                ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($k['nama_kategori']) ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Supplier</label>
                                <select name="id_supplier" class="form-select">
                                    <option value="">Tidak ada supplier</option>
                                    <?php while ($s = mysqli_fetch_assoc($supplier)) { ?>
                                        <option value="<?= $s['id_supplier'] ?>"
                                            <?= $s['id_supplier'] == $barang['id_supplier']
                                                ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($s['nama_supplier']) ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Harga (Rp)</label>
                                <input type="number" name="harga" class="form-control" min="0" step="0.01" value="<?= $barang['harga'] ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Stok</label>
                                <input type="number" name="stok" class="form-control" min="0" value="<?= $barang['stok'] ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Satuan</label>
                                <input type="text" name="satuan" class="form-control" value="<?= htmlspecialchars($barang['satuan']) ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Stok Minimum</label>
                                <input type="number" name="stok_minimum" class="form-control" min="0" value="<?= $barang['stok_minimum'] ?>" required>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-end gap-2">
                            <a href="index.php" class="btn btn-secondary">
                                <i class="fa-solid fa-arrow-left me-1"></i>
                                Kembali
                            </a>
                            <button type="submit" name="simpan"
                                class="btn btn-primary">
                                <i class="fa-solid fa-floppy-disk me-1"></i>
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <p class="text-center text-muted small mt-3">
                Sistem Informasi Inventaris Gudang
            </p>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>