<?php
// الاتصال بقاعدة البيانات
$conn = new mysqli('localhost', 'root', '', 'ecommerce_db');

// التحقق من الاتصال
if ($conn->connect_error) {
    die("فشل الاتصال بقاعدة البيانات: " . $conn->connect_error);
}

// جلب المنتجات
$sql = "SELECT * FROM products";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrator Control Panel</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 20px;
        }
        h1,h2 {
            color: #333;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        img {
            width: 100px;
            height: auto;
        }
        button {
            padding: 8px 12px;
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
        .actions form {
            display: inline-block;
            margin: 0 5px;
        }
        .add-product {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h1>Administrator Control Panel</h1>

    <a href="add_product.php" class="add-product">
        <button>Add new product</button>
    </a>

    <h2>Current Products</h2>
    <table>
        <tr>
            <th>Name</th>
            <th>Price</th>
            <th>Image</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['price']; ?></td>
                <td>
    <?php
    $image = $row['image'];
    // التحقق مما إذا كانت الصورة هي رابط أو صورة مرفوعة
    if (filter_var($image, FILTER_VALIDATE_URL)) {
        // إذا كانت الصورة رابط
        echo '<img src="' . $image . '" alt="' . $row['name'] . '" width="100">';
    } else {
        // إذا كانت الصورة مرفوعة على السيرفر
        echo '<img src="images/' . $image . '" alt="' . $row['name'] . '" width="100">';
    }
    ?>
</td>

                <td class="actions">
                    <!-- تعديل المنتج -->
                    <form action="edit_product.php" method="GET">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <button type="submit">Edit</button>
                    </form>
                    <!-- حذف المنتج -->
                    <form action="admin_action.php" method="POST">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="action" value="delete">delete</button>
                    </form>
                </td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>

<?php
$conn->close();
?>
