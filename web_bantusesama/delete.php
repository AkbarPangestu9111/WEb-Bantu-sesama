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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete'])) {
    $id_to_delete = $_POST['donation_id'];
    
    // Delete the record
    $delete_query = "DELETE FROM data_donasi WHERE id = ? AND Ussername = ?";
    $stmt = mysqli_prepare($koneksi, $delete_query);
    mysqli_stmt_bind_param($stmt, "is", $id_to_delete, $session_username);
    
    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Data berhasil dihapus!'); window.location.href='delete.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data!');</script>";
    }
}


$query_select = "SELECT id, Nama_Donasi, Target_Donasi,Progress,Penerima FROM data_donasi WHERE Ussername = ?";
$stmt = mysqli_prepare($koneksi, $query_select);
mysqli_stmt_bind_param($stmt, "s", $session_username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);


$selected_donation = null;
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query_selected = "SELECT id, Nama_Donasi, Target_Donasi,Progress,Penerima FROM data_donasi WHERE id = ? AND Ussername = ?";
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
    <title>Hapus Donasi</title>
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
            border-left: 4px solid #e74c3c;
        }
        
        .donation-details p {
            margin: 5px 0;
        }
        
        .delete-button {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
        }
        
        .delete-button:hover {
            background-color: #c0392b;
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
    </style>
</head>
<body>
    <div class="container">
        <h1>Hapus Data Donasi</h1>
        
        <form method="get" action="delete.php">
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
            <h3>Detail Donasi yang Akan Dihapus</h3>
            <p><strong>ID Donasi:</strong> <?php echo htmlspecialchars($selected_donation['id']); ?></p>
            <p><strong>Nama Donasi:</strong> <?php echo htmlspecialchars($selected_donation['Nama_Donasi']); ?></p>
            <p><strong>Target Donasi:</strong> Rp <?php echo number_format($selected_donation['Target_Donasi']); ?></p>
            <p><strong>Progress:</strong> Rp <?php echo number_format($selected_donation['Progress']); ?></p>
            <p><strong>Penerima:</strong> <?php echo htmlspecialchars($selected_donation['Nama_Donasi']); ?></p>
            <form method="post" action="delete.php" onsubmit="return confirmDelete()">
                <input type="hidden" name="donation_id" value="<?php echo $selected_donation['id']; ?>">
                <button type="submit" name="delete" class="delete-button">Hapus Donasi</button>
            </form>
        </div>
        <?php endif; ?>
        
        <a href="profil.php" class="back-link">← Kembali ke Daftar Donasi</a>
    </div>

    <script>
        function confirmDelete() {
            return confirm("Apakah Anda yakin ingin menghapus data donasi ini?");
        }
    </script>
</body>
</html>

<?php
mysqli_close($koneksi);
?>