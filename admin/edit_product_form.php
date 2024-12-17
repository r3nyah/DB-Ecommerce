<?php
include 'config.php'; // Menyertakan file koneksi

if (!isset($_SESSION['admin'])) {
    // Jika belum login, arahkan ke halaman login
    header("Location: login_form.php");
    exit();
}

// Pastikan ada ID produk yang dikirimkan melalui URL
if (!isset($_GET['id'])) {
    header("Location: products.php?error=ProductIDMissing");
    exit();
}

$productId = $_GET['id'];

// Ambil data produk berdasarkan ID
$productQuery = "SELECT * FROM product WHERE product_id = '$productId'";
$productResult = mysqli_query($conn, $productQuery);

if (mysqli_num_rows($productResult) == 0) {
    header("Location: products.php?error=ProductNotFound");
    exit();
}

$product = mysqli_fetch_assoc($productResult);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Edit Product</h2>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="edit_product.php" method="POST" enctype="multipart/form-data">
            <!-- Input untuk ID Produk -->
            <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">

            <div class="mb-3">
                <label for="product_name" class="form-label">Product Name</label>
                <input type="text" class="form-control" id="product_name" name="product_name" value="<?php echo $product['product_name']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="category_id" class="form-label">Category</label>
                <select class="form-control" id="category_id" name="category_id">
                    <option value="">Select a category</option>
                    <?php
                    // Ambil daftar kategori dari database
                    $categoryQuery = "SELECT * FROM category";
                    $categoryResult = mysqli_query($conn, $categoryQuery);
                    while ($row = mysqli_fetch_assoc($categoryResult)) {
                        $selected = $product['category_id'] == $row['category_id'] ? 'selected' : '';
                        echo '<option value="' . $row['category_id'] . '" ' . $selected . '>' . $row['category_name'] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="new_category" class="form-label">Or Add New Category</label>
                <input type="text" class="form-control" id="new_category" name="new_category" placeholder="Enter new category (optional)">
            </div>
            <div class="mb-3">
                <label for="product_image" class="form-label">Product Image</label>
                <input type="file" class="form-control" id="product_image" name="product_image" accept="image/*">
                <small class="text-muted">Leave empty to keep the current image.</small>
                <?php if (!empty($product['product_image'])): ?>
                    <div class="mt-2">
                        <img src="<?php echo $product['product_image']; ?>" alt="Product Image" style="max-width: 200px;">
                    </div>
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <label for="product_description">Description:</label>
                <textarea id="product_description" name="product_description" rows="4" class="form-control" required><?php echo $product['product_description']; ?></textarea>
            </div>
            <div class="mb-3">
                <label for="product_price" class="form-label">Price</label>
                <input type="number" class="form-control" id="product_price" name="product_price" value="<?php echo $product['product_price']; ?>" min="0" required>
            </div>
            <div class="mb-3">
                <label for="product_stock" class="form-label">Stock</label>
                <input type="number" class="form-control" id="product_stock" name="product_stock" value="<?php echo $product['product_stock']; ?>" min="0" required>
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="products.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>
