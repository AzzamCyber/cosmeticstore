<?php require_once 'includes/header.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login / Register | La Belle Peau Skincare</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" />
    <style>
    body { background: linear-gradient(120deg,#FFF0F3,#FFD6E0); font-family: 'Poppins', Arial, sans-serif; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center py-6">
    <div class="w-full max-w-md mx-auto px-3">
        <div class="bg-pink-100/70 backdrop-blur-xl rounded-2xl shadow-2xl p-7 flex flex-col items-center" data-aos="fade-up">
            <img src="assets/img/logo.png" alt="logo" class="w-14 h-14 mb-2 drop-shadow-lg" />
            <div class="flex justify-center items-center gap-3 mb-5 w-full">
                <button id="tabLogin" class="font-bold text-pink-500 px-5 py-2 rounded-xl bg-white shadow mb-1 active"
                    onclick="showTab('login')">Login</button>
                <button id="tabRegister" class="font-bold text-gray-400 px-5 py-2 rounded-xl hover:bg-white hover:text-pink-500 transition mb-1"
                    onclick="showTab('register')">Register</button>
            </div>
            <!-- LOGIN FORM -->
            <form id="formLogin" method="POST" action="login_process.php" class="w-full flex flex-col gap-3" autocomplete="on">
                <label class="text-pink-600 text-sm mb-1 font-semibold">Email</label>
                <input type="email" name="email" required class="w-full py-2 px-4 rounded-xl bg-white/60 border border-pink-300 focus:outline-pink-400 font-light mb-2" placeholder="Email">
                <label class="text-pink-600 text-sm mb-1 font-semibold">Password</label>
                <input type="password" name="password" required class="w-full py-2 px-4 rounded-xl bg-white/60 border border-pink-300 focus:outline-pink-400 font-light mb-4" placeholder="Password">
                <button type="submit" class="w-full py-2 bg-pink-400 text-white font-bold rounded-xl hover:bg-pink-600 transition">Login</button>
            </form>
            <!-- REGISTER FORM -->
            <form id="formRegister" method="POST" action="register_process.php" class="w-full flex flex-col gap-3 hidden" autocomplete="on">
                <label class="text-pink-600 text-sm mb-1 font-semibold">Username</label>
                <input type="text" name="username" required class="w-full py-2 px-4 rounded-xl bg-white/60 border border-pink-300 focus:outline-pink-400 font-light mb-2" placeholder="Username">
                <label class="text-pink-600 text-sm mb-1 font-semibold">Email</label>
                <input type="email" name="email" required class="w-full py-2 px-4 rounded-xl bg-white/60 border border-pink-300 focus:outline-pink-400 font-light mb-2" placeholder="Email">
                <label class="text-pink-600 text-sm mb-1 font-semibold">Password</label>
                <input type="password" name="password" required class="w-full py-2 px-4 rounded-xl bg-white/60 border border-pink-300 focus:outline-pink-400 font-light mb-4" placeholder="Password">
                <button type="submit" class="w-full py-2 bg-pink-400 text-white font-bold rounded-xl hover:bg-pink-600 transition">Register</button>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script>AOS.init();</script>
    <script>
    // Simple Tab Switch
    function showTab(tab){
        document.getElementById('formLogin').style.display = (tab=='login') ? 'flex' : 'none';
        document.getElementById('formRegister').style.display = (tab=='register') ? 'flex' : 'none';
        document.getElementById('tabLogin').classList.toggle('text-pink-500', tab=='login');
        document.getElementById('tabLogin').classList.toggle('bg-white', tab=='login');
        document.getElementById('tabLogin').classList.toggle('text-gray-400', tab=='register');
        document.getElementById('tabRegister').classList.toggle('text-pink-500', tab=='register');
        document.getElementById('tabRegister').classList.toggle('bg-white', tab=='register');
        document.getElementById('tabRegister').classList.toggle('text-gray-400', tab=='login');
    }
    // Default tab
    showTab('login');
    </script>
</body>
</html>
