<?php
include 'koneksi.php';
$password=$_POST['pws']
$ussername=$_POST['usn']
$sql = "SELECT * FROM data_donatur WHERE username = '$usn' AND password = '$pws'";
mysqli_query($koneksi,$sql);
?>