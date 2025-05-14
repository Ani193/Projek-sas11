<?php
session_start();
// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "sewa_alat");
if ($conn->connect_error) {
  die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data alat aktif
$sql = "SELECT * FROM barang WHERE aktif = 1";
$result = $conn->query($sql);
$barangList = [];
if ($result->num_rows > 0) {
  while($barang = $result->fetch_assoc()) {
    $barangList[] = $barang;
  }
} 

// Ambil data paket aktif
$paketList = [];
$resultPaket = $conn->query("SELECT * FROM paket WHERE aktif = 1");
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
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      padding: 20px;
    }
    .header-k, .header-k2 {
      text-align: center;
      margin-bottom: 30px;
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
      <form method="GET" action="<?= isset($_SESSION['userLogin']) ? 'formulirSewa.php' : 'loginUser.php'; ?>">
        <input type="hidden" name="id" value="<?= $barang['id']; ?>">
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
      <div class="judul-k"><?= $paket['nama']; ?></div>
      <div class="keterangan-k"><?= nl2br($paket['deskripsi']); ?></div>
      <div class="stok-k">stok: <?= $paket['stok']; ?></div>
      <div class="harga-k">Rp <?= number_format($paket['harga'], 0, ',', '.'); ?></div>
    </div>
  <?php endforeach; ?>
</div>

</body>
</html>