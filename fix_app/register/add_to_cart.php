<?php
session_start();
require_once('../config.php');

header('Content-Type: application/json');

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Anda harus login terlebih dahulu.']);
    exit();
}

// Ambil data JSON yang dikirim dari halaman produk
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['id_kopi'], $data['kuantitas'], $data['gula'])) {
    $idUser = $_SESSION['user_id'];
    $idKopi = $data['id_kopi'];
    $kuantitas = $data['kuantitas'];
    $gula = $data['gula'];

    try {
        // Cek apakah produk sudah ada di keranjang
        $query = $db->prepare("SELECT * FROM keranjang WHERE id_user = ? AND id_kopi = ?");
        $query->execute([$idUser, $idKopi]);
        $item = $query->fetch(PDO::FETCH_ASSOC);

        // Cek stok produk
        $stokQuery = $db->prepare("SELECT stok FROM Kopi WHERE id_kopi = ?");
        $stokQuery->execute([$idKopi]);
        $stok = $stokQuery->fetch(PDO::FETCH_ASSOC)['stok'];

        // Validasi jumlah agar tidak melebihi stok
        if ($kuantitas > $stok) {
            echo json_encode(['success' => false, 'message' => 'Jumlah melebihi stok yang tersedia.']);
            exit();
        }

        if ($item) {
            // Update kuantitas dan gula jika produk sudah ada di keranjang
            $updateQuery = $db->prepare("UPDATE keranjang SET kuantitas = kuantitas + ?, gula = ? WHERE id_user = ? AND id_kopi = ?");
            $updateQuery->execute([$kuantitas, $gula, $idUser, $idKopi]);

            // Update stok produk di tabel Kopi setelah ditambahkan ke keranjang
            $updateStokQuery = $db->prepare("UPDATE Kopi SET stok = stok - ? WHERE id_kopi = ?");
            $updateStokQuery->execute([$kuantitas, $idKopi]);
        } else {
            // Tambahkan item baru ke keranjang
            $insertQuery = $db->prepare("INSERT INTO keranjang (id_user, id_kopi, kuantitas, gula) VALUES (?, ?, ?, ?)");
            $insertQuery->execute([$idUser, $idKopi, $kuantitas, $gula]);

            // Update stok produk di tabel Kopi setelah ditambahkan ke keranjang
            $updateStokQuery = $db->prepare("UPDATE Kopi SET stok = stok - ? WHERE id_kopi = ?");
            $updateStokQuery->execute([$kuantitas, $idKopi]);
        }

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Data tidak valid.']);
}
