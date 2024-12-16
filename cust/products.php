<?php
if(session_id() == '' || !isset($_SESSION)){ session_start(); }

// Konfigurasi pagination
$productsPerPage = 8; // Jumlah produk yang ditampilkan per halaman

// Mendapatkan kata kunci pencarian jika ada
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

// Mengambil nomor halaman saat ini dari URL, jika tidak ada set ke halaman 1
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$startFrom = ($page - 1) * $productsPerPage; // Mulai dari produk ke-...

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - MikroTik E-Commerce</title>
    <!-- Favicon -->
    <link rel="icon" href="favicon.ico" type="images/logo.jpg">
    <!-- Link ke file CSS eksternal -->
    <link href="style.css" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card-img-top {
            height: 100px; /* Ukuran gambar lebih kecil */
            object-fit: fill; /* Membuat gambar mengisi area card */
        }
        .card {
            height: 100%; /* Memastikan card mengisi seluruh ruang */
        }
        body {
        padding-top: 40px; /* Sesuaikan dengan tinggi navbar */
    }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark  fixed-top">
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
                            <a class="nav-link active" href="products.php">Products</a>
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

                    <!-- Search Form -->
                    <form class="d-flex ms-auto" method="GET" action="products.php">
                        <input class="form-control me-2" type="search" placeholder="Search products" aria-label="Search" name="search" value="<?php echo $searchQuery; ?>">
                        <button class="btn btn-outline-light" type="submit">Search</button>
                    </form>
                </div>
            </div>
        </nav>
    </header>

    <!-- Products Section -->
    <section id="products" class="container my-5">
        <h2 class="text-center mb-4">Our Products</h2>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
            <?php
            // Mengambil koneksi database dari file eksternal
            include 'config.php'; // Pastikan file config.php sudah ada dan terhubung ke database

            // Query untuk mengambil data produk dengan pagination dan pencarian
            if ($searchQuery != '') {
                $query = "SELECT product_id, product_name, product_price, product_image FROM product WHERE product_name LIKE '%$searchQuery%' LIMIT $startFrom, $productsPerPage";
            } else {
                $query = "SELECT product_id, product_name, product_price, product_image FROM product LIMIT $startFrom, $productsPerPage";
            }
            $result = mysqli_query($conn, $query);

            // Mengecek apakah ada data produk
            if (mysqli_num_rows($result) > 0) {
                // Loop melalui setiap produk
                while ($row = mysqli_fetch_assoc($result)) {
                    $productId = $row['product_id'];
                    $productName = $row['product_name'];
                    $productPrice = $row['product_price'];
                    $productImage = $row['product_image'];
                    // Menampilkan setiap produk dalam format card
                    echo '
                    <div class="col">
                        <div class="card h-100">
                            <img src="' . $productImage . '" class="card-img-top" alt="' . $productName . '" style="height: 200px; object-fit: fill;">
                            <div class="card-body">
                                <h5 class="card-title">' . $productName . '</h5>
                                <p class="card-text">RP.' . number_format($productPrice, 2) . '</p>
                                <a href="productDetails.php?id=' . $productId . '" class="btn btn-primary">View Details</a>
                            </div>
                        </div>
                    </div>';
                }
            } else {
                // Jika tidak ada data produk
                echo '<p class="text-center">No products found.</p>';
            }
            ?>
        </div>

        <!-- Pagination -->
        <nav aria-label="Page navigation" class="mt-4"> <!-- Menambahkan margin-top pada pagination -->
            <ul class="pagination justify-content-center">
                <?php
                // Query untuk menghitung total produk
                if ($searchQuery != '') {
                    $totalQuery = "SELECT COUNT(*) AS total FROM product WHERE product_name LIKE '%$searchQuery%'";
                } else {
                    $totalQuery = "SELECT COUNT(*) AS total FROM product";
                }
                $totalResult = mysqli_query($conn, $totalQuery);
                $totalRow = mysqli_fetch_assoc($totalResult);
                $totalProducts = $totalRow['total'];

                // Menghitung total halaman yang dibutuhkan
                $totalPages = ceil($totalProducts / $productsPerPage);

                // Menampilkan pagination links
                for ($i = 1; $i <= $totalPages; $i++) {
                    $activeClass = ($i == $page) ? 'active' : '';
                    echo "<li class='page-item $activeClass'><a class='page-link' href='products.php?page=$i&search=$searchQuery'>$i</a></li>";
                }
                ?>
            </ul>
        </nav>
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
