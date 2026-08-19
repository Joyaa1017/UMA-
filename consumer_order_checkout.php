<?php
session_start();
require_once 'db.php';

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    die("User not logged in.");
}

// Fetch consumers
$consumerStmt = $pdo->prepare("CALL get_consumer_by_user_id(?)");
$consumerStmt->execute([$user_id]);
$consumers = $consumerStmt->fetch(PDO::FETCH_ASSOC);
$consumerStmt->closeCursor();

$userstmt = $pdo->prepare("SELECT username FROM users WHERE id = ?");
$userstmt->execute([$user_id]);
$user = $userstmt->fetch();

$product_id = $_POST['product_id'] ?? null;
$quantity = $_POST['quantity'] ?? 1;

if (!$product_id) {
    die("No product selected.");
}

// Get product info to find the farmer
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch();

if (!$product) {
    die("Product not found.");
}
$total_price = $product['product_price'] * $quantity;

// This is the connection: product.user_id is the FARMER's user ID
$farmer_id = $product['user_id'];
?>

<html>

<head>
    <title>
        Checkout
    </title>
    <link crossorigin="anonymous" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            background: linear-gradient(45deg, #1C1D21, #243117);
            color: white;
            font-family: 'Poppins';
        }

        .container {
            margin-top: 50px;
        }

        .checkout-title {
            font-size: 2.5rem;
            font-weight: bold;
            color: #ffffff;
        }

        .note {
            color: #FFF;
            font-size: 12px;
            margin-top: 10px;
        }

        .section-title {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .edit-link {
            color: #ffffff;
            text-decoration: underline;
            cursor: pointer;
        }

        .info-box {
            background-color: rgba(255, 255, 255, 0.1);
            padding: 10px 15px;
            /* Decreased top and bottom padding */
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .order-summary {
            background-color: #597445;
            padding: 10px 20px;
            /* Decreased top and bottom padding */
            border-radius: 10px;
            color: #ffffff;
        }

        .order-summary img {
            width: 50px;
            height: 50px;
            border-radius: 5px;
        }

        .order-summary .item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .order-summary .item img {
            margin-right: 10px;
        }

        .order-summary .total {
            font-weight: bold;
            font-size: 1.2rem;
        }

        .payment-method {
            margin-top: 20px;
        }

        .payment-method label {
            display: block;
            margin-bottom: 10px;
        }

        .place-order-btn {
            color: #445835;
            background-color: #ffffff;
            font-weight: bold;
            border: none;
            border-radius: 90px;
            padding: 10px 20px;
            cursor: pointer;
            width: 100%;
            /* Make the button full width */
        }

        .navbar img {
            width: 100px;
            height: 70px;
            border: none;
            border-radius: 50px;
        }

        .navbar .icons {
            display: flex;
            align-items: center;
        }

        .navbar .icons .icon {
            background-color: #ffffff;
            border-radius: 50%;
            padding: 10px;
            margin-left: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
        }

        .navbar .icons .icon.green {
            background-color: #445835;
            cursor: pointer;
        }

        .navbar .icons .icon i {
            color: #2c3e50;
        }

        .navbar .icons .icon.green i {
            color: #ffffff;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="navbar">
            <img alt="Logo with a green and yellow leaf" height="108" width="108" src="img/uma.png" />
            <div class="icons">
                <div class="icon">
                    <a href="consumer_home.php">
                        <i class="fa fa-shopping-bag">
                        </i>
                    </a>
                </div>
                <div class="icon green">
                    <a href="consumer_cart.php">
                        <i class="fas fa-shopping-cart">
                        </i>
                    </a>
                </div>
                <div class="icon">
                    <a href="consumer_account.php">
                        <i class="fas fa-user">
                        </i>
                    </a>
                </div>
            </div>
        </div>
        <h1 class="checkout-title">Checkout</h1>
        <div class="row">
            <div class="col-md-6">
                <div class="info-box">
                    <div class="section-title">
                        <i class="fas fa-map-marker-alt"></i>
                        Your location
                    </div>
                    <p id="location">
                        <?php echo htmlspecialchars($consumers['street'] . ' ' . $consumers['address']); ?>
                    </p>
                </div>

                <div class="info-box">
                    <div class="section-title">
                        <i class="fas fa-user"></i>
                        Personal Details
                    </div>
                    <p id="personal-details">
                        <?php echo htmlspecialchars($consumers['firstname'] . ' ' . $consumers['lastname']); ?><br>
                        <?php echo htmlspecialchars($consumers['phone_number']); ?><br>
                        <?php
                        if (isset($user['username'])) {
                            echo htmlspecialchars($user['username']);
                        } else {
                            echo "<p>Undefined User</p>";
                        }
                        ?>
                    </p>
                </div>
            </div>

            <div class="col-md-6">
                <div class="order-summary">
                    <form action="consumer_order_transaction.php" method="POST">
                        <input type="hidden" name="product_id" value="<?= $product['id']; ?>">
                        <input type="hidden" name="quantity" value="<?= $quantity; ?>">
                        <input type="hidden" name="product_name" value="<?= $product['product_name'] ?>">
                        <input type="hidden" name="product_price" value="<?= $product['product_price'] ?>">
                        <input type="hidden" name="farmer_id" value="<?= $product['user_id'] ?>">

                        <p><strong>Product:</strong> <?= htmlspecialchars($product['product_name']); ?></p>
                        <p><strong>Price:</strong> ₱<?= number_format($product['product_price'], 2); ?></p>
                        <p><strong>Quantity:</strong> <?= $quantity; ?></p>
                        <p><strong>Total Price:</strong> ₱<?= number_format($total_price, 2); ?></p>

                        <button type="submit">Place Order</button>
                    </form>

                </div>
            </div>

        </div>

        <div class="note">
            Note: This product cannot be cancelled once order is placed.
        </div>
    </div>
    </div>
    </div>
    </div>
</body>

</html>