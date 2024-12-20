<?php
session_start();
$is_homepage = false;
require_once('./db/conn.php');
// Check if the user is logged in and has the 'user' role
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Fetch the user role from the database
    $sql_str = "
    SELECT r.role_name AS role_name 
        FROM user_roles ur
        JOIN roles r ON ur.role_id = r.role_id
        WHERE ur.user_id = $user_id";

    $result = mysqli_query($conn, $sql_str);
    $role_row = mysqli_fetch_assoc($result);

   
    $user_role = $role_row ? $role_row['role_name'] : null;
} else {
  
    $user_role = null;
}

// Ensure product ID is set
if (isset($_GET['id'])) {
    $pid = intval($_GET['id']);  // Use intval to sanitize the ID
} else {
    die("Product ID not found.");
}

// Handle form submission for "Add to Cart"
if (isset($_POST['addtocart'])) {
    $id = intval($_POST['pid']); // Sanitize the product ID
    $qty = intval($_POST['qty']); // Sanitize the quantity

    // Ensure user is logged in to add items to the cart
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];

        // Check if the product already exists in the user's cart
        $query = "SELECT * FROM cart WHERE user_id = ? AND product_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $user_id, $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $cart_item = $result->fetch_assoc();

        if ($cart_item) {
            // Update the existing cart item's quantity
            $new_quantity = $cart_item['quantity'] + $qty;
            $update_query = "UPDATE cart SET quantity = ?, total = ? WHERE id = ?";
            $total_price = $new_quantity * $cart_item['price']; // Calculate the new total price
            $stmt = $conn->prepare($update_query);
            $stmt->bind_param("idi", $new_quantity, $total_price, $cart_item['id']);
            $stmt->execute();
        } else {
            // Add a new cart item
            $query = "SELECT * FROM products WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $product = $result->fetch_assoc();

            if ($product) {
                // Prepare and execute the insert query
                $insert_query = "INSERT INTO cart (user_id, product_id, quantity, price, total) VALUES (?, ?, ?, ?, ?)";
                $price = $product['price']; // Get the product price
                $total = $qty * $price; // Calculate total price
                $stmt = $conn->prepare($insert_query);
                $stmt->bind_param("iiiii", $user_id, $id, $qty, $price, $total);
                $stmt->execute();
            }
        }

        // Update session message for success
        $_SESSION['cart_success'] = true;
    } else {
        // Handle case where user is not logged in (optional)
        $_SESSION['cart_error'] = "You must be logged in to add items to the cart.";
    }

    // Redirect to prevent form resubmission
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;
}


require_once('components/header.php');

// Fetch product details
// Fetch product details including category and brand
$sql_str = "
SELECT p.*,c.name AS category_name, b.name AS brand_name
FROM products p
JOIN categories c ON p.category_id = c.id
JOIN brands b ON p.brand_id = b.id
WHERE p.id = $pid";
$result = mysqli_query($conn, $sql_str);
$row = mysqli_fetch_assoc($result);

$anh_arr = explode(';', $row['images']);

// Get product feedback
$sql_feedback = "SELECT f.rating, f.comment, u.username, f.created_at 
                 FROM feedback f
                 JOIN users u ON f.user_id = u.user_id
                 WHERE f.product_id = $pid";
$result_feedback = mysqli_query($conn, $sql_feedback);
// Fetch product feedback and calculate average rating
$sql_feedback2 = "SELECT AVG(f.rating) as avg_rating, COUNT(f.id) as total_reviews 
                 FROM feedback f
                 WHERE f.product_id = $pid";
$result_feedback2 = mysqli_query($conn, $sql_feedback2);
$feedback_data = mysqli_fetch_assoc($result_feedback2);
$avg_rating = round($feedback_data['avg_rating'], 1);
$total_reviews = $feedback_data['total_reviews'];
?>
<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="QT Store">
    <meta name="keywords" content="QT Store, Contact, Ecommerce">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- Css Styles -->
    <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="css/style.css" type="text/css">

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        .product__details__rating i {
            color: #FFD700;
           
            font-weight: normal;
         
        }

        h5 {
            font-weight: normal;
          
        }

        .rating i {
            color: #FFD700;
           
        }

        .rating i.fas {
            color: #ffcc00;
          
        }
    </style>
</head>

<body>

    
    <section class="breadcrumb-section set-bg" data-setbg="img/banner/bannerbackround.jpeg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="breadcrumb__text">
                        <h2><?= $row['name'] ?></h2>
                        <div class="breadcrumb__option">
                            <a href="./index.php">Home</a>
                            <a href="./shop.php">Product</a>
                            <span><?= $row['name'] ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Product Details Section Begin -->
    <section class="product-details spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <div class="product__details__pic">
                        <div class="product__details__pic__item">
                            <img class="product__details__pic__item--large" src="<?= "admin/" . $anh_arr[0] ?>" alt="<?= $row['name'] ?>">
                        </div>
                        <div class="product__details__pic__slider owl-carousel">
                            <?php foreach ($anh_arr as $img): ?>
                                <img data-imgbigurl="<?= "admin/" . $img ?>" src="<?= "admin/" . $img ?>">
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="product__details__text">
                        <h3><?= $row['name'] ?></h3>
                        <div class="product__details__price">
                            <?php if ($row['disscounted_price'] > 0): ?>
                                <span class="discounted-price">$<?= $row['disscounted_price'] ?></span>
                                <span style="text-decoration: line-through;">$<?= $row['price'] ?></span>
                            <?php else: ?>
                                <span>$<?= $row['price'] ?></span>
                            <?php endif; ?>
                        </div>

                        <div class="product__details__rating">
                            <span class="stars">
                                <?php for ($i = 0; $i < 5; $i++): ?>
                                    <?php if ($i < $avg_rating): ?>
                                        <i class="fa fa-star"></i>
                                    <?php else: ?>
                                        <i class="fa fa-star-o"></i>
                                    <?php endif; ?>
                                <?php endfor; ?>
                            </span>
                            <span>(<?= $total_reviews ?> reviews)</span>
                        </div>
                        <p><?= $row['summary'] ?></p>
                        <form method="post">
                        <div class="product__details__quantity">
                            <div class="quantity">
                                <div class="pro-qty">
                                    <input type="number" value="1" min="1" max="<?= $row['stock'] ?>" name="qty">
                                </div>
                                <input type="hidden" name="pid" value="<?= $pid ?>">
                            </div>
                        </div>
                            <?php if ($row['stock'] == 0): ?>
                                <p><strong style="color: red;">Currently out of stock</strong></p>
                            <?php else: ?>
                                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'User'): ?>
                                    <button class="primary-btn" name="addtocart">Add To Cart</button>
                                <?php else: ?>
                                    <p><strong>Only users can add products to the cart.</strong></p>
                                <?php endif; ?>
                            <?php endif; ?>
                        </form>

                        <ul>
                            <li><b>Category</b> <span><?= $row['category_name'] ?></span></li>
                            <li><b>Brand</b> <span><?= $row['brand_name'] ?></span></li>
                            <li><b>Availability</b> <span><?= $row['stock'] ?></span></li>

                        </ul>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="product__details__tab">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#tabs-1" role="tab" aria-selected="true">Description</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tabs-2" role="tab" aria-selected="false">Reviews</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tabs-1" role="tabpanel">
                                <div class="product__details__tab__desc">
                                    <h6>Product Information</h6>
                                    <?= $row['description'] ?>
                                </div>
                            </div>
                            <div class="tab-pane" id="tabs-2" role="tabpanel">
                                <div class="product__details__tab__desc">
                                    <h6>Product Feedback</h6>
                                    <!-- Display Existing Feedback -->
                                    <?php while ($feedback = mysqli_fetch_assoc($result_feedback)): ?>
                                        <div class="feedback-item">
                                            <strong><?= $feedback['username'] ?></strong>
                                            <span><?= date('F j, Y', strtotime($feedback['created_at'])) ?></span>
                                            <div class="rating">
                                                <?php for ($i = 0; $i < $feedback['rating']; $i++): ?>
                                                    <i class="fa fa-star"></i>
                                                <?php endfor; ?>
                                            </div>
                                            <p><?= $feedback['comment'] ?></p>
                                        </div>
                                        <hr>
                                    <?php endwhile; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Related Product Section Begin -->
    <section class="related-product">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title related__product__title">
                        <h2>Related products</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <?php
              
                $cid = $row['category_id'];
                $sql2 = "SELECT * FROM products WHERE category_id = $cid AND id <> $pid";
                $result2 = mysqli_query($conn, $sql2);
                while ($row2 = mysqli_fetch_assoc($result2)) {
                    $arrs = explode(";", $row2["images"]);
                ?>
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="product__item">
                            <div class="product__item__pic set-bg" data-setbg="<?= "admin/" . $arrs[0] ?>">
                                <ul class="product__item__pic__hover">
                                    <?php if ($row2['stock'] > 0): ?>
                                        <li><a href="#" class="add-to-cart" data-id="<?= $row2['id'] ?>" data-qty="1"><i class="fa fa-shopping-cart"></i></a></li>
                                    <?php else: ?>
                                        <!-- Show out of stock message if product stock is 0 -->
                                        <li><span style="color: red;">Currently out of stock</span></li>
                                    <?php endif; ?>
                                </ul>

                            </div>
                            <div class="product__item__text">
                                <h6><a href="product.php?id=<?= $row2['id'] ?>"><?= $row2['name'] ?></a></h6>

                                <!-- Loại bỏ tô đen sao -->
                                <div class="product__details__rating">
                                    <span class="stars">
                                        <?php for ($i = 0; $i < 5; $i++): ?>
                                            <?php if ($i < $avg_rating): ?>
                                                <i class="fa fa-star"></i>
                                            <?php else: ?>
                                                <i class="fa fa-star-o"></i>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                    </span>
                                </div>

                              
                                <?php if ($row2['disscounted_price'] > 0): ?>
                                    <h5>
                                        <span class="discounted-price">$<?= $row2['disscounted_price'] ?></span>
                                        <span style="text-decoration: line-through;">$<?= $row2['price'] ?></span>

                                    </h5>
                                <?php else: ?>
                                    <h5>$<?= $row2['price'] ?></h5>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>
    <!-- Related Product Section End -->

    <script>
        window.onload = function() {
            <?php if (isset($_SESSION['cart_success'])): ?>
                Swal.fire({
                    title: 'Successfully!',
                    text: 'Add to cart successfully!',
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
                <?php unset($_SESSION['cart_success']); ?>
            <?php endif; ?>

        }
    </script>
</body>

</html>
<?php
require_once('components/footer.php');
?>