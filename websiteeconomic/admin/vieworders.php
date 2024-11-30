<?php
// Lấy ID của đơn hàng để chỉnh sửa
$id = $_GET['id'];

// Kết nối đến cơ sở dữ liệu
require('../db/conn.php');

function anhdaidien($arrstr, $height)
{
    $arr = explode(';', $arrstr);
    return "<img src='{$arr[0]}' height='{$height}' />";
}
// Lấy thông tin chi tiết đơn hàng
$sql_str = "SELECT * FROM orders WHERE id=$id";
$res = mysqli_query($conn, $sql_str);
$order = mysqli_fetch_assoc($res);

if (isset($_POST['btnUpdate'])) {
    // Lấy dữ liệu từ form
    $status = $_POST['status'];

    // Bắt đầu giao dịch để đảm bảo chỉ cập nhật kho khi thay đổi trạng thái thành công
    mysqli_begin_transaction($conn);

    try {
        // Nếu trạng thái mới là 'Delivered', trừ kho
        if ($status === 'Delivered') {
            // Lấy tất cả sản phẩm trong đơn hàng
            $sql = "SELECT product_id, qty FROM order_details WHERE order_id = $id";
            $order_details_result = mysqli_query($conn, $sql);

            if (!$order_details_result) {
                throw new Exception("Lỗi khi lấy chi tiết đơn hàng: " . mysqli_error($conn));
            }

            // Duyệt qua từng sản phẩm và trừ kho
            while ($item = mysqli_fetch_assoc($order_details_result)) {
                $product_id = $item['product_id'];
                $qty = $item['qty'];

                // Cập nhật kho sản phẩm, đảm bảo số lượng lớn hơn hoặc bằng số lượng mua
                $update_stock_query = "UPDATE products SET stock = stock - $qty WHERE id = $product_id AND stock >= $qty";
                $update_result = mysqli_query($conn, $update_stock_query);

                // Nếu kho không thể cập nhật (không đủ hàng hoặc lỗi truy vấn), rollback và báo lỗi
                if (!$update_result || mysqli_affected_rows($conn) == 0) {
                    throw new Exception("Không đủ kho cho sản phẩm ID: $product_id hoặc không thể cập nhật kho.");
                }
            }
        }

        // Cập nhật trạng thái đơn hàng
        $sql_str = "UPDATE orders SET status = '$status' WHERE id = $id";
        if (!mysqli_query($conn, $sql_str)) {
            throw new Exception("Lỗi khi cập nhật trạng thái đơn hàng: " . mysqli_error($conn));
        }

        // Commit giao dịch nếu mọi thứ thành công
        mysqli_commit($conn);

        // Chuyển hướng đến trang danh sách đơn hàng
        header("location: ./listorders.php");
    } catch (Exception $e) {
        // Rollback giao dịch nếu có lỗi
        mysqli_rollback($conn);
        echo "<div class='alert alert-danger'>Không thể cập nhật đơn hàng: " . $e->getMessage() . "</div>";
    }
} else {
    require('includes/header.php');
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Order Management</title>

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

        <style>
            /* General styling */
            .card {
                border: 1px solid #007BFF;
                /* Light blue border for card */
            }

            .card-header {
                background-color: #007BFF;
                /* Blue header */
                color: white;
                /* White text */
            }

            .btn-primary {
                background-color: #007BFF;
                /* Blue button */
                border-color: #007BFF;
            }

            .btn-primary:hover {
                background-color: #0056b3;
                /* Darker blue on hover */
            }

            .table-striped tbody tr:nth-of-type(odd) {
                background-color: #f2f9ff;
                /* Light blue background for odd rows */
            }

            .thead-dark {
                background-color: #0056b3;
                /* Dark blue for table headers */
                color: white;
            }

            .alert-info {
                background-color: #d9edf7;
                /* Blue alert background */
                color: #31708f;
                border-color: #bce8f1;
            }
        </style>
    </head>

    <body>

        <div class="container my-5">
            <div class="card shadow-lg">
                <div class="card-header">
                    <h2 class="h4 text-center text-white">Order Management - Update Status</h2>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h4 class="text-secondary">Order Details</h4>
                            <table class="table table-striped">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Number</th>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT *, products.name AS pname, products.images AS pimage, order_details.price AS oprice 
                                    FROM products, order_details 
                                    WHERE products.id = order_details.product_id AND order_id = $id";
                                    $res = mysqli_query($conn, $sql);
                                    $stt = 0;
                                    $totalamount = 0;
                                    while ($row = mysqli_fetch_assoc($res)) {
                                        $totalamount += $row['qty'] * $row['oprice'];
                                    ?>
                                        <tr>
                                            <td><?= ++$stt ?></td>
                                            <td><?= anhdaidien($row['images'], "100px") ?></td>
                                            <td>$<?= number_format($row['oprice'], 0, '', '.') ?></td>
                                            <td><?= $row['qty'] ?></td>
                                            <td>$<?= number_format($row['qty'] * $row['oprice'], 0, '', '.') ?></td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                            <div class="alert alert-info">
                                <strong>Total Amount:</strong> $<?= number_format($totalamount, 0, '', '.'); ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <form class="form" method="post" action="#">
                                <h4 class="text-secondary">Customer Information</h4>
                                <div class="form-group">
                                    <label><strong>Customer:</strong></label>
                                    <p><?= $order['firstname'] . ' ' . $order['lastname'] ?></p>
                                </div>
                                <div class="form-group">
                                    <label><strong>Address:</strong></label>
                                    <p><?= $order['address'] ?></p>
                                </div>
                                <div class="form-group">
                                    <label><strong>Phone:</strong></label>
                                    <p><?= $order['phone'] ?></p>
                                </div>
                                <div class="form-group">
                                    <label><strong>Email:</strong></label>
                                    <p><?= $order['email'] ?></p>
                                </div>
                                <div class="form-group">
                                    <label for="status"><strong>Order Status:</strong></label>
                                    <select name="status" id="status" class="form-control">
                                        <option <?= $order['status'] == 'Processing' ? 'selected' : '' ?>>Processing</option>
                                        <option <?= $order['status'] == 'Confirmed' ? 'selected' : '' ?>>Confirmed</option>
                                        <option <?= $order['status'] == 'Shipping' ? 'selected' : '' ?>>Shipping</option>
                                        <option <?= $order['status'] == 'Delivered' ? 'selected' : '' ?>>Delivered</option>
                                        <option <?= $order['status'] == 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                    </select>
                                </div>
                                <button class="btn btn-primary mt-3" name="btnUpdate">Update</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bootstrap JS and dependencies -->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    </body>

    </html>

<?php
    require('includes/footer.php');
}
?>