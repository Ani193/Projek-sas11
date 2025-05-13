<?php
session_start();

//mengecek apakah user sudah login
if (!isset($_SESSION['emial'])){
  header("Location: loginAdmin.php");
  exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Menu Halaman</title>
<style>
body {
  font-family: sans-serif;
  background-color: #f0f0f0; 
  display: flex;
  justify-content: center; 
  align-items: center;
  min-height: 100vh;
  margin: 0;
}

.container {
  background-color: #fff;
  border-radius: 8px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  padding: 20px;
  text-align: center; 
}

.menu-item {
  background-color: #4CAF50; 
  color: white;
  padding: 10px 20px;
  margin-bottom: 10px;
  width: 400px;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s ease; /* Efek transisi */
}

.menu-item:hover {
  background-color: #45a049; 
}

.menu-item:last-child { 
  background-color: #45a049; 
}

.menu-item:last-child:hover {
  background-color: #45a049; 
}

/* Responsivitas */
@media (max-width: 576px) {
  .container {
    padding: 10px;
  }
  .menu-item {
    padding: 10px 15px;
  }
}
</style>
</head>
<body>
<div class="container">
  <h1>Halaman Menu</h1>
  <div class="menu-item" onclick="window.location.href='alatAdmin.html'">ALAT YANG DI SEWA</div>
  <div class="menu-item" onclick="window.location.href='daftrPemesananAdmin.html'">PESANAN</div>
  <div class="menu-item" onclick="window.location.href='Promo&Diskon_Admin.html'">PROMO DAN DISKON</div>
  <div class="menu-item" onclick="window.location.href='paketPenyewaan.html'">PAKET PENYEWAAN</div>
  <div class="menu-item" onclick="window.location.href='verifikasi_pembayaran.html'">MEMVERIFIKASI PEMBAYARAN</div>
</div>

</body>
</html>