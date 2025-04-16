<?php
session_start();

$host = "localhost";
$username = "root";
$passwordd = "";
$database = "wbs";

$koneksi = mysqli_connect($host, $username, $passwordd, $database);

// Cek koneksi
if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = filter_input(INPUT_POST, "nama", FILTER_SANITIZE_SPECIAL_CHARS);
    $pekerjaan = filter_input(INPUT_POST, "pekerjaan", FILTER_SANITIZE_SPECIAL_CHARS);
    $username_input = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
    $password = $_POST["password"];
    $confirm_Password = $_POST["confirm_password"];

    // Validasi
    if (empty($nama) || empty($pekerjaan) || empty($username_input) || empty($password) || empty($confirm_Password)) {
        $error_message = "Semua field harus diisi!";
    } elseif ($password !== $confirm_Password) {
        $error_message = "Password dan konfirmasi password tidak cocok!";
    } else {
        // Cek apakah username sudah digunakan
        $cekusername_query = "SELECT * FROM data_donatur WHERE ussername=?";
        $stmt = mysqli_prepare($koneksi, $cekusername_query);
        mysqli_stmt_bind_param($stmt, "s", $username_input);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            $error_message = "Username sudah digunakan, silakan pilih username lain!";
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Masukkan data
            $insert_query = "INSERT INTO data_donatur (Nama, Pekerjaan, ussername, password) VALUES (?, ?, ?, ?)";
            $stmt_insert = mysqli_prepare($koneksi, $insert_query);
            mysqli_stmt_bind_param($stmt_insert, "ssss", $nama, $pekerjaan, $username_input, $hashed_password);

            if (mysqli_stmt_execute($stmt_insert)) {
                $success_message = "Pendaftaran berhasil! Silakan login.";
                $nama = $pekerjaan = $username_input = '';
            } else {
                $error_message = "Terjadi kesalahan saat menyimpan data.";
            }

            mysqli_stmt_close($stmt_insert);
        }

        mysqli_stmt_close($stmt);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - WBS</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }
        
        body {
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-image: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .signup-container {
            background-color: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 500px;
        }
        
        .signup-container h2 {
            margin-bottom: 1.5rem;
            color: #333;
            font-size: 2rem;
            text-align: center;
        }
        
        .form-group {
            margin-bottom: 1rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #555;
            font-weight: bold;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            transition: all 0.3s;
        }
        
        .form-group input:focus {
            border-color: #667eea;
            outline: none;
            box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.2);
        }
        
        button[type="submit"] {
            background-color: #667eea;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 5px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
            width: 100%;
            margin-top: 10px;
        }
        
        button[type="submit"]:hover {
            background-color: #5a6fd1;
        }
        
        .message-box {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
            animation: fadeIn 0.5s;
        }
        
        .error-message {
            background-color: #ffebee;
            color: #c62828;
            border: 1px solid #ef9a9a;
        }
        
        .success-message {
            background-color: #e8f5e9;
            color: #2e7d32;
            border: 1px solid #a5d6a7;
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
        
        .login-link a:hover {
            text-decoration: underline;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @media (max-width: 480px) {
            .signup-container {
                padding: 1.5rem;
                margin: 0 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <h2>Daftar Akun</h2>
        
        <?php if (!empty($error_message)): ?>
            <div class="message-box error-message">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($success_message)): ?>
            <div class="message-box success-message">
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>
        
        <form action="" method="post">
            <div class="form-group">
                <label for="nama">Nama Lengkap</label>
                <input type="text" id="nama" name="nama" placeholder="Masukkan nama lengkap" required
                       value="<?php echo isset($nama) ? htmlspecialchars($nama) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="pekerjaan">Pekerjaan</label>
                <input type="text" id="pekerjaan" name="pekerjaan" placeholder="Masukkan pekerjaan" required
                       value="<?php echo isset($pekerjaan) ? htmlspecialchars($pekerjaan) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Buat username" required
                       value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Buat password" required>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Konfirmasi Password</label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Ulangi password" required>
            </div>
            
            <button type="submit">Daftar</button>
        </form>
        
        <div class="login-link">
            Sudah punya akun? <a href="login.php">Login disini</a>
        </div>
    </div>
</body>
</html>