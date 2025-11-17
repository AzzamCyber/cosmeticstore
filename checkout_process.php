<?php
session_start();
require_once 'includes/db.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Proses order POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = mysqli_real_escape_string($db, $_POST['nama'] ?? '');
    $alamat = mysqli_real_escape_string($db, $_POST['alamat'] ?? '');
    $phone = mysqli_real_escape_string($db, $_POST['phone'] ?? '');
    $payment_method_id = intval($_POST['payment_method_id'] ?? 0);

    if ($nama && $alamat && $phone && $payment_method_id) {
        // Ambil keranjang user
        $cart = mysqli_query($db, "SELECT c.*, p.name, p.price FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = $user_id");
        $total = 0; $items = [];
        while($r = mysqli_fetch_assoc($cart)){
            $total += $r['price'] * $r['quantity'];
            $items[] = $r;
        }
        // Simpan order
        mysqli_query($db, "INSERT INTO orders (user_id, total, address, payment_method, phone, status, created_at) VALUES 
            ($user_id, $total, '$alamat', $payment_method_id, '$phone', 'Pending', NOW())");
        $order_id = mysqli_insert_id($db);
        // Simpan item
        foreach($items as $i){
            mysqli_query($db,"INSERT INTO order_items (order_id, product_id, quantity, price) VALUES
                ($order_id, {$i['product_id']}, {$i['quantity']}, {$i['price']})");
        }
        // Hapus keranjang
        mysqli_query($db,"DELETE FROM cart WHERE user_id=$user_id");
        // Ambil payment method
        $pay = mysqli_fetch_assoc(mysqli_query($db,"SELECT * FROM payment_methods WHERE id=$payment_method_id"));
        $orderOk = true;
    } else {
        $orderOk = false;
    }
} else {
    // jika page diakses langsung, return ke index
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Order Sukses | La Belle Peau Skincare</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body{background:linear-gradient(120deg,#FFD6E0,#FFF0F3);min-height:100vh;font-family:'Poppins',Arial,sans-serif;}
        .card{background:rgba(255,255,255,0.82);backdrop-filter:blur(14px);}
        @media(max-width:767px){.order-detail{padding:18px 3px;}}
        @media(min-width:768px){.order-detail{padding:26px 18px;}}
    </style>
</head>
<body class="min-h-screen flex flex-col">
<?php require_once 'includes/header.php'; ?>
<div class="max-w-2xl mx-auto flex-1 px-2 order-detail flex flex-col items-center mt-10 mb-6">
    <?php if(!empty($orderOk)): ?>
        <div class="card w-full rounded-2xl p-6 shadow-xl mt-5 mb-8 text-center flex flex-col items-center">
            <div class="text-green-500 text-3xl mb-2">✔️</div>
            <h2 class="text-xl font-extrabold text-pink-600 mb-2">Checkout Sukses!</h2>
            <div class="text-pink-400 text-sm mb-2">Order ID: <b>#<?= $order_id ?></b></div>
            <div class="mb-4 text-sm text-gray-600">Terima kasih, pesanan Anda sudah berhasil dibuat.</div>
            <!-- Produk -->
            <div class="bg-pink-50 rounded-xl px-4 py-3 mb-4 text-left w-full max-w-md mx-auto">
                <div class="font-bold text-pink-500 mb-2">Detail Pesanan:</div>
                <?php foreach($items as $itm): ?>
                    <div class="flex justify-between border-b border-pink-200 py-1">
                        <span><?= htmlspecialchars($itm['name']) ?> <span class="text-[10px] text-pink-400">(x<?= $itm['quantity'] ?>)</span></span>
                        <span class="font-bold text-pink-600">Rp<?= number_format($itm['price']*$itm['quantity'],0,',','.') ?></span>
                    </div>
                <?php endforeach ?>
                <div class="flex justify-between font-bold text-lg text-pink-700 mt-2">
                    <span>Total</span>
                    <span>Rp<?= number_format($total,0,',','.') ?></span>
                </div>
            </div>
            <!-- Shipping & payment -->
            <div class="w-full max-w-md mx-auto text-left mb-2">
                <div class="mb-2"><b>Nama Penerima:</b> <?= htmlspecialchars($nama) ?></div>
                <div class="mb-2"><b>Alamat:</b> <?= htmlspecialchars($alamat) ?></div>
                <div class="mb-2"><b>No. HP:</b> <?= htmlspecialchars($phone) ?></div>
            </div>
            <div class="p-4 bg-white/70 rounded-xl shadow w-full max-w-md mx-auto mb-1">
                <div class="font-bold text-pink-500">Metode Pembayaran:</div>
                <div class="mb-1"><b><?= htmlspecialchars($pay['name']) ?></b></div>
                <div class="mb-2 text-xs text-gray-600"><?= htmlspecialchars($pay['instructions']) ?></div>
                <?php if($pay['bank_name']): ?><div><b>Bank:</b> <?= htmlspecialchars($pay['bank_name']) ?></div><?php endif; ?>
                <?php if($pay['account_number']): ?><div><b>No:</b> <?= htmlspecialchars($pay['account_number']) ?></div><?php endif; ?>
                <?php if($pay['account_name']): ?><div><b>Atas Nama:</b> <?= htmlspecialchars($pay['account_name']) ?></div><?php endif; ?>
            </div>
            <div class="mt-6 mb-3 text-pink-500 text-sm">Silakan lanjut pembayaran sesuai instruksi di atas.</div>
            <a href="orders_client.php" class="mt-2 px-6 py-2 bg-pink-400 text-white font-bold rounded-lg shadow hover:bg-pink-600 transition">Lihat/Status Pesanan</a>
            <a href="index.php" class="mt-3 px-3 py-2 text-pink-600 underline text-sm">Kembali ke Halaman Utama</a>
        </div>
    <?php else: ?>
        <div class="bg-white/80 border border-pink-200 rounded-2xl p-8 shadow-xl mt-12 text-center">
            <div class="text-3xl text-pink-500 mb-2">❌</div>
            <h2 class="text-xl font-extrabold text-pink-400 mb-2">Gagal Checkout</h2>
            <div class="mb-2 text-gray-600">Pastikan semua data diisi & keranjang tidak kosong.</div>
            <a href="checkout.php" class="mt-2 px-6 py-2 bg-pink-300 text-white font-bold rounded-lg shadow hover:bg-pink-600 transition">Kembali ke Checkout</a>
        </div>
    <?php endif ?>
</div>
<footer class="mt-auto text-center py-7 text-pink-700 bg-white/40 backdrop-blur-xl rounded-t-2xl shadow-lg">
    <div class="text-lg font-medium flex flex-col items-center">
        <span>© <?= date("Y") ?> <b>La Belle Peau Skincare</b></span>
        <span class="text-pink-500 font-light text-base">Your Secret to Effortless Radiance</span>
    </div>
</footer>
</body>
</html>
