<?php
session_start();
include "config.php";

$products = mysqli_query($conn, "SELECT * FROM products ORDER BY product_name ASC");

// Count per category for filter badge numbers
$counts = ['All' => 0, 'Eyeglasses' => 0, 'Sunglasses' => 0, 'Reading Glasses' => 0, 'Accessories' => 0, 'Contact Lenses' => 0];
$all_products = [];
while ($row = mysqli_fetch_assoc($products)) {
    $all_products[] = $row;
    $counts['All']++;
    $cat = trim($row['category']);
    if (isset($counts[$cat])) {
        $counts[$cat]++;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Shop — Optical Shop</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="shop.css">
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
            <a href="shop.php"class="nav-link active">Shop</a>
                   
        <div class="back-btn-wrap">
        <a href="customer_dashboard.php" class="btn-back">← Back to Dashboard</a>
        </div>
            

        </nav>
    </div>
</header>

<!-- ============ PAGE HEADER ============ -->
<div class="shop-header">
    <h1>Our <span>Collection</span></h1>
    <p>Browse our full range of eyewear and accessories</p>
</div>

<!-- ============ MAIN CONTENT ============ -->
<div class="shop-container">

    <!-- Filter Bar -->
    <div class="filter-bar">
        <span class="filter-label">Filter by:</span>

        <button class="filter-btn active" onclick="filterProducts('All', this)">
            🛍️ All
            <span class="filter-count"><?php echo $counts['All']; ?></span>
        </button>

        <button class="filter-btn" onclick="filterProducts('Eyeglasses', this)">
            👓 Eyeglasses
            <span class="filter-count"><?php echo $counts['Eyeglasses']; ?></span>
        </button>

        <button class="filter-btn" onclick="filterProducts('Sunglasses', this)">
            🕶️ Sunglasses
            <span class="filter-count"><?php echo $counts['Sunglasses']; ?></span>
        </button>

        <button class="filter-btn" onclick="filterProducts('Reading Glasses', this)">
            📖 Reading Glasses
            <span class="filter-count"><?php echo $counts['Reading Glasses']; ?></span>
        </button>

        <button class="filter-btn" onclick="filterProducts('Contact Lenses', this)">
            👁️ Contact Lenses
            <span class="filter-count"><?php echo $counts['Contact Lenses']; ?></span>
        </button>

        <button class="filter-btn" onclick="filterProducts('Accessories', this)">
            🎒 Accessories
            <span class="filter-count"><?php echo $counts['Accessories']; ?></span>
        </button>
    </div>

    <!-- Results Info -->
    <div class="results-info">
        <p class="results-count">Showing <strong id="visible-count"><?php echo $counts['All']; ?></strong> products</p>
    </div>

    <!-- Product Grid -->
    <div class="product-grid" id="product-grid">

        <?php foreach ($all_products as $row): ?>

        <div class="product-card" data-category="<?php echo htmlspecialchars(trim($row['category'])); ?>">

            <div class="product-card-img-wrap">
                <img
                    class="product-card-img"
                    src="images/<?php echo htmlspecialchars($row['image']); ?>"
                    alt="<?php echo htmlspecialchars($row['product_name']); ?>">
                <span class="product-category-badge">
                    <?php echo htmlspecialchars($row['category']); ?>
                </span>
            </div>

            <div class="product-card-body">
                <h3><?php echo htmlspecialchars($row['product_name']); ?></h3>
                <p class="product-meta">Brand: <span><?php echo htmlspecialchars($row['brand']); ?></span></p>
                <p class="product-price">₱<?php echo number_format($row['price'], 2); ?></p>
                <p class="product-stock <?php echo $row['stock'] <= 5 ? 'low' : 'good'; ?>">
                    <?php echo $row['stock'] <= 5 ? '⚠️ Only ' . $row['stock'] . ' left' : '✅ In Stock (' . $row['stock'] . ')'; ?>
                </p>
                <a href="product_details.php?id=<?php echo $row['product_id']; ?>" class="btn-view">
                    View Product
                </a>
            </div>

        </div>

        <?php endforeach; ?>

    </div>

    <!-- Empty State -->
    <div class="empty-state" id="empty-state">
        <div class="empty-icon">🔍</div>
        <h3>No products found</h3>
        <p>No items in this category yet. Try a different filter.</p>
    </div>

    

</div>

<!-- ============ PRE-FOOTER ============ -->
<section class="pre-footer">
    <div class="pre-footer-content">
        <div class="pre-footer-column">
            <h4>Products</h4>
            <ul>
                <li>Eyeglasses</li>
                <li>Sunglasses</li>
                <li>Reading Glasses</li>
                <li>Contact Lenses</li>
                <li>Accessories</li>
            </ul>
        </div>
        <div class="pre-footer-column">
            <h4>Information</h4>
            <ul>
                <li>Featured Products</li>
                <li>Optical Guide</li>
            </ul>
        </div>
        <div class="pre-footer-column">
            <h4>Help</h4>
            <ul>
                <li><a href="bookanappointment.php" style="color:inherit;text-decoration:none;">Book an Appointment</a></li>
                <li><a href="bookanappointment.php" style="color:inherit;text-decoration:none;">Repair with Us</a></li>
                <li>About Us</li>
                <li>Contact Us</li>
            </ul>
        </div>
        <div class="pre-footer-column">
            <h4>Follow Us</h4>
            <div class="social-links">
                <a href="#" aria-label="Facebook">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M22 12.07C22 6.55 17.5 2 12 2S2 6.55 2 12.07c0 5 3.66 9.13 8.44 9.93v-7.03H7.9v-2.9h2.54V9.41c0-2.5 1.49-3.89 3.77-3.89 1.09 0 2.23.2 2.23.2v2.45h-1.25c-1.23 0-1.62.77-1.62 1.56v1.87h2.77l-.44 2.9h-2.33v7.03C18.34 21.2 22 17.08 22 12.07z"/></svg>
                </a>
                <a href="#" aria-label="Instagram">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2.5" y="2.5" width="19" height="19" rx="5"/><path d="M16.5 11.99a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0z"/><path d="M18.5 5.5h.01"/></svg>
                </a>
                <a href="#" aria-label="Messenger">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M2 12c0-5.5 4.5-10 10-10s10 4.5 10 10-4.5 10-10 10c-.5 0-1-.05-1.5-.14L2 22V12z"/><path d="M7 9l4.5 3L17 9l-4.5 5L7 12v-3z" fill="#fff"/></svg>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- ============ FOOTER ============ -->
<footer>
    <p>© 2026 Optical Management System</p>
</footer>

<script>
function filterProducts(category, btn) {
    // Update active button
    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    const cards = document.querySelectorAll('.product-card');
    let visible = 0;

    cards.forEach(card => {
        const cardCategory = (card.dataset.category || '').trim();

        // Simple exact match — no substring tricks
        const match = category === 'All'
            || cardCategory.toLowerCase() === category.toLowerCase();

        card.classList.toggle('hidden', !match);
        if (match) visible++;
    });

    // Update count display
    document.getElementById('visible-count').textContent = visible;

    // Show/hide empty state
    document.getElementById('empty-state').classList.toggle('show', visible === 0);
}
</script>

</body>
</html>