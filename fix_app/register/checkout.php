<?php
session_start();
require_once('../config.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$idUser = $_SESSION['user_id'];

// Mengambil total harga dari keranjang
$query = $db->prepare("SELECT SUM(keranjang.kuantitas * kopi.harga) AS total_harga
                       FROM keranjang 
                       JOIN kopi ON keranjang.id_kopi = kopi.id_kopi
                       WHERE keranjang.id_user = ?");
$query->execute([$idUser]);
$totalHarga = $query->fetchColumn();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $metodePembayaran = $_POST['metode_pembayaran'];
    $nomorMeja = $_POST['nomor_meja'] ?? null;

    try {
        $db->beginTransaction();

        // Menyimpan transaksi
        $query = $db->prepare("INSERT INTO transaksi (id_user, tanggal_transaksi, total_harga, metode_pembayaran, status_pesanan, nomor_meja) 
                              VALUES (?, CURDATE(), ?, ?, 'Menunggu Pembayaran', ?)");
        $query->execute([$idUser, $totalHarga, $metodePembayaran, $nomorMeja]);

        $idTransaksi = $db->lastInsertId();

        // Menarik data keranjang untuk memasukkan ke detail transaksi
        $query = $db->prepare("SELECT keranjang.id_kopi, keranjang.kuantitas, kopi.harga
                               FROM keranjang
                               JOIN kopi ON keranjang.id_kopi = kopi.id_kopi
                               WHERE keranjang.id_user = ?");
        $query->execute([$idUser]);
        $items = $query->fetchAll(PDO::FETCH_ASSOC);

        // Menyimpan detail transaksi
        foreach ($items as $item) {
            $query = $db->prepare("INSERT INTO detailtransaksi (id_transaksi, id_kopi, kuantitas, harga_satuan)
                                  VALUES (?, ?, ?, ?)");
            $query->execute([$idTransaksi, $item['id_kopi'], $item['kuantitas'], $item['harga']]);

            // Mengurangi stok kopi
            $query = $db->prepare("UPDATE kopi SET stok = stok - ? WHERE id_kopi = ?");
            $query->execute([$item['kuantitas'], $item['id_kopi']]);
        }

        // Menghapus keranjang setelah checkout
        $query = $db->prepare("DELETE FROM keranjang WHERE id_user = ?");
        $query->execute([$idUser]);

        $db->commit();

        header("Location: transaksi.php");
        exit;

    } catch (PDOException $e) {
        $db->rollBack();
        echo "Terjadi kesalahan: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Checkout</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

  <style>
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f4f6f7;
        padding-top: 20px;
    }

    .container {
        margin-top: 30px;
        padding: 40px;
        background-color: #fff;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        border-radius: 15px;
        max-width: 600px;
    }

    h2 {
        text-align: center;
        font-size: 2.5rem;
        color: #333;
        margin-bottom: 30px;
    }

    .form-label {
        font-size: 1.8rem;
        font-weight: bold;
        color: #333;
    }

    .form-control {
        font-size: 1.8rem;
        padding: 1rem;
        height: 55px;
        border-radius: 10px;
        border: 1px solid #ccc;
        transition: border 0.3s ease;
    }

    .form-control::placeholder {
        font-size: 1.6rem;
        color: #aaa;
    }

    .form-control:focus {
        border-color: #28a745;
    }

    .mb-4 {
        margin-bottom: 40px;
    }

    .btn {
        font-size: 1.8rem;
        padding: 15px;
        background-color: #28a745;
        border: none;
        color: white;
        border-radius: 10px;
        width: 100%;
    }

    .btn:hover {
        background-color: #218838;
    }

    .payment-methods {
        display: flex;
        justify-content: space-between;
        margin-top: 30px;
    }

    .payment-method {
        text-align: center;
        cursor: pointer;
        padding: 20px;
        border: 2px solid #ccc;
        border-radius: 10px;
        transition: all 0.3s ease;
        width: 22%;
    }

    .payment-method:hover {
        background-color: #f1f1f1;
    }

    .payment-method i {
        font-size: 40px;
        color: #28a745;
        margin-bottom: 15px;
    }

    .payment-method p {
        font-size: 1.8rem;
        font-weight: bold;
        color: #333;
    }

    .total-price {
        background-color: #e9f7e9;
        padding: 15px;
        border-radius: 10px;
        font-size: 2rem;
        font-weight: bold;
        text-align: center;
        margin-bottom: 30px;
    }

    .invalid-feedback {
        font-size: 1.5rem;
        color: red;
    }

    input:invalid, select:invalid {
        border-color: red;
    }

    .payment-methods .payment-method.selected {
        border-color: #28a745;
    }

    .payment-methods .payment-method.selected:hover {
        background-color: #e9f7e9;
    }

  </style>
</head>
<body>
  <?php include('navbar.php'); ?>

  <div class="container">
    <h2>Pembayaran</h2>
    <form method="POST" onsubmit="return validateForm()">
      <div class="total-price">
        <span>Total Harga: </span><span>IDR <?= number_format($totalHarga, 0, ',', '.') ?></span>
      </div>

      <div class="mb-4">
        <label for="nomor_meja" class="form-label">Nomor Meja</label>
        <input type="text" class="form-control" id="nomor_meja" name="nomor_meja" placeholder="Masukkan nomor meja (opsional)">
      </div>

      <div class="mb-4">
        <label for="metode_pembayaran" class="form-label">Metode Pembayaran</label>
        <div class="payment-methods">
          <div class="payment-method" id="gopay" onclick="selectPaymentMethod('GoPay')">
            <i class="fab fa-google-pay"></i>
            <p>GoPay</p>
          </div>
          <div class="payment-method" id="ovo" onclick="selectPaymentMethod('OVO')">
            <i class="fab fa-cc-visa"></i>
            <p>OVO</p>
          </div>
          <div class="payment-method" id="cod" onclick="selectPaymentMethod('COD')">
            <i class="fas fa-box"></i>
            <p>CASH</p>
          </div>
          <div class="payment-method" id="transfer-bank" onclick="selectPaymentMethod('Transfer Bank')">
            <i class="fas fa-university"></i>
            <p>Transfer Bank</p>
          </div>
        </div>
        <input type="hidden" id="metode_pembayaran" name="metode_pembayaran" required>
        <div class="invalid-feedback">Metode pembayaran harus dipilih.</div>
      </div>

      <button type="submit" class="btn" id="submit-btn" disabled>Lanjutkan Pembayaran</button>
    </form>
  </div>

  <?php include('footer.php'); ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function validateForm() {
      var nomorMeja = document.getElementById('nomor_meja').value;
      var metodePembayaran = document.getElementById('metode_pembayaran').value;

      if (metodePembayaran === 'COD' && !nomorMeja) {
        document.getElementById('nomor_meja').setCustomValidity('Nomor meja harus diisi jika memilih COD.');
        return false;
      } else {
        document.getElementById('nomor_meja').setCustomValidity('');
        return true;
      }
    }

    // Enable/Disable tombol Lanjutkan Pembayaran
    document.getElementById('metode_pembayaran').addEventListener('change', toggleSubmitButton);
    document.getElementById('nomor_meja').addEventListener('input', toggleSubmitButton);

    function toggleSubmitButton() {
      var metodePembayaran = document.getElementById('metode_pembayaran').value;
      var nomorMeja = document.getElementById('nomor_meja').value;
      var submitBtn = document.getElementById('submit-btn');
      
      if (metodePembayaran && (metodePembayaran !== 'COD' || nomorMeja)) {
        submitBtn.disabled = false;
      } else {
        submitBtn.disabled = true;
      }
    }

    // Select Payment Method
    function selectPaymentMethod(method) {
      document.getElementById('metode_pembayaran').value = method;
      toggleSubmitButton();

      // Highlight the selected method
      var paymentMethods = document.querySelectorAll('.payment-method');
      paymentMethods.forEach(function(paymentMethod) {
        paymentMethod.classList.remove('selected');
      });

      var selectedMethod = document.getElementById(method.toLowerCase().replace(' ', '-'));
      selectedMethod.classList.add('selected');
    }
  </script>
</body>
</html>
