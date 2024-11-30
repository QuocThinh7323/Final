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

    function anhdaidien($arrstr, $height)
    {
        // Split the image string into an array
        $arr = explode(';', $arrstr);
        return "<img src='$arr[0]' height='$height' />";
    }
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Management</title>
    <!-- Add any necessary CSS and JS files here -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Blog</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Number</th>
                                <th>Title</th>
                                <th>Avatar</th>
                                <th>Category</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            require('../db/conn.php');
                            $sql_str = "SELECT *, news.id as nid FROM news, newscategories 
                                        WHERE news.newscategory_id = newscategories.id 
                                        ORDER BY news.created_at";
                            $result = mysqli_query($conn, $sql_str);
                            $stt = 0;
                            while ($row = mysqli_fetch_assoc($result)) {
                                $stt++;
                            ?>
                                <tr>
                                    <td><?= $stt ?></td>
                                    <td><?= $row['title'] ?></td>
                                    <td><img src='<?= $row['avatar'] ?>' height='100px' /></td>
                                    <td><?= $row['name'] ?></td>
                                    <td>
                                        <a class="btn btn-warning" href="editnews.php?id=<?= $row['nid'] ?>">
                                        <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <a class="btn btn-danger" href="deletenews.php?id=<?= $row['nid'] ?>" onclick="return confirm('Are you sure you want to delete this news?');">
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

    <?php
    require('includes/footer.php');
    ?>
    <!-- Include Bootstrap JS if needed -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
