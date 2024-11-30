<?php 
require_once('components/header.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - QT Store</title>
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> -->
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }
        .about-header {
            background: #f8f9fa;
            padding: 50px 0;
            text-align: center;
        }
        .about-header h1 {
            font-size: 2.5rem;
            font-weight: bold;
        }
        .about-header p {
            font-size: 1.2rem;
        }
        .about-content {
            padding: 50px 0;
        }
        .about-section {
            padding: 20px;
        }
        .about-section img {
            max-width: 100%;
            border-radius: 10px;
        }
        .about-section h2 {
            font-size: 2rem;
            margin-bottom: 20px;
        }
        .about-section p {
            font-size: 1.1rem;
        }
    </style>
</head>
<body>

<!-- About Us Header -->
<section class="about-header">
    <div class="container">
        <h1>About QT Store</h1>
        <p>Your trusted destination for the latest trends and quality products.</p>
    </div>
</section>

<!-- About Us Content -->
<section class="about-content">
    <div class="container">
        <div class="row">
            <div class="col-md-6 about-section">
                <h2>Who We Are</h2>
                <p>At QT Store, we believe in providing our customers with an unmatched shopping experience. From fashion to electronics, home essentials to personal care, we offer a wide range of products carefully selected to meet the diverse needs of our customers.</p>
            </div>
            <div class="col-md-6 about-section">
                <img src="http://banghenhahangcafe.com/wp-content/uploads/2021/03/thiet-ke-cua-hang-dien-thoai-nho-2.jpg" alt="Our Store">
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 about-section">
                <img src="https://suno.vn/blog/wp-content/uploads/2017/02/kinh-doanh-phu-kien-dien-thoai-mau-ma-phong-phu.jpg" alt="Our Team">
            </div>
            <div class="col-md-6 about-section">
                <h2>Our Vision</h2>
                <p>We aim to be the leading online retailer by delivering innovative and reliable products that enhance the lifestyle of our customers. Our focus is on quality, affordability, and customer satisfaction.</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 about-section text-center">
                <h2>Why Choose Us</h2>
                <p>At QT Store, customer satisfaction is at the heart of everything we do. Our team works hard to bring you the best selection of products, competitive prices, and excellent customer service. We are committed to continuous improvement and innovation to serve you better.</p>
            </div>
        </div>
    </div>
</section>
<?php
require_once('components/footer.php');
?>

</body>
</html>
