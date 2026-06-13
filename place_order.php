<?php
session_start();

include "config.php";

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$payment_method   = $_POST['payment_method'];
$gcash_reference  = isset($_POST['gcash_reference'])  ? mysqli_real_escape_string($conn, $_POST['gcash_reference'])  : '';
$card_last4       = isset($_POST['card_last4'])        ? mysqli_real_escape_string($conn, $_POST['card_last4'])        : '';
$card_owner       = isset($_POST['card_owner'])        ? mysqli_real_escape_string($conn, $_POST['card_owner'])        : '';
$card_approval    = isset($_POST['card_approval'])     ? mysqli_real_escape_string($conn, $_POST['card_approval'])     : '';
$card_name        = isset($_POST['card_name'])         ? mysqli_real_escape_string($conn, $_POST['card_name'])         : '';

// Build a readable payment label for the success page
$payment_label = $payment_method;
if($payment_method === 'GCash'){
    $payment_label = "GCash (Ref: {$gcash_reference})";
} elseif($payment_method === 'Card'){
    $payment_label = "{$card_name} ending in {$card_last4} (Approval: {$card_approval})";
}


/* GET CART ITEMS */

$cart_query = mysqli_query($conn,

"SELECT
cart.product_id,
cart.quantity,
products.product_name,
products.price,
products.stock

FROM cart

JOIN products
ON cart.product_id = products.product_id

WHERE cart.user_id='$user_id'");

$total_amount = 0;
$items = [];

while($row = mysqli_fetch_assoc($cart_query)){
    $subtotal      = $row['price'] * $row['quantity'];
    $total_amount += $subtotal;
    $items[]       = $row;
}


/* INSERT SALE — now includes reference/card columns */

mysqli_query($conn,

"INSERT INTO sales
(customer_id, total_amount, payment_method, status,
 gcash_reference, card_last4, card_owner, card_approval)

VALUES

('$user_id',
 '$total_amount',
 '$payment_method',
 'Pending',
 " . ($gcash_reference ? "'$gcash_reference'" : "NULL") . ",
 " . ($card_last4      ? "'$card_last4'"      : "NULL") . ",
 " . ($card_owner      ? "'$card_owner'"      : "NULL") . ",
 " . ($card_approval   ? "'$card_approval'"   : "NULL") . "
)");

$sale_id = mysqli_insert_id($conn);


/* INSERT SALE ITEMS */

foreach($items as $item){

    $product_id = $item['product_id'];
    $quantity   = $item['quantity'];
    $price      = $item['price'];

    mysqli_query($conn,

    "INSERT INTO sale_items
    (sale_id, product_id, quantity, price)
    VALUES
    ('$sale_id', '$product_id', '$quantity', '$price')");


    /* UPDATE STOCK */

    $new_stock = $item['stock'] - $quantity;

    mysqli_query($conn,

    "UPDATE products
    SET stock='$new_stock'
    WHERE product_id='$product_id'");
}


/* CLEAR CART */

mysqli_query($conn,

"DELETE FROM cart
WHERE user_id='$user_id'");


/* RUN ETL */

include "etl.php";

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Order Success — Optical Shop</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="place_order.css">
</head>
<body>

<header class="navbar">
    <div class="navbar-inner">
        <a href="index.php" class="navbar-brand">
            <img src="logo1.png" alt="Optical Shop Logo" class="nav-logo">
            <span class="nav-brand-name">Optical Shop</span>
        </a>
        <nav class="navbar-links">
            <a href="index.php" class="nav-link">Home</a>
            <a href="shop.php" class="nav-link">Shop</a>
            <a href="index.php#about" class="nav-link">About</a>
            <a href="index.php#contact" class="nav-link">Contact</a>
            <a href="bookanappointment.php" class="nav-link">Book Appointment</a>
        </nav>
    </div>
</header>

<div class="page-header">
    <h1>Order <span>Confirmed</span></h1>
    <p>Your order has been processed successfully.</p>
</div>

<div class="order-container">
    <div class="order-card">
        <div class="success-section">
            <div class="success-icon-wrapper">
                <div class="success-icon">✓</div>
            </div>
            <h2>Order Placed Successfully</h2>
            <p class="order-id">Order #<?php echo sprintf('%06d', $sale_id); ?></p>
        </div>

        <div class="order-details">
            <div class="detail-row">
                <span class="detail-label">Total Amount</span>
                <span class="detail-value amount">₱<?php echo number_format($total_amount, 2); ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Payment Method</span>
                <span class="detail-value"><?php echo htmlspecialchars($payment_label); ?></span>
            </div>

            <?php if($payment_method === 'GCash' && $gcash_reference): ?>
            <div class="detail-row">
                <span class="detail-label">GCash Reference #</span>
                <span class="detail-value"><strong><?php echo htmlspecialchars($gcash_reference); ?></strong></span>
            </div>
            <?php endif; ?>

            <?php if($payment_method === 'Card' && $card_last4): ?>
            <div class="detail-row">
                <span class="detail-label">Card</span>
                <span class="detail-value"><?php echo htmlspecialchars($card_name); ?> ending in <?php echo htmlspecialchars($card_last4); ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Approval Code</span>
                <span class="detail-value"><strong><?php echo htmlspecialchars($card_approval); ?></strong></span>
            </div>
            <?php endif; ?>

            <div class="detail-row">
                <span class="detail-label">Status</span>
                <span class="detail-value"><span class="status-badge">Pending</span></span>
            </div>
        </div>

        <div class="items-summary">
            <h3>Order Items</h3>
            <div class="items-list">
                <?php foreach($items as $item): ?>
                <div class="item-row">
                    <div class="item-info">
                        <div class="item-name"><?php echo htmlspecialchars($item['product_name']); ?></div>
                        <div class="item-qty">Qty: <?php echo $item['quantity']; ?></div>
                    </div>
                    <span class="item-price">₱<?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="action-buttons">
            <a href="customer_orders.php" class="btn-primary">View My Orders</a>
            <a href="shop.php" class="btn-secondary">Continue Shopping</a>
        </div>
    </div>
</div>

</body>
</html>