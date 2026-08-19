<?php
session_start();
require 'db.php'; 


$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// var_dump($username, $password);
// exit;

$errors = [];

if (empty($username)) $errors[] = "Username is required.";
if (empty($password)) $errors[] = "Password is required.";

if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    header("Location: login.php");
    exit;
}


// Fetch user from users table
$stmt = $pdo->prepare("SELECT id, password, role FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['role'] = $user['role'];

    // Check if user is in farmers table
    $farmerStmt = $pdo->prepare("SELECT id FROM farmers WHERE user_id = ?");
    $farmerStmt->execute([$user['id']]);
    $farmer = $farmerStmt->fetch();

    if ($farmer) {
        $_SESSION['farmer_id'] = $farmer['id'];
        header("Location: farmer_home.php");
        exit;
    }

    // Check if user is in consumers table
    $consumerStmt = $pdo->prepare("SELECT id FROM consumers WHERE user_id = ?");
    $consumerStmt->execute([$user['id']]);
    $consumer = $consumerStmt->fetch();

    if ($consumer) {
        $_SESSION['consumer_id'] = $consumer['id'];
        header("Location: consumer_home.php");
        exit;
    }

    // Default fallback if user has no assigned role table
    header("Location: consumer_home.php");
    exit;
}

$_SESSION['errors'] = ['The provided credentials do not match our records.'];
header("Location: login.php");
