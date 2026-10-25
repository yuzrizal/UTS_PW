<?php
// Pengaturan dasar website (Variabel PHP)
$nama_website = "Website Saya";
$tahun_sekarang = date("Y");

// Contoh data artikel/menu dinamis dari array PHP
$menu_navigasi = [
    "Beranda" => "index.php",
    "Tentang Kami" => "tentang.php",
    "Layanan" => "layanan.php",
    "Kontak" => "kontak.php"
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $nama_website; ?> - Halaman Indeks</title>
    <!-- Memuat Bootstrap CSS dari CDN untuk tampilan modern -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <!-- Bagian Navigasi (Navbar) -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php"><?php echo $nama_website; ?></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <?php foreach ($menu_navigasi as $judul => $link): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo $link; ?>"><?php echo $judul; ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Konten Utama -->
    <header class="bg-light py-5 text-center">
        <div class="container">
            <h1 class="display-5 fw-bold">Selamat Datang di <?php echo $nama_website; ?></h1>
            <p class="col-lg-8 mx-auto fs-4 text-muted">
                Halaman ini dibuat menggunakan kombinasi bahasa PHP dan HTML yang terstruktur dengan baik.
            </p>
        </div>
    </header>

    <main class="container my-5">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="p-4 bg-white border rounded-3 shadow-sm">
                    <h2>Dinamis dengan PHP</h2>
                    <p>PHP memungkinkan Anda menyisipkan logika pemrograman, tanggal otomatis, dan memproses data dari database dengan mudah.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4 bg-white border rounded-3 shadow-sm">
                    <h2>Desain Responsif</h2>
                    <p>Tampilan otomatis menyesuaikan dengan berbagai ukuran layar perangkat berkat dukungan kerangka kerja Bootstrap.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4 bg-white border rounded-3 shadow-sm">
                    <h2>Mudah Dikembangkan</h2>
                    <p>Struktur kode yang modular memudahkan Anda untuk memecah bagian header, footer, dan konten menjadi file terpisah.</p>
                </div>
            </div>
        </div>
    </main>

    <!-- Bagian Kaki (Footer) -->
    <footer class="bg-dark text-white text-center py-3 mt-auto">
        <div class="container">
            <p class="mb-0">&copy; <?php echo $tahun_sekarang; ?> <?php echo $nama_website; ?>. Hak Cipta Dilindungi.</p>
        </div>
    </footer>

    <!-- Memuat Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
