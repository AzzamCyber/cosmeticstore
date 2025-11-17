<?php
if (session_status() == PHP_SESSION_NONE) session_start();
$is_login = isset($_SESSION['user_id']);
$username = $_SESSION['username'] ?? '';
?>
<nav class="fixed top-0 left-0 right-0 z-20 glass bg-white/40 backdrop-blur-xl shadow-lg rounded-b-2xl w-full max-w-full px-3 py-3 md:px-6 md:py-4 flex justify-between items-center overflow-x-hidden">
    <div class="flex items-center gap-2 min-w-0">
        <img src="assets/img/logo.png" alt="logo" class="h-9 w-9 object-cover rounded-xl shadow bg-white">
        <span class="text-xl font-bold text-pink-400 tracking-wide drop-shadow-lg truncate">La Belle Peau</span>
    </div>
    <!-- Desktop Menu -->
    <ul class="hidden md:flex gap-6 text-pink-600 font-semibold text-base items-center">
        <li><a href="index.php" class="hover:text-pink-500">Home</a></li>
        <li><a href="#produk" class="hover:text-pink-500">Produk</a></li>
        <li><a href="checkout.php" class="hover:text-pink-500">Checkout</a></li>
        <?php if($is_login): ?>
            <li><a href="dashboard_client.php" class="hover:text-pink-500">Dashboard</a></li>
            <li><span class="text-pink-400 font-bold"><?= htmlspecialchars($username) ?></span></li>
            <li>
                <form method="post" action="logout.php" style="display:inline;">
                    <button type="submit" class="bg-pink-400 text-white px-3 py-1.5 rounded-md shadow hover:bg-pink-600 transition">Logout</button>
                </form>
            </li>
        <?php else: ?>
            <li><a href="login.php" class="bg-pink-400 text-white px-3 py-1.5 rounded-md shadow hover:bg-pink-600 transition">Login</a></li>
        <?php endif; ?>
    </ul>
    <!-- Hamburger / Close Button -->
    <button id="nav-toggle" aria-label="Menu" class="md:hidden flex items-center p-2 rounded-lg focus:outline-pink-400 group z-50">
        <svg id="icon-burger" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="w-8 h-8"><path d="M4 7h16M4 12h16M4 17h16" stroke="#ec4899" stroke-width="2" stroke-linecap="round"/></svg>
        <svg id="icon-close" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="w-8 h-8 hidden"><path d="M6 6l12 12M6 18L18 6" stroke="#ec4899" stroke-width="2" stroke-linecap="round"/></svg>
    </button>
</nav>
<div class="pt-16 md:pt-20"></div>

<!-- MOBILE NAVIGATION MENU -->
<div id="nav-menu" class="fixed top-0 left-0 w-full h-full z-40 transform -translate-x-full transition duration-300 ease-in-out md:hidden bg-white/90 backdrop-blur-lg flex flex-col pt-28 pb-10 px-10 gap-6 text-pink-600 font-bold text-lg shadow-2xl">
    <a href="index.php" class="hover:text-pink-400 transition">Home</a>
    <a href="#produk" class="hover:text-pink-400 transition">Produk</a>
    <a href="checkout.php" class="hover:text-pink-400 transition">Checkout</a>
    <?php if($is_login): ?>
        <a href="dashboard_client.php" class="hover:text-pink-400 transition">Dashboard</a>
        <span class="text-pink-400 font-bold"><?= htmlspecialchars($username) ?></span>
        <form method="post" action="logout.php">
            <button type="submit" class="bg-pink-400 text-white w-full px-3 py-2 rounded-md shadow hover:bg-pink-600 transition">Logout</button>
        </form>
    <?php else: ?>
        <a href="login.php" class="bg-pink-400 text-white px-3 py-2 rounded-md text-center shadow hover:bg-pink-600 transition">Login</a>
    <?php endif; ?>
</div>
<div id="nav-overlay" class="hidden fixed inset-0 z-30 bg-black bg-opacity-30"></div>

<!-- JS MOBILE NAV -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const navToggle = document.getElementById('nav-toggle');
    const navMenu = document.getElementById('nav-menu');
    const navOverlay = document.getElementById('nav-overlay');
    const iconBurger = document.getElementById('icon-burger');
    const iconClose = document.getElementById('icon-close');
    const navLinks = navMenu.querySelectorAll('a');

    function showCloseIcon(opened) {
        if (opened) {
            iconBurger.classList.add('hidden');
            iconClose.classList.remove('hidden');
        } else {
            iconBurger.classList.remove('hidden');
            iconClose.classList.add('hidden');
        }
    }
    function menuOpened() {
        return !navMenu.classList.contains('-translate-x-full');
    }
    function closeNav() {
        navMenu.classList.add('-translate-x-full');
        navOverlay.classList.add('hidden');
        showCloseIcon(false);
    }

    if (navToggle && navMenu && navOverlay) {
        navToggle.addEventListener('click', function() {
            const opened = menuOpened();
            navMenu.classList.toggle('-translate-x-full');
            navOverlay.classList.toggle('hidden');
            showCloseIcon(!opened);
        });
        navOverlay.addEventListener('click', closeNav);
        navLinks.forEach(function(link) { link.addEventListener('click', closeNav); });
    }
});
</script>
