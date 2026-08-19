<?php
session_start();

if (isset($_GET['role'])) {
    $_SESSION['role'] = $_GET['role']; // Saves 'farmer' or 'consumer'
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Personal Information</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body { font-family: 'Roboto', sans-serif; }
        * { font-family: 'Poppins'; }
        .step-circle { background-color: #6a994e !important; }
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap');
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
            background-color: #6a994e !important;
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
    </style>
</head>
<body class="bg-gradient-to-r from-black to-green-900 h-screen flex items-center justify-center">
    <div class="bg-black bg-opacity-50 p-10 rounded-lg w-3/4">
        <i class="fas fa-arrow-left text-white text-2xl mb-5 cursor-pointer"></i>
        <h1 class="text-white text-3xl font-bold mb-2">Personal <span class="font-normal">Information</span></h1>
        <p class="text-gray-400 mb-5">Enter your account details</p>

        <?php if (isset($_SESSION['errors'])): ?>
            <?php foreach ($_SESSION['errors'] as $error): ?>
                <p class="text-red-500"><?= htmlspecialchars($error) ?></p>
            <?php endforeach; unset($_SESSION['errors']); ?>
        <?php endif; ?>

        <form action="signup_consumer_process.php" method="POST" enctype="multipart/form-data">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                
                <div class="mb-4">
                    <label class="block text-gray-400 mb-1">First Name</label>
                    <input type="text" name="firstname" placeholder="Enter First Name" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-400 mb-1">Address</label>
                    <select name="address" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700" >
                        <option value="Select Baranggay">Select Baranggay</option>
                        <option>Brgy. A. O. Floirendo</option>
                        <option>Brgy. Datu Abdul</option>
                        <option>Brgy. Buenavista</option>
                        <option>Brgy. Cacao</option>
                        <option>Brgy. Cagangohan</option>
                        <option>Brgy. Consolacion</option>
                        <option>Brgy. Dapco</option>
                        <option>Brgy. Gredu</option>
                        <option>Brgy. J.P Laurel</option>
                        <option>Brgy. Kasilak</option>
                        <option>Brgy. Katipunan</option>
                        <option>Brgy. Katualan</option>
                        <option>Brgy. Kauswagan</option>
                        <option>Brgy. Kiotoy</option>
                        <option>Brgy. Little Panay</option>
                        <option>Brgy. Lower Panaga (Roxas)</option>
                        <option>Brgy. Mabunao</option>
                        <option>Brgy. Maduao</option>
                        <option>Brgy. Malativas</option>
                        <option>Brgy. Manay</option>
                        <option>Brgy. Nanyo</option>
                        <option>Brgy. New Malay</option>
                        <option>Brgy. New Malitbog</option>
                        <option>Brgy. New Pandan</option>
                        <option>Brgy. New Visayas</option>
                        <option>Brgy. Quezon</option>
                        <option>Brgy. Salvacion</option>
                        <option>Brgy. San Francisco</option>
                        <option>Brgy. San Nicolas</option>
                        <option>Brgy. San Pedro</option>
                        <option>Brgy. San Roque</option>
                        <option>Brgy. San Vicente</option>
                        <option>Brgy. Sta. Cruz</option>
                        <option>Brgy. Sto. Nino</option>
                        <option>Brgy. Sindaton</option>
                        <option>Brgy. Tagpore</option>
                        <option>Brgy. Tibungol</option>
                        <option>Brgy. Upper Licanan</option>
                        <option>Brgy. Waterfall</option>
                        </select>               
                     </div>
                <div class="mb-4">
                    <label class="block text-gray-400 mb-1">Last Name</label>
                    <input type="text" name="lastname" placeholder="Enter Last Name" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-400 mb-1">Select Profile Picture</label>
                    <input type="file" name="consumer_image" placeholder="Place Image" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-400 mb-1">Purok</label>
                    <input type="text" name="purok" placeholder="Enter Purok" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-400 mb-1">Phone Number</label>
                    <input type="number" maxlength="11" name="phone_number" placeholder="Enter Phone Number" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-400 mb-1">Street</label>
                    <input type="text" name="street" placeholder="Enter Street" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700">
                </div>
            </div>
            <button type="submit" class="w-full p-2 rounded bg-green-600 text-white mt-4">Enter</button>
        </form>
    </div>
</body>
</html>
