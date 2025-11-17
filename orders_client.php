<?php
session_start();
require_once 'includes/db.php';
if(!isset($_SESSION['user_id'])){
    header('Location: login.php');
    exit();
}
$user_id = $_SESSION['user_id'];

$q_orders = mysqli_query($db, "SELECT o.*, pm.name as payment_name 
    FROM orders o 
    LEFT JOIN payment_methods pm ON o.payment_method = pm.id
    WHERE o.user_id = $user_id 
    ORDER BY o.created_at DESC");

$total_orders = mysqli_num_rows($q_orders);
$total_spending = 0;
foreach($q_orders as $row){ if($row['status']=='Completed') $total_spending += $row['total']; }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pesanan Anda | La Belle Peau Skincare</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body{background:linear-gradient(120deg,#FFD6E0 65%,#FFF0F3 100%);font-family:'Poppins',Arial,sans-serif;}
        .badge{padding:4px 13px;font-size:13px;border-radius:999px;}
        .badge.Pending{background:#FDE2E4;color:#a54269;}
        .badge.Processing{background:#FFF0F3;color:#c24141;}
        .badge.Completed{background:#bcebc6;color:#208e3b;}
        .badge.Cancelled{background:#ffe0e0;color:#a14141;}
        .table-wrap{overflow-x:auto;}
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
            <a href="orders_client.php" class="sidebar-item active flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-pink-500 bg-pink-100 transition">
                🛒 <span>Pesanan</span>
            </a>
            <a href="profile_client.php" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-pink-500 hover:bg-pink-100 transition">
                🙍‍♂️ <span>Data Akun</span>
            </a>
            <form method="post" action="logout.php"><button type="submit" class="w-full flex items-center gap-3 px-4 py-3 mt-10 bg-pink-400 text-white rounded-xl font-semibold hover:bg-pink-600 transition">🚪 <span>Logout</span></button></form>
        </nav>
    </aside>
    <!-- Main Content -->
    <main class="flex-1 w-full px-3 pt-5 pb-3 flex flex-col items-center">
        <h2 class="text-pink-600 font-bold text-2xl mb-2 text-center">Daftar Pesanan Anda</h2>
        <!-- Card Statistik -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 w-full max-w-xl mb-8 mt-1">
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
        <!-- Table Modern -->
        <div class="table-wrap bg-white/85 rounded-xl shadow-lg p-4 mb-6 w-full max-w-3xl">
            <table class="w-full text-xs md:text-base rounded-xl overflow-hidden">
                <thead>
                    <tr class="bg-pink-100/80 font-bold text-pink-500 rounded-xl">
                        <th class="py-2 px-2">ID</th>
                        <th class="py-2 px-2">Tanggal</th>
                        <th class="py-2 px-2">Pembayaran</th>
                        <th class="py-2 px-2">Alamat</th>
                        <th class="py-2 px-2">Total</th>
                        <th class="py-2 px-2">Status</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($q_orders as $row): ?>
                    <tr class="border-b border-pink-100 bg-white hover:bg-pink-50 transition">
                        <td class="py-2 px-2 font-bold text-pink-600">#<?= $row['id'] ?></td>
                        <td class="py-2 px-2"><?= date('d/m/Y H:i',strtotime($row['created_at'])) ?></td>
                        <td class="py-2 px-2 text-pink-500"><?= htmlspecialchars($row['payment_name']) ?: '-' ?></td>
                        <td class="py-2 px-2"><?= htmlspecialchars($row['address']) ?></td>
                        <td class="py-2 px-2 text-rose-500 font-bold">Rp<?= number_format($row['total'],0,',','.') ?></td>
                        <td class="py-2 px-2"><span class="badge <?= $row['status'] ?>"><?= $row['status'] ?></span></td>
                    </tr>
                <?php endforeach ?>
                </tbody>
            </table>
        </div>
        <!-- Info/promo -->
        <div class="w-full max-w-xl mx-auto mb-4 px-5 py-4 bg-gradient-to-r from-pink-100 via-white to-pink-50 rounded-xl shadow">
            <div class="text-pink-600 font-bold text-lg mb-1">Perhatian!</div>
            <ul class="text-sm text-pink-700 list-disc ml-3">
                <li>Untuk pesanan "Pending", silakan lakukan pembayaran sesuai instruksi yang tertera di detail pesanan.</li>
                <li>Promo free shipping untuk transaksi di atas Rp200.000 selama bulan ini!</li>
            </ul>
        </div>
    </main>
    <!-- Bottom nav mobile -->
    <nav class="bottom-nav md:hidden text-pink-500">
        <a href="dashboard_client.php" class="flex flex-col items-center justify-center">
            🏠 <span class="text-[13px] leading-tight">Dashboard</span>
        </a>
        <a href="orders_client.php" class="active flex flex-col items-center justify-center">
            🛒 <span class="text-[13px] leading-tight font-bold">Pesanan</span>
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
