<?php
// register.php

require_once('../config.php');

// Proses registrasi saat form disubmit
if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $nomor_telepon = $_POST['nomor_telepon'];
    $alamat = $_POST['alamat'];

    // Validasi input
    if (empty($username) || empty($password) || empty($nama) || empty($email) || empty($nomor_telepon)) {
        $error_message = "Semua kolom harus diisi!";
    } else {
        // Hash password
        $password_hash = password_hash($password, PASSWORD_BCRYPT);

        // Simpan data ke database
        $query = $db->prepare("INSERT INTO user (username, password, role, nama, email, nomor_telepon, alamat) 
                               VALUES (:username, :password, 'customer', :nama, :email, :nomor_telepon, :alamat)");
        $query->bindParam(':username', $username);
        $query->bindParam(':password', $password_hash);
        $query->bindParam(':nama', $nama);
        $query->bindParam(':email', $email);
        $query->bindParam(':nomor_telepon', $nomor_telepon);
        $query->bindParam(':alamat', $alamat);
        
        if ($query->execute()) {
            header('Location: login.php');
            exit();
        } else {
            $error_message = "Gagal registrasi. Coba lagi.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Kopi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f5f5;
            font-family: 'Arial', sans-serif;
        }
        .container {
            max-width: 500px;
            margin-top: 50px;
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .form-control {
            border-radius: 20px;
        }
        .input-group-text {
            border-radius: 20px;
            background-color: #f0f0f0;
        }
        .btn-primary {
            background-color: #4e3b31; /* Warna kopi */
            border: none;
        }
        .btn-primary:hover {
            background-color: #3c2e24;
        }
        .icon {
            font-size: 20px;
            color: #4e3b31;
        }
        .alert {
            border-radius: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="text-center mb-4">Daftar Akun Baru</h2>

    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger"><?= $error_message; ?></div>
    <?php endif; ?>

    <form action="register.php" method="POST">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <div class="input-group">
                <input type="text" class="form-control" id="username" name="username" required>
                <span class="input-group-text"><i class="fas fa-user icon"></i></span>
            </div>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <div class="input-group">
                <input type="password" class="form-control" id="password" name="password" required>
                <span class="input-group-text"><i class="fas fa-key icon"></i></span>
            </div>
        </div>
        <div class="mb-3">
            <label for="nama" class="form-label">Nama</label>
            <div class="input-group">
                <input type="text" class="form-control" id="nama" name="nama" required>
                <span class="input-group-text"><i class="fas fa-user-circle icon"></i></span>
            </div>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <div class="input-group">
                <input type="email" class="form-control" id="email" name="email" required>
                <span class="input-group-text"><i class="fas fa-envelope icon"></i></span>
            </div>
        </div>
        <div class="mb-3">
            <label for="nomor_telepon" class="form-label">Nomor Telepon</label>
            <div class="input-group">
                <input type="text" class="form-control" id="nomor_telepon" name="nomor_telepon" required>
                <span class="input-group-text"><i class="fas fa-phone icon"></i></span>
            </div>
        </div>
        <div class="mb-3">
            <label for="alamat" class="form-label">Alamat</label>
            <div class="input-group">
                <textarea class="form-control" id="alamat" name="alamat" rows="3"></textarea>
                <span class="input-group-text"><i class="fas fa-home icon"></i></span>
            </div>
        </div>

        <button type="submit" name="register" class="btn btn-primary w-100">Daftar</button>
    </form>

    <p class="text-center mt-3">Sudah punya akun? <a href="login.php">Login disini</a></p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
