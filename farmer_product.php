<?php
// views/Farmerhome.php
session_start();
require_once 'db.php';
require_once 'product_model.php';

// Fetch Farmers
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
  die('User not logged in.');
}

// Fetch farmers
$farmerStmt = $pdo->prepare("SELECT * FROM farmers WHERE user_id = ?");
$farmerStmt->execute([$user_id]);
$farmers = $farmerStmt->fetchAll();

// Fetch products
$productStmt = $pdo->query("SELECT * FROM products");
$products = $productStmt->fetchAll();

function displayFarmersProductsTable($pdo)
{
  $search = isset($_GET['search']) ? trim($_GET['search']) : '';
  $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
  $limit = 5;
  $offset = ($page - 1) * $limit;
  $searchParam = "%$search%";

  // Count total rows
  $countQuery = "SELECT COUNT(*) FROM products p 
                   JOIN farmers f ON p.user_id = f.user_id 
                   WHERE p.product_name LIKE ?";
  $stmt = $pdo->prepare($countQuery);
  $stmt->execute([$searchParam]);
  $totalProducts = $stmt->fetchColumn();
  $totalPages = ceil($totalProducts / $limit);

  // Fetch paginated farmers
  $query = "SELECT p.id, p.product_name, p.product_price, p.product_stock, p.product_category, p.product_image, p.product_description, f.firstname, f.lastname
              FROM products p 
              JOIN farmers f ON p.user_id = f.user_id
              WHERE p.product_name LIKE ? 
              LIMIT ? OFFSET ?";

  $stmt = $pdo->prepare($query);
  $stmt->bindValue(1, $searchParam, PDO::PARAM_STR);
  $stmt->bindValue(2, (int)$limit, PDO::PARAM_INT);
  $stmt->bindValue(3, (int)$offset, PDO::PARAM_INT);
  $stmt->execute();

  $productss = $stmt->fetchAll(PDO::FETCH_ASSOC);

  return compact('productss', 'search', 'page', 'totalPages');
}

extract(displayFarmersProductsTable($pdo));
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&amp;display=swap" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <title>List of Products</title>
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

    .edit {
      background-color: #597445;
      border: none;
      border-radius: 8px;
      padding: 10px 18px;
      cursor: pointer;
      color: white;
      font-size: 15px;
      font-family: 'Poppins';
    }

    .delete {
      background-color: #597445;
      border: none;
      border-radius: 8px;
      padding: 10px 10px;
      color: white;
      font-size: 15px;
      font-family: 'Poppins';
    }

    .edit a {
      text-decoration: none;
      color: white;
      font-size: 15px;
      font-family: 'Poppins';
    }

    .delete:hover {
      background-color: #658147;
    }

    .edit:hover {
      background-color: #658147;
    }

    .main-content {
      flex: 1;
      padding: 20px;
    }

    .main-content h1 {
      font-size: 24px;
      font-weight: 700;
      margin-bottom: 20px;
    }

    .main-content h1 span {
      color: #658147;
    }

    .form-container {
      background-color: #1a1a1a;
      padding: 20px;
      border-radius: 10px;
      margin-bottom: 20px;
    }

    .form-container input,
    .form-container select {
      width: calc(50% - 10px);
      padding: 10px;
      margin: 10px 5px;
      border: none;
      border-radius: 5px;
      background-color: #333;
      color: #fff;
    }

    .form-container .submit {
      padding: 10px 20px;
      background-color: #597445;
      color: #fff;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    .form-container .note {
      color: #888;
      font-size: 12px;
      margin-top: 10px;
    }

    .table-container {
      background-color: #1a1a1a;
      padding: 20px;
      border-radius: 10px;
    }

    .table-container table {
      width: 100%;
      border-collapse: collapse;
    }

    .table-container th,
    .table-container td {
      padding: 10px;
      text-align: left;
    }

    .table-container th {
      background-color: #333;
    }

    .table-container td {
      background-color: #2a2a2a;
    }

    .table-container img {
      width: 50px;
      height: 50px;
      border-radius: 5px;
    }

    .table-container .action i {
      cursor: pointer;
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
  <div class="sidebar">
    <?php foreach ($farmers as $farmer) { ?>
      <img alt="Profile picture of <?php echo htmlspecialchars($farmer['firstname'] . ' ' . $farmer['lastname']); ?>"
        height="100" width="100" src="uploads/<?php echo htmlspecialchars($farmer['farmer_image']); ?>" />
      <h2>
        <?php echo htmlspecialchars($farmer['firstname'] . ' ' . $farmer['lastname']); ?>
      </h2>
      <p>
        <?php echo htmlspecialchars($farmer['address']); ?>
      </p>
    <?php } ?>

    <div class="menu">
      <a class="" href="farmer_home.php">
        <i class="fas fa-th-large">
        </i>
        Overview
      </a>
      <a href="farmer_product.php" style="background-color: #597445;">
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
  <div class="main-content">


    <?php if (!empty($errors['product_image'])): ?>
      <p class="text-red-500"><?php echo htmlspecialchars($errors['product_image']); ?></p>
    <?php endif; ?>

    <?php if (!empty($errors['product_name'])): ?>
      <p class="text-red-500"><?php echo htmlspecialchars($errors['product_name']); ?></p>
    <?php endif; ?>

    <?php if (!empty($errors['product_price'])): ?>
      <p class="text-red-500"><?php echo htmlspecialchars($errors['product_price']); ?></p>
    <?php endif; ?>

    <?php if (!empty($errors['product_category'])): ?>
      <p class="text-red-500"><?php echo htmlspecialchars($errors['product_category']); ?></p>
    <?php endif; ?>

    <?php if (!empty($errors['product_description'])): ?>
      <p class="text-red-500"><?php echo htmlspecialchars($errors['product_description']); ?></p>
    <?php endif; ?>


    <?php if (!empty($errors['product_stock'])): ?>
      <p class="text-red-500"><?php echo htmlspecialchars($errors['product_stock']); ?></p>
    <?php endif; ?>


    <!-- Add Product Button (on Product List page) -->
    <div class="container mt-4">
      <div class="row align-items-center mb-3">
        <div class="col d-flex justify-content-between align-items-center">
          <h1 class="text-white">Product <span>List</span></h1>
          <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addProductModal"
            style="background-color: #597445; border: none; border-radius: 5px;">
            Add Product
          </button>
        </div>
      </div>
    </div>


    <!-- Add Product Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel"
      aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content" style="background-color: #1a1a1a; padding: 20px; border-radius: 10px;">
          <div class="modal-header">
            <h5 class="modal-title" id="addProductModalLabel">Add New Product</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>

          <!-- Product Form (Action to post data) -->
          <form action="product_controller.php" method="POST" enctype="multipart/form-data">
            <div class="modal-body">
              <input type="hidden" name="create_product" value="1">

              <!-- Product Image -->
              <div class="mb-3">
                <label for="product_image" class="form-label text-white">Product Image</label>
                <input type="file" class="form-control" name="product_image" id="product_image"
                  style="background-color: #333; color: #fff; border: none;" required>
              </div>

              <!-- Product Name -->
              <div class="mb-3">
                <label for="product_name" class="form-label text-white">Product Name</label>
                <input type="text" class="form-control" name="product_name" id="product_name"
                  placeholder="Enter Product Name" style="background-color: #333; color: #fff; border: none;" required>
              </div>

              <!-- Product Price -->
              <div class="mb-3">
                <label for="product_price" class="form-label text-white">Product Price <small>(kl)</small></label>
                <input type="text" class="form-control" name="product_price" id="product_price"
                  placeholder="Enter Price" style="background-color: #333; color: #fff; border: none;" required>
              </div>

              <!-- Product Category -->
              <div class="mb-3">
                <label for="product_category" class="form-label text-white">Product Category</label>
                <select name="product_category" id="product_category" class="form-select"
                  style="background-color: #333; color: #fff; border: none;" required>
                  <option disabled selected>Select Category</option>
                  <option>Fruits</option>
                  <option>Meat</option>
                  <option>Grains</option>
                  <option>Vegetables</option>
                  <option>Spices</option>
                  <option>Dairy</option>
                  <option>Chicken</option>
                </select>
              </div>

              <!-- Product Name -->
              <div class="mb-3">
                <label for="product_description" class="form-label text-white">Product Description</label>
                <textarea type="text" class="form-control" name="product_description" id="product_description"
                  placeholder="Enter Product Description" style="background-color: #333; color: #fff; border: none;" required></textarea>
              </div>

              <!-- Product Stock -->
              <div class="mb-3">
                <label for="product_stock" class="form-label text-white">Product Stock<small>(kg)</small></label>
                <input type="text" class="form-control" name="product_stock" id="product_stock"
                  placeholder="Enter Stock" style="background-color: #333; color: #fff; border: none;" required>
              </div>
            </div>

            <!-- Modal Footer with Close and Submit Buttons -->
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <!-- Submit Button -->
              <button type="submit" class="btn btn-success"
                style="background-color: #597445; color: #fff; border: none; border-radius: 5px;">Post Product</button>
            </div>
          </form>
        </div>
      </div>
    </div>


    <div class="container py-4 bg-dark text-white rounded shadow">

      <!-- Search Form -->
      <form method="GET" class="mb-4 d-flex align-items-center gap-2">
        <input type="text" name="search" class="form-control rounded-pill bg-secondary text-white border-0" placeholder="Search Products..." value="<?= htmlspecialchars($search) ?>">

        <?php if (!empty($search)): ?>
          <a href="farmer_product.php" class="btn btn-danger rounded-circle d-flex align-items-center justify-content-center" title="Clear search" style="width: 40px; height: 40px;">
            <i class="fas fa-times"></i>
          </a>
        <?php endif; ?>

        <button type="submit" class="btn btn-light rounded-pill px-4">Search</button>
      </form>

      <!-- Product Table -->
      <div class="table-responsive">
        <table class="table table-dark table-hover table-sm align-middle">
          <thead class="text-uppercase text-secondary small border-bottom border-secondary">
            <tr>
              <th style="width: 8%;">Image</th>
              <th style="width: 15%;">Product</th>
              <th style="width: 30%;">Description</th>
              <th style="width: 10%;">Price</th>
              <th style="width: 8%;">Stock</th>
              <th style="width: 15%;">Category</th>
              <th class="text-center" style="width: 14%;">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($productss as $product): ?>
              <tr style="font-size: 0.85rem;">
                <td>
                  <?php
                  $imageName = trim($product['product_image']);
                  $imagePathEncoded = 'uploads/' . rawurlencode($imageName);
                  ?>
                  <img src="<?= $imagePathEncoded ?>" alt="product" width="40" height="40" class="rounded"
                    onerror="console.error('Image failed to load: <?= $imageName ?>')">
                </td>
                <td><?= htmlspecialchars($product['product_name'] ?? '') ?></td>
                <td><?= htmlspecialchars($product['product_description'] ?? '') ?></td>
                <td>₱<?= number_format($product['product_price'] ?? 0, 2) ?></td>
                <td><?= htmlspecialchars($product['product_stock'] ?? '') ?></td>
                <td><?= htmlspecialchars($product['product_category'] ?? '') ?></td>
                <td class="text-center">
                  <div class="d-flex justify-content-center gap-1">
                    <button class="btn btn-outline-light btn-sm edit"
                      data-bs-toggle="modal"
                      data-bs-target="#exampleModal"
                      data-product-id="<?= htmlspecialchars($product['id'], ENT_QUOTES) ?>"
                      data-product-image="<?= htmlspecialchars($product['product_image'], ENT_QUOTES) ?>"
                      data-product-name="<?= htmlspecialchars($product['product_name'], ENT_QUOTES) ?>"
                      data-product-description="<?= htmlspecialchars($product['product_description'], ENT_QUOTES) ?>"
                      data-product-price="<?= htmlspecialchars($product['product_price'], ENT_QUOTES) ?>"
                      data-product-stock="<?= htmlspecialchars($product['product_stock'], ENT_QUOTES) ?>"
                      data-product-category="<?= htmlspecialchars($product['product_category'], ENT_QUOTES) ?>">
                      Edit
                    </button>
                    <button class="btn btn-danger btn-sm btn-delete"
                      data-bs-toggle="modal"
                      data-bs-target="#deleteConfirmModal"
                      data-product-id="<?= htmlspecialchars($product['id'], ENT_QUOTES) ?>">
                      Delete
                    </button>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="d-flex justify-content-center align-items-center mt-4 position-relative">
        <!-- Centered pagination buttons -->
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

      <script>
        document.addEventListener("DOMContentLoaded", function() {
          const prevBtn = document.getElementById("prevBtn");
          const nextBtn = document.getElementById("nextBtn");
          const currentPage = parseInt(document.getElementById("currentPage").value);
          const totalPages = <?= $totalPages ?>;
          const searchQuery = "<?= urlencode($search) ?>";

          // Disable buttons when on first/last page
          prevBtn.disabled = (currentPage <= 1);
          nextBtn.disabled = (currentPage >= totalPages);

          prevBtn.addEventListener("click", function() {
            if (currentPage > 1) {
              window.location.href = `?page=${currentPage - 1}&search=${searchQuery}`;
            }
          });

          nextBtn.addEventListener("click", function() {
            if (currentPage < totalPages) {
              window.location.href = `?page=${currentPage + 1}&search=${searchQuery}`;
            }
          });
        });
      </script>


      <!-- Delete Confirmation Modal -->
      <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
          <div class="modal-content" style="background-color: #1a1a1a; color: #fff; border-radius: 10px;">
            <div class="modal-header">
              <h5 class="modal-title" id="deleteConfirmLabel">Confirm Delete</h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              Are you sure you want to delete this product?
            </div>
            <div class="modal-footer">
              <form id="deleteForm" method="POST" action="product_controller.php">
                <input type="hidden" name="delete_product_id" id="deleteProductId">
                <input type="hidden" name="delete_product" value="1">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger">Delete</button>
              </form>
            </div>
          </div>
        </div>
      </div>
      <script>
        document.querySelectorAll('.btn-delete').forEach(button => {
          button.addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');
            document.getElementById('deleteProductId').value = productId;
          });
        });
      </script>
      <!-- // console.log(product.id); -->

      <!-- Product Update Modal -->
      <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered"> <!-- Changed to modal-md -->
          <div class="modal-content"
            style="background-color: #1a1a1a; color: white; padding: 20px; border-radius: 12px;">

            <div class="modal-header border-0 pb-0">
              <h5 class="modal-title w-100 text-center" id="exampleModalLabel" style="font-size: 1.25rem;">Update Product</h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form method="POST" action="product_controller.php" id="form-profile" enctype="multipart/form-data" class="modal-body pt-2 pb-0">
              <input type="hidden" name="update_product" value="1">
              <input type="hidden" name="id" id="update_product_id" value="">
              <input type="hidden" name="existing_image" id="existing_image" value="">

              <!-- Product Image -->
              <label class="form-label mt-2 mb-1">Product Image</label>
              <input type="file" id="update_product_image" name="product_image"
                accept="image/*" style="display: none;">
              <div id="image-preview" class="mb-2"
                style="max-width: 120px; max-height: 120px; display: none; overflow: hidden; border: 1px solid #555; border-radius: 4px;">
                <img id="preview-img" src="" alt="Image preview"
                  style="width: 100%; height: 100%; object-fit: contain;" />
              </div>
              <button type="button" id="select-image" class="btn btn-secondary btn-sm w-100 mb-2">Select Image</button>

              <!-- Product Description -->
              <label class="form-label mt-2 mb-1">Description</label>
              <textarea id="update_product_description" name="product_description"
                class="form-control form-control-sm mb-2"
                style="background-color: #333; color: white; border: none;"></textarea>

              <!-- Product Name -->
              <label class="form-label mt-2 mb-1">Name</label>
              <input type="text" id="update_product_name" name="product_name"
                class="form-control form-control-sm mb-2"
                style="background-color: #333; color: white; border: none;">

              <!-- Price -->
              <label class="form-label mt-2 mb-1">Price</label>
              <input type="text" id="update_product_price" name="product_price"
                class="form-control form-control-sm mb-2"
                style="background-color: #333; color: white; border: none;">

              <!-- Category -->
              <label class="form-label mt-2 mb-1">Category</label>
              <select name="product_category" id="update_product_category"
                class="form-select form-select-sm mb-2"
                style="background-color: #333; color: white; border: none;">
                <option disabled selected>Select category</option>
                <option>Fruits</option>
                <option>Meat</option>
                <option>Cereals</option>
                <option>Vegetables</option>
                <option>Spices</option>
                <option>Dairy</option>
                <option>Chicken</option>
              </select>

              <!-- Stock -->
              <label class="form-label mt-2 mb-1">Stock</label>
              <input type="text" id="update_product_stock" name="product_stock"
                class="form-control form-control-sm mb-3"
                style="background-color: #333; color: white; border: none;">

              <!-- Modal Footer -->
              <div class="modal-footer border-0 p-0 pt-2 d-flex justify-content-between">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary btn-sm"
                  style="padding: 8px 16px; background-color: #597445; color: #fff; border: none; border-radius: 5px;"
                  data-bs-toggle="modal" data-bs-target="#confirmSubmitModal">
                  Save changes
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>


      <script>
        document.getElementById('select-image').addEventListener('click', function() {
          document.getElementById('update_product_image').click();
        });

        document.getElementById('update_product_image').addEventListener('change', function() {
          var file = this.files[0];
          if (file) {
            var reader = new FileReader();
            reader.onload = function(event) {
              var img = document.getElementById('preview-img');
              img.src = event.target.result;
              document.getElementById('image-preview').style.display = 'block';
            };
            reader.readAsDataURL(file);
          }
        });

        document.addEventListener("DOMContentLoaded", function() {
          const editButtons = document.querySelectorAll(".edit");

          // console.log(product.id);
          editButtons.forEach(button => {
            button.addEventListener("click", function() {

              // Get data attributes
              const id = button.getAttribute("data-product-id");
              const image = button.getAttribute("data-product-image"); // this might be misused (see note below)
              const name = button.getAttribute("data-product-name");
              const description = button.getAttribute("data-product-description");
              const price = button.getAttribute("data-product-price");
              const stock = button.getAttribute("data-product-stock");
              const category = button.getAttribute("data-product-category");
              // const exiting_image = button.getAttribute("data-product-image");

              console.log("Setting values:", id);

              // Set modal fields

              document.getElementById("update_product_id").value = id;
              document.getElementById("update_product_name").value = name;
              document.getElementById("update_product_description").value = description;
              document.getElementById("update_product_price").value = price;
              document.getElementById("update_product_stock").value = stock;
              document.getElementById("update_product_category").value = category;
              document.getElementById('existing_image').value = image || '';


              // // Optional: preview image if needed
              if (image) {
                document.getElementById("preview-img").src = 'uploads/' + image;
                document.getElementById("image-preview").style.display = 'block';
              }
            });
          });
        });
      </script>


      <!-- Confirmation Modal -->
      <div class="modal fade" id="confirmSubmitModal" tabindex="-1" aria-labelledby="confirmSubmitModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
          <div class="modal-content" style="background-color: #1a1a1a; color: #fff; border-radius: 10px;">
            <div class="modal-header">
              <h5 class="modal-title" id="confirmSubmitModalLabel">Confirm Save</h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              Are you sure you want to save the changes?
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
              <button type="button" class="btn btn-primary" onclick="submitProductForm()"
                style="background-color: #597445; border: none;">
                Yes
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Script -->
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



</body>

</html>