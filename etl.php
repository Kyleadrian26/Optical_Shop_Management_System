<?php

include "config.php";

echo "<h2>ETL DEBUG MODE</h2>";

/* CLEAR OLD DATA */

mysqli_query($conn, "DELETE FROM fact_sales");
mysqli_query($conn, "DELETE FROM dimension_product");
mysqli_query($conn, "DELETE FROM dimension_date");
mysqli_query($conn, "DELETE FROM dimension_payment");

echo "Old warehouse data cleared.<br><br>";

/* INSERT INTO dimension_product */

$product_query = mysqli_query($conn,"SELECT * FROM products");

while($product = mysqli_fetch_assoc($product_query)){

    $product_id = $product['product_id'];
    $product_name = $product['product_name'];
    $category = $product['category'];
    $brand = $product['brand'];

    $result = mysqli_query($conn,

    "INSERT INTO dimension_product
    (product_id, product_name, category, brand)

    VALUES

    ('$product_id','$product_name',
    '$category','$brand')");

    if(!$result){
        die("Dimension Product Error: ".mysqli_error($conn));
    }
}

echo "Dimension Product Loaded.<br>";



/* INSERT INTO dimension_payment */

$payment_query = mysqli_query($conn,
"SELECT DISTINCT payment_method FROM sales");

while($payment = mysqli_fetch_assoc($payment_query)){

    $payment_method = $payment['payment_method'];

    $result = mysqli_query($conn,

    "INSERT INTO dimension_payment
    (payment_method)

    VALUES

    ('$payment_method')");

    if(!$result){
        die("Dimension Payment Error: ".mysqli_error($conn));
    }
}

echo "Dimension Payment Loaded.<br>";



/* INSERT INTO dimension_date */

$date_query = mysqli_query($conn,

"SELECT DISTINCT DATE(sale_date) AS full_date
FROM sales");

while($date = mysqli_fetch_assoc($date_query)){

    $full_date = $date['full_date'];

    $month_name = date("F", strtotime($full_date));

    $year = date("Y", strtotime($full_date));

    $result = mysqli_query($conn,

    "INSERT INTO dimension_date
    (full_date, month_name, year)

    VALUES

    ('$full_date','$month_name','$year')");

    if(!$result){
        die("Dimension Date Error: ".mysqli_error($conn));
    }
}

echo "Dimension Date Loaded.<br>";



/* FACT TABLE */

$fact_query = mysqli_query($conn,

"SELECT

sale_items.quantity,
sale_items.price,
products.product_id,
sales.payment_method,
DATE(sales.sale_date) AS sale_date

FROM sale_items

JOIN products
ON sale_items.product_id = products.product_id

JOIN sales
ON sale_items.sale_id = sales.sale_id");

while($fact = mysqli_fetch_assoc($fact_query)){

    $quantity = $fact['quantity'];

    $total_sales = $fact['quantity'] * $fact['price'];

    $product_id = $fact['product_id'];

    $payment_method = $fact['payment_method'];

    $sale_date = $fact['sale_date'];



    $product_key_query = mysqli_query($conn,

    "SELECT product_key
    FROM dimension_product

    WHERE product_id='$product_id'");

    $product_key_data = mysqli_fetch_assoc($product_key_query);

    if(!$product_key_data){
        die("Missing Product Key for Product ID: ".$product_id);
    }

    $product_key = $product_key_data['product_key'];



    $payment_key_query = mysqli_query($conn,

    "SELECT payment_key
    FROM dimension_payment

    WHERE payment_method='$payment_method'");

    $payment_key_data = mysqli_fetch_assoc($payment_key_query);

    if(!$payment_key_data){
        die("Missing Payment Key: ".$payment_method);
    }

    $payment_key = $payment_key_data['payment_key'];



    $date_key_query = mysqli_query($conn,

    "SELECT date_key
    FROM dimension_date

    WHERE full_date='$sale_date'");

    $date_key_data = mysqli_fetch_assoc($date_key_query);

    if(!$date_key_data){
        die("Missing Date Key: ".$sale_date);
    }

    $date_key = $date_key_data['date_key'];



    $result = mysqli_query($conn,

    "INSERT INTO fact_sales

    (product_key, date_key,
    payment_key, quantity, total_sales)

    VALUES

    ('$product_key','$date_key',
    '$payment_key','$quantity','$total_sales')");

    if(!$result){
        die("Fact Table Error: ".mysqli_error($conn));
    }
}

echo "<br><h2>ETL COMPLETED SUCCESSFULLY!</h2>";

?>