<?php
// connect.php - Replace with your actual connection details
require_once('../db/conn.php');

// Lấy tất cả các vai trò từ bảng roles
$roles_stmt = $pdo->query("SELECT * FROM roles");
$roles = $roles_stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $full_name = $_POST['full_name'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $phone_number = $_POST['phone_number'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $selected_role = $_POST['role'] ?? null; // Lấy vai trò đã chọn

    // Xử lý tải lên ảnh đại diện
    $avatar = '';
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == UPLOAD_ERR_OK) {
        $avatar = '../uploads/' . basename($_FILES['avatar']['name']);
        move_uploaded_file($_FILES['avatar']['tmp_name'], $avatar);
    }

    // Câu lệnh SQL để chèn tài khoản mới vào bảng users
    $sql = "INSERT INTO users (username, full_name, password, phone_number, email, avatar, address) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $username, $full_name, $password, $phone_number, $email, $avatar, $address);

    if ($stmt->execute()) {
        // Lấy ID của tài khoản mới được thêm vào
        $new_user_id = $stmt->insert_id;

        // Chèn vai trò đã chọn vào bảng user_roles nếu có
        if ($selected_role) {
            $role_stmt = $pdo->prepare("INSERT INTO user_roles (user_id, role_id) VALUES (?, ?)");
            $role_stmt->execute([$new_user_id, $selected_role]);
        }

        // Chuyển hướng đến trang account_management.php sau khi thêm thành công
        header("Location: account_management.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Account</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .form-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 400px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }
        input[type="text"],
        input[type="password"],
        input[type="email"],
        input[type="file"],
        select {
            width: calc(100% - 20px);
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            border: none;
            border-radius: 4px;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .error {
            color: red;
            font-size: 14px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Add New Account</h1>
        <form method="POST" id="accountForm" enctype="multipart/form-data">
            <div class="error" id="error"></div>
            <label>Username:</label>
            <input type="text" name="username" required><br>
            <label>Full Name:</label>
            <input type="text" name="full_name"><br>
            <label>Password:</label>
            <input type="password" name="password" required><br>
            <label>Phone Number:</label>
            <input type="text" name="phone_number" required><br>
            <label>Email:</label>
            <input type="email" name="email" required><br>
            <label>Avatar:</label>
            <input type="file" name="avatar"><br>
            <label>Address:</label>
            <input type="text" name="address"><br>
            <label>Role:</label>
            <select name="role">
                <option value="">Select a role</option>
                <?php foreach ($roles as $role): ?>
                    <option value="<?= htmlspecialchars($role['role_id']) ?>"><?= htmlspecialchars($role['role_name']) ?></option>
                <?php endforeach; ?>
            </select><br>
            <button type="submit">Add Account</button>
        </form>
    </div>

    <script>
        document.getElementById('accountForm').addEventListener('submit', function(event) {
            const errorDiv = document.getElementById('error');
            errorDiv.innerHTML = ''; // Clear previous errors
            let errors = [];

            const phoneNumber = document.querySelector('input[name="phone_number"]').value;
            const email = document.querySelector('input[name="email"]').value;

            // Basic phone number validation
            const phoneRegex = /^[0-9]{10,11}$/;
            if (!phoneRegex.test(phoneNumber)) {
                errors.push('Invalid phone number format. Must be 10-11 digits.');
            }

            // Basic email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                errors.push('Invalid email format.');
            }

            // Show errors if any
            if (errors.length > 0) {
                event.preventDefault();
                errorDiv.innerHTML = errors.join('<br>');
            }
        });
    </script>
</body>
</html>
