<?php 
    require('includes/header.php');
    // Check if the user is either 'Admin' or 'Staff'
    if ($_SESSION['role'] != 'Admin') {
        // If the user does not have the required role, redirect to the access denied page
        header('Location: ./access_denied.php');
        exit();
    }
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order List</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .Processing {
            background-color: orange;
        }
        .Confirmed {
            background-color: yellowgreen;
        }
        .Shipping {
            background-color: lightblue;
        }
        .Delivered {
            background-color: lightgreen;
            color: green;
        }
        .Cancelled {
            background-color: lightpink;
            color: red;
        }
        .status {
            padding: 5px 10px;
            border-radius: 5px;
            display: inline-block;
            font-weight: bold;
            font-size: 12px;
            text-align: center;
            width: 60%;
        }
        td {
            vertical-align: middle;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">List Order</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Order code</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Payment Method</th>
                                <th>Action</th>   
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            require('../db/conn.php');
                            $sql_str = "SELECT * FROM orders ORDER BY created_at";
                            $result = mysqli_query($conn, $sql_str);
                            $stt = 0;
                            while ($row = mysqli_fetch_assoc($result)){
                                $stt++;
                                $statusClass = $row['status']; // CSS class for status
                            ?>
                                <tr>
                                    <td><?=$row['id']?></td>
                                    <td><?=$row['created_at']?></td>
                                    <td><span class='status <?=$statusClass?>'><?=$row['status']?></span></td>
                                    <td><?=$row['payment_method']?></td>
                                    <td>
                                        <a class="btn btn-warning" href="./vieworders.php?id=<?=$row['id']?>">
                                            <i class="bi bi-eye"></i>
                                        </a>  
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>                                
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?php
    require('includes/footer.php');
    ?>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
