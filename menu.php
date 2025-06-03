<?php
session_start();

//mengecek apakah user sudah login
if (!isset($_SESSION['email'])){
  header("Location: loginAdmin.php");
  exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Menu Halaman</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
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

 /* Navbar */
 .navbar {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  height: 60px;
  background-color: rgba(0, 32, 64, 0.8);
  color: #ffffff;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 2rem;
  z-index: 1000;
 }

 .logo img {
  vertical-align: middle;
  height:120px;
 }

 .nav-links a {
  color: white;
  margin: 0 10px;
  text-decoration: none;
  font-weight: 600;
 }

 .nav-links a:hover {
  text-decoration: underline;
 }

 .navbar-right {
  display: flex;
  align-items: center;
  gap: 10px;
 }

 .icon-admin {
  color: white;
  font-size: 18px;
  text-decoration: none;
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
   <!-- Navbar -->
  <header class="navbar">
    <div class="logo">
      <img src="logoo.png" alt="logo" />
    </div>

    <nav class="nav-links">
      <a href="gabungan.php">Beranda</a>
      <a href="menu.php">Menu</a>
    </nav>       


      <a href="loginAdmin.php" class="icon-admin" title="Login Admin">
        <i class="fas fa-user"></i>
      </a>
    </div>
  </header>
<div class="container">
  <h1>Halaman Menu</h1>
  <div class="menu-item" onclick="window.location.href='alatAdmin.php'">ALAT YANG DI SEWA</div>
  <div class="menu-item" onclick="window.location.href='data_penyewa_userrr.html'">PESANAN</div>
  <div class="menu-item" onclick="window.location.href='Promo&Diskon_Admin.html'">PROMO DAN DISKON</div>
  <div class="menu-item" onclick="window.location.href='paketPenyewaan.php'">PAKET PENYEWAAN</div>
  <div class="menu-item" onclick="window.location.href='verifikasi_pembayaran_admin.html.html'">MEMVERIFIKASI PEMBAYARAN</div>
  <div class="menu-item" onclick="window.location.href='grafik_mtk.html'">GRAFIK PENYEWAAN</div>
</div>

</body>
</html>