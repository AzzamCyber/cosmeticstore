<?php
session_start();
require_once 'includes/db.php';
header('Content-Type:application/json');
if(!isset($_SESSION['user_id'])) exit(json_encode(['status'=>'unauth']));
$user_id = $_SESSION['user_id'];
$product_id = intval($_POST['product'] ?? 0);
$qty = intval($_POST['qty'] ?? 1);

if($qty > 0){
    mysqli_query($db,"UPDATE cart SET quantity=$qty WHERE user_id=$user_id AND product_id=$product_id");
}else{
    // Hapus dari keranjang
    mysqli_query($db,"DELETE FROM cart WHERE user_id=$user_id AND product_id=$product_id");
}
$q = mysqli_query($db,"SELECT c.*, p.price FROM cart c JOIN products p ON c.product_id=p.id WHERE c.user_id=$user_id");
$total=0;
foreach($q as $r) $total += $r['price']*$r['quantity'];
echo json_encode(['status'=>'ok','total'=>number_format($total,0,',','.')]);
exit();
?>
