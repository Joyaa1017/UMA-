<?php
session_start();
require_once 'db.php'; // your DB connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $phone_number = trim($_POST['phone_number']);
    $barangay = trim($_POST['barangay']);
    $purok = trim($_POST['purok']);
    $street = trim($_POST['street']);
    $farmname = $_POST['farmname'];
    $email = $_POST['email'];
    $password = $_POST['password']; // Optional

    // Add validation if needed


    // === Update farmers table ===
    $farmerStmt = $pdo->prepare("
    UPDATE farmers
    SET firstname = ?, lastname = ?, phone_number = ?, address = ?, purok = ?, street = ?, farmname = ?
    WHERE user_id = ?
");
    $farmerUpdated = $farmerStmt->execute([
        $firstname,
        $lastname,
        $phone_number,
        $barangay,
        $purok,
        $street,
        $farmname,
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
    if ($farmerUpdated && $userUpdated) {
        header("Location: farmer_account.php");
        exit();
    } else {
        echo "Update failed.";
    }
} else {
    echo "Invalid request.";
}
