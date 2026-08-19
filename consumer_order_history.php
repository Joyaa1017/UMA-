<?php
// views/consumer_account.php
session_start();
require_once 'db.php';
require_once 'product_model.php';

// Fetch Consumers
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    die('User not logged in.');
}

$consumerstmt = $pdo->prepare("
    SELECT consumers.*, users.username, users.password
    FROM consumers
    JOIN users ON consumers.user_id = users.id
    WHERE consumers.user_id = ?
");
$consumerstmt->execute([$user_id]);
$consumers = $consumerstmt->fetch();

if (!$consumers) {
    die("Consumer not found.");
}

function displayConsumerTransactionsTable($pdo)
{
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
    $limit = 5;
    $offset = ($page - 1) * $limit;
    $searchParam = "%$search%";

    // Count total rows
    $countQuery = "SELECT COUNT(*) FROM products p 
                   JOIN farmers f ON p.user_id = f.user_id 
                   WHERE p.product_name LIKE ?";
    $stmt = $pdo->prepare($countQuery);
    $stmt->execute([$searchParam]);
    $totalProducts = $stmt->fetchColumn();
    $totalPages = ceil($totalProducts / $limit);

    // Fetch paginated farmers
    $query = "SELECT p.id, p.product_name, p.product_price, p.product_stock, p.product_category, p.product_image, p.product_description, f.firstname, f.lastname
              FROM products p 
              JOIN farmers f ON p.user_id = f.user_id
              WHERE p.product_name LIKE ? 
              LIMIT ? OFFSET ?";

    $stmt = $pdo->prepare($query);
    $stmt->bindValue(1, $searchParam, PDO::PARAM_STR);
    $stmt->bindValue(2, (int)$limit, PDO::PARAM_INT);
    $stmt->bindValue(3, (int)$offset, PDO::PARAM_INT);
    $stmt->execute();

    $productss = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return compact('productss', 'search', 'page', 'totalPages');
}

extract(displayConsumerTransactionsTable($pdo));
?>
<html>

<head>
    <link crossorigin="anonymous" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet" />
    <title>Order</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        * {
            font-family: 'Poppins';
        }

        body {
            background-color: #f8f9fa00;
            margin: 0;
            padding: 0;
        }

        .sidebar {
            background-color: #1c1c1c;
            color: white;
            height: 100vh;
            padding: 20px;
            position: fixed;
            width: 250px;
        }

        .sidebar img {
            border-radius: 50%;
            width: 100px;
            height: 100px;
        }

        .sidebar h4,
        .sidebar p {
            margin: 10px 0;
        }

        .sidebar .nav-link {
            color: white;
            font-size: 18px;
            margin: 10px 0;
        }

        .sidebar .nav-link.active {
            background-color: #597445;
            border-radius: 5px;
        }

        .sidebar .logout-btn {
            background-color: #597445;
            border: none;
            border-radius: 5px;
            color: white;
            margin-top: 20px;
            padding: 10px;
            width: 100%;
        }

        .content h2 {
            /* font-size: 5px; */
            font-weight: bold;
        }

        .content h2 span {
            font-weight: normal;
        }

        .content .form-control {
            background-color: transparent;
            border: none;
            border-bottom: 1px solid white;
            border-radius: 0;
            color: white;
            margin-bottom: 20px;
        }

        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap');

        * {
            font-family: 'Poppins';
        }

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

        .content .form-control:focus {
            box-shadow: none;
        }

        .content .form-select {
            background-color: transparent;
            border: none;
            border-bottom: 1px solid white;
            border-radius: 0;
            color: white;
            margin-bottom: 20px;
        }

        .content .form-select:focus {
            box-shadow: none;
        }

        .content .update-btn {
            background-color: black;
            border: none;
            border-radius: 5px;
            color: white;
            padding: 10px 20px;
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

        .table-container {
            max-width: 1100px;
            margin: 0 auto 50px;
            background-color: #fff;
            padding: 25px 30px;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        }

        .table-container h2 {
            font-size: 1.8rem;
            margin-bottom: 20px;

            padding-left: 10px;
        }

        .table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 10px;
        }

        .table thead th {
            background-color: #ff6b6b;
            color: white;
            padding: 14px 18px;    
            text-align: left;
            outline-width: 10px;
        }

        .table tbody tr:hover {
            transform: scale(1.01);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            background-color: #1C1D21;
        }

        .table td {
            padding: 14px 18px;
            vertical-align: middle;
        }


        .table td:last-child {
            font-weight: bold;
            text-transform: capitalize;
            color: #444;
        }

        .order-history {
            text-align: center;
            margin-bottom: 20px;
        }

        .order-history h1 {
            font-size: 2.5rem;
            font-weight: bold;
        }

        .order-history h1 span {
            color: #597445;
        }

        .btn-group {
            margin-bottom: 20px;
        }

        .btn-group .btn {
            border-radius: 20px;
            padding: 10px 20px;
            font-size: 1rem;
            font-weight: bold;
            background-color: rgba(0, 0, 0, 0);
            color: rgb(255, 255, 255);
        }

        .btn-group .btn:hover {
            background-color: #597445;
            color: white;
        }

        .table-container {
            background-color: #2c2c2c;
            border-radius: 20px;
            padding: 20px;
        }

        .table-container h2 {
            font-size: 1.5rem;
            margin-bottom: 20px;
        }

        .table-container .table {
            color:#444;
        }

        .table-container .table th,
        .table-container .table td {
            border: none;
        }

        .table-container .table th {
            font-weight: bold;
        }

        .table-container .table td {
            font-size: 1rem;
        }

        /* .table-container .table tbody td {
            color: white;
        } */

        .table-container .table .status-processing {
            color: #ffcc00;
        }

        .table-container .table .status-completed {
            color: #ffcc00;
        }

        .table-container .table-actions {
            text-align: right;
            margin-bottom: 10px;
        }

        .table-container .table-actions a {
            color: #597445;
            margin-left: 20px;
            text-decoration: none;
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
    <section>
        <div class="sidebar d-flex flex-column">
            <div class="sidebar-content">
                <div class="text-center">
                    <img
                        alt="Profile picture of <?php echo htmlspecialchars($consumers['firstname'] . ' ' . $consumers['lastname']); ?>"
                        height="100"
                        width="100"
                        src="uploads/<?php echo htmlspecialchars($consumers['consumer_image']); ?>" />
                    <h4>
                        <?php echo htmlspecialchars($consumers['firstname'] . ' ' . $consumers['lastname']); ?>
                    </h4>
                    <p>
                        <?php echo htmlspecialchars($consumers['address']); ?>
                    </p>
                </div>
                <nav class="nav flex-column">
                    <a class="nav-link " href="consumer_account.php">
                        <i class="fas fa-user"></i>
                        Account Info
                    </a>
                    <a class="nav-link active" href="consumer_order_history.php">
                        <i class="fas fa-box"></i>
                        Orders
                    </a>
                    <a class="nav-link" href="consumer_notif.php">
                        <i class="fas fa-bell"></i>
                        Notifications
                    </a>
                    <a class="nav-link" href="consumer_feed.php">
                        <i class="fas fa-comment"></i>
                        Feedback
                    </a>
                </nav>
            </div>
            <div class="mt-auto">
                <button class="logout-btn">Logout</button>
            </div>
        </div>
    </section>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const logoutBtn = document.querySelector('.logout-btn');

            // Create overlay elements
            const overlay = document.createElement('div');
            overlay.className = 'logout-overlay';

            const overlayContent = document.createElement('div');
            overlayContent.className = 'logout-overlay-content';

            const title = document.createElement('h2');
            title.textContent = 'Confirm Logout';

            const message = document.createElement('p');
            message.textContent = 'Are you sure you want to log out?';

            const confirmBtn = document.createElement('button');
            confirmBtn.textContent = 'Yes';
            confirmBtn.className = 'confirm-logout-btn';

            const cancelBtn = document.createElement('button');
            cancelBtn.textContent = 'Cancel';
            cancelBtn.className = 'cancel-logout-btn';

            // Construct overlay
            overlayContent.appendChild(title);
            overlayContent.appendChild(message);
            overlayContent.appendChild(confirmBtn);
            overlayContent.appendChild(cancelBtn);
            overlay.appendChild(overlayContent);

            // Add overlay to body
            document.body.appendChild(overlay);

            // Style overlay
            const style = document.createElement('style');
            style.textContent = `
        .logout-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            z-index: 1000;
        }
        .logout-overlay-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #1c1c1c;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
            color: white;
        }
        .logout-overlay-content h2 {
            margin-top: 0;
        }
        .logout-overlay-content button {
            margin: 10px;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .confirm-logout-btn {
            background-color: #597445;
            color: white;
        }
        .cancel-logout-btn {
            background-color: #ccc;
        }
    `;
            document.head.appendChild(style);

            // Event Listeners
            logoutBtn.addEventListener('click', function(e) {
                e.preventDefault();
                overlay.style.display = 'block';
            });

            cancelBtn.addEventListener('click', function() {
                overlay.style.display = 'none';
            });

            confirmBtn.addEventListener('click', function() {
                // Perform logout action
                window.location.href = "login.php";
            });

            overlay.addEventListener('click', function(e) {
                if (e.target === overlay) {
                    overlay.style.display = 'none';
                }
            });
        });
    </script>
    <div class="content">
        <div class="navbar">
            <img alt="Logo with a green and yellow leaf" height="108" width="108" src="img/uma.png" />
            <div class="icons">
                <div class="icon">
                    <a href="consumer_home.php">
                        <i class="fa fa-shopping-bag">
                        </i>
                    </a>
                </div>
                <div class="icon">
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

        <section>
            <div class="order-history">
                <h1>Order <span>History</span></h1>
            </div>
            <div class="table-container">
                <h2>Latest Orders</h2>

                <table class="table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Order Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="ordersBody">
                    </tbody>
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
                        window.location.href = `?page=${currentPage - 1}&search=${searchQuery}`;
                    }
                });

                nextBtn.addEventListener("click", function() {
                    if (currentPage < totalPages) {
                        window.location.href = `?page=${currentPage + 1}&search=${searchQuery}`;
                    }
                });
            });
        </script>

        <script>
            let currentPage = 1;

            document.addEventListener('DOMContentLoaded', function() {
                fetchOrders(currentPage);
            });

            function fetchOrders(page) {
                fetch(`consumer_fetch_orders.php?page=${page}`)
                    .then(response => response.json())
                    .then(data => {
                        const tbody = document.getElementById('ordersBody');
                        tbody.innerHTML = '';

                        data.orders.forEach(order => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                        <td>#${order.id}</td>
                        <td>${order.product_name}</td>
                        <td>${order.product_quantity}</td>
                        <td>₱${parseFloat(order.total_price).toFixed(2)}</td>
                        <td>${order.created_at}</td>
                        <td>${order.status}</td>
                    `;
                            tbody.appendChild(row);
                        });

                        renderPagination(data.totalPages, page);
                    })
                    .catch(error => {
                        console.error('Error fetching orders:', error);
                    });
            }

            function renderPagination(totalPages, currentPage) {
                const container = document.getElementById('pagination');
                container.innerHTML = '';

                for (let i = 1; i <= totalPages; i++) {
                    const btn = document.createElement('button');
                    btn.textContent = i;
                    btn.classList.add('page-btn');
                    if (i === currentPage) btn.classList.add('active');
                    btn.addEventListener('click', () => fetchOrders(i));
                    container.appendChild(btn);
                }
            }
        </script>
        </table>
    </div>
    </section>
</body>

</html>