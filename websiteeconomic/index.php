<?php
session_start();
$is_homepage = true;
require_once('components/header.php');
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

    // Kiểm tra và gán giá trị cho biến $user_role
    $user_role = $role_row ? $role_row['role_name'] : null;
} else {
    // Người dùng chưa đăng nhập, gán giá trị null cho $user_role
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

<script>
    $(document).ready(function() {
        $('.add-to-cart').click(function(e) {
            e.preventDefault();
            var pid = $(this).data('id');
            var qty = $(this).data('qty');
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

<link rel="stylesheet" href="./css/style.css" type="text/css">
<link rel="stylesheet" href="./css/index.css" type="text/css">


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
<!-- Banner Slider Begin -->
<div class="hero__banner owl-carousel owl-theme">
    <div class="item">
        <img src="./img/banner/banner.jpeg" alt="Banner 1" class="img-fluid">
    </div>
    <div class="item">
        <img src="./img/banner/banner2.jpeg" alt="Banner 2" class="img-fluid">
    </div>
    <div class="item">
        <img src="./img/banner/banner3.jpeg" alt="Banner 3" class="img-fluid">
    </div>
</div>
<!-- Banner Slider End -->
</div>
</section>
<!-- Hero Section End -->

<!-- Owl Carousel JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

<!-- Initialization Script -->
<script>
    $(document).ready(function() {
        $(".hero__banner").owlCarousel({
            items: 1,
            loop: true,
            autoplay: true,
            autoplayTimeout: 6000,
            nav: true,
            dots: true,
            navText: ["<i class='fa fa-chevron-left'></i>", "<i class='fa fa-chevron-right'></i>"]
        });
    });
</script>


<!-- Categories Section Begin -->
<!-- New Arrivals Section Begin -->
<section class="new-arrivals spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title">
                    <h2>New Arrivals</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <?php
            // Fetching the newest products
            $sql_str = "SELECT products.id as pid, products.name as pname, images, price, stock, categories.slug as cslug, 
            IFNULL(AVG(feedback.rating), 0) as avg_rating
            FROM products
            JOIN categories ON products.category_id = categories.id
            LEFT JOIN feedback ON products.id = feedback.product_id
            GROUP BY products.id
            ORDER BY products.created_at DESC LIMIT 8";

            $result = mysqli_query($conn, $sql_str);
            while ($row = mysqli_fetch_assoc($result)) {
                $anh_arr = explode(';', $row['images']);
            ?>
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="featured__item">
                        <div class="featured__item__pic set-bg" data-setbg="<?= "admin/" . $anh_arr[0] ?>">
                            <ul class="featured__item__pic__hover">
                            <?php 
                        // Kiểm tra vai trò người dùng và số lượng sản phẩm trong kho
                        if ($user_role == 'User' && $row['stock'] > 0) { 
                        ?>
                            <!-- Hiển thị nút thêm vào giỏ hàng nếu sản phẩm còn hàng -->
                            <li><a href="#" class="add-to-cart" data-id="<?= $row['pid'] ?>" data-qty="1"><i class="fa fa-shopping-cart"></i></a></li>
                        <?php 
                        } else if ($row['stock'] == 0) { 
                        ?>
                            <!-- Hiển thị thông báo hết hàng nếu không còn sản phẩm -->
                            <li><span style="color: red;">Currently out of stock</span></li>
                        <?php 
                        } 
                        ?>
                            </ul>
                        </div>
                        <div class="featured__item__text">
                            <h6><a class="view-detail" href="product.php?id=<?= $row['pid'] ?>"><?= $row['pname'] ?></a></h6>

                            <!-- Display star rating -->
                            <div class="rating">
                                <?php for ($i = 1; $i <= 5; $i++) { ?>
                                    <i class="fa fa-star<?= $i <= round($row['avg_rating']) ? '' : '-o' ?>"></i>
                                <?php } ?>
                            </div>

                            <h5><?= $row['price'] ?> $</h5>
                        </div>

                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</section>
<!-- New Arrivals Section End -->

<section class="featured spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title">
                    <h2>Featured Product</h2>
                </div>
                <div class="featured__controls">
                    <ul>
                        <li class="active" data-filter="*">All</li>
                        <?php
                        $sql_str = "SELECT * FROM categories ORDER BY name";
                        $result = mysqli_query($conn, $sql_str);
                        while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                            <li data-filter=".<?= $row['slug'] ?>"><?= $row['name'] ?></li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row featured__filter">
            <?php
            $sql_str = "SELECT products.id as pid, products.name as pname, images, price, stock, categories.slug as cslug, 
            IFNULL(AVG(feedback.rating), 0) as avg_rating
            FROM products
            JOIN categories ON products.category_id = categories.id
            LEFT JOIN feedback ON products.id = feedback.product_id
            GROUP BY products.id";
            $result = mysqli_query($conn, $sql_str);
            while ($row = mysqli_fetch_assoc($result)) {
                $anh_arr = explode(';', $row['images']);
            ?>
                <div class="col-lg-3 col-md-4 col-sm-6 mix <?= $row['cslug'] ?>">
                    <div class="featured__item">
                        <div class="featured__item__pic set-bg" data-setbg="<?= "admin/" . $anh_arr[0] ?>">
                            <ul class="featured__item__pic__hover">
                            <?php 
                        // Kiểm tra vai trò người dùng và số lượng sản phẩm trong kho
                        if ($user_role == 'User' && $row['stock'] > 0) { 
                        ?>
                            <!-- Hiển thị nút thêm vào giỏ hàng nếu sản phẩm còn hàng -->
                            <li><a href="#" class="add-to-cart" data-id="<?= $row['pid'] ?>" data-qty="1"><i class="fa fa-shopping-cart"></i></a></li>
                        <?php 
                        } else if ($row['stock'] == 0) { 
                        ?>
                            <!-- Hiển thị thông báo hết hàng nếu không còn sản phẩm -->
                            <li><span style="color: red;">Currently out of stock</span></li>
                        <?php 
                        } 
                        ?>
                            </ul>
                        </div>
                        <div class="featured__item__text">
                            <h6><a class="view-detail" href="product.php?id=<?= $row['pid'] ?>"><?= $row['pname'] ?></a></h6>

                            <!-- Display star rating -->
                            <div class="rating">
                                <?php for ($i = 1; $i <= 5; $i++) { ?>
                                    <i class="fa fa-star<?= $i <= round($row['avg_rating']) ? '' : '-o' ?>" style="color: gold;"></i>
                                <?php } ?>
                            </div>

                            <h5><?= $row['price'] ?> $</h5>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>

    </div>
</section>

<!-- Featured Section End -->

<!-- Banner Begin -->
<div class="banner">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="banner__pic">
                <img src="./img/hero/banner.jpg" alt="" style="height: 320px; width: auto;">
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="banner__pic">
                    <img src="./img/hero/banner2.jpg" alt="">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Blog Section Begin -->
<section class="from-blog spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title from-blog__title">
                    <h2>From The Blog</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <?php
            $sql_str = "select * from news order by created_at desc limit 0,3 ";
            $result = mysqli_query($conn, $sql_str);
            while ($row = mysqli_fetch_assoc($result)) {
            ?>
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <div class="blog__item">
                        <div class="blog__item__pic">
                            <img src="<?= 'admin/' . $row['avatar'] ?>" alt="">
                        </div>
                        <div class=" blog__item__text">
                            <ul>
                                <li><i class="fa fa-calendar-o"></i> <?= $row['created_at'] ?> </li>
                                <li><i class="fa fa-comment-o"></i> 5</li>
                            </ul>
                            <h5><a href="news.php?id=<?= $row['id'] ?>"><?= $row['title'] ?></a></h5>
                            <p><?= $row['sumary'] ?> </p>
                        </div>
                    </div>
                </div>
            <?php } ?>

        </div>
    </div>
</section>
<!-- Blog Section End -->

<?php

require_once('components/footer.php');
?>