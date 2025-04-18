<?php

function getAccessToken($consumer_key, $consumer_secret) {
    $credentials = base64_encode($consumer_key . ':' . $consumer_secret);
    $url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Basic ' . $credentials
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response);
    return $result->access_token;
}

function lipaNaMpesaOnline($token, $shortcode, $passkey, $callback_url, $phone_number, $amount, $account_reference, $transaction_desc) {
    $timestamp = date('YmdHis');
    $password = base64_encode($shortcode . $passkey . $timestamp);

    $url = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
    $data = [
        "BusinessShortCode" => "174379", // Your organization's shortcode
        "Password" => "MTc0Mzc5YmZiMjc5ZjlhYTliZGJjZjE1OGU5N2RkNzFhNDY3Y2QyZTBjODkzMDU5YjEwZjc4ZTZiNzJhZGExZWQyYzkxOTIwMjQwODA0MTcwMzQ1", // Generated password (base64 encoded)
        "Timestamp" => $timestamp, // Current timestamp
        "TransactionType" => "CustomerPayBillOnline",
        "Amount" => "1500", // Amount to be paid
        "PartyA" => $phone_number, // The phone number to receive the STK push
        "PartyB" => "174379", // Your organization's shortcode
        "PhoneNumber" => $phone_number, // The phone number to receive the STK push
        "CallBackURL" => "https://mydomain.com/path", // Your callback URL
        "AccountReference" => "Medicare", // Appointment number or any relevant reference
        "TransactionDesc" => "appointment fee" // Description of the transaction
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $token,
        'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true); // Return decoded response for better handling
}

function sendMpesaSTKPush($phone_number, $amount, $account_reference) {
    $consumer_key = 'wuBTFwNMLkjY0gbzOTZ3X4YKe68RihgyfQdA9YoETCHj7oBp';
    $consumer_secret = 'YsluAIbON4SDeChr5TIOXRybfVHm7MU0ovOOPfpouLyTeH6Gw6GiznxGlWCpVyPX';
    $shortcode = '174379';
    $passkey = 'your_passkey'; // Update with your actual passkey
    $callback_url = 'https://mydomain.com/path'; // Update with your actual callback URL

    $token = getAccessToken($consumer_key, $consumer_secret);

    return lipaNaMpesaOnline($token, $shortcode, $passkey, $callback_url, $phone_number, $amount, $account_reference, "Appointment booking");
}
?>

