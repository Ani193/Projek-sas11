<?php
session_start();

// Simpan ID barang kalau datang dari tombol Sewa
if (isset($_GET['id'])) {
  $_SESSION['pendingSewa'] = $_GET['id'];
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user";

// Buat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Koneksi gagal: " . $conn->connect_error);
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST['email'];
  $password = $_POST['password'];
  $remember = isset($_POST['remember']) ? true : false;

  // Cek apakah email sudah ada
  $sql = "SELECT * FROM users WHERE email = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();
  $user = $result->fetch_assoc();

  if ($user) {
    if (password_verify($password, $user['password'])) {
      $_SESSION['userLogin'] = $user['id'];
      $_SESSION['email'] = $email;

      if ($remember) {
        setcookie('rememberedEmail', $email, time() + (86400 * 30), "/");
      } else {
        setcookie('rememberedEmail', '', time() - 3600, "/");
      }

      // Redirect setelah login
      if (isset($_SESSION['pendingSewa'])) {
        $id = $_SESSION['pendingSewa'];
        unset($_SESSION['pendingSewa']);
        header("Location: form_userr.html?id=" . $id);
      } else {
        header("Location: form_userr.html"); // ganti dengan halaman katalog utama jika perlu
      }
      exit();
    } else {
      $message = "Password salah!";
    }
  } else {
    // Jika email belum terdaftar, buat akun baru
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (email, password) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $hashedPassword);
    $stmt->execute();

    $message = "Akun baru berhasil dibuat! Silakan login kembali.";
  }

  $conn->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login User</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    * { box-sizing: border-box; }
    body {
      font-family: Arial, sans-serif;
      background: linear-gradient(to right, #8ec5fc, #e0c3fc);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }
    .login-container {
      background: white;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 8px 16px rgba(0,0,0,0.2);
      width: 100%;
      max-width: 350px;
      text-align: center;
    }
    .login-container h1 {
      margin-bottom: 20px;
      font-size: 28px;
      color: #333;
    }
    input[type="text"], input[type="password"] {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border: 1px solid #ccc;
      border-radius: 8px;
    }
    button {
      width: 100%;
      padding: 12px;
      margin-top: 15px;
      background-color: #4d9ef6;
      border: none;
      color: white;
      font-weight: bold;
      font-size: 16px;
      border-radius: 8px;
      cursor: pointer;
    }
    button:hover {
      background-color: #378ae0;
    }
    .remember-me {
      margin-top: 10px;
      text-align: left;
    }
    #message {
      margin-top: 15px;
      font-size: 14px;
      color: red;
    }
  </style>
</head>
<body>

<div class="login-container">
  <h1>Login User</h1>
  <form method="POST" action="">
    <input type="text" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <div class="remember-me">
      <input type="checkbox" name="remember" id="remember"> Remember me
    </div>
    <button type="submit">Login</button>
  </form>
  <p id="message"><?= isset($message) ? $message : ''; ?></p>
</div>

</body>
</html>