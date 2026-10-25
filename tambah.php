<?php
include 'koneksi.php';

$error = null;

// Nilai default form (dipakai juga untuk mengisi ulang form jika gagal simpan)
$old = [
    'kode_barang'  => '',
    'nama_barang'  => '',
    'id_kategori'  => '',
    'id_supplier'  => '',
    'harga'        => '',
    'stok'         => '0',
    'satuan'       => '',
    'stok_minimum' => '5',
];

// Saran kode barang berikutnya (BRG001, BRG002, ...)
$res = mysqli_query($conn, "SELECT MAX(CAST(SUBSTRING(kode_barang, 4) AS UNSIGNED)) AS maks FROM barang WHERE kode_barang LIKE 'BRG%'");
$row = $res ? mysqli_fetch_assoc($res) : null;
$old['kode_barang'] = 'BRG' . str_pad(((int) ($row['maks'] ?? 0)) + 1, 3, '0', STR_PAD_LEFT);

if (isset($_POST['simpan'])) {
    $old['kode_barang']  = trim($_POST['kode_barang'] ?? '');
    $old['nama_barang']  = trim($_POST['nama_barang'] ?? '');
    $old['id_kategori']  = $_POST['id_kategori'] ?? '';
    $old['id_supplier']  = $_POST['id_supplier'] ?? '';
    $old['harga']        = $_POST['harga'] ?? '';
    $old['stok']         = $_POST['stok'] ?? '0';
    $old['satuan']       = trim($_POST['satuan'] ?? '');
    $old['stok_minimum'] = $_POST['stok_minimum'] ?? '5';

    $kode     = $old['kode_barang'];
    $nama     = $old['nama_barang'];
    $kategori = (int) $old['id_kategori'];
    $supplier = $old['id_supplier'] !== '' ? (int) $old['id_supplier'] : null;
    $harga    = (float) $old['harga'];
    $stok     = (int) $old['stok'];
    $satuan   = $old['satuan'];
    $minimum  = (int) $old['stok_minimum'];

    // Validasi
    if ($kode === '' || $nama === '' || $satuan === '') {
        $error = "Kode barang, nama barang, dan satuan wajib diisi.";
    } elseif ($kategori <= 0) {
        $error = "Silakan pilih kategori.";
    } elseif ($harga < 0 || $stok < 0 || $minimum < 0) {
        $error = "Harga, stok, dan stok minimum tidak boleh negatif.";
    } else {
        $sql = "INSERT INTO barang
                (kode_barang, nama_barang, id_kategori, id_supplier, harga, stok, satuan, stok_minimum)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        // Tipe: s s i i d i s i  (id_supplier null tetap bisa dikirim sebagai i)
        mysqli_stmt_bind_param($stmt, "ssiidisi", $kode, $nama, $kategori, $supplier, $harga, $stok, $satuan, $minimum);

        try {
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            header("Location: index.php?pesan=tambah_berhasil");
            exit;
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() === 1062) {
                $error = "Kode barang '" . $kode . "' sudah digunakan. Gunakan kode lain.";
            } else {
                $error = "Gagal menambah data: " . $e->getMessage();
            }
        }
    }
}

$kategori_list = mysqli_query($conn, "SELECT * FROM kategori ORDER BY nama_kategori");
$supplier_list = mysqli_query($conn, "SELECT * FROM supplier ORDER BY nama_supplier");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Barang - Inventaris Gudang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
                        <i class="fa-solid fa-circle-plus text-primary me-2"></i>
                        Tambah Barang
                    </h4>
                    <p class="text-muted mb-0">
                        Masukkan data barang baru ke inventaris gudang.
                    </p>
                </div>

                <div class="card-body p-4">

                    <?php if ($error) { ?>
                        <div class="alert alert-danger">
                            <?= htmlspecialchars($error) ?>
                        </div>
                    <?php } ?>

                    <form method="POST">

                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label">Kode Barang</label>
                                <input type="text" name="kode_barang" class="form-control" maxlength="20"
                                       value="<?= htmlspecialchars($old['kode_barang']) ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nama Barang</label>
                                <input type="text" name="nama_barang" class="form-control" maxlength="100"
                                       value="<?= htmlspecialchars($old['nama_barang']) ?>" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Kategori</label>
                                <select name="id_kategori" class="form-select" required>
                                    <option value="">-- Pilih kategori --</option>
                                    <?php while ($k = mysqli_fetch_assoc($kategori_list)) { ?>
                                        <option value="<?= $k['id_kategori'] ?>"
                                            <?= (string) $k['id_kategori'] === (string) $old['id_kategori'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($k['nama_kategori']) ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Supplier</label>
                                <select name="id_supplier" class="form-select">
                                    <option value="">Tidak ada supplier</option>
                                    <?php while ($s = mysqli_fetch_assoc($supplier_list)) { ?>
                                        <option value="<?= $s['id_supplier'] ?>"
                                            <?= (string) $s['id_supplier'] === (string) $old['id_supplier'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($s['nama_supplier']) ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Harga (Rp)</label>
                                <input type="number" name="harga" class="form-control" min="0" step="0.01"
                                       value="<?= htmlspecialchars($old['harga']) ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Stok Awal</label>
                                <input type="number" name="stok" class="form-control" min="0"
                                       value="<?= htmlspecialchars($old['stok']) ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Satuan</label>
                                <input type="text" name="satuan" class="form-control" maxlength="20" placeholder="Pcs, Box, Lusin, ..."
                                       value="<?= htmlspecialchars($old['satuan']) ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Stok Minimum</label>
                                <input type="number" name="stok_minimum" class="form-control" min="0"
                                       value="<?= htmlspecialchars($old['stok_minimum']) ?>" required>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-end gap-2">
                            <a href="index.php" class="btn btn-secondary">
                                <i class="fa-solid fa-arrow-left me-1"></i>
                                Kembali
                            </a>
                            <button type="submit" name="simpan" class="btn btn-primary">
                                <i class="fa-solid fa-floppy-disk me-1"></i>
                                Simpan Barang
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