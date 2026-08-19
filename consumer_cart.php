<?php
// views/consumer_home.php
session_start();
require_once 'db.php';
require_once 'product_model.php';

// Ensure user is logged in
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    die('User not logged in.');
}
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// 1. Handle add to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $productId = (int) $_POST['prod_id'];
    $quantity = (int) $_POST['product_quantity'];

    $stmt = $pdo->prepare("CALL add_or_update_cart_item(?, ?, ?)");
    $stmt->execute([$user_id, $productId, $quantity]);
    $stmt->closeCursor();

    header("Location: consumer_cart.php");
    exit();
}

// var_dump($_POST);
// exit;

// 2. Handle delete cart item
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_cart_item'])) {
    $cartId = (int) $_POST['cart_id'];

    $stmt = $pdo->prepare("CALL delete_cart_item(?, ?)");
    $stmt->execute([$cartId, $user_id]);
    $stmt->closeCursor();

    header("Location: consumer_cart.php");
    exit();
}

// 3. Fetch consumer info (optional)
$consumerStmt = $pdo->prepare("CALL get_consumer_by_user_id(?)");
$consumerStmt->execute([$user_id]);
$consumer = $consumerStmt->fetch(PDO::FETCH_ASSOC);
$consumerStmt->closeCursor();

// 4. Fetch cart items
$stmt = $pdo->prepare("CALL get_cart_items_by_user(?)");
$stmt->execute([$user_id]);
$cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt->closeCursor();
?>

<html>

<head>
    <title>Your Cart</title>
    <link crossorigin="anonymous" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet" />
    <style>
        body {
            background: linear-gradient(45deg, #1C1D21, #48612f);
            color: white;
            font-family: 'Poppins';
            background-repeat: no-repeat;
            /* Prevents the background from repeating */
            background-attachment: fixed;
            /* Optional: keeps the background fixed during scrolling */
            background-size: cover;
            /* Ensures background covers the entire area */
        }

        .navbar {
            background-color: #1a1a1a00;
        }

        .navbar-brand img {
            width: 40px;
        }

        .welcome-text {
            color: #597445;
        }

        .highlight-text {
            color: #ffffff;
        }

        .btn-custom {
            background-color: #ffffff;
            color: #1a1a1a;
            border-radius: 90px;
            padding: 10px 20px;
        }

        .category-btn {
            background-color: #ffffff;
            color: #2a2a2a;
            border: none;
            border-radius: 20px;
            padding: 5px 15px;
            margin: 5px;

        }

        .category-btn:hover {
            background-color: #2a2a2a;
            color: #ffffff;
        }

        .card {
            background: linear-gradient(45deg, #1C1D21, #314220);
            color: white;
            border-radius: 10px;
        }

        .card-img-top {
            border-radius: 10px;
        }

        .table-container {
            margin: 20px;
        }

        .table {
            width: 100%;
            background-color: #2a2a2a;
            border-radius: 10px;
            overflow: hidden;
        }

        .table th,
        .table td {
            padding: 15px;
            text-align: center;
        }

        .table th {
            background-color: #333;
        }

        .table td {
            background-color: #3a3a3a;
        }

        .product-row {
            background-color: #658147;
            border-radius: 10px;
            margin-bottom: 10px;
            padding: 10px;
            padding-bottom: 20px;
        }

        .product-row img {
            width: 35px;
            height: 35px;
            border-radius: 10px;

        }

        .details {
            background-color: #658147;
            border-radius: 10px;
            padding: 20px;
            width: 100%;
        }

        .details h5,
        .details p {
            margin: 0;
        }

        .details .total {
            font-size: 24px;
            font-weight: bold;
        }

        .btn-checkout {
            background-color: #658147;
            border: none;
            color: white;
            padding: 10px;
            padding-left: 5rem;
            padding-right: 5.8rem;
            border-radius: 90px;
            cursor: pointer;
            text-align: center;
            margin: 0;
        }

        .btn-checkout:hover {
            background-color: #4e6832;
        }

        .quantity-control {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .quantity-control button {
            background-color: transparent;
            border: none;
            color: white;
            font-size: 18px;
            cursor: pointer;
        }

        .quantity-control input {
            width: 30px;
            text-align: center;
            border: none;
            background-color: transparent;
            color: white;
        }

        .remove-btn {
            background-color: transparent;
            border: none;
            color: white;
            font-size: 18px;
            cursor: pointer;
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
            background-color: #658147;
            cursor: pointer;
        }

        .navbar .icons .icon i {
            color: #2c3e50;
        }

        .navbar .icons .icon.green i {
            color: #ffffff;
        }

        .cart-checkbox {
            transform: scale(1.3);
            cursor: pointer;
        }
    </style>
</head>

<body class="container-xl">
    <!-- Navbar -->
    <br>
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

    <!--Welcome-->
    <section>
        <div class="container text-center mt-5">
            <br><br><br><br>
            <h1 class="welcome-text">
                What’s inside
                <span class="highlight-text"> my cart?</span>
            </h1>
            <p class="lead">Review your selected items below. Ready to proceed? Click “Checkout” to complete your <br>
                purchase. If you need to make any changes, you can update quantities or remove items <br>
                directly from yaour cart. Happy shopping!</p>
            <a class="btn btn-custom" style="margin-bottom: 11rem;" href="#cart"> View cart </a>
            <br><br><br>
        </div>
    </section>

    <style>
        table td {
            border: none;
            border-bottom: 1px solid white;
        }
    </style>

    <form method="POST" action="consumer_cart_checkout.php">
        <div class="" id="cart">
            <div class="w-100 d-flex gap-5">
                <div class="w-100">
                    <table class="w-100 p-3 table table-responsive bg-transparent text-center">
                        <thead class="mb-3 text-white">
                            <tr>
                                <th class="col-1 bg-transparent text-white"></th>
                                <th class="bg-transparent text-white">Image</th>
                                <th class="bg-transparent text-white">Name</th>
                                <th class="bg-transparent text-white">Price</th>
                                <th class="bg-transparent text-white">Quantity</th>
                                <th class="bg-transparent text-white">Remove</th>
                            </tr>
                        </thead>
                        <tbody class="product-row mb-3 rounded-4 border-0">
                            <?php if (count($cartItems) === 0): ?>
                                <tr>
                                    <td colspan="6" class="text-white text-center">Your cart is empty.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($cartItems as $index => $cartItem):
                                    $productStmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
                                    $productStmt->execute([$cartItem['product_id']]);
                                    $product = $productStmt->fetch();
                                    if (!$product) continue;
                                ?>
                                    <tr data-cart-id="<?= htmlspecialchars($cartItem['id']) ?>">
                                        <td class="bg-transparent col-1">
                                            <input type="checkbox" name="selected_cart_items[]" value="<?= htmlspecialchars($cartItem['id']) ?>" class="cart-checkbox" />
                                        </td>
                                        <td class="bg-transparent">
                                            <img alt="Image of <?= htmlspecialchars($product['product_name']) ?>"
                                                height="15"
                                                src="img/<?= htmlspecialchars($product['product_image']) ?>"
                                                width="15" />
                                        </td>
                                        <td class="bg-transparent text-white"><?= htmlspecialchars($product['product_name']) ?></td>
                                        <td class="bg-transparent text-white">₱<?= number_format($product['product_price'], 2) ?></td>
                                        <td class="bg-transparent">
                                            <div class="quantity-control">
                                                <button type="button" onclick="removeQuantity(<?= $index ?>)">-</button>
                                                <input type="number" min="1" name="quantities[<?= $cartItem['id'] ?>]" value="<?= $cartItem['product_quantity'] ?>" class="quantity" />
                                                <button type="button" onclick="addQuantity(<?= $index ?>)">+</button>
                                            </div>
                                        </td>
                                        <td class="bg-transparent">
                                            <button class="remove-btn" type="button" onclick="deleteCart(<?= $cartItem['id'] ?>)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="col-3" style="text-align: left;">
                    <div class="row mb-3">
                        <div class="col-12 text-center">DETAILS</div>
                    </div>
                    <div class="details">
                        <p>
                            Shipping
                            <span class="float-end">Free</span>
                        </p>
                    </div>

                    <div class="mt-3">
                        <?php if (count($cartItems) > 0): ?>
                            <button type="submit" class="btn-checkout" style="text-decoration: none;">Check out</button>
                        <?php else: ?>
                            <p class="text-white text-center">Your cart is empty.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </form>


    <script>
        function checkout(obj) {
            event.preventDefault(); // Prevent default form behavior

            // Set the hidden input value for product ID
            const product = document.getElementById('prod_id');
            product.value = JSON.stringify(obj);

            // Submit the form
            document.getElementById('checkout_form').submit();
        }

        function updateQuantity(cartId, newQuantity) {
            $.ajax({
                url: "consumer_edit_cart.php",
                type: 'POST',
                data: {
                    cart_id: cartId,
                    product_quantity: newQuantity
                },
                success: function(response) {
                    const res = JSON.parse(response);
                    if (res.success) {
                        alert(res.message);
                    } else {
                        alert('Failed to update quantity: ' + res.message);
                    }
                },
                error: function(xhr) {
                    alert('An error occurred: ' + xhr.responseText);
                },
            });
        }


        function addQuantity(id) {
            const input = document.querySelectorAll('.quantity');
            if (!input[id]) {
                console.error('Input field not found for ID:', id); // Debug log
                return;
            }
            let num = parseInt(input[id].value);
            num += 1;
            input[id].value = num;

            const cartId = input[id].closest('tr').getAttribute('data-cart-id');
            if (!cartId) {
                console.error('Cart ID not found for input:', input[id]); // Debug log
                return;
            }

            updateQuantity(cartId, num);
        }

        function removeQuantity(id) {
            const input = document.querySelectorAll('.quantity');
            if (!input[id]) {
                console.error('Input field not found for ID:', id); // Debug log
                return;
            }
            let num = parseInt(input[id].value);
            if (num > 1) {
                num -= 1;
                input[id].value = num;

                const cartId = input[id].closest('tr').getAttribute('data-cart-id');
                if (!cartId) {
                    console.error('Cart ID not found for input:', input[id]); // Debug log
                    return;
                }

                updateQuantity(cartId, num);
            } else {
                console.warn('Quantity is already at the minimum value.'); // Debug log
            }
        }

        function deleteCart(id) {
            if (confirm("Are you sure you want to remove this item from your cart?")) {
                document.getElementById('delete_product_id').value = id;
                document.getElementById('delete-form').submit();
            }
        }

        document.getElementById("cart-checkout-form").addEventListener("submit", function(e) {
            const checkedBoxes = document.querySelectorAll('.cart-checkbox:checked');
            if (checkedBoxes.length === 0) {
                alert("Please select at least one item to check out.");
                e.preventDefault();
                return;
            }

            const selectedIds = Array.from(checkedBoxes).map(cb => cb.value);
            document.getElementById("selected-cart-ids").value = JSON.stringify(selectedIds);
        });
    </script>

    </script>
    <form action="consumer_cart.php" method="POST" hidden id="delete-form">
        <input type="hidden" id="delete_product_id" name="cart_id">
        <input type="hidden" name="delete_cart_item" value="1">
    </form>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>