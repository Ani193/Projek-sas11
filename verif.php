<?php
$servername = "localhost";
$username = "root"; // ganti kalau perlu
$password = ""; // ganti kalau ada password database
$dbname = "nama_database"; // ganti nama database kamu

// Koneksi ke database
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Tangkap data dari request
$id = $_POST['id'];
$aksi = $_POST['aksi']; // setujui atau tolak

// Update status di database
if ($aksi == 'setujui') {
    $status = 'disetujui';
} else if ($aksi == 'tolak') {
    $status = 'ditolak';
} else {
    die("Aksi tidak valid");
}

$sql = "UPDATE pembayaran SET status='$status' WHERE id=$id";

if ($conn->query($sql) === TRUE) {
    echo "Berhasil";
} else {
    echo "Gagal: " . $conn->error;
}

$conn->close();
?>