<?php
session_start();
require_once('./db/conn.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if user is not logged in
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Check if the cancel order request is submitted
if (isset($_POST['cancel_order'])) {
    $order_id = $_POST['order_id'];

    // Verify if the order belongs to the user and its status is "Processing"
    $sql = "SELECT id FROM orders WHERE id = ? AND user_id = ? AND status = 'Processing'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $order_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Order is found and status is "Processing", proceed to cancel the order
        $update_sql = "UPDATE orders SET status = 'Cancelled' WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("i", $order_id);

        if ($update_stmt->execute()) {
            // Successfully updated the order status
            $_SESSION['success_message'] = "Order has been cancelled successfully.";
        } else {
            // Failed to update the order status
            $_SESSION['error_message'] = "Failed to cancel the order. Please try again.";
        }
    } else {
        // Order not found or status is not "Processing"
        $_SESSION['error_message'] = "Invalid order or the order cannot be cancelled.";
    }

    // Redirect back to order history page
    header("Location: historyorder.php");
    exit();
} else {
    // Redirect to order history if accessed directly
    header("Location: historyorder.php");
    exit();
}
?>
