<?php
session_start();

// Cek login
if(!isset($_SESSION['admin_logged_in'])){
    header('Location: login.php');
    exit();
}

require_once '../includes/db.php';

// Ambil statistik
$total_products = mysqli_fetch_assoc(mysqli_query($db, "SELECT COUNT(*) as count FROM products"))['count'];
$total_orders = mysqli_fetch_assoc(mysqli_query($db, "SELECT COUNT(*) as count FROM orders"))['count'];
$total_revenue = mysqli_fetch_assoc(mysqli_query($db, "SELECT SUM(total) as total FROM orders WHERE status='Completed'"))['total'] ?? 0;
$total_users = mysqli_fetch_assoc(mysqli_query($db, "SELECT COUNT(*) as count FROM users"))['count'];

// Logout
if(isset($_POST['logout'])){
    session_destroy();
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard | La Belle Peau</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" />
    <style>
        body{
            background:linear-gradient(120deg,#FFD6E0,#FFF0F3);
            font-family:'Poppins',Arial,sans-serif;
            overflow-x:hidden;
        }
        .sidebar-item.active{
            background-color:#ec4899!important;
            color:#fff!important;
        }
    </style>
</head>
<body class="min-h-screen">
    <div class="flex flex-col md:flex-row">
        <!-- SIDEBAR -->
        <aside class="w-full md:w-64 bg-white/50 backdrop-blur-xl shadow-lg md:min-h-screen">
            <div class="p-6">
                <div class="flex items-center gap-3 mb-8">
                    <img src="../assets/img/logo.png" alt="Logo" class="w-12 h-12 rounded-xl shadow">
                    <div>
                        <h1 class="text-pink-500 font-bold text-lg">La Belle Peau</h1>
                        <p class="text-pink-400 text-xs">Admin Panel</p>
                    </div>
                </div>
                
                <nav class="space-y-3">
                    <a href="dashboard.php" class="sidebar-item active flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-pink-500 hover:bg-pink-100 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path></svg>
                        <span class="hidden md:block">Dashboard</span>
                    </a>
                    <a href="manage_products.php" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-pink-500 hover:bg-pink-100 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"></path></svg>
                        <span class="hidden md:block">Produk</span>
                    </a>
                    <a href="manage_orders.php" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-pink-500 hover:bg-pink-100 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        <span class="hidden md:block">Pesanan</span>
                    </a>
                    <a href="settings.php" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-pink-500 hover:bg-pink-100 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <span class="hidden md:block">Settings</span>
                    </a>
                </nav>
                
                <form method="post" class="mt-8">
                    <button type="submit" name="logout" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-red-400 text-white rounded-xl font-semibold hover:bg-red-500 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        <span class="hidden md:block">Logout</span>
                    </button>
                </form>
            </div>
        </aside>
        
        <!-- MAIN CONTENT -->
        <main class="flex-1 p-6 md:p-10">
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-pink-600 mb-2">Dashboard</h2>
                <p class="text-pink-500">Selamat datang, <?= htmlspecialchars($_SESSION['admin_username']) ?>!</p>
            </div>
            
            <!-- STATISTIK CARDS -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-10" data-aos="fade-up">
                <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg p-6 text-center">
                    <div class="text-3xl font-bold text-pink-500 mb-2"><?= $total_products ?></div>
                    <div class="text-pink-400 font-semibold">Total Produk</div>
                </div>
                <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg p-6 text-center">
                    <div class="text-3xl font-bold text-pink-500 mb-2"><?= $total_orders ?></div>
                    <div class="text-pink-400 font-semibold">Total Pesanan</div>
                </div>
                <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg p-6 text-center">
                    <div class="text-2xl font-bold text-pink-500 mb-2">Rp<?= number_format($total_revenue,0,',','.') ?></div>
                    <div class="text-pink-400 font-semibold">Pendapatan</div>
                </div>
                <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg p-6 text-center">
                    <div class="text-3xl font-bold text-pink-500 mb-2"><?= $total_users ?></div>
                    <div class="text-pink-400 font-semibold">Total User</div>
                </div>
            </div>
            
            <!-- QUICK ACTIONS -->
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg p-6" data-aos="fade-up" data-aos-delay="100">
                <h3 class="text-xl font-bold text-pink-600 mb-6">Quick Actions</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="manage_products.php" class="flex items-center gap-3 p-4 bg-pink-100 rounded-xl hover:bg-pink-200 transition">
                        <svg class="w-8 h-8 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        <div>
                            <div class="font-semibold text-pink-600">Tambah Produk</div>
                            <div class="text-sm text-pink-500">Upload produk baru</div>
                        </div>
                    </a>
                    <a href="manage_orders.php" class="flex items-center gap-3 p-4 bg-pink-100 rounded-xl hover:bg-pink-200 transition">
                        <svg class="w-8 h-8 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        <div>
                            <div class="font-semibold text-pink-600">Kelola Pesanan</div>
                            <div class="text-sm text-pink-500">Update status pesanan</div>
                        </div>
                    </a>
                    <a href="settings.php" class="flex items-center gap-3 p-4 bg-pink-100 rounded-xl hover:bg-pink-200 transition">
                        <svg class="w-8 h-8 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <div>
                            <div class="font-semibold text-pink-600">Website Settings</div>
                            <div class="text-sm text-pink-500">Konfigurasi website</div>
                        </div>
                    </a>
                </div>
            </div>
        </main>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script>AOS.init();</script>
</body>
</html>
