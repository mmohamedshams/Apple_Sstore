<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'ecommerce_db');

if ($conn->connect_error) {
    die("فشل الاتصال بقاعدة البيانات: " . $conn->connect_error);
}

$sql = "SELECT * FROM products";
$result = $conn->query($sql);

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql_cart_count = "SELECT SUM(quantity) AS cart_count FROM cart WHERE user_id = $user_id";
$result_cart_count = $conn->query($sql_cart_count);

$cart_count = 0; 
if ($result_cart_count && $row = $result_cart_count->fetch_assoc()) {
    $cart_count = $row['cart_count'] ? $row['cart_count'] : 0;
}

// Handle search functionality
$search_query = '';
if (isset($_POST['search'])) {
    $search_query = $_POST['search'];
    $sql = "SELECT * FROM products WHERE name LIKE '%$search_query%'";
    $result = $conn->query($sql);
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apple Store</title>
    <link rel="icon" href="https://cdn-icons-png.flaticon.com/512/94/94225.png">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

<header>
    <div class="header-container">
        <div class="user-info">
            <img src="https://pngimg.com/d/amazon_PNG11.png" alt="">
            
            <span class="welcome-message">Welcome <?php echo $_SESSION['email']; ?>
               
                <div class="user-dropdown">
                    <p>User: <?php echo $_SESSION['email']; ?></p>
                    <p>Account ID: <?php echo $_SESSION['user_id']; ?></p>
                    <a href="logout.php" class="logout-button">Logout</a>
                </div>
            
            </span>
        </div>
        <div class="search-container">
            <form action="search_results.php" method="GET">
                <input type="text" name="search" placeholder="Search for products..." required>
                <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
            </form>
        </div>
        <div class="cart-icon">
            <a href="cart.php">
                <i class="fas fa-shopping-cart"></i>
                <span class="cart-count"><?php echo $cart_count > 0 ? $cart_count : '0'; ?></span>
            </a>
        </div>
    </div>
</header>


<section class="categories-header">
  
    <div class="categories">
        <i class="fa-solid fa-bars"></i>
        <a href="#">Best Sellers</a>
        <a href="#">Electronics</a>
        <a href="#">Today's Deals</a>
        <a href="#">Mobile Phones </a>
        <a href="#">New Releases</a>
    </div>
</section>



<div class="products">
    <?php while ($row = $result->fetch_assoc()) { ?>
        <div class="product">
            <?php
            $image = $row['image'];

            if (filter_var($image, FILTER_VALIDATE_URL)) {
                echo '<img src="' . $image . '" alt="' . $row['name'] . '" width="100">';
            } else {
                echo '<img src="images/' . $image . '" alt="' . $row['name'] . '" width="100">';
            }
            ?>
            <h3><?php echo $row['name']; ?></h3>
            <p>Price: <?php echo $row['price']; ?> $</p>
            <form action="add_to_cart.php" method="POST">
                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                <button type="submit">Add to Cart</button>
            </form>
        </div>
    <?php } ?>
</div>

</body>
</html>
