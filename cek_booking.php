<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Cek Status Booking</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container mt-5">
    <h2 class="text-center mb-4">Cek Status Booking</h2>
    <form method="POST" class="d-flex justify-content-center">
      <input type="text" name="kode_booking" class="form-control w-50 me-2" placeholder="Masukkan Kode Booking Anda" required>
      <button type="submit" class="btn btn-primary">Cek Status</button>
    </form>
  </div>

<?php
include "db/koneksi.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kodeBooking = mysqli_real_escape_string($conn, $_POST["kode_booking"]);
    $query = "SELECT * FROM booking WHERE kode_booking = '$kodeBooking' ORDER BY created_at DESC";
    $result = mysqli_query($conn, $query);

    echo '<div class="container mt-4">';
    if (mysqli_num_rows($result) > 0) {
        echo "<h5>Hasil Booking Anda:</h5>";
        echo '<table class="table table-bordered table-striped">';
        echo '<thead class="table-dark"><tr>
                <th>Nama</th>
                <th>Layanan</th>
                <th>Tanggal</th>
                <th>Jam</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr></thead><tbody>';

        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr>
                    <td>' .
                htmlspecialchars($row["nama"]) .
                '</td>
                    <td>' .
                htmlspecialchars($row["layanan"]) .
                '</td>
                <td>' .
                $row["tanggal_booking"] .
                '</td>
                <td>' .
                $row["jam_booking"] .
                '</td>
                <td><span class="badge bg-' .
                ($row["status"] == "Selesai"
                    ? "success"
                    : ($row["status"] == "Diproses"
                        ? "warning"
                        : "secondary")) .
                '">' .
                $row["status"] .
                '</span></td>
                <td>' .
                "<a href='/project2025/Midtrans/midtrans/examples/snap/checkout-process-simple-version.php?kodeBooking=$kodeBooking'>Bayar</a>" .
                '</td>
                  </tr>';
        }
        echo "</tbody></table>";
    } else {
        echo '<div class="alert alert-danger">Data booking tidak ditemukan untuk nomor tersebut.</div>';
    }
    echo "</div>";
}
?>
</body>
</html>
