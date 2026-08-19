<!DOCTYPE html>
<html>

<head>
    <title>UMA</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <link crossorigin="anonymous" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        * {
            font-family: 'Poppins';
        }

        .bg-green,
        .btn-green {
            background-color: #597445;
            color: #ffffff;
        }

        .contact-card {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 20px;
            width: 400px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: left;
        }

        .contact-card h2 {
            color: #4a5e4d;
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .contact-card p {
            color: #4a5e4d;
            font-size: 14px;
            margin: 5px 0;
        }

        .contact-card i {
            color: #4a5e4d;
            margin-right: 10px;
        }

        .btn-green:hover {
            background-color: #283f17;
        }

        .green {
            color: #597445;
        }

        body {
            background-color: #1a1a1a;
            color: #ffffff;
        }

        .navbar {
            background-color: #1a1a1a;
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
            color: #597445;
        }

        .navbar-nav .nav-link {
            color: #ffffff;
            margin-right: 1rem;
        }

        .navbar-nav .nav-link:hover {
            color: #597445;
        }

        .custom-img {
            height: 500px;
            width: 90%;
            margin-left: 100px;

        }

        .grid-img {
            width: 100%;
            height: 230px;
            /* Adjust as needed */
            object-fit: cover;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .grid-img:hover {
            transform: scale(1.02);
        }

        .hero-section {
            padding: 4rem 0;
            text-align: left;
        }

        .hero-section h1 {
            color: #597445;
            font-size: 2.5rem;
            font-weight: bold;
        }

        .hero-section p {
            color: #b3b3b3;
            margin: 1rem 0 2rem;
        }

        .hero-section .btn-outline-light {
            color: #597445;
            border-color: #597445;
        }

        .hero-section .btn-outline-light:hover {
            background-color: #597445;
            color: #ffffff;
        }

        /* Testimonials*/
        .testimonial-section {
            text-align: center;
            padding: 50px 0;
        }

        .testimonial-section h2 {
            color: #597445;
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .testimonial-section p {
            color: #a0a0a0;
            font-size: 1rem;
            margin-bottom: 40px;
        }

        .testimonial-card {
            background-color: #ffffff;
            color: #000000;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            padding: 20px;
            margin: 0 10px;
        }

        .testimonial-card img {
            border-radius: 10px;
            width: 100px;
            height: 100px;
            object-fit: cover;
            margin-right: 20px;
        }


        .testimonial-card p {
            margin: 0;
        }

        .testimonial-card .name {
            font-weight: bold;
            margin-top: 10px;
        }

        .testimonial-card .role {
            color: #6c757d;
        }

        .carousel-indicators button {
            background-color: #597445;
        }

        /* FAQ*/
        .header-text {
            color: #597445;
        }

        .sub-text {
            color: #e7e2e2;
        }

        .follow-button {
            background-color: #597445;
            color: #ffffff !important;
            border: none;
            padding: 10px 20px;
            border-radius: 20px;
            font-size: 16px;
            text-decoration: none;
        }

        .follow-button a {
            text-decoration: none;
            color: white;
        }

        .follow-button:hover {
            background-color: #597445;
        }

        .image-grid img {
            width: 100%;
            border-radius: 10px;
        }

        /* FAQ*/

        .faq-box {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            border: 0.5px solid #cce5cc;
        }

        .faq-box .accordion-button,
        .faq-box .accordion-body {
            /* color: #207f45; */
            /* UMA-style green */
            background-color: #f9fdf9;
        }

        .faq-box .accordion-button:focus {
            box-shadow: none;
        }

        .faq-wrapper {
            max-width: 3000px;
            /* or 600px, depending on how narrow you want it */
            width: 100%;
            padding: 20px;
        }


        .faq-section {
            text-align: center;
            padding: 50px 10px;
        }

        .faq-title {
            color: #597445;
            font-size: 24px;
            font-weight: bold;
        }

        .faq-subtitle {
            color: #b0b0b0;
            font-size: 16px;
            margin-bottom: 30px;
        }

        .faq-item {
            background-color: #ffffff;
            color: #000000;
            border-radius: 20px;
            /* padding: 20px; */
            margin: 10px 0;
            text-align: left;
        }

        .faq-item h5 {
            font-size: 18px;
            font-weight: bold;
        }

        .faq-item p {
            font-size: 14px;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <img src="img/logo.png" alt="" width="8%" class="py-4">
            <a class="navbar-brand fw-bolder" href="#" style="color: c;">ma</a>
            <button aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation"
                class="navbar-toggler" data-bs-target="#navbarNav" data-bs-toggle="collapse" type="button">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="btn btn-green rounded-pill fw-medium p-2 px-4 me-4" href="login.php">Login</a>
                    </li>
                    </ul>
                <ul class="navbar-nav ">

                    <li class="nav-item">
                        <a class="btn btn-green rounded-pill fw-medium p-2 px-4 " href="signup.php">Sign Up</a>
                    </li>
                    </ul>
            </div>
        </div>
    </nav>

    <!--Landing Page-->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="green" style="font-size: 3.3rem;">Discover the freshness <br> of local</h1>
                    <p class="lead" style="font-size: 18px;">Experience the true taste of your community with UMA. Our
                        online marketplace connects you directly to local farmers, food producers, and community
                        gardens, offering a wide variety of fresh, sustainable, and delicious products.</p>
                    <a class="btn btn-light green rounded-pill p-3 px-4  me-3" href="#feature"> Learn more </a>
                    <a class="btn btn-get-started btn-green rounded-pill fw-medium p-3 px-4 "
                        href="{{ route('sign-up') }}">Get started <i class="fa fa-arrow-right"
                            aria-hidden="true"></i></a>
                </div>
                <div class="col-md-6 text-center">
                    <img alt="Illustration of a farmer walking with a hat and overalls" class="custom-img"
                        src="img/home3.png" />
                </div>
            </div>
        </div>
    </section>

    <!--Features-->
    <section class="container" id="feature">
        <div class="row gx-5 justify-content-center">
            <div class="col-lg-8 col-xl-12 col-s-12">
                <div class="text-center">
                    <br><br><br> <br><br> <br>
                    <h2 class="fw-bolder"><span class="px-2 green">Browse our set of features</span></h2>
                    <p class="lead mb-5">Find fresh, sustainable food sources right in your neighborhood.
                        <br>Connect with local farmers and producers to support your community and
                        <br>enjoy delicious, high-quality products
                    </p>
                </div>
            </div>
        </div>
    </section>
    <!--Numbers-->
    <section class="container" id="numbers-section"> <br>
        <h2 class="text-center green ">Our results in numbers</h2> <br> <br>
        <div class="row justify-content-center">
            <div class="col-md-3 col-sm-6 col-6 text-center">
                <h3 id="satisfaction">0</h3>
                <p>Customer satisfaction</p>
            </div>
            <div class="col-md-3 col-sm-6 col-6 text-center">
                <h3 id="users">0</h3>
                <p>Registered Farmers</p>
            </div>
            <div class="col-md-3 col-sm-6 col-6 text-center">
                <h3 id="team-members">0</h3>
                <p>Registered Consumers</p>
            </div>
            <div class="col-md-3 col-sm-6 col-6 text-center">
                <h3 id="growth">0</h3>
                <p>Community Reach</p>
            </div>
        </div>
    </section>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        function animateValue(id, start, end, duration) {
            if (start === end) return;
            var range = end - start;
            var current = start;
            var increment = end > start ? 1 : -1;
            var stepTime = Math.abs(Math.floor(duration / range));
            var obj = document.getElementById(id);
            var timer = setInterval(function() {
                current += increment;
                obj.innerHTML = current;
                if (current == end) {
                    clearInterval(timer);
                }
            }, stepTime);
        }

        function isElementInViewport(el) {
            var rect = el.getBoundingClientRect();
            return (
                rect.top >= 0 &&
                rect.left >= 0 &&
                rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                rect.right <= (window.innerWidth || document.documentElement.clientWidth)
            );
        }

        var animated = false;

        $(window).scroll(function() {
            var element = document.getElementById('numbers-section');
            if (isElementInViewport(element) && !animated) {
                animateValue("satisfaction", 0, 50, 2000);
                animateValue("users", 0, 20, 2000);
                animateValue("team-members", 0, 24, 2000);
                animateValue("growth", 0, 27, 2000);
                animated = true;
            }
        });
    </script>

    <!--Steps-->
    <section class="container">

    </section>
    <!--Testimonials-->
    <section class="container">
        <div class="testimonial-section">
            <h2>What our clients say</h2>
            <p>Hear what our customers have to say about their experience shopping local on UMA.</p>
            <div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <div class="testimonial-card">
                            <img src="img/eric.png" alt="Portrait of Eric Vill">
                            <div>
                                <p>UMA has made it so easy for me to connect with local farmers and find fresh,
                                    high-quality produce.</p>
                                <p class="name">Eric Vill</p>
                                <p class="role">Local consumer</p>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="testimonial-card">
                            <img src="matt.png" alt="Portrait of Matt Cannon with a child">
                            <div>
                                <p>I love supporting local businesses, and UMA has been a great resource for discovering
                                    new and exciting food options.</p>
                                <p class="name">Matt Cannon</p>
                                <p class="role">Web User</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#testimonialCarousel" data-bs-slide-to="0" class="active"
                        aria-current="true" aria-label="Slide 1"></button>
                    <button type="button" data-bs-target="#testimonialCarousel" data-bs-slide-to="1"
                        aria-label="Slide 2"></button>
                </div>
            </div>
        </div>
    </section>

    <!--Socials-->
    <section class="container">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="header-text">Follow us on Instagram</h2>
                    <p class="sub-text">Connect with us via our social media account in Instagram and stay updated to
                        latest news with us.</p>
                </div>
                <button class="follow-button"><a href="https://www.instagram.com/umaofficial_ph?igsh=emM1YTR2bW44cTU1"
                        target="_blank">Follow us</a></button>
            </div>
            <div class="row image-grid mt-4">
                <div class="col-md-4 mb-4">
                    <img class="grid-img" src="img/pic1.png" alt="Two children smiling at the camera">
                </div>
                <div class="col-md-4 mb-4">
                    <img class="grid-img" src="img/woman.jpg"
                        alt="A woman selecting vegetables at a market">
                </div>
                <div class="col-md-4 mb-4">
                    <img class="grid-img" src="img/market.jpg"
                        alt="A market stall with various fruits and vegetables">
                </div>
                <div class="col-md-4 mb-4">
                    <img class="grid-img" src="img/oldman.jpg"
                        alt="An elderly man at a market stall with various goods">
                </div>
                <div class="col-md-4 mb-4">
                    <img class="grid-img" src="img/jars.jpg" alt="Jars of preserved food on a shelf">
                </div>
                <div class="col-md-4 mb-4">
                    <img class="grid-img" src="img/vegetables.jpg"
                        alt="A market stall with various colorful vegetables">
                </div>
            </div>
        </div>
    </section>

    <!--FAQ-->
    <section class="container">
        <div class="faq-wrapper mx-auto">
            <div class="container faq-section">
                <div class="row">
                    <div class="col-12">
                        <div class="faq-title green">Frequently Asked Questions</div>
                        <div class="faq-subtitle">Need help? Find answers to common questions about UMA.</div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-12 col-md-8">
                        <div class="accordion" id="faqAccordion">

                            <!-- FAQ 1 -->
                            <div class="accordion-item faq-box mb-3">
                                <div class="faq-item">
                                    <h2 class="accordion-header" id="headingOne">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseOne" aria-expanded="true"
                                            aria-controls="collapseOne">
                                            What types of farms and food producers are listed on the website?
                                        </button>
                                    </h2>
                                    <div id="collapseOne" class="accordion-collapse collapse show"
                                        aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            We list a variety of farms, including fruit and vegetable farms, livestock
                                            farms,
                                            dairy
                                            farms, wineries, breweries, and food producers like bakeries, cheesemakers,
                                            and
                                            honey producers.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- FAQ 2 -->
                            <div class="accordion-item faq-box mb-3">
                                <div class="faq-item">
                                    <h2 class="accordion-header" id="headingTwo">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseTwo"
                                            aria-expanded="false" aria-controls="collapseTwo">
                                            How do I find farms and producers near me?
                                        </button>
                                    </h2>
                                    <div id="collapseTwo" class="accordion-collapse collapse"
                                        aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            You can use our search and filter tools to find nearby farms and producers
                                            based
                                            on
                                            location and product type.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- FAQ 3 -->
                            <div class="accordion-item faq-box mb-3">
                                <div class="faq-item">
                                    <h2 class="accordion-header" id="headingThree">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseThree"
                                            aria-expanded="false" aria-controls="collapseThree">
                                            Can I buy food directly from the farms and producers?
                                        </button>
                                    </h2>
                                    <div id="collapseThree" class="accordion-collapse collapse"
                                        aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            Yes! UMA connects you directly with local farms and producers so you can
                                            order
                                            food
                                            from them and either pick it up or have it delivered, depending on their
                                            options.
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!--Socials-->
    <section class="container">
        <div class="container d-flex justify-content-center align-items-center vh 100">
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="contact-card">
                        <h3 style="color: #4a5e4d; font-weight:500;">Contact us</h3>
                        <p>If you have any concerns please contact us if any of these following information.</p>
                        <p><i class="fas fa-envelope"></i> uma@gmail.com</p>
                        <p><i class="fas fa-phone"></i> +639-3866-8175</p>
                        <p><i class="fas fa-map-marker-alt"></i> Prk. Malakalya Brgy. Masaktan Mahal City</p>
                    </div>
                </div>
                <div class="col-md-6 order-first order-lg-last">
                    <div class="image-card ">
                        <img src="img/woman2.png" width="400" height="210"
                            alt="A person wearing a headscarf and a black coat, picking vegetables at a market stall">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <br>
    <br>

    <footer class="text-center text-lg-start text-white" style="background-color: #1c1c1c;">
        <!-- Grid container -->
        <div class="container p-4 pb-0">
            <!-- Section: Links -->
            <section class="">
                <!--Grid row-->
                <div class="row">
                    <!-- Grid column -->
                    <div class="col-md-3 col-lg-3 col-xl-3 mx-auto mt-3">
                        <h4 class="mb-4 fw-bolder"><span class="px-2">UMA</span></h4>
                        <p>Experience the true taste of your community with UMA.</p>
                    </div>
                    <!-- Grid column -->
                    <hr class="w-100 clearfix d-md-none" />
                    <!-- Grid column
                <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mt-3">
                    <h6 class="text-uppercase mb-4 fw-bolder text-success">USEFUL LINKS</h6>
                    <p><a class="text-light nav-link text-light page link-success link-offset-2" href="about.html">About</a></p>
                    <p><a class="text-light nav-link text-light page link-success link-offset-2" href="contact.html">Landing</a></p>
                    <p><a class="text-light nav-link text-light page link-success link-offset-2" href="blog-home.html">Blog</a></p>
                    <p><a class="text-light nav-link text-light page link-success link-offset-2" href="pricing.html">Donate</a></p>
                </div>
                 -->

                    <!-- Grid column -->
                    <hr class="w-100 clearfix d-md-none" />

                    <!-- Grid column -->
                    <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mt-3">
                        <h6 class="text-uppercase mb-4 fw-bolder text-success">Contact</h6>
                        <p><i class=""></i> Panabo City</p>
                        <p><i class=""></i> uma@gmail.com</p>
                        <p><i class=""></i>09123456789</p>
                        <p><i class=""></i>09123456789</p>
                    </div>
                    <!-- Grid column -->
                </div>
                <!--Grid row-->
            </section>
            <!-- Section: Links -->

            <hr class="my-3">
            <!-- Section: Copyright -->
            <section class="p-3 pt-0">
                <div class="row d-flex align-items-center">
                    <!-- Grid column -->
                    <div class="col-md-7 col-lg-8 text-center text-md-start">
                        <!-- Copyright -->
                        <div class="p-3">© 2024 Copyright </div>
                        <!-- Copyright -->
                    </div>
                    <!-- Grid column -->

                    <!-- Grid column -->
                    <div class="col-md-5 col-lg-4 ml-lg-0 text-center text-md-end">
                        <!-- Facebook -->
                        <a class="btn btn-outline-light rounded-circle">
                            <i class="fab fa-facebook-f"></i></a>

                        <!-- Twitter -->
                        <a class="btn btn-outline-light  rounded-circle">
                            <i class="fab fa-twitter"></i></a>

                        <!-- Google -->
                        <a class="btn btn-outline-light  rounded-circle">
                            <i class="fab fa-instagram"></i></a>
                        </a>

                        <!-- Instagram -->
                        <a class="btn btn-outline-light  rounded-circle">
                            <i class="fab fa-google"></i>
                        </a>
                    </div>
                    <!-- Grid column -->
                </div>
            </section>
            <!-- Section: Copyright -->
        </div>
        <!-- Grid container -->
    </footer>
</body>

</html>
