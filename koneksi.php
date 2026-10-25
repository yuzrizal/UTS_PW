<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'inventaris_gudang';

$koneksi = mysqli_connect($host, $user, $pass, $db);

if ($conn) {
    $status_koneksi = "Berhasil terhubung ke database '$db'!";
    $status_class = "success";
} else {
    $status_koneksi = "Koneksi gagal: " . mysqli_connect_error();
    $status_class = "error";
}
?>
