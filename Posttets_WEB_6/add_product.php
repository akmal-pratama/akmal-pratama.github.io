<?php
session_start();
require 'db.php'; // Tambahkan koneksi database

$alertMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['action'] == 'add') {
    $name = isset($_POST['name']) ? filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING) : null;
    $price = isset($_POST['price']) ? filter_var(trim($_POST['price']), FILTER_VALIDATE_INT) : null;
    $stock = isset($_POST['stock']) ? filter_var(trim($_POST['stock']), FILTER_VALIDATE_INT) : null;
    $maxFileSize = 2 * 1024 * 1024; // 2MB

    if ($name && $price && $stock) {
        if (isset($_FILES['image']) && $_FILES['image']['size'] <= $maxFileSize) {
            $fileTmpPath = $_FILES['image']['tmp_name'];
            $fileName = date('Y-m-d H.i.s') . '.' . pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $uploadFileDir = './uploads/';
            if (!is_dir($uploadFileDir)) {
                mkdir($uploadFileDir, 0777, true); // Buat direktori jika belum ada
            }
            $dest_path = $uploadFileDir . $fileName;

            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $stmt = $pdo->prepare("INSERT INTO products (name, price, stock, image) VALUES (?, ?, ?, ?)");
                $stmt->execute([$name, $price, $stock, $fileName]);
                $alertMessage = 'Produk berhasil ditambahkan!';
            }
        }
    }
    header("Location: crud.php?message=" . urlencode($alertMessage));
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk Baru</title>
    <link rel="stylesheet" href="crud-style.css">
</head>
<body>
    <h2>Tambah Produk Baru</h2>
    <form method="POST" action="add_product.php" enctype="multipart/form-data">
        <input type="hidden" name="action" value="add">
        <label for="name">Nama Produk:</label>
        <input type="text" id="name" name="name" required><br>

        <label for="price">Harga:</label>
        <input type="number" id="price" name="price" required><br>

        <label for="stock">Stok:</label>
        <input type="number" id="stock" name="stock" required><br>

        <label for="image">Gambar:</label>
        <input type="file" id="image" name="image" accept="image/*" required><br>

        <button type="submit">Tambah</button>
    </form>
    <a href="crud.php">Kembali ke Daftar Produk</a>
</body>
</html>