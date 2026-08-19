<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Sign Up</title>
  <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
  <style>
    body {
      margin: 0;
      font-family: 'Poppins';
      background: linear-gradient(60deg, #0f0f0f, #658147);
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .login-container {
      background-color: #2A2A2A;
      padding: 40px 30px;
      border-radius: 40px;
      width: 320px;
      text-align: center;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.6);
    }

    .logo img {
      width: 80px;
      height: 80px;
      border-radius: 10px;
    }

    .login-title {
      color: #658147;
      font-size: 26px;
      font-weight: bold;
      margin-bottom: 25px;
    }

    input[type="email"],
    input[type="password"] {
      width: 100%;
      padding: 13.5px;
      margin: 10px 0;
      border: none;
      border-radius: 15px;
      font-size: 14px;
      box-sizing: border-box;
    }

    ::placeholder {
      color: #aaaaaa;
      text-align: left;
      font-size: 14px;  
    }

    .terms-container {
      font-size: 12px;
      color: #ccc;
      text-align: left;
      margin: 10px 0;
      display: flex;
      align-items: center;
    }

    .terms-container input {
      margin-right: 8px;
    }

    .terms-container a {
      color: #5fa87b;
      text-decoration: none;
    }

    .login-button {
      width: 100%;
      background-color: #5f783d;
      color: white;
      padding: 12px;
      border: none;
      border-radius: 10px;
      margin-top: 15px;
      font-weight: bold;
      font-size: 14px;
      cursor: pointer;
    }

    .signup {
      margin-top: 15px;
      font-size: 13px;
      color: #ccc;
      text-align: left;
    }

    .signup a {
      color: #5fa87b;
      text-decoration: none;
    }

    .error-message {
      color: red;
      text-align: left;
      font-size: 12px;
      margin: 5px 0 5px;
    }
  </style>
</head>
<body>

<?php session_start(); ?>

<div class="login-container">
  <div class="logo">
    <img src="img/1725975655620-removebg-preview.png" alt="Logo" />
  </div>
  <div class="login-title">Sign Up</div>
  
  <form action="register_handler.php" method="POST">

    <?php if (isset($_SESSION['errors'])): ?>
      <div class="error-message">
        <?php foreach ($_SESSION['errors'] as $error): ?>
          <p><?= htmlspecialchars($error) ?></p>
        <?php endforeach; unset($_SESSION['errors']); ?>
      </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
      <div class="error-message">
        <p><?= htmlspecialchars($_SESSION['error']) ?></p>
        <?php unset($_SESSION['error']); ?>
      </div>
    <?php endif; ?>

    <input type="email" placeholder="Email" name="username" required>
    <input type="password" placeholder="Password" name="password" required id="password">
    <input type="password" placeholder="Confirm Password" name="conpassword" required id="conpassword">

    <!-- <label class="terms-container">
      <div>
        <input type="checkbox" required>
        I agree to the <a href="#">Terms and Conditions</a>
      </div>
    </label> -->

    <button class="login-button" type="submit">Sign Up</button>
  </form>

  <div class="signup">
    Already have an account? <a href="login.php">Login</a>
  </div>
</div>

<script>
  const form = document.querySelector("form");
  const passwordInput = document.getElementById("password");

  form.addEventListener("submit", function (e) {
    if (passwordInput.value.length < 6) {
      e.preventDefault();
      passwordInput.setCustomValidity("");
      alert("Password must have at least 6 characters.");
      passwordInput.focus();
    }
  });
</script>

</body>
</html>
