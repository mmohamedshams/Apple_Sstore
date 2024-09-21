<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'ecommerce_db');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['id'];
    $user_id = $_SESSION['user_id'];

    // تحقق مما إذا كان المنتج موجوداً بالفعل في السلة
    $check_cart = $conn->query("SELECT * FROM cart WHERE user_id = $user_id AND product_id = $product_id");

    if ($check_cart->num_rows > 0) {
        // إذا كان المنتج موجود بالفعل، قم بزيادة الكمية
        $conn->query("UPDATE cart SET quantity = quantity + 1 WHERE user_id = $user_id AND product_id = $product_id");
    } else {
        // إذا لم يكن المنتج موجوداً، أضفه إلى السلة
        $conn->query("INSERT INTO cart (user_id, product_id, quantity) VALUES ($user_id, $product_id, 1)");
    }

    header("Location: index.php");
    exit();
}
?>
