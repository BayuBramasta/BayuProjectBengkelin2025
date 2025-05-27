<?php
session_start();
include "db/koneksi.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $result = mysqli_query(
        $conn,
        "SELECT * FROM admin WHERE username='$username'"
    );

    if (mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);
        if (password_verify($password, $user["password"])) {
            $_SESSION["login"] = true;
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5 col-md-4">
  <h3 class="text-center mb-3">Login Admin</h3>
  <?php if ($error): ?>
    <div class="alert alert-danger"><?= $error ?></div>
  <?php endif; ?>
  <form method="POST" class="border border-dark p-4 d-grid gap-2 bg-white shadow rounded">
    <div>
      <label class="form-label">Username</label>
      <input type="text" name="username" class="form-control border border-dark" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Password</label>
      <input type="password" name="password" class="form-control border border-dark" required>
    </div>
    <button type="submit" class="btn btn-primary w-100 mb-2">Login</button>
    <a href="index.html" class="link-underline-light text-center">Halaman Utama</a>
  </form>
</div>
</body>
</html>
