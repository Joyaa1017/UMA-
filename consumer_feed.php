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

?>

<html>

<head>
    <link crossorigin="anonymous" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet" />
    <title>Feedback</title>
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
                    <a class="nav-link" href="consumer_notif.php">
                        <i class="fas fa-bell"></i>
                        Notifications
                    </a>
                    <a class="nav-link active" href="consumer_feed.php">
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
                <div class="icon ">
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
  <div class="container mt-5">
    <div class="row">
      <!-- Feedback Form -->
      <div class="col-md-6 p-4 rounded text-white" style="background-color: #1c1c1c;">
        <h1 class="mb-4 text-center">Feedback</h1>
        <p class="mb-3 text-center">Do you have anything to say?</p>

        <form action="consumer_feed_transaction.php" method="POST" id="feedbackForm">
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

          <input type="hidden" name="user_id" value="<?= $consumers['user_id'] ?>">

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