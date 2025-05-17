<?php
// session_start();
require_once('../config.php');

// Jika pengguna belum login, arahkan ke halaman login
// if (!isset($_SESSION['user_id'])) {
//     header('Location: index.php');
//     exit();
// }

try {
    // Ambil data produk kopi dari database
    $query = $db->prepare("SELECT * FROM Kopi");
$query->execute();
$kopi = $query->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Terjadi kesalahan saat mengambil data: " . $e->getMessage());
}

// Pastikan $kopi tidak null
if (!$kopi) {
    $kopi = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Produk Kopi</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="boxicons-2.1.4/css/boxicons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.5.0/remixicon.css">
    <!-- Feather icon -->
    <script src="https://unpkg.com/feather-icons"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
/* Menghilangkan garis bawah pada navbar */
header .navbar a {
    font-size: 2rem;
    padding: 0 1.5rem;
    color: #666;
    text-decoration: none; /* Menambahkan ini untuk menghilangkan garis bawah */
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

.btn {
    display: inline-block;
    margin-top: 1rem;
    border-radius: 5rem;
    background: var(--dark);
    color: var(--light);
    padding: .9rem 3.5rem;
    cursor: pointer;
    font-size: 1.7rem;
    text-align: center;
}

.btn:hover {
    background: var(--pink);
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

/* Produk section */
.product-card {
    border: 1px solid var(--gray);
    border-radius: 10px;
    overflow: hidden;
    background-color: #f8f8f8;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
}

.product-card img {
    width: 100%;
    height: auto;
    object-fit: cover;
    max-height: 250px; /* Menjaga ukuran gambar agar tidak terlalu besar */
}

.product-card:hover {
    transform: translateY(-5px);
}

.product-card .content {
    padding: 1.5rem;
    text-align: center;
}

.product-card h3 {
    font-size: 2rem;
    color: var(--dark);
    margin-bottom: 1rem;
}

.product-card p {
    color: var(--gray);
    margin-bottom: 1.5rem;
}

.product-card .price {
    font-size: 1.8rem;
    color: var(--brown);
    font-weight: bold;
}

.product-card .btn {
    margin-top: 1rem;
    font-size: 1.6rem;
    padding: .8rem 3rem;
    background-color: var(--brown);
    color: var(--light);
}

.product-card .btn:hover {
    background-color: var(--dark);
}

/* Modal */
/* Modal */
.modal-body p {
    font-size: 1.6rem;
    color: var(--dark);
}

.modal-header {
    background-color: var(--brown);
    color: var(--light);
    font-size: 1.8rem;
}

.modal-footer .btn {
    font-size: 1.5rem;
    padding: .7rem 2.5rem;
    background-color: var(--brown);
    color: var(--light);
}

.modal-footer .btn:hover {
    background-color: var(--dark);
}

/* Modal Button */
.modal-footer .btn:disabled {
    background-color: #ccc;
    cursor: not-allowed;
}

/* Modal content */
.modal-content {
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}
/* CSS untuk memberikan efek border merah pada input yang tidak valid */
.is-invalid {
  border: 2px solid red !important;
}

.is-invalid:focus {
  outline: none;
  box-shadow: 0 0 5px rgba(255, 0, 0, 0.5);
}

/* Mengatur gaya pesan kesalahan */
.invalid-feedback {
  color: red;
  font-size: 0.9rem;
  display: none;
}

/* Menampilkan pesan kesalahan ketika input tidak valid */
input:invalid ~ .invalid-feedback {
  display: block;
}


/* Footer */
footer {
    text-align: center;
    padding: 2rem;
    background-color: var(--dark);
    color: var(--light);
    font-size: 1.4rem;
    margin-top: 3rem;
}

  </style>
</head>
<body>
<!-- Header Section Starts -->
<header>
<?php include('navbar.php'); ?>
</header>
<!-- Header Section Ends -->

<!-- Produk -->
<div class="container my-5">
  <h2 class="text-center mb-4" style="color: #6f4e37;">Produk Kopi Kami</h2>
  <div class="row">
    <?php if (!empty($kopi)): ?>
      <?php foreach ($kopi as $produk): ?>
        <div class="col-md-4">
          <div class="product-card">
            <img src="../image/<?php echo htmlspecialchars($produk['gambar_produk']); ?>" alt="<?php echo htmlspecialchars($produk['nama_kopi']); ?>">
            <div class="content">
              <h3><?php echo htmlspecialchars($produk['nama_kopi']); ?></h3>
              <p>Jenis: <?php echo htmlspecialchars($produk['jenis_kopi']); ?></p>
              <div class="price">IDR <?php echo number_format($produk['harga'], 0, ',', '.'); ?></div>
              <button class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#modal-<?php echo $produk['id_kopi']; ?>">
                <i class="fas fa-info-circle"></i> Detail
              </button>
            </div>
          </div>
        </div>

<!-- Modal -->
<div class="modal fade" id="modal-<?php echo $produk['id_kopi']; ?>" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" style="font-size: 1.8rem;"><?php echo htmlspecialchars($produk['nama_kopi']); ?></h5>
      </div>
      <div class="modal-body">
        <p><strong>Jenis:</strong> <?php echo htmlspecialchars($produk['jenis_kopi']); ?></p>
        <p><strong>Harga:</strong> IDR <?php echo number_format($produk['harga'], 0, ',', '.'); ?></p>
        <p><strong>Stok Tersedia:</strong> <?php echo htmlspecialchars($produk['stok']); ?> unit</p>
        <p><strong>Deskripsi:</strong> <?php echo htmlspecialchars($produk['deskripsi']); ?></p>

        <!-- Input untuk jumlah -->
        <div class="mb-3">
          <label for="quantity-<?php echo $produk['id_kopi']; ?>" class="form-label">
            Jumlah: <span style="color: red;">*</span>
          </label>
          <input 
            type="number" 
            id="quantity-<?php echo $produk['id_kopi']; ?>" 
            class="form-control quantity-input" 
            value="1" 
            min="1" 
            max="<?php echo htmlspecialchars($produk['stok']); ?>"
            required 
            style="border-radius: 10px; padding: 0.8rem;">
            <div class="invalid-feedback">Jumlah tidak bisa kurang dari 1 atau melebihi stok yang tersedia.</div>
        </div>
        
        <!-- Dropdown gula -->
        <div class="mb-3">
          <label for="sugar-<?php echo $produk['id_kopi']; ?>" class="form-label">
            Gula: <span style="color: red;">*</span>
          </label>
          <select 
            id="sugar-<?php echo $produk['id_kopi']; ?>" 
            class="form-select" 
            required 
            style="border-radius: 10px; padding: 0.8rem;">
            <option value="">Pilih Gula</option>
            <option value="tanpa">Tanpa Gula</option>
            <option value="sedikit">Sedikit</option>
            <option value="normal">Normal</option>
            <option value="banyak">Banyak</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button 
          type="button" 
          class="btn btn-success add-to-cart" 
          data-id="<?php echo $produk['id_kopi']; ?>" 
          data-name="<?php echo htmlspecialchars($produk['nama_kopi']); ?>" 
          data-price="<?php echo $produk['harga']; ?>"
          data-stock="<?php echo $produk['stok']; ?>"
          data-bs-dismiss="modal"
          style="border-radius: 10px; padding: 0.8rem 2rem;">
          <i class="fas fa-shopping-cart"></i> Tambah ke Keranjang
        </button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 10px; padding: 0.8rem 2rem;">Tutup</button>
      </div>
    </div>
  </div>
</div>


      <?php endforeach; ?>
    <?php else: ?>
      <p class="text-center">Produk kopi belum tersedia.</p>
    <?php endif; ?>
  </div>
</div>

<?php include('footer.php'); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', () => {
  const cartButtons = document.querySelectorAll('.add-to-cart');

  cartButtons.forEach(button => {
    button.addEventListener('click', () => {
      const productId = button.getAttribute('data-id');
      const productName = button.getAttribute('data-name');
      const productPrice = button.getAttribute('data-price');
      const productStock = parseInt(button.getAttribute('data-stock')); // Pastikan stok dalam bentuk angka
      const quantityInput = document.getElementById(`quantity-${productId}`);
      const quantity = parseInt(quantityInput.value); // Ambil jumlah kuantitas yang diinput oleh pengguna
      const sugar = document.getElementById(`sugar-${productId}`).value;

      // Validasi jumlah agar tidak bisa lebih dari stok
      if (quantity <= 0 || quantity > productStock) {
        quantityInput.classList.add('is-invalid');
        quantityInput.setCustomValidity(`Jumlah tidak bisa kurang dari 1 atau lebih dari stok (${productStock})`);
        alert(`Jumlah tidak bisa 0 atau kurang, atau melebihi stok yang tersedia (${productStock}).`);
        quantityInput.focus();
        return; // Hentikan eksekusi jika validasi gagal
      } else {
        quantityInput.classList.remove('is-invalid');
        quantityInput.setCustomValidity(""); // Reset validasi jika input valid
      }

      // Kirim data ke server menggunakan fetch
      fetch('add_to_cart.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          id_kopi: productId,
          nama_kopi: productName,
          harga: productPrice,
          kuantitas: quantity,
          gula: sugar,
        }),
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert('Produk berhasil ditambahkan ke keranjang!');
        } else {
          alert('Terjadi kesalahan: ' + data.message);
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan, silakan coba lagi.');
      });
    });
  });
});


</script>
</body>
</html>
