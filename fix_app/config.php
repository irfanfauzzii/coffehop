<?php
// Pengaturan koneksi ke database
$host = 'localhost';       // Host database
$dbname = 'kopi';  // Nama database Anda
$username = 'root';        // Username untuk login ke database
$password = '';            // Password untuk login ke database

try {
    // Membuat koneksi dengan database menggunakan PDO
    $db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    
    // Set mode error PDO untuk exception
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Jika terjadi error, tampilkan pesan error
    echo "Koneksi gagal: " . $e->getMessage();
    exit();
}
?>
