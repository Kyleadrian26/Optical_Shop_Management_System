<?php
session_start();
include "config.php";

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$orders = mysqli_query($conn,

"SELECT
    sales.sale_id,
    sales.total_amount,
    sales.payment_method,
    sales.status,
    sales.sale_date,
    sale_items.quantity,
    sale_items.price,
    products.product_name,
    products.image
FROM sales
JOIN sale_items ON sales.sale_id = sale_items.sale_id
JOIN products ON sale_items.product_id = products.product_id
WHERE sales.customer_id='$user_id'
ORDER BY sales.sale_id DESC");
?>

<!DOCTYPE html>
<html>
<head>

<title>My Orders</title>

<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="cart.css">

</head>
<body>

<div class="dashboard-container">

<h1>MY ORDERS</h1>

<table>

<tr>

<th>Order ID</th>
<th>Product</th>
<th>Price</th>
<th>Quantity</th>
<th>Subtotal</th>
<th>Status</th>
<th>Date</th>

</tr>

<?php while($row = mysqli_fetch_assoc($orders)){ ?>

<tr>

<td><?php echo $row['sale_id']; ?></td>

<td class="product-cell">
    <div class="cart-image">
        <img src="images/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['product_name']); ?>">
    </div>
    <div class="product-details">
        <strong><?php echo htmlspecialchars($row['product_name']); ?></strong>
    </div>
</td>

<td>₱<?php echo number_format($row['price'],2); ?></td>

<td><?php echo $row['quantity']; ?></td>

<td>₱<?php echo number_format($row['price'] * $row['quantity'],2); ?></td>

<td>
<?php
if($row['status'] == 'Pending'){
    echo "🟡 Pending";
}
elseif($row['status'] == 'Processing'){
    echo "🔵 Processing";
}
elseif($row['status'] == 'Ready for Pickup'){
    echo "🟠 Ready for Pickup";
}
elseif($row['status'] == 'Completed'){
    echo "🟢 Completed";
}
else{
    echo htmlspecialchars($row['status']);
}
?>
</td>

<td><?php echo $row['sale_date']; ?></td>

</tr>

<?php } ?>

</table>

<br>

<a href="customer_dashboard.php">
<button>Back to Dashboard</button>
</a>

</div>

</body>
</html>