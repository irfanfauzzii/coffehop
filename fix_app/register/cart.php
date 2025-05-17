<?php
session_start();
require_once('../config.php');

// Jika pengguna belum login, arahkan ke halaman login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$idUser = $_SESSION['user_id'];

try {
    // Ambil data produk yang ada di keranjang dengan harga dari tabel Kopi
    $query = $db->prepare("
        SELECT k.id_keranjang, k.id_kopi, k.kuantitas, k.gula, p.nama_kopi, p.harga, p.gambar_produk
        FROM keranjang k
        JOIN Kopi p ON k.id_kopi = p.id_kopi
        WHERE k.id_user = ?
    ");
    $query->execute([$idUser]);
    $keranjang = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Terjadi kesalahan saat mengambil data: " . $e->getMessage());
}

if (!$keranjang) {
    $keranjang = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Keranjang Belanja</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <?php include('navbar.php'); ?>
  <div class="container my-5">
    <h2 class="text-center mb-4">Keranjang Belanja</h2>
    <div class="row">
      <?php if (!empty($keranjang)): ?>
        <?php foreach ($keranjang as $item): ?>
          <div class="col-md-4">
            <div class="card">
              <img src="../image/<?php echo htmlspecialchars($item['gambar_produk']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($item['nama_kopi']); ?>">
              <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($item['nama_kopi']); ?></h5>
                <p class="card-text">Harga: IDR <?php echo number_format($item['harga'], 0, ',', '.'); ?></p>
                <p>Jumlah: <?php echo $item['kuantitas']; ?> - Gula: <?php echo htmlspecialchars($item['gula']); ?></p>
                <button class="btn btn-warning edit-item" data-id="<?php echo $item['id_keranjang']; ?>" data-kuantitas="<?php echo $item['kuantitas']; ?>" data-gula="<?php echo htmlspecialchars($item['gula']); ?>">Edit</button>
                <button class="btn btn-danger delete-item" data-id="<?php echo $item['id_keranjang']; ?>">Hapus</button>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p class="text-center">Keranjang Anda kosong.</p>
      <?php endif; ?>
    </div>

    <div class="text-center">
      <a href="checkout.php" class="btn btn-success">Selesaikan Pesanan</a>
    </div>
  </div>
  <?php include('footer.php'); ?>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Script yang kamu kasih
  document.querySelectorAll('.delete-item').forEach(button => {
    button.addEventListener('click', () => {
      const id = button.getAttribute('data-id');
      
      fetch('delete_item.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ id_keranjang: id })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert('Produk berhasil dihapus.');
          location.reload(); // Reload halaman untuk memperbarui keranjang
        } else {
          alert('Terjadi kesalahan: ' + data.message);
        }
      });
    });
  });

  document.querySelectorAll('.edit-item').forEach(button => {
    button.addEventListener('click', () => {
      const id = button.getAttribute('data-id');
      const kuantitas = button.getAttribute('data-kuantitas');
      const gula = button.getAttribute('data-gula');

      // Arahkan ke halaman edit atau tampilkan modal untuk edit
      window.location.href = `edit_item.php?id=${id}&kuantitas=${kuantitas}&gula=${gula}`;
    });
  });
</script>
</body>
</html>
