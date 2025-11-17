<?php
session_start();
require_once '../includes/db.php';

if(!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}

// Simpel: simpan setting di 1 tabel (atau bisa juga file .env/.json jika belum ada)
if(!mysqli_query($db,"SHOW TABLES LIKE 'site_settings'")->num_rows)
    mysqli_query($db,"CREATE TABLE IF NOT EXISTS site_settings (
        id INT AUTO_INCREMENT PRIMARY KEY,
        logo VARCHAR(255) DEFAULT NULL,
        tagline VARCHAR(255) DEFAULT '',
        color VARCHAR(12) DEFAULT '#ec4899'
    )");
$cek = mysqli_query($db, "SELECT * FROM site_settings LIMIT 1");
if(mysqli_num_rows($cek)==0){
    mysqli_query($db,"INSERT INTO site_settings (tagline, color) VALUES ('Your Secret to Effortless Radiance','#ec4899')");
}
$setting = mysqli_fetch_assoc(mysqli_query($db,"SELECT * FROM site_settings LIMIT 1"));

// Edit: simpan perubahan
if(isset($_POST['save'])){
    $tagline = mysqli_real_escape_string($db, $_POST['tagline']);
    $color = mysqli_real_escape_string($db, $_POST['color']);
    $logo = $setting['logo'];
    if(isset($_FILES['logo']) && $_FILES['logo']['size']>0){
        $ext = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
        $namafile = 'mainlogo_'.time().'.'.$ext;
        move_uploaded_file($_FILES['logo']['tmp_name'], "../assets/img/".$namafile);
        $logo = $namafile;
    }
    mysqli_query($db,"UPDATE site_settings SET tagline='$tagline', color='$color', logo='$logo' WHERE id=".$setting['id']);
    header('Location: settings.php?saved=1');
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Setting Website | Admin La Belle Peau</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" />
    <style>
        body {background:linear-gradient(120deg,#FFD6E0,#FFF0F3);font-family:'Poppins',Arial,sans-serif;overflow-x:hidden;}
        .sidebar-item.active{background-color:#ec4899!important;color:#fff!important;}
    </style>
</head>
<body>
    <div class="flex flex-col md:flex-row">

        <!-- SIDEBAR konsisten admin -->
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
                    <a href="dashboard.php" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-pink-500 hover:bg-pink-100 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                        </svg><span class="hidden md:block">Dashboard</span>
                    </a>
                    <a href="manage_products.php" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-pink-500 hover:bg-pink-100 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"></path>
                        </svg><span class="hidden md:block">Produk</span>
                    </a>
                    <a href="manage_orders.php" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-pink-500 hover:bg-pink-100 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg><span class="hidden md:block">Pesanan</span>
                    </a>
                    <a href="settings.php" class="sidebar-item active flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-pink-500 hover:bg-pink-100 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg><span class="hidden md:block">Settings</span>
                    </a>
                </nav>
            </div>
        </aside>
        
        <main class="flex-1 py-8 px-3 md:px-10">
            <section class="mb-10 bg-white/60 backdrop-blur-xl rounded-2xl shadow-xl p-4 md:p-8 max-w-lg mx-auto">
                <h2 class="text-pink-600 font-bold text-xl mb-6 text-center">Website Settings</h2>
                <?php if(isset($_GET['saved'])): ?>
                    <div class="bg-green-100 border border-green-200 rounded-xl text-green-700 px-4 py-3 text-center mb-5 text-sm">Settings berhasil disimpan!</div>
                <?php endif ?>
                <form method="post" enctype="multipart/form-data" class="flex flex-col gap-4">
                    <div class="flex flex-col items-center gap-1">
                        <label class="font-semibold text-pink-500 mb-1">Logo Website</label>
                        <img src="../assets/img/<?= $setting['logo']?$setting['logo']:'logo.png' ?>" alt="logo" class="h-20 w-20 rounded-xl border bg-white shadow mb-2 object-contain">
                        <input type="file" name="logo" class="block w-full text-xs py-2" accept="image/*"/>
                    </div>
                    <div>
                        <label class="font-semibold text-pink-500 mb-1">Tagline Website</label>
                        <input type="text" name="tagline" class="w-full py-2 px-4 rounded-xl bg-pink-100/70 border border-pink-300" 
                            value="<?= htmlspecialchars($setting['tagline']) ?>" required maxlength="64">
                    </div>
                    <div>
                        <label class="font-semibold text-pink-500 mb-1">Warna Utama (#HEX)</label>
                        <input type="text" name="color" class="w-full py-2 px-4 rounded-xl bg-pink-100/70 border border-pink-300" 
                            value="<?= htmlspecialchars($setting['color']) ?>" required pattern="^#([A-Fa-f0-9]{3,8})$" maxlength="9">
                    </div>
                    <button type="submit" name="save" 
                        class="py-2 bg-pink-400 text-white font-bold rounded-xl hover:bg-pink-600 transition shadow mt-4">Simpan Setting</button>
                </form>
            </section>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script>AOS.init();</script>
</body>
</html>
