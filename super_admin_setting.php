<?php

session_start();
require_once 'db.php';
// var_dump($_SESSION['admin_id']);
// var_dump($admin);

// Redirect if not logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login_admin.php");
    exit;
}

// Fetch admin info
$adminId = $_SESSION['admin_id'];
$stmt = $pdo->prepare("SELECT admin_username, admin_password FROM admin WHERE admin_id = ?");
$stmt->execute([$adminId]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<html>

<head>
    <link crossorigin="anonymous" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet" />

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>


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
            color: #000000;
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
                    <a class="nav-link" href="super_admin_dashboard.php">
                        <i class="fas fa-user"></i>
                        Dashboard
                    </a>
                    <a class="nav-link" href="super_admin_farmers.php">
                        <i class="fas fa-box"></i>
                        Farmers
                    </a>
                    <a class="nav-link" href="super_admin_consumers.php">
                        <i class="fas fa-bell"></i>
                        Consumers
                    </a>
                    <a class="nav-link " href="super_admin_products.php">
                        <i class="fas fa-bell"></i>
                        Products
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
        </div>
        <main class="container mx-auto mt-8 p-4">

            <section class="bg-green p-6 rounded-lg shadow-lg">
                <h2 class="text-3xl font-bold mb-4">Account Settings</h2>
                <a class="btn" href="#" id="editButton" data-bs-toggle="modal" data-bs-target="#editInfoModal">
                    <i class="fas fa-edit"></i>
                </a>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <input type="hidden" name="admin_id" value="<?php echo htmlspecialchars($admin[$adminId]) ?>">

                    <div>
                        <label class="block text-white-700" for="adminName">Admin Username</label> <br>
                        <input class="w-full text-black p-2 border border-gray-300 rounded-lg"
                            id="adminName" name="admin_username"
                            value="<?php echo htmlspecialchars($admin['admin_username']) ?>" readonly />
                    </div>
                </div>
                <div class="mt-4">
                    <label class="block text-white-700" for="adminPassword">Password</label><br>
                    <input class="w-full text-black p-2 border border-gray-300 rounded-lg"
                        id="adminPassword" name="admin_password" type="password" value="********" readonly /><br>
                </div>
            </section>

            <!-- Edit Admin Info Modal -->
            <div class="modal fade" id="editInfoModal" tabindex="-1" aria-labelledby="editInfoModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form id="settingsForm" method="POST" action="super_admin_update_settings.php" autocomplete="off"1>
                            <div class="modal-header">
                                <h5 class="modal-title" id="editInfoModalLabel">Edit Admin Information</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <input type="hidden" name="update_admin_id" value="<?php echo htmlspecialchars($adminId); ?>">
                                    <div>
                                        <label class="block text-white-700" for="adminName">Admin Username</label> <br>
                                        <input class="w-full text-black p-2 border border-gray-300 rounded-lg"
                                            id="adminName" name="update_admin_username" required type="text"
                                            value="<?php echo htmlspecialchars($admin['admin_username']) ?>" />
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <label class="block text-white-700" for="adminPassword">New Password</label><br>
                                    <div class="relative">
                                        <input autocomplete="new-password" class="w-full text-black p-2 border border-gray-300 rounded-lg pr-10"
                                            id="newAdminPassword" name="update_admin_password" type="password"
                                            placeholder="Enter new password (optional)" />

                                        <span class="absolute right-3 top-1/2 transform -translate-y-1/2 cursor-pointer" onclick="togglePassword('newAdminPassword', 'eyeIcon')">
                                            <i id="eyeIcon" class="fas fa-eye text-gray-600"></i>
                                        </span><br>
                                        <small>Leave blank to keep current</small>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <label class="block text-white-700" for="adminPassword">Confirm New Password</label><br>
                                    <div class="relative">
                                        <input class="w-full text-black p-2 border border-gray-300 rounded-lg pr-10"
                                            id="confirmAdminPassword" name="update_re-enteradmin_password" type="password" placeholder="Re-enter new password" />
                                        <span class="absolute right-3 top-1/2 transform -translate-y-1/2 cursor-pointer" onclick="togglePassword('confirmAdminPassword', 'eyeIcon1')">
                                            <i id="eyeIcon1" class="fas fa-eye text-gray-600"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-success" type="submit">Save Settings</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>

        <script>
            function togglePassword(inputId, iconId) {
                const passwordInput = document.getElementById(inputId);
                const eyeIcon = document.getElementById(iconId);

                if (passwordInput.type === "password") {
                    passwordInput.type = "text";
                    eyeIcon.classList.remove("fa-eye");
                    eyeIcon.classList.add("fa-eye-slash");
                } else {
                    passwordInput.type = "password";
                    eyeIcon.classList.remove("fa-eye-slash");
                    eyeIcon.classList.add("fa-eye");
                }
            }
        </script>

    </div>

</body>

</html>