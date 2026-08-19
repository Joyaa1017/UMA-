<?php
session_start();
require_once 'db.php'; // your DB connection

echo "<pre>";
print_r($_POST);
echo "</pre>";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $phone_number = trim($_POST['phone_number']);
    $barangay = trim($_POST['barangay']);
    $purok = trim($_POST['purok']);
    $street = trim($_POST['street']);
    $email = $_POST['email'];
    $password = $_POST['password']; // Optional

    // Add validation if needed

    $consumerStmt = $pdo->prepare("
    UPDATE consumers
    SET firstname = ?, lastname = ?, phone_number = ?, address = ?, purok = ?, street = ?
    WHERE user_id = ?
");
    $consumerUpdated = $consumerStmt->execute([
        $firstname,
        $lastname,
        $phone_number,
        $barangay,
        $purok,
        $street,
        $user_id
    ]);


    // === Update users table ===
    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $userStmt = $pdo->prepare("UPDATE users SET username = ?, password = ? WHERE id = ?");
        $userUpdated = $userStmt->execute([$email, $hashedPassword, $user_id]);
    } else {
        $userStmt = $pdo->prepare("UPDATE users SET username = ? WHERE id = ?");
        $userUpdated = $userStmt->execute([$email, $user_id]);
    }

    // === Redirect with success message ===
    if ($consumerUpdated && $userUpdated) {
        header("Location: consumer_account.php");
        exit();
    } else {
        echo "Update failed.<br>";
        var_dump($consumerUpdated, $userUpdated);
        print_r($consumerStmt->errorInfo());
        print_r($userStmt->errorInfo());
    }
} else {
    echo "Invalid request.";
}
