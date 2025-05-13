<?php
session_start(); // Mulai session
$error = "";
$email_cookie = "";

if (isset($_COOKIE["remember_email"])) {
    $email_cookie = trim($_COOKIE["remember_email"]);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $remember = isset($_POST["remember"]);

    $conn = new mysqli("localhost", "root", "", "camping");
    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT * FROM admin WHERE BINARY TRIM(email) = ? AND BINARY TRIM(password) = ?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // Simpan email di session
        $_SESSION['email'] = $email;

        if ($remember) {
            setcookie("remember_email", $email, time() + (30 * 24 * 60 * 60)); // 30 hari
        } else {
            setcookie("remember_email", "", time() - 3600); // Hapus cookie
        }

        header("Location: menu.php"); // Redirect ke halaman menu
        exit();
    } else {
        $error = "Email atau password salah.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login Admin</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #e4eef2;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }

    .login-container {
      background: white;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
      width: 420px;
    }

    h1 {
      text-align: center;
      color: #007bff;
      margin-bottom: 25px;
    }

    table {
      width: 100%;
    }

    table td {
      padding: 10px 0;
    }

    input[type="text"],
    input[type="password"] {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 16px;
    }

    input:focus {
      border-color: #007bff;
      outline: none;
      box-shadow: 0 0 5px rgba(0, 123, 255, 0.4);
    }

    .remember-me-container {
      margin-top: 10px;
      font-size: 14px;
    }

    .remember-me-container input {
      margin-right: 6px;
    }

    button {
      width: 100%;
      margin-top: 20px;
      padding: 12px;
      background-color: #007bff;
      color: white;
      border: none;
      border-radius: 6px;
      font-size: 16px;
      cursor: pointer;
    }

    button:hover {
      background-color: #0056b3;
    }

    .pesan-error {
      color: red;
      font-size: 14px;
      text-align: center;
      margin-top: 10px;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <h1>Login Admin</h1>
    <form method="POST" action="">
      <table>
        <tr>
          <td>Email</td>
        </tr>
        <tr>
          <td><input type="text" name="email" value="<?= htmlspecialchars($email_cookie) ?>" required></td>
        </tr>
        <tr>
          <td>Password</td>
        </tr>
        <tr>
          <td><input type="password" name="password" required></td>
        </tr>
        <tr>
          <td class="remember-me-container">
            <label>
              <input type="checkbox" name="remember" <?= $email_cookie ? 'checked' : '' ?>> Remember Me
            </label>
          </td>
        </tr>
      </table>
      <button type="submit">Sign In</button>
      <div class="pesan-error"><?= htmlspecialchars($error) ?></div>
    </form>
  </div>
</body>
</html>