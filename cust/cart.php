<?php
session_start();
include 'config.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['customer_id'])) {
    header("Location: loginform.php");
    exit;
}

$customer_id = $_SESSION['customer_id'];

// Query untuk menampilkan produk yang ada di keranjang
$queryCart = "SELECT ci.cart_item_id, ci.quantity, ci.total_price, p.product_name, p.product_price, p.product_image
              FROM cart_items ci
              JOIN product p ON ci.product_id = p.product_id
              JOIN cart c ON ci.cart_id = c.cart_id
              WHERE c.customer_id = '$customer_id'";
$resultCart = mysqli_query($conn, $queryCart);

// Handle hapus item
if (isset($_POST['remove_item_id'])) {
    $cart_item_id = intval($_POST['remove_item_id']);

    // Hapus item dari tabel cart_items
    $queryDelete = "DELETE FROM cart_items WHERE cart_item_id = '$cart_item_id'";
    if (mysqli_query($conn, $queryDelete)) {
        $_SESSION['alert'] = "Item berhasil dihapus dari keranjang.";
    } else {
        $_SESSION['alert'] = "Gagal menghapus item. Coba lagi.";
    }

    // Redirect untuk mencegah pengiriman ulang form
    header("Location: cart.php");
    exit;
}

// Menghitung total harga produk
$totalPrice = 0;
if (mysqli_num_rows($resultCart) > 0) {
    while ($item = mysqli_fetch_assoc($resultCart)) {
        $totalPrice += $item['total_price'];
    }
}

// Reset pointer result untuk rendering ulang tabel
mysqli_data_seek($resultCart, 0);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart | MikroTik E-Commerce</title>
    <link href="style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        .cart-table {
            margin-top: 20px;
        }
        .cart-table td, .cart-table th {
            vertical-align: middle;
        }
        .cart-footer {
            margin-top: 20px;
        }
    </style>
</head>
<body>

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
                            <a class="nav-link active" aria-current="page" href="index.php">Home</a>
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


    <div class="container">
        <h2 class="my-4">Your Shopping Cart</h2>

        <?php
        // Menampilkan alert jika ada
        if (isset($_SESSION['alert'])) {
            echo '<div class="alert alert-info text-center" role="alert">' . $_SESSION['alert'] . '</div>';
            unset($_SESSION['alert']);
        }
        ?>

        <?php if (mysqli_num_rows($resultCart) > 0): ?>
            <form action="cart.php" method="POST">
                <table class="table cart-table">
                    <thead>
                        <tr>
                            <th>Select</th>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total Price</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($item = mysqli_fetch_assoc($resultCart)): ?>
                            <tr>
                                <td>
                                    <input type="checkbox" name="selected_items[]" value="<?php echo $item['cart_item_id']; ?>">
                                </td>
                                <td>
                                    <img src="<?php echo $item['product_image']; ?>" alt="<?php echo $item['product_name']; ?>" width="50">
                                    <?php echo $item['product_name']; ?>
                                </td>
                                <td>Rp. <?php echo number_format($item['product_price'], 2); ?></td>
                                <td>
                                    <input type="number" name="quantity[<?php echo $item['cart_item_id']; ?>]" value="<?php echo $item['quantity']; ?>" min="1" class="form-control w-50">
                                </td>
                                <td>Rp. <?php echo number_format($item['total_price'], 2); ?></td>
                                <td>
                                    <!-- Tombol Hapus -->
                                    <form action="cart.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="remove_item_id" value="<?php echo $item['cart_item_id']; ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>

                <div class="cart-footer">
                    <h4>Total: Rp. <?php echo number_format($totalPrice, 2); ?></h4>
                    <button type="submit" class="btn btn-primary">Update Cart</button>
                    <button type="submit" formaction="checkout.php" class="btn btn-success">Checkout</button>
                </div>
            </form>
        <?php else: ?>
            <p>Your cart is empty. <a href="products.php">Shop now</a>.</p>
        <?php endif; ?>
    </div>
    
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
