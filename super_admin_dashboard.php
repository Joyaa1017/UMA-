<?php
// views/consumer_home.php
session_start();
require_once 'db.php';
// require_once 'product_model.php';

// Count total farmers
$stmtFarmers = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'Farmer'");
$totalFarmers = $stmtFarmers->fetchColumn();

// Count total consumers
$stmtConsumers = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'Consumer'");
$totalConsumers = $stmtConsumers->fetchColumn();

// Count total products
$stmtProducts = $pdo->query("SELECT COUNT(*) FROM products");
$totalProducts = $stmtProducts->fetchColumn();

// For fetching feedbacks ni 
$stmtFeedback = $pdo->query("
    SELECT f.feedback, f.ratings, f.created_at, u.username
    FROM feedbacks f
    JOIN users u ON f.user_id = u.id
    ORDER BY f.created_at DESC
    LIMIT 10
");
$feedbacks = $stmtFeedback->fetchAll(PDO::FETCH_ASSOC);

// For fetching ratings ni 
$stmtRatings = $pdo->query("
    SELECT ratings, COUNT(*) as count
    FROM feedbacks
    GROUP BY ratings
    ORDER BY ratings DESC
");
$ratingData = $stmtRatings->fetchAll(PDO::FETCH_ASSOC);

?>

<html>

<head>
    <link crossorigin="anonymous" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <title>Admin</title>
    <style>
        body {
            background: linear-gradient(45deg, #354226, #000000);
            color: white;
            margin: 0;
            padding: 0;
        }

        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap');

        * {
            font-family: 'Poppins';
        }

        p {
            font-size: 16px;
        }

        .text-black {
            color: #000000;
        }

        .content {
            background: linear-gradient(45deg, #658147, #1C1D21);
            color: white;
            margin-left: 250px;
            padding: 40px;
            height: 100vh;
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
                        alt="admin image"
                        height="100"
                        width="100"
                        src="img/administrator.png" />
                    <h4>
                        Welcome Admin
                    </h4>
                </div>
                <nav class="nav flex-column">
                    <a class="nav-link active" href="super_admin_dashboard.php">
                        <i class="fas fa-user"></i>
                        Dashboard
                    </a>
                    <a class="nav-link" href="super_admin_farmers.php">
                        <i class="fas fa-user-tie"></i>
                        Farmers
                    </a>
                    <a class="nav-link" href="super_admin_consumers.php">
                        <i class="fas fa-shopping-cart"></i>
                        Consumers
                    </a>
                    <a class="nav-link " href="super_admin_products.php">
                        <i class="fas fa-box"></i>
                        Products
                    </a>
                    <a class="nav-link " href="super_admin_report.php">
                        <i class="fas fa-box"></i>
                        Report
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
                window.location.href = "login_admin.php";
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
                    <a href="super_admin_setting.php">
                        <i class="fas fa-user"></i>
                    </a>
                </div>
            </div>
        </div>

        <main class="container mx-auto mt-8 p-4">
            <section class="bg-green p-6 rounded-lg shadow-lg">
                <h2 class="text-3xl font-bold mb-4">Overview</h2>
              <div class="row">
    <div class="col-md-4">
        <div class="bg-blue-600 p-4 rounded-lg shadow-lg text-white" style="text-align: center;">
            <h3 class="text-xl font-bold">Total Farmers</h3>
            <h4 class="text-2xl" id="totalFarmers"><?php echo $totalFarmers; ?></h4>
        </div>
    </div>

    <div class="col-md-4">
        <div class="bg-green-600 p-4 rounded-lg shadow-lg text-white" style="text-align: center;">
            <h3 class="text-xl font-bold">Total Consumers</h3>
            <h4 class="text-2xl" id="totalConsumers"><?php echo $totalConsumers; ?></h4>
        </div>
    </div>

    <div class="col-md-4">
        <div class="bg-yellow-600 p-4 rounded-lg shadow-lg text-white" style="text-align: center;">
            <h3 class="text-xl font-bold">Total Products</h3>
            <h4 class="text-2xl" id="totalProducts"><?php echo $totalProducts; ?></h4>
        </div>
    </div>
</div>

            </section>

            <section class="mt-5 bg-dark p-4 rounded shadow">
                <h3 class="text-white mb-3">Ratings Overview</h3>
                <canvas id="ratingChart" width="400" height="200"></canvas>
            </section>


            <div class="feedback_table">
                <section class="mt-5 bg-dark p-4 rounded shadow">
                    <h3 class="text-white mb-3">Recent Feedback</h3>
                    <table class="table table-striped table-dark">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Message</th>
                                <th>Rating</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($feedbacks as $fb): ?>
                                <tr>
                                    <td><?= htmlspecialchars($fb['username']) ?></td>
                                    <td><?= htmlspecialchars($fb['feedback']) ?></td>
                                    <td><?= htmlspecialchars($fb['ratings']) ?>⭐</td>
                                    <td><?= htmlspecialchars(date('d-m-Y', strtotime($fb['created_at']))) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </section>
            </div>
        </main>

        <script>
            const ratingData = <?php echo json_encode($ratingData); ?>;
            const labels = ratingData.map(item => item.ratings + "⭐");
            const data = ratingData.map(item => item.count);

            const ctx = document.getElementById('ratingChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Number of Ratings',
                        data: data,
                        backgroundColor: '#658147'
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            precision: 0
                        }
                    }
                }
            });
        </script>
</body>

</html>