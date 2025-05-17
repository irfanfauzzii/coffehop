<?php
require_once('../config.php');

$idTransaksi = $_GET['id_transaksi'] ?? null;

if (!$idTransaksi) {
    echo json_encode(['error' => 'ID transaksi tidak ditemukan.']);
    exit;
}

$query = $db->prepare("
    SELECT k.nama_kopi, dt.kuantitas, dt.harga_satuan, 
           (dt.kuantitas * dt.harga_satuan) AS subtotal
    FROM detailtransaksi dt
    JOIN kopi k ON dt.id_kopi = k.id_kopi
    WHERE dt.id_transaksi = ?
");
$query->execute([$idTransaksi]);
$details = $query->fetchAll(PDO::FETCH_ASSOC);

$query2 = $db->prepare("SELECT tanggal_transaksi, total_harga, metode_pembayaran, status_pesanan FROM transaksi WHERE id_transaksi = ?");
$query2->execute([$idTransaksi]);
$transaction = $query2->fetch(PDO::FETCH_ASSOC);

if (empty($transaction) || empty($details)) {
    echo json_encode(['error' => 'Detail transaksi tidak ditemukan.']);
    exit;
}

// Format data untuk JSON
$response = [
    'id_transaksi' => $idTransaksi,
    'tanggal_transaksi' => $transaction['tanggal_transaksi'],
    'total_harga' => number_format($transaction['total_harga'], 2, ',', '.'),
    'metode_pembayaran' => $transaction['metode_pembayaran'],
    'status_pesanan' => $transaction['status_pesanan'],
    'items' => []
];

foreach ($details as $detail) {
    $response['items'][] = [
        'nama_kopi' => $detail['nama_kopi'],
        'kuantitas' => $detail['kuantitas'],
        'harga_satuan' => number_format($detail['harga_satuan'], 2, ',', '.'),
        'subtotal' => number_format($detail['subtotal'], 2, ',', '.')
    ];
}

echo json_encode($response);
?>
