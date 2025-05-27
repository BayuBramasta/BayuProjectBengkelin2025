<?php
include "db/koneksi.php"; // Include koneksi.php

// Cek koneksi
if ($conn) {
    echo "Koneksi ke database berhasil!";
} else {
    echo "Koneksi gagal!";
}
?>
