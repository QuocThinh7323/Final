<?php
session_start();
require_once('./db/conn.php');
// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // If not logged in, redirect to the login page
    header("Location: login.php");
    exit;
}
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
?>

<div class="container">
    <div class="row">
        <div class="col-lg-3">
            <div class="hero__categories">
                <div class="hero__categories__all">
                    <i class="fa fa-bars"></i>
                    <span>Product Categories</span>
                </div>
                <ul>
                    <?php
                    require('./db/conn.php');
                    $sql_str = "SELECT * FROM categories ORDER BY name,id";
                    $result = mysqli_query($conn, $sql_str);
                    while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                        <li><a href="category.php?id=<?= $row['id'] ?>"><?= htmlspecialchars($row['name']) ?></a></li>
                    <?php } ?>
                </ul>
            </div>
        </div>
        <div class="col-lg-9">
            <div class="hero__search__form">
                <form action="search.php" method="get">
                    <select name="category">
                        <option value='*'>All Categories</option>
                        <?php
                        require('./db/conn.php');
                        $sql_str = "SELECT * FROM categories ORDER BY name";
                        $result = mysqli_query($conn, $sql_str);
                        while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                            <option value=<?= $row['id'] ?>><?= $row['name'] ?> </option>
                        <?php } ?>
                    </select>
                    <input type="text" name="keyword" placeholder="What do you need?">
                    <button type="submit" class="site-btn">SEARCH</button>
                </form>
            </div>

        </div>

    </div>
    <br>
    <br>
</div>

<!-- Breadcrumb Section Begin -->
<section class="breadcrumb-section set-bg" data-setbg="img/banner/bannerbackround.jpeg">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="breadcrumb__text">
                    <h2>QT Store
                    </h2>
                    <div class="breadcrumb__option">
                        <a href="./index.php">Home</a>
                        <span>Shop</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Breadcrumb Section End -->

<!-- Product Section Begin -->
<section class="product spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-5">
                <div class="sidebar">
                    <div class="sidebar__item">
                        <h4>Product Categories</h4>
                        <ul>
                            <?php
                            require('./db/conn.php');
                            $sql_str = "SELECT * FROM categories ORDER BY name,id";
                            $result = mysqli_query($conn, $sql_str);
                            while ($row = mysqli_fetch_assoc($result)) {
                            ?>
                                <li><a href="category.php?id=<?= $row['id'] ?>"><?= htmlspecialchars($row['name']) ?></a></li>
                            <?php } ?>
                        </ul>
                    </div>
                    <div class="sidebar__item">
                        <!-- <h4>Price</h4>
                        <div class="price-range-wrap">
                            <div class="price-range ui-slider ui-corner-all ui-slider-horizontal ui-widget ui-widget-content"
                                data-min="10" data-max="540">
                                <div class="ui-slider-range ui-corner-all ui-widget-header"></div>
                                <span tabindex="0" class="ui-slider-handle ui-corner-all ui-state-default"></span>
                                <span tabindex="0" class="ui-slider-handle ui-corner-all ui-state-default"></span>
                            </div>
                            <div class="range-slider">
                                <div class="price-input">
                                    <input type="text" id="minamount">
                                    <input type="text" id="maxamount">
                                </div>
                            </div>
                        </div> -->
                    </div>

                    <div class="sidebar__item">
                        <div class="latest-product__text">
                            <h4>Newest Product</h4>
                            <div class="latest-product__slider owl-carousel">
                                <div class="latest-prdouct__slider__item">
                                    <?php
                                    $sql_str = "SELECT products.id as pid, products.name as pname, images, price, 
                                                IFNULL(AVG(feedback.rating), 0) as avg_rating
                                                FROM products
                                                LEFT JOIN feedback ON products.id = feedback.product_id
                                                GROUP BY products.id
                                                ORDER BY products.created_at DESC 
                                                LIMIT 0, 3";
                                    $result = mysqli_query($conn, $sql_str);
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $anh_arr = explode(';', $row['images']);
                                    ?>
                                        <a href="product.php?id=<?= $row['pid'] ?>" class="latest-product__item">
                                            <div class="latest-product__item__pic">
                                                <img src="<?= "admin/" . $anh_arr[0] ?>" alt="">
                                            </div>
                                            <div class="latest-product__item__text">
                                                <h6><?= $row['pname'] ?></h6>

                                                <!-- Display star rating -->
                                                <div class="rating">
                                                    <?php for ($i = 1; $i <= 5; $i++) { ?>
                                                        <i class="fa fa-star<?= $i <= round($row['avg_rating']) ? '' : '-o' ?>" style="color: gold;"></i>
                                                    <?php } ?>
                                                </div>

                                                <span>$ <?= $row['price'] ?></span>
                                            </div>
                                        </a>
                                    <?php
                                    }
                                    ?>
                                </div>
                                <div class="latest-prdouct__slider__item">
                                    <?php
                                    $sql_str = "SELECT products.id as pid, products.name as pname, images, price, 
                                                IFNULL(AVG(feedback.rating), 0) as avg_rating
                                                FROM products
                                                LEFT JOIN feedback ON products.id = feedback.product_id
                                                GROUP BY products.id
                                                ORDER BY products.created_at DESC 
                                                LIMIT 3, 3";
                                    $result = mysqli_query($conn, $sql_str);
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $anh_arr = explode(';', $row['images']);
                                    ?>
                                        <a href="product.php?id=<?= $row['pid'] ?>" class="latest-product__item">
                                            <div class="latest-product__item__pic">
                                                <img src="<?= "admin/" . $anh_arr[0] ?>" alt="">
                                            </div>
                                            <div class="latest-product__item__text">
                                                <h6><?= $row['pname'] ?></h6>

                                                <!-- Display star rating -->
                                                <div class="rating">
                                                    <?php for ($i = 1; $i <= 5; $i++) { ?>
                                                        <i class="fa fa-star<?= $i <= round($row['avg_rating']) ? '' : '-o' ?>" style="color: gold;"></i>
                                                    <?php } ?>
                                                </div>

                                                <span>$ <?= $row['price'] ?></span>
                                            </div>
                                        </a>
                                    <?php
                                    }
                                    ?>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 col-md-7">
                <div class="product__discount">
                    <div class="section-title product__discount__title">
                        <h2>Product Discount</h2>
                    </div>
                    <div class="row">
                        <div class="product__discount__slider owl-carousel">
                            <?php
                            $sql_str = "SELECT products.id as pid, products.name as pname, categories.name as cname, 
                            ROUND((price - disscounted_price)/price*100) as discount, 
                            images, price, stock, disscounted_price, 
                            IFNULL(AVG(feedback.rating), 0) as avg_rating
                            FROM products
                            JOIN categories ON products.category_id = categories.id
                            LEFT JOIN feedback ON products.id = feedback.product_id
                            GROUP BY products.id
                            ORDER BY discount DESC 
                            LIMIT 0, 6";

                            $result = mysqli_query($conn, $sql_str);
                            while ($row = mysqli_fetch_assoc($result)) {
                                $anh_arr = explode(';', $row['images']);
                            ?>
                                <div class="col-lg-4">
                                    <div class="product__discount__item">
                                        <div class="product__discount__item__pic set-bg"
                                            data-setbg="<?= "admin/" . $anh_arr[0] ?>">
                                            <div class="product__discount__percent">-<?= $row['discount'] ?>%</div>
                                            <ul class="product__item__pic__hover">

                                                <!-- <li><a href="#"><i class="fa fa-retweet"></i></a></li> -->
                                                 <!-- <?php
                                                   
                                                    if ($user_role == 'User' && $row['stock'] > 0) {
                                                    ?>
                                                    
                                                    <li><a href="#" class="add-to-cart" data-id="<?= $row['pid'] ?>" data-qty="1"><i class="fa fa-shopping-cart"></i></a></li>
                                                <?php
                                                    } else if ($row['stock'] == 0) {
                                                ?>
                                                  
                                                    <li><span style="color: red;">Currently out of stock</span></li>
                                                <?php
                                                    }
                                                ?> -->
                                            </ul>
                                        </div>
                                        <div class="product__discount__item__text">
                                            <span><?= $row['cname'] ?></span>
                                            <h5><a class="view-detail" href="product.php?id=<?= $row['pid'] ?>"><?= $row['pname'] ?></a></h5>
                                          
                                            <div class="rating">
                                                <?php for ($i = 1; $i <= 5; $i++) { ?>
                                                    <i class="fa fa-star<?= $i <= round($row['avg_rating']) ? '' : '-o' ?>" style="color: gold;"></i>
                                                <?php } ?>
                                            </div>
                                            <div class="product__item__price">$ <?= $row['disscounted_price'] ?> <span> $ <?= $row['price'] ?></span></div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>

                        </div>
                    </div>
                </div>
                <div class="filter__item">
                    <div class="row">
                        <div class="col-lg-4 col-md-5">
                            <!-- <div class="filter__sort">
                                <span>Sort By</span>
                                <select>
                                    <option value="0">Default</option>
                                    <option value="0">Default</option>
                                </select>
                            </div> -->
                        </div>
                        <?php
                        $sql_str = "SELECT products.*, 
                        IFNULL(AVG(feedback.rating), 0) as avg_rating
                        FROM products
                        LEFT JOIN feedback ON products.id = feedback.product_id
                        GROUP BY products.id
                        ORDER BY products.name";

                        $result = mysqli_query($conn, $sql_str);

                        ?>
                        <div class="col-lg-4 col-md-4">
                            <div class="filter__found">
                                <h6>Have <span><?= mysqli_num_rows($result) ?></span> product</h6>
                            </div>
                        </div>
                        <!-- <div class="col-lg-4 col-md-3">
                            <div class="filter__option">
                                <span class="icon_grid-2x2"></span>
                                <span class="icon_ul"></span>   
                            </div>
                        </div> -->
                    </div>
                </div>
                <div class="row">
                    <?php
                    while ($row = mysqli_fetch_assoc($result)) {
                        $anh_arr = explode(';', $row['images']);
                    ?>
                        <div class="col-lg-4 col-md-6 col-sm-6">
                            <div class="product__item">
                                <div class="product__item__pic set-bg" data-setbg="<?= "admin/" . $anh_arr[0] ?>">
                                    <ul class="product__item__pic__hover">
                                        <!-- <li><a href="#"><i class="fa fa-heart"></i></a></li> -->

                                        <?php if ($user_role == 'User' && $row['stock'] > 0) { ?>
                                            <li><a href="#" class="add-to-cart" product-data-id="<?= $row['id'] ?>" product-data-qty="1"><i class="fa fa-shopping-cart"></i></a></li>
                                        <?php } else if ($row['stock'] == 0) {
                                        ?>
                                            <!-- Hiển thị thông báo hết hàng nếu không còn sản phẩm -->
                                            <li><span style="color: red;">Currently out of stock</span></li>
                                        <?php
                                        }
                                        ?>
                                    </ul>
                                </div>
                                <div class="product__item__text">
                                    <h6><a class="view-detail" href="product.php?id=<?= $row['id'] ?>"><?= $row['name'] ?></a></h6>
                                    <!-- Display star rating -->
                                    <div class="rating">
                                        <?php for ($i = 1; $i <= 5; $i++) { ?>
                                            <i class="fa fa-star<?= $i <= round($row['avg_rating']) ? '' : '-o' ?>" style="color: gold;"></i>
                                        <?php } ?>
                                    </div>
                                    <h5>$ <?= $row['price'] ?></h5>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <div class="product__pagination">
                    <a href="#">1</a>
                    <a href="#">2</a>
                    <a href="#">3</a>
                    <a href="#"><i class="fa fa-long-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Product Section End -->

<script>
    $(document).ready(function() {
        $('.add-to-cart').click(function(e) {
            e.preventDefault();
            // var pid = $(this).data('id');
            // var qty = $(this).data('qty');
            var pid;
            var qty;
            if ($(this).attr('discount-data-id')) {
                pid = $(this).attr('discount-data-id');
                qty = $(this).attr('discount-data-qty');

                console.log('Product ID: ' + pid);
                console.log('Quantity: ' + qty);
            } else {
                pid = $(this).attr('product-data-id');
                qty = $(this).attr('product-data-qty');
                console.log('Product ID: ' + pid);
                console.log('Quantity: ' + qty);
            }



            $.ajax({
                type: 'POST',
                url: '<?= $_SERVER['REQUEST_URI'] ?>',
                data: {
                    addtocart: true,
                    pid: pid,
                    qty: qty
                },
                success: function(response) {
                   
                    Swal.fire({
                        title: 'Success!',
                        text: 'The product has been added to the cart!',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });
                },
                error: function() {
                    
                    Swal.fire({
                        title: 'Error!',
                        text: 'An error has occurred. Please try again.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });

       
        $('.view-detail').click(function(e) {
          
            $.ajax({
                type: 'GET',
                success: function() {
                    window.location.href = $(e.target).attr('href');
                }
            });
        });
    });
</script>

<?php

require_once('components/footer.php');
?>