<?php
session_start();
require_once 'includes/db.php';
header('Content-Type: application/json');

// Cek login user, kalau belum login redirect via AJAX
if(!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'unauth']);
    exit();
}

$user_id = $_SESSION['user_id'];
$product_id = intval($_POST['product'] ?? 0);

if(!$product_id) {
    echo json_encode(['status' => 'err', 'msg'=>'Product ID missing']);
    exit();
}

// Cek apakah produk sudah di keranjang user
$cek = mysqli_query($db, "SELECT * FROM cart WHERE user_id = $user_id AND product_id = $product_id");
if(mysqli_num_rows($cek) > 0){
    // Sudah ada, tambah quantity
    mysqli_query($db, "UPDATE cart SET quantity = quantity + 1 WHERE user_id = $user_id AND product_id = $product_id");
} else {
    // Belum ada, insert baru
    mysqli_query($db, "INSERT INTO cart (user_id, product_id, quantity) VALUES ($user_id, $product_id, 1)");
}

// Optional: hitung total cart user untuk badge/info pop up
$count = mysqli_fetch_assoc(mysqli_query($db, "SELECT SUM(quantity) as c FROM cart WHERE user_id = $user_id"))['c'];

echo json_encode(['status'=>'ok', 'cart_count'=>$count]);
exit();
?>
