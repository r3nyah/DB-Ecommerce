<?php
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['admin'])) {
    // Jika belum login, arahkan ke halaman login
    header("Location: admin/login_form.php");
    exit();
}

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
        body {
            padding-top: 40px; /* Sesuaikan dengan tinggi navbar */
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
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
                           <?php if(isset($_SESSION['admin'])): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="transactions.php">Transactions</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="admin/logout.php">Logout</a>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link" href="admin/login.php">Login</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="admin/register.php">Register</a>
                            </li>
                        <?php endif; ?>
                    </ul>
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

        <a href="add_product_form.php?id=' . $productId . '" class="btn btn-primary btn-sm mb-3">Add</a>


        <!-- Tabel Produk -->
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th>Image</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Actions</th> <!-- Kolom Actions -->
                </tr>
            </thead>
            <tbody>
                <?php
                include 'config.php'; // Koneksi ke database

                // Query untuk mengambil data produk
                if ($searchQuery != '') {
                    $query = "SELECT p.product_id, p.product_name, p.product_image, p.product_price, p.product_stock, c.category_name
                              FROM product p
                              JOIN category c ON p.category_id = c.category_id
                              WHERE p.product_name LIKE '%$searchQuery%' LIMIT $startFrom, $productsPerPage";
                } else {
                    $query = "SELECT p.product_id, p.product_name, p.product_image, p.product_price, p.product_stock, c.category_name
                              FROM product p
                              JOIN category c ON p.category_id = c.category_id
                              LIMIT $startFrom, $productsPerPage";
                }
                $result = mysqli_query($conn, $query);

                
                if (mysqli_num_rows($result) > 0) {
                    // Loop melalui setiap produk
                    while ($row = mysqli_fetch_assoc($result)) {
                        $productId = $row['product_id'];
                        $productName = $row['product_name'];
                        $categoryName = $row['category_name'];
                        $productImage = $row['product_image'];
                        $productprice = $row['product_price'];
                        $productStock = $row['product_stock'];
                        // Menampilkan setiap produk dalam format tabel
                        echo '
                        <tr>
                            <td>' . $productName . '</td>
                            <td>' . $categoryName . '</td>
                            <td><img src="' . $productImage . '" alt="' . $productName . '" style="width: 100px; height: 100px; object-fit: cover;"></td>
                            <td>' . $productprice . '</td>
                            <td>' . $productStock . '</td>
                            <td>
                                <a href="edit_product_form.php?id=' . $productId . '" class="btn btn-warning btn-sm">Edit</a>
                                <a href="delete.php?id=' . $productId . '" class="btn btn-danger btn-sm">Delete</a>
                            </td>
                        </tr>';
                    }
                } else {
                    // Jika tidak ada data produk
                    echo '<tr><td colspan="5" class="text-center">No products found.</td></tr>';
                }
                ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <nav aria-label="Page navigation" class="mt-4">
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
    <footer class="footer">
        <p>&copy; 2024 MikroTik E-Commerce | All Rights Reserved</p>
    </footer>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>

