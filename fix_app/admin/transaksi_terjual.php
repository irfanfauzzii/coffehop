<?php
// Menyertakan file konfigurasi untuk koneksi database
include('../config.php');

// Default tanggal (hari ini)
$tanggal = date('Y-m-d');

// Jika ada pilihan tanggal (misalnya besok), gunakan tanggal yang dipilih
if (isset($_GET['tanggal'])) {
    $tanggal = $_GET['tanggal'];
}

try {
    // Query untuk mendapatkan produk yang terjual beserta kuantitas dan harga satuan berdasarkan tanggal transaksi
    $query = "SELECT k.nama_kopi, SUM(d.kuantitas) as total_terjual, d.harga_satuan
              FROM detailtransaksi d
              JOIN transaksi t ON d.id_transaksi = t.id_transaksi
              JOIN kopi k ON d.id_kopi = k.id_kopi
              WHERE t.tanggal_transaksi = :tanggal
              GROUP BY k.nama_kopi, d.harga_satuan";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':tanggal', $tanggal, PDO::PARAM_STR);
    $stmt->execute();

    // Ambil hasil produk terjual
    $produk_terjual = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit;
}

// Menutup koneksi

// echo "Tanggal yang dihasilkan oleh PHP: " . date('Y-m-d');  // Debug format tanggal


$stmt = null;
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produk Terjual</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --brown: #6f4e37; /* Coklat */
            --dark: #333;
            --light: #fff;
            --gray: #999;
            --beige: #f7f0e1;  /* Warna beige untuk latar */
            --blue: #007BFF;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
            color: #333;
            padding-top: 8rem; /* Memberikan ruang untuk navbar tetap */
        }

        h1 {
            text-align: center;
            color: var(--brown);
            font-size: 3rem; /* Memperbesar ukuran teks judul */
            margin-bottom: 2rem;
        }

        .transaksi-container {
            width: 90%;
            max-width: 1100px;
            margin: 0 auto;
            background-color: var(--light);
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            margin-top: 6rem; /* Memberikan jarak antara navbar dan konten */
        }

        .form-date {
            margin-bottom: 30px;
            display: flex;
            justify-content: center;
            gap: 15px;
            align-items: center;
        }

        .form-date .form-label {
            font-size: 1.3rem; /* Memperbesar ukuran teks label */
            color: var(--dark);
        }

        .form-date .form-control {
            padding: 12px; /* Menambah padding input agar lebih besar */
            font-size: 1.2rem; /* Memperbesar ukuran teks input */
            border: 1px solid #ddd;
            border-radius: 8px;
            width: 250px; /* Memperbesar lebar input */
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .form-date .form-control:focus {
            border-color: var(--blue);
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        .form-date .btn {
            padding: 12px 24px; /* Memperbesar ukuran tombol */
            font-size: 1.2rem; /* Memperbesar ukuran teks tombol */
            font-weight: bold;
            background-color: var(--blue);
            color: var(--light);
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .form-date .btn:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 1.5rem; /* Memperbesar ukuran teks dalam tabel */
        }

        .table th,
        .table td {
            padding: 20px; /* Memperbesar padding dalam tabel */
            text-align: center;
            border: 1px solid #ddd;
            font-size: 1.5rem; /* Memperbesar ukuran teks dalam tabel */
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

        .info {
            text-align: center;
            background-color: #f4f4f4;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        .info h2 {
            font-size: 1.5rem;
            color: var(--brown);
        }

        /* Responsif */
        @media (max-width: 768px) {
            .transaksi-container {
                width: 95%;
            }

            .form-date {
                flex-direction: column;
            }

            .form-date .form-label {
                width: 100%;
                text-align: left;
            }

            .form-date .form-control,
            .form-date .btn {
                width: 100%;
            }

            /* Menyesuaikan ukuran tabel untuk layar kecil */
            .table {
                font-size: 1.2rem; /* Ukuran font untuk perangkat kecil */
            }
        }
    </style>
</head>

<body>
    <?php include('../register/navadmin.php'); ?>
    <div class="container">
        <h1 class="text-center my-4">Produk Terjual pada <?= $tanggal ?></h1>

        <form method="GET" action="" class="form-date">
    <div class="row justify-content-center align-items-center">
        <div class="col-auto">
            <label for="tanggal" class="form-label">Pilih Tanggal:</label>
        </div>
        <div class="col-auto">
            <!-- Pastikan nilai date sudah benar -->
            <input type="date" name="tanggal" id="tanggal" class="form-control" value="<?= $tanggal ?>" max="<?= date('Y-m-d') ?>">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary">Tampilkan</button>
        </div>
    </div>
</form>

        <!-- Tabel Daftar Produk Terjual -->
        <?php if (count($produk_terjual) > 0): ?>
            <table class="produk-terjual table table-striped">
                <thead>
                    <tr>
                        <th>Nama Produk</th>
                        <th>Jumlah Terjual</th>
                        <th>Harga Satuan</th>
                        <th>Total Harga</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $total_pendapatan = 0; // Untuk menghitung total pendapatan
                    foreach ($produk_terjual as $produk): 
                        $total_harga = $produk['total_terjual'] * $produk['harga_satuan'];
                        $total_pendapatan += $total_harga;
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($produk['nama_kopi']) ?></td>
                            <td><?= htmlspecialchars($produk['total_terjual']) ?></td>
                            <td>Rp <?= number_format($produk['harga_satuan'], 2, ',', '.') ?></td>
                            <td>Rp <?= number_format($total_harga, 2, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="info">
                <h2>Total Pendapatan: Rp <?= number_format($total_pendapatan, 2, ',', '.') ?></h2>
            </div>
        <?php else: ?>
            <p class="text-center">Tidak ada produk terjual pada tanggal ini.</p>
        <?php endif; ?>
    </div>
</body>
</html>
