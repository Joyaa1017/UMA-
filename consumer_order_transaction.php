<?php
session_start();
require_once 'db.php';

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    die("User not logged in.");
}

$product_id = $_POST['product_id'] ?? null;
$quantity = $_POST['quantity'] ?? null;

if (!$product_id || !$quantity) {
    die("Product ID or Quantity missing.");
}

// Fetch product details
$stmt = $pdo->prepare("SELECT user_id, product_name, product_price, product_stock FROM products WHERE id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch();

if (!$product) {
    die("Product not found.");
}

if ($quantity > $product['product_stock']) {
    die("Requested quantity exceeds available stock.");
}

$farmer_id = $product['user_id'];
$product_name = $product['product_name'];
$product_price = number_format((float)$product['product_price'], 2, '.', '');

$total_amount = $product_price * $quantity;

// Create transaction
$transactionstmt = $pdo->prepare("CALL insert_transaction_and_notification(?, ?, @transact_id)");
$transactionstmt->execute([$user_id, $total_amount]);

$transact_id = $pdo->query("SELECT @transact_id")->fetchColumn();
if (!$transact_id) {
    die("Failed to get transaction ID.");
}

// Insert transaction details directly
$insertStmt = $pdo->prepare("INSERT INTO transaction_details 
    (transact_id, farmer_id, prod_id, product_name, product_quantity, product_price)
    VALUES (?, ?, ?, ?, ?, ?)");
$insertStmt->execute([
    $transact_id,
    $farmer_id,
    $product_id,
    $product_name,
    $quantity,
    $product_price
]);

// Get consumer info
$consumerStmt = $pdo->prepare("SELECT firstname, lastname FROM consumers WHERE user_id = ?");
$consumerStmt->execute([$user_id]);
$consumer = $consumerStmt->fetch();

$created_at = date('Y-m-d H:i:s'); // Use current time or fetch if needed from DB

// Message for farmer
$farmerMessage = "An Order has been placed\nOrder #$transact_id\nProduct: $product_name\nCustomer: {$consumer['firstname']} {$consumer['lastname']}\nDate: $created_at";

// Message for consumer
$consumerMessage = "Your Order has been placed!\nOrder #$transact_id\nProduct: $product_name\nDate: $created_at";

// Insert farmer notification
$notifStmt1 = $pdo->prepare("INSERT INTO notifications (user_id, farmer_id, transact_id, message, recipient_type) VALUES (?, ?, ?, ?, 'farmer')");
$notifStmt1->execute([$user_id, $farmer_id, $transact_id, $farmerMessage]);

// Insert consumer notification
$notifStmt2 = $pdo->prepare("INSERT INTO notifications (user_id, farmer_id, transact_id, message, recipient_type) VALUES (?, ?, ?, ?, 'consumer')");
$notifStmt2->execute([$user_id, $farmer_id, $transact_id, $consumerMessage]);

// Redirect after success
header("Location: consumer_home.php?success=1");
exit;
?> 
