<?php
  // Koneksi ke database
  $conn = new mysqli("localhost", "root", "", "sewa_alat"); // sesuaikan
  if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
  }

  $sql = "SELECT * FROM barang WHERE aktif = 1";
  $result = $conn->query($sql);

  // Tampilkan katalog barang
  $barangList = [];
  if ($result->num_rows > 0) {
    while($barang = $result->fetch_assoc()) {
      $barangList[] = $barang;
    }
  } else {
    echo "<p>Tidak ada barang aktif yang tersedia.</p>";
  }

  $conn->close();
?>

<?php
session_start();
// Mengecek apakah user sudah login
if (!isset($_SESSION['userLogin'])) {
  header("Location: loginUser.php"); // Arahkan ke halaman login jika belum login
  exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Halaman Katalog</title>
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <style>
    /* CSS Anda tetap sama */
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      padding: 20px;
    }
    .header-k {
      text-align: center;
      margin-bottom: 30px;
    }
    .header-k h1 {
      font-size: 40px;
      font-family: "Noto Serif Ahom", serif;
    }
    .katalog-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr); /* Menampilkan 4 kolom */
      gap: 20px;
      justify-items: center;
    }
    .katalog-item {
      width: 100%;
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
    .stok-k {
      font-size: 14px;
      color: #333;
    }
    .keterangan-k {
      font-size: 14px;
      color: #666;
      margin: 10px 0;
    }
    .harga-k {
      font-size: 16px;
      color: red;
      font-weight: bold;
    }
    button {
      margin-top: 10px;
      padding: 6px 12px;
    }
    .header-k2 {
      text-align: center;
      margin-bottom: 30px;
    }
    .header-k2 h1 {
      font-size: 40px;
      font-family: "Noto Serif Ahom", serif;
    }
  </style>
</head>
<body>

<div class="header-k">
  <h1>Di Sewakan</h1>
</div>

<div class="katalog-grid" id="katalogBarang">
  <?php foreach($barangList as $barang): ?>
    <div class="katalog-item">
      <img src="<?= $barang['foto']; ?>" alt="<?= $barang['nama']; ?>">
      <div class="judul-k"><?= $barang['nama']; ?></div>
      <div class="stok-k">Stok: <span><?= $barang['stok']; ?></span></div>
      <div class="keterangan-k"><?= nl2br($barang['deskripsi']); ?></div>
      <div class="harga-k">Rp <?= number_format($barang['harga'], 0, ',', '.'); ?></div>
      <form method="GET" action="formulirSewa.php">
        <input type="hidden" name="id" value="<?= $barang['id']; ?>">
        <button type="submit">Sewa</button>
      </form>
    </div>
  <?php endforeach; ?>
</div>

<div class="header-k2">
  <h1>Paket Penyewaan</h1>
</div>

</body>
</html>