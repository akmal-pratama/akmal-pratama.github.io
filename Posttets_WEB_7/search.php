<?php
// Ambil query dari URL
$query = filter_var(trim($_GET['query']), FILTER_SANITIZE_STRING);

// Ambil data produk dari database berdasarkan query
// ... kode untuk mengambil data produk ...

// Contoh data produk
$products = [
    ['name' => 'Sofa', 'price' => 4500000],
    ['name' => 'Meja Makan', 'price' => 6000000],
    ['name' => 'Rak Buku', 'price' => 3000000],
    ['name' => 'Kursi Kantor', 'price' => 2250000],
];

// Filter produk berdasarkan query
$filtered_products = array_filter($products, function($product) use ($query) {
    return stripos($product['name'], $query) !== false;
});

// Kembalikan hasil dalam format JSON
echo json_encode(array_values($filtered_products));
?>