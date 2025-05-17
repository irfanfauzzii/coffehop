<?php
// Aktifkan error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Memulai session dan menghubungkan ke database
session_start();
require_once('../config.php');

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 20; // Jumlah data per halaman
$offset = ($page - 1) * $perPage;

// Query transaksi dengan LIMIT dan OFFSET
$query = $db->prepare("SELECT * FROM transaksi ORDER BY tanggal_transaksi DESC LIMIT :offset, :perPage");
$query->bindValue(':offset', $offset, PDO::PARAM_INT);
$query->bindValue(':perPage', $perPage, PDO::PARAM_INT);
$query->execute();
$transaksiList = $query->fetchAll(PDO::FETCH_ASSOC);

// Hitung total data
$totalQuery = $db->query("SELECT COUNT(*) as total FROM transaksi");
$totalData = $totalQuery->fetch(PDO::FETCH_ASSOC)['total'];
$totalPages = ceil($totalData / $perPage);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Transaksi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        :root {
    --brown: #6f4e37; /* Coklat */
    --dark: #333;
    --light: #fff;
    --gray: #999;
    --beige: #f7f0e1;  /* Warna beige untuk latar */
    --blue: #007BFF;
}

body {
    font-family: 'Arial', sans-serif;
    background-color: #f7f7f7;
    color: #333;
    padding-top: 6rem; /* Memberikan ruang untuk navbar tetap */
}

h2 {
    text-align: center;
    color: var(--brown);
    font-size: 2.5rem;
    margin-bottom: 2rem;
}

.table-responsive {
    margin-top: 2rem;
}

.table {
    width: 100%;
    border-collapse: collapse;
    font-size: 1.4rem;
}

.table th,
.table td {
    padding: 15px;
    text-align: center;
    border: 1px solid #ddd;
}

.table th {
    background-color: var(--brown);
    color: var(--light);
}

.table td {
    background-color: var(--light);
    color: var(--dark);
}

.table tr:nth-child(even) {
    background-color: #f9f9f9;
}

.table tr:hover {
    background-color: var(--beige);
}

.table td,
.table th {
    font-size: 1.4rem;
    color: var(--brown);
}

.table tr:hover td {
    background-color: var(--beige);
}

.pagination {
    justify-content: center;
    margin-top: 2rem;
}

.page-item.active .page-link {
    background-color: var(--blue);
    border-color: var(--blue);
}

.page-link {
    color: var(--blue);
    font-size: 1.2rem;
}

.page-link:hover {
    background-color: #0056b3;
    color: var(--light);
}

    </style>
</head>
<body>

<?php include('../register/navadmin.php'); ?>

<div class="container my-5">
    <h2 class="text-center">Daftar Transaksi</h2>

    <?php if (count($transaksiList) > 0): ?>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal Transaksi</th>
                        <th>Total Harga</th>
                        <th>Metode Pembayaran</th>
                        <th>Status Pesanan</th>
                        <th>Update Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transaksiList as $index => $transaksi): ?>
                        <tr>
                            <td><?= $index + 1 + $offset ?></td>
                            <td><?= $transaksi['tanggal_transaksi'] ?></td>
                            <td>Rp <?= number_format($transaksi['total_harga'], 2, ',', '.') ?></td>
                            <td><?= $transaksi['metode_pembayaran'] ?></td>
                            <td>
                                <?php 
                                    $status = $transaksi['status_pesanan'];
                                    if ($status == 'Diproses') {
                                        echo '<span class="badge bg-warning">Diproses</span>';
                                    } elseif ($status == 'Selesai') {
                                        echo '<span class="badge bg-success">Selesai</span>';
                                    } else {
                                        echo '<span class="badge bg-danger">Dibatalkan</span>';
                                    }
                                ?>
                            </td>
                            <td>
                                <a href="update_status.php?id_transaksi=<?= $transaksi['id_transaksi'] ?>" class="btn btn-primary">Update Status</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Navigasi Pagination -->
        <nav>
            <ul class="pagination justify-content-center">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>

    <?php else: ?>
        <p class="text-center">Belum ada transaksi.</p>
    <?php endif; ?>
</div>

<?php include('../register/footer.php'); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
