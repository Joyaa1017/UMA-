<?php
session_start();
require_once 'db.php';
require_once 'product_model.php';

$productModel = new Product($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];

    $product_name = trim($_POST['product_name'] ?? '');
    $product_description = trim($_POST['product_description'] ?? '');
    $product_price = (int) ($_POST['product_price'] ?? 0);
    $product_stock = (int) ($_POST['product_stock'] ?? 0);
    $product_category = trim($_POST['product_category'] ?? '');
    $product_id = $_POST['id'] ?? null;

    $product_image = null;
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['product_image']['tmp_name'];
        $fileName = basename($_FILES['product_image']['name']);
        $uploadDir = 'uploads/';
        $destPath = $uploadDir . $fileName;

        if (move_uploaded_file($fileTmpPath, $destPath)) {
            $product_image = $fileName;
        } else {
            die('Error moving uploaded file.');
        }
    }

    // ---------- CREATE ----------
    if (isset($_POST['create_product'])) {
        if (empty($product_name) || empty($product_category) || empty($product_image)) {
            die('Please fill all required fields.');
        }

        $productModel->create([
            'user_id' => $user_id,
            'product_name' => $product_name,
            'product_description' => $product_description,
            'product_price' => $product_price,
            'product_stock' => $product_stock,
            'product_category' => $product_category,
            'product_image' => $product_image
        ]);

        header('Location: farmer_product.php');
        exit();
    }

    // ---------- UPDATE ----------
    if (isset($_POST['update_product'])) {
        if (empty($_POST['id'])) {
            die('Missing product ID.');
        }

        $product_id = $_POST['id'];
        $product_name = $_POST['product_name'];
        $product_description = $_POST['product_description'];
        $product_price = $_POST['product_price'];
        $product_stock = $_POST['product_stock'];
        $product_category = $_POST['product_category'];
        $user_id = $_SESSION['user_id'];

        // Set fallback image value from hidden field
        $product_image = $_POST['existing_image'] ?? null;

        // If a new image is uploaded, override existing image
        if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['product_image']['tmp_name'];
            $fileName = basename($_FILES['product_image']['name']);
            $uploadDir = 'uploads/';
            $destPath = $uploadDir . $fileName;

            if (move_uploaded_file($fileTmpPath, $destPath)) {
                $product_image = $fileName;
            } else {
                die('Error moving uploaded file.');
            }
        }

        $productModel->update([
            'id' => $product_id,
            'user_id' => $user_id,
            'product_name' => $product_name,
            'product_description' => $product_description,
            'product_price' => $product_price,
            'product_stock' => $product_stock,
            'product_category' => $product_category,
            'product_image' => $product_image
        ]);

        header('Location: farmer_product.php');
        exit();
    }
    
    // ---------- DELETE ----------
    elseif (isset($_POST['delete_product'])) {
        $product_id = $_POST['delete_product_id'] ?? null;

        if (!$product_id) {
            die('Missing product ID.');
        }

        $productModel->delete($product_id);
        header('Location: farmer_product.php');
        exit();
    }
}
