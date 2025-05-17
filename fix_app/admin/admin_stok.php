<?php
require_once('../config.php');

$response = [];

// Cek jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $id_kopi = $_POST['id_kopi'];
    $stok_perubahan = $_POST['stok_perubahan'];
    $tipe_perubahan = $_POST['tipe_perubahan']; // 'tambah' atau 'kurang'

    // Cek jika stok yang dimasukkan valid
    if (!is_numeric($stok_perubahan) || $stok_perubahan < 1) {
        $response['status'] = 'error';
        $response['message'] = 'Jumlah stok yang dimasukkan tidak valid.';
    } else {
        // Tentukan operator berdasarkan tipe perubahan
        $operator = ($tipe_perubahan == 'tambah') ? '+' : '-';

        // Query untuk menambah atau mengurangi stok
        $query = $db->prepare("UPDATE kopi SET stok = stok $operator :stok_perubahan WHERE id_kopi = :id_kopi");
        $query->bindParam(':id_kopi', $id_kopi);
        $query->bindParam(':stok_perubahan', $stok_perubahan);

        if ($query->execute()) {
            $response['status'] = 'success';
            $response['message'] = 'Stok berhasil diperbarui!';
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Gagal memperbarui stok, coba lagi.';
        }
    }

    // Kirimkan respons JSON
    echo json_encode($response);
    exit();
}

// Ambil daftar kopi dari database untuk tabel
$kopiQuery = $db->query("SELECT id_kopi, nama_kopi, stok FROM kopi");
$kopiList = $kopiQuery->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perbarui Stok Kopi - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
   <style>
    /* Menambahkan warna latar belakang dengan nuansa coklat kopi */
    body {
        background-color: #f4f1eb; /* Warna latar belakang yang lembut */
        color: #4b2e1a; /* Warna teks coklat gelap, memberi kesan kopi */
        font-family: 'Arial', sans-serif;
    }

    /* Header Modal */
    .modal-header {
        background-color: #6f4f1e; /* Warna coklat kopi untuk header */
        color: white;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
    }

    /* Tombol sukses dengan warna hijau kopi */
    .btn-success {
        background-color: #9b8a3d; /* Warna hijau tua kopi */
        border-color: #9b8a3d;
    }
    .btn-success:hover {
        background-color: #7c6f2c; /* Warna saat tombol di-hover */
        border-color: #6f5f2a;
    }

    /* Modal */
    .modal-content {
        border-radius: 10px;
        background-color: #ffffff; /* Warna latar belakang modal tetap putih */
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
    }

    /* Gaya tabel */
    .table {
        font-size: 16px;
        background-color: #fff; /* Latar belakang tabel putih */
        border-radius: 10px;
    }

    /* Styling pada kolom tabel */
    .table th, .table td {
        text-align: center;
        padding: 10px;
        border: 1px solid #ddd;
        font-size: 16px;
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: #f5f5f5; /* Latar belakang tabel bergaris */
    }

    .btn {
        font-size: 14px; /* Ukuran tombol lebih kecil */
    }

    /* Perbarui tampilan modal form */
    .form-label {
        font-weight: bold;
    }

    .btn-primary {
        background-color: #6f4f1e;
        border-color: #6f4f1e;
    }

    .btn-primary:hover {
        background-color: #4b2e1a;
        border-color: #3a1f13;
    }

    /* Gaya footer */
    footer {
        background-color: #6f4f1e;
        color: white;
        padding: 10px 0;
        text-align: center;
    }

    /* Gaya untuk form input */
    .form-control {
        border-radius: 5px;
        border: 1px solid #6f4f1e;
        font-size: 16px;
    }

    .form-control:focus {
        border-color: #4b2e1a;
        box-shadow: none;
    }

    body {
        padding-top: 90px;
    }

    /* Tombol close */
    .btn-close {
        background-color: transparent;
        color: #fff;
    }

    /* Modal - besar input dan teks */
    .modal-body .form-label, .modal-body .form-control {
        font-size: 18px; /* Ukuran teks lebih besar */
    }

    .modal-body .form-control {
        padding: 10px; /* Lebih besar padding di dalam input */
    }

    /* Tambahan margin untuk navbar */
    nav {
        margin-bottom: 0px;
    }
</style>

</head>
<body>

<?php include('../register/navadmin.php'); ?>

<div class="container">
    <h2 class="text-center mb-4">Daftar Kopi</h2>

    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Nama Kopi</th>
                <th>Stok</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($kopiList as $kopi) : ?>
                <tr>
                    <td><?= $kopi['nama_kopi'] ?></td>
                    <td><?= $kopi['stok'] ?></td>
                    <td>
                        <button class="btn btn-primary" data-id="<?= $kopi['id_kopi'] ?>" data-nama="<?= $kopi['nama_kopi'] ?>" data-stok="<?= $kopi['stok'] ?>" data-bs-toggle="modal" data-bs-target="#modalPerbaruiStok">
                            <i class="bi bi-plus-circle"></i> Perbarui Stok
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Modal Form Perbarui Stok -->
<div class="modal fade" id="modalPerbaruiStok" tabindex="-1" aria-labelledby="modalPerbaruiStokLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPerbaruiStokLabel">Perbarui Stok Kopi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="perbarui-stok-form">
                    <div class="mb-3">
                        <label for="nama_kopi_modal" class="form-label">Nama Kopi</label>
                        <input type="text" class="form-control" id="nama_kopi_modal" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="stok_perubahan_modal" class="form-label">Jumlah Stok yang Diubah</label>
                        <input type="number" name="stok_perubahan" id="stok_perubahan_modal" class="form-control" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label for="tipe_perubahan_modal" class="form-label">Tipe Perubahan</label>
                        <select name="tipe_perubahan" id="tipe_perubahan_modal" class="form-control" required>
                            <option value="tambah">Tambah Stok</option>
                            <option value="kurang">Kurangi Stok</option>
                        </select>
                    </div>
                    <input type="hidden" name="id_kopi" id="id_kopi_modal">
                    <button type="submit" class="btn btn-success w-100">Perbarui Stok</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include('../register/footer.php'); ?>

<!-- JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('modalPerbaruiStok');
    const form = document.getElementById('perbarui-stok-form');
    const idKopiModal = document.getElementById('id_kopi_modal');
    const namaKopiModal = document.getElementById('nama_kopi_modal');
    const stokPerubahanModal = document.getElementById('stok_perubahan_modal');
    const tipePerubahanModal = document.getElementById('tipe_perubahan_modal');

    const buttons = document.querySelectorAll('button[data-bs-toggle="modal"]');
    buttons.forEach((button) => {
        button.addEventListener('click', function () {
            const idKopi = this.getAttribute('data-id');
            const namaKopi = this.getAttribute('data-nama');
            const stok = this.getAttribute('data-stok');

            idKopiModal.value = idKopi;
            namaKopiModal.value = namaKopi;
            stokPerubahanModal.setAttribute('min', 1); // Menetapkan minimum stok yang ditambahkan
        });
    });

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(form);
        
        fetch('admin_stok.php', {
            method: 'POST',
            body: formData,
        })
        .then((response) => response.json())
        .then((data) => {
            if (data.status === 'success') {
                alert('Stok berhasil diperbarui!');
                modal.classList.remove('show');
                form.reset();
                location.reload(); // Reload untuk menampilkan stok terbaru
            } else {
                alert(data.message);
            }
        })
        .catch((error) => {
            alert('Terjadi kesalahan, coba lagi.');
        });
    });
});
</script>

</body>
</html>
