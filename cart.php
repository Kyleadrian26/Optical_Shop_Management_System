<?php
session_start();
include "config.php";

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$cart_query = mysqli_query($conn,

"SELECT

cart.cart_id,
cart.quantity,

products.product_name,
products.price,
products.image

FROM cart

JOIN products
ON cart.product_id = products.product_id

WHERE cart.user_id='$user_id'");
?>

<!DOCTYPE html>
<html>
<head>

<title>Shopping Cart</title>

<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="cart.css">

</head>

<body>

<div class="dashboard-container">

<h1>SHOPPING CART</h1>

<table>

<tr>

<th>Product</th>
<th>Price</th>
<th>Quantity</th>
<th>Subtotal</th>
<th>Action</th>

</tr>

<?php

$total = 0;

while($row = mysqli_fetch_assoc($cart_query)){

$subtotal = $row['price'] * $row['quantity'];

$total += $subtotal;
?>

<tr>

<td class="product-cell">
    <div class="cart-image">
        <img src="images/<?php echo $row['image']; ?>" alt="<?php echo htmlspecialchars($row['product_name']); ?>">
    </div>
    <div class="product-details">
        <strong><?php echo $row['product_name']; ?></strong>
        <span>₱<?php echo number_format($row['price'],2); ?></span>
    </div>
</td>

<td>₱<?php echo number_format($row['price'],2); ?></td>

<td><?php echo $row['quantity']; ?></td>

<td>₱<?php echo number_format($subtotal,2); ?></td>

<td class="action-cell">
    <a href="update_cart.php?action=add&cart_id=<?php echo $row['cart_id']; ?>"><button type="button">+</button></a>
    <a href="update_cart.php?action=minus&cart_id=<?php echo $row['cart_id']; ?>"><button type="button">-</button></a>
    <a href="delete_cart.php?cart_id=<?php echo $row['cart_id']; ?>"><button type="button" class="remove-btn">Remove</button></a>
</td>

</tr>

<?php } ?>

<tr>

<td colspan="4">

<strong>Total</strong>

</td>

<td>

<strong>
₱<?php echo number_format($total,2); ?>
</strong>

</td>

</tr>

</table>

<br>

<a href="shop.php">
<button>Continue Shopping</button>
</a>

<a href="checkout.php">
<button>Checkout</button>
</a>

<a href="customer_dashboard.php">
<button>Back to Dashboard</button>
</a>

</div>

</body>
</html>
