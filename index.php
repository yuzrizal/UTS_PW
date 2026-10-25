<?php
include 'koneksi.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Inventaris Gudang</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#"><i class="fa-solid fa-boxes-stacked me-2"></i>Inventaris Gudang</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" href="#">Dashboard</a></li>

                    <li class="nav-item"><a class="nav-link" href="laporan.php">laporan</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <!-- Notifikasi Status Koneksi Database -->
        <?php if (!empty($status_koneksi)): ?>
            <div class="alert alert-<?= $status_class; ?> alert-dismissible fade show shadow-sm" role="alert">
                <i class="fa-solid <?= $status_class == 'success' ? 'fa-circle-check' : 'fa-triangle-exclamation'; ?> me-2"></i>
                <strong>Status Database:</strong> <?= $status_koneksi; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Quick Summary Cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-white p-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary text-white rounded-3 p-3 me-3">
                            <i class="fa-solid fa-box-archive fa-2x"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Total Jenis Barang</h6>
                            <?php 
                                $total_items = 0;
                                if($conn) {
                                    $q = mysqli_query($conn, "SELECT COUNT(*) as total FROM barang");
                                    if($q) { $res = mysqli_fetch_assoc($q); $total_items = $res['total']; }
                                }
                            ?>
                            <h3 class="fw-bold mb-0"><?= $total_items; ?></h3>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-white p-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-warning text-white rounded-3 p-3 me-3">
                            <i class="fa-solid fa-triangle-exclamation fa-2x"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Stok Menipis</h6>
                            <?php 
                                $stok_low = 0;
                                if($conn) {
                                    $q = mysqli_query($conn, "SELECT COUNT(*) as total FROM barang WHERE stok < stok_minimum");
                                    if($q) { $res = mysqli_fetch_assoc($q); $stok_low = $res['total']; }
                                }
                            ?>
                            <h3 class="fw-bold mb-0"><?= $stok_low; ?></h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-white p-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-success text-white rounded-3 p-3 me-3">
                            <i class="fa-solid fa-layer-group fa-2x"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Kategori Barang</h6>
                            <?php 
                                $total_kategori = 0;
                                if($conn) {
                                    $q = mysqli_query($conn, "SELECT COUNT(DISTINCT id_kategori) as total FROM barang");
                                    if($q) { $res = mysqli_fetch_assoc($q); $total_kategori = $res['total']; }
                                }
                            ?>
                            <h3 class="fw-bold mb-0"><?= $total_kategori; ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section Tabel Data Barang -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold"><i class="fa-solid fa-list me-2"></i>Daftar Stok Inventaris</h5>
                <a href="tambah.php" class="btn btn-primary btn-sm">
                    <i class="fa-solid fa-plus me-1"></i> Tambah Barang
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Kategori</th>
                                <th>Stok</th>
                                <th>Harga Satuan</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($conn) {
                                // Mengambil data dari tabel barang
                                $query = "SELECT b.*, k.nama_kategori
                                          FROM barang b
                                          LEFT JOIN kategori k ON b.id_kategori = k.id_kategori
                                          ORDER BY b.nama_barang DESC";
                                $result = mysqli_query($conn, $query);

                                if ($result && mysqli_num_rows($result) > 0) {
                                    $no = 1;
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><span class="badge bg-secondary"><?= htmlspecialchars($row['kode_barang'] ?? '-'); ?></span></td>
                                            <td><strong><?= htmlspecialchars($row['nama_barang'] ?? ''); ?></strong></td>
                                            <td><?= htmlspecialchars($row['nama_kategori'] ?? '-'); ?></td>
                                            <td>
                                                <?php if(($row['stok'] <= $row['stok_minimum'])): ?>
                                                    <span class="badge bg-danger"><?= $row['stok']; ?> (Stok Rendah)</span>
                                                <?php else: ?>
                                                    <span class="badge bg-success"><?= $row['stok']; ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td>Rp <?= number_format($row['harga'] ?? 0, 0, ',', '.'); ?></td>
                                            <td class="text-center">
                                                <a href="edit.php?id=<?= $row['id_barang']; ?>" class="btn btn-sm btn-outline-warning me-1">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </a>
                                                <a href="delete.php?id=<?= $row['id_barang']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus barang ini?')">
                                                    <i class="fa-solid fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    echo '<tr><td colspan="7" class="text-center text-muted py-4">Belum ada data barang di database.</td></tr>';
                                }
                            } else {
                                echo '<tr><td colspan="7" class="text-center text-danger py-4">Database belum terhubung. Silakan periksa konfigurasi.</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>