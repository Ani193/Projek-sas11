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
  <meta charset="UTF-8">
  <title>Persyaratan sewa</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

  <style>
    body {
    font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
      background-color:#f0f4f8;
      color: #000;
      margin: 50px;
      padding: 30px;
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
    .container {
      background-color:#ffffff;
      border: 2px solid #000;
      border-radius: 10px;
      padding: 20px;
      max-width: 800px;
      margin: auto;
    }
    h1 {
      text-align: center;
      margin-bottom: 30px;
      font-family: "Poppins", sans-serif;;
    }
    .step {
      margin-bottom: 20px;
    }
    .step-title {
      font-weight: bold;
      margin-bottom: 8px;
     font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
    }
    .step-content {
      margin-left: 20px;
      margin-bottom: 8px;
    }
    .p {
      display: flex;
      justify-content: center;
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
    <a href="persyaratanSewa.php">Persyaratan</a>
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

  <div class="container">
    <h1>Persyaratan Umum Penyewaan Alat Camping</h1>

    <div class="step">
        <div class="step-title">1. Reservasi/Pemesanan</div>
        <div class="step-content">
          - Disarankan melakukan pemesanan  minimal H-2 atau beberapa hari sebelum tanggal meggunaan, 
          apalagi saat musim liburan<br>
      </div>

    <div class="step">
      <div class="step-title">2. Formulir Penyewaan</div>
      <div class="step-content">
        - Mengisi formulir peyewaan alat camping (berupa form online), yang berisi data diri 
          dan rincian alat yang disewa<br>
    </div>

    <div class="step">
      <div class="step-title">3. Pembayaran</div>
      <div class="step-content">
        - Pembayaran langsung full payment<br>
        - Pembayaran bisa dilakukan secara online melalui transfer e-wallet (seperti OVO, DANA, GoPay)
          Konfirmasi pembayaran ke admin setelah melakukan transaksi.<br>
      </div>
    </div>

    <h1>Peraturan Peyewaan Alat Camping</h1>

    <div class="step">
      <div class="step-title">1. Durasi Sewa</div>
      <div class="step-content">
        - Durasi sewa 2 hari 1 malam<br>
        - Keterlambatan pengembalian akan dikenakan biaya tambahan
      </div>
    </div>

    <div class="step">
      <div class="step-title">2. Kondisi barang</div>
      <div class="step-content">
        - Barang harus dikembalikan dalam kondisi lengkap, bersih dan kering, terutama untuk tenda, 
          matras, dan sleeping bag<br>
        - Jika terdapat kerusakan atau kehilangan, penyewa wajib megganti sesuai dengan ketentuan
          (misalnya membayar biaya perbaikan atau ganti rugi)
      </div>
    </div>

    <div class="step">
      <div class="step-title">3. Larangan</div>
      <div class="step-content">
        - Tidak diperbolehkan meminjamkan alat kepada pihak ketiga tanpa izin<br>
        - Dilarang meggunakan alat untuk kegiatan yang merusak lingkungan 
      </div>
    </div>

    <div class="step">
      <div class="step-title">4. Pengecekan Barang</div>
      <div class="step-content">
        - Pihak penyewa dan penyedia biasanya melakukan pengecekan bersama saat alat diambil dan 
          dikembalikan
      </div>
    </div>

    <div class="step">
        <div class="step-title">5. Penjemputan & Pengembalian Barang</div>
        <div class="step-content">
          - Barang bisa diambil sendiri ke lokasi penyewaan<br>
          - Waktu penjemputan dan pengembalian barang biasanya sudah diatur sesuai kesepakatan 
            penyewa dan peyedia
        </div>
    </div>

      <div class="step">
        <div class="step-title">6. Tanggung Jawab </div>
        <div class="step-content">
          - Segala bentuk kerusakan akibat kelalaian akan menjadi tanggung jawab penyewa<br>
          - Kehilangan alat karena pencurian atau kelalaian juga wajib diganti sesuai nilai barang
        </div>
      </div> 
      
  </div>
</body>
</html>
