<?php
// views/Farmerhome.php
session_start();
require_once 'db.php';

// Fetch Farmers
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    die('User not logged in.');
}

$farmerStmt = $pdo->prepare("
    SELECT farmers.*, users.username, users.password
    FROM farmers
    JOIN users ON farmers.user_id = users.id
    WHERE farmers.user_id = ?
");
$farmerStmt->execute([$user_id]);
$farmer = $farmerStmt->fetch(PDO::FETCH_ASSOC);

// Fetch products
$productStmt = $pdo->query("SELECT * FROM products");
$products = $productStmt->fetchAll();
?>

<html>

<head>
    <title>
        Dashboard
    </title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&amp;display=swap" rel="stylesheet" />
    <!-- <link rel="stylesheet" href="main_farmer_dash.css"> -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        * {
            font-family: 'Poppins';
        }

        body {
            margin: 0;
            font-family: 'Roboto', sans-serif;
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

        .status-dropdown {
            padding: 5px 8px;
            border-radius: 5px;
            color: #fff;
            background-color: red;
            border: none;
        }

        .status-dropdown.completed {
            background-color: #597445;
        }

        .status-dropdown.processing {
            background-color: red;
        }

        .status-dropdown.in-delivery {
            background-color: orange;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px;
            max-width: 400px;
            width: 90%;
        }

        .cancel-btn {
            background-color: red;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
        }

        /* Base button style */
        .status-button {
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 4px;
            color: white;
            border: none;
        }

        /* Processing Button - Red */
        .status-button.processing {
            background-color: #f44336;
            /* Red background */
            border: 1px solid #f44336;
            /* Red border */
        }

        .status-button.processing:hover {
            background-color: #d32f2f;
            /* Darker red on hover */
            border-color: #d32f2f;
            /* Darker red border on hover */
        }

        /* Completed Button - Green */
        .status-button.completed {
            background-color: #4CAF50;
            /* Green background */
            border: 1px solid #4CAF50;
            /* Green border */
        }

        .status-button.completed:hover {
            background-color: #45a049;
            /* Darker green on hover */
            border-color: #45a049;
            /* Darker green border on hover */
        }

        /* Filter Button Active State */
        .filter-buttons button.active {
            background-color: #4CAF50;
            /* Green background for active filter */
            color: white;
        }

        /* Filter Buttons Hover Effect */
        .filter-buttons button:hover {
            background-color: #ddd;
            /* Light gray on hover */
            color: black;
        }

        /* Modal Styles */
        .modal {
            display: none;
            /* Hidden by default */
            position: fixed;
            z-index: 1;
            /* Sit on top */
            left: 0;
            top: 0;
            width: 100%;
            /* Full width */
            height: 100%;
            /* Full height */
            background-color: rgb(0, 0, 0);
            /* Fallback color */
            background-color: rgba(0, 0, 0, 0.4);
            /* Black with opacity */
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 30%;
            text-align: center;
            color: black;
            /* Set modal text to black */
        }

        #confirmStatus,
        #cancelStatus {
            padding: 10px 20px;
            margin: 10px;
            cursor: pointer;
            border: none;
            border-radius: 4px;
            background-color: #4CAF50;
            color: white;
        }

        #cancelStatus {
            background-color: #f44336;
        }

        #confirmStatus:hover,
        #cancelStatus:hover {
            background-color: #45a049;
        }

        .filter-buttons button {
            margin-right: 10px;
            padding: 8px 14px;
            font-size: 16px;
            cursor: pointer;
            border: 1px solid #ccc;
            background-color: #f8f8f8;
            border-radius: 4px;
            transition: background-color 0.2s;
        }

        .filter-buttons button {
            margin-right: 10px;
            padding: 5px 10px;
            cursor: pointer;
            color: black;
            /* Set text color to black */
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
                <a class="" href="farmer_home.php">
                    <i class="fas fa-th-large">
                    </i>
                    Overview
                </a>
                <a href="farmer_product.php">
                    <i class="fas fa-box">
                    </i>
                    Products
                </a>
                <a href="farmer_order.php" style="background-color: #597445;">
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
            <br><br>
            <div class="header" style="font-size:x-large;">
                <h1><b>Latest Orders</b></h1>
            </div>
            <div class="card latest-orders">

                <!-- Filter Buttons -->
                <div class="filter-buttons d-flex gap-2">
                    <button id="all" onclick="filterOrders('all')" class="btn btn-outline-light" title="All">
                        <i class="fas fa-list"></i>
                    </button>
                    <button id="processing" onclick="filterOrders('processing')" class="btn btn-outline-light" title="Processing">
                        <i class="fas fa-spinner"></i>
                    </button>
                    <button id="in-delivery" onclick="filterOrders('in-delivery')" class="btn btn-outline-light" title="In Delivery">
                        <i class="fas fa-truck"></i>
                    </button>
                    <button id="completed" onclick="filterOrders('completed')" class="btn btn-outline-light" title="Completed">
                        <i class="fas fa-check-circle"></i>
                    </button>
                </div>

                <br>
                <table id="ordersTable">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Customer Name</th>
                            <th>Order Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="ordersBody">
                        <!-- Rows will be inserted dynamically -->
                    </tbody>
                </table>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        fetchOrders();
                    });

                    function fetchOrders() {
                        fetch('farmer_fetch_orders.php')
                            .then(response => response.json())
                            .then(data => {
                                console.log(data);
                                const tbody = document.getElementById('ordersBody');
                                tbody.innerHTML = '';
                                data.forEach(order => {
                                    const status = order.status.toLowerCase();
                                    const row = document.createElement('tr');
                                    row.setAttribute('data-id', order.id);
                                    row.setAttribute('data-status', status);

                                    row.innerHTML = `
        <td>#${order.id}</td>
        <td>${order.product_name}</td>
        <td>${order.product_quantity}</td>
        <td>₱${parseFloat(order.total_price).toFixed(2)}</td>
        <td>${order.firstname} ${order.lastname}</td>
        <td>${order.created_at}</td>
        <td>
            <select class="status-dropdown ${status}" onchange="confirmStatusChange(this, ${order.id})">
                <option value="processing" ${status === 'processing' ? 'selected' : ''}>Processing</option>
                <option value="in-delivery" ${status === 'in-delivery' ? 'selected' : ''}>In Delivery</option>
                <option value="completed" ${status === 'completed' ? 'selected' : ''}>Completed</option>
            </select>
        </td>
    `;
                                    tbody.appendChild(row);
                                });

                            })

                            .catch(error => {
                                console.error('Error fetching orders:', error);
                                alert("Failed to fetch orders. Check console for details.");
                            });
                    }
                </script>
            </div>

            <!-- Modal -->
            <div id="statusModal" class="modal">
                <div class="modal-content">
                    <h2 id="modalText">Are you sure you want to change the status?</h2>
                    <button id="confirmStatus" onclick="applyStatusChange()">Yes</button>
                    <button class="cancel-btn" onclick="closeModal()">No</button>

                </div>
            </div>

            <!-- CSS -->

            </style>

            <script>
                let currentDropdown;
                let selectedStatus;

                function confirmStatusChange(selectElement) {
                    selectedStatus = selectElement.value;
                    currentDropdown = selectElement;

                    // Revert selection until confirmed
                    const currentRow = selectElement.closest('tr');
                    const currentStatus = currentRow.getAttribute('data-status');
                    selectElement.value = currentStatus;

                    document.getElementById("modalText").textContent =
                        `Are you sure you want to change the status to "${selectedStatus.replace('-', ' ')}"?`;

                    document.getElementById('statusModal').style.display = 'flex';
                }

                function applyStatusChange() {
                    if (currentDropdown && selectedStatus) {
                        const row = currentDropdown.closest('tr');
                        row.setAttribute('data-status', selectedStatus);
                        currentDropdown.value = selectedStatus;

                        // Update class for dropdown color
                        currentDropdown.className = `status-dropdown ${selectedStatus}`;

                        // Send the status update request to the backend
                        fetch('farmer_update_status.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded'
                                },
                                body: `id=${encodeURIComponent(row.getAttribute('data-id'))}&status=${encodeURIComponent(selectedStatus)}`
                            })
                            .then(response => response.text())
                            .then(text => {
                                console.log('Server response:', text);
                                if (text.includes("Status updated successfully")) {
                                    closeModal();
                                    fetchOrders();
                                } else {
                                    alert("Server error: " + text);
                                }
                            })
                            .catch(error => {
                                console.error("Error updating status:", error);
                                alert("Error updating status. Please try again.");
                            });

                    }

                }

                function closeModal() {
                    document.getElementById('statusModal').style.display = 'none';
                    currentDropdown = null;
                    selectedStatus = null;
                }

                function filterOrders(status) {
                    const rows = document.querySelectorAll('#ordersTable tbody tr');
                    rows.forEach(row => {
                        if (status === 'all') {
                            row.style.display = '';
                        } else {
                            row.style.display = row.getAttribute('data-status') === status ? '' : 'none';
                        }
                    });

                    const buttons = document.querySelectorAll('.filter-buttons button');
                    buttons.forEach(button => button.classList.remove('active'));
                    document.getElementById(status).classList.add('active');
                }

                let activeFilter = 'all';

                function filterOrders(status) {
                    const rows = document.querySelectorAll('#ordersTable tbody tr');
                    const buttons = document.querySelectorAll('.filter-buttons button');

                    // If the same button is clicked again, reset to 'all'
                    if (activeFilter === status) {
                        status = 'all';
                        activeFilter = 'all';
                    } else {
                        activeFilter = status;
                    }

                    // Filter rows
                    rows.forEach(row => {
                        if (status === 'all') {
                            row.style.display = '';
                        } else {
                            row.style.display = row.getAttribute('data-status') === status ? '' : 'none';
                        }
                    });

                    // Toggle active styles on buttons
                    buttons.forEach(button => {
                        if (button.id === status) {
                            button.style.backgroundColor = '#597445';
                            button.style.color = 'white';
                        } else {
                            button.style.backgroundColor = '';
                            button.style.color = 'black';
                        }
                    });
                }
            </script>

        </div>
    </div>


</body>

</html>