<?php
session_start();

// Hủy bỏ tất cả các session
session_unset();
session_destroy();

// Chuyển hướng người dùng về trang đăng nhập
header("Location: index.php");
exit();
?>
