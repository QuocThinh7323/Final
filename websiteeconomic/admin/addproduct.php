<?php

require('../db/conn.php');

// Lấy dữ liệu từ form
$name = $_POST['name'];
$slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
$sumary = $_POST['sumary'];
$description = $_POST['description'];
$stock = $_POST['stock'];
$originalprice = $_POST['originalprice'];
$category = $_POST['category'];
$brand = $_POST['brand'];

// Kiểm tra giá trị saleprice và gán giá trị NULL nếu không được nhập
$saleprice = !empty($_POST['saleprice']) ? $_POST['saleprice'] : 'NULL';

// Xử lý hình ảnh
$countfiles = count($_FILES['anhs']['name']);
$imgs = '';

for ($i = 0; $i < $countfiles; $i++) {
    $filename = $_FILES['anhs']['name'][$i];

    // Đường dẫn tải lên
    $location = "uploads/" . uniqid() . $filename;
    $extension = pathinfo($location, PATHINFO_EXTENSION);
    $extension = strtolower($extension);

    // Các đuôi tệp được phép
    $valid_extensions = array("jpg", "jpeg", "png");

    if (in_array(strtolower($extension), $valid_extensions)) {
        // Upload file nếu di chuyển thành công
        if (move_uploaded_file($_FILES['anhs']['tmp_name'][$i], $location)) {
            $imgs .= $location . ";";
        }
    }
}

$imgs = substr($imgs, 0, -1);

// Câu lệnh thêm sản phẩm vào bảng, saleprice có thể là NULL
$sql_str = "INSERT INTO `products` (`id`, `name`, `slug`, `description`, `summary`, `stock`, `price`, `disscounted_price`, `images`, `category_id`, `brand_id`, `status`, `created_at`, `updated_at`) VALUES 
    (NULL, '$name', 
    '$slug', 
    '$description', '$sumary', $stock, $originalprice, $saleprice, '$imgs', $category, $brand, 'Active', NOW(), NOW());";

// Thực thi câu lệnh
mysqli_query($conn, $sql_str);

// Quay về trang chính
header("location: index.php");

?>