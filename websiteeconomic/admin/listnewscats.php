<?php
require('includes/header.php');
// Start the session if it's not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is either 'Admin' or 'Staff'
if ($_SESSION['role'] !== 'Admin') {
    // If the user does not have the required role, redirect to the access denied page
    header('Location: ./access_denied.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Category</title>
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
        .container {
            max-width: 1200px; /* Adjust container width as needed */
        }
        .card-header {
            background-color: #007bff;
            color: white;
        }
        .thead-dark th {
            background-color: #343a40;
            color: white;
        }
    </style>
</head>
<body>

<div class="container my-5">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Product Category</h6>
        </div>
        <div class="card-body">
            <!-- Search Box -->
            <div class="mb-3">
                <input type="text" id="searchInput" class="form-control" placeholder="Search Categories...">
            </div>
            <!-- Responsive Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-dark">
                        <tr>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Status</th>
                            <th>Operation</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Status</th>
                            <th>Operation</th>
                        </tr>
                    </tfoot>
                    <tbody id="categoryTableBody">
                        <?php 
                        require('../db/conn.php');
                        $sql_str = "SELECT * FROM newscategories ORDER BY name";
                        $result = mysqli_query($conn, $sql_str);
                        while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                            <tr>
                                <td><?= htmlspecialchars($row['name'], ENT_QUOTES) ?></td>
                                <td><?= htmlspecialchars($row['slug'], ENT_QUOTES) ?></td>
                                <td><?= htmlspecialchars($row['status'], ENT_QUOTES) ?></td>
                                <td>
                                    <a class="btn btn-warning btn-sm" href="editnewscategory.php?id=<?= $row['id'] ?>">
                                    <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <a class="btn btn-danger btn-sm" href="deletenewscategory.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this item?');">
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
            $('#categoryTableBody tr').filter(function () {
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
