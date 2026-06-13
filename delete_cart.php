<?php
session_start();
include "config.php";

$cart_id = $_GET['cart_id'];

mysqli_query($conn,

"DELETE FROM cart
WHERE cart_id='$cart_id'");

header("Location: cart.php");
?>