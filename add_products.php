<?php
session_start();
include "config.php";

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    header("Location: login.php");
    exit();
}

$error = '';

if(isset($_POST['submit'])){

    $name     = mysqli_real_escape_string($conn, trim($_POST['product_name']));
    $price    = mysqli_real_escape_string($conn, trim($_POST['price']));
    $stock    = mysqli_real_escape_string($conn, trim($_POST['stock']));
    $category = mysqli_real_escape_string($conn, trim($_POST['category']));
    $brand    = mysqli_real_escape_string($conn, trim($_POST['brand']));
    $image_name = '';

    if(isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK){
        $uploadDir = __DIR__ . '/images/';
        $fileTmp   = $_FILES['image']['tmp_name'];
        $fileName  = basename($_FILES['image']['name']);
        $fileExt   = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowed   = ['jpg','jpeg','png','gif','webp'];

        if(!in_array($fileExt, $allowed)){
            $error = "Only JPG, JPEG, PNG, GIF, and WEBP image files are allowed.";
        } else {
            $safeName   = preg_replace('/[^A-Za-z0-9._-]/', '', $fileName);
            $image_name = time() . '_' . $safeName;

            if(!move_uploaded_file($fileTmp, $uploadDir . $image_name)){
                $error = "Failed to upload image file. Please try again.";
            }
        }
    } else {
        $error = "Please choose an image file for the product.";
    }

    if(empty($error)){
        mysqli_query($conn,
            "INSERT INTO products (product_name, price, stock, category, brand, image)
             VALUES ('$name','$price','$stock','$category','$brand','$image_name')");

        header("Location: products.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add Product — Admin</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="add_products.css">
</head>
<body>

<!-- ============ PAGE HEADER ============ -->
<div class="page-header">
    <h1>Add <span>Product</span></h1>
    <p>Fill in the details to add a new product to the inventory</p>
</div>

<!-- ============ FORM CARD ============ -->
<div class="form-container">
    <div class="form-card">

        <?php if(!empty($error)): ?>
            <div class="error-message">⚠️ <?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">

            <div class="form-group">
                <label>Product Name</label>
                <input
                    type="text"
                    name="product_name"
                    placeholder="e.g. Ray-Ban Aviator Classic"
                    required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Price (₱)</label>
                    <input
                        type="number"
                        step="0.01"
                        name="price"
                        placeholder="e.g. 2500.00"
                        required>
                </div>
                <div class="form-group">
                    <label>Stock</label>
                    <input
                        type="number"
                        name="stock"
                        placeholder="e.g. 20"
                        required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Category</label>
                    <select name="category" required>
                        <option value="" disabled selected>Select category</option>
                        <option value="Eyeglasses">Eyeglasses</option>
                        <option value="Sunglasses">Sunglasses</option>
                        <option value="Reading Glasses">Reading Glasses</option>
                        <option value="Contact Lenses">Contact Lenses</option>
                        <option value="Accessories">Accessories</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Brand</label>
                    <input
                        type="text"
                        name="brand"
                        placeholder="e.g. Ray-Ban"
                        required>
                </div>
            </div>

            <div class="form-group">
                <label>Product Image</label>
                <div class="file-wrap">
                    <input
                        type="file"
                        name="image"
                        id="image"
                        accept="image/*"
                        required
                        onchange="previewImage(event)">
                    <label for="image" class="file-label">📁 Choose Image</label>
                </div>
                <img id="img-preview" class="img-preview" src="" alt="Preview" style="display:none;">
            </div>

            <div class="btn-group">
                <button type="submit" name="submit" class="btn-submit">
                    ✅ Add Product
                </button>
                <a href="products.php" class="btn-back">
                    ← Back to Products
                </a>
            </div>

        </form>

    </div>
</div>

<!-- ============ FOOTER ============ -->
<footer>
    <p>© 2026 Optical Management System — Admin Panel</p>
</footer>

<script>
function previewImage(event) {
    const preview = document.getElementById('img-preview');
    const file = event.target.files[0];
    if (file) {
        preview.src = URL.createObjectURL(file);
        preview.style.display = 'block';
    }
}
</script>

</body>
</html>