<?php
require_once('./db/conn.php'); // Connect to the database

// Get order_id from the URL
$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

// Get order information
$order_query = "SELECT * FROM orders WHERE id = ?";
$order_stmt = $conn->prepare($order_query);
$order_stmt->bind_param("i", $order_id);
$order_stmt->execute();
$order_result = $order_stmt->get_result();
$order = $order_result->fetch_assoc();

if (!$order) {
    echo "<script>alert('Order does not exist!'); window.location.href = './index.php';</script>";
    exit();
}

// Get order details
$order_details_query = "SELECT od.*, p.name FROM order_details od JOIN products p ON od.product_id = p.id WHERE od.order_id = ?";
$order_details_stmt = $conn->prepare($order_details_query);
$order_details_stmt->bind_param("i", $order_id);
$order_details_stmt->execute();
$order_details_result = $order_details_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You!</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .thank-you-section {
            padding: 50px 0;
        }
        .thank-you-section h2 {
            color: #28a745;
            font-weight: bold;
        }
        .thank-you-section .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .thank-you-section .list-group-item {
            border: none;
            font-size: 16px;
        }
        .total {
            font-size: 18px;
            font-weight: bold;
            color: #343a40;
        }
        .thank-you-section p {
            font-size: 16px;
        }
    </style>
</head>
<body>

<section class="breadcrumb-section set-bg" data-setbg="img/banner/bannerbackround.jpeg">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="breadcrumb__text">
                    <h2>Thank You</h2>
                    <div class="breadcrumb__option">
                        <a href="./index.php">Home</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="thank-you-section">
    <div class="container">
        <div class="text-center mb-4">
            <h2>Thank you for placing your order!</h2>
            <p class="lead">We have received your order.</p>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h4><strong>Your Order Information:</strong></h4>
            </div>
            <div class="card-body">
                <p><strong>Name:</strong> <?php echo htmlspecialchars($order['firstname'] . ' ' . $order['lastname']); ?></p>
                <p><strong>Address:</strong> <?php echo htmlspecialchars($order['address']); ?></p>
                <p><strong>Phone Number:</strong> <?php echo htmlspecialchars($order['phone']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($order['email']); ?></p>
                <p><strong>Payment Method:</strong> <?php echo htmlspecialchars($order['payment_method']); ?></p>
                <p><strong>Status:</strong> <?php echo htmlspecialchars($order['status']); ?></p>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h4><strong>Order Details:</strong></h4>
            </div>
            <div class="card-body">
                <ul class="list-group">
                    <?php 
                    $total = 0; // Variable to calculate the total
                    while ($detail = $order_details_result->fetch_assoc()): 
                        $item_total = $detail['qty'] * $detail['price']; // Calculate total for each product
                        $total += $item_total; // Add to the total
                    ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <?php echo htmlspecialchars($detail['name']); ?> x <?php echo htmlspecialchars($detail['qty']); ?>
                        </li>
                    <?php endwhile; ?>
                </ul>
                <div class="mt-3 total">
                    Total: <strong>$<?php echo number_format($total, 2); ?></strong>
                </div>
            </div>
        </div>
    </div>
</section>



<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php require_once('components/footer.php'); ?>