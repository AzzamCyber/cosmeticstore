<?php
require_once 'includes/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validasi input
    if ($email == '' || $password == '') {
        $_SESSION['login_error'] = "Email & Password harus diisi!";
        header('Location: login.php');
        exit();
    }

    // Ambil user dari database (role client)
    $q = mysqli_query($db, "SELECT * FROM users WHERE email='$email' AND role='client'");
    $user = mysqli_fetch_assoc($q);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header('Location: dashboard_client.php'); // Halaman dashboard client user
        exit();
    } else {
        $_SESSION['login_error'] = "Login gagal. Email atau Password salah!";
        header('Location: login.php');
        exit();
    }
} else {
    header('Location: login.php');
    exit();
}
?>
