// --- KONFIGURASI DATABASE ---
// PASTE URL DARI GOOGLE APPS SCRIPT DI SINI (JANGAN SAMPAI SALAH)
const API_URL = 'https://script.google.com/macros/s/AKfycbwCXAcgg52jpgQJklQ5vLEoDsLbwZtDgoEzEihuli1SMPKVghhjISvJi4ftDQeZMgbZ/exec'; 

const form = document.getElementById('itemForm');
const itemNameInput = document.getElementById('itemName');
const itemPriceInput = document.getElementById('itemPrice');
const editIndexInput = document.getElementById('edit-index'); // Kita pakai ini buat simpan ID Baris Excel
const submitBtn = document.getElementById('submit-btn');
const cancelBtn = document.getElementById('cancel-btn');
const tableBody = document.getElementById('tableBody');

// Helper: Tampilkan Loading saat ambil data
function showLoading(isLoading) {
    if (isLoading) {
        tableBody.innerHTML = '<tr><td colspan="4" style="text-align:center;">⏳ Sedang memuat data dari Google Sheets...</td></tr>';
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
        const items = await response.json();
        renderTable(items);
    } catch (error) {
        console.error("Error:", error);
        tableBody.innerHTML = '<tr><td colspan="4" style="color:red; text-align:center;">Gagal mengambil data. Cek koneksi internet.</td></tr>';
    } finally {
        // Matikan loading, kembalikan tombol
        submitBtn.disabled = false;
    }
}

// 2. RENDER: Tampilkan ke Tabel
function renderTable(items) {
    tableBody.innerHTML = '';
    
    if (items.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="4" class="empty-message">Database kosong.</td></tr>';
        return;
    }

    items.forEach((item, index) => {
        const formattedPrice = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(item.price);

        // Kita simpan ID baris Excel (item.id) di tombol data-id
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

// 3. CREATE & UPDATE: Kirim Data ke Sheets
form.addEventListener('submit', async function(e) {
    e.preventDefault();

    const name = itemNameInput.value;
    const price = Number(itemPriceInput.value);
    const id = editIndexInput.value; // Ini ID baris excel

    // Tentukan Aksi: create atau update
    const action = id ? 'update' : 'create';
    
    // Ubah tombol jadi loading
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'Menyimpan...';
    submitBtn.disabled = true;

    try {
        // Kirim data ke API
        await fetch(API_URL, {
            method: 'POST',
            body: JSON.stringify({ action, id, name, price }) // Kita kirim JSON
        });

        // Sukses! Reset form dan ambil data terbaru
        resetForm();
        fetchItems(); 

    } catch (error) {
        alert("Gagal menyimpan data!");
    } finally {
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
    }
});

// 4. EVENT LISTENER (Klik Tombol Tabel)
tableBody.addEventListener('click', async function(e) {
    // TOMBOL EDIT
    if (e.target.classList.contains('btn-edit')) {
        const id = e.target.getAttribute('data-id');
        const name = e.target.getAttribute('data-name');
        const price = e.target.getAttribute('data-price');

        // Masukkan data ke form
        itemNameInput.value = name;
        itemPriceInput.value = price;
        editIndexInput.value = id;

        submitBtn.textContent = 'Update';
        submitBtn.style.backgroundColor = '#ffc107';
        submitBtn.style.color = '#333';
        cancelBtn.style.display = 'block';
    }
    
    // TOMBOL HAPUS
    if (e.target.classList.contains('btn-delete')) {
        if(confirm('Yakin ingin menghapus data ini dari Database?')) {
            const id = e.target.getAttribute('data-id');
            
            // Tampilkan loading kecil di tombol
            e.target.textContent = '...';
            e.target.disabled = true;

            await fetch(API_URL, {
                method: 'POST',
                body: JSON.stringify({ action: 'delete', id: id })
            });

            fetchItems(); // Refresh tabel
        }
    }
});

// 5. Reset Form
function resetForm() {
    form.reset();
    editIndexInput.value = '';
    submitBtn.textContent = 'Tambah';
    submitBtn.style.backgroundColor = '#28a745';
    submitBtn.style.color = 'white';
    cancelBtn.style.display = 'none';
}

// Jalankan saat pertama kali buka web
fetchItems();