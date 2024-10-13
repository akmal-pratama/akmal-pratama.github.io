<?php
session_start();
require 'db.php'; // Tambahkan koneksi database

$alertMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['action'] == 'update') {
    $id = $_POST['id'];
    $name = isset($_POST['name']) ? filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING) : null;
    $price = isset($_POST['price']) ? filter_var(trim($_POST['price']), FILTER_VALIDATE_INT) : null;
    $stock = isset($_POST['stock']) ? filter_var(trim($_POST['stock']), FILTER_VALIDATE_INT) : null;

    if ($id && $name && $price && $stock) {
        $stmt = $pdo->prepare("UPDATE products SET name = ?, price = ?, stock = ? WHERE id = ?");
        $stmt->execute([$name, $price, $stock, $id]);
        $alertMessage = 'Produk berhasil diperbarui!';
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
    <title>Update Produk</title>
    <link rel="stylesheet" href="crud-style.css">
</head>
<body>
    <h2>Update Produk</h2>
    <form id="update-form" method="POST" action="update_product.php">
        <input type="hidden" name="action" value="update">
        <input type="hidden" id="update-id" name="id">
        <label for="update-name">Nama Produk:</label>
        <input type="text" id="update-name" name="name" required><br>

        <label for="update-price">Harga:</label>
        <input type="number" id="update-price" name="price" required><br>

        <label for="update-stock">Stok:</label>
        <input type="number" id="update-stock" name="stock" required><br>

        <button type="submit">Update</button>
    </form>
    <a href="crud.php">Kembali ke Daftar Produk</a>
</body>
</html>