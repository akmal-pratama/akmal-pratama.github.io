<?php
session_start();

// Tambahkan koneksi ke database
$servername = "localhost"; // Ganti dengan nama server Anda
$username = "root"; // Username default untuk XAMPP
$password = ""; // Password default untuk XAMPP (kosong)
$dbname = "toko_perabotan"; // Nama database Anda

// Buat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING);
    $password = filter_var(trim($_POST['password']), FILTER_SANITIZE_STRING);
    
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Simpan data pengguna ke database
    $sql = "INSERT INTO users (name, password) VALUES ('$name', '$hashed_password')";
    if ($conn->query($sql) === TRUE) {
        echo "Registrasi berhasil!";
        header("Location: login.php"); // Arahkan ke login.php setelah registrasi
        exit(); // Pastikan untuk menghentikan eksekusi skrip setelah pengalihan
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close(); // Tutup koneksi
?>

<link rel="stylesheet" type="text/css" href="l&r.css">

<form method="POST" action="">
    <label for="name">Nama Lengkap:</label>
    <input type="text" id="name" name="name" required><br>
    <label for="password">Kata Sandi:</label>
    <input type="password" id="password" name="password" required><br>
    <button type="submit">Daftar</button>
</form>
