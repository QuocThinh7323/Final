<?php
session_start();
require_once('./db/conn.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $qty = intval($_POST['qty']);

    if ($id <= 0 || $qty <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid product ID or quantity.']);
        exit;
    }

    // Lấy thông tin số lượng tồn kho của sản phẩm từ cơ sở dữ liệu
    $stmt = $conn->prepare("SELECT p.stock FROM cart c JOIN products p ON c.product_id = p.id WHERE c.id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if (!$product) {
        echo json_encode(['success' => false, 'message' => 'Product not found.']);
        exit;
    }

    // Kiểm tra nếu số lượng muốn cập nhật lớn hơn số lượng trong kho
    if ($qty > $product['stock']) {
        echo json_encode(['success' => false, 'message' => 'This product is currently out of stock.']);
        exit;
    }

    // Cập nhật số lượng trong giỏ hàng
    $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
    $stmt->bind_param("ii", $qty, $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => 'Cart updated successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update cart.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
