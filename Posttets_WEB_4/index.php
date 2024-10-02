<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil nilai dari form
    $name = htmlspecialchars($_POST['name']);
    $age = htmlspecialchars($_POST['age']);
    $password = htmlspecialchars($_POST['password']); // Biasanya password tidak ditampilkan, hanya disimpan

    // Tampilkan hasil input
    echo "<section id='result-section'>";
    echo "<h2>Hasil Inputan</h2>";
    echo "<p><strong>Nama Lengkap:</strong> " . $name . "</p>";
    echo "<p><strong>Umur:</strong> " . $age . "</p>";
    echo "</section>";
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
    <!-- Kotak Pop-up -->
    <div id="popup" class="popup hidden">
        <div class="popup-content">
            <span id="close-popup">&times;</span>
            <h2>Selamat Datang di Toko Perabotan Rumah Kami!</h2>
            <p>Temukan koleksi terbaru dan penawaran menarik kami.</p>
        </div>
    </div>

    <!-- Header -->
    <header>
        <div class="logo">Toko Perabotan Rumah</div>
        <nav>
            <ul class="nav-links">
                <li><a href="#">Beranda</a></li>
                <li><a href="#about">Tentang Kami</a></li>
            </ul>
            <button id="dark-mode-toggle">
                <i class="fas fa-sun" id="sun-icon"></i>
                <i class="fas fa-moon" id="moon-icon" style="display: none;"></i>
            </button>
            <li><button id="cart-button">Keranjang (<span id="cart-count">0</span>)</button></li>         
        </nav>
        <div class="hamburger">
            <div class="bar"></div>
            <div class="bar"></div>
            <div class="bar"></div>
        </div>  
        <div id="cart-modal" class="hidden">
            <div class="cart-content">
                <h2>Keranjang Belanja</h2>
                <ul id="cart-items"></ul>
                <button id="clear-cart">Kosongkan Keranjang</button>
                <button id="close-cart">Tutup</button>
            </div>
        </div>
    </header>

        

    <!-- Konten Utama -->
    <main>
        <section id="home">
            <h1>Selamat Datang di Toko Perabotan Kami</h1>
            <p>Jelajahi koleksi furnitur modern dan dekorasi rumah kami.</p>
        </section>
    
        <section id="products">
            <h2>Produk Kami</h2>
            <div class="product-container">
                <div class="product-card">
                    <img src="gambar/sofa.jpg" alt="Produk 1">
                    <h3>Sofa</h3>
                    <p>Rp 4.500.000</p>
                    <button>Tambah ke Keranjang</button>
                </div>
                <div class="product-card">
                    <img src="gambar/meja makan.jpg" alt="Produk 2">
                    <h3>Meja Makan</h3>
                    <p>Rp 6.000.000</p>
                    <button>Tambah ke Keranjang</button>
                </div>
                <div class="product-card">
                    <img src="gambar/rak buku.jpg" alt="Produk 3">
                    <h3>Rak Buku</h3>
                    <p>Rp 3.000.000</p>
                    <button>Tambah ke Keranjang</button>
                </div>
                <div class="product-card">
                    <img src="gambar/kursi kantor.jpg" alt="Produk 4">
                    <h3>Kursi Kantor</h3>
                    <p>Rp 2.250.000</p>
                    <button>Tambah ke Keranjang</button>
                </div>
            </div>
        </section>
    
        <section id="about">
            <h2>Tentang Kami</h2>
            <p>Nama saya Akmal Alvian Pratama dengan NIM 2309106021 dari kelas A1 sekian terima kasih lorem ipsum</p>
        </section>
    </main>

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


    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Toko Perabotan Rumah</p>
    </footer>

    <script src="script.js"></script>
</body>
</html>