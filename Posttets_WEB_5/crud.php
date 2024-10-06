<?php
session_start();
require 'db.php'; // Tambahkan koneksi database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];

    // Periksa apakah kunci-kunci ini ada sebelum mengaksesnya
    $name = isset($_POST['name']) ? filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING) : null;
    $price = isset($_POST['price']) ? filter_var(trim($_POST['price']), FILTER_VALIDATE_INT) : null;
    $stock = isset($_POST['stock']) ? filter_var(trim($_POST['stock']), FILTER_VALIDATE_INT) : null;

    if ($action == 'add' && $name && $price && $stock) {
        $stmt = $pdo->prepare("INSERT INTO products (name, price, stock, image) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $price, $stock, 'default.jpg']);
    } elseif ($action == 'delete' && isset($_POST['id'])) {
        $id = $_POST['id'];
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$id]);
    } elseif ($action == 'update' && isset($_POST['id']) && $name && $price && $stock) {
        $id = $_POST['id'];
        $stmt = $pdo->prepare("UPDATE products SET name = ?, price = ?, stock = ? WHERE id = ?");
        $stmt->execute([$name, $price, $stock, $id]);
    }
}

$products = $pdo->query("SELECT * FROM products")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Produk</title>
    <link rel="stylesheet" href="crud-style.css"> <!-- Link ke file CSS baru -->
</head>
<body>
    <h1>Daftar Produk</h1>
    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?= htmlspecialchars($product['name']) ?></td>
                    <td>Rp <?= number_format($product['price'], 0, ',', '.') ?></td>
                    <td><?= htmlspecialchars($product['stock']) ?></td>
                    <td>
                        <form method="POST" action="crud.php" style="display:inline;">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?= $product['id'] ?>">
                            <button type="submit">Hapus</button>
                        </form>
                        <button onclick="editProduct(<?= $product['id'] ?>, '<?= htmlspecialchars($product['name']) ?>', <?= $product['price'] ?>, <?= $product['stock'] ?>)">Edit</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h2>Tambah Produk Baru</h2>
    <form method="POST" action="crud.php">
        <input type="hidden" name="action" value="add">
        <label for="name">Nama Produk:</label>
        <input type="text" id="name" name="name" required><br>

        <label for="price">Harga:</label>
        <input type="number" id="price" name="price" required><br>

        <label for="stock">Stok:</label>
        <input type="number" id="stock" name="stock" required><br>

        <button type="submit">Tambah</button>
    </form>

    <h2>Update Produk</h2>
    <form id="update-form" method="POST" action="crud.php" style="display:none;">
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

    <a href="index.php">Kembali ke Beranda</a>

    <script>
        function editProduct(id, name, price, stock) {
            document.getElementById('update-id').value = id;
            document.getElementById('update-name').value = name;
            document.getElementById('update-price').value = price;
            document.getElementById('update-stock').value = stock;
            document.getElementById('update-form').style.display = 'block';
        }
    </script>
</body>
</html>
