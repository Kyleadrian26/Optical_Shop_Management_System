<?php
include "config.php";

$result = mysqli_query($conn, "SELECT * FROM products ORDER BY product_name ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Products — Admin</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="products.css">
</head>
<body>

<!-- ============ PAGE HEADER ============ -->
<div class="page-header">
    <h1>Product <span>List</span></h1>
    <p>Manage all products in your inventory</p>
</div>

<!-- ============ MAIN CONTENT ============ -->
<div class="admin-container">

    <!-- Top Bar -->
    <div class="top-bar">
        <a href="admin_dashboard.php" class="btn-back">← Back to Dashboard</a>
        <a href="add_products.php"    class="btn-add">+ Add Product</a>
    </div>

    <!-- Product Count -->
    <p class="product-count">
        Total Products: <strong><?php echo mysqli_num_rows($result); ?></strong>
    </p>

    <!-- Table -->
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th>Brand</th>
                    <th>Price</th>
                    <th>Stock</th>
                </tr>
            </thead>
            <tbody>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td class="td-id"><?php echo htmlspecialchars($row['product_id']); ?></td>
                    <td class="td-img">
                        <img
                            src="images/<?php echo htmlspecialchars($row['image']); ?>"
                            alt="<?php echo htmlspecialchars($row['product_name']); ?>"
                            onerror="this.src='bg.png';">
                    </td>
                    <td class="td-name"><?php echo htmlspecialchars($row['product_name']); ?></td>
                    <td><span class="badge"><?php echo htmlspecialchars($row['category']); ?></span></td>
                    <td><?php echo htmlspecialchars($row['brand']); ?></td>
                    <td class="td-price">₱<?php echo number_format($row['price'], 2); ?></td>
                    <td class="td-stock <?php echo $row['stock'] <= 5 ? 'low' : 'good'; ?>">
                        <?php echo $row['stock'] <= 5
                            ? '⚠️ ' . $row['stock']
                            : '✅ ' . $row['stock']; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</div>

<!-- ============ FOOTER ============ -->
<footer>
    <p>© 2026 Optical Management System — Admin Panel</p>
</footer>

</body>
</html>