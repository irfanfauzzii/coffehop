<?php
session_start();
require_once('../config.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$idUser = $_SESSION['user_id'];
$idTransaksi = $_GET['id_transaksi'] ?? null;

if ($idTransaksi === null) {
    die("ID Transaksi tidak ditemukan.");
}

// Query untuk mengambil data transaksi
$query = $db->prepare("SELECT * FROM transaksi WHERE id_transaksi = ? AND id_user = ?");
$query->execute([$idTransaksi, $idUser]);
$transaksi = $query->fetch(PDO::FETCH_ASSOC);

if (!$transaksi) {
    die("Transaksi tidak ditemukan.");
}

// Query untuk mengambil detail transaksi
$query = $db->prepare("SELECT dt.id_detail_transaksi, k.nama_kopi, dt.kuantitas, dt.harga_satuan, 
                              (dt.kuantitas * dt.harga_satuan) AS subtotal
                       FROM detailtransaksi dt
                       JOIN kopi k ON dt.id_kopi = k.id_kopi
                       WHERE dt.id_transaksi = ?");
$query->execute([$idTransaksi]);
$detailTransaksi = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detail Transaksi</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <?php include('navbar.php'); ?>

  <div class="container my-5">
    <h2>Detail Transaksi</h2>

    <table class="table table-bordered">
      <thead>
        <tr>
          <th>No</th>
          <th>Nama Kopi</th>
          <th>Kuantitas</th>
          <th>Harga Satuan</th>
          <th>Subtotal</th>
        </tr>
      </thead>
      <tbody>
        <?php $totalHarga = 0; ?>
        <?php foreach ($detailTransaksi as $index => $item): ?>
          <tr>
            <td><?= $index + 1 ?></td>
            <td><?= $item['nama_kopi'] ?></td>
            <td><?= $item['kuantitas'] ?></td>
            <td>Rp <?= number_format($item['harga_satuan'], 2, ',', '.') ?></td>
            <td>Rp <?= number_format($item['subtotal'], 2, ',', '.') ?></td>
          </tr>
          <?php $totalHarga += $item['subtotal']; ?>
        <?php endforeach; ?>
      </tbody>
    </table>

    <h4>Total Pembayaran: Rp <?= number_format($totalHarga, 2, ',', '.') ?></h4>

  </div>

  <?php include('footer.php'); ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
