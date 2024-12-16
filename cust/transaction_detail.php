<?php
session_start();
include 'config.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['customer_id'])) {
    header("Location: loginform.php");
    exit;
}

$customer_id = $_SESSION['customer_id'];
$transaction_id = $_GET['transaction_id'];

// Ambil detail transaksi berdasarkan transaction_id
$queryTransactionDetail = "
    SELECT td.transaction_detail_id, td.quantity, td.total_price, td.status, 
           p.product_name, p.product_price, p.product_description, 
           c.category_name, cu.customer_address, t.transaction_date, t.transaction_id, td.transaction_description
    FROM transaction_detail td
    JOIN product p ON td.product_id = p.product_id
    JOIN category c ON p.category_id = c.category_id
    JOIN transaction t ON td.transaction_id = t.transaction_id
    JOIN customer cu ON t.customer_id = cu.customer_id
    WHERE td.transaction_id = '$transaction_id' AND cu.customer_id = '$customer_id'
";

// Menjalankan query
$resultTransactionDetail = mysqli_query($conn, $queryTransactionDetail);

// Menangani error jika query gagal
if (!$resultTransactionDetail) {
    die('Query Error: ' . mysqli_error($conn));
}

if (mysqli_num_rows($resultTransactionDetail) == 0) {
    echo "No details found for this transaction.";
    exit;
}

// Ambil informasi transaksi untuk ditampilkan pertama kali
$transaction = mysqli_fetch_assoc($resultTransactionDetail);
$transaction_id = $transaction['transaction_id'];
$transaction_date = $transaction['transaction_date'];
$customer_address = $transaction['customer_address'];
$transaction_status = $transaction['status'];  // Ambil status
$transaction_description = $transaction['transaction_description'];  // Ambil deskripsi
$total_price = 0;  // Variabel untuk menyimpan total harga transaksi

// Hitung total harga transaksi
do {
    $total_price += $transaction['total_price'];
} while ($transaction = mysqli_fetch_assoc($resultTransactionDetail));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Receipt | MikroTik E-Commerce</title>
    <link href="style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .receipt-container {
            background-color: white;
            border: 1px solid #ddd;
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .receipt-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .receipt-header h2 {
            margin: 0;
            color: #333;
        }
        .receipt-header p {
            margin: 0;
            font-size: 14px;
            color: #777;
        }
        .receipt-detail {
            margin-bottom: 20px;
        }
        .receipt-detail h4 {
            margin-bottom: 10px;
        }
        .product-item {
            display: flex;
            justify-content: space-between;
            border-bottom: 1px solid #eee;
            padding: 10px 0;
        }
        .product-item:last-child {
            border-bottom: none;
        }
        .product-name {
            font-weight: bold;
        }
        .product-price {
            color: #28a745;
        }
        .address {
            margin-bottom: 20px;
        }
        .status {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .total-price {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            text-align: right;
        }
        .description {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="receipt-container">
    <div class="receipt-header">
        <h2>Transaction Receipt</h2>
        <p>Transaction ID: <?php echo $transaction_id; ?></p>
        <p>Transaction Date: <?php echo date('d-m-Y H:i:s', strtotime($transaction_date)); ?></p>
    </div>

    <div class="receipt-detail">
        <h4>Product Details</h4>
        <?php
        // Reset result pointer to the beginning
        mysqli_data_seek($resultTransactionDetail, 0);

        // Menampilkan produk
        while ($transaction = mysqli_fetch_assoc($resultTransactionDetail)) { ?>
            <div class="product-item">
                <div class="product-name"><?php echo $transaction['product_name']; ?></div>
                <div class="product-quantity">x <?php echo $transaction['quantity']; ?></div>
                <div class="product-price">Rp. <?php echo number_format($transaction['total_price'], 2); ?></div>
            </div>
        <?php } ?>
    </div>

    <div class="address">
        <h4>Shipping Address</h4>
        <p><?php echo $customer_address; ?></p>
    </div>

    <div class="description">
        <h4>Transaction Description</h4>
        <p><?php echo $transaction_description; ?></p>
    </div>

    <div class="status">
        <h4>Status: <?php echo ucfirst($transaction_status); ?></h4>
    </div>

    <div class="total-price">
        Total: Rp. <?php echo number_format($total_price, 2); ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
