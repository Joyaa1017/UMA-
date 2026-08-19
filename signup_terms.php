<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms and Conditions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #1e1e1e, #2e5939);
            color: #ffffff;
            font-family: 'Poppins', sans-serif;
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
            color: white;
        }
        .container {
            margin-top: 50px;
        }
        .terms-title {
            font-size: 2rem;
            font-weight: 600;
        }
        .terms-title span {
            color: #b3ffb3;
        }
        .terms-content {
            margin-top: 20px;
        }
        .form-check-label {
            color: #b3ffb3;
        }
        .btn-enter {
            background-color: #6a994e;
            color: white;
            font-weight: 500;
            border: none;
            border-radius: 15px;
            padding: 11px 150px;
            font-size: 1rem;
            margin-top: 10px;
        }
        a{
            text-decoration: none;
            color: white;
            font-weight: bold;
        }
        .steps {
            margin-top: 50px;
        }
        .step {
            text-align: center;
            color: #b3ffb3;
        }
        .step i {
            font-size: 2rem;
            margin-bottom: 10px;
        }
        .step-title {
            font-size: 1.2rem;
            margin-top: 10px;
        }
        .user-obligations {
            margin-top: 20px;
        }
        .user-obligations h5 {
            color: #b3ffb3;
            margin-top: 20px;
        }
        .terms-section, .steps-section {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6 terms-section">
                <div class="terms-title">
                    Terms and <span>Conditions</span>
                </div>
                <div class="terms-content">
                    <p>By using our website, you agree to comply with and be bound by the following terms and conditions. Please read them carefully before using our site.</p>
                    <p><strong>Acceptance of Terms:</strong> By accessing and using our website, you accept and agree to be bound by these terms and conditions. If you do not agree, please do not use our site.</p>
                    <p><strong>Products and Services:</strong> We provide a platform for farmers to list and sell their products to consumers. All products listed are subject to availability and may be withdrawn at any time. We are not responsible for the quality or safety of the products sold.</p>
                    <div class="form-check">
                        <input class="form-check-input" required type="checkbox" id="agreeTerms">
                        <label class="form-check-label" for="agreeTerms">
                            I agree to the terms and conditions.
                        </label>
                    </div>
                    <button class="btn btn-enter"><a href="signup_complete.php">Enter</a></button>
                </div>
            </div>
            <div class="col-md-6 steps-section">
                <div class="steps">
                    <div style="display: flex; align-items: center;">
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
                         <div class="step-circle" style="background-color: #6a994e; border: 2px solid transparent;">
                          2
                         </div>
                         <div class="step-text">
                          Step 2
                         </div>
                        </div>
                        <div class="step-line">
                        </div>
                        <div class="step">
                         <div class="step-circle" style="background-color: #6a994e; border: 2px solid transparent ;">
                          3
                         </div>
                         <div class="step-text">
                          Step 3
                         </div>
                        </div>
                       </div>
                      </div>
                <div class="user-obligations">
                    <h5>User Obligations</h5>
                    <p><strong>Account Creation:</strong> Users must create an account to purchase or sell products. You agree to provide accurate and complete information during the registration process.</p>
                    <p><strong>Prohibited Activities:</strong> Users may not use our site for any unlawful purpose, including the posting or transmission of any material that is defamatory, obscene, or otherwise illegal.</p>
                    <p><strong>Payment and Pricing:</strong> All prices listed on our site are in Ura. Payments are processed through our secure payment gateway. We reserve the right to change prices at any time without notice.</p>
                    <p><strong>Returns and Refunds:</strong> If you are not satisfied with your purchase, please contact the seller directly. Return and refund policies may vary by seller and product.</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>