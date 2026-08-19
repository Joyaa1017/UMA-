<?php
session_start();
require 'db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login_admin.php");
    exit;
}

$adminId = $_POST['update_admin_id'] ?? '';
$username = trim($_POST['update_admin_username'] ?? '');
$password = trim($_POST['update_admin_password'] ?? '');
$confirmPassword = trim($_POST['update_re-enteradmin_password'] ?? '');

// Check if password fields are filled but do not match
if (!empty($password) || !empty($confirmPassword)) {
    if ($password !== $confirmPassword) {
        $_SESSION['error'] = "Passwords do not match.";
        header("Location: super_admin_setting.php");
        exit;
    }
    }

    var_dump($username,$password, $confirmpassword);

// Check which fields to update
if (!empty($username) && !empty($password)) {
    // Update both username and password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE admin SET admin_username = ?, admin_password = ? WHERE admin_id = ?");
    $stmt->execute([$username, $hashedPassword, $adminId]);
} elseif (!empty($username)) {
    // Update only username
    $stmt = $pdo->prepare("UPDATE admin SET admin_username = ? WHERE admin_id = ?");
    $stmt->execute([$username, $adminId]);
} elseif (!empty($password)) {
    // Update only password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE admin SET admin_password = ? WHERE admin_id = ?");
    $stmt->execute([$hashedPassword, $adminId]);
}

// Redirect back to settings page
header("Location: super_admin_setting.php");
exit;
?>
