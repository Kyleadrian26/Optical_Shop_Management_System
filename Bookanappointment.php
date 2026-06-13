<?php
session_start();
include "config.php";

$success = '';
$error   = '';

// ============ HANDLE APPOINTMENT SUBMISSION ============
if(isset($_POST['submit_appointment'])){
    $first_name      = mysqli_real_escape_string($conn, trim($_POST['first_name']));
    $last_name       = mysqli_real_escape_string($conn, trim($_POST['last_name']));
    $email           = mysqli_real_escape_string($conn, trim($_POST['email']));
    $phone           = mysqli_real_escape_string($conn, trim($_POST['phone']));
    $preferred_date  = mysqli_real_escape_string($conn, trim($_POST['date']));
    $preferred_time  = mysqli_real_escape_string($conn, trim($_POST['time']));
    $purpose         = mysqli_real_escape_string($conn, trim($_POST['purpose']));
    $notes           = mysqli_real_escape_string($conn, trim($_POST['notes']));

    mysqli_query($conn,
        "INSERT INTO appointments
            (first_name, last_name, email, phone, preferred_date, preferred_time, purpose, notes)
         VALUES
            ('$first_name','$last_name','$email','$phone','$preferred_date','$preferred_time','$purpose','$notes')"
    );
    $success = 'appointment';
}

// ============ HANDLE ORDER SUBMISSION ============
if(isset($_POST['submit_order'])){
    $first_name       = mysqli_real_escape_string($conn, trim($_POST['first_name']));
    $last_name        = mysqli_real_escape_string($conn, trim($_POST['last_name']));
    $email            = mysqli_real_escape_string($conn, trim($_POST['email']));
    $phone            = mysqli_real_escape_string($conn, trim($_POST['phone']));
    $eyewear_type     = mysqli_real_escape_string($conn, trim($_POST['eyewear_type'] ?? ''));
    $lens_type        = mysqli_real_escape_string($conn, trim($_POST['lens_type'] ?? ''));
    $frame_pref       = mysqli_real_escape_string($conn, trim($_POST['frame_pref'] ?? ''));
    $budget           = mysqli_real_escape_string($conn, trim($_POST['budget'] ?? ''));
    $has_prescription = mysqli_real_escape_string($conn, trim($_POST['has_prescription'] ?? ''));
    $notes            = mysqli_real_escape_string($conn, trim($_POST['notes'] ?? ''));

    mysqli_query($conn,
        "INSERT INTO order_requests
            (first_name, last_name, email, phone, eyewear_type, lens_type, frame_pref, budget, has_prescription, notes)
         VALUES
            ('$first_name','$last_name','$email','$phone','$eyewear_type','$lens_type','$frame_pref','$budget','$has_prescription','$notes')"
    );
    $success = 'order';
}

// ============ HANDLE REPAIR SUBMISSION ============
if(isset($_POST['submit_repair'])){
    $first_name          = mysqli_real_escape_string($conn, trim($_POST['first_name']));
    $last_name           = mysqli_real_escape_string($conn, trim($_POST['last_name']));
    $email               = mysqli_real_escape_string($conn, trim($_POST['email']));
    $phone               = mysqli_real_escape_string($conn, trim($_POST['phone']));
    $glasses_type        = mysqli_real_escape_string($conn, trim($_POST['glasses_type']));
    $repair_types        = isset($_POST['repair_type']) ? implode(', ', $_POST['repair_type']) : '';
    $repair_types        = mysqli_real_escape_string($conn, $repair_types);
    $dropoff_date        = mysqli_real_escape_string($conn, trim($_POST['dropoff_date'] ?? ''));
    $dropoff_time        = mysqli_real_escape_string($conn, trim($_POST['dropoff_time'] ?? ''));
    $damage_description  = mysqli_real_escape_string($conn, trim($_POST['damage_description'] ?? ''));

    $dropoff_date_val = !empty($dropoff_date) ? "'$dropoff_date'" : "NULL";

    mysqli_query($conn,
        "INSERT INTO repair_requests
            (first_name, last_name, email, phone, glasses_type, repair_types, dropoff_date, dropoff_time, damage_description)
         VALUES
            ('$first_name','$last_name','$email','$phone','$glasses_type','$repair_types',$dropoff_date_val,'$dropoff_time','$damage_description')"
    );
    $success = 'repair';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Book an Appointment — Optical Shop</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="bookanappointment.css">
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
            <a href="shop.php"              class="nav-link">Shop</a>
            <a href="index.php#about"       class="nav-link">About</a>
            <a href="index.php#contact"     class="nav-link">Contact</a>
            <a href="bookanappointment.php" class="nav-link active">Book Appointment</a>
            <a href="login.php"             class="nav-btn-outline">Login</a>
        </nav>
    </div>
</header>

<!-- ============ HERO ============ -->
<div class="booking-hero">
    <div>
        <h1>Book With <span>Optical Shop</span></h1>
        <p>Schedule an appointment, order custom glasses, or bring your eyewear in for repair — all in one place.</p>
    </div>
</div>

<!-- ============ TABS ============ -->
<div class="booking-tabs-wrapper">
    <div class="booking-tabs">
        <button class="tab-btn <?php echo ($success === 'appointment' || $success === '') ? 'active' : ''; ?>"
                onclick="switchTab('appointment', this)">
            <span class="tab-icon">📅</span> Book Appointment
        </button>
        <button class="tab-btn <?php echo $success === 'order' ? 'active' : ''; ?>"
                onclick="switchTab('order', this)">
            <span class="tab-icon">👓</span> Order Glasses
        </button>
        <button class="tab-btn <?php echo $success === 'repair' ? 'active' : ''; ?>"
                onclick="switchTab('repair', this)">
            <span class="tab-icon">🔧</span> Repair with Us
        </button>
    </div>
</div>

<!-- ============ APPOINTMENT SECTION ============ -->
<section id="sec-appointment"
    class="booking-section <?php echo ($success === 'appointment' || $success === '') ? 'active' : ''; ?>">
    <div class="section-inner">

        <?php if($success === 'appointment'): ?>
        <div class="success-banner show">
            ✅ &nbsp; Your appointment has successfully booked! We'll contact you shortly to confirm.
        </div>
        <?php endif; ?>

        <div class="form-card">
            <div class="form-card-header">
                <span class="badge">📅 Book an Appointment</span>
                <h2>Schedule a Visit</h2>
                <p>Book a visit with our opticians for an eye exam, consultation, or fitting.</p>
            </div>

            <form method="POST" action="bookanappointment.php">
                <div class="form-grid">
                    <div class="form-group">
                        <label>First Name <span class="req">*</span></label>
                        <input type="text" name="first_name" placeholder="e.g. Juan" required>
                    </div>
                    <div class="form-group">
                        <label>Last Name <span class="req">*</span></label>
                        <input type="text" name="last_name" placeholder="e.g. dela Cruz" required>
                    </div>
                    <div class="form-group">
                        <label>Email Address <span class="req">*</span></label>
                        <input type="email" name="email" placeholder="you@email.com" required>
                    </div>
                    <div class="form-group">
                        <label>Phone Number <span class="req">*</span></label>
                        <input type="tel" name="phone" placeholder="09XXXXXXXXX" required>
                    </div>
                    <div class="form-group">
                        <label>Preferred Date <span class="req">*</span></label>
                        <input type="date" name="date" required>
                    </div>
                    <div class="form-group">
                        <label>Preferred Time <span class="req">*</span></label>
                        <select name="time" required>
                            <option value="" disabled selected>Select a time</option>
                            <option>9:00 AM</option>
                            <option>10:00 AM</option>
                            <option>11:00 AM</option>
                            <option>1:00 PM</option>
                            <option>2:00 PM</option>
                            <option>3:00 PM</option>
                            <option>4:00 PM</option>
                        </select>
                    </div>
                    <div class="form-group full">
                        <label>Purpose of Visit <span class="req">*</span></label>
                        <select name="purpose" required>
                            <option value="" disabled selected>Select a purpose</option>
                            <option>Eye Exams</option>
                            <option>Prescription Check / Update</option>
                            <option>Eyeglass Fitting</option>
                            <option>Contact Lens Fitting</option>
                            <option>Frame Selection Assistance</option>
                            <option>Follow-up Consultation</option>
                            <option>Other</option>
                        </select>
                    </div>
                    <div class="form-group full">
                        <label>Additional Notes</label>
                        <textarea name="notes" placeholder="Any special concerns, existing prescriptions, or requests..."></textarea>
                    </div>
                </div>
                <hr class="form-divider">
                <div class="form-submit">
                    <p class="form-note">📞 We'll call or text you within 24 hours to confirm.</p>
                    <button type="submit" name="submit_appointment" class="btn-submit">
                        Confirm Appointment <span>→</span>
                    </button>
                </div>
            </form>
        </div>

    </div>
</section>

<!-- ============ ORDER GLASSES SECTION ============ -->
<section id="sec-order"
    class="booking-section <?php echo $success === 'order' ? 'active' : ''; ?>">
    <div class="section-inner">

        <?php if($success === 'order'): ?>
        <div class="success-banner show">
            ✅ &nbsp; Your order request has been received! We'll reach out to discuss details and pricing.
        </div>
        <?php endif; ?>

        <div class="form-card">
            <div class="form-card-header">
                <span class="badge">👓 Order Glasses</span>
                <h2>Order Custom Eyewear</h2>
                <p>Tell us what you're looking for and we'll help you find the perfect pair.</p>
            </div>

            <form method="POST" action="bookanappointment.php">
                <div class="form-grid">
                    <div class="form-group">
                        <label>First Name <span class="req">*</span></label>
                        <input type="text" name="first_name" placeholder="e.g. Juan" required>
                    </div>
                    <div class="form-group">
                        <label>Last Name <span class="req">*</span></label>
                        <input type="text" name="last_name" placeholder="e.g. dela Cruz" required>
                    </div>
                    <div class="form-group">
                        <label>Email Address <span class="req">*</span></label>
                        <input type="email" name="email" placeholder="you@email.com" required>
                    </div>
                    <div class="form-group">
                        <label>Phone Number <span class="req">*</span></label>
                        <input type="tel" name="phone" placeholder="09XXXXXXXXX" required>
                    </div>
                </div>

                <hr class="form-divider">

                <div class="form-group" style="margin-bottom: 20px;">
                    <label>Type of Eyewear <span class="req">*</span></label>
                    <div class="eyeglass-options">
                        <label class="eyeglass-card">
                            <input type="radio" name="eyewear_type" value="Eyeglasses" required>
                            <span class="eg-icon">👓</span>
                            <span class="eg-name">Eyeglasses</span>
                            <span class="eg-desc">Prescription frames for everyday use</span>
                        </label>
                        <label class="eyeglass-card">
                            <input type="radio" name="eyewear_type" value="Sunglasses">
                            <span class="eg-icon">🕶️</span>
                            <span class="eg-name">Sunglasses</span>
                            <span class="eg-desc">UV-protective fashion shades</span>
                        </label>
                        <label class="eyeglass-card">
                            <input type="radio" name="eyewear_type" value="Reading Glasses">
                            <span class="eg-icon">📖</span>
                            <span class="eg-name">Reading Glasses</span>
                            <span class="eg-desc">Magnifying lenses for close work</span>
                        </label>
                        <label class="eyeglass-card">
                            <input type="radio" name="eyewear_type" value="Contact Lenses">
                            <span class="eg-icon">👁️</span>
                            <span class="eg-name">Contact Lenses</span>
                            <span class="eg-desc">Daily, monthly, or toric lenses</span>
                        </label>
                        <label class="eyeglass-card">
                            <input type="radio" name="eyewear_type" value="Kids Eyeglasses">
                            <span class="eg-icon">🧒</span>
                            <span class="eg-name">Kids Glasses</span>
                            <span class="eg-desc">Durable frames for children</span>
                        </label>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label>Lens Type</label>
                        <select name="lens_type">
                            <option value="" disabled selected>Select lens type</option>
                            <option>Single Vision</option>
                            <option>Bifocal</option>
                            <option>Progressive (No-line Bifocal)</option>
                            <option>Blue Light Filtering</option>
                            <option>Photochromic (Transitions)</option>
                            <option>No Prescription (Plano)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Frame Preference</label>
                        <select name="frame_pref">
                            <option value="" disabled selected>Select frame style</option>
                            <option>Full Rim</option>
                            <option>Half Rim</option>
                            <option>Rimless</option>
                            <option>Cat-eye</option>
                            <option>Round</option>
                            <option>Rectangle / Square</option>
                            <option>Aviator</option>
                            <option>Sporty / Wraparound</option>
                            <option>No Preference</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Budget Range (₱)</label>
                        <select name="budget">
                            <option value="" disabled selected>Select budget</option>
                            <option>Under ₱1,000</option>
                            <option>₱1,000 – ₱2,500</option>
                            <option>₱2,500 – ₱5,000</option>
                            <option>₱5,000 – ₱10,000</option>
                            <option>Above ₱10,000</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Do you have a prescription?</label>
                        <select name="has_prescription">
                            <option value="" disabled selected>Select</option>
                            <option>Yes, I have a valid prescription</option>
                            <option>No, I need an eye exam first</option>
                            <option>Not sure / need consultation</option>
                        </select>
                    </div>
                    <div class="form-group full">
                        <label>Special Requests or Notes</label>
                        <textarea name="notes" placeholder="Preferred colors, specific brands, coating requests..."></textarea>
                    </div>
                </div>

                <hr class="form-divider">
                <div class="form-submit">
                    <p class="form-note">🛍️ We'll contact you to discuss final details, pricing, and pickup/delivery.</p>
                    <button type="submit" name="submit_order" class="btn-submit">
                        Submit Order Request <span>→</span>
                    </button>
                </div>
            </form>
        </div>

    </div>
</section>

<!-- ============ REPAIR SECTION ============ -->
<section id="sec-repair"
    class="booking-section <?php echo $success === 'repair' ? 'active' : ''; ?>">
    <div class="section-inner">

        <?php if($success === 'repair'): ?>
        <div class="success-banner show">
            ✅ &nbsp; Your repair request has been submitted! We'll get in touch to confirm the details.
        </div>
        <?php endif; ?>

        <div class="form-card">
            <div class="form-card-header">
                <span class="badge">🔧 Repair with Us</span>
                <h2>Eyewear Repair Service</h2>
                <p>Bring your damaged eyewear to us and we'll fix it up.</p>
            </div>

            <form method="POST" action="bookanappointment.php">
                <div class="form-grid">
                    <div class="form-group">
                        <label>First Name <span class="req">*</span></label>
                        <input type="text" name="first_name" placeholder="e.g. Juan" required>
                    </div>
                    <div class="form-group">
                        <label>Last Name <span class="req">*</span></label>
                        <input type="text" name="last_name" placeholder="e.g. dela Cruz" required>
                    </div>
                    <div class="form-group">
                        <label>Email Address <span class="req">*</span></label>
                        <input type="email" name="email" placeholder="you@email.com" required>
                    </div>
                    <div class="form-group">
                        <label>Phone Number <span class="req">*</span></label>
                        <input type="tel" name="phone" placeholder="09XXXXXXXXX" required>
                    </div>
                </div>

                <hr class="form-divider">

                <div class="form-group" style="margin-bottom: 20px;">
                    <label>Type of Glasses to Repair <span class="req">*</span></label>
                    <select name="glasses_type" required class="glasses-type-select">
                        <option value="" disabled selected>Select type of glasses</option>
                        <option>Prescription Eyeglasses</option>
                        <option>Sunglasses</option>
                        <option>Reading Glasses</option>
                        <option>Kids / Children's Glasses</option>
                        <option>Sports / Safety Glasses</option>
                        <option>Progressive / Bifocal Glasses</option>
                        <option>Designer / Luxury Frames</option>
                        <option>Contact Lens Accessories</option>
                        <option>Other</option>
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label>Type of Repair Needed <span class="req">*</span>
                        <span style="font-size:0.8rem; color:var(--text-soft); font-weight:400;">(Select all that apply)</span>
                    </label>
                    <div class="repair-grid">
                        <label class="repair-option"><input type="checkbox" name="repair_type[]" value="Frame Repair / Adjustment"><span class="repair-icon">🖼️</span><span class="repair-label">Frame Repair / Adjustment</span></label>
                        <label class="repair-option"><input type="checkbox" name="repair_type[]" value="Broken Frame Repair"><span class="repair-icon">💥</span><span class="repair-label">Broken Frame Repair</span></label>
                        <label class="repair-option"><input type="checkbox" name="repair_type[]" value="Lens Replacement"><span class="repair-icon">🔭</span><span class="repair-label">Lens Replacement</span></label>
                        <label class="repair-option"><input type="checkbox" name="repair_type[]" value="Scratched Lens Repair"><span class="repair-icon">🔄</span><span class="repair-label">Scratched Lens Repair</span></label>
                        <label class="repair-option"><input type="checkbox" name="repair_type[]" value="Lens Coating Repair"><span class="repair-icon">✨</span><span class="repair-label">Lens Coating Repair</span></label>
                        <label class="repair-option"><input type="checkbox" name="repair_type[]" value="Screw / Hinge Repair"><span class="repair-icon">🪛</span><span class="repair-label">Screw / Hinge Repair</span></label>
                        <label class="repair-option"><input type="checkbox" name="repair_type[]" value="Nose Pad Replacement"><span class="repair-icon">👃</span><span class="repair-label">Nose Pad Replacement</span></label>
                        <label class="repair-option"><input type="checkbox" name="repair_type[]" value="Temple / Arm Repair"><span class="repair-icon">🦾</span><span class="repair-label">Temple / Arm Repair</span></label>
                        <label class="repair-option"><input type="checkbox" name="repair_type[]" value="Frame Reshaping / Realignment"><span class="repair-icon">⚙️</span><span class="repair-label">Frame Reshaping / Realignment</span></label>
                        <label class="repair-option"><input type="checkbox" name="repair_type[]" value="Anti-Reflective Coating Reapplication"><span class="repair-icon">🌟</span><span class="repair-label">Anti-Reflective Coating</span></label>
                        <label class="repair-option"><input type="checkbox" name="repair_type[]" value="UV Coating Reapplication"><span class="repair-icon">☀️</span><span class="repair-label">UV Coating Reapplication</span></label>
                        <label class="repair-option"><input type="checkbox" name="repair_type[]" value="General Cleaning & Tune-Up"><span class="repair-icon">🧼</span><span class="repair-label">General Cleaning & Tune-Up</span></label>
                        <label class="repair-option"><input type="checkbox" name="repair_type[]" value="Other Repair"><span class="repair-icon">🔧</span><span class="repair-label">Other / Not Listed</span></label>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label>Preferred Drop-off Date</label>
                        <input type="date" name="dropoff_date">
                    </div>
                    <div class="form-group">
                        <label>Preferred Drop-off Time</label>
                        <select name="dropoff_time">
                            <option value="" disabled selected>Select a time</option>
                            <option>9:00 AM</option>
                            <option>10:00 AM</option>
                            <option>11:00 AM</option>
                            <option>1:00 PM</option>
                            <option>2:00 PM</option>
                            <option>3:00 PM</option>
                            <option>4:00 PM</option>
                        </select>
                    </div>
                    <div class="form-group full">
                        <label>Describe the Damage</label>
                        <textarea name="damage_description" placeholder="Please describe what's broken or damaged..."></textarea>
                    </div>
                </div>

                <hr class="form-divider">
                <div class="form-submit">
                    <p class="form-note">🔧 Our technician will assess the damage and provide an estimate before proceeding.</p>
                    <button type="submit" name="submit_repair" class="btn-submit">
                        Submit Repair Request <span>→</span>
                    </button>
                </div>
            </form>
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
                <li>Featured Products</li>
                <li>Optical Guide</li>
            </ul>
        </div>
        <div class="pre-footer-column">
            <h4>Help</h4>
            <ul>
                <li>Book an Appointment</li>
                <li>Repair with Us</li>
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
function switchTab(tab, btn) {
    document.querySelectorAll('.booking-section').forEach(s => s.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('sec-' + tab).classList.add('active');
    btn.classList.add('active');
    window.scrollTo({ top: document.querySelector('.booking-tabs-wrapper').offsetTop - 72, behavior: 'smooth' });
}

document.querySelectorAll('.repair-option input[type="checkbox"]').forEach(cb => {
    cb.addEventListener('change', function () {
        this.closest('.repair-option').classList.toggle('checked', this.checked);
    });
});

document.querySelectorAll('.eyeglass-card input[type="radio"]').forEach(r => {
    r.addEventListener('change', function () {
        document.querySelectorAll('.eyeglass-card').forEach(c => c.classList.remove('selected'));
        this.closest('.eyeglass-card').classList.add('selected');
    });
});
</script>

</body>
</html>
