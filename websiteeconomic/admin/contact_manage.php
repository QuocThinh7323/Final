<?php 
session_start();
require('includes/header.php');
require_once('../db/conn.php'); // Database connection

// Fetch all contact messages
$sql = "SELECT * FROM contact_message ORDER BY submitted_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Contacts</title>
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
            <h4 class="m-0 font-weight-bold">Manage Contact Messages</h4>
        </div>
        <div class="card-body">
            <!-- Search Box -->
            <div class="mb-3">
                <input type="text" id="searchInput" class="form-control" placeholder="Search Contacts...">
            </div>
            <!-- Responsive Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Message</th>
                            <th>Date Submitted</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Message</th>
                            <th>Date Submitted</th>
                            <th>Actions</th>
                        </tr>
                    </tfoot>
                    <tbody id="contactTableBody">
                        <?php if ($result->num_rows > 0): ?>
                            <?php while($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $row['message_id']; ?></td>
                                    <td><?= $row['name']; ?></td>
                                    <td><?= $row['user_email']; ?></td>
                                    <td><?= $row['message']; ?></td>
                                    <td><?= $row['submitted_at']; ?></td>
                                    <td>
                                        <form action="delete_contact.php" method="POST" style="display:inline;">
                                            <input type="hidden" name="id" value="<?= $row['message_id']; ?>">
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?');">
                                            <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6">No contact messages found.</td>
                            </tr>
                        <?php endif; ?>
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
            $('#contactTableBody tr').filter(function () {
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
