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

$consumerstmt = $pdo->prepare("SELECT * FROM consumer_profile_view WHERE user_id = ?");
$consumerstmt->execute([$user_id]);
$consumers = $consumerstmt->fetch();

if (!$consumers) {
    die("Consumer not found.");
}
// Fetch products
$productStmt = $pdo->query("SELECT * FROM products");
$products = $productStmt->fetchAll();

?>

<html>

<head>
    <link crossorigin="anonymous" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="styles.css">
    <title>Dashboard</title>
    <style>
        body {

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

        .navbar {
            background: transparent;
            border-bottom: none;
            margin-bottom: 20px;
        }

        .navbar .navbar-brand img {
            height: 50px;
        }

        .navbar .form-control {
            border-radius: 20px;
        }

        .navbar .btn,
        .btn {
            background-color: #4caf4f00;
            border: none;
            border-radius: 50%;
            color: white;
            padding: 10px;
        }

        /* Custom Modal Styling */
        .modal-content {
            background-color: #1c1c1c;
            color: white;
            border: none;
        }

        .modal-header {
            border-bottom: 1px solid #597445;
            background-color: #292929;
        }

        .modal-footer {
            border-top: 1px solid #597445;
            background-color: #292929;
        }

        .modal-title {
            color: #ffffff;
        }

        .modal-body label {
            color: #cccccc;

        }

        .modal-content .form-control {
            background-color: transparent;
            color: white;
            border: 1px solid #444;
        }

        .modal-content .form-control:focus {
            border-color: #597445;
            box-shadow: none;
        }

        .btn-success {
            background-color: #597445;
            border-radius: 1px;
        }

        .btn-success:hover {
            background-color: #6b8a55;
            border-radius: 1px;
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
                    <a class="nav-link active" href="consumer_account.php">
                        <i class="fas fa-user"></i>
                        Account Info
                    </a>
                    <a class="nav-link" href="consumer_order_history.php">
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
        <nav class="navbar">
            <a class="navbar-brand" href="#">
                <img alt="Logo" height="300;" width="100;" src="img/uma.png" />
            </a>
            <div class="ms-3">
                <!-- <a class="btn" href="#">
                    <i class="fas fa-search"></i>
                </a> -->
                <a class="btn" href="consumer_home.php">
                    <i class="fa fa-shopping-bag"></i>
                </a>
                <a class="btn" href="consumer_cart.php">
                    <i class="fas fa-shopping-cart"></i>
                </a>
                <a class="btn green" href="#">
                    <i class="fas fa-user"></i>
                </a>
            </div>
        </nav>
        <h2>
            Personal
            <span>Information</span>
            <a class="btn" href="#" id="editButton" data-bs-toggle="modal" data-bs-target="#editInfoModal">
                <i class="fas fa-edit"></i>
            </a>

        </h2>
        <!-- Edit Info Modal -->
        <div class="modal fade" id="editInfoModal" tabindex="-1" aria-labelledby="editInfoModalLabel" aria-hidden="true">
            <div class="modal-dialog">


                <form class="modal-content" id="editInfoForm" method="POST" action="update_consumer_info.php">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editInfoModalLabel">Edit Personal Information</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($consumers['user_id']); ?>">

                                <div class="mb-3">
                                    <label class="form-label">Email Address</label>
                                    <input class="form-control" type="email" name="email" value="<?php echo htmlspecialchars($consumers['username']); ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Password</label>
                                    <input class="form-control" type="password" name="password" value="<?php echo htmlspecialchars($consumers['password']); ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">First Name</label>
                                    <input class="form-control" type="text" name="firstname" value="<?php echo htmlspecialchars($consumers['firstname']); ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Last Name</label>
                                    <input class="form-control" type="text" name="lastname" value="<?php echo htmlspecialchars($consumers['lastname']); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Phone Number</label>
                                    <input class="form-control" type="text" name="phone_number" value="<?php echo htmlspecialchars($consumers['phone_number']); ?>">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Barangay</label>
                                    <input class="form-control" type="text" name="barangay" value="<?php echo htmlspecialchars($consumers['address']); ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Purok</label>
                                    <input class="form-control" type="text" name="purok" value="<?php echo htmlspecialchars($consumers['purok']); ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Street</label>
                                    <input class="form-control" type="text" name="street" value="<?php echo htmlspecialchars($consumers['street']); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Save Changes</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>

        <form id="personalInfoForm">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label text-white">Email Address:</label>
                        <input class="form-control" type="email" name="email" value="<?php echo htmlspecialchars($consumers['username']); ?>">

                    </div>
                    <div class="mb-3">
                        <label class="form-label text-white">Password:</label>
                        <input class="form-control" type="password" name="password" value="<?php echo htmlspecialchars($consumers['password']); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-white">First Name:</label>
                        <input class="form-control" type="text" name="firstname" value="<?php echo htmlspecialchars($consumers['firstname']); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-white">Last Name:</label>
                        <input class="form-control" type="text" name="lastname" value="<?php echo htmlspecialchars($consumers['lastname']); ?>">

                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label text-white">Phone Number:</label>
                        <input class="form-control" type="text" name="phone_number" value="<?php echo htmlspecialchars($consumers['phone_number']); ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-white">Barangay:</label>
                        <input class="form-control" type="text" name="barangay" value="<?php echo htmlspecialchars($consumers['address']); ?>">

                    </div>

                    <div class="mb-3">
                        <label class="form-label text-white">Purok:</label>
                        <input class="form-control" type="text" name="purok" value="<?php echo htmlspecialchars($consumers['purok']); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-white">Street:</label>
                        <input class="form-control" type="text" name="street" value="<?php echo htmlspecialchars($consumers['street']); ?>">

                    </div>
                </div>
            </div>
        </form>
        <script>
            document.getElementById('editInfoForm').addEventListener('submit', function(e) {
                // Optional: Show confirmation alert
                alert('Your personal information has been updated successfully.');
                // You can also close the modal here if needed
                // $('#editInfoModal').modal('hide');
            });
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const editButton = document.getElementById('editButton');
                const form = document.getElementById('personalInfoForm');
                const inputs = form.querySelectorAll('input, select');
                const updateButton = form.querySelector('.update-btn');
                let isEditing = false;
                let originalValues = {};

                // Store original values
                inputs.forEach(input => {
                    originalValues[input.placeholder || input.name] = input.value;
                });

                editButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    isEditing = !isEditing;

                    if (isEditing) {
                        // Enable editing
                        inputs.forEach(input => {
                            input.disabled = false;
                        });
                        updateButton.disabled = false;
                        editButton.innerHTML = '<i class="fas fa-times"></i>';
                        editButton.style.color = 'gray';
                    } else {
                        // Disable editing and revert changes
                        inputs.forEach(input => {
                            input.disabled = true;
                            input.value = originalValues[input.placeholder || input.name];
                        });
                        updateButton.disabled = true;
                        editButton.innerHTML = '<i class="fas fa-edit"></i>';
                        editButton.style.color = 'white';
                    }
                });

                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    // Here you would typically send the form data to the server
                    console.log('Form submitted');

                    // Update original values with new values
                    inputs.forEach(input => {
                        originalValues[input.placeholder || input.name] = input.value;
                    });

                    // After successful submission, disable editing
                    isEditing = false;
                    inputs.forEach(input => {
                        input.disabled = true;
                    });
                    updateButton.disabled = true;
                    editButton.innerHTML = '<i class="fas fa-edit"></i>';
                    editButton.style.color = 'white';
                });
            });
        </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

</body>

</html>