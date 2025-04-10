<?php
$host = "localhost"; // host database
$username = "root"; // username database
$password = ""; // password database (kosong jika tidak ada)
$database = "wbs"; // nama database

// Membuat koneksi
$conn = new mysqli($host, $username, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

?> 