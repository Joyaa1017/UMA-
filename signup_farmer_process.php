<?php
session_start();
$mysqli = new mysqli("localhost", "root", "", "uma_main");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// 👇 Handle AJAX request to fetch orders (used by JS table)
if (isset($_GET['fetch_orders'])) {
    header('Content-Type: application/json');

    $sql = "SELECT t.id, t.farmer_id, t.product_name, t.product_quantity, t.total_price, t.created_at, t.status, c.firstname, c.lastname
            FROM transactions t
            JOIN consumers c ON t.user_id = c.user_id
            ORDER BY t.created_at DESC";
    $result = $mysqli->query($sql);
    $orders = [];

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }
    }

    echo json_encode($orders);
    exit;
}

// 👇 Handle AJAX request to update order status
if (isset($_GET['update_status']) && isset($_GET['id']) && isset($_GET['status'])) {
    $order_id = intval($_GET['id']);
    $new_status = $mysqli->real_escape_string($_GET['status']);

    $stmt = $mysqli->prepare("UPDATE transactions SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $order_id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => $stmt->error]);
    }
    exit;
}

// 👇 The rest of the code below is for form submission handling

$errors = [];

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'] ?? 'consumer';

$stmt = $mysqli->prepare("UPDATE users SET role = ? WHERE id = ?");
$stmt->bind_param("si", $role, $user_id);
$stmt->execute();
$stmt->close();

$firstname = trim($_POST['firstname']);
$lastname = trim($_POST['lastname']);
$farmname = trim($_POST['farmname']);
$phone_number = trim($_POST['phone_number']);
$address = $_POST['address'];
$purok = trim($_POST['purok']);
$street = trim($_POST['street']);

if (!$user_id) $errors[] = "User not logged in or session expired.";
if (empty($firstname)) $errors[] = "First name is required.";
if (empty($lastname)) $errors[] = "Last name is required.";
if (empty($farmname)) $errors[] = "Farm name is required.";
if (empty($phone_number)) $errors[] = "Phone number is required.";
if (empty($address)) $errors[] = "Address is required.";
if (empty($purok)) $errors[] = "Purok is required.";
if (empty($street)) $errors[] = "Street is required.";

$target_dir = "uploads/";
$farmer_image = "";

if (!is_dir($target_dir)) {
    mkdir($target_dir, 0755, true);
}

if (isset($_FILES["farmer_image"]) && $_FILES["farmer_image"]["error"] == 0) {
    $farmer_image = basename($_FILES["farmer_image"]["name"]);
    $target_file = $target_dir . $farmer_image;
    if (!move_uploaded_file($_FILES["farmer_image"]["tmp_name"], $target_file)) {
        $errors[] = "Failed to upload image.";
    }
} else {
    $errors[] = "Image upload failed.";
}

if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    header("Location: signup_farmer.php");
    exit;
}

$stmt = $mysqli->prepare("INSERT INTO farmers (user_id, firstname, lastname, farmer_image, phone_number, farmname, address, purok, street) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("issssssss", $user_id, $firstname, $lastname, $farmer_image, $phone_number, $farmname, $address, $purok, $street);

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
