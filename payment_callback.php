<?php
session_start();
require_once 'connection_cust.php'; // Include your database connection
require 'vendor/autoload.php'; // Ensure Guzzle is correctly loaded

$secretKey = 'sk_test_uZmyFAdFPDEqhTmswuwnzcmv'; // Replace with your PayMongo secret key

// Check if the payment intent ID is in the session
if (!isset($_SESSION['paymentIntentID'])) {
    echo 'No payment intent found.';
    exit();
}

$paymentIntentID = $_SESSION['paymentIntentID'];
$httpClient = new \GuzzleHttp\Client();

try {
    // Retrieve payment details from PayMongo
    $response = $httpClient->get("https://api.paymongo.com/v1/payment_intents/$paymentIntentID", [
        'headers' => [
            'Authorization' => 'Basic ' . base64_encode($secretKey . ':'),
        ]
    ]);

    $responseBody = json_decode($response->getBody(), true);
    
    // Check payment status
    $paymentStatus = $responseBody['data']['attributes']['status'];
    $reservationID = $_SESSION['reservationID'];
    $userID = $_SESSION['uUserID'];
    $totalCost = $_SESSION['totalCost'];
    $receiptPath = 'receipts'; // Modify this to the actual path or URL to the receipt

    // Insert payment details into the database if payment is successful
    if ($paymentStatus === 'completed') {
        $sql = "INSERT INTO payments (reservationID, userID, totalCost, paymentStatus, receiptPath) 
                VALUES (:reservationID, :userID, :totalCost, 'completed', :receiptPath)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':reservationID', $reservationID);
        $stmt->bindParam(':userID', $userID);
        $stmt->bindParam(':totalCost', $totalCost);
        $stmt->bindParam(':receiptPath', $receiptPath);
        $stmt->execute();
        
        // Clear session data
        unset($_SESSION['paymentIntentID']);
        unset($_SESSION['reservationID']);
        unset($_SESSION['totalCost']);
        
        // Redirect to a success page
        header('Location: customer_page.php'); // Change this to your desired success page
        exit();
    } else {
        // Handle payment failure
        echo 'Payment not completed. Status: ' . htmlspecialchars($paymentStatus);
    }
} catch (Exception $e) {
    echo 'Error retrieving payment intent: ' . $e->getMessage();
}
