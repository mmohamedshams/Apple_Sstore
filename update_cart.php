<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'ecommerce_db');

$user_id = $_SESSION['user_id'];

// تحديث كميات المنتجات في السلة
foreach ($_POST['quantity'] as $product_id => $quantity) {
    $quantity = intval($quantity);
    if ($quantity > 0) {
        $sql = "UPDATE cart SET quantity = $quantity WHERE user_id = $user_id AND product_id = $product_id";
        $conn->query($sql);
    } else {
        // إذا كانت الكمية أقل من أو تساوي صفر، يمكن حذف العنصر
        $sql = "DELETE FROM cart WHERE user_id = $user_id AND product_id = $product_id";
        $conn->query($sql);
    }
}

header("Location: cart.php");
exit();
?>
