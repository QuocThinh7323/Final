<?php
// Connect to the database
require('../db/conn.php');

// Check if 'id' is set in the URL and sanitize it
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Retrieve category information
$sql_str = "SELECT * FROM categories WHERE id = ?";
$stmt = $conn->prepare($sql_str);
$stmt->bind_param("i", $id);
$stmt->execute();
$cat = $stmt->get_result()->fetch_assoc();

if (!$cat) {
    die('Error: Category not found.');
}

// Handle form submission
if (isset($_POST['btnUpdate'])) {
    $name = $_POST['name'];
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
    
    // Update category information
    $sql_str2 = "UPDATE categories SET name = ?, slug = ? WHERE id = ?";
    $stmt2 = $conn->prepare($sql_str2);
    $stmt2->bind_param("ssi", $name, $slug, $id);
    $stmt2->execute();
    
    // Redirect to the list of categories
    header("Location: listcats.php");
    exit();
} else {
    require('includes/header.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Category</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Include Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- Custom CSS -->
    <style>
        .container {
            max-width: 800px; /* Increase container width */
        }
        .card-header {
            background-color: #007bff;
            color: white;
        }
        .status-message {
            display: none;
            margin-top: 20px;
        }
        .status-message.success {
            color: #28a745;
        }
        .status-message.error {
            color: #dc3545;
        }
        .form-control-user {
            width: 100%; /* Ensure form controls take full width of the container */
        }
        .btn-update {
            margin-top: 20px;
        }
        .text-center-custom {
            text-align: center; /* Center align text */
        }
        .form-group label {
            font-weight: bold;
            text-align: center; 
            display: block; 
            margin-bottom: 10px; 
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="card o-hidden border-0 shadow-lg">
        <div class="card-header text-center">
            <h4 class="font-weight-bold">Update Category</h4>
        </div>
        <div class="card-body">
            <form class="user" method="post" id="updateForm">
                <div class="form-group">
                    <label for="name">Category Name</label>
                    <input type="text" class="form-control form-control-user"
                           id="name" name="name" placeholder="Category Name"
                           value="<?= htmlspecialchars($cat['name'], ENT_QUOTES) ?>">
                </div>
                <div class="text-center-custom"> 
                    <button type="submit" class="btn btn-primary btn-update" name="btnUpdate">
                        <i class="fas fa-save"></i> Update
                    </button>
                </div>
                <div class="status-message" id="statusMessage"></div>
            </form>
        </div>
    </div>
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function () {
        $('#updateForm').on('submit', function (e) {
            e.preventDefault(); 

            $.ajax({
                url: '',
                type: 'POST',
                data: $(this).serialize(),
                success: function (response) {
                    $('#statusMessage').removeClass('error').addClass('success').text('Category updated successfully!').fadeIn();
                    setTimeout(function () {
                        window.location.href = 'listcats.php'; 
                    }, 2000);
                },
                error: function (xhr, status, error) {
                    $('#statusMessage').removeClass('success').addClass('error').text('An error occurred while updating the category.').fadeIn();
                }
            });
        });
    });
</script>

<?php
    require('includes/footer.php');
}
?>
</body>
</html>
