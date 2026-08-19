<?php
session_start();
require_once 'db.php';

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    http_response_code(401);
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

// Pagination setup
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
$limit = 5;
$offset = ($page - 1) * $limit;

// Get total order count
$countStmt = $pdo->prepare("SELECT COUNT(*) FROM transactions WHERE user_id = ?");
$countStmt->execute([$user_id]);
$totalOrders = $countStmt->fetchColumn();
$totalPages = ceil($totalOrders / $limit);

// Fetch paginated orders
$sql = "
    SELECT t.id, t.product_name, t.product_quantity, t.total_price, 
           t.status, t.created_at, c.firstname, c.lastname
    FROM transactions t
    JOIN consumers c ON t.user_id = c.user_id
    WHERE t.user_id = ?
    ORDER BY t.created_at DESC
    LIMIT ? OFFSET ?
";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(1, $user_id, PDO::PARAM_INT);
$stmt->bindValue(2, $limit, PDO::PARAM_INT);
$stmt->bindValue(3, $offset, PDO::PARAM_INT);
$stmt->execute();

$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode([
    'orders' => $orders,
    'totalPages' => $totalPages
]);
?>
