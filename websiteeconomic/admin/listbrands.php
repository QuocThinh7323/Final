<?php
require('includes/header.php');

// Check if the user has an Admin role
if ($_SESSION['role'] !== 'Admin') {
    // If the user is not an Admin, display an access-denied message
    echo "<div class='container my-5'><div class='alert alert-danger' role='alert'>You are not authorized to access this page.</div></div>";
    require('includes/footer.php');
    exit(); // Stop further script execution
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List All Brands</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Custom CSS for additional styling -->
    <style>
        .table th, .table td {
            vertical-align: middle;
            text-align: center;
        }
        .btn {
            margin-right: 5px;
        }
    </style>
</head>
<body>

<div class="container my-5">
    <div class="card shadow">
        <div class="card-header py-3 bg-primary text-white">
            <h4 class="m-0 font-weight-bold">List All Brands</h4>
        </div>
        <div class="card-body">
            <!-- Search Box -->
            <div class="mb-3">
                <input type="text" id="searchInput" class="form-control" placeholder="Search Brands...">
            </div>
            <!-- Responsive Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-dark">
                        <tr>
                            <th>Brand</th>
                            <th>Slug</th>
                            <th>Status</th>
                            <th>Operation</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Brand</th>
                            <th>Slug</th>
                            <th>Status</th>
                            <th>Operation</th>
                        </tr>
                    </tfoot>
                    <tbody id="brandTableBody">
                        <?php
                        require('../db/conn.php');
                        $sql_str = "SELECT * FROM brands ORDER BY id";
                        $result = mysqli_query($conn, $sql_str);
                        while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                            <tr>
                                <td><?= $row['name'] ?></td>
                                <td><?= $row['slug'] ?></td>
                                <td><?= $row['status'] ?></td>
                                <td>
                                    <a class="btn btn-warning btn-sm" href="editbrand.php?id=<?= $row['id'] ?>">
                                    <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <a class="btn btn-danger btn-sm" href="deletebrands.php?id=<?= $row['id'] ?>" 
   onclick="return confirm('Are you sure you want to delete this item?');">
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

<!-- Include jQuery and Bootstrap JS -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- Custom JavaScript for filtering table -->
<script>
    $(document).ready(function () {
        $('#searchInput').on('keyup', function () {
            var value = $(this).val().toLowerCase();
            $('#brandTableBody tr').filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
</script>

<?php
require('includes/footer.php');
?>
</body>
</html>
