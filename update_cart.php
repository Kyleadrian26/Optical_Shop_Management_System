<?php
session_start();
include "config.php";

$cart_id = $_GET['cart_id'];
$action = $_GET['action'];

$item = mysqli_fetch_assoc(

mysqli_query($conn,

"SELECT * FROM cart
WHERE cart_id='$cart_id'")

);

if($action == "add"){

    $new_qty = $item['quantity'] + 1;

    mysqli_query($conn,

    "UPDATE cart
    SET quantity='$new_qty'
    WHERE cart_id='$cart_id'");
}

if($action == "minus"){

    $new_qty = $item['quantity'] - 1;

    if($new_qty <= 0){

        mysqli_query($conn,

        "DELETE FROM cart
        WHERE cart_id='$cart_id'");

    }else{

        mysqli_query($conn,

        "UPDATE cart
        SET quantity='$new_qty'
        WHERE cart_id='$cart_id'");
    }
}

header("Location: cart.php");
?>