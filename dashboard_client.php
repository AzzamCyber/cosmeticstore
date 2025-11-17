<?php
session_start();
require_once 'includes/db.php';
if(!isset($_SESSION['user_id'])){
    header('Location: login.php');
    exit();
}
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'] ?? '';
$user = mysqli_fetch_assoc(mysqli_query($db, "SELECT * FROM users WHERE id=$user_id"));

$total_orders = mysqli_fetch_assoc(mysqli_query($db, "SELECT COUNT(*) as c FROM orders WHERE user_id=$user_id"))['c'];
$total_spending = mysqli_fetch_assoc(mysqli_query($db, "SELECT SUM(total) as total FROM orders WHERE user_id=$user_id AND status='Completed'"))['total'] ?? 0;

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Client | La Belle Peau</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body{background:linear-gradient(120deg,#FFD6E0 65%,#FFF0F3 100%);font-family:'Poppins',Arial,sans-serif;}
        /* Bottom nav style mobile */
        .bottom-nav{
            position:fixed;left:0;right:0;bottom:0;z-index:50;background:rgba(255,255,255,.98);box-shadow:0 1px 24px rgba(220,20,100,.09);
            border-top:1.5px solid #f0a4b2;display:flex;justify-content:space-around;padding:7px 0;
        }
        .bottom-nav a{flex:1;text-align:center;color:#ec4899;font-weight:600;padding:4px 0;font-size:16px;}
        .bottom-nav a.active{color:#fff;background:#ec4899;border-radius:17px;font-weight:bold;}
        @media(min-width:768px){
            .bottom-nav{display:none!important;}
            .side-nav{display:block;}
        }
        @media(max-width:767px){
            .side-nav{display:none!important;}
            main{padding-bottom:55px;}
        }
    </style>
</head>
<body>
<div class="flex min-h-screen w-full bg-gradient-to-br from-pink-200 via-pink-50 to-white">
    <!-- Sidebar Desktop -->
    <aside class="side-nav w-60 bg-white/70 backdrop-blur-xl shadow-lg min-h-screen hidden md:flex flex-col py-8 px-2">
        <a href="index.php" class="flex items-center gap-2 px-4 py-2 bg-pink-500 hover:bg-pink-600 text-white rounded-lg font-bold shadow mb-5">
            🔙 <span>Kembali ke Website</span>
        </a>
        <nav class="flex flex-col space-y-2">
            <a href="dashboard_client.php" class="sidebar-item active flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-pink-500 bg-pink-100 transition">
                🏠 <span>Dashboard</span>
            </a>
            <a href="orders_client.php" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-pink-500 hover:bg-pink-100 transition">
                🛒 <span>Pesanan</span>
            </a>
            <a href="profile_client.php" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-pink-500 hover:bg-pink-100 transition">
                🙍‍♂️ <span>Data Akun</span>
            </a>
            <form method="post" action="logout.php"><button type="submit" class="w-full flex items-center gap-3 px-4 py-3 mt-10 bg-pink-400 text-white rounded-xl font-semibold hover:bg-pink-600 transition">🚪 <span>Logout</span></button></form>
        </nav>
    </aside>
    <!-- Main Content -->
    <main class="flex-1 w-full px-3 pt-5 pb-3 flex flex-col justify-center items-center">
        <!-- Hero/Status area -->
        <div class="w-full max-w-xl mx-auto rounded-2xl shadow-lg bg-white/95 p-5 mb-6 mt-2 flex flex-col md:flex-row items-center gap-4">
            <div class="flex items-center justify-center">
                <img src="assets/img/avatar.png" alt="Avatar" class="w-20 h-20 md:w-24 md:h-24 rounded-full border-4 border-pink-200 shadow-lg bg-white">
            </div>
            <div class="flex flex-col justify-center items-center md:items-start w-full">
                <div class="font-bold text-pink-600 text-xl md:text-2xl mb-1"><?= htmlspecialchars($user['name'] ?? $username) ?></div>
                <div class="text-pink-400 text-sm mb-1"><?= htmlspecialchars($user['email'] ?? '-') ?></div>
                <div class="text-xs bg-pink-50 rounded-lg px-3 py-1 text-pink-600 font-semibold inline-block">Member sejak <?= date('d M Y', strtotime($user['created_at'] ?? date('Y-m-d'))) ?></div>
            </div>
        </div>
        <!-- Statistik -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 w-full max-w-xl mb-8">
            <div class="bg-pink-50/80 rounded-xl shadow p-4 text-center">
                <div class="font-bold text-lg text-pink-600"><?= $total_orders ?></div>
                <div class="text-xs text-pink-400 mt-1 font-semibold">Total Pesanan</div>
            </div>
            <div class="bg-pink-50/80 rounded-xl shadow p-4 text-center">
                <div class="font-bold text-lg text-rose-500">Rp<?= number_format($total_spending,0,',','.') ?></div>
                <div class="text-xs text-pink-400 mt-1 font-semibold">Total Belanja</div>
            </div>
            <div class="bg-pink-50/80 rounded-xl shadow p-4 text-center flex flex-col justify-center items-center">
                <div class="font-semibold text-pink-600">⭐</div>
                <div class="text-xs text-pink-400 mt-1 font-semibold">Loyalty</div>
            </div>
            <div class="bg-pink-50/80 rounded-xl shadow p-4 text-center flex flex-col justify-center items-center">
                <div class="font-semibold text-pink-600">🎁</div>
                <div class="text-xs text-pink-400 mt-1 font-semibold">Promo</div>
            </div>
        </div>
        <!-- Shortcut Card -->
        <div class="w-full flex flex-col md:flex-row gap-4 max-w-xl mb-10">
            <a href="orders_client.php" class="flex-1 flex items-center gap-4 px-6 py-4 bg-pink-400 hover:bg-pink-500 text-white rounded-xl font-bold shadow-md text-lg transition">
                🛍️ <span>Lihat Pesanan</span>
            </a>
            <a href="profile_client.php" class="flex-1 flex items-center gap-4 px-6 py-4 bg-pink-100 hover:bg-pink-200 text-pink-600 rounded-xl font-bold shadow-md text-lg transition">
                🙍‍♂️ <span>Data Akun</span>
            </a>
        </div>
        <!-- Info / tips / promo -->
        <div class="w-full max-w-xl mx-auto mb-5 px-5 py-4 bg-gradient-to-r from-pink-100 via-white to-pink-50 rounded-xl shadow">
            <div class="text-pink-600 font-bold text-lg mb-1">Tips & Promo</div>
            <ul class="text-sm text-pink-700 list-disc ml-3">
                <li>Belanja lebih hemat dengan kode voucher LA-BELLE-10</li>
                <li>Dapatkan loyalty point untuk setiap transaksi completed</li>
                <li>Chat CS via WhatsApp untuk help & konsultasi produk</li>
            </ul>
        </div>
    </main>
    <!-- Bottom nav mobile -->
    <nav class="bottom-nav md:hidden text-pink-500">
        <a href="dashboard_client.php" class="active flex flex-col items-center justify-center">
            🏠 <span class="text-[13px] leading-tight font-bold">Dashboard</span>
        </a>
        <a href="orders_client.php" class="flex flex-col items-center justify-center">
            🛒 <span class="text-[13px] leading-tight">Pesanan</span>
        </a>
        <a href="profile_client.php" class="flex flex-col items-center justify-center">
            🙍‍♂️ <span class="text-[13px] leading-tight">Akun</span>
        </a>
        <a href="logout.php" class="flex flex-col items-center justify-center">
            🚪 <span class="text-[13px] leading-tight">Logout</span>
        </a>
    </nav>
</div>
</body>
</html>
