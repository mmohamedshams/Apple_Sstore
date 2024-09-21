<?php
// الاتصال بقاعدة البيانات
$conn = new mysqli('localhost', 'root', '', 'ecommerce_db');

// التحقق من الاتصال
if ($conn->connect_error) {
    die("فشل الاتصال بقاعدة البيانات: " . $conn->connect_error);
}

// التحقق من العملية المطلوبة (إضافة أو حذف)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    // إضافة منتج
    if ($action == 'add') {
        $name = $_POST['name'];
        $price = $_POST['price'];
        $image = $_FILES['image']['name'];
        
        // رفع الصورة إلى مجلد الصور
        move_uploaded_file($_FILES['image']['tmp_name'], 'images/' . $image);
        
        // إدخال المنتج إلى قاعدة البيانات
        $sql = "INSERT INTO products (name, price, image) VALUES ('$name', '$price', '$image')";
        if ($conn->query($sql) === TRUE) {
            echo "تم إضافة المنتج بنجاح";
        } else {
            echo "حدث خطأ: " . $conn->error;
        }
    }

    // حذف منتج
    if ($action == 'delete') {
        $id = $_POST['id'];
        $sql = "DELETE FROM products WHERE id = $id";
        if ($conn->query($sql) === TRUE) {
            echo "تم حذف المنتج بنجاح";
        } else {
            echo "حدث خطأ: " . $conn->error;
        }
    }
}

// إعادة التوجيه إلى لوحة التحكم بعد تنفيذ الإجراء
header("Location: admin.php");
exit();

$conn->close();
?>
