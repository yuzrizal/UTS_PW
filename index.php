<?php
// Simulasi data inventaris gudang (bisa diganti dengan koneksi database MySQL asli)
$koneksi_db = true; // Anggap koneksi berhasil

// Contoh data dummy barang gudang
$data_barang = [
    ["id" => 1, "kode" => "BRG-001", "nama" => "Laptop Asus ZenBook 14", "kategori" => "Elektronik", "stok" => 15, "satuan" => "Unit", "lokasi" => "Rak A1"],
    ["id" => 2, "kode" => "BRG-002", "nama" => "Kertas HVS A4 70gr", "kategori" => "Al tulis Kantor", "stok" => 4, "satuan" => "Rim", "lokasi" => "Rak B2"], // Stok menipis
    ["id" => 3, "kode" => "BRG-003", "nama" => "Kursi Kantor Ergonomis", "kategori" => "Furniture", "stok" => 28, "satuan" => "Pcs", "lokasi" => "Rak C3"],
    ["id" => 4, "kode" => "BRG-004", "nama" => "Printer Epson L3210", "kategori" => "Elektronik", "stok" => 8, "satuan" => "Unit", "lokasi" => "Rak A2"],
    ["id" => 5, "kode" => "BRG-005", "nama" => "Ballpoint Standard Hitam", "kategori" => "Alat Tulis Kantor", "stok" => 2, "satuan" => "Box", "lokasi" => "Rak B1"]  // Stok menipis
];

// Hitung statistik ringkas
$total_jenis_barang = count($data_barang);
$total_stok = array_sum(array_column($data_barang, 'stok'));
$stok_menipis = count(array_filter($data_barang, function($item) {
    return $item['stok'] <= 5;
}));
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Inventaris Gudang</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="index.php">
                <i class="bi bi-box-seam me-2"></i>GudangPRO
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="barang.php">Data Barang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="laporan.php">Laporan</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Konten Utama -->
    <div class="container my-4">
        
        <!-- Header Judul -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h2>Dashboard Inventaris</h2>
                <p class="text-muted">Selamat datang kembali, Kelola stok barang gudang dengan mudah.</p>
            </div>
            <div class="col-md-6 text-md-end">
                <a href="tambah_barang.php" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Barang Baru
                </a>
            </div>
        </div>

        <!-- Kartu Statistik (Cards) -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-white border-start border-primary border-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Total Jenis Barang</h6>
                                <h3 class="fw-bold mb-0"><?php echo $total_jenis_barang; ?></h3>
                            </div>
                            <div class="fs-1 text-primary opacity-50"><i class="bi bi-boxes"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-white border-start border-success border-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Akumulasi Stok</h6>
                                <h3 class="fw-bold mb-0"><?php echo $total_stok; ?></h3>
                            </div>
                            <div class="fs-1 text-success opacity-50"><i class="bi bi-stack"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-white border-start border-danger border-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Stok Menipis (≤ 5)</h6>
                                <h3 class="fw-bold text-danger mb-0"><?php echo $stok_menipis; ?></h3>
                            </div>
                            <div class="fs-1 text-danger opacity-50"><i class="bi bi-exclamation-triangle"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Stok Barang Terbaru -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Daftar Stok Barang Terbaru</h5>
                <a href="barang.php" class="btn btn-sm btn-outline-secondary">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">#</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Kategori</th>
                                <th>Stok</th>
                                <th>Lokasi Rak</th>
                                <th class="text-end pe-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($data_barang)): ?>
                                <?php foreach ($data_barang as $index => $row): ?>
                                    <tr>
                                        <td class="ps-3"><?php echo $index + 1; ?></td>
                                        <td><span class="badge bg-secondary"><?php echo $row['kode']; ?></span></td>
                                        <td class="fw-semibold"><?php echo $row['nama']; ?></td>
                                        <td><?php echo $row['kategori']; ?></td>
                                        <td>
                                            <?php if ($row['stok'] <= 5): ?>
                                                <span class="text-danger fw-bold"><?php echo $row['stok'] . ' ' . $row['satuan']; ?> 
                                                    <i class="bi bi-exclamation-circle-fill" title="Stok Menipis!"></i>
                                                </span>
                                            <?php else: ?>
                                                <span class="text-success fw-semibold"><?php echo $row['stok'] . ' ' . $row['satuan']; ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo $row['lokasi']; ?></td>
                                        <td class="text-end pe-3">
                                            <a href="edit_barang.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-primary" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="hapus_barang.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger" title="Hapus" onclick="return confirm('Yakin ingin menghapus data ini?')">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center py-3 text-muted">Belum ada data barang di gudang.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <!-- Footer -->
    <footer class="text-center py-4 text-muted mt-5">
        <small>&copy; 2026 Sistem Inventaris Gudang. Dibangun dengan PHP & Bootstrap.</small>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
