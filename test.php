<?php
require_once 'includes/db.php';
require_once 'includes/header.php';
$produk = mysqli_query($db, "SELECT * FROM products ORDER BY created_at DESC");
$kategori = mysqli_query($db, "SELECT DISTINCT category FROM products");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>La Belle Peau Skincare</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" />
    <style>
        * {box-sizing: border-box; margin: 0; padding: 0;}
        body, html { overflow-x: hidden; width: 100%; max-width: 100vw;}
        body {font-family: 'Poppins', Arial, sans-serif; background: linear-gradient(120deg, #FFD6E0 60%, #FFF0F3 100%);}
        .banner-blur {position:relative; min-height:220px; width: 100%; max-width: 100vw; overflow: hidden;}
        .banner-blur img {position: absolute; left:50%; top:50%; transform:translate(-50%,-50%);
            width:100vw; min-width:600px; max-width:1200px; max-height:260px; z-index:0;
            filter: blur(18px) brightness(0.83); opacity:0.53; object-fit:cover;}
        .banner-content { position:relative; z-index:2; width: 100%; max-width: 100vw;}
        .modal { display:none; }
        .modal.open { display:flex; }
        section, div { max-width: 100vw; box-sizing: border-box; }
        @media (max-width: 767px) {
            .modal-content {max-width: 99vw; padding: 18px 7px;}
            .modal-content .kategori-list {gap: 8px;}
            .modal-content .kategori-list a {font-size: 1rem; padding: 8px 14px;}
            .modal-content .kategori-list {flex-wrap: wrap; justify-content: center;}
            .modal-content .text-xl {font-size: 1.1rem;}
        }
    </style>
</head>
<body class="min-h-screen text-gray-800">

<!-- Cookie Popup Modern -->
<div id="cookie-popup" class="fixed bottom-6 left-1/2 -translate-x-1/2 z-50 bg-white/80 shadow-2xl backdrop-blur-2xl px-4 py-3 rounded-xl flex items-center gap-3 transition duration-200 border border-pink-200 max-w-[95vw]" style="display:none;">
    <div class="flex-shrink-0"><img src="assets/img/logo.png" alt="Logo" class="h-8 w-8 rounded-xl"></div>
    <span class="text-pink-700 text-sm">Website ini menggunakan cookie untuk pengalaman user terbaik.</span>
    <button id="cookie-accept" class="ml-auto px-4 py-2 bg-pink-400 text-white rounded-lg font-bold hover:bg-pink-600 transition text-sm">OK</button>
</div>

<!-- Hero dengan Banner Blur dan Logo -->
<section class="banner-blur w-full flex items-center justify-center mb-4">
    <img src="assets/img/banner.png" alt="Banner La Belle Peau" draggable="false" />
    <div class="banner-content flex flex-col items-center justify-center text-center w-full pt-14 pb-5 px-4" data-aos="fade-up" data-aos-delay="120">
        <img src="assets/img/logo.png" alt="Logo La Belle Peau" class="w-20 h-20 md:w-24 md:h-24 mb-4 drop-shadow-lg mx-auto">
        <h1 class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-pink-400 drop-shadow-lg leading-snug text-center">La Belle Peau Skincare</h1>
        <p class="mb-2 text-base md:text-lg text-pink-800 text-center">Your Secret to Effortless Radiance</p>
    </div>
</section>

<!-- Filter Kategori: Tombol Pop-Up -->
<section class="w-full mb-6 flex justify-center px-4">
    <button id="filterBtn" class="flex items-center gap-2 px-4 py-2 md:px-5 md:py-3 bg-pink-400 text-white font-bold rounded-xl shadow-lg hover:bg-pink-500 transition text-sm md:text-base max-w-[90vw]">
        <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 6h16M6 10v8a2 2 0 002 2h8a2 2 0 002-2v-8" /></svg>
        <span>Pilih Kategori</span>
    </button>
</section>
<div id="filterModal" class="modal fixed z-50 inset-0 bg-black/20 backdrop-blur-sm flex-col items-center justify-center p-3 transition duration-200" style="display:none;">
    <div class="modal-content mx-auto w-full max-w-sm bg-white/95 rounded-2xl shadow-2xl p-5 flex flex-col gap-2">
        <div class="text-lg md:text-xl font-bold text-pink-600 mb-4 flex items-center gap-2 justify-center">
            <svg class="w-6 h-6" fill="none" stroke="#ec4899" stroke-width="2" viewBox="0 0 24 24"><path d="M4 6h16M6 10v8a2 2 0 002 2h8a2 2 0 002-2v-8" /></svg>
            Pilih Kategori Produk
        </div>
        <div class="kategori-list flex flex-wrap gap-2 items-center justify-center text-pink-700 mb-4">
            <a href="index.php" class="px-3 py-2 rounded-full bg-pink-50 border border-pink-200 hover:bg-pink-100 font-semibold shadow text-xs">🌈 Semua</a>
            <?php
            $icon_map = [
                'Serum' => '💧',
                'Toner' => '🌀',
                'Cleanser' => '🧼',
                'Moisturizer' => '🧴',
                'Sunscreen' => '☀️',
                'Lip Balm' => '👄',
                'Mask' => '🛡️',
                'Micellar' => '🍑'
            ];
            mysqli_data_seek($kategori, 0);
            while($row = mysqli_fetch_assoc($kategori)){
                $cat = $row['category'];
                $icon = isset($icon_map[$cat]) ? $icon_map[$cat] : '✨';
                echo '<a href="index.php?kat='.urlencode($cat).'" class="px-3 py-2 rounded-full bg-pink-100 border border-pink-400 hover:bg-pink-200 text-pink-500 font-bold shadow flex items-center gap-1 transition text-xs">'
                .'<span>'.$icon.'</span>'.$cat.'</a>';
            }
            ?>
        </div>
        <button id="closeModal" class="mt-1 w-full py-2 bg-pink-400 text-white rounded-xl font-bold shadow hover:bg-pink-600 transition text-sm">Tutup</button>
    </div>
</div>

<!-- GRID PRODUK -->
<section id="produk" class="w-full max-w-7xl mx-auto px-3 py-6">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-6">
    <?php
    $i=0;
    while($row = mysqli_fetch_assoc($produk)): ?>
        <div class="bg-pink-100/70 backdrop-blur-xl rounded-2xl shadow-lg p-2 md:p-4 hover:scale-105 hover:shadow-2xl transition duration-300 flex flex-col"
             data-aos="fade-up" data-aos-delay="<?= 80+$i*60 ?>">
            <div class="relative mb-2">
                <img src="assets/img/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>"
                    class="rounded-xl shadow h-28 md:h-52 w-full object-cover bg-white border border-white">
                <span class="absolute top-2 right-2 bg-white/80 text-pink-600 font-bold px-2 py-1 rounded-xl text-[10px] md:text-xs shadow border border-pink-200"><?= $row['stock'] > 0 ? 'Stok ' . $row['stock'] : 'Habis' ?></span>
            </div>
            <h3 class="text-pink-500 font-semibold leading-tight mb-1 text-xs md:text-base"><?= htmlspecialchars($row['name']) ?></h3>
            <p class="text-gray-700 mb-2 text-xs md:text-sm line-clamp-2"><?= htmlspecialchars($row['description']) ?></p>
            <div class="flex justify-between items-center gap-1 mt-auto pt-1">
                <span class="font-bold text-rose-400 text-sm md:text-lg">Rp<?= number_format($row['price'],0,',','.') ?></span>
                <a href="#" onclick="addToCart(<?= $row['id'] ?>); return false;"
                   class="px-2 py-1 md:px-3 md:py-1 rounded-full bg-pink-400 text-white font-semibold text-xs shadow hover:bg-pink-500 hover:scale-105 transition">
                   Beli
                </a>
            </div>
        </div>
    <?php $i++; endwhile; ?>
    </div>
</section>

<!-- Keranjang BAR (Mobile) -->
<div id="cartBar" class="fixed bottom-0 left-0 right-0 z-50 px-4 py-3 bg-pink-500 text-white rounded-t-xl shadow-xl flex items-center justify-between transition duration-300"
     style="display:none">
    <b id="cartBarMsg" class="mr-3">Produk ditambahkan ke keranjang!</b>
    <a href="checkout.php" class="bg-white text-pink-500 font-bold px-4 py-2 rounded-lg shadow hover:bg-pink-100 transition">Checkout</a>
</div>
<!-- Mini Cart Pop Up (Desktop) -->
<div id="cartPopUp" class="fixed inset-0 z-50 flex items-center justify-center transition duration-300" style="display:none;">
    <div class="bg-white/95 rounded-2xl shadow-2xl p-8 flex flex-col items-center">
        <div class="font-bold text-pink-600 text-lg mb-4" id="cartPopUpMsg">Produk masuk ke keranjang!</div>
        <a href="checkout.php" class="bg-pink-400 text-white font-bold px-6 py-3 rounded-xl shadow hover:bg-pink-600 transition mt-2">Checkout</a>
        <button onclick="closeCartPop()" class="text-pink-600 mt-3 font-semibold">Tutup</button>
    </div>
</div>

<footer class="mt-14 text-center py-6 text-pink-700 bg-white/40 backdrop-blur-xl rounded-t-2xl shadow-lg w-full">
  <div class="text-base md:text-lg font-medium flex flex-col items-center px-4">
    <span>© <?= date("Y") ?> <b>La Belle Peau Skincare</b></span>
    <span class="text-pink-500 font-light text-sm md:text-base">Your Secret to Effortless Radiance</span>
  </div>
</footer>

<!-- JS: kategori, cookie, dan add to cart pop up -->
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
AOS.init();

// Cookie popup
document.addEventListener('DOMContentLoaded', function() {
    const cookiePopup = document.getElementById('cookie-popup');
    const cookieAccept = document.getElementById('cookie-accept');
    if(cookiePopup && cookieAccept){
        if(!localStorage.getItem('cookieAccepted')){
            cookiePopup.style.display='flex';
        }
        cookieAccept.addEventListener('click', function(){
            cookiePopup.style.display='none';
            localStorage.setItem('cookieAccepted', true);
        });
    }
    // Kategori modal pop
    const filterBtn = document.getElementById('filterBtn');
    const filterModal = document.getElementById('filterModal');
    const closeModal = document.getElementById('closeModal');
    if(filterBtn && filterModal && closeModal){
        filterBtn.addEventListener('click', function(){
            filterModal.style.display = 'flex';
        });
        closeModal.addEventListener('click', function(){
            filterModal.style.display = 'none';
        });
        filterModal.addEventListener('click', function(e){
            if(e.target == filterModal){
                filterModal.style.display = 'none';
            }
        });
    }
});

// Add to Cart AJAX pop up modern
function addToCart(product_id){
    if(!product_id) return;
    fetch('add_to_cart_ajax.php', {
        method:'POST',
        headers:{'Content-Type':'application/x-www-form-urlencoded'},
        body:'product='+product_id
    })
    .then(res=>res.json())
    .then(d=>{
        if(d.status=='unauth'){
            window.location.href='login.php';
        } else if(d.status=='ok'){
            if(window.innerWidth <= 768){
                document.getElementById('cartBar').style.display='flex';
                setTimeout(()=>{document.getElementById('cartBar').style.display='none';},3500);
            } else {
                document.getElementById('cartPopUp').style.display='flex';
            }
        }
    });
}
function closeCartPop(){
    document.getElementById('cartPopUp').style.display='none';
}
</script>
</body>
</html>
