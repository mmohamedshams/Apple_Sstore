<?php
// الاتصال بقاعدة البيانات
$conn = new mysqli('localhost', 'root', '', 'ecommerce_db');

// التحقق من الاتصال
if ($conn->connect_error) {
    die("فشل الاتصال بقاعدة البيانات: " . $conn->connect_error);
}

// جلب بيانات المنتج للتعديل
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM products WHERE id = $id";
    $result = $conn->query($sql);
    $product = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $imageType = $_POST['imageType'];

    // تحديث بناءً على نوع الصورة (رابط أو مرفوعة)
    if ($imageType == 'upload' && !empty($_FILES['image']['name'])) {
        // رفع الصورة وتحديث المسار
        $image = $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], 'images/' . $image);
        $sql = "UPDATE products SET name='$name', price='$price', image='$image' WHERE id=$id";
    } elseif ($imageType == 'url' && !empty($_POST['imageUrl'])) {
        // تحديث باستخدام الرابط المدخل
        $image = $_POST['imageUrl'];
        $sql = "UPDATE products SET name='$name', price='$price', image='$image' WHERE id=$id";
    } else {
        // بدون تعديل الصورة
        $sql = "UPDATE products SET name='$name', price='$price' WHERE id=$id";
    }

    if ($conn->query($sql) === TRUE) {
        echo "<p class='success'>تم تعديل المنتج بنجاح</p>";
    } else {
        echo "<p class='error'>حدث خطأ: " . $conn->error . "</p>";
    }

    // إعادة التوجيه إلى لوحة التحكم
    header("Location: admin.php");
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit the product</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }

        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

        form {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 10px;
        }

        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 10px;
        }

        button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }

        .image-upload,
        .image-url {
            display: none;
        }

        .success {
            color: green;
            text-align: center;
            font-size: 18px;
        }

        .error {
            color: red;
            text-align: center;
            font-size: 18px;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #007bff;
            font-size: 16px;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
    <script>
        function toggleImageFields(value) {
            document.querySelector('.image-upload').style.display = value === 'upload' ? 'block' : 'none';
            document.querySelector('.image-url').style.display = value === 'url' ? 'block' : 'none';
        }
    </script>
</head>
<body>
    <h1>Edit the product</h1>

    <form action="edit_product.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($product['id']); ?>">
        
        <label for="name">Product Name:</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>

        <label for="price">Price:</label>
        <input type="text" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" required>

        <label for="imageType">Image type:</label>
        <select name="imageType" onchange="toggleImageFields(this.value)" required>
            <option value="">Select image type</option>
            <option value="upload">Upload image</option>
            <option value="url">Image link</option>
        </select>

        <div class="image-upload">
            <label for="image">Upload image:</label>
            <input type="file" name="image">
        </div>

        <div class="image-url">
            <label for="imageUrl">Image link:</label>
            <input type="text" name="imageUrl" value="<?php echo filter_var($product['image'], FILTER_VALIDATE_URL) ? htmlspecialchars($product['image']) : ''; ?>">
        </div>

        <button type="submit">Edit</button>
        <a href="admin.php">Back to Control Panel</a>
    </form>
    
  
</body>
</html>
