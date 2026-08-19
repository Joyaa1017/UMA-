<?php
session_start();
require_once 'db.php';

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id || !isset($_POST['reason'], $_POST['farmer_id'])) {
    die('Invalid report request.');
}

$reason = trim($_POST['reason']);
$custom_reason = trim($_POST['custom_reason'] ?? '');
$farmer_id = $_POST['farmer_id'];
$product_id = $_POST['product_id'] ?? null; // Optional

// Insert report into the database
try {
    $stmt = $pdo->prepare("INSERT INTO reports (user_id, product_id, farmer_id, reason, custom_reason)
                           VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([
        $user_id,
        $product_id, // This will be null for farmer-only reports
        $farmer_id,
        $reason,
        $custom_reason
    ]);

    // Redirect based on report type
    if ($product_id) {
        // Product report → redirect to home
        header("Location: consumer_home.php?reported=1");
    } else {
        // Farmer-only report → redirect to farmer profile
        header("Location: consumer_farmer_profile.php?farmer_id=" . urlencode($farmer_id) . "&reported=1");
    }
    exit;
} catch (PDOException $e) {
    error_log("Report insert failed: " . $e->getMessage());
    header("Location: consumer_home.php?error=report_failed");
    exit;
}
