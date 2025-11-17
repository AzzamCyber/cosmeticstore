<?php
require_once 'includes/db.php';
session_start();

// Validasi input
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Cek kosong
    if ($username == '' || $email == '' || $password == '') {
        $_SESSION['reg_error'] = "Semua field harus diisi!";
        header("Location: login.php");
        exit();
    }

    // Email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['reg_error'] = "Email tidak valid!";
        header("Location: login.php");
        exit();
    }

    // Cek user sudah ada?
    $cek = mysqli_query($db, "SELECT id FROM users WHERE username='$username' OR email='$email'");
    if (mysqli_num_rows($cek) > 0) {
        $_SESSION['reg_error'] = "Username atau email sudah terdaftar!";
        header("Location: login.php");
        exit();
    }

    // Hash password
    $hash = password_hash($password, PASSWORD_DEFAULT);

    // Input ke DB (role client)
    $ins = mysqli_query($db, "INSERT INTO users (username, email, password, role) VALUES 
        ('$username', '$email', '$hash', 'client')");

    if ($ins) {
        $_SESSION['user_id'] = mysqli_insert_id($db);
        $_SESSION['username'] = $username;
        header("Location: index.php");
        exit();
    } else {
        $_SESSION['reg_error'] = "Gagal register, coba lagi!";
        header("Location: login.php");
        exit();
    }
} else {
    header("Location: login.php");
    exit();
}
?>
