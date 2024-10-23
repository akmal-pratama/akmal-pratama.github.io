<?php
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit;
}

require 'db.php'; // Tambahkan koneksi database

$alertMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];

    // Periksa apakah kunci-kunci ini ada sebelum mengaksesnya
    $name = isset($_POST['name']) ? filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING) : null;
    $price = isset($_POST['price']) ? filter_var(trim($_POST['price']), FILTER_VALIDATE_INT) : null;
    $stock = isset($_POST['stock']) ? filter_var(trim($_POST['stock']), FILTER_VALIDATE_INT) : null;
    $maxFileSize = 2 * 1024 * 1024; // 2MB

    if ($action == 'add' && $name && $price && $stock) {
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
    } elseif ($action == 'delete' && isset($_POST['id'])) {
        $id = $_POST['id'];
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$id]);
    } elseif ($action == 'update' && isset($_POST['id']) && $name && $price && $stock) {
        $id = $_POST['id'];
        $stmt = $pdo->prepare("UPDATE products SET name = ?, price = ?, stock = ? WHERE id = ?");
        $stmt->execute([$name, $price, $stock, $id]);
        $alertMessage = 'Produk berhasil diperbarui!';
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
    <?php if ($alertMessage): ?>
        <script>alert('<?= $alertMessage ?>');</script>
    <?php endif; ?>
    <h1>Daftar Produk</h1>
    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Gambar</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?= htmlspecialchars($product['name']) ?></td>
                    <td>Rp <?= number_format($product['price'], 0, ',', '.') ?></td>
                    <td><?= htmlspecialchars($product['stock']) ?></td>
                    <td><img src="uploads/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" width="50"></td>
                    <td>
                        <form method="POST" action="crud.php" style="display:inline;" id="delete-form-<?= $product['id'] ?>">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?= $product['id'] ?>">
                            <button type="button" onclick="confirmDelete(<?= $product['id'] ?>)">Hapus</button>
                        </form>
                        <a href="update_product.php?id=<?= $product['id'] ?>&name=<?= htmlspecialchars($product['name']) ?>&price=<?= $product['price'] ?>&stock=<?= $product['stock'] ?>">Edit</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="add_product.php">Tambah Produk Baru</a>
    <a href="index.php" class="back-to-home">Kembali ke Beranda</a>

    <script>
        function confirmDelete(productId) {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: "btn btn-success",
                    cancelButton: "btn btn-danger"
                },
                buttonsStyling: false
            });

            swalWithBootstrapButtons.fire({
                title: "Apakah Anda yakin?",
                text: "Anda tidak akan dapat mengembalikan ini!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Ya, hapus!",
                cancelButtonText: "Tidak, batalkan!",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + productId).submit();
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    swalWithBootstrapButtons.fire({
                        title: "Dibatalkan",
                        text: "Produk Anda aman :)",
                        icon: "error"
                    });
                }
            });
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
