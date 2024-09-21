<?php
session_start();
session_unset(); // إزالة جميع المتغيرات من الجلسة
session_destroy(); // تدمير الجلسة

// توجيه المستخدم إلى صفحة تسجيل الدخول بعد تسجيل الخروج
header("Location: login.php");
exit();
?>
