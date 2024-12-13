<?php
session_start();
include 'config.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['customer_id'])) {
    header("Location: loginform.php");
    exit;
}

$customer_id = $_SESSION['customer_id'];

// Ambil total harga dari keranjang
$queryTotalPrice = "SELECT SUM(ci.total_price) AS total_price
                    FROM cart_items ci
                    JOIN cart c ON ci.cart_id = c.cart_id
                    WHERE c.customer_id = '$customer_id'";
$resultTotalPrice = mysqli_query($conn, $queryTotalPrice);
$rowTotalPrice = mysqli_fetch_assoc($resultTotalPrice);
$totalPrice = $rowTotalPrice['total_price'] ?? 0;

// Handle konfirmasi pembayaran
if (isset($_POST['confirm_payment'])) {
    $payment_proof = $_FILES['payment_proof']['name'];
    $payment_proof_tmp = $_FILES['payment_proof']['tmp_name'];

    // Pastikan file bukti pembayaran di-upload dengan benar
    if (!empty($payment_proof)) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($payment_proof);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Validasi jenis file gambar
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($imageFileType, $allowed_types)) {
            if (move_uploaded_file($payment_proof_tmp, $target_file)) {
                // Insert transaksi ke tabel transaction
                $queryInsertTransaction = "INSERT INTO transaction (transaction_date, customer_id)
                                           VALUES (CURRENT_TIMESTAMP, '$customer_id')";
                if (mysqli_query($conn, $queryInsertTransaction)) {
                    $transaction_id = mysqli_insert_id($conn);

                    // Pindahkan item dari cart_items ke transaction_detail
                    $queryCartItems = "SELECT * FROM cart_items ci
                                       JOIN cart c ON ci.cart_id = c.cart_id
                                       WHERE c.customer_id = '$customer_id'";
                    $resultCartItems = mysqli_query($conn, $queryCartItems);

                    while ($item = mysqli_fetch_assoc($resultCartItems)) {
                        $product_id = $item['product_id'];
                        $quantity = $item['quantity'];
                        $total_price = $item['total_price'];

                        // Insert detail transaksi ke tabel transaction_detail
                        $queryInsertDetail = "INSERT INTO transaction_detail (transaction_id, product_id, quantity, total_price, status, payment_proof)
                                              VALUES ('$transaction_id', '$product_id', '$quantity', '$total_price', 'Pending', '$target_file')";
                        mysqli_query($conn, $queryInsertDetail);
                    }

                    // Hapus item dari cart_items
                    $queryDeleteCart = "DELETE ci FROM cart_items ci
                                        JOIN cart c ON ci.cart_id = c.cart_id
                                        WHERE c.customer_id = '$customer_id'";
                    mysqli_query($conn, $queryDeleteCart);

                    //hapus data cart
                    $queryDeleteCart = "DELETE FROM cart WHERE customer_id = '$customer_id'";
                    mysqli_query($conn, $queryDeleteCart);

                    $_SESSION['alert'] = "Pembayaran berhasil! Transaksi Anda sedang diproses.";
                    header("Location: transaction_history.php");
                    exit;
                } else {
                    $_SESSION['alert'] = "Terjadi kesalahan saat memproses transaksi.";
                }
            } else {
                $_SESSION['alert'] = "Gagal mengunggah bukti pembayaran.";
            }
        } else {
            $_SESSION['alert'] = "Format file tidak valid. Harap unggah file gambar (jpg, jpeg, png, gif).";
        }
    } else {
        $_SESSION['alert'] = "Harap unggah bukti pembayaran.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout | MikroTik E-Commerce</title>
    <link href="style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* CSS tambahan untuk memastikan form terletak di tengah */
        .centered-container {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
</head>
<body>
<div class="container centered-container">
    <div class="w-100" style="max-width: 600px;">
        <h2 class="my-4 text-center">Checkout</h2>

        <?php
        if (isset($_SESSION['alert'])) {
            echo '<div class="alert alert-info">' . $_SESSION['alert'] . '</div>';
            unset($_SESSION['alert']);
        }
        ?>

        <h4 class="text-center">Bank Transfer</h4>
        <p><strong>Bank:</strong> BCA</p>
        <p><strong>Nomor Rekening:</strong> 1234567890</p>
        <p><strong>Atas Nama:</strong> Agus</p>

        <form action="checkout.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3 text-center">
                <h4>Total Belanja: Rp. <?php echo number_format($totalPrice, 2); ?></h4>
            </div>

            <div class="mb-3">
                <label for="payment_proof" class="form-label">Unggah Bukti Pembayaran</label>
                <input type="file" name="payment_proof" id="payment_proof" class="form-control" required>
            </div>

            <button type="submit" name="confirm_payment" class="btn btn-success w-100">Konfirmasi Pembayaran</button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
