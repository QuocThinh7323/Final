<?php
// edit_account.php - Editing an existing account
require('../db/conn.php');

$user_id = $_GET['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $full_name = $_POST['full_name'];
    $phone_number = $_POST['phone_number'];
    $email = $_POST['email'];
    $address = $_POST['address'];

    // Kiểm tra xem có mật khẩu mới không
    $password_set = !empty($_POST['password']);
    if ($password_set) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    }

    // Kiểm tra xem có tệp ảnh mới được tải lên không
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == UPLOAD_ERR_OK) {
        // Lưu tệp ảnh mới
        $avatar = '../uploads/' . basename($_FILES['avatar']['name']);
        move_uploaded_file($_FILES['avatar']['tmp_name'], $avatar);
    } else {
        // Sử dụng ảnh hiện tại nếu không tải lên ảnh mới
        $avatar = $user['avatar'];
    }

    if ($password_set) {
        $stmt = $pdo->prepare("UPDATE users SET username = ?, full_name = ?, password = ?, phone_number = ?, email = ?, avatar = ?, address = ? WHERE user_id = ?");
        $stmt->execute([$username, $full_name, $password, $phone_number, $email, $avatar, $address, $user_id]);
    } else {
        $stmt = $pdo->prepare("UPDATE users SET username = ?, full_name = ?, phone_number = ?, email = ?, avatar = ?, address = ? WHERE user_id = ?");
        $stmt->execute([$username, $full_name, $phone_number, $email, $avatar, $address, $user_id]);
    }

    header('Location: account_management.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Account</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .form-container {
            max-width: 500px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="password"],
        input[type="email"],
        input[type="file"],
        input[type="tel"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            padding: 10px 20px;
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
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Edit Account</h1>
        <form method="POST" enctype="multipart/form-data">
            <label>Username:</label>
            <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
            
            <label>Full Name:</label>
            <input type="text" name="full_name" value="<?= htmlspecialchars($user['full_name']) ?>">
            
            <label>Password (leave blank to keep current):</label>
            <input type="password" name="password">
            
            <label>Phone Number:</label>
            <input type="tel" name="phone_number" value="<?= htmlspecialchars($user['phone_number']) ?>" required>
            
            <label>Email:</label>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
            
            <label>Avatar (current):</label>
            <?php if ($user['avatar']): ?>
                <img src="<?= htmlspecialchars($user['avatar']) ?>" alt="Current Avatar" style="max-width: 100px;"><br>
            <?php endif; ?>
            <input type="file" name="avatar">
            
            <label>Address:</label>
            <input type="text" name="address" value="<?= htmlspecialchars($user['address']) ?>">
            
            <button type="submit">Update Account</button>
        </form>
    </div>
</body>
</html>
