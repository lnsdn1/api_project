<?php
session_start();
require 'vendor/autoload.php'; // Ensure Guzzle is correctly loaded

// Redirect to login if not logged in
if (!isset($_SESSION['uContact'])) {
    header("Location: index.php");
    exit();
}

// Check if the cart is empty
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit();
}

// Connect to database
require 'conx.php';

// Fetch products in the cart
$cart_items = $_SESSION['cart'];
$product_ids = array_keys($cart_items);
$products = array();

if (!empty($product_ids)) {
    $ids = implode(',', array_fill(0, count($product_ids), '?'));
    $sql = "SELECT * FROM products WHERE id IN ($ids)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(str_repeat('i', count($product_ids)), ...$product_ids);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $products[$row['id']] = $row;
    }
}

// Get the total price of the order
$total_price = 0;
foreach ($cart_items as $id => $quantity) {
    $total_price += $products[$id]['product_price'] * $quantity;
}

// Save necessary data into session for PayMongo
$_SESSION['total_price'] = $total_price;
$_SESSION['id'] = uniqid('order_');  // Creating a unique reservation ID (you could use auto-increment or a real order ID from DB)

// PayMongo API integration
$secretKey = 'sk_test_uZmyFAdFPDEqhTmswuwnzcmv'; // Replace with your PayMongo secret key
$userName = $_SESSION['uFname'];
$userEmail = $_SESSION['uContact'];

// Convert total cost to centavos (PayMongo uses centavos)
$amountInCentavos = $total_price * 100;

$httpClient = new \GuzzleHttp\Client();

try {
    // Create the payment link in PayMongo
    $response = $httpClient->post('https://api.paymongo.com/v1/links', [
        'headers' => [
            'Authorization' => 'Basic ' . base64_encode($secretKey . ':'),  // Base64 encode secret key
            'Content-Type'  => 'application/json',
        ],
        'json' => [
            'data' => [
                'attributes' => [
                    'amount'      => $amountInCentavos,  // Amount in centavos
                    'description' => 'Payment for Beyond The Crust Ordering',
                    'remarks'     => 'Order #' . $_SESSION['id'] . ' for Ordering',
                    'customer'    => [
                        'name'  => $userName,
                        'email' => $userEmail
                    ],
                    'redirect'    => [
                        'success' => 'http://localhost/carmona/order_success.php',  // Redirect to success page after payment success
                        'failed'  => 'http://localhost/carmona/homepage.php'  // Redirect to homepage if payment fails
                    ]
                ]
            ]
        ]
    ]);

    $responseBody = json_decode($response->getBody(), true);

    // Retrieve the PayMongo payment intent ID from the response
    $paymentIntentID = $responseBody['data']['id']; // PayMongo's payment intent ID

    // Store the paymentIntentID in the session to check later
    $_SESSION['paymentIntentID'] = $paymentIntentID;

    // Display a button for canceling the payment
    echo '<a href="cart.php" class="btn btn-danger">Cancel Payment</a>';

    // Redirect the user to the payment link
    header('Location: ' . $responseBody['data']['attributes']['checkout_url']);
    exit();
} catch (Exception $e) {
    echo 'Error creating payment link: ' . $e->getMessage();
}
?>

