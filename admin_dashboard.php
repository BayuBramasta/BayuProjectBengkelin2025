<?php
session_start();
if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit();
}
include "db/koneksi.php";
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="#">Dashboard Admin</a>
    <div class="d-flex">
      <a href="admin_booking.php" class="btn btn-primary me-2">Query</a>
      <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
</div>
</nav>

<div class="container mt-4">
  <h3 class="fw-bold mb-5 text-center">Ringkasan Booking</h3>
  <div class="container d-flex">
    <!-- menampilkan chart -->
    <canvas id="chartBooking" style="width: 100% !important; max-width: 600px; height: 350px;"></canvas>

    <?php
    // Booking hari ini
    $today = date("Y-m-d");
    $result_today = mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total_today FROM booking WHERE tanggal_booking = '$today'"
    );
    $data_today = mysqli_fetch_assoc($result_today);

    // Semua booking
    $result_all = mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total_all FROM booking"
    );
    $data_all = mysqli_fetch_assoc($result_all);

    // Booking selesai
    $result_done = mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total_done FROM booking WHERE status = 'Selesai'"
    );
    $data_done = mysqli_fetch_assoc($result_done);
    ?>
    <div class="container text-center">
      <div class="col-md-6 mb-2 ">
        <div class="card text-white bg-primary shadow">
          <div class="card-body">
            <h5 class="card-title">Booking Hari Ini</h5>
            <p class="card-text fs-4"><?= $data_today["total_today"] ?></p>
          </div>
        </div>
      </div>
    
      <div class="col-md-6 mb-2">
        <div class="card text-white bg-success shadow">
          <div class="card-body">
            <h5 class="card-title">Total Booking</h5>
            <p class="card-text fs-4"><?= $data_all["total_all"] ?></p>
          </div>
        </div>
      </div>
    
      <div class="col-md-6">
        <div class="card text-white bg-warning shadow">
          <div class="card-body">
            <h5 class="card-title">Selesai</h5>
            <p class="card-text fs-4"><?= $data_done["total_done"] ?></p>
          </div>
        </div>
    </div>
    </div>
    <?php $result_recent = mysqli_query(
        $conn,
        "SELECT * FROM booking ORDER BY created_at DESC LIMIT 5"
    ); ?>
    <!-- ambil data dari php ke js -->
    <?php
    // Contoh: Total booking tiap status
    $labels = ["Diproses", "Selesai", "Pending"];
    $data = [];

    foreach ($labels as $status) {
        $query = mysqli_query(
            $conn,
            "SELECT COUNT(*) as total FROM booking WHERE status='$status'"
        );
        $count = mysqli_fetch_assoc($query)["total"];
        $data[] = $count;
    }
    ?>
<script>
  const ctx = document.getElementById('chartBooking').getContext('2d');
  const chartBooking = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: <?= json_encode($labels) ?>,
      datasets: [{
        label: 'Jumlah Booking',
        data: <?= json_encode($data) ?>,
        backgroundColor: ['#ffc107', '#198754', '#6c757d']
      }]
    },
    options: {
      responsive: false,
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            precision: 0
          }
        }
      }
    }
  });
</script>

  </div>
</div>

<!-- 5 tabel terbaru -->
<div class="container">
<div class="card shadow mt-4">
  <div class="card-header bg-dark text-white">
    <h5 class="mb-0 text-center p-2 fw-bold">5 Booking Terbaru</h5>
  </div>
  <div class="card-body p-0">
    <table class="table table-striped mb-0">
      <thead class="table-dark">
        <tr>
          <th>Nama</th>
          <th>Layanan</th>
          <th>Tanggal</th>
          <th>Jam</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = mysqli_fetch_assoc($result_recent)): ?>
        <tr>
          <td><?= htmlspecialchars($row["nama"]) ?></td>
          <td><?= htmlspecialchars($row["layanan"]) ?></td>
          <td><?= $row["tanggal_booking"] ?></td>
          <td><?= $row["jam_booking"] ?></td>
          <td>
            <?php
            $status = htmlspecialchars($row["status"]);
            $badge = "secondary";
            if ($status == "Diproses") {
                $badge = "warning";
            }
            if ($status == "Selesai") {
                $badge = "success";
            }
            if ($status == "Lunas") {
                $badge = "success";
            }
            ?>
            <span class="badge bg-<?= $badge ?>"><?= $status ?></span>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>
</div>

</body>
</html>
