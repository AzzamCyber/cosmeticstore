<?php
session_start();
require_once 'includes/db.php';
if(!isset($_SESSION['user_id'])){
    header('Location: login.php');
    exit();
}
$user_id = $_SESSION['user_id'];
$user = mysqli_fetch_assoc(mysqli_query($db, "SELECT * FROM users WHERE id=$user_id"));

$success = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = mysqli_real_escape_string($db, $_POST['nama'] ?? '');
    $email = mysqli_real_escape_string($db, $_POST['email'] ?? '');

    if($nama && $email){
        mysqli_query($db, "UPDATE users SET name='$nama', email='$email' WHERE id=$user_id");
        $user = mysqli_fetch_assoc(mysqli_query($db, "SELECT * FROM users WHERE id=$user_id"));
        $success = true;
    } else {
        $success = false;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Akun | La Belle Peau</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body{background:linear-gradient(120deg,#FFD6E0 65%,#FFF0F3 100%);font-family:'Poppins',Arial,sans-serif;}
        .sidebar-item.active{background-color:#ec4899!important;color:#fff!important;}
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
            <a href="dashboard_client.php" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-pink-500 hover:bg-pink-100 transition">
                🏠 <span>Dashboard</span>
            </a>
            <a href="orders_client.php" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-pink-500 hover:bg-pink-100 transition">
                🛒 <span>Pesanan</span>
            </a>
            <a href="profile_client.php" class="sidebar-item active flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-pink-500 bg-pink-100 transition">
                🙍‍♂️ <span>Data Akun</span>
            </a>
            <form method="post" action="logout.php"><button type="submit" class="w-full flex items-center gap-3 px-4 py-3 mt-10 bg-pink-400 text-white rounded-xl font-semibold hover:bg-pink-600 transition">🚪 <span>Logout</span></button></form>
        </nav>
    </aside>
    <!-- Main Content -->
    <main class="flex-1 w-full px-3 pt-7 pb-3 flex flex-col justify-center items-center">
        <h2 class="text-pink-600 font-bold text-2xl mb-2 text-center">Profil Akun</h2>
        <!-- Card User Info -->
        <div class="max-w-md w-full bg-white/90 rounded-xl shadow-lg mb-6 p-5 flex flex-col items-center">
            <img src="assets/img/avatar.png" alt="Avatar" class="w-20 h-20 rounded-full shadow-lg border-4 border-pink-100 bg-white mb-2">
            <div class="font-bold text-pink-600 text-lg mb-1"><?= htmlspecialchars($user['name'] ?? '') ?></div>
            <div class="text-pink-400 mb-2 text-sm"><?= htmlspecialchars($user['email'] ?? '') ?></div>
            <div class="text-xs bg-pink-50 rounded-lg px-3 py-1 text-pink-600 font-semibold mb-2 inline-block">Member sejak <?= date('d M Y', strtotime($user['created_at'] ?? date('Y-m-d'))) ?></div>
        </div>
        <!-- Form Edit Profile -->
        <div class="max-w-md w-full bg-pink-50 rounded-xl shadow p-5 mb-8">
            <?php if($success===true): ?>
                <div class="mb-4 px-4 py-3 bg-green-100 text-green-700 rounded-lg text-sm text-center font-semibold">Data berhasil diupdate!</div>
            <?php elseif($success===false): ?>
                <div class="mb-4 px-4 py-3 bg-rose-100 text-rose-700 rounded-lg text-sm text-center font-semibold">Data gagal diupdate, pastikan semua diisi!</div>
            <?php endif; ?>
            <form method="POST" class="flex flex-col gap-3">
                <label class="text-pink-600 font-medium">Nama Lengkap</label>
                <input type="text" name="nama" value="<?= htmlspecialchars($user['name'] ?? '') ?>" required class="rounded-lg px-4 py-2 border border-pink-100 bg-white focus:outline-pink-500">
                <label class="text-pink-600 font-medium">Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required class="rounded-lg px-4 py-2 border border-pink-100 bg-white focus:outline-pink-500">
                <button type="submit" class="mt-4 w-full px-4 py-2 bg-pink-500 text-white font-bold rounded-lg shadow hover:bg-pink-600 transition">Update Data Akun</button>
            </form>
        </div>
    </main>
    <!-- Bottom nav mobile -->
    <nav class="bottom-nav md:hidden text-pink-500">
        <a href="dashboard_client.php" class="flex flex-col items-center justify-center">
            🏠 <span class="text-[13px] leading-tight">Dashboard</span>
        </a>
        <a href="orders_client.php" class="flex flex-col items-center justify-center">
            🛒 <span class="text-[13px] leading-tight">Pesanan</span>
        </a>
        <a href="profile_client.php" class="active flex flex-col items-center justify-center">
            🙍‍♂️ <span class="text-[13px] leading-tight font-bold">Akun</span>
        </a>
        <a href="logout.php" class="flex flex-col items-center justify-center">
            🚪 <span class="text-[13px] leading-tight">Logout</span>
        </a>
    </nav>
</div>
</body>
</html>
