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
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Handle donation submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['donate'])) {
    $donation_id = $_POST['donation_id'];
    $jumlah_donasi = $_POST['jumlah_donasi'];
    
    // Update the progress in database
    $update_query = "UPDATE data_donasi SET Progress = Progress + ? WHERE id = ? AND Ussername = ?";
    $stmt = mysqli_prepare($koneksi, $update_query);
    mysqli_stmt_bind_param($stmt, "dis", $jumlah_donasi, $donation_id, $session_username);
    
    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Donasi berhasil ditambahkan!'); window.location.href='search.php?search=".urlencode($search)."';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan donasi!');</script>";
    }
}

// Query data donasi
$query = "SELECT id, Nama_Donasi, Target_Donasi, Progress, Penerima 
          FROM data_donasi 
          WHERE Ussername = ? AND Nama_Donasi LIKE ?";
$stmt = mysqli_prepare($koneksi, $query);
$search_param = "%$search%";
mysqli_stmt_bind_param($stmt, "ss", $session_username, $search_param);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cari Donasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .search-box {
            width: 60%;
            margin: 30px auto;
        }
        .card-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: start;
        }
        .card {
            width: 300px;
        }
        body {
            background-color: #f5f5f5;
            padding: 20px;
        }
        .container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .modal-content {
            padding: 20px;
        }
    </style>
</head>
<body>

    <div class="container">
        <h3 class="mt-4 mb-3">Cari Donasi</h3>

        <form method="GET" class="search-box d-flex">
            <input type="text" name="search" class="form-control me-2" placeholder="Cari donasi..." value="<?= htmlspecialchars($search) ?>">
            <button class="btn btn-outline-primary">🔍</button>
        </form>

        <div class="card-container">
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <div class="card shadow-sm p-3 mb-4 bg-white rounded">
                        <div class="card-body">
                            <h5 class="card-title text-capitalize"><?= htmlspecialchars($row['Nama_Donasi']) ?></h5>
                            <p class="text-success">Target: Rp <?= number_format($row['Target_Donasi'], 0, ',', '.') ?></p>
                            <p class="text-primary">Progress: Rp <?= number_format($row['Progress'], 0, ',', '.') ?></p>
                            <p>Penerima: <?= htmlspecialchars($row['Penerima']) ?></p>
                            
                            <!-- Donate Button with Modal Trigger -->
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#donateModal<?= $row['id'] ?>">
                                Donasi
                            </button>

                            
                            <!-- Donation Modal -->
                            <div class="modal fade" id="donateModal<?= $row['id'] ?>" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Formulir Donasi</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form method="POST" action="search.php">
                                                <input type="hidden" name="donation_id" value="<?= $row['id'] ?>">
                                                <div class="mb-3">
                                                    <label for="jumlah_donasi<?= $row['id'] ?>" class="form-label">Jumlah Donasi (Rp)</label>
                                                    <input type="number" class="form-control" id="jumlah_donasi<?= $row['id'] ?>" name="jumlah_donasi" required>
                                                </div>
                                                <button type="submit" name="donate" class="btn btn-primary">Kirim Donasi</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Tidak ada data donasi ditemukan.</p>
            <?php endif; ?>
        </div>
        
        <a href="sesudahlogin.php" class="btn btn-secondary mt-3">Kembali ke Halaman Utama</a>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
mysqli_close($koneksi);
?>