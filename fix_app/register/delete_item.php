<?php
require_once('../config.php');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $idKeranjang = $data['id_keranjang'];

    if (!isset($idKeranjang)) {
        echo json_encode(['success' => false, 'message' => 'ID keranjang tidak ditemukan']);
        exit;
    }

    try {
        // Ambil data kuantitas produk dari keranjang
        $query = $db->prepare("SELECT id_kopi, kuantitas FROM keranjang WHERE id_keranjang = ?");
        $query->execute([$idKeranjang]);
        $item = $query->fetch(PDO::FETCH_ASSOC);

        if (!$item) {
            echo json_encode(['success' => false, 'message' => 'Produk tidak ditemukan']);
            exit;
        }

        // Mulai transaksi untuk mengupdate stok dan menghapus item dari keranjang
        $db->beginTransaction();

        // Menghapus item dari keranjang
        $deleteQuery = $db->prepare("DELETE FROM keranjang WHERE id_keranjang = ?");
        $deleteQuery->execute([$idKeranjang]);

        // Memperbarui stok produk menggunakan trigger
        $updateQuery = $db->prepare("UPDATE Kopi SET stok = stok + ? WHERE id_kopi = ?");
        $updateQuery->execute([$item['kuantitas'], $item['id_kopi']]);

        // Commit transaksi
        $db->commit();

        echo json_encode(['success' => true, 'message' => 'Produk berhasil dihapus']);
    } catch (PDOException $e) {
        $db->rollBack();
        echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
    }
}
?>
