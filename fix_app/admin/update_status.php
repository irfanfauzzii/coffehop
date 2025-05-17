<?php
session_start();
require_once('../config.php');

// Pastikan id_transaksi ada di URL
if (!isset($_GET['id_transaksi'])) {
    $_SESSION['error_message'] = "ID Transaksi tidak ditemukan!";
    header('Location: daftar_transaksi.php');
    exit();
}

$id_transaksi = $_GET['id_transaksi'];

// Ambil data transaksi berdasarkan id_transaksi
$query = $db->prepare("SELECT * FROM transaksi WHERE id_transaksi = :id_transaksi");
$query->bindParam(':id_transaksi', $id_transaksi);
$query->execute();
$transaksi = $query->fetch(PDO::FETCH_ASSOC);

// Jika transaksi tidak ditemukan
if (!$transaksi) {
    $_SESSION['error_message'] = "Transaksi tidak ditemukan!";
    header('Location: daftar_transaksi.php');
    exit();
}

// Proses form jika disubmit
if (isset($_POST['status_pesanan'])) {
    $status_pesanan = $_POST['status_pesanan'];

    // Validasi status pesanan (harus sesuai dengan ENUM)
    $valid_status = ['Diproses', 'Selesai', 'Dibatalkan'];
    if (!in_array($status_pesanan, $valid_status)) {
        $_SESSION['error_message'] = "Status pesanan tidak valid!";
        header('Location: update_status.php?id_transaksi=' . $id_transaksi);
        exit();
    }

    // Query untuk update status pesanan
    $updateQuery = $db->prepare("UPDATE transaksi SET status_pesanan = :status_pesanan WHERE id_transaksi = :id_transaksi");
    $updateQuery->bindParam(':status_pesanan', $status_pesanan);
    $updateQuery->bindParam(':id_transaksi', $id_transaksi);

    // Eksekusi query
    if ($updateQuery->execute()) {
        $_SESSION['success_message'] = "Status pesanan berhasil diperbarui!";
    } else {
        $_SESSION['error_message'] = "Gagal memperbarui status pesanan!";
    }

    // Redirect kembali ke daftar transaksi
    header('Location: daftar_transaksi.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Status Pesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding-top: 80px;
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
        }

        h2 {
            font-size: 2.4rem;
            font-weight: bold;
            color: #6f4e37;
            margin-bottom: 30px;
            text-align: center;
        }

        .container {
            max-width: 800px;
            margin: auto;
        }

        .info-transaksi {
            background-color: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .info-transaksi p {
            font-size: 1.6rem;
            color: #333;
            margin-bottom: 10px;
        }

        .info-transaksi strong {
            color: #6f4e37;
        }

        .form-label {
            font-weight: bold;
            color: #333;
            font-size: 1.4rem; /* Memperbesar ukuran font label */
        }

        .form-select, .form-control {
            border-radius: 10px;
            padding: 15px;
            font-size: 1.4rem; /* Ukuran font input lebih besar */
            width: 100%;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
            transition: border-color 0.3s ease;
            margin-bottom: 20px;
        }

        .form-select:focus, .form-control:focus {
            border-color: #6f4e37;
            box-shadow: 0 0 5px rgba(111, 78, 55, 0.5);
        }

        .btn-primary {
    background-color: #6f4e37;
    border: none;
    border-radius: 10px;
    padding: 12px 20px; /* Mengurangi ukuran padding */
    font-size: 1.6rem; /* Ukuran font tombol lebih besar */
    transition: background-color 0.3s ease;
    width: auto; /* Menyesuaikan ukuran tombol dengan konten */
    margin-top: 20px;
    display: block;
    margin-left: auto;
    margin-right: auto; /* Membuat tombol berada di tengah */
}

.btn-primary:hover {
    background-color: #4e3b31;
}

.btn-primary:focus {
    outline: none;
    box-shadow: 0 0 5px rgba(111, 78, 55, 0.5);
}


        footer {
            text-align: center;
            margin-top: 30px;
            padding: 10px;
            background-color: #6f4e37;
            color: white;
            border-radius: 10px;
        }

        .alert {
            border-radius: 10px;
            padding: 15px;
            font-size: 1.4rem;
            margin-bottom: 20px;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }
    </style>
</head>
<body>

<?php include('../register/navadmin.php'); ?>

<div class="container my-5">
    <h2 class="text-center">Update Status Pesanan</h2>

    <!-- Informasi Transaksi -->
    <div class="info-transaksi">
        <p><strong>ID Transaksi:</strong> <?= htmlspecialchars($transaksi['id_transaksi']) ?></p>
        <p><strong>Tanggal Transaksi:</strong> <?= date('d F Y', strtotime($transaksi['tanggal_transaksi'])) ?></p>
        <p><strong>Status Saat Ini:</strong> <?= htmlspecialchars($transaksi['status_pesanan']) ?></p>
    </div>

    <!-- Form Update Status -->
    <form action="update_status.php?id_transaksi=<?= $transaksi['id_transaksi'] ?>" method="POST">
        <div class="mb-3">
            <label for="status_pesanan" class="form-label">Status Pesanan</label>
            <select name="status_pesanan" id="status_pesanan" class="form-select">
                <option value="Diproses" <?= $transaksi['status_pesanan'] == 'Diproses' ? 'selected' : '' ?>>Diproses</option>
                <option value="Selesai" <?= $transaksi['status_pesanan'] == 'Selesai' ? 'selected' : '' ?>>Selesai</option>
                <option value="Dibatalkan" <?= $transaksi['status_pesanan'] == 'Dibatalkan' ? 'selected' : '' ?>>Dibatalkan</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update Status</button>
    </form>
</div>

<?php include('../register/footer.php'); ?>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>

</body>
</html>
