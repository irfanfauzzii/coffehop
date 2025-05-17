<?php
require_once('../config.php');
// Ambil ulasan dari database
try {
    $query = $db->query("
    SELECT u.id_ulasan, u.rating, u.komentar, u.tanggal_ulas, k.nama_kopi, us.nama
    FROM ulasan u
    JOIN user us ON u.id_user = us.id_user
    JOIN kopi k ON u.id_kopi = k.id_kopi
    ORDER BY u.tanggal_ulas DESC
");

    if ($query) {
        $ulasan = $query->fetchAll(PDO::FETCH_ASSOC);
    } else {
        echo "Query gagal dieksekusi.";
    }
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Ulasan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
        /* CSS */
        :root {
            --brown: #6f4f1f;
            --dark: #333;
            --light: #fff;
            --gray: #999;
            --bg-color: #f8f6f2;
            --highlight: #d1a378;
        }

        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background: var(--bg-color);
            color: var(--dark);
        }

        .container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 2rem;
            background: var(--light);
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            padding-top: 100px; /* Menambahkan jarak atas untuk menghindari tertutup navbar */
        }

        h2 {
            text-align: center;
            font-size: 3rem; /* Memperbesar ukuran teks judul */
            color: var(--brown);
            margin-bottom: 2rem;
            font-weight: bold;
        }

        .ulasan-card {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .ulasan-item {
            padding: 1.5rem;
            border-radius: 10px;
            background: #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out;
        }

        .ulasan-item:hover {
            transform: translateY(-5px);
        }

        .ulasan-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .kopi-name {
            font-size: 1.5rem;
            color: var(--brown);
            font-weight: bold;
        }

        .stars {
            color: #d1a378; /* Warna bintang aktif */
            font-size: 1.5rem;
        }

        .stars .fa-star {
            color: #ddd; /* Warna bintang kosong */
        }

        .stars .fa-star.active {
            color: #ffbb33; /* Warna bintang yang diaktifkan */
        }

        .komentar {
            font-size: 1.1rem; /* Memperbesar ukuran teks komentar */
            color: var(--dark);
            margin-top: 1rem;
            line-height: 1.6;
        }

        .user-name {
            margin-top: 1rem;
            font-size: 1rem; /* Memperbesar ukuran teks nama pengguna */
            color: var(--gray);
            font-style: italic;
        }

    </style>
</head>
<body>

<!-- Navbar -->
<?php include('navadmin.php'); ?>

<div class="container">
    <h2>Hasil Ulasan Pengguna</h2>

    <div class="ulasan-card">
        <?php if (!empty($ulasan)): ?>
            <?php foreach ($ulasan as $item): ?>
                <div class="ulasan-item">
                    <div class="ulasan-header">
                        <div class="kopi-name"><?php echo htmlspecialchars($item['nama_kopi']); ?></div>
                        <div class="stars">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="fa fa-star <?php echo $i <= $item['rating'] ? 'active' : ''; ?>"></i>
                            <?php endfor; ?>
                        </div>
                    </div>
                    <div class="komentar"><?php echo htmlspecialchars($item['komentar']); ?></div>
                    <div class="user-name">- <?php echo htmlspecialchars($item['nama']); ?></div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Belum ada ulasan untuk saat ini. Jadilah yang pertama memberikan ulasan!</p>
        <?php endif; ?>
    </div>
</div>

<!-- Footer -->
<?php include('footer.php'); ?>

</body>
</html>
