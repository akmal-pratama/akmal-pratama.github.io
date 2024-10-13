// Fungsi untuk toggle dark mode
const darkModeToggle = document.getElementById('dark-mode-toggle');
const body = document.body;
const sunIcon = document.getElementById('sun-icon');
const moonIcon = document.getElementById('moon-icon');

darkModeToggle.addEventListener('click', () => {
    body.classList.toggle('dark-mode');
    const isDarkMode = body.classList.contains('dark-mode');
    sunIcon.style.display = isDarkMode ? 'none' : 'inline';
    moonIcon.style.display = isDarkMode ? 'inline' : 'none';
});

// Fungsi untuk menu hamburger
const hamburger = document.querySelector('.hamburger');
const navLinks = document.querySelector('.nav-links');

hamburger.addEventListener('click', () => {
    hamburger.classList.toggle('active');
    navLinks.classList.toggle('active');
});

// Fungsi untuk modal keranjang
const cartModal = document.getElementById('cart-modal');
const closeCartButton = document.getElementById('close-cart');
const cartButton = document.getElementById('cart-button');

cartButton.addEventListener('click', () => cartModal.classList.remove('hidden'));
closeCartButton.addEventListener('click', () => cartModal.classList.add('hidden'));

// Fungsi untuk validasi form registrasi
const form = document.getElementById('registration-form');

form.addEventListener('submit', (event) => {
    const name = document.getElementById('name').value;
    const age = parseInt(document.getElementById('age').value);
    
    if (name.length < 3) {
        alert('Nama harus lebih dari 3 karakter.');
        event.preventDefault();
    }
    
    if (age < 1 || age > 100) {
        alert('Umur harus antara 1 dan 100.');
        event.preventDefault();
    }
});

// Tambahkan JavaScript untuk menangani klik tombol dan menampilkan pop-up
document.addEventListener('DOMContentLoaded', function() {
    var stockButtons = document.querySelectorAll('.stock-button');
    var popup = document.getElementById('popup');
    var popupContent = document.querySelector('.popup-content');

    stockButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            var stock = this.getAttribute('data-stock');
            popupContent.innerHTML = '<h2>Informasi Stok</h2>';
            popupContent.innerHTML += '<p>Jumlah Stok: ' + stock + '</p>';
            popup.classList.remove('hidden');

            // Sembunyikan pop-up setelah 3 detik
            setTimeout(function() {
                popup.classList.add('hidden');
            }, 3000);
        });
    });
});

