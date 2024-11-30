<?php
// account_management.php - Main account management file
require('../db/conn.php');
require('includes/header.php');
// Check if the user has an Admin role
if ($_SESSION['role'] !== 'Admin') {
    // If the user is not an Admin, display an access-denied message
    echo "<div class='container my-5'><div class='alert alert-danger' role='alert'>You are not authorized to access this page.</div></div>";
    require('includes/footer.php');
    exit(); // Stop further script execution
}
// Fetch all users along with their roles
$stmt = $pdo->query("
    SELECT u.user_id, u.username, u.full_name, u.email, u.phone_number, u.created_at, 
           GROUP_CONCAT(r.role_name SEPARATOR ', ') AS roles
    FROM users u
    LEFT JOIN user_roles ur ON u.user_id = ur.user_id
    LEFT JOIN roles r ON ur.role_id = r.role_id
    GROUP BY u.user_id, u.username, u.full_name, u.email, u.phone_number, u.created_at
");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Account Management</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        /* Custom CSS for additional styling */
        body {
            background-color: #f4f4f9;
        }
        .table-wrapper {
            margin: 20px auto;
            max-width: 1000px;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .btn-custom {
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 8px 12px;
            text-decoration: none;
            margin-right: 5px;
        }
        .btn-custom:hover {
            background-color: #0056b3;
        }
        .btn-action {
            margin-right: 5px;
        }
        .table-wrapper {
    margin: 20px auto;
    max-width: 1200px; /* Điều chỉnh giá trị này tùy theo nhu cầu */
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

    </style>
</head>
<body>
    <div class="container">
        <div class="table-wrapper">
            <h1 class="text-center">Account Management</h1>
            <div class="text-right mb-3">
                <a href="add_account.php" class="btn btn-primary btn-custom">Add New Account</a>
            </div>
            <table class="table table-striped table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>Username</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Phone Number</th>
                        <th>Created At</th>
                        <th>Roles</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['username']) ?></td>
                            <td><?= htmlspecialchars($user['full_name']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td><?= htmlspecialchars($user['phone_number']) ?></td>
                            <td><?= htmlspecialchars($user['created_at']) ?></td>
                            <td><?= htmlspecialchars($user['roles']) ?></td>
                            <td>
                            <div class="d-flex">
                                <a href="edit_account.php?user_id=<?= $user['user_id'] ?>" class="btn btn-success btn-sm btn-action">
                                <i class="bi bi-pencil-square"></i>
                                </a>
                                <a href="assign_roles.php?user_id=<?= $user['user_id'] ?>" class="btn btn-warning btn-sm btn-action">
                                <i class="bi bi-person-gear"></i>

                                </a>
                                <a href="delete_account.php?user_id=<?= $user['user_id'] ?>" class="btn btn-danger btn-sm btn-action" onclick="return confirm('Are you sure you want to delete this account?');">
                                <i class="bi bi-trash"></i>
                                </a>
                            </div>
                        </td>

                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Include Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
