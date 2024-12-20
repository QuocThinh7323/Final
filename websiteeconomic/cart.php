    <?php
    session_start();

    // Include database connection
    require_once('./db/conn.php'); // Ensure this file creates the $conn variable
    require_once('components/header.php');

    // Function to display the representative image
    function anhdaidien($arrstr, $height)
    {
        $arr = explode(';', $arrstr);
        return "<img src='admin/{$arr[0]}' height='$height' />";
    }

    // Function to get product price by cart ID
    function get_price_by_cart_id($cart_id)
    {
        global $conn; // Declare $conn as global
        $query = "SELECT price FROM cart WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $cart_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $item = $result->fetch_assoc();

        return $item['price'] ?? 0; 
    }

    // Get cart items from database
    function get_cart($user_id)
{
    global $conn; 
    $query = "SELECT c.id, c.quantity, c.price, c.total, p.name, p.images, p.stock, p.disscounted_price 
              FROM cart c 
              JOIN products p ON c.product_id = p.id 
              WHERE c.user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}
    // Update cart item quantity
    function update_cart($cart_id, $quantity)
    {
        global $conn; // Declare $conn as global
        // Assume get_price_by_cart_id is a function that retrieves the price of the item
        $price = get_price_by_cart_id($cart_id); // Implement this function based on your needs
        $total = $quantity * $price; // Calculate total based on quantity and price
        $query = "UPDATE cart SET quantity = ?, total = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("idi", $quantity, $total, $cart_id); 
        $stmt->execute();
    }

    // Delete cart item
    function delete_cart($cart_id)
    {
        global $conn; // Declare $conn as global
        $query = "DELETE FROM cart WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $cart_id);
        $stmt->execute();
    }

    // Get total amount of cart
    function get_total_amount($cart) {
        $total = 0;
        foreach ($cart as $item) {
            $price = !empty($item['disscounted_price']) && $item['disscounted_price'] > 0 ? $item['disscounted_price'] : $item['price'];
            $total += $price * $item['quantity'];
        }
        return $total;
    }
    

    // Validate checkout
    function validate_checkout($cart)
    {
        if (empty($cart)) {
            echo "<script>alert('You need to choose a product to checkout.');</script>";
            return false;
        }
        return true;
    }
    function display_price($price, $discounted_price) {
        if (isset($discounted_price) && $discounted_price > 0) {
            return '$' . number_format($discounted_price, 0, '', '.');
        }
        return '$' . number_format($price, 0, '', '.');
    }
    
    

    // Get user ID from session
    $user_id = $_SESSION['user_id']; // Ensure user is logged in
    $cart = get_cart($user_id);
    $total_amount = get_total_amount($cart);
    ?>


    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Your Cart</title>
        <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <style>
            /* Custom button styles */
            .btn-custom {
                border-radius: 20px;
                padding: 6px 12px;
                font-size: 14px;
                text-transform: uppercase;
                font-weight: bold;
                transition: background-color 0.3s, color 0.3s;
            }

            .btn-custom:hover {
                background-color: #0056b3;
                color: white;
            }

            .btn-custom-primary {
                background-color: #007bff;
                border: none;
                color: white;
            }

            .btn-custom-success {
                background-color: #28a745;
                border: none;
                color: white;
            }

            .btn-custom-warning {
                background-color: #ffc107;
                border: none;
                color: black;
            }

            .btn-custom-danger {
                background-color: #dc3545;
                border: none;
                color: white;
            }

            .btn-group-custom {
                margin-right: 10px;
            }

            .btn-group-custom form {
                display: inline;
            }

            .btn-group-custom .btn {
                margin-right: 5px;
            }
        </style>
    </head>

    <body>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const updateTotalAmount = () => {
                    let total = 0;
                    document.querySelectorAll('.product-checkbox:checked').forEach(checkbox => {
                        total += parseFloat(checkbox.getAttribute('data-price'));
                    });
                    document.getElementById('totalAmount').innerText = `$${total.toLocaleString()}`;
                    document.getElementById('checkoutButton').disabled = !document.querySelector('.product-checkbox:checked');
                };

                const toggleSelectAll = (source) => {
                    document.querySelectorAll('.product-checkbox').forEach(checkbox => {
                        checkbox.checked = source.checked;
                    });
                    updateTotalAmount();
                };

             
                document.getElementById('selectAll').addEventListener('change', (e) => {
                    toggleSelectAll(e.target);
                    updateTotalAmount();
                });

          
                document.querySelectorAll('.product-checkbox').forEach(checkbox => {
                    checkbox.addEventListener('change', updateTotalAmount);
                });

                const validateCheckout = () => {
    const selectedItems = document.querySelectorAll('.product-checkbox:checked');
    const outOfStockItems = [];

    selectedItems.forEach(checkbox => {
        const stockStatus = checkbox.getAttribute('data-stock');

        if (stockStatus === "0") {
            outOfStockItems.push(checkbox.value);
        }
    });

    if (outOfStockItems.length > 0) {
        Swal.fire({
            title: 'Out of Stock',
            text: `The following selected items are out of stock: ${outOfStockItems.join(', ')}. Please remove them from your cart to proceed.`,
            icon: 'error',
            confirmButtonText: 'OK'
        });
        return false;
    }

    if (selectedItems.length === 0) {
        Swal.fire({
            title: 'No Product Selected',
            text: "You need to choose a product to checkout.",
            icon: 'warning',
            confirmButtonText: 'OK'
        });
        return false;
    }

    return true;
};


               
                document.querySelector('form.checkout-form').addEventListener('submit', (e) => {
                    if (!validateCheckout()) {
                        e.preventDefault(); 
                    }
                });
            });



            // JavaScript function to handle the update button
            function updateItem(cart_id) {
                const qtyInput = document.querySelector(`input[name="qty[${cart_id}]"]`);
                const quantity = qtyInput.value; 

                if (quantity < 1) {
                    alert('Quantity must be at least 1');
                    return; 
                }

                const formData = new FormData();
                formData.append('id', cart_id);
                formData.append('qty', quantity); 

                fetch('updatecart.php', { 
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Success', data.message, 'success').then(() => {
                                location.reload(); 
                            });
                        } else {
                            Swal.fire('Error', data.message, 'error').then(() => {
                                location.href = 'cart.php';;
                            });;
                        }
                    })
                    .catch(error => {
                        Swal.fire('Error', 'An error occurred while updating the product.', 'error');
                        console.error('Error:', error);
                    });
            }

            // JavaScript function to handle the delete button
            function deleteItem(cart_id) {
                if (confirm('Are you sure you want to remove the product from your cart??')) {
                    const formData = new FormData();
                    formData.append('id', cart_id);

                    console.log('Deleting cart item with ID:', cart_id); 

                    fetch('deletecart.php', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            console.log('Response from deletecart.php:', data); 
                            if (data.success) {
                                Swal.fire('Success', data.message, 'success').then(() => {
                                    location.reload(); // Reload the page to reflect changes
                                });
                            } else {
                                Swal.fire('Error', data.message, 'error').then(() => {
                                    location.reload();
                                });
                            }
                        })
                        .catch(error => {
                            Swal.fire('Error', 'An error occurred while deleting the product.', 'error');
                            console.error('Error:', error);
                        });
                }
            }
        </script>
        <!-- Breadcrumb Section Begin -->
        <section class="breadcrumb-section set-bg" data-setbg="img/banner/bannerbackround.jpeg">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <div class="breadcrumb__text">
                            <h2>Cart</h2>
                            <div class="breadcrumb__option">
                                <a href="./index.php">Home</a>
                                <span>Cart</span>
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
                    <h4>Your Cart</h4>
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="checkout__order">
                                <h4>Your Order</h4>
                                <form class="checkout-form" action="checkout.php" method="post">
                                    <div class="checkout__order__products">Products</div>
                                    <table class="table">
                                        <tr>
                                            <th>
                                                <input type="checkbox" id="selectAll"> <!-- Checkbox Select All -->
                                                Select All
                                            </th>
                                            <th>Number</th>
                                            <th>Product Name</th>
                                            <th>Avatar</th>
                                            <th>Unit Price</th>
                                            <th>Quantity</th>
                                            <th>Cash</th>
                                            <th>Status</th>
                                        </tr>

                                        <?php
                                        $count = 0; // Number
                                        foreach ($cart as $item) {
                                            $subtotal = $item['quantity'] * (isset($item['discounted_price']) && $item['discounted_price'] > 0 ? $item['discounted_price'] : $item['price']);

                                        ?>
                                            <tr>
                                                <td>
                                                <input type="checkbox" class="product-checkbox stock-status" name="selected_items[]" value="<?= $item['id'] ?>" data-price="<?= $subtotal ?>" data-stock="<?= $item['stock'] ?>">

                                                </td>
                                                <td><?= ++$count ?></td>
                                                <td><?= htmlspecialchars($item['name']) ?></td>
                                                <td><?= anhdaidien($item['images'], "100px") ?></td>
                                                <td><?= display_price($item['price'], $item['disscounted_price']) ?></td>
                                                <td><input type="number" name="qty[<?= $item['id'] ?>]" value="<?= $item['quantity'] ?>" min="1" /></td>
                                                <td><?= display_price($item['price'], $item['disscounted_price']) ?></td>

                                                <td>
                                                    <div class="btn-group btn-group-custom" style="display: flex; align-items: center;">
                                                        <?php if ($item['stock'] > 0): ?>
                                                            <button type="button" class="btn btn-custom btn-custom-warning btn-sm ms-2" onclick="updateItem(<?= $item['id'] ?>)">
                                                                <i class="bi bi-pencil-square"></i>
                                                            </button>
                                                        <?php endif; ?>
                                                        <?php if ($item['stock'] == 0): ?>
                                                            <span class="text-danger me-2 stock-status">Currently out of stock</span>
                                                        <?php endif; ?>

                                                        <button type="button" class="btn btn-custom btn-custom-danger btn-sm" onclick="deleteItem(<?= $item['id'] ?>)">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </table>
                                    <div class="checkout__order__total">
                                        Total amount: <span id="totalAmount">$<?= number_format($total_amount, 0, '', '.') ?></span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <a href="shop.php" class="btn btn-custom btn-custom-primary btn-sm">Continue Shopping</a>
                                        <button type="submit" class="btn btn-custom btn-custom-success btn-sm" id="checkoutButton" disabled>Checkout Selected</button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Checkout Section End -->

        <?php
        require_once('components/footer.php');
        ?>
    </body>

    </html>