//Mengambil data pemesanan alat camp dari localStorage
const daftarPemesanan = JSON.parse(localStorage.getItem('listDataPemesanan')) || [];

// Menampilkan data pemesanan di tabel
const tampilkanPemesanan = () => {
    const tblGabungan = document.getElementById('tblGabungan').getElementsByTagName('tbody')[0];
    tblGabungan.innerHTML = '';

    daftarPemesanan.forEach((data, index) => {
        const row = tblGabungan.insertRow();
        row.innerHTML = `
            <td>${index + 1}</td>
            <td>${data.id}</td>
            <td>${data.nama}</td>
            <td>${data.noTelpon}</td>
            <td>${data.alamat}</td>
            <td>${data.status}</td>
            <td>${data.alat}</td>
            <td>${data.jumlah}</td>
            <td>${data.hargaAlat}</td>
            <td>${data.paket}</td>
            <td>${data.hargaPaket}</td>
            <td>${data.total}</td>
            <td>${data.tanggal}</td>
            <td>${data.durasi}</td>
            <td>${data.metode}</td>
        `;
    });
};

// Panggil fungsi untuk menampilkan data
tampilkanPemesanan();