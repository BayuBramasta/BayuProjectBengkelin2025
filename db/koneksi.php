<?php
// Pengaturan koneksi ke database
$host = "localhost"; // Server database
$user = "root"; // Username MySQL (default XAMPP)
$pass = ""; // Password (default XAMPP kosong)
$db = "db_project2025"; // Nama database yang kita buat

// Buat koneksi ke MySQL
$conn = mysqli_connect($host, $user, $pass, $db);

// Cek apakah koneksi berhasil
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
} else {
    // Koneksi berhasil
    // echo "Koneksi ke database berhasil!";
}
?>
