<?php
session_start();
require_once 'includes/db.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$q_cart = mysqli_query($db, "SELECT c.*, p.name, p.price, p.image FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = $user_id");
$cart_items = [];
$total_order = 0;
foreach($q_cart as $row){
    $cart_items[] = $row;
    $total_order += $row['price'] * $row['quantity'];
}

// Payment Methods (DB)
$q_pay = mysqli_query($db, "SELECT * FROM payment_methods");
$methods = [];
foreach($q_pay as $m) $methods[] = $m;

if(count($cart_items) == 0){
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Checkout | La Belle Peau Skincare</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" />
    <style>
        body{font-family:'Poppins',Arial,sans-serif;background:linear-gradient(120deg,#FFD6E0,#FFF0F3);min-height:100vh;}
        .cart-item-edit input[type=number]{width:32px;text-align:center;}
        /* keranjang: overflow pada mobile */
        .cart-box-scroll{max-height:260px;overflow-y:auto;scroll-behavior:smooth;}
        @media(min-width:768px){
            .cart-box-scroll{max-height:none;overflow-y:visible;}
        }
        footer{margin-top:40px;}
    </style>
</head>
<body class="min-h-screen text-gray-800 flex flex-col">

<?php require_once 'includes/header.php'; ?>

<div class="max-w-4xl mx-auto flex-1 px-3 py-7 flex flex-col md:flex-row gap-8 items-start md:items-start">
    <!-- Keranjang Belanja -->
    <aside class="glass w-full md:w-1/2 rounded-2xl shadow-lg p-6 mb-6 md:mb-0">
        <h2 class="text-pink-600 font-bold text-xl mb-4">Keranjang</h2>
        <div class="cart-box-scroll" id="cart-list">
        <?php foreach($cart_items as $item): ?>
            <div class="flex gap-3 mb-4 items-center cart-item-edit" id="cartItem<?= $item['product_id'] ?>">
                <img src="assets/img/product/<?= htmlspecialchars($item['image']) ?>" class="w-16 h-16 rounded-xl border shadow"/>
                <div class="flex-1">
                    <div class="font-bold text-pink-500"><?= htmlspecialchars($item['name']) ?></div>
                    <div class="flex items-center gap-2 mt-2">
                        <button class="px-2 py-1 bg-pink-200 rounded-full font-bold text-pink-600" onclick="editQty(<?= $item['product_id'] ?>,-1)">-</button>
                        <span class="px-2 py-1 bg-white border font-semibold rounded text-pink-600" id="qty<?= $item['product_id'] ?>"><?= $item['quantity'] ?></span>
                        <button class="px-2 py-1 bg-pink-200 rounded-full font-bold text-pink-600" onclick="editQty(<?= $item['product_id'] ?>,1)">+</button>
                    </div>
                </div>
                <div class="font-bold text-rose-400 flex flex-col text-right">
                    Rp<?= number_format($item['price'],0,',','.') ?>
                    <span class="text-sm text-gray-500 font-normal">Qty: <b id="qtyLabel<?= $item['product_id'] ?>"><?= $item['quantity'] ?></b></span>
                </div>
            </div>
        <?php endforeach ?>
        </div>
        <div class="border-t border-pink-300 my-4"></div>
        <div class="flex justify-between font-bold text-pink-600 text-lg">
            <span>Total</span>
            <span id="total_order">Rp<?= number_format($total_order,0,',','.') ?></span>
        </div>
    </aside>
    <!-- Checkout Form -->
    <form class="glass w-full md:w-1/2 rounded-2xl shadow-lg p-6" action="checkout_process.php" method="POST">
        <h2 class="text-pink-600 font-bold text-xl mb-6">Detail Pengiriman & Pembayaran</h2>
        <label class="text-pink-700 mb-2">Nama Penerima</label>
        <input type="text" name="nama" required placeholder="Nama Lengkap"
               class="w-full mb-4 py-2 px-4 rounded-xl bg-pink-100/40 border border-pink-300 focus:outline-pink-400"/>
        <label class="text-pink-700 mb-2">Alamat Lengkap</label>
        <textarea name="alamat" required placeholder="Alamat lengkap"
                  class="w-full mb-4 py-2 px-4 rounded-xl bg-pink-100/40 border border-pink-300"></textarea>
        <label class="text-pink-700 mb-2">Nomor HP</label>
        <input type="text" name="phone" required pattern="\d{10,13}" maxlength="13" placeholder="08xxxxxxxxxx"
               class="w-full mb-4 py-2 px-4 rounded-xl bg-pink-100/40 border border-pink-300 focus:outline-pink-400"/>
        <label class="text-pink-700 mb-2">Metode Pembayaran</label>
        <select name="payment_method_id" id="payment_method_id" required class="w-full mb-6 py-2 px-4 rounded-xl bg-pink-100/40 border border-pink-300">
            <option value="">Pilih metode pembayaran</option>
            <?php foreach($methods as $met): ?>
                <option value="<?= $met['id'] ?>"><?= htmlspecialchars($met['name']) ?></option>
            <?php endforeach ?>
        </select>
        <!-- Payment Info Preview -->
        <div id="payinfo" class="mb-4"></div>
        <button type="submit"
                class="w-full py-3 bg-pink-400 rounded-xl text-white font-bold text-lg shadow-lg hover:bg-pink-600 hover:scale-105 transition mt-2">
            Bayar Sekarang
        </button>
    </form>
</div>

<footer class="mt-auto text-center py-7 text-pink-700 bg-white/40 backdrop-blur-xl rounded-t-2xl shadow-lg">
    <div class="text-lg font-medium flex flex-col items-center">
        <span>© <?= date("Y") ?> <b>La Belle Peau Skincare</b></span>
        <span class="text-pink-500 font-light text-base">Your Secret to Effortless Radiance</span>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
AOS.init();
// Payment metode preview
let paymeta = <?= json_encode($methods) ?>;
document.getElementById('payment_method_id').addEventListener('change', function(){
    let sel=this.value;
    let area = document.getElementById('payinfo');
    let pay = paymeta.find(x=>x.id==sel);
    if(pay){
        area.innerHTML =
        `<div class="bg-pink-50 p-4 rounded-lg border border-pink-300 shadow">
            <div class="font-bold text-pink-600 text-lg mb-1">${pay.name}</div>
            <div class="mb-2 text-gray-800">${pay.instructions}</div>
            ${pay.bank_name ? `<div class="mb-1"><b>Bank:</b> ${pay.bank_name}</div>`:``}
            ${pay.account_number ? `<div><b>No:</b> ${pay.account_number}</div>`:``}
            ${pay.account_name ? `<div><b>Atas Nama:</b> ${pay.account_name}</div>`:``}
        </div>`;
    }else{
        area.innerHTML='';
    }
});

// PLUS/MINUS QTY (with remove)
function editQty(prod_id, delta){
    let qtyEl = document.getElementById('qty'+prod_id);
    let qtyLabel = document.getElementById('qtyLabel'+prod_id);
    let qty = parseInt(qtyEl.innerText) + delta;
    if(qty <= 0) qty = 0;
    fetch('update_cart_ajax.php',{
        method:'POST',
        headers:{'Content-Type':'application/x-www-form-urlencoded'},
        body:'product='+prod_id+'&qty='+qty
    }).then(res=>res.json()).then(d=>{
        if(d.status=='ok'){
            if(qty > 0){
                qtyEl.innerText = qty;
                qtyLabel.innerText = qty;
                document.getElementById('total_order').innerText='Rp'+d.total;
            }else{
                // Remove row
                let row = document.getElementById('cartItem'+prod_id);
                if(row) row.parentNode.removeChild(row);
                document.getElementById('total_order').innerText='Rp'+d.total;
                // Jika cart kosong, langsung redirect ke produk
                if(d.total == '0') window.location.href='index.php';
            }
        }
    });
}
</script>
</body>
</html>
