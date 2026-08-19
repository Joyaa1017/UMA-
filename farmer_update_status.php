<?php
session_start();
require_once 'db.php';

if (isset($_POST['id']) && isset($_POST['status'])) {
    $orderId = $_POST['id'];
    $newStatus = $_POST['status'];

    $validStatuses = ['processing', 'in-delivery', 'completed'];
    if (!in_array($newStatus, $validStatuses)) {
        http_response_code(400);
        echo "Invalid status";
        exit;
    }

    try {
        $stmt = $pdo->prepare("UPDATE transactions SET status = :status WHERE id = :id");
        $stmt->bindParam(':status', $newStatus);
        $stmt->bindParam(':id', $orderId);
        $stmt->execute();

        echo "Status updated successfully";
    } catch (PDOException $e) {
        http_response_code(500);
        echo "Error: " . $e->getMessage();
    }
}
?>
