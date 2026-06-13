<?php
session_start();

if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer'){
    header("Location: login.php");
    exit();
}

include "config.php";

$user_id = $_SESSION['user_id'];

$query = mysqli_query($conn, "SELECT * FROM users WHERE user_id='$user_id'");
$user = mysqli_fetch_assoc($query);

$stats = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) AS total_orders, COALESCE(SUM(total_amount),0) AS total_spent
     FROM sales
     WHERE customer_id='$user_id'"
));

$last_order_result = mysqli_query($conn,
    "SELECT sale_id, total_amount, status, sale_date
     FROM sales
     WHERE customer_id='$user_id'
     ORDER BY sale_id DESC
     LIMIT 1"
);
$last_order = mysqli_fetch_assoc($last_order_result);
?>

<!DOCTYPE html>
<html>
<head>
<title>My Profile</title>
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="customer.css">
</head>
<body>

<div class="profile-page">
    <div class="profile-header">
        <div>
            <h1>My Profile</h1>
            <p>Manage your account details, review recent activity, and access quick shopping tools.</p>
        </div>
        <a href="customer_dashboard.php" class="back-link">← Back to Dashboard</a>
    </div>

    <div class="profile-grid">
        <aside class="profile-card">
            <div class="profile-avatar">
                <span><?php echo strtoupper(substr($user['fullname'], 0, 1)); ?></span>
            </div>
            <h2><?php echo htmlspecialchars($user['fullname']); ?></h2>
            <p class="profile-role"><?php echo ucfirst($user['role']); ?></p>

            <div class="profile-summary">
                <div><strong>Username</strong><span><?php echo htmlspecialchars($user['username']); ?></span></div>
                <div><strong>Member Since</strong><span><?php echo isset($user['created_at']) ? date('M d, Y', strtotime($user['created_at'])) : 'N/A'; ?></span></div>
            </div>

            <div class="action-links">
                <a href="shop.php">Browse Products</a>
                <a href="customer_orders.php">My Orders</a>
                <a href="customer_dashboard.php">Dashboard Home</a>
            </div>
        </aside>

        <section class="profile-details">
            <div class="details-card">
                <h3>Account Information</h3>
                <div class="detail-row"><span>Full Name</span><strong><?php echo htmlspecialchars($user['fullname']); ?></strong></div>
                <div class="detail-row"><span>Username</span><strong><?php echo htmlspecialchars($user['username']); ?></strong></div>
                <div class="detail-row"><span>Role</span><strong><?php echo ucfirst($user['role']); ?></strong></div>
            </div>

            <div class="details-card">
                <h3>Shopping Activity</h3>
                <div class="detail-row"><span>Total Orders</span><strong><?php echo $stats['total_orders']; ?></strong></div>
                <div class="detail-row"><span>Total Spent</span><strong>₱<?php echo number_format($stats['total_spent'], 2); ?></strong></div>
                <?php if($last_order): ?>
                    <div class="detail-row"><span>Last Order</span><strong>#<?php echo $last_order['sale_id']; ?></strong></div>
                    <div class="detail-row"><span>Status</span><strong><?php echo htmlspecialchars($last_order['status']); ?></strong></div>
                    <div class="detail-row"><span>Date</span><strong><?php echo $last_order['sale_date']; ?></strong></div>
                <?php else: ?>
                    <div class="detail-row"><span>Last Order</span><strong>No orders yet</strong></div>
                <?php endif; ?>
            </div>

            <div class="details-card help-card">
                <h3>Need Help?</h3>
                <p>If you want to update profile details or need assistance, return to the dashboard or review your orders.</p>
                <a href="customer_dashboard.php">Go to Dashboard</a>
            </div>
        </section>
    </div>
</div>

</body>
</html>