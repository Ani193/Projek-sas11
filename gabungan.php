<?php
session_start();
// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "sewa_alat");
if ($conn->connect_error) {
  die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data alat aktif
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Ambil data alat aktif 
$sql = "SELECT * FROM barang WHERE aktif = 1";
if ($search !== '') {
  $sql .= " AND nama LIKE '%" . $conn->real_escape_string($search) . "%'";
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
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>gabungan</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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

    .search-form {
      display: flex;
      align-items: center;
      background-color: white;
      border-radius: 20px;
      padding: 2px 8px;
    }

    .search {
      border: none;
      outline: none;
      padding: 6px;
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

    /* Notifikasi box */
    #notifBox {
      position: fixed;
      top: 60px; /* dibawah navbar */
      left: 50%;
      transform: translateX(-50%);
      background-color: #fffae6;
      color: #444;
      font-weight: bold;
      padding: 15px 30px;
      border: 1px solid #ffd966;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.2);
      display: none;
      z-index: 1100;
      max-width: 90%;
      text-align: center;
      font-size: 16px;
    }

    /* Hero Section */
    .hero {
      height: 100vh;
      background-image: url('gunung.jpg');
      background-size: cover;
      background-position: center;
      display: flex;
      align-items: center;
      justify-content: flex-start;
      padding: 60px 2rem 2rem 2rem;
      color: #fff;
    }

    .hero-content {
      max-width: 600px;
      text-align: left;
    }

    .hero-content h1 {
      font-size: 3rem;
      font-weight: bold;
      margin-bottom: 1rem;
    }

    .hero-content p {
      font-size: 1.25rem;
      margin-bottom: 1.5rem;
    }

    .btn {
      background-color: white;
      color: #1f2937;
      padding: 0.75rem 1.5rem;
      border-radius: 0.5rem;
      font-weight: 600;
      text-decoration: none;
      transition: background-color 0.3s;
      display: inline-block;
    }

    .btn:hover {
      background-color: #e5e7eb;
    }

    /* Section 2 */
    .container2 {
      display: flex;
      flex-wrap: wrap;
      padding: 40px;
      justify-content: space-between;
      align-items: center;
      background-color: white;
    }

    .text-section2 {
      min-width: 300px;
      max-width: 600px;
    }

    .text-section2 h1 {
      font-size: 32px;
      margin-bottom: 20px;
    }

    .text-section2 p {
      font-size: 16px;
      margin-bottom: 20px;
      line-height: 1.6;
      color: #333;
    }

    .bttn-2 {
      background-color: black;
      color: white;
      padding: 12px 24px;
      border: none;
      font-weight: bold;
      cursor: pointer;
    }

    .bttn-2:hover {
      background-color: #444;
    }

    .image-section2 {
      flex: 1;
      text-align: center;
    }

    .image-section2 img {
      width: 300px;
      max-width: 100%;
      border-radius: 10px;
    }

    .info-2 {
      background-color: #000;
      color: white;
      padding: 20px;
      margin-top: 20px;
      border-radius: 5px;
      font-size: 14px;
      line-height: 1.4;
    }

    /* Section 3 */
    .container-3 {
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 40px;
      background-color: lightgray;
    }

    .header-3 {
      text-align: center;
      display: flex;
      flex-direction: column;
      margin-bottom: 20px;
    }

    .header-3 h1 {
      color: black;
      font-size: 45px;
      margin: 0;
    }

    .header-3 p {
      font-size: 16px;
      color: black;
      margin: 10px 0 0 0;
    }

    .row {
      display: flex;
      flex-direction: row;
      gap: 70px;
      text-align: center;
      padding: 50px 0;
      flex-wrap: wrap;
      box-sizing: border-box;
      justify-content: center;
    }

    .col-4 {
      width: 300px;
      background: white;
      border-radius: 8px;
      padding: 15px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .image-3 {
      position: relative;
    }

    .image-3 img {
      max-width: 100%;
      border-radius: 6px;
    }

    .judul-3 {
      font-weight: bold;
      font-size: 20px;
      margin-top: 15px;
      color: #111;
    }

    .keterangan-3 {
      font-size: 15px;
      color: #666;
      margin-top: 6px;
      text-align: left;
    }

    /* Footer */
    footer {
      background-color: #1e1e2f;
      color: #ffffff;
      padding: 40px 20px;
    }

    .footer-container-4 {
      max-width: 1200px;
      margin: auto;
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
    }

    .footer-column-4 {
      flex: 1 1 250px;
      margin: 10px;
    }

    .footer-column-4 h3 {
      border-bottom: 2px solid #f39c12;
      padding-bottom: 10px;
      margin-bottom: 20px;
      color: #f39c12;
    }

    .footer-column-4 ul {
      list-style: none;
      padding: 0;
    }

    .footer-column-4 ul li {
      margin: 10px 0;
    }

    .footer-column-4 ul li a {
      color: #ccc;
      text-decoration: none;
      transition: color 0.3s;
    }

    .footer-column-4 ul li a:hover {
      color: #ffffff;
    }

    .social-icons-4 img {
      margin-right: 10px;
      width: 30px;
      height: 30px;
      vertical-align: middle;
      cursor: pointer;
      filter: brightness(0) invert(1);
      transition: filter 0.3s;
    }

    .social-icons-4 img:hover {
      filter: brightness(0) invert(0.75);
    }

    .footer-bottom-4 {
      text-align: center;
      margin-top: 30px;
      color: #aaa;
      font-size: 14px;
    }

    @media (max-width: 768px) {
      .footer-container-4 {
        flex-direction: column;
        align-items: center;
      }
      .row {
        gap: 30px;
      }
    }
  </style>
</head>
<body>

  <!-- Notifikasi -->
  <div id="notifBox"></div>

  <!-- Navbar -->
  <header class="navbar">
    <div class="logo">
      <img src="logoo.png" alt="logo" />
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

  <!-- Hero Section -->
  <section class="hero">
    <div class="hero-content">
      <h1>BASECAME OUTDOOR</h1>
      <p>Temukan petualangan terbaikmu bersama kami di alam bebas.</p>
      <a href="form_userr.html" class="btn">sewa sekarang!!</a>
    </div>
  </section>

  <!-- Section 2 -->
<section id="section2" class="container2">
  <div class="text-section2">
    <h1>Komitmen BASECAMP Outdoor untuk Aktivitas dan Hobby Anda</h1>
    <p>Ketika anda berfikir tentang aktivitas petualangan, jangan biarkan masalah peralatan penunjang menjadi hambatan kegembiraan anda...</p>
    <p>Melalui barang yang berkualitas pada kelasnya, kami berkomitmen untuk mendukung kegiatan petualangan anda...</p>
  </div>
  <div class="image-section2">
    <img src="peta.jpeg" alt="BASECAMP outdoor" />
    <div class="info-2">
      <h2>BASECAMP outdoor</h2>
      <p>RENTAL PERLENGKAPAN CAMPING, Kabupaten Batang</p>
      <p>Lokasi toko penyewaan:<br>Jl. Raya Kandeman, Kabupaten Batang</p>
    </div>
  </div>
</section>

<!-- Section 3 -->
<section id="section3" class="container-3">
  <div class="header-3">
    <h1>Katalog Produk</h1>
    <p>Untuk melihat keseluruhan produk yang kami sediakan, silahkan mengunjungi halaman Katalog produk dibawah ini.</p>
  </div>
  <a href="Hal_Katalog.php" class="btn">Katalog Selengkapnya</a>
  <div class="row">
    <div class="col-4">
      <div class="image-3">
        <img src="tenda2org.png" alt="Tenda" />
      </div>
      <div class="judul-3">Tenda Kapasitas 2 Orang</div>
      <div class="keterangan-3">
        - Double layer, Waterproof<br>
        - Fly,190T Polyester PU 3000 mm<br>
        - Berat 2,1kg<br>
      </div>
    </div>

    <div class="col-4">
      <div class="image-3">
        <img src="Tenda5org.jpg" alt="Tenda" />
      </div>
      <div class="judul-3">Tenda Kapasitas 6 Orang</div>
      <div class="keterangan-3">
        - Double layer, Waterproof<br>
        - Fly,190T Polyester PU 3000 mm<br>
        - Berat 2,1kg<br>
      </div>
    </div>

    <div class="col-4">
      <div class="image-3">
        <img src="Carrier.png" alt="carrier" />
      </div>
      <div class="judul-3">Carrier Bag (Belum termasuk coverbag)</div>
      <div class="keterangan-3">
        - Panjang 27,5 Inci & lebar 19 Inci<br>
        - Kantong jaring untuk visibilitas<br>
        - Kantong pinggul untuk akses barang<br>
      </div>
    </div>
  </div>
</section>

<!-- Footer -->
<section id="4">
  <footer>
    <div class="footer-container-4">
      <div class="footer-column-4">
        <h3>Tentang Kami</h3>
        <p>Basecamp Outdoor - Solusi Sewa Alat Camping Terpercaya untuk Petualangan Anda. Temukan berbagai peralatan berkualitas untuk mendukung kegiatan outdoor Anda. Terhubung dengan alam, nikmati petualangan, dan buat kenangan tak terlupakan.</p>
      </div>
      <div class="footer-column-4">
        <h3>Quick Link</h3>
        <ul>
          <li><a href="gabungan.html">Beranda</a></li>
          <li><a href="Hal_Katalog.php">Katalog</a></li>
          <li><a href="persyaratanSewa.html">Persyaratan Penyewaan</a></li>
          <li><a href="loginUser.php">Sewa Sekarang</a></li>
        </ul>
      </div>
      <div class="footer-column-4">
        <h3>Ikuti Kami</h3>
        <div class="social-icons-4">
          <a href="#"><img src="ig.png" alt="Instagram"></a>
          <a href="#"><img src="fb.png" alt="Facebook"></a>
          <a href="#"><img src="twitt.png" alt="Twitter"></a>
        </div>
      </div>
    </div>
    <div class="footer-bottom-4">
      &copy; 2025 BASECAMP OUTDOOR
    </div>
  </footer>
</section>

<script>
  // Notifikasi user dari localStorage
  document.addEventListener("DOMContentLoaded", () => {
    const notif = localStorage.getItem("notifUser");
    if (notif) {
      alert(notif);  // Bisa diganti dengan toast notification jika mau
      localStorage.removeItem("notifUser");
    }
  });

  // Fungsi reset form (pastikan form ada id dan elemen sesuai)
  function resetForm() {
    if(document.getElementById("nama")) document.getElementById("nama").value = "";
    if(document.getElementById("email")) document.getElementById("email").value = "";
    if(document.getElementById("telepon")) document.getElementById("telepon").value = "";
    if(document.getElementById("alamat")) document.getElementById("alamat").value = "";
    if(document.getElementById("tanggalPesan")) document.getElementById("tanggalPesan").value = "";

    document.querySelectorAll('.alat').forEach(cb => cb.checked = false);
    document.querySelectorAll('.jumlah').forEach(sel => sel.value = "1");

    if(document.getElementById("paketPemula")) document.getElementById("paketPemula").checked = false;
    if(document.getElementById("paketKeluarga")) document.getElementById("paketKeluarga").checked = false;
    if(document.getElementById("paketLengkap")) document.getElementById("paketLengkap").checked = false;

    if(document.getElementById("buktiMember")) document.getElementById("buktiMember").value = "";
    if(document.getElementById("buktiTransfer")) document.getElementById("buktiTransfer").value = "";
    const memberRadio = document.querySelector('input[name="member"][value="biasa"]');
    if(memberRadio) memberRadio.checked = true;

    const metodeRadio = document.querySelector('input[name="metode"]:checked');
    if(metodeRadio) metodeRadio.checked = false;

    const diskonText = document.getElementById("diskonText");
    if(diskonText) {
      diskonText.classList.add("hidden");
      diskonText.textContent = "";
    }
    const totalBayar = document.getElementById("totalBayar");
    if(totalBayar) totalBayar.textContent = "Total Bayar: Rp0";

    const barcodeImage = document.getElementById("barcodeImage");
    if(barcodeImage) barcodeImage.style.display = "none";
  }
</script>