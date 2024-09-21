<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'ecommerce_db');

// التحقق من الاتصال
if ($conn->connect_error) {
    die("فشل الاتصال بقاعدة البيانات: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // البحث عن المستخدم في قاعدة البيانات
    $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['name'];

        // توجيه المستخدم إلى صفحة المنتجات بعد تسجيل الدخول
        header("Location: index.php");
        exit();
    } else {
        $error = "بيانات الدخول غير صحيحة!";
    }
}
$_SESSION['email'] = $user['email']; // ضع هذا في صفحة تسجيل الدخول بعد التحقق من البيانات

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="login-container">
        <h1 class="login-title">Login</h1>
        <?php if (isset($error)) { echo "<p class='error-message'>$error</p>"; } ?>
        <form action="login.php" method="POST" class="login-form">
            <label for="email" class="form-label">email:</label>
            <input type="email" name="email" id="email" class="form-input" placeholder="email" required>
            <label for="password" class="form-label">password:</label>
            <input type="password" name="password" id="password" class="form-input" placeholder="password" required>
            <button type="submit" class="submit-button">Login</button>
        </form>
    </div>
</body>
</html>

