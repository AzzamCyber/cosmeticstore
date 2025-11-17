<?php
$host = 'localhost';
$user = 'root';
$pass = ''; // password MySQL biasanya kosong di XAMPP
$db_name = 'labellepeau';

$db = mysqli_connect($host, $user, $pass, $db_name);

if (!$db) {
    die('Koneksi Database Gagal: ' . mysqli_connect_error());
}
?>
