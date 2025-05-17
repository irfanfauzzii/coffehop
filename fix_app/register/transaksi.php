<?php
session_start();
require_once('../config.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$idUser = $_SESSION['user_id'];

// Query untuk mendapatkan transaksi berdasarkan user
$query = $db->prepare("SELECT * FROM transaksi WHERE id_user = ? ORDER BY tanggal_transaksi DESC");
$query->execute([$idUser]);
$transaksiList = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar Transaksi</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
  <link rel="stylesheet" href="boxicons-2.1.4/css/boxicons.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.5.0/remixicon.css">
  <script src="https://unpkg.com/feather-icons"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    /* Styling untuk tampilan struk */
    .modal-content {
        background-color:rgb(113, 78, 78);
        color: #333;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        padding: 2rem;
    }

    .modal-header {
        background-color: #6f4e37;
        color: white;
        border-bottom: 2px solid #5a3d28;
    }

    .modal-body {
        font-size: 1.4rem;
        line-height: 1.8;
        margin-top: 1rem;
    }
    .table th, .table td {
        color: #6f4e37; /* Warna coklat untuk teks */
        padding: 12px 15px;
        text-align: left;
        vertical-align: middle;
        border: 1px solid #ddd;
    }

    .table th {
        background-color: #6f4e37;
        color: white;
        font-weight: bold;
        text-transform: uppercase;
    }

    .table-striped tbody tr:nth-child(odd) {
        background-color:rgb(255, 255, 255);
    }

    /* Tombol Detail yang Lebih Besar dan Rapi */
    .btn-detail {
        background-color: #6f4e37;
        color: white;
        padding: 12px 30px; /* Ukuran padding lebih besar */
        border-radius: 30px;
        font-weight: bold;
        text-decoration: none;
        transition: background-color 0.3s ease;
        font-size: 1.2rem; /* Ukuran font lebih besar */
        display: inline-block; /* Menjaga agar tombol tetap dalam baris yang sama */
        margin: 5px 10px; /* Memberikan jarak antar tombol */
    }

    .btn-detail:hover {
        background-color: #5a3d28;
    }

    .btn-detail:active {
        background-color: #4a2b17;
    }

    .modal-footer button {
        padding: 10px 20px;
        font-size: 1.2rem;
    }

    .modal-body p {
        font-size: 1.4rem;
    }

    .table-responsive {
        overflow-x: auto;
    }

    /* Styling untuk Judul */
    h2 {
        background-color: #f7f7f7;
        padding: 20px;
        border-radius: 10px;
        color: #6f4e37;
        font-size: 3rem;
        text-align: center;
        font-weight: bold;
        margin-bottom: 10px; /* Memberikan ruang lebih besar bawah judul */
    }

    .section-title {
        font-size: 2rem;
        font-weight: bold;
        margin-bottom: 1.5rem;
        text-align: center;
        color: #6f4e37;
    }
    /* Mengubah warna teks header tabel */
.table th {
    color: #6f4e37; /* Warna coklat muda untuk teks */
}

    /* Styling Tombol Print */
    .btn-print {
        background-color: #007bff;
        color: white;
        padding: 10px 20px;
        border-radius: 30px;
        font-size: 1.2rem;
        font-weight: bold;
        transition: background-color 0.3s ease;
    }

    .btn-print:hover {
        background-color: #0056b3;
    }

    /* Menambahkan margin-top untuk menghindari tumpang tindih dengan navbar */
    .container.my-5 {
    margin-top: 120px; /* Memberikan jarak lebih besar antara navbar dan konten */
    padding-top: 50px; /* Menambah jarak atas */
}


    /* Menambah padding bawah pada tabel untuk ruang lebih */
    .table-responsive {
        padding-bottom: 30px; /* Mengurangi padding bawah */
    }
  </style>
</head>
<body>

<?php include('navbar.php'); ?>

<!-- Main Content Section -->
<div class="container my-5">
    <!-- Menambahkan Judul Daftar Transaksi -->
    <h2>Daftar Transaksi</h2> <!-- Judul "Daftar Transaksi" ditambahkan di sini -->
    
    <?php if (count($transaksiList) > 0): ?>
      <div class="table-responsive">
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>No</th>
              <th>Tanggal Transaksi</th>
              <th>Total Harga</th>
              <th>Metode Pembayaran</th>
              <th>Status Pesanan</th>
              <th>Detail</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($transaksiList as $index => $transaksi): ?>
              <tr>
                <td><?= $index + 1 ?></td>
                <td><?= $transaksi['tanggal_transaksi'] ?></td>
                <td>Rp <?= number_format($transaksi['total_harga'], 2, ',', '.') ?></td>
                <td><?= $transaksi['metode_pembayaran'] ?></td>
                <td><?= $transaksi['status_pesanan'] ?></td>
                <td>
                    <a href="#" class="btn-detail" data-bs-toggle="modal" data-bs-target="#modalDetail" onclick="loadDetail(<?= $transaksi['id_transaksi'] ?>)">
                        <i class="fas fa-info-circle"></i> Detail
                    </a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <p>Belum ada transaksi yang dilakukan.</p>
    <?php endif; ?>
</div>


<!-- Modal Content - Struk Pembelian -->
<div class="modal fade" id="modalDetail" tabindex="-1" aria-labelledby="modalDetailLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDetailLabel">Struk Pembelian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalContent">
                <!-- Konten struk akan dimuat di sini -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn-print" onclick="printStruk()">Cetak</button>
            </div>
        </div>
    </div>
</div>

<script>
    function loadDetail(idTransaksi) {
        fetch('get.php?id_transaksi=' + idTransaksi)
            .then(response => response.json())
            .then(data => {
                let detailsHtml = ` 
                    <div style="text-align: center;">
                        <h3 style="color: #6f4e37; font-size: 2rem;">Struk Pembelian</h3>
                        <p style="font-size: 1.4rem; font-weight: bold;">ID Transaksi: ${data.id_transaksi}</p>
                        <p style="font-size: 1.4rem; font-weight: bold;">Tanggal Transaksi: ${data.tanggal_transaksi}</p>
                        <hr>
                        <h5 style="color: #6f4e37; font-size: 1.8rem;">Daftar Pembelian</h5>
                        ${data.items.map(item => `
                            <p style="font-size: 1.4rem; margin: 0;">
                                ${item.nama_kopi} x ${item.kuantitas} | Rp ${item.harga_satuan} 
                                = Rp ${item.subtotal}
                            </p>
                        `).join('')}
                        <hr>
                        <p style="font-size: 1.6rem; font-weight: bold;">Total Harga: Rp ${data.total_harga}</p>
                        <p style="font-size: 1.6rem;">Metode Pembayaran: ${data.metode_pembayaran}</p>
                        <p style="font-size: 1.6rem;">Status Pesanan: ${data.status_pesanan}</p>
                        <hr>
                        <p style="color: #999; font-size: 1.4rem;">Terima kasih atas pembelian Anda!</p>
                    </div>
                `;
                document.getElementById('modalContent').innerHTML = detailsHtml;
            })
            .catch(error => {
                document.getElementById('modalContent').innerHTML = '<p class="text-danger">Gagal memuat detail transaksi.</p>';
            });
    }

    function printStruk() {
        let content = document.getElementById('modalContent').innerHTML;
        let printWindow = window.open('', '', 'height=600, width=800');
        printWindow.document.write('<html><head><title>Struk Pembelian</title></head><body>');
        printWindow.document.write(content);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.print();
    }
</script>

<?php include('footer.php'); ?>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>

</body>
</html>
