<?php
// Check if a session is already started
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Start a session only if none is active
}

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
}

// Kiểm tra xem đây có phải là trang chủ không
$is_homepage = basename($_SERVER['PHP_SELF']) == 'index.php';
?>

<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="QT Store">
    <meta name="keywords" content="QT Store, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QT Store</title>

    <!-- Favicon -->
    <link rel="icon" href="./img/product/logonqtstore.png" type="image/x-icon">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;600;900&display=swap" rel="stylesheet">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Css Styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" type="text/css">
    <link rel="stylesheet" href="./css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="./css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="./css/elegant-icons.css" type="text/css">
    <link rel="stylesheet" href="./css/nice-select.css" type="text/css">
    <link rel="stylesheet" href="./css/jquery-ui.min.css" type="text/css">
    <link rel="stylesheet" href="./css/owl.carousel.min.css" type="text/css">
    <link rel="stylesheet" href="./css/slicknav.min.css" type="text/css">
    <link rel="stylesheet" href="./css/style.css" type="text/css">

    <!-- Owl Carousel CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.css">

    <!-- Thêm SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <style>
        .header-top {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            font-size: 0.9rem;
        }

        .header-top .list-inline-item {
            margin-right: 15px;
        }

        .header-top .list-inline-item a {
            color: #495057;
        }

        .header-top .list-inline-item a:hover {
            color: #007bff;
        }

        .user-account .btn {
            margin-right: 5px;
        }

        .hero__banner .item img {
            width: 100%;
            height: auto;
        }

        .owl-nav button.owl-prev,
        .owl-nav button.owl-next {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            outline: none;
        }

        .owl-nav button.owl-prev {
            left: -25px;
        }

        .owl-nav button.owl-next {
            right: -25px;
        }

        .owl-nav button i {
            font-size: 2rem;
            color: #333;
        }
    </style>
</head>

<body>
    <!-- Header Section Begin -->
    <header class="header">
        <div class="header-top bg-light py-2">
            <div class="container">
                <div class="row align-items-center">
                    <!-- Left Side: Contact Info -->
                    <div class="col-lg-6 col-md-6 d-flex align-items-center">
                        <ul class="list-inline mb-0">
                            <li class="list-inline-item"><i class="fa fa-envelope"></i> phanquocthinh0004@gmail.com</li>
                            <li class="list-inline-item ml-3"><i class="fa fa-phone"></i> 0352997883</li>
                        </ul>
                    </div>
                    <!-- Right Side: Social Media and User Account -->
                    <div class="col-lg-6 col-md-6 text-lg-right text-md-right text-center">
                        <ul class="list-inline mb-0">
                        </ul>
                        <div class="user-account d-inline-block ml-3">
                            <?php if (isset($_SESSION['user_id'])) : ?>
                                <a href="profile.php" class="btn btn-outline-primary btn-sm">
                                    <i class="ti-user"></i> Hi, <?php echo htmlspecialchars($_SESSION['username']); ?>
                                </a>
                                <a href="logout.php" class="btn btn-outline-danger btn-sm ml-2">
                                    <i class="ti-power-off"></i> Logout
                                </a>
                            <?php else : ?>
                                <a href="login.php" class="btn btn-outline-success btn-sm">
                                    <i class="ti-user"></i> Login
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="header__logo">
                    <a href="./index.php">
    <img src="./img/product/logoheadermain.png" alt="" >
                    </a>

                    </div>
                </div>
                <div class="col-lg-6">
                    <nav class="header__menu">
                        <ul>
                            <li class="active"><a href="./index.php">Home</a></li>
                            <li><a href="./shop.php">Shop</a></li>
                            <li><a href="./blog.php">Blog</a></li>
                            <li><a href="./contact.php">Contact</a></li>
                            <li><a href="./historyorder.php">History Order</a></li>
                        </ul>
                    </nav>
                </div>
                <div class="col-lg-3">
                    <div class="header__cart">
                        <ul>
                            <li><a href="./yeuthich.php"><i class="fa fa-heart"></i> <span>
                                        <?php
                                        $favorite = [];
                                        if (isset($_SESSION['favorite'])) {
                                            $favorite = $_SESSION['favorite'];
                                        }
                                        $count = 0;
                                        echo $count;
                                        ?>
                                    </span></a></li>


                            <li><a href="./cart.php"><i class="fa fa-shopping-bag"></i> <span>
                                        <?php
                                        // Assuming you have the database connection included already
                                        require_once('./db/conn.php'); // Ensure this file creates the $conn variable

                                        // Initialize variables for cart count and total price
                                        $count = 0;
                                        $total = 0;
                                        // Only execute if the user is logged in
                                        if (isset($_SESSION['user_id'])) {
                                            $user_id = $_SESSION['user_id'];

                                            // Fetch cart items from the database
                                            $query = "SELECT product_id, quantity, price FROM cart WHERE user_id = ?";
                                            $stmt = $conn->prepare($query);
                                            $stmt->bind_param("i", $user_id);
                                            $stmt->execute();
                                            $result = $stmt->get_result();

                                            // Count items and calculate total price
                                            while ($item = $result->fetch_assoc()) {
                                                $count ++; // Increment count by the quantity
                                                $total += $item['quantity'] * $item['price']; // Calculate total price
                                            }
                                        }
                                        echo $count;
                                        ?>
                                    </span></a></li>

                            </span></a></li>
                        </ul>
                        <div class="header__cart__price">item: <span><?= number_format($total, 0, '', '.') . " $" ?></span></div>
                    </div>
                </div>
            </div>
            <div class="humberger__open">
                <i class="fa fa-bars"></i>
            </div>
        </div>
    </header>
    <!-- Header Section End -->

    <!-- Hero Section Begin -->
    <?php
    if ($is_homepage) {
        echo '<section class="hero">';
    } else {
        echo '<section class="hero hero-normal">';
    }
    ?>


</body>

</html>