<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('HTTP/1.1 401 Unauthorized');
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

$query = "SELECT `Nama_Donasi`, `Target_Donasi`, `Progress` FROM `data_donasi` WHERE `Ussername` = ?";
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "s", $session_username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$data = [
    'labels' => [],
    'targets' => [],
    'progresses' => []
];

while ($row = mysqli_fetch_assoc($result)) {
    $data['labels'][] = $row['Nama_Donasi'];
    $data['targets'][] = $row['Target_Donasi'];
    $data['progresses'][] = $row['Progress'];
}

header('Content-Type: application/json');
echo json_encode($data);

mysqli_close($koneksi);
?>