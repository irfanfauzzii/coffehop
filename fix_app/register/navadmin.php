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

  </style>
</head>
<body>
<!-- Header Section Starts -->
<header>
    <input type="checkbox" id="toggler">
    <label for="toggler" class="fas fa-bars"></label>

    <a href="#" class="logo">irffan<span>coffe</span></a>

    <nav class="navbar">
    <a href="../admin/daftar_transaksi.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'about.php') ? 'active' : ''; ?>">daftar_transaksi</a>
    <a href="../admin/transaksi_terjual.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'home.php') ? 'active' : ''; ?>">Riwayat Penjualan</a>
    <a href="../admin/admin_stok.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'home.php') ? 'active' : ''; ?>">Stok</a>
    <a href="../register/tampilkan_ulasan.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'products.php') ? 'active' : ''; ?>">Ulasan Pengunjung</a>

</nav>


    <div class="icons">
        <!-- <a href="cart.php" class="fas fa-shopping-cart"></a> -->
        <a href="../admin/logout.php" class="fas fa-user"></a>
    </div>
</header>