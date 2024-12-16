<?php
if(session_id() == '' || !isset($_SESSION)){ session_start(); }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MikroTik E-Commerce</title>
    <link rel="icon" href="favicon.ico" type="images/logo.jpg" >
    <link href="style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="">
                    <img src="images/logo.png" alt="Logo"> Lintas Buana
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="products.php">Products</a>
                        </li>
                        <?php if(isset($_SESSION['username'])): ?>

                            <li class="nav-item">
                                <a class="nav-link" href="cart.php">Cart</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="transaction_history.php">Transactions</a>
                            </li>
                            <!-- Jika user sudah login, tampilkan logout -->

                            <li class="nav-item">
                                <a class="nav-link" href="logout.php">Logout</a>
                            </li>
                        <?php else: ?>
                            <!-- Jika user belum login, tampilkan login dan register -->
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

    <!-- Hero Section -->
    <section class="hero">
        <h2>Welcome to Lintas Buana</h2>
        <p>Shop the best MikroTik routers and accessories!</p>
        <a href="products.php" class="btn btn-primary btn-lg">Browse Products</a>
    </section>

    <!-- Products Section -->
    <section id="products" class="container my-5">
        <h2 class="text-center mb-4">Our Products</h2>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
            <?php
            include 'config.php'; 

            $query = "SELECT product_id, product_name, product_price, product_image FROM product LIMIT 4";
            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $productId = $row['product_id']; // Fetch product_id
                    $productName = $row['product_name'];
                    $productPrice = $row['product_price'];
                    $productImage = $row['product_image'];
                    echo '
                    <div class="col">
                        <div class="card h-100">
                            <img src="' . $productImage . '" class="card-img-top" alt="' . $productName . '" style="height: 200px; object-fit: fill;">
                            <div class="card-body">
                                <h5 class="card-title">' . $productName . '</h5>
                                <p class="card-text">Price: $' . number_format($productPrice, 2) . '</p>
                                <a href="productDetails.php?id=' . $productId . '" class="btn btn-primary">View Details</a>
                            </div>
                        </div>
                    </div>';
                }
            } else {
                echo '<p class="text-center">No products available.</p>';
            }
            ?>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3 mt-auto">
        <div class="container">
            <p class="mb-0">&copy; 2024 MikroTik E-Commerce | All Rights Reserved</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
