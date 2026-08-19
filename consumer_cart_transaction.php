<?php
session_start();
require_once 'db.php';

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    die("User not logged in.");
}

$cart_item_ids = $_POST['cart_item_ids'] ?? [];
$quantities = $_POST['cart_item_quantities'] ?? [];

if (empty($cart_item_ids) || empty($quantities) || count($cart_item_ids) !== count($quantities)) {
    die("Invalid cart data.");
}

$products = [];
$total_amount = 0.00;

foreach ($cart_item_ids as $index => $product_id) {
    $quantity = (int)$quantities[$index];

    $stmt = $pdo->prepare("SELECT user_id, product_name, product_price, product_stock FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();

    if (!$product) {
        die("Product not found (ID: $product_id).");
    }

    if ($quantity > $product['product_stock']) {
        die("Quantity exceeds stock for product: " . htmlspecialchars($product['product_name']));
    }

    $subtotal = $product['product_price'] * $quantity;
    $total_amount += $subtotal;

    $products[] = [
        'product_id' => $product_id,
        'product_name' => $product['product_name'],
        'product_price' => $product['product_price'],
        'quantity' => $quantity,
        'farmer_id' => $product['user_id'],
        'subtotal' => $subtotal
    ];
}

// Insert into transactions table
$transStmt = $pdo->prepare("INSERT INTO transactions (user_id, total_amount) VALUES (?, ?)");
$transStmt->execute([$user_id, $total_amount]);
$transact_id = $pdo->lastInsertId();

// Insert into transaction_details and notifications
foreach ($products as $prod) {
    $detailStmt = $pdo->prepare("INSERT INTO transaction_details 
        (transact_id, farmer_id, prod_id, product_name, product_quantity, product_price) 
        VALUES (?, ?, ?, ?, ?, ?)");
    $detailStmt->execute([
        $transact_id,
        $prod['farmer_id'],
        $prod['product_id'],
        $prod['product_name'],
        $prod['quantity'],
        $prod['product_price']
    ]);

    // Notification for farmer
    $farmerMessage = "An order has been placed:\nOrder #$transact_id\nProduct: {$prod['product_name']}\nQuantity: {$prod['quantity']}";
    $notifStmt = $pdo->prepare("INSERT INTO notifications (user_id, farmer_id, transact_id, message, recipient_type) VALUES (?, ?, ?, ?, ?)");
    $notifStmt->execute([ $user_id, $prod['farmer_id'], $transact_id, $farmerMessage, 'farmer' ]);
}

// Notification for consumer
$consumerMessage = "Your order (Order #$transact_id) with " . count($products) . " items has been placed successfully.";
$pdo->prepare("INSERT INTO notifications (user_id, farmer_id, transact_id, message, recipient_type) VALUES (?, ?, ?, ?, ?)")
    ->execute([$user_id, $prod['farmer_id'], $transact_id, $consumerMessage, 'consumer']);

// Remove ordered items from cart
$placeholders = implode(',', array_fill(0, count($cart_item_ids), '?'));
$deleteStmt = $pdo->prepare("DELETE FROM carts WHERE user_id = ? AND product_id IN ($placeholders)");
$deleteStmt->execute(array_merge([$user_id], $cart_item_ids));

header("Location: consumer_home.php?success=1");
exit;
?>
