<?php
session_start();
require 'vendor/autoload.php'; // Ensure Guzzle is correctly loaded

$secretKey = 'sk_test_uZmyFAdFPDEqhTmswuwnzcmv'; // Replace with your PayMongo secret key

// Check if reservationID and paymentIntentID are set in the session
if (!isset($_SESSION['reservationID']) || !isset($_SESSION['paymentIntentID'])) {
    echo '<script>alert("No payment found. Please try again."); window.location.href = "page_reservation.php";</script>';
    exit();
}

$reservationID = $_SESSION['reservationID'];
$paymentIntentID = $_SESSION['paymentIntentID']; // Payment intent ID from PayMongo

$httpClient = new \GuzzleHttp\Client();

try {
    // Fetch the payment intent details from PayMongo
    $response = $httpClient->get('https://api.paymongo.com/v1/payment_intents/' . $paymentIntentID, [
        'headers' => [
            'Authorization' => 'Basic ' . base64_encode($secretKey . ':'),
            'Content-Type'  => 'application/json',
        ]
    ]);

    // Decode the response body
    $responseBody = json_decode($response->getBody(), true);

    // Debug: Output the full response to check the payment status
    echo '<pre>';
    print_r($responseBody);  // Print the entire response for debugging purposes
    echo '</pre>';

    // Check if the payment was successful
    if ($responseBody['data']['attributes']['status'] == 'succeeded') {
        // Update the reservation status to 'completed'
        require_once 'connection_cust.php'; // Your database connection

        $sqlUpdatePayment = "UPDATE reservations SET paymentStatus = 'completed' WHERE reservationID = :reservationID";
        $stmtUpdate = $pdo->prepare($sqlUpdatePayment);
        $stmtUpdate->bindParam(':reservationID', $reservationID);

        // Check if the query executes successfully
        if ($stmtUpdate->execute()) {
            echo '<script>alert("Payment successful! Your reservation is confirmed."); window.location.href = "page_success.php";</script>';
        } else {
            echo '<script>alert("Database update failed.");</script>';
        }
    } else {
        echo '<script>alert("Payment not yet completed or still pending. Please try again."); window.location.href = "page_reservation.php";</script>';
    }
} catch (Exception $e) {
    echo 'Error checking payment status: ' . $e->getMessage();
}
?>
