<?php
require 'vendor/autoload.php'; // Ensure Guzzle is correctly loaded

$secretKey = 'sk_test_uZmyFAdFPDEqhTmswuwnzcmv'; // Replace with your PayMongo secret key

$httpClient = new \GuzzleHttp\Client();

try {
    $response = $httpClient->post('https://api.paymongo.com/v1/links', [
        'headers' => [
            'Authorization' => 'Basic ' . base64_encode($secretKey . ':'),
            'Content-Type'  => 'application/json',
        ],
        'json' => [
            'data' => [
                'attributes' => [
                    'amount'      => 150000,  // Amount in centavos (e.g., 150000 = ₱1500.00)
                    'description' => 'Payment for Garage Music Studio booking',
                    'remarks'     => 'Reservation #1234 for recording session'
                ]
            ]
        ]
    ]);

    $responseBody = json_decode($response->getBody(), true);
    
    // Redirect the user to the payment link
    header('Location: ' . $responseBody['data']['attributes']['checkout_url']);
    exit();
} catch (Exception $e) {
    echo 'Error creating payment link: ' . $e->getMessage();
}
?>
