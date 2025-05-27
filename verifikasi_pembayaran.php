<?php
include "db/koneksi.php";

// include PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require "PHPMailer/src/Exception.php";
require "PHPMailer/src/PHPMailer.php";
require "PHPMailer/src/SMTP.php";

if (isset($_POST["verif"])) {
    $id = $_POST["id"];
    $result = mysqli_query($conn, "SELECT * FROM booking WHERE id=$id");
    $data = mysqli_fetch_assoc($result);
    $update = "UPDATE booking SET status_pembayaran='Sudah Bayar', status='Lunas' WHERE id=$id";
    mysqli_query($conn, $update);
    // define variable for mail
    $nama = $data["nama"];
    $layanan = $data["layanan"];
    $tanggal_booking = $data["tanggal_booking"];
    $status = $data["status"];

    if ($update) {
        header("Location: admin_booking.php?status=verifikasi_sukses");
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = "smtp.gmail.com";
            $mail->SMTPAuth = true;
            $mail->Username = "bayubramasta125@gmail.com";
            $mail->Password = "sual mowj ucoj wqnr"; // gunakan App Password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom("bayubramasta125@gmail.com", "Bengkel GG");
            $mail->addAddress($data["email"], $data["nama"]);

            $mail->isHTML(true);
            $mail->Subject = "Status Booking Anda Telah Diperbarui";
            $mail->Body = "
        <h3>Hai, $nama!</h3>
        <p>Pembayaran anda telah diverifikasi.</p>
        <table>
            <tr><td><strong>Layanan</strong></td><td>: $layanan</td></tr>
            <tr><td><strong>Tanggal</strong></td><td>: $tanggal_booking</td></tr>
            <tr><td><strong>Status Pembayaran</strong></td><td>: <b>$status</b></td></tr>
        </table>
        <p>Terima kasih telah menggunakan layanan kami! 🚀</p>
    ";

            $mail->send();
        } catch (Exception $e) {
            echo "Email gagal dikirim. Error: {$mail->ErrorInfo}";
        }
        exit();
    } else {
        echo "Gagal mengupdate data!";
    }
    echo $query["email"];
}
?>
