<?php
session_start();

// Koneksi database
$host = "localhost";
$username = "root";
$password = ""; // tidak pakai spasi
$database = "wbs";

$koneksi = mysqli_connect($host, $username, $password, $database);

// Cek koneksi
if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $un = filter_input(INPUT_POST, "lgn2", FILTER_SANITIZE_SPECIAL_CHARS);
    $pw = filter_input(INPUT_POST, "lgn3", FILTER_SANITIZE_SPECIAL_CHARS);

    // Ambil data user berdasarkan username
    $query = "SELECT * FROM `data_donatur` WHERE `Ussername` = ?";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "s", $un);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);

        // Verifikasi password dengan hash
        // NOTE: Make sure your password column in database is named 'Password' or change this
        if (password_verify($pw, $data['Password'])) {
             $_SESSION['username']= $un;
            header("Location: profil.php");
            exit();
        } else {
            $error_message = "Password salah!";
        }
    } else {
        $error_message = "Username tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
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
        
        .lgn {
            background-color: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            text-align: center;
            position: relative;
        }
        
        .lgn h2 {
            margin-bottom: 1.5rem;
            color: #333;
            font-size: 2rem;
        }
        
        .lgn form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        
        .lgn label {
            text-align: left;
            margin-bottom: -10px;
            color: #555;
            font-weight: bold;
        }
        
        .lgn input {
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            transition: all 0.3s;
        }
        
        .lgn input:focus {
            border-color: #667eea;
            outline: none;
            box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.2);
        }
        
        .lgn button {
            background-color: #667eea;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 5px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 10px;
        }
        
        .lgn button:hover {
            background-color: #5a6fd1;
        }
        
        /* Style untuk error message box */
        .error-box {
            background-color: #ffebee;
            color: #c62828;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #ef9a9a;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: fadeIn 0.5s;
        }
        
        .error-box:before {
            content: "!";
            display: inline-block;
            background-color: #c62828;
            color: white;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            text-align: center;
            line-height: 24px;
            margin-right: 10px;
            font-weight: bold;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Responsive design */
        @media (max-width: 480px) {
            .lgn {
                padding: 1.5rem;
                margin: 0 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="lgn">
        <h2>Login</h2>
        
        <?php if (!empty($error_message)): ?>
            <div class="error-box">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>
        
        <form action="" method="POST">
            <label for="lgn2">Username</label>
            <input type="text" name="lgn2" id="lgn2" placeholder="Enter your username" required>
            <label for="lgn3">Password</label>
            <input type="password" name="lgn3" id="lgn3" placeholder="Enter your password" required>
            <button type="submit">Submit</button>
        </form>
    </div>
</body>
</html>