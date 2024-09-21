<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'ecommerce_db');

$user_id = $_SESSION['user_id'];

// Fetch products in the user's cart
$sql = "SELECT products.name, products.price, cart.quantity, cart.product_id
        FROM cart 
        JOIN products ON cart.product_id = products.id
        WHERE cart.user_id = $user_id";
$result = $conn->query($sql);

$total_price = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            padding: 20px;
        }

        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        th, td {
            padding: 12px 15px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        td {
            font-size: 16px;
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
            margin-top: 10px;
            transition: background-color 0.3s ease;
        }

        .button:hover {
            background-color: #0056b3;
        }

        .total-price {
            font-size: 20px;
            font-weight: bold;
            text-align: center;
            margin-top: 20px;
        }

        form button {
            padding: 8px 12px;
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        form button:hover {
            background-color: #c82333;
        }

        p {
            text-align: center;
            font-size: 18px;
            color: #333;
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .quantity-button {
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 5px 10px;
            cursor: pointer;
            font-size: 16px;
            margin: 0 5px;
            transition: background-color 0.3s ease;
        }

        .quantity-button:hover {
            background-color: #0056b3;
        }

        .quantity-display {
            font-size: 16px;
            width: 50px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 5px;
        }
    </style>
</head>
<body>
    <h1>Shopping Cart</h1>

    <?php if ($result->num_rows > 0) { ?>
        <form action="update_cart.php" method="POST">
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['price']; ?> $</td>
                            <td>
                                <div class="quantity-controls">
                                    <button type="button" class="quantity-button" onclick="changeQuantity(<?php echo $row['product_id']; ?>, -1)">-</button>
                                    <input type="text" id="quantity-<?php echo $row['product_id']; ?>" name="quantity[<?php echo $row['product_id']; ?>]" value="<?php echo $row['quantity']; ?>" class="quantity-display" readonly>
                                    <button type="button" class="quantity-button" onclick="changeQuantity(<?php echo $row['product_id']; ?>, 1)">+</button>
                                </div>
                            </td>
                            <td><?php echo $row['price'] * $row['quantity']; ?> $</td>
                            <td>
                                <a href="remove_from_cart.php?product_id=<?php echo $row['product_id']; ?>" class="button">Remove</a>
                            </td>
                        </tr>
                        <?php $total_price += $row['price'] * $row['quantity']; ?>
                    <?php } ?>
                </tbody>
            </table>
            <div class="total-price">Total Price: <?php echo $total_price; ?> $</div> 
        </form>
        <button type="submit" class="button">Update Quantities</button>
        <a href="index.php" class="button">Continue Shopping</a>
    <?php } else { ?>
        <p>Your cart is empty.</p>
        <a href="index.php" class="button">Continue Shopping</a>
    <?php } ?>

    <script>
        function changeQuantity(productId, change) {
            var quantityInput = document.getElementById('quantity-' + productId);
            var currentQuantity = parseInt(quantityInput.value);
            var newQuantity = currentQuantity + change;
            if (newQuantity < 1) newQuantity = 1; // Prevent negative quantity
            quantityInput.value = newQuantity;
        }
    </script>
</body>
</html>
