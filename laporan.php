<?php
include 'koneksi.php';

date_default_timezone_set('Asia/Jakarta');

if (!$conn) {
    die("Database belum terhubung. Silakan periksa koneksi.php.");
}

$error = null;

// ---------- Ambil nilai filter dari URL ----------
$f_kategori = isset($_GET['kategori']) ? (int) $_GET['kategori'] : 0;
$f_supplier = $_GET['supplier'] ?? '';          // '' = semua, 'none' = tanpa supplier, angka = id supplier
$f_status   = $_GET['status'] ?? '';            // '' = semua, 'rendah', 'aman'
$f_cari     = trim($_GET['cari'] ?? '');

// ---------- Susun kondisi WHERE dengan prepared statement ----------
$where  = [];
$types  = '';
$params = [];

if ($f_kategori > 0) {
    $where[] = "b.id_kategori = ?";
    $types  .= 'i';
    $params[] = $f_kategori;
}

if ($f_supplier === 'none') {
    $where[] = "b.id_supplier IS NULL";
} elseif ((int) $f_supplier > 0) {
    $where[] = "b.id_supplier = ?";
    $types  .= 'i';
    $params[] = (int) $f_supplier;
}

if ($f_status === 'rendah') {
    $where[] = "b.stok <= b.stok_minimum";
} elseif ($f_status === 'aman') {
    $where[] = "b.stok > b.stok_minimum";
}

if ($f_cari !== '') {
    $where[] = "(b.kode_barang LIKE ? OR b.nama_barang LIKE ?)";
    $types  .= 'ss';
    $like = '%' . $f_cari . '%';
    $params[] = $like;
    $params[] = $like;
}

$where_sql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

// ---------- Ambil data barang ----------
$data = [];

try {
    $sql = "SELECT b.*, k.nama_kategori, s.nama_supplier
            FROM barang b
            LEFT JOIN kategori k ON b.id_kategori = k.id_kategori
            LEFT JOIN supplier s ON b.id_supplier = s.id_supplier
            $where_sql
            ORDER BY k.nama_kategori, b.nama_barang";

    $stmt = mysqli_prepare($conn, $sql);
    if ($params) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    mysqli_stmt_close($stmt);
} catch (mysqli_sql_exception $e) {
    $error = "Gagal mengambil data laporan: " . $e->getMessage();
}

// ---------- Hitung ringkasan ----------
$total_jenis = count($data);
$total_stok  = 0;
$total_nilai = 0;
$total_rendah = 0;
$per_kategori = [];

foreach ($data as $row) {
    $nilai = $row['stok'] * $row['harga'];

    $total_stok  += $row['stok'];
    $total_nilai += $nilai;

    if ($row['stok'] <= $row['stok_minimum']) {
        $total_rendah++;
    }

    $nama_kat = $row['nama_kategori'] ?? 'Tanpa kategori';
    if (!isset($per_kategori[$nama_kat])) {
        $per_kategori[$nama_kat] = ['jenis' => 0, 'stok' => 0, 'nilai' => 0];
    }
    $per_kategori[$nama_kat]['jenis']++;
    $per_kategori[$nama_kat]['stok']  += $row['stok'];
    $per_kategori[$nama_kat]['nilai'] += $nilai;
}

// ---------- Data untuk dropdown filter ----------
$kategori_list = mysqli_query($conn, "SELECT * FROM kategori ORDER BY nama_kategori");
$supplier_list = mysqli_query($conn, "SELECT * FROM supplier ORDER BY nama_supplier");

function rupiah($angka)
{
    return 'Rp ' . number_format($angka, 0, ',', '.');
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Stok - Inventaris Gudang</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: #fff !important; }
            .card { box-shadow: none !important; border: 1px solid #dee2e6 !important; }
            .container { max-width: 100% !important; }
        }
    </style>
</head>
<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm mb-4 no-print">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php"><i class="fa-solid fa-boxes-stacked me-2"></i>Inventaris Gudang</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link active" href="laporan.php">Laporan</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container pb-5">

        <!-- Judul Laporan -->
        <div class="d-flex justify-content-between align-items-start mb-4">
            <div>
                <h4 class="fw-bold mb-1">
                    <i class="fa-solid fa-file-lines text-primary me-2"></i>Laporan Stok Inventaris
                </h4>
                <p class="text-muted mb-0">
                    Dicetak pada <?= date('d-m-Y H:i') ?> WIB
                </p>
            </div>
            <button type="button" class="btn btn-outline-primary btn-sm no-print" onclick="window.print()">
                <i class="fa-solid fa-print me-1"></i> Cetak Laporan
            </button>
        </div>

        <?php if ($error) { ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php } ?>

        <!-- Filter -->
        <div class="card border-0 shadow-sm mb-4 no-print">
            <div class="card-body">
                <form method="GET" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">Cari Barang</label>
                        <input type="text" name="cari" class="form-control" placeholder="Kode atau nama barang"
                               value="<?= htmlspecialchars($f_cari) ?>">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Kategori</label>
                        <select name="kategori" class="form-select">
                            <option value="">Semua kategori</option>
                            <?php while ($k = mysqli_fetch_assoc($kategori_list)) { ?>
                                <option value="<?= $k['id_kategori'] ?>"
                                    <?= $f_kategori == $k['id_kategori'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($k['nama_kategori']) ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Supplier</label>
                        <select name="supplier" class="form-select">
                            <option value="">Semua supplier</option>
                            <option value="none" <?= $f_supplier === 'none' ? 'selected' : '' ?>>Tidak ada supplier</option>
                            <?php while ($s = mysqli_fetch_assoc($supplier_list)) { ?>
                                <option value="<?= $s['id_supplier'] ?>"
                                    <?= (string) $f_supplier === (string) $s['id_supplier'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($s['nama_supplier']) ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Status Stok</label>
                        <select name="status" class="form-select">
                            <option value="">Semua status</option>
                            <option value="rendah" <?= $f_status === 'rendah' ? 'selected' : '' ?>>Stok rendah</option>
                            <option value="aman" <?= $f_status === 'aman' ? 'selected' : '' ?>>Stok aman</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-filter me-1"></i> Tampilkan
                        </button>
                        <a href="laporan.php" class="btn btn-secondary">
                            <i class="fa-solid fa-rotate-left me-1"></i> Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Ringkasan -->
        <div class="row g-3 mb-4">
            <div class="col-md-3 col-6">
                <div class="card border-0 shadow-sm bg-white p-3 h-100">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary text-white rounded-3 p-3 me-3">
                            <i class="fa-solid fa-box-archive fa-2x"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Jenis Barang</h6>
                            <h3 class="fw-bold mb-0"><?= $total_jenis ?></h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card border-0 shadow-sm bg-white p-3 h-100">
                    <div class="d-flex align-items-center">
                        <div class="bg-info text-white rounded-3 p-3 me-3">
                            <i class="fa-solid fa-cubes fa-2x"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Total Stok</h6>
                            <h3 class="fw-bold mb-0"><?= number_format($total_stok, 0, ',', '.') ?></h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card border-0 shadow-sm bg-white p-3 h-100">
                    <div class="d-flex align-items-center">
                        <div class="bg-success text-white rounded-3 p-3 me-3">
                            <i class="fa-solid fa-sack-dollar fa-2x"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Nilai Persediaan</h6>
                            <h5 class="fw-bold mb-0"><?= rupiah($total_nilai) ?></h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card border-0 shadow-sm bg-white p-3 h-100">
                    <div class="d-flex align-items-center">
                        <div class="bg-warning text-white rounded-3 p-3 me-3">
                            <i class="fa-solid fa-triangle-exclamation fa-2x"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Stok Rendah</h6>
                            <h3 class="fw-bold mb-0"><?= $total_rendah ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rekap per Kategori -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold"><i class="fa-solid fa-layer-group me-2"></i>Rekap per Kategori</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Kategori</th>
                                <th class="text-end">Jenis Barang</th>
                                <th class="text-end">Total Stok</th>
                                <th class="text-end">Nilai Persediaan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($per_kategori) { ?>
                                <?php foreach ($per_kategori as $nama_kat => $rekap) { ?>
                                    <tr>
                                        <td><?= htmlspecialchars($nama_kat) ?></td>
                                        <td class="text-end"><?= $rekap['jenis'] ?></td>
                                        <td class="text-end"><?= number_format($rekap['stok'], 0, ',', '.') ?></td>
                                        <td class="text-end"><?= rupiah($rekap['nilai']) ?></td>
                                    </tr>
                                <?php } ?>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">Tidak ada data untuk ditampilkan.</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Detail Stok Barang -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold"><i class="fa-solid fa-list me-2"></i>Detail Stok Barang</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Kategori</th>
                                <th>Supplier</th>
                                <th class="text-end">Stok</th>
                                <th class="text-end">Stok Min.</th>
                                <th class="text-end">Harga Satuan</th>
                                <th class="text-end">Nilai Total</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($data) { ?>
                                <?php $no = 1; foreach ($data as $row) { ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><span class="badge bg-secondary"><?= htmlspecialchars($row['kode_barang']) ?></span></td>
                                        <td><strong><?= htmlspecialchars($row['nama_barang']) ?></strong></td>
                                        <td><?= htmlspecialchars($row['nama_kategori'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($row['nama_supplier'] ?? '-') ?></td>
                                        <td class="text-end"><?= $row['stok'] ?> <?= htmlspecialchars($row['satuan']) ?></td>
                                        <td class="text-end"><?= $row['stok_minimum'] ?></td>
                                        <td class="text-end"><?= rupiah($row['harga']) ?></td>
                                        <td class="text-end"><?= rupiah($row['stok'] * $row['harga']) ?></td>
                                        <td class="text-center">
                                            <?php if ($row['stok'] == 0) { ?>
                                                <span class="badge bg-dark">Habis</span>
                                            <?php } elseif ($row['stok'] <= $row['stok_minimum']) { ?>
                                                <span class="badge bg-danger">Stok Rendah</span>
                                            <?php } else { ?>
                                                <span class="badge bg-success">Aman</span>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="10" class="text-center text-muted py-4">
                                        Tidak ada data barang yang sesuai dengan filter.
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                        <?php if ($data) { ?>
                            <tfoot class="table-light fw-bold">
                                <tr>
                                    <td colspan="5" class="text-end">Total</td>
                                    <td class="text-end"><?= number_format($total_stok, 0, ',', '.') ?></td>
                                    <td></td>
                                    <td></td>
                                    <td class="text-end"><?= rupiah($total_nilai) ?></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        <?php } ?>
                    </table>
                </div>
            </div>
        </div>

        <p class="text-center text-muted small mt-3">
            Sistem Informasi Inventaris Gudang
        </p>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>