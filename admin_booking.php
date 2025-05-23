<?php
session_start();
if (!isset($_SESSION['login'])) {
  header("Location: login.php");
  exit;
}
include 'db/koneksi.php';
// sort & search
$sort = "DESC";
if (isset($_POST['newest'])) {
  $sort = "DESC";
}
elseif(isset($_POST['oldest'])){
  $sort = "";
  
}

// Cek jika ada input pencarian
$search = isset($_POST['search']) ? $_POST['search'] : '';

// Query untuk mencari berdasarkan nama, tanggal, atau layanan
$sql = "SELECT * FROM booking WHERE 
        nama LIKE '%$search%' OR 
        tanggal_booking LIKE '%$search%' OR 
        layanan LIKE '%$search%' ORDER BY created_at $sort";

// Eksekusi query
$result = mysqli_query($conn, $sql);

// Export ke CSV
if (isset($_POST['export_csv'])) {
    // Query data booking
    $result = mysqli_query($conn, "SELECT * FROM booking");

    // Output headers untuk download CSV
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="booking_data.csv"');
    
    // Open PHP output stream untuk mengeluarkan CSV
    $output = fopen('php://output', 'w');
    
    // Menulis header ke CSV
    fputcsv($output, ['No', 'Nama', 'No HP', 'Tipe Motor', 'Layanan', 'Keluhan', 'Tanggal', 'Jam', 'Waktu Booking', 'Status']);

    // Menulis setiap row data ke CSV
    $no = 1;
    while ($row = mysqli_fetch_assoc($result)) {
        fputcsv($output, [
          $no++, 
          $row['nama'], 
            $row['no_hp'], 
            $row['tipe_motor'], 
            $row['layanan'], 
            $row['keluhan'], 
            $row['tanggal_booking'], 
            $row['jam_booking'], 
            $row['created_at'], 
            $row['status']
        ]);
      }
      
    fclose($output);
    exit;
  }

// Export ke PDF
require('fpdf186/fpdf.php');

if (isset($_POST['export_pdf'])) {
    // Query data booking
    $result = mysqli_query($conn, "SELECT * FROM booking");

    // Membuat instance PDF
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 12);
    
    // Menulis judul
    $pdf->Cell(200, 10, 'Daftar Booking Servis', 0, 1, 'C');
    $pdf->Ln(10);

    // Menulis header tabel
    $pdf->Cell(10, 10, 'No', 1);
    $pdf->Cell(40, 10, 'Nama', 1);
    $pdf->Cell(30, 10, 'No HP', 1);
    $pdf->Cell(40, 10, 'Tipe Motor', 1);
    $pdf->Cell(40, 10, 'Layanan', 1);
    $pdf->Cell(30, 10, 'Tanggal', 1);
    $pdf->Cell(30, 10, 'Jam', 1);
    $pdf->Cell(40, 10, 'Waktu Booking', 1);
    $pdf->Cell(30, 10, 'Status', 1);
    $pdf->Ln();
    
    // Menulis data ke tabel PDF
    $no = 1;
    while ($row = mysqli_fetch_assoc($result)) {
      $pdf->Cell(10, 10, $no++, 1);
        $pdf->Cell(40, 10, $row['nama'], 1);
        $pdf->Cell(30, 10, $row['no_hp'], 1);
        $pdf->Cell(40, 10, $row['tipe_motor'], 1);
        $pdf->Cell(40, 10, $row['layanan'], 1);
        $pdf->Cell(30, 10, $row['tanggal_booking'], 1);
        $pdf->Cell(30, 10, $row['jam_booking'], 1);
        $pdf->Cell(40, 10, $row['created_at'], 1);
        $pdf->Cell(30, 10, $row['status'], 1);
        $pdf->Ln();
    }

    // Output PDF ke browser
    $pdf->Output('D', 'booking_data.pdf');
    exit;
}

?>
<!-- html here -->
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Admin - Daftar Booking</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets\css\admin_booking.css">
  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light">
  <!--navbar-->
<nav class="navbar bg-dark text-white">
  <div class="container-fluid">
    <h2 class="text-center fw-bold">Daftar Booking Servis</h2>
    <form class="d-flex" method="POST">
      <!-- sortBy -->
      <div class="dropdown me-2">
        <button class="btn btn-secondary dropdown-toggle bg-dark border border-dark" type="button" data-bs-toggle="dropdown" aria-expanded="false">SortBy</button>
        <ul class="dropdown-menu">
          <li><button type="submit" name="newest" class="dropdown-item">Newest</button></li>
          <li><button type="submit" name="oldest" class="dropdown-item">Oldest</button></li>
        </ul>
      </div>
      <!-- dropdown SaveAs -->
      <div class="dropdown me-2">
        <button class="btn btn-secondary dropdown-toggle bg-dark border border-dark" type="button" data-bs-toggle="dropdown" aria-expanded="false">Save As</button>
        <ul class="dropdown-menu">
          <li><button type="submit" name="export_csv" class="dropdown-item">CSV</button></li>
          <li><button type="submit" name="export_pdf" class="dropdown-item">PDF</button></li>
        </ul>
      </div>
      <!-- Tombol kembali ke dashboard -->
      <button class="btn btn-primary "><a href="admin_dashboard.php" class="text-white col align-self-center link-underline-primary">Dashboard</a></button>
    </form>
  </div>
</nav>
<!-- notifikasi -->

<?php if (isset($_GET['status'])):?>
  <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
    <?php
      if ($_GET['status'] == 'hapus_sukses'){
        echo 'Data berhasil dihapus!';
      } 
      elseif ($_GET['status'] == 'edit_sukses'){
        echo 'Data berhasil diubah!';
      } 
      elseif ($_GET['status'] == 'verifikasi_sukses'){
        echo 'Pembayaran berhasil diverifikasi!';
      } 
      elseif ($_GET['status'] == 'input_gagal'){
        echo 'Gagal menyimpan data!';
      } 
    ?>
    <a href="clearBooking.php" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></a>
  </div>
<?php endif; ?>


  <div class="container mt-5">

    <!-- table for query -->
    <form class="d-flex mb-3" method="POST">
      <label class="col align-self-center me-2">Cari Booking:</label>
      <input type="text" class="form-control me-2 border border-dark" name="search" placeholder="Nama, Tanggal, atau Layanan" value="<?= isset($_POST['search']) ? $_POST['search'] : '' ?>">
      <button type="submit" class="btn btn-primary me-2">Cari</button>
    </form>

    <table class="table table-bordered table-striped bg-white shadow table-responsive-sm">
      <thead class="table-dark">
        <tr>
          <th>No</th>
          <th>Nama</th>
          <th>No HP</th>
          <th>Tipe Motor</th>
          <th>Layanan</th>
          <th>Keluhan</th>
          <th>Tanggal</th>
          <th>Jam</th>
          <th>Waktu Booking</th>
          <th>Status</th>
          <th>Aksi</th>
          <th>Bukti</th>
          <th>Sudah bayar</th>
        </tr>

      </thead>
      <tbody>
      <?php if (mysqli_num_rows($result) > 0): ?>
        <?php $no = 1; while($row = mysqli_fetch_assoc($result)) : ?>
        <tr>
          <td><?= $no++ ?></td>
          <td><?= htmlspecialchars($row['nama']) ?></td>
          <td><?= htmlspecialchars($row['no_hp']) ?></td>
          <td><?= htmlspecialchars($row['tipe_motor']) ?></td>
          <td><?= htmlspecialchars($row['layanan']) ?></td>
          <td><?= htmlspecialchars($row['keluhan']) ?></td>
          <td><?= $row['tanggal_booking'] ?></td>
          <td><?= $row['jam_booking'] ?></td>
          <td><?= $row['created_at'] ?></td>
          <td>
            <?php
              $status = htmlspecialchars($row['status']);
              $badge = 'secondary';
              if ($status == 'Diproses') $badge = 'warning';
              if ($status == 'Selesai') $badge = 'success';
              if ($status == 'Lunas') $badge = 'success';
            ?>
            <span class="badge bg-<?= $badge ?>"><?= $status ?></span>
          </td>
          <td class="d-grid">
             <a href="admin_hapus.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger mb-2" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
            <a href="admin_edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
          </td>
          <!-- upload bukti -->
          <td>
            <form method="post" enctype="multipart/form-data" class="d-grid">
              <input type="file" name="bukti" class="inputfile" id="file" required />
              <label class="btn btn-primary btn-sm" for="file">Upload</label>
              <button  class="btn btn-success btn-sm mt-2" type="submit">Kirim</button>
            </form>
            <?php
              include 'db/koneksi.php';
              $id = $row['id'];
              
              if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $nama_file = $_FILES['bukti']['name'];
                $tmp = $_FILES['bukti']['tmp_name'];
                $folder = 'uploads/' . $nama_file;
                
                move_uploaded_file($tmp, $folder);
                
                $query = "UPDATE booking SET bukti_transfer='$folder', status_pembayaran='Menunggu Verifikasi' WHERE id=$id";
                mysqli_query($conn, $query);
                
                header("Location: admin_booking.php?upload=success");
              }
              ?>
          </td>
          <td>
            <!--Verifikasi bukti pembayaran -->
            <?php if ($row['bukti_transfer']): ?>
              <a href="<?= $row['bukti_transfer'] ?>" target="_blank">Lihat Bukti</a>
            <?php endif; ?>
          <form method="post" action="verifikasi_pembayaran.php">
            <input type="hidden" name="id" value="<?= $row['id'] ?>">
            <button type="submit" name="verif" class="btn btn-success">Tandai Sudah</button>
          </form>
          </td>
        </tr>
        <?php endwhile; ?>
          <?php else: ?>
            <tr><td colspan="9" class="text-center">Belum ada booking.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</body>
<script>
  // Hapus parameter status setelah notifikasi ditutup
  const alertCloseBtn = document.querySelector('[data-bs-dismiss="alert"]');
  if (alertCloseBtn) {
    alertCloseBtn.addEventListener('click', function () {
      const url = new URL(window.location.href);
      url.searchParams.delete('status');
      window.history.replaceState({}, document.title, url.toString());
    });
  }
</script>
</html>
