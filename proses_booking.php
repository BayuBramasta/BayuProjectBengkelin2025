<?php
include 'db/koneksi.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama             = htmlspecialchars($_POST['nama']);
    $no_hp            = htmlspecialchars($_POST['no_hp']);
    $tipe_motor       = htmlspecialchars($_POST['tipe_motor']);
    $layanan          = htmlspecialchars($_POST['layanan']);
    $keluhan          = htmlspecialchars($_POST['keluhan']);
    $tanggal_booking  = htmlspecialchars($_POST['tanggal_booking']);
    $jam_booking      = htmlspecialchars($_POST['jam_booking']);
    $email            = $_POST['email'];
    // generate kode booking
    $kode_booking     = 'BK-' . strtoupper(uniqid());
}
    if (empty($nama) || empty($no_hp) || empty($tanggal_booking) || empty($jam_booking)) {
        echo "<h3 style='color:red;'>Ada data wajib yang belum diisi!</h3>";
        echo "<a href='booking.php'>← Kembali ke Form</a>";
        exit;
    }

    // Simpan ke database
    $query = "INSERT INTO booking (kode_booking, nama, no_hp, tipe_motor, layanan, keluhan, tanggal_booking, jam_booking, email) 
              VALUES ('$kode_booking','$nama', '$no_hp', '$tipe_motor', '$layanan', '$keluhan', '$tanggal_booking', '$jam_booking', '$email')";

// mengirim pesan ke email pengguna
    $mail = new PHPMailer(true);
    
  try {
        //Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com'; // SMTP server
        $mail->SMTPAuth   = true;
        $mail->Username   = 'bayubramasta125@gmail.com'; // ganti dengan emailmu
        $mail->Password   = 'sual mowj ucoj wqnr'; // ganti dengan app password Gmail
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
    
        //Recipients
        $mail->setFrom('bayubramasta125@gmail.com', 'Bayu');
        $mail->addAddress($email, $nama);
    
        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Konfirmasi Booking Servis Motor';
        // email format lama
        // $mail->Body    = "Halo $nama,\n\nBooking servis Anda telah berhasil!\n\nDetail Booking:\nLayanan: $layanan\nTanggal: $tanggal_booking\nJam: $jam_booking\n\nTerima kasih telah mempercayakan servis motor Anda kepada kami!";
        $mail->Body    = '
        <div style="font-family: Arial, sans-serif; padding: 20px; background-color: #f4f4f4;">
    <div style="max-width: 600px; margin: auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
      <div style="background-color: #0d6efd; color: white; padding: 20px; text-align: center;">
        <h2>Booking Berhasil!</h2>
      </div>
      <div style="padding: 20px;">
        <p>Halo <strong>' . htmlspecialchars($nama) . '</strong>,</p>
        <p>Terima kasih telah melakukan booking servis motor. Berikut detail booking Anda:</p>
        <table style="width: 100%; border-collapse: collapse;">
          <tr><td style="padding: 8px;">📅 <strong>Kode Booking:</strong></td><td>' . htmlspecialchars($kode_booking) . '</td></tr>
          <tr><td style="padding: 8px;">📅 <strong>Tanggal:</strong></td><td>' . htmlspecialchars($tanggal_booking) . '</td></tr>
          <tr><td style="padding: 8px;">⏰ <strong>Jam:</strong></td><td>' . htmlspecialchars($jam_booking) . '</td></tr>
          <tr><td style="padding: 8px;">🔧 <strong>Layanan:</strong></td><td>' . htmlspecialchars($layanan) . '</td></tr>
          <tr><td style="padding: 8px;">🚗 <strong>Tipe Motor:</strong></td><td>' . htmlspecialchars($tipe_motor) . '</td></tr>
          <tr><td style="padding: 8px;">📞 <strong>No HP:</strong></td><td>' . htmlspecialchars($no_hp) . '</td></tr>
          <tr><td style="padding: 8px;">📝 <strong>Keluhan:</strong></td><td>' . htmlspecialchars($keluhan) . '</td></tr>
        </table>
        <p style="margin-top: 20px;">Kami akan segera memproses permintaan Anda. Sampai jumpa di bengkel!</p>
      </div>
      <div style="background-color: #f1f1f1; padding: 15px; text-align: center; font-size: 12px; color: #666;">
        &copy; ' . date('Y') . ' Servis Motor. All rights reserved.
      </div>
    </div>
  </div>
        ';
        
        $mail->send();
        echo 'Email berhasil dikirim!';
    } catch (Exception $e) {
        echo "Email gagal dikirim. Error: {$mail->ErrorInfo}";
    }
    // Kirim email
    mail($to, $subject, $message, $headers);

    if (mysqli_query($conn, $query)) {
        echo '<script>alert("Booking berhasil disimpan!"); window.location.href="booking.php";</script>';
    } else {
        echo "Gagal menyimpan data: " . mysqli_error($conn);
    }

?>
