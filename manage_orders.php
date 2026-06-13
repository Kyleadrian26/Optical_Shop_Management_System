<?php
session_start();

include "config.php";

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    header("Location: login.php");
    exit();
}

$orders = mysqli_query($conn,

"SELECT
sales.sale_id,
sales.total_amount,
sales.payment_method,
sales.status,
sales.sale_date,
sales.gcash_reference,
sales.card_last4,
sales.card_owner,
sales.card_approval,
users.fullname

FROM sales

LEFT JOIN users ON sales.customer_id = users.user_id

ORDER BY sales.sale_id DESC");
?>

<!DOCTYPE html>
<html>
<head>

<title>Manage Orders</title>

<link rel="stylesheet" href="style.css">

<style>
.proof-badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 0.78rem;
    font-weight: 600;
    letter-spacing: 0.02em;
}

.proof-gcash {
    background: #e6f4ea;
    color: #2e7d32;
    border: 1px solid #a5d6a7;
}

.proof-card {
    background: #e3f2fd;
    color: #1565c0;
    border: 1px solid #90caf9;
}

.proof-cash {
    background: #f3f3f3;
    color: #666;
    border: 1px solid #ddd;
}

.proof-none {
    background: #fff3e0;
    color: #e65100;
    border: 1px solid #ffcc80;
    font-style: italic;
}

.ref-detail {
    margin-top: 5px;
    font-size: 0.82rem;
    color: #444;
    line-height: 1.6;
}

.ref-detail span {
    display: block;
}

.ref-number {
    font-weight: 700;
    font-family: monospace;
    font-size: 0.9rem;
    color: #111;
    letter-spacing: 0.05em;
}
</style>

</head>
<body>

<div class="dashboard-container">

<h1>MANAGE ORDERS</h1>

<table border="1" cellpadding="10">

<tr>
<th>Order ID</th>
<th>Customer</th>
<th>Total</th>
<th>Payment Method</th>
<th>Payment Proof</th>
<th>Status</th>
<th>Date</th>
<th>Update</th>
</tr>

<?php while($row = mysqli_fetch_assoc($orders)){ ?>

<tr>

<td><?php echo $row['sale_id']; ?></td>

<td><?php echo $row['fullname'] ? $row['fullname'] : 'Walk-in Customer'; ?></td>

<td>₱<?php echo number_format($row['total_amount'],2); ?></td>

<td><?php echo $row['payment_method']; ?></td>

<!-- PAYMENT PROOF COLUMN -->
<td>
<?php if($row['payment_method'] === 'GCash'): ?>

    <?php if($row['gcash_reference']): ?>
        <span class="proof-badge proof-gcash">✓ GCash Verified</span>
        <div class="ref-detail">
            <span>Ref #:</span>
            <span class="ref-number"><?php echo htmlspecialchars($row['gcash_reference']); ?></span>
        </div>
    <?php else: ?>
        <span class="proof-badge proof-none">⚠ No Reference</span>
    <?php endif; ?>

<?php elseif($row['payment_method'] === 'Card'): ?>

    <?php if($row['card_last4']): ?>
        <span class="proof-badge proof-card">✓ Card Verified</span>
        <div class="ref-detail">
            <span><?php echo htmlspecialchars($row['card_owner']); ?></span>
            <span>Card ending in <strong><?php echo htmlspecialchars($row['card_last4']); ?></strong></span>
            <span>Approval: <span class="ref-number"><?php echo htmlspecialchars($row['card_approval']); ?></span></span>
        </div>
    <?php else: ?>
        <span class="proof-badge proof-none">⚠ No Card Info</span>
    <?php endif; ?>

<?php else: ?>
    <span class="proof-badge proof-cash">Cash</span>
<?php endif; ?>
</td>

<td><?php echo $row['status']; ?></td>

<td><?php echo $row['sale_date']; ?></td>

<td>
<form action="update_order_status.php" method="POST">
<input type="hidden" name="sale_id" value="<?php echo $row['sale_id']; ?>">
<select name="status">
<option value="Pending">Pending</option>
<option value="Processing">Processing</option>
<option value="Ready for Pickup">Ready for Pickup</option>
<option value="Completed">Completed</option>
</select>
<button type="submit">Update</button>
</form>
</td>

</tr>

<?php } ?>

</table>

<br>

<a href="admin_dashboard.php">
<button>Back to Dashboard</button>
</a>

</div>

</body>
</html>