<?php 
session_start();
require_once('./db/conn.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];

    // Delete the contact message
    $sql = "DELETE FROM contact_messages WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Message deleted successfully.";
    } else {
        $_SESSION['error'] = "Unable to delete message.";
    }

    // Redirect back to the contact management page
    header("Location: admin_contact.php");
    exit();
}

$conn->close();
?>
