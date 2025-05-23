<?php
// simpan_user.php (sekali pakai, bisa dihapus nanti)
include 'db/koneksi.php';
$username = 'admin';
$password = password_hash('12345', PASSWORD_DEFAULT);

mysqli_query($conn, "INSERT INTO admin (username, password) VALUES ('$username', '$password')");
echo "Akun admin berhasil ditambahkan!";
?>
