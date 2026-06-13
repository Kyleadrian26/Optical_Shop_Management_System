<?php
session_start();
include "config.php";

if(!isset($_GET['id'])){
    die("Product not found!");
}

$product_id = mysqli_real_escape_string($conn, $_GET['id']);

$query = mysqli_query($conn,
"SELECT * FROM products WHERE product_id='$product_id'");

$product = mysqli_fetch_assoc($query);

if(!$product){
    die("Product not found!");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo htmlspecialchars($product['product_name']); ?> — Optical Shop</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="product_details.css">
</head>
<body>

<!-- ============ NAVBAR ============ -->
<header class="navbar">
    <div class="navbar-inner">
        <div class="navbar-brand">
            <img src="logo1.png" alt="Optical Shop Logo" class="nav-logo">
            <span class="nav-brand-name">Optical Shop</span>
        </div>
        <nav class="navbar-links">
            <a href="index.php"             class="nav-link">Home</a>
            <a href="shop.php"              class="nav-link active">Shop</a>
            <a href="index.php#about"       class="nav-link">About</a>
            <a href="index.php#contact"     class="nav-link">Contact</a>
            <a href="bookanappointment.php" class="nav-link">Book Appointment</a>
            <a href="login.php"             class="nav-btn-outline">Login</a>
        </nav>
    </div>
</header>

<!-- ============ PAGE HEADER ============ -->
<div class="page-header">
    <h1>Product <span>Details</span></h1>
    <p>View full information about this item</p>
</div>

<!-- ============ MAIN CONTENT ============ -->
<div class="details-container">

    <div class="product-card">

        <!-- Image Side -->
        <div class="product-image">
            <img
                src="images/<?php echo htmlspecialchars($product['image']); ?>"
                alt="<?php echo htmlspecialchars($product['product_name']); ?>"
                onerror="this.src='bg.png';">
            <span class="category-badge">
                <?php echo htmlspecialchars($product['category']); ?>
            </span>
        </div>

        <!-- Info Side -->
        <div class="product-info">

            <h1><?php echo htmlspecialchars($product['product_name']); ?></h1>

            <div class="meta-row">
                <span class="meta-label">Category</span>
                <span class="meta-value"><?php echo htmlspecialchars($product['category']); ?></span>
            </div>

            <div class="meta-row">
                <span class="meta-label">Brand</span>
                <span class="meta-value"><?php echo htmlspecialchars($product['brand']); ?></span>
            </div>

            <div class="price-block">
                <span class="price-label">Price</span>
                <span class="price">₱<?php echo number_format($product['price'], 2); ?></span>
            </div>

            <p class="stock <?php echo $product['stock'] <= 5 ? 'low' : 'good'; ?>">
                <?php echo $product['stock'] <= 5
                    ? '⚠️ Only ' . $product['stock'] . ' left in stock'
                    : '✅ In Stock (' . $product['stock'] . ' available)'; ?>
            </p>

            <form action="add_to_cart.php" method="POST">

                <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">

                <div class="quantity-block">
                    <label class="qty-label" for="quantity">Quantity</label>
                    <input
                        type="number"
                        id="quantity"
                        name="quantity"
                        value="1"
                        min="1"
                        max="<?php echo $product['stock']; ?>"
                        required>
                </div>

                <div class="btn-group">
                    <button type="submit" class="btn-cart">
                        🛒 Add to Cart
                    </button>
                    <a href="shop.php" class="btn-back">
                        ← Back to Shop
                    </a>
                </div>

            </form>

        </div>

    </div>

</div>

<!-- ============ FOOTER ============ -->
<footer>
    <p>© 2026 Optical Management System</p>
</footer>

</body>
</html>