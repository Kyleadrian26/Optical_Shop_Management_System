<?php

session_start();
include "config.php";

$product_filter = "";
$payment_filter = "";

if(isset($_GET['product']) && $_GET['product'] != ""){
    $product_filter =
    "dimension_product.product_name='".$_GET['product']."'";
}

if(isset($_GET['payment']) && $_GET['payment'] != ""){
    $payment_filter =
    "dimension_payment.payment_method='".$_GET['payment']."'";
}

$where = "";

if($product_filter != "" && $payment_filter != ""){
    $where = "WHERE $product_filter AND $payment_filter";
}
elseif($product_filter != ""){
    $where = "WHERE $product_filter";
}
elseif($payment_filter != ""){
    $where = "WHERE $payment_filter";
}

$query = mysqli_query($conn,

"SELECT

dimension_product.product_name,
dimension_payment.payment_method,

SUM(fact_sales.quantity) AS total_quantity,

SUM(fact_sales.total_sales) AS total_sales

FROM fact_sales

JOIN dimension_product
ON fact_sales.product_key =
dimension_product.product_key

JOIN dimension_payment
ON fact_sales.payment_key =
dimension_payment.payment_key

$where

GROUP BY
dimension_product.product_name,
dimension_payment.payment_method");

?>

<!DOCTYPE html>
<html>
<head>
<title>Dice Analytics</title>
<link rel="stylesheet" href="style.css">
</head>

<body>

<div class="dashboard-container">

<h1>DICE ANALYTICS</h1>

<form method="GET">

<select name="product">

<option value="">
All Products
</option>

<?php

$products = mysqli_query($conn,
"SELECT DISTINCT product_name
FROM dimension_product");

while($p = mysqli_fetch_assoc($products)){

?>

<option value="<?php echo $p['product_name']; ?>">
<?php echo $p['product_name']; ?>
</option>

<?php } ?>

</select>


<select name="payment">

<option value="">
All Payments
</option>

<option value="Cash">
Cash
</option>

<option value="GCash">
GCash
</option>

<option value="Card">
Card
</option>

</select>

<button type="submit">
Filter
</button>

</form>

<br>

<table>

<tr>
<th>Product</th>
<th>Payment</th>
<th>Quantity</th>
<th>Total Sales</th>
</tr>

<?php while($row = mysqli_fetch_assoc($query)){ ?>

<tr>

<td><?php echo $row['product_name']; ?></td>

<td><?php echo $row['payment_method']; ?></td>

<td><?php echo $row['total_quantity']; ?></td>

<td>₱<?php echo $row['total_sales']; ?></td>

</tr>

<?php } ?>

</table>

<br>

<a href="olap_reports.php">
<button>Back to OLAP Reports</button>
</a>

</div>

</body>
</html>
