<?php
session_start();
require_once('../config.php');

// Pastikan ID transaksi dan status diposting
if (isset($_POST['id_transaksi']) && isset($_POST['status_pesanan'])) {
    $id_transaksi = $_POST['id_transaksi'];
    $status_pesanan = $_POST['status_pesanan'];

    try {
        // Update status transaksi
        $query = $db->prepare("UPDATE transaksi SET status_pesanan = :status_pesanan WHERE id_transaksi = :id_transaksi");
        $query->bindParam(':status_pesanan', $status_pesanan);
        $query->bindParam(':id_transaksi', $id_transaksi);
        
        // Mengeksekusi query
        if ($query->execute()) {
            // Jika berhasil, arahkan ke halaman daftar transaksi lagi
            header("Location: admin.php");
            exit();
        } else {
            // Jika gagal, tampilkan pesan error
            echo "Gagal mengubah status transaksi.";
        }
    } catch (PDOException $e) {
        // Menangani error jika terjadi masalah dengan query
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Data tidak lengkap.";
}
?>
