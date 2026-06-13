<?php
session_start();

if(!isset($_SESSION['role'])){
    header("Location: login.php");
    exit();
}

if($_SESSION['role'] != 'admin'){
    header("Location: login.php");
    exit();
}

include "config.php";


/* TOTAL REVENUE */
$revenue_query = mysqli_query($conn,

"SELECT IFNULL(SUM(total_amount),0) AS revenue
FROM sales");

$revenue = mysqli_fetch_assoc($revenue_query);


/* TOTAL TRANSACTIONS */
$transaction_query = mysqli_query($conn,

"SELECT COUNT(*) AS total_transactions
FROM sales");

$transactions = mysqli_fetch_assoc($transaction_query);


/* BEST SELLING PRODUCT */
$best_product_query = mysqli_query($conn,

"SELECT
products.product_name,
SUM(sale_items.quantity) AS qty

FROM sale_items

JOIN products
ON sale_items.product_id = products.product_id

GROUP BY products.product_name

ORDER BY qty DESC

LIMIT 1");

$best_product = mysqli_fetch_assoc($best_product_query);

if(!$best_product){
    $best_product['product_name'] = "No Sales Yet";
}


/* TOTAL PRODUCTS */
$total_products = mysqli_query($conn,
"SELECT COUNT(*) AS total FROM products");

$product_data = mysqli_fetch_assoc($total_products);


/* TOTAL SALES */
$total_sales = mysqli_query($conn,

"SELECT IFNULL(SUM(total_amount),0) AS total
FROM sales");

$sales_data = mysqli_fetch_assoc($total_sales);


/* LOW STOCK */
$low_stock = mysqli_query($conn,

"SELECT COUNT(*) AS lowstock
FROM products
WHERE stock <= 5");

$lowstock_data = mysqli_fetch_assoc($low_stock);


/* MONTHLY SALES */
$monthly_sales = mysqli_query($conn,

"SELECT
MONTH(sale_date) AS month,
SUM(total_amount) AS total

FROM sales

GROUP BY MONTH(sale_date)");

$months = [];
$totals = [];

while($row = mysqli_fetch_assoc($monthly_sales)){

    $months[] = $row['month'];
    $totals[] = $row['total'];
}


/* TOP SELLING PRODUCTS */
$top_products = mysqli_query($conn,

"SELECT
products.product_name,
SUM(sale_items.quantity) AS total_sold

FROM sale_items

JOIN products
ON sale_items.product_id = products.product_id

GROUP BY products.product_name

ORDER BY total_sold DESC

LIMIT 5");


/* PAYMENT METHOD BREAKDOWN */
$payment_query = mysqli_query($conn,

"SELECT payment_method, COUNT(*) AS count
FROM sales
GROUP BY payment_method");

$payment_labels = [];
$payment_counts = [];

while($row = mysqli_fetch_assoc($payment_query)){
    $payment_labels[] = $row['payment_method'];
    $payment_counts[] = $row['count'];
}
?>

<!DOCTYPE html>
<html>
<head>

<title>Admin Dashboard</title>

<link rel="stylesheet" href="style.css">

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body>

<div class="dashboard-container">

<h1>ADMIN DASHBOARD</h1>

<div class="dashboard-box">

<h2>
Total Products:
<?php echo $product_data['total']; ?>
</h2>

<h2>
Total Sales:
₱<?php echo number_format($sales_data['total'],2); ?>
</h2>

<h2>
Low Stock Products:
<?php echo $lowstock_data['lowstock']; ?>
</h2>

</div>


<div class="kpi-container">

<div class="kpi-card">

<h2>
₱<?php echo number_format($revenue['revenue'],2); ?>
</h2>

<p>Total Revenue</p>

</div>


<div class="kpi-card">

<h2>
<?php echo $transactions['total_transactions']; ?>
</h2>

<p>Total Transactions</p>

</div>


<div class="kpi-card">

<h2>
<?php echo $best_product['product_name']; ?>
</h2>

<p>Best Selling Product</p>

</div>


<div class="kpi-card">

<h2>
<?php echo $lowstock_data['lowstock']; ?>
</h2>

<p>Low Stock Products</p>

</div>

</div>


<div class="button-group">

<a href="products.php">
<button>Manage Products</button>
</a>

<a href="manage_orders.php">
<button>Manage Orders</button>
</a>

<a href="process_orders.php">
<button>Process Orders</button>
</a>

<a href="olap_reports.php">
<button>OLAP Analytics</button>
</a>

<a href="logout.php">
<button>Logout</button>
</a>

</div>


<div class="dashboard-box">

<h2>Monthly Sales Analytics</h2>

<canvas id="salesChart"></canvas>

</div>


<!-- PAYMENT METHOD PIE CHART -->
<div class="dashboard-box">

<h2>Payment Method Breakdown</h2>

<div style="max-width: 400px; margin: 0 auto;">
    <canvas id="paymentChart"></canvas>
</div>

</div>


<div class="dashboard-box">

<h2>Sales History</h2>

<table border="1" cellpadding="10">

<tr>
<th>Sale ID</th>
<th>Total Amount</th>
<th>Payment Method</th>
<th>Date</th>
</tr>

<?php

$sales = mysqli_query($conn,
"SELECT * FROM sales ORDER BY sale_id DESC");

while($row = mysqli_fetch_assoc($sales)){

?>

<tr>

<td><?php echo $row['sale_id']; ?></td>

<td>₱<?php echo number_format($row['total_amount'],2); ?></td>

<td><?php echo $row['payment_method']; ?></td>

<td><?php echo $row['sale_date']; ?></td>

</tr>

<?php } ?>

</table>

</div>


<div class="dashboard-box">

<h2>Top Selling Products</h2>

<table border="1" cellpadding="10">

<tr>
<th>Product Name</th>
<th>Total Sold</th>
</tr>

<?php while($top = mysqli_fetch_assoc($top_products)){ ?>

<tr>

<td>
<?php echo $top['product_name']; ?>
</td>

<td>
<?php echo $top['total_sold']; ?>
</td>

</tr>

<?php } ?>

</table>

</div>

</div>


<script>

/* BAR CHART — Monthly Sales */
const ctx = document.getElementById('salesChart');

new Chart(ctx, {

type: 'bar',

data: {

labels: <?php echo json_encode($months); ?>,

datasets: [{

label: 'Monthly Sales',

data: <?php echo json_encode($totals); ?>,

borderWidth: 1

}]
},

options: {

responsive: true,

scales: {
y: {
beginAtZero: true
}
}

}

});


/* PIE CHART — Payment Method Breakdown */
const paymentCtx = document.getElementById('paymentChart');

new Chart(paymentCtx, {

    type: 'pie',

    data: {

        labels: <?php echo json_encode($payment_labels); ?>,

        datasets: [{
            label: 'Payment Methods',
            data: <?php echo json_encode($payment_counts); ?>,
            backgroundColor: [
                '#B8860B',   // Gold       — Cash
                '#3d2110',   // Dark Brown — GCash
                '#6b4a27',   // Soft Brown — Card
                '#f7e189',   // Light Gold — others
            ],
            borderColor: '#ffffff',
            borderWidth: 2
        }]

    },

    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 20,
                    font: { size: 14 }
                }
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                        const value = context.parsed;
                        const percent = ((value / total) * 100).toFixed(1);
                        return ` ${context.label}: ${value} transactions (${percent}%)`;
                    }
                }
            }
        }
    }

});

</script>

</body>
</html>