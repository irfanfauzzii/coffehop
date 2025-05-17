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
<html lang="en">
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
:root {
    --pink: #e84393;
    --dark: #333;
    --light: #fff;
    --gray: #999;
    --brown: #6f4e37; /* Coklat */
}

* {
    margin: 0;
    padding: 0;
    font-family: Verdana, Geneva, Tahoma, sans-serif;
    outline: none;
    text-decoration: none;
    text-transform: capitalize;
    transition: .2s linear;
}
/* Memperbesar ukuran font pada tabel */
.table th, .table td {
    font-size: 1.25rem;  /* Mengatur ukuran teks lebih besar */
}
h2 {
    font-size: 2rem; /* Ukuran font untuk judul */
}

/* Menghilangkan garis bawah pada navbar */
header .navbar a {
    font-size: 2rem;
    padding: 0 1.5rem;
    color: #666;
    text-decoration: none; /* Menambahkan ini untuk menghilangkan garis bawah */
}

.btn-coffee {
    background-color: #6f4e37; /* Cokelat kopi */
    color: white;
    border: 2px solid #6f4e37;
    padding: 12px 25px;
    font-size: 1.5rem;
    font-weight: bold;
    border-radius: 25px;
    text-transform: uppercase;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    transition: all 0.3s ease-in-out;
}

.btn-coffee:hover {
    background-color: #5a3d28; /* Darker brown when hovered */
    border-color: #5a3d28;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
}

.btn-coffee:active {
    background-color: #3e2b1f; /* Dark brown when clicked */
    border-color: #3e2b1f;
    box-shadow: none;
}

.btn-coffee:focus {
    outline: none;
}


.btn-detail i {
    font-size: 1.4rem;
}

/* Modal content with improved item list appearance */
.modal-content {
    background-color: #fff;
    color: #333;
    border-radius: 15px;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
    padding: 2rem;
}

.modal-body {
    font-size: 1.2rem;
    line-height: 1.6;
    margin-top: 1.5rem;
}

/* Item list style */
.modal-body ul {
    list-style-type: none;
    padding: 0;
}

.modal-body li {
    margin-bottom: 1rem;
}

/* Remove blue outline and appearance for Detail button */
.btn-detail {
    background-color: #6f4e37; /* Default brown color */
    color: white;
    border: none; /* Remove any border */
    padding: 12px 25px;
    font-size: 1.2rem;
    font-weight: bold;
    border-radius: 25px;
    transition: background-color 0.3s ease-in-out;
    text-decoration: none; /* Ensure it doesn't appear as a link */
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.8rem;
}

.btn-detail:hover {
    background-color: #5a3d28; /* Darker brown on hover */
}

.btn-detail:active {
    background-color: #3e2b1f; /* Even darker brown when clicked */
}

.btn-detail i {
    font-size: 1.4rem;
}

/* Modal footer button style - without blue outline */
.modal-footer button {
    background-color: #6f4e37; /* Coklat saat default */
    color: white;
    font-size: 1.2rem;
    padding: 0.8rem 1.6rem;
    border-radius: 25px;
    border: none;
    transition: background-color 0.3s ease-in-out;
}

.modal-footer button:hover {
    background-color: #5a3d28; /* Darker brown when hover */
}




header .navbar a.active {
    color: var(--brown); /* Mengubah warna teks saat aktif */
    font-weight: bold;   /* Menambahkan penebalan font untuk link aktif */
}

/* Navbar item hover effect */
header .navbar a:hover {
    color: var(--brown); /* Warna coklat saat hover */
}

html {
    font-size: 62.5%;
    scroll-behavior: smooth;
    scroll-padding-top: 6rem;
    overflow-y: auto;
}

section {
    padding: 8rem 9% 2rem 9%; /* Menambahkan padding atas untuk memberi ruang pada konten */
}

.heading {
    text-align: center;
    font-size: 4rem;
    color: var(--dark);
    padding: 2rem;
    margin-bottom: 4rem;
    background: rgba(111, 78, 55, .05); /* Coklat muda */
    border-radius: 1rem;
}

.heading span {
    color: var(--brown);
    font-weight: bold;
}

header {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    background: var(--light);
    padding: 2rem 9%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    z-index: 1000;
    box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .1);
}

header .logo {
    font-size: 3rem;
    color: var(--dark);
    font-weight: bolder;
}

header .logo span {
    color: var(--brown);
}

header .navbar a {
    font-size: 2rem;
    padding: 0 1.5rem;
    color: #666;
}

header .navbar a:hover {
    color: var(--brown);
}

header .icons a {
    font-size: 2.5rem;
    color: var(--dark);
    margin-left: 1.5rem;
}

header .icons a:hover {
    color: var(--brown);
}

header #toggler {
    display: none;
}

header .fa-bars {
    font-size: 3rem;
    color: var(--dark);
    border-radius: .5rem;
    padding: .5rem 1.5rem;
    cursor: pointer;
    border: .1rem solid rgba(0, 0, 0, .3);
    display: none;
}

section {
    padding: 8rem 9% 2rem 9%; /* Menambahkan padding atas agar konten terlihat */
}

.table {
    margin-top: 3rem;
}

h2 {
    margin-top: 10rem; /* Memberikan ruang di atas judul */
}

header {
    z-index: 1000; /* Pastikan navbar berada di atas konten */
}

  </style>
</head>
<body>
<?php include('navbar.php'); ?>
 <!-- Main Content Section -->
<div class="container my-5">
    <h2 class="text-center mb-4" style="color: #6f4e37; font-size: 2rem;">Daftar Transaksi</h2>
    
    <?php if (count($transaksiList) > 0): ?>
      <div class="table-responsive">
        <table class="table table-bordered table-hover" style="font-size: 1.25rem;">
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
                <td>
    <a href="get.php" class="btn-detail" data-bs-toggle="modal" data-bs-target="#modalDetail" onclick="loadDetail(<?= $transaksi['id_transaksi'] ?>)">
        <i class="fas fa-info-circle"></i> Detail
    </a>
</td>





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

<!-- Modal content diperbarui -->
<div class="modal fade" id="modalDetail" tabindex="-1" aria-labelledby="modalDetailLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content" style="background-color: #fff; color: #333; border-radius: 15px; box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);">
            <div class="modal-header" style="background-color: #6f4e37; color: white; border-bottom: 2px solid #5a3d28;">
                <h5 class="modal-title" id="modalDetailLabel" style="font-size: 2rem; font-weight: bold;">Detail Transaksi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalContent" style="font-size: 1.2rem; line-height: 1.6; padding: 1.5rem;">
                <!-- Konten detail transaksi akan dimuat di sini melalui JavaScript -->
            </div>
            <div class="modal-footer" style="background-color: #6f4e37; border-top: 2px solid #5a3d28;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="font-size: 1.2rem; background-color: #6f4e37; border: none; padding: 0.8rem 1.6rem; border-radius: 25px;">
                    Tutup
                </button>
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
                <p><strong>ID Transaksi:</strong> ${data.id_transaksi}</p>
                <p><strong>Tanggal Transaksi:</strong> ${data.tanggal_transaksi}</p>
                <p><strong>Total Harga:</strong> Rp ${data.total_harga}</p>
                <p><strong>Metode Pembayaran:</strong> ${data.metode_pembayaran}</p>
                <p><strong>Status Pesanan:</strong> ${data.status_pesanan}</p>
                <hr>
                <h5 style="color: #6f4e37;">Detail Barang</h5>
                ${data.items.map(item => `
                    <p><strong>Nama Kopi:</strong> ${item.nama_kopi}</p>
                    <p><strong>Kuantitas:</strong> ${item.kuantitas}</p>
                    <p><strong>Harga Satuan:</strong> Rp ${item.harga_satuan}</p>
                    <p><strong>Subtotal:</strong> Rp ${item.subtotal}</p>
                    <hr>
                `).join('')}
            `;
            document.getElementById('modalContent').innerHTML = detailsHtml;
        })
        .catch(error => {
            document.getElementById('modalContent').innerHTML = '<p class="text-danger">Gagal memuat detail transaksi.</p>';
        });
}

</script>





<?php include('footer.php'); ?>

  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>

</body>
</html>
