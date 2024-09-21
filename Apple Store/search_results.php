<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'ecommerce_db');

if ($conn->connect_error) {
    die("فشل الاتصال بقاعدة البيانات: " . $conn->connect_error);
}

// Get the search query from the URL
$search_query = isset($_GET['search']) ? $_GET['search'] : '';

// Prepare and execute the search query
$sql = "SELECT * FROM products WHERE name LIKE '%$search_query%'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link rel="stylesheet" href="styles.css">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="https://cdn-icons-png.flaticon.com/512/94/94225.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
</head>
<body>



<header>
    <div class="header-container">
        <div class="user-info">
        <a href="logout.php" class="logout-button">Logout</a>
            <span class="welcome-message">Welcome, <?php echo $_SESSION['email']; ?></span>
            
        </div>
        <div class="search-container">
            <form action="search_results.php" method="GET">
                <input type="text" name="search" placeholder="Search for products..." required>
                <button type="submit">Search</button>
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
        <a href="#">Category 1</a>
        <a href="#">Category 2</a>
        <a href="#">Category 3</a>
    </div>
</section>


<div class="products">
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="product">
                <img src="<?php echo filter_var($row['image'], FILTER_VALIDATE_URL) ? $row['image'] : 'images/' . $row['image']; ?>" alt="<?php echo $row['name']; ?>" width="100">
                <h3><?php echo $row['name']; ?></h3>
                <p>Price: <?php echo $row['price']; ?> $</p>
                <form action="add_to_cart.php" method="POST">
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                    <button type="submit">Add to Cart</button>
                </form>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No products found.</p>
    <?php endif; ?>
</div>

</body>
</html>
