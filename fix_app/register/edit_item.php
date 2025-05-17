<?php
session_start();
require_once('../config.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$idUser = $_SESSION['user_id'];
$idKeranjang = $_GET['id'];
$kuantitas = $_GET['kuantitas'];
$gula = $_GET['gula'];

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newKuantitas = $_POST['kuantitas'];
    $newGula = $_POST['gula'];

    try {
        // Update kuantitas dan gula produk di keranjang
        $query = $db->prepare("UPDATE keranjang SET kuantitas = ?, gula = ? WHERE id_keranjang = ? AND id_user = ?");
        $query->execute([$newKuantitas, $newGula, $idKeranjang, $idUser]);

        // Redirect ke halaman keranjang setelah update
        header('Location: cart.php');
        exit;

    } catch (PDOException $e) {
        echo "Terjadi kesalahan: " . $e->getMessage();
    }
} else {
    // Ambil data produk di keranjang untuk form edit
    try {
        $query = $db->prepare("SELECT k.kuantitas, k.gula FROM keranjang k WHERE k.id_keranjang = ? AND k.id_user = ?");
        $query->execute([$idKeranjang, $idUser]);
        $item = $query->fetch(PDO::FETCH_ASSOC);

        if (!$item) {
            echo "Produk tidak ditemukan atau sudah dihapus dari keranjang.";
            exit;
        }

        // Set nilai default kuantitas dan gula
        $kuantitas = $item['kuantitas'];
        $gula = $item['gula'];
    } catch (PDOException $e) {
        echo "Terjadi kesalahan: " . $e->getMessage();
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Keranjang</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Arial', sans-serif;
    }
    .container {
      background-color: #ffffff;
      padding: 40px;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    h2 {
      color: #6f4e37; /* Warna kopi */
      font-size: 24px;
      margin-bottom: 20px;
    }
    label {
      font-size: 18px;
      color: #6f4e37;
    }
    .btn-primary {
      background-color: #6f4e37;
      border-color: #6f4e37;
    }
    .btn-primary:hover {
      background-color: #563d29;
      border-color: #563d29;
    }
  </style>
</head>
<body>
  <?php include('navbar.php'); ?>

  <div class="container my-5">
    <h2>Edit Produk di Keranjang</h2>
    <form method="POST">
      <div class="mb-3">
        <label for="kuantitas" class="form-label">Jumlah</label>
        <input type="number" class="form-control" id="kuantitas" name="kuantitas" value="<?php echo htmlspecialchars($kuantitas); ?>" required>
      </div>
      <div class="mb-3">
        <label for="gula" class="form-label">Pilih Gula</label>
        <select class="form-select" id="gula" name="gula" required>
          <option value="Gula Pasir" <?php echo ($gula == 'Gula Pasir') ? 'selected' : ''; ?>>Gula Pasir</option>
          <option value="Gula Aren" <?php echo ($gula == 'Gula Aren') ? 'selected' : ''; ?>>Gula Aren</option>
          <option value="Gula Kelapa" <?php echo ($gula == 'Gula Kelapa') ? 'selected' : ''; ?>>Gula Kelapa</option>
          <option value="Gula Stevia" <?php echo ($gula == 'Gula Stevia') ? 'selected' : ''; ?>>Gula Stevia</option>
        </select>
      </div>
      <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    </form>
  </div>

  <?php include('footer.php'); ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
