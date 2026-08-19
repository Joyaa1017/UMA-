<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login</title>
  <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
      width: 400px;
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
      padding: 12px;
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

    .remember-password {
      font-size: 13px;
      text-align: left;
      margin: 5px 0 15px;
    }

    .remember-password a {
      color: #5fa87b;
      text-decoration: none;
    }

    .login-button {
      width: 100%;
      background-color: #5f783d;
      color: white;
      padding: 12px;
      border: none;
      border-radius: 6px;
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
      margin: 5px 0 0;
    }
  </style>
</head>
<body>

  <div class="login-container">
    <div class="logo">
      <img src="img/1725975655620-removebg-preview.png" alt="Logo" />
    </div>
    <div class="login-title">Forgot Password</div>
    <form action="login_handler.php" method="POST">

      <?php
      session_start();
      if (isset($_SESSION['errors'])) {
          echo "<div class='error-message'>";
          foreach ($_SESSION['errors'] as $error) {
              echo "<p>{$error}</p>";
          }
          echo "</div>";
          unset($_SESSION['errors']);
      }
      ?>

      <input type="email" placeholder="Email" required name="username">
      <input type="password" placeholder="Password" required id="password" name="password">
      <input type="password" placeholder="Re-enter Password" required id="password" name="password">

      <button class="login-button" type="submit">Login</button>
    </form>
          <div class="remember-password">
        <a href="login_admin.php">Remember Password? Back to Login</a>
      </div>
  </div>

  <script>
    const form = document.querySelector("form");
    const passwordInput = document.getElementById("password");

    form.addEventListener("submit", function (e) {
      if (passwordInput.value.length < 6) {
        e.preventDefault();
        passwordInput.setCustomValidity("");
        alert("Password must be at least 6 characters long.");
        passwordInput.focus();
      }
    });
  </script>

</body>
</html>
