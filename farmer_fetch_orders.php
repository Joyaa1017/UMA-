<?php
session_start();
require_once 'db.php';

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    http_response_code(401);
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

// Get farmer_id using user_id
$farmerStmt = $pdo->prepare("SELECT id FROM farmers WHERE user_id = ?");
$farmerStmt->execute([$user_id]);
$farmer = $farmerStmt->fetch(PDO::FETCH_ASSOC);

if (!$farmer) {
    http_response_code(404);
    echo json_encode(['error' => 'Farmer not found']);
    exit;
}

$farmer_id = $farmer['id'];

// Now fetch orders using farmer_id
$sql = "
    SELECT t.transact_id AS id, td.product_name, td.product_quantity, td.product_price, 
           t.status, t.created_at, c.firstname, c.lastname
    FROM transactions t
    JOIN transaction_details td ON t.transact_id = td.transact_id
    JOIN consumers c ON t.user_id = c.user_id
    WHERE td.farmer_id = ?
    ORDER BY t.created_at DESC
";
try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$farmer_id]);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($orders);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
