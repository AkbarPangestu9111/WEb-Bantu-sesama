<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Donasi</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }
        
        .profile-container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .donation-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        
        .donation-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 20px;
            width: 300px;
            transition: transform 0.3s ease;
        }
        
        .donation-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .donation-title {
            font-size: 1.2rem;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        
        .donation-target {
            font-size: 1rem;
            color: #27ae60;
            margin-bottom: 15px;
        }
        
        .donation-donor {
            font-size: 0.9rem;
            color: #7f8c8d;
            font-style: italic;
        }
        
        .no-data {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            color: #7f8c8d;
        }
        
        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }
        
        .update-button, .delete-button {
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        
        .update-button {
            background-color: #3498db;
            color: white;
        }
        
        .update-button:hover {
            background-color: #2980b9;
        }
        
        .delete-button {
            background-color: #e74c3c;
            color: white;
        }
        
        .delete-button:hover {
            background-color: #c0392b;
        }
        .donation-penerima{
            font-size: 1rem;
            color: #27ae60;
            margin-bottom: 15px;
        }
        .home-button {
            background-color: #2ecc71;
            color: white;
        }
        
        .home-button:hover {
            background-color: #27ae60;
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <?php
        $host = "localhost";
        $username = "root";
        $passwordd = "";
        $database = "wbs";
        
        $koneksi = mysqli_connect($host, $username, $passwordd, $database);
        if (!$koneksi) {
            die("Connection failed: " . mysqli_connect_error());
        }
        
        
        if(isset($_SESSION['username'])) {
            $session_username = $_SESSION['username'];
            $query = "SELECT `Nama`, `Pekerjaan`, `Ussername` FROM `data_donatur` WHERE `Ussername` = ?";
            $stmt = mysqli_prepare($koneksi, $query);
            mysqli_stmt_bind_param($stmt, "s", $session_username);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            if ($row = mysqli_fetch_assoc($result)) {
                echo "<h2>Profil Donatur</h2>";
                echo "<p><strong>Nama:</strong> " . htmlspecialchars($row['Nama']) . "</p>";
                echo "<p><strong>Pekerjaan:</strong> " . htmlspecialchars($row['Pekerjaan']) . "</p>";
                echo "<p><strong>Username:</strong> " . htmlspecialchars($row['Ussername']) . "</p>";
            } else {
                echo "<p>Data profil tidak ditemukan.</p>";
            }
        } else {
            echo "<p>Anda belum login. <a href='login.php'>Login disini</a></p>";
        }
        ?>
        <div class="button-container">
        <a href="home2.php" class="home-button">Home</a>
    </div>
    </div>
    
    <h2>Daftar Donasi</h2>
    
    <div class="donation-container">
        <?php
        
        $query_select = "SELECT `id`, `Nama_Donasi`, `Target_Donasi`,`Progress`,`Penerima` FROM `data_donasi` WHERE `Ussername` = '$session_username'";
        
        $result = mysqli_query($koneksi, $query_select);
        
        if (!$result) {
            // Debugging: tampilkan error query jika ada
            die("Query error: " . mysqli_error($koneksi));
        }

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<div class="donation-card">';
                echo '<div class="donation-title">' . htmlspecialchars($row['Nama_Donasi']) . '</div>';
                echo '<div class="donation-target">Target: Rp ' . number_format($row['Target_Donasi']) . '</div>';
                echo '<div class="donation-target">Target: Rp ' . number_format($row['Progress']) . '</div>';
                echo '<div class="donation-penerima">Penerima:' . htmlspecialchars($row['Penerima']) . '</div>';
                // Add action buttons with the donation ID as a parameter
                echo '<div class="action-buttons">';
                echo '<a href="update.php?id=' . $row['id'] . '" class="update-button">Update</a>';
                echo '<a href="delete.php?id=' . $row['id'] . '" class="delete-button">Delete</a>';
                echo '</div>';
                
                echo '</div>';
            }
        } else {
            echo '<div class="no-data">Belum ada data donasi tersedia untuk akun Anda</div>';
            
            // Debugging: tampilkan query yang dijalankan
            echo '<div style="background:#ffecec;padding:10px;margin-top:20px;">';
            echo '<strong>Debug Info:</strong><br>';
            echo 'Query: ' . htmlspecialchars($query_select) . '<br>';
            echo 'Session username: ' . htmlspecialchars($session_username ?? 'NULL');
            echo '</div>';
        }
        
        mysqli_close($koneksi);
        ?>
    </div>
    
</body>
</html>