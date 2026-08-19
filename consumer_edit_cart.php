<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cartId = $_POST['cart_id'] ?? null;
    $newQuantity = $_POST['product_quantity'] ?? null;

    if (!$cartId || !$newQuantity || $newQuantity < 1) {
        echo json_encode(['success' => false, 'message' => 'Invalid input.']);
        exit;
    }

    // Update the cart in the database
    $stmt = $pdo->prepare("UPDATE carts SET product_quantity = ? WHERE id = ?");
    if ($stmt->execute([$newQuantity, $cartId])) {
        echo json_encode(['success' => true, 'message' => 'Quantity updated successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database update failed.']);
    }
}
?>
