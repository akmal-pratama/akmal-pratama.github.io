<?php
session_start();
session_destroy(); // Hapus session
header("Location: index.php"); // Kembali ke halaman utama
exit();
?>