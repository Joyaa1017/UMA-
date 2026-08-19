<?php
session_start();
require_once 'db.php'; // Make sure this contains your PDO connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'] ?? null;
    $feedback = trim($_POST['feedback'] ?? '');
    $rating = $_POST['rating'] ?? null;

    if (!$user_id || !$feedback || !$rating) {
        die('All fields are required.');
    }


    $sql = "INSERT INTO feedbacks (user_id, feedback, ratings, created_at) 
            VALUES (?, ?, ?, NOW())";

    $stmt = $pdo->prepare($sql);
    $success = $stmt->execute([$user_id, $feedback, $rating]);

    if ($success) {
        // Redirect or show success message
        header("Location: consumer_feed.php");
        exit;
    } else {
        echo "Failed to submit feedback.";
    }
} else {
    http_response_code(405);
    echo "Invalid request.";
}
?>
