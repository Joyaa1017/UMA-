<html>
 <head>
  <title>
   Which one are you?
  </title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet"/>
  <style>
   body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(180deg, #1b1b1b 0%, #3a5a40 100%);
            color: white;
        }
        .container {
            text-align: center;
            padding: 20px;
        }
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px;
        }
        .header i {
            font-size: 24px;
            margin-right: 10px;
        }
        .header h1 {
            font-size: 36px;
            margin: 0;
        }
        .steps {
            display: flex;
            align-items: center;
            margin: 20px 0;
        }
        .step {
            display: flex;
            align-items: center;
            margin: 0 10px;
        }
        .step-circle {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: #6a994e;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 16px;
            margin-right: 10px;
        }
        .step-line {
            width: 50px;
            height: 2px;
            background-color: white;
        }
        .step-text {
            font-size: 16px;
        }
        .content {
            display: flex;
            justify-content: center;
            align-items: center;
            background-image: url('img/choices.png');
            background-size: cover;
            background-position: center;
            border-radius: 15px;
            padding: 20px;
            margin: 20px 0;
        }
        .content div {
            width: 50%;
            padding: 20px;
            border-radius: 10px;
            margin: 0 10px;
        }
        .content h2 {
            font-size: 24px;
            margin-bottom: 10px;
        }
        .content p {
            font-size: 16px;
            margin-bottom: 20px;
        }
        .content button {
            background-color: #6a994e;
            color: white;
            border: none;
            padding: 15px 65px;
            border-radius: 15px;
            font-size: 16px;
            cursor: pointer;
        }
        a{
            text-decoration: none;
            color: white;
            font-weight: 500;
            font-family: 'Poppins';
        }
        .footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            font-family: 'Poppins', sans-serif;
        }
        .footer .logo {
            display: flex;
            align-items: center;
        }
        .footer .logo img {
            width: 40px;
            height: 40px;
            margin-right: 10px;
        }
        .footer .nav {
            display: flex;
            gap: 20px;
        }
        .footer .nav a {
            color: white;
            text-decoration: none;
            font-size: 16px;
        }
        .footer .social {
            display: flex;
            gap: 10px;
        }
        .footer .social i {
            font-size: 20px;
            color: white;
        }
  </style>
 </head>
 <body>
  <div class="container">
   <div class="header">
    <div style="display: flex; align-items: center;">
     <i class="fas fa-arrow-left">
     </i>
     <h1>
      Which one are you?
     </h1>
    </div>
    <div class="steps">
     <div class="step">
      <div class="step-circle">
       1
      </div>
      <div class="step-text">
       Step 1
      </div>
     </div>
     <div class="step-line">
     </div>
     <div class="step">
      <div class="step-circle" style="background-color: transparent; border: 2px solid white;">
       2
      </div>
      <div class="step-text">
       Step 2
      </div>
     </div>
     <div class="step-line">
     </div>
     <div class="step">
      <div class="step-circle" style="background-color: transparent; border: 2px solid white;">
       3
      </div>
      <div class="step-text">
       Step 3
      </div>
     </div>
    </div>
   </div>
   <div class="content">
    <div>
     <h2>
      Farmer
     </h2>
     <p>
      Step into a world designed just for you. Discover the latest in sustainable farming practices, market prices, and suppliers for all your agricultural needs.
     </p>
     <button>
      <a href="signup_farmer.php?role=farmer">Farmer</a>
     </button>
    </div>
    <div>
     <h2>
      Consumer
     </h2>
     <p>
      Dive into a fresh and vibrant marketplace where you can find farm-to-table products, mouth-watering recipes, and tips on how to choose the best produce.
     </p>
     <button>
        <a href="signup_consumer.php?role=consumer">Consumer</a>
     </button>
    </div>
   </div>
   <div class="footer">
    <div class="logo">
     <img alt="Logo" height="40" src="img/logo.png" width="40"/>
    </div>
    <div class="nav">
     <a href="#">
      Home
     </a>
     <a href="#">
      About
     </a>
     <a href="#">
      Donate
     </a>
     <a href="#">
      Blog
     </a>
     <a href="#">
      Contact
     </a>
    </div>
    <div class="social">
     <i class="fab fa-facebook-f">
     </i>
     <i class="fab fa-twitter">
     </i>
     <i class="fab fa-instagram">
     </i>
     <i class="fab fa-linkedin-in">
     </i>
    </div>
   </div>
  </div>
 </body>
</html>