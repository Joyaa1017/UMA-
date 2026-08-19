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
// $consumerstmt = $pdo->prepare("SELECT * FROM consumers WHERE user_id = ?");
$consumerstmt->execute([$user_id]);
$consumers = $consumerstmt->fetch();

if (!$consumers) {
    die("Consumer not found.");
}

// Check for farmer_id in URL
$farmer_id = $_GET['farmer_id'] ?? null;
if (!$farmer_id) {
    die('Farmer ID not provided.');
}

// Fetch farmer details
$farmerstmt = $pdo->prepare("
    SELECT farmers.*, users.username 
    FROM farmers
    JOIN users ON farmers.user_id = users.id
    WHERE farmers.user_id = ?
");
$farmerstmt->execute([$farmer_id]);
$farmer = $farmerstmt->fetch();

if (!$farmer) {
    die("Farmer not found.");
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
        padding: 40px;
        min-height: 100vh;
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


    

    .btn-success {
        background-color: #597445;
        border-radius: 1px;
    }

    .btn-success:hover {
        background-color: #6b8a55;
        border-radius: 1px;
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
</style>

</head>

<body>


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
            <img alt="Logo" height="300" width="100" src="img/uma.png" />
        </a>
        <div class="ms-3">
            <a class="btn" href="consumer_home.php" title="Back">
                <i class="fas fa-arrow-left"></i>
            </a>
            <a class="btn" href="consumer_cart.php" title="Cart">
                <i class="fas fa-shopping-cart"></i>
            </a>
            <a class="btn" href="consumer_account.php" title="Account">
                <i class="fas fa-user"></i>
            </a>
        </div>
    </nav>



        <!-- Report User Modal -->
        <div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="reportModalLabel">Report Farmer: <span id="reported_product_name"></span></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="report-form" method="POST" action="report_controller.php">
                            <input type="hidden" name="report_farmer_only" value="1">
                            <input type="hidden" name="farmer_id" value="<?php echo htmlspecialchars($farmer['user_id']); ?>">
                            <input type="hidden" name="reporter_user_id" id="reporter_user_id" value="<?php echo htmlspecialchars($consumers['user_id']); ?>">

                            <div class="mb-3">
                                <label for="report-reason" class="form-label">Reason for Reporting</label>
                                <select class="form-select" name="reason" id="report-reason" style="color: 597445;" required>
                                    <option value="Profanity">Profanity</option>
                                    <option value="Abuse">Abuse</option>
                                    <option value="Harassment">Harassment</option>
                                    <option value="Spam">Spam</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="mb-3" id="custom-reason-container">
                                <label for="custom-reason" class="form-label">Custom Reason (Optional)</label>
                                <textarea class="form-control" name="custom_reason" id="custom-reason" rows="3"></textarea>
                            </div>
                            <button type="submit" class="btn btn-danger">Report</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>



<style>
  #personalInfoForm .profile-container {
    display: flex;
    align-items: flex-start; /* Align top edges */
    gap: 30px; /* Space between image and form */
  }

  #personalInfoForm img.profile-pic {
    width: 140px;
    height: 140px;
    object-fit: cover;
    border-radius: 10px;
    border: 2px solid #597445;
  }

  #personalInfoForm .fields {
    flex: 1; /* Take remaining space */
  }
</style>

<h2>
    Farmer <span>Information</span>
    <!-- Report button as icon -->
    <button type="button" class="btn btn-danger ms-2"
        id="reportProductBtn"
        data-bs-toggle="modal"
        data-bs-target="#reportModal"
        title="Report Product">
        <i class="fas fa-flag"></i>
    </button>
</h2>
</br>

<form id="personalInfoForm">
  <div class="profile-container">
    <img
      alt="Profile picture of <?php echo htmlspecialchars($farmer['firstname'] . ' ' . $farmer['lastname']); ?>"
      class="profile-pic"
      src="uploads/<?php echo htmlspecialchars($farmer['farmer_image']); ?>"
    />
    <br>

    <div class="fields">
      <div class="row">
        <div class="col-md-6">
          <div class="mb-3">
            <label class="form-label text-white">Farmer's Name:</label>
            <input class="form-control" type="text" name="firstname" value="<?php echo htmlspecialchars($farmer['firstname'] . ' ' . $farmer['lastname']);  ?>">
          </div>
          <div class="mb-3">
            <label class="form-label text-white">Email Address:</label>
            <input class="form-control" type="email" name="email" value="<?php echo htmlspecialchars($farmer['username']); ?>">
          </div>
          <div class="mb-3">
            <label class="form-label text-white">Phone Number:</label>
            <input class="form-control" type="text" name="phone_number" value="<?php echo htmlspecialchars($farmer['phone_number']); ?>">
          </div>
        </div>
        <div class="col-md-6">
          <div class="mb-3">
            <label class="form-label text-white">Farm Name:</label>
            <input class="form-control" type="text" name="farm_name" value="<?php echo htmlspecialchars($farmer['farmname'] ?? ''); ?>">
          </div>
          <div class="mb-3">
            <label class="form-label text-white">Barangay:</label>
            <input class="form-control" type="text" name="barangay" value="<?php echo htmlspecialchars( $farmer['address'] . ' ' . $farmer['purok']. ' ' . $farmer['street']);?>">
          </div>
        </div>
      </div>
    </div>
  </div>
</form>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

</body>

</html>