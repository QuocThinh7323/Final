<?php
session_start();
require_once('./db/conn.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

$user_id = $_SESSION['user_id'];
$current_password = $_POST['current_password'];
$new_password = $_POST['new_password'];
$confirm_new_password = $_POST['confirm_new_password'];

// Check if new passwords match
if ($new_password !== $confirm_new_password) {
    echo json_encode(['status' => 'error', 'message' => 'New passwords do not match']);
    exit();
}

// Fetch the current password from the database
$query = "SELECT password FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    echo json_encode(['status' => 'error', 'message' => 'User not found']);
    exit();
}

// Verify current password
if (!password_verify($current_password, $user['password'])) {
    echo json_encode(['status' => 'error', 'message' => 'Current password is incorrect']);
    exit();
}

// Hash the new password and update the database
$new_password_hashed = password_hash($new_password, PASSWORD_BCRYPT);

$update_query = "UPDATE users SET password = ? WHERE user_id = ?";
$stmt = $conn->prepare($update_query);
$stmt->bind_param('si', $new_password_hashed, $user_id);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Password updated successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to update password']);
}

$stmt->close();
$conn->close();
?>
