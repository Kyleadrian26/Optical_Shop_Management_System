<?php

session_start();

include "config.php";


/* PRODUCT DRILL-DOWN */

$drilldown_query = mysqli_query($conn,

"SELECT

dimension_product.product_name,

SUM(fact_sales.quantity) AS total_quantity,

SUM(fact_sales.total_sales) AS total_sales

FROM fact_sales

JOIN dimension_product
ON fact_sales.product_key =
dimension_product.product_key

GROUP BY dimension_product.product_name

ORDER BY total_sales DESC");

?>

<!DOCTYPE html>
<html>

<head>

<title>Drill Down Analytics</title>

<link rel="stylesheet" href="style.css">

</head>

<body>

<div class="dashboard-container">

<h1>DRILL-DOWN ANALYTICS</h1>

<p>
This report shows detailed product-level sales information.
</p>

<table>

<tr>

<th>Product Name</th>
<th>Total Quantity Sold</th>
<th>Total Sales</th>

</tr>

<?php while($row = mysqli_fetch_assoc($drilldown_query)){ ?>

<tr>

<td>
<?php echo $row['product_name']; ?>
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

<br>

<a href="olap_reports.php">
<button>Back to OLAP Reports</button>
</a>

</div>

</body>
</html>
