<?php
session_start();  // Pastikan session dimulai
require_once('../config.php');

// Proses login saat form disubmit
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validasi input
    if (empty($username) || empty($password)) {
        $error_message = "Semua kolom harus diisi!";
    } else {
        // Query untuk mencari user berdasarkan username
        $query = $db->prepare("SELECT * FROM user WHERE username = :username");
        $query->bindParam(':username', $username);
        $query->execute();
        $user = $query->fetch(PDO::FETCH_ASSOC);

        // Verifikasi password dan role
        if ($user && password_verify($password, $user['password'])) {
            // Set session jika login berhasil
            $_SESSION['user_id'] = $user['id_user'];
            $_SESSION['role'] = $user['role'];

            // Redirect ke halaman utama
            header('Location: home.php');
            exit();
        } else {
            $error_message = "Username atau password salah!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Kopi</title>
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

    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger"><?= $error_message; ?></div>
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

        <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
    </form>

    <p class="text-center mt-3">Belum punya akun? <a href="register.php">Daftar disini</a></p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
