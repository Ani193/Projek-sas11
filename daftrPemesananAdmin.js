// Mengambil data siswa dan ekstra dari localStorage
const daftarSiswa = JSON.parse(localStorage.getItem('listDataSiswa')) || [];
const daftarEkstra = JSON.parse(localStorage.getItem('listDataEkstra')) || [];

// Menggabungkan data siswa dan ekstra
const gabunganData = daftarSiswa.map((siswa, index) => {
    const ekstra = daftarEkstra[index] || { ekstra: 'tidak ada', motivasi: 'tidak ada' };
    return {
        ...siswa,
        ekstra: ekstra.ekstra,
        motivasi: ekstra.motivasi
    };
});

// Menampilkan data gabungan di tabel
const tampilkanGabungan = () => {
    const tblGabungan = document.getElementById('tblGabungan').getElementsByTagName('tbody')[0];
    tblGabungan.innerHTML = '';

    gabunganData.forEach((data, index) => {
        const row = tblGabungan.insertRow();
        row.innerHTML = `
            <td>${index + 1}</td>
            <td>${data.no}</td>
            <td>${data.id}</td>
            <td>${data.nama}</td>
            <td>${data.noTelpon}</td>
            <td>${data.alamat}</td>
            <td>${data.status}</td>
            <td>${data.alat}</td>
            <td>${data.jumlah}</td>
            <td>${data.harga}</td>
            <td>${data.paket}</td>
            <td>${data.harga}</td>
            <td>${data.total}</td>
            <td>${data.tanggal}</td>
            <td>${data.durasi}</td>
            <td>${data.metode}</td>
        `;
    });
};

// Panggil fungsi untuk menampilkan data
tampilkanGabungan();