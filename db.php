<?php
$host = 'localhost';       // Change if you're not using localhost
$db   = 'uma_main';        // Your database name
$user = 'root';            // Your MySQL username (change if needed)
$pass = '';                // Your MySQL password (change if needed)
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
