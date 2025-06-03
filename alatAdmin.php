<?php
// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "sewa_alat"); // Ganti 'nama_database' sesuai database Anda
if ($conn->connect_error) {
  die("Koneksi gagal: " . $conn->connect_error);
}

// Tambah atau update barang
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $id = $_POST['id'] ?? '';
  $nama = $_POST['nama'];
  $harga = $_POST['harga'];
  $stok = $_POST['stok'];
  $deskripsi = $_POST['deskripsi'];
  $aktif = 1;

  // Upload dan simpan gambar
  $fotoPath = '';
  if ($_FILES['foto']['name']) {
    $fotoName = time() . '_' . basename($_FILES["foto"]["name"]);
    $targetDir = "uploads/";
    if (!is_dir($targetDir)) mkdir($targetDir);
    $targetFile = $targetDir . $fotoName;
    if (move_uploaded_file($_FILES["foto"]["tmp_name"], $targetFile)) {
      $fotoPath = $targetFile;
    }
  }

  if ($id) {
    // Update
    if ($fotoPath) {
      $sql = "UPDATE barang SET nama='$nama', harga='$harga', stok='$stok', deskripsi='$deskripsi', foto='$fotoPath' WHERE id=$id";
    } else {
      $sql = "UPDATE barang SET nama='$nama', harga='$harga', stok='$stok', deskripsi='$deskripsi' WHERE id=$id";
    }
  } else {
    // Tambah
    $sql = "INSERT INTO barang (nama, harga, stok, deskripsi, foto, aktif) VALUES ('$nama','$harga','$stok','$deskripsi','$fotoPath', $aktif)";
  }

  $conn->query($sql);
  header("Location: alatAdmin.php");
  exit();
}

// Nonaktifkan/aktifkan
if (isset($_GET['toggle'])) {
  $id = $_GET['toggle'];
  $result = $conn->query("SELECT aktif FROM barang WHERE id=$id");
  if ($row = $result->fetch_assoc()) {
    $newStatus = $row['aktif'] ? 0 : 1;
    $conn->query("UPDATE barang SET aktif=$newStatus WHERE id=$id");
  }
  header("Location: alatAdmin.php");
  exit();
}

// Hapus
if (isset($_GET['hapus'])) {
  $id = $_GET['hapus'];
  $conn->query("DELETE FROM barang WHERE id=$id");
  header("Location: alatAdmin.php");
  exit();
}

// Ambil data untuk diedit
$editData = null;
if (isset($_GET['edit'])) {
  $id = $_GET['edit'];
  $result = $conn->query("SELECT * FROM barang WHERE id=$id");
  $editData = $result->fetch_assoc();
}

// Ambil semua barang
$barangList = $conn->query("SELECT * FROM barang");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Admin - Data Barang</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 20px;
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
      height:100px;
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
    
    h2 {
      margin-bottom: 10px;
      margin-top:70px;
    }
    form, table {
      margin-bottom: 20px;
      width: 100%;
    }
    input, select, button, textarea {
      margin: 5px 0;
      padding: 5px;
      width: 100%;
      box-sizing: border-box;
    }
    table {
      border-collapse: collapse;
      width: 100%;
    }
    th, td {
      border: 1px solid #ccc;
      padding: 10px;
      text-align: center;
    }
    img {
      width: 100px;
      height: 100px;
      object-fit: cover;
    }
    .nonaktif {
      background-color: #ddd;
    }
    button {
      padding: 5px 10px;
      margin: 2px;
      border: none;
      color: white;
      background-color: #007bff;
      border-radius: 4px;
      cursor: pointer;
    }
    button:hover {
      background-color: #0056b3;
    }
    .hapus {
      background-color: #dc3545;
    }
    .hapus:hover {
      background-color: #a71d2a;
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


<h2><?php echo $editData ? 'Edit Barang' : 'Tambah Barang'; ?></h2>
<form method="POST" enctype="multipart/form-data">
  <?php if ($editData): ?>
    <input type="hidden" name="id" value="<?= $editData['id'] ?>">
  <?php endif; ?>
  <input type="text" name="nama" placeholder="Nama Barang" value="<?= $editData['nama'] ?? '' ?>" required>
  <input type="number" name="harga" placeholder="Harga" value="<?= $editData['harga'] ?? '' ?>" required>
  <input type="number" name="stok" placeholder="Stok" value="<?= $editData['stok'] ?? '' ?>" required>
  <textarea name="deskripsi" placeholder="Deskripsi" required><?= $editData['deskripsi'] ?? '' ?></textarea>
  <input type="file" name="foto" <?= $editData ? '' : 'required' ?>>
  <button type="submit"><?= $editData ? 'Simpan Perubahan' : 'Tambah Barang' ?></button>
</form>

<h2>Data Barang</h2>
<table>
  <thead>
    <tr>
      <th>Foto</th>
      <th>Nama</th>
      <th>Harga</th>
      <th>Stok</th>
      <th>Deskripsi</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php while ($b = $barangList->fetch_assoc()): ?>
      <tr class="<?= $b['aktif'] ? '' : 'nonaktif' ?>">
        <td><img src="<?= $b['foto'] ?>" alt="Foto"></td>
        <td><?= $b['nama'] ?></td>
        <td>Rp <?= number_format($b['harga'], 0, ',', '.') ?></td>
        <td><?= $b['stok'] ?></td>
        <td><?= nl2br($b['deskripsi']) ?></td>
        <td>
          <form method="GET" style="display:inline">
            <input type="hidden" name="edit" value="<?= $b['id'] ?>">
            <button type="submit">Edit</button>
          </form>
          <form method="GET" style="display:inline">
            <input type="hidden" name="toggle" value="<?= $b['id'] ?>">
            <button type="submit"><?= $b['aktif'] ? 'Nonaktifkan' : 'Aktifkan' ?></button>
          </form>
          <form method="GET" style="display:inline" onsubmit="return confirm('Yakin ingin menghapus?')">
            <input type="hidden" name="hapus" value="<?= $b['id'] ?>">
            <button type="submit" class="hapus">Hapus</button>
          </form>
        </td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>

</body>
</html>