<?php
session_start();
require 'db.php';

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

$errors = [];

if (empty($username)) $errors[] = "Username is required.";
if (empty($password)) $errors[] = "Password is required.";

if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    header("Location: login_admin.php");
    exit;
}

// Fetch admin from database
$stmt = $pdo->prepare("SELECT admin_id, admin_username, admin_password FROM admin WHERE admin_username = ?");
$stmt->execute([$username]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

var_dump($admin);

// Use plain text comparison (not secure)
if ($admin && password_verify($password, $admin['admin_password'])) {
    $_SESSION['admin_id'] = $admin['admin_id'];
    $_SESSION['admin_username'] = $admin['admin_username'];

    header("Location: super_admin_dashboard.php");
    exit;
}

// If login fails
$_SESSION['errors'] = ['The provided credentials do not match our records.'];
header("Location: login_admin.php");
exit;
?>
