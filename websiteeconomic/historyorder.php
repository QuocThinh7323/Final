 <?php
    ob_start();  // Start output buffering
    session_start();
    $is_homepage = false;
    require_once('./db/conn.php');
    require_once('components/header.php');

    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        // If not logged in, redirect to the login page
        header("Location: login.php");
        exit;
    }
    ob_end_flush();  // End output buffering and flush the buffer
    $user_id = $_SESSION['user_id'];

    function anhdaidien($arrstr, $height)
    {
        $arr = explode(';', $arrstr);
        return "<img src='admin/{$arr[0]}' height='$height' />";
    }
    ?>
    <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Order History</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css"> <!-- Add your custom styles here -->
    <style>
        /* Add any custom styles here */
        .rating i {
            color: #FFD700;
            /* Màu vàng */
            cursor: pointer;
            font-size: 3rem;
        }
    </style>
</head>

<body>
   

    <section class="order-history spad">
        <div class="container">
            <div class="order-history__form">
                <h4>Order History</h4>
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <div class="order-history__details">
                            <table class="table">
                                <tr>
                                    <th>Order ID</th>
                                    <th>Product Name</th>
                                    <th>Product Image</th>
                                    <th>Discounted Price</th>
                                    <th>Total</th>
                                    <th>Order Status</th>
                                    <th>Action</th>
                                </tr>

                                <?php
                                $sql = "SELECT o.id as order_id, p.name as product_name, p.images as product_images, 
                                        od.price as discounted_price, od.total as total_price, o.status as order_status 
                                        FROM orders o 
                                        JOIN order_details od ON o.id = od.order_id 
                                        JOIN products p ON od.product_id = p.id 
                                        WHERE o.user_id = ?";

                                $stmt = $conn->prepare($sql);
                                $stmt->bind_param("i", $user_id);
                                $stmt->execute();
                                $result = $stmt->get_result();

                                if ($result->num_rows === 0) {
                                    echo "No orders found.";
                                } else {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td>{$row['order_id']}</td>";
                                        echo "<td>{$row['product_name']}</td>";
                                        echo "<td>" . anhdaidien($row['product_images'], "100px") . "</td>";
                                        echo "<td>$" . number_format($row['discounted_price'], 0, '', '.') . "</td>";
                                        echo "<td>$" . number_format($row['total_price'], 0, '', '.') . "</td>";
                                        echo "<td>{$row['order_status']}</td>";
                                        echo "<td>";

                                        if ($row['order_status'] === 'Processing') {
                                            echo "<form method='post' action='cancel_order.php'>
                                                    <input type='hidden' name='order_id' value='{$row['order_id']}'>
                                                    <button type='submit' name='cancel_order' class='btn btn-danger'>Cancel Order</button>
                                                  </form>";
                                        }

                                        if ($row['order_status'] === 'Delivered') {
                                            echo "<button class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#feedbackModal' 
                                                  data-order-id='{$row['order_id']}'>Feedback</button>";
                                        }

                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                }
                                ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Bootstrap Feedback Modal -->
    <div class="modal fade" id="feedbackModal" tabindex="-1" aria-labelledby="feedbackModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="feedbackModalLabel">Submit Feedback</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="feedback.php" method="POST">
                        <input type="hidden" name="order_id" id="order-id">

                        <p><strong>Rate the Product:</strong></p>
                        <ul class="h2 rating d-flex justify-content-center pb-3" style="list-style-type:none; padding: 0;">
                            <li class="mx-2"><i class="far fa-star fa-2x text-warning" title="Bad" data-value="1"></i></li>
                            <li class="mx-2"><i class="far fa-star fa-2x text-warning" title="Poor" data-value="2"></i></li>
                            <li class="mx-2"><i class="far fa-star fa-2x text-warning" title="OK" data-value="3"></i></li>
                            <li class="mx-2"><i class="far fa-star fa-2x text-warning" title="Good" data-value="4"></i></li>
                            <li class="mx-2"><i class="far fa-star fa-2x text-warning" title="Excellent" data-value="5"></i></li>
                        </ul>


                        <input type="hidden" name="rating" id="rating-input" required>

                        <p><strong>Feedback:</strong></p>
                        <textarea class="form-control" rows="4" name="comment" required></textarea>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Include Font Awesome and Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Handle star rating selection
        document.querySelectorAll('.rating i').forEach(star => {
            star.addEventListener('click', function() {
                let rating = this.getAttribute('data-value');
                document.getElementById('rating-input').value = rating;

                document.querySelectorAll('.rating i').forEach(star => star.classList.remove('fas'));
                document.querySelectorAll('.rating i').forEach(star => star.classList.add('far'));
                for (let i = 0; i < rating; i++) {
                    document.querySelectorAll('.rating i')[i].classList.remove('far');
                    document.querySelectorAll('.rating i')[i].classList.add('fas');
                }
            });
        });

        // Pass order ID to the modal
        var feedbackModal = document.getElementById('feedbackModal');
        feedbackModal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget;
            var orderId = button.getAttribute('data-order-id');
            var modalInput = feedbackModal.querySelector('#order-id');
            modalInput.value = orderId;
        });
    </script>

    <?php
    require_once('components/footer.php');
    ?>
</body>

</html>