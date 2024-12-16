<?php
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['admin'])) {
    // Jika belum login, arahkan ke halaman login
    header("Location: admin/login_form.php");
    exit();
}

include 'config.php';

// Ambil tahun dari input pengguna atau default ke tahun sekarang
$selectedYear = isset($_GET['year']) ? $_GET['year'] : date('Y');

// Daftar lengkap bulan
$months = [
    1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
    5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
    9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
];

// Query untuk mendapatkan pendapatan bulanan berdasarkan tahun yang dipilih
$query = "SELECT 
            SUM(td.total_price) AS TotalRevenue,
            MONTH(t.transaction_date) AS TransactionMonth
          FROM transaction_detail td
          JOIN transaction t ON td.transaction_id = t.transaction_id
          WHERE YEAR(t.transaction_date) = '$selectedYear'
          GROUP BY MONTH(t.transaction_date)
          ORDER BY MONTH(t.transaction_date) ASC";

$result = mysqli_query($conn, $query);

// Inisialisasi array data transaksi
$transactionData = [];
while ($row = mysqli_fetch_assoc($result)) {
    $transactionData[(int)$row['TransactionMonth']] = (float)$row['TotalRevenue'];
}

// Gabungkan dengan semua bulan (jika tidak ada transaksi, set nilai 0)
$finalData = [];
foreach ($months as $monthNum => $monthName) {
    $finalData[] = [
        'TransactionMonth' => $monthName,
        'TotalRevenue' => isset($transactionData[$monthNum]) ? $transactionData[$monthNum] : 0
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MikroTik E-Commerce</title>
    <link rel="icon" href="favicon.ico" type="images/logo.png">
    <link href="style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                        <?php if(isset($_SESSION['admin'])): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="products.php">Products</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="transactions.php">Transactions</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="admin/logout.php">Logout</a>
                            </li>
                        <?php else: ?>
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

    <!-- Hero Section -->
    <section class="hero text-center">
        <h2>Welcome Admin</h2>
    </section>

    <!-- Revenue Chart Section -->
    <!-- Canvas untuk grafik -->
<section class="container mt-5">
    <h3 class="text-center">Revenue Chart</h3>

    <!-- Form untuk memilih tahun -->
    <form method="GET" class="text-center mb-4">
        <label for="year">Select Year:</label>
        <select name="year" id="year" class="form-select d-inline-block w-auto">
            <?php
            // Generate list of years dynamically
            $currentYear = date('Y');
            for ($i = $currentYear; $i >= $currentYear - 10; $i--) {
                $selected = ($i == $selectedYear) ? 'selected' : '';
                echo "<option value='$i' $selected>$i</option>";
            }
            ?>
        </select>
        <button type="submit" class="btn btn-primary">Show Chart</button>
    </form>

    <!-- Canvas untuk grafik dengan ukuran kecil -->
    <div class="chart-container" style="position: relative; height: 700px; width: 800px; margin: 0 auto;">
        <canvas id="revenueChart"></canvas>
    </div>
</section>


    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2024 MikroTik E-Commerce | All Rights Reserved</p>
    </footer>

    <script>
        // Data dari PHP untuk Chart.js
        const chartData = <?php echo json_encode($finalData); ?>;
        const labels = chartData.map(item => item.TransactionMonth);
        const revenues = chartData.map(item => item.TotalRevenue);

        // Inisialisasi Chart.js
        const ctx = document.getElementById('revenueChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar', // Tipe grafik (bisa diubah ke 'line', 'pie', dll.)
            data: {
                labels: labels,
                datasets: [{
                    label: `Total Revenue in <?php echo $selectedYear; ?> (Rp)`,
                    data: revenues,
                    backgroundColor: 'rgba(235, 54, 54, 0.2)',
                    borderColor: 'rgb(235, 54, 54)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
