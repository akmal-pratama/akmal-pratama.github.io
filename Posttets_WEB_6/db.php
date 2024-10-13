<?php
$host = 'localhost';
$db = 'toko_perabotan';
$user = 'root'; // Default user untuk XAMPP
$pass = ''; // Default password untuk XAMPP adalah kosong

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}
?>