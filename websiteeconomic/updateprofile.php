<?php
session_start();
require_once('./db/conn.php'); // Assuming conn.php contains the database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in.']);
    exit();
}

$user_id = $_SESSION['user_id'];

// Validate and sanitize input
$username = htmlspecialchars(trim($_POST['username']));
$full_name = htmlspecialchars(trim($_POST['full_name']));
$email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
$phone_number = htmlspecialchars(trim($_POST['phone_number']));

// Ensure email is valid
if (!$email) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid email format.']);
    exit();
}

$avatar = "";

// Handle avatar upload if present
if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == UPLOAD_ERR_OK) {
    $upload_dir = 'uploads/';

    // Check if the directory exists, if not, create it
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Validate file extension
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
    $file_extension = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));

    if (!in_array($file_extension, $allowed_extensions)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid file type. Only JPG, JPEG, PNG, and GIF files are allowed.']);
        exit();
    }

    // Generate a unique name for the file to avoid overwriting existing files
    $avatar = uniqid() . '-' . basename($_FILES['avatar']['name']);
    $upload_file = $upload_dir . $avatar;

    if (!move_uploaded_file($_FILES['avatar']['tmp_name'], $upload_file)) {
        echo json_encode(['status' => 'error', 'message' => 'Error uploading avatar.']);
        exit();
    }
}

// Prepare the SQL query to update profile information
$query = "UPDATE users SET username = ?, full_name = ?, email = ?, phone_number = ?" . ($avatar ? ", avatar = ?" : "") . " WHERE user_id = ?";
$stmt = $conn->prepare($query);

// Bind parameters based on whether the avatar is uploaded
if ($avatar) {
    $stmt->bind_param('sssssi', $username, $full_name, $email, $phone_number, $avatar, $user_id);
} else {
    $stmt->bind_param('ssssi', $username, $full_name, $email, $phone_number, $user_id);
}

// Execute the query and check for success
if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Profile updated successfully.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Error updating profile.']);
}

$stmt->close();
?>
