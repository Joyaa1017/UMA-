<?php
// views/Farmerhome.php
session_start();
require_once 'db.php';

// Fetch Farmers
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    die('User not logged in.');
}

$farmerstmt = $pdo->prepare("SELECT * FROM farmers WHERE user_id = ?");
$farmerstmt->execute([$user_id]);
$farmer = $farmerstmt->fetch();

if (!$farmer) {
    die("Farmer not found.");
}
?>

<html>

<head>
    <title>
        Dashboard
    </title>
    <link crossorigin="anonymous" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" rel="stylesheet" />
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
                <a href="farmer_account.php">
                    <i class="fas fa-user">
                    </i>
                    Account
                </a>
                <a href="farmer_feed.php" style="background-color: #597445;">
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
<section>
  <div class="container mt-5">
    <div class="row">
      <!-- Feedback Form -->
      <div class="col-md-6 p-4 rounded text-white" style="background-color: #1c1c1c;">
        <h1 class="mb-4 text-center">Feedback</h1>
        <p class="mb-3 text-center">Do you have anything to say?</p>

        <form action="farmer_feed_transaction.php" method="POST" id="feedbackForm">
          <div class="mb-4 text-center">
            <label class="mb-2 d-block">Rating</label>
            <div class="star-rating d-inline-block">
              <input type="radio" id="star1" name="rating" value="5" />
              <label for="star1" title="5 stars"><i class="fas fa-star"></i></label>
              <input type="radio" id="star2" name="rating" value="4" />
              <label for="star2" title="4 stars"><i class="fas fa-star"></i></label>
              <input type="radio" id="star3" name="rating" value="3" />
              <label for="star3" title="3 stars"><i class="fas fa-star"></i></label>
              <input type="radio" id="star4" name="rating" value="2" />
              <label for="star4" title="2 stars"><i class="fas fa-star"></i></label>
              <input type="radio" id="star5" name="rating" value="1" />
              <label for="star5" title="1 star"><i class="fas fa-star"></i></label>
            </div>
          </div>

          <div class="form-floating mb-4">
            <textarea class="form-control" id="feedbackMessage" placeholder="Leave a comment here" name="feedback" style="height: 150px;"></textarea>
            <label for="feedbackMessage">Your Feedback</label>
          </div>

          <input type="hidden" name="user_id" value="<?= $farmer['user_id'] ?>">

          <div class="text-center">
            <button class="btn btn-light px-5" type="button" id="confirmSendBtn">Send</button>
          </div>
        </form>
      </div>

      <!-- Right-side Image -->
     <!-- Image (Right Side) -->
    <div class="col-md-6 p-0 d-none d-md-block">
      <div class="h-100 w-100">
        <img src="img/feed2.png" alt="Feedback illustration"
          class="w-100 h-100" style="object-fit: cover;" />
      </div>
    </div>

  </div>
</div>

</section>


<!-- Confirmation Modal for Send Feedback -->
<div class="confirmation-overlay" style="display: none;">
    <div class="confirmation-modal">
        <h2>Confirm Feedback Submission</h2>
        <p>Are you sure you want to submit this feedback?</p>
        <button class="confirm-btn">Yes</button>
        <button class="cancel-btn">Cancel</button>
    </div>
</div>


        <style>
            .star-rating {
                direction: rtl;
                display: flex;
                justify-content: flex-start;
            }

            .star-rating input {
                display: none;
            }

            .star-rating label {
                font-size: 30px;
                color: #ccc;
                cursor: pointer;
            }

            .star-rating input:checked~label {
                color: #f39c12;
            }

            .star-rating label:hover,
            .star-rating label:hover~label {
                color: #f39c12;
            }
            .confirmation-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.7);
        z-index: 1000;
    }

    .confirmation-modal {
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

    .confirmation-modal h2 {
        margin-top: 0;
    }

    .confirmation-modal button {
        margin: 10px;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .confirm-btn {
        background-color: #597445;
        color: white;
    }

    .cancel-btn {
        background-color: #ccc;
    }
        </style>
        <script>
    document.addEventListener('DOMContentLoaded', function () {
        const sendBtn = document.getElementById('confirmSendBtn');
        const confirmationOverlay = document.querySelector('.confirmation-overlay');
        const confirmBtn = document.querySelector('.confirm-btn');
        const cancelBtn = document.querySelector('.cancel-btn');
        const feedbackForm = document.getElementById('feedbackForm');

        sendBtn.addEventListener('click', function () {
            // Display the confirmation modal
            confirmationOverlay.style.display = 'block';
        });

        confirmBtn.addEventListener('click', function () {
            // Submit the form after confirmation
            feedbackForm.submit();
        });

        cancelBtn.addEventListener('click', function () {
            // Close the confirmation modal without submitting the form
            confirmationOverlay.style.display = 'none';
        });

        // Close the confirmation modal if user clicks outside of it
        confirmationOverlay.addEventListener('click', function (e) {
            if (e.target === confirmationOverlay) {
                confirmationOverlay.style.display = 'none';
            }
        });
    });
</script>
</body>

</html>