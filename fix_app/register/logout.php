<?php
session_start();

// Menghapus semua data sesi
session_unset();
session_destroy();

// Mengarahkan pengguna ke halaman login setelah logout
header('Location: login.php');
exit();
?>
