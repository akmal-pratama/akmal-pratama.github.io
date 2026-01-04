// --- KONFIGURASI DATABASE ---
// GANTI URL DI BAWAH INI DENGAN URL GOOGLE SCRIPT KAMU
const API_URL = 'https://script.google.com/macros/s/AKfycbwCXAcgg52jpgQJklQ5vLEoDsLbwZtDgoEzEihuli1SMPKVghhjISvJi4ftDQeZMgbZ/exec'; 

const form = document.getElementById('itemForm');
const itemNameInput = document.getElementById('itemName');
const itemPriceInput = document.getElementById('itemPrice');
const editIndexInput = document.getElementById('edit-index');
const submitBtn = document.getElementById('submit-btn');
const cancelBtn = document.getElementById('cancel-btn');
const tableBody = document.getElementById('tableBody');
const searchInput = document.getElementById('searchInput');

// Variabel Global untuk menyimpan data agar pencarian cepat
let allItems = [];

// Helper: Tampilkan Loading
function showLoading(isLoading) {
    if (isLoading) {
        tableBody.innerHTML = '<tr><td colspan="4" style="text-align:center;">⏳ Sedang memuat data...</td></tr>';
        submitBtn.disabled = true;
    } else {
        submitBtn.disabled = false;
    }
}

// 1. READ: Ambil Data dari Google Sheets
async function fetchItems() {
    showLoading(true);
    try {
        const response = await fetch(API_URL);
        const data = await response.json();
        
        // Simpan data ke variabel global
        allItems = data; 
        
        // Tampilkan semua data
        renderTable(allItems);
        
    } catch (error) {
        console.error("Error:", error);
        tableBody.innerHTML = '<tr><td colspan="4" style="color:red; text-align:center;">Gagal mengambil data. Cek koneksi internet.</td></tr>';
    } finally {
        submitBtn.disabled = false;
    }
}

// 2. RENDER: Tampilkan ke Tabel
function renderTable(items) {
    tableBody.innerHTML = '';
    
    if (items.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="4" class="empty-message">Data tidak ditemukan.</td></tr>';
        return;
    }

    items.forEach((item, index) => {
        const formattedPrice = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(item.price);

        const row = `
            <tr>
                <td>${index + 1}</td>
                <td>${item.name}</td>
                <td>${formattedPrice}</td>
                <td class="action-buttons">
                    <button class="btn-edit" data-id="${item.id}" data-name="${item.name}" data-price="${item.price}">Edit</button>
                    <button class="btn-delete" data-id="${item.id}">Hapus</button>
                </td>
            </tr>
        `;
        tableBody.innerHTML += row;
    });
}

// 3. FITUR SEARCH (Live Search)
searchInput.addEventListener('input', function(e) {
    const keyword = e.target.value.toLowerCase();
    
    // Filter data dari variabel global
    const filteredItems = allItems.filter(item => 
        item.name.toLowerCase().includes(keyword)
    );
    
    renderTable(filteredItems);
});

// 4. CREATE & UPDATE: Kirim Data ke Sheets
form.addEventListener('submit', async function(e) {
    e.preventDefault();

    const name = itemNameInput.value;
    const price = Number(itemPriceInput.value);
    const id = editIndexInput.value; 
    const action = id ? 'update' : 'create';
    
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'Menyimpan...';
    submitBtn.disabled = true;

    try {
        await fetch(API_URL, {
            method: 'POST',
            body: JSON.stringify({ action, id, name, price })
        });

        resetForm();
        searchInput.value = ''; // Reset search box
        fetchItems(); // Refresh data

    } catch (error) {
        alert("Gagal menyimpan data!");
        console.error(error);
    } finally {
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
    }
});

// 5. EVENT LISTENER (Klik Tombol Tabel)
tableBody.addEventListener('click', async function(e) {
    // Tombol Edit
    if (e.target.classList.contains('btn-edit')) {
        const id = e.target.getAttribute('data-id');
        const name = e.target.getAttribute('data-name');
        const price = e.target.getAttribute('data-price');

        itemNameInput.value = name;
        itemPriceInput.value = price;
        editIndexInput.value = id;

        submitBtn.textContent = 'Update';
        submitBtn.style.backgroundColor = '#ffc107';
        submitBtn.style.color = '#333';
        cancelBtn.style.display = 'block';
        
        // Scroll ke atas biar user liat formnya
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
    
    // Tombol Hapus
    if (e.target.classList.contains('btn-delete')) {
        if(confirm('Yakin ingin menghapus data ini dari Database?')) {
            const id = e.target.getAttribute('data-id');
            e.target.textContent = '...';
            e.target.disabled = true;

            await fetch(API_URL, {
                method: 'POST',
                body: JSON.stringify({ action: 'delete', id: id })
            });

            fetchItems();
        }
    }
});

function resetForm() {
    form.reset();
    editIndexInput.value = '';
    submitBtn.textContent = 'Tambah';
    submitBtn.style.backgroundColor = '#28a745';
    submitBtn.style.color = 'white';
    cancelBtn.style.display = 'none';
}

// Jalankan saat load

fetchItems();
