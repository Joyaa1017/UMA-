<?php
session_start();
require_once 'db.php';
require_once 'product_model.php';
require_once 'farmer_model.php';
require_once 'consumer_model.php';
require_once 'report_model.php';


// Instantiate models
$farmerModel = new Farmer($pdo);
$productModel = new Product($pdo);
$consumerModel = new Consumer($pdo);
$reportModel = new Report($pdo);

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? null;

    if (!$action) {
        die("No action specified.");
    }

    switch ($action) {

        case 'delete_farmer':
            $farmer_id = $_POST['delete_farmer_id'] ?? null;

            if (!$farmer_id) {
                die('Missing farmer ID.');
            }

            $farmerModel->delete($farmer_id);
            header('Location: super_admin_farmers.php');
            exit();

        case 'delete_consumer':
            $consumer_id = $_POST['delete_consumer_id'] ?? null;

            if (!$consumer_id) {
                die('Missing consumer ID.');
            }

            $consumerModel->delete($consumer_id);
            header('Location: super_admin_consumers.php');
            exit();

        case 'delete_product':
            $product_id = $_POST['delete_product_id'] ?? null;

            if (!$product_id) {
                die('Missing product ID.');
            }

            $productModel->delete($product_id);
            header('Location: super_admin_products.php');
            exit();

        case 'delete_report':
            $report_id = $_POST['delete_report_id'] ?? null;

            if (!$report_id) {
                die('Missing report ID.');
            }

            $reportModel->delete($report_id);
            header('Location: super_admin_report.php');
            exit();

        default:
            die('Unknown action: ' . htmlspecialchars($action));
    }
}
