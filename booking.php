<!-- booking.php -->
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Booking Servis Motor - BengkelIn</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
  </style>
</head>
<body class="bg-light">
  <div class="container mt-3 col-md-6 p-3 mb-3">
    <h2 class="mb-4 text-center fw-bold">Form Booking Servis Motor</h2>
    <form action="proses_booking.php"  method="POST" class="border border-dark p-4 bg-white shadow rounded d-grid gap-2">
      <div class="mb-3">
        <label for="nama" class="form-label">Nama</label>
        <input type="text" class="form-control border border-dark" id="nama" name="nama" required>
      </div>
      <div class="mb-3">
        <label for="no_hp" class="form-label">Nomor HP</label>
        <input type="text" class="form-control border border-dark" id="no_hp" name="no_hp" required>
      </div>
      <div class="mb-3">
        <label for="tipe_motor" class="form-label">Tipe Motor</label>
        <input type="text" class="form-control border border-dark" id="tipe_motor" name="tipe_motor">
      </div>
      <div class="mb-3">
        <label for="layanan" class="form-label ">Jenis Layanan</label>
        <select class="form-select border border-dark" id="layanan" name="layanan">
          <option value="Ganti Oli">Ganti Oli</option>
          <option value="Servis Ringan">Servis Ringan</option>
          <option value="Servis Berat">Servis Berat</option>
          <option value="Tambal Ban">Tambal Ban</option>
        </select>
      </div>
      <div class="mb-3">
        <label for="keluhan" class="form-label">Keluhan</label>
        <textarea class="form-control border border-dark" id="keluhan" name="keluhan" rows="3"></textarea>
      </div>
      <div class="mb-3">
        <label for="tanggal_booking" class="form-label">Tanggal Booking</label>
        <input type="date" class="form-control border border-dark" id="tanggal_booking" name="tanggal_booking" required>
      </div>
      <div class="mt-1">
          <label for="jam_booking" class="form-label">Jam Booking</label>
          <input type="time" class="form-control border border-dark" id="jam_booking" name="jam_booking" required>
      </div>
      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control border border-dark" name="email" required>
      </div>
      <button type="submit" class="btn btn-primary p-2 mt-3">Kirim Booking</button>
      <a href="index.html" class="link-underline-light text-primary text-center">Halaman Utama</a>
    </form>
  </div>
</body>
</html>
