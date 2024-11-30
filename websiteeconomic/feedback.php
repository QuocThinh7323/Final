<?php
session_start();
require_once('./db/conn.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'];
    $rating = (int)$_POST['rating'];
    $comment = trim($_POST['comment']);

    if (!empty($order_id) && !empty($rating) && !empty($comment) && $rating >= 1 && $rating <= 5) {
        $sql_get_product = "SELECT product_id FROM order_details WHERE order_id = ? LIMIT 1";
        $stmt_product = $conn->prepare($sql_get_product);
        $stmt_product->bind_param("i", $order_id);
        $stmt_product->execute();
        $result_product = $stmt_product->get_result();

        if ($result_product->num_rows > 0) {
            $row_product = $result_product->fetch_assoc();
            $product_id = $row_product['product_id'];

            // Check if the user has already submitted feedback
            $sql_check_feedback = "SELECT id FROM feedback WHERE product_id = ? AND user_id = ?";
            $stmt_check = $conn->prepare($sql_check_feedback);
            $stmt_check->bind_param("ii", $product_id, $user_id);
            $stmt_check->execute();
            $result_check = $stmt_check->get_result();

            if ($result_check->num_rows == 0) {
                $sql = "INSERT INTO feedback (product_id, user_id, rating, comment, created_at)
                        VALUES (?, ?, ?, ?, NOW())";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("iiis", $product_id, $user_id, $rating, $comment);

                if ($stmt->execute()) {
                    echo "<script>
                            alert('Feedback submitted successfully!');
                            window.location.href = 'historyorder.php';
                          </script>";
                } else {
                    echo "Error: " . $stmt->error;
                }
                $stmt->close();
            } else {
                echo "<script>
                        alert('You have already submitted feedback for this product.');
                        window.location.href = 'historyorder.php';
                      </script>";
            }
            $stmt_check->close();
        } else {
            echo "Product not found for this order.";
        }
        $stmt_product->close();
    } else {
        echo "<script>alert('Please fill in all fields and provide a valid rating.');</script>";
    }
}

$conn->close();
?>
