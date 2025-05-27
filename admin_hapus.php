<?php
include "db/koneksi.php";

if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $query = mysqli_query($conn, "DELETE FROM booking WHERE id=$id");

    if ($query) {
        header("Location: admin_booking.php?status=hapus_sukses");
        exit();
    } else {
        echo "Gagal menghapus data!";
    }
} else {
    echo "ID tidak ditemukan.";
}
?>
