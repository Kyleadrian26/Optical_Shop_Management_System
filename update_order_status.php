<?php

include "config.php";

$sale_id = $_POST['sale_id'];
$status = $_POST['status'];

mysqli_query($conn,

"UPDATE sales

SET status='$status'

WHERE sale_id='$sale_id'");

header("Location: manage_orders.php");

?>