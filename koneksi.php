<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'inventaris_gudang';

$conn = mysqli_connect($host, $user, $pass, $db);

$status_koneksi = "";
$status_class   = "";

if ($conn) {
    mysqli_set_charset($conn, "utf8mb4");
    $status_koneksi = "Berhasil terhubung ke database '$db'!";
    $status_class   = "success";
} else {
    $status_koneksi = "Koneksi gagal: " . mysqli_connect_error();
    $status_class   = "danger";
}
?>