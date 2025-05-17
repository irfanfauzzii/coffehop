<?php
// session_start();

// // Cek apakah pengguna sudah login dan memiliki role 'customer'
// if ($_SESSION['role'] != 'customer') {
//     header('Location: login.php');
//     exit();
    require_once('../config.php');
// }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>tentang kami</title>
    <!-- Font awesome cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="boxicons-2.1.4/css/boxicons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.5.0/remixicon.css">
    <!-- Custom CSS file -->
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Styling Umum */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 4rem 2rem;
        }

        .contact {
            padding: 8rem 7% 2rem;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .about h2,
        .products h2,
        .contact h2 {
            text-align: center;
            font-size: 3.5rem; /* Ukuran font diperbesar */
            margin-bottom: 3rem;
            color: #333;
        }

        .about h2 span,
        .products h2 span,
        .contact h2 span {
            color: #8B4513;  /* Coklat untuk header */
        }

        /* Menambahkan Flexbox untuk pengaturan gambar dan konten */
.about .row {
    display: flex;
    align-items: center; /* Memastikan gambar dan konten sejajar secara vertikal */
    justify-content: center; /* Memusatkan elemen secara horizontal */
    gap: 2rem; /* Memberikan jarak antara gambar dan konten */
}

/* Gambar */
.about .about-img {
    flex: 1; /* Memberi gambar ruang yang cukup */
    max-width: 35%; /* Membatasi lebar gambar */
    text-align: center; /* Memastikan gambar terpusat dalam div */
}

.about .about-img img {
    width: 100%;  /* Membuat gambar responsif */
    height: auto;  /* Memastikan gambar tidak terdistorsi */
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    object-fit: cover; /* Memastikan gambar tidak terdistorsi */
}

/* Konten */
.about .content {
    flex: 1; /* Memberi konten ruang yang cukup */
    max-width: 50%; /* Membatasi lebar konten */
    padding: 2rem;
    text-align: left;
}

.about .content h3 {
    font-size: 3rem;
    color: #8B4513;
    margin-bottom: 1.5rem;
    font-weight: bold;
}

.about .content p {
    font-size: 1.6rem;
    color: #4F4F4F;
    line-height: 1.8;
    margin-bottom: 1.5rem;
}

/* Responsif pada layar kecil */
@media (max-width: 768px) {
    .about .row {
        flex-direction: column; /* Menumpuk gambar dan konten secara vertikal */
        align-items: center; /* Memusatkan gambar dan konten secara horizontal */
    }

    .about .about-img,
    .about .content {
        max-width: 90%; /* Memberi gambar dan konten ruang lebih pada layar kecil */
        text-align: center; /* Memusatkan konten pada layar kecil */
    }
}


        /* Styling untuk Navbar */
        .navbar a.active {
            color: #FF5722;
        }

        /* Styling untuk Footer */
        footer {
            background-color: #333;
            color: #fff;
            padding: 2rem 0;
            text-align: center;
            margin-top: 3rem; /* Jarak footer dari konten */
        }
    </style>
</head>
<body>
<?php include('navbar.php'); ?>

<section id="about" class="about">
    <h2><span>Tentang</span> Kami</h2>

    <div class="row">
        <div class="about-img">
            <div class="container">
                <img src="../image/about.jpg" alt="Tentang Kami">
            </div>
        </div>

        <div class="content">
            <h3>Kenapa Memilih Kopi Kami</h3>
            <p>"Kenapa memilih kopi kami? Karena kami mengutamakan kualitas biji kopi terbaik, proses pengolahan yang sempurna, rasa yang khas, dan pelayanan ramah untuk memastikan pengalaman ngopi Anda selalu istimewa."</p>
            <p>"Secangkir kopi kami adalah perpaduan sempurna rasa autentik, aroma memikat, dan kualitas terbaik, menjadikan setiap tegukan luar biasa."</p>
        </div>
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

<?php include('footer.php'); ?>

</body>
</html>
