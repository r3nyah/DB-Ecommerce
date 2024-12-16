<?php
session_start();
include 'config.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['customer_id'])) {
    header("Location: loginform.php");
    exit;
}

$customer_id = $_SESSION['customer_id'];

// Konfigurasi pagination
$transactionsPerPage = 8; // Jumlah transaksi yang ditampilkan per halaman
$page = isset($_GET['page']) ? $_GET['page'] : 1; // Nomor halaman saat ini
$startFrom = ($page - 1) * $transactionsPerPage; // Mulai dari transaksi ke-...

// Mendapatkan kata kunci pencarian jika ada
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

// Query untuk mengambil transaksi
// Query untuk mengambil transaksi
if ($searchQuery != '') {
    $queryTransactions = "SELECT t.transaction_id, t.transaction_date, 
                                  p.product_name, td.quantity, td.total_price
                           FROM transaction t
                           JOIN transaction_detail td ON t.transaction_id = td.transaction_id
                           JOIN product p ON td.product_id = p.product_id
                           WHERE t.customer_id = '$customer_id'
                           AND (t.transaction_id LIKE '%$searchQuery%'
                                OR p.product_name LIKE '%$searchQuery%')
                           ORDER BY t.transaction_date DESC
                           LIMIT $startFrom, $transactionsPerPage";
} else {
    $queryTransactions = "SELECT t.transaction_id, t.transaction_date, 
                                  p.product_name, td.quantity, td.total_price
                           FROM transaction t
                           JOIN transaction_detail td ON t.transaction_id = td.transaction_id
                           JOIN product p ON td.product_id = p.product_id
                           WHERE t.customer_id = '$customer_id'
                           ORDER BY t.transaction_date DESC
                           LIMIT $startFrom, $transactionsPerPage";
}
$resultTransactions = mysqli_query($conn, $queryTransactions);

// Query untuk menghitung total transaksi
if ($searchQuery != '') {
    $totalQuery = "SELECT COUNT(*) AS total
                   FROM transaction t
                   JOIN transaction_detail td ON t.transaction_id = td.transaction_id
                   JOIN product p ON td.product_id = p.product_id
                   WHERE t.customer_id = '$customer_id'
                   AND (t.transaction_id LIKE '%$searchQuery%'
                        OR p.product_name LIKE '%$searchQuery%')";
} else {
    $totalQuery = "SELECT COUNT(*) AS total
                   FROM transaction
                   WHERE customer_id = '$customer_id'";
}
$totalResult = mysqli_query($conn, $totalQuery);
$totalRow = mysqli_fetch_assoc($totalResult);
$totalTransactions = $totalRow['total'];
$totalPages = ceil($totalTransactions / $transactionsPerPage); // Total halaman

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction History | MikroTik E-Commerce</title>
    <link href="style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card-img-top {
            height: 100px; /* Ukuran gambar lebih kecil */
            object-fit: fill; /* Membuat gambar mengisi area card */
        }
        body {
            padding-top: 70px; /* Sesuaikan dengan tinggi navbar */
        }
    </style>
</head>
<body>
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
                    <form class="d-flex ms-auto" method="GET" action="transaction_history.php">
                        <input class="form-control me-2" type="search" placeholder="Search products" aria-label="Search" name="search" value="<?php echo $searchQuery; ?>">
                        <button class="btn btn-outline-light" type="submit">Search</button>
                    </form>
                </div>
            </div>
        </nav>
    </header>

<div class="container my-4">
    <h2 class="text-center mb-4">Transaction History</h2>
    
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Transaction ID</th>
                <th>Transaction Date</th>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($resultTransactions) > 0): ?>
                <?php while ($transaction = mysqli_fetch_assoc($resultTransactions)): ?>
                    <tr>
                        <td><?php echo $transaction['transaction_id']; ?></td>
                        <td><?php echo $transaction['transaction_date']; ?></td>
                        <td><?php echo $transaction['product_name']; ?></td>
                        <td><?php echo $transaction['quantity']; ?></td>
                        <td>Rp. <?php echo number_format($transaction['total_price'], 2); ?></td>
                        <td>
                            <a href="transaction_detail.php?transaction_id=<?php echo $transaction['transaction_id']; ?>" class="btn btn-danger btn-sm">Detail</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">No transaction history found. <a href="products.php">Shop now</a>.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Pagination -->
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                    <a class="page-link" href="transaction_history.php?page=<?php echo $i; ?>&search=<?php echo $searchQuery; ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
