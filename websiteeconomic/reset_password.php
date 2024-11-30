
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
        .container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            max-width: 400px;
            width: 100%;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        input[type="password"], input[type="hidden"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #218838;
        }
        p {
            text-align: center;
            color: #d9534f; /* Red color for error messages */
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Reset Password</h2>
        <form action="reset_password.php" method="POST">
            <?php if (isset($_GET['token'])): ?>
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>" required>
                <input type="password" name="password" placeholder="Enter new password" required>
                <button type="submit">Reset Password</button>
            <?php else: ?>
                <p>Invalid or missing token.</p>
            <?php endif; ?>
        </form>

        <?php
        require_once('./db/conn.php');

        // Initialize message variable
        $message = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (!empty($_POST['token']) && !empty($_POST['password'])) {
                $token = $_POST['token'];
                $new_password = password_hash($_POST['password'], PASSWORD_BCRYPT);

                // Verify token
                $stmt = $conn->prepare("SELECT email FROM password_resets WHERE token = ?");
                $stmt->bind_param("s", $token);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $email = $row['email'];

                    // Update new password
                    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
                    $stmt->bind_param("ss", $new_password, $email);
                    $stmt->execute();

                    // Delete used token
                    $stmt = $conn->prepare("DELETE FROM password_resets WHERE token = ?");
                    $stmt->bind_param("s", $token);
                    $stmt->execute();

                    // Set success message
                    $message = "Password has been reset successfully.";
                } else {
                    // Set error message for invalid token
                    $message = "Invalid token.";
                }
            } else {
                // Set error message for missing fields
                $message = "Token and new password are required.";
            }
        }

        // Display message if available
        if ($message) {
            echo "<p>$message</p>";
            // Redirect after 3 seconds if the password was reset successfully
            if ($message === "Password has been reset successfully.") {
                echo "<script>
                        setTimeout(function() {
                            window.location.href = 'login.php';
                        }, 3000); // Redirect after 3 seconds
                      </script>";
            }
        }
        ?>
    </div>
</body>
</html>
