<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../mpesa.php';

// Capture form data and sanitize
$amount = $_POST['amount'];
$account_reference = $_POST['account_reference'];
$phone_number = $_POST['phone_number'];

// Call the function to send M-Pesa STK push
$mpesa_response = sendMpesaSTKPush($phone_number, $amount, $account_reference);

// Log M-Pesa response for debugging
file_put_contents('mpesa_response.log', print_r($mpesa_response, true));

// Redirect to patient's dashboard
header("Location: index.php");
exit;
?>
