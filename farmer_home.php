<?php
// views/Farmerhome.php
session_start();
require_once 'db.php';

// Check if user is logged in
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    die('User not logged in.');
}

// Fetch farmer data
$farmerStmt = $pdo->prepare("
    SELECT farmers.*, users.username
    FROM farmers
    JOIN users ON farmers.user_id = users.id
    WHERE farmers.user_id = ?
");
$farmerStmt->execute([$user_id]);
$farmer = $farmerStmt->fetch(PDO::FETCH_ASSOC);

// Fetch all products (optional, not used in notification logic)
$productStmt = $pdo->query("SELECT * FROM products");
$products = $productStmt->fetchAll();

// Function to fetch farmer notifications with pagination
function displayFarmersNotification($pdo, $user_id)
{
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = 5;
    $offset = ($page - 1) * $limit;

    // Count total unique transactions that triggered notifications for the farmer
    $countQuery = "
        SELECT COUNT(DISTINCT t.transact_id)
        FROM notifications n
        JOIN transactions t ON n.transact_id = t.transact_id
        WHERE n.farmer_id = ?
          AND n.recipient_type = 'farmer'
    ";
    $stmt = $pdo->prepare($countQuery);
    $stmt->execute([$user_id]);
    $totalNotifFarmers = $stmt->fetchColumn();
    $totalPages = ceil($totalNotifFarmers / $limit);

    // Fetch paginated notifications with grouped product names
    $query = "
        SELECT 
            t.transact_id,
            t.created_at,
            GROUP_CONCAT(p.product_name SEPARATOR ', ') AS product_names
        FROM notifications n
        JOIN transactions t ON n.transact_id = t.transact_id
        JOIN transaction_details td ON t.transact_id = td.transact_id
        JOIN products p ON td.prod_id = p.id
        WHERE n.farmer_id = ?
          AND n.recipient_type = 'farmer'
        GROUP BY t.transact_id
        ORDER BY t.created_at DESC
        LIMIT ? OFFSET ?
    ";
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(1, $user_id, PDO::PARAM_INT);
    $stmt->bindValue(2, $limit, PDO::PARAM_INT);
    $stmt->bindValue(3, $offset, PDO::PARAM_INT);
    $stmt->execute();

    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return compact('notifications', 'search', 'page', 'totalPages');
}

// Extract results into local variables
extract(displayFarmersNotification($pdo, $user_id));
?>


<html>

<head>
    <title>
        Dashboard
    </title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&amp;display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="main_farmer_dash.css">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        * {
            font-family: 'Poppins';
        }

        body {
            margin: 0;
            font-family: 'Poppins';
            background: linear-gradient(45deg, #658147, #1C1D21);
            color: #fff;
        }

        .container {
            display: flex;
            height: 100vh;
        }

        .sidebar {
            width: 250px;
            background-color: #1a1a1a;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .sidebar img {
            border-radius: 50%;
            width: 100px;
            height: 100px;
        }

        .sidebar h2,
        .sidebar p {
            margin: 10px 0;
            text-align: center;
        }

        .sidebar h2 {
            font-size: 18px;
            font-weight: 500;
        }

        .sidebar p {
            font-size: 14px;
            color: #888;
        }

        .sidebar .menu {
            width: 100%;
            margin-top: 20px;
        }

        .sidebar .menu a {
            display: flex;
            align-items: center;
            padding: 10px 20px;
            color: #fff;
            text-decoration: none;
            font-size: 16px;
            margin-bottom: 10px;
            border-radius: 5px;
        }

        .sidebar .menu a.active,
        .sidebar .menu a:hover {
            background-color: #597445;
        }

        .sidebar .menu a i {
            margin-right: 10px;
        }

        .sidebar .logout-btn {
            margin-top: auto;
            padding: 7px 40px;
            background-color: #597445;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            cursor: pointer;
            border: none;
            font-size: 15px;
        }

        .main-content {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
        }

        .card {
            background-color: transparent;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .card h3 {
            margin: 0 0 10px;
            font-size: 18px;
        }

        .card .stats {
            display: flex;
            justify-content: space-between;
        }

        .card .stats .stat {
            display: flex;
            align-items: center;
        }

        .card .stats .stat i {
            font-size: 24px;
            margin-right: 10px;
        }

        .card .stats .stat .value {
            font-size: 24px;
            font-weight: bold;
        }

        .card .stats .stat .change {
            font-size: 14px;
            color: #4CAF50;
        }

        .card .stats .stat .change.down {
            color: #f44336;
        }

        .messages,
        .top-products,
        .latest-orders {
            display: flex;
            flex-direction: column;
        }

        .messages .message,
        .top-products .product,
        .latest-orders .order {
            display: flex;
            align-items: center;
            padding: 10px;
            background-color: #444;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .messages .message img,
        .top-products .product img {
            border-radius: 50%;
            width: 50px;
            height: 50px;
            margin-right: 10px;
        }

        .messages .message .text,
        .top-products .product .text {
            flex: 1;
        }

        .messages .message .text p,
        .top-products .product .text p {
            margin: 0;
        }

        .messages .message .text .time {
            font-size: 12px;
            color: #888;
        }

        .messages .message .reply {
            background-color: #4CAF50;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        .latest-orders table {
            width: 100%;
            border-collapse: collapse;
        }

        .latest-orders table th,
        .latest-orders table td {
            padding: 10px;
            text-align: left;
        }

        .latest-orders table th {
            background-color: #555;
        }

        .latest-orders table td {
            background-color: #444;
        }

        .latest-orders table .status.processing {
            color: #4CAF50;
        }

        .latest-orders table .status.completed {
            color: #f44336;
        }

        .notifications-list {
            padding: 0;
            list-style: none;
        }

        .notification-item {
            background-color: #1c1c1c;
            margin: 10px 0;
            padding: 15px;
            border-radius: 5px;
            color: white;
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
    </style>
</head>

<body>
    <div class="container">
        <div class="sidebar">
            <img
                alt="Profile picture of <?php echo htmlspecialchars($farmer['firstname'] . ' ' . $farmer['lastname']); ?>"
                height="100"
                width="100"
                src="uploads/<?php echo htmlspecialchars($farmer['farmer_image']); ?>" />
            <h2>
                <?php echo htmlspecialchars($farmer['firstname'] . ' ' . $farmer['lastname']); ?>
            </h2>
            <p>
                <?php echo htmlspecialchars($farmer['address']); ?>
            </p>
            <div class="menu">
                <a class="" href="farmer_home.php" style="background-color: #597445;">
                    <i class="fas fa-th-large">
                    </i>
                    Overview
                </a>
                <a href="farmer_product.php">
                    <i class="fas fa-box">
                    </i>
                    Products
                </a>
                <a href="farmer_order.php">
                    <i class="fas fa-shopping-cart">
                    </i>
                    Orders
                </a>
                <a href="farmer_account.php">
                    <i class="fas fa-user">
                    </i>
                    Account
                </a>
                <a href="farmer_feed.php">
                    <i class="fas fa-comment">
                    </i>
                    Feedback
                </a>
            </div>
            <div class="logout-btn">
                <button class="logout-btn">Logout</button>
            </div>
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
        </div>
        <div class="main-content">
            <div class="card">
                <div class="stats">
                    <div class="stat">
                        <div>
                        </div>
                    </div>
                    <div class="stat">
                        <div>
                        </div>
                    </div>
                    <div class="stat">
                        <div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="max-w-4xl mx-auto p-4">
                <h1 class="text-xl font-bold mb-4">Top Products in 2024</h1>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-zinc-800 p-4 rounded-lg flex items-center">
                        <img alt="Image of Rice" class="w-12 h-12 rounded-full mr-4" src="img/rice.png" />
                        <div>
                            <div class="font-semibold">Rice</div>
                            <div>345,656</div>
                        </div>
                    </div>
                    <div class="bg-zinc-800 p-4 rounded-lg flex items-center">
                        <img alt="Image of Apple" class="w-12 h-12 rounded-full mr-4" src="img/melon.png" />
                        <div>
                            <div class="font-semibold">Melon</div>
                            <div>200,654</div>
                        </div>
                    </div>
                    <div class="bg-zinc-800 p-4 rounded-lg flex items-center">
                        <img alt="Image of Orange" class="w-12 h-12 rounded-full mr-4" src="img/orange.png" />
                        <div>
                            <div class="font-semibold">Orange</div>
                            <div>189,767</div>
                        </div>
                    </div>
                </div>
            </div>

            <style>
                .forbody {
                    margin: 0;
                    padding: 0;
                }

                .notifications-list {
                    padding: 0;
                    list-style: none;
                }

                .notification-item {
                    background-color: #1c1c1c;
                    margin: 10px 0;
                    padding: 15px;
                    border-radius: 5px;
                    color: white;
                }
            </style>
            <section>
                <div class="forbody">
                    <h1 class="text-xl  mb-4">Your Notifications</h1>
                    <ul class="notifications-list">
                        <?php if (empty($notifications)): ?>
                            <div class="card" style="background-color: #1c1c1c;">
                                <div class="card-body text-center">
                                    <i class="fas fa-bell fa-3x mb-3" style="color:  #ff6b6b;"></i>
                                    <p style="color:  #ff6b6b;">No notifications yet</p>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="card" style="background-color: #1c1c1c;">
                                <div class="card-body">
                                    <ul class="list-group" style="list-style-type: none; padding: 0;">
                                        <?php foreach ($notifications as $notif): ?>
                                            <!-- <li class="notification-item"> -->
                                            <li class="notification-item">
                                                <div class="notification-details">
                                                    <i class="fas fa-bell"></i><br>
                                                    <strong>Your Order has been placed!</strong>
                                                    Order #<?= htmlspecialchars($notif['transact_id']) ?><br>
                                                    Product: <?= htmlspecialchars($notif['product_names']) ?><br>
                                                    <small>Order Date: <?= htmlspecialchars($notif['created_at']) ?></small>
                                                </div>
                                            </li>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                    </ul>
                                </div>
                                <div class="d-flex justify-content-center align-items-center mt-4 position-relative">

                                    <!-- Centered pagination buttons -->
                                    <div>
                                        <button id="prevBtn" class="me-2">&larr; Back</button>
                                        <button id="nextBtn">Next &rarr;</button>
                                    </div>

                                    <!-- Right-aligned page number (absolutely positioned) -->
                                    <div class="position-absolute end-0 me-3">
                                        <span class="text-white fw-bold" style="font-size: 13px;">
                                            Page <?= $page ?> of <?= $totalPages ?>
                                        </span>
                                    </div>

                                </div>

                                <!-- Hidden field to track the current page -->
                                <input type="hidden" id="currentPage" value="<?= $page ?>">

                                <script>
                                    document.addEventListener("DOMContentLoaded", function() {
                                        const prevBtn = document.getElementById("prevBtn");
                                        const nextBtn = document.getElementById("nextBtn");
                                        const currentPage = parseInt(document.getElementById("currentPage").value);
                                        const totalPages = <?= $totalPages ?>;
                                        const searchQuery = "<?= urlencode($search) ?>";

                                        // Disable buttons when on first/last page
                                        prevBtn.disabled = (currentPage <= 1);
                                        nextBtn.disabled = (currentPage >= totalPages);

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
                            </div>
                </div>
            </section>
            <style>
                .forbody {
                    max-width: 1000px;
                    margin: 30px auto;
                    padding: 20px;
                }

                .forbody h2 {
                    font-size: 2.2rem;

                    margin-bottom: 25px;

                    padding-left: 15px;
                }

                .notifications-list {
                    list-style: none;
                    padding: 0;
                    margin: 0;
                }

                .notification-item {
                    background-color: #fff3f3;
                    margin-bottom: 15px;
                    padding: 20px 25px;
                    border-radius: 12px;
                    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06);
                    transition: transform 0.2s ease, box-shadow 0.2s ease;
                }

                .notification-item:hover {
                    transform: scale(1.01);
                    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
                }

                .notification-details {
                    font-size: 1rem;
                    color: #333;
                }

                .notification-details i {
                    color: #ff6b6b;
                    margin-right: 10px;
                }

                .notification-details strong {
                    font-size: 1.1rem;
                    color: #d64545;
                }

                .notification-details small {
                    display: block;
                    color: #777;
                    margin-top: 5px;
                    font-size: 0.9rem;
                }

                /* Optional: different colors for notification types */
                .notification-item.success {
                    background-color: #e6ffed;
                }

                .notification-item.warning {
                    background-color: #fff9e6;
                }

                .notification-item.info {
                    background-color: #e6f4ff;
                }

                .notification-item.error {
                    background-color: #ffe6e6;
                }

                .card-body.text-center {
                    background-color: rgb(245, 232, 232);
                    padding: 30px;
                    border-radius: 12px;
                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
                }

                .card-body.text-center i {
                    color: #ff6b6b;
                }

                .card-body.text-center p {
                    color: #ff6b6b;
                    font-size: 1.1rem;
                }
            </style>
</body>

</html>