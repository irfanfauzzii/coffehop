<?php
session_start(); // Pastikan session dimulai

require_once('../config.php');

// Pastikan sesi pengguna sudah ada
if (!isset($_SESSION['user_id'])) {
    $response['status'] = 'error';
    $response['message'] = 'Pengguna belum login!';
    echo json_encode($response);
    exit();
}

$response = []; // Variabel untuk mengirimkan respons AJAX

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $komentar = $_POST['komentar'];
    $rating = $_POST['rating'];
    $id_user = $_SESSION['user_id']; // ID pengguna yang sedang login
    $id_kopi = $_POST['id_kopi']; // ID kopi yang diulas

    // Validasi input: pastikan rating dan komentar tidak kosong
    if (empty($rating) || empty($komentar)) {
        $response['status'] = 'error';
        $response['message'] = 'Rating dan komentar harus diisi!';
        echo json_encode($response);
        exit();
    }

    // Query untuk memasukkan ulasan ke database
    $query = $db->prepare("INSERT INTO ulasan (id_user, id_kopi, rating, komentar) VALUES (:id_user, :id_kopi, :rating, :komentar)");
    $query->bindParam(':id_user', $id_user);
    $query->bindParam(':id_kopi', $id_kopi);
    $query->bindParam(':rating', $rating);
    $query->bindParam(':komentar', $komentar);

    if ($query->execute()) {
        $response['status'] = 'success';
        $response['message'] = 'Ulasan Anda berhasil dikirim!';
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Gagal mengirim ulasan, coba lagi. Error: ' . implode(', ', $query->errorInfo());
    }

    // Kirim respons JSON untuk AJAX
    echo json_encode($response);
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ulasan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>
<body>
<?php include('navbar.php'); ?>

<!-- Container Utama -->
<div class="form-wrapper">
    <!-- Bagian Form -->
    <div class="form-section">
        <h2>✨ Berikan Ulasan Anda ✨</h2>
        <p class="subtitle">Kami sangat menghargai pendapat Anda untuk meningkatkan kualitas kopi kami!</p>
        <div id="alert-container"></div>

        <form id="ulasan-form">
            <div class="mb-3">
                <label for="id_kopi" class="form-label">☕ Pilih Kopi Favorit Anda</label>
                <select name="id_kopi" id="id_kopi" class="form-control" required>
                    <?php
                    // Ambil daftar kopi dari database
                    $kopiQuery = $db->query("SELECT id_kopi, nama_kopi FROM kopi");
                    while ($kopi = $kopiQuery->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='{$kopi['id_kopi']}'>{$kopi['nama_kopi']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">⭐ Beri Rating</label>
                <div id="rating-stars" class="stars">
                    <i class="fa fa-star" data-value="1"></i>
                    <i class="fa fa-star" data-value="2"></i>
                    <i class="fa fa-star" data-value="3"></i>
                    <i class="fa fa-star" data-value="4"></i>
                    <i class="fa fa-star" data-value="5"></i>
                </div>
                <input type="hidden" name="rating" id="rating" required>
            </div>
            <div class="mb-3">
                <label for="komentar" class="form-label">💬 Tambahkan Komentar Anda</label>
                <textarea class="form-control" id="komentar" name="komentar" rows="4" required></textarea>
            </div>
            <button type="submit" class="btn">✨ Kirim Ulasan ✨</button>
        </form>
    </div>

    <!-- Bagian Gambar -->
    <div class="image-section">
        <img src="../image/ulasan.jpg" alt="Coffee Image">
    </div>
</div>

<?php include('footer.php'); ?>

<!-- CSS -->
<style>
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
    padding-top: 0rem; /* Menambahkan padding atas untuk memberi ruang bagi navbar */
}

.form-wrapper {
    display: flex;
    justify-content: space-between;
    align-items: center;
    max-width: 1200px;
    margin: 90px auto 10px auto; /* Menambahkan margin atas lebih besar */
    padding: 2rem;
    background: var(--light);
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    gap: 2rem;
}

h2 {
    text-align: center;
    font-size: 2.5rem;
    color: var(--brown);
    margin-bottom: 1rem;
    text-transform: uppercase;
    font-weight: bold;
}

.subtitle {
    text-align: center;
    font-size: 1.2rem;
    color: var(--highlight);
    margin-bottom: 2rem;
    font-style: italic;
}

.form-section {
    flex: 1;
    padding: 1rem 2rem;
}

.image-section {
    flex: 1;
    display: flex;
    justify-content: center;
    align-items: center;
}

.image-section img {
    max-width: 100%;
    height: auto;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.form-label {
    font-size: 1.2rem;
    font-weight: bold;
    color: var(--dark);
}

.stars i {
    font-size: 2rem;
    color: var(--gray);
    cursor: pointer;
    margin: 0 5px;
    transition: color 0.2s ease-in-out;
}

.stars i.active,
.stars i:hover {
    color: var(--brown);
}

.form-control {
    width: 100%;
    padding: 15px;
    font-size: 1rem;
    margin-bottom: 20px;
    border: 1px solid #ccc;
    border-radius: 10px;
    box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
    transition: border-color 0.3s ease-in-out;
}

.form-control:focus {
    border-color: var(--brown);
    outline: none;
}

.btn {
    background-color: var(--brown);
    color: var(--light);
    padding: 1rem;
    font-size: 1.2rem;
    text-align: center;
    border: none;
    border-radius: 50px;
    cursor: pointer;
    width: 100%;
    font-weight: bold;
    transition: background-color 0.3s ease-in-out;
}

.btn:hover {
    background-color: #5d3a1f;
}
</style>

<!-- JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const stars = document.querySelectorAll('#rating-stars .fa-star');
    const ratingInput = document.querySelector('#rating');
    const alertContainer = document.querySelector('#alert-container');
    const form = document.querySelector('#ulasan-form');

    // Pilih rating
    stars.forEach((star) => {
        star.addEventListener('click', function () {
            const value = this.getAttribute('data-value');
            ratingInput.value = value;

            // Aktifkan bintang sesuai nilai
            stars.forEach((s) => s.classList.remove('active'));
            for (let i = 0; i < value; i++) {
                stars[i].classList.add('active');
            }
        });
    });

    // Kirim form dengan AJAX
    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(form);
        fetch('ulasan.php', {
            method: 'POST',
            body: formData,
        })
            .then((response) => response.json())
            .then((data) => {
                alertContainer.innerHTML = `<div class="alert ${data.status === 'success' ? 'success' : ''}">
                    ${data.message}
                </div>`;
                alertContainer.querySelector('.alert').style.display = 'block';

                if (data.status === 'success') {
                    form.reset();
                    stars.forEach((star) => star.classList.remove('active'));
                }
            })
            .catch(() => {
                alertContainer.innerHTML = `<div class="alert">Terjadi kesalahan, coba lagi.</div>`;
                alertContainer.querySelector('.alert').style.display = 'block';
            });
    });
});
</script>
</body>
</html>
