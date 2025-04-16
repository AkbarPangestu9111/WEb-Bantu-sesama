<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$host = "localhost";
$username = "root";
$passwordd = "";
$database = "wbs";

$koneksi = mysqli_connect($host, $username, $passwordd, $database);
if (!$koneksi) {
    die("Connection failed: " . mysqli_connect_error());
}

$session_username = $_SESSION['username'];
$message = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $id_to_update = $_POST['donation_id'];
    $nama_donasi = mysqli_real_escape_string($koneksi, $_POST['nama_donasi']);
    $target_donasi = mysqli_real_escape_string($koneksi, $_POST['target_donasi']);
    
    // Update the record
    $update_query = "UPDATE data_donasi SET Nama_Donasi = ?, Target_Donasi = ? WHERE id = ? AND Ussername = ?";
    $stmt = mysqli_prepare($koneksi, $update_query);
    mysqli_stmt_bind_param($stmt, "sdis", $nama_donasi, $target_donasi, $id_to_update, $session_username);
    
    if (mysqli_stmt_execute($stmt)) {
        $message = "<div class='success'>Data berhasil diupdate!</div>";
    } else {
        $message = "<div class='error'>Gagal mengupdate data: " . mysqli_error($koneksi) . "</div>";
    }
}

// Get all donations for the current user
$query_select = "SELECT id, Nama_Donasi, Target_Donasi FROM data_donasi WHERE Ussername = ?";
$stmt = mysqli_prepare($koneksi, $query_select);
mysqli_stmt_bind_param($stmt, "s", $session_username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Get selected donation details if ID is provided
$selected_donation = null;
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query_selected = "SELECT id, Nama_Donasi, Target_Donasi FROM data_donasi WHERE id = ? AND Ussername = ?";
    $stmt = mysqli_prepare($koneksi, $query_selected);
    mysqli_stmt_bind_param($stmt, "is", $id, $session_username);
    mysqli_stmt_execute($stmt);
    $selected_result = mysqli_stmt_get_result($stmt);
    $selected_donation = mysqli_fetch_assoc($selected_result);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Donasi</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        h1 {
            color: #2c3e50;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        select, input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        
        .donation-details {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 4px;
            margin-top: 20px;
            border-left: 4px solid #3498db;
        }
        
        .donation-details p {
            margin: 5px 0;
        }
        
        .update-button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
        }
        
        .update-button:hover {
            background-color: #2980b9;
        }
        
        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #3498db;
            text-decoration: none;
        }
        
        .back-link:hover {
            text-decoration: underline;
        }
        
        .success {
            color: #27ae60;
            background-color: #e8f5e9;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        
        .error {
            color: #e74c3c;
            background-color: #ffebee;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Update Data Donasi</h1>
        
        <?php echo $message; ?>
        
        <form method="get" action="update.php">
            <div class="form-group">
                <label for="donation_id">Pilih Donasi:</label>
                <select name="id" id="donation_id" onchange="this.form.submit()" required>
                    <option value="">-- Pilih Donasi --</option>
                    <?php
                    mysqli_data_seek($result, 0); // Reset result pointer
                    while ($row = mysqli_fetch_assoc($result)) {
                        $selected = (isset($_GET['id']) && $_GET['id'] == $row['id']) ? 'selected' : '';
                        echo "<option value='{$row['id']}' $selected>{$row['Nama_Donasi']} (ID: {$row['id']})</option>";
                    }
                    ?>
                </select>
            </div>
        </form>
        
        <?php if ($selected_donation): ?>
        <div class="donation-details">
            <h3>Form Update Donasi</h3>
            <form method="post" action="update.php">
                <input type="hidden" name="donation_id" value="<?php echo $selected_donation['id']; ?>">
                
                <div class="form-group">
                    <label for="nama_donasi">Nama Donasi:</label>
                    <input type="text" id="nama_donasi" name="nama_donasi" 
                           value="<?php echo htmlspecialchars($selected_donation['Nama_Donasi']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="target_donasi">Target Donasi (Rp):</label>
                    <input type="number" id="target_donasi" name="target_donasi" 
                           value="<?php echo htmlspecialchars($selected_donation['Target_Donasi']); ?>" required>
                </div>
                
                <button type="submit" name="update" class="update-button">Update Donasi</button>
            </form>
        </div>
        <?php endif; ?>
        
        <a href="profil.php" class="back-link">← Kembali ke Daftar Donasi</a>
    </div>
</body>
</html>

<?php
mysqli_close($koneksi);
?>