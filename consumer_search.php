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
$consumerStmt = $pdo->prepare("SELECT * FROM consumers WHERE user_id = ?");
$consumerStmt->execute([$user_id]);
$consumers = $consumerStmt->fetchAll();

$productModel = new Product($pdo);
// / Handle search
$search = $_GET['search'] ?? '';

// Pagination settings
$limit = 12;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Get filtered product count for pagination
$totalProducts = $productModel->countFilteredProducts($search);
$totalPages = ceil($totalProducts / $limit);

// Fetch products with search and pagination
$products = $productModel->getFilteredProducts($search, $limit, $offset);
?>


<html>

<head>
    <link crossorigin="anonymous" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet" />
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        * {
            font-family: 'Poppins';
        }

        body {
            background: linear-gradient(45deg, #658147, #1C1D21);
            color: white;
        }

        .search-bar {
            margin: 20px 0;
        }

        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap');


        p {
            font-size: 16px;
        }

        .content {
            background: linear-gradient(45deg, #658147, #1C1D21);
            color: white;
            margin-left: 250px;
            padding: 40px;
            height: 100vh;
        }

        .search-bar input {
            border-radius: 20px;
            padding: 10px 20px;
            width: 300px;
        }
        
        .search-bar .fa-search,
        .search-bar .fa-sliders-h {
            margin-left: -30px;
            cursor: pointer;
        }

        .category-buttons {
            margin-bottom: 20px;
        }

        .category-buttons .btn {
            border-radius: 20px;
            margin: 5px;
        }

        .category-buttons .btn.active {
            background-color: #597445;
            color: white;
            border: #1c1d2100;
            padding-left: 1rem;
            padding-right: 1rem;
        }

        .product-card {
            background-color: white;
            border-radius: 10px;
            padding: 0;
            /* Remove padding to let image fill the card */
            margin: 10px;
            text-align: center;
            color: black;
            position: relative;
            width: 100%;
            /* Make the card full width of its column */
            height: 200px;
            /* Set a fixed height to ensure the card is square */
        }

        .product-card img {
            width: 100%;
            height: 100%;
            /* Ensure the image covers the card */
            border-radius: 10px;
            /* Match the card's border radius */
            object-fit: cover;
            /* Ensure the image covers the area without stretching */
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



        /* Pagination Arrow Styles */
        #prevBtn,
        #nextBtn {
            background-color: #444444;
            /* Bootstrap primary blue */
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: 500;
            border-radius: 8px;
            transition: background-color 0.3s, transform 0.2s;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            margin-bottom: 10px;
        }

        #prevBtn:hover,
        #nextBtn:hover {
            background-color: #597445;
            transform: translateY(-2px);
        }

        #prevBtn:disabled,
        #nextBtn:disabled {
            background-color: #adb5bd;
            cursor: not-allowed;
            box-shadow: none;
            transform: none;
        }

        @media (max-width: 768px) {
            .sidebar {
                height: auto;
                position: relative;
                width: 100%;
            }

            .content {
                margin-left: 0;
                padding: 20px;
            }

            .navbar .form-control {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <a class="btn" href="consumer_home.php" style="color: white;">
                <i class="fas fa-arrow-left fa-2x">
                </i>
            </a>
            <div class="search-bar d-flex align-items-center">
                <form method="GET" class="mb-3 d-flex">
                    <input type="text" name="search" class="form-control me-2" placeholder="Search Products..." value="<?= htmlspecialchars($search) ?>">
                    <?php if (!empty($search)): ?>
                        <a href="consumer_search.php" class="btn btn-danger" title="Clear search">
                            <i class="fas fa-times"></i>
                        </a>
                    <?php endif; ?>
                    <button type="submit" class="btn btn-light">Search</button>
                </form>
            </div>
            <div>
                <img height="100px" src="img/logo.png" width="" />
            </div>
        </div>
        <div class="category-buttons d-flex justify-content-center" id="categories">
            <button class="btn btn-light category-btn active" type="button" onclick="filterProducts('all')">All</button>
            <button class="btn btn-light category-btn" type="button" onclick="filterProducts('Grains')">Grains</button>
            <button class="btn btn-light category-btn" type="button" onclick="filterProducts('Fruits')">Fruits</button>
            <button class="btn btn-light category-btn" type="button" onclick="filterProducts('Vegetables')">Vegetables</button>
            <button class="btn btn-light category-btn" type="button" onclick="filterProducts('Spices')">Spices</button>
            <button class="btn btn-light category-btn" type="button" onclick="filterProducts('Dairy')">Dairy</button>
            <button class="btn btn-light category-btn" type="button" onclick="filterProducts('Chicken')">Chicken</button>
            <button class="btn btn-light category-btn" type="button" onclick="filterProducts('Meat')">Meat</button>
        </div>
        <section id="product-list" class="row">
            <?php foreach ($products as $product): ?>
                <div class="col-sm-3 product-item" data-category="<?= htmlspecialchars($product['product_category']) ?>">
                    <div class="thumb-wrapper">
                        <div class="img-box">
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
                                            <?= $product['farmer_id'] ?>
                                    )">
                        </div>
                        <div class="thumb-content">
                            <br>
                            <h4 class="px-5"><?= htmlspecialchars($product['product_name']) ?></h4>
                            <p class="item-price"><b>₱<?= number_format($product['product_price'], 2) ?>/Kilogram</b></p>

                            <!-- Order Button -->
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

                            <!-- Cart Button -->
                            <form action="consumer_cart.php" method="POST" style="display: inline;">
                                <input type="hidden" name="prod_id" value="<?= $product['id'] ?>">
                                <input type="hidden" name="product_quantity" value="1">
                                <input type="hidden" name="consumer_id" value="<?= $_SESSION['user_id'] ?>">
                                <input type="hidden" name="add_to_cart" value="1">
                                <button type="submit"
                                    class="btn btn-cart px-3"
                                    onclick="return confirm('Product added to cart successfully!')">
                                    <i class="fa fa-cart-plus me-2"></i>Cart
                                </button>
                            </form>
                            <style>
                                .btn-cart {
                                    background-color: #4f4f4f;
                                    /* dark gray */
                                    color: #fff;
                                    transition: background-color 0.3s ease;
                                }

                                .btn-cart:hover {
                                    background-color: rgba(255, 255, 255, 0.1);
                                    /* light overlay on hover */
                                    color: #fff;
                                }
                            </style>


                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

    </div>
    </section>

    </table>
    <div class="d-flex justify-content-center align-items-center mt-4 position-relative">
        <!-- Centered pagination buttons -->
        <div>
            <button id="prevBtn" class="btn btn-outline-light me-2">&larr; Back</button>
            <button id="nextBtn" class="btn btn-outline-light">Next &rarr;</button>
        </div>

        <!-- Page number on the right corner -->
        <div class="position-absolute end-0 me-4">
            <span class="text-white fw-bold" style="font-size: 13px;">Page <?= $page ?> of <?= $totalPages ?></span>
        </div>
    </div>
    <!-- Hidden field to track the current page -->
    <input type="hidden" id="currentPage" value="<?= $page ?>">

    </div>
    </section>
    <br> <br>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const prevBtn = document.getElementById("prevBtn");
            const nextBtn = document.getElementById("nextBtn");
            const currentPageInput = document.getElementById("currentPage");
            const currentPage = parseInt(currentPageInput.value);
            const totalPages = <?= $totalPages ?>;
            const searchQuery = "<?= urlencode($search) ?>";

            // Disable prev/next if on first or last page
            if (currentPage <= 1) {
                prevBtn.disabled = true;
            }
            if (currentPage >= totalPages) {
                nextBtn.disabled = true;
            }

            prevBtn.addEventListener("click", function() {
                if (currentPage > 1) {
                    window.location.href = `consumer_search.php?page=${currentPage - 1}&search=${searchQuery}`;
                }
            });

            nextBtn.addEventListener("click", function() {
                if (currentPage < totalPages) {
                    window.location.href = `consumer_search.php?page=${currentPage + 1}&search=${searchQuery}`;
                }
            });
        });
    </script>


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
        <div class="modal-dialog">a
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
    </div>
</body>

</html>