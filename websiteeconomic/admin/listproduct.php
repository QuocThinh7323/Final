<?php 
require('includes/header.php');

// Check if the user is either 'Admin' or 'Staff'
if ($_SESSION['role'] !== 'Admin') {
    header('Location: ./access_denied.php');
    exit();
}

function anhdaidien($arrstr, $height) {
    $arr = explode(';', $arrstr);
    return "<img src='{$arr[0]}' height='{$height}' />";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        .table th, .table td {
            vertical-align: middle;
            text-align: center;
        }
        .btn {
            margin-right: 5px;
        }
        .container {
            max-width: 1200px;
        }
        .card-header {
            background-color: #007bff;
            color: white;
        }
        .thead-dark th {
            background-color: #343a40;
            color: white;
        }
        /* Custom status badge styling */
        .badge-active {
            background-color: #d4edda;
            color: #155724;
            padding: 5px 10px;
            border-radius: 5px;
        }
        .badge-inactive {
            background-color: #f8d7da;
            color: #721c24;
            padding: 5px 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<div class="container my-5">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h4 class="m-0 font-weight-bold">List All Products</h4>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <input type="text" id="searchInput" class="form-control" placeholder="Search Products...">
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-dark">
                        <tr>
                            <th>Product Name</th>
                            <th>Avatar</th>
                            <th>Category</th>
                            <th>Brand</th>
                            <th>Status</th>
                            <th>Stock</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Product Name</th>
                            <th>Avatar</th>
                            <th>Category</th>
                            <th>Brand</th>
                            <th>Status</th>
                            <th>Stock</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody id="productTableBody">
                        <?php 
                        require('../db/conn.php');
                        $sql_str = "SELECT 
                            products.id as pid,
                            products.name as pname,
                            images,
                            categories.name as cname,
                            brands.name as bname,
                            products.status as pstatus,
                            products.stock as stock
                        FROM products
                        JOIN categories ON products.category_id = categories.id
                        JOIN brands ON products.brand_id = brands.id
                        ORDER BY products.name";
                        
                        $result = mysqli_query($conn, $sql_str);
                        while ($row = mysqli_fetch_assoc($result)) {
                            // Check stock and display appropriate status
                            if ($row['stock'] == 0) {
                                $status = 'Inactive';
                                $statusClass = 'badge-inactive';
                            } else {
                                $status = 'Active';
                                $statusClass = 'badge-active';
                            }
                        ?>
                            <tr>
                                <td><?= htmlspecialchars($row['pname'], ENT_QUOTES) ?></td>
                                <td><?= anhdaidien($row['images'], "100px") ?></td>
                                <td><?= htmlspecialchars($row['cname'], ENT_QUOTES) ?></td>
                                <td><?= htmlspecialchars($row['bname'], ENT_QUOTES) ?></td>
                                <td><span class="badge <?= $statusClass ?>"><?= $status ?></span></td>
                                <td><?= htmlspecialchars($row['stock'], ENT_QUOTES) ?></td>
                                <td>
                                    <a class="btn btn-warning btn-sm" href="editproduct.php?id=<?= $row['pid'] ?>">
                                    <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <a class="btn btn-danger btn-sm" href="deleteproduct.php?id=<?= $row['pid'] ?>" onclick="return confirm('Are you sure you want to delete this product?');">
                                    <i class="bi bi-trash"></i>
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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function () {
        $('#searchInput').on('keyup', function () {
            var value = $(this).val().toLowerCase();
            $('#productTableBody tr').filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });
    });
</script>

<?php
require('includes/footer.php');
?>
</body>
</html>
