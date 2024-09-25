const darkModeToggle = document.getElementById('dark-mode-toggle');
const body = document.body;

darkModeToggle.addEventListener('click', () => {
    body.classList.toggle('dark-mode');
    
    const sunIcon = document.getElementById('sun-icon');
    const moonIcon = document.getElementById('moon-icon');

    if (body.classList.contains('dark-mode')) {
        sunIcon.style.display = 'none'; 
        moonIcon.style.display = 'inline'; 
    } else {
        sunIcon.style.display = 'inline'; 
        moonIcon.style.display = 'none'; 
    }
}); 

const hamburger = document.querySelector('.hamburger');
const navLinks = document.querySelector('.nav-links');

hamburger.addEventListener('click', () => {
    hamburger.classList.toggle('active');
    navLinks.classList.toggle('active');
});

// Ambil elemen yang diperlukan
const cartModal = document.getElementById('cart-modal');
const closeCartButton = document.getElementById('close-cart');
const cartButton = document.getElementById('cart-button'); // Tombol untuk membuka keranjang

// Event listener untuk membuka modal keranjang
cartButton.addEventListener('click', () => {
    cartModal.classList.remove('hidden'); // Tampilkan modal
});

// Event listener untuk menutup modal keranjang
closeCartButton.addEventListener('click', () => {
    cartModal.classList.add('hidden'); // Sembunyikan modal
});