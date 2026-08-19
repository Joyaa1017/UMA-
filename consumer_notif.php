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

$consumerstmt = $pdo->prepare("SELECT * FROM consumers WHERE user_id = ?");
$consumerstmt->execute([$user_id]);
$consumers = $consumerstmt->fetch();

if (!$consumers) {
    die("Consumer not found.");
}

function displayConsumersNotification($pdo, $user_id)
{
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
    $limit = 5;
    $offset = ($page - 1) * $limit;

    // Count total rows for this farmer
    $countQuery = "
    SELECT COUNT(DISTINCT t.transact_id) 
    FROM notifications n
    JOIN transactions t ON n.transact_id = t.transact_id
    WHERE n.user_id = ?
      AND n.recipient_type = 'consumer'
";

    $stmt = $pdo->prepare($countQuery);
    $stmt->execute([$user_id]);
    $totalNotifConsumers = $stmt->fetchColumn();
    $totalPages = ceil($totalNotifConsumers / $limit);

    // Fetch paginated notifications for this consumer
    $query = "
    SELECT 
        t.transact_id AS transact_id,
        t.total_amount,
        t.created_at,
        GROUP_CONCAT(p.product_name SEPARATOR ', ') AS product_names
    FROM notifications n
    JOIN transactions t ON n.transact_id = t.transact_id
    JOIN transaction_details td ON t.transact_id = td.transact_id
    JOIN products p ON td.prod_id = p.id
    WHERE n.user_id = ?
     AND n.recipient_type = 'consumer'
    GROUP BY t.transact_id
    ORDER BY t.created_at DESC
    LIMIT ? OFFSET ?
";

    $stmt = $pdo->prepare($query);
    $stmt->bindValue(1, $user_id, PDO::PARAM_INT);
    $stmt->bindValue(2, (int)$limit, PDO::PARAM_INT);
    $stmt->bindValue(3, (int)$offset, PDO::PARAM_INT);
    $stmt->execute();

    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // echo "<pre>";
    // echo "Total Notifications: $totalNotifConsumers\n";
    // echo "Current Page: $page / $totalPages\n";
    // print_r($notifications);
    // echo "</pre>";


    return compact('notifications', 'search', 'page', 'totalPages');
}

extract(displayConsumersNotification($pdo, $user_id));
?>

<html>

<head>
    <link crossorigin="anonymous" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet" />
    <title>Notifications</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        html,
        body {
            height: 100%;
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
            padding: 70px;
            min-height: 100%;
        }

        .sidebar {
            background-color: #1c1c1c;
            color: white;
            height: 100vh;
            padding: 20px;
            position: fixed;
            /* position: absolute; */
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
            font-size: 36px;
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
                    <a class="nav-link" href="consumer_account.php">
                        <i class="fas fa-user"></i>
                        Account Info
                    </a>
                    <a class="nav-link" href="consumer_order_history.php">
                        <i class="fas fa-box"></i>
                        Orders
                    </a>
                    <a class="nav-link active" href="consumer_notif.php">
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
            <img alt="Logo with a green and yellow leaf" href="consumer_home.php" height="108" width="108" src="img/uma.png" />
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
            <div class="forbody">
                <h2 class="mb-4">Your Notifications</h2>
                <ul class="notifications-list">
                    <?php if (empty($notifications)): ?>
                        <div class="card" style="background-color: #1c1c1c;">
                            <div class="card-body text-center">
                                <i class="fas fa-bell fa-3x mb-3" style="color: #ff6b6b;"></i>
                                <p style="color: black;">No notifications yet</p>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="card" style="background-color: #1c1c1c;">
                            <div class="card-body">
                                <ul class="list-group" style="list-style-type: none; padding: 0;">
                                    <?php foreach ($notifications as $notif): ?>
                                        <li class="notification-item mb-3 p-3 border rounded" style="background-color: #2a2a2a; color: white;">
                                            <div class="notification-details">
                                                <i class="fas fa-bell mb-2"></i>
                                                <strong>Your order has been placed!</strong><br>
                                                <span>Order #: <?= htmlspecialchars($notif['transact_id']) ?></span><br>
                                                <span>Products: <?= htmlspecialchars($notif['product_names']) ?></span><br>
                                                <span>Total Amount: <?= htmlspecialchars($notif['total_amount']) ?></span><br>
                                                <small>Order Date: <?= htmlspecialchars($notif['created_at']) ?></small>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                </ul>
                            </div>
                            <?php if (!empty($notifications) && $totalPages > 1): ?>

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
                            <?php endif; ?>
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

    </div>
    </section>
    </div>
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
            background-color: #fff3f3;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .card-body.text-center i {
            color: #ff6b6b;
        }

        .card-body.text-center p {
            color: #555;
            font-size: 1.1rem;
        }
    </style>


</body>

</html>