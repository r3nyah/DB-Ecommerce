<?php
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['admin'])) {
    // Jika belum login, arahkan ke halaman login
    header("Location: login_form.php");
    exit();
}

include 'config.php'; // Koneksi ke database

// Cek apakah ada ID transaksi yang diterima melalui URL
if (isset($_GET['transaction_id'])) {
    $transactionId = $_GET['transaction_id'];

    // Query untuk mengambil data transaksi berdasarkan ID
    $query = "SELECT t.transaction_id, td.status 
              FROM transaction t
              JOIN transaction_detail td ON t.transaction_id = td.transaction_id
              WHERE t.transaction_id = $transactionId";
    $result = mysqli_query($conn, $query);

    // Jika transaksi ditemukan
    if (mysqli_num_rows($result) > 0) {
        $transaction = mysqli_fetch_assoc($result);
        $currentStatus = $transaction['status'];
    } else {
        echo "Transaction not found!";
        exit();
    }
} else {
    echo "No transaction ID provided!";
    exit();
}

// Cek apakah form telah disubmit
if (isset($_POST['submit'])) {
    $newStatus = $_POST['status'];

    // Query untuk mengupdate status transaksi
    $updateQuery = "UPDATE transaction_detail 
                    SET status = '$newStatus'
                    WHERE transaction_id = $transactionId";

    if (mysqli_query($conn, $updateQuery)) {
        // Redirect setelah berhasil update
        header("Location: transactions.php");
        exit();
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Transaction - MikroTik E-Commerce</title>
    <link href="style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="">
                    <img src="images/logo.jpg" alt="Logo"> Lintas Buana
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#">Home</a>
                        </li>
                        
                        <?php if(isset($_SESSION['admin'])): ?>

                            <li class="nav-item">
                            <a class="nav-link" href="products.php">Products</a>
                            </li>
                            
                            <li class="nav-item">
                                <a class="nav-link" href="transactions.php">Transactions</a>
                            </li>
                            <!-- Jika user sudah login, tampilkan logout -->

                            <li class="nav-item">
                                <a class="nav-link" href="admin/logout.php">Logout</a>
                            </li>

                            

                        <?php else: ?>
                            <!-- Jika user belum login, tampilkan login dan register -->
                            <li class="nav-item">
                                <a class="nav-link" href="admin/login_form.php">Login</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="admin/register_form.php">Register</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- Edit Transaction Section -->
    <section class="container my-5">
        <h2 class="text-center mb-4">Edit Transaction Status</h2>

        <form method="POST">
            <div class="mb-3">
                <label for="transaction_id" class="form-label">Transaction ID</label>
                <input type="text" class="form-control" id="transaction_id" value="<?php echo $transactionId; ?>" disabled>
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="cancelled" <?php echo ($currentStatus == 'cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                    <option value="pending" <?php echo ($currentStatus == 'process') ? 'selected' : ''; ?>>Process</option>
                    <option value="completed" <?php echo ($currentStatus == 'complete') ? 'selected' : ''; ?>>Complete</option>
                </select>
            </div>

            <button type="submit" name="submit" class="btn btn-primary">Update Status</button>
        </form>
    </section>

    <footer class="footer">
        <p>&copy; 2024 MikroTik E-Commerce | All Rights Reserved</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
