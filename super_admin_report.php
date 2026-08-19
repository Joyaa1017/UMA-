<?php
// views/consumer_home.php
session_start();
require_once 'db.php';

function displayReportsTable($pdo)
{
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    $reportType = isset($_GET['type']) && in_array($_GET['type'], ['product', 'farmer']) ? $_GET['type'] : 'product';
    $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;

    $limit = 5;
    $offset = ($page - 1) * $limit;
    $searchParam = "%$search%";

    if ($reportType === 'product') {
        $countQuery = "SELECT COUNT(*) FROM reports r
                   JOIN products p ON r.product_id = p.id
                   JOIN farmers f ON r.farmer_id = f.user_id
                   JOIN consumers c ON r.user_id = c.user_id
                   WHERE (c.firstname LIKE ? OR c.lastname LIKE ? OR f.firstname LIKE ? OR f.lastname LIKE ? OR p.product_name LIKE ?)
                   AND r.report_type = 'product'";

        $countStmt = $pdo->prepare($countQuery);
        $countStmt->bindValue(1, $searchParam);
        $countStmt->bindValue(2, $searchParam);
        $countStmt->bindValue(3, $searchParam);
        $countStmt->bindValue(4, $searchParam);
        $countStmt->bindValue(5, $searchParam);
        $countStmt->execute();
        $totalReports = $countStmt->fetchColumn();
        $totalPages = ceil($totalReports / $limit);

        $query = "SELECT r.report_id, r.reason, r.custom_reason, r.created_at, r.report_type,
                    c.firstname AS consumer_firstname, c.lastname AS consumer_lastname,
                    f.firstname AS farmer_firstname, f.lastname AS farmer_lastname,
                    CONCAT(f.firstname, ' ', f.lastname) AS farmer_name,
                    CONCAT(c.firstname, ' ', c.lastname) AS consumer_name,
                    p.product_name
              FROM reports r
              JOIN products p ON r.product_id = p.id
              JOIN farmers f ON r.farmer_id = f.user_id
              JOIN consumers c ON r.user_id = c.user_id
              WHERE (c.firstname LIKE ? OR c.lastname LIKE ? OR f.firstname LIKE ? OR f.lastname LIKE ? OR p.product_name LIKE ?)
              AND r.report_type = 'product'
              ORDER BY r.created_at DESC
              LIMIT ? OFFSET ?";

        $stmt = $pdo->prepare($query);
        $stmt->bindValue(1, $searchParam);
        $stmt->bindValue(2, $searchParam);
        $stmt->bindValue(3, $searchParam);
        $stmt->bindValue(4, $searchParam);
        $stmt->bindValue(5, $searchParam);
        $stmt->bindValue(6, (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(7, (int)$offset, PDO::PARAM_INT);
        $stmt->execute();

        $reports = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else { // reportType is 'farmer'
        $countQuery = "SELECT COUNT(*) FROM reports r
                   JOIN farmers f ON r.farmer_id = f.user_id
                   JOIN consumers c ON r.user_id = c.user_id
                   WHERE (c.firstname LIKE ? OR c.lastname LIKE ? OR f.firstname LIKE ? OR f.lastname LIKE ?)
                   AND r.report_type = 'farmer'";

        $countStmt = $pdo->prepare($countQuery);
        $countStmt->bindValue(1, $searchParam);
        $countStmt->bindValue(2, $searchParam);
        $countStmt->bindValue(3, $searchParam);
        $countStmt->bindValue(4, $searchParam);
        $countStmt->execute();
        $totalReports = $countStmt->fetchColumn();
        $totalPages = ceil($totalReports / $limit);

        $query = "SELECT r.report_id, r.reason, r.custom_reason, r.created_at, r.report_type,
                    c.firstname AS consumer_firstname, c.lastname AS consumer_lastname,
                    f.firstname AS farmer_firstname, f.lastname AS farmer_lastname,
                    CONCAT(f.firstname, ' ', f.lastname) AS farmer_name,
                    CONCAT(c.firstname, ' ', c.lastname) AS consumer_name
              FROM reports r
              JOIN farmers f ON r.farmer_id = f.user_id
              JOIN consumers c ON r.user_id = c.user_id
              WHERE (c.firstname LIKE ? OR c.lastname LIKE ? OR f.firstname LIKE ? OR f.lastname LIKE ?)
              AND r.report_type = 'farmer'
              ORDER BY r.created_at DESC
              LIMIT ? OFFSET ?";

        $stmt = $pdo->prepare($query);
        $stmt->bindValue(1, $searchParam);
        $stmt->bindValue(2, $searchParam);
        $stmt->bindValue(3, $searchParam);
        $stmt->bindValue(4, $searchParam);
        $stmt->bindValue(5, (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(6, (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        $reports = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    return compact('reports', 'search', 'page', 'totalPages', 'reportType');
}
extract(displayReportsTable($pdo));
?>
<html>

<head>
    <link crossorigin="anonymous" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet" />
    <title>Admin</title>
    <style>
        body {
            background: linear-gradient(45deg, #354226, #000000);
            color: white;
            margin: 0;
            padding: 0;
        }

        /* @import url('https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap'); */

        * {
            font-family: 'Poppins';
        }

        p {
            font-size: 16px;
        }

        .text-black {
            color: #000000;
        }

        .content {
            background: linear-gradient(45deg, #658147, #1C1D21);
            color: white;
            margin-left: 250px;
            padding: 40px;
            min-height: 100%;

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

        .navbar img {
            width: 100px;
            height: 70px;
            border: none;
            border-radius: 50px;
        }

        .custom-search-btn {
            background-color: #1c1c1c;
            color: white;
            transition: background-color 0.3s ease;
        }

        .custom-search-btn:hover {
            background-color: #597445;
            /* Change to your desired hover color */
        }

        .custom-clearsearch-btn {
            background-color: rgba(238, 32, 32, 0.92);
            color: white;
            transition: background-color 0.3s ease;

        }

        .custom-clearsearch-btn:hover {
            background-color: rgba(253, 20, 20, 0.97);
            /* Change to your desired hover color */
        }

        .bg-custom-dark {
            background: linear-gradient(45deg, #1c1c1c, rgb(50, 50, 50));
            border-radius: 5px;

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
                        alt="admin image"
                        height="100"
                        width="100"
                        src="img/administrator.png" />
                    <h4>
                        Welcome Admin
                    </h4>
                </div>
                <nav class="nav flex-column">
                    <a class="nav-link" href="super_admin_dashboard.php">
                        <i class="fas fa-user"></i>
                        Dashboard
                    </a>
                    <a class="nav-link" href="super_admin_farmers.php">
                        <i class="fas fa-user-tie"></i>
                        Farmers
                    </a>
                    <a class="nav-link" href="super_admin_consumers.php">
                        <i class="fas fa-shopping-cart"></i>
                        Consumers
                    </a>
                    <a class="nav-link" href="super_admin_products.php">
                        <i class="fas fa-box"></i>
                        Products
                    </a>
                    <a class="nav-link active" href="super_admin_report.php">
                        <i class="fas fa-box"></i>
                        Report
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
                window.location.href = "login_admin.php";
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
        </div>

        <section class="mt-8">
            <div class="d-flex flex-wrap justify-content-between align-items-start px-4 py-3">
                <h2 class="mb-0 me-3">Reports</h2>

                <form method="GET" class="d-flex flex-grow-1 mt-2" style="max-width: 600px;">
                    <input
                        type="text"
                        name="search"
                        class="form-control me-2 rounded-pill shadow-sm"
                        placeholder="Search products..."
                        value="<?= htmlspecialchars($search) ?>"
                        style="height: 40px; background-color: white; color:#1a1a1a;">

                    <?php if (!empty($search)): ?>
                        <a
                            href="super_admin_products.php"
                            class="btn btn-outline-danger me-2 rounded-pill custom-clearsearch-btn"
                            title="Clear search"
                            style="height: 40px; width: 50px; padding: 10;">
                            <i class="fas fa-times"></i>
                        </a>
                    <?php endif; ?>

                    <button
                        type="submit"
                        class="btn rounded-pill shadow-sm custom-search-btn"
                        style="height: 45px; width: 150px;">
                        <i class="fas fa-search me-1"></i> Search
                    </button>
                </form>
            </div>
            <div class="bg-custom-dark rounded-lg shadow-lg">

                <div class="text-center mb-4">
                    <a href="?type=product" class="btn btn-outline-light <?= $reportType === 'product' ? 'active' : '' ?>">Product Reports</a>
                    <a href="?type=farmer" class="btn btn-outline-light <?= $reportType === 'farmer' ? 'active' : '' ?>">Farmer Reports</a>
                </div>

                <!-- PRODUCT REPORT TABLE -->
                <table id="productTable" class="table text-white table-bordered" style="display: table;">
                    <thead class="table-dark">
                        <tr>
                            <th>Reported Product</th>
                            <th>Reported By</th>
                            <th>Reason</th>
                            <th>Custom Reason</th>
                            <th>Reported At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reports as $report): ?>
                            <tr>
                                <td><?= $report['product_name'] ?></td>
                                <td><?= $report['consumer_name'] ?></td>
                                <td><?= $report['reason'] ?></td>
                                <td><?= $report['custom_reason'] ?></td>
                                <td><?= $report['created_at'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <!-- FARMER REPORT TABLE -->
                <table id="farmerTable" class="table text-white table-bordered" style="display: none;">
                    <thead class="table-dark">
                        <tr>
                            <th>Reported Farmer</th>
                            <th>Reported By</th>
                            <th>Reason</th>
                            <th>Custom Reason</th>
                            <th>Reported At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reports as $report): ?>
                            <tr>
                                <td><?= $report['farmer_name'] ?></td>
                                <td><?= $report['consumer_name'] ?></td>
                                <td><?= $report['reason'] ?></td>
                                <td><?= $report['custom_reason'] ?></td>
                                <td><?= $report['created_at'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>


                <div class="d-flex justify-content-center align-items-center mt-4 position-relative">
                    <div>
                        <button id="prevBtn" class="btn btn-outline-light me-2">&larr; Back</button>
                        <button id="nextBtn" class="btn btn-outline-light">Next &rarr;</button>
                    </div>
                    <!-- Page number on the right corner -->
                    <div class="position-absolute end-0 me-4">
                        <span class="text-white fw-bold" style="font-size: 13px;">Page <?= $page ?> of <?= $totalPages ?></span>
                    </div>
                </div>
                <!-- Hidden field to track the current page -->
                <input type="hidden" id="currentPage" value="<?= $page ?>">
            </div>
        </section>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const reportType = "<?= $reportType ?>";
                showTable(reportType); // Call this on page load to display the correct table
            });

            function showTable(type) {
                document.getElementById('productTable').style.display = (type === 'product') ? 'table' : 'none';
                document.getElementById('farmerTable').style.display = (type === 'farmer') ? 'table' : 'none';
            }
            document.addEventListener("DOMContentLoaded", function() {
                const prevBtn = document.getElementById("prevBtn");
                const nextBtn = document.getElementById("nextBtn");
                const currentPageInput = document.getElementById("currentPage");
                const currentPage = parseInt(currentPageInput.value);
                const totalPages = <?= $totalPages ?>;
                const searchQuery = "<?= urlencode($search) ?>";

                // Disable prev/next if on first or last page
                if (currentPage <= 1) {
                    prevBtn.disabled = true;
                }
                if (currentPage >= totalPages) {
                    nextBtn.disabled = true;
                }
                prevBtn.addEventListener("click", function() {
                    if (currentPage > 1) {
                        window.location.href = `?page=${currentPage - 1}&search=${searchQuery}&type=${reportType}`;
                    }
                });
                nextBtn.addEventListener("click", function() {
                    if (currentPage < totalPages) {
                        window.location.href = `?page=${currentPage + 1}&search=${searchQuery}&type=${reportType}`;
                    }
                });
            });
        </script>

        <!-- View Report Modal -->
        <div class="modal fade" id="viewReportModal" tabindex="-1" aria-labelledby="viewReportLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm modal-dialog-centered">
                <div class="modal-content" style="background-color: #1a1a1a; color: #fff; border-radius: 10px;">
                    <div class="modal-header">
                        <h5 class="modal-title" id="viewReportLabel">Report</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-6">
                                    <p>Report Product: <span id="modalProductName"></span></p>
                                </div>
                                <div class="col-md-6">
                                    <p>Reported By: <span id="modalReporterName"></span></p>
                                </div>
                                <div class="col-md-6">
                                    <p>Reported Farmer: <span id="modalFarmerName"></span></p>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <p>Reason: <span id="modalReason"></span></p>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <p>Custom Reason: <span id="modalCustomReason"></span></p>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <p>Reported At: <span id="modalCreatedAt"></span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.querySelectorAll('.btn-delete').forEach(button => {
                button.addEventListener('click', function() {
                    const reportId = this.getAttribute('data-report-id');
                    console.log(reportId);

                    document.getElementById('deleteReportId').value = reportId;
                });
            });

            document.addEventListener("DOMContentLoaded", function() {
                const viewButtons = document.querySelectorAll("button[data-bs-target='#viewReportModal']");

                viewButtons.forEach(button => {
                    button.addEventListener("click", function() {
                        document.getElementById("modalProductName").textContent = this.getAttribute("data-report-reported_product");
                        document.getElementById("modalReporterName").textContent = this.getAttribute("data-report-reporter");
                        document.getElementById("modalFarmerName").textContent = this.getAttribute("data-report-reported_farmer");
                        document.getElementById("modalReason").textContent = this.getAttribute("data-report-reason");
                        document.getElementById("modalCustomReason").textContent = this.getAttribute("data-report-custom_reason");
                        document.getElementById("modalCreatedAt").textContent = new Date(this.getAttribute("data-report-created")).toLocaleDateString();
                    });
                });
            });
        </script>

        <script>
            function submitProductForm() {
                document.getElementById('form-profile').submit();
            }
        </script>
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-GLhlTQ8iRABQe+29KK4h5C2d5b/W7nR4vKjxj9kSzv26rNf4Pp4j5zZ/xF96gm5f" crossorigin="anonymous">
        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
            crossorigin="anonymous"></script>



    </div>
</body>

</html>