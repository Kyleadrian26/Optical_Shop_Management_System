<?php
session_start();

if(!isset($_SESSION['role'])){
    header("Location: login.php");
    exit();
}

if($_SESSION['role'] != 'customer'){
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Customer Dashboard</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="style.css">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
    <div class="navbar-inner">
        <div class="navbar-brand">
            <img src="logo1.png" alt="Optical Shop" class="nav-logo">
            <span class="nav-brand-name">Optical Shop</span>
        </div>
        <div class="navbar-links">
            <a href="shop.php" class="nav-link">Shop</a>
            <a href="customer_orders.php" class="nav-link">Orders</a>
            <a href="cart.php" class="nav-link">
                <span class="cart-icon">🛒</span> Cart
            </a>
            <a href="profile.php" class="nav-avatar">
                <span><?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?></span>
            </a>
            <a href="logout.php" class="nav-logout">Logout</a>
        </div>
    </div>
</nav>

<!-- HERO SLIDER -->
<section class="hero-slider">
    <div class="slider-window">
        <div class="slider-track">
            <div class="slide"><img src="men.png" alt="Men Collection"></div>
            <div class="slide"><img src="Girl.png" alt="Women Collection"></div>
            <div class="slide"><img src="boy.png" alt="Boys Collection"></div>
            <div class="slide"><img src="lady.png" alt="Ladies Collection"></div>
        </div>

        <!-- Overlay text on slider -->
        <div class="slider-overlay">
            <p class="slider-tagline">New Arrivals 2026</p>
            <h2 class="slider-heading">See the World<br>in Style</h2>
            <a href="shop.php" class="slider-cta">Shop Now</a>
        </div>

        <!-- Prev / Next -->
        <button class="slider-btn slider-btn--prev" aria-label="Previous">&#8249;</button>
        <button class="slider-btn slider-btn--next" aria-label="Next">&#8250;</button>

        <!-- Dots -->
        <div class="slider-dots">
            <span class="dot active"></span>
            <span class="dot"></span>
            <span class="dot"></span>
            <span class="dot"></span>
        </div>
    </div>
</section>

<!-- WELCOME STRIP -->
<section class="welcome-strip">
    <div class="welcome-inner">
        <div class="welcome-text">
            <p class="welcome-label">Welcome back</p>
            <h1 class="welcome-name"><?php echo htmlspecialchars($_SESSION['username']); ?> 👋</h1>
            <p class="welcome-sub">Your optical journey starts here. Browse eyewear, track orders, and manage your profile with ease.</p>
        </div>
        <div class="welcome-stats">
            <div class="stat-pill">🕶️ <span>Eyewear Experts</span></div>
            <div class="stat-pill">🚚 <span>Free Delivery</span></div>
            <div class="stat-pill">✅ <span>Trusted Quality</span></div>
        </div>
    </div>
</section>

<!-- QUICK ACCESS CARDS -->
<section class="quick-access">
    <div class="section-inner">
        <h2 class="section-title">Quick Access</h2>
        <div class="qa-grid">
            <a href="shop.php" class="qa-card">
                <div class="qa-icon">🕶️</div>
                <div class="qa-info">
                    <span class="qa-title">Browse Products</span>
                    <span class="qa-desc">Explore our full collection</span>
                </div>
                <span class="qa-arrow">→</span>
            </a>
            <a href="customer_orders.php" class="qa-card">
                <div class="qa-icon">📦</div>
                <div class="qa-info">
                    <span class="qa-title">My Orders</span>
                    <span class="qa-desc">Track & view past orders</span>
                </div>
                <span class="qa-arrow">→</span>
            </a>
            <a href="cart.php" class="qa-card">
                <div class="qa-icon">🛒</div>
                <div class="qa-info">
                    <span class="qa-title">My Cart</span>
                    <span class="qa-desc">Review items & checkout</span>
                </div>
                <span class="qa-arrow">→</span>
            </a>
            <a href="profile.php" class="qa-card">
                <div class="qa-icon">👤</div>
                <div class="qa-info">
                    <span class="qa-title">My Profile</span>
                    <span class="qa-desc">Manage your account</span>
                </div>
                <span class="qa-arrow">→</span>
            </a>
        </div>
    </div>
</section>

<!-- PRE-FOOTER -->
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
                <li>Book an Appointment</li>
                <li>Repair with us</li>
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

<footer>
    <p>© 2026 Optical Management System</p>
</footer>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const track = document.querySelector('.slider-track');
    const slides = Array.from(document.querySelectorAll('.slide'));
    const prevBtn = document.querySelector('.slider-btn--prev');
    const nextBtn = document.querySelector('.slider-btn--next');
    const dots = Array.from(document.querySelectorAll('.dot'));
    let index = 0;
    let autoplay;

    const goTo = (i) => {
        index = (i + slides.length) % slides.length;
        track.style.transform = `translateX(${index * -100}%)`;
        dots.forEach(d => d.classList.remove('active'));
        dots[index].classList.add('active');
    };

    const startAutoplay = () => {
        autoplay = setInterval(() => goTo(index + 1), 3500);
    };

    const resetAutoplay = () => {
        clearInterval(autoplay);
        startAutoplay();
    };

    prevBtn.addEventListener('click', () => { goTo(index - 1); resetAutoplay(); });
    nextBtn.addEventListener('click', () => { goTo(index + 1); resetAutoplay(); });
    dots.forEach((dot, i) => dot.addEventListener('click', () => { goTo(i); resetAutoplay(); }));

    startAutoplay();
});
</script>

</body>
</html>