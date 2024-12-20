    <?php
    session_start();
    $is_homepage = false;
    require_once('./db/conn.php'); // Include your database connection file

    // Function to get cart items from database
    function get_cart($user_id)
    {
        global $conn; 
        $query = "SELECT c.id, c.product_id, c.quantity AS qty, c.price, c.total, p.name, p.images 
                FROM cart c 
                JOIN products p ON c.product_id = p.id 
                WHERE c.user_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Check if the user is logged in
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'NULL';

    // Get cart items from database
    $cart = get_cart($user_id);

    // Get selected items from the form submission (if any)
    $selected_items = isset($_POST['selected_items']) ? $_POST['selected_items'] : [];

    // Initialize total
    $totalcheckout = 0;
    $selected_cart_items = [];

    // Filter the cart to include only selected items
    if (!empty($selected_items)) {
        foreach ($cart as $item) {
            if (in_array((string)$item['id'], $selected_items)) {
                $selected_cart_items[] = $item;
                $item_price = isset($item['disscounted_price']) && !is_null($item['disscounted_price'])
                    ? $item['disscounted_price']
                    : $item['price'];
                $item_qty = isset($item['qty']) ? $item['qty'] : 0; // Fallback if qty is not found
                $totalcheckout += $item_qty * $item_price;
            }
        }
    }

    // else {
    //     // If no items are selected, assume all items in the cart are selected
    //     $selected_cart_items = $cart;
    //     foreach ($selected_cart_items as $item) {
    //         $item_price = isset($item['disscounted_price']) && !is_null($item['disscounted_price'])
    //             ? $item['disscounted_price']
    //             : $item['price'];
    //         $item_qty = isset($item['qty']) ? $item['qty'] : 0; // Fallback if qty is not found
    //         $total += $item_qty * $item_price;
    //     }
    // }

    // Handle order placement
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btOrder'])) {
        // Validate required fields
        $required_fields = ['firstname', 'lastname', 'phone', 'email', 'address'];
        $missing_fields = [];

        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                $missing_fields[] = $field;
            }
        }

        if (!empty($missing_fields)) {
            echo "<script>alert('The following fields are missing: " . implode(", ", $missing_fields) . "');</script>";
        } else {
            // Sanitize user input
            $firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
            $lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
            $phone = mysqli_real_escape_string($conn, $_POST['phone']);
            $email = mysqli_real_escape_string($conn, $_POST['email']);
            $address = mysqli_real_escape_string($conn, $_POST['address']);
            $payment_method = mysqli_real_escape_string($conn, $_POST['payment_method']);

            // Start database transaction
            mysqli_begin_transaction($conn);
            try {
                // Insert into orders table
                $sqli = "INSERT INTO orders (user_id, firstname, lastname, address, phone, email, status, payment_method, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?, ?, 'Processing', ?, NOW(), NOW())";
                $stmt = $conn->prepare($sqli);
                $stmt->bind_param("issssss", $user_id, $firstname, $lastname, $address, $phone, $email, $payment_method);
                $stmt->execute();
                if (!$stmt) {
                    throw new Exception("Error inserting into orders: " . $stmt->error);
                }

                $last_order_id = mysqli_insert_id($conn);

                // Insert into order_details
                foreach ($selected_cart_items as $item) {
                    $pid = $item['product_id'];

                    // Check if the product exists in the products table
                    $product_check_sql = "SELECT * FROM `products` WHERE id = " . $pid;
                    $product_stmt = $conn->prepare($product_check_sql);
                    // $product_stmt->bind_param("i", $pid);
                    $product_stmt->execute();
                    $product_result = $product_stmt->get_result();

                    if (!($product_result)) {
                        echo "Product with ID $pid does not exist.";
                        continue; // Skip this product or handle as necessary
                    }

                    $price = isset($item['disscounted_price']) && !is_null($item['disscounted_price'])
                        ? $item['disscounted_price']
                        : $item['price'];
                    $qty = isset($item['qty']) ? $item['qty'] : 0; // Fallback if qty is not found
                    $total_item = $price * $qty;

                    // Insert into order_details
                    $sqli2 = "INSERT INTO order_details (order_id, product_id, price, qty, total, created_at, updated_at) 
                            VALUES ($last_order_id, $pid, $price, $qty, $total_item, NOW(), NOW())";
                    if (!mysqli_query($conn, $sqli2)) {
                        throw new Exception("Error inserting into order_details: " . mysqli_error($conn));
                    }
                    // Delete item from cart

                    // foreach ($selected_cart_items as $item) {
                    //     $pid = $item['product_id'];


                    echo "product_id: " . $pid;
                    echo "cart_id: " . $item['id'];
                    echo "prod_id: " . $user_id;

                    // Xóa sản phẩm đã chọn trong giỏ hàng
                    $delete_sql = "DELETE FROM cart WHERE user_id = ? AND product_id = ? AND id = ?";
                    $delete_stmt = $conn->prepare($delete_sql);
                    $delete_stmt->bind_param("iii", $user_id, $pid, $item['id']); 
                    $delete_stmt->execute();

                  
                    // if ($delete_stmt->affected_rows == 0) {
                    //     echo "No rows deleted for product ID $pid. Check if it exists in the cart.";
                    // }


                }

                // Commit transaction
                mysqli_commit($conn);

                // Redirect based on payment method
                if ($payment_method === 'bank_transfer') {
                    // Pass the total amount and other details via URL parameters (or use POST)
                    header("Location: bank_transfer_info.php?total=" . urlencode($totalcheckout) . "&order_id=" . urlencode($last_order_id));
                } else {
                    header("Location: thankyou.php?order_id=" . urlencode($last_order_id));
                }
                exit();
            } catch (Exception $e) {
                mysqli_rollback($conn);
                echo "<script>alert('" . $e->getMessage() . "');</script>";
                error_log($e->getMessage());
            }
        }
    }
    // Include header
    require_once('components/header.php');
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Checkout</title>
        <!-- Link to CSS files -->
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/jquery-ui.min.css">
        <link rel="stylesheet" href="css/owl.carousel.min.css">
        <link rel="stylesheet" href="css/font-awesome.min.css">
        <link rel="stylesheet" href="css/slicknav.min.css">
        <link rel="stylesheet" href="css/style.css">
        <style>
            .breadcrumb__text {
                display: flex;
                flex-direction: column;
                align-items: center;
                text-align: center;
            }
        </style>
    </head>

    <body>

        <!-- Breadcrumb Section Begin -->
        <section class="breadcrumb-section set-bg" data-setbg="img/banner/bannerbackround.jpeg">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="breadcrumb__text">
                            <h2>Checkout</h2>
                            <div class="breadcrumb__option">
                                <a href="./index.php">Home</a>
                                <span>Checkout</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Breadcrumb Section End -->

        <!-- Checkout Section Begin -->
        <section class="checkout spad">
            <div class="container">
                <div class="checkout__form">
                    <h4>Customer Information</h4>
                    <form action="checkout.php" method="post">
                        <div class="row">
                            <div class="col-lg-7 col-md-6">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="checkout__input">
                                            <p>First Name<span>*</span></p>
                                            <input type="text" name="firstname" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="checkout__input">
                                            <p>Last Name<span>*</span></p>
                                            <input type="text" name="lastname" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="checkout__input">
                                    <p>Shipping Address<span>*</span></p>
                                    <input type="text" placeholder="Address" name="address" required>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="checkout__input">
                                            <p>Phone Number<span>*</span></p>
                                            <input type="text" name="phone" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="checkout__input">
                                            <p>Email<span>*</span></p>
                                            <input type="email" name="email" required>
                                        </div>
                                    </div>
                                </div>


                            </div>

                            <div class="col-lg-5 col-md-6"> <!-- Tăng kích thước cột -->
                                <div class="checkout__order">
                                    <h4>Your Order</h4>
                                    <div class="checkout__order__products">Products <span>Total</span></div>
                                    <ul>
                                        <?php foreach ($selected_cart_items as $item): ?>
                                            <?php
                                            $item_price = isset($item['disscounted_price']) && !is_null($item['disscounted_price'])
                                                ? $item['disscounted_price']
                                                : $item['price'];
                                            $item_total = isset($item['qty']) ? $item['qty'] * $item_price : 0;
                                            ?>
                                            <li>
                                                <?php echo $item['name']; ?> x <?php echo isset($item['qty']) ? $item['qty'] : 0; ?>
                                                <span>$<?php echo number_format($item_total, 2); ?></span>
                                                <input type="hidden" name="selected_items[]" value="<?php echo $item['id']; ?>">
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                    <div class="checkout__order__total">Total <span>$<?php echo number_format($totalcheckout, 2); ?></span></div>
                                    <label for="payment_method">Payment Method</label>
                                    <div class="checkout__input__select">
                                        <label>
                                            <input type="radio" name="payment_method" value="cash" required>
                                            Cash
                                        </label>
                                        <label>
                                            <input type="radio" name="payment_method" value="bank_transfer" required>
                                            Bank Transfer
                                        </label>
                                    </div>
                                    <button type="submit" class="site-btn" name="btOrder">Place Order</button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </section>
        <!-- Checkout Section End -->

        <?php require_once('components/footer.php'); ?>

        <!-- Link to JS files -->
        <script src="js/script.js"></script>
    </body>

    </html>