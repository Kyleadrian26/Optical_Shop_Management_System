<?php

session_start();

include "config.php";


/* PAYMENT FILTER */

$payment_filter = "";

if(isset($_GET['payment_method']) &&
$_GET['payment_method'] != ""){

    $payment_method =
    $_GET['payment_method'];

    $payment_filter =
    "WHERE dimension_payment.payment_method =
    '$payment_method'";
}


/* MAIN OLAP QUERY */

$query = mysqli_query($conn,

"SELECT

dimension_product.product_name,
dimension_payment.payment_method,

SUM(fact_sales.quantity)
AS total_quantity,

SUM(fact_sales.total_sales)
AS total_sales

FROM fact_sales

JOIN dimension_product
ON fact_sales.product_key =
dimension_product.product_key

JOIN dimension_payment
ON fact_sales.payment_key =
dimension_payment.payment_key

$payment_filter

GROUP BY
dimension_product.product_name,
dimension_payment.payment_method

ORDER BY total_sales DESC");


/* MONTHLY ROLL-UP */

$monthly_rollup = mysqli_query($conn,

"SELECT

dimension_date.month_name,

SUM(fact_sales.total_sales)
AS monthly_sales

FROM fact_sales

JOIN dimension_date
ON fact_sales.date_key =
dimension_date.date_key

GROUP BY dimension_date.month_name");


?>

<!DOCTYPE html>
<html>

<head>

<title>OLAP Analytics Report</title>

<link rel="stylesheet" href="style.css">

</head>

<body>

<div class="dashboard-container">

<h1>OLAP ANALYTICS REPORT</h1>


<!-- FILTER -->

<form method="GET">

<select name="payment_method">

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


<!-- MAIN ANALYTICS TABLE -->

<table>

<tr>

<th>Product</th>
<th>Payment Method</th>
<th>Total Quantity</th>
<th>Total Sales</th>

</tr>

<?php while($row = mysqli_fetch_assoc($query)){ ?>

<tr>

<td>
<?php echo $row['product_name']; ?>
</td>

<td>
<?php echo $row['payment_method']; ?>
</td>

<td>
<?php echo $row['total_quantity']; ?>
</td>

<td>
₱<?php echo $row['total_sales']; ?>
</td>

</tr>

<?php } ?>

</table>


<br><br>


<!-- MONTHLY ROLL-UP -->

<h2>Monthly Sales Roll-Up</h2>

<table>

<tr>

<th>Month</th>
<th>Total Sales</th>

</tr>

<?php while($month = mysqli_fetch_assoc($monthly_rollup)){ ?>

<tr>

<td>
<?php echo $month['month_name']; ?>
</td>

<td>
₱<?php echo $month['monthly_sales']; ?>
</td>

</tr>

<?php } ?>

</table>


<br>


<a href="drilldown_report.php">
<button>
Drill-Down Analytics
</button>
</a>

<a href="dice_report.php">
<button>Dice Analytics</button>
</a>

<a href="admin_dashboard.php">
<button>
Back to Dashboard
</button>
</a>


</div>

</body>
</html>