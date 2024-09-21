<?php
// الاتصال بقاعدة البيانات
$conn = new mysqli('localhost', 'root', '', 'ecommerce_db');

// التحقق من الاتصال
if ($conn->connect_error) {
    die("فشل الاتصال بقاعدة البيانات: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $imageType = $_POST['imageType'];
    $image = '';

    // إذا اختار المستخدم رفع صورة
    if ($imageType == 'upload') {
        $image = $_FILES['image']['name'];
        $imagePath = "images/" . basename($image);

        // رفع الصورة إلى المجلد
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
            echo "فشل في رفع الصورة.";
        }
    } elseif ($imageType == 'url') {
        // إذا اختار المستخدم إدخال رابط صورة
        $image = $_POST['imageUrl'];
    }

    // إضافة المنتج إلى قاعدة البيانات
    $sql = "INSERT INTO products (name, price, image) VALUES ('$name', '$price', '$image')";
    if ($conn->query($sql) === TRUE) {
        header('Location: admin.php');
    } else {
        echo "خطأ: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Product</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        label {
            font-weight: bold;
            color: #333;
        }

        input[type="text"],
        select {
            width: 100%;
            padding: 10px;
            margin: 10px 0 20px;
            border-radius: 4px;
            border: 1px solid #ccc;
            box-sizing: border-box;
            font-size: 16px;
        }

        input[type="file"] {
            margin: 10px 0;
        }

        .image-upload,
        .image-url {
            display: none;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #218838;
        }

        a {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #007bff;
            text-decoration: none;
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
    <form action="add_product.php" method="POST" enctype="multipart/form-data">
        <h1>Add New Product</h1>

        <label for="name">Product Name</label>
        <input type="text" name="name" required>

        <label for="price">Price</label>
        <input type="text" name="price" required>

        <label for="imageType">Image Type</label>
        <select name="imageType" onchange="toggleImageFields(this.value)" required>
            <option value="">Select image type</option>
            <option value="upload">Upload Image</option>
            <option value="url">Image Link</option>
        </select>

        <div class="image-upload">
            <label for="image">Upload Image</label>
            <input type="file" name="image">
        </div>

        <div class="image-url">
            <label for="imageUrl">Image Link</label>
            <input type="text" name="imageUrl">
        </div>

        <button type="submit">Add Product</button>

        <a href="admin.php">Back to Control Panel</a>
    </form>
</body>
</html>

