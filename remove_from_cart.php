<?php
session_start();

// Redirect to homepage if not logged in
if (!isset($_SESSION['uContact'])) {
    header("Location: index.php");
    exit();
}

// Connect to database
require_once 'conx.php';

// Handle cart remove
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'remove') {
    $product_id = $_POST['product_id'];

    // Remove item from cart
    unset($_SESSION['cart'][$product_id]);
}

// Redirect back to homepage
header("Location: homepage.php");
exit();
?>
