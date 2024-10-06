<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING);
    $age = filter_var(trim($_POST['age']), FILTER_VALIDATE_INT);
    $password = filter_var(trim($_POST['password']), FILTER_SANITIZE_STRING);

    if ($name && $age && $password) {
        // Simpan hasil inputan ke dalam variabel JavaScript
        echo "<script>
                var name = '" . htmlspecialchars($name) . "';
                var age = '" . htmlspecialchars($age) . "';
                document.addEventListener('DOMContentLoaded', function() {
                    var popup = document.getElementById('popup');
                    var popupContent = document.querySelector('.popup-content');
                    
                    popupContent.innerHTML += '<h2>Hasil Inputan</h2>';
                    popupContent.innerHTML += '<p><strong>Nama Lengkap:</strong> ' + name + '</p>';
                    popupContent.innerHTML += '<p><strong>Umur:</strong> ' + age + '</p>';
                    popup.classList.remove('hidden');

                    // Sembunyikan pop-up setelah 3 detik
                    setTimeout(function() {
                        popup.classList.add('hidden');
                    }, 3000);
                });
              </script>";
    } else {
        echo "<p>Input tidak valid. Silakan coba lagi.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Perabotan Rumah</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body class="light-mode">
    <div id="popup" class="popup hidden">
        <div class="popup-content">
            <span id="close-popup">&times;</span>
            <h2>Selamat Datang di Toko Perabotan Rumah Kami!</h2>
            <p>Temukan koleksi terbaru dan penawaran menarik kami.</p>
        </div>
    </div>

    <header>
        <div class="logo">Toko Perabotan Rumah</div>
        <nav>
            <ul class="nav-links">
                <li><a href="#">Beranda</a></li>
                <li><a href="#about">Tentang Kami</a></li>
                <li><a href="crud.php">Kelola Produk</a></li> <!-- Tautan ke halaman CRUD -->
            </ul>
            <button id="dark-mode-toggle">
                <i class="fas fa-sun" id="sun-icon"></i>
                <i class="fas fa-moon" id="moon-icon" style="display: none;"></i>
            </button>
        </nav>
        <div class="hamburger">
            <div class="bar"></div>
            <div class="bar"></div>
            <div class="bar"></div>
        </div>  
    </header>

    <main>
        <section id="home">
            <h1>Selamat Datang di Toko Perabotan Kami</h1>
            <p>Jelajahi koleksi furnitur modern dan dekorasi rumah kami.</p>
        </section>
    
        <section id="products">
            <h2>Produk Kami</h2>
            <div class="product-container">
                <?php
                $products = [
                    ['name' => 'Sofa', 'price' => 4500000, 'image' => 'sofa.jpg', 'stock' => 10],
                    ['name' => 'Meja Makan', 'price' => 6000000, 'image' => 'meja makan.jpg', 'stock' => 5],
                    ['name' => 'Rak Buku', 'price' => 3000000, 'image' => 'rak buku.jpg', 'stock' => 8],
                    ['name' => 'Kursi Kantor', 'price' => 2250000, 'image' => 'kursi kantor.jpg', 'stock' => 15]
                ];

                foreach ($products as $product) {
                    echo "<div class='product-card'>";
                    echo "<img src='gambar/{$product['image']}' alt='{$product['name']}'>";
                    echo "<h3>{$product['name']}</h3>";
                    echo "<p>Rp " . number_format($product['price'], 0, ',', '.') . "</p>";
                    echo "<button class='stock-button' data-stock='{$product['stock']}'>Stok</button>"; // Tambahkan data stok
                    echo "</div>";
                }
                ?>
            </div>
        </section>
    
        <section id="about">
            <h2>Tentang Kami</h2>
            <p>Nama saya Akmal Alvian Pratama dengan NIM 2309106021 dari kelas A1 sekian terima kasih lorem ipsum</p>
        </section>

        <section id="form-section">
            <h2>Form Pendaftaran</h2>
            <form id="registration-form" method="POST" action="">
                <label for="name">Nama Lengkap:</label>
                <input type="text" id="name" name="name" required><br>

                <label for="age">Umur:</label>
                <input type="number" id="age" name="age" min="1" max="100" required><br>

                <label for="password">Kata Sandi:</label>
                <input type="password" id="password" name="password" required><br>

                <button type="submit" id="submit-button">Submit</button>
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Toko Perabotan Rumah</p>
    </footer>

    <script src="script.js"></script>
</body>
</html>