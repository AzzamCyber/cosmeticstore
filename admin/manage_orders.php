<?php
session_start();
require_once '../includes/db.php';

if(!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}

// Handle update status order
if(isset($_POST['update_status'])){
    $oid = intval($_POST['order_id']);
    $status = mysqli_real_escape_string($db, $_POST['status']);
    mysqli_query($db, "UPDATE orders SET status='$status' WHERE id=$oid");
    header('Location: manage_orders.php');
    exit();
}

// Query orders JOIN user & order_item & product
$res_orders = mysqli_query($db,
    "SELECT o.*, u.username, u.email
     FROM orders o
     JOIN users u ON o.user_id = u.id
     ORDER BY o.created_at DESC");

// Ambil mapping order item per order
$order_items = [];
$q_items = mysqli_query($db, "
    SELECT oi.*, p.name 
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
");
while($oi = mysqli_fetch_assoc($q_items)){
    $order_items[$oi['order_id']][] = $oi;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Pesanan | Admin La Belle Peau</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" />
    <style>
        body {background:linear-gradient(120deg,#FFD6E0,#FFF0F3);font-family:'Poppins',Arial,sans-serif;overflow-x:hidden;}
        .sidebar-item.active{background-color:#ec4899!important;color:#fff!important;}
        .badge {padding:4px 13px;border-radius:999px;font-size:12px;}
        .badge.Pending{background:#FDE2E4;color:#a54269;}
        .badge.Processing{background:#FFF0F3;color:#c24141;}
        .badge.Completed{background:#bcebc6;color:#208e3b;}
        .badge.Cancelled{background:#ffe0e0;color:#a14141;}
    </style>
</head>
<body>
    <div class="flex flex-col md:flex-row">

        <!-- SIDEBAR (copy dari dashboard.php) -->
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
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path></svg>
                        <span class="hidden md:block">Dashboard</span>
                    </a>
                    <a href="manage_products.php" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-pink-500 hover:bg-pink-100 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"></path></svg>
                        <span class="hidden md:block">Produk</span>
                    </a>
                    <a href="manage_orders.php" class="sidebar-item active flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-pink-500 hover:bg-pink-100 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        <span class="hidden md:block">Pesanan</span>
                    </a>
                    <a href="settings.php" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-pink-500 hover:bg-pink-100 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <span class="hidden md:block">Settings</span>
                    </a>
                </nav>
            </div>
        </aside>

        <!-- MAIN CONTENT PESANAN -->
        <main class="flex-1 py-8 px-3 md:px-10">
            <section class="mb-10 bg-white/60 backdrop-blur-xl rounded-2xl shadow-xl p-3 md:p-7">
                <h2 class="text-pink-600 font-bold text-xl mb-6">Daftar Pesanan</h2>
                <div class="overflow-x-auto">
                <table class="min-w-[700px] w-full table-fixed text-xs md:text-sm">
                    <thead>
                        <tr class="bg-pink-100/80 font-bold text-pink-500">
                            <th class="p-2 w-20">No</th>
                            <th class="p-2 w-16">ID</th>
                            <th class="p-2 w-32">User</th>
                            <th class="p-2 w-64">Barang</th>
                            <th class="p-2 w-24">Total</th>
                            <th class="p-2 w-28">Alamat</th>
                            <th class="p-2 w-20">Metode</th>
                            <th class="p-2 w-32">Waktu</th>
                            <th class="p-2 w-24">Status</th>
                            <th class="p-2 w-18">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                    $i=1;
                    while($o = mysqli_fetch_assoc($res_orders)): 
                        $items = isset($order_items[$o['id']]) ? $order_items[$o['id']] : [];
                        $produk = join(', ', array_map(function($item){
                            return htmlspecialchars($item['name']).' ('.$item['quantity'].')';
                        },$items));
                        ?>
                        <tr class="border-b border-pink-100 text-gray-700 bg-white hover:bg-pink-100/80 transition">
                            <td class="p-2"><?= $i++ ?></td>
                            <td class="p-2"><?= $o['id'] ?></td>
                            <td class="p-2"><?= htmlspecialchars($o['username']) ?><br>
                                <span class="text-gray-500 text-xs"><?= htmlspecialchars($o['email']) ?></span>
                            </td>
                            <td class="p-2"><?= $produk ?></td>
                            <td class="p-2 text-pink-600 font-bold">Rp<?= number_format($o['total'],0,',','.') ?></td>
                            <td class="p-2 max-w-[90px] overflow-x-auto"><?= htmlspecialchars($o['address']) ?></td>
                            <td class="p-2"><?= htmlspecialchars($o['payment_method']) ?></td>
                            <td class="p-2"><?= date('d-m-Y H:i',strtotime($o['created_at'])) ?></td>
                            <td class="p-2">
                                <span class="badge <?= $o['status'] ?>"><?= $o['status'] ?></span>
                            </td>
                            <td class="p-2">
                                <form method="post" class="flex flex-col">
                                    <input type="hidden" name="order_id" value="<?= $o['id'] ?>">
                                    <select name="status" class="border border-pink-300 rounded px-2 py-1 text-xs mb-2">
                                        <option<?= $o['status']=='Pending'?' selected':'' ?>>Pending</option>
                                        <option<?= $o['status']=='Processing'?' selected':'' ?>>Processing</option>
                                        <option<?= $o['status']=='Completed'?' selected':'' ?>>Completed</option>
                                        <option<?= $o['status']=='Cancelled'?' selected':'' ?>>Cancelled</option>
                                    </select>
                                    <button type="submit" name="update_status" class="bg-pink-400 text-white rounded font-bold px-2 py-1 text-xs hover:bg-pink-600 transition">Update</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile ?>
                    </tbody>
                </table>
                </div>
            </section>
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script>AOS.init();</script>
</body>
</html>
