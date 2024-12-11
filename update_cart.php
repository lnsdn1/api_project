<?php
session_start();

// Redirect to homepage if not logged in
if (!isset($_SESSION['uContact'])) {
    header("Location: index.php");
    exit();
}

// Connect to database
require_once 'conx.php';

// Handle cart update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'update') {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Validate quantity
    if ($quantity <= 0) {
        // Remove item from cart if quantity is zero or less
        unset($_SESSION['cart'][$product_id]);
    } else {
        // Update quantity in cart
        $_SESSION['cart'][$product_id] = $quantity;
    }
}

// Redirect back to homepage
header("Location: homepage.php");
exit();
?>
