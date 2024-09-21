<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'ecommerce_db');

$user_id = $_SESSION['user_id'];
$product_id = intval($_GET['product_id']);

// إزالة المنتج من السلة
$sql = "DELETE FROM cart WHERE user_id = $user_id AND product_id = $product_id";
$conn->query($sql);

header("Location: cart.php");
exit();
?>
