<?php
session_start();  // Panggil session_start() hanya sekali di bagian atas halaman

// Cek apakah pengguna sudah login dan memiliki peran 'customer'
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer') {
    header('Location: login.php');
    exit();
}

echo "Selamat datang di halaman utama!";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>kedai kopi</title>
    <!-- Font awesome cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="boxicons-2.1.4/css/boxicons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.5.0/remixicon.css">
    <!-- Custom CSS file -->
    <link rel="stylesheet" href="styles.css">
    <style>
    :root {
        --brown: #6f4f1f; /* Coklat */
        --dark: #333; /* Hitam */
        --light: #fff;
        --gray: #999;
    }

    .navbar a.active {
        color: var(--brown); /* Warna coklat untuk link yang aktif */
        font-weight: bold;
    }

    section {
        margin-bottom: 0; 
        padding-bottom: 0;
    }

    .home {
        display: flex;
        align-items: center;
        min-height: 100vh;
        background: url('../image/bg2.jpg') no-repeat center center;
        background-size: cover;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .footer {
        background: var(--light);
        padding: 4rem 9%;
        color: var(--gray);
        text-align: center;
        margin-top: 0;
        padding-top: 0;
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

    html {
        font-size: 62.5%;
        scroll-behavior: smooth;
        scroll-padding-top: 6rem;
        overflow-y: auto;
    }

    section {
        padding: 2rem 9%;
    }

    .heading {
        text-align: center;
        font-size: 3.5rem;
        color: var(--dark);
        padding: 2rem; 
        margin-bottom: 0;
        background: rgba(255, 51, 153, .05);
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
        background: var(--brown);
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

    .home .content {
        max-width: 50rem;
        color: var(--light);
    }

    .home .content h3 {
        font-size: 4rem; /* Menurunkan ukuran font agar lebih proporsional */
        color: var(--brown); /* Menggunakan coklat untuk teks */
    }

    .home .content span {
        font-size: 2.5rem; /* Menurunkan ukuran font untuk span */
        color: var(--dark); /* Menggunakan hitam untuk teks */
        padding: 1rem 0;
        line-height: 1.5;
    }

    .home .content p {
        font-size: 1.4rem; /* Menurunkan ukuran font agar lebih nyaman dibaca */
        color: var(--gray); /* Warna abu-abu untuk deskripsi */
        padding: 1rem 0;
        line-height: 1.5;
    }

    /* Responsif untuk ukuran layar kecil */
    @media (max-width: 768px) {
        .home .content h3 {
            font-size: 3rem;
        }
        
        .home .content span {
            font-size: 2rem;
        }
        
        .home .content p {
            font-size: 1.2rem;
        }
    }
    </style>

</head>
<body>
<?php include('navbar.php'); ?>
    </header>
    <!-- Header Section Ends -->

    <!-- Home Section Starts -->
    <section class="home" id="home">
        <div class="content">
            <h3>Nikmati Kopi Terbaik Setiap Hari</h3>
            <span>Aroma Istimewa, Rasa Tak Tertandingi</span>
            <p>Jelajahi Berbagai Jenis Kopi Pilihan Dari Seluruh Nusantara. Temukan Biji Kopi Arabika Terbaik, Kopi Spesial Racikan Espresso Kami, Dan Nikmati Sensasi Secangkir Kopi Yang Dibuat Dengan Penuh Cinta. Pesan Sekarang Dan Rasakan Perbedaan!</p>
            <a href="products.php" class="btn">Belanja Sekarang</a>
        </div>
    </section>
    <script>
    // Ambil semua elemen navbar
    const navbarLinks = document.querySelectorAll('.navbar a');

    // Tambahkan event listener pada setiap link
    navbarLinks.forEach(link => {
        link.addEventListener('click', function() {
            // Hapus kelas active dari semua link
            navbarLinks.forEach(nav => nav.classList.remove('active'));
            
            // Tambahkan kelas active ke link yang diklik
            link.classList.add('active');
        });
    });
    </script>
    <!-- Home Section Ends -->

    <?php include('footer.php'); ?>
    <!-- Footer Section Ends -->

</body>
</html>
