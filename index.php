<?php
session_start();
include "config.php";

$featuredImages = [
    '1780721559_SunGlassesBlack.png',
    '1780721603_SunGlassesBlue.png',
    '1780721669_SunGlassesGreen.png',
    'Casual Black.png',
    'Casual Blue.png',
    'Casual Green.png',
    '1780724968_ReadingGlassesBlack.png',
    '1780725000_ReadingGlassesGold.png',
    '1780725024_ReadingGlassesSilver.png',
    '1780724770_LeatherCaseBlack.png',
    '1780724896_NeckStrapBlack.png',
    '1780724735_CaseBrown.png'
];

$featuredPrices = [];

$query = mysqli_query($conn, "SELECT image, price FROM products WHERE image IN ('" . implode("','", array_map(function($name) use ($conn) { return mysqli_real_escape_string($conn, $name); }, $featuredImages)) . "')");
if($query) {
    while($row = mysqli_fetch_assoc($query)) {
        $featuredPrices[$row['image']] = $row['price'];
    }
}

function getFeaturedPrice($image, $prices) {
    $fallbackPrices = [
        'Casual Black.png' => 4999.00,
        'Casual Blue.png'  => 4999.00,
        'Casual Green.png' => 4999.00,
    ];
    if (isset($prices[$image]))         return '₱' . number_format($prices[$image], 2);
    if (isset($fallbackPrices[$image])) return '₱' . number_format($fallbackPrices[$image], 2);
    return '₱0.00';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Optical Shop — See The World Clearly</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="home.css">
</head>
<body>

<!-- ============ HEADER / NAVBAR ============ -->
<header class="navbar">
    <div class="navbar-inner">
        <div class="navbar-brand">
            <img src="logo1.png" alt="Optical Shop Logo" class="nav-logo">
            <span class="nav-brand-name">Optical Shop</span>
        </div>
        <nav class="navbar-links">
            <a href="index.php"                 class="nav-link active">Home</a>
            <a href="login.php"                 class="nav-link">Shop</a>
            <a href="#about"                    class="nav-link">About</a>
            <a href="#contact"                  class="nav-link">Contact</a>
            <a href="bookanappointment.php"     class="nav-link">Book Appointment</a>
            <a href="login.php"                 class="nav-btn-outline">Login</a>
            <a href="signup.php"                class="nav-btn-fill">Sign Up</a>
        </nav>
    </div>
</header>
    
<!-- Updated the Shop put in the home page for customer to see -->
<!-- ============ SHOP ============ -->
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

<!-- ============ HERO ============ -->
<section class="hero">
    <div class="hero-inner">
        <div class="hero-left">
            <p class="hero-eyebrow">New Collection 2026</p>
            <div class="hero-heading">
                <img class="hero-logo" src="logo1.png" alt="Logo">
                <h1>See The World<br><span>Clearly</span></h1>
            </div>
            <p class="hero-sub">Quality Eyewear, Contact Lenses,<br>and Vision Solutions</p>
            <div class="hero-buttons">
                <a href="login.php"                class="btn-primary">Shop Now</a>
                <a href="bookanappointment.php"    class="btn-outline">Book Appointment</a>
            </div>
        </div>

        <div class="hero-right">
            <div class="hero-product">
                <img src="sunglasses.png" alt="Sunglasses">
                <h4>Sunglasses</h4>
            </div>
            <div class="hero-product">
                <img src="rglasses.png" alt="Reading Glasses">
                <h4>Reading Glasses</h4>
            </div>
            <div class="hero-product">
                <img src="contact lens.png" alt="Contact Lens">
                <h4>Contact Lens</h4>
            </div>
            <div class="hero-product">
                <img src="AC.png" alt="Accessories">
                <h4>Accessories</h4>
            </div>
        </div>
    </div>
</section>

<!-- ============ TRUST BAR ============ -->
<div class="trust-bar">
    <div class="trust-inner">
        <div class="trust-item">🚚 <span>Free Delivery on Orders ₱2,000+</span></div>
        <div class="trust-item">✅ <span>Certified Optical Quality</span></div>
        <div class="trust-item">🔄 <span>Easy Returns & Exchange</span></div>
        <div class="trust-item">👓 <span>1,000+ Happy Customers</span></div>
    </div>
</div>

<!-- ============ FEATURED PRODUCTS ============ -->
<section class="featured">
    <div class="section-inner">
        <div class="section-header">
            <h2 class="section-title">Featured Products</h2>
            <p class="section-sub">Handpicked styles for every look and lifestyle</p>
        </div>

        <div class="product-container">

            <div class="product-card">
                <div class="product-card-top">
                    <span class="product-badge">New</span>
                    <h3>Sunglasses</h3>
                    <p class="card-intro">Bold sunglass styles with edge and UV protection.</p>
                </div>
                <div class="product-gallery">
                    <div class="product-item">
                        <img src="images/1780721559_SunGlassesBlack.png" alt="Black Sunglasses">
                        <span>Black Shade<br><?php echo getFeaturedPrice('1780721559_SunGlassesBlack.png', $featuredPrices); ?></span>
                    </div>
                    <div class="product-item">
                        <img src="images/1780721603_SunGlassesBlue.png" alt="Blue Sunglasses">
                        <span>Blue Lens<br><?php echo getFeaturedPrice('1780721603_SunGlassesBlue.png', $featuredPrices); ?></span>
                    </div>
                    <div class="product-item">
                        <img src="images/1780721669_SunGlassesGreen.png" alt="Green Sunglasses">
                        <span>Green Tint<br><?php echo getFeaturedPrice('1780721669_SunGlassesGreen.png', $featuredPrices); ?></span>
                    </div>
                </div>
                <a href="login.php" class="card-cta">View Collection →</a>
            </div>

            <div class="product-card">
                <div class="product-card-top">
                    <span class="product-badge">Popular</span>
                    <h3>Eyeglasses</h3>
                    <p class="card-intro">Elegant frames for everyday wear and clarity.</p>
                </div>
                <div class="product-gallery">
                    <div class="product-item">
                        <img src="images/Casual Black.png" alt="Black Eyeglasses">
                        <span>Black Frame<br><?php echo getFeaturedPrice('Casual Black.png', $featuredPrices); ?></span>
                    </div>
                    <div class="product-item">
                        <img src="images/Casual Blue.png" alt="Blue Eyeglasses">
                        <span>Blue Accent<br><?php echo getFeaturedPrice('Casual Blue.png', $featuredPrices); ?></span>
                    </div>
                    <div class="product-item">
                        <img src="images/Casual Green.png" alt="Green Eyeglasses">
                        <span>Green Frame<br><?php echo getFeaturedPrice('Casual Green.png', $featuredPrices); ?></span>
                    </div>
                </div>
                <a href="login.php" class="card-cta">View Collection →</a>
            </div>

            <div class="product-card">
                <div class="product-card-top">
                    <span class="product-badge">Trending</span>
                    <h3>Reading Glasses</h3>
                    <p class="card-intro">Comfortable reading frames with polished detail.</p>
                </div>
                <div class="product-gallery">
                    <div class="product-item">
                        <img src="images/1780724968_ReadingGlassesBlack.png" alt="Black Reading">
                        <span>Black<br><?php echo getFeaturedPrice('1780724968_ReadingGlassesBlack.png', $featuredPrices); ?></span>
                    </div>
                    <div class="product-item">
                        <img src="images/1780725000_ReadingGlassesGold.png" alt="Gold Reading">
                        <span>Gold<br><?php echo getFeaturedPrice('1780725000_ReadingGlassesGold.png', $featuredPrices); ?></span>
                    </div>
                    <div class="product-item">
                        <img src="images/1780725024_ReadingGlassesSilver.png" alt="Silver Reading">
                        <span>Silver<br><?php echo getFeaturedPrice('1780725024_ReadingGlassesSilver.png', $featuredPrices); ?></span>
                    </div>
                </div>
                <a href="login.php" class="card-cta">View Collection →</a>
            </div>

            <div class="product-card">
                <div class="product-card-top">
                    <span class="product-badge">Must Have</span>
                    <h3>Accessories</h3>
                    <p class="card-intro">Top eyewear accessories for travel and care.</p>
                </div>
                <div class="product-gallery">
                    <div class="product-item">
                        <img src="images/1780724770_LeatherCaseBlack.png" alt="Leather Case">
                        <span>Leather Case<br><?php echo getFeaturedPrice('1780724770_LeatherCaseBlack.png', $featuredPrices); ?></span>
                    </div>
                    <div class="product-item">
                        <img src="images/1780724896_NeckStrapBlack.png" alt="Neck Strap">
                        <span>Neck Strap<br><?php echo getFeaturedPrice('1780724896_NeckStrapBlack.png', $featuredPrices); ?></span>
                    </div>
                    <div class="product-item">
                        <img src="images/1780724735_CaseBrown.png" alt="Brown Case">
                        <span>Brown Case<br><?php echo getFeaturedPrice('1780724735_CaseBrown.png', $featuredPrices); ?></span>
                    </div>
                </div>
                <a href="login.php" class="card-cta">View Collection →</a>
            </div>

        </div>
    </div>
</section>

<!-- ============ OPTICAL GUIDE ============ -->
<section class="optical-guide">
    <div class="section-inner">
        <div class="section-header">
            <h2 class="section-title">Optical Guide</h2>
            <p class="section-sub">Everything you need to know about eyewear</p>
        </div>
        <div class="guide-grid">
            <div class="guide-card">
                <div class="guide-icon">👓</div>
                <h4>What Are Eyeglasses?</h4>
                <p>Eyeglasses are corrective or protective lenses mounted in frames that sit on the face. They correct refractive errors like nearsightedness, farsightedness, and astigmatism using lenses shaped to focus light properly onto the retina.</p>
            </div>
            <div class="guide-card">
                <div class="guide-icon">🔬</div>
                <h4>Types of Lenses</h4>
                <p>Common lens types include single-vision, bifocal, and progressive. Lenses are also available in materials like plastic, polycarbonate, and high-index for thinner profiles.</p>
            </div>
            <div class="guide-card">
                <div class="guide-icon">✨</div>
                <h4>Lens Coatings & Treatments</h4>
                <p>Anti-reflective coatings reduce glare, scratch-resistant coatings improve durability, and UV coatings protect eyes from harmful ultraviolet light. Blue-light filtering is also available for screen use.</p>
            </div>
            <div class="guide-card">
                <div class="guide-icon">🖼️</div>
                <h4>Choosing Frames</h4>
                <p>Choose frames that fit your face shape and lifestyle. Consider frame size, bridge width, and temple length. Lightweight materials like TR90 or titanium are great for all-day wear.</p>
            </div>
            <div class="guide-card">
                <div class="guide-icon">👁️</div>
                <h4>Contact Lenses Basics</h4>
                <p>Contact lenses sit directly on the eye and can correct many of the same vision problems as glasses. They require proper fitting, cleaning, and regular replacement to maintain eye health.</p>
            </div>
            <div class="guide-card">
                <div class="guide-icon">🧼</div>
                <h4>Care & Maintenance</h4>
                <p>Clean lenses with appropriate cleaner, store contacts in fresh solution, and avoid placing glasses lens-down. Schedule regular eye exams to keep your prescription up to date.</p>
            </div>
        </div>
    </div>
</section>

<!-- ============ ABOUT ============ -->
<section id="about" class="about-section">
    <div class="section-inner">
        <div class="section-header">
            <h2 class="section-title">Why Choose Us?</h2>
            <p class="section-sub">At our Optical Shop, we believe clear vision and great style should go hand in hand.</p>
        </div>

        <div class="about-intro-text">
            <p>Our goal is to provide high-quality eyewear that helps customers see better, feel confident, and express their unique personality. Whether you're looking for prescription eyeglasses, reading glasses, fashionable sunglasses, or essential eyewear accessories, we are committed to offering products that combine comfort, durability, and style.</p>
        </div>

        <div class="about-grid">
            <div class="about-card">
                <div class="about-icon">🎯</div>
                <h4>Our Mission</h4>
                <p>To provide affordable, high-quality eyewear and accessories that improve our customers' vision, comfort, and everyday lives while delivering excellent service and a convenient shopping experience.</p>
            </div>
            <div class="about-card">
                <div class="about-icon">🌟</div>
                <h4>Our Vision</h4>
                <p>To become a trusted optical destination known for quality products, customer satisfaction, and innovative eyewear solutions that help people see the world more clearly and confidently.</p>
            </div>
            <div class="about-card">
                <div class="about-icon">🛍️</div>
                <h4>What We Offer</h4>
                <ul>
                    <li>👓 Eyeglasses – Stylish and comfortable frames for everyday use.</li>
                    <li>📖 Reading Glasses – Clear vision solutions for reading tasks.</li>
                    <li>🕶️ Sunglasses – Fashionable UV-protective eyewear.</li>
                    <li>🎒 Accessories – Cases, cloths, and cleaning essentials.</li>
                </ul>
            </div>
            <div class="about-card">
                <div class="about-icon">🤝</div>
                <h4>Our Commitment</h4>
                <p>We are dedicated to helping every customer find the perfect eyewear. Through quality products, reliable service, and a customer-first approach, we make eye care simple, accessible, and enjoyable for everyone.</p>
            </div>
        </div>
    </div>
</section>

<!-- ============ CONTACT ============ -->
<section id="contact" class="contact-section">
    <div class="section-inner">
        <div class="section-header">
            <h2 class="section-title">We're Here to Help</h2>
            <p class="section-sub">Reach out to us anytime — we'd love to hear from you</p>
        </div>
        <div class="contact-box">
            <div class="contact-item">
                <span class="contact-icon">
                    <svg viewBox="0 0 24 24" fill="none"><path d="M4 5C4 3.89543 4.89543 3 6 3H18C19.1046 3 20 3.89543 20 5V19C20 20.1046 19.1046 21 18 21H6C4.89543 21 4 20.1046 4 19V5Z" stroke="#B8860B" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/><path d="M4 7L12 13L20 7" stroke="#B8860B" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </span>
                <div class="contact-content">
                    <h4>Email</h4>
                    <p>optical@gmail.com</p>
                </div>
            </div>
            <div class="contact-item">
                <span class="contact-icon">
                    <svg viewBox="0 0 24 24" fill="none"><path d="M22 16.92V20C22 20.5304 21.7893 21.0391 21.4142 21.4142C21.0391 21.7893 20.5304 22 20 22C12.6112 22 6 15.3888 6 8C6 7.46957 6.21071 6.96086 6.58579 6.58579C6.96086 6.21071 7.46957 6 8 6H11.09C11.6131 6 12.0902 6.2151 12.4142 6.58579L14.83 9.17C15.0706 9.40041 15.1708 9.74509 15.1033 10.0723L14.21 13.68C14.1293 14.057 13.8412 14.3663 13.47 14.49L11.11 15.18C11.03 15.204 10.948 15.217 10.866 15.217C10.5718 15.217 10.2903 15.069 10.071 14.805L8.96 13.32C8.76813 13.0784 8.50656 12.8924 8.205 12.781L7 12.22" stroke="#B8860B" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </span>
                <div class="contact-content">
                    <h4>Phone</h4>
                    <p>09123456789</p>
                </div>
            </div>
            <div class="contact-item">
                <span class="contact-icon">
                    <svg viewBox="0 0 24 24" fill="none"><path d="M3 10.5L12 3L21 10.5V20C21 20.5523 20.5523 21 20 21H15C14.4477 21 14 20.5523 14 20V15C14 14.4477 13.5523 14 13 14H11C10.4477 14 10 14.4477 10 15V20C10 20.5523 9.55228 21 9 21H4C3.44772 21 3 20.5523 3 20V10.5Z" stroke="#B8860B" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </span>
                <div class="contact-content">
                    <h4>Address</h4>
                    <p>123 Vision Street, Clearview City, Philippines</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============ PRE-FOOTER ============ -->
<section class="pre-footer">
    <div class="pre-footer-content">
        <div class="pre-footer-column">
            <h4>Products</h4>
            <ul>
                <li>Eyeglasses</li>
                <li>Sunglasses</li>
                <li>Reading Glasses</li>
                <li>Accessories</li>
            </ul>
        </div>
        <div class="pre-footer-column">
            <h4>Information</h4>
            <ul>
                <li>Feature Products</li>
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
                <a href="#" aria-label="TikTok">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M17.5 4.5h2v3.06c.4.16.78.35 1.12.58v-5.14h-3.12v1.5zm-2.82 0c-.08 2.2-1.75 3.82-3.96 3.82-.22 0-.43 0-.64-.03v3.2c.26.03.53.05.8.05 1.28 0 2.47-.43 3.44-1.15v4.06c0 2.17-1.78 3.94-3.97 3.94-2.19 0-3.97-1.77-3.97-3.94S8.99 9.5 11.18 9.5c.18 0 .35.01.52.03v3.2c-.2-.03-.4-.05-.61-.05-1.28 0-2.47.43-3.44 1.15v4.06c0 2.17 1.78 3.94 3.97 3.94 2.19 0 3.97-1.77 3.97-3.94V9.5h1.5V4.5h-1.5v0z"/></svg>
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
document.addEventListener('DOMContentLoaded', function () {
    // Scroll reveal
    const revealEls = document.querySelectorAll('.product-card, .guide-card, .about-card');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            const idx = Array.from(revealEls).indexOf(entry.target);
            if (entry.isIntersecting) {
                setTimeout(() => entry.target.classList.add('visible'), idx * 100);
            } else {
                entry.target.classList.remove('visible');
            }
        });
    }, { threshold: 0.15 });
    revealEls.forEach(el => observer.observe(el));

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(a => {
        a.addEventListener('click', e => {
            const target = document.querySelector(a.getAttribute('href'));
            if (target) { e.preventDefault(); target.scrollIntoView({ behavior: 'smooth' }); }
        });
    });
});
</script>

</body>
</html>
