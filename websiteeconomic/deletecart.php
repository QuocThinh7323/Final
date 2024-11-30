<?php
require_once('./db/conn.php');
session_start();

$userId = $_SESSION['user_id'] ?? null;
$itemId = $_POST['id'] ?? null;

if ($userId && $itemId) {
    $stmt = $pdo->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
    $success = $stmt->execute([$itemId, $userId]);

    if ($success) {
        echo json_encode(['success' => true, 'message' => 'Item removed from cart.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to remove item.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
?>
