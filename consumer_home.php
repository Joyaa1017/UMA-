<?php
// views/consumer_home.php
session_start();
require_once 'db.php';
require_once 'product_model.php';

// Fetch Consumers
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    die('User not logged in.');
}
// Fetch consumers
$consumerStmt = $pdo->prepare("CALL get_consumer_by_user_id(?)");
$consumerStmt->execute([$user_id]);
$consumers = $consumerStmt->fetchAll(PDO::FETCH_ASSOC);
$consumerStmt->closeCursor();

$productModel = new Product($pdo);
$products = $productModel->allWithFarmers();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>User Homepage</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link crossorigin="anonymous" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />


    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap');

        body {
            background: linear-gradient(45deg, #658147, #1C1D21);
            color: white;
            font-family: 'DM Sans';
        }

        .navbar {
            background-color: transparent;
            display: flex;
            justify-content: flex-end;
            padding: 20px;
            position: relative;
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

        /* Centered logo BELOW navbar */
        .logo-container {
            text-align: center;
            margin-top: 30px;
            /* Push the logo further down */
            margin-bottom: -80px;


        }

        .center-logo {
            width: 108px;
            height: 108px;
            border: none;
            border-radius: 50px;

        }

        .green {
            color: #658147;
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

        h2 {
            color: #ffffff;
            font-size: 26px;
            font-weight: 300;
            text-align: center;
            text-transform: uppercase;
            position: relative;
            margin: 30px 0 60px;
        }

        h2::after {
            content: "";
            width: 100px;
            position: absolute;
            margin: 0 auto;
            height: 4px;
            border-radius: 1px;
            background: #658147;
            left: 0;
            right: 0;
            bottom: -20px;
        }

        .thumb-wrapper {
            padding: 25px 15px;
            background: linear-gradient(135deg, #597445, #1C1D21);
            border-radius: 6px;
            text-align: center;
            position: relative;
            box-shadow: 0 2px 3px rgba(0, 0, 0, 0.2);
            margin-bottom: 30px;
            /* Add margin for spacing */
        }

        .thumb-content h4 {
            font-size: 18px;
        }

        .thumb-content .icon {
            text-decoration: none;
            color: white;
        }

        .thumb-content .icon:hover {
            color: #96b97b;
        }

        .item-price {
            font-size: 13px;
            padding: 2px 0;
        }

        .item-price strike {
            opacity: 0.7;
            margin-right: 5px;
        }

        .wish-icon {
            position: absolute;
            right: 10px;
            top: 10px;
            z-index: 99;
            cursor: pointer;
            font-size: 16px;
            color: #b64747;
            /* Change to a more visible color if needed */
        }

        /* Add CSS for the wish icon */
        .wish-icon .fa-heart {
            color: #ff6161;
        }

        .container {
            padding: 50px 0;
        }

        .profile {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
            padding: 10px;
        }

        .profile img {
            border-radius: 50%;
            width: 50px;
            height: 50px;
            margin-right: 15px;
        }

        .profile-text {
            text-align: left;
        }

        .profile-text h5 {
            margin: 0;
            font-size: 16px;
            color: #ffffff;
            white-space: nowrap;
        }

        .profile-text p {
            margin: 0;
            font-size: 14px;
            color: #b0b0b0;
        }

        #img {
            width: 108px;
            height: 108px;
            border: none;
            border-radius: 10px;
        }

        .btn-see-more {
            background-color: #698a48;
            border: none;
            padding: 10px 100px;
            color: white;
            font-size: 16px;
            border-radius: 5px;
            margin-top: 30px;
        }

        .modal-content {
            background: linear-gradient(135deg, #597445, #1C1D21);
            /* Same gradient as body */
            color: white;
            /* Text color */
            border-radius: 10px;
            /* Rounded corners */
        }

        .modal-header {
            border-bottom: none;
            /* Remove border */
        }

        .modal-title {
            font-size: 24px;
            /* Increase title size */
            font-weight: bold;
            /* Bold title */
        }

        .modal-body {
            padding: 20px;
            /* Padding for body */
        }

        .modal-footer {
            border-top: none;
            /* Remove border */
        }

        /* Button styles */
        .btn-primary {
            background-color: #ffffff;
            /* Button color */
            color: #1a1a1a;
            /* Text color */
            border: none;
            /* Remove border */
            border-radius: 20px;
            /* Rounded corners */
        }

        .btn-outline-secondary {
            color: white;
            /* Text color */
            border-color: white;
            /* Border color */
        }

        .btn-outline-secondary:hover {
            background-color: rgba(255, 255, 255, 0.1);
            /* Hover effect */
        }
    </style>
    <script>
        $(document).ready(function() {
            $(".wish-icon i").click(function() {
                $(this).toggleClass("fa-heart fa-heart-o");
            });
        });
    </script>
</head>

<body>
    <div class="container-xl">
        <br><br>
        <div class="navbar">
            <div class="icons">
                <div class="icon">
                    <a href="consumer_search.php">
                        <i class="fas fa-search"></i>
                    </a>
                </div>
                <div class="icon">
                    <a href="consumer_cart.php">
                        <i class="fas fa-shopping-cart"></i>
                    </a>
                </div>
                <div class="icon">
                    <a href="consumer_account.php">
                        <i class="fas fa-user"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Logo centered BELOW the navbar -->
        <div class="logo-container">
            <a href="consumer_home.php">
                <img alt="Logo with a green and yellow leaf" src="img/logo.png" class="center-logo">
            </a>
        </div>

        <section>
            <div class="container text-center mt-5">

                <h1 class="welcome-text">
                    Welcome,

                    <?php foreach ($consumers as $consumer) { ?>
                        <span class="highlight-text"><?php echo htmlspecialchars($consumer['firstname']); ?></span>
                    <?php } ?>
                </h1>
                <h1 class="welcome-text" style="font-weight: bold;">Discover the freshness of local</h1>
                <p class="lead">Experience the true taste of your community with UMA. Our online marketplace connects
                    <br> you directly to local farmers, food producers, and community partners, offering a wide
                    <br>variety of fresh, sustainable, and delicious products.
                </p>
                <a class="btn btn-custom" style="margin-bottom: 11rem;" href="#categories"> Find farm products </a>
            </div>
        </section>
        <section>
            <div class="row">
                <div class="col-md-12">
                    <!--For Categories (moved above Featured Products)-->
                    <section>
                        <div class="container text-center mt-5" id="categories">
                            <h2>Featured <b>Products</b></h2>
                            <button class="btn category-btn active" type="button" onclick="filterProducts('all')">All</button>
                            <button class="btn category-btn" type="button" onclick="filterProducts('Grains')">Grains</button>
                            <button class="btn category-btn" type="button" onclick="filterProducts('Fruits')">Fruits</button>
                            <button class="btn category-btn" type="button" onclick="filterProducts('Vegetables')">Vegetables</button>
                            <button class="btn category-btn" type="button" onclick="filterProducts('Spices')">Spices</button>
                            <button class="btn category-btn" type="button" onclick="filterProducts('Dairy')">Dairy</button>
                            <button class="btn category-btn" type="button" onclick="filterProducts('Chicken')">Chicken</button>
                            <button class="btn category-btn" type="button" onclick="filterProducts('Meat')">Meat</button>
                            <br><br>
                        </div>
                    </section>

                    <style>
                        .btn-cart {
                            background-color: #4f4f4f;
                            /* dark gray */


                            color: #fff;
                            transition: background-color 0.3s ease;
                        }

                        .btn-cart:hover {
                            background-color: rgba(255, 255, 255, 0.1);
                            color: #fff;
                        }
                    </style>

                    <section id="product-list" class="row">
                        <?php foreach ($products as $product): ?>
                            <div class="col-sm-3 product-item" data-category="<?= htmlspecialchars($product['product_category']) ?>">
                                <div class="thumb-wrapper p-2 border rounded shadow-sm bg-white mb-4">
                                    <div class="img-box text-center">
                                        <img src="uploads/<?= htmlspecialchars($product['product_image']) ?>"
                                            class="img-fluid"
                                            alt="Product's picture"
                                            style="height:150px; width:150px; cursor:pointer;"
                                            data-bs-toggle="modal"
                                            data-bs-target="#productDetailsModal"
                                            onclick="showProductDetails(
                            '<?= htmlspecialchars(addslashes($product['product_name'])) ?>',
                            '<?= htmlspecialchars(addslashes($product['product_description'])) ?>',
                            '<?= htmlspecialchars(addslashes($product['product_image'])) ?>',
                            '<?= htmlspecialchars(addslashes($product['firstname'] . ' ' . $product['lastname'])) ?>',
                            <?= $product['product_price'] ?>,
                            'Ships within 2-3 business days.',
                            <?= $product['id'] ?>,
                            <?= $product['user_id'] ?>
                        )">
                                    </div>
                                    <div class="thumb-content">
                                        <br>
                                        <h4 class="px-5"><?= htmlspecialchars($product['product_name']) ?></h4>
                                        <p class="item-price"><b>₱<?= number_format($product['product_price'], 2) ?>/Kilogram</b></p>
                                        <div class="d-flex justify-content-center gap-2">
                                            <!-- Add this CSS (inside <style> tag or external CSS file) -->
                                            <style>
                                                .btn-order {
                                                    background-color: #597445;
                                                    color: #fff;
                                                    transition: background-color 0.3s ease;
                                                }

                                                .btn-order:hover {
                                                    background-color: #4a5e39;
                                                }
                                            </style>

                                            <!-- Order Button -->
                                            <form action="consumer_order_checkout.php" method="POST" id="sub_order_<?= $product['id'] ?>" style="display: inline;">
                                                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                                <button type="button"
                                                    class="btn btn-order px-3"
                                                    onclick="document.getElementById('sub_order_<?= $product['id'] ?>').submit()">
                                                    <i class="fa fa-shopping-bag me-2"></i>Order
                                                </button>
                                            </form>


                                            <!-- Cart Button (Blue with hover) -->
                                            <form action="consumer_cart.php" method="POST">
                                                <input type="hidden" name="prod_id" value="<?= $product['id'] ?>">
                                                <input type="hidden" name="product_quantity" value="1">
                                                <input type="hidden" name="consumer_id" value="<?= $_SESSION['user_id'] ?>">
                                                <input type="hidden" name="add_to_cart" value="1">
                                                <button type="submit" class="btn btn-cart px-3">
                                                    <i class="fa fa-cart-plus me-2"></i>Cart
                                                </button>
                                            </form>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </section>

                    <script>
                        function filterProducts(category) {
                            const products = document.querySelectorAll('.product-item');

                            products.forEach(product => {
                                const productCategory = product.getAttribute('data-category');
                                const priceElement = product.querySelector('.item-price b');

                                // Update unit based on category
                                if (priceElement) {
                                    if (productCategory === 'Spices') {
                                        priceElement.textContent = priceElement.textContent.replace(/\/(Kilogram|Piece|Dozen|Liter|Bottle)?/g, '/Bottle');
                                    } else {
                                        priceElement.textContent = priceElement.textContent.replace(/\/(Kilogram|Piece|Dozen|Liter|Bottle)?/g, '/Kilogram');
                                    }
                                }

                                // Show or hide products based on selected category
                                if (category === 'all' || productCategory === category) {
                                    product.style.display = 'block';
                                } else {
                                    product.style.display = 'none';
                                }
                            });

                            // Highlight active button
                            const buttons = document.querySelectorAll('.category-btn');
                            buttons.forEach(btn => btn.classList.remove('active'));
                            document.querySelector(`[onclick="filterProducts('${category}')"]`).classList.add('active');
                        }

                        function showProductDetails(name, description, image, farmer, price, shipping, productId, farmerId) {
                            document.getElementById('modalProductName').innerText = name;
                            document.getElementById('modalProductPrice').innerText = '₱' + parseFloat(price).toFixed(2) + '/Kilogram';
                            document.getElementById('modalProductDescription').innerText = description;
                            document.getElementById('modalProductFarmer').innerText = farmer;
                            document.getElementById('modalProductFarmerLink').href = 'consumer_farmer_profile.php?farmer_id=' + farmerId;
                            document.getElementById('modalShippingInfo').innerText = shipping;
                            document.getElementById('modalProductImage').src = 'uploads/' + image;

                            // ✅ Dynamically set data attributes for the report button
                            const reportBtn = document.getElementById('reportProductBtn');
                            reportBtn.dataset.productId = productId;
                            reportBtn.dataset.farmerId = farmerId;
                            reportBtn.dataset.productName = name;
                            reportBtn.dataset.userId = <?= json_encode($user_id) ?>;
                        }
                    </script>


                    <!-- Product Details Modal -->
                    <style>
                        .modal-content {
                            border-radius: 15px;
                            overflow: hidden;
                        }

                        .modal-header.bg-dark,
                        .modal-footer.bg-dark {
                            background-color: #212529 !important;
                            border-bottom: 1px solid #444;
                            border-top: 1px solid #444;
                        }

                        .modal-body.bg-dark {
                            background-color: #1c1f23 !important;
                        }

                        .modal-title {
                            color: #f8f9fa;
                        }

                        .btn-close {
                            filter: invert(1);
                        }

                        .info-label {
                            font-weight: 600;
                            color: #adb5bd;
                            font-size: 0.9rem;
                        }

                        .info-value {
                            color: #f8f9fa;
                        }

                        .btn-report {
                            position: absolute;
                            top: 15px;
                            right: 20px;
                            z-index: 10;
                        }

                        .green {
                            color: #28a745;
                        }
                    </style>
                    <!-- CSS STYLES -->
                    <style>
                        /* Modal Image Styling - Fit image fully without cropping */
                        #modalProductImage {
                            width: 100%;
                            height: 300px;
                            object-fit: contain;
                            /* ensures entire image fits */
                            display: block;
                            margin: 0 auto;
                            border-radius: 8px;
                            background-color: #1e1e1e;
                            /* background to handle transparent or small images */
                            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
                        }

                        /* Responsive image height for smaller devices */
                        @media (max-width: 576px) {
                            #modalProductImage {
                                height: 200px;
                            }
                        }
                    </style>

                    <!-- PRODUCT DETAILS MODAL -->
                    <div class="modal fade" id="productDetailsModal" tabindex="-1" aria-labelledby="productDetailsModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header bg-dark">
                                    <h5 class="modal-title" id="productDetailsModalLabel">Product Details</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>

                                <div class="modal-body bg-dark text-light">
                                    <div class="row g-4">

                                        <!-- Image Section -->
                                        <div class="col-md-6 text-center">
                                            <img id="modalProductImage" alt="Product Image">
                                        </div>

                                        <!-- Details Section -->
                                        <div class="col-md-6 position-relative">
                                            <!-- Report Button -->
                                            <button type="button" class="btn btn-outline-danger btn-sm btn-report"
                                                id="reportProductBtn"
                                                data-bs-toggle="modal"
                                                data-bs-target="#reportModal">
                                                <i class="fa fa-flag"></i> Report
                                            </button>

                                            <!-- Product Info -->
                                            <h4 id="modalProductName" class="mt-2 fw-bold text-white"></h4>
                                            <p id="modalProductPrice" class="fs-5 text-success fw-semibold mb-2"></p>

                                            <div>
                                                <span class="info-label">Farmer:</span>
                                                <a href="#" id="modalProductFarmerLink" class="info-value text-decoration-none text-success" target="_blank">
                                                    <span id="modalProductFarmer"></span>
                                                </a>
                                            </div>

                                            <!-- Description -->
                                            <div class="mt-4">
                                                <h6 class="green">Description</h6>
                                                <p id="modalProductDescription" class="info-value"></p>
                                            </div>

                                            <!-- Shipping Info -->
                                            <div class="mt-3">
                                                <h6 class="green">Shipping Information</h6>
                                                <p id="modalShippingInfo" class="info-value"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer bg-dark">
                                    <!-- Optional footer buttons -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- REPORT PRODUCT MODAL -->
                    <div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">

                                <!-- Modal Header -->
                                <div class="modal-header">
                                    <h5 class="modal-title" id="reportModalLabel">Report Product: <span id="reported_product_name"></span></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>

                                <!-- Modal Body -->
                                <div class="modal-body">
                                    <form id="report-form" method="POST" action="report_controller.php">

                                        <!-- Hidden Inputs -->
                                        <input type="hidden" name="product_id" id="product_id">
                                        <input type="hidden" name="farmer_id" id="farmer_id">
                                        <input type="hidden" name="reporter_user_id" id="reporter_user_id">

                                        <!-- Reason Dropdown -->
                                        <div class="mb-3">
                                            <label for="report-reason" class="form-label">Reason for Reporting</label>
                                            <select class="form-select" name="reason" id="report-reason" required>
                                                <option value="Profanity">Profanity</option>
                                                <option value="Abuse">Abuse</option>
                                                <option value="Harassment">Harassment</option>
                                                <option value="Spam">Spam</option>
                                                <option value="Other">Other</option>
                                            </select>
                                        </div>

                                        <!-- Optional Custom Reason -->
                                        <div class="mb-3" id="custom-reason-container">
                                            <label for="custom-reason" class="form-label">Custom Reason (Optional)</label>
                                            <textarea class="form-control" name="custom_reason" id="custom-reason" rows="3"></textarea>
                                        </div>

                                        <!-- Submit Button with Confirmation -->
                                        <button type="submit"
                                            class="btn btn-danger"
                                            onclick="return confirm('Report submitted successfully!');">
                                            Report
                                        </button>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>



                    <script>
                        $(document).ready(function() {
                            $('#reportModal').on('show.bs.modal', function(event) {
                                var button = event.relatedTarget; // This is the button that triggered the modal

                                var productId = button.getAttribute('data-product-id');
                                var farmerId = button.getAttribute('data-farmer-id');
                                var productName = button.getAttribute('data-product-name');
                                var userId = button.getAttribute('data-user-id');

                                var modal = $(this);
                                modal.find('#product_id').val(productId);
                                modal.find('#farmer_id').val(farmerId);
                                modal.find('#reported_product_name').text(productName);
                                modal.find('#reporter_user_id').val(userId);
                            });
                        });
                    </script>
                    <script>
                        function filterProducts(category) {
                            const products = document.querySelectorAll('.product-item');
                            let shownCount = 0;

                            products.forEach(product => {
                                const productCategory = product.getAttribute('data-category');
                                const priceElement = product.querySelector('.item-price b');

                                // Update price unit based on category
                                if (priceElement) {
                                    if (productCategory === 'Spices') {
                                        priceElement.textContent = priceElement.textContent.replace(/\/(Kilogram|Piece|Dozen|Liter|Bottle)?/g, '/Bottle');
                                    } else {
                                        priceElement.textContent = priceElement.textContent.replace(/\/(Kilogram|Piece|Dozen|Liter|Bottle)?/g, '/Kilogram');
                                    }
                                }

                                // Filtering logic
                                if (category === 'all') {
                                    // Show only the first 8 items
                                    if (shownCount < 8) {
                                        product.style.display = 'block';
                                        shownCount++;
                                    } else {
                                        product.style.display = 'none';
                                    }
                                } else {
                                    // Show all matching category items
                                    if (productCategory === category) {
                                        product.style.display = 'block';
                                    } else {
                                        product.style.display = 'none';
                                    }
                                }
                            });

                            // Update active button style
                            const buttons = document.querySelectorAll('.category-btn');
                            buttons.forEach(btn => btn.classList.remove('active'));
                            document.querySelector(`[onclick="filterProducts('${category}')"]`).classList.add('active');
                        }

                        // Apply default 'all' filter on page load
                        document.addEventListener("DOMContentLoaded", () => filterProducts('all'));
                    </script>

</body>


</html>