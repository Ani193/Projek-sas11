<?php
$conn = new mysqli("localhost", "root", "", "sewa_alat");
if ($conn->connect_error) {
  die("Koneksi gagal: " . $conn->connect_error);
}

// Tambah atau update paket
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $id = $_POST['id'] ?? '';
  $nama = $_POST['nama'];
  $harga = $_POST['harga'];
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
    if ($fotoPath) {
      $sql = "UPDATE paket SET nama='$nama', harga='$harga', deskripsi='$deskripsi', foto='$fotoPath' WHERE id=$id";
    } else {
      $sql = "UPDATE paket SET nama='$nama', harga='$harga', deskripsi='$deskripsi' WHERE id=$id";
    }
  } else {
    $sql = "INSERT INTO paket (nama, harga, deskripsi, foto, aktif) VALUES ('$nama','$harga','$deskripsi','$fotoPath', $aktif)";
  }

  $conn->query($sql);
  header("Location: paketPenyewaan.php");
  exit();
}

// Toggle aktif/nonaktif
if (isset($_GET['toggle'])) {
  $id = $_GET['toggle'];
  $result = $conn->query("SELECT aktif FROM paket WHERE id=$id");
  if ($row = $result->fetch_assoc()) {
    $newStatus = $row['aktif'] ? 0 : 1;
    $conn->query("UPDATE paket SET aktif=$newStatus WHERE id=$id");
  }
  header("Location: paketAdmin.php");
  exit();
}

// Hapus
if (isset($_GET['hapus'])) {
  $id = $_GET['hapus'];
  $conn->query("DELETE FROM paket WHERE id=$id");
  header("Location: paketAdmin.php");
  exit();
}

// Ambil data untuk diedit
$editData = null;
if (isset($_GET['edit'])) {
  $id = $_GET['edit'];
  $result = $conn->query("SELECT * FROM paket WHERE id=$id");
  $editData = $result->fetch_assoc();
}

// Ambil semua paket
$paketList = $conn->query("SELECT * FROM paket");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Admin - Data Paket</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 20px;
    }
    h2 {
      margin-bottom: 10px;
    }
    form, table {
      margin-bottom: 20px;
      width: 100%;
    }
    input, textarea, button {
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
      vertical-align: top;
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

<h2><?= $editData ? 'Edit Paket' : 'Tambah Paket' ?></h2>
<form method="POST" enctype="multipart/form-data">
  <?php if ($editData): ?>
    <input type="hidden" name="id" value="<?= $editData['id'] ?>">
  <?php endif; ?>
  <input type="text" name="nama" placeholder="Nama Paket" value="<?= $editData['nama'] ?? '' ?>" required>
  <input type="number" name="harga" placeholder="Harga" value="<?= $editData['harga'] ?? '' ?>" required>
  <textarea name="deskripsi" placeholder="Deskripsi" required><?= $editData['deskripsi'] ?? '' ?></textarea>
  <input type="file" name="foto" <?= $editData ? '' : 'required' ?>>
  <button type="submit"><?= $editData ? 'Simpan Perubahan' : 'Tambah Paket' ?></button>
</form>

<h2>Data Paket Penyewaan</h2>
<table>
  <thead>
    <tr>
      <th>Foto</th>
      <th>Nama</th>
      <th>Harga</th>
      <th>Deskripsi</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php while ($p = $paketList->fetch_assoc()): ?>
      <tr class="<?= $p['aktif'] ? '' : 'nonaktif' ?>">
        <td><img src="<?= $p['foto'] ?>" alt="Foto Paket"></td>
        <td><?= $p['nama'] ?></td>
        <td>Rp <?= number_format($p['harga'], 0, ',', '.') ?></td>
        <td><?= nl2br($p['deskripsi']) ?></td>
        <td>
          <form method="GET" style="display:inline">
            <input type="hidden" name="edit" value="<?= $p['id'] ?>">
            <button type="submit">Edit</button>
          </form>
          <form method="GET" style="display:inline">
            <input type="hidden" name="toggle" value="<?= $p['id'] ?>">
            <button type="submit"><?= $p['aktif'] ? 'Nonaktifkan' : 'Aktifkan' ?></button>
          </form>
          <form method="GET" style="display:inline" onsubmit="return confirm('Yakin ingin menghapus?')">
            <input type="hidden" name="hapus" value="<?= $p['id'] ?>">
            <button type="submit" class="hapus">Hapus</button>
          </form>
        </td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>

</body>
</html>