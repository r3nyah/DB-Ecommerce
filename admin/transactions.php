<?php
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['admin'])) {
    // Jika belum login, arahkan ke halaman login
    header("Location: login_form.php");
    exit();
}

include 'config.php'; // Koneksi ke database
$adminId = $_SESSION['admin_id'];

// Konfigurasi pagination
$transactionsPerPage = 8; // Jumlah transaksi per halaman
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$startFrom = ($page - 1) * $transactionsPerPage;

// Mendapatkan kata kunci pencarian jika ada
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

// Query untuk mengambil data transaksi
if ($searchQuery != '') {
    $query = "SELECT t.transaction_id, t.transaction_date, 
                 CONCAT(c.first_name, ' ', c.last_name) AS customer_name, 
                 c.email, c.phone_number, 
                 p.product_name, td.quantity, td.total_price, td.status, td.payment_proof
          FROM transaction t
          JOIN transaction_detail td ON t.transaction_id = td.transaction_id
          JOIN customer c ON t.customer_id = c.customer_id
          JOIN product p ON td.product_id = p.product_id
          WHERE c.first_name LIKE '%$searchQuery%' 
             OR c.last_name LIKE '%$searchQuery%'
             OR p.product_name LIKE '%$searchQuery%'
             OR t.transaction_date LIKE '%$searchQuery%'
          LIMIT $startFrom, $transactionsPerPage";

} else {
    $query = "SELECT t.transaction_id, t.transaction_date, 
                     CONCAT(c.first_name, ' ', c.last_name) AS customer_name, 
                     c.email, c.phone_number, 
                     p.product_name, td.quantity, td.total_price , td.status, td.payment_proof
              FROM transaction t
              JOIN transaction_detail td ON t.transaction_id = td.transaction_id
              JOIN customer c ON t.customer_id = c.customer_id
              JOIN product p ON td.product_id = p.product_id
              LIMIT $startFrom, $transactionsPerPage";
}

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transactions - MikroTik E-Commerce</title>
    <link href="style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card-img-top {
            height: 100px; /* Ukuran gambar lebih kecil */
            object-fit: fill; /* Membuat gambar mengisi area card */
        }
        body {
            padding-top: 40px; /* Sesuaikan dengan tinggi navbar */
        }
        .modal img {
            max-width: 100%;
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
                    <form class="d-flex ms-auto" method="GET" action="transactions.php">
                        <input class="form-control me-2" type="search" placeholder="Search transactions" aria-label="Search" name="search" value="<?php echo $searchQuery; ?>">
                        <button class="btn btn-outline-light" type="submit">Search</button>
                    </form>
                </div>
            </div>
        </nav>
    </header>
    <!-- Transactions Section -->
    <section class="container my-5">
        <h2 class="text-center mb-4">Transactions</h2>

        <!-- Tabel Transaksi -->
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Customer Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Transaction Date</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<tr>
                                <td>' . $row['customer_name'] . '</td>
                                <td>' . $row['email'] . '</td>
                                <td>' . $row['phone_number'] . '</td>
                                <td>' . $row['transaction_date'] . '</td>
                                <td>' . $row['product_name'] . '</td>
                                <td>' . $row['quantity'] . '</td>
                                <td>' . $row['total_price'] . '</td>
                                <td>' . $row['status'] . '</td>
                                <td>
                                    <a href="edit_transaction.php?transaction_id=' . $row['transaction_id'] . '" class="btn btn-primary btn-sm">Edit</a>
                                    <button class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#proofModal' . $row['transaction_id'] . '">Bukti</button>
                                </td>
                              </tr>';

                              

                        // Modal for Payment Proof
                        echo '<div class="modal fade" id="proofModal' . $row['transaction_id'] . '" tabindex="-1" aria-labelledby="proofModalLabel' . $row['transaction_id'] . '" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="proofModalLabel' . $row['transaction_id'] . '">Payment Proof</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-center">
                                            <img src="' . $row['payment_proof'] . '" alt="Payment Proof" class="img-fluid">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>';
                    }
                } else {
                    echo '<tr><td colspan="9" class="text-center">No transactions found.</td></tr>';
                }
                ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <nav aria-label="Page navigation" class="mt-4">
            <ul class="pagination justify-content-center">
                <?php
                // Query untuk menghitung total transaksi
                if ($searchQuery != '') {
                    $totalQuery = "SELECT COUNT(*) AS total 
                                   FROM transaction t
                                   JOIN customer c ON t.customer_id = c.customer_id
                                   WHERE c.first_name LIKE '%$searchQuery%' OR c.last_name LIKE '%$searchQuery%'";
                } else {
                    $totalQuery = "SELECT COUNT(*) AS total FROM transaction";
                }
                $totalResult = mysqli_query($conn, $totalQuery);
                $totalRow = mysqli_fetch_assoc($totalResult);
                $totalTransactions = $totalRow['total'];
                $totalPages = ceil($totalTransactions / $transactionsPerPage);

                for ($i = 1; $i <= $totalPages; $i++) {
                    $activeClass = ($i == $page) ? 'active' : '';
                    echo "<li class='page-item $activeClass'><a class='page-link' href='transactions.php?page=$i&search=$searchQuery'>$i</a></li>";
                }
                ?>
            </ul>
        </nav>
    </section>
    <footer class="footer">
        <p>&copy; 2024 MikroTik E-Commerce | All Rights Reserved</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
