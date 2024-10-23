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

    // Ambil hashed password dari database berdasarkan $name
    $sql = "SELECT password FROM users WHERE name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $name); // Menggunakan prepared statement untuk mencegah SQL injection
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashed_password = $row['password'];

        if (password_verify($password, $hashed_password)) {
            $_SESSION['user'] = $name; // Simpan nama pengguna di session
            header("Location: index.php"); // Arahkan ke index.php setelah login berhasil
            exit(); // Pastikan untuk menghentikan eksekusi script setelah pengalihan
        } else {
            echo "Nama pengguna atau kata sandi salah.";
        }
    } else {
        echo "Nama pengguna atau kata sandi salah.";
    }
}

$conn->close(); // Tutup koneksi
?>

<!-- Tambahkan link ke file CSS l&r.css -->
<link rel="stylesheet" type="text/css" href="l&r.css">

<form method="POST" action="">
    <label for="name">Nama Lengkap:</label>
    <input type="text" id="name" name="name" required><br>
    <label for="password">Kata Sandi:</label>
    <input type="password" id="password" name="password" required><br>
    <button type="submit">Login</button>
</form>

<!-- Tambahkan tautan ke halaman register -->
<a href="register.php">Daftar di sini</a>
