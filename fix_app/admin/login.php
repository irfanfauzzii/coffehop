<?php
session_start();
require_once('../config.php');

// Cek apakah user sudah login
if (isset($_SESSION['user_id'])) {
    header('Location: daftar_transaksi.php'); // Untuk admin atau halaman yang sesuai
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query untuk memeriksa username dan password
    $query = $db->prepare("SELECT * FROM user WHERE username = :username");
    $query->execute(['username' => $username]);
    $user = $query->fetch(PDO::FETCH_ASSOC);

    if ($user && md5($password) === $user['password']) {
        // Password cocok
        $_SESSION['user_id'] = $user['id_user'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // Redirect berdasarkan role user
        if ($user['role'] == 'admin') {
            header('Location: daftar_transaksi.php'); // Admin diarahkan ke daftar transaksi
        } else {
            header('Location: index.php'); // Customer diarahkan ke halaman utama
        }
        exit();
    } else {
        $error = 'Username atau password salah.';
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
    <h2 class="text-center mb-4">Login</h2>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form action="login.php" method="POST">
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
        <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>

    <p class="text-center mt-3">Belum punya akun? <a href="register.php">Daftar disini</a></p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
