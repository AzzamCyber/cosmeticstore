<?php
session_start();

require_once '../includes/db.php';

if(isset($_SESSION['admin_logged_in'])){
    header('Location: dashboard.php');
    exit();
}

$error = '';
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $username = $_POST['username'];
    $password = $_POST['password'];
    $q = mysqli_query($db, "SELECT * FROM users WHERE username='$username' AND role='admin'");
    $d = mysqli_fetch_assoc($q);
    if($d && password_verify($password, $d['password'])){
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $d['username'];
        header('Location: dashboard.php');
        exit();
    } else {
        $error = 'Username atau password salah!';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin Login | La Belle Peau</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" />
    <style>
        body{
            background:linear-gradient(120deg,#FFD6E0,#FFF0F3);
            font-family:'Poppins',Arial,sans-serif;
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen px-4">
    <div class="w-full max-w-md">
        <form method="post" action="" class="bg-pink-100/80 backdrop-blur-xl rounded-2xl shadow-2xl p-8 w-full" data-aos="fade-up">
            <div class="text-center mb-8">
                <img src="../assets/img/logo.png" alt="logo" class="w-16 h-16 mx-auto mb-4 rounded-xl shadow-lg">
                <h2 class="text-2xl font-extrabold text-pink-500">Admin Login</h2>
                <p class="text-pink-400 text-sm mt-2">La Belle Peau Skincare</p>
            </div>
            
            <?php if($error): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-4">
                <?= htmlspecialchars($error) ?>
            </div>
            <?php endif; ?>
            
            <div class="mb-4">
                <label class="block text-pink-600 text-sm font-semibold mb-2">Username</label>
                <input type="text" name="username" required 
                       class="w-full py-3 px-4 rounded-xl bg-white/60 border border-pink-300 focus:outline-pink-400" 
                       placeholder="Username">
            </div>
            
            <div class="mb-6">
                <label class="block text-pink-600 text-sm font-semibold mb-2">Password</label>
                <input type="password" name="password" required 
                       class="w-full py-3 px-4 rounded-xl bg-white/60 border border-pink-300 focus:outline-pink-400" 
                       placeholder="Password">
            </div>
            
            <button type="submit" 
                    class="w-full py-3 bg-pink-400 text-white font-bold rounded-xl hover:bg-pink-600 transition shadow-lg">
                Login
            </button>
            
            <div class="mt-4 text-center">
                <a href="../index.php" class="text-pink-500 text-sm hover:text-pink-600">← Kembali ke Website</a>
            </div>
        </form>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script>AOS.init();</script>
</body>
</html>
