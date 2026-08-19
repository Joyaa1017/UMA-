<?php
session_start();
$mysqli = new mysqli("localhost", "root", "", "uma_main");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$errors = [];

//for role
$user_id = $_SESSION['user_id']; // Assuming this is already saved after login
$role = $_SESSION['role'] ?? 'consumer'; // Default to 'consumer' if not set

// Update the role in users table
$stmt = $mysqli->prepare("UPDATE users SET role = ? WHERE id = ?");
$stmt->bind_param("si", $role, $user_id);
$stmt->execute();
$stmt->close();

$firstname = trim($_POST['firstname']);
$lastname = trim($_POST['lastname']);
$phone_number = trim($_POST['phone_number']);
$address = $_POST['address'];
$purok = trim($_POST['purok']);
$street = trim($_POST['street']);

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    $errors[] = "User not logged in or session expired.";
}

// Simple validation
if (empty($firstname)) $errors[] = "First name is required.";
if (empty($lastname)) $errors[] = "Last name is required.";
if (empty($phone_number)) $errors[] = "Phone number is required.";
if (empty($address)) $errors[] = "Address is required.";
if (empty($purok)) $errors[] = "Purok is required.";
if (empty($street)) $errors[] = "Street is required.";

// Handle file upload
$target_dir = "uploads/";
$consumer_image = "";

if (!is_dir($target_dir)) {
    mkdir($target_dir, 0755, true); // create uploads/ if not exist
}

if (isset($_FILES["consumer_image"]) && $_FILES["consumer_image"]["error"] == 0) {
    $consumer_image = basename($_FILES["consumer_image"]["name"]);
    $target_file = $target_dir . $consumer_image;
    if (!move_uploaded_file($_FILES["consumer_image"]["tmp_name"], $target_file)) {
        $errors[] = "Failed to upload image.";
    }
} else {
    $errors[] = "Image upload failed.";
}

if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    header("Location: signup_consumer.php");
    exit;
}

// Insert into database
$stmt = $mysqli->prepare("INSERT INTO consumers (user_id, firstname, lastname, phone_number, address, consumer_image, purok, street) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("isssssss", $user_id, $firstname, $lastname, $phone_number, $address, $consumer_image, $purok, $street);

if ($stmt->execute()) {
    $_SESSION['success'] = "Information saved!";
    header("Location: signup_terms.php");
} else {
    $_SESSION['errors'] = ["Database error: " . $stmt->error];
    header("Location: signup.php");
}

$stmt->close();
$mysqli->close();
?>
