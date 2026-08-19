<?php
// views/Farmerhome.php
session_start();
require_once 'db.php';

// Fetch Farmers
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
  die('User not logged in.');
}

$farmerStmt = $pdo->prepare("SELECT * FROM view_farmer_account WHERE user_id = ?");
$farmerStmt->execute([$user_id]);
$farmer = $farmerStmt->fetch();

// Fetch products
$productStmt = $pdo->query("SELECT * FROM products");
$products = $productStmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&amp;display=swap" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


  <style>
    * {
      font-family: 'Poppins';
    }

    body {
      margin: 0;
      display: flex;
      height: 100vh;
      background: linear-gradient(45deg, #658147, #1C1D21);
      color: #fff;
    }

    .sidebar {
      width: 250px;
      background-color: #1c1c1c;
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

    .content {
      width: 75%;
      padding: 40px;
      box-sizing: border-box;
      background: linear-gradient(45deg, #658147, #1C1D21);
    }

    .content h1 {
      font-size: 24px;
      margin-bottom: 20px;
    }

    .content h1 span {
      font-weight: 700;
    }

    .content h1 a {
      font-size: 14px;
      color: #b0b0b0;
      text-decoration: none;
      margin-left: 10px;
    }

    .form-group {
      display: flex;
      justify-content: space-between;
      margin-bottom: 20px;
    }

    .form-group div {
      width: 48%;
    }

    .form-group label {
      display: block;
      margin-bottom: 5px;
      font-size: 14px;
    }

    .form-group input {
      width: 100%;
      padding: 10px;
      border: none;
      border-bottom: 1px solid white;
      border-radius: 0;
      background-color: transparent;
      color: #fff;
      font-size: 16px;
    }

    .form-group input[type="password"] {
      letter-spacing: 2px;
    }

    .form-group select {
      width: 100%;
      padding: 10px;
      border: none;
      border-radius: 5px;
      background-color: #2c2c2c;
      color: #fff;
      font-size: 14px;
    }

    .form-group select option {
      background-color: #2c2c2c;
    }

    .update-btn {
      display: block;
      width: 100%;
      padding: 10px;
      background-color: #000;
      color: #fff;
      text-align: center;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
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
  </style>
</head>

<body>
  <!-- <div class="container"> -->
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
      <a href="farmer_order.php">
        <i class="fas fa-shopping-cart">
        </i>
        Orders
      </a>
      <a href="farmer_account.php" style="background-color: #597445;">
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
  <div class="content">
    <h1>
      Personal
      <span>
        Information
      </span>
      <a class="btn" href="#" id="editButton" data-bs-toggle="modal" data-bs-target="#editInfoModal">
        <i class="fas fa-edit"></i>
      </a>

    </h1>
    <!-- Edit Info Modal -->
    <div class="modal fade" id="editInfoModal" tabindex="-1" aria-labelledby="editInfoModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <!-- <form class="modal-content" id="editInfoForm"> -->
        <div class="modal-header">
          <h5 class="modal-title" id="editInfoModalLabel">Edit Personal Information</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <form class="modal-content" method="POST" action="update_farmer_info.php">
          <div class="modal-body">
            <div class="row">
              <input type="hidden" name="user_id" value="<?php echo $farmer['user_id']; ?>">

              <div class="col-md-6">
                <div class="mb-3">
                  <label class="form-label">Email Address</label>
                  <input class="form-control" type="email" name="email" value="<?php echo htmlspecialchars($farmer['username']); ?>">
                </div>

                <div class="mb-3">
                  <label class="form-label">Password</label>
                  <input class="form-control" type="password" name="password" value="">
                  <small class="text-muted">Leave blank to keep current password</small>
                </div>

                <div class="mb-3">
                  <label class="form-label">Barangay</label>
                  <input class="form-control" type="text" name="barangay" value="<?php echo htmlspecialchars($farmer['address']); ?>">
                </div>

                <div class="mb-3">
                  <label class="form-label">Purok</label>
                  <input class="form-control" type="text" name="purok" value="<?php echo htmlspecialchars($farmer['purok']); ?>">
                </div>
              </div>

              <div class="col-md-6">

                <div class="mb-3">
                  <label class="form-label">First Name</label>
                  <input class="form-control" type="text" name="firstname" value="<?php echo htmlspecialchars($farmer['firstname']); ?>">
                </div>
                <div class="mb-3">
                  <label class="form-label">Last Name</label>
                  <input class="form-control" type="text" name="lastname" value="<?php echo htmlspecialchars($farmer['lastname']); ?>">
                </div>

                <div class="mb-3">
                  <label class="form-label">Farm Name</label>
                  <input class="form-control" type="text" name="farmname" value="<?php echo htmlspecialchars($farmer['farmname']); ?>">
                </div>
                <div class="mb-3">
                  <label class="form-label">Phone Number</label>
                  <input class="form-control" type="text" name="phone_number" value="<?php echo htmlspecialchars($farmer['phone_number']); ?>">
                </div>
                <div class="mb-3">
                  <label class="form-label">Street</label>
                  <input class="form-control" type="text" name="street" value="<?php echo htmlspecialchars($farmer['street']); ?>">
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
    <div class="form-group">
      <div>
        <label>
          Email Address
        </label>
        <input type="text" value="<?php echo htmlspecialchars($farmer['username']); ?>" readonly>
      </div>
      <div>
        <label>
          Barangay
        </label>
        <input type="text" value="<?php echo htmlspecialchars($farmer['address']); ?>" readonly>
      </div>
    </div>

    <div class="form-group">
      <div>
        <label>Full Name</label>
        <input type="text" value="<?php echo htmlspecialchars($farmer['firstname'] . ' ' . $farmer['lastname']); ?>" readonly>
      </div>
      <div>
        <label>
          Street
        </label>
        <input type="text" value="<?php echo htmlspecialchars($farmer['street']); ?>" readonly>
      </div>

    </div>
    <div class="form-group">
      <div>
        <label>
          Farmer's Name
        </label>
        <input type="text" value="<?php echo htmlspecialchars($farmer['farmname']); ?>" readonly>
      </div>
      <div>
        <label>
          Purok
        </label>
        <input type="text" value="<?php echo htmlspecialchars($farmer['purok']); ?>" readonly>

      </div>
    </div>

    <div class="form-group">
      <div>
        <label>
          Phone Number
        </label>
        <input type="text" value="<?php echo htmlspecialchars($farmer['phone_number']); ?>" readonly>

      </div>
    </div>

  </div>





</body>

</html>