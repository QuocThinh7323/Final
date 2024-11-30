<?php
// assign_roles.php - Trang để gán quyền cho người dùng
require('../db/conn.php');

// Lấy ID người dùng từ URL
$user_id = $_GET['user_id'];

// Lấy tất cả các vai trò từ bảng roles
$all_roles_stmt = $pdo->query("SELECT * FROM roles");
$all_roles = $all_roles_stmt->fetchAll(PDO::FETCH_ASSOC);

// Lấy các vai trò hiện tại được gán cho người dùng
$user_roles_stmt = $pdo->prepare("SELECT role_id FROM user_roles WHERE user_id = :user_id");
$user_roles_stmt->execute(['user_id' => $user_id]);
$user_roles = $user_roles_stmt->fetchAll(PDO::FETCH_COLUMN, 0);

// Xử lý gửi form để cập nhật vai trò
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy các vai trò đã chọn từ form
    $selected_roles = $_POST['roles'] ?? [];

    // Bắt đầu transaction
    $pdo->beginTransaction();
    
    // Xóa các vai trò hiện tại
    $pdo->prepare("DELETE FROM user_roles WHERE user_id = :user_id")->execute(['user_id' => $user_id]);

    // Thêm các vai trò mới
    $insert_stmt = $pdo->prepare("INSERT INTO user_roles (user_id, role_id) VALUES (:user_id, :role_id)");
    foreach ($selected_roles as $role_id) {
        $insert_stmt->execute(['user_id' => $user_id, 'role_id' => $role_id]);
    }

    // Hoàn tất transaction
    $pdo->commit();

    // Chuyển hướng sau khi cập nhật
    header("Location: account_management.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Assign Roles</title>
    <!-- Bao gồm Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f4f4f9;
            font-family: 'Arial', sans-serif;
        }
        .form-wrapper {
            margin: 20px auto;
            max-width: 600px;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .btn-custom {
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 10px 20px;
            text-decoration: none;
            margin-right: 5px;
        }
        .btn-custom:hover {
            background-color: #0056b3;
        }
        .btn-secondary-custom {
            background-color: #6c757d;
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 10px 20px;
            text-decoration: none;
        }
        .btn-secondary-custom:hover {
            background-color: #5a6268;
        }
        .form-check-input {
            margin-top: 10px;
            margin-right: 10px;
        }
        .form-check-label {
            margin-bottom: 0;
        }
        .select-all {
            cursor: pointer;
            color: #007bff;
            text-decoration: underline;
            display: inline-block;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-wrapper">
            <h2 class="text-center">Assign Roles to User</h2>
            <p class="text-center select-all" id="select-all">Select All</p>
            <form method="POST">
                <?php foreach ($all_roles as $role): ?>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="roles[]" value="<?= htmlspecialchars($role['role_id']) ?>" 
                            <?= in_array($role['role_id'], $user_roles) ? 'checked' : '' ?>>
                        <label class="form-check-label">
                            <?= htmlspecialchars($role['role_name']) ?>
                        </label>
                    </div>
                <?php endforeach; ?>
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary btn-custom">Update Roles</button>
                    <a href="account_management.php" class="btn btn-secondary btn-secondary-custom">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Bao gồm Bootstrap JS và các phụ thuộc -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- JavaScript tùy chỉnh -->
    <script>
        document.getElementById('select-all').addEventListener('click', function() {
            let checkboxes = document.querySelectorAll('.form-check-input');
            checkboxes.forEach(checkbox => checkbox.checked = true);
        });
    </script>
</body>
</html>
