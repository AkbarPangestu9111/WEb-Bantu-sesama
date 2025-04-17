<?php
session_start(); // This must be at the very top before any output

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_donasi = $_POST["nama_donasi"];
    $target_donasi = $_POST["target_donasi"];
    $session_username = $_SESSION['username'];
    $progress = $_POST["progress"];
    $penerima = $_POST["penerima"];

    $host = "localhost";
    $user = "root"; 
    $pass = "";     
    $db   = "wbs"; 

    $conn = new mysqli($host, $user, $pass, $db);

    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO data_donasi (nama_donasi, target_donasi, ussername, Progress, Penerima) 
            VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sdsss", $nama_donasi, $target_donasi, $session_username, $progress, $penerima);

    if ($stmt->execute()) {
        echo "<script>alert('Donasi berhasil dikirim!');</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Form Donasi</title>
  <style>
    html, body {
      height: 100%;
      margin: 0;
      background-color: #f4f0da;
      font-family: 'Courier New', monospace;
    }

    body {
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .form-container {
      width: 100%;
      max-width: 500px;
      padding: 24px;
      background-color: #ffffffcc;
      border-radius: 18px;
      box-shadow: 8px 8px 0 #000000;
      border: 1px solid #000000;
    }

    h2 {
      font-size: 24px;
      font-weight: bold;
      color:rgb(0, 0, 0);
      margin-bottom: 24px;
      text-align: center;
    }

    label {
      display: block;
      margin-bottom: 6px;
      font-weight: bold;
      color: #000000;
    }

    input[type="text"],
    input[type="number"] {
      width: 100%;
      padding: 5px;
      margin-bottom: 20px;
      border: none;
      border-bottom: 2px solid #000;
      font-family: 'dm sans', monospace;
      background-color:rgb(245, 245, 245);
      color: #222;
      font-size: 16px;
    }

    .login-link {
            text-align: center;
            margin-top: 1rem;
            color: #666;
        }

    .login-link a {
            color: #667eea;
            text-decoration: none;
        }

    .submit-button {
      background-color: #f0a500;
      color: #000;
      font-size: 18px;
      font-weight: bold;
      padding: 12px 24px;
      border: none;
      border-radius: 14px;
      box-shadow: 5px 5px 0 #222;
      cursor: pointer;
      font-family: 'dm sans', monospace;
      transition: all 0.2s ease;
      display: block;
      margin: auto;
    }

    .submit-button:hover {
      background-color: #d98e00;
    }
  </style>
</head>
<body>

  <div class="form-container">
    <h2>Formulir Donasi</h2>
    <form method="POST">
      <label for="nama_donasi">Nama Donasi:</label>
      <input type="text" id="nama_donasi" name="nama_donasi" required>

      <label for="target_donasi">Target Donasi (Rp):</label>
      <input type="number" id="target_donasi" name="target_donasi" required>
    
      <label for="progress">Progress (Rp):</label>
      <input type="number" id="progress" name="progress" required>

      <label for="penerima">Penerima:</label>
      <input type="text" id="penerima" name="penerima" required>

      <button type="submit" class="submit-button">Kirim Donasi</button>

      <div class="login-link">
      Cek data anda disini <a href="profil.php">cek disini</a>
      </div>
    </form>
  </div>

</body>
</html>