<?php
session_start();
include "config.php";

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$query = mysqli_query($conn,

"SELECT cart.*, products.product_name, products.price
FROM cart
JOIN products ON cart.product_id = products.product_id
WHERE cart.user_id='$user_id'");

$total = 0;
?>

<!DOCTYPE html>
<html>
<head>

<title>Checkout</title>

<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="cart.css">

<style>
.payment-details { margin-top: 16px; }

.payment-info-box {
    background: #f5f5f5;
    border-left: 4px solid #000;
    border-radius: 8px;
    padding: 16px 20px;
    margin-bottom: 16px;
}

.payment-info-box p {
    margin: 4px 0;
    font-size: 0.95rem;
    color: #333;
}

.ref-field {
    margin-top: 14px;
}

.ref-field label {
    display: block;
    font-weight: 600;
    font-size: 0.9rem;
    margin-bottom: 6px;
    color: #222;
}

.ref-field input {
    width: 100%;
    padding: 12px 14px;
    border: 1.5px solid #ccc;
    border-radius: 8px;
    font-size: 0.95rem;
    transition: border-color 0.2s;
    background: #fff;
}

.ref-field input:focus {
    outline: none;
    border-color: #000;
}

.ref-field small {
    display: block;
    margin-top: 5px;
    color: #888;
    font-size: 0.8rem;
}

.hidden { display: none; }
</style>

</head>

<body>

<div class="dashboard-container">

<h1>CHECKOUT</h1>

<table>

<tr>
<th>Product</th>
<th>Price</th>
<th>Quantity</th>
<th>Subtotal</th>
</tr>

<?php while($row = mysqli_fetch_assoc($query)){

    $subtotal = $row['price'] * $row['quantity'];
    $total += $subtotal;
?>

<tr>
<td><?php echo $row['product_name']; ?></td>
<td>₱<?php echo number_format($row['price'],2); ?></td>
<td><?php echo $row['quantity']; ?></td>
<td>₱<?php echo number_format($subtotal,2); ?></td>
</tr>

<?php } ?>

<tr>
<td colspan="3"><b>Total Amount</b></td>
<td><b>₱<?php echo number_format($total,2); ?></b></td>
</tr>

</table>

<br>

<form action="place_order.php" method="POST" class="checkout-form">

    <div class="field-row">
        <label for="payment_method">Payment Method</label>
        <select id="payment_method" name="payment_method" required>
            <option value="">Select Payment</option>
            <option value="Cash">Cash</option>
            <option value="GCash">GCash</option>
            <option value="Card">Card</option>
        </select>
    </div>


    <!-- GCASH DETAILS -->
    <div class="payment-details payment-gcash hidden">

        <div class="payment-info-box">
            <p><strong>GCash Instructions</strong></p>
            <p>Send payment to:</p>
            <p><strong>OpticalShop</strong></p>
            <p><strong>09123456789</strong></p>
        </div>

        <div class="ref-field">
            <label for="gcash_reference">GCash Reference Number <span style="color:red;">*</span></label>
            <input
                type="text"
                id="gcash_reference"
                name="gcash_reference"
                placeholder="e.g. 1234567890123"
                maxlength="20"
            >
            <small>Enter the 13-digit reference number from your GCash app after sending payment.</small>
        </div>

        <input type="hidden" name="gcash_shop" value="OpticalShop">
        <input type="hidden" name="gcash_number" value="09123456789">

    </div>


    <!-- CARD DETAILS -->
    <div class="payment-details payment-card hidden">

        <div class="payment-info-box">
            <p><strong>Card Payment</strong></p>
            <p>Please have your card ready. Fill in the details below after payment is processed.</p>
        </div>

        <div class="field-row">
            <label for="card_name">Card Type</label>
            <input type="text" id="card_name" name="card_name" placeholder="Visa / Mastercard" autocomplete="cc-brand">
        </div>

        <div class="field-row">
            <label for="card_owner">Card Owner Name</label>
            <input type="text" id="card_owner" name="card_owner" placeholder="Name on card" autocomplete="cc-name">
        </div>

        <div class="ref-field">
            <label for="card_last4">Last 4 Digits of Card <span style="color:red;">*</span></label>
            <input
                type="text"
                id="card_last4"
                name="card_last4"
                placeholder="e.g. 1234"
                maxlength="4"
                pattern="\d{4}"
            >
            <small>Enter only the last 4 digits of your card number.</small>
        </div>

        <div class="ref-field">
            <label for="card_approval">Approval / Authorization Code <span style="color:red;">*</span></label>
            <input
                type="text"
                id="card_approval"
                name="card_approval"
                placeholder="e.g. 123456"
                maxlength="20"
            >
            <small>Found on your card terminal receipt after the transaction.</small>
        </div>

    </div>


    <button type="submit">Place Order</button>

</form>

</div>

<script>
const paymentSelect   = document.getElementById('payment_method');
const gcashDetails    = document.querySelector('.payment-gcash');
const cardDetails     = document.querySelector('.payment-card');

const gcashReference  = document.getElementById('gcash_reference');
const cardLast4       = document.getElementById('card_last4');
const cardApproval    = document.getElementById('card_approval');
const cardName        = document.getElementById('card_name');
const cardOwner       = document.getElementById('card_owner');

function updatePaymentFields() {
    const value = paymentSelect.value;

    gcashDetails.classList.toggle('hidden', value !== 'GCash');
    cardDetails.classList.toggle('hidden', value !== 'Card');

    // Reset required on all
    gcashReference.required = false;
    cardLast4.required      = false;
    cardApproval.required   = false;
    cardName.required       = false;
    cardOwner.required      = false;

    if(value === 'GCash'){
        gcashReference.required = true;
    }

    if(value === 'Card'){
        cardLast4.required    = true;
        cardApproval.required = true;
        cardName.required     = true;
        cardOwner.required    = true;
    }
}

paymentSelect.addEventListener('change', updatePaymentFields);
updatePaymentFields();
</script>

</body>
</html>