<?php
session_start();
require_once('../db/conn.php');

// // Kiểm tra quyền quản trị
// if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
//     header("Location: index.php");
//     exit();
// }

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $feedback_id = $_GET['id']; 

    // Xóa feedback từ bảng
    $sql = "DELETE FROM feedback WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $feedback_id);
    // Execute and check if deletion was successful
    if ($stmt->execute()) {
        header("Location: manage_feedback.php"); // Redirect after successful deletion
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();
