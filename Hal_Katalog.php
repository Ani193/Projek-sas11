<?php
session_start();
// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "sewa_alat");
if ($conn->connect_error) {
  die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data alat aktif
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Ambil data alat aktif (filter jika ada pencarian)
$sql = "SELECT * FROM barang WHERE aktif = 1";
if ($search !== '') {
  $sql .= " AND nama LIKE '%" . $conn->real_escape_string($search) . "%'";
}

$result = $conn->query($sql);
$barangList = [];
if ($result->num_rows > 0) {
  while($barang = $result->fetch_assoc()) {
    $barangList[] = $barang;
  }
} 

// Ambil data paket aktif
$paketList = [];
$sqlPaket = "SELECT * FROM paket WHERE aktif = 1";
if ($search !== '') {
  $sqlPaket .= " AND nama LIKE '%" . $conn->real_escape_string($search) . "%'";
}
$resultPaket = $conn->query($sqlPaket);

if ($resultPaket && $resultPaket->num_rows > 0) {
  while($paket = $resultPaket->fetch_assoc()) {
    $paketList[] = $paket;
  }
}

$conn->close();


?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Halaman Katalog</title>
 
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      padding: 20px;
    }
  /* Navbar */
    .navbar {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      height: 60px;
      background-color:rgba(0, 32, 64, 0.8);
      color: #ffffff;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 2rem;
      z-index: 10;
    }

    .logo img{
     vertical-align: middle;
    }
    .nav-links a {
      color: white;
      margin: 0 10px;
      text-decoration: none;
    }

    .nav-links a:hover {
      text-decoration: underline;
    }

    .navbar-right {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .search-form {
      display: flex;
      align-items: center;
      background-color: white;
      border-radius: 20px;
      padding: 2px 4px;
      height: 28px;
    }

    .search {
      border: none;
      outline: none;
      padding: 2px 4px;
      border-radius: 20px;
      font-size: 14px;
      color: #333;
    }

    .search-btn {
      background: none;
      border: none;
      cursor: pointer;
      font-size: 16px;
      margin-left: 4px;
      color: black;
    }

    .icon-admin {
      color: white;
      font-size: 18px;
      text-decoration: none;
    }
    .header-k, .header-k2 {
      text-align: center;
      margin-top: 80px;
    }
    .header-k h1, .header-k2 h1 {
      font-size: 40px;
      font-family: "Noto Serif Ahom", serif;
    }
    .katalog-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 20px;
      justify-items: center;
    }
    .katalog-item {
      border: 1px solid #ccc;
      border-radius: 10px;
      padding: 10px;
      text-align: center;
      max-width: 220px;
    }
    .katalog-item img {
      width: 100%;
      height: 150px;
      object-fit: cover;
      border-radius: 5px;
    }
    .judul-k {
      font-weight: bold;
      font-size: 18px;
      margin-top: 10px;
    }
    .stok-k, .keterangan-k, .harga-k {
      font-size: 14px;
      margin: 5px 0;
    }
    .harga-k {
      color: red;
      font-weight: bold;
    }
    button {
      margin-top: 10px;
      padding: 6px 12px;
    }
  </style>
</head>
<body>
<!-- Navbar -->
<header class="navbar">
 <div class="logo">
      <img src="logoo.png" alt="logo" style="height: 120px;">
  </div>

  <nav class="nav-links">
    <a href="gabungan.php">Beranda</a>
    <a href="Hal_Katalog.php">Katalog</a>
    <a href="persyaratanSewa.html">Persyaratan</a>
    <a href="loginUser.php">Penyewaan</a>
  </nav>

  <div class="navbar-right">
  <form class="search-form" method="GET" action="Hal_Katalog.php">
  <input type="text" class="search" name="search" placeholder="Cari..." value="<?= htmlspecialchars($search); ?>" />
  </form>

    <a href="loginAdmin.php" class="icon-admin" title="Login Admin">
      <i class="fas fa-user"></i>
    </a>
  </div>
</header>
<div class="header-k">
  <h1>Di Sewakan</h1>
</div>

<div class="katalog-grid">
  <?php foreach($barangList as $barang): ?>
    <div class="katalog-item">
      <img src="<?= $barang['foto']; ?>" alt="<?= $barang['nama']; ?>">
      <div class="judul-k"><?= $barang['nama']; ?></div>
      <div class="stok-k">Stok: <?= $barang['stok']; ?></div>
      <div class="keterangan-k"><?= nl2br($barang['deskripsi']); ?></div>
      <div class="harga-k">Rp <?= number_format($barang['harga'], 0, ',', '.'); ?></div>
      <form method="GET" action="<?= isset($_SESSION['userLogin']) ? 'form_userr.php' : 'loginUser.php'; ?>">
  <input type="hidden" name="id" value="<?= $barang['id']; ?>">
  <input type="hidden" name="tipe" value="barang">
  <button type="submit">Sewa</button>
</form>
    </div>
  <?php endforeach; ?>
</div>

<div class="header-k2">
  <h1>Paket Penyewaan</h1>
</div>

<div class="katalog-grid">
  <?php foreach($paketList as $paket): ?>
    <div class="katalog-item">
      <img src="<?= $paket['foto']; ?>" alt="<?= $paket['nama']; ?>">
      <div class="judul-k"><?= $paket['nama']; ?></div>
      <div class="stok-k">Stok: <?= $paket['stok']; ?></div>
      <div class="keterangan-k"><?= nl2br($paket['deskripsi']); ?></div>
      <div class="harga-k">Rp <?= number_format($paket['harga'], 0, ',', '.'); ?></div>
      <form method="GET" action="<?= isset($_SESSION['userLogin']) ? 'form_userr.php' : 'loginUser.php'; ?>">
  <input type="hidden" name="id" value="<?= $paket['id']; ?>">
  <input type="hidden" name="tipe" value="paket">
  <button type="submit">Sewa</button>
</form>
    </div>
  <?php endforeach; ?>
</div>

</body>
</html> 