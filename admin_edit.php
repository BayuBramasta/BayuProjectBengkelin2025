<?php
include 'db/koneksi.php';

// include PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$id = $_GET['id'];
$result = mysqli_query($conn, "SELECT * FROM booking WHERE id=$id");
$data = mysqli_fetch_assoc($result);

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama             = htmlspecialchars($_POST['nama']);
    $no_hp            = htmlspecialchars($_POST['no_hp']);
    $tipe_motor       = htmlspecialchars($_POST['tipe_motor']);
    $layanan          = htmlspecialchars($_POST['layanan']);
    $keluhan          = htmlspecialchars($_POST['keluhan']);
    $tanggal_booking  = htmlspecialchars($_POST['tanggal_booking']);
    $jam_booking      = htmlspecialchars($_POST['jam_booking']);
    $status           = htmlspecialchars($_POST['status']);

    $update = mysqli_query($conn, "UPDATE booking SET
        nama='$nama', 
        no_hp='$no_hp', 
        tipe_motor='$tipe_motor', 
        layanan='$layanan', 
        keluhan='$keluhan', 
        tanggal_booking='$tanggal_booking', 
        jam_booking='$jam_booking',
        status='$status' 
        WHERE id=$id");
    if ($update) {
        header("Location: admin_booking.php?status=edit_sukses");
        // setelah berhasil update status booking
        $mail = new PHPMailer(true);
        try {
          $mail->isSMTP();
          $mail->Host = 'smtp.gmail.com';
          $mail->SMTPAuth = true;
          $mail->Username = 'bayubramasta125@gmail.com';
          $mail->Password = 'sual mowj ucoj wqnr'; // gunakan App Password
          $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
          $mail->Port = 587;

          $mail->setFrom('bayubramasta125@gmail.com', 'Bengkel GG');
          $mail->addAddress($data['email'], $nama);

          $mail->isHTML(true);
          $mail->Subject = 'Status Booking Anda Telah Diperbarui';
          $mail->Body = "
        <h3>Hai, $nama!</h3>
        <p>Status booking servis Anda telah diperbarui.</p>
        <table>
            <tr><td><strong>Layanan</strong></td><td>: $layanan</td></tr>
            <tr><td><strong>Tanggal</strong></td><td>: $tanggal_booking</td></tr>
            <tr><td><strong>Status Baru</strong></td><td>: <b>$status</b></td></tr>
        </table>
        <p>Terima kasih telah menggunakan layanan kami! 🚀</p>
    ";

        $mail->send();
        } catch (Exception $e) {
        echo "Email gagal dikirim. Error: {$mail->ErrorInfo}";
        }
        exit;
    } else {
        echo "Gagal mengupdate data!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Booking</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
  <h2 class="mb-4 text-center">Edit Booking</h2>
  <form method="POST" class="border border-dark p-4 bg-white shadow rounded d-grid gap-2">
    <div class="mb-3">
      <label class="form-label">Nama</label>
      <input type="text" class="form-control" name="nama" value="<?= $data['nama'] ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">No HP</label>
      <input type="text" class="form-control" name="no_hp" value="<?= $data['no_hp'] ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Tipe Motor</label>
      <input type="text" class="form-control" name="tipe_motor" value="<?= $data['tipe_motor'] ?>">
    </div>
    <div class="mb-3">
      <label class="form-label">Jenis Layanan</label>
      <select class="form-select" name="layanan">
        <option <?= $data['layanan'] == 'Ganti Oli' ? 'selected' : '' ?>>Ganti Oli</option>
        <option <?= $data['layanan'] == 'Servis Ringan' ? 'selected' : '' ?>>Servis Ringan</option>
        <option <?= $data['layanan'] == 'Servis Berat' ? 'selected' : '' ?>>Servis Berat</option>
        <option <?= $data['layanan'] == 'Tambal Ban' ? 'selected' : '' ?>>Tambal Ban</option>
      </select>
    </div>
    <div class="mb-3">
      <label class="form-label">Keluhan</label>
      <textarea class="form-control" name="keluhan"><?= $data['keluhan'] ?></textarea>
    </div>
    <div class="mb-3">
      <label class="form-label">Tanggal Booking</label>
      <input type="date" class="form-control" name="tanggal_booking" value="<?= $data['tanggal_booking'] ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Jam Booking</label>
      <input type="time" class="form-control" name="jam_booking" value="<?= $data['jam_booking'] ?>" required>
    </div>
    <div class="mb-3">
      <label for="status" class="form-label">Status</label>
        <select name="status" class="form-select" required>
          <option value="Menunggu" <?= $data['status'] == 'Menunggu' ? 'selected' : '' ?>>Menunggu</option>
          <option value="Diproses" <?= $data['status'] == 'Diproses' ? 'selected' : '' ?>>Diproses</option>
          <option value="Selesai" <?= $data['status'] == 'Selesai' ? 'selected' : '' ?>>Selesai</option>
        </select>
    </div>
    <button type="submit" class="btn btn-success">Simpan Perubahan</button>
    <a href="admin_booking.php" class="btn btn-secondary">Batal</a>
  </form>
</div>
</body>
</html>
