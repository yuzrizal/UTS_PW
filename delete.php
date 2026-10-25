
<?php
include 'koneksi.php';

// Periksa apakah ID barang dikirim melalui URL
if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];

    if ($id > 0) {
        // Query menghapus barang berdasarkan ID
        $query = "DELETE FROM barang WHERE id_barang = ?";
        $stmt = mysqli_prepare($conn, $query);

        mysqli_stmt_bind_param($stmt, "i", $id);

        try {
            if (mysqli_stmt_execute($stmt)) {
                if (mysqli_stmt_affected_rows($stmt) > 0) {
                    header("Location: index.php?pesan=hapus_berhasil");
                    exit;
                } else {
                    echo "Barang tidak ditemukan.";
                }
            } else {
                echo "Gagal menghapus barang. Barang mungkin masih digunakan di tabel lain.";
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "ID barang tidak valid.";
    }
} else {
    echo "ID barang tidak ditemukan.";
}

echo '<br><a href="index.php">Kembali ke halaman barang</a>';
?>
