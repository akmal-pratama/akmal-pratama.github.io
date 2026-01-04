// 1. Inisialisasi Data
let items = JSON.parse(localStorage.getItem('items')) || [];
const form = document.getElementById('itemForm');
const itemNameInput = document.getElementById('itemName');
const itemPriceInput = document.getElementById('itemPrice');
const editIndexInput = document.getElementById('edit-index');
const submitBtn = document.getElementById('submit-btn');
const cancelBtn = document.getElementById('cancel-btn');
const tableBody = document.getElementById('tableBody');

// 2. Fungsi Render Tabel
function renderTable() {
    tableBody.innerHTML = '';
    
    if (items.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="4" class="empty-message">Belum ada barang. Silakan tambah data.</td></tr>';
        return;
    }

    items.forEach((item, index) => {
        const formattedPrice = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(item.price);

        // PERUBAHAN DI SINI: Kita hapus onclick="..." dan ganti pakai data-index
        const row = `
            <tr>
                <td>${index + 1}</td>
                <td>${item.name}</td>
                <td>${formattedPrice}</td>
                <td class="action-buttons">
                    <button class="btn-edit" data-index="${index}">Edit</button>
                    <button class="btn-delete" data-index="${index}">Hapus</button>
                </td>
            </tr>
        `;
        tableBody.innerHTML += row;
    });
}

// 3. EVENT LISTENER UNTUK TOMBOL (Teknik Event Delegation)
// Ini mendeteksi klik pada tabel, lalu mengecek tombol apa yang ditekan
tableBody.addEventListener('click', function(e) {
    // Jika yang diklik adalah tombol HAPUS
    if (e.target.classList.contains('btn-delete')) {
        const index = e.target.getAttribute('data-index');
        deleteItem(index);
    }
    
    // Jika yang diklik adalah tombol EDIT
    if (e.target.classList.contains('btn-edit')) {
        const index = e.target.getAttribute('data-index');
        editItem(index);
    }
});

// 4. Fungsi Tambah / Update
form.addEventListener('submit', function(e) {
    e.preventDefault();

    const name = itemNameInput.value;
    const price = Number(itemPriceInput.value);
    const index = editIndexInput.value;

    if (index === '') {
        items.push({ name, price });
    } else {
        items[index] = { name, price };
    }

    saveAndRender();
    resetForm();
});

// 5. Fungsi Logika Edit
function editItem(index) {
    const item = items[index];
    itemNameInput.value = item.name;
    itemPriceInput.value = item.price;
    editIndexInput.value = index;

    submitBtn.textContent = 'Update';
    submitBtn.style.backgroundColor = '#ffc107';
    submitBtn.style.color = '#333';
    cancelBtn.style.display = 'block';
}

// 6. Fungsi Logika Hapus
function deleteItem(index) {
    if(confirm('Yakin ingin menghapus barang ini?')) {
        // Hapus 1 item berdasarkan index
        items.splice(index, 1);
        saveAndRender();
        
        // Jika user menghapus barang yang sedang diedit, reset formnya
        if (index == editIndexInput.value) {
            resetForm();
        }
    }
}

// 7. Helper
function saveAndRender() {
    localStorage.setItem('items', JSON.stringify(items));
    renderTable();
}

function resetForm() {
    form.reset();
    editIndexInput.value = '';
    submitBtn.textContent = 'Tambah';
    submitBtn.style.backgroundColor = '#28a745';
    submitBtn.style.color = 'white';
    cancelBtn.style.display = 'none';
}

// Jalankan saat load
renderTable();