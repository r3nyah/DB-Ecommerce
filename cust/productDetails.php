<?php
// Memulai session
if (session_id() == '' || !isset($_SESSION)) {
    session_start();
}

// Mengambil ID produk dari URL
$productID = isset($_GET['id']) ? $_GET['id'] : null;

if ($productID) {
    // Mengambil koneksi database
    include 'config.php';

    // Query untuk mengambil data produk berdasarkan ID
    $query = "SELECT product_name, product_price, product_image, product_description, category_name, product_stock
              FROM product
              JOIN category ON product.category_id = category.category_id
              WHERE product.product_id = $productID";
    $result = mysqli_query($conn, $query);

    // Jika produk ditemukan
    if (mysqli_num_rows($result) > 0) {
        $product = mysqli_fetch_assoc($result);
    } else {
        echo '<p class="text-center">Product not found.</p>';
        exit;
    }
} else {
    echo '<p class="text-center">Invalid product.</p>';
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($product['product_name']) ?> - Product Details</title>
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link href="style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .img-fluid {
            border: 3px solid #FF4B4B;
            display: block;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php">
                    <img src="images/logo.png" alt="Logo"> Lintas Buana
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="products.php">Products</a>
                        </li>
                        <?php if (isset($_SESSION['username'])): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="cart.php">Cart</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="logout.php">Logout</a>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link" href="loginform.php">Login</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="registerform.php">Register</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- Product Detail Section -->
    <section class="container my-5">
        <div class="row">
            <div class="col-md-6">
                <img src="<?= htmlspecialchars($product['product_image']) ?>" alt="<?= htmlspecialchars($product['product_name']) ?>" class="img-fluid">
            </div>
            <div class="col-md-6">
                <h2><?= htmlspecialchars($product['product_name']) ?></h2>
                <p><strong>Category:</strong> <?= htmlspecialchars($product['category_name']) ?></p>
                <p><strong>Price:</strong> RP. <?= number_format($product['product_price'], 2) ?></p>
                <p><strong>Description:</strong></p>
                <p><?= nl2br(htmlspecialchars($product['product_description'])) ?></p>
                <p><strong>Stock:</strong> <?= number_format($product['product_stock']) ?></p>
                <a href="products.php" class="btn btn-secondary">Back to Products</a>
                <form action="add_to_cart.php" method="POST" class="mt-3">
    <input type="hidden" name="product_id" value="<?= htmlspecialchars($productID) ?>">
    <label for="quantity" class="form-label">Quantity:</label>
    <input type="number" id="quantity" name="quantity" value="1" 
           min="1" 
           max="<?= $product['product_stock'] ?>" 
           class="form-control w-25 mb-3">
    <button type="submit" class="btn btn-primary">Add to Cart</button>
</form>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3 mt-auto">
        <div class="container">
            <p class="mb-0">&copy; 2024 MikroTik E-Commerce | All Rights Reserved</p>
        </div>
    </footer>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>